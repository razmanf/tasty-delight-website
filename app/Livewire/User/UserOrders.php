<?php

namespace App\Livewire\User;

use App\Models\Order;
use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class UserOrders extends Component
{
    use WithPagination;
    use WithFileUploads;

    public string $search = '';
    public string $statusFilter = '';

    // Review Modal State
    public $reviewingOrderId = null;
    public $reviewingOrderType = null;
    public $rating = 5;
    public $comment = '';
    public $riderRating = 5;
    public $riderComment = '';
    public $media = [];
    public $newMedia = [];

    public function updatingSearch(): void { $this->resetPage(); }
    public function updatingStatusFilter(): void { $this->resetPage(); }

    public function openReviewModal($orderId, $orderType)
    {
        $this->reviewingOrderId = $orderId;
        $this->reviewingOrderType = $orderType;
        $this->rating = 5;
        $this->comment = '';
        $this->riderRating = 5;
        $this->riderComment = '';
        $this->media = [];
    }

    public function closeReviewModal()
    {
        $this->reviewingOrderId = null;
        $this->reviewingOrderType = null;
        $this->media = [];
        $this->newMedia = [];
    }

    public function updatedNewMedia()
    {
        $this->validate([
            'newMedia.*' => 'file|mimes:jpg,jpeg,png,webp,mp4,mov,avi|max:20480',
        ]);

        foreach ($this->newMedia as $file) {
            if (count($this->media) < 5) {
                $this->media[] = $file;
            }
        }
        $this->newMedia = [];
    }

    public function removeMedia($index)
    {
        if (isset($this->media[$index])) {
            unset($this->media[$index]);
            $this->media = array_values($this->media);
        }
    }

    public function submitReview()
    {
        $this->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
            'riderRating' => 'nullable|integer|min:1|max:5',
            'riderComment' => 'nullable|string|max:1000',
            'media.*' => 'nullable|file|mimes:jpg,jpeg,png,webp,mp4,mov,avi|max:20480', // 20MB limit for videos
            'media' => 'max:5', // Max 5 files
        ]);

        $order = Order::where('id', $this->reviewingOrderId)->where('user_id', Auth::id())->firstOrFail();

        $mediaPaths = [];
        if ($this->media) {
            foreach ($this->media as $file) {
                $mediaPaths[] = $file->store('reviews', 'public');
            }
        }

        Review::create([
            'user_id' => Auth::id(),
            'order_id' => $order->id,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'rider_rating' => $this->reviewingOrderType === 'delivery' ? $this->riderRating : null,
            'rider_comment' => $this->reviewingOrderType === 'delivery' ? $this->riderComment : null,
            'media' => $mediaPaths,
        ]);

        $this->closeReviewModal();
        session()->flash('success', 'Your review has been submitted. Thank you for your feedback!');
    }

    public function render()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['products', 'review'])
            ->when($this->search, fn ($q) => $q->where('id', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->latest()
            ->paginate(10);

        return view('livewire.user.user-orders', compact('orders'))
            ->layout('layouts.user');
    }
}
