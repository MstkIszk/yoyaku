@php
    // セッションから表示用データを取得。
    // RConfirmへのリダイレクト時は、コントローラーから直接渡される $validated を使用。
    // 画面リロードなど、セッションからの復元が必要な場合は session('reservation_display_data') を使用。
    $data = $validated ?? session('reservation_display_data', []);

    // データがセッションにない場合はエラー防止のためリダイレクト
    if (empty($data)) {
        return redirect()->route('profile.homelist')->with('error', '予約データが見つかりませんでした。再度予約情報を入力してください。');
    }
@endphp

<x-app-layout>
    Date {{ $data['ReserveDate'] }}

    <x-slot name="header">
        <x-article-title caption="{{ __('Confirm Resavation Entry') }}" />
    </x-slot>

    <div class="py-12">
        <x-controltemfileio nameitem="ClitNameKanji" extension=".yoyaku"></x-controltemfileio>

        <x-message :message="session('message')" />
        <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <form class="h-adr" method="post" action="{{ route('reserve.store') }}">
        {{-- <form class="h-adr" method="post" action="{{ route('reserve.store') }}"> --}}
            @csrf
                <!-- ----------------------------------------------------- -->
                <!-- POSTデータをHiddenフィールドで再送信 -->
                <!-- ----------------------------------------------------- -->
                @foreach ($data as $key => $value)
                    {{-- 表示専用のテキストデータ以外（_textが付かないもの）を送信 --}}
                    @if (!str_ends_with($key, '_text'))
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endif
                @endforeach
                <!-- Hidden: 連絡事項 (textareaのvalueは$dataから取得) -->
                <input type="hidden" name="MessageText" value="{{ $data['MessageText'] ?? '' }}">



                <x-rTextbox name="CliTel1"       type="label" value="{{$data['CliTel1']}}">{{ __('phone') }}</x-rTextbox>
                <x-rTextbox name="ReserveDate"   type="label" value="{{$data['ReserveDate']}}">{{ __('Reserve date') }}</x-rTextbox>
                <x-rTextbox name="CliResvCnt"    type="label"  value="{{$data['CliResvCnt']}}" >{{ __('Reserve count') }}</x-rTextbox>
                <x-rTextbox name="CliResvType"   type="label"  value="{{$data['CliResvType_text'] ?? $data['CliResvType']}}" >{{ __('Reserve type') }}</x-rTextbox>
                <x-rTextbox name="ClitNameKanji" type="label" value="{{$data['ClitNameKanji']}}">{{ __('Name Kanji') }}</x-rTextbox>
                <x-rTextbox name="ClitNameKana"  type="label" value="{{$data['ClitNameKana']}}">{{ __('Name Kana') }}</x-rTextbox>
                <x-rTextbox name="CliAddrZip"    type="label" value="{{$data['CliAddrZip']}}">{{ __('postal code') }}</x-rTextbox>

                <label>住所</label>
                <x-rTextbox name="CliAddrPref"   type="label"  value="{{$data['CliAddrPref']}}">{{ __('province') }}</x-rTextbox>
                <x-rTextbox name="CliAddrCity"   type="label"  value="{{$data['CliAddrCity']}}">{{  __('municipality')  }}:</x-rTextbox>
                <x-rTextbox name="CliAddrOther"  type="label"  value="{{$data['CliAddrOther']}}">{{ __('village') }}</x-rTextbox>
                <x-rTextbox name="CliEMail"      type="label" value="{{$data['CliEMail']}}">{{ __('Email') }}</x-rTextbox>
                <x-rTextbox name="CliWaysPay"    type="label" value="{{$data['CliWaysPay_text'] ?? $data['CliWaysPay']}}">{{ __('WaysPay') }}</x-rTextbox>

                <x-rTextarea id="MessageText" name="MessageText" value="{{$data['MessageText']}}">{{ __('MessageText') }}</x-rTextarea><br>

                <!-- ----------------------------------------------------- -->
                <!-- ボタンエリア -->
                <!-- ----------------------------------------------------- -->
                <div class="mt-8 flex justify-center space-x-6">
                    <x-primary-button>確認</x-primary-button>
                    <x-back-button>戻る</x-back-button>
                </div>


            </form>
       </div>
    </div>
</x-app-layout>
