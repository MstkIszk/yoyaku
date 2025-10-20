<x-app-layout>
    <x-slot name="header">
        <x-article-title caption="{{ __('Resavation Edit') }}" />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            Reserve {{ $reserve }} OK<br>

            <form method="post" action="{{ route('reserve.update',$reserve) }}">
                @csrf
                @method('patch');

                <label for="reservation_date">予約日:</label>
                <input type="datetime-local" id="ReserveDate" name="ReserveDate" required 
                        value="{{ old('ReserveDate',$reserve->ReserveDate) }}"><br>

                <label for="CliResvCnt">予約人数:</label>
                <input type="number" id="CliResvCnt" name="CliResvCnt" required
                    value="{{ old('CliResvCnt',$reserve->CliResvCnt) }}"><br>

                <label for="CliResvType">予約タイプ:</label>
                <select id="CliResvType" name="CliResvType" required>
                    <option value="1" {{ old('CliResvType', $reserve->CliResvType) == '1' ? 'selected' : '' }}>タイプ1</option>
                    <option value="2" {{ old('CliResvType', $reserve->CliResvType) == '2' ? 'selected' : '' }}>タイプ2</option>
                </select><br>

                <label for="ClitNameKanji">氏名（漢字）:</label>
                <input type="text" id="ClitNameKanji" name="ClitNameKanji" required
                    value="{{ old('ClitNameKanji',$reserve->ClitNameKanji) }}"><br>

                <label for="ClitNameKana">氏名（カナ）:</label>
                <input type="text" id="ClitNameKana" name="ClitNameKana" required
                    value="{{ old('ClitNameKana',$reserve->ClitNameKana) }}"><br>

                <label for="CliAddrZip">郵便番号:</label>
                <input type="text" id="CliAddrZip" name="CliAddrZip" required
                    value="{{ old('CliAddrZip',$reserve->CliAddrZip) }}"><br>

                <label>住所</label>
                <label for="CliAddrPref">県名:</label>
                <input type="text" id="CliAddrPref" name="CliAddrPref" required
                    value="{{ old('CliAddrPref',$reserve->CliAddrPref) }}"><br>

                <label for="CliAddrCity">市町村名:</label>
                <input type="text" id="CliAddrCity" name="CliAddrCity" required
                    value="{{ old('CliAddrCity',$reserve->CliAddrCity) }}"><br>

                <label for="CliAddrOther">市町村名:</label>
                <input type="text" id="CliAddrOther" name="CliAddrOther" required
                    value="{{ old('CliAddrOther',$reserve->CliAddrCity) }}"><br>

                <label for="tel">電話番号:</label>
                <input type="tel" id="CliTel1" name="CliTel1" required
                    value="{{ old('CliTel1',$reserve->CliTel1) }}"><br>

                <label for="CliEMail">メールアドレス:</label>
                <input type="email" id="CliEMail" name="CliEMail" required
                    value="{{ old('CliEMail',$reserve->CliEMail) }}"><br>

                <label for="MessageText">連絡事項:</label>
                <textarea id="MessageText" name="MessageText">
                    {{ old('MessageText',$reserve->MessageText) }}
                </textarea><br>


                <fieldset id="WayPay">
                    <legend>支払方法</legend>
                    <input id="item-p1" type="radio" name="CliWaysPay" value="1" {{ old('CliWaysPay',$reserve->CliWaysPay ) == '1' ? 'checked' : '' }}/>
                    <label for="item-p1">代金引換</label>

                    <input id="item-p2" type="radio" name="CliWaysPay" value="2" {{ old('CliWaysPay',$reserve->CliWaysPay ) == '2' ? 'checked' : '' }}/>
                    <label for="item-p2">銀行振り込み</label>

                    <input id="item-3" type="radio" name="CliWaysPay" value="3" {{ old('CliWaysPay',$reserve->CliWaysPay ) == '3' ? 'checked' : '' }}/>
                    <label for="item-3">PayPay支払</label>
                    ←選択してください<br>
                </fieldset>




                

                <x-primary-button class="mt-4">
                    更新実行
                </x-primary-button>
            </form>
       </div>
    </div>
</x-app-layout>
