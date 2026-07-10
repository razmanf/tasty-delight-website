<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    // Get all favourites for the authenticated user
    public function index()
    {
        $favorites = Favorite::with('product')
            ->where('user_id', Auth::id())
            ->get()
            ->map(fn($fav) => $fav->product);

        return response()->json($favorites);
    }

    // Add a product to favourites
    public function store($productId)
    {
        $userId = Auth::id();

        $favorite = Favorite::firstOrCreate([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        return response()->json(['message' => 'Added to favorites', 'favorite' => $favorite]);
    }

    // Remove a product from favourites
    public function destroy($productId)
    {
        $userId = Auth::id();

        Favorite::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();

        return response()->json(['message' => 'Removed from favorites']);
    }
}
