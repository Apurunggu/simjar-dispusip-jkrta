<?php
/**
 * Analisis Struktur File Excel
 */
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use PhpOffice\PhpSpreadsheet\IOFactory;

$excelFile = "C:\\Users\\gepen\\Downloads\\DATA DUKUNG APLIKASI STOK BARANG.xlsx";

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║           ANALISIS STRUKTUR FILE EXCEL                     ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

try {
    $spreadsheet = IOFactory::load($excelFile);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();
    
    echo "📊 INFO FILE:\n";
    echo "   Total baris: " . count($rows) . "\n";
    echo "   Total kolom: " . count($rows[0] ?? []) . "\n";
    echo "   Sheet names: " . implode(", ", $spreadsheet->getSheetNames()) . "\n\n";
    
    // Tampilkan baris 1-10 untuk lihat struktur
    echo "📋 BARIS 1-10 (UNTUK MELIHAT STRUKTUR):\n";
    echo str_repeat("─", 120) . "\n";
    for ($i = 0; $i < min(10, count($rows)); $i++) {
        echo "Baris " . ($i+1) . ": ";
        $row = $rows[$i];
        for ($j = 0; $j < min(8, count($row)); $j++) {
            $val = $row[$j] ?? '';
            $val = strlen($val) > 20 ? substr($val, 0, 20) . "..." : $val;
            echo sprintf("[%s] ", $val);
        }
        echo "\n";
    }
    echo str_repeat("─", 120) . "\n\n";
    
    // Cari di mana header sebenarnya
    echo "🔍 MENCARI HEADER...\n";
    for ($i = 0; $i < min(20, count($rows)); $i++) {
        $row = $rows[$i];
        $rowStr = implode("|", $row);
        
        // Cek apakah baris ini terlihat seperti header (contains "NAMA", "QTY", dsb)
        if (stripos($rowStr, 'nama') !== false || stripos($rowStr, 'perangkat') !== false) {
            echo "   ✓ Baris " . ($i+1) . " MUNGKIN HEADER:\n";
            echo "     Kolom: " . implode(" | ", array_slice($row, 0, 8)) . "\n\n";
        }
    }
    
    // Cek format kolom
    echo "🔎 DETEKSI KOLOM DALAM SETIAP BARIS:\n";
    echo str_repeat("─", 60) . "\n";
    for ($i = 0; $i < count($rows); $i++) {
        $row = $rows[$i];
        $found = false;
        
        // Cek apakah baris ini punya "NAMA PERANGKAT" atau variasinya
        foreach ($row as $idx => $cell) {
            if (stripos($cell, 'nama') !== false && stripos($cell, 'perangkat') !== false) {
                echo "Baris " . ($i+1) . ", Kolom " . ($idx+1) . ": NAMA PERANGKAT\n";
                $found = true;
                break;
            }
        }
    }
    echo str_repeat("─", 60) . "\n";
    
} catch (\Throwable $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n";
?>
