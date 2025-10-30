<?php

namespace App\Http\Controllers;

use App\Models\ReserveDate;
use Illuminate\Http\Request;

class ReserveDateController extends Controller
{
    public function store(Request $request) {

        //  登録日時
        $ReqDate = date("Y-m-d H:i:s");

        $randomNumber = mt_rand(1000, 9999);

        // バリデーションを通過した場合、予約処理を行う
        if($request->validate([
            'baseCode' =>  'required',    //  対象の店舗コード
            'eigyotype' => 'required',    //  営業タイプ
            'destDate' =>  'required',    //  対象日付
            'operating' => 'required',    //  営業状態');    // 1:通常営業・2:休業・3:貸し切り
            'capacity' =>  'required',    //  '定員');        //  定員
            'yoyakusu' =>  'required',    //  '予約人数');     //  ネット予約以外の予約人数
        ],
        [
            'ReserveDate.required'   =>  '対象日付を指定してください',      //  予約日
        ]
        )) {
            $reserve = ReserveDate::create([
                'baseCode'  =>  $request->baseCode,
                'productID' =>  $request->productID,
                'eigyotype' =>  $request->eigyotype,
                'destDate'  =>  $request->ReserveDate,      //  予約日
                'operating' =>  $request->operating,
                'capacity'  =>  $request->capacity,
                'yoyakusu'  =>  $request->yoyakusu,
                'memo'      =>  $request->memo
            ]);


            return redirect()->route('reserve.index');
        }
        return back();        
    }

    
    public function readDateInfo(Request $request) {

        //$dcnt = count($reservations);
        $destDate = $request->destDate . " 00:00:00";
        //  ディフォルトとなる値を読み込み
        $json_string = '{
                        "id":-1,
                        "baseCode": "' . $request->baseCode . '", 
                        "productID": "' . $request->productID . '",
                        "eigyotype": "' . $request->eigyotype . '",
                        "destDate": "' . $request->destDate . '",
                        "operating": 0,
                        "capacity": 15, 
                        "yoyakusu": 0, 
                        "memo": ""
                        }';
        $data = json_decode($json_string, true);
        $baseCode = (int)$request->baseCode;
        $productID = (int)$request->productID;
        // 指定された予約日、Base ID、Product IDに一致する予約可能情報を取得
        $reservations = ReserveDate::whereRaw('DATE(destDate) = ?', [substr($destDate, 0, 10)])
            ->where('baseCode', $baseCode )      // baseCodeの条件を追加
            ->where('productID', $productID) // productIDの条件を追加
            ->first();

        if($reservations) {
        //if($dcnt > 0) {
            //  見つかったら JSONの該当項目を書き換え
            $data['id'] = $reservations->id;      
            $data['operating'] = $reservations->operating;      
            $data['capacity'] = $reservations->capacity;
            $data['yoyakusu'] = $reservations->yoyakusu;
            $data['memo'] = $reservations->memo;
        }

        return response()->json($data);
    }

    public function writeDateInfo(Request $request,$status = null) {
            
        $data = [
            'baseCode'   => $request->baseCode,
            'productID'  => $request->productID,
            'eigyotype'  => $request->eigyotype,
            'destDate'   => $request->destDate,
            'operating'  => $request->operating,
            'capacity'   => $request->capacity,
            'yoyakusu'   => $request->yoyakusu,
            'memo'       => $request->memo, // memo が存在する場合
        ];
        $status = 0;
        if($request->id == "-1") {
            //  新規追加
            ReserveDate::create( $data);
        }
        else {
            // 1. IDでレコードを検索して更新
            $reserveDate = ReserveDate::find($request->id);
            if ($reserveDate) {
                $reserveDate->update($data);
                return response()->json(['message' => '予約情報を更新しました。']);
            } else {
                return response()->json(['message' => '指定されたIDの予約情報が見つかりませんでした。'], 404); // 404 Not Found            
            }
        }
        return response()->json(['message' => '予約情報を更新・追加しました。']);
    }
}
