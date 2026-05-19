<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    // The model that this factory will create.
    protected $model = Product::class;

    // Static counter to cycle through products without duplicates.
    private static int $productIndex = 0;

    // Define the default state for a Product.
    public function definition(): array
    {
        $products = [
            ['name' => 'Organic Whole Wheat Bread', 'description' => 'Fresh baked organic whole wheat bread made with premium ingredients and no preservatives. Perfect for healthy sandwiches and toast every morning.'],
            ['name' => 'Free-Range Chicken Breast', 'description' => 'High quality free-range chicken breast sourced from local farms. Lean protein packed and ideal for grilling, baking, or pan-searing.'],
            ['name' => 'Greek Yogurt Vanilla', 'description' => 'Creamy Greek yogurt with natural vanilla flavor. Rich in probiotics and high in protein content for a nutritious breakfast option.'],
            ['name' => 'Fresh Salmon Fillets', 'description' => 'Wild-caught salmon fillets packed with omega-3 fatty acids. Perfect for a healthy dinner with crispy skin and tender flesh inside.'],
            ['name' => 'Organic Spinach Salad Mix', 'description' => 'Pre-washed organic baby spinach blend ready to eat. Loaded with iron and nutrients for a quick and healthy salad base.'],
            ['name' => 'Extra Virgin Olive Oil', 'description' => 'Cold-pressed extra virgin olive oil from Mediterranean olives. Rich flavor and perfect for dressings, dips, and healthy cooking.'],
            ['name' => 'Almond Butter Natural', 'description' => 'Smooth natural almond butter made from roasted almonds with no added sugar. Great spread for toast and baking applications.'],
            ['name' => 'Wild Blueberry Pack', 'description' => 'Fresh frozen wild blueberries harvested at peak ripeness. Packed with antioxidants and perfect for smoothies and baking recipes.'],
            ['name' => 'Grass-Fed Ground Beef', 'description' => 'Premium grass-fed ground beef with excellent marbling. Ideal for burgers, tacos, and pasta sauce preparations.'],
            ['name' => 'Aged Cheddar Cheese', 'description' => 'Sharp aged cheddar cheese with complex flavor profile. Perfect for cheese boards, melting on burgers, or cooking applications.'],
            ['name' => 'Organic Carrots Bundle', 'description' => 'Fresh organic carrots harvested daily from local farms. Rich in beta-carotene and perfect for roasting, soups, and raw snacking.'],
            ['name' => 'Brown Rice Organic', 'description' => 'Whole grain brown rice rich in fiber and nutrients. Versatile for bowls, stir-fries, and everyday meals with nutty flavor.'],
            ['name' => 'Farm Fresh Eggs Dozen', 'description' => 'Pasture-raised farm fresh eggs with vibrant golden yolks. High in omega-3 and perfect for any breakfast preparation.'],
            ['name' => 'Tomato Sauce Marinara', 'description' => 'Traditional Italian marinara sauce made from ripe tomatoes and herbs. Ready to use for pasta, pizza, and Italian cooking.'],
            ['name' => 'Avocado Fresh', 'description' => 'Creamy ripe avocados perfect for guacamole and salads. Rich in healthy fats and nutrients for heart health.']           
        ];

        // Cycle through products sequentially to avoid duplicates.
        $product = $products[self::$productIndex % count($products)];
        self::$productIndex++;

        return [
            'name' => $product['name'],
            'description' => $product['description'],
            // Assign a random category ID between 1 and 52.
            'category_id' => rand(1, 52),
            // Generate a random price with 2 decimals between 1 and 100.
            'price' => fake()->randomFloat(2, 1, 100),
        ];
    }
}
