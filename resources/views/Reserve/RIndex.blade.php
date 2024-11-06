<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="mx-auto px-6">
        @if(session('message'))
            <div class="text-red-600 font-bold">
                {{ session('message') }}
            </div>       
        @endif
        @foreach ($reserve as $data)
        
        <div class="mt-4 p-8 bg-white w-full rounded-2xl">
            <h1 class="mt-4 p-8 bg-white w-full sm:rounded-lg">
                <div class="p-4 text-lg font-semibold">
                    {{ $data->ReserveDate }}
                </div>
            </h1>
            <div class="p-4 text-sm font-semibold">
                    {{ $data->ClitNameKanji }}
            </div>
            <div class="p-4 text-sm font-semibold">
                    {{ $data->CliResvCnt }}
            </div>
            <div class="text-right">
                <a href="{{ route('reserve.show', $data) }}">
                    <x-primary-button>
                        表示
                    </x-primary-button>
                </a>
            </div>
        </div>
        @endforeach
    </div>
</x-app-layout>
