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

                    <x-rTextbox name="productName" type="text" 
                        value="{{ old('productName', $product->productName) }}" required>商品名:</x-rTextbox>
                    
                    <!-- 有効/無効の切り替え -->
                    <div class="mb-4">
                        <label class="block font-medium text-sm text-gray-700">状態:</label>
                        <select name="IsEnabled" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                            <option value="1" {{ old('IsEnabled', $product->IsEnabled) == 1 ? 'selected' : '' }}>有効</option>
                            <option value="0" {{ old('IsEnabled', $product->IsEnabled) == 0 ? 'selected' : '' }}>無効</option>
                        </select>
                        @error('IsEnabled')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

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
                    <x-rTextbox name="price" type="number" 
                        value="{{ old('price', $product->price) }}" required>料金:</x-rTextbox>

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
                        <!-- productのWaysPayをビット値として利用し、チェックボックスの状態を決定 -->
                        @php
                            $currentWaysPay = old('WaysPay') !== null ? array_sum(old('WaysPay')) : $product->WaysPay;
                        @endphp

                        @foreach ($shopPaysWay as $way)
                            <input type="checkbox" name="WaysPay[]" 
                                value="{{ $way->PrBit }}" id="WaysPay_{{ $loop->index }}"
                                @if (($currentWaysPay & $way->PrBit) === $way->PrBit) checked @endif>
                            <label for="WaysPay_{{ $loop->index }}">{{ $way->PrName }}</label><br>
                        @endforeach
                    </x-rCheckbox>
                    
                    <!-- x-r-text-area コンポーネントの定義が不明なため、ここでは標準の textarea を使用して old/productの値を適用 -->
                    <div class="mb-4">
                        <label for="memo" class="block font-medium text-sm text-gray-700">案内 (メモ):</label>
                        <textarea name="memo" id="memo" rows="4" class="mt-1 block w-full rounded-md shadow-sm border-gray-300 focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">{{ old('memo', $product->memo) }}</textarea>
                        @error('memo')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 focus:bg-indigo-500 active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        更新
                    </button>
                    <a href="{{ route('dashboard') }}" class="ml-4 inline-flex items-center px-4 py-2 bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-gray-800 uppercase tracking-widest hover:bg-gray-300 transition ease-in-out duration-150">
                        キャンセル
                    </a>
                </form>
            </div>
        </div>
    </div>


</x-app-layout>
