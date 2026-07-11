@section('title', 'My Cart')

<div x-data="{ 
    isEditMode: false,
    showConfirmModal: false, 
    isSelectAll: false,
    toggleAll() {
        this.isSelectAll = !this.isSelectAll;
        $wire.toggleAll(this.isSelectAll);
    }
}">
    <h1 class="text-2xl font-bold mb-6 flex items-center gap-3" style="color: var(--td-text);">
        <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-md" style="background: var(--td-primary); color: white;">
            <i class="fa-solid fa-cart-shopping"></i>
        </div>
        My Cart
    </h1>

    @if(!$cart || $cart->items->isEmpty())
        <div class="td-card text-center py-20">
            <i class="fa-solid fa-cart-arrow-down text-6xl mb-5" style="color: var(--td-muted);"></i>
            <p class="text-xl font-bold" style="color: var(--td-text);">Your cart is empty</p>
            <p class="text-sm mt-2" style="color: var(--td-muted);">Add items from our menu to get started.</p>
            <a href="{{ route('user.menu') }}" class="td-btn-primary mt-6 text-sm">
                Browse Menu <i class="fa-solid fa-arrow-right ml-1"></i>
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Cart Items -->
            <div class="lg:col-span-2 space-y-4">
                
                <!-- Bulk Actions Bar -->
                <div class="flex items-center justify-between p-4 rounded-xl border bg-gray-50 dark:bg-gray-800/50" style="border-color: var(--td-border);">
                    <div class="flex items-center gap-3 cursor-pointer" @click="toggleAll()" x-show="isEditMode" style="display: none;">
                        <div class="w-5 h-5 rounded border flex items-center justify-center transition-colors"
                             :class="$wire.selectedItems.length === {{ $cart->items->count() }} ? 'bg-primary border-primary' : 'bg-white dark:bg-gray-700'"
                             :style="$wire.selectedItems.length === {{ $cart->items->count() }} ? 'background: var(--td-primary); border-color: var(--td-primary);' : 'border-color: var(--td-border);'">
                            <i class="fa-solid fa-check text-white text-xs" x-show="$wire.selectedItems.length === {{ $cart->items->count() }}"></i>
                        </div>
                        <span class="text-sm font-bold select-none" style="color: var(--td-text);">Select All</span>
                    </div>
                    <div x-show="!isEditMode"></div>
                    
                    <div class="flex items-center gap-2">
                        <button type="button" @click="isEditMode = false" x-show="isEditMode" style="display: none;" class="td-btn-secondary text-sm py-1.5 px-3 text-red-500 hover:bg-red-50 border-red-200">
                            <i class="fa-solid fa-xmark"></i> Cancel
                        </button>
                        <button type="button" @click="isEditMode = true" class="td-btn-secondary text-sm py-1.5 px-3 transition-opacity" :class="isEditMode ? 'opacity-50 cursor-not-allowed' : ''" :disabled="isEditMode">
                            <i class="fa-solid fa-pen"></i> Edit
                        </button>
                        <button type="button" @click="showConfirmModal = true" class="td-btn-secondary text-sm py-1.5 px-3 transition-opacity" :class="!isEditMode ? 'opacity-50 cursor-not-allowed' : ''" :disabled="!isEditMode">
                            <i class="fa-solid fa-floppy-disk"></i> Save Changes
                        </button>
                    </div>
                </div>

                @foreach($cart->items as $item)
                <div class="td-card flex flex-col sm:flex-row items-start sm:items-center gap-4 transition-all hover:border-orange-200 group relative"
                     :class="$wire.selectedItems.includes('{{ $item->id }}') ? 'opacity-50 bg-gray-50 dark:bg-gray-800/50' : ''">

                    <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 shadow-sm border border-gray-100 dark:border-gray-700">
                        @if($item->product->image)
                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                 alt="{{ $item->product->name }}"
                                 @click="$dispatch('open-image-modal', '{{ asset('storage/' . $item->product->image) }}')"
                                 class="w-full h-full object-cover transition-transform duration-300 group-hover:scale-110 cursor-pointer"
                                 onerror="this.src='{{ asset('images/placeholder-food.png') }}'">
                        @else
                            <div class="w-full h-full flex items-center justify-center" style="background: #DD66251A;">
                                <i class="fa-solid fa-utensils text-xl" style="color: var(--td-primary);"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <p class="font-bold text-lg truncate" style="color: var(--td-text);">{{ $item->product->name }}</p>
                        <p class="text-xs mt-1" style="color: var(--td-muted);">{{ $item->product->category?->name }}</p>
                        
                        <div class="flex items-center gap-4 mt-3" x-show="!$wire.selectedItems.includes('{{ $item->id }}')">
                            <!-- Quantity Controls -->
                            <div x-show="isEditMode" style="display: none;" class="flex items-center border rounded-lg h-9 overflow-hidden shadow-sm bg-gray-50 dark:bg-gray-800" style="border-color: var(--td-border);">
                                <button wire:click="decrementItem({{ $item->id }})" 
                                        class="w-10 h-full flex items-center justify-center transition-colors hover:bg-gray-200 dark:hover:bg-gray-700"
                                        style="color: var(--td-text);">
                                    <i class="fa-solid fa-minus text-xs"></i>
                                </button>
                                <span class="w-10 text-center text-sm font-bold bg-white dark:bg-gray-900 h-full flex items-center justify-center" style="color: var(--td-text);">
                                    {{ $quantities[$item->id] ?? $item->quantity }}
                                </span>
                                <button wire:click="incrementItem({{ $item->id }})" 
                                        class="w-10 h-full flex items-center justify-center transition-colors hover:bg-gray-200 dark:hover:bg-gray-700"
                                        style="color: var(--td-text);">
                                    <i class="fa-solid fa-plus text-xs"></i>
                                </button>
                            </div>
                            <div x-show="!isEditMode" class="text-sm font-bold" style="color: var(--td-text);">
                                Qty: {{ $quantities[$item->id] ?? $item->quantity }}
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-4 flex-shrink-0">
                        <div class="flex flex-col items-end gap-1">
                            <span class="font-black text-xl" style="color: var(--td-primary);">
                                ${{ number_format(($quantities[$item->id] ?? $item->quantity) * $item->product->price, 2) }}
                            </span>
                            <span class="text-xs" style="color: var(--td-muted);">${{ number_format($item->product->price, 2) }} each</span>
                        </div>

                        <!-- Checkbox (Delete) -->
                        <div class="flex items-center justify-center" x-show="isEditMode" style="display: none;">
                            <label class="cursor-pointer flex items-center">
                                <input type="checkbox" wire:model.live="selectedItems" value="{{ $item->id }}" class="hidden">
                                <div class="w-8 h-8 rounded-full border flex items-center justify-center transition-colors"
                                     style="border-color: var(--td-border);"
                                     :style="$wire.selectedItems.includes('{{ $item->id }}') ? 'background: var(--td-danger); border-color: var(--td-danger);' : 'background: transparent;'">
                                    <i class="fa-solid fa-trash-can text-sm" :class="$wire.selectedItems.includes('{{ $item->id }}') ? 'text-white' : 'text-red-400'"></i>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Order Summary -->
            <div class="td-card h-fit sticky top-24 border-2 border-orange-500/10">
                <h2 class="font-bold text-xl mb-5 flex items-center gap-2" style="color: var(--td-text);">
                    <i class="fa-solid fa-receipt text-primary" style="color: var(--td-primary);"></i> Summary
                </h2>
                <div class="space-y-4 mb-6">
                    <div class="flex justify-between text-sm font-medium">
                        <span style="color: var(--td-muted);">Subtotal</span>
                        <span style="color: var(--td-text);">${{ number_format($total, 2) }}</span>
                    </div>
                    <div class="flex justify-between text-sm font-medium">
                        <span style="color: var(--td-muted);">Tax & Fees</span>
                        <span style="color: var(--td-text);">Calculated at checkout</span>
                    </div>
                    <div class="flex justify-between text-sm font-medium">
                        <span style="color: var(--td-muted);">Delivery Fee</span>
                        <span class="text-green-600 font-bold bg-green-100 px-2 py-0.5 rounded-md">Free</span>
                    </div>
                    
                    <div class="border-t-2 border-dashed pt-4" style="border-color: var(--td-border);">
                        <div class="flex justify-between font-black text-2xl">
                            <span style="color: var(--td-text);">Total</span>
                            <span style="color: var(--td-primary);">${{ number_format($total, 2) }}</span>
                        </div>
                    </div>
                </div>
                
                <a href="{{ url('user/checkout') }}" class="td-btn-primary w-full justify-center py-3.5 text-base shadow-lg hover:shadow-xl group">
                    Proceed to Checkout <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform"></i>
                </a>
                
                <div class="mt-4 flex items-center justify-center gap-4 text-2xl text-gray-400">
                    <i class="fa-brands fa-cc-visa hover:text-blue-600 transition-colors"></i>
                    <i class="fa-brands fa-cc-mastercard hover:text-red-500 transition-colors"></i>
                    <i class="fa-brands fa-cc-stripe hover:text-indigo-500 transition-colors"></i>
                    <i class="fa-brands fa-cc-paypal hover:text-blue-500 transition-colors"></i>
                </div>
                <p class="text-[10px] text-center mt-3 uppercase tracking-widest font-bold" style="color: var(--td-muted);">Secure SSL Encrypted</p>
            </div>
        </div>
    @endif

    <!-- ── Alpine Custom Modal ── -->
    <!-- We use `pointer-events-none` on wrapper during transition, but overlay itself catches clicks. However, we DO NOT bind @click.away -->
    <div x-show="showConfirmModal"
         style="display: none;"
         class="fixed inset-0 z-[100] flex items-center justify-center px-4"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
         
        <!-- Backdrop (No click-away dismiss per requirements) -->
        <div class="absolute inset-0 bg-black/60 backdrop-blur-sm"></div>

        <!-- Modal Dialog -->
        <div class="td-card relative z-10 max-w-sm w-full p-6 text-center transform shadow-2xl"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
             
            <div class="w-16 h-16 rounded-full mx-auto flex items-center justify-center mb-4 shadow-inner" style="background: var(--td-warning); color: white;">
                <i class="fa-solid fa-triangle-exclamation text-2xl"></i>
            </div>
            
            <h3 class="text-xl font-bold mb-2" style="color: var(--td-text);">Save Changes?</h3>
            <p class="text-sm mb-6" style="color: var(--td-muted);">
                Are you sure you want to apply these changes? <br>
                <span x-show="$wire.selectedItems.length > 0" class="text-red-500 font-semibold">
                    <span x-text="$wire.selectedItems.length"></span> items will be deleted.
                </span>
            </p>
            
            <div class="flex gap-3 w-full">
                <button @click="showConfirmModal = false" class="flex-1 py-2.5 rounded-xl font-bold bg-gray-100 hover:bg-gray-200 dark:bg-gray-800 dark:hover:bg-gray-700 transition-colors" style="color: var(--td-text);">
                    Cancel
                </button>
                <button @click="$wire.applyChanges(); showConfirmModal = false; isEditMode = false;" class="flex-1 py-2.5 rounded-xl font-bold text-white shadow-md transition-transform hover:scale-105" style="background: var(--td-primary);">
                    Apply
                </button>
            </div>
        </div>
    </div>

</div>
