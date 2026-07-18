@section('title', 'My Reviews')

<div x-data="{ showDeleteModal: false, deleteId: null }">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold" style="color: var(--td-text);">
            <i class="fa-solid fa-star mr-2" style="color: #FFB400;"></i> My Reviews
        </h1>
        <div class="relative">
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by product..."
                   class="pl-9 pr-4 py-2 rounded-xl border text-sm outline-none transition-all w-full sm:w-52"
                   style="border-color: var(--td-border); background: var(--td-bg); color: var(--td-text);">
            <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-xs" style="color: var(--td-muted);"></i>
        </div>
    </div>

    @if($reviews->isEmpty())
        <div class="td-card text-center py-20">
            <i class="fa-solid fa-star-half-stroke text-6xl mb-5" style="color: var(--td-muted);"></i>
            <p class="text-xl font-bold" style="color: var(--td-text);">No reviews yet</p>
            <p class="text-sm mt-2" style="color: var(--td-muted);">Reviews you write on products will appear here.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($reviews as $review)
            <div class="td-card">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-4 flex-1 min-w-0">
                        <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0">
                            @php 
                                $imagePath = $review->product?->image ?? $review->order?->products->first()?->image; 
                            @endphp
                            @if($imagePath)
                                <img src="{{ \Illuminate\Support\Str::startsWith($imagePath, ['http://', 'https://']) ? $imagePath : asset('storage/' . $imagePath) }}"
                                     alt="{{ $review->product?->name ?? 'Order' }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="background: #DD66251A;">
                                    <i class="fa-solid fa-utensils" style="color: var(--td-primary);"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0 mt-1">
                            <p class="font-bold truncate text-lg mb-2" style="color: var(--td-text);">
                                {{ $review->product?->name ?? ($review->order ? 'Order #' . $review->order->id . ' - ' . $review->order->products->pluck('name')->take(2)->join(', ') . ($review->order->products->count() > 2 ? '...' : '') : 'Deleted Product') }}
                            </p>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @if($review->rating)
                                <div class="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1 block">Food Review</span>
                                    <div class="flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star text-sm"
                                               style="color: #FFB400;"></i>
                                        @endfor
                                        <span class="text-[10px] ml-2" style="color: var(--td-muted);">{{ $review->created_at->format('M d, Y') }}</span>
                                    </div>
                                    @if($review->comment)
                                        <p class="text-sm mt-1.5 leading-relaxed" style="color: var(--td-muted);">{{ $review->comment }}</p>
                                    @endif
                                </div>
                                @endif

                                @if($review->rider_rating)
                                <div class="bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg border border-gray-100 dark:border-gray-700">
                                    <span class="text-[10px] font-bold uppercase tracking-wider text-gray-500 mb-1 block">Rider Review</span>
                                    <div class="flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <i class="fa-{{ $i <= $review->rider_rating ? 'solid' : 'regular' }} fa-star text-sm"
                                               style="color: #FFB400;"></i>
                                        @endfor
                                    </div>
                                    @if($review->rider_comment)
                                        <p class="text-sm mt-1.5 leading-relaxed" style="color: var(--td-muted);">{{ $review->rider_comment }}</p>
                                    @endif
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <button @click="showDeleteModal = true; deleteId = {{ $review->id }}"
                            class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-red-500 hover:bg-red-50 transition-colors">
                        <i class="fa-solid fa-trash-can text-sm"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $reviews->links() }}</div>
    @endif

    <!-- Delete Confirmation Modal -->
    <div x-show="showDeleteModal" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center">
        <!-- Backdrop -->
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm transition-opacity"
             x-show="showDeleteModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             x-transition:leave-end="opacity-0"></div>

        <!-- Modal Panel -->
        <div class="relative bg-white dark:bg-gray-900 rounded-2xl shadow-xl w-full max-w-sm mx-4 overflow-hidden transform transition-all"
             style="border: 1px solid var(--td-border);"
             x-show="showDeleteModal"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
            
            <div class="p-6 text-center">
                <div class="w-16 h-16 rounded-full bg-red-100 dark:bg-red-900/30 flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-triangle-exclamation text-2xl text-red-600 dark:text-red-400"></i>
                </div>
                <h3 class="text-xl font-bold mb-2" style="color: var(--td-text);">Delete Review</h3>
                <p class="text-sm" style="color: var(--td-muted);">Are you sure you want to delete this review? This action cannot be undone.</p>
            </div>
            
            <div class="p-4 flex gap-3" style="background: var(--td-bg);">
                <button @click="showDeleteModal = false" class="flex-1 py-2.5 rounded-xl font-bold transition-all text-sm bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700" style="color: var(--td-text);">Cancel</button>
                <button @click="$wire.deleteReview(deleteId); showDeleteModal = false" class="flex-1 py-2.5 rounded-xl font-bold transition-all text-sm bg-red-600 hover:bg-red-700 text-white shadow-sm">Delete</button>
            </div>
        </div>
    </div>
</div>
