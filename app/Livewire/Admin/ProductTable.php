<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;
use Livewire\WithFileUploads;

class ProductTable extends Component
{
    use WithPagination, WithFileUploads;

    public $productId;
    public $name;
    public $price;
    public $description;
    public $category_id;
    public $image;

    public $category = null;
    public string $search = '';
    public $searchInput = '';
    public $showCreateForm = false;  // control create form visibility

    protected $updatesQueryString = ['search', 'category'];
    protected $paginationTheme = 'tailwind';

    public function rules()
    {
        return [
            'name' => 'required|string|max:255|unique:products,name,' . $this->productId,
            'price' => 'required|numeric',
            'description' => 'nullable|string',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function updatedSearch() 
    { 
        $this->resetPage(); 
    }

    public function updatedCategory()
    {
        $this->resetPage();
    }

    public function applySearch()
    {
        $this->search = $this->searchInput;
        $this->resetPage();
    }

    public function resetFilters()
    {
        $this->search = '';
        $this->searchInput = '';
        $this->category = '';
        $this->resetPage();
    }

    public function openCreateForm()
    {
        $this->resetForm();
        $this->showCreateForm = true;
    }

    public function cancelCreateForm()
    {
        $this->resetForm();
        $this->showCreateForm = false;
    }

    public function createProduct()
    {
        $this->validate();

        $product = new Product();
        $product->name = $this->name;
        $product->price = $this->price;
        $product->description = $this->description;
        $product->category_id = $this->category_id;

        if ($this->image instanceof \Illuminate\Http\UploadedFile) {
            $path = $this->image->store('product-images', 'public');
            $product->image = $path;
        }

        $product->save();

        session()->flash('success', 'Product created successfully!');

        $this->resetForm();
        $this->showCreateForm = false;
    }

    public function editProduct($id)
    {
        $product = Product::findOrFail((int) $id);

        $this->productId = $product->id;
        $this->name = $product->name;
        $this->price = $product->price;
        $this->description = $product->description;
        $this->category_id = $product->category_id;
        $this->image = $product->image;

        $this->dispatch('scroll-to-edit');
    }

    #[On('scroll-to-edit-form')]
    public function handleScroll() {}

    public function updateProduct()
    {
        $this->validate();

        $product = Product::findOrFail($this->productId);
        $product->name = $this->name;
        $product->price = $this->price;
        $product->description = $this->description;
        $product->category_id = $this->category_id;

        if ($this->image instanceof \Illuminate\Http\UploadedFile) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $path = $this->image->store('product-images', 'public');
            $product->image = $path;
        }

        $product->save();

        session()->flash('success', 'Product updated successfully!');

        $this->resetForm();
    }

    public function deleteProduct($id)
    {
        $p = Product::findOrFail($id);

        if ($p->image) {
            Storage::disk('public')->delete($p->image);
        }

        $p->delete();

        session()->flash('success', 'Product deleted successfully.');
    }

    public function resetForm()
    {
        $this->reset(['productId', 'name', 'price', 'description', 'category_id', 'image']);
    }

    public function render()
    {
        $query = Product::with('category');

        if ($this->category !== null && $this->category !== '') {
            $query->where('category_id', $this->category);
        }

        if ($this->search) {
            $term = '%' . $this->search . '%';
            $query->where(function ($q) use ($term) {
                $q->where('name', 'like', $term)
                  ->orWhere('description', 'like', $term)
                  ->orWhere('price', 'like', $term)
                  ->orWhere('stock', 'like', $term);
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(10);
        $categories = Category::orderBy('name')->get();

        return view('livewire.admin.product-table', compact('products', 'categories'))->layout('layouts.app');
    }
}
