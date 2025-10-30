<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\DB;
use App\Models\ReserveDate; // reserv_dateテーブルのモデル名

class CheckReservationCount implements ValidationRule
{
    protected $reserveDate;
    protected $baseId;
    protected $productId;

    /**
     * Create a new rule instance.
     *
     * @param string $reserveDate 予約日時 (ReserveDate)
     * @param int $baseId 店舗ID (Baseid)
     * @param int $productId 商品ID (Productid)
     */
    public function __construct($reserveDate, $baseId, $productId)
    {
        $this->reserveDate = $reserveDate;
        $this->baseId = $baseId;
        $this->productId = $productId;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @param  mixed  $value チェック対象の値 (CliResvCnt - 今回の予約人数)
     * @return void
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $requestedCount = (int)$value;

        // ReserveDate (reserv_date) テーブルから対象のレコードを取得
        $reservationSlot = ReserveDate::where('baseCode', $this->baseId)
            ->where('productID', $this->productId)
            ->where('destDate', $this->reserveDate)
            ->first();

        if ($reservationSlot) {
            // capacity: 定員, yoyakusu: ネット予約以外の予約人数
            $currentNetReservations = DB::table('reserv')
                ->where('Baseid', $this->baseId)
                ->where('Productid', $this->productId)
                ->where('ReserveDate', $this->reserveDate)
                ->where('Status', '!=', 9) // 予約キャンセル/無効(Status=9を仮定)を除外
                ->sum('CliResvCnt');

            // ネット予約以外の予約人数 (yoyakusu) + ネット予約の合計 + 今回の予約人数
            $totalReserved = $reservationSlot->yoyakusu + $currentNetReservations + $requestedCount;

            if ($totalReserved > $reservationSlot->capacity) {
                $available = $reservationSlot->capacity - ($reservationSlot->yoyakusu + $currentNetReservations);
                
                if ($available < 0) $available = 0; // 既に定員オーバーの場合
                
                $fail("予約人数が定員を超過しています。現在予約可能な人数は、残枠{$available}名です。");
            }
        }// else {
            // 対象の予約日時の枠が存在しない場合 (システムエラーの可能性)
        //    $fail('指定された予約日時が見つかりません。');
        //}
    }
}
