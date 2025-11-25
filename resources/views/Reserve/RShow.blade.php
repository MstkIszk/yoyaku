<!-- 予約の詳細 -->
<x-app-layout>
    <x-slot name="header">
        <x-article-title caption="{{ __('Reserve show') }}" />
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mt-4 p-4">
            <x-rTextbox name="ReserveDate"   type="label" value="{{ $reserve->ReserveDate }}">{{  __('Resarve date') }}</x-rTextbox>
            <x-rTextbox name="CliResvCnt"    type="label"  value="{{ $reserve->CliResvCnt }}" >{{  __('Resarve count') }}</x-rTextbox>

            @php
                $SelectedItem = '未定義';
            @endphp
            @foreach ($YoyakuTypeList as $item)
                @if ($item->id === $reserve->CliResvType)
                    {{-- 目的の値が見つかった --}}
                    @php
                        $SelectedItem = $item->courseName;
                    @endphp
                    @break
                @endif
            @endforeach
            <x-rTextbox name="CliResvType"   type="label"  value="{{ $SelectedItem }}" >{{  __('Resarve type') }}</x-rTextbox>

            {{ html()->label('name') }}

            <x-rTextbox name="ClitNameKanji" type="label" value="{{ $reserve->ClitNameKanji }}">{{  __('Name Kanji') }}</x-rTextbox>
            <x-rTextbox name="ClitNameKana"  type="label" value="{{ $reserve->ClitNameKana }}">{{  __('Name Kana') }}</x-rTextbox>
            <x-rTextbox name="CliAddrZip"    type="label" value="{{ $reserve->CliAddrZip }}">{{  __('Address Zip') }}</x-rTextbox>
            <label>住所</label>
            <x-rTextbox name="CliAddrPref"   type="label"  value="{{ $reserve->CliAddrPref }}">{{  __('Address Pref') }}</x-rTextbox>
            <x-rTextbox name="CliAddrCity"   type="label"  value="{{ $reserve->CliAddrCity }}">{{  __('Address City') }}</x-rTextbox>
            <x-rTextbox name="CliAddrOther"  type="label"  value="{{ $reserve->CliAddrOther }}">{{  __('Address Other') }}</x-rTextbox>
            <x-rTextbox name="CliTel1"       type="label" value="{{ $reserve->CliTel1 }}">{{  __('Phone') }}:</x-rTextbox>
            <x-rTextbox name="CliEMail"      type="label" value="{{ $reserve->CliEMail }}">{{  __('Email') }}:</x-rTextbox>

            @foreach ($WaysPayList as $item)
                @if ($item->id === $reserve->CliWaysPay)
                    <x-rTextbox name="CliWaysPay"    type="label" value="{{ $item->PrName }}">{{  __('WaysPay') }}</x-rTextbox>
                    @break
                @endif
            @endforeach

            <x-rTextarea name="MessageText" attr="label" msgText="{{$reserve->MessageText}}">{{  __('MessageText') }}</x-rTextarea><br>
            <a href="{{route('reserve.edit',$reserve)}}">
                <x-primary-button>
                    編集
                </x-primary-button>
            </a>

            <form method="post" action="{{route('reserve.destroy',$reserve)}}" class="flex-2">
                @csrf
                @method('delete')
                <x-primary-button>
                    削除
                </x-primary-button>
            </form>

       </div>
    </div>
</x-app-layout>
 