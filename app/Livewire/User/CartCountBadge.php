<?php

namespace App\Livewire\User;

use Livewire\Component;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\On;

class CartCountBadge extends Component
{
    #[On('cart-updated')]
    public function render()
    {
        $count = 0;
        if (Auth::check()) {
            $cart = Cart::where('user_id', Auth::id())->first();
            if ($cart) {
                $count = $cart->items()->sum('quantity');
            }
        }
        return view('livewire.user.cart-count-badge', compact('count'));
    }
}
