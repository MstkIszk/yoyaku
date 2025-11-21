
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('オプション/アクセサリー登録') }}
        </h2>
    </x-slot>

    <style>
    .prise-new-req {
        margin 4px;
        font-size: 1.2rem;
        color: blue;
    }    
    .prise-edit-button {
        /* 1. margin-leftの追加 */
        margin-left: 2px;
        
        cursor: pointer;
        padding: 2px 2px; /* 枠のサイズを指定 */

        /* 2. 金色のボタン形状 */
        /* 背景をグラデーションにして深みを出し、枠線も金色に */
        background: linear-gradient(to bottom, #FFD700 0%, #FFC700 100%); /* Gold color */
        border: 2px solid #DAA520; /* Darker gold border */
        color: #333333; /* 文字色は視認性の高いダークグレーに */
        
        border-radius: 4px; /* 角を少し丸める */
        width: 16px;
        height: 18px;
        
        /* ホバー時のシャドウ変化のために初期のシャドウを透明で設定 */
        box-shadow: 0 0 0 0px transparent;
    }
    </style>



    <div class="py-12">
        {{-- コントロールパネルのファイル入出力機能 (仮に予約コース用として表示) --}}
        <x-controltemfileio nameitem="shopAndproduct" extension=".course"></x-controltemfileio>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-bold mb-4 border-b pb-2 text-yellow-700">
                        {{-- productが存在する場合のみ商品名を表示 --}}
                        {{ __('商品:') }} {{ $product->productName ?? '商品ID ' . $productID }} {{ __('の予約コース一括登録・編集') }}
                    </h3>

                    <form id="course-form" method="POST" action="{{ route("user_courses.store") }}">
                        @csrf

                        {{-- 必須の隠しフィールド --}}
                        <input type="text" name="basecode" value="{{ $user->id }}" style="display: none;">
                        <input type="text" name="destProductID" value="{{ $productID }}" {{-- style="display: none;" --}}>
                        <input type="text" name="shopname" value="{{ $user->spName }}" style="display: none;">
                        <input type="text" name="shopAndproduct" value="{{ $user->spName }}ー{{ $product->productName }}" style="display: none;">

                        {{-- エラーメッセージ表示
                        @if ($errors->any())
                            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                         --}}

                        <div class="overflow-x-auto">
                            <table id="course-table" class="list_table min-w-full divide-y divide-gray-200">
                                <thead>
                                    <tr>
                                        <th class="txtWidth_2 text-center">{{ __('ID') }}</th>
                                        <th class="txtWidth_30pc text-left">{{ __('コース名') }}<span class="text-red-500">*</span></th>
                                        <th class="txtWidth_11 text-right">{{ __('平日料金') }}／{{ __('休日料金') }}
                                            <span class="text-red-500">*</span>
                                        </th>
                                        <th class="txtWidth_2 text-center">{{ __('有効') }}</th>
                                        <th class="txtWidth_auto text-left">{{ __('メモ') }}</th>
                                        <th class="txtWidth_3 text-center">{{ __('順序') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="course-body" class="bg-white divide-y divide-gray-200">
                                    @php
                                        // 既存のコースデータを取得。渡されていない場合は空のCollectionとして扱う
                                        $existingCourses = $courses ?? collect();
                                        $minRows = 10;
                                        // 初期表示する行数は、既存データの件数と最低10行の多い方とする
                                        $totalRows = max($minRows, $existingCourses->count());
                                    @endphp

                                    {{-- 既存データの表示 --}}
                                    @foreach ($existingCourses as $index => $course)
                                    <tr class="hover:bg-yellow-50 data-row">
                                        {{-- ID (2rem幅) --}}
                                        <td class="py-1 px-2 whitespace-nowrap text-xs text-gray-900 text-center seqNo">
                                            {{-- 既存レコードのIDを保存するために必須 --}}
                                            <input type="hidden" name="courses[{{ $index }}][id]" value="{{ $course->id }}">
                                            <input type="text" name="courses[{{ $index }}][courseCode]" 
                                                    value="{{ $course->courseCode }}" 
                                                    class="innerLabel txtWidth_2 text-center" placeholder="自動" readonly>
                                        </td>
                                        {{-- コース名 --}}
                                        <td class="courseName">
                                            <input type="text" name="courses[{{ $index }}][courseName]" 
                                                    value="{{ old('courses.' . $index . '.courseName', $course->courseName) }}"
                                                    class="innerLabel txtWidth_90pc" 
                                                    placeholder="{{ __('コース名') }}">
                                        </td>
                                        {{-- 平日料金 休日料金 --}}
                                        <td class="price_group">
                                            {{-- ★ UserCoursePrice のデータをループで表示する部分 --}}
                                            <div class="text-xs text-left w-full mt-2 space-y-1">
                                                {{-- コントローラで UserCoursePrice リレーションが使われているので 配列名もリレーション関数名となる --}}
                                                @forelse ($course->userCoursePrices as $priceItem)
                                                    <div class="p-1 border-b border-gray-100 last:border-b-0">
                                                        {{-- priceNameを表示 --}}
                                                        <p class="font-semibold text-gray-800">{{ $priceItem->priceName ?? __('料金プラン') }}</p>
                                                        <p class="text-gray-600">
                                                            {{-- 平日料金をカンマ区切りで表示 --}}
                                                            {{ __('平') }} ￥{{ number_format($priceItem->weekdayPrice ?? 0) }} 
                                                            <span class="text-gray-400">／</span>
                                                            {{-- 休日料金をカンマ区切りで表示 --}}
                                                            {{ __('休') }} ￥{{ number_format($priceItem->weekendPrice ?? 0) }}
                                                        </p>
                                                    </div>
                                                @empty
                                                    <div class="p-1 text-red-500">
                                                        {{ __('料金情報がありません') }}
                                                    </div>
                                                @endforelse


                                            {{-- 平日料金 --}}
                                            <input type="hidden" name="courses[{{ $index }}][weekdayPrice]" 
                                                    value="{{ old('courses.' . $index . '.weekdayPrice', $course->weekdayPrice) }}"
                                                    class="innerText ryokin" placeholder="0" min="0" step="1" required>
                                            {{-- 休日料金 --}}
                                            <input type="hidden" name="courses[{{ $index }}][weekendPrice]" 
                                                    value="{{ old('courses.' . $index . '.weekendPrice', $course->weekendPrice) }}"
                                                    class="innerText ryokin" placeholder="0" min="0" step="1" required>

                                            {{-- 料金変更ページへのリンク。courseIDとして $course->id を渡す --}}
                                            <a href="{{ route('UserCoursePrice.create', ['course_id' => $course->id]) }}" 
                                                class="prise-edit-button">
                                                変更
                                            </a>
                                        </td>
                                        {{-- 有効チェックボックス --}}
                                        <td class="text-center status">
                                            <div class="checkbox-line-button">
                                                <div class="checkbox-button-container">
                                                    <input type="checkbox" 
                                                        name="courses[{{ $index }}][IsEnabled]" 
                                                        id="courses-{{ $index }}-IsEnabled" 
                                                        class="checkbox-line" 
                                                        value="1" 
                                                        {{-- old または $course->IsEnabled の値が 0 以外（真）であれば checked 属性を付与 --}}
                                                        @checked(old('courses.' . $index . '.IsEnabled', $course->IsEnabled ?? 1))
                                                    >
                                                    <label class="toggle-label" for="courses-{{ $index }}-IsEnabled"></label>
                                                </div>
                                            </div>
                                        </td>
                                        {{-- メモ --}}
                                        <td class="py-1 px-2 memo">
                                            <input type="text" name="courses[{{ $index }}][memo]" 
                                                    value="{{ old('courses.' . $index . '.memo', $course->memo) }}"
                                                    class="innerText txtWidth_96pc" 
                                                    placeholder="{{ __('備考/説明') }}">
                                        </td>

                                        {{-- 行の入れ替えボタン --}}
                                        <td class="py-1 px-2 text-center row-controls">
                                            <button type="button" class="updown-button" title="{{ __('上に移動') }}">
                                                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                            </button>
                                            <button type="button" class="updown-button" title="{{ __('下に移動') }}">
                                                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach

                                    {{-- 初期表示の空行 --}}
                                    @for ( $i = $existingCourses->count() ; $i < $totalRows; $i++)
                                    <tr class="hover:bg-yellow-50">
                                        {{-- ID/courseCode --}}
                                        <td class="py-1 px-2 whitespace-nowrap text-xs text-gray-900 text-center seqNo">
                                            <input type="hidden" name="courses[{{ $i }}][id]" value="">
                                            <input type="text" name="courses[{{ $i }}][courseCode]" class="innerLabel txtWidth_2 text-center" placeholder="自動" readonly>
                                        </td>
                                        {{-- コース名 --}}
                                        <td class="courseName">
                                            <input type="text" name="courses[{{ $i }}][courseName]" 
                                                    value="{{ old('courses.' . $i . '.courseName') }}"
                                                    class="innerText txtWidth_90pc" 
                                                    placeholder="{{ __('コース名') }}">
                                        </td>
                                        <td class="price_group">
                                            <div class="prise-new-req">料金は保存後に登録</div>
                                            {{-- 平日料金 --}}
                                            <input type="hidden" name="courses[{{ $i }}][weekdayPrice]" 
                                                    value="{{ old('courses.' . $i . '.weekdayPrice') }}"
                                                    class="innerText ryokin" placeholder="0" min="0" step="1">
                                            {{-- 休日料金 --}}
                                            <input type="hidden" name="courses[{{ $i }}][weekendPrice]" 
                                                    value="{{ old('courses.' . $i . '.weekendPrice') }}"
                                                    class="innerText ryokin" placeholder="0" min="0" step="1">
                                        </td>
                                        {{-- 有効チェックボックス (デフォルトchecked) --}}
                                        <td class="text-center status">
                                            <div class="checkbox-line-button">
                                                <div class="checkbox-button-container">
                                                    <input type="checkbox" name="courses[{{ $i }}][IsEnabled]" 
                                                            id="courses-{{ $i }}-IsEnabled" 
                                                            class="checkbox-line" value="1" checked>
                                                    <label class="toggle-label" for="courses-{{ $i }}-IsEnabled"></label>
                                                </div>
                                            </div>
                                        </td>
                                        {{-- メモ --}}
                                        <td class="py-1 px-2 memo">
                                            <input type="text" name="courses[{{ $i }}][memo]" 
                                                    value="{{ old('courses.' . $i . '.memo') }}"
                                                    class="innerText txtWidth_96pc" 
                                                    placeholder="{{ __('備考/説明') }}">
                                        </td>

                                        {{-- 行の入れ替えボタン --}}
                                        <td class="py-1 px-2 text-center row-controls">
                                            <button type="button" class="updown-button" title="{{ __('上に移動') }}">
                                                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                                            </button>
                                            <button type="button" class="updown-button" title="{{ __('下に移動') }}">
                                                <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                            </button>
                                        </td>
                                    </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        <div class="mt-6 flex justify-between items-center">
                            <button type="button" id="add-row-button" class="link-button bg-green-500 hover:bg-green-600 focus:ring-green-500">
                                {{ __('行を追加') }}
                            </button>
                            <div>
                                {{-- 適切な戻り先ルートに変更してください --}}
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
            const tableBody = document.getElementById('course-body');
            const addRowButton = document.getElementById('add-row-button');
            
            // Bladeによってレンダリングされた現在の行数を初期値として取得
            let rowCount = tableBody.rows.length; 

            /**
             * テーブル内の全行のフォームフィールドのインデックス番号とIDを更新する
             * (courses[i][...])
             */
            function reIndexRows() {
                const rows = tableBody.querySelectorAll('tr');
                rows.forEach((row, newIndex) => {
                    // 1. 各行の input, select, textarea 要素を検索
                    const inputs = row.querySelectorAll('input, select, textarea');
                    
                    // courseCode (ID) フィールドを更新
                    const courseCodeInput = row.querySelector('input[name*="[courseCode]"]');
                    if (courseCodeInput) {
                        // courseCodeは表示順として使用するため、1から始まる連番を割り当てます
                        courseCodeInput.value = newIndex + 1;
                    }

                    inputs.forEach(input => {
                        const oldName = input.getAttribute('name');
                        
                        if (oldName && oldName.startsWith('courses[')) {
                            // 新しい行番号に合わせてインデックスを置換します。
                            const newName = oldName.replace(/courses\[\d+\]/, `courses[${newIndex}]`);
                            input.setAttribute('name', newName);
                        }

                        const oldId = input.getAttribute('id');
                        if (oldId && oldId.startsWith('courses-')) {
                            // ID属性のインデックス部分を新しいインデックスに置換 (例: courses-5-IsEnabled)
                            const parts = oldId.split('-');
                            if (parts.length >= 3) {
                                const newId = `courses-${newIndex}-${parts.slice(2).join('-')}`;
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
                // DOMの入れ替え
                if (currentRow.compareDocumentPosition(targetRow) & Node.DOCUMENT_POSITION_FOLLOWING) {
                    // currentRowがtargetRowより前にある場合 (currentRowが上、targetRowが下)
                    targetRow.parentNode.insertBefore(targetRow, currentRow); // targetRowをcurrentRowの前に挿入 (上に移動)
                } else {
                    // targetRowがcurrentRowより前にある場合 (targetRowが上、currentRowが下)
                    currentRow.parentNode.insertBefore(currentRow, targetRow); // currentRowをtargetRowの前に挿入 (下に移動)
                }
                
                // courseCodeとインデックスを再割り当て
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

                // 入れ替え対象の行が存在し、それが<tr>（テーブルボディ内の行）であることを確認
                if (targetRow && targetRow.tagName === 'TR' && targetRow.parentElement === tableBody) {
                    event.preventDefault(); // フォームの送信を防ぐ
                    swapRows(currentRow, targetRow);
                }
            });

            // 初回ロード時にも courseCode を連番にセットする
            reIndexRows();


            /**
             * 新しい行（入力フィールド）をテーブルに追加します。
             */
            function addCourseRow() {
                // 新しい行のインデックスとして rowCount を使用し、追加後にインクリメント
                const index = rowCount;
                const newRow = document.createElement('tr');
                newRow.className = 'hover:bg-yellow-50';

                // courseCode は新しい行のインデックス + 1
                const newCourseCode = index + 1;

                // 行のHTMLコンテンツ
                newRow.innerHTML = `
                    <td class="py-1 px-2 whitespace-nowrap text-xs text-gray-900 text-center seqNo">
                        {{-- 既存データ用の隠しフィールド。新規登録時は空。 --}}
                        <input type="hidden" name="courses[${index}][id]" value="">
                        <input type="text" name="courses[${index}][courseCode]" 
                        class="innerLabel txtWidth_2 text-center" value="${newCourseCode}" readonly>
                    </td>
                    <td class="courseName">
                        <input type="text" name="courses[${index}][courseName]" 
                                class="innerText txtWidth_90pc" 
                                placeholder="{{ __('コース名') }}">
                    </td>
                    <td class="price_group">
                        <div class="prise-new-req">料金は保存後に登録</div>
                        <input type="hidden" name="courses[${index}][weekdayPrice]" 
                                class="innerText ryokin" 
                                placeholder="0" min="0" step="1">
                        <input type="hidden" name="courses[${index}][weekendPrice]" 
                                class="innerText ryokin" 
                                placeholder="0" min="0" step="1">
                    </td>
                    <td class="text-center status">
                        <div class="checkbox-line-button">
                            <div class="checkbox-button-container">
                                <input type="checkbox" name="courses[${index}][IsEnabled]" 
                                        id="courses-${index}-IsEnabled" 
                                        class="checkbox-line" value="1" checked>
                                <label class="toggle-label" for="courses-${index}-IsEnabled"></label>
                            </div>
                        </div>
                    </td>
                    <td class="py-1 px-2 memo">
                        <input type="text" name="courses[${index}][memo]" 
                                class="innerText txtWidth_96pc" 
                                placeholder="{{ __('備考/説明') }}">
                    </td>
                    <td class="py-1 px-2 text-center row-controls">
                        <button type="button" class="updown-button" title="{{ __('上に移動') }}">
                            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
                        </button>
                        <button type="button" class="updown-button" title="{{ __('下に移動') }}">
                            <svg class="w-4 h-4 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </button>
                    </td>
                `;
                
                tableBody.appendChild(newRow);
                // reIndexRows()を呼び出すことで、新しい行を含めて全行のインデックスとcourseCodeが更新されます
                reIndexRows();
            }

            // 行追加ボタンのリスナー
            addRowButton.addEventListener('click', addCourseRow);
        });
    </script>

</x-app-layout>
