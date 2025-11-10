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
            /* 絞り込みフォーム用のスタイル */
            .filter-form-group {
                display: flex;
                flex-wrap: wrap;
                gap: 1rem;
                align-items: center;
                margin-bottom: 1.5rem;
                padding: 1rem;
                background-color: #f7f7f7;
                border-radius: 0.5rem;
            }
            .filter-item {
                display: flex;
                flex-direction: column;
            }
            .filter-item label {
                font-weight: bold;
                font-size: 0.875rem;
                margin-bottom: 0.25rem;
            }
            .filter-item input, .filter-item select {
                padding: 0.5rem;
                border: 1px solid #ccc;
                border-radius: 0.25rem;
            }
            .filter-actions {
                align-self: flex-end;
                padding-bottom: 0.5rem;
            }
            .pagination-controls {
                display: flex;
                justify-content: center;
                align-items: center;
                margin-top: 1rem;
            }
            .pagination-button {
                padding: 0.5rem 1rem;
                margin: 0 0.5rem;
                cursor: pointer;
                background-color: #4a5568;
                color: white;
                border: none;
                border-radius: 0.25rem;
                text-decoration: none;
                line-height: 1;
            }
            .pagination-button:disabled {
                background-color: #cccccc;
                cursor: not-allowed;
            }
            #no-data-message {
                display: none;
                padding: 1rem;
                text-align: center;
            }
        </style>

        @if(session('message'))
            <div class="text-red-600 font-bold mb-4">
                {{ session('message') }}
            </div>       
        @endif
        
        <div id="search-form" class="filter-form-group">
            
            <div class="filter-item">
                <label for="ProductID">商品</label>
                <select id="ProductID">
                    <option value="0">すべて</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->productID }}" 
                                {{ (int)$product->productID === (int)$filterProductID ? 'selected' : '' }}>
                            {{ $product->productName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="filter-item">
                <label for="CliTel1">電話番号</label>
                <input type="tel" id="CliTel1" 
                       value="{{ $filterCliTel1 }}" placeholder="電話番号を入力" style="min-width: 150px;">
            </div>

            <div class="filter-item">
                <label for="DateStart">予約日 (開始)</label>
                <input type="date" id="DateStart" 
                       value="{{ $filterDateStart }}" style="min-width: 150px;">
            </div>

            <div class="filter-item">
                <label for="DateEnd">予約日 (終了)</label>
                <input type="date" id="DateEnd" 
                       value="{{ $filterDateEnd }}" style="min-width: 150px;">
            </div>

            <div class="filter-actions">
                <x-primary-button id="search-button" type="button">検索</x-primary-button>
            </div>
        </div>
        
        <div class="list-frame">
            <table>
                <thead>
                    <tr><th>受付日時</th><th>氏名</th><th>予約日</th><th>人数</th><th>　</th></tr>
                </thead>
                <tbody id="reserve-table-body">
                    </tbody>
            </table>
            <p class="p-4 text-gray-600" id="no-data-message">表示できる予約データはありません。</p>
        </div>

        <div class="pagination-controls">
            <button id="prev-page" class="pagination-button" type="button">&lt; 前のページ</button>
            <span id="page-info"></span>
            <button id="next-page" class="pagination-button" type="button">次のページ &gt;</button>
        </div>
        
    </div>

    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/plugin/customParseFormat.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/locale/ja.js"></script>

    <script>
        {{-- 
        // Day.js の設定
        dayjs.extend(dayjs.customParseFormat); 
        dayjs.locale('ja');
        --}}

        // エンドポイントとルートを Blade で定義
        const API_URL = "{{ route('reserve.index') }}"; 
        const ROUTE_SHOW = "{{ route('reserve.show', ':id') }}";
        
        // DOM要素の取得
        const tableBody = document.getElementById('reserve-table-body');
        const noDataMessage = document.getElementById('no-data-message');
        const prevButton = document.getElementById('prev-page');
        const nextButton = document.getElementById('next-page');
        const pageInfoSpan = document.getElementById('page-info');
        const searchButton = document.getElementById('search-button');

        // 現在の検索/ページの状態を保持するオブジェクト
        let currentState = {
            page: 1,
            baseID: {{ $BaseShopID ?? 0 }},
            CliTel1: document.getElementById('CliTel1').value,
            ProductID: document.getElementById('ProductID').value,
            DateStart: document.getElementById('DateStart').value,
            DateEnd: document.getElementById('DateEnd').value,
        };

        /**
         * 予約データをAPIから取得し、テーブルを更新する (jQuery $.ajaxを使用)
         * @param {Object} params - 検索パラメータとページ情報
         */
        function fetchAndRender(params) {
            
            // $.ajax が使用できるように jQuery がロードされている必要があります
            if (typeof jQuery === 'undefined') {
                console.error("jQuery is not loaded. Cannot use $.ajax.");
                return;
            }

            $.ajax({
                url: API_URL, 
                type: 'GET',
                data: params, 
                dataType: 'json', 
                
                beforeSend: function() {
                    // データの読み込み中であることをユーザーに伝える処理（任意）
                },
                
                success: function(data) {
                    // テーブルのレンダリング
                    renderTable(data);
                    
                    // ページネーションコントロールの更新
                    updatePaginationControls(data);
                    
                    // 状態を更新
                    currentState.page = data.current_page;
                },
                
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("データ取得中にエラーが発生しました:", textStatus, errorThrown);
                    
                    noDataMessage.textContent = 'データの取得に失敗しました。';
                    noDataMessage.style.display = 'block';
                    tableBody.innerHTML = '';
                    
                    // ページ情報をリセット
                    updatePaginationControls({current_page: 1, last_page: 1}); 
                }
            });
        }
        
        /**
         * 予約データをテーブルにレンダリングする
         * @param {Object} data - ページネーションデータオブジェクト (Laravelのpaginate()形式)
         */
        function renderTable(data) {
            tableBody.innerHTML = ''; 

            if (data.data && data.data.length > 0) {
                noDataMessage.style.display = 'none';
                
                data.data.forEach(item => {
                    const row = tableBody.insertRow();
                    
                    // 日付フォーマット
                    const reqDate = dayjs(item.ReqDate);
                    const reserveDate = dayjs(item.ReserveDate);
                    const formattedReqDate = `${reqDate.format('YYYY-MM-DD')}<br>${reqDate.format('HH:mm:ss')}`;
                    const formattedReserveDate = `${reserveDate.format('YYYY-MM-DD')}<br>(${reserveDate.format('ddd')}) ${reserveDate.format('HH:mm')}`;

                    // セルデータの定義
                    const cells = [
                        formattedReqDate,
                        `${item.ClitNameKanji}<br>${item.ClitNameKana}`,
                        formattedReserveDate,
                        item.CliResvCnt,
                        `<a href="${ROUTE_SHOW.replace(':id', item.id)}">
                            <button class="bg-gray-800 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">表示</button>
                        </a>`
                    ];

                    cells.forEach(cellHtml => {
                        const cell = row.insertCell();
                        cell.innerHTML = cellHtml;
                    });
                });
            } else {
                noDataMessage.textContent = '表示できる予約データはありません。';
                noDataMessage.style.display = 'block';
            }
        }
        
        /**
         * ページネーションコントロールの状態とテキストを更新する
         * @param {Object} data - ページネーションデータオブジェクト
         */
        function updatePaginationControls(data) {
            // ページ番号表示の更新
            pageInfoSpan.textContent = `${data.current_page} / ${data.last_page}`;

            // ボタンの有効/無効化
            prevButton.disabled = data.current_page === 1;
            nextButton.disabled = data.current_page === data.last_page;
        }

        // --- イベントリスナー設定 ---
        
        // 検索ボタンのイベント
        searchButton.addEventListener('click', function() {
            // フォームから最新の検索条件を取得し、ページを1に戻す
            currentState = {
                page: 1,
                baseID: {{ $BaseShopID ?? 0 }},
                CliTel1: document.getElementById('CliTel1').value,
                ProductID: document.getElementById('ProductID').value,
                DateStart: document.getElementById('DateStart').value,
                DateEnd: document.getElementById('DateEnd').value,
            };
            fetchAndRender(currentState);
        });

        // 「前のページ」ボタンの処理
        prevButton.addEventListener('click', function() {
            if (currentState.page > 1) {
                currentState.page--;
                fetchAndRender(currentState);
            }
        });

        // 「次のページ」ボタンの処理
        nextButton.addEventListener('click', function() {
            // 次のページがあるかどうかは updatePaginationControls でボタンが無効化される
            currentState.page++;
            fetchAndRender(currentState);
        });

        // --- 初回ロード時の実行 ---
        document.addEventListener('DOMContentLoaded', function() {
            // 画面ロード時に一度データを取得
            fetchAndRender(currentState);
        });
    </script>
</x-app-layout>
