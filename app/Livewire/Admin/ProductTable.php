<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Product;
use App\Models\Category;
use Livewire\WithPagination;

class ProductTable extends Component
{
    use WithPagination;

    // Add this property to disable pagination cache issues
    protected $paginationTheme = 'tailwind'; // or bootstrap if you use that

    public $search = '';
    public $category = '';
    public $perPage = 10;

    protected $queryString = ['search', 'category'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategory()
    {
        $this->resetPage();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function deleteProduct($id)
    {
        Product::findOrFail($id)->delete();
        session()->flash('success', 'Product deleted successfully.');
    }

    public function render()
    {
        $categories = Category::all();

        $products = Product::with('category')
            ->when($this->search, fn($q) =>
                $q->where('name', 'like', '%' . $this->search . '%')
            )
            ->when($this->category, fn($q) =>
                $q->where('category_id', $this->category)
            )
            ->latest()->paginate($this->perPage);

        return view('livewire.admin.product-table', [
            'products' => $products,
            'categories' => $categories
        ]);
    }
}

