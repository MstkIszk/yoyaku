<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Auth;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id'; // Reserve.Baseid が参照するキー
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'spName',
        'spNameKana',
        'spCode',
        'spAddrZip',
        'spAddrPref',
        'spAddrCity',
        'spAddrOther',
        'spTel1',
        'spTel2',
        'spEMail',
        'spURL',
        'spMsgText',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    function getAuthPasswordName() {
        return "undefine";
    }
    function isOwner($id) {
        if (Auth::check()) {
            if(Auth::user()->id == $id) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * UserProduct (商品/サービス) との1対多のリレーションを定義します。
     * 店舗 (User) は複数の商品 (UserProduct) を持ちます。
     * 外部キーは user_products テーブルの 'baseCode' (店舗ID) です。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany  
    {
        // 'baseCode' が User モデルの 'id' を参照している場合
        return $this->hasMany(UserProduct::class, 'baseCode', 'id');
    }

    /**
     * ユーザーが持つ UserAccessory リレーションを取得します。
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function accessories(): HasMany
    {
        // UserAccessory の 'baseCode' が User の 'id' を参照
        return $this->hasMany(UserAccessory::class, 'baseCode', 'id');
    }

}
