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
            $results = Product::with('category')
                ->where(function ($q) {
                    $q->where('name', 'like', "%{$this->query}%")
                      ->orWhere('description', 'like', "%{$this->query}%")
                      ->orWhereHas('category', fn ($c) => $c->where('name', 'like', "%{$this->query}%"));
                })
                ->withAvg('reviews', 'rating')
                ->orderByDesc('reviews_avg_rating')
                ->get();
        }

        return view('livewire.user.user-search', compact('results'))
            ->layout('layouts.user');
    }
}
