<!-- 店舗登録画面 -->

<!--「resources\views\auth\register.blade.php」 -- START<br-->
<x-guest-layout>
    <x-controltemfileio nameitem="baseNameKanji" extension=".corp"></x-controltemfileio>

    <form class="h-adr" method="POST" action="{{ route('register') }}">
        @csrf

        <x-rTextbox type="text" name="name" value="{{old('name')}}" attr="required autofocus" autocomplete="name" >{{ __('UserID') }}</x-rTextbox>
        <x-rTextbox type="password" name="password" value="{{old('password')}}" attr="required" autocomplete="new-password" >{{ __('password') }}</x-rTextbox>
        <x-rTextbox type="password" name="password_confirmation" value="{{old('password_confirmation')}}" attr="required" autocomplete="new-password" >{{ __('Confirm Password') }}</x-rTextbox>

        <a class="textLink" href="{{ route('login') }}">
            {{ __('Already registered?') }}
        </a>

        <hr>
        <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>

        <x-rTextbox name="baseNameKanji" required value="{{old('baseNameKanji')}}">{{ __('Shop name') }}</x-rTextbox>
        <x-rTextbox name="baseNameKana" required value="{{old('baseNameKana')}}">{{ __('Shop name') }}{{ __('(KANA)') }}</x-rTextbox>
        
        <label>{{ __('validation.attributes.address') }}</label>
        <span class="p-country-name" style="display:none;">Japan</span>
        <x-rTextbox class="p-postal-code " name="baseAddrZip" required value="{{old('baseAddrZip')}}">{{ __('postal code') }}:</x-rTextbox>
        <x-rTextbox name="baseAddrPref" class="p-region " required value="{{old('baseAddrPref')}}">{{ __('province') }}:</x-rTextbox>
        <x-rTextbox name="baseAddrCity" class="p-locality "  required value="{{old('baseAddrCity')}}">{{ __('municipality') }}:</x-rTextbox>
        <x-rTextbox name="baseAddrOther" class="p-street-address p-extended-address "  required value="{{old('baseAddrOther')}}">{{ __('village') }}:</x-rTextbox>
        <x-rTextbox name="baseTel1" type="tel" value="{{old('baseTel1')}}">{{ __('phone') }}1:</x-rTextbox>
        <x-rTextbox name="baseTel2" type="tel" value="{{old('baseTel2')}}">{{ __('phone') }}2:</x-rTextbox>
        <x-rTextbox name="baseEMail" type="email" required value="{{old('Email')}}">{{ __('Email') }}:</x-rTextbox>
        <x-rTextbox name="baseURL" type="url" value="{{old('baseURL')}}">URL:</x-rTextbox>
        <x-rTextarea name="MessageText" attr="label" msgText="{{old('Guide message')}}"></x-rTextarea><br>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
        </form>
</x-guest-layout>
<!--「resources\views\auth\register.blade.php」 -- END<br-->
