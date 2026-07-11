<?php

namespace App\Livewire\User;

use App\Models\Product;
use App\Models\Review;
use Livewire\Component;

class GlobalSearch extends Component
{
    public string $query = '';

    public function render()
    {
        $products = collect();
        $reviews = collect();

        if (strlen($this->query) >= 2) {
            $products = Product::where('name', 'like', '%' . $this->query . '%')
                ->orWhere('description', 'like', '%' . $this->query . '%')
                ->take(5)
                ->get();

            $reviews = Review::where('comment', 'like', '%' . $this->query . '%')
                ->with('product')
                ->take(3)
                ->get();
        }

        return view('livewire.user.global-search', compact('products', 'reviews'));
    }
}
