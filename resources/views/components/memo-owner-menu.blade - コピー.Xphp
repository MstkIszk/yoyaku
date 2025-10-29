<div class="image-container">


<style> /* ハンバーガーメニュー */
    /*  */
    .header__hamburger {
        position: fixed;
        display: block;
        top: 2px;    
        right: 10px;    
        width: 48px;
        height: 48px;
    }
    .hamburger {
        /*background-color: transparent;  /*buttonタグデフォルトスタイルを打ち消し*/
        border-color: transparent;      /*buttonタグデフォルトスタイルを打ち消し*/
        z-index: 9999;
        border-radius: 100%;
        background: #ec6d88;
    }

    /* ハンバーガーメニューの線 */
    .hamburger span {
        width: 100%;
        height: 1px;                /* 1pxの */
        background-color: #000;     /*      黒線 */
        position: relative;
        transition: ease .4s; /*ハンバーガーメニュークリック時の三本線の動きを遅延*/
        display: block;
    }

    .hamburger span:nth-child(1) { top: 0; }
    .hamburger span:nth-child(2) { margin: 8px 0; }
    .hamburger span:nth-child(3) { top: 0; }

    /* ハンバーガーメニュークリック後のスタイルとして × 印とする */
    .header__nav.active { transform: translateX(0); }
    .hamburger.active span:nth-child(1) {
        top: 5px;   
        transform: rotate(45deg);   
    }
    .hamburger.active span:nth-child(2) {
        opacity: 0; /* 真ん中の線は透明に */
    }
    .hamburger.active span:nth-child(3) {
        top: -13px;
        transform: rotate(-45deg);
    }
</style>


<!-- ハンバーガーメニューをクリックしたらactiveクラスを付与する制御をJavaScriptで行う -->
<button class="header__hamburger hamburger" id="js-hamburger">
    <span></span>
    <span></span>
    <span></span>                    
</button>


<!-- メニュー -->
<style>
    .header__nav {
        right:11rem;
        width:0rem;
    }
    .nav_item {
        list-style: none;
    }
    .nav_topbar {
        width:100%;
        height: 1.2rem;
        vertical-align: center;
        background-color: aquamarine;
    }

    .nav {
        /*display: none;*/
        position: fixed;
        top: 0;
        bottom: 0;
        /*left: 10rem;*/
        right: 0;
        padding-top: 70px;
        background: #fff;
        color: #333;
        z-index: 2000;
        overflow-x: hidden;
        overflow-y: auto;
        transition: ease .4s; /*ハンバーガーメニュークリック時の三本線の動きを遅延*/
    }
    .nav ul {
        background: #fff;
        display: block;
        position: relative;
        list-style-type: none;
        margin: 0;
        padding: 0;
    }
    .nav li {
        cursor: pointer;
        line-height: 40px;
        font-size: 1rem;
        margin:2px;
        -webkit-touch-callout: none;
        -webkit-tap-highlight-color: rgba(0, 0, 0, 0);
    }                    
</style>

<!-- メニュー -->
<nav class="header__nav nav" id="js-nav">
    <ul class="nav__items nav-items">
    @if (Route::has('login'))
        @auth
            <div class="nav_topbar">{{ Auth::user()->spName }}</div>
        @endauth


        <!-- loginというルート要素があれば -->
        <div class="menu">
            @auth
                <!-- ログインしていれば -->
                <li class="nav_item"></li><a href="{{ url('/dashboard') }}" class="menu_item">Dashboard</a></li>
                <li class="nav_item"></li><a href="{{ url('/userinfedit') }}" class="menu_item">{{ __('Edit profile') }}</a></li>
                <li class="nav_item"></li><a href="{{ url('/logout') }}" class="menu_item">{{ __('logout') }}</a></li>
            @else
                <!-- ログインしていない -->
                <li class="nav_item"></li><a href="{{ route('login') }}" class="menu_item">Log in</a></li>

                @if (Route::has('register'))
                    <!-- registerというルート要素があれば -->
                    <li class="nav_item"></li><a href="{{ route('register') }}" class="menu_item">Register</a></li>
                @endif
            @endauth
        </div>
    @endif
    </ul>
</nav>

<script>
    const ham = document.querySelector('#js-hamburger'); //js-hamburgerの要素を取得し、変数hamに格納
    const nav = document.querySelector('#js-nav'); //js-navの要素を取得し、変数navに格納
    isHide=true;
    ham.addEventListener('click', function () { //ハンバーガーメニューをクリックしたら
        ham.classList.toggle('active'); // ハンバーガーメニューにactiveクラスを付け外し
        nav.classList.toggle('active'); // ナビゲーションメニューにactiveクラスを付け外し
        if(isHide) {
            nav.style.width = "11rem";
            isHide = false;
        }
        else {
            nav.style.width = "0rem";
            isHide = true;
        }
    });
</script>




<!--address>
    <a href="mailto:jim@example.com">jim@example.com</a><br />
    <a href="tel:+14155550132">+1 (415) 555‑0132</a>
</address-->

</div>
<div>
    <!-- No surplus words or unnecessary actions. - Marcus Aurelius -->

</div>