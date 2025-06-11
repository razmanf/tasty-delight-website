<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(
            Product::with('category', 'reviews')->paginate(10)
        );
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:category,id',
            'image' => 'sometimes|image|max:2048'
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products');
        }

        $product = Product::create($validated);
        return new ProductResource($product->load('category'));
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load('category', 'reviews.user'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'price' => 'sometimes|numeric|min:0',
            'category_id' => 'sometimes|exists:category,id'
        ]);

        $product->update($validated);
        return new ProductResource($product->fresh()->load('category'));
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return response()->noContent();
    }
}