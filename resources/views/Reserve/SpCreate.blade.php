<!-- resources\views\Reserve\SpCreate.blade.php start -->
<x-app-layout>
    <x-slot name="header">
        <x-article-title caption="{{ __('Dashboard') }}" />
    </x-slot>

    <div class="py-12">
        <x-message :message="session('message')" />
        <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
            <form class="h-adr" method="post" action="{{ route('reserve.shopstore') }}">
                @csrf
                <span class="p-country-name" style="display:none;">Japan</span>
                <x-input-error :messages="$errors->get('ReserveDate')" class="mt-2" />

                <x-input-error :messages="$errors->get('SpNameKanji')" class="mt-2" />
                <x-rTextbox name="SpNameKanji" required value="{{old('SpNameKanji')}}">店舗名(漢字)</x-rTextbox>
                <x-input-error :messages="$errors->get('SpNameKana')" class="mt-2" />
                <x-rTextbox name="SpNameKana" required value="{{old('SpNameKana')}}">店舗名(カナ):</x-rTextbox>
                <x-input-error :messages="$errors->get('SpAddrZip')" class="mt-2" />
                <x-rTextbox class="p-postal-code " name="SpAddrZip" required value="{{old('SpAddrZip')}}">郵便番号:</x-rTextbox>

                <label>所在地</label>
                <!--input type="text" class="p-region p-locality p-street-address p-extended-address" /-->
                <x-input-error :messages="$errors->get('SpAddrPref')" class="mt-2" />
                <x-rTextbox name="SpAddrPref" class="p-region " required value="{{old('SpAddrPref')}}">県名:</x-rTextbox>

                <x-input-error :messages="$errors->get('SpAddrCity')" class="mt-2" />
                <x-rTextbox name="SpAddrCity" class="p-locality "  required value="{{old('SpAddrCity')}}">市町村名:</x-rTextbox>

                <x-input-error :messages="$errors->get('SpAddrOther')" class="mt-2" />
                <x-rTextbox name="SpAddrOther" class="p-street-address p-extended-address "  required value="{{old('SpAddrOther')}}">地域名:</x-rTextbox>

                <x-input-error :messages="$errors->get('SpTel1')" class="mt-2" />
                <x-rTextbox name="Spel1" type="tel" value="{{old('Spel1')}}">電話番号:</x-rTextbox>

                <x-input-error :messages="$errors->get('SpEMail')" class="mt-2" />
                <x-rTextbox name="SpEMail" type="email" required value="{{old('SpEMail')}}">メールアドレス:</x-rTextbox>

                <x-input-error :messages="$errors->get('SpURL')" class="mt-2" />
                <x-rTextbox name="SpURL" type="email" required value="{{old('SpURL')}}">URL:</x-rTextbox>

                <fieldset id="WayPay">
                    <legend>支払方法</legend>

                    @foreach ( \App\Models\Reserve::GetPaysWay() as $item) 
                        <input type="checkbox" id="SpWaysPay{{ $loop->index }}" name="SpWaysPay" value="{{ $item[0] }}" />
                        <label for="SpWaysPay{{ $loop->index }}">{{ $item[1] }}</label>
                    @endforeach
                </fieldset>

                <label for="MessageText">店舗説明:</label>
                <x-r-text-area name="MessageText" value="{{old('MessageText')}}">店舗説明:</x-rTextbox>
                <!--textarea id="MessageText" name="MessageText" value="{{old('MessageText')}}"></textarea><br-->

                <x-primary-button class="mt-4">
                    店舗登録
                </x-primary-button>

            </form>
       </div>
    </div>
</x-app-layout>
<!-- resources\views\Reserve\SpCreate.blade.php end -->
