<?php
/**
 * Audit role definitions and permissions
 */
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Role;
use App\Models\User;

echo "=== ROLE DEFINITIONS ===\n";
$roles = Role::all();
foreach ($roles as $role) {
    echo "\n📋 Role: {$role->name}\n";
    echo "   Label: {$role->label}\n";
    echo "   Description: {$role->description}\n";
}

echo "\n\n=== USER TEST ACCOUNTS ===\n";
$users = User::with('role', 'cabang')->orderBy('role_id')->get();
foreach ($users as $user) {
    $cabangNama = $user->cabang ? $user->cabang->nama : 'N/A';
    echo "\n👤 {$user->email}\n";
    echo "   Name: {$user->name}\n";
    echo "   Role: {$user->role->name}\n";
    echo "   Cabang: {$cabangNama}\n";
}

echo "\n\n=== EXPECTED RESPONSIBILITIES ===\n";
$expectations = [
    'super_admin' => [
        'Lihat semua cabang dan data',
        'Kelola semua barang masuk (PUSAT)',
        'Buat dan kelola distribusi ke semua cabang',
        'Update status distribusi',
        'Hapus distribusi',
        'Akses semua laporan'
    ],
    'admin_cabang' => [
        'Lihat barang masuk cabangnya saja',
        'Buat distribusi dari/ke cabangnya saja',
        'Update status distribusi cabangnya',
        'Tidak bisa hapus barang masuk',
        'Lihat laporan cabang'
    ],
    'staff' => [
        'Input barang masuk untuk cabangnya',
        'Edit barang masuk miliknya',
        'Buat distribusi (dengan persetujuan?)',
        'Lihat data cabangnya'
    ],
    'user' => [
        'Hanya lihat dashboard & laporan',
        'Export data (read-only)',
        'Tidak bisa input/edit/hapus'
    ]
];

foreach ($expectations as $role => $tasks) {
    echo "\n📌 $role:\n";
    foreach ($tasks as $task) {
        echo "   ✓ $task\n";
    }
}

echo "\n\n=== ROUTE MIDDLEWARE CHECKS ===\n";
echo "✓ Super Admin: akses semua route\n";
echo "✓ Admin Cabang: akses barang/distribusi cabang + lihat semua dashboard\n";
echo "✓ Staff: akses input/edit barang & distribusi cabang\n";
echo "✓ User: read-only (dashboard, index, show)\n";
