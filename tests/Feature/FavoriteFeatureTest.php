<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Product;
use App\Models\Category;
use Livewire\Livewire;
use App\Livewire\User\UserMenu;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FavoriteFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        $category = Category::create(['name' => 'Burgers']);
        Product::create([
            'category_id' => $category->id,
            'name' => 'Bacon Burger',
            'description' => 'Test Burger',
            'price' => 12.50,
            'image' => 'https://example.com/image.jpg'
        ]);
    }

    public function test_favorite_is_saved_in_database()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::first();

        // Ensure no favorites exist initially
        $this->assertCount(0, $user->favorites);

        // Toggle favorite on
        Livewire::test(UserMenu::class)
            ->call('toggleFavorite', $product->id)
            ->assertDispatched('favorite-toggled');

        // It should be permanently saved in the database
        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }

    public function test_favoriting_twice_toggles_it_off()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $product = Product::first();

        // Toggle favorite on
        Livewire::test(UserMenu::class)
            ->call('toggleFavorite', $product->id);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);

        // Toggle favorite off
        Livewire::test(UserMenu::class)
            ->call('toggleFavorite', $product->id);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $user->id,
            'product_id' => $product->id,
        ]);
    }
}
