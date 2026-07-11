<?php

namespace App\Livewire\User;

use App\Models\Favorite;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Livewire\User\HasCart;

class UserFavorites extends Component
{
    use HasCart;
    public function removeFavorite(int $productId): void
    {
        Favorite::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->delete();

        $this->dispatch('notify', message: 'Removed from favorites.');
    }

    public function render()
    {
        $favorites = Auth::user()->favorites()->with('product.category')->latest()->get();

        return view('livewire.user.user-favorites', compact('favorites'))
            ->layout('layouts.user');
    }
}
