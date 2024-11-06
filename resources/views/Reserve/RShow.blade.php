<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mt-4 p-4">
            <label for="reservation_date">予約日:</label>
            <h1 type="text-lg font-semibold">
                {{ $reserve->ReserveDate }}
            </h1>

            <label for="CliResvCnt">予約人数:</label>
            <p class="mt-4 whitespace-pre-line">
                {{ $reserve->CliResvCnt }}>
            </p>

            <label for="CliResvType">予約タイプ:</label>
            <select id="CliResvType" name="CliResvType" required>
                <option value="1" @if(1 === (int)old('CliResvType')) selected @endif>タイプ1</option>
                <option value="2" @if(2 === (int)old('CliResvType')) selected @endif>タイプ2</option>
            </select><br>
            {{ html()->label('name') }}

            <label for="ClitNameKanji">氏名（漢字）:</label>
            <p class="mt-4 whitespace-pre-line">
                {{ $reserve->ClitNameKanji }}
            </p>

            <label for="ClitNameKana">氏名（カナ）:</label>
            <p class="mt-4 whitespace-pre-line">
                {{ $reserve->ClitNameKana }}
            </p>

            <label for="CliAddrZip">郵便番号:</label>
            <p class="mt-4 whitespace-pre-line">
                {{ $reserve->CliAddrZip }}
            </p>

            <label>住所</label>
            <label for="CliAddrPref">県名:</label>
            <p class="mt-4 whitespace-pre-line">
                {{ $reserve->CliAddrPref }}
            </p>

            <label for="CliAddrCity">市町村名:</label>
            <p class="mt-4 whitespace-pre-line">
                {{ $reserve->CliAddrCity }}
            </p>

            <label for="tel">電話番号:</label>
            <p class="mt-4 whitespace-pre-line">
                {{ $reserve->CliTel1 }}
            </p>

            <label for="CliEMail">メールアドレス:</label>
            <p class="mt-4 whitespace-pre-line">
                {{ $reserve->CliEMail }}
            </p>

            <label for="MessageText">連絡事項:</label>
            <p class="mt-4 whitespace-pre-line">
                {{ $reserve->MessageText }}
            </p>

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
