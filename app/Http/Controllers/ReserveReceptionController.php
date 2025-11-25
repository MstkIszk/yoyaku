<?php

namespace App\Http\Controllers;

use App\Models\ReserveReception;
use App\Http\Controllers\ReserveStatus;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserProduct;
use App\Models\Reserve;
use App\Models\UserCalender;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class ReserveReceptionController extends Controller
{
    /**
     * 予約一覧画面の表示
     * URLパラメータから日付を受け取る
     *
     * @param string|null $reqdate URLパラメータの日付 (YYYY-MM-DD形式を想定)
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function index($reqdate = null)
    {
        $UserInf = Auth::user();

        // 指定日付が指定されていない場合は本日日付とする
        try {
            if ($reqdate) {
                // Carbonで日付オブジェクトを作成し、日付形式を正規化 (YYYY-MM-DD)
                $targetDate = Carbon::parse($reqdate)->toDateString();
            } else {
                $targetDate = Carbon::today()->toDateString();
            }
        } catch (\Exception $e) {
            // 無効な日付フォーマットが渡された場合
            $targetDate = Carbon::today()->toDateString();
        }

        // ログインユーザーのIDを取得（店舗IDとして使用）
        $shop_id = $UserInf->id;

        // id = user->id のデータ（一般ユーザー、つまり店舗）を抽出し、
        // 関連する商品データ (products) も同時に取得する
        $ShopInf = User::with(['products' => function ($query) use ($targetDate) {
            // 有効な商品で、かつ営業期間内のものに絞る
            $query->where('IsEnabled', 1)
                  ->where('DateStart', '<=', $targetDate)
                  ->where('DateEnd', '>=', $targetDate)
                  // 並び順を指定
                  ->orderBy('productID'); 
            // 読み込んだ商品 (products) に紐づくコース (courses) も同時に取得      
            //$query->with('courses');                  
            $query->with('productCourses');                  
        }])
        ->where('id', $shop_id)
        ->first();

        // データが見つからなかった場合の処理
        if (!$ShopInf) {
            // 404エラーを表示
            return abort(404, '店舗情報が見つかりません。');
        }

        // Viewへ渡すデータの compact
        return view("Reserve.ReceptionList", compact('targetDate', 'UserInf', 'ShopInf'));
    }


    /**
     * AJAXリクエストに応答し、指定された商品IDと日付の予約一覧をJSONで返す
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReservationsForProduct(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d',
            'baseCode' => 'required|integer',
            'productID' => 'required|integer',
        ]);

        $targetDate = $request->input('date');
        $baseCode = $request->input('baseCode');
        $productID = $request->input('productID');

        // Reserveテーブルから予約データを取得
        // コース名を取得するためにUserCourseモデルをリレーションで結合する
        $reservations = Reserve::where('Baseid', $baseCode)
            ->where('Productid', $productID)
            // ReserveDate が指定日付と一致するものを抽出
            ->whereDate('ReserveDate', $targetDate)
            // コース名を取得するためのリレーション
            ->with(['course' => function ($query) {
                $query->select('id', 'courseName'); // 必要なカラムのみ
            }])
            ->orderBy('ReqDate', 'asc') // 予約日時順にソート
            ->get([
                'id',
                'ClitNameKanji',
                'ClitNameKana',
                'CliAddrZip',
                'CliAddrPref',
                'CliAddrCity',
                'CliAddrOther',
                'CliTel1',
                'CliEMail',
                'CliResvType', // コースIDとして使用
                'CliResvCnt',
                'Courseid', // リレーションキーとして必要
                'Status'    //  予約状態

            ]);

        // 必要なデータ形式に整形
        $reservationData = $reservations->map(function ($reservation) {
            return [
                'OrderNo'       =>  $reservation->id,
                'ClitNameKanji' => $reservation->ClitNameKanji,
                'ClitNameKana' => $reservation->ClitNameKana,
                'CliAddrZip' => $reservation->CliAddrZip,
                'CliAddrPref' => $reservation->CliAddrPref,
                'CliAddrCity' => $reservation->CliAddrCity,
                'CliAddrOther' => $reservation->CliAddrOther,
                'CliTel1' => $reservation->CliTel1,
                'CliEMail' => $reservation->CliEMail,
                'CliResvCnt' => $reservation->CliResvCnt,
                // コースIDから取得したコース名を設定
                'courseName' => $reservation->course ? $reservation->course->courseName : '不明',
                'Status' => $reservation->Status
            ];
        });

        return response()->json([
            'reservations' => $reservationData,
        ]);
    }

    /**
     * 受付する予約の詳細画面を表示する
     *
     * @param int $reservID 予約ID
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create($reservID)
    {
        // Reserveテーブルから$reservIDに該当するデータを取得し、関連テーブルをEager Loading
        $reserve = Reserve::with([
            // 予約者が紐づく店舗情報 (User)
            'user' => function ($query) {
                $query->select('id', 'name'); // nameのみを取得する前提
            },
            // 予約された商品 (UserProduct)
            'product' => function ($query) {
                $query->select('productID', 'productName'); // productNameのみを取得する前提
            },
            // 予約されたコース (UserCourse) と、それに紐づく料金情報 (UserCoursePrice) も合わせて取得
            'course.userCoursePrices', 
            // オプション商品 (UserAccessory) - BaseidをbaseCodeとして使用
            'accessories' => function ($query) {
                $query->where('IsEnabled', '!=', 0)
                      ->orderBy('productID'); 
            }
        ])
        ->where('id', $reservID)
        ->first();

        // 予約が見つからない場合はエラー処理
        if (!$reserve) {
            Log::error("Reserve ID: {$reservID} not found.");
            return redirect()->route('ReserveReception.index')->with('error', '指定された予約が見つかりませんでした。');
        }

        // コース料金（当日の曜日によって weekdayPrice または weekendPrice を採用）
        // 実際には曜日判定ロジックが必要です。ここでは簡単のため weekdayPrice を採用
        $isWeekend = UserCalender::GetHoriday($reserve->ReserveDate); // データベース検索の結果に応じて true/false
        $coursePrice = $isWeekend ? $reserve->course->weekendPrice : $reserve->course->weekdayPrice;
        
        // 予約情報をビューに渡す
        return view('Reserve.ReceptionAccept', [
            'reserve' => $reserve,
            'coursePrice' => $coursePrice,
            'isWeekend' => $isWeekend,
        ]);
    }

    /**
     * 受付情報（コース、オプションの単価と数量）をデータベースに格納する
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): Factory|RedirectResponse|View 
    {
        $validated = $request->validate([
            'ReserveID' => 'required|integer',
            'items' => 'required|array',
            'items.*.payType' => 'required|integer|in:1,2', // 1:コース, 2:オプション
            'items.*.index' => 'required|integer',         // 対象商品コード (courseID / accessoryID)
            'items.*.name' => 'nullable|string|max:255',   // 単価
            'items.*.price' => 'required|integer|min:0',   // 単価
            'items.*.count' => 'required|integer|min:0',   // 数量
            'items.*.memo' => 'nullable|string|max:255',   // メモ
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $reserveId = $validated['ReserveID'];
                
                // 既存の受付データを削除（冪等性確保のため。必要に応じて変更）
                ReserveReception::where('ReserveID', $reserveId)->delete();

                // 新しい受付データを保存
                foreach ($validated['items'] as $item) {
                    if ($item['count'] > 0) {
                        ReserveReception::create([
                            'ReserveID' => $reserveId,
                            'payType'   => $item['payType'],
                            'index'     => $item['index'],
                            'name'      => $item['name'],
                            'price'     => $item['price'],
                            'count'     => $item['count'],
                            'memo'      => $item['memo'] ?? '',
                        ]);
                    }
                }

                // 予約ステータスを受付完了などに更新するロジックもここに追加
                // 例: Reserve::where('id', $reserveId)->update(['Status' => 2]); 
            });

            //return redirect()->route('reserve.list')->with('success', '受付処理が完了しました。');
            //return redirect()->route('ReserveReception.show','reserveId')->with('success', '受付処理が完了しました。');
            $ReserveId = $request->input('ReserveID');;

            $reserve = Reserve::with(['user', 'product', 'course'])->find($ReserveId);
            if (!$reserve) {
                // 予約が見つからない場合はエラー処理
                return redirect()->route('ReserveReception.index')->with('error', '指定された予約情報が見つかりません。');
            }
            // 予約状態を完了（Accept）に更新
            $reserve->Status = 2;   //ReserveStatus::Accept;
            $reserve->save(); // データベースに保存            
    
            // 予約IDに紐づく受付情報を取得 (storeで保存したデータ)
            $receptions = ReserveReception::where('ReserveID', $ReserveId)->get();
    
            // 合計金額を計算
            $grandTotal = $receptions->sum(fn ($reception) => $reception->price * $reception->count);
    
            $product = UserProduct::find($reserve->Productid);
   
            //  受付完了画面を表示
            return view('Reserve.ReceptionShow', [
                'reserve' => $reserve,
                'product' => $product,
                'receptions' => $receptions,
                'grandTotal' => $grandTotal,
            ]);
    
            
       } catch (\Exception $e) {
            Log::error("受付処理エラー: " . $e->getMessage());
            return back()->withInput()->with('error', '受付処理中にエラーが発生しました。');
        }
    }


    /**
     * 受付完了画面を表示する
     *
     * @param int $reservid ReserveID
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function show($reservid)
    {
        $reserve = Reserve::with(['user', 'product', 'course'])->find($reservid);
        
        if (!$reserve) {
            // 予約が見つからない場合はエラー処理
            return redirect()->route('ReserveReception.index')->with('error', '指定された予約情報が見つかりません。');
        }

        // 予約IDに紐づく受付情報を取得 (storeで保存したデータ)
        $receptions = ReserveReception::where('ReserveID', $reservid)->get();

        $product = UserProduct::find($reserve->Productid);

        // 合計金額を計算
        $grandTotal = $receptions->sum(fn ($reception) => $reception->price * $reception->count);

        return view('Reserve.ReceptionShow', [
            'reserve' => $reserve,
            'product' => $product,
            'receptions' => $receptions,
            'grandTotal' => $grandTotal,
        ]);
    }

    /**
     * 予約データからPDF領収書を生成し、ダウンロードします。
     *
     * @param int $reserveId
     * @return \Illuminate\Http\Response
     */
    public function topdf($reserveId): RedirectResponse|Response
    {
        $reserve = Reserve::with(['user', 'product', 'course','reception'])->find($reserveId);
        
        if (!$reserve) {
            // 予約が見つからない場合はエラー処理
            return redirect()->route('ReserveReception.index')->with('error', '指定された予約情報が見つかりません。');
        }

        // 合計金額の計算
        $total = 0;
        foreach ($reserve->accessories as $item) {
            $item->subtotal = $item->price * $item->count;
            $total += $item->subtotal;
        }

        // 2. PDF生成処理の開始
        $baseId = $reserve->Baseid;
        $templatePath = resource_path("pdf/shop_{$baseId}_ryosyu_base.pdf");

        // Fpdi（既存PDFの読み込み機能を持つ拡張TCPDF）のインスタンスを作成
        $pdf = new Fpdi();
        
        // フォント設定（日本語フォント: kozgoprolight を使用）
        // ※ kochi-gothic/kochi-mincho などの代替フォントを設定する場合は適宜変更してください
        //$font = 'kozgoprobold'; 
        
        // 太字に近いゴシック体を使いたい場合    'kozanmproregular'
        // 明朝体を使いたい場合     'kozminproregular'
        // 汎用的なゴシック体を使いたい場合        'cid0jp'

        // ページ設定
        $pdf->SetMargins(0, 0, 0); // マージンをゼロに設定
        $pdf->SetAutoPageBreak(false, 0); // 自動改ページ無効
        
        // テンプレートの読み込み
        try {
            if (!file_exists($templatePath)) {
                return response("PDFテンプレートファイルが見つかりません: {$templatePath}", 500);
            }
            $pdf->setSourceFile($templatePath);
            $tplIdx = $pdf->importPage(1);
        } catch (\Exception $e) {
            return response("PDFテンプレートの読み込みエラー: " . $e->getMessage(), 500);
        }

        // ページを追加し、テンプレートを配置
        $pdf->AddPage();
        $pdf->useTemplate($tplIdx);

        // 3. PDF内のフィールドにデータ埋め込み
        
        // 3-1. 予約IDと日付の埋め込み (座標はshop_5_ryosyu_base.pdfを元に仮定)
        $pdf->SetFont('cid0jp', '', 12);
        $pdf->SetTextColor(0, 0, 0); // 黒色
        
        // No.の横
        $pdf->Text(160, 38, $reserve->id);
        
        // 発行日の横
        $pdf->Text(160, 44, Carbon::now()->format('Y/m/d')); // 発行日を現在の日付に設定

        // 顧客情報の仮埋め込み（PDFに「ご注文日」「納入日」「入金日」のフィールドが存在することを想定）
        $pdf->SetFont('cid0jp', '', 12);
        $pdf->Text(44, 65.5,  $reserve->ReqDate->format('Y年m月d日'));   //納入日: ' .
        $pdf->Text(44, 72.5,  $reserve->ReserveDate->format('Y年m月d日'));    //'ご注文日: ' .
        //$pdf->Text(100, 56, '入金日: ' . $reserve->paymentDate); 


        // 3-2. 明細行の埋め込み
        $startY = 124; // 明細の開始Y座標を仮定
        $lineHeight = 7; // 1行の高さ
        $pdf->SetFont('cid0jp', '', 12);
        $total = 0; 
        foreach ($reserve->reception as $index => $reception) {
            $y = $startY + ($index * $lineHeight);
            
            // 摘要 (商品名 + メモ)
            $description = $reception->name;
            $pdf->Text(15, $y, $description);

            // 数量
            $pdf->SetXY(92, $y); 
            $pdf->Cell(15, $lineHeight, number_format($reception->count), 0, 0, 'R'); 

            // 単価
            $pdf->SetXY(125, $y); 
            $pdf->Cell(25, $lineHeight, number_format($reception->price), 0, 0, 'R');

            $total += $reception->price * $reception->count;
            // 単位 (ここでは「式」を仮定)
            //$pdf->Text(100, $y, '式');

            // 金額 (小計)
            $pdf->SetXY(170, $y); 
            $pdf->Cell(25, $lineHeight, number_format($reception->price * $reception->count), 0, 0, 'R');
        }

        $totalFormatted = number_format($total);

        // 3-3. 合計金額の埋め込み
        $pdf->SetFont('cid0jp', 'B', 12);
        
        // 下部の「合計」欄 (明細表の最後の行、座標はPDFを元に仮定)
        // PDFの明細表の合計金額表示セルを想定
        $pdf->SetFont('cid0jp', 'B', 12);
        $pdf->SetXY(168, 236.5); // 仮のY座標
        $pdf->Cell(25, $lineHeight, '￥' . $totalFormatted, 0, 0, 'R');

        //  上部の合計金額
        $pdf->SetFont('cid0jp', '', 24);
        $pdf->SetXY(45, 97);
        $pdf->Cell(45, 10, '￥' . $totalFormatted, 0, 0, 'R'); 


        // 4. PDFの出力
        return response($pdf->Output('領収書_' . $reserve->id . '.pdf', 'I'), 200)
            ->header('Content-Type', 'application/pdf');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ReserveReception $reserveReception)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ReserveReception $reserveReception)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ReserveReception $reserveReception)
    {
        //
    }
}
