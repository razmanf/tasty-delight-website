<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Livewire\Livewire;
use App\Livewire\Admin\ProductTable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminProductFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $category;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->admin = User::factory()->create(['role' => 'admin']);
        $this->category = Category::create(['name' => 'Burgers']);
        
        Product::create([
            'category_id' => $this->category->id,
            'name' => 'Original Bacon Burger',
            'description' => 'Test',
            'price' => 10.00,
            'image' => 'test.jpg'
        ]);
    }

    public function test_admin_cannot_create_product_with_duplicate_name()
    {
        $this->actingAs($this->admin);

        Livewire::test(ProductTable::class)
            ->set('name', 'Original Bacon Burger') // Exact duplicate
            ->set('price', 12.00)
            ->set('category_id', $this->category->id)
            ->call('createProduct')
            ->assertHasErrors(['name' => 'unique']); // Should fail validation

        $this->assertCount(1, Product::all()); // Still only 1 product
    }

    public function test_admin_can_update_product_without_triggering_unique_validation_on_itself()
    {
        $this->actingAs($this->admin);
        
        $product = Product::first();

        Livewire::test(ProductTable::class)
            ->call('editProduct', $product->id)
            ->set('price', 15.00) // Change price but keep name the same
            ->call('updateProduct')
            ->assertHasNoErrors();

        $this->assertEquals(15.00, $product->fresh()->price);
        $this->assertEquals('Original Bacon Burger', $product->fresh()->name);
    }

    public function test_admin_cannot_update_product_to_existing_name_of_another_product()
    {
        $this->actingAs($this->admin);

        // Create a second product
        Product::create([
            'category_id' => $this->category->id,
            'name' => 'Cheese Burger',
            'description' => 'Test',
            'price' => 8.00,
            'image' => 'test2.jpg'
        ]);

        $secondProduct = Product::where('name', 'Cheese Burger')->first();

        Livewire::test(ProductTable::class)
            ->call('editProduct', $secondProduct->id)
            ->set('name', 'Original Bacon Burger') // Try to steal the name of the first product
            ->call('updateProduct')
            ->assertHasErrors(['name' => 'unique']);

        // Assert the name did not change
        $this->assertEquals('Cheese Burger', $secondProduct->fresh()->name);
    }
}
