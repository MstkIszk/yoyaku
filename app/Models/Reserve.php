<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UserCourse;

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
            'Courseid',         //  user_courseのID
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

    /**
     * Bit列で指定された支払い方法を取得し、配列として返します。
     *
     * @param int $WaysPayBit user_productsテーブルのWaysPayBitの値 (ビット列)
     * @return array 選択された支払い方法のリスト (形式: [[PrBit, PrName], ...])
     */
    public function GetPaysWay(int $WaysPayBit): array
    {
        // 支払い方法テーブルからPrBitとPrNameをすべて取得
        // データベースへの負荷を考慮し、必要なカラムのみ指定
        $waysPays = ShopWaysPay::all(['PrBit', 'PrName']);

        $PaysWayList = [];

        // 取得した支払い方法をループ処理
        foreach ($waysPays as $way) {
            // ビットAND演算を実行: (テーブルのPrBit) & (引数の$WaysPayBit)
            // 結果が0でなければ、その支払い方法が有効であることを示す
            if (($way->PrBit & $WaysPayBit) !== 0) {
                // 支払い方法を [PrBit, PrName] の形式でリストに追加
                $PaysWayList[] = [
                    $way->PrBit,
                    $way->PrName
                ];
            }
        }

        return $PaysWayList;
    }

    protected static $ReserveStatusList = [
        'Accept'    => 0, //  受付後、未確認
        'complete'  => 1, //  受付後、メール確認済
        'cancel'    => 9, //  キャンセル済
    ];
}
