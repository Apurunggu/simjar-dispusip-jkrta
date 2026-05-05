<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

echo "=== TEST DETEKSI KOLOM FILE USER ===\n\n";

// Simulasi file user dengan format berbeda
$testHeaders = [
    'NO',
    'NAMA PERANGKAT', 
    'MERK/TYPE',
    'QTY',
    'Satuan',
    'SISA STOK',
    'KEPEMILIKAN',
    'STATUS',
    'POSISI',
    'TAHUN PENGADAAN',
    'KETERANGAN'
];

echo "Header dari file user:\n";
foreach ($testHeaders as $idx => $h) {
    echo "  [$idx] '$h'\n";
}

// Normalize headers
$header = [];
foreach ($testHeaders as $idx => $h) {
    if (empty($h)) {
        $header[$idx] = null;
        continue;
    }
    $normalized = strtolower(trim($h));
    $normalized = str_replace(["\t", "\n", "\r", "  "], '', $normalized);
    $normalized = preg_replace('/\s+/', '', $normalized);
    $header[$idx] = $normalized;
}

echo "\nNormalized headers:\n";
foreach ($header as $idx => $h) {
    echo "  [$idx] '$h'\n";
}

// Fuzzy matching function (sama dengan controller)
$findColumn = function($searchTerms) use ($header) {
    $searchTerms = (array) $searchTerms;
    foreach ($searchTerms as $term) {
        // Normalize search term
        $termNormalized = strtolower(trim($term));
        $termNormalized = preg_replace('/\s+/', '', $termNormalized);
        
        foreach ($header as $idx => $colName) {
            if ($colName === null) continue;
            
            // Exact match
            if ($colName === $termNormalized) {
                return $idx;
            }
            
            // Partial match
            if (strpos($colName, $termNormalized) !== false || 
                strpos($termNormalized, $colName) !== false) {
                return $idx;
            }
        }
    }
    return null;
};

// Test detection
echo "\n=== COLUMN DETECTION TEST ===\n";
$colName = $findColumn(['nama perangkat', 'namaperangkat', 'nama_perangkat', 'nama barang', 'namabarang']);
$colQty = $findColumn(['qty', 'jumlah', 'quantity']);
$colMerk = $findColumn(['merk/type', 'merktype', 'merk', 'type', 'jenis', 'merk type']);
$colNo = $findColumn(['no', 'nomor', 'nomor_barang']);
$colStok = $findColumn(['sisa stok', 'sisastok', 'stok', 'sisa_stok', 'stock']);
$colKeterangan = $findColumn(['keterangan', 'ket', 'catatan']);
$colTahun = $findColumn(['tahun pengadaan', 'tahunpengadaan', 'tahun']);
$colSatuan = $findColumn(['satuan', 'unit']);
$colStatus = $findColumn(['status', 'kondisi']);
$colPosisi = $findColumn(['posisi', 'lokasi']);

echo "Detection results:\n";
echo "  NAMA PERANGKAT: " . ($colName !== null ? "✓ Index $colName" : "✗ NOT FOUND") . "\n";
echo "  QTY: " . ($colQty !== null ? "✓ Index $colQty" : "✗ NOT FOUND") . "\n";
echo "  MERK/TYPE: " . ($colMerk !== null ? "✓ Index $colMerk" : "✗ NOT FOUND") . "\n";
echo "  NO: " . ($colNo !== null ? "✓ Index $colNo" : "✗ NOT FOUND") . "\n";
echo "  SISA STOK: " . ($colStok !== null ? "✓ Index $colStok" : "✗ NOT FOUND") . "\n";
echo "  KETERANGAN: " . ($colKeterangan !== null ? "✓ Index $colKeterangan" : "✗ NOT FOUND") . "\n";
echo "  TAHUN PENGADAAN: " . ($colTahun !== null ? "✓ Index $colTahun" : "✗ NOT FOUND") . "\n";
echo "  SATUAN: " . ($colSatuan !== null ? "✓ Index $colSatuan" : "✗ NOT FOUND") . "\n";
echo "  STATUS: " . ($colStatus !== null ? "✓ Index $colStatus" : "✗ NOT FOUND") . "\n";
echo "  POSISI: " . ($colPosisi !== null ? "✓ Index $colPosisi" : "✗ NOT FOUND") . "\n";

// Validate required columns
if ($colName === null || $colQty === null) {
    echo "\n✗ ERROR: Kolom wajib tidak ditemukan!\n";
    exit(1);
} else {
    echo "\n✓ Semua kolom wajib ditemukan! Import bisa dilanjutkan.\n";
}

echo "\n=== TEST SELESAI ===\n";
