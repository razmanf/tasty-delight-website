<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Delete existing products
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        Product::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        
        // Clean up old product images to prevent duplicates
        \Illuminate\Support\Facades\Storage::disk('public')->deleteDirectory('product-images');

        $categories = Category::all()->keyBy('name');

        $products = [
            // Burgers
            ['name' => 'Bacon Burger', 'price' => 12.50, 'cat' => 'Burgers', 'img' => 'https://images.unsplash.com/photo-1553979459-d2229ba7433b?w=600&q=80', 'desc' => 'Juicy beef patty topped with crispy smoked bacon, melted cheddar, and our signature sauce.'],
            ['name' => 'Cheese Burger', 'price' => 10.50, 'cat' => 'Burgers', 'img' => 'https://images.unsplash.com/photo-1568901346375-23c9450c58cd?w=600&q=80', 'desc' => 'Classic 100% beef patty with melted American cheese, fresh lettuce, and tomato.'],
            ['name' => 'Chicken Burger', 'price' => 11.00, 'cat' => 'Burgers', 'img' => 'https://images.unsplash.com/photo-1606755962773-d324e0a13086?w=600&q=80', 'desc' => 'Crispy fried chicken breast with mayo, pickles, and crisp lettuce on a toasted bun.'],
            ['name' => 'Veggie Burger', 'price' => 9.50, 'cat' => 'Burgers', 'img' => 'https://images.unsplash.com/photo-1550547660-d9450f859349?w=600&q=80', 'desc' => 'Plant-based patty packed with flavor, topped with fresh avocado and greens.'],
            
            // Pizzas
            ['name' => 'Margherita Pizza', 'price' => 14.00, 'cat' => 'Pizzas', 'img' => 'https://images.unsplash.com/photo-1574071318508-1cdbab80d002?w=600&q=80', 'desc' => 'Classic stone-baked pizza with fresh mozzarella, tomatoes, and sweet basil.'],
            ['name' => 'Pepperoni Pizza', 'price' => 16.00, 'cat' => 'Pizzas', 'img' => 'https://images.unsplash.com/photo-1628840042765-356cda07504e?w=600&q=80', 'desc' => 'Loaded with premium pepperoni slices on our signature homemade tomato sauce.'],
            ['name' => 'Hawaiian Pizza', 'price' => 15.50, 'cat' => 'Pizzas', 'img' => 'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&q=80', 'desc' => 'The perfect balance of sweet pineapple and savory ham on a thin crust.'],
            ['name' => 'Vegetarian Pizza', 'price' => 14.50, 'cat' => 'Pizzas', 'img' => 'https://images.unsplash.com/photo-1604382354936-07c5d9983bd3?w=600&q=80', 'desc' => 'Topped with fresh bell peppers, onions, mushrooms, and black olives.'],
            
            // Desserts
            ['name' => 'Chocolate Cake', 'price' => 6.50, 'cat' => 'Desserts', 'img' => 'https://images.unsplash.com/photo-1578985545062-69928b1d9587?w=600&q=80', 'desc' => 'Rich, decadent chocolate layer cake with creamy fudge frosting.'],
            ['name' => 'Vanilla Ice Cream', 'price' => 4.00, 'cat' => 'Desserts', 'img' => 'https://images.unsplash.com/photo-1557142046-c704a3adf364?w=600&q=80', 'desc' => 'Two scoops of classic vanilla bean ice cream made with real cream.'],
            ['name' => 'Strawberry Cheesecake', 'price' => 7.00, 'cat' => 'Desserts', 'img' => 'https://images.unsplash.com/photo-1533134242443-d4fd215305ad?w=600&q=80', 'desc' => 'New York style cheesecake topped with a sweet strawberry glaze.'],
            ['name' => 'Caramel Pudding', 'price' => 5.50, 'cat' => 'Desserts', 'img' => 'https://images.unsplash.com/photo-1609501676725-632349e54d63?w=600&q=80', 'desc' => 'Smooth and creamy custard topped with a layer of soft caramel.'],
            
            // Beverages
            ['name' => 'Iced Coffee', 'price' => 4.50, 'cat' => 'Beverages', 'img' => 'https://images.unsplash.com/photo-1461023058943-0708e52235eb?w=600&q=80', 'desc' => 'Premium cold-brewed coffee served over ice with your choice of milk.'],
            ['name' => 'Hot Tea', 'price' => 3.00, 'cat' => 'Beverages', 'img' => 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=600&q=80', 'desc' => 'A comforting cup of freshly steeped Earl Grey or Chamomile tea.'],
            ['name' => 'Sparkling Water', 'price' => 2.50, 'cat' => 'Beverages', 'img' => 'https://images.unsplash.com/photo-1556881286-fc6915169721?w=600&q=80', 'desc' => 'Crisp, refreshing carbonated mineral water with a twist of lime.'],
            ['name' => 'Frozen Lemonade', 'price' => 4.00, 'cat' => 'Beverages', 'img' => 'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?w=600&q=80', 'desc' => 'Ice-cold blended lemonade, perfect for a hot summer day.'],
            
            // Salads
            ['name' => 'Caesar Salad', 'price' => 9.00, 'cat' => 'Salads', 'img' => 'https://images.unsplash.com/photo-1550304943-4f24f54ddde9?w=600&q=80', 'desc' => 'Crisp romaine lettuce, parmesan cheese, croutons, and classic Caesar dressing.'],
            ['name' => 'Greek Salad', 'price' => 9.50, 'cat' => 'Salads', 'img' => 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=600&q=80', 'desc' => 'Fresh cucumbers, tomatoes, red onions, olives, and feta cheese.'],
            ['name' => 'Garden Salad', 'price' => 8.00, 'cat' => 'Salads', 'img' => 'https://images.unsplash.com/photo-1512621776951-a57141f2eefd?w=600&q=80', 'desc' => 'A vibrant mix of seasonal greens and fresh garden vegetables.'],
            ['name' => 'Cobb Salad', 'price' => 10.50, 'cat' => 'Salads', 'img' => 'https://images.unsplash.com/photo-1529312266912-b33cfce2eefd?w=600&q=80', 'desc' => 'Mixed greens topped with chicken, bacon, hard-boiled eggs, and avocado.'],
            
            // Breakfast
            ['name' => 'Pancake Stack', 'price' => 8.50, 'cat' => 'Breakfast', 'img' => 'https://images.unsplash.com/photo-1528207776546-384cb1119b71?w=600&q=80', 'desc' => 'Three fluffy buttermilk pancakes served with warm maple syrup and butter.'],
            ['name' => 'Belgian Waffle', 'price' => 7.50, 'cat' => 'Breakfast', 'img' => 'https://images.unsplash.com/photo-1562376552-0d160a2f9f27?w=600&q=80', 'desc' => 'Crispy on the outside, light and fluffy on the inside, dusted with powdered sugar.'],
            ['name' => 'Cheese Omelette', 'price' => 9.00, 'cat' => 'Breakfast', 'img' => 'https://images.unsplash.com/photo-1510693060292-0b1a629b35db?w=600&q=80', 'desc' => 'Three-egg omelette folded with melted cheddar cheese and herbs.'],
            ['name' => 'French Toast', 'price' => 8.50, 'cat' => 'Breakfast', 'img' => 'https://images.unsplash.com/photo-1484723091791-009f3ddf64ee?w=600&q=80', 'desc' => 'Thick slices of artisan bread dipped in our signature cinnamon-egg batter.'],
        ];

        $outOfStockIndices = array_rand($products, 3);
        
        foreach ($products as $index => $p) {
            $catId = isset($categories[$p['cat']]) ? $categories[$p['cat']]->id : null;
            if ($catId) {
                // Determine local filename based on product name
                $slug = \Illuminate\Support\Str::slug($p['name']);
                $filename = $slug . '.webp';
                
                // Copy the file from seeders/images to storage/app/public/product-images/{catId}
                $sourcePath = database_path('seeders/images/' . $filename);
                $productFolder = 'product-images/' . $catId;
                $targetName = $productFolder . '/' . uniqid() . '-' . $filename;
                
                if (file_exists($sourcePath)) {
                    // Create product-images folder if it doesn't exist
                    if (!\Illuminate\Support\Facades\Storage::disk('public')->exists($productFolder)) {
                        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory($productFolder);
                    }
                    \Illuminate\Support\Facades\Storage::disk('public')->put($targetName, file_get_contents($sourcePath));
                }

                Product::create([
                    'category_id' => $catId,
                    'name' => $p['name'],
                    'description' => $p['desc'],
                    'price' => $p['price'],
                    'stock' => in_array($index, $outOfStockIndices) ? 0 : rand(30, 100),
                    'image' => isset($sourcePath) && file_exists($sourcePath) ? $targetName : $p['img'],
                ]);
            }
        }
        
        $this->command->info('Seeded exactly 24 unique products with realistic prices and images!');
    }
}