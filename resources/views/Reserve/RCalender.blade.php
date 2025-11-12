<x-app-layout :ShopInf="$ShopInf" >
    <x-slot name="header">
        <x-article-title caption="{{ __('Shop Calender') }}" />
    </x-slot>

    <link href="{{ asset('css\calender.css') }}" rel="stylesheet">

    <!-- p>現在のフォルダ: {{ getcwd() }}<br>
            URL:{{ Request::url() }}<br>
            asset:{{ asset('images/backimage1.jpg') }}</p-->

    {{-- JavaScriptに渡すための JSONを作成 --}}
    @php
        $productListJson = json_encode($ProductList);
    @endphp

    <div class="py-12">

        @livewireStyles

        <div class="card">
            <div class="card-header" style="text-align: center;">
                - {{ $ShopInf->spName }}：
                <select id="productSelector" onchange="changeProduct()">
                    @foreach ($ProductList as $product)
                        <option value="{{ $product->id }}" {{ $product->id == $ProductID ? 'selected' : '' }}>
                            {{ $product->productName }}
                        </option>
                    @endforeach
                </select>
                予約 -
            </div>
            <!-- livewire:shopclosedmodal 'ShopInf','ProductInf'/-->
            @livewireScripts
            
            <div class="card-body">
                <ul>
                    <li>カレンダーより、ご希望の予約日をお選びください。</li>
                    <li>予約日は次回予約のみお取りいただけます。</li>
                </ul>

                <div class="calender">
                    <form class="prev-next-form"></form>
                    <table class="calender">
                        <thead>
                        <tr>
                            <td colspan="2">
                                <div class="allow_box">
                                    <label  class="arrow-left">
                                        <a href="#" class="dateLink" id="date_befor_link" onclick="GetYoyakuCalender( '{{ calendar_culc($month,-1) }}' )">前月</a>
                                    </label>
                                </div>
                            </td>
                            <th colspan="3">
                                <label  class="text-center" id="date_month">
                                    {{ date('Y',strtotime($month)) }}年{{ date('m', strtotime($month)) }}月
                                </label>
                            </th>
                            <td colspan="2">
                                <div class="allow_box">
                                    <label  class="arrow-right">
                                            <a href="#" class="dateLink" id="date_after_link" onclick="GetYoyakuCalender( '{{ calendar_culc($month,+1) }}' )">次月</a>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="sun"><div class="weekday">日</div></th>
                            <th class="mon"><div class="weekday">月</div></th>
                            <th class="tue"><div class="weekday">火</div></th>
                            <th class="wed"><div class="weekday">水</div></th>
                            <th class="thu"><div class="weekday">木</div></th>
                            <th class="fri"><div class="weekday">金</div></th>
                            <th class="sat"><div class="weekday">土</div></th>
                        </tr>
                        </thead>
                        <tbody id="calenderTable">
                        </tbody>
                    </table>

                    <script>
                    var CurrYM = '{{ $month }}';

                    //  PHPから商品リストデータをJSON形式で受け取る
                    //  実際にはPHPの実行結果であるJSON文字列が埋め込まれます。
                    //  const productList = JSON.parse('< ?= $productListJson ?? "[]" ?>--}}');
                    const productList = @json($ProductList );

                    const today   = new Date();
                    const todayYY = today.getFullYear();
                    const todayMM = ('0' + (today.getMonth() + 1)).slice(-2); // 月は0から始まるため+1し、桁数を2桁に調整
                    const todayDD = ('0' + today.getDate()).slice(-2);
                    // 現在選択中の商品IDを取得する関数を定義
                    function getCurrentProductID() {
                        const selector = document.getElementById('productSelector');
                        return selector ? selector.value : null;
                    }
                    // 現在選択中の商品のキャパシティを取得する関数
                    function getCurrentProductCapacity() {
                        const selectedId = getCurrentProductID();
                        if (!selectedId) {
                            console.warn("商品セレクターが見つからないか、IDが選択されていません。");
                            return 0; // 安全なデフォルト値
                        }

                        // productListから、IDが一致する商品を見つける
                        const selectedProduct = productList.find(p => p.id === Number(selectedId));

                        // 商品が見つかればその capacity を返し、見つからなければ 0 を返す
                        return selectedProduct ? selectedProduct.capacity : 0;
                    }


                    // 商品選択ドロップダウンが変更されたときの処理
                    function changeProduct() {
                        // 選択された商品IDをセッションに反映させるために、URLをリダイレクトする
                        const currentProductID = getCurrentProductID();
                        if (currentProductID) {

                            // 1. サーバー側（Blade）で必須パラメータ（user_id）を渡し、
                            //    動的なパラメータ（product_id）にプレースホルダーを使用する。
                            //    これによりLaravelのエラーを回避し、完全なURLパスを生成させます。
                            const baseRoute = '{{ route('reserve.calender', [
                                'user_id' => $ShopInf->id,
                                'product_id' => 'TEMP_PRODUCT_ID' // プレースホルダー
                            ]) }}';

                            // 2. JavaScriptでプレースホルダーを現在の選択値に置き換える。
                            const currentProductID = getCurrentProductID(); // ユーザーの既存の関数
                            const newUrl = baseRoute.replace('TEMP_PRODUCT_ID', currentProductID);

                            // URLのルート名と必要なパラメータに基づいてリダイレクトURLを生成
                            // コントローラーのcalenderメソッドの引数に合わせてURLを再構築します
                            {{--const newUrl = '{{ route('reserve.calender') }}/{{ $ShopInf->id }}/' + currentProductID;--}}
                            window.location.href = newUrl;
                        }
                    }

                    var opetbl = [];
                    @foreach ( \App\Models\ReserveDate::GetOperating() as $item) 
                    opetbl.push({code: {{ $item[0] }},name: '{{ $item[1] }}' });
                    @endforeach

                    function getOpeName(code) {
                        const foundItem = opetbl.find(item => item.code === code);
                        return foundItem ? foundItem.name : "未定義";    
                    }

                    GetYoyakuCalender(CurrYM);  //  初回のカレンダー表示
                    function calendar_culc(reqYM,numMonth){
                        // DateTimeオブジェクトに変換
                        const [year, month] = reqYM.split('-');
                        const date = new Date(year, month - 1, 1);

                        // 前の月の末日を取得
                        date.setMonth(date.getMonth() + numMonth);
                        const lastDayOfPreviousMonth = new Date(date.getFullYear(), date.getMonth() + 1, 0);

                        // 年と月を取得
                        const previousYear = lastDayOfPreviousMonth.getFullYear();
                        const previousMonth = lastDayOfPreviousMonth.getMonth() + 1;

                        return `${previousYear}-${previousMonth.toString().padStart(2, '0')}`;
                    }

                    function GetYoyakuCalender(reqYM) {
                        CurrYM = reqYM;
                        const [reqYY, reqMM, reqDD] = reqYM.split('-');
                        chkDD = 0;
                        if((reqYY == todayYY) && (reqMM == todayMM)) {
                            chkDD = todayDD;
                        }

                        // 既存のtbody要素を削除
                        const tbody = document.getElementById('calenderTable'); // idが'calenderTable'のtbody要素を想定
                        while (tbody.firstChild) {    // tbodyの子要素がなくなるまでループ
                            tbody.removeChild(tbody.firstChild);
                        }
                        document.getElementById('date_month').textContent = reqYY + '年　' + reqMM + '月';

                        // 前月へのリンクを書き換え
                        const dateBeforLink = document.getElementById('date_befor_link');
                        dateBeforLink.onclick = function() {
                            var strLink = calendar_culc( reqYM,-1);
                            GetYoyakuCalender(strLink);
                        };

                        // 次月へのリンクを書き換え
                        const dateAfterLink = document.getElementById('date_after_link');
                        dateAfterLink.onclick = function() {
                            var strLink = calendar_culc( reqYM,+1);
                            GetYoyakuCalender(strLink);
                        };

                        let clsAry = ["sun","mon","tue","wed","thu","fri","sat"];
                        var calendarDiv = document.getElementById('calenderTable');
                        /*  var calendarAry =  @#json($calender); 呼び出し元からのカレンダーデータ取得*/
                        var maxYoyaku = getCurrentProductCapacity();

                        // ProductIDをドロップダウンから取得
                        const currentProductID = getCurrentProductID();
                        if (!currentProductID) {
                            console.error("ProductIDが設定されていません。");
                            return;
                        }

                        $.ajax({
                            url: '{{ route('reserve.calenderGet') }}', // リクエストを送るURL
                            type: 'GET',
                            data: {
                                basecode  : {{ $ShopInf->id }},
                                ProductID : currentProductID,
                                month   : reqYM
                            },                            
                            success: function(response) {
                                // サーバーから返ってきたデータの処理
                                console.log(response);

                                calendarAry = response;

                                var weekStr=""; //  編集先文字列
                                var dayIx = 0;
                                var weekIx = 0;
                                const template = document.createElement('template');
                                calendarAry.forEach(function(weekAry) {

                                    weekStr="<tr>"; //  編集先文字列
                                    weekAry.forEach(function(dayInfo) {     //  一週間分づつ編集
                                        weekStr+='<td><div class="area" id="area' + dayIx + '">';

                                        if(dayInfo.day > 0) {       
                                            if(dayInfo.day == chkDD) {
                                                weekStr += '<div class="day_today">' + dayInfo.day + '</div>';
                                            } else {
                                                weekStr += '<div class="day">' + dayInfo.day + '</div>';
                                            }

                                            operatingCode = 1;  
                                            if ("operating" in dayInfo) {
                                                operatingCode = dayInfo.operating;
                                            }

                                            var zanSeki = maxYoyaku - dayInfo.totalCnt;
                                            editBtnStyle = 'class="edit_button ';
                                            switch(operatingCode) {
                                            case 1:
                                                editBtnStyle += 'edit_button_normal';
                                                break;
                                            case 2:
                                                editBtnStyle += 'edit_button_horiday';
                                                break;
                                            case 3:
                                                editBtnStyle += 'edit_button_private';
                                                break;
                                            }
                                            editBtnDef = '<div class="edit_button ' + editBtnStyle + '">' + getOpeName(operatingCode) + '</div>'
                                            @auth
                                                @if(Auth::user()->id == $ShopInf->id)
                                                    //weekStr += '<button class="cnt" id="Yoyaku' + dayInfo.day + 
                                                    //           '" onclick="openYoyakuInput(' + dayInfo.day + 
                                                    //           ')">zanSeki</button>';
                                                    editBtnDef = '<button ' + editBtnStyle + '" onclick=\'openEditModal(' + dayIx + ',"' + dayInfo.day + '")\'>' + getOpeName(operatingCode) + '</button>';
                                                @endif
                                            @endauth
                                            weekStr += editBtnDef;


                                            if(dayInfo.DayName) {
                                                weekStr += '<div class="dayname">' + dayInfo.DayName + '</div>'; //  祭日の名前
                                            }
                                            else {
                                                weekStr += '<div class="dayname">　</div>'; //  祭日の名前
                                            }

                                            weekStr += '<div class="names">';   //  予約者リスト
                                            if(dayInfo.totalCnt > 0) {
                                                //  予約者がいる場合、名前・人数とリンクを追加
                                                var memCnt = 0;
                                                dayInfo.member.forEach(function(member) {
                                                    if(memCnt > 0) {
                                                        weekStr += '<br>';
                                                    }
                                                    weekStr += member.name + ':' + member.cnt;
                                                    memCnt++;
                                                })
                                            }
                                            else {
                                                weekStr += "　　　　　";
                                            }
                                            weekStr += '</div>';
                                            weekStr += '<div class="yoyaku_cnt">' + zanSeki + '</div>';
                                    
                                            if((operatingCode == 1) && (dayInfo.day > chkDD)) {   //  明日以降ならば予約ボタンを表示
                                                weekStr += '<button class="yoyaku_button" id="Yoyaku' + dayInfo.day + 
                                                           '" onclick="openYoyakuInput(' + dayInfo.day + ')" ';  
                                                if(zanSeki <= 0) {
                                                    weekStr += 'disabled';
                                                }
                                                weekStr += '>予約</button>';  
                                            }
                                        }

                                        //  枠の終わり
                                        weekStr+='</div></td>';

                                        dayIx++;
                                    })
                                    weekStr+='</tr>';
                                    template.innerHTML = weekStr;
                                    content = template.content;
                                    calendarDiv.appendChild(content);
                                    weekIx++;
                                })
                            },
                            error: function(error) {
                                // エラーが発生した場合の処理
                                console.error(error);
                            }
                        });
                    }
                    function openYoyakuInput(day) {
                        //  YYYY-MM-DD に編集
                        var reqDate = CurrYM.substr(0,7) + '-' + ('00' + day).slice(-2);

                        // ProductIDをドロップダウンから取得
                        const productIDForReserve = getCurrentProductID();

                        //  新規画面へのURL
                        {{--//const newUrl = '{{ Route('reserve.create') }}/{{ $ShopInf->id }}/{{ $ProductInf->id }}/' + reqDate ;--}}
                        const newUrl = '{{ Route('reserve.create') }}/{{ $ShopInf->id }}/' + productIDForReserve + '/' + reqDate ;
                        window.location.href = newUrl;            // リダイレクト
                    }
                    function updateButtonColor(area,data) {

                        const selIx = Number(data.operating);
                        const editButton = area.querySelector('.edit_button');      // area 内の edit_button を取得
                        const yoyakulabel = area.querySelector("[id^='Yoyaku']");   // area 内の edit_button を取得
                        const daylabel = area.querySelector('.day');                // area 内の edit_button を取得
                        {{-- 既存の色クラスをすべて削除する --}}
                        editButton.classList.remove('edit_button_normal', 'edit_button_horiday', 'edit_button_private');
                        var newText = '未定義';
                        
                        // opetblから対応するエントリを探す
                        const operation = opetbl.find(item => item.code === selIx);
                        if (operation) {
                            newText = operation.name;
                        }
                        switch (selIx) {
                        case 1:
                            editButton.classList.add('edit_button_normal');
                            break;
                        case 2:
                            editButton.classList.add('edit_button_horiday');
                            break;
                        case 3:
                            editButton.classList.add('edit_button_private');
                            break;
                        default:
                            editButton.style.backgroundColor = ''; // デフォルトの色をクリア
                        }
                        // ボタンのテキストの置き換え
                        editButton.textContent = newText;

                        if (yoyakulabel) {
                            if((selIx == 1) && (todayDD > daylabel.textContent)) {
                                yoyakulabel.style.visibility = ''; // または 'visible'
                            } else {
                                yoyakulabel.style.visibility = 'hidden';
                            }
                        }
                        {{-- class名 yoyaku_cnt を探す --}}
                        const yoyakuCntElement = area.querySelector('.yoyaku_cnt'); 
                        yoyakuCntElement.textContent = data.yoyakusu;
                    }                                    
                    
                    </script>
                    <!-- ダイアログ本体 -->
                    <div id="easyModal" class="modal">
                        <div class="modal-content">
                            <input type="hidden" id="dayIx">
                            <input type="hidden" id="id">
                            <input type="hidden" id="baseCode" value="{{ $ShopInf->id }}">   <!-- 店コード -->
                            <input type="hidden" id="eigyotype" value="1">  <!-- 目的コード 1:ワカサギ -->
                            <input type="hidden" id="destDate">
                            <div class="modal-header" id="modal-header">
                                <label id="dateCaption" writingsuggestions="true">Great job 🎉</label>
                                <span class="modalClose" onclick="modalClose()">×</span>
                            </div>
                            <div class="modal-body">
                                営業状態：<select id="operating">
                                @foreach ( \App\Models\ReserveDate::GetOperating() as $item) 
                                    <option value="{{ $item[0] }}">{{ $item[1] }}</option>
                                @endforeach
                                </select>
                                <br>
                                予約可能枠：<input type="number" min="1" max="20" step="1" id="capacity"><br>
                                予約済人数：<input type="number" min="0" max="20" step="1" id="yoyakusu"><br>
                                メモ：<textarea id="memo"> </textarea>
                                <hr>
                                <button class="mt-4" id="applysts" onclick="writeDateInfo()">適用</button>
                            </div>
                        </div>
                    </div>

                    <script>
                        const editModal = document.getElementById('easyModal');
                        function openEditModal(dayIx,strDate) {
                            //  設定対象の日付枠
                            document.getElementById('dayIx').value         = dayIx;
                            //  対象日付の表示
                            reqDate = CurrYM.substr(0, 7) + '-' +  ('00' + strDate).slice(-2);
                            headDiv = document.getElementById('dateCaption');
                            headDiv.textContent = reqDate;
                            document.getElementById('destDate').value = reqDate;

                            //  指定日の情報を読み取り
                            {{-- app\Http\Controllers\ReserveDateController.php  readDateInfo() --}}
                            $.ajax({
                                url: '{{ route('reserve.readDateInfo') }}', // リクエストを送るURL
                                type: 'GET',
                                data: {
                                    type      : '1',
                                    baseCode  : Number({{ $ShopInf->id }}),
                                    productID : Number( currentProductID ),
                                    eigyotype : '1',
                                    destDate  : reqDate,
                                },
                                success: function(response) {
                                    // サーバーから返ってきたデータの処理
                                    console.log(response);
                                    dateinfo = response;

                                    document.getElementById('id').value         = response.id;
                                    document.getElementById('operating').value = response.operating;
                                    document.getElementById('capacity').value   = response.capacity;
                                    document.getElementById('yoyakusu').value   = response.yoyakusu;
                                    document.getElementById('memo').value       = response.memo;
                                },
                                error: function(error) {
                                    // エラーが発生した場合の処理
                                    console.error(error);
                                }
                            });
                            editModal.style.display = 'block';
                        }
                        //  指定日の情報を書き込む
                        function writeDateInfo() {
                            const data = {
                                id: document.getElementById('id').value,
                                baseCode: {{ $ShopInf->id }},
                                productID: currentProductID,
                                eigyotype: document.getElementById('eigyotype').value,
                                destDate: document.getElementById('destDate').value,
                                operating: document.getElementById('operating').value,
                                capacity: document.getElementById('capacity').value,
                                yoyakusu: document.getElementById('yoyakusu').value,
                                memo: document.getElementById('memo').value
                            };
                            $.ajax({
                                url: '{{ route('reserve.writeDateInfo') }}', // リクエストを送るURL
                                type: 'POST',
                                dataType: 'json', // JSON 形式でレスポンスを受け取る
                                data: JSON.stringify(data), // データを JSON 文字列に変換して送信
                                contentType: 'application/json', // JSON 形式で送信
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF トークン
                                },
                                success: function(responseData) {
                                    console.log('Success:', responseData); // 成功時の処理
                                    const areaName =  'area' + document.getElementById('dayIx').value;
                                    const area = document.getElementById(areaName); // area12 の要素を取得
                                    updateButtonColor(area,data);
                                },
                                error: function(xhr, status, error) {
                                    console.error('Error:', status, error); // エラー時の処理
                                }
                            });
                            modalClose();
                        }        
                        function modalClose() {
                            editModal.style.display = 'none';
                        }            
                    </script>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
