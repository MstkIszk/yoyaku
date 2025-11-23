<?php

//　Seeder 実行 コマンド
// php artisan db:seed --class=ReserveSeeder

namespace Database\Seeders;

use App\Models\Reserve;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ReserveSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // ReserveDate の範囲: 2025/11/1 ～ 2025/12/31
        $startDate = Carbon::create(2025, 11, 1);
        $endDate = Carbon::create(2025, 12, 31);
        
        // OrderNo の初期値 (8から連番)
        $orderNo = 8;

        // 期間内の全ての日付に対してループ
        for ($date = $startDate; $date->lte($endDate); $date->addDay()) {
            
            // 1日あたりの予約数 (4～6件) をランダムに決定
            $reservationCount = rand(4, 6);
            
            for ($i = 0; $i < $reservationCount; $i++) {
                
                // Factoryを使用してデータを生成
                $data = Reserve::factory()->make([
                    'ReserveDate' => $date->format('Y-m-d') . ' 07:00:00',
                ])->toArray();

                // OrderNo を設定
                $data['OrderNo'] = $orderNo++;

                // MessageText の再設定（Factory側のfullNameKanjiを参照できないため）
                // Factoryで生成したClitNameKanjiをそのまま使用
                $data['MessageText'] = 'テスト' . $data['ClitNameKanji'];
                
                // データベースに挿入
                Reserve::create($data);
            }
        }
    }
}
