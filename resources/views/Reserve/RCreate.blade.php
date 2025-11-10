<x-app-layout>
    Date {{ $DestDate }}

    <x-slot name="header">
        <x-article-title caption="{{ __('New Resavation Entry') }}" />
    </x-slot>

    <div class="py-12">
        <x-controltemfileio nameitem="ClitNameKanji" extension=".yoyaku"></x-controltemfileio>

        <x-message :message="session('message')" />
        <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
        <form class="h-adr" method="post" action="{{ route('reserve.confirm') }}">
        {{-- <form class="h-adr" method="post" action="{{ route('reserve.store') }}"> --}}
            @csrf
                <input type="hidden" name="Baseid" value="{{ $user->id }}">
                <input type="hidden" name="Productid" value="{{ $Product->id }}">

                <span class="p-country-name" style="display:none;">Japan</span>
                <x-rTextbox name="CliTel1" type="tel" value="{{old('CliTel1')}}">電話番号:</x-rTextbox>
                <input type="button" id="refTelButton" value="　検索　" />
						<Label id="RefMessage">過去にご予約頂いた方は、最終予約情報から住所氏名等を引用します。</Label>

                <x-rTextbox name="ReserveDate" type="datetime-local" 
                    value="{{old('ReserveDate', $DestDate) }}" required>予約日:</x-rTextbox>
                <x-rTextbox name="CliResvCnt" type="number"  value="{{old('CliResvCnt',1)}}" required>予約人数:</x-rTextbox>

                <x-rSelect name="CliResvType" caption="予約タイプ"  attributes="required">
                    @foreach ( \App\Models\Reserve::GetYoyakuType( $user->id,$Product->id) as $item) 
                        <option value="{{ $item[0] }}"  {{ old('CliResvType') == $item[0] ? 'selected' : '' }}>{{ $item[1] }}</option>
                    @endforeach
                </x-rSelect><br>

                <x-rTextbox name="ClitNameKanji" required value="{{old('ClitNameKanji')}}">氏名（漢字）</x-rTextbox>
                <x-rTextbox name="ClitNameKana" required value="{{old('ClitNameKana')}}">カナ氏名:</x-rTextbox>
                <x-rTextbox class="p-postal-code " name="CliAddrZip" required value="{{old('CliAddrZip')}}">郵便番号:</x-rTextbox>

                <label>住所</label>
                <x-rTextbox name="CliAddrPref" class="p-region " required value="{{old('CliAddrPref')}}">県名:</x-rTextbox>
                <x-rTextbox name="CliAddrCity" class="p-locality "  required value="{{old('CliAddrCity')}}">市町村名:</x-rTextbox>
                <x-rTextbox name="CliAddrOther" class="p-street-address p-extended-address "  required value="{{old('CliAddrOther')}}">地域名:</x-rTextbox>
                <x-rTextbox name="CliEMail" type="email" required value="{{old('CliEMail')}}">メールアドレス:</x-rTextbox>

                <fieldset id="WayPay">
                    <legend>支払方法</legend>

                    @foreach ( $WaysPayList as $item) 
                        <input type="radio" id="CliWaysPay{{ $loop->index }}" name="CliWaysPay" value="{{ $item[0] }}" />
                        <label for="CliWaysPay{{ $loop->index }}">{{ $item[2] }}</label>
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
