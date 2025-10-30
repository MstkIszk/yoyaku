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
        // baseCode は users.id を参照しています
        $products = $user->products()
                         ->orderBy('productID', 'asc') // productIDで並び替え
                         ->get();

        return view('dashboard', [
            'user' => $user,
            'products' => $products,
        ]);
    }
}
