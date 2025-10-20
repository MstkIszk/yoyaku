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
            'baseNameKanji' => ['required', 'string', 'max:50'],
            'baseNameKana' => ['required', 'string', 'max:50'],
            'baseAddrZip' => ['required', 'string', 'max:8'],
            'baseAddrPref' => ['required', 'string', 'max:255'],
            'baseAddrCity' => ['required', 'string', 'max:255'],
            'baseAddrOther' => ['required', 'string', 'max:255'],
            'baseTel1' => ['required', 'string', 'max:255'],
            'baseTel2' => ['max:255'],
            'baseEMail' => ['required', 'string', 'lowercase', 'email', 'max:255'],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->baseEMail,
            'password' => Hash::make($request->password),
            'spName' => $request->baseNameKanji,
            'spNameKana' => $request->baseNameKana,
            'spCode' => 1,
            'spAddrZip' => $request->baseAddrZip,
            'spAddrPref' => $request->baseAddrPref,
            'spAddrCity' => $request->baseAddrCity,
            'spAddrOther' => $request->baseAddrOther,
            'spTel1' => $request->baseTel1,
            'spTel2' => $request->baseTel2??'',
            'spEMail' => $request->baseEMail,
            'spURL' => $request->baseURL??'',
            "spMsgText" => $request->MessageText??'',
            
        ]);

        //event(new Registered($user));

        Auth::login($user);
        session(['ShopID' => $user->id]); // 選択された店舗のIDを保存

        //return redirect(RouteServiceProvider::HOME);
        //  店舗のトップ画面へ
        return redirect()->route('reserve.calender', ['id' => $user->id]);
    }
}
