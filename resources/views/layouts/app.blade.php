<!-- resources\views\layouts\app.blade.php --- Start -->
<!DOCTYPE html>
<link href="{{ asset('css\chat.css') }}" rel="stylesheet">
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- .ebv , config\app.php から読み込む -->
        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script src="{{ asset('js/app.js') }}" defer></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <script src=“https://cdnjs.cloudflare.com/ajax/libs/push.js/0.0.11/push.min.js”></script>

        <link href="{{ asset('css/chat.css') }}" rel="stylesheet">

        <style>
        body {
            -webkit-text-size-adjust: 100%;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            font-smoothing: antialiased;
            text-rendering: auto;
            font-size: 14px;
            font-family: "NotoSansCJKjp-Regular", sans-serif;
            color: #000;
            background: #fcfad4;
        }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page yield Content -- start -->
            @yield ('content')
            <!-- Page yield Content -- end -->
            <main>
            <!-- app.blade. -- main tag start -->
                {{ $slot }}
            <!-- app.blade. -- main tag end -->
            </main>
        </div>
        <x-footerbar>
        </x-footerbar>

    </body>
</html>
<!-- resources\views\layouts\app.blade.php --- end -->
