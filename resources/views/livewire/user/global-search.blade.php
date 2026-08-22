<div class="relative w-full flex items-center" x-data="{ expanded: false, focused: false }" @click.outside="if (typeof expanded !== 'undefined') expanded = false; if (typeof focused !== 'undefined') focused = false">
    <div class="relative w-full z-50">
        <input
            wire:model.live.debounce.300ms="query"
            type="text"
            name="global_query"
            placeholder="Search..."
            class="td-search-input pr-12 transition-all duration-300 w-full md:w-44"
            :class="expanded ? 'md:w-72' : ''"
            @focus="expanded = true; focused = true"
            @keydown.escape.window="expanded = false; focused = false"
            @keydown.enter.prevent="if($wire.query.length >= 2) window.location.href = '{{ route('user.menu') }}?search=' + $wire.query"
        >
        @if(strlen($query) > 0)
            <button type="button" wire:click="$set('query', '')" class="absolute right-8 top-1/2 -translate-y-1/2 text-white/50 hover:text-white transition-colors">
                <i class="fa-solid fa-xmark text-sm"></i>
            </button>
        @endif
        <button type="button" class="absolute right-2 top-1/2 -translate-y-1/2 text-white/70 hover:text-white pointer-events-none">
            <i class="fa-solid fa-magnifying-glass text-sm"></i>
        </button>

        <!-- Dropdown Suggestions -->
        @if(strlen($query) >= 2)
        <div x-show="typeof focused !== 'undefined' && focused"
             x-transition
             class="absolute top-full left-0 mt-2 w-full md:w-72 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border overflow-hidden"
             style="border-color: var(--td-border); z-index: 100;">
            
            <!-- Products Section -->
            @if($products->isNotEmpty())
                <div class="px-3 py-2 text-xs font-semibold uppercase tracking-wider text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/50">
                    Menu Items
                </div>
                <ul>
                    @foreach($products as $product)
                        <li>
                            <a href="{{ route('user.menu') }}?highlight={{ $product->id }}" 
                               class="flex items-center gap-3 px-3 py-2 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-300">
                                @if($product->image)
                                    <img src="{{ \Illuminate\Support\Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}" class="w-8 h-8 rounded-md object-cover">
                                @else
                                    <div class="w-8 h-8 rounded-md flex items-center justify-center bg-gray-200 dark:bg-gray-700 text-gray-500">
                                        <i class="fa-solid fa-utensils text-xs"></i>
                                    </div>
                                @endif
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">$ {{ number_format($product->price, 2) }}</p>
                                </div>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @endif

            @if($products->isEmpty())
                <div class="px-4 py-6 text-center">
                    <p class="text-sm text-gray-500 dark:text-gray-400">No results found for "{{ $query }}"</p>
                </div>
            @else
                <div class="border-t p-1 bg-gray-50 dark:bg-gray-900/50" style="border-color: var(--td-border);">
                    <a href="{{ route('user.menu') }}?search={{ urlencode($query) }}" class="block w-full text-center text-xs font-semibold py-1.5 hover:underline" style="color: var(--td-primary);">
                        View all results &rarr;
                    </a>
                </div>
            @endif
        </div>
        @endif
    </div>
</div>
