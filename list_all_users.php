<?php
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== DAFTAR SEMUA AKUN USER ===\n\n";

$users = \App\Models\User::with(['role', 'cabang'])->get();

if ($users->isEmpty()) {
    echo "Tidak ada user ditemukan.\n";
    exit;
}

echo "Total User: " . $users->count() . "\n\n";
echo str_repeat("=", 120) . "\n";
printf("%-5s | %-25s | %-30s | %-15s | %-20s\n", "ID", "Nama", "Email", "Role", "Cabang");
echo str_repeat("=", 120) . "\n";

foreach ($users as $user) {
    printf(
        "%-5d | %-25s | %-30s | %-15s | %-20s\n",
        $user->id,
        substr($user->name, 0, 25),
        substr($user->email, 0, 30),
        $user->role?->label ?? '-',
        $user->cabang?->nama_cabang ?? '-'
    );
}

echo str_repeat("=", 120) . "\n\n";

echo "📝 CATATAN PENTING:\n";
echo "- Password sudah di-hash dengan bcrypt, tidak bisa di-display\n";
echo "- Untuk testing, default password adalah: 12345 (untuk akun seeder)\n";
echo "- Password harus minimal 8 karakter (sudah di-fix)\n\n";

echo "🔑 AKUN UNTUK TESTING:\n";
echo "1. Super Admin\n";
echo "   Email: admin@simjar.test\n";
echo "   Password: 12345 (default seeder)\n";
echo "   Role: Super Admin (lihat semua cabang)\n\n";

echo "2. Admin Cabang 2-5\n";
echo "   Email: admin2@simjar.test, admin3@simjar.test, admin4@simjar.test, admin5@simjar.test\n";
echo "   Password: 12345 (default seeder)\n";
echo "   Role: Admin Cabang (hanya cabang mereka)\n\n";

echo "3. Staff\n";
echo "   Email: staff@simjar.test\n";
echo "   Password: 12345 (default seeder) atau 'password' (dari seeder lain)\n";
echo "   Role: Staff (input distribusi)\n\n";

echo "4. User (Viewer)\n";
echo "   Email: user@simjar.test\n";
echo "   Password: 12345 (default seeder) atau 'password' (dari seeder lain)\n";
echo "   Role: User (hanya lihat laporan)\n\n";

echo "⚠️  PERUBAHAN PASSWORD:\n";
echo "Jika Anda ingin reset password user tertentu, bisa menggunakan form reset password\n";
echo "di menu User Management (hanya untuk Admin Cabang & User)\n";
