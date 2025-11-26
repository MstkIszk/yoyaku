<?php

namespace Database\Factories;

use App\Models\Reserve;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ReserveFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Reserve::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // 氏名とフリガナを生成
        $lastNameKanji = $this->faker->lastName;
        $firstNameKanji = $this->faker->firstName;
        $fullNameKanji = $lastNameKanji . $firstNameKanji;
        
        // 仮のカナ名を生成（実際のカナ変換は複雑なため、ここではシンプルな生成）
        $fullNameKana = $this->faker->kanaName; 

        // 長野県の住所情報を生成
        $zip = '3' . $this->faker->randomNumber(6, true); // 長野県の郵便番号（3から始まる）
        $pref = '長野県';
        $city = $this->faker->randomElement(['長野市', '松本市', '上田市', '飯田市']);
        $addrOther = $this->faker->streetAddress;
        
        // 長野県の電話番号（026から始まるを想定）
        $tel = '026-' . $this->faker->randomNumber(3, true) . '-' . $this->faker->randomNumber(4, true);

        // 予約日時 (ReqDate) の範囲: 2025/11/1 7:00 ～ 2025/11/20 21:00
        $reqDateStart = new \DateTime('2025-11-01 07:00:00');
        $reqDateEnd = new \DateTime('2025-11-20 21:00:00');
        $reqDate = $this->faker->dateTimeBetween($reqDateStart, $reqDateEnd)->format('Y-m-d H:i:s');
        
        // 予約日 (ReserveDate) の範囲: 2025/11/1 7:00 ～ 2025/12/31 7:00
        $reserveDateBase = $this->faker->dateTimeBetween('2025-11-01', '2025-12-31')->format('Y-m-d');
        $reserveDate = $reserveDateBase . ' 07:00:00';

        // OrderNo は Seeder 側で連番を振るため、Factory ではダミー値を返すか、 null を許容する場合は指定しない
        // 今回は Seeder で制御する前提で他のデータを定義

        return [
            // OrderNo は Seeder 側で設定
            'Baseid' => 1,
            'Productid' => 1,
            'Courseid' => 1,
            'KeyStr' => 'ABCDFEG',
            'ReqDate' => $reqDate,
            'ReserveDate' => $reserveDate,
            'ClitNo' => 1,
            'ClitNameKanji' => $fullNameKanji,
            'ClitNameKana' => $fullNameKana,
            'CliAddrZip' => $zip,
            'CliAddrPref' => $pref,
            'CliAddrCity' => $city,
            'CliAddrOther' => $addrOther,
            'CliTel1' => $tel,
            'CliEMail' => $this->faker->email, // 任意のメールアドレス
            'CliResvType' => 1, // Courseidと同じ値
            'CliResvCnt' => $this->faker->numberBetween(1, 4),
            'CliWaysPay' => 1,
            'MessageText' => 'テスト' . $fullNameKanji,
            'RandomNumber' => $this->faker->numberBetween(1000, 9999),
            'UpdateDate' => now(), // 本日日付
            'Status' => 1,
        ];
    }
}
