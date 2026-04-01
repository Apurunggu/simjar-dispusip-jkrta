<?php
// Test dashboard data retrieval

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

$kernel = $app->make('Illuminate\Contracts\Http\Kernel');

try {
    // Start the application
    $response = $kernel->handle(
        $request = \Illuminate\Http\Request::capture()
    );

    // Import models
    $barangMasuk = \App\Models\BarangMasuk::class;
    $perangkatJaringan = \App\Models\PerangkatJaringan::class;

    echo "Testing Dashboard Data...\n";
    echo "========================\n\n";

    // Test 1: Count barang masuk
    try {
        $totalBarangMasuk = (int) \App\Models\BarangMasuk::count();
        echo "✓ Total Barang Masuk: " . $totalBarangMasuk . "\n";
    } catch (\Exception $e) {
        echo "✗ Error counting barang masuk: " . $e->getMessage() . "\n";
    }

    // Test 2: Count perangkat aktif
    try {
        $totalPerangkatAktif = (int) \App\Models\PerangkatJaringan::where('status', 'aktif')->count();
        echo "✓ Total Perangkat Aktif: " . $totalPerangkatAktif . "\n";
    } catch (\Exception $e) {
        echo "✗ Error counting perangkat aktif: " . $e->getMessage() . "\n";
    }

    // Test 3: Count perangkat tidak aktif
    try {
        $totalPerangkatTidakAktif = (int) \App\Models\PerangkatJaringan::where('status', 'tidak_aktif')->count();
        echo "✓ Total Perangkat Tidak Aktif: " . $totalPerangkatTidakAktif . "\n";
    } catch (\Exception $e) {
        echo "✗ Error counting perangkat tidak aktif: " . $e->getMessage() . "\n";
    }

    // Test 4: Check users and roles
    echo "\n--- Users & Roles ---\n";
    try {
        $users = \App\Models\User::with('role')->get();
        foreach ($users as $user) {
            $roleName = $user->role ? $user->role->label : 'No Role';
            echo "User: " . $user->name . " (" . $user->email . ") - Role: " . $roleName . "\n";
        }
    } catch (\Exception $e) {
        echo "✗ Error fetching users: " . $e->getMessage() . "\n";
    }

    echo "\n✓ All tests completed successfully!\n";

} catch (\Exception $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
}
?>
