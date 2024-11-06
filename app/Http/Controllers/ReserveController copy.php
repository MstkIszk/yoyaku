<?php
//  予約の受付用コントローラ

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reserve;


enum ReserveStatus : int
{
    const Entry = 0; //  受付後、未確認
    const Accept = 1; //  受付後、メール確認済
    const Cancel = 9; //  キャンセル済
}


class ReserveController extends Controller
{
    // routes\web.php から呼ばれる関数

    public function create() {  //  新規投稿
        return view("Reserve.RCreate");

        // makeメソッドでインスタンスを作成（データベースには保存しない）
        //$reserve = Reserve::make([
        //    'ClitNo'        =>  1, 
        //    'ReqDate'       =>  "2024-09-15 07:00:00",
        //]);
        //return view("Reserve.REdit",compact('reserve'));
    }
    public function store(Request $request) {
        //  ガード文字列
        //          0         1         2         3         4         5         6         
        $MasterKeyStr = 'AerD#$HjaiouAp@o|&YU%#jkTFG&Hiuwe)(aBng>OP_K&kljkA?OUf;=SgF:E%FdYBuY#Etx';
        $KeyStr = substr($MasterKeyStr,date("s"),10);
        //  登録日時
        $ReqDate = date("Y-m-d H:i:s");

        $newOrderNo = Reserve::max('OrderNo') + 1;
        $today = date('Y-m-d');
        $reserve = Reserve::create([
            'OrderNo'       =>  $newOrderNo,    //  予約番号
            'KeyStr'        =>  $KeyStr,        //  照会時に比較する	
            'ReqDate'       =>  $ReqDate,       //  予約日時	
            'ReserveDate'   =>  $request->ReserveDate,      //  予約日
            'ClitNo'        =>  1,                          //  変更数
            'ClitNameKanji' =>  $request->ClitNameKanji,    //  氏名漢字
            'ClitNameKana'  =>  $request->ClitNameKana,     //  氏名カナ
            'CliAddrZip'    =>  $request->CliAddrZip,       //  郵便番号
            'CliAddrPref'   =>  $request->CliAddrPref,      //  県名
            'CliAddrCity'   =>  $request->CliAddrCity,      //  市町村名
            'CliTel1'       =>  $request->CliTel1,          //  電話番号
            'CliEMail'      =>  $request->CliEMail,         //  メールアドレス
            'CliResvType'   =>  $request->CliResvType,      //  予約タイプ
            'CliResvCnt'    =>  $request->CliResvCnt,       //  予約人数
            'CliWaysPay'    =>  $request->CliWaysPay,       //  支払い方法
            'MessageText'   =>  $request->MessageText,      //  連絡
            'UpdateDate'    =>  $today,                     //  更新日
            'Status'        =>  ReserveStatus::Entry        //  予約状態 	
        ]);
        //  処理後に元のページに戻る
        return redirect()->route('reserve.index');
    }
    public function show($id) {
        $reserve = Reserve::find($id);
        return view("Reserve.RShow", compact('reserve'));
    }
    public function index() {
        $reserve = Reserve::all();   //  予約データの一括読み込み
        return view("Reserve.RIndex",compact('reserve'));
    }
    public function edit(Reserve $reserve) {
        //return view("Reserve.RUpdate",compact('reserve'));
        return view("Reserve.REdit",compact('reserve'));
    }
    public function update(Request $request, Reserve $reserve) {
        //  入力の妥当性チェック
        $validated = $request->validate([
            //      required : 必須入力
            //      max:nn : 最大文字列
            //      'integer | between:0,150' : 数値 0～150 
            //      ['max:1', 'regex:/^[男|女]+$/u'],
            "ReserveDate" =>  "",      //  予約日
            "ClitNameKanji" =>  "required|max:20",
            "ClitNameKana"  =>  "required|max:20",
            "ClitNo" =>  "",           //  予約番号
            "CliAddrZip" =>  "",       //  郵便番号
            "CliAddrPref" =>  "",      //  県名
            "CliAddrCity" =>  "",      //  市町村名
            "CliTel1" =>  "",          //  電話番号
            "CliEMail" =>  "",         //  メールアドレス
            "CliResvType" =>  "",      //  予約タイプ	
            "CliResvCnt" =>  "",       //  予約人数
            "CliWaysPay" =>  "",       //  支払い方法
            "MessageText" =>  ""      //  連絡
            ],
            [
                //  エラーメッセージ記述
                'ClitNameKanji.max' => '漢字名は20文字以内。',
                'ClitNameKana.required' => 'bodyは必須項目です。'
            ]
           
        );
        if($reserve->update($validated) == false) {
            $request->session()->flush('message','更新に失敗しました');            
        }
        else {
            //  処理後に一覧表示ページに戻る
            return redirect()->route('reserve.index');
        }
    }
    public function cancel() {
        return view("Reserve.RCancel");
    }
    public function destroy(Request $request, Reserve $reserve) {
        $reserve->delete();
        $request->session()->flush('message', '削除しました');
        return redirect()->route('reserve.index');
    }
    
    public function calender(Request $request,$month = "") {

        if($month == "") {
            $month = date('Y-m-d');
        }
        return view("Reserve.RCalender",compact('month'));
    }

    public function calenderGet(Request $request,$month = "") {

        $month = $request->month;

        //  １日と最終日を取得
        $firstDay = $compDay = date('Y-m-01', strtotime($month));
        $lastDay = date('Y-m-t', strtotime($month));

        // 指定月の範囲内のデータを取得
        $reservations = Reserve::whereBetween('ReserveDate', [$firstDay, $lastDay])->orderBy('ReserveDate')->get();
        $dcnt = count($reservations);
        $dix = 0;
        $resvDate = 99;
        if($dcnt > 0) {
            $resv = $reservations[$dix];    //  年月日の日の部分だけを切り出し
            $resvDate = substr($resv->ReserveDate,8,2);
        }

        //  日数を取得
        $dateCnt = date('t',strtotime($lastDay));

        //  初日の曜日コード取得
        $weekdayCode = $posX = date('w', strtotime($firstDay));

        $calender = [];
        //$week = ['','','','','','',''];
        $json_week = '[{"day":"0"},{"day":"0"},{"day":"0"},{"day":"0"},{"day":"0"},{"day":"0"},{"day":"0"}]';
        //$week = collect(json_decode($json_week, true));
        $week = json_decode($json_week, true);

        $json_string = '[{"name": "Alice", "age": 30}, {"name": "Bob", "age": 25}]';
        $data = json_decode($json_string, true);

        for ($day = 1; $day <= $dateCnt; $day ++) {
            //  日を配列に埋め込み

            $week[$posX]['day'] = $day; 
            $totalCnt = 0;
            while($day == $resvDate) {  
                // 同じ日に予約の登録があったら、配列に新しい要素を追加
                $memName = $resv['ClitNameKanji'];
                $memCnt = $resv['CliResvCnt'];
                $week[$posX]['member'][] =  [ 'name' => $memName,'cnt' => $memCnt];
                $totalCnt += $memCnt;
                if($dcnt > ++$dix) {
                    $resv = $reservations[$dix];
                    $resvDate = substr($resv->ReserveDate,8,2);
                }
                else {
                    $resvDate = 99;
                }
                //$resvDate = $resv['ReserveDate']->date;
            }
            $week[$posX]['totalCnt'] = $totalCnt;

            if(++$posX > 6) {   //  週が終わったら
                array_push($calender,$week);
                $posX = 0;
                $week = json_decode($json_week, true);
            } 
        }
        //  余りがあれば追加する
        if($posX > 0) {
            array_push($calender,$week);
        }

        return response()->json($calender);
    }

}
