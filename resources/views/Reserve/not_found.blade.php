<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{  __('request error') }}
        </h2>
    </x-slot>

    <div class="session_message_area">
        {{-- コントローラから渡された $message を表示 --}}
        <p class="error">
            @isset($message)
                {{ $message }}
            @else
                指定された予約が見つかりません。
            @endisset
        </p>
    </div>
    
    <div class="back-button">
        <a href="{{ route('profile.homelist') }}" class="text-blue-500 hover:underline">トップページに戻る</a>
    </div>
</x-app-layout>
