<?php
//  予約の受付用コントローラ

namespace App\Http\Controllers;

use Auth;
use App\Models\Reserve;
use App\Models\ReserveDate;
use Illuminate\Support\Facades\Session;
use App\Rules\CheckReservationCount;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User; // User モデルをインポート
use App\Models\UserProduct;
use App\Models\UserCourse;
use App\Models\UserCalender;
use Illuminate\Support\Facades\View;
use App\Models\ShopWaysPay;
use App\Mail\GenericNotificationMail; // Mailableをインポート
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

enum ReserveStatus : int
{
    const Entry = 0; //  受付後、未確認
    const Accept = 1; //  受付後、メール確認済
    const Cancel = 9; //  キャンセル済
}

// カスタムバリデーションロジック
Validator::extend('phone_number', function ($attribute, $value, $parameters, $validator) {
    // 例: 日本の携帯電話番号のみ許可
    return preg_match('/^(0{1}\d{1,4}-{0,1}\d{1,4}-{0,1}\d{4})$/', $value);
    //return User::where('username', $value)->doesntExist();

});
Validator::extend('postal_code', function ($attribute, $value, $parameters) {
    // 郵便番号のデータベースと照合するなど、より高度なチェックを行う
    return preg_match('/^\d{3}-\d{4}|\d{7}$/', $value);
});
Validator::extend('yoyaku_date', function ($attribute, $value, $parameters) {
    // 郵便番号のデータベースと照合するなど、より高度なチェックを行う
    $TodayDate = date('Y-m-dT07:00');
    return ($value > $TodayDate);
});

class ReserveController extends Controller
{
    // routes\web.php から呼ばれる関数

    //  店舗の新規登録
    public function spcreate(Request $request) {
        $WaysPayList = ShopWaysPay::GetWaysPayList();
        return view("Reserve.SpCreate",compact('WaysPayList'));
    }
    //  店舗登録の確定    //  予約の編集
    public function spedit(Reserve $reserve) {
        //return view("Reserve.RUpdate",compact('reserve'));
        return view("Reserve.SpEdit",compact('reserve'));
    }
    public function spupdate(Request $request, Reserve $reserve) {
        //  入力の妥当性チェック
        $validated = $request->validate([
            //      required : 必須入力
            //      max:nn : 最大文字列
            //      'integer | between:0,150' : 数値 0～150 
            //      ['max:1', 'regex:/^[男|女]+$/u'],
            "SpNameKanji" =>  "required|max:20",
            "SptNameKana"  =>  "required|max:20",
            "SpAddrZip" =>  "",       //  郵便番号
            "SpAddrPref" =>  "",      //  県名
            "SpAddrCity" =>  "",      //  市町村名
            "SpTel1" =>  "required|phone_number",          //  電話番号
            "SpTel2" =>  "required|phone_number",          //  電話番号
            "SpEMail" =>  "required|email",         //  メールアドレス
            "SpResvType" =>  "",      //  予約タイプ	
            "SpWaysPay" =>  "",       //  支払い方法
            "MessageText" =>  ""      //  連絡
            ],
            [
                //  エラーメッセージ記述
                'SptNameKanji.max' => '漢字名は20文字以内。',
                'SptNameKana.required' => 'カナは必須項目です。'
            ]
           
        );
        if($reserve->update($validated) == false) {
            $request->session()->flush('message','更新に失敗しました');            
        }
        else {
            //  処理後に一覧表示ページに戻る
            return redirect()->route('reserve.index');
        }
    }
    
    //  予約登録画面の表示
    public function create(Request $request,$ShopID=0,$ProductID=0,$ReqDate="") {  //  新規投稿

        if(strlen($ReqDate) > 0) {
            $DestDate = $ReqDate . ' 07:00';
        }
        else {
            $DestDate = date('Y-m-d 07:00');
        }
        if($ShopID == 0) {
            $ShopID = $request->ShopID; // キーに対応する値を取得
        }
        if($ProductID == 0) {
            $ProductID = $request->ProductID; // キーに対応する値を取得
        }
        $user = User::find($ShopID);
        $Product = UserProduct::find($ProductID);
        $ReqType = 1;
        $WaysPayList = ShopWaysPay::GetWaysPay($Product->WaysPayBit);
        $YoyakuTypeList = UserCourse::GetYoyakuType($user->id, $Product->id);

        return view("Reserve.RCreate",compact('user','Product', 'DestDate','ReqType','WaysPayList','YoyakuTypeList'));
        //return view("Reserve.RCreate")->with('DestDate', $DestDate)->with('ReqType', $ReqType);

        // makeメソッドでインスタンスを作成（データベースには保存しない）
        //$reserve = Reserve::make([
        //    'ClitNo'        =>  1, 
        //    'ReqDate'       =>  "2024-09-15 07:00:00",
        //]);
        //return view("Reserve.REdit",compact('reserve'));
    }
    
    //  予約の確認と確認コード入力画面
    public function confirm(Request $request,$ShopID=0,$ProductID=0,$ReqDate="") {  //  新規投稿
        // -----------------------------------------------------
        // 1. バリデーションルールの定義
        // -----------------------------------------------------
        $rules = [
            'ReserveDate'   => 'required|yoyaku_date', // 予約日
            'ClitNameKanji' => 'required|string|max:100', // 氏名漢字
            'ClitNameKana'  => 'required|string|max:100', // 氏名カナ
            'CliAddrZip'    => 'required|postal_code', // 郵便番号
            'CliAddrPref'   => 'required|string|max:50', // 県名
            'CliAddrCity'   => 'required|string|max:50', // 市町村名
            'CliAddrOther'  => 'required|string|max:100', // 地域名
            'CliTel1'       => ['required', 'phone_number'], // 電話番号：配列形式に変更
            'CliEMail'      => 'required|email|max:255', // メールアドレス
            'CliResvType'   => 'required|integer', // 予約タイプ
            'CliResvCnt'    => [
                'required', 
                'numeric', 
                // 予約可能人数チェックのカスタムルールを適用
                new CheckReservationCount(
                    $request->ReserveDate, 
                    $request->Baseid, 
                    $request->Productid
                )
            ],
            'CliWaysPay'    => 'required|integer', // 支払い方法
            'MessageText'   => 'nullable|string|max:500', // 連絡事項 (nullableに変更)
            'Baseid'        => 'required|integer',
            'Productid'     => 'required|integer',
        ];

        $messages = [
            'ReserveDate.required'      => '予約日を指定してください',
            'ReserveDate.yoyaku_date'   => '予約日は明日以降としてください',
            'ClitNameKanji.required'    => '氏名を入力してください',
            'ClitNameKana.required'     => 'ヨミガナを入力してください',
            'CliAddrZip.required'       => '郵便番号を入力してください',
            'CliAddrZip.postal_code'    => '郵便番号の形式が不正です',
            'CliAddrPref.required'      => '県名を入力してください',
            'CliAddrCity.required'      => '市町村名を入力してください',
            'CliAddrOther.required'     => '地域名を入力してください',
            'CliTel1.required'          => '電話番号を指定してください',
            'CliTel1.phone_number'      => '電話番号の形式が不正です',
            'CliEMail.required'         => 'メールアドレスを指定してください',
            'CliEMail.email'            => 'メールアドレスの形式が不正です',
            'CliResvType.required'      => '予約タイプを指定してください',
            'CliResvCnt.required'       => '予約人数を指定してください',
            'CliResvCnt.numeric'        => '予約人数は数値で指定してください',
            'CliWaysPay.required'       => 'お支払い方法を指定してください'
        ];

        // -----------------------------------------------------
        // 2. 電話番号と日付の重複チェック（Reserveテーブル）
        // -----------------------------------------------------
        // 過去の同じ電話番号で、同じ予約日時に有効な予約がないか確認
        $rules['CliTel1'][] = Rule::unique('reserv', 'CliTel1')->where(function ($query) use ($request) {
            return $query->where('ReserveDate', $request->ReserveDate)
                         ->where('Baseid', $request->Baseid)
                         ->where('Productid', $request->Productid)
                         ->where('Status', '!=', 9); // キャンセル/無効な予約を除外 (Status=9を仮定)
        });
        $messages['CliTel1.unique'] = 'この電話番号と予約日時の組み合わせは既に予約済みです。';


        // -----------------------------------------------------
        // 3. バリデーション実行
        // -----------------------------------------------------
        $validated = $request->validate($rules, $messages);
        
        // -----------------------------------------------------
        // 4. バリデーションOKの場合: データをセッションに保存して確認画面へ
        // -----------------------------------------------------
        Session::flash('reservation_data', $validated);
        
        // 予約タイプ、支払方法の表示用テキストを取得し、セッションに追加
        $reservationType = UserCourse::find($validated['CliResvType']);
        $paymentWay =  ShopWaysPay::find($validated['CliWaysPay']);

        //$validated['CliResvType_text'] = collect($reservationTypeArray)->firstWhere(0, $request->CliResvType)[1] ?? '不明';
        $validated['CliResvType_text'] = $reservationType['courseName'];
        //$validated['CliWaysPay_text'] = collect($paymentWayArray)->firstWhere(0, $request->CliWaysPay)[1] ?? '不明';
        $validated['CliWaysPay_text'] = $paymentWay['PrName'];

        Session::flash('reservation_display_data', $validated);

        return view('Reserve.RConfirm', compact('validated'));
    }
    
    //  予約の格納
    public function store(Request $request) {

        // セッションから確認画面でPOSTされたデータを取得
        $data = $request->session()->pull('reservation_data');

        // セッションデータがない場合は、不正なアクセスまたはセッション切れとしてリダイレクト
        if (empty($data)) {
            return redirect()->route('reserve.index')->with('error', '予約データが取得できませんでした。最初からやり直してください。');
        }

        //  ガード文字列
        //          0         1         2         3         4         5         6         
        $MasterKeyStr = 'AerD#$HjaiouAp@o|&YU%#jkTFG&Hiuwe)(aBng>OP_K&kljkA?OUf;=SgF:E%FdYBuY#Etx';
        $KeyStr = substr($MasterKeyStr,date("s"),10);
        //  登録日時
        $ReqDate = date("Y-m-d H:i:s");

        $newOrderNo = Reserve::max('OrderNo') + 1;
        $today = date('Y-m-d');

        $randomNumber = mt_rand(1000, 9999);

        // -----------------------------------------------------
        // データベースへの保存
        // -----------------------------------------------------
        try {
            $reserve = Reserve::create([
                'OrderNo'       => $newOrderNo, // 予約番号
                'Baseid'        => $data['Baseid'], // 対象店舗
                'Productid'     => $data['Productid'], // 対象商品
                'Courseid'      => $data['CliResvType'],    //  user_courseのID
                'KeyStr'        => $KeyStr,                 // 照会時に比較する 
                'ReqDate'       => $ReqDate,                // 予約受付日時 
                'ReserveDate'   => $data['ReserveDate'],    // 予約希望日時
                'ClitNo'        => 1, // 変更数（初期は1）
                'ClitNameKanji' => $data['ClitNameKanji'],  // 氏名漢字
                'ClitNameKana'  => $data['ClitNameKana'],   // 氏名カナ
                'CliAddrZip'    => $data['CliAddrZip'],     // 郵便番号
                'CliAddrPref'   => $data['CliAddrPref'],    // 県名
                'CliAddrCity'   => $data['CliAddrCity'],    // 市町村名
                'CliAddrOther'  => $data['CliAddrOther'],   // 地域名
                'CliTel1'       => $data['CliTel1'],        // 電話番号
                'CliEMail'      => $data['CliEMail'],       // メールアドレス
                'CliResvType'   => $data['CliResvType'],    // 予約タイプ
                'CliResvCnt'    => $data['CliResvCnt'],     // 予約人数
                'CliWaysPay'    => $data['CliWaysPay'],     // 支払い方法
                'MessageText'   => $data['MessageText'],    // 連絡
                'UpdateDate'    => $today, // 更新日
                'RandomNumber'  => $randomNumber, // 確認用の乱数
                'Status'        => ReserveStatus::Entry // 予約状態 (Enumを仮定)
            ]);
        } catch (\Exception $e) {
            Log::error('予約登録エラー: ' . $e->getMessage());
            return redirect()->route('reserve.index')->with('error', '予約処理中にエラーが発生しました。再度お試しください。');
        }

        $InquiryMailAddr	= "yukimi@kyum.chu.jp";
        $AdditMailFile		= public_path() . "/TextParam/managerList.txt";
        $AdditMailAddr		= file_get_contents($AdditMailFile);

        mb_language("Japanese");
        mb_internal_encoding("UTF-8");

        $ShopInf = User::find($data['Baseid']);
        $ProductInf = UserProduct::find($data['Productid']);
        $CourseInf = UserCourse::find($data['CliResvType']);

        
        // メールの共通ヘッダー情報
        $fromAddress = $ShopInf['spEMail'];
        $fromName = $ShopInf['spName'];        

        // 宛先 (顧客 $data['CliEMail'] と 店舗 $ShopInf['spEMail'] の両方)
        // Mailable側でカンマ区切りを処理するため、文字列のまま渡す
        $to = "";
        if($data['CliEMail'] == "" ) {
            $to = $ShopInf['spEMail']; 
        }
        else {
            $to = $data['CliEMail'] . "," . $ShopInf['spEMail']; 
        }

        //$title = $_POST['title'];
        $title = "【" . $ShopInf['spName'] . "】 ご予約ありがとうございます。";


        $headers = "Content-Type: text/plain \r\n";
        $headers .= "Return-Path: $InquiryMailAddr \r\n";
        $headers .=	"From: " . mb_encode_mimeheader($ShopInf['spName'] ) . "<" . $ShopInf['spEMail'] . ">\r\n";
        $headers .= "Bcc:$AdditMailAddr \r\n";
        $param = "-f $InquiryMailAddr";	//	サーバーによってはReturn-Path:を勝手に書き換えてしまうものもあるので、そのときは追加オプションのほうで -f を指定してあげます。





        $messageContent = "受付日時:\r\n$ReqDate\r\n\r\n";
        $messageContent .= "お名前:\r\n" .  $request->ClitNameKanji . "(" . $request->ClitNameKana . ")様\r\n\r\n";
        $messageContent .= $ProductInf['productName'] . "　(" . $CourseInf['courseName'] . "名)\r\n\r\n";
        $messageContent .= "予約日:\r\n" .  $request->ReserveDate . "　(" . $request->CliResvCnt . "名)\r\n\r\n";
        $messageContent .= "予約確認番号:\r\n" .   $randomNumber . "\r\n\r\n";

        // NOTE: ルーティングURLは、Laravelのroute()ヘルパーを使用して取得する方が安全です。
        $confirmUrl = route('reserve.confirm', [
            'id' => $newOrderNo, 
            'KeyStr' => $KeyStr, 
            'randomNumber' => $randomNumber
        ]);
                
        $messageContent .= "入力画面を再表示する場合は、以下のURLを開いてください:\r\n";
        $messageContent .= "{$confirmUrl}\r\n\r\n"; // URLを文字列として埋め込み

        $messageContent .= "ご連絡先\r\n TEL:" . $request->CliTel1 . "\r\n";
        $messageContent .= "MAIL:" . $request->CliEMail . "\r\n\r\n";

        $messageContent .= "------------------------\r\n";
        $messageContent .= "連絡先: " . $ShopInf['spName'] . "\r\n";
        $messageContent .= "〒: " . $ShopInf['spAddrZip'] . "\r\n";
        $messageContent .= $ShopInf['spAddrPref'] . "　".$ShopInf['spAddrCity']. "　".$ShopInf['spAddrOther'] . "\r\n";
        $messageContent .= "TEL: " . $ShopInf['spTel1'] . "\r\n";
        $messageContent .= "E-Mail: " . $ShopInf['spEMail'] . "\r\n";
        $messageContent .= "----------------------------------------------\r\n";


        // Mailableインスタンスを作成し、必要な情報を渡す
        $reservationMail = new GenericNotificationMail(
            toAddress: $to,
            subjectText: $title,
            content: $messageContent,
            fromAddress: $fromAddress,
            fromName: $fromName,
            toName: $data['ClitNameKanji'] // 宛先名として顧客名をセット
        );

        // Bccアドレスを設定
        if (!empty($AdditMailAddr)) {
            // カンマ区切りで複数のBccアドレスがある場合も考慮し、配列に変換
            $bccEmails = collect(explode(',', $AdditMailAddr))
                ->map(fn($email) => trim($email))
                ->filter()
                ->all();
            
            // MailファサードのBccメソッドを使用
            $mailer = Mail::bcc($bccEmails);
        } else {
            $mailer = Mail::shouldReceive('send')->once()->andReturn(true); // $mailer を初期化
            $mailer = Mail::getFacadeRoot(); // 実際の Mailer インスタンスを取得
        }

        // 送信処理（try-catchでエラーを捕捉し、セッションにメッセージをセット）
        try {
            // Mail::bcc(...) が設定されている場合は $mailer を使用して送信
            // そうでない場合は Mail::send() を直接使用
            if (!empty($AdditMailAddr)) {
                $mailer->send($reservationMail);
            } else {
                Mail::send($reservationMail);
            }

            $request->session()->flash('message','確認メールを送信しました');
        } catch (\Exception $e) {
            Log::error('予約確認メール送信エラー: ' . $e->getMessage());
            $request->session()->flash('message','確認メールの送信に失敗しました');
        }
        //  一覧のページに戻る
        return redirect()->route('reserve.calender',
         [
            'user_id' => $data['Baseid'],        // パスセグメント1 (456)
            'product_id' => $data['Productid'],    // パスセグメント2 (789)
        ]);
    }
    //  予約の確定
    public function fixed(Request $request,$id=0,$KeyStr="") {
        $result = Reserve::where('id', $request->id)
                            ->where('KeyStr', $request->KeyStr)
                            ->first();
        if ($result) {
            $result->status = ReserveStatus::Accept;
            $result->save();
            $request->session()->flash('message','予約が確定しました');
            return view("Reserve.RShow", compact('result'));
        } 
        else {
            // レコードが見つからなかった場合の処理
            $request->session()->flash('message','確認が見つかりません');
        }
    }
    //  予約の照会
    public function show($id) {
        $reserve = Reserve::find($id);
        return view("Reserve.RShow", compact('reserve'));
    }
    //  予約の照会
    public function telnoinput(Request $request) {
        // 2. セッションからIDを取得
        $ShopID = $request->session()->get('ShopID',0);
        $ProductID = $request->session()->get('ProductID',0);

        return view("Reserve.RTelNoinput",compact('ShopID','ProductID'));
    }
    //  電話番号画面から呼ばれた場合はReserv->CliTel1,Baseid,$ShopID,Productidで抽出後のデータを渡す
    public function index(Request $request,$CliTel1,$ShopID=0,$ProductID=0) {

        // **クエリの構築**
        $query = Reserve::query();

        // 1. 電話番号 (CliTel1) で絞り込み
        if ($CliTel1) {
            $query->where('CliTel1', $CliTel1);
        }

        // 2. 店舗ID (Baseid) で絞り込み
        if ($ShopID) {
            $query->where('Baseid', $ShopID);
        }

        // 3. 商品ID (Productid) で絞り込み
        if ($ProductID > 0) {
            $query->where('Productid', $ProductID);
        }

        // **ソートとページネーション**
        $reserve = $query->orderBy('ReserveDate', 'desc') // ReserveDate 降順
                         ->paginate(50); // 50件/ページとする

        if ((!$reserve) || ($reserve->count() <= 0)) {
            // レコードが見つからなかった場合の処理
            $request->session()->flash('message','指定された電話番号での予約は見つかりません');
            return view("Reserve.RTelNoinput");
        }
        $products = "";
 
        return view("Reserve.RIndex",compact('reserve','products'));
    }

    /**
     * 予約一覧データをJSON形式で返す (Ajax用) バックエンド処理
     * @param Request $request GETリクエストからの絞り込みパラメータを含む
     * @return \Illuminate\Http\JsonResponse
     */
    public function reserveIndex(Request $request)
    {
        // 1. 検索条件の取得 (クエリパラメータから直接取得)
        $filterCliTel1 = $request->query('CliTel1');
        $BaseShopID = $request->query('baseID');
        $filterProductID = (int) $request->query('ProductID', 0);
        $filterDateStart = $request->query('DateStart');
        $filterDateEnd = $request->query('DateEnd');
        $page = (int) $request->query('page', 1); // ページ番号を取得

        // 2. クエリの構築と絞り込み
        $query = Reserve::query();
        $query->where('Baseid', $BaseShopID); // ログインユーザーの店舗データに限定

        // 電話番号での絞り込み
        if ($filterCliTel1) {
            $query->where('CliTel1', $filterCliTel1);
        }

        // 商品IDでの絞り込み
        if ($filterProductID > 0) {
            $query->where('Productid', $filterProductID);
        }

        // 予約日 (ReserveDate) の範囲で絞り込み
        if ($filterDateStart) {
            $query->where('ReserveDate', '>=', $filterDateStart . ' 00:00:00');
        }
        if ($filterDateEnd) {
            $query->where('ReserveDate', '<=', $filterDateEnd . ' 23:59:59');
        }

        // 4. ソートとページネーション
        $reserveData = $query->orderBy('ReserveDate', 'desc')
                            ->paginate(50, ['*'], 'page', $page);
                            
        // 5. 商品リストの取得 (ドロップダウン用 - 初回ページロード時にのみ使用)
        // 通常のAPIコールではこの情報は不要だが、今回は統合的な処理として含める。
        // ただし、この関数はAjaxでのデータ取得専用となるため、productsリストの取得は不要。
        // productsリストの取得は、Viewを表示する別の関数で行うか、今回の処理では省略する。

        // **電話番号検索後の処理 (RTelNoinputへリダイレクト) は、このJSONエンドポイントでは行わない。**

        // 6. JSON形式でデータを返す
        return response()->json($reserveData);
    }    

    //  予約の編集
    public function edit(Reserve $reserve) {
        //return view("Reserve.RUpdate",compact('reserve'));
        return view("Reserve.REdit",compact('reserve'));
    }
    public function update(Request $request, Reserve $reserve) {
        //  入力の妥当性チェック
        $validated = $request->validate([
            //      required : 必須入力
            //      max:nn : 最大文字列
            //      'integer | between:0,150' : 数値 0～150 
            //      ['max:1', 'regex:/^[男|女]+$/u'],
            "ReserveDate" =>  "",      //  予約日
            "ClitNameKanji" =>  "required|max:20",
            "ClitNameKana"  =>  "required|max:20",
            "ClitNo" =>  "",           //  予約番号
            "CliAddrZip" =>  "",       //  郵便番号
            "CliAddrPref" =>  "",      //  県名
            "CliAddrCity" =>  "",      //  市町村名
            "CliTel1" =>  "required|phone_number",          //  電話番号
            "CliEMail" =>  "required|email",         //  メールアドレス
            "CliResvType" =>  "",      //  予約タイプ	
            "CliResvCnt" =>   ['required', 'numeric', new CheckReservationCount($reserve->ReserveDate, $reserve->CliResvCnt)],       //  予約人数
            "CliWaysPay" =>  "",       //  支払い方法
            "MessageText" =>  ""      //  連絡
            ],
            [
                //  エラーメッセージ記述
                'ClitNameKanji.max' => '漢字名は20文字以内。',
                'ClitNameKana.required' => 'bodyは必須項目です。'
            ]
           
        );
        if($reserve->update($validated) == false) {
            $request->session()->flush('message','更新に失敗しました');            
        }
        else {
            //  処理後に一覧表示ページに戻る
            return redirect()->route('reserve.index');
        }
    }
    public function cancel() {
        return view("Reserve.RCancel");
    }
    public function destroy(Request $request, Reserve $reserve) {
        $reserve->delete();
        $request->session()->flush('message', '削除しました');
        return redirect()->route('reserve.index');
    }
    
    /**
     * 予約カレンダー表示
     */
    public function calender(Request $request, $user_id = null, $product_id = null)
    {
        // 1. パラメータをセッションに格納
        if ($user_id !== null) {
            $request->session()->put('ShopID', $user_id);
        }
        if ($product_id !== null) {
            $request->session()->put('ProductID', $product_id);
        }
        $month = date('Y-m-d');

        // 2. セッションからIDを取得
        $ShopID = $request->session()->get('ShopID');
        $ProductID = $request->session()->get('ProductID');


        // 3. 店舗情報と商品情報の取得
        // $ShopIDが設定されていない場合はリダイレクト
        if (!$ShopID) {
            return redirect()->route('profile.homelist');
        }
        
        $ShopInf = User::find($ShopID);
        $ProductInf = null;

        //if ($ProductID) {
        //     // 商品IDが存在する場合、商品情報を取得
        //     $ProductInf = UserProduct::find($ProductID);
        //     // 取得できなかった場合も考慮
        //     if (!$ProductInf) {
        //         // エラー処理またはリダイレクトを検討
        //     }
        //}

        // ----------------------------------------------------------------
        // 【修正点1】baseCode = user_id での商品リスト抽出
        // ----------------------------------------------------------------
        // baseCodeが$ShopID (user_id)に一致する全ての商品を取得し、$ProductListに格納
        $ProductList = UserProduct::where('baseCode', $ShopID)->get();
        // ----------------------------------------------------------------

        // 選択中のProductIDが存在しない、または無効な場合、リストの最初の要素を選択する
        if (!$ProductID || $ProductList->where('id', $ProductID)->isEmpty()) {
            if ($ProductList->isNotEmpty()) {
                // リストの最初の商品のIDをProductIDとする
                $ProductID = $ProductList->first()->id;
                $request->session()->put('ProductID', $ProductID);
            } else {
                // 商品リストが空の場合、ProductIDをクリアする
                $ProductID = null;
                $request->session()->forget('ProductID');
            }
        }
        

        // 4. 不足しているチェック
        if(!$ShopInf) {
            // $ShopIDがセッションにありながら無効な場合のリダイレクト
            return redirect()->route('profile.homelist');
        }

        // 今後、カレンダービューで $ShopInf と $ProductInf を利用できるように compact に追加
        //return view("Reserve.RCalender", compact('month', 'ShopInf', 'ProductInf'));
        return view("Reserve.RCalender", compact('month', 'ShopInf', 'ProductList', 'ProductID'));
    }

    //  指定月のカレンダー情報を読み込むバックエンド処理
    public function calenderGet(Request $request,$id=0,$month = "") {

        $month = $request->month;

        //  １日と最終日を取得
        $firstDay = $compDay = date('Y-m-01', strtotime($month));
        $lastDay = date('Y-m-t', strtotime($month));

        // 指定月の範囲内のデータを取得
        $reservations = Reserve::whereBetween('ReserveDate', [$firstDay, $lastDay])
            ->where('Baseid', $request->basecode)
            ->where('Productid', $request->ProductID)
            ->orderBy('ReserveDate')->get();
        $dcnt = count($reservations);
        $dix = 0;
        $resvDate = 99;
        if($dcnt > 0) {
            $resv = $reservations[$dix];    //  年月日の日の部分だけを切り出し
            $resvDate = substr($resv->ReserveDate,8,2);
        }

        // ----------------------------------------------------
        // UserCalenderから祭日データを読み込む
        // ----------------------------------------------------
        // UserCalenderから指定月の祭日(datetype = 3)を取得し、日付をキーとする連想配列に変換
        // destDateが'Y-m-d H:i:s'形式の場合も'Y-m-d'形式のキーでアクセスできるようにします。
        $holidays = UserCalender::whereBetween('destDate', [$firstDay, $lastDay])
            ->where('datetype', 3) // datetype: 3 (祭日)
            // baseCodeによる絞り込みが必要な場合は、以下の行をコメントアウト解除してください
            // ->where('baseCode', $request->basecode) 
            ->get()
            ->mapWithKeys(function ($item) {
                // キーを 'Y-m-d' 形式の文字列（例: '2025-11-07'）に統一してマッピングする
                return [date('Y-m-d', strtotime($item->destDate)) => $item->memo];
            });
        // ----------------------------------------------------

        // 日付情報を読み込み
        $reserveDates = ReserveDate::whereBetween('destDate', [$firstDay, $lastDay])
                ->where('baseCode', $request->basecode)
                ->where('productID', $request->ProductID)
                ->orderBy('destDate')->get();
        $infoIx = 0;
        $infoCnt = $reserveDates->count();

        //  日数を取得
        $dateCnt = date('t',strtotime($lastDay));

        //  初日の曜日コード取得
        $weekdayCode = $posX = date('w', strtotime($firstDay));

        $calender = [];
        //$week = ['','','','','','',''];
        $json_week = '[{"day":"0","type":"0","name":""},{"day":"0","type":"0","name":""},{"day":"0","type":"0","name":""},{"day":"0","type":"0","name":""},{"day":"0","type":"0","name":""},{"day":"0","type":"0","name":""},{"day":"0","type":"0","name":""}]';
        //$week = collect(json_decode($json_week, true));
        $week = json_decode($json_week, true);

        $json_string = '[{"name": "Alice", "age": 30}, {"name": "Bob", "age": 25}]';
        $data = json_decode($json_string, true);

        $currentMonth = date('Y-m', strtotime($firstDay)); // YYYY-MM形式

        $DateIx = 0;
        for ($day = 1; $day <= $dateCnt; $day ++) {
            // 現在の日付（'Y-m-d'形式）を作成
            $currentDate = sprintf('%s-%02d', $currentMonth, $day);            //  日を配列に埋め込み

            $week[$posX]['day'] = $day; 
            $totalCnt = 0;
            while($day == $resvDate) {  
                // 同じ日に予約の登録があったら、配列に新しい要素を追加
                $memName = $resv['ClitNameKanji'];
                $memCnt = $resv['CliResvCnt'];
                $week[$posX]['member'][] =  [ 'name' => $memName,'cnt' => $memCnt];
                $totalCnt += $memCnt;
                if($dcnt > ++$dix) {
                    $resv = $reservations[$dix];
                    $resvDate = substr($resv->ReserveDate,8,2);
                }
                else {
                    $resvDate = 99;
                }
                //$resvDate = $resv['ReserveDate']->date;
            }

           // ----------------------------------------------------
            // 【追加された処理】祭日情報の追加
            // ----------------------------------------------------
            if (isset($holidays[$currentDate])) {
                // 祭日情報を 'DayName' として追加
                $week[$posX]['DayName'] = $holidays[$currentDate];
            }
            // ----------------------------------------------------


            if($infoIx < $infoCnt) {
                $destDate = substr($reserveDates[$infoIx]->destDate, 8, 2);
                if($day == $destDate) {     //  同じ日付ならば 反映して次のデータへ
                    $week[$posX]['operating'] = $reserveDates[$infoIx]->operating;
                    $week[$posX]['stars'] = $reserveDates[$infoIx]->stars;
                    $week[$posX]['memo'] = $reserveDates[$infoIx]->memo;
                    $infoIx++;
                }
            } 
            $week[$posX]['totalCnt'] = $totalCnt;

            if(++$posX > 6) {   //  週が終わったら
                array_push($calender,$week);
                $posX = 0;
                $week = json_decode($json_week, true);
            } 
        }
        //  余りがあれば追加する
        if($posX > 0) {
            array_push($calender,$week);
        }

        return response()->json($calender);
    }
    public function GetCustmerData(Request $request,$tel = "") {

        $type = $request->type;
        $tel = $request->Tel;

        // 指定月の範囲内のデータを取得
        $reservations = Reserve::where('CliTel1', $tel)->orderBy('id', 'desc')->first();
        //$dcnt = count($reservations);
        return response()->json($reservations);
    }

}
