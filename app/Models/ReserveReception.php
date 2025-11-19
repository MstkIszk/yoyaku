<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReserveReception extends Model
{
    use HasFactory;

    protected $table = 'reserv_reception';

    //  保存・更新したいカラムを設定
    protected $fillable = [
        'ReserveID',    // reservテーブルの id を参照
        'payType',  // １:コース商品 / 2:オプション商品
        'index',   //  対象商品コード
        'price', 	//  単価
        'count',     //  数量
        'memo',        //  メモ書き
    ];
}
