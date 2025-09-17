<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Order;
use App\Models\User;

class OrderTable extends Component
{
    use WithPagination;

    public $searchInput = '';
    public $status, $total_amount, $user_id;
    public $orderId;
    public $showCreateForm = false;
    public $dateFrom, $dateTo;

    protected $paginationTheme = 'tailwind';

    public function render()
    {
        $query = Order::with('user');

        if ($this->searchInput) {
            $query->where(function ($q) {
                $q->where('status', 'like', '%' . $this->searchInput . '%')
                  ->orWhere('id', $this->searchInput);
            });
        }

        if ($this->dateFrom) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if ($this->dateTo) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return view('livewire.admin.order-table', [
            'orders' => $query->paginate(10),
            'users'  => User::all(),
        ]);
    }

    public function applySearch()
    {
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->reset(['searchInput', 'dateFrom', 'dateTo']);
        $this->resetPage();
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showCreateForm = true;
    }

    public function cancelCreateForm()
    {
        $this->showCreateForm = false;
        $this->resetForm();
    }

    public function createOrder()
    {
        $this->validate([
            'status'       => 'required|string',
            'total_amount' => 'required|numeric',
            'user_id'      => 'required|exists:users,id',
        ]);

        Order::create([
            'status'       => $this->status,
            'total_amount' => $this->total_amount,
            'user_id'      => $this->user_id,
        ]);

        $this->resetForm();
        $this->showCreateForm = false;
    }

    public function editOrder($id)
    {
        $order = Order::findOrFail($id);
        $this->orderId      = $order->id;
        $this->status       = $order->status;
        $this->total_amount = $order->total_amount;
        $this->user_id      = $order->user_id;
    }

    public function updateOrder()
    {
        $this->validate([
            'status'       => 'required|string',
            'total_amount' => 'required|numeric',
            'user_id'      => 'required|exists:users,id',
        ]);

        $order = Order::findOrFail($this->orderId);
        $order->update([
            'status'       => $this->status,
            'total_amount' => $this->total_amount,
            'user_id'      => $this->user_id,
        ]);

        $this->resetForm();
        $this->orderId = null;
    }

    public function deleteOrder($id)
    {
        Order::findOrFail($id)->delete();
    }

    private function resetForm()
    {
        $this->reset(['status', 'total_amount', 'user_id']);
    }
}
