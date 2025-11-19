<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReserveDate extends Model
{
    use HasFactory;

    protected $table = 'reserv_date';

    //  保存・更新したいカラムを設定
    protected $fillable = [
        'baseCode',     //  対象の店舗コード
        'productID',    //  商品コード
        'eigyotype',    //  営業タイプ
        'destDate',     //  対象日付
        'operating',    // 1:通常営業・2:休業・3:貸し切り
        'capacity',     //  定員
        'yoyakusu',     //  ネット予約以外の予約人数
        'stars',        //  ★１～★５
        'memo'          //  メモ書き
    ];

    
    protected $casts = [
        'baseCode'  => 'integer',     //  対象の店舗コード
        'productID' => 'integer',    //  商品コード
        'eigyotype' => 'integer',    //  営業タイプ
        'destDate'  => 'datetime',    //  対象日付
        'operating' => 'integer',    // 1:通常営業・2:休業・3:貸し切り
        'capacity'  => 'integer',     //  定員
        'yoyakusu'  => 'integer',     //  ネット予約以外の予約人数
        'stars'     => 'integer',     //  
        'memo'      => 'string'           //  メモ書き
    ];

    
    public static $operatingList = [
        [   1  , '営業'    ],
        [   2  , '休業'  ],
        [   3  , '貸切'    ]
    ];

    public static function  GetOperating() {
        return self::$operatingList;
    }
}
