<!-- resources\views\layouts\navigation.blade.php --- Start -->
<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!--「resources\views\layouts\navigation.blade.php」 -->
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <!-- a href="{{ route('dashboard') }}" -->
                        <!--  resources\views\components\application-logo.blade.php -->
                        <x-application-logo  class="block h-9 w-auto fill-current text-gray-800" />
                    <!-- /a -->
                </div>
                <x-owner-menu />

                <!--  app\View\Components\ShopMenu.php 店ごとのメニュー-->
                <!--  └ resources\views\components\shop-menu.blade.php -->
                <x-shop-menu :message="session('message')" />
            </div>
        </div>
    </div>

</nav>
<!-- resources\views\layouts\navigation.blade.php --- End -->
