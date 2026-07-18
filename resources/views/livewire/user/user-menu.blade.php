@section('title', 'Our Menu')

<div>
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

    <!-- Category Description -->
    @if($selectedCategoryId != 0)
        @php
            $selectedCat = $categories->firstWhere('id', $selectedCategoryId);
        @endphp
        @if($selectedCat && $selectedCat->description)
            <div class="mb-8 px-4 py-3 rounded-2xl border" style="background: var(--td-bg); border-color: var(--td-border);">
                <p class="text-sm font-medium" style="color: var(--td-muted);">
                    <i class="fa-solid fa-circle-info mr-2" style="color: var(--td-primary);"></i>
                    {{ $selectedCat->description }}
                </p>
            </div>
        @endif
    @endif

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
                            class="absolute top-3 right-3 z-20 w-8 h-8 rounded-full bg-white/80 backdrop-blur shadow-md flex items-center justify-center transition-all duration-300 hover:scale-110 group/fav hover:bg-[#DD6625]">
                        <i class="fa-{{ in_array($product->id, $favorites) ? 'solid' : 'regular' }} fa-heart text-red-500 text-sm mt-0.5 transition-colors group-hover/fav:text-white"></i>
                    </button>

                    <!-- Image -->
                    <div class="h-48 overflow-hidden relative bg-gray-100 dark:bg-gray-800">
                        @if($product->image)
                            <img src="{{ \Illuminate\Support\Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                                 @click="$dispatch('open-image-modal', '{{ \Illuminate\Support\Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}')"
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
                        
                        <p class="text-sm mb-5 flex-1" style="color: var(--td-muted);">
                            {{ $product->description ?? 'Freshly prepared and made to order.' }}
                        </p>
                        
                        @if($product->stock > 0)
                          <div x-data="{ added: false }" class="w-full">
                              <button wire:click="addToCart({{ $product->id }})"
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
                                     :class="added ? 'opacity-100 translate-x-0' : 'opacity-0 translate-x-4'"></i>
                              </button>
                          </div>
                          @else
                          <button disabled
                                  class="w-full py-2.5 rounded-xl font-bold transition-all duration-300 flex items-center justify-center shadow-sm relative overflow-hidden cursor-not-allowed bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500">
                              <div class="flex items-center gap-2">
                                  <i class="fa-solid fa-ban"></i>
                                  <span>Out of Stock</span>
                              </div>
                          </button>
                          @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif


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
