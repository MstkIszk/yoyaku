<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [   //  ディフォルトで有効な
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class, 
        \Illuminate\Http\Middleware\HandleCors::class,   // クロスオリジンリクエストを許可
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,    // メンテナンスモードでのリクエストを防ぐ
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,     // フォームのサイズを検証
        \App\Http\Middleware\TrimStrings::class,    // 文字列のトリム
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [ // 指定したグループで有効なミドルウェア
        'web' => [
            \App\Http\Middleware\EncryptCookies::class, // クッキーを暗号化
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class, // クッキーを追加
            \Illuminate\Session\Middleware\StartSession::class, // セッションを開始
            \Illuminate\View\Middleware\ShareErrorsFromSession::class, // エラーを共有
            \App\Http\Middleware\VerifyCsrfToken::class, // CSRFトークンを検証  
            \Illuminate\Routing\Middleware\SubstituteBindings::class, // バインディングを置換   
        ],

        'api' => [
            // \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api', // リクエストを制限
            \Illuminate\Routing\Middleware\SubstituteBindings::class, // バインディングを置換
        ],
    ];

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class, // 認証
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class, // 基本認証
        'auth.session' => \Illuminate\Session\Middleware\AuthenticateSession::class, // セッション認証
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class, // キャッシュヘッダーを設定
        'can' => \Illuminate\Auth\Middleware\Authorize::class, // 承認
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class, // 未認証の場合リダイレクト
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class, // パスワードの確認
        'precognitive' => \Illuminate\Foundation\Http\Middleware\HandlePrecognitiveRequests::class, // 事前リクエストを処理
        'signed' => \App\Http\Middleware\ValidateSignature::class, // シグネチャを検証
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class, // リクエストを制限   
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class, // メールを確認
        'admin' => \App\Http\Middleware\RoleMiddleware::class, // ロールミドルウェア
    ];
}
