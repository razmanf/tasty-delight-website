<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Order;
use App\Models\User;
use App\Models\Product;

class AdminDashboard extends Component
{
    public $traffic;
    public $newUsers;
    public $sales;
    public $performance;

    public $months;
    public $sales2025;
    public $sales2024;
    public $orders2025;
    public $orders2024;

    public $searchQuery = '';
    public $searchResults = [];

    public function mount()
    {
        // Sample metrics—you can replace these queries with your real ones
        $this->traffic     = rand(300000, 400000);
        $this->newUsers    = User::where('created_at', '>=', now()->subWeek())->count();
        $this->sales       = Order::where('created_at', '>=', now()->subDay())->count();
        $this->performance = round(
            $this->sales / max(1, Order::where('created_at','>=',now()->subMonth())->count()) * 100,
            1
        );

        // Static chart data (replace with real aggregates if you like)
        $this->months     = ['Jan','Feb','Mar','Apr','May','Jun','Jul'];
        $this->sales2025  = [65,78,85,72,55,65,88];
        $this->sales2024  = [40,75,88,78,55,60,90];
        $this->orders2025 = [30,80,75,35,45,10,85];
        $this->orders2024 = [25,70,85,75,32, 8,88];
    }

    public function updatedSearchQuery()
    {
        if (strlen($this->searchQuery) < 2) {
            $this->searchResults = [];
            return;
        }

        $this->searchResults = Product::query()
            ->where('name','like','%'.$this->searchQuery.'%')
            ->limit(5)
            ->get(['id','name','price'])
            ->toArray();
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard');
    }
}
