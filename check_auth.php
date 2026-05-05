<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\DB;

echo "=== USER & AUTH CHECK ===\n\n";

// Check users table
$userCount = User::count();
echo "Total users in database: $userCount\n";

if ($userCount > 0) {
    $users = User::limit(3)->get();
    echo "\nFirst users:\n";
    foreach ($users as $user) {
        echo "  - ID: {$user->id}, Name: {$user->name}, Email: {$user->email}\n";
        $roles = $user->getRoleNames();
        echo "    Roles: " . ($roles->count() > 0 ? implode(', ', $roles->toArray()) : 'No roles') . "\n";
    }
} else {
    echo "❌ NO USERS FOUND - Need to create test user\n";
    
    // Try to create a test user
    try {
        echo "\nAttempting to create test user...\n";
        
        // First check if roles exist
        $adminRole = DB::table('roles')->where('name', 'super_admin')->first();
        if (!$adminRole) {
            echo "  Creating super_admin role...\n";
            DB::table('roles')->insert([
                'name' => 'super_admin',
                'label' => 'Super Admin',
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
        
        $user = User::create([
            'name' => 'Admin Test',
            'email' => 'admin@test.local',
            'password' => bcrypt('password123'),
            'email_verified_at' => now()
        ]);
        
        $user->assignRole('super_admin');
        echo "  ✓ User created successfully\n";
        echo "    Email: admin@test.local\n";
        echo "    Password: password123\n";
        
    } catch (\Exception $e) {
        echo "  ❌ Error creating user: " . $e->getMessage() . "\n";
    }
}

echo "\n=== DATABASE TABLES ===\n";
$tables = DB::select("SHOW TABLES");
$tableList = [];
foreach ($tables as $table) {
    $tableName = array_values((array)$table)[0];
    if (!in_array($tableName, ['failed_jobs', 'personal_access_tokens'])) {
        $tableList[] = $tableName;
    }
}
echo implode(', ', $tableList) . "\n";

echo "\n=== SESSION & CACHE ===\n";
echo "Session driver: " . config('session.driver') . "\n";
echo "Cache driver: " . config('cache.default') . "\n";

?>
