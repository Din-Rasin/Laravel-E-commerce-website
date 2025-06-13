<?php

// This script creates a test order for the currently logged in user

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;

// Get all users
$users = User::all();

echo "Available users:\n";
foreach ($users as $user) {
    echo "User ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}\n";
}

// Ask for user ID
echo "\nEnter user ID to create a test order for (or press Enter to use the first user): ";
$userId = trim(fgets(STDIN));

if (empty($userId)) {
    $user = User::first();
    $userId = $user->id;
}

// Get the user
$user = User::find($userId);

if (!$user) {
    echo "User not found!\n";
    exit(1);
}

echo "Creating test order for user: {$user->name} (ID: {$user->id})\n";

// Get a product
$product = Product::first();

if (!$product) {
    echo "No products found! Creating a test product...\n";
    
    // Create a test product
    $product = Product::create([
        'category_id' => 1, // Assuming category ID 1 exists
        'name' => 'Test Product',
        'slug' => 'test-product',
        'description' => 'This is a test product',
        'price' => 99.99,
        'quantity' => 100,
        'active' => true,
    ]);
}

try {
    DB::beginTransaction();
    
    // Create an order
    $order = new Order([
        'user_id' => $user->id,
        'total_amount' => $product->price,
        'status' => 'pending',
        'payment_method' => 'cash',
        'shipping_address' => '123 Test Street',
        'shipping_city' => 'Test City',
        'shipping_state' => 'Test State',
        'shipping_zipcode' => '12345',
        'shipping_phone' => '123-456-7890',
        'notes' => 'This is a test order',
    ]);
    
    // Generate a token
    $order->token = bin2hex(random_bytes(32));
    
    $order->save();
    
    // Create an order item
    $orderItem = new OrderItem([
        'order_id' => $order->id,
        'product_id' => $product->id,
        'quantity' => 1,
        'price' => $product->price,
    ]);
    
    $orderItem->save();
    
    DB::commit();
    
    echo "Test order created successfully!\n";
    echo "Order ID: {$order->id}\n";
    echo "Order Token: {$order->token}\n";
    echo "Total Amount: \${$order->total_amount}\n";
    
} catch (\Exception $e) {
    DB::rollBack();
    echo "Error creating test order: {$e->getMessage()}\n";
    exit(1);
}

echo "\nDone!\n";
