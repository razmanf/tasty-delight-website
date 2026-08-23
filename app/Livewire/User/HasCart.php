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

        $product = Product::find($productId);
        if (!$product || $product->stock <= 0) {
            session()->flash('error', 'This item is currently out of stock.');
            return;
        }

        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        
        $cartItem = CartItem::where('cart_id', $cart->id)
                            ->where('product_id', $productId)
                            ->first();

        if ($cartItem) {
            if ($cartItem->quantity + 1 > $product->stock) {
                session()->flash('error', 'You cannot add more of this item to the cart (stock limit reached).');
                return;
            }
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $productId,
                'quantity' => 1
            ]);
        }

        $this->dispatch('cart-updated');
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
