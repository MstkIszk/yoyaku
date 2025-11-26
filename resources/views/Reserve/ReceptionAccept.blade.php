<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="/images/favicon-32x32.png">
    <title>予約受付 | {{ $reserve->product->productName ?? '受付画面' }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .reception-table th, .reception-table td {
            padding: 0.5rem;
            border: 1px solid #e5e7eb;
        }
        .reception-table th {
            background-color: #f3f4f6;
            text-align: left;
            font-weight: 600;
        }
    </style>
</head>
<body class="bg-gray-100 p-8">

<div class="max-w-4xl mx-auto bg-white p-6 rounded-xl shadow-lg">
    <h1 class="text-3xl font-bold text-gray-800 mb-4">
        {{ $reserve->user->name ?? '店舗名不明' }} 受付
    </h1>
    <h2 class="text-xl font-semibold text-gray-600 mb-6">
        {{ $reserve->product->productName ?? '商品名不明' }}
    </h2>

    <div class="bg-blue-50 p-4 rounded-lg border border-blue-200 mb-6">
        <p class="text-lg font-bold text-blue-800">予約情報 (ID: {{ $reserve->id }})</p>
        <p>氏名: <span class="font-semibold">{{ $reserve->ClitNameKanji }} 様</span> ({{ $reserve->ClitNameKana }})</p>
        <p>予約日時: {{ $reserve->ReserveDate }}</p>
        <p>人数: {{ $reserve->CliResvCnt }} 名</p>
    </div>

    <!-- 料金計算フォーム -->
    <form id="receptionForm" action="{{ route('ReserveReception.store') }}" method="POST">
        @csrf
        <input type="hidden" name="ReserveID" value="{{ $reserve->id }}">
        
        <table class="reception-table w-full text-sm mb-6">
            <thead>
                <tr>
                    <th class="w-1/3">商品名 / コース名</th>
                    <th class="w-1/5 text-right">単価 (円)</th>
                    <th class="w-1/5 text-center">数量</th>
                    <th class="w-1/5 text-right">小計 (円)</th>
                </tr>
            </thead>
            <tbody>

                {{-- コース名を表示 (グループヘッダーとして利用) --}}
                @if ($reserve->course)
                <tr>
                    <td colspan="4" class="bg-indigo-100 font-bold text-indigo-800">
                        コース: {{ $reserve->course->courseName ?? 'コース不明' }}
                    </td>
                </tr>
                @endif

                {{-- 1. コースに紐づく料金設定をループで表示 --}}
                @php
                    // 予約人数をデフォルト数量とする
                    $defaultCourseCount = $reserve->CliResvCnt;
                    // itemのインデックスを管理するためのカウンター
                    $itemIndex = 0; 
                @endphp

                @forelse ($reserve->course->userCoursePrices as $price)
                    @php
                        // 当日の曜日によって price を決定
                        $currentPrice = $isWeekend ? $price->weekendPrice : $price->weekdayPrice;
                        // コースの最初の料金設定のみに予約人数をデフォルトセット
                        $currentCount = ($itemIndex === 0) ? $defaultCourseCount : 0; 
                    @endphp
                    <tr data-type="1" data-id="{{ $price->courseCode }}_{{ $price->priceCode }}" data-price="{{ $currentPrice }}">
                        <td>
                            {{-- 料金名を表示 --}}
                            <span class="font-bold text-indigo-700 ml-4">{{ $price->priceName ?? '料金不明' }}</span>
                            <p class="text-xs text-gray-500 ml-4">{{ $price->memo ?? '' }}</p>
                        </td>
                        <td class="text-right price-value">{{ number_format($currentPrice) }}</td>
                        <td class="text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button type="button" class="quantity-btn minus-btn bg-red-100 text-red-600 rounded-full w-6 h-6 leading-none">-</button>
                                <input type="number" name="items[{{ $itemIndex }}][count]" class="count-input w-12 text-center border rounded-md" 
                                    value="{{ $currentCount }}" min="0" required data-index="{{ $itemIndex }}">
                                <button type="button" class="quantity-btn plus-btn bg-green-100 text-green-600 rounded-full w-6 h-6 leading-none">+</button>
                            </div>
                            <input type="hidden" name="items[{{ $itemIndex }}][payType]" value="1">          {{-- 1がコース情報であることを示す --}}
                            <input type="hidden" name="items[{{ $itemIndex }}][index]" value="{{ $price->id }}"> {{-- price IDを送信 --}}
                            <input type="hidden" name="items[{{ $itemIndex }}][name]" value="{{ $price->priceName ?? '' }}">          {{-- 1がコース情報であることを示す --}}
                            <input type="hidden" name="items[{{ $itemIndex }}][price]" value="{{ $currentPrice }}" class="input-price">
                            <input type="hidden" name="items[{{ $itemIndex }}][memo]" value="{{ $price->memo ?? '' }}">
                        </td>
                        <td class="text-right subtotal-value">
                            {{ number_format($currentPrice * $currentCount) }}
                        </td>
                    </tr>
                    @php
                        $itemIndex++;
                    @endphp
                @empty
                    <tr>
                        <td colspan="4" class="text-center text-red-500">コースの料金設定が見つかりませんでした。</td>
                    </tr>
                @endforelse

                <!-- 2. オプション商品 -->
                @foreach ($reserve->accessories as $index => $accessory)
                    <tr data-type="2" data-id="{{ $accessory->id }}" data-price="{{ $accessory->price }}">
                        <td>
                            <span class="font-bold">{{ $accessory->productName }}</span>
                            <p class="text-xs text-gray-500">{{ $accessory->memo ?? '' }}</p>
                        </td>
                        <td class="text-right price-value">{{ number_format($accessory->price) }}</td>
                        <td class="text-center">
                            <div class="flex items-center justify-center space-x-2">
                                <button type="button" class="quantity-btn minus-btn bg-red-100 text-red-600 rounded-full w-6 h-6 leading-none">-</button>
                                <input type="number" name="items[{{ $itemIndex }}][count]" class="count-input w-12 text-center border rounded-md" 
                                    value="0" min="0" required data-index="{{ $itemIndex }}">
                                <button type="button" class="quantity-btn plus-btn bg-green-100 text-green-600 rounded-full w-6 h-6 leading-none">+</button>
                            </div>
                            <input type="hidden" name="items[{{ $itemIndex }}][payType]" value="2">         {{-- 2がオプション情報であることを示す --}}
                            <input type="hidden" name="items[{{ $itemIndex }}][index]" value="{{ $accessory->id }}">
                            <input type="hidden" name="items[{{ $itemIndex }}][name]" value="{{ $accessory->productName }}" >
                            <input type="hidden" name="items[{{ $itemIndex }}][price]" value="{{ $accessory->price }}" class="input-price">
                            <input type="hidden" name="items[{{ $itemIndex }}][memo]" value="{{ $accessory->memo ?? '' }}">
                        </td>
                        <td class="text-right subtotal-value">
                            0
                        </td>
                    </tr>
                    @php
                        $itemIndex++;
                    @endphp
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50">
                    <th colspan="3" class="text-right text-lg">合計金額</th>
                    <td id="grandTotal" class="text-right text-xl font-bold text-red-600">0</td>
                </tr>
            </tfoot>
        </table>

        <!-- ボタンエリア -->
        <div class="flex justify-end space-x-4 pt-4">
            <a href="{{ route('ReserveReception.index') }}" class="px-6 py-2 bg-gray-300 text-gray-800 rounded-lg hover:bg-gray-400 transition">キャンセル</a>
            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white font-bold rounded-lg hover:bg-indigo-700 transition">受付を登録</button>
        </div>
    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const table = document.querySelector('.reception-table');
        const grandTotalElement = document.getElementById('grandTotal');

        /**
         * 小計と合計金額を計算し、表示を更新する関数
         */
        function calculateTotal() {
            let grandTotal = 0;
            
            // 各行の処理
            table.querySelectorAll('tbody tr').forEach(row => {
                // コース名グループヘッダー行はスキップ
                if (row.querySelector('td[colspan="4"]')) {
                    return;
                }

                const countInput = row.querySelector('.count-input');
                // data-price属性から単価を取得
                const priceValue = parseInt(row.dataset.price);
                const subtotalElement = row.querySelector('.subtotal-value');
                
                // 数量が取得できない場合は 0
                const count = parseInt(countInput ? countInput.value : 0) || 0;

                // 小計計算
                const subtotal = priceValue * count;
                grandTotal += subtotal;

                // 小計表示の更新
                if (subtotalElement) {
                   subtotalElement.textContent = subtotal.toLocaleString('ja-JP');
                }
            });

            // 合計金額表示の更新
            grandTotalElement.textContent = grandTotal.toLocaleString('ja-JP');
        }

        /**
         * 数量入力フィールドの変更イベントリスナーを設定
         */
        table.querySelectorAll('.count-input').forEach(input => {
            input.addEventListener('change', (e) => {
                // 負の値を入力できないようにする
                let value = parseInt(e.target.value);
                if (isNaN(value) || value < 0) {
                    e.target.value = 0;
                }
                calculateTotal();
            });
            input.addEventListener('input', (e) => {
                 // リアルタイムで入力値が変化した場合も計算
                 calculateTotal();
            });
        });

        /**
         * [+] [-] ボタンのクリックイベントリスナーを設定
         */
        table.querySelectorAll('.quantity-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                // クリックされたボタンから最も近い行を取得
                const row = e.target.closest('tr');
                const countInput = row.querySelector('.count-input');

                if (!countInput) return; // 数量入力がない行（例: コース名行）はスキップ

                let count = parseInt(countInput.value) || 0;

                if (e.target.classList.contains('plus-btn')) {
                    count++;
                } else if (e.target.classList.contains('minus-btn')) {
                    count = Math.max(0, count - 1); // 0未満にはしない
                }

                countInput.value = count;
                calculateTotal();
            });
        });

        // 初期表示時に合計を計算
        calculateTotal();

        /**
         * フォーム送信時の処理
         */
        document.getElementById('receptionForm').addEventListener('submit', function(e) {
            // クライアント側で最終チェックが必要な場合はここに追加
        });
    });
</script>

</body>
</html>