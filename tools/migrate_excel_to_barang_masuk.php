<?php
// Script: migrate_excel_to_barang_masuk.php
// Migrasi data dari barang_masuk_excel ke barang_masuk (mapping field utama)

require __DIR__ . '/../vendor/autoload.php';

date_default_timezone_set('Asia/Jakarta');

$dbHost = 'localhost';
$dbName = 'simjar_db';
$dbUser = 'root';
$dbPass = '';

$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// Ambil semua data dari barang_masuk_excel
$sql = "SELECT * FROM barang_masuk_excel";
$data = $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);

$count = 0;
foreach ($data as $row) {
    // Mapping field Excel ke field barang_masuk
    $nomor_barang = uniqid('BRG-');
    $nama_barang = $row['nama_perangkat'] ?? '-';
    $kategori = $row['merk_type'] ?? '-';
    $jumlah = $row['qty'] ?? 0;
    $stok = $row['sisa_stok'] ?? 0;
    $satuan = $row['satuan'] ?? null;
    $sisa_stok = $row['sisa_stok'] ?? null;
    $kepemilikan = $row['kepemilikan'] ?? null;
    $status = $row['status'] ?? null;
    $posisi = $row['posisi'] ?? null;
    $tahun_pengadaan = $row['tahun_pengadaan'] ?? null;
    $barang_masuk_excel = $row['barang_masuk'] ?? null;
    $barang_keluar = $row['barang_keluar'] ?? null;
    $tanggal_masuk = date('Y-m-d'); // Atau mapping dari Excel jika ada
    $keterangan = $row['keterangan'] ?? null;
    $dokumen = null;
    $cabang_id = null;

    $stmt = $pdo->prepare("INSERT INTO barang_masuk (nomor_barang, nama_barang, kategori, jumlah, stok, satuan, sisa_stok, kepemilikan, status, posisi, tahun_pengadaan, barang_masuk, barang_keluar, tanggal_masuk, keterangan, dokumen, cabang_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute([$nomor_barang, $nama_barang, $kategori, $jumlah, $stok, $satuan, $sisa_stok, $kepemilikan, $status, $posisi, $tahun_pengadaan, $barang_masuk_excel, $barang_keluar, $tanggal_masuk, $keterangan, $dokumen, $cabang_id]);
    $count++;
}
echo "Migrasi selesai! Total: $count data dipindahkan ke barang_masuk\n";
