<?php
//  予約の受付用コントローラ

namespace App\Http\Controllers;

use App\Models\Reserve;
use App\Models\ReserveDate;
use App\Rules\CheckReservationCount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User; // User モデルをインポート
use Illuminate\Support\Facades\View;
enum ReserveStatus : int
{
    const Entry = 0; //  受付後、未確認
    const Accept = 1; //  受付後、メール確認済
    const Cancel = 9; //  キャンセル済
}

// カスタムバリデーションロジック
Validator::extend('phone_number', function ($attribute, $value, $parameters, $validator) {
    // 例: 日本の携帯電話番号のみ許可
    return preg_match('/^(0{1}\d{1,4}-{0,1}\d{1,4}-{0,1}\d{4})$/', $value);
    //return User::where('username', $value)->doesntExist();

});
Validator::extend('postal_code', function ($attribute, $value, $parameters) {
    // 郵便番号のデータベースと照合するなど、より高度なチェックを行う
    return preg_match('/^\d{3}-\d{4}|\d{7}$/', $value);
});
Validator::extend('yoyaku_date', function ($attribute, $value, $parameters) {
    // 郵便番号のデータベースと照合するなど、より高度なチェックを行う
    $TodayDate = date('Y-m-dT07:00');
    return ($value > $TodayDate);
});
class ReserveController extends Controller
{
    // routes\web.php から呼ばれる関数

    //  店舗の新規登録
    public function spcreate(Request $request) {
        return view("Reserve.SpCreate");
    }
    //  店舗登録の確定    //  予約の編集
    public function spedit(Reserve $reserve) {
        //return view("Reserve.RUpdate",compact('reserve'));
        return view("Reserve.SpEdit",compact('reserve'));
    }
    public function spupdate(Request $request, Reserve $reserve) {
        //  入力の妥当性チェック
        $validated = $request->validate([
            //      required : 必須入力
            //      max:nn : 最大文字列
            //      'integer | between:0,150' : 数値 0～150 
            //      ['max:1', 'regex:/^[男|女]+$/u'],
            "SpNameKanji" =>  "required|max:20",
            "SptNameKana"  =>  "required|max:20",
            "SpAddrZip" =>  "",       //  郵便番号
            "SpAddrPref" =>  "",      //  県名
            "SpAddrCity" =>  "",      //  市町村名
            "SpTel1" =>  "required|phone_number",          //  電話番号
            "SpTel2" =>  "required|phone_number",          //  電話番号
            "SpEMail" =>  "required|email",         //  メールアドレス
            "SpResvType" =>  "",      //  予約タイプ	
            "SpWaysPay" =>  "",       //  支払い方法
            "MessageText" =>  ""      //  連絡
            ],
            [
                //  エラーメッセージ記述
                'SptNameKanji.max' => '漢字名は20文字以内。',
                'SptNameKana.required' => 'カナは必須項目です。'
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
    

    public function create(Request $request,$ReqDate='',$ReqType=0) {  //  新規投稿

        if(strlen($ReqDate) > 0) {
            $DestDate = $ReqDate . ' 07:00';
        }
        else {
            $DestDate = date('Y-m-d 07:00');
        }
        $ShopID = session('ShopID'); // キーに対応する値を取得
        $user = User::find($ShopID);
        return view("Reserve.RCreate",compact('user', 'DestDate','ReqType'));
        //return view("Reserve.RCreate")->with('DestDate', $DestDate)->with('ReqType', $ReqType);

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

        $randomNumber = mt_rand(1000, 9999);

        // バリデーションを通過した場合、予約処理を行う
        if($request->validate([
            'ReserveDate'   =>  'required|yoyaku_date',      //  予約日
            'ClitNameKanji' =>  'required',    //  氏名漢字
            'ClitNameKana'  =>  'required',     //  氏名カナ
            'CliAddrZip'    =>  'required|postal_code',       //  郵便番号
            'CliTel1'       =>  'required|phone_number',          //  電話番号
            'CliEMail'      =>  'required|email',         //  メールアドレス
            'CliResvType'   =>  'required',      //  予約タイプ
            'CliResvCnt'    =>  ['required', 'numeric', new CheckReservationCount($request->ReserveDate, $request->CliResvCnt)],       //  予約人数
            'CliWaysPay'    =>  'required'
        ],
        [
            'ReserveDate.required'   =>  '予約日を指定してください',      //  予約日
            'ReserveDate.yoyaku_date'   =>  '予約日は明日以降としてください',      //  予約日
            'ClitNameKanji.required' =>  '氏名を入力してください',    //  氏名漢字
            'ClitNameKana.required'  =>  'ヨミガナを入力してください',     //  氏名カナ
            'CliAddrZip.required'    =>  '郵便番号を入力してください',       //  郵便番号
            'CliAddrZip.postal_code'    =>  '郵便番号の形式が不正です',       //  郵便番号
            'CliTel1.required'       =>  '電話番号を指定してください',          //  電話番号
            'CliTel1.phone_number'       =>  '電話番号の形式が不正です',          //  電話番号
            'CliEMail.required'      =>  'メールアドレスを指定してください',         //  メールアドレス
            'CliEMail.email'      =>  'メールアドレスの形式が不正です',         //  メールアドレス
            'CliResvType.required'   =>  '予約タイプを指定してください',      //  予約タイプ
            'CliResvCnt.required'    =>  '予約人数を指定してください',
            'CliResvCnt.numeric'    =>  '予約人数は数値で指定してください',
            'CliWaysPay.required'    =>  '相払い方法を指定してください'
        ]
        )) {
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
                'CliAddrOther'  =>  $request->CliAddrOther,      //  地域名
                'CliTel1'       =>  $request->CliTel1,          //  電話番号
                'CliEMail'      =>  $request->CliEMail,         //  メールアドレス
                'CliResvType'   =>  $request->CliResvType,      //  予約タイプ
                'CliResvCnt'    =>  $request->CliResvCnt,       //  予約人数
                'CliWaysPay'    =>  $request->CliWaysPay,       //  支払い方法
                'MessageText'   =>  $request->MessageText,      //  連絡
                'UpdateDate'    =>  $today,                     //  更新日
                'RandomNumber'  =>  $randomNumber,              //  確認用の乱数
                'Status'        =>  ReserveStatus::Entry        //  予約状態
            ]);


            $OrderMailAddr		= "maki+order@hot-naniai.lix.jp";
            $InquiryMailAddr	= "maki+inquiry@hot-naniai.lix.jp";
            $OtherMailAddr		= "maki+other@hot-naniai.lix.jp";
            $AdditMailFile		= public_path() . "/TextParam/managerList.txt";
            $AdditMailAddr		= file_get_contents($AdditMailFile);
            $FurikomiKFile		= public_path() . "/TextParam/BankInfo.txt";
            $FurikomiKouza		= file_get_contents($FurikomiKFile);

            mb_language("Japanese");
            mb_internal_encoding("UTF-8");

            //$to = $_POST['to'];
            $to = $request->CliEMail;
            //$title = $_POST['title'];
            $title = "【あちゃまＷＥＢ開発】 お問い合わせありがとうございます。";
            $headers = "";	//"Content-Type:text/html;charset=UTF-8\r\n";
            $headers .= "Content-Type: text/plain \r\n";
            $headers .= "Return-Path: $InquiryMailAddr \r\n";
            $headers .=	"From: " . mb_encode_mimeheader("七二会森林クラブ　お問合せ係") . "<$InquiryMailAddr>\r\n";
            $headers .= "Bcc:$AdditMailAddr \r\n";
            $param = "-f $InquiryMailAddr";	//	サーバーによってはReturn-Path:を勝手に書き換えてしまうものもあるので、そのときは追加オプションのほうで -f を指定してあげます。

            $message = "受付日時:\r\n$ReqDate\r\n\r\n";
            $message .= "お名前:\r\n" .  $request->ClitNameKanji . "(" . $request->ClitNameKana . ")様\r\n";
            $message .= "予約日:\r\n" .  $request->ReserveDate . "　(" . $request->CliResvCnt . "名)\r\n\r\n";

            $message .= "予約確認番号:\r\n" .   $randomNumber . "\r\n\r\n";

            $message .= "入力画面を再表示する場合は、以下のURLを開いてください:\r\n";
            $message .= "{{ reserve.confirm }}?id=$newOrderNo&KeyStr='" . $KeyStr . "'&randomNumber='" . $randomNumber . "'\r\n";

            $message .= "ご連絡先\r\n TEL:" . $request->CliTel1 . "\r\n";
            $message .= "MAIL:" . $request->CliEMail . "\r\n\r\n";

            $message .= "------------------------\r\n連絡先: あちゃまＷＥＢ開発\r\n";
            $message .= "長野県 長野市 平林2-19-12-605\r\n";
            $message .= "TEL: 090-3585-2572\r\n";
            $message .= "E-Mail: info@kyum.chu.jp\r\n";


            if(mb_send_mail($to, $title, $message, $headers, $param)) {
                $request->session()->flash('message','確認メールを送信しました');
            }
            else {
                $request->session()->flash('message','確認メールの送信に失敗しました');
            }
            //  一覧のページに戻る
            return redirect()->route('reserve.index');
        }
        return back();        
    }
    //  予約の確認と確認コード入力画面
    public function confirm(Request $request,$id=0,$KeyStr="") {
        $result = Reserve::where('id', $request->id)
                            ->where('KeyStr', $request->KeyStr)
                            ->first();
        if ($result) {
            $request->session()->flash('message','予約内容を確認してください');
            return view("Reserve.RConfirm", compact('result'));
        } 
        else {
            // レコードが見つからなかった場合の処理
            $request->session()->flash('message','予約が見つかりません');
        }
    }
    //  予約の確定
    public function fixed(Request $request,$id=0,$KeyStr="") {
        $result = Reserve::where('id', $request->id)
                            ->where('KeyStr', $request->KeyStr)
                            ->first();
        if ($result) {
            $result->status = ReserveStatus::Accept;
            $result->save();
            $request->session()->flash('message','予約が確定しました');
            return view("Reserve.RShow", compact('result'));
        } 
        else {
            // レコードが見つからなかった場合の処理
            $request->session()->flash('message','確認が見つかりません');
        }
    }
    //  予約の照会
    public function show($id) {
        $reserve = Reserve::find($id);
        return view("Reserve.RShow", compact('reserve'));
    }
    //  予約の照会
    public function telnoinput(Request $request) {
        return view("Reserve.RTelNoinput");
    }
    //  管理画面　予約一覧の表示
    //  管理画面から呼ばれた場合はViewに対して全データ、電話番号画面から呼ばれた場合は抽出後のデータを渡す
    public function index(Request $request,$CliTel1 = "") {
        $reserve = null;
        if($request->CliTel1 == "") {
            $reserve = Reserve::all();   //  予約データの一括読み込み
        }
        else {
            //  指定した電話番号だけを抽出
            $reserve = Reserve::where('CliTel1', $request->CliTel1)->get();

            if ((!$reserve) || ($reserve->count() <= 0)) {
                // レコードが見つからなかった場合の処理
                $request->session()->flash('message','指定された電話番号での予約は見つかりません');
                return view("Reserve.RTelNoinput");
            }
        }
        return view("Reserve.RIndex",compact('reserve'));
    }
    //  予約の編集
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
            "CliTel1" =>  "required|phone_number",          //  電話番号
            "CliEMail" =>  "required|email",         //  メールアドレス
            "CliResvType" =>  "",      //  予約タイプ	
            "CliResvCnt" =>   ['required', 'numeric', new CheckReservationCount($reserve->ReserveDate, $reserve->CliResvCnt)],       //  予約人数
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
    
    //  店舗の情報と予約情報を表示
    public function shopsel(Request $request,$id = 0,$month = "") {

        if($id == 0) {
            return redirect()->route('profile.homelist');
        }
        $ShopInf = User::find($id); // 指定されたIDの予約データを取得
        if(!$ShopInf) {
            return redirect()->route('profile.homelist');
        }
        session(['ShopID' => $id]); // 選択された店舗のIDを保存
        if($month == "") {
            $month = date('Y-m-d');
        }

        return view("Reserve.RCalender",compact('month','ShopInf'));
    }

    
    public function calender(Request $request,$month = "",$user = null) {

        if($month == "") {
            $month = date('Y-m-d');
        }
        $ShopID = session(['ShopID']);
        $ShopInf = User::find($ShopID);
        if(!$ShopInf) {
            return redirect()->route('profile.homelist');
        }

        $ProductID  = session(['ProductID']);

        return view("Reserve.RCalender",compact('month','ShopInf'));
    }

    //  指定月のカレンダー情報を読み込むバックエンド処理
    public function calenderGet(Request $request,$id=0,$month = "") {

        $month = $request->month;

        //  １日と最終日を取得
        $firstDay = $compDay = date('Y-m-01', strtotime($month));
        $lastDay = date('Y-m-t', strtotime($month));

        // 指定月の範囲内のデータを取得
        $reservations = Reserve::whereBetween('ReserveDate', [$firstDay, $lastDay])
            ->where('Baseid', $request->id)
            ->orderBy('ReserveDate')->get();
        $dcnt = count($reservations);
        $dix = 0;
        $resvDate = 99;
        if($dcnt > 0) {
            $resv = $reservations[$dix];    //  年月日の日の部分だけを切り出し
            $resvDate = substr($resv->ReserveDate,8,2);
        }

        // 日付情報を読み込み
        $reserveDates = ReserveDate::whereBetween('destDate', [$firstDay, $lastDay])->orderBy('destDate')->get();
        $infoIx = 0;
        $infoCnt = $reserveDates->count();

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

        $DateIx = 0;
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
            if($infoIx < $infoCnt) {
                $destDate = substr($reserveDates[$infoIx]->destDate, 8, 2);
                if($day == $destDate) {     //  同じ日付ならば 反映して次のデータへ
                    $week[$posX]['operating'] = $reserveDates[$infoIx]->operating;
                    $infoIx++;
                }
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
    public function GetCustmerData(Request $request,$tel = "") {

        $type = $request->type;
        $tel = $request->Tel;

        // 指定月の範囲内のデータを取得
        $reservations = Reserve::where('CliTel1', $tel)->orderBy('id', 'desc')->first();
        //$dcnt = count($reservations);
        return response()->json($reservations);
    }

}
