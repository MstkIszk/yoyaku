<!-- 店舗登録画面 -->

<!--「resources\views\auth\register.blade.php」 -- START<br-->
<x-guest-layout>
    <form class="h-adr" method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>


        <a class="textLink" href="{{ route('login') }}">
            {{ __('Already registered?') }}
        </a>

        <hr>
        <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>

        <x-input-error :messages="$errors->get('baseNameKanji')" class="mt-2" />
        <x-rTextbox name="baseNameKanji" required value="{{old('baseNameKanji')}}">{{ __('Shop name') }}</x-rTextbox>

        <x-input-error :messages="$errors->get('baseNameKana')" class="mt-2" />
        <x-rTextbox name="baseNameKana" required value="{{old('baseNameKana')}}">{{ __('Shop name') }}{{ __('(KANA)') }}</x-rTextbox>
        
        <label>{{ __('validation.attributes.address') }}</label>
        <span class="p-country-name" style="display:none;">Japan</span>

        <x-input-error :messages="$errors->get('baseAddrZip')" class="mt-2" />
        <x-rTextbox class="p-postal-code " name="baseAddrZip" required value="{{old('baseAddrZip')}}">{{ __('postal code') }}:</x-rTextbox>

        <x-input-error :messages="$errors->get('baseAddrPref')" class="mt-2" />
        <x-rTextbox name="baseAddrPref" class="p-region " required value="{{old('baseAddrPref')}}">{{ __('province') }}:</x-rTextbox>

        <x-input-error :messages="$errors->get('baseAddrCity')" class="mt-2" />
        <x-rTextbox name="baseAddrCity" class="p-locality "  required value="{{old('baseAddrCity')}}">{{ __('municipality') }}:</x-rTextbox>

        <x-input-error :messages="$errors->get('baseAddrOther')" class="mt-2" />
        <x-rTextbox name="baseAddrOther" class="p-street-address p-extended-address "  required value="{{old('baseAddrOther')}}">{{ __('village') }}:</x-rTextbox>

        <x-input-error :messages="$errors->get('baseTel1')" class="mt-2" />
        <x-rTextbox name="baseTel1" type="tel" value="{{old('baseTel1')}}">{{ __('phone') }}1:</x-rTextbox>

        <x-input-error :messages="$errors->get('baseTel2')" class="mt-2" />
        <x-rTextbox name="baseTel2" type="tel" value="{{old('baseTel2')}}">{{ __('phone') }}2:</x-rTextbox>

        <x-input-error :messages="$errors->get('baseEMail')" class="mt-2" />
        <x-rTextbox name="baseEMail" type="email" required value="{{old('Email')}}">{{ __('Email') }}:</x-rTextbox>

        <x-input-error :messages="$errors->get('baseURL')" class="mt-2" />
        <x-rTextbox name="baseURL" type="url" value="{{old('baseURL')}}">URL:</x-rTextbox>

        <label for="MessageText">{{ __('validation.attributes.guide_text') }}:</label><br>
        <textarea id="MessageText" name="MessageText" value="{{old('Guide message')}}"></textarea><br>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
        </form>
</x-guest-layout>
<!--「resources\views\auth\register.blade.php」 -- END<br-->
