<x-app-layout>
    <x-slot name="header">
        <x-article-title caption="{{ __('Resavation search') }}" />
    </x-slot>

    <div class="py-12">
        <x-message :message="session('message')" />
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <form method="get" action="{{ route('reserve.index') }}">
                @csrf
                
                <label for="tel">電話番号:</label>
                <input type="tel" required id="CliTel1" name="CliTel1" required
                    value="{{ old('CliTel1') }}"><br>

                <x-primary-button class="mt-4">
                    予約検索
                </x-primary-button>
            </form>
       </div>
    </div>
</x-app-layout>
