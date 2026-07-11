<?php

namespace App\Livewire;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Livewire\User\HasCart;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserDashboard extends Component
{
    use HasCart;
    public function render()
    {
        $user = Auth::user();

        $recentOrders = Order::where('user_id', $user->id)
            ->with('products')
            ->latest()
            ->take(3)
            ->get();

        $totalOrders = Order::where('user_id', $user->id)->count();
        $totalSpent  = Order::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'delivered'])
            ->sum('total_amount');
        $totalReviews    = Review::where('user_id', $user->id)->count();
        $totalFavorites  = $user->favorites()->count() ?? 0;

        // Recommended: top-rated products
        $recommendedProducts = Product::withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->take(4)
            ->get();

        // Trending: recently ordered or random popular items
        $trendingProducts = Product::withAvg('reviews', 'rating')->withCount('reviews')->inRandomOrder()->take(4)->get(); // Simulating trending
        
        // Special Offers: products with a simulated "discount" (random ones for UI purpose)
        $specialOffers = Product::withAvg('reviews', 'rating')->withCount('reviews')->inRandomOrder()->take(2)->get(); // Simulating offers

        $hour = now()->hour;
        $greeting = match(true) {
            $hour < 12 => 'Good morning',
            $hour < 17 => 'Good afternoon',
            default    => 'Good evening',
        };

        $favorites = Auth::user()->favorites->pluck('product_id')->toArray();

        return view('livewire.user-dashboard', compact(
            'user', 'recentOrders', 'totalOrders', 'totalSpent',
            'totalReviews', 'totalFavorites', 'recommendedProducts', 'trendingProducts', 'specialOffers', 'favorites', 'greeting'
        ))->layout('layouts.user');
    }

    public function toggleFavorite($productId)
    {
        $user = Auth::user();
        if ($user->favorites()->where('product_id', $productId)->exists()) {
            $user->favorites()->where('product_id', $productId)->delete();
        } else {
            $user->favorites()->create(['product_id' => $productId]);
        }
        $this->dispatch('favorite-toggled');
    }
}
