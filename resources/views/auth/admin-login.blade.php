<x-guest-layout>
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{-- formのactionを /login に設定し、IDとパスワードを保持するCookieを設定するJS関数を呼び出す --}}
    <form method="POST" action="{{ route('login') }}" onsubmit="saveCredentialsToCookie(this); return true;">
        @csrf

        {{-- Cookieの値があればそれを表示し、なければ old('name') を表示 --}}
        <x-rTextbox name="name" type="text" value="{{ old('name', $cookie_id ?? '') }}" >{{ __('UserID') }}</x-rTextbox>
        
        {{-- Cookieの値があればそれを表示し、なければ old('password') を表示 --}}
        <x-rTextbox name="password" type="password" value="{{ old('password', $cookie_password ?? '') }}" >{{ __('Password') }}</x-rTextbox>

        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>

<script>
    /**
     * フォーム送信前にIDとパスワードをCookieに保存する
     * @param {HTMLFormElement} form
     */
    function saveCredentialsToCookie(form) {
        const id = form.elements.name.value;
        const password = form.elements.password.value;
        const rememberMe = form.elements.remember.checked;

        // Cookieの有効期限を設定 (例: 30日)
        const expiryDate = new Date();
        expiryDate.setDate(expiryDate.getDate() + 30);
        const expires = "; expires=" + expiryDate.toUTCString();
        
        // 🚨 注意: パスワードをCookieに平文で保存するのはセキュリティリスクがあります。
        // 通常はRemember Meトークンのみを保存します。
        
        // ユーザーIDをCookieに保存
        document.cookie = "remember_id=" + encodeURIComponent(id) + expires + "; path=/";

        // パスワードを保存 (Remember Meチェック時のみ、または常に保存)
        if (rememberMe) {
             document.cookie = "remember_password=" + encodeURIComponent(password) + expires + "; path=/";
        } else {
             // Remember Meチェックがない場合はCookieを削除
             document.cookie = "remember_password=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        }
        
        // その後、フォームは action="{{ route('login') }}" へ遷移します。
        return true; 
    }
</script>
