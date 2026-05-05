<?php
// Direct database check
$host = '127.0.0.1';
$db = 'simjar_db';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    
    $barang = $pdo->query("SELECT COUNT(*) as cnt FROM barang_masuk")->fetch(PDO::FETCH_ASSOC)['cnt'];
    $total = $pdo->query("SELECT COALESCE(SUM(jumlah), 0) as total FROM barang_masuk")->fetch(PDO::FETCH_ASSOC)['total'];
    $stok = $pdo->query("SELECT COALESCE(SUM(stok), 0) as stok FROM barang_masuk")->fetch(PDO::FETCH_ASSOC)['stok'];
    $perangkat = $pdo->query("SELECT COUNT(*) as cnt FROM perangkat_jaringan")->fetch(PDO::FETCH_ASSOC)['cnt'];
    $users = $pdo->query("SELECT COUNT(*) as cnt FROM users")->fetch(PDO::FETCH_ASSOC)['cnt'];
    
    echo "✓ DATABASE CHECK (Direct Connection)\n";
    echo "=====================================\n";
    echo "Barang Masuk Count: $barang\n";
    echo "Total Jumlah: $total\n";
    echo "Total Stok: $stok\n";
    echo "Perangkat Jaringan: $perangkat\n";
    echo "Users: $users\n";
    
    if ($barang == 0) {
        echo "\n⚠ WARNING: Database barang_masuk kosong!\n";
    }
} catch (PDOException $e) {
    echo "✗ Database Error: " . $e->getMessage() . "\n";
}
?>
