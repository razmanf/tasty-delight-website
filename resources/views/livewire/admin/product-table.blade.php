<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"
    x-data
    @scroll-to-edit-form.window="document.getElementById('edit-product-form')?.scrollIntoView({ behavior: 'smooth', block: 'start' })">

    {{-- Create Product Form --}}
    @if($showCreateForm)
    <div class="bg-green-100 p-4 rounded shadow mb-4 mt-4">
        <h2 class="text-lg font-semibold mb-2">Create New Product</h2>

        <div class="mb-2">
            <label class="block mb-1">Name</label>
            <input type="text" wire:model="name" class="w-full border rounded p-2">
        </div>

        <div class="mb-2">
            <label class="block mb-1">Price</label>
            <input type="number" wire:model="price" class="w-full border rounded p-2">
        </div>

        <div class="mb-2">
            <label class="block mb-1">Description</label>
            <textarea wire:model="description" class="w-full border rounded p-2"></textarea>
        </div>

        <div class="mb-2">
            <label class="block mb-1">Category</label>
            <select wire:model="category_id" class="w-full border rounded p-2">
                <option value="">Select Category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-2">
            <label class="block mb-1">Product Image</label>
            <input type="file" wire:model="image" class="form-input w-full">
        </div>

        <div class="flex gap-2">
            <button wire:click="createProduct" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Create</button>
            <button wire:click="cancelCreateForm" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 transition">Cancel</button>
        </div>
    </div>
    @endif

    {{-- Edit Product Form --}}
    @if($productId)
    <div id="edit-product-form" class="bg-yellow-100 p-4 rounded shadow mb-4 mt-4" x-init="$nextTick(() => { $dispatch('scroll-to-edit-form') })">
        <h2 class="text-lg font-semibold mb-2">Edit Product</h2>

        <div class="mb-2">
            <label class="block mb-1">Name</label>
            <input type="text" wire:model="name" class="w-full border rounded p-2">
        </div>

        <div class="mb-2">
            <label class="block mb-1">Price</label>
            <input type="number" wire:model="price" class="w-full border rounded p-2">
        </div>

        <div class="mb-2">
            <label class="block mb-1">Description</label>
            <textarea wire:model="description" class="w-full border rounded p-2"></textarea>
        </div>

        <div class="mb-2">
            <label class="block mb-1">Category</label>
            <select wire:model="category_id" class="w-full border rounded p-2">
                <option value="">Select Category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        @if ($image)
            <div class="mb-4">
                <label class="block mb-1">Product Image</label>
                <img src="{{ Storage::url($image) }}" alt="Product Image" class="w-32 h-32 object-cover">
            </div>
        @endif

        <div class="mb-2">
            <input type="file" wire:model="image" class="form-input w-full">
        </div>

        <div class="flex gap-2">
            <button wire:click="updateProduct" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">Update</button>
            <button wire:click="$set('productId', null)" class="px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 transition">Cancel</button>
        </div>
    </div>
    @endif

    {{-- Filters --}}
    <div class="flex flex-wrap gap-4 items-center mt-6">
        {{-- Category Filter --}}
        <div>
            <select wire:model="category" class="border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                <option value="">All Categories</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Search Box --}}
        <form wire:submit.prevent="applySearch" class="flex gap-2 flex-1">
            <input type="text" wire:model.defer="searchInput" placeholder="Search products..." class="w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
            <button type="submit" class="inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Search</button>
            <button type="button" wire:click="resetFilters" class="inline-block px-3 py-1 bg-gray-400 text-white rounded hover:bg-gray-500">Reset</button>
        </form>

        {{-- Add Product Button --}}
        <div class="ml-auto">
            <button wire:click="openCreateForm" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                Add Product
            </button>
        </div>
    </div>

    {{-- Product Table --}}
    <div class="mt-6 overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @foreach(['Name','Description','Price','Image','Category','Stock'] as $col)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $col }}</th>
                    @endforeach
                    <th class="px-6 py-3"></th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($products as $p)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-3">{{ $p->name }}</td>
                        <td class="px-6 py-3 text-sm text-gray-500">{{ Str::limit($p->description, 50) }}</td>
                        <td class="px-6 py-3">{{ number_format($p->price, 2) }}</td>
                        <td class="px-6 py-3">
                            @if($p->image)
                                <img src="{{ asset('storage/'.$p->image) }}" alt="{{ $p->name }}" class="h-10 w-10 object-cover rounded">
                            @endif
                        </td>
                        <td class="px-6 py-3">{{ $p->category->name ?? '-' }}</td>
                        <td class="px-6 py-3">{{ $p->stock }}</td>
                        <td class="px-6 py-3 text-right space-x-2">
                            <button wire:click="editProduct({{ $p->id }})" class="inline-block px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">Edit</button>
                            <button x-data @click="if (confirm('Are you sure?')) { $wire.deleteProduct({{ $p->id }}) }" class="inline-block px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">Delete</button>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="px-6 py-4 text-center text-gray-500">No products found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="mt-4 mb-10">{{ $products->links() }}</div>
</div>
