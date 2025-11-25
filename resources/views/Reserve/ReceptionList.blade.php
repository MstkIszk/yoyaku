<!-- Laravel Blade Template: Reserve/ReceptionList.blade.php -->
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $targetDate }} の予約者一覧
        </h2>
    </x-slot>

    <!-- 必要な外部ライブラリの追加 -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://yubinbango.github.io/yubinbango/yubinbango.js" charset="UTF-8"></script>

    <!-- 基本的なテーブルのスタイル定義 -->
    <style>
        /* 親要素: name-box */
        .name-box {
            display: flex;          /* 横並びにする */
            align-items: center;    /* 垂直方向の中央揃え (ボタンとテキストのベースラインを合わせる) */
            gap: 0.5rem;            /* 要素間にわずかなスペースを追加 (Tailwind: space-x-2 相当) */
            width: 100%;            /* 親要素の幅いっぱいに広がる */
        }
        
        /* 左側の氏名部分 */
        .name-box .left-name {
            flex-grow: 1;           /* 残りのスペースを全て占有する */
            min-width: 0;           /* オーバーフロー対策 */
        }
        
        /* 右側のボタン部分 */
        .name-box .right-button {
            /* flex-shrink: 0; は通常不要だが、念のため追加 */
            flex-shrink: 0;         /* 縮小させず、内容の幅を維持する */
            text-align: right;      /* ボタン内要素を右寄せ */
        }
        /* 画面幅が 799px 以下の場合に適用 */
        @media (max-width: 799px) {
            
            /* name-box 全体を縦並び (flex-direction: column) に変更 */
            .name-box {
                flex-direction: column; /* 要素を縦に重ねて表示 */
                align-items: flex-start; /* 左寄せに変更 (必要に応じて) */
                gap: 0.25rem; /* 縦積み時の間隔を少し狭くする */
            }

            /* 左側の氏名部分 */
            .name-box .left-name {
                width: 100%; /* 全幅に広げる */
                margin-bottom: 0.25rem; /* ボタンとの間に少し間隔を空ける */
                /* text-align: left; はデフォルトなので通常不要 */
            }

            /* 右側のボタン部分 */
            .name-box .right-button {
                width: 100%; /* ボタンも全幅に広げると見やすい */
                flex-shrink: 1;
                /* ボタン全体を右寄せにしたい場合は以下を設定 */
                text-align: right; 
                /* もしボタンを中央に配置したい場合は text-align: center; を使用 */
            }
            
            /* ボタン自体がインライン要素の場合、aタグなどをブロック要素にする調整が必要になることがあります */
            .name-box .right-button a {
                /* ボタンの a タグをブロック要素にして width: 100% が効くようにする（デザイン次第で調整） */
                /* display: block; */ 
            }
        }
        /* 画面幅が 799px 以下の場合に適用 */
        @media (max-width: 699px) {
            .list_table .address {
                display: none;                /* 要素を非表示にする */
            }      
        }

        /* 商品ブロック間の間隔 */
        .product-block {
            margin-bottom: 2.5rem; 
        }

        /* モーダル関連スタイル */
        /* モーダル全体: 初期非表示・画面中央固定配置 */
        .modal-hidden-center {
            position: fixed; 
            top: 0; right: 0; bottom: 0; left: 0; 
            z-index: 50;     
            display: flex;   
            align-items: center; 
            justify-content: center; 
            transition: opacity 0.3s ease-out; /* 透明度にもトランジションを適用 */
        }
        
        /* 修正: 初期非表示状態のスタイルを別クラスとして定義 */
        .modal-initial-hide {
            opacity: 0;      
            pointer-events: none; 
        }
        /* 修正: 表示状態のスタイル (JavaScriptで付与/削除を切り替える) */
        .modal-visible {
            opacity: 1;
            pointer-events: auto;
        }

        /* モーダルコンテンツの基本スタイル */
        .modal-content-base {
            background-color: white; 
            width: 100%; 
            max-width: 32rem; 
            margin-left: auto; 
            margin-right: auto; 
            border-radius: 1rem; 
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); 
            z-index: 50; 
            overflow-y: auto; 
        }
        
        /* モーダルヘッダーの基本スタイル (py-4 px-6 border-b border-gray-200 bg-indigo-600 rounded-t-xl) */
        .modal-header {
            padding: 1rem 1.5rem; /* py-4 px-6 */
            border-bottom: 1px solid #e5e7eb; /* border-b border-gray-200 */
            background-color: #4f46e5; /* bg-indigo-600 */
            border-top-left-radius: 1rem; /* rounded-t-xl */
            border-top-right-radius: 1rem; /* rounded-t-xl */
        }

        .modal-overlay {
            background-color: rgba(0, 0, 0, 0.75);
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }
        /* モーダルコンテンツのトランジション用クラス */
        .modal-content-transition {
            transform: scale(0.95); 
            transition: transform 0.3s ease-out;
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

                <!-- 日付選択フォーム -->
                <div class="mb-6 flex items-center space-x-4">
                    <label for="date-selector" class="text-lg font-medium text-gray-700">日付を選択:</label>
                    <input type="date" id="date-selector" value="{{ $targetDate }}" 
                            class="border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    <button id="date-change-button" 
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150">
                        日付変更
                    </button>
                </div>

                <!-- 商品ごとの予約一覧テーブルコンテナ -->
                <div id="reservation-list-container" class="article_frame">
                    @forelse ($ShopInf->products as $product)
                        <div class="product-block bg-gray-50 p-4 rounded-lg shadow-md" 
                             data-base-code="{{ $ShopInf->id }}" 
                             data-product-id="{{ $product->id }}">
                            
                            <div class="flex items-center justify-between mb-4 border-b pb-2">
                                <h3 class="text-xl font-bold text-indigo-700">
                                    {{ $product->productName }} (ID: {{ $product->id }})
                                </h3>
                                
                                <!-- [当日受付]ボタンをループ内に配置 -->
                                <button class="open-walkin-modal-button bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150 text-sm"
                                        data-product-id="{{ $product->id }}">
                                    当日受付
                                </button>
                            </div>

                            <table class="list_table">
                                <thead class="bg-indigo-50">
                                    <tr>
                                        <th class="product">氏名 (漢字)<br>(カナ)</th>
                                        <th class="status">予約コース</th>
                                        <th class="count">予約人数</th>
                                        <th class="product">電話番号<br>メール</th>
                                        <th class="address">住所</th>
                                    </tr>
                                </thead>
                                <!-- 予約データが挿入される場所 -->
                                <tbody class="bg-white divide-y divide-gray-200 table-body" id="table-body-{{ $product->id }}">
                                    <tr><td colspan="7" class="text-center py-4 text-gray-500 loading-indicator">予約データを読み込み中...</td></tr>
                                </tbody>
                            </table>
                            
                            <!-- 予約がない場合のメッセージ -->
                            <p class="no-reservations hidden mt-4 text-center text-gray-500 font-medium"></p>

                        </div>
                    @empty
                        <p class="text-center text-gray-500">この店舗には有効な商品が登録されていません。</p>
                    @endforelse
                </div>

            </div>
        </div>
    </div>
    <!-- 当日受付ポップアップ (Modal) -->
    <!-- 修正: CSSで定義した初期非表示クラスを追加 -->
    <div id="walkin-modal" class="modal-hidden-center modal-initial-hide">
        <!-- Overlay -->
        <div class="modal-overlay"></div>

        <!-- Modal Content -->
        <div class="modal-content-base modal-content-transition transform scale-95">
            <div class="article_title_box">

            <!-- Modal Header -->
            <!-- modal-header クラスに集約 -->
            <div class="modal-header">
                <h3 class="text-2xl font-semibold text-white">当日受付情報入力</h3>
            </div>

            <!-- Modal Body (Form) -->
            <form id="walkin-form" class="h-adr p-6" method="post" action="{{ route('reserve.store') }}">
                @csrf
                <!-- 郵便番号自動入力用 -->
                <span class="p-country-name" style="display:none;">Japan</span>

                <!-- 必須入力欄 -->
                <div class="space-y-4">
                    <x-rTextbox class="w-full" name="CliTel1" id="CliTel1" required value="{{old('CliTel1')}}">電話番号:</x-rTextbox>
                    <div class="flex items-center space-x-3">
                        <input type="button" id="refTelButton" value="　検索　" 
                                class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150 cursor-pointer text-sm" />
                        <Label id="RefMessage" class="text-sm text-gray-500">最終予約情報から住所氏名等を引用します。</Label>
                    </div>

                    <x-rTextbox class="w-full" name="ClitNameKanji" required value="{{old('ClitNameKanji')}}">氏名 (漢字):</x-rTextbox>
                    
                    <x-rTextbox class="p-postal-code w-full" name="CliAddrZip" required value="{{old('CliAddrZip')}}">郵便番号:</x-rTextbox>
                    
                    <x-rTextbox class="w-full" type="number" name="CliResvCnt" required value="{{old('CliResvCnt')}}">人数:</x-rTextbox>
                    
                    <!--  コースID選択 (当日受付のため、選択された商品に紐づくコースのみをJSで表示) -->
                    <div class="mt-2">
                        <label for="Courseid" class="block text-sm font-medium text-gray-700">利用コース:</label>
                        <!-- ここに選択肢を動的に挿入します -->
                        <select id="Courseid" name="Courseid" required class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 mt-1">
                            <!-- JSによってオプションが挿入されます -->
                        </select>
                    </div>
 
                    <!-- 非表示項目 -->
                    <input type="hidden" name="Baseid" id="Baseid" value="{{ $ShopInf->id }}">
                    <input type="hidden" name="Productid" id="Productid" value=""> <!-- JSで選択されたコースIDから設定 -->
                    <input type="hidden" name="ReqDate" id="ReqDate" value="{{ $targetDate }}">
                    <input type="hidden" name="ReserveDate" id="ReserveDate" value="{{ $targetDate }}">
                    <input type="hidden" name="ClitNameKana" id="ClitNameKana" value="">
                    
                    <input type="hidden" name="CliAddrPref" id="CliAddrPref" class="p-region" value="{{old('CliAddrPref')}}">
                    <input type="hidden" name="CliAddrCity" id="CliAddrCity" class="p-locality" value="{{old('CliAddrCity')}}">
                    <input type="hidden" name="CliAddrOther" id="CliAddrOther" class="p-street-address p-extended-address" value="{{old('CliAddrOther')}}">

                    <input type="hidden" name="CliEMail" id="CliEMail" value="">
                    <input type="hidden" name="CliResvType" id="CliResvType" value="1">
                    <input type="hidden" name="CliWaysPay" id="CliWaysPay" value="1">
                    <input type="hidden" name="MessageText" id="MessageText" value="">
                    <!-- UpdateDateはLaravel側で自動設定されることが多いが、念のため本日日付で送信 -->
                    <input type="hidden" name="UpdateDate" id="UpdateDate" value="{{ date('Y-m-d H:i:s') }}">
                    <input type="hidden" name="Status" id="Status" value="1">
                </div>

                <!-- Modal Footer (Buttons) -->
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" id="cancel-walkin-modal" 
                            class="bg-gray-400 hover:bg-gray-500 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150">
                        キャンセル
                    </button>
                    <button type="submit" id="submit-walkin-form" 
                            class="bg-teal-600 hover:bg-teal-700 text-white font-bold py-2 px-4 rounded-lg shadow transition duration-150">
                        登録/受付
                    </button>
                </div>
            </form>
            </div>
        </div>
    </div>


    <!-- JavaScriptセクション -->
    <script>
        // Bladeから商品データをJavaScriptに渡す（JSONエンコード）
        const shopProducts = @json($ShopInf->products);

        document.addEventListener('DOMContentLoaded', function() {
            // CSRFトークンを取得
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const reservationBlocks = document.querySelectorAll('.product-block');
            const targetDateInput = document.getElementById('date-selector');
            const dateChangeButton = document.getElementById('date-change-button');
            // 受付ボタンの基本URLをBladeで生成
            // Route::get('/reception/{reservid?}', ...) に対応
            const receptionUrlBase = "{{ route('ReserveReception.create') }}";
            // モーダル関連要素
            const walkinModal = document.getElementById('walkin-modal');
            const walkinContent = walkinModal.querySelector('.modal-content-base'); 
            // 当日受付ボタンは複数あるため、全てを取得
            const openModalButtons = document.querySelectorAll('.open-walkin-modal-button'); 
            const closeModalButton = document.getElementById('cancel-walkin-modal');
            const walkinForm = document.getElementById('walkin-form');
            
            // 当日受付フォームの要素
            const courseSelect = document.getElementById('Courseid');
            const productIdInput = document.getElementById('Productid');
            const refTelButton = document.getElementById('refTelButton');
            
            // JSONデータをフォームのテキストフィールドに適用するヘルパー関数
            function ApplyJsonToText(value, name) {
                const element = document.getElementById(name);
                if (element) {
                    element.value = value || ''; // nullやundefinedの場合は空文字列を設定
                } else {
                    // console.warn(`要素ID '${name}' が見つかりませんでした。`);
                }
            }


            /**
             * 指定した商品ブロックの予約データを取得し、テーブルに描画する
             * @param {HTMLElement} block 商品ブロック要素
             * @param {string} date YYYY-MM-DD形式の日付
             */
            async function fetchAndRenderReservations(block, date) {
                const baseCode = block.dataset.baseCode;
                const productID = block.dataset.productId;
                const tableBody = block.querySelector('.table-body');
                const noReservationsMessage = block.querySelector('.no-reservations');

                // 描画をクリアし、ローディングメッセージを表示
                tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-gray-500 loading-indicator">予約データを読み込み中...</td></tr>`;
                noReservationsMessage.classList.add('hidden');

                try {
                    const response = await fetch("{{ route('api.reservations.list') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken 
                        },
                        body: JSON.stringify({
                            date: date,
                            baseCode: baseCode,
                            productID: productID
                        })
                    });

                    if (!response.ok) {
                        throw new Error('APIからデータの取得に失敗しました。');
                    }

                    const data = await response.json();
                    
                    // 以前のローディングメッセージを削除
                    tableBody.innerHTML = ''; 

                    if (data.reservations && data.reservations.length > 0) {
                        data.reservations.forEach(reservation => {
                            const newRow = tableBody.insertRow();
                            newRow.classList.add('hover:bg-gray-100', 'transition', 'duration-100');
                            
                            const address = `〒${reservation.CliAddrZip}<br>${reservation.CliAddrCity}${reservation.CliAddrOther}`;
                            var AcceptButton = '';
                            if(reservation.Status == 1) {
                                AcceptButton = `
                                    class="link-button" title="受付処理へ進む (ID: ${reservation.OrderNo})"> 受付`;
                            }
                            else /*if(reservation.Status == 1)*/ {
                                AcceptButton = `
                                    class="link-button lever-button" title="受付処理へ進む (ID: ${reservation.OrderNo})"> 再受付`;
                            }

                            // 受付ボタン付きの氏名セルを作成
                            newRow.innerHTML = `
                                <td class="product">
                                    <div class="name-box">
                                        <!-- 氏名 -->
                                        <div class="left-name">
                                            <span class="font-semibold">${reservation.ClitNameKanji}様</span><br>
                                            <span class="text-gray-500 text-xs">${reservation.ClitNameKana}</span>
                                        </div>
                                        <!-- 受付ボタン -->
                                        <div class="right-button">
                                            <a href="${receptionUrlBase}/${reservation.OrderNo}" ${AcceptButton}</a>
                                        </div>
                                    </div>
                                </td>
                                <td class="status">${reservation.courseName}</td>
                                <td class="count">${reservation.CliResvCnt}名</td>
                                <td class="contact">${reservation.CliTel1}<br>${reservation.CliEMail || 'なし'}</td>
                                <td class="address">${address}</td>
                            `;
                        });
                    } else {
                        noReservationsMessage.classList.remove('hidden');
                        tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-gray-500">予約データがありません</td></tr>`;
                    }

                } catch (error) {
                    console.error("予約データの取得エラー:", error);
                    tableBody.innerHTML = `<tr><td colspan="7" class="text-center py-4 text-red-500">データの読み込み中にエラーが発生しました。</td></tr>`;
                }
            }

            /**
             * ページロード時と日付変更時に、全ての商品ブロックのデータを更新する
             * @param {string} date YYYY-MM-DD形式の日付
             */
            function loadAllReservations(date) {
                reservationBlocks.forEach(block => {
                    fetchAndRenderReservations(block, date);
                });
            }

            // --- モーダル表示/非表示処理 ---
            /**
             * モーダルの表示状態を切り替える
             * @param {boolean} show 表示するかどうか
             * @param {string | null} initialProductId 当日受付ボタンから渡された商品ID
             */
            function toggleModal(show, initialProductId = null) {
                if (show) {
                    // 修正: 初期非表示クラスを削除し、表示クラスを追加
                    walkinModal.classList.remove('modal-initial-hide');
                    walkinModal.classList.add('modal-visible');
                    
                    // モーダルコンテンツのトランジション開始 (scale-95 -> scale-100)
                    walkinContent.classList.remove('scale-95');
                    walkinContent.classList.add('scale-100');
                    
                    // 初期Productidを設定
                    if (initialProductId) {
                        productIdInput.value = initialProductId;
                        
                        // 選択されたProductidに紐づくコースのみを動的に生成
                        populateCourses(initialProductId);

                    } else {
                        // Productidが指定されていない場合はクリア
                        productIdInput.value = '';
                        courseSelect.innerHTML = '<option value="" disabled selected>コースが選択できません</option>';
                    }
                } else {
                    // 修正: 表示クラスを削除し、初期非表示クラスを追加
                    walkinModal.classList.remove('modal-visible');
                    walkinModal.classList.add('modal-initial-hide');
                    
                    // モーダルコンテンツのトランジション終了 (scale-100 -> scale-95)
                    walkinContent.classList.remove('scale-100');
                    walkinContent.classList.add('scale-95');
                    // モーダルを閉じる際にフォームをリセット
                    walkinForm.reset();
                    $("#RefMessage").text("最終予約情報から住所氏名等を引用します。");
                    $("#RefMessage").css("color", "gray");
                }
            }

            /**
             * 指定された商品IDに基づいて、コース選択肢を動的に生成する
             * @param {string} productId 選択された商品ID
             */
            function populateCourses(productId) {
                courseSelect.innerHTML = ''; // 既存のオプションをクリア

                // 該当する商品を見つける
                const product = shopProducts.find(p => String(p.id) === productId);

                if (product && product.product_courses && product.product_courses.length > 0) {
                    // 見つかった商品のコースをオプションとして追加
                    product.product_courses.forEach(course => {
                        const option = document.createElement('option');
                        option.value = course.id;
                        option.textContent = course.courseName;
                        // data-product-id は不要になったが、互換性のため残す
                        // option.dataset.productId = productId; 
                        courseSelect.appendChild(option);
                    });
                } else {
                    // コースが見つからない、またはコースがない場合
                    const option = document.createElement('option');
                    option.value = '';
                    option.textContent = 'この商品にはコースが登録されていません';
                    option.disabled = true;
                    option.selected = true;
                    courseSelect.appendChild(option);
                    // 登録ボタンを無効化するなどの処理も検討可能
                }
            }

            // --- イベントリスナー ---

            // 1. ページロード時: 初期日付でデータを読み込む
            loadAllReservations(targetDateInput.value);

            // 2. 日付変更ボタンクリック時: 新しい日付でデータを読み込み、URLを更新
            dateChangeButton.addEventListener('click', function() {
                const newDate = targetDateInput.value;
                if (newDate) {
                    // URLを新しい日付で更新し、ページ全体をリロード
                    window.location.href = "{{ route('ReserveReception.index') }}/" + newDate;
                }
            });

            // 3. 当日受付ボタンクリック: モーダル表示
            openModalButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const productId = this.dataset.productId;
                    toggleModal(true, productId);
                    // コースの動的生成はtoggleModal内で実行されるため、ここでは不要
                });
            });

            // 4. モーダルキャンセルボタンクリック: モーダル非表示
            closeModalButton.addEventListener('click', () => toggleModal(false));

            // 5. コース選択変更時のProductid更新ロジックは不要になったため削除

            // 6. 検索ボタンのロジック (jQuery使用)
            $('#refTelButton').click(function() { 
                var TelNo = $("input[name='CliTel1']").val();
                TelNo = $.trim(TelNo);
                
                if(TelNo == "") {
                    // NOTE: alert() は使えないため、メッセージ表示で対応
                    $("#RefMessage").text("注文時に入力した電話番号を入力してください");
                    $("#RefMessage").css("color","red");
                    return;
                }
                
                // 検索処理
                $.ajax({
                    url: '{{ route('reserve.GetCustmerData') }}', // リクエストを送るURL
                    type: 'GET',
                    data: {
                        type  : 'TopOne',
                        Tel   : TelNo
                    },         
                    success: function(response) {
                        console.log("get customer info success.");
                        console.log(response);
                        var json = response;

                        if(json && json.id > 0) {
                            ApplyJsonToText(json.ClitNameKanji,    "ClitNameKanji");  // お名前
                            ApplyJsonToText(json.ClitNameKana,    "ClitNameKana");  // フリガナ
                            ApplyJsonToText(json.CliAddrZip,      "CliAddrZip");    // 郵便番号
                            ApplyJsonToText(json.CliAddrPref,     "CliAddrPref");  // 都道府県 (hidden)
                            ApplyJsonToText(json.CliAddrCity,     "CliAddrCity");  // 市区町村 (hidden)
                            ApplyJsonToText(json.CliAddrOther,    "CliAddrOther"); // 町名番地 (hidden)
                            ApplyJsonToText(json.CliEMail,        "CliEMail");     // メールアドレス (hidden)
                            // CliTel1は既にフォームに入力されているので上書きしない
                            $("#RefMessage").text("注文履歴から引用しました");
                            $("#RefMessage").css("color","blue");
                        }
                        else {
                            $("#RefMessage").text("注文履歴に指定された電話番号は登録されていません");
                            $("#RefMessage").css("color","red");
                            // 検索失敗時はフォームの引用フィールドをクリア（電話番号以外）
                            ApplyJsonToText("", "ClitNameKanji");
                            ApplyJsonToText("", "ClitNameKana");
                            ApplyJsonToText("", "CliAddrZip");
                            ApplyJsonToText("", "CliAddrPref");
                            ApplyJsonToText("", "CliAddrCity");
                            ApplyJsonToText("", "CliAddrOther");
                            ApplyJsonToText("", "CliEMail");
                        }
                    },
                    error: function(error) {
                        console.error('予約登録失敗:', error);
                        $("#RefMessage").text("データの取得中にエラーが発生しました");
                        $("#RefMessage").css("color","red");
                    }
                });
            });


            // 7. フォーム送信後の処理 (登録/受付ボタン)
            // フォームのsubmitイベントをインターセプトし、成功時にリダイレクトする
            walkinForm.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                const form = e.target;
                const formData = new FormData(form);

                // 登録/受付ボタンを無効化して二重送信を防ぐ
                const submitButton = document.getElementById('submit-walkin-form');
                submitButton.disabled = true;
                submitButton.textContent = '処理中...';

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': csrfToken 
                        }
                    });

                    // 応答が成功ステータス (2xx) でない場合はエラーとして処理
                    if (!response.ok) {
                        const errorData = await response.json();
                        console.error('予約登録失敗:', errorData);
                        // バリデーションエラーなど、より詳細なエラー表示を行うのが理想
                        alert("予約の登録に失敗しました。入力内容を確認してください。");
                        return;
                    }
                    
                    const result = await response.json();

                    // 成功した場合、新しいOrderNoを使って受付画面にリダイレクト
                    if (result.OrderNo) {
                        // route('reserve.store')実行後、"${receptionUrlBase}/${reservation.OrderNo}" に遷移
                        window.location.href = `${receptionUrlBase}/${result.OrderNo}`;
                    } else {
                        // 予期せぬ成功レスポンスの場合
                        alert("登録は完了しましたが、予約番号が返されませんでした。一覧に戻ります。");
                        toggleModal(false);
                        loadAllReservations(targetDateInput.value); // 一覧を再読み込み
                    }

                } catch (error) {
                    console.error('予約登録エラー:', error);
                    alert("予期せぬエラーが発生しました。");
                } finally {
                    // ボタンを元に戻す
                    submitButton.disabled = false;
                    submitButton.textContent = '登録/受付';
                }
            });
        });
    </script>
</x-app-layout>
