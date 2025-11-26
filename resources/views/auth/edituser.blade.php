<x-guest-layout>
    <form class="h-adr" method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        ID: {{ Auth::user()->id }}

        <style>
            .textLink {
                text-decoration: underline;
                font-size: 0.875rem; /* 14px */
                line-height: 1.25rem; /* 20px */
                color: #71717a;
            }
            .textLink::hover {
                color: red; /* 文字色を赤くする */
                background-color: red; /* 背景色を赤くする */
                border-color: red; /* 枠線を赤くする */
            }

            .inputBoxArea {
                display: flex; /* Flexbox を有効にする */
                flex-direction: column; /* デフォルトは縦並び */
            }

            /* 画面幅が 800px 以上の場合 */
            @media (min-width: 1000px) {
                .inputBoxArea {
                    flex-direction: row; /* 横並びにする */
                    align-items: center; /* 垂直方向に中央揃え */
                }

                .inputBoxArea label {
                    width: 8em;
                    margin-right: 10px; /* ラベルとテキストボックスの間に間隔を作る */
                }
            }            
        </style>

        <a class="textLink" href="{{ route('login') }}">
            {{ __('Already registered?') }}
        </a>

        <hr>
        <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>
        <x-rTextbox name="baseNameKanji" required value="{{Auth::user()->spName}}">{{ __('Shop name') }}</x-rTextbox>
        <x-rTextbox name="baseNameKana" required value="{{Auth::user()->spNameKana}}">{{ __('Shop name') }}{{ __('(KANA)') }}</x-rTextbox>
        <label>{{ __('validation.attributes.address') }}</label>
        <span class="p-country-name" style="display:none;">Japan</span>
        <x-rTextbox  name="baseAddrZip"     class="p-postal-code "  required value="{{Auth::user()->spAddrZip}}">{{ __('postal code') }}:</x-rTextbox>
        <x-rTextbox  name="baseAddrPref"    class="p-region "       required value="{{Auth::user()->spAddrPref}}">{{ __('province') }}:</x-rTextbox>
        <x-rTextbox  name="baseAddrCity"    class="p-locality "     required value="{{Auth::user()->spAddrCity}}">{{ __('municipality') }}:</x-rTextbox>
        <x-rTextbox  name="baseAddrOther"   class="p-street-address p-extended-address "  required value="{{Auth::user()->spAddrOther}}">{{ __('village') }}:</x-rTextbox>
        <x-rTextbox  name="baseTel1"     type="tel" value="{{Auth::user()->spTel1}}">{{ __('phone') }}1:</x-rTextbox>
        <x-rTextbox  name="baseTel2"     type="tel" value="{{Auth::user()->spTel2}}">{{ __('phone') }}2:</x-rTextbox>
        <x-rTextbox  name="baseEMail"    type="email" required value="{{Auth::user()->spEMail}}">{{ __('Email') }}:</x-rTextbox>
        <x-rTextbox  name="baseURL"      type="url" value="{{Auth::user()->baseURL}}">URL:</x-rTextbox>
        <x-rCheckbox name="ResvTypeBit" caption="予約タイプ"> 
            <div class="checkbox-line">
                <input type="checkbox" name="spResvType" value="1" id="spResvType"
                    {{-- old() の値に応じてチェック状態を復元 --}}
                    @checked( Auth::user()->spResvType & 1 )
                >
                <label for="spResvType">ネット予約可能</label>
            </div>
        </x-rCheckbox>

        <label for="MessageText">{{ __('validation.attributes.guide_text') }}:</label><br>
        <textarea id="MessageText" name="MessageText" value="{{Auth::user()->spMsgText}}"></textarea><br>

        <div class="flex items-center justify-end mt-4">

            <x-primary-button class="ms-4">
                {{ __('Update') }}
            </x-primary-button>
        </div>
        </form>
</x-guest-layout>
<!--「resources\views\auth\edituser.blade php」 -- END<br> -->
