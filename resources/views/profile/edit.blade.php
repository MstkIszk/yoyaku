<!-- 店舗登録画面 -->
<x-app-layout>
    <!-- app\Http\Controllers\ProfileController.php -->
    <!--└「resources\views\profile\edit.blade.php」---START -->
    <x-slot name="header">
        <x-article-title caption="{{ __('Profile') }}" />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- プロフィール情報更新 -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <form action="{{ route('user_products.create') }}" method="GET">
                
                <button type="submit">新規商品登録</button>
            </form>

            <!-- パスワード更新 -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <!-- アカウント削除 -->
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
    <!--「resources\views\profile\edit.blade.php」---END -->
</x-app-layout>
