<?php

namespace App\Http\Controllers;


use App\Models\UserCourse;
use App\Models\UserProduct; // user_productsテーブルのモデルを仮定
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserCourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * 予約コースの登録・編集画面を表示します。
     *
     * @param int $productID 編集対象の商品名ID
     * @return \Illuminate\View\View
     */
    public function create(int $productID)
    {
        // 認証ユーザーの取得
        $user = Auth::user();

        // 指定された productID に紐づく既存のコースを取得
        // baseCode（店舗ID）も合わせてフィルタリングすることで、他の店舗のデータにアクセスするのを防ぎます。
        $courses = UserCourse::where('baseCode', $user->id)
            ->where('productID', $productID)
            ->orderBy('courseCode') // 表示順（courseCode）でソート
            ->get();
        
        // 予約コースが紐づく商品情報を取得（Viewで商品名を表示するためなど）
        $product = UserProduct::find($productID);

        // Viewに既存データを渡す
        return view('user_course.CsCreate', compact('user', 'productID', 'product', 'courses'));
    }

    /**
     * 予約コースデータを保存（新規登録/更新）します。
     * * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // フォームから送信されたコースデータ（配列の配列）
        $coursesData = $request->input('courses', []);
        $productID = $request->input('productID'); // フォームの隠しフィールドから productID を取得
        $coursesToSave = [];

        // productIDが必須
        if (!$productID) {
             return redirect()->back()->withErrors(['msg' => '商品の特定に必要なIDが不足しています。'])
                                     ->withInput();
        }

        // フォームから送信されたデータを処理し、保存対象のデータ構造に変換
        $courseCodeCounter = 1; // courseCode（表示順）を採番するためのカウンター
        foreach ($coursesData as $data) {
            // courseName が空でない行のみを処理対象とする
            if (!empty($data['courseName'])) {
                // weekdayPrice と weekendPrice が数値であることを確認し、整数にキャスト
                $weekdayPrice = is_numeric($data['weekdayPrice']) ? (int) $data['weekdayPrice'] : 0;
                $weekendPrice = is_numeric($data['weekendPrice']) ? (int) $data['weekendPrice'] : 0;

                $coursesToSave[] = [
                    'id' => $data['id'] ?? null,              // 既存レコードのID
                    'baseCode' => $user->id,
                    'productID' => (int) $productID,          // 紐づける商品ID
                    'courseCode' => $courseCodeCounter++,     // フォームの表示順に基づく連番
                    'courseName' => $data['courseName'],
                    'IsEnabled' => isset($data['IsEnabled']) ? 1 : 0, // チェックボックスの有無
                    'weekdayPrice' => $weekdayPrice,
                    'weekendPrice' => $weekendPrice,
                    'memo' => $data['memo'] ?? '',
                ];
            }
        }

        if (empty($coursesToSave)) {
            return redirect()->back()->withErrors(['msg' => '登録または更新する予約コースのデータがありません。'])
                                     ->withInput();
        }

        DB::beginTransaction();
        try {
            // 1. 既存コースのIDリストを取得 (この処理で更新対象の productID/baseCode に紐づくIDのみを取得)
            $existingCourseIds = UserCourse::where('baseCode', $user->id)
                ->where('productID', $productID)
                ->pluck('id')
                ->toArray();

            $idsToKeep = [];
            $newlyCreatedCount = 0;
            $updatedCount = 0;

            foreach ($coursesToSave as $item) {
                // IDがあれば更新、なければ新規作成
                if (!empty($item['id'])) {
                    // IDを保持するリストに追加
                    $idsToKeep[] = $item['id'];
                    
                    $course = UserCourse::where('id', $item['id'])
                        ->where('baseCode', $user->id) // 自身の店舗のデータのみを扱う
                        ->where('productID', $productID) // 自身の商品のデータのみを扱う
                        ->first();
                        
                    if ($course) {
                        // 更新（baseCodeとproductIDは既に設定されているので除外）
                        $course->update(collect($item)->except(['id', 'baseCode', 'productID'])->toArray());
                        $updatedCount++;
                    } else {
                        // IDが指定されたが、レコードが見つからない場合はスキップ（セキュリティ的な理由）
                        // または、新規作成として扱うことも可能だが、ここでは単にスキップする
                        Log::warning('Course ID ' . $item['id'] . ' not found for update or does not belong to user.');
                    }
                } else {
                    // 新規作成
                    UserCourse::create($item);
                    $newlyCreatedCount++;
                }
            }

            // 2. フォームに存在しない既存レコードを削除
            // 既存のIDリストから、今回のフォームで「保持する」とされたIDを除外したものが削除対象
            $idsToDelete = array_diff($existingCourseIds, $idsToKeep);
            $deletedCount = 0;
            if (!empty($idsToDelete)) {
                $deletedCount = UserCourse::whereIn('id', $idsToDelete)
                    ->where('baseCode', $user->id) // 念のため削除対象のbaseCodeも確認
                    ->where('productID', $productID) // 念のため削除対象のproductIDも確認
                    ->delete();
            }

            DB::commit();

            $message = "予約コースが更新されました。新規登録: {$newlyCreatedCount}件, 更新: {$updatedCount}件, 削除: {$deletedCount}件。";
            
            // 成功後、商品一覧（または適切なページ）にリダイレクト
            return redirect()->route('dashboard')->with('status', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User Course save failed: ' . $e->getMessage());
            
            return redirect()->back()->withErrors(['msg' => '予約コースの保存中にエラーが発生しました。お手数ですが、システム管理者にお問い合わせください。'])
                                     ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UserCourse $userCourse)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserCourse $userCourse)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserCourse $userCourse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserCourse $userCourse)
    {
        //
    }
}
