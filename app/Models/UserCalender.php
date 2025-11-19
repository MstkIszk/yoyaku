<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class UserCalender extends Model
{
    use HasFactory;

    /**
     * モデルに関連付けるテーブル
     *
     * @var string
     */
    protected $table = 'user_calenders';

    /**
     * 複数代入可能な属性
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'destDate',
        'datetype',
        'memo',
    ];

    /**
     * ネイティブなタイプへキャストする属性
     *
     * @var array<string, string>
     */
    protected $casts = [
        'destDate' => 'datetime',
    ];

    /**
     * 指定された日付が休日であるかを判定します。
     * * 1. 土曜日または日曜日であれば true を返します。
     * 2. それ以外の場合、user_calenders テーブルを検索します。
     *
     * @param string $date 日付 (YYYY-MM-DD 形式)
     * @return bool 休日であれば true、そうでなければ false
     */
    public static function GetHoriday(string $date): bool
    {
        try {
            // 渡された文字列日付をCarbonインスタンスに変換
            $carbonDate = Carbon::parse($date);
        } catch (\Exception $e) {
            // 日付形式が不正な場合は false を返す
            // 実際のアプリケーションでは、より詳細なエラーハンドリングが必要です
            \Log::error("Invalid date format provided to GetHoriday: {$date}");
            return false;
        }

        // 1. 土曜日 (6) または日曜日 (0) の判定
        // Carbon::isWeekend() を使用すると、土日を簡単に判定できます。
        if ($carbonDate->isWeekend()) {
            return true;
        }

        // 2. user_calenders テーブルを検索
        // データベースの destDate は dateTime型ですが、検索には日付部分のみを使用します。
        // Carbon::toDateString() は YYYY-MM-DD 形式を返します。
        $targetDateString = $carbonDate->toDateString(); 

        $isCustomHoliday = UserCalender::whereDate('destDate', $targetDateString)
                                       // datetypeが祭日(3)などの場合も考慮されますが、
                                       // この要件では存在するかどうかのみを判定します。
                                       ->exists();

        // データベースに存在すれば true、しなければ false
        return $isCustomHoliday;
    }


}
