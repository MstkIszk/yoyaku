<?php

namespace App\Http\Controllers;

use App\Models\ShopProduct;
use App\Models\ShopWaysPay;
use App\Models\UserProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon; // 日付/時刻操作のためにインポート

class UserProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $shopPaysWay = ShopWaysPay::all();
        return view('user_products.create', compact('shopPaysWay'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'baseCode' => 'required',
            'productName' => 'required',
            'DateStart' => 'required|date|before:DateEnd',
            'DateEnd' => 'required|date',
            'TimeStart' => 'required|date_format:H:i|before:TimeEnd',
            'TimeEnd' => 'required|date_format:H:i',
            'capacity' => 'required|integer|min:0|max:99',
            'price' => 'required|integer',
            'WaysPay' => 'required|array',
            'memo' => 'nullable|string',
        ]);

        $currentBaseCode = $request->baseCode;
        $maxProductID = UserProduct::where('baseCode', $currentBaseCode)
                                ->max('productID');
        $newProductID = ($maxProductID ?? 0) + 1;

        UserProduct::create([
            'baseCode' => $request->baseCode,
            'productID' => $newProductID,
            'productName' => $request->productName,
            'IsEnabled' => 1,
            'DateStart' => $request->DateStart,
            'DateEnd' => $request->DateEnd,
            'TimeStart' => $request->TimeStart,
            'TimeEnd' => $request->TimeEnd,
            'capacity' => $request->capacity,
            'price' => $request->price,
            'WaysPay' => array_sum($request->WaysPay),
            'memo' => $request->memo,
        ]);

        return redirect()->route('dashboard')->with('success', '商品が登録されました。');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserProduct $product)
    {
        // 認証済みユーザー
        $user = Auth::user();

        $pid = $product->id;
        $baseCode = $product->baseCode;
        // 認証済みユーザーの商品かチェック
        // baseCode (店舗ID) が現在ログインしているユーザーのIDと一致するか確認
        if ($baseCode !== $user->id) {
            abort(403, $pid . 'Unauthorized action. This product does not belong to your shop.');
        }

        // 支払い方法のオプションを取得 (create関数と同じロジックを想定)
        // 支払い方法テーブルが不明なため、ShopPaysWayモデルを仮定
        $shopPaysWay = ShopWaysPay::all();

        return view('user_products.edit', [
            'user' => $user,
            'product' => $product,
            'shopPaysWay' => $shopPaysWay,
        ]);
    }

    /**
     * 指定された商品をデータベースで更新する
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\UserProduct  $userProduct
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, UserProduct $product)
    {
        // 認証済みユーザー
        $user = Auth::user();

        // 認証済みユーザーの商品かチェック
        if ($product->baseCode !== $user->id) {
            abort(403, 'Unauthorized action.');
        }

        // バリデーションルール
        $request->validate([
            'productName' => 'required|string|max:255',
            'DateStart' => 'required|date|before_or_equal:DateEnd',
            'DateEnd' => 'required|date|after_or_equal:DateStart',
            // TimeStartとTimeEndは 'H:i' フォーマットを想定
            'TimeStart' => 'required|date_format:H:i|before:TimeEnd',
            'TimeEnd' => 'required|date_format:H:i|after:TimeStart',
            'capacity' => 'required|integer|min:0|max:99',
            'price' => 'required|integer|min:0',
            'WaysPay' => 'required|array',
            'IsEnabled' => 'required|boolean',
            'memo' => 'nullable|string|max:1000',
        ]);

        // 支払い方法のビット和を計算
        $waysPaySum = array_sum($request->WaysPay);

        // 更新
        $product->update([
            'productName' => $request->productName,
            'DateStart' => $request->DateStart,
            'DateEnd' => $request->DateEnd,
            'TimeStart' => $request->TimeStart,
            'TimeEnd' => $request->TimeEnd,
            'capacity' => $request->capacity,
            'price' => $request->price,
            'WaysPay' => $waysPaySum,
            'IsEnabled' => $request->IsEnabled,
            'memo' => $request->memo,
            // baseCode と productID は変更しない
        ]);

        return redirect()->route('dashboard')->with('success', '商品情報が正常に更新されました。');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
