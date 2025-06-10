<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin Panel - @yield('title', 'Dashboard')</title>

    <!-- Include Vite assets -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Include Livewire styles -->
    @livewireStyles
</head>
<body>
    <main style="padding: 2rem;">
        @yield('content')
    </main>

    <footer style="text-align:center; padding:1rem; border-top:1px solid #ddd; color:#888;">
        &copy; {{ date('Y') }} Tasty Delight Admin
    </footer>

    <!-- Include Livewire scripts -->
    @livewireScripts
</body>
</html>
