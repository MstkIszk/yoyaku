<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    「resources\views\layouts\shop.blade.php」
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}">
                        <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
                    </a>
                </div>

                
                <style>

                    .header-fadeInUp {      /* メニューの枠 基本形 */
                        -webkit-transform: translate3d(0, 40px, 0);
                        transform: translate3d(0, 40px, 0);
                        opacity: 80;
                        -webkit-transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
                        transition: all 0.6s cubic-bezier(0.23, 1, 0.32, 1);
                    }

                    .headerAside {      /* メニューの枠 レスポンシブ部分 */
                        position: absolute;
                        top: 4px;
                        z-index: 999;
                        display: flex;
                        right: 107px;
                    }
                    @media (max-width: 769px) {
                        .headerAside {
                            position: fixed;
                            bottom: 30px;
                            left: 17px;
                            right: 17px;
                        }
                    }

                    .headerAside__item {
                        display: flex;
                        width: 177px;
                        height: 60px;
                        list-style-type: none;
                        text-align: center;
                    }
                    @media (max-width: 769px) {
                        .headerAside__item {
                            //width: 165px;
                            width: 100%;
                            height: 52px;
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

                <ol class="headerAside header-fadeInUp">
                    <li class="headerAside__item" ><a href="{{ route('reserve.index') }}" class="headerAside__link">予約リスト</a></li>
                    <li class="headerAside__item"><a href="{{ route('reserve.create') }}" class="headerAside__link">予約登録</a></li>
                    <li class="headerAside__item"><a href="{{ route('reserve.telnoinput') }}" class="headerAside__link">予約検索</a></li>
                    <!--li class="headerAside__item"><a href="{{ route('reserve.calender') }}" class="headerAside__link">カレンダー</a></li-->
                </ol>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('reserve.index')" :active="request()->routeIs('reserve.index')">
                        予約一覧
                    </x-nav-link>
                    <x-nav-link :href="route('reserve.telnoinput')" :active="request()->routeIs('reserve.telnoinput')">
                        予約検索
                    </x-nav-link>
                    <x-nav-link :href="route('reserve.create')" :active="request()->routeIs('RCreate')">
                        予約登録
                    </x-nav-link>
                </div>

            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        @auth
                        <div>{{ Auth::user()->name }}</div>
                        @else
                        <div>ゲストさん</div>
                        @endauth
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        @auth
                        <div>{{ Auth::user()->name }}</div>
                        @else
                        <div>ゲストさん</div>
                        @endauth
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-nav-link :href="route('reserve.index')" :active="request()->routeIs('reserve.index')">
                予約一覧
            </x-nav-link>

            <x-nav-link :href="route('reserve.telnoinput')" :active="request()->routeIs('reserve.telnoinput')">
                予約検索
            </x-nav-link>

            <x-nav-link :href="route('reserve.create')" :active="request()->routeIs('reserve.create')">
                予約登録
            </x-nav-link>
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
            @auth
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            @else                        
                <div class="font-medium text-base text-gray-800">ゲストさん</div>
                <div class="font-medium text-sm text-gray-500">未登録</div>
            @endauth
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
