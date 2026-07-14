<div class="td-card p-0 overflow-hidden flex flex-col group relative hover:border-orange-200 transition-colors">
    <!-- Favorite Button -->
    <button wire:click="toggleFavorite({{ $product->id }})" 
            class="absolute top-3 right-3 z-20 w-8 h-8 rounded-full bg-white/80 backdrop-blur shadow-md flex items-center justify-center transition-all duration-300 hover:scale-110 group/fav hover:bg-[#DD6625]">
        <i class="fa-{{ in_array($product->id, $favorites ?? []) ? 'solid' : 'regular' }} fa-heart text-red-500 text-sm mt-0.5 transition-colors group-hover/fav:text-white"></i>
    </button>

    <div class="h-40 overflow-hidden relative bg-gray-100 dark:bg-gray-800">
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
    
    <div class="p-4 flex flex-col flex-1">
        <p class="font-bold text-lg mb-1 truncate" style="color: var(--td-text);">{{ $product->name }}</p>
        <div class="flex items-center justify-between mb-3">
            <span class="text-xs px-2 py-0.5 rounded-md font-semibold truncate max-w-[100%]" style="background: #DD662515; color: var(--td-primary);">
                {{ $product->category?->name ?? 'Uncategorized' }}
            </span>
        </div>
        
        <p class="text-xs line-clamp-2 mb-4 flex-1" style="color: var(--td-muted);">
            {{ $product->description ?? 'Freshly prepared and made to order.' }}
        </p>
        
        <div class="flex items-center justify-between mt-auto mb-3">
            <span class="font-black text-lg" style="color: var(--td-primary);">${{ number_format($product->price, 2) }}</span>
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
