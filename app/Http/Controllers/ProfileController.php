<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User; // User モデルをインポート

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
   /**
     * Handle an incoming authentication request.
     */
    public function userinfedit()
    {
        return view('profile.edit');
    }

    public function userinfupdate(Request $request): RedirectResponse  // 更新処理
    {
        $user = Auth::user();

        $request->validate([ // バリデーション
            'baseNameKanji' => ['required', 'string', 'max:255'],
            'baseNameKana' => ['required', 'string', 'max:255'],
            'baseAddrZip' => ['required', 'string', 'max:8'],
            'baseAddrPref' => ['required', 'string', 'max:255'],
            'baseAddrCity' => ['required', 'string', 'max:255'],
            'baseAddrOther' => ['required', 'string', 'max:255'],
            'baseTel1' => ['required', 'string', 'max:255'],
            'baseTel2' => ['max:255'],
        ]);

        $user->spName = $request->baseNameKanji;
        $user->spNameKana = $request->baseNameKana;
        $user->spAddrZip = $request->input('p-postal-code'); // ハイフン付き郵便番号に対応
        $user->spAddrPref = $request->baseAddrPref;
        $user->spAddrCity = $request->baseAddrCity;
        $user->spAddrOther = $request->baseAddrOther;
        $user->spTel1 = $request->baseTel1;
        $user->spTel2 = $request->baseTel2;
        $user->spEMail = $request->baseEMail;
        $user->spURL = $request->baseURL;
        $user->spMsgText = $request->MessageText;
        $user->save();

        return redirect()->route('profile.edit')->with('status', 'profile-updated'); // リダイレクト
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    /**
     * home画面で店舗一覧を表示.
     */
    public function homelist(Request $request)
    {
        //  roleがadmin以外のデータ を抽出
        $shops = User::where('role', '!=', 'admin')->get();

        //    取得したデータをViewに渡す
        return view('welcome', compact('shops'));
    }
}
