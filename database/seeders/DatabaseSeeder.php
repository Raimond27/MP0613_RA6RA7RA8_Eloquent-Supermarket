<?php

namespace Database\Seeders;

use App\Models\CalendarEvent;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run additional seeders.
        $this->call([
            RoleSeeder::class,
            CategorySeeder::class,
            UserSeeder::class,
            FeeSeeder::class,
        ]);

        // Create 100 products using the factory.
        $products = Product::factory(15)->create();

        // For each product, create between 1 associated image.
        foreach ($products as $product) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_path' => 'products/stock_product_' . $product->id . '.png'
            ]);
        }

        // Create 5 calendar events using the factory.
        CalendarEvent::factory(5)->create();
    }
}
