@section('title', 'My Favorites')

<div x-data="{
    showConfirmModal: false,
    itemToDelete: null,
    confirmDelete(id) {
        this.itemToDelete = id;
        this.showConfirmModal = true;
    },
    executeDelete() {
        if(this.itemToDelete) {
            $wire.removeFavorite(this.itemToDelete);
            this.showConfirmModal = false;
            this.itemToDelete = null;
        }
    }
}">
    <h1 class="text-2xl font-bold mb-6" style="color: var(--td-text);">
        <i class="fa-solid fa-heart mr-2" style="color: #EF4444;"></i> My Favorites
    </h1>

    @if($favorites->isEmpty())
        <div class="td-card text-center py-20">
            <i class="fa-solid fa-heart-crack text-6xl mb-5" style="color: var(--td-muted);"></i>
            <p class="text-xl font-bold" style="color: var(--td-text);">No favorites yet</p>
            <p class="text-sm mt-2" style="color: var(--td-muted);">Items you favorite will appear here.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($favorites as $fav)
            @if($fav->product)
            <div class="td-card overflow-hidden p-0 group">
                <div class="h-40 overflow-hidden relative">
                    @if($fav->product->image)
                        <img src="{{ \Illuminate\Support\Str::startsWith($fav->product->image, ['http://', 'https://']) ? $fav->product->image : asset('storage/' . $fav->product->image) }}"
                             alt="{{ $fav->product->name }}"
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                             onerror="this.src='{{ asset('images/placeholder-food.png') }}'">
                    @else
                        <div class="w-full h-full flex items-center justify-center" style="background: #DD66251A;">
                            <i class="fa-solid fa-utensils text-3xl" style="color: var(--td-primary);"></i>
                        </div>
                    @endif
                    <!-- Remove button -->
                    <button @click="confirmDelete('{{ $fav->product->id }}')" 
                            class="absolute top-3 right-3 z-20 w-8 h-8 rounded-full bg-white/80 backdrop-blur shadow-md flex items-center justify-center transition-all duration-300 hover:scale-110 group/fav hover:bg-[#DD6625] transform-gpu backface-hidden">
                        <i class="fa-solid fa-heart fa-fw text-red-500 text-sm transition-colors group-hover/fav:text-white"></i>
                    </button>
                </div>
                <div class="p-4">
                    <p class="font-bold truncate" style="color: var(--td-text);">{{ $fav->product->name }}</p>
                    @if($fav->product->category)
                        <span class="text-xs px-2 py-0.5 rounded-full mt-1 inline-block"
                              style="background-color: #FFCD381A; color: #B45309;">{{ $fav->product->category->name }}</span>
                    @endif
                    <div class="flex items-center justify-between mt-3">
                        <span class="font-bold text-lg" style="color: var(--td-primary);">$ {{ number_format($fav->product->price, 2) }}</span>
                        @if($fav->product->reviews_avg_rating)
                            <span class="flex items-center gap-1 text-sm font-medium" style="color: var(--td-warning);">
                                <i class="fa-solid fa-star text-xs"></i> {{ number_format($fav->product->reviews_avg_rating, 1) }}
                            </span>
                        @endif
                    </div>
                    @if($fav->product->stock > 0)
                    <div x-data="{ added: false }" class="w-full">
                        <button wire:click="addToCart({{ $fav->product->id }})"
                                @click="added = true; setTimeout(() => added = false, 2000)"
                                class="mt-4 w-full py-2.5 rounded-xl font-bold transition-all duration-300 flex items-center justify-center shadow-sm relative overflow-hidden"
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
                    @else
                    <button disabled
                            class="mt-4 w-full py-2.5 rounded-xl font-bold transition-all duration-300 flex items-center justify-center shadow-sm relative overflow-hidden cursor-not-allowed bg-gray-100 dark:bg-gray-800 text-gray-400 dark:text-gray-500">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-ban"></i>
                            <span>Out of Stock</span>
                        </div>
                    </button>
                    @endif
                </div>
            </div>
            @endif
            @endforeach
        </div>
    @endif

    <!-- Alpine Custom Modal -->
    <div x-show="showConfirmModal"
         style="display: none;"
         class="fixed inset-0 z-[100] flex items-center justify-center px-4"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        <div class="td-card relative z-10 max-w-sm w-full p-6 text-center transform shadow-2xl"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
             
            <div class="w-16 h-16 rounded-full mx-auto flex items-center justify-center mb-4 shadow-inner bg-red-100 text-red-500">
                <i class="fa-solid fa-heart-crack text-2xl"></i>
            </div>
            
            <h3 class="text-xl font-bold mb-2" style="color: var(--td-text);">Remove Favorite?</h3>
            <p class="text-sm mb-6" style="color: var(--td-muted);">
                Are you sure you want to remove this item from your favorites?
            </p>
            
            <div class="flex gap-3 w-full">
                <button @click="showConfirmModal = false" class="flex-1 py-2.5 rounded-xl font-bold bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors" style="color: var(--td-text);">
                    Cancel
                </button>
                <button @click="executeDelete()" class="flex-1 py-2.5 rounded-xl font-bold text-white shadow-md transition-transform hover:scale-105 bg-red-500 hover:bg-red-600">
                    Remove
                </button>
            </div>
        </div>
    </div>
</div>
