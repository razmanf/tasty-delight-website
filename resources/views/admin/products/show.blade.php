<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Product Details - {{ $product->name }}
        </h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">

            <div class="mb-6">
                <a href="{{ route('admin.products.index') }}" class="text-blue-600 hover:underline">&larr; Back to Products</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Product Image --}}
                <div>
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full rounded">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center text-gray-500 rounded">
                            No Image Available
                        </div>
                    @endif
                </div>

                {{-- Product Details --}}
                <div>
                    <h2 class="text-xl font-semibold mb-2">{{ $product->name }}</h2>
                    <p class="text-gray-700 mb-2">{{ $product->description ?? 'No description available.' }}</p>

                    <p class="mb-2">
                        <span class="font-semibold">Price:</span> Rs. {{ number_format($product->price, 2) }}
                    </p>

                    <p class="mb-2">
                        <span class="font-semibold">Category:</span> {{ $product->category ? $product->category->name : 'Uncategorized' }}
                    </p>

                    <p class="mb-2">
                        <span class="font-semibold">Stock Quantity:</span> {{ $product->stock ?? 'N/A' }}
                    </p>

                    <p class="mb-2">
                        <span class="font-semibold">Created at:</span> {{ $product->created_at->format('d M Y') }}
                    </p>

                    <p class="mb-2">
                        <span class="font-semibold">Updated at:</span> {{ $product->updated_at->format('d M Y') }}
                    </p>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.products.edit', $product->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-white hover:bg-blue-700">
                    Edit Product
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
