<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>トップページ</title>
    </head>

    <body class="antialiased">
        <header>
            <style>
                .head_frame {
                width: 100%;
                background-image: url('{{ asset('images/slide_back.jpg') }}');
                background-size: repeat;
                background-position: center;
                position: relative;
                }

                .head_inbox {
                position: relative;
                /* head_image の配置基準にするため */
                display: flex; /* Flexbox を使用して要素を配置 */
                flex-direction: column; /* 縦方向に要素を並べる */
                align-items: center; /* 横方向は中央揃え */
                }

                .head_image {
                min-width: 200px; /* 最小高さ */
                max-width: 600px; /* 最大高さ */
                height: auto; /* 高さはコンテンツに合わせて伸縮 */
                min-height: 200px; /* 最小幅 */
                width: 100%; /* head_inbox の幅に合わせて伸縮 */
                display: block;
                margin: 0 auto;
                }

                .head_title {
                position: absolute;
                top: 4px;
                left: 4px;
                /* 必要に応じて textbox のスタイルを設定 */
                background-color: rgba(255, 255, 255, 0.7); /* 例：半透明の白色背景 */
                padding: 5px;
                }
                .head_unei {
                position: absolute;
                bottom: 4px;
                right: 4px;
                /* 必要に応じて textbox のスタイルを設定 */
                background-color: rgba(255, 255, 255, 0.7); /* 例：半透明の白色背景 */
                padding: 5px;
                }
            </style>
            <div class="head_frame">
                <div class="head_inbox">
                    <img class="head_image" src="{{ asset('images/head_mounten.png') }}" alt="トップ画像">
                    <div class="head_title">総合予約システム</div>
                    <div class="head_unei">運営：七二会森林クラブ</div>
                </div>
            </div>
           <style>
                .footerSmallBtns {
                    display: -webkit-box;
                    display: -ms-flexbox;
                    display: flex;
                    -webkit-box-pack: justify;
                    -ms-flex-pack: justify;
                    justify-content: space-between;
                }

                .footerSmallBtn {
                    display: block;
                    position: relative;
                }
                .c-hoverBtn:hover {
                    -webkit-animation: 0.2s ease 0s 1 normal forwards running buttonHover;
                    animation: 0.2s ease 0s 1 normal forwards running buttonHover;
                    -webkit-filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 8px 16px);
                    filter: drop-shadow(rgba(0, 0, 0, 0.2) 0px 8px 16px);
                }

                @media (min-width: 769px) {
                    .footerSmallBtn__wrap {
                        width: 30%;
                    }
                }

                @media (max-width: 768px) {
                    .footerSmallBtn__wrap {
                        width: 31%;
                    }
                }



            </style>
        </header>
        <x-owner-menu />


        <!-- 店舗一覧の表示 -->
        <style>
                    a[href^='mailto']::before {
                    content: '📧👮 ';
                    }

                    a[href^='tel']::before {
                    content: '📞 ';
                    }
                    .shop_list {
                        display: flex;
                        justify-content: center; /* 水平方向の中央揃え */
                        align-items: center; /* 垂直方向の中央揃え */
                        min-height: 10vh; /* 画面全体の高さを確保 */
                    }

        </style>
        <h1> 登録店舗一覧</h1>
        <div class="shop_list">
            <table border="1">
                <tr>
                    <th>ID</th>
                    <th>店舗名</th>
                    <th>住所</th>
                </tr>
                @foreach ($shops as $shop)
                <tr>
                    <td>{{ $shop->id }}</td>
                    <td><a href="{{ route('reserve.shopsel', $shop->id) }}" >{{ $shop->spName }}</a><br>{{ $shop->spNameKana }}</td>
                    <td>{{ $shop->spAddrCity }}{{ $shop->spAddrOther }}<br><a href="tel:{{ $shop->spTel1 }}">{{ $shop->spTel1 }}</a></td>
                    <td>{{ $shop->spMsgText }}</td>
                </tr>
                @endforeach
            </table>
        </div>  


        <footer>
            <x-footerbar>
            </x-footerbar>
        </footer>

    </body>
</html>
