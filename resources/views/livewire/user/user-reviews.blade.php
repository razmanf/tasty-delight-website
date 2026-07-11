@section('title', 'My Reviews')

<div>
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
        <div class="td-card text-center py-16">
            <i class="fa-regular fa-star text-5xl mb-4" style="color: var(--td-muted);"></i>
            <p class="text-lg font-semibold" style="color: var(--td-text);">No reviews yet</p>
            <p class="text-sm mt-1" style="color: var(--td-muted);">Reviews you write on products will appear here.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($reviews as $review)
            <div class="td-card">
                <div class="flex items-start justify-between gap-4">
                    <div class="flex items-start gap-4 flex-1 min-w-0">
                        <div class="w-12 h-12 rounded-xl overflow-hidden flex-shrink-0">
                            @if($review->product?->image)
                                <img src="{{ asset('storage/' . $review->product->image) }}"
                                     alt="{{ $review->product->name }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center" style="background: #DD66251A;">
                                    <i class="fa-solid fa-utensils" style="color: var(--td-primary);"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="font-bold truncate" style="color: var(--td-text);">{{ $review->product?->name ?? 'Deleted Product' }}</p>
                            <div class="flex items-center gap-0.5 mt-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-{{ $i <= $review->rating ? 'solid' : 'regular' }} fa-star text-sm"
                                       style="color: #FFB400;"></i>
                                @endfor
                                <span class="text-xs ml-2" style="color: var(--td-muted);">{{ $review->created_at->format('M d, Y') }}</span>
                            </div>
                            @if($review->comment)
                                <p class="text-sm mt-2 leading-relaxed" style="color: var(--td-muted);">{{ $review->comment }}</p>
                            @endif
                        </div>
                    </div>
                    <button wire:click="deleteReview({{ $review->id }})"
                            wire:confirm="Delete this review?"
                            class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-red-500 hover:bg-red-50 transition-colors">
                        <i class="fa-solid fa-trash-can text-sm"></i>
                    </button>
                </div>
            </div>
            @endforeach
        </div>
        <div class="mt-6">{{ $reviews->links() }}</div>
    @endif
</div>
