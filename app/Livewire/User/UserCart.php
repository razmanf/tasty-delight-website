<?php

namespace App\Livewire\User;

use App\Models\Cart;
use App\Models\CartItem;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserCart extends Component
{
    public array $selectedItems = [];
    public array $quantities = [];

    public function mount()
    {
        $this->syncQuantities();
    }

    public function syncQuantities()
    {
        $cart = Cart::where('user_id', Auth::id())->with('items')->first();
        if ($cart) {
            foreach ($cart->items as $item) {
                $this->quantities[$item->id] = $item->quantity;
            }
        }
    }

    public bool $selectAll = false;

    public function updatedSelectAll($value)
    {
        if ($value) {
            $cart = Cart::where('user_id', Auth::id())->with('items')->first();
            if ($cart) {
                $this->selectedItems = $cart->items->pluck('id')->map(fn($id) => (string)$id)->toArray();
            }
        } else {
            $this->selectedItems = [];
        }
    }

    public function updatedSelectedItems()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        if ($cart && $cart->items()->count() > 0) {
            $this->selectAll = count($this->selectedItems) === $cart->items()->count();
        } else {
            $this->selectAll = false;
        }
    }

    public function cancelEdit()
    {
        $this->syncQuantities();
        $this->selectedItems = [];
        $this->selectAll = false;
    }

    public function deleteSelected()
    {
        if (!empty($this->selectedItems)) {
            CartItem::whereIn('id', $this->selectedItems)
                ->whereHas('cart', fn ($q) => $q->where('user_id', Auth::id()))
                ->delete();
            
            foreach ($this->selectedItems as $id) {
                unset($this->quantities[$id]);
            }
            $this->selectedItems = [];
            $this->selectAll = false;
            
            $this->dispatch('cart-updated');
            session()->flash('success', 'Selected items deleted from cart!');
        }
    }

    public function clearCart()
    {
        $cart = Cart::where('user_id', Auth::id())->first();
        if ($cart) {
            $cart->items()->delete();
            $this->quantities = [];
            $this->selectedItems = [];
            $this->selectAll = false;
            
            $this->dispatch('cart-updated');
            session()->flash('success', 'Cart cleared successfully!');
        }
    }

    public function incrementItem($itemId)
    {
        if (isset($this->quantities[$itemId])) {
            $this->quantities[$itemId]++;
        }
    }

    public function decrementItem($itemId)
    {
        if (isset($this->quantities[$itemId]) && $this->quantities[$itemId] > 1) {
            $this->quantities[$itemId]--;
        }
    }

    public function applyChanges()
    {
        // Handle quantity updates for remaining items
        foreach ($this->quantities as $id => $quantity) {
            $item = CartItem::where('id', $id)
                ->whereHas('cart', fn ($q) => $q->where('user_id', Auth::id()))
                ->first();
                
            if ($item && $item->quantity != $quantity) {
                $item->update(['quantity' => $quantity]);
            }
        }

        $this->selectedItems = [];
        $this->selectAll = false;

        $this->dispatch('cart-updated');
        session()->flash('success', 'Cart changes applied successfully!');
    }

    public function render()
    {
        $cart = Cart::where('user_id', Auth::id())
            ->with(['items.product.category'])
            ->first();

        // Calculate total based on local $quantities
        $total = 0;
        $hasStockIssues = false;
        
        if ($cart) {
            foreach ($cart->items as $item) {
                $q = $this->quantities[$item->id] ?? $item->quantity;
                
                // Check stock
                if ($item->product->stock < $q) {
                    $hasStockIssues = true;
                }
                
                if (!in_array($item->id, $this->selectedItems)) {
                    $total += $q * $item->product->price;
                }
            }
        }

        return view('livewire.user.user-cart', compact('cart', 'total', 'hasStockIssues'))
            ->layout('layouts.user');
    }
}
