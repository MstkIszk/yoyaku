<?php
//  

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReserveBase;

class ReserveBaseController extends Controller
{
    public function create(Request $request,$ReqType=0) {  //  店舗新規登録

        return view("Reserve.BaseCreate",compact('ReqType'));
    }
    
    //
    public function store(Request $request) {
        //  ガード文字列
        //          0         1         2         3         4         5         6         
        $MasterKeyStr = 'AerD#$HjaiouAp@o|&YU%#jkTFG&Hiuwe)(aBng>OP_K&kljkA?OUf;=SgF:E%FdYBuY#Etx';
        $KeyStr = substr($MasterKeyStr,date("s"),10);

        $newOrderNo = ReserveBase::max('OrderNo') + 1;
        $today = date('Y-m-d');

        $randomNumber = mt_rand(1000, 9999);

        // バリデーションを通過した場合、予約処理を行う
        if($request->validate([
            'basetNameKanji' =>  'required',    //  氏名漢字
            'basetNameKana'  =>  'required',     //  氏名カナ
            'baseAddrZip'    =>  'required|postal_code',       //  郵便番号
            'baseTel1'       =>  'required|phone_number',          //  電話番号
            'baseTel2'       =>  'phone_number',          //  電話番号
            'baseEMail'      =>  'required|email',         //  メールアドレス
            'baseURL'        =>  'url',         //  メールアドレス
            'baseResvType'   =>  'required',      //  予約タイプ
        ],
        [
            'basetNameKanji.required' =>  '店名を入力してください',    //  氏名漢字
            'basetNameKana.required'  =>  'ヨミガナを入力してください',     //  氏名カナ
            'baseAddrZip.required'    =>  '郵便番号を入力してください',       //  郵便番号
            'baseAddrZip.postal_code' =>  '郵便番号の形式が不正です',       //  郵便番号
            'baseTel1.required'       =>  '電話番号を指定してください',          //  電話番号１
            'baseTel1.phone_number'   =>  '電話番号の形式が不正です',          //  電話番号１
            'baseTel2.phone_number'   =>  '電話番号の形式が不正です',          //  電話番号２
            'baseEMail.required'      =>  'メールアドレスを指定してください',         //  メールアドレス
            'baseEMail.email'         =>  'メールアドレスの形式が不正です',         //  メールアドレス
            'baseURL.url'             =>  'URLの形式が不正です',         //  メールアドレス
        ]
        )) {
            $reserve = ReserveBase::create([
                'spNameKanji'   =>  $request->basetNameKanji,    //  氏名漢字
                'spNameKana'    =>  $request->basetNameKana,     //  氏名カナ
                'spAddrZip'     =>  $request->baseAddrZip,       //  郵便番号
                'spAddrPref'    =>  $request->baseAddrPref,      //  県名
                'spAddrCity'    =>  $request->baseAddrCity,      //  市町村名
                'spAddrOther'   =>  $request->baseAddrOther,      //  地域名
                'spTel1'        =>  $request->baseTel1,          //  電話番号
                'spTel2'        =>  $request->baseTel1,          //  電話番号
                'spEMail'       =>  $request->baseEMail,         //  メールアドレス
                'spURL'         =>  $request->baseURL,         //  メールアドレス
            ]);

            //  一覧のページに戻る
            return redirect()->route('reserve.index');
        }
        return back();        
    }
}
