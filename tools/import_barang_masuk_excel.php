<?php
// Script: import_barang_masuk_excel.php
// Import data Excel ke tabel barang_masuk_excel (mapping 1:1 dengan Excel)

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

date_default_timezone_set('Asia/Jakarta');

// --- KONFIGURASI ---
$excelFile = __DIR__ . '/../public/storage/dokumen/distribusi/data.xlsx'; // Pastikan file ada di sini
$dbHost = 'localhost';
$dbName = 'simjar_db';
$dbUser = 'root';
$dbPass = '';

// --- KONEKSI DATABASE ---
$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// --- BACA FILE EXCEL ---
if (!file_exists($excelFile)) {
    die("File Excel tidak ditemukan: $excelFile\n");
}
$spreadsheet = IOFactory::load($excelFile);
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray(null, true, true, true);

// --- MAPPING KOLOM ---
// Asumsi header di baris 1, data mulai baris 2
for ($i = 2; $i <= count($rows); $i++) {
    $row = $rows[$i];
    if (empty($row['B'])) continue; // Lewati baris kosong

    $nama_perangkat = $row['B'] ?? null;
    $merk_type = $row['C'] ?? null;
    $qty = (int)($row['D'] ?? 0);
    $satuan = $row['E'] ?? null;
    $sisa_stok = (int)($row['F'] ?? 0);
    $kepemilikan = $row['G'] ?? null;
    $status = $row['H'] ?? null;
    $posisi = $row['I'] ?? null;
    $tahun_pengadaan = $row['J'] ?? null;
    $keterangan = $row['K'] ?? null;
    $barang_masuk = $row['L'] ?? null;
    $barang_keluar = $row['M'] ?? null;

    $stmt = $pdo->prepare("INSERT INTO barang_masuk_excel (nama_perangkat, merk_type, qty, satuan, sisa_stok, kepemilikan, status, posisi, tahun_pengadaan, keterangan, barang_masuk, barang_keluar, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute([$nama_perangkat, $merk_type, $qty, $satuan, $sisa_stok, $kepemilikan, $status, $posisi, $tahun_pengadaan, $keterangan, $barang_masuk, $barang_keluar]);
}

echo "Import selesai!\n";
