<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="icon" type="image/png" href="/images/favicon-32x32.png">
        <link href="{{ asset('css/chat.css') }}" rel="stylesheet">
        <link rel="icon" type="image/png" href="/images/favicon-32x32.png">

        <title>トップページ</title>
    </head>

    <body class="antialiased">
        <header>
            <div class="head_frame">
                <div class="head_inbox">
                    <img class="head_image" src="{{ asset('images/head_mounten.png') }}" alt="トップ画像">

                    <div class="head_title">総合予約システム</div>

                    <div class="head_center_title">
                        {{-- 認証済み（ログイン中）の場合 --}}
                        @auth
                            {{-- ログインユーザーの spName を表示 --}}
                            {{ Auth::user()->spName ?? '店舗名未設定' }}
                        @else
                            {{-- 未ログインの場合 --}}
                            予約トップ画面
                        @endauth
                    </div>

                    <div class="head_unei">運営：あちゃまＷＥＢ開発</div>
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

<style>
            .shop_list {
                display: flex;
                flex-direction: column;
                gap: 1.5rem; /* 店舗間のスペース */
                width: 97%; /* 親領域全体を指す */
            }
            .shop_box {
                border: 1px solid #e5e7eb; /* light gray */
                border-radius: 0.5rem; /* rounded-lg */
                padding: 1rem;
                background-color: #ffffff; /* white */
                box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06); /* shadow-md */
                display: flex;
                flex-direction: column; /* 中の要素は縦並び */

                width: 96%;
                margin-left: 0;
                margin-right: auto;
            }
            .shop_line {
                display: flex; /* 中の要素は横並び */
                align-items: center;
                border-bottom: 1px solid #f3f4f6; /* very light gray for separation */
                padding-bottom: 0.5rem;
                margin-bottom: 0.5rem;
            }
            .shop_id {
                width: 2rem;
                text-align: right;
                padding-right: 0.25rem;
            }
            .shop_name {
                width: 30%;
                text-align: left;
                padding-left: 0.5rem;
                line-height: 1.4;
            }
            .shop_msg {
                flex-grow: 1; /* 幅残り全て */
                text-align: left;
                padding-left: 0.5rem;
            }
            /* 商品情報用のスタイル */
            .shop_product {
                display: flex; /* 中の要素は横並び */
                align-items: center;
                /* padding-top: 0.3rem; 
                padding-bottom: 0.3rem; */
                padding: 0.75rem 0.5rem; /* 上下のパディングを増やし、左右は維持 */
                
                /* 左ボーダーの強化と、背景、影、丸みを追加 */
                border-left: 4px solid #4f46e5; /* indigo-700 のような濃い色、太さも増す */
                background-color: #f9fafb; /* 非常に薄いグレーの背景 */
                border-radius: 0.375rem; /* 角を少し丸く */
                box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); /* 軽い影 */
                
                margin-left: 1rem; /* 商品リスト全体のインデント */
                /* padding-left: 0.5rem; は border-leftとbackground-colorで調整 */
                margin-bottom: 0.75rem; /* 各商品アイテムの下にスペースを追加 */
            }
            /* .shop_product の最初の項目だけに適用される余白を調整 */
            .shop_product:first-of-type {
                margin-top: 0.5rem; /* リスト最初の要素と前のコンテンツの間に少しスペースを追加 */
            }
            .product_id {
                margin-left: 4.1rem; /* shop_idの幅3remとshop_lineのpaddingに合わせて調整（3rem + 1rem（shop_boxのpadding）- 0.5rem（padding-bottom）の調整を考慮して4.1remに */
                width: 2rem;
                text-align: right;
                padding-right: 0.25rem;
                border: 0;
            }
            .product_name {
                width: 12rem;
                text-align: left;
                padding-left: 0.5rem;
                border: 0;
            }
            .product_price {
                width: 6rem; /* 4remだと料金がはみ出る可能性があるため、少し広げました */
                text-align: right;
                padding-right: 0.5rem;
                border: 0;
            }
        </style>

        <h1> 登録店舗一覧</h1>

        <div class="shop_list article_frame">
            @foreach ($shops as $shop)
                <div class="shop_box">
                    <div class="shop_line">
                        <div class="shop_id">{{ $shop->id }}</div>
                        <div class="shop_name">
                            <a href="{{ route('profile.shopsel', $shop->id) }}" class="text-indigo-600 hover:text-indigo-800 font-semibold">{{ $shop->spName }}</a><br>
                            <span class="text-xs text-gray-500">{{ $shop->spNameKana }}</span>
                        </div>
                        <div class="shop_name">
                            {{ $shop->spAddrCity }}{{ $shop->spAddrOther }}<br>
                            <a href="tel:{{ $shop->spTel1 }}" class="text-sm text-green-600 hover:text-green-800">{{ $shop->spTel1 }}</a>
                        </div>
                        <div class="shop_msg">{{ $shop->spMsgText }}</div>
                    </div>

                    <!-- 商品一覧 -->
                    @if ($shop->products->count() > 0)
                        <h4 class="text-sm font-semibold mt-2 mb-1 pl-4 text-gray-700">提供サービス/商品:</h4>
                        @foreach ($shop->products as $product)
                            <div class="shop_product">
                                <div class="product_id">{{ $product->id }}</div>
                                <!--div class="product_name font-medium">{{ $product->productName }}</div-->

                                <div class="product_name font-medium">
                                    {{-- ここにリンクを追加 --}}
                                    <a href="{{ route('reserve.calender', ['user_id' => $shop->id, 'product_id' => $product->id]) }}" class="text-blue-600 hover:text-blue-800">
                                        {{ $product->productName }}
                                    </a>
                                </div>

                                <div class="product_name text-sm text-gray-600">時間：{{ \Carbon\Carbon::parse($product->TimeStart)->format('H:i') }}～{{ \Carbon\Carbon::parse($product->TimeEnd)->format('H:i') }}</div>
                                <div class="product_price font-bold text-red-600">￥{{ number_format($product->price) }}</div>
                            </div>
                        @endforeach
                    @else
                         <p class="text-sm text-gray-500 mt-2 pl-4">この店舗には現在、登録されているサービスはありません。</p>
                    @endif
                </div>
            @endforeach
        </div>        

        <footer>
            <x-footerbar>
            </x-footerbar>
        </footer>

    </body>
</html>
