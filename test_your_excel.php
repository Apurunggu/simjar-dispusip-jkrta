<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

echo "=== TEST KOLOM FILE ANDA ===\n\n";

// Ambil file yang user upload
$files = glob(__DIR__ . '/*.xlsx');
$files = array_merge($files, glob(__DIR__ . '/*.xls'));
$files = array_merge($files, glob(__DIR__ . '/*.csv'));

if (empty($files)) {
    echo "Tidak ada file Excel ditemukan. Tempat simpan file di direktori root.\n";
    exit;
}

$latestFile = end($files);
echo "File yang dideteksi: " . basename($latestFile) . "\n\n";

try {
    $spreadsheet = IOFactory::load($latestFile);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();
    
    echo "Kolom Header dari file Anda:\n";
    $rawHeader = $rows[0] ?? [];
    foreach ($rawHeader as $idx => $h) {
        $normalized = strtolower(trim($h ?? ''));
        $normalized = str_replace(["\t", "\n", "\r"], '', $normalized);
        $normalized = preg_replace('/\s+/', '', $normalized);
        echo "  [$idx] ORIGINAL: '$h' → NORMALIZED: '$normalized'\n";
    }
    
    echo "\n\nData baris pertama:\n";
    if (isset($rows[1])) {
        foreach ($rows[1] as $idx => $val) {
            $header = $rawHeader[$idx] ?? 'UNKNOWN';
            echo "  [$idx] $header = '$val'\n";
        }
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
