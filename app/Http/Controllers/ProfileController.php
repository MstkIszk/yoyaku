<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use App\Models\User; // User モデルをインポート
use App\Models\UserProduct;

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
        $formattedZipCode = zipCodeFormat($request->baseAddrZip);
        $formattedTel1 = telNoFormat($request->baseTel1); // デフォルトは元の値
        $formattedTel2 = telNoFormat($request->baseTel2); // デフォルトは元の値
        $ResvTypeBit = 0;
        if($request->has('spResvType')){
            $ResvTypeBit = $request->spResvType;
        }

        $user->spName = $request->baseNameKanji;
        $user->spNameKana = $request->baseNameKana;
        $user->spAddrZip = $formattedZipCode; // ハイフン付き郵便番号に対応
        $user->spAddrPref = $request->baseAddrPref;
        $user->spAddrCity = $request->baseAddrCity;
        $user->spAddrOther = $request->baseAddrOther;
        $user->spTel1 = $formattedTel1;
        $user->spTel2 = $formattedTel2;
        $user->spEMail = $request->baseEMail;
        $user->spURL = $request->baseURL;
        $user->spResvType = $ResvTypeBit;
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
     * トップページ（'/' ルート）からのアクセスを処理し、
     * Cookieがあれば自動ログインと予約カレンダーへのリダイレクトを行う。
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function homelist(Request $request)
    {
        //  roleがadmin以外のデータ を抽出
        $shops = User::where('role', '!=', 'admin')->get();

        //    取得したデータをViewに渡す
        return view('welcome', compact('shops'));
    }

    //  店舗の情報と予約情報を表示
    public function shopsel(Request $request,$shop_id) {

        if($shop_id == 0) {
            return redirect()->route('profile.homelist');
        }

        // id = $shop_id のデータ（一般ユーザー、つまり店舗）を抽出し、
        // 関連する商品データ (app\Models\User.php::products) も同時に取得する
        $ShopInf = User::with('products', 'accessories')
                        ->where('id',  $shop_id)
                        ->first();

        // データが見つからなかった場合の処理
        if (!$ShopInf) {
            //  404エラーを表示、またはリダイレクト
            abort(404);
        }                        
        session(['ShopID' => $shop_id]); // 選択された店舗のIDを保存
        return view("auth.showuser",compact('ShopInf'));
    }
    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function reserveList(Request $request):  RedirectResponse|View
    {
        // ログインチェック
        if (!Auth::check()) {
            return redirect('/login')->with('error', '予約一覧を表示するにはログインが必要です。');
        }

        $BaseShopID = Auth::id();

        // 検索フォームの初期値設定
        $filterCliTel1 = $request->old('CliTel1', '');
        $filterProductID = (int) $request->old('ProductID', 0);
        $filterDateStart = $request->old('DateStart');
        $filterDateEnd = $request->old('DateEnd');

        // 商品リストの取得 (ドロップダウン用)
        $products = UserProduct::where('baseCode', $BaseShopID)
                            ->where('IsEnabled', 1)
                            ->orderBy('productID')
                            ->get(['productID', 'productName']);

        // Viewを返す。データはJavaScriptで取得するため、ここではデータは渡さない。
        return view("Reserve.RIndex", compact(
            'BaseShopID',
            'products',
            'filterCliTel1',
            'filterProductID',
            'filterDateStart',
            'filterDateEnd'
        ));
    }
}
