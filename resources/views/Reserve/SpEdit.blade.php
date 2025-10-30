<x-app-layout>
    <x-slot name="header">
        <x-article-title caption="{{ __('Dashboard') }}" />
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            Reserve {{ $reserve }} OK<br>

            <form method="post" action="{{ route('reserve.shopupdate',$reserve) }}">
                @csrf
                @method('patch');

                <label for="SpNameKanji">店名(漢字):</label>
                <input type="text" id="SpNameKanji" name="SpNameKanji" required
                    value="{{ old('SptNameKanji',$reserve->SptNameKanji) }}"><br>

                <label for="SpNameKana">氏名(カナ):</label>
                <input type="text" id="SpNameKana" name="SpNameKana" required
                    value="{{ old('SpNameKana',$reserve->SptNameKana) }}"><br>

                <label for="SpAddrZip">郵便番号:</label>
                <input type="text" id="SpAddrZip" name="SpAddrZip" required
                    value="{{ old('SpAddrZip',$reserve->SpAddrZip) }}"><br>

                <label>住所</label>
                <label for="SpAddrPref">県名:</label>
                <input type="text" id="SpAddrPref" name="SpAddrPref" required
                    value="{{ old('SpAddrPref',$reserve->SpAddrPref) }}"><br>

                <label for="SpAddrCity">市町村名:</label>
                <input type="text" id="SpAddrCity" name="SpAddrCity" required
                    value="{{ old('SpAddrCity',$reserve->SpAddrCity) }}"><br>

                <label for="SpAddrOther">その他:</label>
                <input type="text" id="SpAddrOther" name="SpAddrOther" required
                    value="{{ old('SpAddrOther',$reserve->SpAddrCity) }}"><br>

                <label for="tel">電話番号:</label>
                <input type="tel" id="SpTel1" name="SpTel1" required
                    value="{{ old('SpTel1',$reserve->SpTel1) }}"><br>

                <label for="SpEMail">メールアドレス:</label>
                <input type="email" id="SpEMail" name="SpEMail" required
                    value="{{ old('SpEMail',$reserve->SpEMail) }}"><br>

                <label for="MessageText">連絡事項:</label>
                <textarea id="MessageText" name="MessageText">
                    {{ old('MessageText',$reserve->MessageText) }}
                </textarea><br>


                <fieldset id="WayPay">
                    <legend>支払方法</legend>
                    <input id="item-p1" type="radio" name="SpWaysPay" value="1" {{ old('SpWaysPay',$reserve->SpWaysPay ) == '1' ? 'checked' : '' }}/>
                    <label for="item-p1">代金引換</label>

                    <input id="item-p2" type="radio" name="SpWaysPay" value="2" {{ old('SpWaysPay',$reserve->SpWaysPay ) == '2' ? 'checked' : '' }}/>
                    <label for="item-p2">銀行振り込み</label>

                    <input id="item-3" type="radio" name="SpWaysPay" value="3" {{ old('SpWaysPay',$reserve->SpWaysPay ) == '3' ? 'checked' : '' }}/>
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
