<?php
$conn = @new mysqli('127.0.0.1', 'root', '', 'simjar_db');
echo '<h2>📍 Data Barang per Cabang</h2>';
echo '<table border="1" style="border-collapse: collapse; width: 100%; margin: 20px 0;">';
echo '<tr style="background: #f2f2f2;"><th style="padding: 10px;">Nomor</th><th>Nama Barang</th><th>Qty</th><th>Stok</th><th>Cabang</th><th>Import Date</th></tr>';

$result = $conn->query('SELECT nomor_barang, nama_barang, jumlah, stok, cabang_id, created_at FROM barang_masuk ORDER BY cabang_id, created_at DESC');
while ($row = $result->fetch_assoc()) {
    echo '<tr>';
    echo '<td style="padding: 8px;">' . $row['nomor_barang'] . '</td>';
    echo '<td style="padding: 8px;">' . $row['nama_barang'] . '</td>';
    echo '<td style="padding: 8px; text-align: center;">' . $row['jumlah'] . '</td>';
    echo '<td style="padding: 8px; text-align: center;">' . $row['stok'] . '</td>';
    echo '<td style="padding: 8px; text-align: center;"><strong>' . $row['cabang_id'] . '</strong></td>';
    echo '<td style="padding: 8px;">' . $row['created_at'] . '</td>';
    echo '</tr>';
}
echo '</table>';
$conn->close();
?>
