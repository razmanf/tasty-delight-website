@extends('layouts.user')

@section('title', 'About Us')

@section('content')
<div class="py-12">
    <!-- Hero Section -->
    <div class="max-w-7xl mx-auto rounded-3xl overflow-hidden shadow-2xl relative mb-16 h-80 flex items-center justify-center">
        <img src="{{ asset('images/about-hero.webp') }}" 
             alt="Restaurant Interior" class="absolute inset-0 w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/60"></div>
        <div class="relative z-10 text-center px-4">
            <h1 class="text-4xl md:text-5xl font-black text-white mb-4" style="font-family: 'Outfit', sans-serif;">Our Story</h1>
            <p class="text-white/90 text-lg max-w-2xl mx-auto">Serving joy, one plate at a time. Discover how TastyDelight became your favorite neighborhood kitchen.</p>
        </div>
    </div>

    <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 items-center mb-16">
        <div>
            <h2 class="text-3xl font-bold mb-4" style="color: var(--td-text);">Passionate About Food</h2>
            <p class="mb-4 text-base leading-relaxed" style="color: var(--td-muted);">
                Since our inception in 2024, TastyDelight has been on a mission to bring fresh, locally sourced ingredients to your table. We believe that food is not just fuel, but an experience meant to be shared with those you love.
            </p>
            <p class="text-base leading-relaxed" style="color: var(--td-muted);">
                Our chefs work tirelessly to craft menus that honor traditional recipes while embracing modern culinary techniques. Every dish is a labor of love, designed to surprise and delight.
            </p>
        </div>
        <div class="rounded-2xl overflow-hidden shadow-lg">
            <img src="{{ asset('images/about-chefs.webp') }}" alt="Chefs Cooking" class="w-full h-auto rounded-2xl object-cover pointer-events-none select-none" draggable="false" oncontextmenu="return false;">
        </div>
        </div>

        <!-- Values Section -->
        <div class="max-w-7xl mx-auto bg-gray-50 dark:bg-gray-800/50 rounded-3xl p-8 md:p-12 border" style="border-color: var(--td-border);">
            <h2 class="text-center text-3xl font-bold mb-10" style="color: var(--td-text);">Our Core Values</h2>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
                <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <i class="fa-solid fa-leaf text-4xl mb-4" style="color: var(--td-primary);"></i>
                    <h3 class="text-xl font-bold mb-2" style="color: var(--td-text);">Freshness First</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">We never compromise on the quality and freshness of our ingredients.</p>
                </div>
                <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <i class="fa-solid fa-face-smile text-4xl mb-4" style="color: var(--td-primary);"></i>
                    <h3 class="text-xl font-bold mb-2" style="color: var(--td-text);">Customer Joy</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">Your satisfaction is our ultimate goal. We aim for a smile with every bite.</p>
                </div>
                <div class="p-6 bg-white dark:bg-gray-800 rounded-2xl shadow-sm hover:shadow-md transition-shadow">
                    <i class="fa-solid fa-earth-americas text-4xl mb-4" style="color: var(--td-primary);"></i>
                    <h3 class="text-xl font-bold mb-2" style="color: var(--td-text);">Sustainability</h3>
                    <p class="text-sm text-gray-600 dark:text-gray-400">We are committed to eco-friendly packaging and ethical sourcing.</p>
                </div>
            </div>
    </div>
</div>
@endsection
