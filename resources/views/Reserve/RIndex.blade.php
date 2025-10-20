<x-app-layout>
    <x-slot name="header">
        <x-article-title caption="{{ __('Resavation List') }}" />
    </x-slot>

    <div class="mx-auto px-6">
        <style>
            .list-frame {
                position: relative;
                display: flex;
                flex-direction: column;
                word-wrap: break-word;
                background-color: #FFDDDD;
                background-clip: border-box;
            }

            .list-frame table{
                width: 100%;
                border-collapse:separate;
                border-spacing: 0;
            }

            .list-frame table th:first-child{
                border-radius: 5px 0 0 0;
            }

            .list-frame table th:last-child{
                border-radius: 0 5px 0 0;
                border-right: 1px solid #3c6690;
            }

            .list-frame table th{
                text-align: center;
                color:white;
                background: linear-gradient(#829ebc,#225588);
                border-left: 1px solid #3c6690;
                border-top: 1px solid #3c6690;
                border-bottom: 1px solid #3c6690;
                box-shadow: 0px 1px 1px rgba(255,255,255,0.3) inset;
                width: 25%;
                padding: 10px 0;
            }

            .list-frame table td{
                text-align: center;
                border-left: 1px solid #a8b7c5;
                border-bottom: 1px solid #a8b7c5;
                border-top:none;
                box-shadow: 0px -3px 5px 1px #eee inset;
                width: 25%;
                padding: 10px 0;
            }

            .list-frame table td:last-child{
                border-right: 1px solid #a8b7c5;
            }            
        </style>


        @if(session('message'))
            <div class="text-red-600 font-bold">
                {{ session('message') }}
            </div>       
        @endif
        <div class="list-frame">
            <table>
            <tr><th>受付日時</th><th>氏名</th><th>予約日</th><th>人数</th><th>　</th></tr>
            @foreach ($reserve as $data)
                <t>
                <td>{{ \Carbon\Carbon::parse($data->ReqDate)->format('Y-m-d') }}<br>
                    {{ \Carbon\Carbon::parse($data->ReqDate)->format('H:i:s') }}</td>

                <td>{{ $data->ClitNameKanji }}<br>{{ $data->ClitNameKana }}</td>
                <td>{{ \Carbon\Carbon::parse($data->ReserveDate)->format('Y-m-d') }}<br>
                   ({{ \Carbon\Carbon::parse($data->ReserveDate)->locale('ja_JP')->isoFormat('ddd') }})
                    {{ \Carbon\Carbon::parse($data->ReserveDate)->format('H:i') }}</td>

                <td>{{ $data->CliResvCnt }}</td>
                <td><a href="{{ route('reserve.show', $data) }}">
                    <x-primary-button>
                        表示
                    </x-primary-button>
                </a></td>
                </tr>
            @endforeach
        </table>
        </div>
    </div>
</x-app-layout>
