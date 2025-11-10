<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
