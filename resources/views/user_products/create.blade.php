<x-app-layout>
    <x-controltemfileio nameitem="productName" extension=".product"></x-controltemfileio>

    <x-slot name="header">
        <x-article-title caption="{{ __('new product') }}" />
    </x-slot>

    <div class="py-12">
        <x-message :message="session('message')" />

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        

            <form action="{{ route('user_products.store') }}" method="POST">
                @csrf

                <input type="hidden" name="baseCode" value="{{ Session::get('ShopID') }}">
                <input type="hidden" name="productID" value="1">

                <x-rTextbox name="IsEnabled" type="checkbox" 
                    value="{{old('IsEnabled', 'checked') }}" required>{{ __('IsEnabled') }}</x-rTextbox>
                <x-rTextbox name="productName" type="text" 
                    value="{{old('productName') }}" required>{{ __('product name') }}</x-rTextbox>
                <x-rTextbox name="DateStart" type="date" 
                    value="{{old('DateStart') }}" required>{{ __('business start date') }}</x-rTextbox>
                <x-rTextbox name="DateEnd" type="date" 
                    value="{{old('DateEnd') }}" required>{{ __('business end date') }}:</x-rTextbox>
                <x-rTextbox name="TimeStart" type="time" 
                    value="{{old('TimeStart') }}" required>{{ __('start time') }}:</x-rTextbox>
                <x-rTextbox name="TimeEnd" type="time" 
                    value="{{old('TimeEnd') }}" required>{{ __('end time') }}:</x-rTextbox>
                <x-rTextbox name="capacity" type="number" 
                    value="{{old('capacity', 10) }}" required>{{ __('capacity') }}:</x-rTextbox>
                <x-rTextbox name="WeekdayPrice" type="number" 
                    value="{{ old('WeekdayPrice', 0) }}" required>{{ __('weekday price') }}</x-rTextbox>
                <x-rTextbox name="WeekendPrice" type="number" 
                    value="{{ old('WeekendPrice', 0) }}" required>{{ __('weekend price') }}</x-rTextbox>
                <x-rTextbox name="AddtionalName" type="text" 
                    value="{{ old('AddtionalName', '') }}">{{ __('additional name') }}</x-rTextbox>
                <x-rTextbox name="AddtionalPrice" type="number" 
                    value="{{ old('AddtionalPrice', 0) }}" required>{{ __('additional price') }}</x-rTextbox>
                
                <x-rCheckbox name="ResvTypeBit" caption="予約タイプ"> 
                    @foreach ($shopReservTypes as $type)
                        <div class="checkbox-line">
                            {{-- ResvTypeBitはRtBitを参照 --}}
                            <input type="checkbox" name="ResvTypeBit[]" 
                                value="{{ $type->RtBit }}" id="ResvType_{{ $loop->index }}"
                                {{-- old() の値に応じてチェック状態を復元 --}}
                                @checked(is_array(old('ResvTypeBit')) && in_array($type->RtBit, old('ResvTypeBit')))
                            >
                            <label for="ResvType_{{ $loop->index }}">{{ $type->RtName }}</label>
                        </div>
                    @endforeach
                </x-rCheckbox>

                <x-rCheckbox name="WaysPayBit" caption="支払い方法">                
                    @foreach ($shopPaysWay as $way)
                    <div class="checkbox-line">
                        <input type="checkbox" name="WaysPayBit[]" 
                                    value="{{ $way->PrBit }}" id="WaysPayBit_{{ $loop->index }}"
                                    {{-- old() の値に応じてチェック状態を復元 --}}
                                    @checked(is_array(old('WaysPayBit')) && in_array($way->PrBit, old('WaysPayBit')))
                            >
                            <label for="WaysPayBit_{{ $loop->index }}">{{ $way->PrName }}</label><br>
                            </div>
                    @endforeach
                </x-rCheckbox>

                <x-rTextarea name="memo" msgText="{{old('memo')}}">{{ __('memo') }}:</x-rTextarea><br>

                <button type="submit" class="register-button">登録</button>
            </form>
       </div>
    </div>
</x-app-layout>
