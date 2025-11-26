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
use Illuminate\Validation\Rule;

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
            'spResvType' => $request->spResvType??0,
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
/**
     * 認証済みユーザーの情報を更新する
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request): RedirectResponse
    {
        // 現在認証されているユーザーを取得
        $user = Auth::user();

        // バリデーションルールを定義
        $request->validate([
            // spName (店舗名:漢字) の一意性チェック。ただし、自分自身は除外する
            'baseNameKanji' => [
                'required', 
                'string', 
                'max:50', 
                Rule::unique('users', 'spName')->ignore($user->id),
            ],
            'baseNameKana' => ['required', 'string', 'max:50'],
            'baseAddrZip' => [
                'required', 
                'string', 
                'max:8',
                'regex:/^(\d{7}|\d{3}-\d{4})$/', 
            ],
            'baseAddrPref' => ['required', 'string', 'max:255'],
            'baseAddrCity' => ['required', 'string', 'max:255'],
            'baseAddrOther' => ['required', 'string', 'max:255'],

            // spTel1 (電話番号1) の一意性チェック。ただし、自分自身は除外する
            'baseTel1' => [
                'required', 
                'string', 
                'max:255',
                'regex:/^0\d{1,4}[-]?\d{1,4}[-]?\d{4}$/', 
                Rule::unique('users', 'spTel1')->ignore($user->id),
            ],
            'baseTel2' => ['nullable', 'string', 'max:255'],
            
            // baseEMail (メールアドレス) の一意性チェック。ただし、自分自身は除外する
            'baseEMail' => [
                'required', 
                'string', 
                'lowercase', 
                'email', 
                'max:255', 
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'baseURL' => ['nullable', 'url', 'max:255'],
            'spResvType' => ['nullable', 'in:1'], // チェックボックスの値
            'MessageText' => ['nullable', 'string'],
            
            // NOTE: パスワードの変更ロジックは今回は含んでいません。
            // 必要であれば、別途パスワード入力があった場合のバリデーションを追加します。
        ]);

        // データ整形 (store関数から流用)
        $formattedZipCode = zipCodeFormat($request->baseAddrZip);
        $formattedTel1 = telNoFormat($request->baseTel1); 
        $formattedTel2 = telNoFormat($request->baseTel2); 
        
        // ユーザー情報を更新
        $user->update([
            // 'name' は edituser.blade.php にないため、更新対象から外す
            'email' => $request->baseEMail,
            'spName' => $request->baseNameKanji,
            'spNameKana' => $request->baseNameKana,
            'spAddrZip' => $formattedZipCode,
            'spAddrPref' => $request->baseAddrPref,
            'spAddrCity' => $request->baseAddrCity,
            'spAddrOther' => $request->baseAddrOther,
            'spTel1' => $formattedTel1,
            'spTel2' => $formattedTel2??'',
            'spEMail' => $request->baseEMail, // emailと同じ値をspEMailにも更新
            'spURL' => $request->baseURL??'',
            
            // spResvType (ビットマスク) は、チェックボックスがONであれば 1、そうでなければ 0 にする
            'spResvType' => $request->has('spResvType') ? 1 : 0, 
            "spMsgText" => $request->MessageText??'',
        ]);

        // 更新後、ダッシュボードに戻る
        return redirect()->route('dashboard')->with('success', 'ユーザー情報が正常に更新されました。');
    }    
}
