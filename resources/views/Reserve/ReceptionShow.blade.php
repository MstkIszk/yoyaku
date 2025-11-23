<!-- Laravel Blade Template: Reserve/ReceptionList.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ \Carbon\Carbon::parse($reserve->ReserveDate)->isoFormat('YYYY年MM月DD日') }}　
            {{ $reserve->ClitNameKanji }} 様　受付完了
        </h2>
    </x-slot>

    <!-- 完了メッセージとステータス表示 -->
    <div class="text-center mb-8 no-print">
        <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
        <h1 class="text-3xl font-bold text-green-700 mb-2">
            受付完了
        </h1>
        <p class="text-gray-600">予約ID: {{ $reserve->id }} の受付情報を登録しました。</p>
        @if (session('success'))
            <p class="mt-2 text-sm text-green-500 font-semibold">{{ session('success') }}</p>
        @endif
    </div>

    <!-- 予約概要情報 -->
    <div class="bg-indigo-50 p-4 rounded-lg border border-indigo-200 mb-8">
        <p class="text-lg font-bold text-indigo-800 mb-2">予約情報</p>
        <div class="grid grid-cols-2 gap-x-6 gap-y-2 text-sm text-gray-700">
            <p><strong>氏名:</strong> <span class="font-semibold">{{ $reserve->ClitNameKanji }} 様</span> ({{ $reserve->ClitNameKana }})</p>
            <p><strong>予約商品:</strong> {{ $reserve->product->productName ?? '商品名不明' }}</p>
            <p><strong>予約日時:</strong> {{ $reserve->ReserveDate }}</p>
            <p><strong>人数:</strong> {{ $reserve->CliResvCnt }} 名</p>
        </div>
    </div>

    <!-- 確定した受付内容（料金明細） -->
    <h2 class="text-xl font-semibold text-gray-800 mb-4 border-b pb-2">確定受付内容</h2>

    <table class="reception-table w-full text-sm mb-6">
        <thead>
            <tr>
                <th class="w-1/3">区分</th>
                <th class="w-1/3">商品名 / コース名</th>
                <th class="w-1/6 text-right">単価 (円)</th>
                <th class="w-1/6 text-center">数量</th>
                <th class="w-1/6 text-right">小計 (円)</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($receptions as $item)
                <tr>
                    <td>{{ $item->payType == 1 ? 'コース' : 'オプション' }}</td>
                    <td>
                        <span class="font-bold">{{ $item->memo }}</span>
                        @if ($item->payType == 1)
                            <p class="text-xs text-gray-500">コースID: {{ $item->index }}</p>
                        @else
                            <p class="text-xs text-gray-500">オプションID: {{ $item->index }}</p>
                        @endif
                    </td>
                    <td class="text-right">{{ number_format($item->price) }}</td>
                    <td class="text-center">{{ $item->count }}</td>
                    <td class="text-right font-bold">{{ number_format($item->price * $item->count) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="bg-gray-50">
                <th colspan="4" class="text-right text-lg">合計金額 (税込)</th>
                <td id="grandTotalDisplay" class="text-right text-xl font-bold text-red-600">
                    {{ number_format($grandTotal) }}
                </td>
            </tr>
        </tfoot>
    </table>

    <!-- ボタンエリア -->
    <div class="flex justify-center space-x-6 pt-6 no-print">
        <!-- 完了ボタン: 予約一覧へ遷移 -->
        <a href="{{ route('ReserveReception.index', ['reservid' => \Carbon\Carbon::parse($reserve->ReserveDate)->isoFormat('YYYY-MM-DD')] ) }}" class="flex items-center justify-center px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl shadow-lg hover:bg-indigo-700 transition transform hover:scale-105">
            完了 (一覧へ)
        </a>

        <!-- 領収書作成ボタン: PDF (印刷) を作成 -->
        <button type="button" onclick="generateReceipt({{ $reserve->id }})" class="flex items-center justify-center px-8 py-3 bg-teal-500 text-white font-bold rounded-xl shadow-lg hover:bg-teal-600 transition transform hover:scale-105">
            領収書作成
        </button>

        <!-- 戻るボタン: 受付情報入力画面へ戻る (ReserveReception.create) -->
        <a href="{{ route('ReserveReception.create', ['reservid' => $reserve->id]) }}" class="flex items-center justify-center px-8 py-3 bg-gray-400 text-gray-800 rounded-xl shadow-lg hover:bg-gray-500 transition transform hover:scale-105">
            戻る (再編集)
        </a>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script>
        /**
         * 領収書PDF生成ルートへ遷移
         * @param {number} reservid - 予約ID
         */
        function generateReceipt(reservid) {
            // Laravelのルートヘルパーを使ってURLを生成し、新しいタブで開く
            const url = '{{ route('ReserveReception.topdf', ['reservid' => 'TEMP_ID']) }}';
            const finalUrl = url.replace('TEMP_ID', reservid);
            window.open(finalUrl, '_blank');
        }
    </script>
</x-app-layout>
