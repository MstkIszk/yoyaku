<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('オプション/アクセサリー登録') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <x-controltemfileio nameitem="shopname" extension=".accesory"></x-controltemfileio>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2 text-yellow-700">
                        {{ __('オプション/アクセサリーの一括登録・編集#') }}
                    </h3>

                    <form id="accessory-form" method="POST" action="{{ route("user_accesories.store") }}">
                        @csrf

                        <input type="text" name="basecode" value="{{ $user->id }}" style="display: none;">
                        <input type="text" name="shopname" value="{{ $user->spName }}" style="display: none;">

                        {{-- エラーメッセージ表示 --}}
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="overflow-x-auto">
                            <table id="accessory-table" class="list_table">
                                <thead>
                                    <tr>
                                        {{-- IDの表示幅を2remに設定 --}}
                                        <th class="txtWidth_2">
                                            ID
                                        </th>
                                        <th class="txtWidth_30pc">
                                            {{ __('アクセサリー名') }}<span class="text-red-500">*</span>
                                        </th>
                                        {{-- 料金の表示幅を6remに設定 --}}
                                        <th class="txtWidth_6">
                                            {{ __('料金') }} (円)<span class="text-red-500">*</span>
                                        </th>
                                        {{-- 有効/無効のチェックボックス (タイトルのみ) --}}
                                        <th class="txtWidth_5">
                                            {{ __('有効') }}
                                        </th>
                                        <th class="txtWidth_auto">
                                            {{ __('メモ') }}
                                        </th>

                                        <th class="txtWidth_3">
                                            {{ __('順序') }}
                                        </th>                                        
                                        {{-- 操作列を削除 --}}
                                    </tr>
                                </thead>
                                <tbody id="accessory-body" class="bg-white divide-y divide-gray-200">
                                    @php
                                        // 既存のアクセサリーデータを取得。渡されていない場合は空のCollectionとして扱う
                                        $existingAccessories = $accessories ?? collect();
                                        $minRows = 10;
                                        // 初期表示する行数は、既存データの件数と最低10行の多い方とする (10件を超えたらその分表示)
                                        $totalRows = max($minRows, $existingAccessories->count());
                                    @endphp

                                    {{-- 既存データの表示 --}}
                                    @foreach ($existingAccessories as $index => $accessory)
                                    <tr class="hover:bg-yellow-50 data-row">
                                        {{-- ID (2rem幅) --}}
                                        <td class="py-1 px-2 whitespace-nowrap text-xs text-gray-900 seqNo">
                                            {{-- 既存レコードのIDを保存するために必須 --}}
                                            <input type="hidden" name="accessories[{{ $index }}][id]" value="{{ $accessory->id }}">
                                            {{-- productIDは編集時には表示する --}}
                                            <input type="text" name="accessories[{{ $index }}][productID]" 
                                                    value="{{ $accessory->productID }}" 
                                                    class="innerLabel txtWidth_2 " placeholder="自動" readonly>
                                        </td>
                                        {{-- アクセサリー名 (既存レコードは読み取り専用) --}}
                                        <td class="product">
                                            <input type="text" name="accessories[{{ $index }}][productName]" value="{{ old('accessories.' . $index . '.productName', $accessory->productName) }}"
                                                    class="innerLabel txtWidth_90pc" 
                                                    placeholder="{{ __('オプション名') }}" readonly>
                                        </td>
                                        {{-- 料金 (6rem幅, 右寄せ) --}}
                                        <td class="ryokin">
                                            <input type="number" name="accessories[{{ $index }}][price]" value="{{ old('accessories.' . $index . '.price', $accessory->price) }}"
                                                    class="innerText text-right txtWidth_80pc " 
                                                    placeholder="0" min="0" step="1">
                                        </td>
                                        {{-- 有効チェックボックス --}}
                                        <td class="text-center status">
                                            <div class="checkbox-line-button">
                                                <div class="checkbox-button-container">
                                                    {{-- <input type="checkbox" name="accessories[{{ $index }}][IsEnabled]" 
                                                                                  id="accessories[{{ $index }}][IsEnabled]" 
                                                            class="checkbox-line " value="{{old('IsEnabled',$accessory->IsEnabled) }}"> --}}


                                                    {{-- old('IsEnabled',$accessory->IsEnabled)の結果が0以外ならば "checked" を出力 --}}
                                                    <input type="checkbox" 
                                                        name="accessories[{{ $index }}][IsEnabled]" 
                                                        id="accessories[{{ $index }}][IsEnabled]" 
                                                        class="checkbox-line" 
                                                        value="1" 
                                                        {{-- old または $accessory->IsEnabled の値が 0 以外（真）であれば checked 属性を付与 --}}
                                                        @checked(old('accessories.' . $index . '.IsEnabled', $accessory->IsEnabled ?? 1))
                                                    >
                                                            
                                                    <label class="toggle-label" for="accessories[{{ $index }}][IsEnabled]"></label>
                                                </div>
                                            </div>
                                        </td>
                                        {{-- メモ --}}
                                        <td class="py-1 px-2 memo">
                                            <input type="text" name="accessories[{{ $index }}][memo]" value="{{ old('accessories.' . $index . '.memo', $accessory->memo) }}"
                                                    class="innerText txtWidth_96pc" 
                                                    placeholder="{{ __('備考/説明') }}">
                                        </td>

                                        {{-- ★ 追加: 行の入れ替えボタン --}}
                                        <td class="py-1 px-2 text-center row-controls">
                                            <button type="button" class="move-up-btn text-blue-600 hover:text-blue-800 focus:outline-none text-xs mr-1 p-1 rounded-full bg-gray-100 hover:bg-gray-200" title="{{ __('上に移動') }}">
                                                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                            </button>
                                            <button type="button" class="move-down-btn text-blue-600 hover:text-blue-800 focus:outline-none text-xs p-1 rounded-full bg-gray-100 hover:bg-gray-200" title="{{ __('下に移動') }}">
                                                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </button>
                                        </td>


                                        {{-- 操作列を削除 --}}
                                    </tr>
                                    @endforeach

                                    {{-- 初期表示の空行（既存データが$minRows未満の場合に追加、または10件以上でも$totalRowsまで） --}}
                                    @for ($i = $existingAccessories->count(); $i < $totalRows; $i++)
                                    <tr class="hover:bg-yellow-50">
                                        {{-- ID (2rem幅) --}}
                                        <td class="seqNo" style="width: 2rem;">
                                            {{-- 新規行なのでIDは空 --}}
                                            <input type="hidden" name="accessories[{{ $i }}][id]" value="">
                                            <input type="text" name="accessories[{{ $i }}][productID]" class="innerLabel" placeholder="自動" readonly>
                                        </td>
                                        {{-- アクセサリー名 --}}
                                        <td class="product">
                                            <input type="text" name="accessories[{{ $i }}][productName]" value="{{ old('accessories.' . $i . '.productName') }}"
                                                    class="innerText" 
                                                    placeholder="{{ __('オプション名') }}">
                                        </td>
                                        {{-- 料金 (6rem幅, 右寄せ) --}}
                                        <td class="ryokin">
                                            <input type="number" name="accessories[{{ $i }}][price]" value="{{ old('accessories.' . $i . '.price') }}"
                                                    class="innerText" 
                                                    placeholder="0" min="0" step="1">
                                        </td>
                                        {{-- 有効チェックボックス (デフォルトchecked) --}}
                                        <td class="status">

                                            <div class="checkbox-line-button">
                                                <div class="checkbox-button-container">
                                                    <input type="checkbox" name="accessories[{{ $i }}][IsEnabled]" id="accessories[{{ $i }}][IsEnabled]" 
                                                            class="checkbox-line " value="1">
                                                    <label class="toggle-label" for="accessories[{{ $i }}][IsEnabled]"></label>
                                                </div>
                                            </div>
                                        </td>
                                        {{-- メモ --}}
                                        <td class="memo">
                                            <input type="text" name="accessories[{{ $i }}][memo]" value="{{ old('accessories.' . $i . '.memo') }}"
                                                    class="innerText" 
                                                    placeholder="{{ __('備考/説明') }}">
                                        </td>
                                    </tr>

                                    {{-- ★ 追加: 行の入れ替えボタン --}}
                                    <td class="py-1 px-2 text-center row-controls">
                                        <button type="button" class="move-up-btn text-blue-600 hover:text-blue-800 focus:outline-none text-xs mr-1 p-1 rounded-full bg-gray-100 hover:bg-gray-200" title="{{ __('上に移動') }}">
                                            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                        </button>
                                        <button type="button" class="move-down-btn text-blue-600 hover:text-blue-800 focus:outline-none text-xs p-1 rounded-full bg-gray-100 hover:bg-gray-200" title="{{ __('下に移動') }}">
                                            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                        </button>
                                    </td>

                                    
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-between items-center">
                            <button type="button" id="add-row-button" class="link-button bg-green-500 hover:bg-green-600 focus:ring-green-500">
                                {{ __('行を追加') }}
                            </button>
                            <div>
                                <a href="{{ route('dashboard') }}" class="link-button bg-gray-500 hover:bg-gray-600 focus:ring-gray-500 mr-4">
                                    {{ __('キャンセル') }}
                                </a>
                                <button type="submit" class="link-button bg-indigo-600 hover:bg-indigo-700 focus:ring-indigo-500">
                                    {{ __('登録/更新') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const tableBody = document.getElementById('accessory-body');
            const addRowButton = document.getElementById('add-row-button');
            
            // Bladeによってレンダリングされた現在の行数を初期値として取得
            let rowCount = tableBody.rows.length; 

            /**
             * テーブル内の全行のフォームフィールドのインデックス番号とIDを更新する
             * (accessories[i][...])
             */
            function reIndexRows() {
                const rows = tableBody.querySelectorAll('tr');
                rows.forEach((row, newIndex) => {
                    // 1. 各行の input, select, textarea 要素を検索
                    const inputs = row.querySelectorAll('input, select, textarea');

                    inputs.forEach(input => {
                        const oldName = input.getAttribute('name');
                        
                        if (oldName && oldName.startsWith('accessories[')) {
                            // productID 以外の項目は、新しい行番号に合わせてインデックスを置換します。
                            // 例: accessories[5][quantity] -> accessories[2][quantity]
                            const newName = oldName.replace(/accessories\[\d+\]/, `accessories[${newIndex}]`);
                            input.setAttribute('name', newName);
                        }

                        const oldId = input.getAttribute('id');
                        if (oldId && oldId.startsWith('accessories-')) {
                            // ID属性のインデックス部分を新しいインデックスに置換 (例: accessories-5-IsEnabled)
                            const parts = oldId.split('-');
                            if (parts.length >= 3) {
                                // IDはHTMLの一意性とラベル関連付けのために新しい行インデックスに更新するのが一般的です
                                const newId = `accessories-${newIndex}-${parts.slice(2).join('-')}`;
                                input.setAttribute('id', newId);

                                // 関連する label の for 属性も更新
                                const label = row.querySelector(`label[for="${oldId}"]`);
                                if (label) {
                                    label.setAttribute('for', newId);
                                }
                            }
                        }
                        
                    });
                });
                
                // rowCountをテーブルの行数にリセット（行追加時のインデックスに使用）
                rowCount = rows.length;
            }

            /**
             * 隣接する行を入れ替える
             * @param {HTMLTableRowElement} currentRow - 現在の行
             * @param {HTMLTableRowElement} targetRow - 入れ替え対象の行
             */
            function swapRows(currentRow, targetRow) {

                // 1. 行の中から、name属性に "[productID]" を含む input 要素を検索
                const currentProductIDInput = currentRow.querySelector('input[name*="[productID]"]');
                const targetProductIDInput = targetRow.querySelector('input[name*="[productID]"]');
                // 2. その要素から value を取得
                currentProductID = currentProductIDInput ? currentProductIDInput.value : null; 
                targetProductID = targetProductIDInput ? targetProductIDInput.value : null; 

               // 3. productIDの値を相互に入れ替える
               if (currentProductIDInput && targetProductIDInput) {
                   currentProductIDInput.value = targetProductID;
                   targetProductIDInput.value = currentProductID;
                   // これで、各行の持つproductIDの値が相互に入れ替わりました
               }

                // DOMの入れ替え
                if (currentRow.compareDocumentPosition(targetRow) & Node.DOCUMENT_POSITION_FOLLOWING) {
                    // currentRowがtargetRowより前にある場合 (currentRowが上、targetRowが下)
                    targetRow.parentNode.insertBefore(targetRow, currentRow); // targetRowをcurrentRowの前に挿入 (上に移動)
                } else {
                    // targetRowがcurrentRowより前にある場合 (targetRowが上、currentRowが下)
                    currentRow.parentNode.insertBefore(currentRow, targetRow); // currentRowをtargetRowの前に挿入 (下に移動)
                }
                
                // インデックスを再割り当て
                reIndexRows();
            }
            
            // 行の入れ替えボタンのイベント委譲
            tableBody.addEventListener('click', function(event) {
                const target = event.target.closest('.move-up-btn, .move-down-btn');
                if (!target) return;

                const currentRow = target.closest('tr');
                let targetRow = null;

                if (target.classList.contains('move-up-btn')) {
                    targetRow = currentRow.previousElementSibling;
                } else if (target.classList.contains('move-down-btn')) {
                    targetRow = currentRow.nextElementSibling;
                }

                // 入れ替え対象の行が存在し、それが<th>（ヘッダー）でないことを確認
                if (targetRow && targetRow.tagName === 'TR') {
                    event.preventDefault(); // フォームの送信を防ぐ
                    swapRows(currentRow, targetRow);
                }
            });



            /**
             * 新しい行（入力フィールド）をテーブルに追加します。
             */
            function addAccessoryRow() {
                // 新しい行のインデックスとして rowCount を使用し、追加後にインクリメント
                const index = rowCount;
                const newRow = document.createElement('tr');
                newRow.className = 'hover:bg-yellow-50';

                // 行のHTMLコンテンツ (操作列と削除ボタンは含まない)
                newRow.innerHTML = `
                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium text-gray-900 seqNo" style="width: 2rem;">
                        {{-- 既存データ用の隠しフィールド。新規登録時は空。 --}}
                        <input type="hidden" name="accessories[${index}][id]" value="">
                        <input type="text" name="accessories[${index}][productID]" 
                        class="innerLabel txtWidth_2" placeholder="自動" readonly>
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm font-medium product">
                        <input type="text" name="accessories[${index}][productName]" 
                                class="innerLabel txtWidth_10" 
                                placeholder="{{ __('オプション名') }}">
                    </td>
                    <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500 ryokin" style="width: 6rem;">
                        <input type="number" name="accessories[${index}][price]" 
                                class="innerText text-right ryokin" 
                                placeholder="0" min="0" step="1">
                    </td>
                    <td class="py-1 px-2 text-center status">

                        <div class="checkbox-line-button">
                            <div class="checkbox-button-container">
                                <input type="checkbox" name="accessories[${index}][IsEnabled]" id="accessories[${index}][IsEnabled]" 
                                        class="checkbox-line " value="1">
                                <label class="toggle-label" for="accessories[${index}][IsEnabled]"></label>
                            </div>
                        </div>

                    </td>
                    <td class="py-1 px-2 memo">
                        <input type="text" name="accessories[${index}][memo]" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" 
                                placeholder="{{ __('備考/説明') }}">
                    </td>
                `;
                
                tableBody.appendChild(newRow);
                rowCount++; // 行数をインクリメント
            }

            // 行追加ボタンのリスナー
            addRowButton.addEventListener('click', addAccessoryRow);
            
            // NOTE: 削除ボタンのロジックはご要望に従い削除しました。
        });
    </script>
</x-app-layout>
