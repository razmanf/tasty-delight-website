<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@hasSection('title') @yield('title') - TastyDelight @else TastyDelight @endif</title>
    <meta name="description" content="@yield('meta_description', 'TastyDelight — Fast & Fresh Food Delivery')">

    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/storage/favicons/favicon.svg" />
    <link rel="shortcut icon" href="/storage/favicons/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="/storage/favicons/apple-touch-icon.png" />
    <link rel="manifest" href="/storage/favicons/site.webmanifest" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Flatpickr (Global) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/dark.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Leaflet Map -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Livewire -->
    @livewireStyles

    <!-- Prevent Dark Mode FOUC & Handle Livewire Navigation -->
    <script>
        function applyTheme() {
            if (localStorage.getItem('theme_{{ auth()->id() }}') === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
        applyTheme();
        document.addEventListener('livewire:navigated', applyTheme);
    </script>
</head>

<body class="font-sans antialiased" style="background-color: var(--td-bg); color: var(--td-text);">
<div
    x-data="{ dark: localStorage.getItem('theme_{{ auth()->id() }}') === 'dark', mobileMenu: false, searchOpen: false, searchQuery: '', profileOpen: false }"
    x-init="$watch('dark', val => { localStorage.setItem('theme_{{ auth()->id() }}', val ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', val) }); document.documentElement.classList.toggle('dark', dark)"
    class="min-h-screen flex flex-col"
>
<!-- ═══════════════════════════ NAVBAR ═══════════════════════════ -->
<nav class="td-navbar px-4 md:px-8 shadow-lg shadow-black/10 dark:shadow-black/50" x-cloak>
    <!-- Left: Logo -->
    <div class="flex items-center flex-shrink-0 py-1">
        <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center" draggable="false" oncontextmenu="return false;" style="user-select: none; -webkit-user-drag: none;">
            <img src="{{ asset('images/tasty-delight-logo.webp') }}"
                 alt="TastyDelight Logo"
                 class="h-16 w-16 rounded-lg object-cover"
                 draggable="false"
                 oncontextmenu="return false;"
                 style="user-select: none; -webkit-user-drag: none; pointer-events: none;"
                 onerror="this.style.display='none'">
        </a>
    </div>

    <!-- Center: Nav Links (desktop) -->
    <div class="hidden nav:flex items-center justify-center flex-1 gap-1 mx-6">
        @if(auth()->check())
            <a href="{{ route('user.dashboard') }}"
               wire:navigate
               class="px-3 py-2 rounded-lg text-sm font-medium text-white/80 hover:text-white hover:bg-white/15 transition-all flex items-center {{ request()->routeIs('user.dashboard') ? 'bg-white/20 text-white' : '' }}">
                <i class="fa-solid fa-house mr-1"></i> Dashboard
            </a>
            <a href="{{ route('user.menu') ?? '#' }}"
               wire:navigate
               class="px-3 py-2 rounded-lg text-sm font-medium text-white/80 hover:text-white hover:bg-white/15 transition-all flex items-center {{ request()->routeIs('user.menu') ? 'bg-white/20 text-white' : '' }}">
                <i class="fa-solid fa-utensils mr-1"></i> Menu
            </a>
            <a href="{{ route('user.orders') }}"
               wire:navigate
               class="px-3 py-2 rounded-lg text-sm font-medium text-white/80 hover:text-white hover:bg-white/15 transition-all flex items-center {{ request()->routeIs('user.orders') ? 'bg-white/20 text-white' : '' }}">
                <i class="fa-solid fa-bag-shopping mr-1"></i> My Orders
            </a>
            <a href="{{ route('user.favorites') }}"
               wire:navigate
               class="px-3 py-2 rounded-lg text-sm font-medium text-white/80 hover:text-white hover:bg-white/15 transition-all flex items-center {{ request()->routeIs('user.favorites') ? 'bg-white/20 text-white' : '' }}">
                <i class="fa-solid fa-heart mr-1"></i> Favorites
            </a>
            <a href="{{ route('user.cart') }}"
               wire:navigate
               class="px-3 py-2 rounded-lg text-sm font-medium text-white/80 hover:text-white hover:bg-white/15 transition-all flex items-center {{ request()->routeIs('user.cart') ? 'bg-white/20 text-white' : '' }}">
                <livewire:user.cart-count-badge key="desktop-cart-badge" />
            </a>
            <a href="{{ route('user.reviews') }}"
               wire:navigate
               class="px-3 py-2 rounded-lg text-sm font-medium text-white/80 hover:text-white hover:bg-white/15 transition-all flex items-center {{ request()->routeIs('user.reviews') ? 'bg-white/20 text-white' : '' }}">
                <i class="fa-solid fa-star mr-1"></i> Reviews
            </a>
        @endif
    </div>

    <!-- Right: Search + Bell + Avatar -->
    <div class="flex items-center gap-3 ml-auto">

        <!-- Search Bar -->
        <div class="hidden nav:block">
            <livewire:user.global-search key="desktop-search" />
        </div>

        @auth
            <!-- Notification Bell Dropdown -->
            <livewire:user.notification-bell />

            <!-- Profile Dropdown Component -->
            <livewire:user.profile-dropdown />
        @endauth
        
        @guest
            <div class="hidden nav:flex items-center gap-2">
                <a href="{{ route('login') }}" class="px-4 py-2 rounded-full text-sm font-bold text-white/90 hover:text-white hover:bg-white/15 transition-all">Log In</a>
                <a href="{{ route('register') }}" class="px-4 py-2 rounded-full text-sm font-bold bg-white text-black hover:bg-gray-100 transition-colors shadow-md">Register</a>
            </div>
        @endguest

        <!-- Mobile Hamburger -->
        <button @click="mobileMenu = !mobileMenu"
                class="nav:hidden text-white/80 hover:text-white p-2 transition-colors">
            <i class="fa-solid text-lg" :class="mobileMenu ? 'fa-xmark' : 'fa-bars'"></i>
        </button>
    </div>
</nav>

<!-- Mobile Menu Drawer -->
<div x-show="mobileMenu"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 -translate-y-2"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 -translate-y-2"
     class="fixed top-20 left-0 right-0 z-40 shadow-xl border-b nav:hidden"
     style="background-color: var(--td-header-bg); border-color: rgba(255,255,255,0.2);"
     @click.outside="mobileMenu = false">

    <!-- Mobile Search -->
    <div class="px-4 pt-4 pb-2 w-full">
        <livewire:user.global-search key="mobile-search" />
    </div>

    <div class="px-4 pb-4 flex flex-col gap-1">
        @if(auth()->check())
            <a href="{{ route('user.dashboard') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/90 hover:bg-white/15 transition-all text-sm font-medium"><i class="fa-solid fa-house w-5 text-center"></i> Dashboard</a>
            <a href="{{ route('user.menu') ?? '#' }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/90 hover:bg-white/15 transition-all text-sm font-medium"><i class="fa-solid fa-utensils w-5 text-center"></i> Menu</a>
            <a href="{{ route('user.orders') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/90 hover:bg-white/15 transition-all text-sm font-medium"><i class="fa-solid fa-bag-shopping w-5 text-center"></i> My Orders</a>
            <a href="{{ route('user.favorites') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/90 hover:bg-white/15 transition-all text-sm font-medium"><i class="fa-solid fa-heart w-5 text-center"></i> Favorites</a>
            <a href="{{ route('user.cart') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/90 hover:bg-white/15 transition-all text-sm font-medium"><livewire:user.cart-count-badge key="mobile-cart-badge" /></a>
            <a href="{{ route('user.reviews') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/90 hover:bg-white/15 transition-all text-sm font-medium"><i class="fa-solid fa-star w-5 text-center"></i> My Reviews</a>
            <a href="{{ route('user.settings') }}" wire:navigate class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/90 hover:bg-white/15 transition-all text-sm font-medium"><i class="fa-solid fa-gear w-5 text-center"></i> Settings</a>
            <div class="border-t border-white/20 mt-2 pt-2">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 px-3 py-2.5 w-full text-left text-red-200 hover:bg-white/10 rounded-lg text-sm font-medium transition-all">
                        <i class="fa-solid fa-right-from-bracket w-5 text-center"></i> Sign Out
                    </button>
                </form>
            </div>
        @else
            <a href="{{ route('login') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/90 hover:bg-white/15 transition-all text-sm font-medium"><i class="fa-solid fa-right-to-bracket w-5 text-center"></i> Log In</a>
            <a href="{{ route('register') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-white/90 hover:bg-white/15 transition-all text-sm font-medium"><i class="fa-solid fa-user-plus w-5 text-center"></i> Register</a>
        @endif
    </div>
</div>

<!-- ═══════════════════════════ PAGE CONTENT ═══════════════════════════ -->
<div class="td-page-content">
    <main class="flex-1 py-8 px-4 md:px-8 max-w-7xl mx-auto w-full">
        <!-- Flash Messages -->
        @if(session('success'))
            <div class="mb-6 flex items-center gap-3 px-4 py-3 rounded-xl text-green-800 bg-green-100 border border-green-200">
                <i class="fa-solid fa-circle-check"></i>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 flex items-center gap-3 px-4 py-3 rounded-xl text-red-800 bg-red-100 border border-red-200">
                <i class="fa-solid fa-circle-xmark"></i>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        @endif

        {{ $slot ?? '' }}
        @yield('content')
    </main>

    <!-- ═══════════════════════ FOOTER ═══════════════════════ -->
    <footer class="td-footer mt-auto">
        <div class="max-w-7xl mx-auto px-4 md:px-8 py-10">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">

                <!-- Col 1: Logo + Tagline -->
                <div class="flex flex-col items-start gap-3">
                    <div class="flex items-center gap-3">
                        <img src="{{ asset('images/tasty-delight-logo.webp') }}"
                             alt="TastyDelight"
                             class="w-12 h-12 rounded-lg object-cover border-2"
                             style="border-color: var(--td-primary); user-select: none; -webkit-user-drag: none; pointer-events: none;"
                             draggable="false"
                             oncontextmenu="return false;"
                             onerror="this.style.display='none'">
                        <span class="font-bold text-lg" style="color: var(--td-text);">TastyDelight</span>
                    </div>
                    <p class="text-sm leading-relaxed" style="color: var(--td-muted);">
                        Fast & Fresh Food Delivery. Satisfying your cravings since 2024.
                    </p>
                    <!-- Social Icons -->
                    <div class="flex gap-3 mt-1">
                        <a href="#" class="text-lg transition-colors hover:opacity-75" style="color: var(--td-primary);" title="Facebook"><i class="fa-brands fa-facebook-f"></i></a>
                        <a href="#" class="text-lg transition-colors hover:opacity-75" style="color: var(--td-primary);" title="Instagram"><i class="fa-brands fa-instagram"></i></a>
                        <a href="#" class="text-lg transition-colors hover:opacity-75" style="color: var(--td-primary);" title="Twitter/X"><i class="fa-brands fa-x-twitter"></i></a>
                    </div>
                </div>

                <!-- Col 2: Quick Links -->
                <div>
                    <h4 class="font-bold text-sm uppercase tracking-wider mb-4" style="color: var(--td-text);">Quick Links</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('user.dashboard') }}" class="text-sm hover:underline transition-colors" style="color: var(--td-muted);">Dashboard</a></li>
                        <li><a href="{{ route('user.menu') ?? '#' }}" class="text-sm hover:underline transition-colors" style="color: var(--td-muted);">Menu</a></li>
                        <li><a href="{{ route('user.orders') }}"    class="text-sm hover:underline transition-colors" style="color: var(--td-muted);">My Orders</a></li>
                        <li><a href="{{ route('user.favorites') }}" class="text-sm hover:underline transition-colors" style="color: var(--td-muted);">My Favorites</a></li>
                        <li><a href="{{ route('about') ?? '#' }}" class="text-sm hover:underline transition-colors" style="color: var(--td-muted);">About Us</a></li>
                    </ul>
                </div>

                <!-- Col 3: Support -->
                <div>
                    <h4 class="font-bold text-sm uppercase tracking-wider mb-4" style="color: var(--td-text);">Support</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('user.reviews') }}"      class="text-sm hover:underline" style="color: var(--td-muted);">My Reviews</a></li>
                        <li><a href="{{ route('user.settings') }}"     class="text-sm hover:underline" style="color: var(--td-muted);">Account Settings</a></li>
                        <li><a href="{{ route('policy.show') }}"       class="text-sm hover:underline" style="color: var(--td-muted);">Privacy Policy</a></li>
                        <li><a href="{{ route('terms.show') }}"        class="text-sm hover:underline" style="color: var(--td-muted);">Terms of Service</a></li>
                        <li><a href="{{ route('contact') ?? '#' }}"    class="text-sm hover:underline" style="color: var(--td-muted);">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Col 4: Contact -->
                <div>
                    <h4 class="font-bold text-sm uppercase tracking-wider mb-4" style="color: var(--td-text);">Contact</h4>
                    <ul class="space-y-2">
                        <li class="flex items-center gap-2 text-sm" style="color: var(--td-muted);">
                            <i class="fa-solid fa-envelope w-4" style="color: var(--td-primary);"></i>
                            support@tastydelight.shop
                        </li>
                        <li class="flex items-center gap-2 text-sm" style="color: var(--td-muted);">
                            <i class="fa-solid fa-phone w-4" style="color: var(--td-primary);"></i>
                            +94 11 234 5678
                        </li>
                        <li class="flex items-center gap-2 text-sm" style="color: var(--td-muted);">
                            <i class="fa-regular fa-clock w-4" style="color: var(--td-primary);"></i>
                            Open Daily, 08:00 - 22:00
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t py-6 pb-24 sm:pb-6" style="border-color: var(--td-border);">
            <div class="max-w-7xl mx-auto px-4 md:px-8 flex flex-col sm:flex-row items-center justify-between gap-4">
                <p class="text-xs text-center sm:text-left leading-relaxed" style="color: var(--td-muted);">
                    &copy; {{ date('Y') }} TastyDelight by Razman Farook. All rights reserved.<br>
                    <a href="/humans.txt" target="_blank" class="hover:underline">Unauthorized copying prohibited.</a>
                </p>
                <p class="text-xs text-center sm:text-right sm:pr-16" style="color: var(--td-muted);">
                    Made with <i class="fa-solid fa-heart mx-0.5" style="color: var(--td-primary);"></i> in Sri Lanka
                </p>
            </div>
        </div>
    </footer>
</div>

<!-- ── Global Image Modal ── -->
<div x-data="{
    isOpen: false,
    imageUrl: '',
    openModal(url) {
        this.imageUrl = url;
        this.isOpen = true;
    }
}"
@open-image-modal.window="openModal($event.detail)"
x-show="isOpen"
style="display: none;"
class="fixed inset-0 z-[200] flex items-center justify-center p-4 pt-16 sm:pt-16 bg-black/80 backdrop-blur-sm"
x-transition:enter="transition ease-out duration-300"
x-transition:enter-start="opacity-0"
x-transition:enter-end="opacity-100"
x-transition:leave="transition ease-in duration-200"
x-transition:leave-start="opacity-100"
x-transition:leave-end="opacity-0"
@click="isOpen = false">
    <div class="relative flex justify-center items-center" @click.stop>
        <button @click="isOpen = false" class="absolute -top-12 right-0 sm:-top-10 text-white hover:text-gray-300 transition-colors z-[210]">
            <i class="fa-solid fa-xmark text-3xl drop-shadow-md"></i>
        </button>
        <img :src="imageUrl" class="max-w-full sm:max-w-3xl max-h-[75vh] sm:max-h-[85vh] object-contain rounded-xl shadow-2xl">
    </div>
</div>

<!-- ── Back to Top Button ── -->
<div x-data="{ show: false }" 
     @scroll.window="show = window.pageYOffset > 300"
     x-show="show" 
     x-transition:enter="transition ease-out duration-300"
     x-transition:enter-start="opacity-0 translate-y-10"
     x-transition:enter-end="opacity-100 translate-y-0"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100 translate-y-0"
     x-transition:leave-end="opacity-0 translate-y-10"
     style="display: none;"
     class="fixed bottom-6 right-6 z-50">
    <button @click="window.scrollTo({top: 0, behavior: 'smooth'})"
            class="w-12 h-12 rounded-full shadow-xl flex items-center justify-center text-white transition-transform hover:scale-110 focus:outline-none"
            style="background: var(--td-primary);">
        <i class="fa-solid fa-arrow-up"></i>
    </button>
</div>

@livewireScripts

<!-- ── Graceful Session Expiry Handling ── -->
<script>
    document.addEventListener('livewire:init', () => {
        Livewire.hook('request', ({ fail }) => {
            fail(({ status, preventDefault }) => {
                if (status === 419) {
                    preventDefault();
                    // Smoothly redirect to login instead of showing the ugly browser alert
                    window.location.href = '{{ route('login') }}?expired=1';
                }
            })
        })
    })
</script>
</div>
</body>
</html>
