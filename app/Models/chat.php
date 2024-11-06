<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chat extends Model
{
    protected $table = 'chat';    //  モデルに関連付けるテーブル

    use HasFactory;
    protected $fillable = [
        'login_id', 'name', 'comment'
    ];

    protected $guarded = [
        'create_at', 'update_at'
    ];    
}
