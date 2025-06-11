@extends('layouts.admin')

@section('title', 'Add New Product')


@section('content')
    <h1 class="text-2xl font-bold mb-4">Add New Product</h1>

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium">Product Name</label>
            <input type="text" name="name" class="border rounded w-full p-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Description</label>
            <textarea name="description" class="border rounded w-full p-2" required></textarea>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Price</label>
            <input type="number" step="0.01" name="price" class="border rounded w-full p-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Stock</label>
            <input type="number" name="stock" class="border rounded w-full p-2" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Category</label>
            <select name="category_id" class="border rounded w-full p-2" required>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium">Image</label>
            <input type="file" name="image" class="border rounded w-full p-2" required>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Create Product</button>
    </form>
@endsection
