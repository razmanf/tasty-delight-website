<?php

namespace App\Livewire\User;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

trait HasCart
{
    public function addToCart(int $productId)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        
        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $productId)
                            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        $this->dispatch('cart-updated');
        session()->flash('success', 'Added to cart!');
    }

    public function getCartItemQuantity(int $productId)
    {
        if (!Auth::check()) return 0;
        
        $cart = Cart::where('user_id', Auth::id())->first();
        if (!$cart) return 0;
        
        $item = CartItem::where('cart_id', $cart->id)->where('product_id', $productId)->first();
        return $item ? $item->quantity : 0;
    }
}
