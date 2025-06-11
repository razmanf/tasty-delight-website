<div>
    <div class="flex flex-col sm:flex-row justify-between items-center mb-4 space-y-3 sm:space-y-0">
        <div class="flex space-x-2 items-center">
            <input
                type="text"
                wire:model.debounce.300ms="search"
                placeholder="Search products..."
                class="border rounded px-3 py-2 w-60"
            >

            <select wire:model="categoryFilter" class="border rounded px-15 py-2">
                <option value="">{{ __('All Categories') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>

            @if($selected)
                <button
                    wire:click="deleteSelected"
                    onclick="confirm('Are you sure? This will delete selected products.') || event.stopImmediatePropagation()"
                    class="bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2 rounded"
                >
                    Delete Selected ({{ count($selected) }})
                </button>
            @endif
        </div>

        <div class="flex items-center space-x-2">
            <button wire:click="sortBy('name')" class="text-sm font-semibold hover:underline">
                Name
                @if($sortField === 'name')
                    @if($sortDirection === 'asc')
                        &uarr;
                    @else
                        &darr;
                    @endif
                @endif
            </button>
            <button wire:click="sortBy('price')" class="text-sm font-semibold hover:underline">
                Price
                @if($sortField === 'price')
                    @if($sortDirection === 'asc')
                        &uarr;
                    @else
                        &darr;
                    @endif
                @endif
            </button>
            <button wire:click="sortBy('stock')" class="text-sm font-semibold hover:underline">
                Stock
                @if($sortField === 'stock')
                    @if($sortDirection === 'asc')
                        &uarr;
                    @else
                        &darr;
                    @endif
                @endif
            </button>
            <button wire:click="sortBy('created_at')" class="text-sm font-semibold hover:underline">
                Created At
                @if($sortField === 'created_at')
                    @if($sortDirection === 'asc')
                        &uarr;
                    @else
                        &darr;
                    @endif
                @endif
            </button>
        </div>
    </div>

    <div wire:loading.delay class="mb-2 text-center text-gray-500">
        Loading...
    </div>

    <table class="min-w-full bg-white shadow rounded">
        <thead>
            <tr>
                <th class="px-4 py-2 border">
                    <input type="checkbox" wire:model="selectPage">
                </th>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('name')">Name</th>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('price')">Price</th>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('stock')">Stock</th>
                <th class="px-4 py-2 border">Category</th>
                <th class="px-4 py-2 border">Description</th>
                <th class="px-4 py-2 border cursor-pointer" wire:click="sortBy('created_at')">Created At</th>
                <th class="px-4 py-2 border text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($products as $product)
                <tr class="hover:bg-gray-100">
                    <td class="border px-4 py-2 text-center">
                        <input type="checkbox" wire:model="selected" value="{{ $product->id }}">
                    </td>
                    <td class="border px-4 py-2">{{ $product->name }}</td>
                    <td class="border px-4 py-2">Rs. {{ number_format($product->price, 2) }}</td>
                    <td class="border px-4 py-2">{{ $product->stock }}</td>
                    <td class="border px-4 py-2">{{ $product->category->name ?? '-' }}</td>
                    <td class="border px-4 py-2 truncate max-w-xs" title="{{ $product->description }}">
                        {{ Str::limit($product->description, 50) ?? '-' }}
                    </td>
                    <td class="border px-4 py-2">{{ $product->created_at->format('d M Y') }}</td>
                    <td class="border px-4 py-2 text-center">
                        <a href="{{ route('admin.products.edit', $product) }}" 
                           class="text-blue-600 hover:underline mr-2" title="Edit">
                            <i class="fa fa-edit"></i>
                        </a>
                        <button
                            wire:click="deleteSelected"
                            onclick="confirm('Are you sure you want to delete this product?') || event.stopImmediatePropagation()"
                            wire:click="$emit('deleteSingle', {{ $product->id }})"
                            class="text-red-600 hover:underline"
                            title="Delete"
                        >
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center p-4 text-gray-500">No products found.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>

<script>
    Livewire.on('deleteSingle', productId => {
        if (confirm('Are you sure you want to delete this product?')) {
            Livewire.emit('deleteSingleConfirmed', productId);
        }
    });
</script>
