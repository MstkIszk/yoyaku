<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <x-message :message="session('message')" />
        <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
            <form class="h-adr" method="post" action="{{ route('reserve.store') }}">
                @csrf
                <span class="p-country-name" style="display:none;">Japan</span>
                <x-input-error :messages="$errors->get('CliTel1')" class="mt-2" />
                <x-rtextbox name="CliTel1" type="tel" value="{{old('CliTel1')}}">電話番号:</x-rtextbox>
                <input type="button" id="refTelButton" value="　検索　" />
						<Label id="RefMessage">過去にご予約頂いた方は、最終予約情報から住所氏名等を引用します。</Label>
					';

                    

                <x-input-error :messages="$errors->get('ReserveDate')" class="mt-2" />
                <x-rtextbox name="ReserveDate" type="datetime-local" 
                    value="{{old('ReserveDate', $DestDate) }}" required>予約日:</x-rtextbox>

                <x-input-error :messages="$errors->get('CliResvCnt')" class="mt-2" />
                <x-rtextbox name="CliResvCnt" type="number"  value="{{old('CliResvCnt',1)}}" required>予約人数:</x-rtextbox>

                <label for="CliResvType">予約タイプ:</label>
                <select id="CliResvType" name="CliResvType" required>
                    @foreach ( \App\Models\Reserve::GetYoyakuType() as $item) 
                        <option value="{{ $item[0] }}"  {{ old('CliResvType') == $item[0] ? 'selected' : '' }}>{{ $item[1] }}</option>
                    @endforeach
                </select><br>

                <!--label for="ClitNameKanji">氏名（漢字）:</label>
                <input type="text" id="ClitNameKanji" name="ClitNameKanji" required><br-->

                <x-input-error :messages="$errors->get('ClitNameKanji')" class="mt-2" />
                <x-rtextbox name="ClitNameKanji" required value="{{old('ClitNameKanji')}}">氏名（漢字）</x-rtextbox>
                <x-input-error :messages="$errors->get('ClitNameKana')" class="mt-2" />
                <x-rtextbox name="ClitNameKana" required value="{{old('ClitNameKana')}}">カナ氏名:</x-rtextbox>
                <x-input-error :messages="$errors->get('CliAddrZip')" class="mt-2" />
                <x-rtextbox class="p-postal-code " name="CliAddrZip" required value="{{old('CliAddrZip')}}">郵便番号:</x-rtextbox>

                <label>住所</label>
                <!--input type="text" class="p-region p-locality p-street-address p-extended-address" /-->
                <x-input-error :messages="$errors->get('CliAddrPref')" class="mt-2" />
                <x-rtextbox name="CliAddrPref" class="p-region " required value="{{old('CliAddrPref')}}">県名:</x-rtextbox>

                <x-input-error :messages="$errors->get('CliAddrCity')" class="mt-2" />
                <x-rtextbox name="CliAddrCity" class="p-locality "  required value="{{old('CliAddrCity')}}">市町村名:</x-rtextbox>

                <x-input-error :messages="$errors->get('CliAddrOther')" class="mt-2" />
                <x-rtextbox name="CliAddrOther" class="p-street-address p-extended-address "  required value="{{old('CliAddrOther')}}">地域名:</x-rtextbox>

                <x-input-error :messages="$errors->get('CliEMail')" class="mt-2" />
                <x-rtextbox name="CliEMail" type="email" required value="{{old('CliEMail')}}">メールアドレス:</x-rtextbox>

                <fieldset id="WayPay">
                    <legend>支払方法</legend>

                    @foreach ( \App\Models\Reserve::GetPaysWay() as $item) 
                        <input type="radio" id="CliWaysPay{{ $loop->index }}" name="CliWaysPay" value="{{ $item[0] }}" />
                        <label for="CliWaysPay{{ $loop->index }}">{{ $item[1] }}</label>
                    @endforeach
                </fieldset>

                <label for="MessageText">連絡事項:</label>
                <textarea id="MessageText" name="MessageText" value="{{old('MessageText')}}"></textarea><br>

                <x-primary-button class="mt-4">
                    予約実行
                </x-primary-button>

                <script>

                    // 登録結果表示用のラベル出力
                    function ApplyJsonToText(DataVal,TextName) {
                        ctlName = "input:text[name=" + TextName + "]";
                        const str1 = $(ctlName).val();
                        //$("#span3").text(str1);
                        if(str1 == "") {
                            $(ctlName).val(DataVal);;
                        }
                    }

                    $(function() {
                        $('#refTelButton').click(function() { 
                            //alert("クリックされました");
                            var TelNo = $("input[name='CliTel1']").val();
                            TelNo = $.trim(TelNo);
                            if(TelNo == "") {
                                alert("注文時に入力した電話番号を入力してください");
                            }
                            else {
                                var paramStr = "Tel=" + TelNo;
                                $.ajax({
                                    url: '{{ route('reserve.GetCustmerData') }}', // リクエストを送るURL
                                    type: 'GET',
                                    data: {
                                        type  : 'TopOne',
                                        Tel   : TelNo
                                    },                            
                                    success: function(response) {
                                        console.log("get customer info success.");
                                        console.log(response);
                                        json = response;

                                        if(json.id > 0) {
                                            ApplyJsonToText(json.ClitNameKanji,	"ClitNameKanji");	//お名前
                                            ApplyJsonToText(json.ClitNameKana,	"ClitNameKana");	//フリガナ
                                            ApplyJsonToText(json.CliAddrZip,	"CliAddrZip");		//郵便番号
                                            ApplyJsonToText(json.CliAddrPref,	"CliAddrPref");		//都道府県
                                            ApplyJsonToText(json.CliAddrCity,	"CliAddrCity");		//市区町村
                                            ApplyJsonToText(json.CliAddrOther,	"CliAddrOther");	//町名番地
                                            ApplyJsonToText(json.CliEMail,		"CliEMail");		//メールアドレス
                                            $("#RefMessage").text("注文履歴から引用しました");
                                            $("#RefMessage").css("color","blue");
                                        }
                                        else {
                                            $("#RefMessage").text("注文履歴に指定された電話番号は登録されていません");
                                            $("#RefMessage").css("color","red");
                                        }
                                    },
                                    error: function(error) {
                                        // エラーが発生した場合の処理
                                        console.error(error);
                                    }
                                })
                            }
                        });
                    });
                </script>

            </form>



       </div>
    </div>
</x-app-layout>
