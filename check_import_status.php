<?php
$conn = @new mysqli('127.0.0.1', 'root', '', 'simjar_db');
if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

echo "=== CHECKING IMPORT DATA ===\n\n";

echo "1. Total barang: ";
$result = $conn->query('SELECT COUNT(*) as total FROM barang_masuk');
$row = $result->fetch_assoc();
echo $row['total'] . "\n";

echo "\n2. Data per cabang:\n";
$result = $conn->query('SELECT cabang_id, COUNT(*) as total FROM barang_masuk GROUP BY cabang_id ORDER BY cabang_id');
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "   Cabang ID {$row['cabang_id']}: {$row['total']} barang\n";
    }
}

echo "\n3. Last 5 imported items:\n";
$result = $conn->query('SELECT nomor_barang, nama_barang, jumlah, stok, cabang_id, created_at FROM barang_masuk ORDER BY created_at DESC LIMIT 5');
if ($result->num_rows == 0) {
    echo "   (Tidak ada data)\n";
} else {
    while ($row = $result->fetch_assoc()) {
        echo "   - {$row['nomor_barang']}: {$row['nama_barang']}\n";
        echo "     Qty: {$row['jumlah']}, Stok: {$row['stok']}, Cabang: {$row['cabang_id']}, Created: {$row['created_at']}\n";
    }
}

echo "\n4. Users in system:\n";
$result = $conn->query('SELECT id, name, email, cabang_id FROM users');
while ($row = $result->fetch_assoc()) {
    echo "   - {$row['name']} (ID: {$row['id']}, Cabang ID: {$row['cabang_id']})\n";
}

echo "\n5. Check Cabangs table structure:\n";
$result = $conn->query('DESCRIBE cabangs');
while ($row = $result->fetch_assoc()) {
    echo "   - {$row['Field']} ({$row['Type']})\n";
}

echo "\n6. Cabangs data:\n";
$result = $conn->query('SELECT * FROM cabangs LIMIT 10');
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "   - ID {$row['id']}: {$row['name']}\n";
    }
} else {
    echo "   (No cabangs)\n";
}

$conn->close();
?>
