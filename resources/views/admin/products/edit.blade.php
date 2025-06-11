@extends('layouts.admin')

@section('title', 'Edit Product')


@section('content')
    <h1 class="text-2xl font-bold mb-4">Edit Product</h1>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium">Product Name</label>
            <input type="text" name="name" value="{{ $product->name }}" class="border rounded w-full p-2">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" class="border rounded w-full p-2">{{ $product->description }}</textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Price</label>
            <input type="number" step="0.01" name="price" value="{{ $product->price }}" class="border rounded w-full p-2">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Stock</label>
            <input type="number" name="stock" value="{{ $product->stock }}" class="border rounded w-full p-2">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Category</label>
            <select name="category_id" class="border rounded w-full p-2">
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Image</label>
            <input type="file" name="image" class="border rounded w-full p-2">
            @if ($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="Product Image" class="w-32 mt-2">
            @endif
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update Product</button>
    </form>
@endsection
