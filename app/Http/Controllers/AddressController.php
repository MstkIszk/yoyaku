<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends Controller
{
    public function fetchAddress(Request $request)
    {
        // 郵便番号の整合性チェック
        $postalCode = $request->input('postalCode');
        if (!isValidPostalCode($postalCode)) {
            return response()->json(['error' => 'Invalid postal code'], 400);
        }

        // API 呼び出し
        $client = new \GuzzleHttp\Client();
        $response = $client->request(‘GET’, config('app.api_endpoint') . '/' . $postalCode);
        $data = json_decode($response->getBody(), true);

        // 住所情報オブジェクトを作成
        $address = new Address();
        $address->prefecture = $data['refecture'];
        $address->city = $data['city'];
        $address->town = $data['town'];
        $address->street = $data['street'];

        // 住所情報オブジェクトを保存
        $address->save();

        // 住所情報オブジェクトを返す
        return response()->json($address);
    }
}

