<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProduct extends Model
{
    use HasFactory;

    protected $table = 'user_products';


    //  保存・更新したいカラムを設定
    protected $fillable = [
        'productName',
        'DateStart',
        'DateEnd',
        'TimeStart',
        'TimeEnd',
        'capacity',
        'price',
        'WaysPay',
        'memo',
    ];

}
