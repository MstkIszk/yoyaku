<x-app-layout>
    <x-slot name="header">
        <x-article-title caption="{{ __('Dashboard') }}" />
    </x-slot>

    <div class="py-12">
        <x-message :message="session('message')" />

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        

            <form action="{{ route('user_products.store') }}" method="POST">
                @csrf

                <input type="hidden" name="baseCode" value="{{ Session::get('ShopID') }}">
                <input type="hidden" name="productID" value="1">



                <div>
                    <label for="productName">商品名</label>
                    <select name="productName" id="productName">
                        @foreach ($shopProducts as $product)
                            <option value="{{ $product->PrName }}">{{ $product->PrName }}</option>
                        @endforeach
                    </select>
                </div>
                <x-input-error :messages="$errors->get('DateStart')" class="mt-2" />
                <x-rTextbox name="DateStart" type="date" 
                    value="{{old('DateStart') }}" required>営業開始日:</x-rTextbox>

                <x-input-error :messages="$errors->get('DateEnd')" class="mt-2" />
                <x-rTextbox name="DateEnd" type="date" 
                    value="{{old('DateEnd') }}" required>営業終了日:</x-rTextbox>

                <x-input-error :messages="$errors->get('TimeStart')" class="mt-2" />
                <x-rTextbox name="TimeStart" type="time" 
                value="{{old('TimeStart') }}" required>開始時刻:</x-rTextbox>

                <x-input-error :messages="$errors->get('TimeEnd')" class="mt-2" />
                <x-rTextbox name="TimeEnd" type="time" 
                    value="{{old('TimeEnd') }}" required>終了時刻:</x-rTextbox>

                <x-input-error :messages="$errors->get('capacity')" class="mt-2" />
                <x-rTextbox name="capacity" type="number" 
                    value="{{old('capacity', 15) }}" required>定員:</x-rTextbox>

                <x-input-error :messages="$errors->get('price')" class="mt-2" />
                <x-rTextbox name="price" type="number" 
                    value="{{old('price', 15) }}" required>料金:</x-rTextbox>
                <style>
                .box12{
                    margin: 2em 0;
                    color: 0;
                    background: #c6ffe4;
                    border-bottom: solid 6px #aac5de;
                    border-radius: 9px;
                }
                .box12 label {
                    margin: 0; 
                    padding: 0;
                }
                </style>
                <div class="box12">
                    <label>支払い方法</label><br>
                    @foreach ($shopPaysWay as $way)
                        <input type="checkbox" name="WaysPay[]" value="{{ $way->PrBit }}" id="WaysPay_{{ $loop->index }}">
                        <label for="WaysPay_{{ $loop->index }}">{{ $way->PrName }}</label><br>
                    @endforeach
                </div>

                <x-r-text-area name="MessageText" msgText="{{old('MessageText')}}">案内:</x-rTextbox>

                <button type="submit">登録</button>
            </form>
       </div>
    </div>
</x-app-layout>
