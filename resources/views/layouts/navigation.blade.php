<!-- resources\views\layouts\navigation.blade.php --- Start -->
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!--「resources\views\layouts\navigation.blade.php」 -->
    <!-- Primary Navigation Menu Area -->
    <x-owner-menu />
    <div class="head_frame">
        <div class="head_inbox">
            <div class="head_image_wrap">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <!-- a href="{{ route('dashboard') }}" -->
                        <!--  resources\views\components\application-logo.blade.php -->
                        <x-application-logo  class="block h-9 w-auto fill-current text-gray-800" />
                    <!-- /a -->
                </div>

                <!--  app\View\Components\ShopMenu.php 店ごとのメニュー-->
                <!--  └ resources\views\components\shop-menu.blade.php -->
                <x-shop-menu :message="session('message')" />
            </div>
        </div>
    </div>
    <script src="{{ asset('js/hard_frame_hide.js') }}"></script>
</nav>
<!-- resources\views\layouts\navigation.blade.php --- End -->
