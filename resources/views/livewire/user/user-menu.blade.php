@section('title', 'Our Menu')

<div x-data="{ mode: @entangle('fulfillmentMode').live }">
    <!-- Hero/Header Section -->
    <div class="relative bg-black rounded-3xl overflow-hidden mb-8 h-48 md:h-64 shadow-xl">
        <img src="https://images.unsplash.com/photo-1555396273-367ea4eb4db5?q=80&w=2000&auto=format&fit=crop" 
             class="absolute inset-0 w-full h-full object-cover opacity-50" alt="Restaurant Atmosphere">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent"></div>
        <div class="absolute bottom-0 left-0 p-6 md:p-8 w-full flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <h1 class="text-3xl md:text-5xl font-black text-white mb-2" style="font-family: 'Outfit', sans-serif;">Our Menu</h1>
                <p class="text-white/80 font-medium max-w-lg">Discover our curated selection of fresh, delicious meals prepared daily by our expert chefs.</p>
            </div>
            
            <!-- Fulfillment Toggle -->
            <div class="bg-white/20 backdrop-blur-md p-1.5 rounded-full inline-flex border border-white/30 shadow-xl relative w-[220px]">
                <!-- Animated pill -->
                <div class="absolute top-1.5 bottom-1.5 w-[calc(50%-0.375rem)] bg-white rounded-full shadow-md transition-transform duration-300 ease-out z-0"
                     :class="mode === 'delivery' ? 'left-1.5 translate-x-0' : 'left-1.5 translate-x-full'"></div>
                
                <button @click="mode = 'delivery'" class="flex-1 py-2 text-sm font-bold transition-colors duration-300 z-10 text-center flex items-center justify-center"
                        :class="mode === 'delivery' ? 'text-black' : 'text-white hover:text-gray-200'">
                    <i class="fa-solid fa-person-biking mr-1.5" :style="mode === 'delivery' ? 'color: var(--td-primary);' : ''"></i> Delivery
                </button>
                <button @click="mode = 'pickup'" class="flex-1 py-2 text-sm font-bold transition-colors duration-300 z-10 text-center flex items-center justify-center"
                        :class="mode === 'pickup' ? 'text-black' : 'text-white hover:text-gray-200'">
                    <i class="fa-solid fa-store mr-1.5" :style="mode === 'pickup' ? 'color: var(--td-primary);' : ''"></i> Pickup
                </button>
            </div>
        </div>
    </div>

    <!-- Fulfillment Details & Map -->
    <div class="td-card mb-8 p-4 md:p-6 transition-all duration-300" x-data="mapData()" x-init="initMap()" :class="timeOpen ? 'relative z-40' : 'relative z-10'">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Details -->
            <div :class="mode === 'delivery' ? 'lg:col-span-1' : 'lg:col-span-3 lg:max-w-xl lg:mx-auto w-full'" class="space-y-4 transition-all duration-300">
                <h3 class="font-bold text-lg text-center lg:text-left" style="color: var(--td-text);" x-text="mode === 'delivery' ? 'Delivery Details' : 'Pickup Details'">
                </h3>
                
                <div x-show="mode === 'delivery'" x-collapse>
                    <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color: var(--td-muted);">Delivery Address</label>
                    <div class="relative">
                        <input type="text" x-model="addressQuery" @input.debounce.500ms="searchAddress()" placeholder="Search address..." 
                               class="w-full rounded-xl border px-10 py-2.5 text-sm outline-none transition-all"
                               style="border-color: var(--td-border); background: var(--td-bg); color: var(--td-text);">
                        <i class="fa-solid fa-location-dot absolute left-3.5 top-1/2 -translate-y-1/2" style="color: var(--td-primary);"></i>
                    </div>
                    
                    <!-- Suggestions -->
                    <div x-show="suggestions.length > 0" class="absolute z-50 mt-1 w-full max-w-sm bg-white dark:bg-gray-800 rounded-xl shadow-lg border max-h-48 overflow-y-auto" style="border-color: var(--td-border);">
                        <template x-for="s in suggestions" :key="s.place_id">
                            <div @click="selectAddress(s)" class="px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 cursor-pointer text-sm" style="color: var(--td-text);" x-text="s.display_name"></div>
                        </template>
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color: var(--td-muted);">Date</label>
                        <div class="relative">
                            <input type="date" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}" 
                                   class="w-full rounded-xl border pl-10 pr-3 py-2.5 text-sm outline-none transition-all cursor-pointer hover:border-orange-500 focus:border-orange-500 focus:ring-1 focus:ring-orange-500 shadow-sm appearance-none" 
                                   style="border-color: var(--td-border); background: var(--td-bg); color: var(--td-text);">
                            <i class="fa-regular fa-calendar absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" style="color: var(--td-primary);"></i>
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold uppercase tracking-wider mb-1.5" style="color: var(--td-muted);">Time</label>
                        <div class="relative" x-data="{
                                selected: 'asap',
                                options: [
                                    { value: 'asap', label: 'ASAP (30-45 min)' },
                                    { value: '12pm', label: '12:00 PM' },
                                    { value: '1pm', label: '1:00 PM' },
                                    { value: '6pm', label: '6:00 PM' }
                                ],
                                get selectedLabel() {
                                    return this.options.find(opt => opt.value === this.selected)?.label;
                                }
                            }" @click.outside="timeOpen = false">
                            
                            <button type="button" @click="timeOpen = !timeOpen" 
                                    class="w-full flex items-center justify-between rounded-xl border pl-10 pr-4 py-2.5 text-sm outline-none transition-all cursor-pointer hover:border-orange-500 shadow-sm"
                                    style="border-color: var(--td-border); background-color: var(--td-bg); color: var(--td-text);">
                                <span x-text="selectedLabel"></span>
                                <i class="fa-solid fa-chevron-down text-xs ml-2 transition-transform duration-200"
                                   :class="timeOpen ? 'rotate-180' : ''" style="color: var(--td-muted);"></i>
                            </button>
                            <i class="fa-regular fa-clock absolute left-3.5 top-[22px] -translate-y-1/2 pointer-events-none" style="color: var(--td-primary);"></i>
                            
                            <div x-show="timeOpen" style="display: none;"
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="transform opacity-0 scale-95"
                                 x-transition:enter-end="transform opacity-100 scale-100"
                                 x-transition:leave="transition ease-in duration-75"
                                 x-transition:leave-start="transform opacity-100 scale-100"
                                 x-transition:leave-end="transform opacity-0 scale-95"
                                 class="absolute z-50 left-0 mt-2 w-full rounded-xl shadow-xl border overflow-hidden"
                                 style="background-color: var(--td-bg); border-color: var(--td-border);">
                                <div class="py-1 max-h-48 overflow-y-auto">
                                    <template x-for="option in options" :key="option.value">
                                        <button type="button"
                                                @click="selected = option.value; timeOpen = false"
                                                class="w-full text-left px-4 py-2 text-sm hover:bg-gray-100 dark:hover:bg-gray-700/50 transition-colors flex items-center justify-between"
                                                :class="selected === option.value ? 'font-semibold' : ''"
                                                style="color: var(--td-text);">
                                            <span x-text="option.label"></span>
                                            <i x-show="selected === option.value" class="fa-solid fa-check text-xs" style="color: var(--td-primary);"></i>
                                        </button>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Map Area -->
            <div x-show="mode === 'delivery'" class="lg:col-span-2 rounded-2xl overflow-hidden border relative z-10" style="border-color: var(--td-border); height: 200px;">
                <div id="leaflet-map" class="w-full h-full z-10"></div>
            </div>
        </div>
    </div>

    <!-- Navigation & Search Row -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6 sticky top-[72px] z-30 py-4" style="background: var(--td-bg);">
        <!-- Category Slider -->
        <div class="w-full md:w-2/3 overflow-x-auto hide-scrollbar pb-1">
            <div class="flex gap-2 min-w-max">
                <button wire:click="selectCategory(0)" 
                        class="px-5 py-2 rounded-full text-sm font-bold border transition-all shadow-sm hover:bg-gray-100 dark:hover:bg-gray-800"
                        style="{{ $selectedCategoryId == 0 ? 'background: var(--td-primary); color: white; border-color: var(--td-primary);' : 'background: transparent; color: var(--td-text); border-color: var(--td-border);' }}">
                    All Items
                </button>
                @foreach($categories as $category)
                    <button wire:click="selectCategory({{ $category->id }})" 
                            class="px-5 py-2 rounded-full text-sm font-bold border transition-all shadow-sm whitespace-nowrap hover:bg-gray-100 dark:hover:bg-gray-800"
                            style="{{ $selectedCategoryId == $category->id ? 'background: var(--td-primary); color: white; border-color: var(--td-primary);' : 'background: transparent; color: var(--td-text); border-color: var(--td-border);' }}">
                        {{ $category->name }}
                    </button>
                @endforeach
            </div>
        </div>
        
        <!-- Menu Search Bar -->
        <div class="w-full md:w-1/3 relative">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search menu items..." 
                   class="w-full rounded-full border pl-11 pr-4 py-2 text-sm outline-none transition-all shadow-sm focus:shadow-md"
                   style="border-color: var(--td-border); background: var(--td-bg); color: var(--td-text);">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2" style="color: var(--td-muted);"></i>
        </div>
    </div>

    <!-- Products Grid -->
    @if($products->isEmpty())
        <div class="td-card text-center py-16">
            <i class="fa-solid fa-face-frown-open text-5xl mb-4" style="color: var(--td-muted);"></i>
            <p class="text-lg font-semibold" style="color: var(--td-text);">No items found</p>
            <p class="text-sm mt-1" style="color: var(--td-muted);">We couldn't find any items matching your criteria.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($products as $product)
                <div class="td-card p-0 overflow-hidden flex flex-col group relative">
                    
                    <!-- Favorite Button -->
                    <button wire:click="toggleFavorite({{ $product->id }})" 
                            class="absolute top-3 right-3 z-20 w-8 h-8 rounded-full bg-white/80 backdrop-blur shadow-md flex items-center justify-center transition-all duration-300 hover:scale-110 group/fav"
                            onmouseover="this.style.background='var(--td-primary)'; this.querySelector('i').style.color='white';"
                            onmouseout="this.style.background='rgba(255, 255, 255, 0.8)'; this.querySelector('i').style.color='#ef4444';">
                        <i class="fa-{{ in_array($product->id, $favorites) ? 'solid' : 'regular' }} fa-heart text-red-500 text-sm mt-0.5 transition-colors"></i>
                    </button>

                    <!-- Image -->
                    <div class="h-48 overflow-hidden relative bg-gray-100 dark:bg-gray-800">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                 @click="$dispatch('open-image-modal', '{{ asset('storage/' . $product->image) }}')"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105 cursor-pointer"
                                 onerror="this.src='{{ asset('images/placeholder-food.png') }}'">
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <i class="fa-solid fa-utensils text-4xl" style="color: var(--td-muted);"></i>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Content -->
                    <div class="p-5 flex flex-col flex-1">
                        <div class="flex justify-between items-start gap-2 mb-1">
                            <h3 class="font-bold text-lg leading-tight" style="color: var(--td-text);">{{ $product->name }}</h3>
                            <span class="font-black text-lg" style="color: var(--td-primary);">${{ number_format($product->price, 2) }}</span>
                        </div>
                        
                        <div class="flex items-center gap-2 mb-3">
                            <span class="text-xs px-2 py-0.5 rounded-md font-semibold truncate max-w-[100%]" style="background: #DD662515; color: var(--td-primary);">
                                {{ $product->category?->name }}
                            </span>
                            @if($product->reviews_avg_rating)
                                <div class="flex items-center gap-1 text-xs font-bold" style="color: var(--td-warning);">
                                    <i class="fa-solid fa-star"></i> {{ number_format($product->reviews_avg_rating, 1) }}
                                    <span class="font-normal opacity-70">({{ $product->reviews_count }})</span>
                                </div>
                            @endif
                        </div>
                        
                        <p class="text-sm line-clamp-2 mb-5 flex-1" style="color: var(--td-muted);">
                            {{ $product->description ?? 'Freshly prepared and made to order.' }}
                        </p>
                        
                        <button x-data="{ added: false }"
                                wire:click="addToCart({{ $product->id }})"
                                @click="added = true; setTimeout(() => added = false, 2000)"
                                class="w-full py-2.5 rounded-xl font-bold transition-all duration-300 flex items-center justify-center shadow-sm relative overflow-hidden"
                                :style="added ? 'background: #16a34a !important; color: white !important;' : 'background: #DD662515; color: var(--td-primary);'"
                                @mouseover="if(!added) { $el.style.background='var(--td-primary)'; $el.style.color='white'; }"
                                @mouseout="if(!added) { $el.style.background='#DD662515'; $el.style.color='var(--td-primary)'; }">
                            <div class="flex items-center gap-2 transition-transform duration-300" :class="added ? '-translate-x-3' : ''">
                                <i class="fa-solid fa-cart-plus"></i>
                                <span x-text="added ? 'Added to Cart' : 'Add to Cart'"></span>
                            </div>
                            <i class="fa-solid fa-check absolute right-4 transition-all duration-300 text-white"
                               :class="added ? 'opacity-100 scale-100' : 'opacity-0 scale-50'"></i>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

<script>
    function mapData() {
        return {
            timeOpen: false,
            map: null,
            marker: null,
            addressQuery: '',
            suggestions: [],
            
            initMap() {
                // Only init if map container exists
                if (!document.getElementById('leaflet-map')) return;
                
                // Default coordinates (e.g. New York, or center of delivery area)
                const defaultCoords = [40.7128, -74.0060];
                
                this.map = L.map('leaflet-map').setView(defaultCoords, 13);
                
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '© OpenStreetMap'
                }).addTo(this.map);
                
                this.marker = L.marker(defaultCoords, {draggable: true}).addTo(this.map);
                
                this.marker.on('dragend', (e) => {
                    const pos = e.target.getLatLng();
                    this.reverseGeocode(pos.lat, pos.lng);
                });
                
                this.map.on('click', (e) => {
                    this.marker.setLatLng(e.latlng);
                    this.reverseGeocode(e.latlng.lat, e.latlng.lng);
                });
            },
            
            async searchAddress() {
                if (this.addressQuery.length < 3) {
                    this.suggestions = [];
                    return;
                }
                try {
                    const res = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.addressQuery)}`);
                    this.suggestions = await res.json();
                } catch(e) { console.error(e); }
            },
            
            selectAddress(place) {
                this.addressQuery = place.display_name;
                this.suggestions = [];
                
                const lat = parseFloat(place.lat);
                const lon = parseFloat(place.lon);
                
                this.map.setView([lat, lon], 15);
                this.marker.setLatLng([lat, lon]);
            },
            
            async reverseGeocode(lat, lon) {
                try {
                    const res = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lon}`);
                    const data = await res.json();
                    if(data && data.display_name) {
                        this.addressQuery = data.display_name;
                    }
                } catch(e) { console.error(e); }
            }
        }
    }
</script>

<style>
.hide-scrollbar::-webkit-scrollbar {
    display: none;
}
.hide-scrollbar {
    -ms-overflow-style: none;
    scrollbar-width: none;
}
input[type="date"]::-webkit-calendar-picker-indicator {
    opacity: 0;
    cursor: pointer;
    position: absolute;
    right: 0;
    top: 0;
    width: 100%;
    height: 100%;
}
</style>
</div>
