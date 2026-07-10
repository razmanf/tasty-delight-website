<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>@hasSection('title') @yield('title') @else Login or register - TastyDelight @endif</title>

        <!-- FavIcon -->

        <link rel="icon" type="image/png" href="/storage/favicons/favicon-96x96.png" sizes="96x96" />
        <link rel="icon" type="image/svg+xml" href="/storage/favicons/favicon.svg" />
        <link rel="shortcut icon" href="/storage/favicons/favicon.ico" />
        <link rel="apple-touch-icon" sizes="180x180" href="/storage/favicons/apple-touch-icon.png" />
        <meta name="apple-mobile-web-app-title" content="TastyDelight" />
        <link rel="manifest" href="/storage/favicons/site.webmanifest" />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Styles -->
        @livewireStyles
    </head>
    <body style="background-image: url('{{ asset('images/background.svg') }}'); background-size: 80%; background-repeat: no-repeat; background-position: 20% center;">
        <div class="font-sans text-gray-900 antialiased bg-transparent">
            {{ $slot }}
        </div>

        @livewireScripts
    </body>
</html>
