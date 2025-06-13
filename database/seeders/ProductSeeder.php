<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::all();

        // Electronics products
        $electronicsCategory = $categories->where('name', 'Electronics')->first();

        $electronicsProducts = [
            [
                'name' => 'Smartphone X',
                'description' => 'Latest smartphone with advanced features',
                'price' => 999.99,
                'quantity' => 50,
                'featured' => true,
            ],
            [
                'name' => 'Laptop Pro',
                'description' => 'High-performance laptop for professionals',
                'price' => 1499.99,
                'quantity' => 30,
                'featured' => true,
            ],
            [
                'name' => 'Wireless Headphones',
                'description' => 'Noise-cancelling wireless headphones',
                'price' => 199.99,
                'quantity' => 100,
                'featured' => false,
            ],
        ];

        $this->createProducts($electronicsProducts, $electronicsCategory->id);

        // Clothing products
        $clothingCategory = $categories->where('name', 'Clothing')->first();

        $clothingProducts = [
            [
                'name' => 'Men\'s T-Shirt',
                'description' => 'Comfortable cotton t-shirt for men',
                'price' => 24.99,
                'quantity' => 200,
                'featured' => false,
            ],
            [
                'name' => 'Women\'s Jeans',
                'description' => 'Stylish jeans for women',
                'price' => 49.99,
                'quantity' => 150,
                'featured' => true,
            ],
            [
                'name' => 'Winter Jacket',
                'description' => 'Warm winter jacket for cold weather',
                'price' => 129.99,
                'quantity' => 80,
                'featured' => false,
            ],
        ];

        $this->createProducts($clothingProducts, $clothingCategory->id);

        // Books products
        $booksCategory = $categories->where('name', 'Books')->first();

        $booksProducts = [
            [
                'name' => 'The Great Novel',
                'description' => 'Bestselling fiction novel',
                'price' => 14.99,
                'quantity' => 300,
                'featured' => true,
            ],
            [
                'name' => 'Cooking Masterclass',
                'description' => 'Comprehensive cooking guide',
                'price' => 29.99,
                'quantity' => 120,
                'featured' => false,
            ],
            [
                'name' => 'History of the World',
                'description' => 'Detailed world history book',
                'price' => 39.99,
                'quantity' => 90,
                'featured' => false,
            ],
        ];

        $this->createProducts($booksProducts, $booksCategory->id);
    }

    /**
     * Create products for a category.
     */
    private function createProducts(array $products, int $categoryId): void
    {
        foreach ($products as $product) {
            Product::create([
                'category_id' => $categoryId,
                'name' => $product['name'],
                'slug' => Str::slug($product['name']),
                'description' => $product['description'],
                'price' => $product['price'],
                'quantity' => $product['quantity'],
                'featured' => $product['featured'],
                'active' => true,
            ]);
        }
    }
}
