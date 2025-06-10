@csrf

<div class="mb-4">
    <label class="block mb-1 font-semibold">Name</label>
    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}"
           class="w-full border px-4 py-2 rounded shadow" required>
</div>

<div class="mb-4">
    <label class="block mb-1 font-semibold">Price</label>
    <input type="number" name="price" step="0.01"
           value="{{ old('price', $product->price ?? '') }}"
           class="w-full border px-4 py-2 rounded shadow" required>
</div>

<div class="mb-4">
    <label class="block mb-1 font-semibold">Category</label>
    <select name="category_id" class="w-full border px-4 py-2 rounded shadow" required>
        <option value="">Select category</option>
        @foreach ($categories as $cat)
            <option value="{{ $cat->id }}"
                @selected(old('category_id', $product->category_id ?? '') == $cat->id)>
                {{ $cat->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-4">
    <label class="block mb-1 font-semibold">Image</label>
    <input type="file" name="image" class="w-full border px-4 py-2 rounded shadow">
    @if (!empty($product->image))
        <img src="{{ asset('storage/' . $product->image) }}"
             class="w-20 h-20 object-cover mt-2 border rounded" alt="product image">
    @endif
</div>

<button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 shadow">
    Save Product
</button>
