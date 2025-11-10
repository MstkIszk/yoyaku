<style>

.header-fadeInUp {      /* メニューの枠 基本形 */
    -webkit-transform: translate3d(0, 40px, 0);
    transform: translate3d(0, 40px, 0);
    opacity: 80;
    -webkit-transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
}

.headerAside {      /* メニューの枠 レスポンシブ部分 */
    position: absolute; /* head_image_wrap を基準に絶対位置指定 */
    /* 下中央に配置するための修正 */
    bottom: 0; /* 画像の下端に合わせる */
    left: 50%; /* 左端から 50% の位置 */
    transform: translateX(-50%); /* 要素の幅の半分だけ左に戻し、中央揃えを完了 */

    z-index: 999;
    display: flex;
    justify-content: center;
}
@media (max-width: 769px) {
    .headerAside {
        position: fixed;
        bottom: 30px;
        left: 17px;
        right: 17px;
        transform: translateX(0);
    }
}

.headerAside__item {
    display: flex;
    width: 10rem;
    list-style-type: none;
    align-items: center; /* 垂直方向に中央揃え */
    justify-content: center; /* 水平方向に中央揃え */
}
@media (max-width: 769px) {
    .headerAside__item {
        //width: 165px;
        width: 100%;
        height: 2rem;
    }
}

.headerAside__item:not(:last-child) {
    margin-right: 12px;
}
@media (max-width: 769px) {
    .headerAside__item:not(:last-child) {
        margin-right: 10px;
    }
}

.headerAside__link {
    flex-grow: 1;
    align-items: center;
    text-align: center;
    position: relative;
    background: #ffe600;
    border-radius: 34px;
    font-size: 14px;
    font-family: "NotoSansCJKjp-Bold", sans-serif;
    line-height: 3rem;
    width: 100%;
    height: 100%;
}

.headerAside__link::before {
    content: "";
    display: block;
    position: absolute;
    top: 50%;
    left: 25px;
    transform: translate(0, -50%);
    background-repeat: no-repeat;
    background-size: 100% auto;
}

.headerAside__link.-packPlan {
    padding-left: 61px;
}
@media (max-width: 769px) {
    .headerAside__link.-packPlan {
        padding-left: 34%;
    }
}

.headerAside__link.-packPlan::before {
    background-image: url("../images/common/icon_packplan.svg");
    width: 26px;
    height: 26px;
}
@media (max-width: 769px) {
    .headerAside__link {
        font-size: 10px;
        line-height: 2rem;
    }
    .headerAside__link.-packPlan::before {
        width: 23px;
        height: 23px;
    }
}

.headerAside__link.-reservation {
    padding-left: 70px;
}
@media (max-width: 769px) {
    .headerAside__link.-reservation {
        padding-left: 41%;
    }
}

.headerAside__link.-reservation::before {
    background-image: url("../images/common/icon_reservation.svg");
    width: 27px;
    height: 27px;
}

.headerAside__link:hover {
    animation: shake 0.6s forwards;
}

@keyframes shake {
    0% {
        animation-timing-function: ease-out;
        transform: scale3d(1, 1, 1) translate3d(0, 0, 0);
    }
    15% {
        animation-timing-function: ease-out;
        transform: scale3d(0.9, 0.9, 1) translate3d(0, 0, 0);
    }
    30% {
        animation-timing-function: ease-out;
        transform: scale3d(1.2, 0.8, 1) translate3d(0, 0, 0);
    }
    50% {
        animation-timing-function: ease-out;
        transform: scale3d(0.85, 1.2, 1) translate3d(0, -10%, 0);
    }
    70% {
        animation-timing-function: ease-out;
        transform: scale3d(1.1, 0.9, 1) translate3d(0, 0, 0);
    }
    100% {
        animation-timing-function: ease-out;
        transform: scale3d(1, 1, 1) translate3d(0, 0, 0);
    }
}
</style>
<div>
    <ol class="headerAside header-fadeInUp">
        {{-- <li class="headerAside__item" ><a href="{{ route('reserve.index') }}" class="headerAside__link">予約リスト</a></li>--}}
        {{-- <li class="headerAside__item"><a href="{{ route('reserve.create') }}" class="headerAside__link">予約登録A</a></li>--}}
        <li class="headerAside__item"><a href="{{ route('reserve.telnoinput') }}" class="headerAside__link">予約検索</a></li>
        {{-- <li class="headerAside__item"><a href="{{ route('reserve.calender') }}" class="headerAside__link">カレンダー</a></li> --}}
    </ol>
</div>
