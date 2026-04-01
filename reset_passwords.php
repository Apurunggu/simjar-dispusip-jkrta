<?php
require 'vendor/autoload.php';

$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Reset all user passwords to 12345
$users = \App\Models\User::all();

foreach ($users as $user) {
    $user->password = bcrypt('12345');
    $user->save();
    echo "✓ Password for {$user->email} reset to 12345\n";
}

echo "\n✓ All passwords reset successfully!\n";
echo "Akun yang bisa login:\n";
echo "1. admin@simjar.test (Super Admin)\n";
echo "2. admin.utara@simjar.test (Admin Cabang Utara)\n";
echo "3. staff@simjar.test (Staff)\n";
echo "4. user@simjar.test (User Viewer)\n";
echo "\nPassword: 12345\n";
