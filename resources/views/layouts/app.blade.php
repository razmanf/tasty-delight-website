    <!DOCTYPE html>
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1">
            <meta name="csrf-token" content="{{ csrf_token() }}">

            <title>{{ config('app.name', 'Laravel') }}</title>
            
            <!-- FavIcon -->

            <link rel="icon" type="image/png" href="/storage/favicons/favicon-96x96.png" sizes="96x96" />
            <link rel="icon" type="image/svg+xml" href="/storage/favicons/favicon.svg" />
            <link rel="shortcut icon" href="/storage/favicons/favicon.ico" />
            <link rel="apple-touch-icon" sizes="180x180" href="/storage/favicons/apple-touch-icon.png" />
            <meta name="apple-mobile-web-app-title" content="TastyDelight" />
            <link rel="manifest" href="/storage/favicons/site.webmanifest" />
            
            <!-- Fonts -->
            <link rel="preconnect" href="https://fonts.googleapis.com">
            <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
            <link 
                href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap"
                rel="stylesheet"
            />
            <!-- Font Awesome CDN -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />

            <!-- Bootstrap -->
            
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css">

            <!-- Scripts -->  
            @vite(['resources/css/app.css', 'resources/js/app.js'])

            <!-- Styles -->
            @livewireStyles
        </head>
        <body class="font-sans antialiased">
            <x-banner />

            <div class="min-h-screen bg-gray-100">
                @livewire('navigation-menu')

                <!-- Page Heading -->
                @if (isset($header))
                    <header class="bg-white shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main>
                    {{ $slot }}
                </main>
            </div>

            @stack('modals')
            @stack('scripts')

            @livewireScripts
            @include('layouts.partials.footer')
        </body>
    </html>
