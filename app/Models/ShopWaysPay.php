<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopWaysPay extends Model
{
    use HasFactory;

    protected $table = 'shop_ways_pays';

    protected $fillable = ['PrBit', 'PrName'];

    public function getWaysPayList()
    {
        return ShopWaysPay::all()->map(function ($way) {
            return ['bit' => $way->PrBit, 'name' => $way->PrName];
        })->toArray();
    }    

}
