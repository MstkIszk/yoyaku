<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;   
use App\Http\Controllers\ReserveController;
use App\Http\Controllers\ProfileController;


class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

/**
     * 管理者ログイン画面を表示し、CookieからIDとパスワードを読み込む
     * * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function admin(Request $request)
    {
        // CookieからIDとパスワードを読み込む
        $cookie_id = $request->cookie('remember_id');
        $cookie_password = $request->cookie('remember_password');
        
        // ビューに値を渡す
        return view('auth.admin-login', [
            'cookie_id' => $cookie_id,
            'cookie_password' => $cookie_password,
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): View
    {
        $request->authenticate();

        $request->session()->regenerate();

        //$controller = new ReserveController();
        $controller = new ProfileController();
        return $controller->shopsel($request,Auth::user()->id);       
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
