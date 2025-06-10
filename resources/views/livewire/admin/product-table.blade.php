<div class="p-6">
    @if (session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded shadow">
            {{ session('success') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-2">
        <input type="text" wire:model.debounce.300ms="search"
               placeholder="Search products..."
               class="border px-4 py-2 rounded shadow w-full md:w-1/3" />

        <select wire:model="category" class="border px-4 py-2 rounded shadow w-full md:w-1/4">
            <option value="">All Categories</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
            @endforeach
        </select>
    </div>

    <div wire:loading.class="opacity-50" wire:key="{{ $category }}-{{ $search }}">
        <table class="min-w-full text-sm border shadow-md rounded overflow-hidden">
            <thead class="bg-gray-100 text-gray-700">
                <tr>
                    <th class="p-3 text-left">#</th>
                    <th class="p-3 text-left">Image</th>
                    <th class="p-3 text-left">Name</th>
                    <th class="p-3 text-left">Price</th>
                    <th class="p-3 text-left">Category</th>
                    <th class="p-3 text-left">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse ($products as $product)
                <tr class="hover:bg-gray-50">
                    <td class="p-3">{{ $product->id }}</td>
                    <td class="p-3">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 class="w-12 h-12 object-cover rounded border" alt="thumb">
                        @else
                            <span class="text-gray-400">No Image</span>
                        @endif
                    </td>
                    <td class="p-3 font-medium">{{ $product->name }}</td>
                    <td class="p-3">${{ number_format($product->price, 2) }}</td>
                    <td class="p-3">{{ $product->category->name ?? 'N/A' }}</td>
                    <td class="p-3 flex gap-2">
                        <a href="{{ route('admin.products.edit', $product->id) }}"
                           class="text-blue-600 hover:underline">Edit</a>

                        <button wire:click="deleteProduct({{ $product->id }})"
                                onclick="confirm('Delete product?') || event.stopImmediatePropagation()"
                                class="text-red-600 hover:underline">Delete</button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-3 text-center text-gray-500">No products found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>

    <div wire:loading>
        <div class="text-center mt-4">
            <svg class="animate-spin h-6 w-6 mx-auto text-blue-500"
                 xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                        stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8v8H4z"></path>
            </svg>
        </div>
    </div>
</div>
