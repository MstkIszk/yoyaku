<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Collection;
use App\Models\UserCoursePrice;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UserCourse extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'user_course';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'baseCode',
        'productID',
        'courseCode',
        'courseName',
        'IsEnabled',
        'weekdayPrice',
        'weekendPrice',
        'memo',
    ];


    /**
     * 日付として扱う属性
     * @var array
     */
    protected $casts = [
        'IsEnabled' => 'boolean',
    ];

    /**
     * このコースが属するUserProductを取得するリレーション
     * 外部キー: user_course.productID
     * 参照元キー: user_products.productID (またはUserProductモデルの$primaryKey)
     * * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userProduct(): BelongsTo
    {
        // 外部キー('productID')と、親モデル（UserProduct）のキー('productID')を指定
        return $this->belongsTo(UserProduct::class, 'productID', 'productID');
    }

    /**
     * このコースが属する商品（UserProduct）を取得します。
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(UserProduct::class, 'productID');
    }

    /**
     * UserCoursePrice モデルとのリレーションを追加
     * このコースに紐づく料金体系を取得します。
     * 親のID（UserCourseのid）を外部キーとして使用します。
     * * 注意: UserCoursePriceテーブルにはcourseCodeがカラムとしてありますが、
     * ここではUserCourseのID（主キー）を使ってリレーションを構築します。
     * もしUserCoursePriceテーブルのcourseCodeカラムがUserCourseのcourseCodeと一致する
     * データを取得したい場合は、HasManyThroughやカスタムリレーションが必要ですが、
     * シンプルにUserCourseのIDを外部キーとして扱うのがLaravelの慣例です。
     * * UserCoursePriceテーブルの外部キーが 'user_course_id' のような名称であるべきですが、
     * 定義に基づき、今回は `courseCode` をリレーションのローカルキー（親キー）として使用します。
     */
    public function userCoursePrices(): HasMany
    {
        // 外部キー('courseCode')と、親モデル（UserProduct）のキー('productID')を指定

        // foreignKey(外部キー): UserCoursePriceテーブルの 'courseCode'
        // localKey(ローカルキー): UserCourseテーブルの 'courseCode'
        // baseCodeとproductIDも一致させるべきですが、ここではcourseCodeのみでシンプルに定義します。
        // （ただし、異なる商品で同じ courseCode を使用している場合は問題が発生する可能性があります）
        return $this->hasMany(UserCoursePrice::class, 'courseCode');
                //->where('baseCode', $this->baseCode); 
    }

    /**
     * 予約コーステーブルから予約タイプのリストを取得する。
     *
     * baseCode, productIDで絞り込み、courseCode順で読み込む。
     *
     * @param int|string $userId 絞り込みに使用するbaseCode (店舗ID)
     * @param int|string $ProductId 絞り込みに使用するproductID (商品名ID)
     * @return \Illuminate\Support\Collection|\App\Models\UserCourse[]
     */
    public static function GetYoyakuType($userId, $ProductId): Collection       
    {
        // $userId を baseCode として使用し、予約コーステーブル（user_course）からデータを取得
        $yoyakuTypes = UserCourse::query()
            // baseCodeで絞り込み
            ->where('baseCode', $userId)
            // productIDで絞り込み
            ->where('productID', $ProductId)
            // courseCodeの昇順で並べ替え
            ->orderBy('courseCode', 'asc')
            // 結果を取得
            ->get();
        
        // 取得したコレクションを返却
        return $yoyakuTypes;
    }    
}
