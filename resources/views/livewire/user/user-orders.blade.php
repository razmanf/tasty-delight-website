@section('title', 'My Orders')

<div wire:poll.5s>
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
        <h1 class="text-2xl font-bold" style="color: var(--td-text);">
            <i class="fa-solid fa-bag-shopping mr-2" style="color: var(--td-primary);"></i> My Orders
        </h1>
        <div class="flex flex-col sm:flex-row gap-3">
            <!-- Search in table -->
            <div class="relative">
                <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search by order #..."
                       class="pl-9 pr-4 py-2 rounded-xl border text-sm outline-none transition-all w-full sm:w-48"
                       style="border-color: var(--td-border); background: var(--td-bg); color: var(--td-text);">
                <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-xs" style="color: var(--td-muted);"></i>
            </div>
            <!-- Status Filter (Alpine JS Dropdown) -->
            <div x-data="{
                    open: false,
                    selected: @entangle('statusFilter').live,
                    options: [
                        { value: '', label: 'All Statuses' },
                        { value: 'pending', label: 'Pending' },
                        { value: 'processing', label: 'Processing' },
                        { value: 'out_for_delivery', label: 'Out for Delivery' },
                        { value: 'delivered', label: 'Delivered' },
                        { value: 'completed', label: 'Completed' },
                        { value: 'cancelled', label: 'Cancelled' }
                    ],
                    get selectedLabel() {
                        return this.options.find(opt => opt.value === this.selected)?.label || 'All Statuses';
                    }
                }"
                 class="relative"
                 @click.outside="open = false">
                 
                <!-- Trigger Button -->
                <button type="button"
                        @click="open = !open"
                        class="flex items-center justify-between w-full sm:w-44 px-4 py-2 rounded-xl border text-sm outline-none transition-all"
                        style="border-color: var(--td-border); background: var(--td-bg); color: var(--td-text);">
                    <span x-text="selectedLabel"></span>
                    <i class="fa-solid fa-chevron-down text-xs ml-2 transition-transform duration-200"
                       :class="open ? 'rotate-180' : ''"
                       style="color: var(--td-muted);"></i>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="transform opacity-0 scale-95"
                     x-transition:enter-end="transform opacity-100 scale-100"
                     x-transition:leave="transition ease-in duration-75"
                     x-transition:leave-start="transform opacity-100 scale-100"
                     x-transition:leave-end="transform opacity-0 scale-95"
                     class="absolute z-50 right-0 w-44 rounded-xl shadow-xl border overflow-hidden"
                     style="background-color: var(--td-bg); border-color: var(--td-border); display: none;">
                    <div class="py-1">
                        <template x-for="option in options" :key="option.value">
                            <button type="button"
                                    @click="selected = option.value; open = false"
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

    @if($orders->isEmpty())
        <div class="td-card text-center py-20">
            <i class="fa-solid fa-box-open text-6xl mb-5" style="color: var(--td-muted);"></i>
            <p class="text-xl font-bold" style="color: var(--td-text);">No orders found</p>
            <p class="text-sm mt-2" style="color: var(--td-muted);">Your order history will appear here.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="td-card" x-data="{ expanded: false }">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background-color: #DD66251A;">
                            <i class="fa-solid fa-receipt" style="color: var(--td-primary);"></i>
                        </div>
                        <div>
                            <div class="flex items-center gap-2">
                                <p class="font-bold" style="color: var(--td-text);">Order #{{ $order->id }}</p>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide border" 
                                    :class="'{{ $order->order_type }}' === 'delivery' ? 'bg-blue-50 text-blue-600 border-blue-200 dark:bg-blue-900/30 dark:border-blue-800 dark:text-blue-400' : 'bg-orange-50 text-orange-600 border-orange-200 dark:bg-orange-900/30 dark:border-orange-800 dark:text-orange-400'">
                                    <i class="fa-solid" :class="'{{ $order->order_type }}' === 'delivery' ? 'fa-motorcycle' : 'fa-store'"></i> {{ ucfirst($order->order_type ?? 'Delivery') }}
                                </span>
                            </div>
                            <p class="text-xs mt-0.5" style="color: var(--td-muted);">{{ $order->created_at->format('M d, Y · g:i A') }}</p>
                            @if($order->products->isNotEmpty())
                                <p class="text-xs mt-1" style="color: var(--td-muted);">
                                    {{ $order->products->take(2)->map(fn($p) => $p->pivot->quantity . 'x ' . $p->name)->join(', ') }}
                                    @if($order->products->count() > 2) & {{ $order->products->count() - 2 }} more @endif
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-4 sm:flex-col sm:items-end">
                        <span class="td-badge td-badge-{{ $order->status }}">
                            {{ ucwords(str_replace('_', ' ', $order->status)) }}
                        </span>
                        <p class="font-bold text-lg" style="color: var(--td-text);">${{ number_format($order->total_amount, 2) }}</p>
                        <div class="flex items-center gap-2">
                            <span class="text-xs" style="color: var(--td-muted);">via {{ ucfirst($order->payment_method ?? 'N/A') }}</span>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-2 flex justify-center">
                    <button @click="expanded = !expanded" class="text-xs font-bold uppercase tracking-wider px-4 py-2 rounded-full hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors flex items-center gap-2" style="color: var(--td-primary);">
                        <span x-text="expanded ? 'Hide Details' : 'View Details'"></span>
                        <i class="fa-solid fa-chevron-down transition-transform duration-200" :class="expanded ? 'rotate-180' : ''"></i>
                    </button>
                </div>

                <!-- Expanded Details Section -->
                <div x-show="expanded" x-collapse class="mt-4 pt-4 border-t" style="border-color: var(--td-border); display: none;">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Order Items & Address -->
                        <div class="space-y-6">
                            <div>
                                <h4 class="text-sm font-bold mb-3 uppercase tracking-wider text-gray-500">Order Items</h4>
                                <div class="space-y-3">
                                    @foreach($order->products as $product)
                                    <div class="flex justify-between items-center text-sm">
                                        <div class="flex items-center gap-2">
                                            <span class="font-bold text-gray-700 dark:text-gray-300">{{ $product->pivot->quantity }}x</span>
                                            <span style="color: var(--td-text);">{{ $product->name }} <span class="text-xs" style="color: var(--td-muted); margin-left: 0.25rem;">(${{ number_format($product->pivot->price, 2) }} each)</span></span>
                                        </div>
                                        <span style="color: var(--td-text);">${{ number_format($product->pivot->quantity * $product->pivot->price, 2) }}</span>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            
                            <!-- Delivery Address if applicable -->
                            @if($order->order_type === 'delivery' && $order->delivery_address)
                            <div class="pt-4 border-t" style="border-color: var(--td-border);">
                                <h4 class="text-sm font-bold mb-3 uppercase tracking-wider text-gray-500">Delivery Address</h4>
                                <p class="block mb-3 text-sm leading-relaxed" style="color: var(--td-text);">
                                    <i class="fa-solid fa-location-dot mr-1" style="color: var(--td-primary);"></i>
                                    {{ $order->delivery_address }}
                                </p>
                                <div class="rounded-xl overflow-hidden border" style="border-color: var(--td-border); height: 180px;">
                                    <iframe width="100%" height="100%" style="border:0;" loading="lazy" allowfullscreen src="https://www.google.com/maps?q={{ urlencode($order->delivery_address) }}&output=embed"></iframe>
                                </div>
                            </div>
                            @endif
                        </div>

                        <!-- Order Summary -->
                        <div>
                            <!-- Receipt Breakdown -->
                            <div class="bg-gray-50 dark:bg-gray-800/50 p-4 rounded-xl">
                                <div class="flex justify-between items-center text-sm mb-2" style="color: var(--td-muted);">
                                    <span>Subtotal</span>
                                    @php $subtotal = $order->total_amount - $order->tax_amount - $order->delivery_fee; @endphp
                                    <span>${{ number_format($subtotal, 2) }}</span>
                                </div>
                                <div class="flex justify-between items-center text-sm mb-2" style="color: var(--td-muted);">
                                    <span>Tax (5%)</span>
                                    <span>${{ number_format($order->tax_amount, 2) }}</span>
                                </div>
                                @if($order->order_type === 'delivery')
                                <div class="flex justify-between items-center text-sm mb-2" style="color: var(--td-muted);">
                                    <span>Delivery Fee</span>
                                    <span>${{ number_format($order->delivery_fee, 2) }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between items-center font-bold text-base mt-3 pt-3 border-t" style="border-color: var(--td-border); color: var(--td-text);">
                                    <span>Total</span>
                                    <span style="color: var(--td-primary);">${{ number_format($order->total_amount, 2) }}</span>
                                </div>
                            </div>
                            
                            <!-- Leave Review Button -->
                            @if(in_array($order->status, ['completed', 'delivered']) && !$order->review)
                            <div class="mt-4">
                                <button wire:click="openReviewModal({{ $order->id }}, '{{ $order->order_type }}')" class="td-btn-primary w-full py-2.5 justify-center">
                                    <i class="fa-solid fa-star mr-2"></i> Leave a Review
                                </button>
                            </div>
                            @elseif($order->review)
                            <div class="mt-4 text-center">
                                <p class="text-sm font-bold text-green-600 dark:text-green-400">
                                    <i class="fa-solid fa-check-circle mr-1"></i> You've reviewed this order!
                                </p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif

    <!-- Review Modal -->
    @if($reviewingOrderId)
    <div class="fixed inset-0 z-[100] flex items-center justify-center px-4 bg-black/50 backdrop-blur-sm" wire:ignore.self>
        <div class="bg-white dark:bg-gray-900 w-full max-w-xl rounded-2xl shadow-2xl flex flex-col max-h-[90vh]">
            <!-- Header -->
            <div class="flex items-center justify-between p-5 border-b border-gray-100 dark:border-gray-800">
                <h3 class="font-bold text-xl" style="color: var(--td-text);">Leave a Review</h3>
                <button wire:click="closeReviewModal" class="w-8 h-8 rounded-full flex items-center justify-center text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            
            <!-- Body -->
            <div class="p-6 overflow-y-auto space-y-6">
                <!-- Food Review -->
                <div>
                    <label class="block text-sm font-bold mb-3 uppercase tracking-wider text-gray-500">How was the food?</label>
                    <div class="flex gap-2 mb-4 text-2xl">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="$set('rating', {{ $i }})" class="transition-colors hover:scale-110" style="color: {{ $rating >= $i ? 'var(--td-secondary)' : 'var(--td-border)' }};">
                                <i class="fa-solid fa-star"></i>
                            </button>
                        @endfor
                    </div>
                    <textarea wire:model.blur="comment" rows="3" class="w-full rounded-xl border bg-gray-50 dark:bg-gray-800/50 text-gray-900 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all border-gray-300 dark:border-gray-700 resize-none" placeholder="Tell us what you liked about the food..."></textarea>
                    @error('rating') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    @error('comment') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <!-- Rider Review (Only if delivery) -->
                @if($reviewingOrderType === 'delivery')
                <div class="pt-6 border-t border-gray-100 dark:border-gray-800">
                    <label class="block text-sm font-bold mb-3 uppercase tracking-wider text-gray-500">How was the delivery rider?</label>
                    <div class="flex gap-2 mb-4 text-2xl">
                        @for($i = 1; $i <= 5; $i++)
                            <button type="button" wire:click="$set('riderRating', {{ $i }})" class="transition-colors hover:scale-110" style="color: {{ $riderRating >= $i ? 'var(--td-secondary)' : 'var(--td-border)' }};">
                                <i class="fa-solid fa-star"></i>
                            </button>
                        @endfor
                    </div>
                    <textarea wire:model.blur="riderComment" rows="2" class="w-full rounded-xl border bg-gray-50 dark:bg-gray-800/50 text-gray-900 dark:text-white px-4 py-3 outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all border-gray-300 dark:border-gray-700 resize-none" placeholder="Was the rider polite and on time?"></textarea>
                    @error('riderRating') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    @error('riderComment') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                @endif

                <!-- Media Uploads -->
                <div class="pt-6 border-t border-gray-100 dark:border-gray-800">
                    <label class="block text-sm font-bold mb-3 uppercase tracking-wider text-gray-500">Add Photos or Videos (Max 5)</label>
                    <div class="flex items-center justify-center w-full">
                        <label class="relative flex flex-col items-center justify-center w-full h-32 border-2 border-dashed rounded-xl cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-800 transition-colors overflow-hidden group" style="border-color: var(--td-primary);">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 text-center">
                                <i class="fa-solid fa-cloud-arrow-up text-3xl mb-2 group-hover:scale-110 transition-transform" style="color: var(--td-primary);"></i>
                                <p class="text-sm" style="color: var(--td-text);"><span class="font-bold">Click to upload</span> or drag and drop</p>
                                <p class="text-xs mt-1" style="color: var(--td-muted);">JPG, PNG, MP4 up to 20MB</p>
                            </div>
                            <input type="file" wire:model="newMedia" multiple accept="image/*,video/mp4,video/quicktime,video/x-msvideo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-50" />
                        </label>
                    </div>
                    <!-- Loading Indicator for uploads -->
                    <div wire:loading wire:target="newMedia" class="text-sm mt-2 font-bold" style="color: var(--td-primary);">
                        <i class="fa-solid fa-spinner fa-spin mr-1"></i> Uploading...
                    </div>
                    @error('media') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                    @error('media.*') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror
                    @error('newMedia.*') <span class="text-red-500 text-xs mt-2 block">{{ $message }}</span> @enderror

                    <!-- Previews -->
                    @if($media && count($media) > 0)
                    <div class="grid grid-cols-5 gap-2 mt-4">
                        @foreach($media as $index => $file)
                            <div class="aspect-square rounded-lg border overflow-hidden border-gray-200 dark:border-gray-700 relative bg-gray-100 dark:bg-gray-800 flex items-center justify-center group">
                                @php $mime = $file->getMimeType(); @endphp
                                @if(str_contains($mime, 'image'))
                                    <img src="{{ $file->temporaryUrl() }}" class="w-full h-full object-cover">
                                @elseif(str_contains($mime, 'video'))
                                    <i class="fa-solid fa-video text-2xl text-gray-400"></i>
                                @endif
                                
                                <button type="button" wire:click="removeMedia({{ $index }})" class="absolute top-1 right-1 bg-black/60 hover:bg-red-500 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Footer -->
            <div class="p-5 border-t border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-800/30 rounded-b-2xl flex justify-end gap-3">
                <button type="button" wire:click="closeReviewModal" class="px-5 py-2.5 rounded-xl font-bold text-sm transition-colors border bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Cancel
                </button>
                <button type="button" wire:click="submitReview" wire:loading.attr="disabled" class="td-btn-primary px-6 py-2.5 flex items-center justify-center min-w-[140px]">
                    <span wire:loading.remove wire:target="submitReview">Submit Review</span>
                    <span wire:loading wire:target="submitReview">
                        <i class="fa-solid fa-spinner fa-spin mr-2"></i> Submitting...
                    </span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
