
<style>
    .footbox {  //  footerの枠  
        background-size: repeat;
        background-position: center;
        background-image: url('{{ asset('images/foot_susuki.png') }}');
        line-height: 30px;
        padding: 25px 20px 10px
    }
    .footer-nav-wrapper {   //  footerの文字を書く範囲
        background-color: transparent;
        margin: 0 auto;
        max-width: 1056px;
        height: 170px;
    }
    .footer-nav-container {
        column-gap: 18px;
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        justify-content: space-between;
        margin-bottom: 10px;
        position: relative;
    }
    .footer-nav-container ul {
        list-style: none;
        margin-top: 10px;
    }
    .footer-nav-container li {
        display: list-item;
        text-align: -webkit-match-parent;
        unicode-bidi: isolate;
    }
</style>  
<div class="footbox">
    <div class="footer-nav-wrapper">
        <div class="footer-nav-container ">
            <div><h3>運営</h3>
            <ul>
                <li><a href="https://kyum.chu.jp/wp/">あちゃまＷＥＢ開発</a></li>
                <li>長野市平林2-19-12-605</li>
                <li>mail:info@kyum.chu.jp</li>
                <li>&copy; 2025/02/24 achama web</li>
            </ul>
            </div>


        </div>
    </div>
    
</div>      
