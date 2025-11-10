<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopWaysPay extends Model
{
    use HasFactory;

    protected $table = 'shop_ways_pays';

    protected $fillable = ['PrBit', 'PrName'];

    public static function GetWaysPayList()
    {
        return ShopWaysPay::all()->map(function ($way) {
            return ['id' => $way->id, 'bit' => $way->PrBit, 'PrName' => $way->PrName];
        })->toArray();
    }    

    /**
     * Bit列で指定された支払い方法を取得し、配列として返します。
     *
     * データベース側でビットAND演算を行い、該当するレコードのみをフィルタリングします。
     *
     * @param int $WaysPayBit user_productsテーブルのWaysPayBitの値 (ビット列)
     * @return array 選択された支払い方法のリスト (形式: [[PrBit, PrName], ...])
     */
    public static function GetWaysPay($WaysPayBit): array
    {
        // Eloquentを使用してクエリを構築
        // whereRawでSQLのビットAND演算子 (&) を利用し、PrBit & $WaysPayBit が 0 ではないものを抽出
        $PaysWayCollection = self::query()
            ->whereRaw('`PrBit` & ?', [$WaysPayBit])
            ->get(['id','PrBit', 'PrName']);

        // 取得したコレクションを[PrBit, PrName]の形式の配列に変換
        $PaysWayList = $PaysWayCollection->map(function ($item) {
            return [
                $item->id,
                $item->PrBit,
                $item->PrName,
            ];
        })->toArray();

        return $PaysWayList;
    }

}
