<?php

// Script: import_barang_masuk.php
// Import data Excel ke tabel barang_masuk menggunakan PhpSpreadsheet

require __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
// use PDO;

date_default_timezone_set('Asia/Jakarta');

// --- KONFIGURASI ---
$excelFile = __DIR__ . '/../public/storage/distribusi/data.xlsx'; // Ganti sesuai lokasi file
$dbHost = 'localhost';
$dbName = 'simjar_db';
$dbUser = 'root';
$dbPass = '';

// --- KONEKSI DATABASE ---
$pdo = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// --- BACA FILE EXCEL ---
$spreadsheet = IOFactory::load($excelFile);
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray(null, true, true, true);

// --- MAPPING KOLOM ---
// Asumsi header di baris 1, data mulai baris 2
for ($i = 2; $i <= count($rows); $i++) {
    $row = $rows[$i];
    if (empty($row['B'])) continue; // Lewati baris kosong

    // Mapping
    $nama_barang = $row['B']; // NAMA PERANGKAT
    $kategori = $row['C'];    // MERK/TYPE
    $jumlah = (int) $row['D']; // QTY
    $stok = (int) $row['F'];   // SISA STOK
    $keterangan = $row['K'] ?? null; // KETERANGAN

    // Field wajib manual/default
    $nomor_barang = uniqid('NB-');
    $tanggal_masuk = date('Y-m-d'); // Atau bisa diisi manual

    // Insert ke database
    $stmt = $pdo->prepare("INSERT INTO barang_masuk (nomor_barang, nama_barang, kategori, jumlah, stok, tanggal_masuk, keterangan, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
    $stmt->execute([$nomor_barang, $nama_barang, $kategori, $jumlah, $stok, $tanggal_masuk, $keterangan]);
}

echo "Import selesai!";
