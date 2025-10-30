<x-app-layout>
    <x-controltemfileio nameitem="productName" extension=".product"></x-controltemfileio>

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

                <x-rTextbox name="productName" type="text" 
                    value="{{old('DateStart') }}" required>商品名:</x-rTextbox>
                <x-rTextbox name="DateStart" type="date" 
                    value="{{old('DateStart') }}" required>営業開始日:</x-rTextbox>
                <x-rTextbox name="DateEnd" type="date" 
                    value="{{old('DateEnd') }}" required>営業終了日:</x-rTextbox>
                <x-rTextbox name="TimeStart" type="time" 
                    value="{{old('TimeStart') }}" required>開始時刻:</x-rTextbox>
                <x-rTextbox name="TimeEnd" type="time" 
                    value="{{old('TimeEnd') }}" required>終了時刻:</x-rTextbox>
                <x-rTextbox name="capacity" type="number" 
                    value="{{old('capacity', 10) }}" required>定員:</x-rTextbox>
                <x-rTextbox name="price" type="number" 
                    value="{{old('price', 1000) }}" required>料金:</x-rTextbox>

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

                <x-rCheckbox name="WaysPay" caption="支払い方法">                
                    @foreach ($shopPaysWay as $way)
                        <input  type="checkbox" name="WaysPay[]" 
                                value="{{ $way->PrBit }}" id="WaysPay_{{ $loop->index }}">
                        <label for="WaysPay_{{ $loop->index }}">{{ $way->PrName }}</label><br>
                    @endforeach
                </x-rCheckbox>

                <x-r-text-area name="memo" msgText="{{old('memo')}}">案内:</x-rTextbox>

                <button type="submit">登録</button>
            </form>
       </div>
    </div>
</x-app-layout>
