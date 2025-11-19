<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAccessory extends Model
{
    use HasFactory;

    protected $table = 'user_accessories';
    // 主キーは 'id' であると仮定
    protected $primaryKey = 'id';
    
    //  保存・更新したいカラムを設定
    protected $fillable = [
        'baseCode',
        'productID',
        'productName',
        'IsEnabled',
        'price',
        'memo',
    ];

    /**
     * 関連付けられているユーザー（店舗）を取得します。
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        // 外部キー 'baseCode' を通じて User モデルの 'id' に結合
        return $this->belongsTo(User::class, 'baseCode', 'id');
    }

}
