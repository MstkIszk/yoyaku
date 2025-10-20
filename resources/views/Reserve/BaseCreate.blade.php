<x-app-layout>
    <x-slot name="header">
        <x-article-title caption="{{ __('Dashboard') }}" />
    </x-slot>

    <div class="py-12">
        <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
        
        <x-message :message="session('message')" />

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        
            <form class="h-adr" method="post" action="{{ route('reservbae.store') }}">
                @csrf

                <x-input-error :messages="$errors->get('baseNameKanji')" class="mt-2" />
                <x-rTextbox name="baseNameKanji" required value="{{old('baseNameKanji')}}">店舗名(漢字)</x-rTextbox>

                <x-input-error :messages="$errors->get('baseNameKana')" class="mt-2" />
                <x-rTextbox name="baseNameKana" required value="{{old('baseNameKana')}}">店舗名(カナ)</x-rTextbox>
                
                <span class="p-country-name" style="display:none;">Japan</span>

                <x-input-error :messages="$errors->get('baseAddrZip')" class="mt-2" />
                <x-rTextbox class="p-postal-code " name="baseAddrZip" required value="{{old('baseAddrZip')}}">郵便番号:</x-rTextbox>

                <label>住所</label>
                <x-input-error :messages="$errors->get('baseAddrPref')" class="mt-2" />
                <x-rTextbox name="CliAddrPref" class="p-region " required value="{{old('baseAddrPref')}}">県名:</x-rTextbox>

                <x-input-error :messages="$errors->get('baseAddrCity')" class="mt-2" />
                <x-rTextbox name="baseAddrCity" class="p-locality "  required value="{{old('baseAddrCity')}}">市町村名:</x-rTextbox>

                <x-input-error :messages="$errors->get('baseAddrOther')" class="mt-2" />
                <x-rTextbox name="baseAddrOther" class="p-street-address p-extended-address "  required value="{{old('baseAddrOther')}}">地域名:</x-rTextbox>

                <x-input-error :messages="$errors->get('baseTel1')" class="mt-2" />
                <x-rTextbox name="baseTel1" type="tel" value="{{old('baseTel1')}}">電話番号１:</x-rTextbox>

                <x-input-error :messages="$errors->get('baseTel2')" class="mt-2" />
                <x-rTextbox name="baseTel2" type="tel" value="{{old('baseTel2')}}">電話番号２:</x-rTextbox>

                <x-input-error :messages="$errors->get('baseEMail')" class="mt-2" />
                <x-rTextbox name="baseEMail" type="email" required value="{{old('baseEMail')}}">メールアドレス:</x-rTextbox>

                <x-input-error :messages="$errors->get('baseURL')" class="mt-2" />
                <x-rTextbox name="baseURL" type="url" required value="{{old('baseURL')}}">URL:</x-rTextbox>

                <label for="MessageText">店舗案内:</label>
                <textarea id="MessageText" name="MessageText" value="{{old('MessageText')}}"></textarea><br>

                <x-primary-button class="mt-4">
                    登録
                </x-primary-button>

            </form>
       </div>
    </div>
</x-app-layout>
