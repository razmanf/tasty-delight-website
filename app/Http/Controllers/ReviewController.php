<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Http\Resources\ReviewResource;
use Illuminate\Http\Request;
use App\Models\Product;


class ReviewController extends Controller
{
    public function index(Product $product)
    {
        return ReviewResource::collection(
            $product->reviews()->with('user')->paginate(5)
        );
    }

    public function store(Request $request, Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|between:1,5',
            'comment' => 'required|string|max:500'
        ]);

        $review = $product->reviews()->create([
            'user_id' => auth()->id(),
            ...$validated
        ]);

        return new ReviewResource($review->load('user'));
    }

    public function destroy(Review $review)
    {
        $review->delete();
        return response()->noContent();
    }
}