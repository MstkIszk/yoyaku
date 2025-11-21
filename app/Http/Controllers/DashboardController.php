<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\UserProduct; // 使用するモデルをインポート

class DashboardController extends Controller
{
    /**
     * ダッシュボードページを表示し、必要なデータを渡す
     */
    public function index()
    {
        // 1. 認証済みユーザー情報を取得
        $user = Auth::user();

        // 2. 認証済みユーザーのID (baseCode) に紐づく商品リストを取得
        // UserProduct モデルに 'courses' リレーション（UserCourseへのリレーション）を追加している
//        $products = $user->products()
//                         ->with(['courses' => function ($query) {
//                             // courseCode（表示順）でソートして取得
//                             $query->orderBy('courseCode', 'asc');
//                         }])
//                         ->orderBy('productID', 'asc') // productIDで並び替え
//                         ->get();
////                         ->toSql();

        $products = $user->products()
                         ->with([
                            'productCourses' => function ($query) {
                                // allCoursesByProductId（Courseモデル）から userCoursePrices をロード
                                $query->with('userCoursePrices'); 
                            }
                        ]) // ★テスト用のリレーション名を使用
                         ->orderBy('productID', 'asc')
                         ->get();

        // 3. Eager Loadingされた後のデータ（ courses ）を確認
        if ($products->isNotEmpty()) {
            $firstProduct = $products->first();
            
            // courses がロードされているか確認
            \Log::info('Courses Count for first product: ' . $firstProduct->courses->count());

            // ロードされているにも関わらず count が 0 の場合、原因は「リレーションの定義」にある（下記原因2へ）
        }

        // 3. 認証済みユーザーのID (baseCode) に紐づくアクセサリーリストを取得
        $accessories = $user->accessories()
                            ->orderBy('productID', 'asc') // productIDで並び替え (UserAccessoryにもproductIDがあるため)
                            ->get();

        return view('dashboard', [
            'user' => $user,
            'products' => $products,
            'accessories' => $accessories, // accessories データをビューに渡す
        ]);
    }
}
