<?php
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Get or create roles
$superAdminRole = \App\Models\Role::firstOrCreate(
    ['name' => 'super_admin'],
    ['label' => 'Super Admin', 'description' => 'Lihat semua cabang']
);
$adminCabangRole = \App\Models\Role::firstOrCreate(
    ['name' => 'admin_cabang'],
    ['label' => 'Admin Cabang', 'description' => 'Hanya lihat cabangnya']
);
$staffRole = \App\Models\Role::firstOrCreate(
    ['name' => 'staff'],
    ['label' => 'Staff', 'description' => 'Input distribusi']
);
$userRole = \App\Models\Role::firstOrCreate(
    ['name' => 'user'],
    ['label' => 'User', 'description' => 'Hanya lihat laporan']
);

// Create cabangs if not exist
$pusat = \App\Models\Cabang::firstOrCreate(
    ['kode_cabang' => 'PUSAT'],
    ['nama_cabang' => 'DISPUSIP Pusat', 'alamat' => 'Jl. Pusat', 'kota' => 'Jakarta', 'provinsi' => 'DKI Jakarta', 'is_pusat' => true]
);
$utara = \App\Models\Cabang::firstOrCreate(
    ['kode_cabang' => 'UTARA'],
    ['nama_cabang' => 'Cabang Utara', 'alamat' => 'Jl. Utara', 'kota' => 'Bekasi', 'provinsi' => 'Jawa Barat', 'is_pusat' => false]
);

// Get roles
$superAdminRole = \App\Models\Role::where('name', 'super_admin')->first();
$adminCabangRole = \App\Models\Role::where('name', 'admin_cabang')->first();
$staffRole = \App\Models\Role::where('name', 'staff')->first();
$userRole = \App\Models\Role::where('name', 'user')->first();

// Clear existing users
\DB::statement('SET FOREIGN_KEY_CHECKS=0');
\App\Models\User::truncate();
\DB::statement('SET FOREIGN_KEY_CHECKS=1');

// Create users
\App\Models\User::create([
    'name' => 'Super Admin',
    'email' => 'admin@simjar.test',
    'password' => bcrypt('12345'),
    'role_id' => $superAdminRole->id,
    'cabang_id' => $pusat->id,
]);

\App\Models\User::create([
    'name' => 'Admin Cabang Utara',
    'email' => 'admin.utara@simjar.test',
    'password' => bcrypt('12345'),
    'role_id' => $adminCabangRole->id,
    'cabang_id' => $utara->id,
]);

\App\Models\User::create([
    'name' => 'Staff',
    'email' => 'staff@simjar.test',
    'password' => bcrypt('12345'),
    'role_id' => $staffRole->id,
    'cabang_id' => $pusat->id,
]);

\App\Models\User::create([
    'name' => 'User Viewer',
    'email' => 'user@simjar.test',
    'password' => bcrypt('12345'),
    'role_id' => $userRole->id,
    'cabang_id' => $pusat->id,
]);

echo "✓ Users created successfully!\n";
echo "Login dengan:\n";
echo "  Email: admin@simjar.test\n";
echo "  Password: 12345\n";
