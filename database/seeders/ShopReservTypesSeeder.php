<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShopReservTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 支払い方法の初期データ
        $Types = [
            ['RtBit' => 1, 'RtName' => '通常'],
            ['RtBit' => 2, 'RtName' => '貸切'],
        ];

        // データの投入
        foreach ($Types as $Type) {
            DB::table('shop_reserv_typess')->insert([
                'RtBit' => $Type['RtBit'],
                'RtName' => $Type['RtName'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
