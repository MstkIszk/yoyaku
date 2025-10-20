<?php
//  店舗登録の定義

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReserveBase extends Model
{
    use HasFactory;

    
    protected $table = 'reserv_base';

    //  保存・更新したいカラムを設定
    protected $fillable = [
        'spName',       //  店舗名
        'spCode',       //  店舗コード
        'spCapacity',   //  定員
        'spNameKana',   //  氏名カナ
        'spAddrZip',    //  郵便番号
        'spAddrPref',   //  県名
        'spAddrCity',   //  市町村名
        'spAddrOther',  //  地区名
        'spTel1',       //  電話番号１
        'spTel2',       //  電話番号２
        'spEMail',      //  メールアドレス
        'spResvType',   //  予約タイプ
        'spiWaysPay',   //  支払い方法
        'spMsgText',    //  連絡
    ];

}
