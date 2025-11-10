<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
}
