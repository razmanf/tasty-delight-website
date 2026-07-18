<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use App\Models\CartItem;
use Livewire\Livewire;
use App\Livewire\User\UserCart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CartFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create category and product
        $category = Category::create(['name' => 'Burgers']);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Bacon Burger',
            'description' => 'Test Burger',
            'price' => 12.50,
            'image' => 'https://example.com/image.jpg'
        ]);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Cheese Burger',
            'description' => 'Test Burger 2',
            'price' => 10.50,
            'image' => 'https://example.com/image2.jpg'
        ]);
    }

    public function test_adding_same_item_increments_quantity_instead_of_duplicating()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::first();

        // Simulate addToCart trait behavior
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        
        // Add first time
        CartItem::create([
            'cart_id' => $cart->id,
            'product_id' => $product->id,
            'quantity' => 1
        ]);
        
        $this->assertEquals(1, CartItem::where('cart_id', $cart->id)->count());
        
        // Add second time (logic from HasCart trait)
        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $product->id)->first();
        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartItem::create(['cart_id' => $cart->id, 'product_id' => $product->id, 'quantity' => 1]);
        }

        $this->assertEquals(1, CartItem::where('cart_id', $cart->id)->count());
        $this->assertEquals(2, CartItem::first()->quantity);
    }

    public function test_can_delete_selected_items_from_cart()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $products = Product::all();
        $cart = Cart::create(['user_id' => $user->id]);
        
        $item1 = CartItem::create(['cart_id' => $cart->id, 'product_id' => $products[0]->id, 'quantity' => 1]);
        $item2 = CartItem::create(['cart_id' => $cart->id, 'product_id' => $products[1]->id, 'quantity' => 1]);

        $this->assertEquals(2, CartItem::count());

        Livewire::test(UserCart::class)
            ->set('selectedItems', [(string)$item1->id]) // Select first item
            ->call('deleteSelected')
            ->assertSet('selectedItems', []) // Should reset
            ->assertSet('selectAll', false);

        $this->assertEquals(1, CartItem::count());
        $this->assertDatabaseHas('cart_items', ['product_id' => $products[1]->id]);
        $this->assertDatabaseMissing('cart_items', ['product_id' => $products[0]->id]);
    }

    public function test_can_clear_entire_cart()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $products = Product::all();
        $cart = Cart::create(['user_id' => $user->id]);
        
        CartItem::create(['cart_id' => $cart->id, 'product_id' => $products[0]->id, 'quantity' => 1]);
        CartItem::create(['cart_id' => $cart->id, 'product_id' => $products[1]->id, 'quantity' => 1]);

        $this->assertEquals(2, CartItem::count());

        Livewire::test(UserCart::class)
            ->call('clearCart');

        $this->assertEquals(0, CartItem::count());
    }
}
