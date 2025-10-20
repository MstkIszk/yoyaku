<?php

namespace App\Http\Controllers;

use App\Models\ShopProduct;
use App\Models\ShopWaysPay;
use App\Models\UserProduct;
use Illuminate\Http\Request;

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
        $shopProducts = ShopProduct::all();
        $shopPaysWay = ShopWaysPay::all();
        return view('user_products.create', compact('shopProducts','shopPaysWay'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
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

        UserProduct::create([
            'baseCode' => $request->baseCode,
            'productID' => $request->productID,
            'productName' => $request->productName,
            'DateStart' => $request->DateStart,
            'DateEnd' => $request->DateEnd,
            'TimeStart' => $request->TimeEnd,
            'TimeEnd' => $request->TimeEnd,
            'capacity' => $request->capacity,
            'price' => $request->price,
            'WaysPay' => array_sum($request->WaysPay),
            'memo' => $request->memo,
        ]);

        return redirect()->route('user_products.create')->with('success', '商品が登録されました。');
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
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
