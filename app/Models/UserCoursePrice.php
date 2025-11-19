<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $baseCode 店舗ID
 * @property int $productID 商品名ID
 * @property int $courseCode コースコード
 * @property int $priceCode 料金コード
 * @property string $courseName 料金名
 * @property int $IsEnabled 有効/無効
 * @property int $weekdayPrice 平日料金
 * @property int $weekendPrice 休日料金
 * @property string $memo メモ書き
 */
class UserCoursePrice extends Model
{
    use HasFactory;

    protected $table = 'user_course_price';

    protected $fillable = [
        'baseCode',
        'productID',
        'courseCode',
        'priceCode',
        'priceName',
        'IsEnabled',
        'weekdayPrice',
        'weekendPrice',
        'memo',
    ];

    // id以外をfillableとする
    protected $guarded = ['id'];

    // 整数型としてキャストされる属性
    protected $casts = [
        'baseCode' => 'integer',
        'productID' => 'integer',
        'courseCode' => 'integer',
        'priceCode' => 'integer',
        'IsEnabled' => 'integer',
        'weekdayPrice' => 'integer',
        'weekendPrice' => 'integer',
    ];

    /**
     * UserProduct (商品名) とのリレーション
     */
    public function userProduct(): BelongsTo
    {
        return $this->belongsTo(UserProduct::class, 'productID');
    }

    /**
     * UserCourse (コース) とのリレーション (productIDがUserCourseとリレーションされている場合)
     */
    public function userCourse(): BelongsTo
    {
        // 実際のリレーションキーに合わせて調整してください。
        // productIDがUserCourseのIDに対応していると仮定します。
        return $this->belongsTo(UserCourse::class, 'productID'); 
    }
    /**
     * リレーション: この料金体系が属するコースを取得
     */
    public function course()
    {
        // UserCourseのcourseCodeを参照します
        return $this->belongsTo(UserCourse::class, 'courseCode', 'courseCode');
    }
}
