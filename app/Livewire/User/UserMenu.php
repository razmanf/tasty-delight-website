<?php

namespace App\Livewire\User;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use App\Livewire\User\HasCart;
use Illuminate\Support\Facades\Auth;

class UserMenu extends Component
{
    use HasCart;

    public string $search = '';
    public int $selectedCategoryId = 0; // 0 means all

    protected $queryString = ['search', 'selectedCategoryId'];

    public function selectCategory($id)
    {
        $this->selectedCategoryId = $id;
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

    public function render()
    {
        $categories = Category::has('products')->get();
        
        $products = Product::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('description', 'like', '%' . $this->search . '%')
                      ->orWhereHas('category', fn ($c) => $c->where('name', 'like', '%' . $this->search . '%')
                                                            ->orWhere('description', 'like', '%' . $this->search . '%'));
                });
            })
            ->when($this->selectedCategoryId > 0, function ($query) {
                $query->where('category_id', $this->selectedCategoryId);
            })
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->get();

        $favorites = Auth::user()->favorites->pluck('product_id')->toArray();

        return view('livewire.user.user-menu', compact('categories', 'products', 'favorites'))
            ->layout('layouts.user');
    }
}
