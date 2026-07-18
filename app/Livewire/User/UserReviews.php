<?php

namespace App\Livewire\User;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class UserReviews extends Component
{
    use WithPagination;

    public string $search = '';

    public function updatingSearch(): void { $this->resetPage(); }

    public function deleteReview(int $reviewId): void
    {
        Review::where('id', $reviewId)
            ->where('user_id', Auth::id())
            ->delete();

        session()->flash('success', 'Review deleted successfully.');
    }

    public function render()
    {
        $reviews = Review::where('user_id', Auth::id())
            ->with(['product', 'order.products'])
            ->when($this->search, function ($q) {
                $q->where(function ($query) {
                    $query->whereHas('product', fn ($p) => $p->where('name', 'like', "%{$this->search}%"))
                          ->orWhereHas('order.products', fn ($p) => $p->where('name', 'like', "%{$this->search}%"));
                });
            })
            ->latest()
            ->paginate(10);

        return view('livewire.user.user-reviews', compact('reviews'))
            ->layout('layouts.user');
    }
}
