<x-app-layout>
    <x-controltemfileio nameitem="productName" extension=".product"></x-controltemfileio>


    <x-slot name="header">
        <x-article-title caption="{{ __('service edit') . $product->productName }}" />
    </x-slot>

    <div class="py-12">
        <x-message :message="session('message')" />
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">商品ID: {{ $product->productID }} ({{ $product->id }})</h3>

                <!-- 編集フォーム (PUT/PATCH メソッドを使用) -->
                <form action="{{ route('user_products.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT') 

                    <!-- 店舗情報 (変更不可として隠す) -->
                    <input type="hidden" name="baseCode" value="{{ $user->id }}">
                    <input type="hidden" name="productID" value="{{ $product->productID }}">

                    <x-rTextbox name="IsEnabled" type="checkbox" 
                    value="{{old('IsEnabled',$product->IsEnabled) }}" required>{{ __('IsEnabled') }}</x-rTextbox>

                    <x-rTextbox name="productName" type="text" 
                        value="{{ old('productName', $product->productName) }}" required>商品名:</x-rTextbox>
                    
                    <x-rTextbox name="DateStart" type="date" 
                        value="{{ old('DateStart', $product->DateStart) }}" required>営業開始日:</x-rTextbox>
                    <x-rTextbox name="DateEnd" type="date" 
                        value="{{ old('DateEnd', $product->DateEnd) }}" required>営業終了日:</x-rTextbox>
                    <x-rTextbox name="TimeStart" type="time" 
                        value="{{ old('TimeStart', $product->TimeStart) }}" required>開始時刻:</x-rTextbox>
                    <x-rTextbox name="TimeEnd" type="time" 
                        value="{{ old('TimeEnd', $product->TimeEnd) }}" required>終了時刻:</x-rTextbox>
                    <x-rTextbox name="capacity" type="number" 
                        value="{{ old('capacity', $product->capacity) }}" required>定員:</x-rTextbox>
                    <x-rTextbox name="WeekdayPrice" type="number" 
                        value="{{ old('WeekdayPrice', $product->WeekdayPrice) }}" required>{{ __('weekday price') }}</x-rTextbox>
                    <x-rTextbox name="WeekendPrice" type="number" 
                        value="{{ old('WeekendPrice', $product->WeekendPrice) }}" required>{{ __('weekend price') }}</x-rTextbox>
                    <x-rTextbox name="AddtionalName" type="text" 
                        value="{{ old('AddtionalName', $product->AddtionalName) }}">{{ __('additional name') }}</x-rTextbox>
                    <x-rTextbox name="AddtionalPrice" type="number" 
                        value="{{ old('AddtionalPrice', $product->AddtionalPrice) }}" required>{{ __('additional price') }}</x-rTextbox>
                    
                    <x-rCheckbox name="ResvTypeBit" caption="予約タイプ"> 
                        @php
                            $currentResvTypeBit = old('ResvTypeBit') !== null ? array_sum(old('ResvTypeBit')) : $product->ResvTypeBit;
                        @endphp

                        @foreach ($shopReservTypes as $type)
                            <div class="checkbox-line">
                                {{-- ResvTypeBitはRtBitを参照 --}}
                                <input type="checkbox" name="ResvTypeBit[]" 
                                    value="{{ $type->RtBit }}" id="ResvType_{{ $loop->index }}"
                                    @if (($currentResvTypeBit & $type->RtBit) === $type->RtBit) checked @endif
                                    >
                                <label for="ResvType_{{ $loop->index }}">{{ $type->RtName }}</label>
                            </div>
                        @endforeach
                    </x-rCheckbox>

                    <x-rCheckbox name="WaysPayBit" caption="支払い方法">                
                        @php
                            $currentWaysPay = old('WaysPayBit') !== null ? array_sum(old('WaysPayBit')) : $product->WaysPayBit;
                        @endphp

                        @foreach ($shopPaysWay as $way)
                        <div class="checkbox-line">
                            <input type="checkbox" name="WaysPayBit[]" 
                                    value="{{ $way->PrBit }}" id="WaysPayBit_{{ $loop->index }}"
                                    @if (($currentWaysPay & $way->PrBit) === $way->PrBit) checked @endif
                            >
                            <label for="WaysPayBit_{{ $loop->index }}">{{ $way->PrName }}</label><br>
                            </div>
                        @endforeach
                    </x-rCheckbox>

                    <x-rTextarea name="memo" msgText="{{old('memo',$product->memo)}}">{{ __('guide') }}:</x-rTextarea>

                    <button type="submit" class="register-button">
                        更新
                    </button>
                    <a href="{{ url('dashboard') }}" class="back-button">
                        {{ __('Cancel') }}
                    </a>

                </form>
            </div>
        </div>
    </div>


</x-app-layout>
