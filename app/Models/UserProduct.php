<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
    
class UserProduct extends Model
{
    use HasFactory;

    protected $table = 'user_products';


    //  保存・更新したいカラムを設定
    protected $fillable = [
        'baseCode',
        'productID',
        'IsEnabled',
        'productName',
        'DateStart',
        'DateEnd',
        'TimeStart',
        'TimeEnd',
        'capacity',
        'WeekdayPrice',
        'WeekendPrice',
        'AddtionalName',
        'AddtionalPrice',
        'ResvTypeBit',
        'WaysPayBit',
        'memo',
    ];

   
    // UserCourse モデルとのリレーションを追加
    public function courses(): HasMany
    {
        // 'user_course'テーブルの 'productID' カラムが、このモデルの 'productID' カラムを参照

        \Log::info('Checking courses for Product ID: ' . $this->productID . ' with BaseCode: ' . $this->baseCode);

        //  第1引数	UserCourse::class	関連付けられるモデル（子モデル）のクラス名です。
        //  第2引数	'productID'	子テーブル（user_courses）のリレーションキー（外部キー）です。
        //          Eloquentは通常、親モデル名に_idを付けたものを自動で外部キーと見なしますが、ここでは明示的にproductIDを指定しています。
        //  第3引数	'productID'	親テーブル（user_products）のローカルキー（主キーとして扱うカラム）です。
        //          このUserProductモデルのどのカラムを使って子モデルを検索するかを指定しています。
        return $this->hasMany(UserCourse::class, 'productID', 'productID')
                    // baseCodeも一致させることで、リレーションのスコープを現在の店舗に限定
                    ->where('baseCode', $this->baseCode); 
    }
    // UserCourse モデルとのリレーションを追加
    public function productCourses(): HasMany
    {
        return $this->hasMany(UserCourse::class, 'productID');
               //->where('baseCode', $this->baseCode); 
    }
}
