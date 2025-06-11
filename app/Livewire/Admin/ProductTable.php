<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;

class ProductTable extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $sortField = 'name';
    public $sortDirection = 'asc';
    public $selected = [];
    public $selectPage = false;
    public $selectAll = false;

    protected $paginationTheme = 'tailwind';

    protected $listeners = ['refreshProducts' => '$refresh', 'deleteSingleConfirmed'];

    public function updatedSelectPage($value)
    {
        if ($value) {
            $this->selected = $this->products->pluck('id')->map(fn($id) => (string) $id)->toArray();
        } else {
            $this->selected = [];
            $this->selectAll = false;
        }
    }

    public function updatedSelected()
    {
        $this->selectPage = false;
        $this->selectAll = false;
    }

    public function deleteSingleConfirmed($productId)
    {
        Product::find($productId)?->delete();
        session()->flash('success', 'Product deleted.');
        $this->resetPage();
    }

    public function selectAll()
    {
        $this->selectAll = true;
        $this->selected = Product::query()
            ->when($this->search, fn($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->categoryFilter, fn($q) => $q->where('category_id', $this->categoryFilter))
            ->pluck('id')->map(fn($id) => (string) $id)->toArray();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategoryFilter()
    {
        $this->resetPage();
    }

    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
        $this->resetPage();
    }

    public function deleteSelected()
    {
        Product::whereIn('id', $this->selected)->delete();
        $this->selected = [];
        $this->selectPage = false;
        $this->selectAll = false;
        session()->flash('success', count($this->selected) . ' products deleted.');
        $this->resetPage();
    }

    public function getProductsProperty()
    {
        return Product::with('category')
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('description', 'like', "%{$this->search}%")
            )
            ->when($this->categoryFilter, fn($q) => $q->where('category_id', $this->categoryFilter))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    public function render()
    {
        return view('livewire.admin.product-table', [
            'products' => $this->products,
            'categories' => Category::all(),
        ]);
    }
}
