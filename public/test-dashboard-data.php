<?php
require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->boot();

try {
    // Check database directly
    $pdo = app('db')->getPdo();
    
    $barangResult = $pdo->query("SELECT COUNT(*) as count FROM barang_masuk");
    $barangCount = $barangResult->fetch(PDO::FETCH_ASSOC)['count'];
    
    $perangkatResult = $pdo->query("SELECT COUNT(*) as count FROM perangkat_jaringan");
    $perangkatCount = $perangkatResult->fetch(PDO::FETCH_ASSOC)['count'];
    
    $usersResult = $pdo->query("SELECT COUNT(*) as count FROM users");
    $usersCount = $usersResult->fetch(PDO::FETCH_ASSOC)['count'];
    
    echo "=== DATABASE STATUS ===\n";
    echo "Barang Masuk: $barangCount records\n";
    echo "Perangkat Jaringan: $perangkatCount records\n";
    echo "Users: $usersCount records\n";
    echo "\n";
    
    // Check actual sum values used in dashboard
    if ($barangCount > 0) {
        $sumResult = $pdo->query("SELECT SUM(jumlah) as total, SUM(stok) as stok FROM barang_masuk");
        $sums = $sumResult->fetch(PDO::FETCH_ASSOC);
        echo "Total Barang Jumlah: " . ($sums['total'] ?? 0) . "\n";
        echo "Total Stok: " . ($sums['stok'] ?? 0) . "\n";
    }
    
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage();
}
?>
