<?php
// Load composer autoload
require __DIR__ . '/vendor/autoload.php';

// Load Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

// Setup
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

// Create roles table if not exists
if (!Schema::hasTable('roles')) {
    Schema::create('roles', function ($table) {
        $table->id();
        $table->string('name')->unique();
        $table->string('label');
        $table->text('description')->nullable();
        $table->timestamps();
    });
    echo "Roles table created\n";
} else {
    echo "Roles table already exists\n";
}

// Add role_id to users if not exists
if (!Schema::hasColumn('users', 'role_id')) {
    Schema::table('users', function ($table) {
        $table->unsignedBigInteger('role_id')->nullable()->after('password');
        $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
    });
    echo "role_id column added to users table\n";
} else {
    echo "role_id column already exists in users table\n";
}

// Seed roles
$roles = [
    ['name' => 'super_admin', 'label' => 'Super Admin', 'description' => 'Lihat semua cabang'],
    ['name' => 'admin_cabang', 'label' => 'Admin Cabang', 'description' => 'Hanya lihat cabangnya'],
    ['name' => 'staff', 'label' => 'Staff', 'description' => 'Input distribusi'],
    ['name' => 'user', 'label' => 'User', 'description' => 'Hanya lihat laporan'],
];

foreach ($roles as $role) {
    DB::table('roles')->updateOrInsert(
        ['name' => $role['name']],
        $role
    );
    echo "Role '{$role['name']}' created or updated\n";
}

echo "\nSetup complete!\n";
