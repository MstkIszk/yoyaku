<?php

//　Seeder 実行 コマンド
// php artisan db:seed --class=ReservDateSeeder

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;
use DateTime;
use DateInterval;
use DatePeriod;
use Illuminate\Support\Carbon;

class ReservDateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // 既存のデータをクリアする場合はコメントを解除
        // DB::table('reserv_date')->truncate();

        $faker = Faker::create('ja_JP');

        // 日付範囲の設定: 2025/11/01 から 2025/12/31
        $startDate = new DateTime('2025-11-01');
        $endDate = new DateTime('2025-12-31');
        
        // 終了日（12/31）を含めるため、期間の終了を翌日に設定
        $period = new DatePeriod(
            $startDate,
            DateInterval::createFromDateString('1 day'),
            $endDate->modify('+1 day')
        );

        $weatherList = ["快晴", "晴", "曇り", "雨", "雪", "霙"];
        $data = [];
        $now = Carbon::now();

        foreach ($period as $date) {
            // 8. stars: 1 to 5 でランダムに設定
            $stars = $faker->numberBetween(1, 5);

            // 9. memo - 1行目: 天気と気温を生成 (気温 -3～18)
            $weather = $faker->randomElement($weatherList);
            $temp = $faker->numberBetween(-3, 18);
            $memoLine1 = "{$weather}　気温{$temp}'Ｃ";

            // 9. memo - 2行目: starsの値に基づいて釣果を生成
            $fishingResult = 0;
            switch ($stars) {
                case 1:
                    $fishingResult = $faker->numberBetween(0, 50);
                    break; // 0～50
                case 2:
                    $fishingResult = $faker->numberBetween(51, 99);
                    break; // 51～99
                case 3:
                    $fishingResult = $faker->numberBetween(100, 200);
                    break; // 100～200
                case 4:
                    $fishingResult = $faker->numberBetween(201, 500);
                    break; // 201～500
                case 5:
                    // 500～1200の範囲。stars=4との境界を明確にするため501から開始
                    $fishingResult = $faker->numberBetween(501, 1200);
                    break; 
            }
            $memoLine2 = "釣果 {$fishingResult}";
            
            // 9. memo: 2行を改行で結合
            $memo = $memoLine1 . "\n" . $memoLine2;

            $data[] = [
                'baseCode'  => 1,      // 1. baseCode: 1 (固定)
                'productID' => 1,     // 2. productID: 1 (固定)
                'eigyotype' => 1,     // 3. eigyotype: 1 (固定)
                'destDate'  => $date->format('Y-m-d 00:00:00'), // 4. destDate: 対象日付 (DATETIME形式)
                'operating' => 1,     // 5. operating: 1 (固定)
                'capacity'  => 14,     // 6. capacity: 14 (固定)
                'yoyakusu'  => 0,      // 7. yoyakusu: 0 (固定)
                'stars'     => $stars, // 8. stars: 1～5 (ランダム)
                'memo'      => $memo,  // 9. memo: 生成されたメモ
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // データを reserv_date テーブルに挿入
        DB::table('reserv_date')->insert($data);
    }
}
