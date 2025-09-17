<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavouritesController extends Controller
{
    // Get all favourites for the authenticated user
    public function index()
    {
        $favourites = Favourite::with('product')
            ->where('user_id', Auth::id())
            ->get()
            ->map(fn($fav) => $fav->product);

        return response()->json($favourites);
    }

    // Add a product to favourites
    public function store($productId)
    {
        $userId = Auth::id();

        $favourite = Favourite::firstOrCreate([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        return response()->json(['message' => 'Added to favourites', 'favourite' => $favourite]);
    }

    // Remove a product from favourites
    public function destroy($productId)
    {
        $userId = Auth::id();

        Favourite::where('user_id', $userId)
            ->where('product_id', $productId)
            ->delete();

        return response()->json(['message' => 'Removed from favourites']);
    }
}
