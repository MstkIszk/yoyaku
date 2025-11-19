<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
                <!-- 1. コース商品 -->
                @php
                    // 予約人数をデフォルト数量とする
                    $defaultCourseCount = $reserve->CliResvCnt;
                @endphp
                <tr data-type="1" data-id="{{ $reserve->Courseid }}" data-price="{{ $coursePrice }}">
                    <td>
                        <span class="font-bold text-indigo-700">{{ $reserve->course->courseName ?? 'コース不明' }}</span>
                        <p class="text-xs text-gray-500">{{ $reserve->course->memo ?? '' }}</p>
                    </td>
                    <td class="text-right price-value">{{ number_format($coursePrice) }}</td>
                    <td class="text-center">
                        <div class="flex items-center justify-center space-x-2">
                            <button type="button" class="quantity-btn minus-btn bg-red-100 text-red-600 rounded-full w-6 h-6 leading-none">-</button>
                            <input type="number" name="items[0][count]" class="count-input w-12 text-center border rounded-md" 
                                value="{{ $defaultCourseCount }}" min="0" required data-index="0">
                            <button type="button" class="quantity-btn plus-btn bg-green-100 text-green-600 rounded-full w-6 h-6 leading-none">+</button>
                        </div>
                        <input type="hidden" name="items[0][payType]" value="1">
                        <input type="hidden" name="items[0][index]" value="{{ $reserve->Courseid }}">
                        <input type="hidden" name="items[0][price]" value="{{ $coursePrice }}" class="input-price">
                        <input type="hidden" name="items[0][memo]" value="{{ $reserve->course->memo ?? '' }}">
                    </td>
                    <td class="text-right subtotal-value">
                        {{ number_format($coursePrice * $defaultCourseCount) }}
                    </td>
                </tr>

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
                                <input type="number" name="items[{{ $index + 1 }}][count]" class="count-input w-12 text-center border rounded-md" 
                                    value="0" min="0" required data-index="{{ $index + 1 }}">
                                <button type="button" class="quantity-btn plus-btn bg-green-100 text-green-600 rounded-full w-6 h-6 leading-none">+</button>
                            </div>
                            <input type="hidden" name="items[{{ $index + 1 }}][payType]" value="2">
                            <input type="hidden" name="items[{{ $index + 1 }}][index]" value="{{ $accessory->id }}">
                            <input type="hidden" name="items[{{ $index + 1 }}][price]" value="{{ $accessory->price }}" class="input-price">
                            <input type="hidden" name="items[{{ $index + 1 }}][memo]" value="{{ $accessory->memo ?? '' }}">
                        </td>
                        <td class="text-right subtotal-value">
                            0
                        </td>
                    </tr>
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
                const countInput = row.querySelector('.count-input');
                const priceValue = parseInt(row.dataset.price);
                const subtotalElement = row.querySelector('.subtotal-value');
                
                const count = parseInt(countInput.value) || 0;

                // 小計計算
                const subtotal = priceValue * count;
                grandTotal += subtotal;

                // 小計表示の更新
                subtotalElement.textContent = subtotal.toLocaleString('ja-JP');
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
         * フォーム送信時の処理（数量が0の場合の項目を除外するなどの処理は、サーバー側で対応済み）
         */
        document.getElementById('receptionForm').addEventListener('submit', function(e) {
            // クライアント側で最終チェックが必要な場合はここに追加
            // 例: 合計が0円でないか、など
        });
    });
</script>

</body>
</html>