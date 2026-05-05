<?php
$conn = @new mysqli('127.0.0.1', 'root', '', 'simjar_db');

echo '<h2>Checking All Barang Data</h2>';

echo '<h3>Total Barang in Database:</h3>';
$total = $conn->query('SELECT COUNT(*) as total FROM barang_masuk')->fetch_assoc()['total'];
echo '<p>Total: <strong>' . $total . '</strong></p>';

echo '<h3>Barang for Cabang ID 1:</h3>';
$result = $conn->query('SELECT * FROM barang_masuk WHERE cabang_id = 1 ORDER BY created_at DESC LIMIT 20');
echo '<p>Total for Cabang 1: <strong>' . $result->num_rows . '</strong></p>';

echo '<table border="1" style="border-collapse: collapse; width: 100%; margin: 10px 0;">';
echo '<tr style="background: #f2f2f2;"><th>ID</th><th>Nomor</th><th>Nama</th><th>Kategori</th><th>Qty</th><th>Stok</th><th>Cabang</th><th>Created</th></tr>';

while ($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td style="padding: 5px;">' . $row['id'] . '</td>';
    echo '<td style="padding: 5px;">' . $row['nomor_barang'] . '</td>';
    echo '<td style="padding: 5px;">' . $row['nama_barang'] . '</td>';
    echo '<td style="padding: 5px;">' . $row['kategori'] . '</td>';
    echo '<td style="padding: 5px; text-align: center;">' . $row['jumlah'] . '</td>';
    echo '<td style="padding: 5px; text-align: center;">' . $row['stok'] . '</td>';
    echo '<td style="padding: 5px; text-align: center;">' . $row['cabang_id'] . '</td>';
    echo '<td style="padding: 5px;">' . $row['created_at'] . '</td>';
    echo '</tr>';
}
echo '</table>';

$conn->close();
?>
