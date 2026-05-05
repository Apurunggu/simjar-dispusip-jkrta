<?php
$conn = @new mysqli('127.0.0.1', 'root', '', 'simjar_db');
if ($conn->connect_error) {
    die('<div style="background: #f8d7da; padding: 15px; border-radius: 5px;"><h2>❌ Koneksi Database Gagal</h2>' . $conn->connect_error . '</div>');
}

?><!DOCTYPE html>
<html>
<head>
    <title>Debug Import Barang</title>
    <style>
        body { font-family: Arial; margin: 20px; }
        .box { border: 1px solid #ccc; padding: 15px; margin: 10px 0; border-radius: 5px; background: #f9f9f9; }
        .warning { background: #fff3cd; border-color: #ffc107; }
        .success { background: #d4edda; border-color: #28a745; }
        .error { background: #f8d7da; border-color: #dc3545; }
        h2 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        code { background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }
        table { width: 100%; border-collapse: collapse; margin: 10px 0; }
        th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
        th { background: #f2f2f2; font-weight: bold; }
    </style>
</head>
<body>

<h1>🔍 DEBUG: Mengapa Import Tidak Terlihat?</h1>

<?php
// Check database data
echo '<div class="box success"><h2>2. Status Data di Database</h2>';
$totalBarang = $conn->query('SELECT COUNT(*) as total FROM barang_masuk')->fetch_assoc()['total'];
echo "✅ Total barang di database: <strong>$totalBarang</strong>";

echo '<h3>Data per Cabang:</h3>';
echo '<table><tr><th>Cabang ID</th><th>Jumlah Barang</th></tr>';
$result = $conn->query('SELECT cabang_id, COUNT(*) as total FROM barang_masuk GROUP BY cabang_id');
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['cabang_id']}</td><td>{$row['total']}</td></tr>";
}
echo '</table>';

// Show latest imports
echo '<h3>Data Barang Terakhir:</h3>';
echo '<table><tr><th>Nomor</th><th>Nama</th><th>Qty</th><th>Stok</th><th>Cabang</th><th>Waktu Import</th></tr>';
$result = $conn->query('SELECT nomor_barang, nama_barang, jumlah, stok, cabang_id, created_at FROM barang_masuk ORDER BY created_at DESC LIMIT 5');
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['nomor_barang']}</td><td>{$row['nama_barang']}</td><td>{$row['jumlah']}</td><td>{$row['stok']}</td><td>{$row['cabang_id']}</td><td>{$row['created_at']}</td></tr>";
}
echo '</table>';
echo '</div>';

// Check users
echo '<div class="box"><h2>3. User Accounts & Cabang</h2>';
echo '<table><tr><th>ID</th><th>Name</th><th>Email</th><th>Cabang ID</th></tr>';
$result = $conn->query('SELECT id, name, email, cabang_id FROM users ORDER BY id');
while ($row = $result->fetch_assoc()) {
    echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td><td>{$row['email']}</td><td>{$row['cabang_id']}</td></tr>";
}
echo '</table>';
echo '</div>';

// Problem analysis
echo '<div class="box warning"><h2>4. Analisis Kemungkinan Masalah</h2>';

echo '<h3>📋 Skenario:</h3>';
echo '<ul>';
echo '<li><strong>Semua data (' . $totalBarang . ' barang) masuk ke Cabang ID 1</strong></li>';
echo '<li><strong>Jika user yang login bukan dari Cabang 1</strong>, maka sistem akan <strong style="color: red;">menyembunyikan data</strong> karena filtering berdasarkan cabang</li>';
echo '</ul>';

echo '<h3>✅ Solusi:</h3>';
echo '<ol>';
echo '<li><strong>Cek user mana yang login saat import dilakukan</strong></li>';
echo '<li><strong>Pastikan user tersebut dari Cabang ID 1</strong></li>';
echo '<li><strong>Jika user dari cabang lain, perlu re-import dengan user dari Cabang 1</strong></li>';
echo '</ol>';

echo '<h3>Cara Cek User Sekarang:</h3>';
echo '<p>Buka: <code>Profil User → Lihat Cabang Anda</code></p>';
echo '<p>Atau ke: <code>127.0.0.1:8000/barang-masuk</code> kemudian cek data yang muncul</p>';

echo '</div>';

// Recomendation
echo '<div class="box success"><h2>5. Rekomendasi</h2>';
echo '<p><strong>Untuk melihat data yang sudah diimport:</strong></p>';
echo '<ol>';
echo '<li>Login dengan <strong>Super Admin</strong> atau <strong>Admin Cabang 1</strong></li>';
echo '<li>Pergi ke: <code>Barang Masuk</code></li>';
echo '<li>Data seharusnya sudah muncul (23 barang)</li>';
echo '</ol>';
echo '</div>';

$conn->close();
?>

</body>
</html>
