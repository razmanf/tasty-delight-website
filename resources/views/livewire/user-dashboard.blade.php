@section('title', 'My Dashboard')

<div>
    <!-- ── Modern Hero Banner (Alpine Carousel) ── -->
    <div x-data="{
            activeSlide: 0,
            slides: [
                'https://images.unsplash.com/photo-1504674900247-0877df9cc836?q=80&w=2000&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1493770348161-369560ae357d?q=80&w=2000&auto=format&fit=crop',
                'https://images.unsplash.com/photo-1476224203421-9ac39bcb3327?q=80&w=2000&auto=format&fit=crop'
            ],
            interval: null,
            next() {
                this.activeSlide = this.activeSlide === this.slides.length - 1 ? 0 : this.activeSlide + 1;
            },
            prev() {
                this.activeSlide = this.activeSlide === 0 ? this.slides.length - 1 : this.activeSlide - 1;
            },
            start() {
                this.interval = setInterval(() => this.next(), 7000);
            },
            stop() {
                clearInterval(this.interval);
            }
         }"
         x-init="start()"
         @mouseenter="stop()"
         @mouseleave="start()"
         class="rounded-3xl mb-8 relative overflow-hidden shadow-xl min-h-[300px] flex items-center group/carousel">
        
        <!-- Background Images -->
        <template x-for="(image, index) in slides" :key="index">
            <div x-show="activeSlide === index" 
                 x-transition:enter="transition ease-out duration-1000"
                 x-transition:enter-start="opacity-0 scale-105"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-1000"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-105"
                 class="absolute inset-0 w-full h-full">
                <img :src="image" class="w-full h-full object-cover opacity-90 dark:opacity-70" alt="Delicious Food">
            </div>
        </template>
        <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent pointer-events-none"></div>
        
        <!-- Navigation Buttons -->
        <div class="absolute bottom-4 right-4 md:right-8 z-30 flex gap-2 opacity-100 md:opacity-0 md:group-hover/carousel:opacity-100 transition-opacity duration-300">
            <button @click="prev()" class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center transition-all hover:bg-white/40 shadow-lg hover:scale-110">
                <i class="fa-solid fa-chevron-left"></i>
            </button>
            <button @click="next()" class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md text-white flex items-center justify-center transition-all hover:bg-white/40 shadow-lg hover:scale-110">
                <i class="fa-solid fa-chevron-right"></i>
            </button>
        </div>
        
        <div class="relative z-10 max-w-2xl p-8 md:p-12">
            <div class="flex items-center gap-4 mb-4">
                @if(auth()->user()->profile_photo_path)
                    <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" alt="{{ $user->name }}"
                         class="w-14 h-14 rounded-full object-cover border-2 border-white/50 shadow-lg">
                @else
                    <img src="{{ asset('images/placeholder-avatar.png') }}" alt="{{ $user->name }}"
                         class="w-14 h-14 rounded-full object-cover border-2 border-white/50 shadow-lg bg-white/10 backdrop-blur-md">
                @endif
                <div>
                    <p class="text-white/80 font-medium">{{ $greeting }},</p>
                    <h1 class="text-2xl md:text-4xl font-black text-white leading-tight" style="font-family: 'Outfit', sans-serif;">
                        {{ explode(' ', $user->name)[0] }}! 👋
                    </h1>
                </div>
            </div>
            <p class="text-white/90 text-sm md:text-base font-medium max-w-lg mb-6 leading-relaxed">
                Hungry? Discover our curated selection of fresh, delicious meals prepared daily by our expert chefs.
            </p>
            <a href="{{ route('user.menu') }}" 
               class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-full font-bold shadow-sm transition-all duration-300 relative overflow-hidden bg-white text-black hover:bg-[#DD6625] hover:text-white group">
                <i class="fa-solid fa-utensils"></i> Order Now
            </a>
        </div>
        
        <!-- Carousel Dots -->
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2 z-20">
            <template x-for="(image, index) in slides" :key="index">
                <button @click="activeSlide = index" 
                        :class="activeSlide === index ? 'w-6 bg-white' : 'w-2 bg-white/50'"
                        class="h-2 rounded-full transition-all duration-300"></button>
            </template>
        </div>
    </div>

    <!-- ── Quick Stats ── -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @php
        $stats = [
            ['label' => 'Total Orders',    'value' => $totalOrders,                              'icon' => 'fa-bag-shopping',  'color' => '#DD6625'],
            ['label' => 'Total Spent',     'value' => '$ ' . number_format($totalSpent, 2),    'icon' => 'fa-wallet',        'color' => '#22C55E'],
            ['label' => 'Favorites',       'value' => $totalFavorites,                           'icon' => 'fa-heart',         'color' => '#EF4444'],
            ['label' => 'My Reviews',      'value' => $totalReviews,                             'icon' => 'fa-star',          'color' => '#FFB400'],
        ];
        @endphp

        @foreach($stats as $stat)
        <div class="td-card flex items-center gap-4 group hover:border-orange-500 transition-colors">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 transition-transform group-hover:scale-110"
                 style="background-color: {{ $stat['color'] }}1A;">
                <i class="fa-solid {{ $stat['icon'] }} text-lg" style="color: {{ $stat['color'] }};"></i>
            </div>
            <div>
                <p class="text-xs font-semibold uppercase tracking-wider" style="color: var(--td-muted);">{{ $stat['label'] }}</p>
                <p class="text-xl font-bold mt-0.5" style="color: var(--td-text);">{{ $stat['value'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    <!-- ── Main Grid ── -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
        
        <!-- Recent Orders -->
        <div class="lg:col-span-2">
            <div class="flex items-center justify-between mb-5">
                <h2 class="td-section-title mb-0 flex items-center gap-2">
                    <i class="fa-solid fa-clock-rotate-left" style="color: var(--td-primary);"></i> Recent Orders
                </h2>
                <a href="{{ route('user.orders') }}" class="text-sm font-bold hover:underline" style="color: var(--td-primary);">
                    View all &rarr;
                </a>
            </div>

            @if($recentOrders->isEmpty())
                <div class="td-card text-center py-12">
                    <i class="fa-solid fa-receipt text-5xl mb-4" style="color: var(--td-muted);"></i>
                    <p class="font-bold text-lg" style="color: var(--td-text);">No orders yet</p>
                    <p class="text-sm mt-1" style="color: var(--td-muted);">Browse our menu and place your first order!</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($recentOrders as $order)
                    <div class="td-card flex flex-col sm:flex-row sm:items-center justify-between gap-4 py-5 hover:border-orange-200 transition-colors">
                        <div class="flex items-center gap-4 min-w-0">
                            <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-sm"
                                 style="background-color: #DD66251A;">
                                <i class="fa-solid fa-bag-shopping" style="color: var(--td-primary);"></i>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-base" style="color: var(--td-text);">Order #{{ $order->id }}</p>
                                <p class="text-xs font-medium" style="color: var(--td-muted);">{{ $order->created_at->format('M d, Y · g:i A') }}</p>
                            </div>
                        </div>
                        <div class="flex items-center justify-between sm:justify-end w-full sm:w-auto gap-4 flex-shrink-0 mt-2 sm:mt-0 pt-2 sm:pt-0 border-t sm:border-0 border-gray-100 dark:border-gray-800">
                            <span class="td-badge td-badge-{{ $order->status }}">
                                {{ ucwords(str_replace('_', ' ', $order->status)) }}
                            </span>
                            <span class="font-black text-lg" style="color: var(--td-text);">${{ number_format($order->total_amount, 2) }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Quick Actions -->
        <div>
            <h2 class="td-section-title mb-5 flex items-center gap-2">
                <i class="fa-solid fa-bolt" style="color: var(--td-secondary);"></i> Quick Links
            </h2>
            <div class="grid grid-cols-2 gap-3">
                @php
                $actions = [
                    ['label' => 'My Menu',      'icon' => 'fa-utensils',      'route' => 'user.menu',       'color' => '#EAB308'],
                    ['label' => 'Favorites',    'icon' => 'fa-heart',         'route' => 'user.favorites',  'color' => '#EF4444'],
                    ['label' => 'My Cart',      'icon' => 'fa-cart-shopping', 'route' => 'user.cart',       'color' => '#22C55E'],
                    ['label' => 'My Reviews',   'icon' => 'fa-star',          'route' => 'user.reviews',    'color' => '#FFB400'],
                    ['label' => 'Notifications','icon' => 'fa-bell',          'route' => 'user.notifications','color' => '#3B82F6'],
                    ['label' => 'Settings',     'icon' => 'fa-gear',          'route' => 'user.settings',   'color' => '#8B5CF6'],
                ];
                @endphp

                @foreach($actions as $action)
                <a href="{{ route($action['route']) }}"
                   class="td-card flex flex-col items-center justify-center gap-3 py-6 text-center group border-2 border-transparent hover:border-gray-200 dark:hover:border-gray-700 transition-all">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110 shadow-sm"
                         style="background-color: {{ $action['color'] }}15;">
                        <i class="fa-solid {{ $action['icon'] }} text-xl" style="color: {{ $action['color'] }};"></i>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wide" style="color: var(--td-text);">{{ $action['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>
    </div>

    <!-- ── Recommended For You ── -->
    @if($recommendedProducts->isNotEmpty())
    <div class="mb-10">
        <div class="flex items-center justify-between mb-5 border-b pb-3" style="border-color: var(--td-border);">
            <div>
                <h2 class="td-section-title mb-0 flex items-center gap-2">
                    <i class="fa-solid fa-thumbs-up" style="color: var(--td-primary);"></i> Recommended For You
                </h2>
                <span class="text-xs font-medium" style="color: var(--td-muted);">Based on top ratings</span>
            </div>
            <a href="{{ route('user.menu') }}" class="td-btn-primary text-sm px-4 py-2">
                View All Items &rarr;
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($recommendedProducts as $product)
            @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
    @endif

    <!-- ── Trending Now ── -->
    @if($trendingProducts->isNotEmpty())
    <div class="mb-10">
        <div class="flex items-center justify-between mb-5 border-b pb-3" style="border-color: var(--td-border);">
            <div>
                <h2 class="td-section-title mb-0 flex items-center gap-2">
                    <i class="fa-solid fa-fire text-red-500"></i> Trending Now
                </h2>
                <span class="text-xs font-medium" style="color: var(--td-muted);">Most popular this week</span>
            </div>
            <a href="{{ route('user.menu') }}" class="td-btn-primary text-sm px-4 py-2">
                View All Items &rarr;
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($trendingProducts as $product)
            @include('partials.product-card', ['product' => $product])
            @endforeach
        </div>
    </div>
    @endif

    <!-- ── Special Offers ── -->
    @if($specialOffers->isNotEmpty())
    <div class="mb-10">
        <div class="flex items-center justify-between mb-5 border-b pb-3" style="border-color: var(--td-border);">
            <div>
                <h2 class="td-section-title mb-0 flex items-center gap-2">
                    <i class="fa-solid fa-tag text-green-500"></i> Special Offers
                </h2>
                <span class="text-xs font-medium" style="color: var(--td-muted);">Limited time deals just for you</span>
            </div>
            <a href="{{ route('user.menu') }}" class="td-btn-primary text-sm px-4 py-2">
                View All Items &rarr;
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-2 gap-6">
            @foreach($specialOffers as $product)
            <div class="td-card p-0 overflow-hidden flex flex-col sm:flex-row group relative border-2 border-green-500/20 hover:border-green-500/50 transition-colors">
                
                <button wire:click="toggleFavorite({{ $product->id }})" 
                        class="absolute top-3 right-3 z-20 w-8 h-8 rounded-full bg-white/80 backdrop-blur shadow-md flex items-center justify-center transition-all duration-300 hover:scale-110 group/fav hover:bg-[#DD6625]">
                    <i class="fa-{{ in_array($product->id, $favorites) ? 'solid' : 'regular' }} fa-heart text-red-500 text-sm mt-0.5 transition-colors group-hover/fav:text-white"></i>
                </button>

                <div class="h-48 sm:h-auto sm:w-1/3 overflow-hidden relative bg-gray-100 dark:bg-gray-800">
                    <div class="absolute top-0 left-0 bg-green-500 text-white text-xs font-black px-3 py-1 z-10 shadow-lg">20% Off</div>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                             @click="$dispatch('open-image-modal', '{{ asset('storage/' . $product->image) }}')"
                             class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 cursor-pointer"
                             onerror="this.src='{{ asset('images/placeholder-food.png') }}'">
                    @endif
                </div>
                
                <div class="p-5 flex flex-col flex-1">
                    <p class="font-bold text-xl mb-1" style="color: var(--td-text);">{{ $product->name }}</p>
                    <div class="flex items-center gap-2 mb-3">
                        <span class="text-xs px-2 py-0.5 rounded-md font-semibold truncate max-w-[100%]" style="background: #DD662515; color: var(--td-primary);">
                            {{ $product->category?->name }}
                        </span>
                    </div>
                    <p class="text-sm line-clamp-2 mb-4 flex-1" style="color: var(--td-muted);">
                        {{ $product->description }}
                    </p>
                    <div class="flex items-center justify-between mt-auto mb-3">
                        <div>
                            <span class="font-black text-2xl text-green-600">${{ number_format($product->price * 0.8, 2) }}</span>
                            <span class="text-sm line-through ml-2" style="color: var(--td-muted);">${{ number_format($product->price, 2) }}</span>
                        </div>
                        @if($product->reviews_avg_rating)
                            <div class="flex items-center gap-1 text-xs font-bold" style="color: var(--td-warning);">
                                <i class="fa-solid fa-star"></i> {{ number_format($product->reviews_avg_rating, 1) }}
                                <span class="font-normal opacity-70" style="color: var(--td-muted);">({{ $product->reviews_count ?? 0 }})</span>
                            </div>
                        @endif
                    </div>
                    <button x-data="{ added: false }"
                            wire:click="addToCart({{ $product->id }})"
                            @click="added = true; setTimeout(() => added = false, 2000)"
                            class="w-full py-2.5 rounded-xl font-bold transition-all duration-300 flex items-center justify-center shadow-sm relative overflow-hidden"
                            :style="added ? 'background: #16a34a !important; color: white !important;' : 'background: #FFCD38; color: black;'"
                            @mouseover="if(!added) { $el.style.background='#16a34a'; $el.style.color='white'; }"
                            @mouseout="if(!added) { $el.style.background='#FFCD38'; $el.style.color='black'; }">
                        <div class="flex items-center gap-2 transition-transform duration-300" :class="added ? '-translate-x-3' : ''">
                            <i class="fa-solid fa-cart-plus"></i>
                            <span x-text="added ? 'Claimed' : 'Claim'"></span>
                        </div>
                        <i class="fa-solid fa-check absolute right-4 transition-all duration-300 text-white"
                           :class="added ? 'opacity-100 scale-100' : 'opacity-0 scale-50'"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
