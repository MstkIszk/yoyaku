<?php

namespace Database\Seeders;

//　Seeder 実行 コマンド
// php artisan db:seed --class=UserCalenderSeeder

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\UserCalender;
use Carbon\Carbon;

class UserCalenderSeeder extends Seeder
{
    /**
     * 実行する祝日データ (2025年10月～2026年12月)
     * 日付は YYYY-MM-DD 形式、datetype は 3 (祭日)
     * 祭日の名称は「メモ」として使用
     *
     * @var array<string, string>
     */
    protected $holidays = [
        // 2025年
        '2025-10-13' => 'スポーツ',
        '2025-11-03' => '文化の日',
        '2025-11-24' => '勤労振替', // 11/23が日曜のため
        '2025-12-23' => '天皇誕生日',

        // 2026年
        '2026-01-01' => '元日',
        '2026-01-12' => '成人の日',
        '2026-02-11' => '建国記念日',
        '2026-02-23' => '天皇誕生日',
        '2026-03-20' => '春分の日',
        '2026-04-29' => '昭和の日',
        '2026-05-04' => 'みどりの日',
        '2026-05-05' => 'こどもの日',
        '2026-07-20' => '海の日',
        '2026-08-11' => '山の日',
        '2026-09-21' => '敬老の日',
        '2026-09-22' => '国民の休日', // 敬老の日と秋分の日(23日)の間
        '2026-09-23' => '秋分の日',
        '2026-10-12' => 'スポーツ',
        '2026-11-03' => '文化の日',
        '2026-11-23' => '勤労感謝',
        '2026-12-23' => '天皇誕生日',
    ];

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 期間を設定
        $startDate = Carbon::create(2025, 10, 1)->startOfDay();
        $endDate = Carbon::create(2026, 12, 31)->endOfDay();
        
        // データを投入する店舗ID (仮に1とする。実際には存在するIDを指定してください)
        $targetBaseCode = 1;

        // 対象期間をループ
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            $datetype = null;
            $memo = null;
            $dateString = $date->format('Y-m-d');

            // 1. 祝日判定 (最優先)
            if (isset($this->holidays[$dateString])) {
                $datetype = 3;
                $memo = $this->holidays[$dateString];
            }
            
            // 2. 曜日判定 (祝日と重なっていない場合のみ)
            //if ($datetype === null) {
            //    if ($date->isSaturday()) {
            //        $datetype = 1; // 土曜日
            //    } elseif ($date->isSunday()) {
            //        $datetype = 2; // 日曜日
            //    }
            //}

            // 土日または祝日である場合のみレコードを作成
            if ($datetype !== null) {
                UserCalender::create([
                    'destDate' => $date->toDateTimeString(),
                    'datetype' => $datetype,
                    'memo' => $memo,
                ]);
            }
        }
    }
}
