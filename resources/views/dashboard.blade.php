<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{ __("You're logged in!") }}
            </div>


            <a href="{{ route('profile.edit') }}" class="link-button">
                登録情報変更
            </a>

            <a href="{{ route('user_products.create') }}" class="link-button">
                商品登録
            </a>


        </div>
    </div>
    
</x-app-layout>
