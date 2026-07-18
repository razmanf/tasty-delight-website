@section('title', 'Search Results')

<div>
    <!-- Back button + heading -->
    <div class="flex items-center gap-4 mb-6">
        <a href="javascript:history.back()"
           class="flex items-center gap-2 text-sm font-medium px-3 py-2 rounded-xl border transition-all hover:shadow-sm"
           style="border-color: var(--td-border); color: var(--td-text); background: var(--td-bg);">
            <i class="fa-solid fa-arrow-left"></i> Back
        </a>
        <div>
            <h1 class="text-xl font-bold" style="color: var(--td-text);">
                Search Results
                @if($query)
                    for <span style="color: var(--td-primary);">"{{ $query }}"</span>
                @endif
            </h1>
            @if($results->isNotEmpty())
                <p class="text-xs mt-0.5" style="color: var(--td-muted);">{{ $results->count() }} item(s) found</p>
            @endif
        </div>
    </div>

    <!-- Live search bar -->
    <div class="relative mb-8 max-w-lg">
        <input wire:model.live.debounce.400ms="query" type="text"
               placeholder="Search menu items, categories..."
               value="{{ $query }}"
               class="w-full pl-11 pr-4 py-3 rounded-2xl border text-sm outline-none transition-all"
               style="border-color: var(--td-border); background: var(--td-bg); color: var(--td-text);">
        <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2" style="color: var(--td-muted);"></i>
    </div>

    @if(strlen($query) < 2)
        <div class="td-card text-center py-16">
            <i class="fa-solid fa-magnifying-glass text-5xl mb-4" style="color: var(--td-muted);"></i>
            <p class="text-lg font-semibold" style="color: var(--td-text);">Start typing to search</p>
            <p class="text-sm mt-1" style="color: var(--td-muted);">Search across all menu items, categories, and descriptions.</p>
        </div>
    @elseif($results->isEmpty())
        <div class="td-card text-center py-16">
            <i class="fa-solid fa-face-sad-tear text-5xl mb-4" style="color: var(--td-muted);"></i>
            <p class="text-lg font-semibold" style="color: var(--td-text);">No results for "{{ $query }}"</p>
            <p class="text-sm mt-1" style="color: var(--td-muted);">Try a different search term or browse our categories.</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
            @foreach($results as $product)
            <div class="td-card overflow-hidden p-0 group">
                <div class="h-40 overflow-hidden">
                    @if($product->image)
                        <img src="{{ \Illuminate\Support\Str::startsWith($product->image, ['http://', 'https://']) ? $product->image : asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-105"
                             onerror="this.src='{{ asset('images/placeholder-food.png') }}'">
                    @else
                        <div class="w-full h-full flex items-center justify-center" style="background: #DD66251A;">
                            <i class="fa-solid fa-utensils text-3xl" style="color: var(--td-primary);"></i>
                        </div>
                    @endif
                </div>
                <div class="p-4">
                    <p class="font-bold truncate" style="color: var(--td-text);">{{ $product->name }}</p>
                    @if($product->category)
                        <span class="text-xs px-2 py-0.5 rounded-full mt-1 inline-block"
                              style="background: #FFCD381A; color: #B45309;">{{ $product->category->name }}</span>
                    @endif
                    <div class="flex items-center justify-between mt-3">
                        <span class="font-bold text-base" style="color: var(--td-primary);">$ {{ number_format($product->price, 2) }}</span>
                        @if($product->reviews_avg_rating)
                            <span class="flex items-center gap-1 text-xs" style="color: var(--td-warning);">
                                <i class="fa-solid fa-star text-xs"></i> {{ number_format($product->reviews_avg_rating, 1) }}
                            </span>
                        @endif
                    </div>
                    @if($product->description)
                        <p class="text-xs mt-2 line-clamp-2" style="color: var(--td-muted);">{{ $product->description }}</p>
                    @endif
                    <button wire:click="addToCart({{ $product->id }})" class="mt-4 w-full py-1.5 rounded-lg text-xs font-semibold transition-colors flex items-center justify-center gap-2" style="background: #DD66251A; color: var(--td-primary);">
                        <i class="fa-solid fa-cart-plus"></i> Add to Cart
                    </button>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>
