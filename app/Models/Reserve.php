<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Reserve extends Model
{
    use HasFactory;
    //  使用するテーブル名を明示的に設定する
    protected $table = 'reserv';

    //  保存・更新したいカラムを設定
    protected $fillable = [
            'OrderNo',          //  予約番号
            'Baseid',   
            'Productid',
            'KeyStr',           //  照会時に比較する
            'ReqDate',          //  予約日時
            'ReserveDate',      //  予約日
            'ClitNo',           //  予約番号
            'ClitNameKanji',    //  氏名漢字
            'ClitNameKana',     //  氏名カナ
            'CliAddrZip',       //  郵便番号
            'CliAddrPref',      //  県名
            'CliAddrCity',      //  市町村名
            'CliAddrOther',     //  地域名
            'CliTel1',          //  電話番号
            'CliEMail',         //  メールアドレス
            'CliResvType',      //  予約タイプ	
            'CliResvCnt',       //  予約人数
            'CliWaysPay',       //  支払い方法
            'MessageText',      //  連絡
            'RandomNumber',     //  確認用の乱数
            'UpdateDate',       //  更新日
            'Status'            //  予約状態
    ];

    public static $MaxReserve = 15;

    /** @var array Datetime型として扱うカラム */
    protected $dates = [
        'ReqDate',
        'ReserveDate',
    ];

    protected $casts = [
        'ReqDate' => 'datetime',
        'ReserveDate' => 'datetime',
        'UpdateDate' => 'datetime',
    ];
    function editValue($val) {
        return ' value="' . $val . '"';
    }

    public static $YoyakuTypeList = [
        [   0  , '終日'    ],
        [   1  , '午前中'  ],
        [   2  , '午後'    ],
        [   3  , '３時間'  ]
    ];
    public static function GetYoyakuType($userId,$ProductIid) {



        return self::$YoyakuTypeList;
    }

    public static $PaysWayList = [
        [   0  , '現金'    ],
        [   1  , '銀行振込'  ],
        [   2  , 'Paypay'    ],
        [   3  , 'クレジットカード'  ]
    ];
    public static function GetPaysWay() {
        return self::$PaysWayList;
    }

    protected static $ReserveStatusList = [
        'Accept'    => 0, //  受付後、未確認
        'complete'  => 1, //  受付後、メール確認済
        'cancel'    => 9, //  キャンセル済
    ];
}
