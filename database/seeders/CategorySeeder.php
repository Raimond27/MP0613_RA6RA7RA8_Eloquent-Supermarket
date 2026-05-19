<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define an array of parent categories and their corresponding child category names.
        $categories = [
            [
                'name' => 'Food & Beverages',
                'children' => [
                    'Fresh Produce',
                    'Dairy & Eggs',
                    'Meat & Seafood',
                    'Bakery & Snacks',
                    'Beverages',
                    'Frozen Foods'
                ]
            ],
            [
                'name' => 'Household Essentials',
                'children' => [
                    'Cleaning Supplies',
                    'Kitchen & Dining Essentials',
                    'Laundry Products',
                    'Home Storage & Organization',
                    'Paper & Plastic Goods'
                ]
            ],
            [
                'name' => 'Personal Care & Beauty',
                'children' => [
                    'Skin Care',
                    'Hair Care',
                    'Makeup & Cosmetics',
                    'Bath & Shower',
                    'Fragrances'
                ]
            ],
            [
                'name' => 'Electronics & Appliances',
                'children' => [
                    'Mobile Phones & Accessories',
                    'Home Appliances',
                    'Computers & Laptops',
                    'Smart Home Devices'
                ]
            ],
            [
                'name' => 'Clothing & Accessories',
                'children' => [
                    'Men\'s Clothing',
                    'Women\'s Clothing',
                    'Footwear',
                    'Jewelry & Watches'
                ]
            ],
            [
                'name' => 'Health & Wellness',
                'children' => [
                    'Vitamins & Supplements',
                    'Medical Supplies',
                    'Fitness Equipment',
                    'First Aid'
                ]
            ],
            [
                'name' => 'Baby & Kids',
                'children' => [
                    'Baby Food & Formula',
                    'Diapers & Wipes',
                    'Toys & Games',
                    'Kids Clothing'
                ]
            ],
            [
                'name' => 'Pet Supplies',
                'children' => [
                    'Pet Food',
                    'Pet Grooming',
                    'Pet Accessories',
                    'Pet Health'
                ]
            ],
            [
                'name' => 'Office & School Supplies',
                'children' => [
                    'Notebooks & Stationery',
                    'Writing Instruments',
                    'Printers & Accessories'
                ]
            ],
            [
                'name' => 'Sports & Outdoors',
                'children' => [
                    'Exercise Equipment',
                    'Outdoor Gear',
                    'Sports Accessories'
                ]
            ]
        ];

        // Iterate through each parent category.
        foreach ($categories as $categoryData) {
            // Create the parent category with a null parent_category field.
            $parentCategory = Category::create([
                'name' => $categoryData['name'],
                'description' => "Category containing " . $categoryData['name'] . " products.",
                'parent_category' => null,
            ]);

            // Iterate through each child category for the current parent.
            foreach ($categoryData['children'] as $child) {
                // Create the child category and assign its parent_category.
                Category::create([
                    'name' => $child,
                    'description' => "Category containing " . $child . " products.",
                    'parent_category' => $parentCategory->id,
                ]);
            }
        }
    }
}
