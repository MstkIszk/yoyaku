<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class shop_calender extends Model
{
    use HasFactory;

    protected $table = 'shop_calender';

    protected $fillable = [

        'dsShopId',     //   店舗ID
        'dsProduct',    //  商品コード
        'dsMax',        //  参加最大人数
        'dsCnt',        //  実参加人数
        'dsDate',       // 対象日
        'dsMemo',       // 連絡
    ];

}
