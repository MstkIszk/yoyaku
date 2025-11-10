<x-app-layout>
    <x-slot name="header">
        <x-article-title caption="{{ __('Resavation search') }}" />
    </x-slot>

    <div class="py-12">
        <x-message :message="session('message')" />
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form method="get" action="{{ route('reserve.index') }}">
                @csrf
                <input type="hidden" name="ShopID" value="{{ $ShopID }}">
                <input type="hidden" name="ProductID" value="{{ $ProductID }}">

                <x-rTextbox name="CliTel1" type="tel" value="{{old('CliTel1')}}">電話番号:</x-rTextbox>
                <x-primary-button class="mt-4">予約検索</x-primary-button>
            </form>
       </div>
    </div>
</x-app-layout>
