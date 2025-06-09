@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-lg">
    <h1 class="text-2xl font-bold mb-6">Edit Product</h1>

    <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf
        @method('PUT')

        <div>
            <label class="block mb-1 font-semibold">Name</label>
            <input type="text" name="name" value="{{ old('name', $product->name) }}" class="w-full border p-2 rounded" required>
            @error('name') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block mb-1 font-semibold">Description</label>
            <textarea name="description" class="w-full border p-2 rounded">{{ old('description', $product->description) }}</textarea>
            @error('description') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block mb-1 font-semibold">Price</label>
            <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" class="w-full border p-2 rounded" required>
            @error('price') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block mb-1 font-semibold">Category</label>
            <select name="category_id" class="w-full border p-2 rounded">
                <option value="">Select Category</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" @selected(old('category_id', $product->category_id) == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <div>
            <label class="block mb-1 font-semibold">Current Image</label><br>
            @if($product->image)
                <img src="{{ asset('storage/'.$product->image) }}" alt="Product Image" class="w-32 h-auto mb-4">
            @else
                <p>No image uploaded</p>
            @endif
        </div>

        <div>
            <label class="block mb-1 font-semibold">Change Image</label>
            <input type="file" name="image" accept="image/*" class="w-full">
            @error('image') <p class="text-red-600">{{ $message }}</p> @enderror
        </div>

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">Update</button>
        <a href="{{ route('admin.products.index') }}" class="ml-4 text-gray-600 hover:underline">Cancel</a>
    </form>
</div>
@endsection
