<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use App\Models\Reserve;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'baseNameKanji' => ['required', 'string', 'max:50', 'unique:users,spName'],
            'baseNameKana' => ['required', 'string', 'max:50'],
            'baseAddrZip' => [
                'required', 
                'string', 
                'max:8',
                // 7桁の数字、または 3桁-4桁 の形式を許可
                'regex:/^(\d{7}|\d{3}-\d{4})$/', 
            ],
            'baseAddrPref' => ['required', 'string', 'max:255'],
            'baseAddrCity' => ['required', 'string', 'max:255'],
            'baseAddrOther' => ['required', 'string', 'max:255'],

            'baseTel1' => [  // 日本の電話番号形式をチェック
                'required', 
                'string', 
                'max:255',
                // 日本の電話番号 (0から始まり、ハイフンあり/なし両方に対応)
                // 例: 090-xxxx-xxxx, 03-xxxx-xxxx, 050-xxxx-xxxx
                'regex:/^0\d{1,4}[-]?\d{1,4}[-]?\d{4}$/', 
                'unique:users,spTel1',
            ],

            'baseTel2' => ['max:255'],
            'baseEMail' => ['required', 'string', 'lowercase', 'email', 'max:255'],
        ]);

        // baseAddrZip: 数字7桁または3桁-4桁の形式から、強制的に 3桁-4桁 に整形
        $formattedZipCode = zipCodeFormat($request->baseAddrZip);
        $formattedTel1 = telNoFormat($request->baseTel1); // デフォルトは元の値
        $formattedTel2 = telNoFormat($request->baseTel2); // デフォルトは元の値

        $user = User::create([
            'name' => $request->name,
            'email' => $request->baseEMail,
            'password' => Hash::make($request->password),
            'spName' => $request->baseNameKanji,
            'spNameKana' => $request->baseNameKana,
            'spCode' => 1,
            'spAddrZip' => $formattedZipCode,
            'spAddrPref' => $request->baseAddrPref,
            'spAddrCity' => $request->baseAddrCity,
            'spAddrOther' => $request->baseAddrOther,
            'spTel1' => $formattedTel1,
            'spTel2' => $formattedTel2??'',
            'spEMail' => $request->baseEMail,
            'spURL' => $request->baseURL??'',
            "spMsgText" => $request->MessageText??'',
        ]);

        //event(new Registered($user));

        Auth::login($user);
        session(['ShopID' => $user->id]); // 選択された店舗のIDを保存

        //return redirect(RouteServiceProvider::HOME);
        //  店舗のトップ画面へ
        //return redirect()->route('reserve.calender', ['id' => $user->id]);
        return redirect()->route('dashboard')->with('success', '店舗情報が正常に登録されました。');
    }
}
