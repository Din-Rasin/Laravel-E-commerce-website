<?php

// This script checks and fixes user roles in the database

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Get all users
$users = User::all();

echo "Checking user roles...\n";

$fixedCount = 0;

foreach ($users as $user) {
    echo "User ID: {$user->id}, Name: {$user->name}, Email: {$user->email}, Role: {$user->role}\n";
    
    // If role is null or empty, set it to 'user'
    if (empty($user->role)) {
        $user->role = 'user';
        $user->save();
        echo "  - Fixed: Role set to 'user'\n";
        $fixedCount++;
    }
}

echo "\nTotal users: " . count($users) . "\n";
echo "Fixed users: {$fixedCount}\n";

// Create a test user if needed
$testUserEmail = 'test@example.com';
$testUser = User::where('email', $testUserEmail)->first();

if (!$testUser) {
    echo "\nCreating test user...\n";
    
    $testUser = User::create([
        'name' => 'Test User',
        'email' => $testUserEmail,
        'password' => Hash::make('password'),
        'role' => 'user',
        'email_verified_at' => now(),
    ]);
    
    echo "Test user created with ID: {$testUser->id}\n";
    echo "Email: {$testUser->email}\n";
    echo "Password: password\n";
} else {
    echo "\nTest user already exists with ID: {$testUser->id}\n";
    
    // Ensure the test user has the correct role
    if ($testUser->role !== 'user') {
        $testUser->role = 'user';
        $testUser->save();
        echo "Updated test user role to 'user'\n";
    }
}

echo "\nDone!\n";
