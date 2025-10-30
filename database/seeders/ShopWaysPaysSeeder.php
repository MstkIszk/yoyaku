<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopWaysPaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // 支払い方法の初期データ
        $ways = [
            // 必須の3つ
            ['PrBit' => 1, 'PrName' => '現金'],
            ['PrBit' => 2, 'PrName' => 'クレジットカード'],
            ['PrBit' => 4, 'PrName' => 'バーコード決済'],
            ['PrBit' => 8, 'PrName' => '電子マネー'],
            ['PrBit' => 16, 'PrName' => '振込・銀行決済'],
        ];

        // データの投入
        foreach ($ways as $way) {
            DB::table('shop_ways_pays')->insert([
                'PrBit' => $way['PrBit'],
                'PrName' => $way['PrName'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
