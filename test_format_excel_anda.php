<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

echo "=== MEMBUAT FILE SIMULASI SESUAI FORMAT ANDA ===\n\n";

// Buat file Excel dengan format SAMA SEPERTI file Anda
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Header SAMA dengan file Anda
$headers = ['NO', 'NAMA PERANGKAT', 'MERK/TYPE', 'QTY', 'Satuan', 'SISA STOK', 'KEPEMILIKAN', 'STATUS', 'POSISI', 'TAHUN PENGADAAN', 'KETERANGAN'];
$sheet->fromArray($headers, NULL, 'A1');

// Data SAMA format dengan file Anda
$data = [
    [1, 'Router TP-Link', 'TP-Link TL-WR840N', 5, 'UNIT', 5, 'DISPUSIP', 'TERPASANG', 'RUANG SERVER', 2021, 'Stock baru'],
    [2, 'Switch Cisco', 'Cisco SG300-52', 3, 'UNIT', 3, 'DISPUSIP', 'TERPASANG', 'RUANG JARINGAN', 2021, 'Donasi'],
    [3, 'AP Ubiquiti', 'Ubiquiti AC-LR', 2, 'UNIT', 2, 'DISPUSIP', 'BELUM TERPASANG', 'RUANG PENYIMPANAN', 2021, ''],
    [4, 'Modem DSL', 'Huawei HG8245', 4, 'UNIT', 4, 'DISPUSIP', 'TERPASANG', 'RUANG TEKNIS', 2022, 'Pengadaan baru'],
];

foreach ($data as $i => $row) {
    $sheet->fromArray([$row], NULL, 'A' . ($i + 2));
}

// Set column widths
$sheet->getColumnDimension('A')->setWidth(5);
$sheet->getColumnDimension('B')->setWidth(25);
$sheet->getColumnDimension('C')->setWidth(25);
$sheet->getColumnDimension('D')->setWidth(8);
$sheet->getColumnDimension('E')->setWidth(8);
$sheet->getColumnDimension('F')->setWidth(10);
$sheet->getColumnDimension('G')->setWidth(12);
$sheet->getColumnDimension('H')->setWidth(15);
$sheet->getColumnDimension('I')->setWidth(20);
$sheet->getColumnDimension('J')->setWidth(15);
$sheet->getColumnDimension('K')->setWidth(25);

$file = __DIR__ . '/storage/app/test_format_anda.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($file);

echo "✓ File simulasi dibuat: test_format_anda.xlsx\n";

// Sekarang test header parsing
echo "\n=== TESTING HEADER PARSING ===\n\n";

$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($file);
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray();

echo "Header original:\n";
$rawHeader = $rows[0];
foreach ($rawHeader as $idx => $h) {
    echo "  [$idx] '$h'\n";
}

echo "\nNormalisasi header:\n";
$header = [];
foreach ($rawHeader as $idx => $h) {
    if (empty($h)) {
        $header[$idx] = null;
        continue;
    }
    $normalized = strtolower(trim($h));
    $normalized = str_replace(["\t", "\n", "\r"], '', $normalized);
    $normalized = preg_replace('/\s+/', '', $normalized);
    $header[$idx] = $normalized;
    echo "  [$idx] '$h' → '$normalized'\n";
}

echo "\n=== TESTING FUZZY MATCHING ===\n\n";

$findColumn = function($searchTerms) use ($header) {
    $searchTerms = (array) $searchTerms;
    foreach ($searchTerms as $term) {
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

$colName = $findColumn(['nama perangkat', 'namaperangkat', 'nama_perangkat', 'nama barang']);
$colQty = $findColumn(['qty', 'jumlah', 'quantity']);
$colMerk = $findColumn(['merk/type', 'merktype', 'merk', 'type', 'jenis']);
$colNo = $findColumn(['no', 'nomor', 'nomor_barang']);
$colStok = $findColumn(['sisa stok', 'sisastok', 'stok', 'sisa_stok']);
$colKet = $findColumn(['keterangan', 'ket']);

echo "Deteksi kolom:\n";
echo "  NAMA PERANGKAT: " . ($colName !== null ? "Kolom $colName ✓" : "TIDAK DITEMUKAN ✗") . "\n";
echo "  QTY: " . ($colQty !== null ? "Kolom $colQty ✓" : "TIDAK DITEMUKAN ✗") . "\n";
echo "  MERK/TYPE: " . ($colMerk !== null ? "Kolom $colMerk ✓" : "TIDAK DITEMUKAN ✗") . "\n";
echo "  NO: " . ($colNo !== null ? "Kolom $colNo ✓" : "TIDAK DITEMUKAN ✗") . "\n";
echo "  SISA STOK: " . ($colStok !== null ? "Kolom $colStok ✓" : "TIDAK DITEMUKAN ✗") . "\n";
echo "  KETERANGAN: " . ($colKet !== null ? "Kolom $colKet ✓" : "TIDAK DITEMUKAN ✗") . "\n";

if ($colName === null || $colQty === null) {
    echo "\n❌ GAGAL: Kolom wajib tidak ditemukan!\n";
    exit(1);
}

echo "\n✓ Semua kolom wajib ditemukan!\n";

echo "\n=== TESTING DATA EXTRACTION ===\n\n";

for ($i = 1; $i < count($rows); $i++) {
    $row = $rows[$i];
    
    if (empty(array_filter($row))) continue;
    
    $nama = isset($row[$colName]) ? trim($row[$colName]) : null;
    $qty = isset($row[$colQty]) ? trim($row[$colQty]) : null;
    $merk = isset($row[$colMerk]) ? trim($row[$colMerk]) : null;
    $stok = isset($row[$colStok]) ? trim($row[$colStok]) : null;
    $ket = isset($row[$colKet]) ? trim($row[$colKet]) : null;
    
    echo "Baris " . $i . ":\n";
    echo "  nama: '$nama'\n";
    echo "  qty: '$qty' → ";
    
    $qty_num = is_numeric($qty) ? intval($qty) : 0;
    if ($qty_num <= 0) {
        echo "INVALID ✗\n";
        continue;
    }
    echo $qty_num . " ✓\n";
    
    echo "  merk: '$merk'\n";
    echo "  stok: '$stok'\n";
    echo "  ket: '$ket'\n";
    echo "  → Data VALID untuk import ✓\n\n";
}

echo "\n=== TEST SELESAI ===\n";
echo "Jika semua menunjukkan ✓, berarti file Anda bisa diimport!\n";
