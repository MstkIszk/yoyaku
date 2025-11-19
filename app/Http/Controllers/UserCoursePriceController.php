<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\UserCoursePrice;
use App\Models\UserCourse; // 仮のモデル
use App\Models\UserProduct; // 仮のモデル

class UserCoursePriceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * コース料金の登録・変更画面を表示します。
     *
     * @param int $courseID UserCourseのID
     * @return \Illuminate\View\View
     */
    public function create(int $courseID)
    {
        // 認証ユーザーの取得
        $user = Auth::user();

        // courseIDで UserCourse とリレーションされている UserCoursePrice を取得
        // UserCourseのproductIDで UserProduct を取得

        // 1. UserCourseの取得
        $userCourse = UserCourse::where('baseCode', $user->id)
                                ->where('id', $courseID)
                                ->firstOrFail();

        // 2. 関連する UserCoursePrice の取得 (既存データ)
        // UserCourse の ID (courseID) が UserCoursePrice の productID または courseCode に関連付けられていると仮定
        // 今回のテーブル定義では UserCoursePrice の productID が UserCourse の ID に対応すると仮定します。
        $userCoursePrices = UserCoursePrice::where('baseCode', $user->id)
                                            ->where('productID', $userCourse->id)
                                            ->orderBy('courseCode')
                                            ->orderBy('priceCode')
                                            ->get();

        // 3. UserProductの取得 (UserCourseのproductIDから)
        // UserCourseモデルに productID カラムがあり、UserProductテーブルのIDを参照していると仮定
        $userProduct = UserProduct::find($userCourse->productID); 
        
        // 必要な変数が全て揃っているか確認
        if (!$userProduct) {
             abort(404, 'UserProduct not found for the course.');
        }

        // Viewに既存データを渡す
        return view('user_course.PriceCreate', compact('user', 'userProduct', 'userCourse', 'userCoursePrices'));
    }

    /**
     * コース料金のデータを登録・更新します。
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        $productID = $request->input('productID'); // UserCourse ID (productIDとして利用)
        $courseID = $request->input('courseID'); // UserCourse ID (productIDとして利用)
        $pricesData = $request->input('prices', []); // フォームから送信された料金データの配列
        $pricesToSave = [];
        $baseCode = $user->id;

        // フォームから送信されたデータを処理し、保存する配列を構築
        foreach ($pricesData as $data) {
            // courseName (料金名) が空でない行のみを処理対象とする
            if (!empty($data['priceName'])) {
                $pricesToSave[] = [
                    'id' => $data['id'] ?? null,              // 既存レコードID (更新時)
                    'productID' => $productID,                 // UserCourse ID を productIDとして使用
                    'courseCode' => $courseID,                 // UserCourse ID を productIDとして使用
                    'priceCode' => (int) ($data['priceCode'] ?? 0),   // 料金コード
                    'priceName' => $data['priceName'],
                    'IsEnabled' => isset($data['IsEnabled']) ? 1 : 0,
                    'weekdayPrice' => (int) ($data['weekdayPrice'] ?? 0),
                    'weekendPrice' => (int) ($data['weekendPrice'] ?? 0),
                    'memo' => $data['memo'] ?? '',
                ];
            }
        }

        if (empty($pricesToSave)) {
            return redirect()->back()->withErrors(['msg' => '登録または更新するコース料金のデータがありません。'])
                                     ->withInput();
        }

        DB::beginTransaction();
        try {
            $updatedCount = 0;
            $createdCount = 0;
            $currentProductIDs = []; // 処理されたレコードIDを追跡

            foreach ($pricesToSave as $item) {
                // IDがあれば更新、なければ新規作成
                if (!empty($item['id'])) {
                    // IDと baseCode、productID (courseID) でレコードを特定
                    $coursePrice = UserCoursePrice::where('id', $item['id'])
                                                  //->where('baseCode', $baseCode)
                                                  //->where('productID', $courseID)
                                                  ->first();
                    if ($coursePrice) {
                        $coursePrice->update($item);
                        $updatedCount++;
                        $currentProductIDs[] = $coursePrice->id;
                    }
                } else {
                    // 新規作成
                    $coursePrice = UserCoursePrice::create(array_merge($item, ['baseCode' => $baseCode]));
                    $createdCount++;
                    $currentProductIDs[] = $coursePrice->id;
                }
            }

            // 【発展的な処理：削除】
            // 送信されなかった既存のコース料金レコードを削除する（今回は要件に含まれていないが、一般的な処理）
            // UserCoursePrice::where('baseCode', $baseCode)
            //                  ->where('productID', $courseID)
            //                  ->whereNotIn('id', $currentProductIDs)
            //                  ->delete();

            DB::commit();

            // 保存成功後、ダッシュボードにリダイレクト
            $message = "コース料金が登録・更新されました (新規: {$createdCount}件, 更新: {$updatedCount}件)。";
            return redirect()->route('dashboard')->with('status', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Course price save failed: ' . $e->getMessage());
            
            return redirect()->back()->withErrors(['msg' => 'コース料金の保存中にエラーが発生しました。'])
                                     ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UserCoursePrice $userCoursePrice)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserCoursePrice $userCoursePrice)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, UserCoursePrice $userCoursePrice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserCoursePrice $userCoursePrice)
    {
        //
    }
}
