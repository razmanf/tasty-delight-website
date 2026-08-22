<?php

namespace App\Livewire\User;

use App\Models\Product;
use Livewire\Component;
use App\Livewire\User\HasCart;

class UserSearch extends Component
{
    use HasCart;

    public string $query = '';

    public function mount(): void
    {
        $this->query = request('q', '');
    }

    public function render()
    {
        $results = collect();

        if (strlen($this->query) >= 2) {
            $queryParam = '%' . $this->query . '%';

            $results = Product::select('products.*')
                ->selectRaw("
                    CASE 
                        WHEN products.name LIKE ? THEN 1 
                        WHEN EXISTS (SELECT 1 FROM categories WHERE categories.id = products.category_id AND categories.name LIKE ?) THEN 2 
                        ELSE 3 
                    END as match_tier
                ", [$queryParam, $queryParam])
                ->where(function ($q) use ($queryParam) {
                    $q->where('products.name', 'like', $queryParam)
                      ->orWhere('products.description', 'like', $queryParam)
                      ->orWhereHas('category', fn ($c) => $c->where('categories.name', 'like', $queryParam)
                                                            ->orWhere('categories.description', 'like', $queryParam));
                })
                ->with('category')
                ->withAvg('reviews', 'rating')
                ->orderBy('match_tier', 'asc')
                ->orderByDesc('reviews_avg_rating')
                ->get();
        }

        return view('livewire.user.user-search', compact('results'))
            ->layout('layouts.user');
    }
}
