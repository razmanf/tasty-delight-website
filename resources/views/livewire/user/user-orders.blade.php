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
                     class="absolute z-50 right-0 mt-2 w-44 rounded-xl shadow-xl border overflow-hidden"
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
        <div class="td-card text-center py-16">
            <i class="fa-solid fa-bag-shopping text-5xl mb-4" style="color: var(--td-muted);"></i>
            <p class="text-lg font-semibold" style="color: var(--td-text);">No orders found</p>
            <p class="text-sm mt-1" style="color: var(--td-muted);">Your order history will appear here.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($orders as $order)
            <div class="td-card">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background-color: #DD66251A;">
                            <i class="fa-solid fa-receipt" style="color: var(--td-primary);"></i>
                        </div>
                        <div>
                            <p class="font-bold" style="color: var(--td-text);">Order #{{ $order->id }}</p>
                            <p class="text-xs mt-0.5" style="color: var(--td-muted);">{{ $order->created_at->format('M d, Y · g:i A') }}</p>
                            @if($order->products->isNotEmpty())
                                <p class="text-xs mt-1" style="color: var(--td-muted);">
                                    {{ $order->products->take(2)->pluck('name')->join(', ') }}
                                    @if($order->products->count() > 2) & {{ $order->products->count() - 2 }} more @endif
                                </p>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center gap-4 sm:flex-col sm:items-end">
                        <span class="td-badge td-badge-{{ $order->status }}">
                            {{ ucwords(str_replace('_', ' ', $order->status)) }}
                        </span>
                        <p class="font-bold text-lg" style="color: var(--td-text);">{{ number_format($order->total_amount, 2) }}</p>
                        <span class="text-xs" style="color: var(--td-muted);">via {{ ucfirst($order->payment_method ?? 'N/A') }}</span>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $orders->links() }}
        </div>
    @endif
</div>
