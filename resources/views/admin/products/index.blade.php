
@extends('layouts.admin')

@section('title', 'Product List')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Products</h1>

    <a href="{{ route('admin.products.create') }}" class="bg-green-500 text-white px-4 py-2 rounded mb-4 inline-block">Add New Product</a>

    <table class="w-full table-auto border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="border p-2">#</th>
                <th class="border p-2">Name</th>
                <th class="border p-2">Price</th>
                <th class="border p-2">Stock</th>
                <th class="border p-2">Category</th>
                <th class="border p-2">Image</th>
                <th class="border p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td class="border p-2">{{ $product->id }}</td>
                <td class="border p-2">{{ $product->name }}</td>
                <td class="border p-2">${{ $product->price }}</td>
                <td class="border p-2">{{ $product->stock }}</td>
                <td class="border p-2">{{ $product->category->name }}</td>
                <td class="border p-2">
                    <img src="{{ asset('storage/' . $product->image) }}" class="w-16 h-16 object-cover">
                </td>
                <td class="border p-2">
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="text-blue-500">Edit</a> |
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td class="border p-2 text-center" colspan="7">No products found.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

     {{-- Pagination links if applicable --}}
     <div class="mt-4">
        {{ $products->links() }}
    </div>
@endsection
