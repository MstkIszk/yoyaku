<link href="{{ asset('css/bublemenu.css') }}" rel="stylesheet">

<style> /* ハンバーガーメニュー */

.image-container {
    width: 100%;
    height: 4rem;
    /* 背景画像を横一杯に繰り返し表示 public\images\ */
    background-image: url('{{ asset('images/menu_back_byfish.png') }}');
    background-repeat: repeat-x; /* 横方向のみ繰り返し */
    
    /* スクロールのための設定 */
    animation: scroll-bg 15s linear infinite; /* アニメーションの適用 */
}

/* アニメーションの定義 */
@keyframes scroll-bg {
    from {
        background-position: 0 0; /* 開始位置: 左端 */
    }
    to {
        background-position: 100% 0; /* 終了位置: 右端 */
    }
}

.nav_item {
    list-style: none;
}
.nav_topbar {
    width: 100%;
    height: 1.2rem;
    vertical-align: center;
    background-color: aquamarine;
    padding: 10px 0; /* paddingを調整して見やすく */
    text-align: center;
}
.menu a {
    text-decoration: none;
    color: #333;
    display: block;
    padding: 10px 15px; /* リンクのクリック範囲を広げる */
}

/* ---------------------------------------------------------------------- */
/* モバイル (780px 未満) - ハンバーガーメニュー関連スタイル                   */
/* ---------------------------------------------------------------------- */

/* ハンバーガーメニューボタン */
.header__hamburger {
    position: fixed;
    display: block; /* モバイルで表示 */
    top: 2px;
    right: 10px;
    width: 48px;
    height: 48px;
    z-index: 9999;
}
.hamburger {
    border-color: transparent;
    border-radius: 100%;
    background: #ec6d88;
}
/* ハンバーガーメニューの線 (既存のスタイルを維持) */
.hamburger span {
    width: 100%;
    height: 3px; /* 1pxから3pxに変更して見やすく */
    background-color: #000;
    position: relative;
    transition: ease .4s;
    display: block;
}
.hamburger span:nth-child(1) { top: 0; }
.hamburger span:nth-child(2) { margin: 8px 0; }
.hamburger span:nth-child(3) { top: 0; }
/* ハンバーガーメニュークリック後のスタイル (X印) */
.hamburger.active span:nth-child(1) {
    top: 11px; /* 調整 */
    transform: rotate(45deg);
}
.hamburger.active span:nth-child(2) { opacity: 0; }
.hamburger.active span:nth-child(3) {
    top: -11px; /* 調整 */
    transform: rotate(-45deg);
}

/* メニューパネル (スライドアウト) */
.header__nav {
    /* 初期状態: 画面外に非表示 */
    position: fixed;
    top: 0;
    bottom: 0;
    right: -11rem; /* 初期状態で画面外に配置 */
    width: 11rem; /* JSで変更する幅 */
    padding-top: 70px;
    z-index: 2000;
    overflow-y: auto;
    transition: ease .4s;
}
/* JSで active が付与されたら表示 */
.header__nav.active {
    right: 0; /* 画面内にスライドイン */
}
/* メニューアイテムのコンテナ 
.header__nav ul {
    list-style-type: none;
    margin: 0;
    padding: 0;
}
.header__nav li {
    line-height: 40px;
    font-size: 1rem;
    margin: 2px;
    /* モバイルでは縦並びを維持 
}
*/

/* ---------------------------------------------------------------------- */
/* デスクトップ (780px 以上) - メディアクエリで上書き                         */
/* ---------------------------------------------------------------------- */
@media (min-width: 780px) {

    /* ハンバーガーボタンを非表示 */
    .header__hamburger {
        display: none;
    }

    /* メニューパネルを画面最上部に横並びで表示 */
    .header__nav {
        /* position: fixed; で最上部に固定 */
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: auto; /* bottomの制限を解除 */
        height: auto;
        width: 100%;
        padding: 0;
        transform: none; /* JSによるスライド効果を無効化 */
    }
    
    /* ログイン店舗名が表示されているバーも非表示 (デスクトップではメニューと統合) */
    .nav_topbar {
        display: none;
    }

    /* ul (nav__items) を横並びにする */
    //.header__nav ul {
    //    display: flex; /* Flexboxで横並び */
    //    justify-content: flex-end; /* 右寄せ */
    //    align-items: center;
    //    height: 40px; /* メニューバーの高さ */
    //}

    //* li (nav_item) を横並びにする */
    //.header__nav li {
    //    margin: 0 10px; /* 間隔を調整 */
    //    line-height: 1;
    //}
    
    /* a (menu_item) のスタイルを調整 */
    .menu a {
        display: inline-block; /* リンクをインラインブロックに */
        color: #333;
        font-weight: bold;
        padding: 0 5px;
    }
    
    /* users の spName が表示される div.menu を横並びにする */
    .menu {
        display: flex; 
        align-items: center;
        margin-right: 20px; /* 右端に少しスペース */
    }
}
</style>

<div class="image-container">

<!-- ハンバーガーメニューをクリックしたらactiveクラスを付与する制御をJavaScriptで行う -->
<button class="header__hamburger hamburger" id="js-hamburger">
    <span></span>
    <span></span>
    <span></span>                    
</button>

<!-- メニュー -->
<nav class="header__nav nav" id="js-nav">
{{-- <ul class="nav__items nav-items"> --}}
<ol class="headerAside header-fadeInUp">
    @if (Route::has('login'))
        @auth
            <div class="nav_topbar">{{ Auth::user()->spName }}</div>
        @endauth

        <!-- loginというルート要素があれば -->
        <div class="menu">
            @auth
                <!-- ログインしていれば -->
                <li class="headerAside__item"><a href="{{ url('/dayinfo') }}"      class="headerAside__link">{{ __('reserve Today') }}</a></li>
                <li class="headerAside__item"><a href="{{ route('reserve.calender', ['user_id' => Auth::user()->id, 'product_id' => 0]) }}" class="headerAside__link">{{ __('Shop Calender') }}</a></li>
                <li class="headerAside__item"><a href="{{ url('/reservelist') }}"  class="headerAside__link">{{ __('Reserve list') }}</a></li>
                <li class="headerAside__item"><a href="{{ url('/dashboard') }}"    class="headerAside__link">{{ __('Dashboard') }}</a></li>
            @else
                <!-- ログインしていない -->
                <li class="headerAside__item"><a href="{{ route('reserve.telnoinput') }}" class="headerAside__link">{{ __('reservation search') }}</a></li>
                <li class="headerAside__item"><a href="{{ route('login') }}"      class="headerAside__link">{{ __('Login') }}</a></li>

                @if (Route::has('register'))
                    <!-- registerというルート要素があれば -->
                    <li class="headerAside__item"><a href="{{ route('register') }}" class="headerAside__link">{{ __('New Shop Entry') }}</a></li>
                @endif
            @endauth
        </div>
    @endif
</ol>
</nav>

<script>

const ham = document.querySelector('#js-hamburger');
    const nav = document.querySelector('#js-nav');
    const mediaQuery = window.matchMedia('(max-width: 779px)'); // 780px未満

    // モバイルメニューの開閉状態を管理する関数
    function toggleMobileMenu() {
        if (mediaQuery.matches) { // 780px未満の場合のみ実行
            ham.classList.toggle('active');
            nav.classList.toggle('active');
            
            // CSSの right: -11rem; と right: 0; の切り替えは、
            // JSで直接 width を操作するのではなく、activeクラスにCSSの right プロパティを定義する方が簡潔で、
            // 上記のCSS修正案に沿っています。ここでは width の直接操作は削除または修正します。
            
            // 既存のJSロジックを修正
            /*
            if (nav.classList.contains('active')) {
                nav.style.width = "11rem";
            } else {
                nav.style.width = "0rem";
            }
            */
        }
    }

    // イベントリスナーを設定
    ham.addEventListener('click', toggleMobileMenu);

    // 画面サイズが変更されたときに、不要なクラスをリセットする処理
    function handleScreenChange(e) {
        if (!e.matches) {
            // 780px以上になったら、アクティブクラスを削除し、モバイル用の width もリセット
            ham.classList.remove('active');
            nav.classList.remove('active');
            nav.style.width = ''; // JSで設定したwidthをリセット
        }
    }

    // リスナーの登録
    mediaQuery.addListener(handleScreenChange);
    // 初期ロード時にもチェック
    handleScreenChange(mediaQuery);
</script>

</div>
{{-- <div>
    <!-- No surplus words or unnecessary actions. - Marcus Aurelius -->

</div>--}} 