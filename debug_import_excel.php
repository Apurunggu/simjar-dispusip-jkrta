<?php
/**
 * Debug script untuk melihat proses import Excel
 * Cek struktur file Excel dan simulasi import
 */
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\BarangMasuk;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║           DEBUG SCRIPT - ANALISIS IMPORT EXCEL             ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Cari file Excel terbaru di upload folder atau temp
$uploadPath = storage_path('app/uploads');
$tmpPath = storage_path('tmp');

echo "🔍 Mencari file Excel terbaru...\n";
echo "   Upload Path: " . $uploadPath . "\n";
echo "   Tmp Path: " . $tmpPath . "\n\n";

// Cari file Excel
$files = [];
if (is_dir($uploadPath)) {
    $uploadFiles = glob($uploadPath . '/*.{xlsx,xls,csv}', GLOB_BRACE);
    $files = array_merge($files, $uploadFiles);
}

if (empty($files)) {
    echo "❌ Tidak ada file Excel ditemukan.\n";
    echo "   Pastikan Anda sudah mengupload file terlebih dahulu.\n\n";
    exit;
}

// Urutkan berdasarkan waktu modifikasi (terbaru pertama)
usort($files, function($a, $b) {
    return filemtime($b) - filemtime($a);
});

$excelFile = $files[0];
echo "✓ File ditemukan: " . basename($excelFile) . "\n";
echo "   Ukuran: " . number_format(filesize($excelFile) / 1024, 2) . " KB\n";
echo "   Waktu: " . date('d/m/Y H:i:s', filemtime($excelFile)) . "\n\n";

// Load Excel file
try {
    $spreadsheet = IOFactory::load($excelFile);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();

    echo "📊 STRUKTUR FILE EXCEL:\n";
    echo "   Jumlah baris: " . count($rows) . "\n";
    echo "   Jumlah kolom: " . count($rows[0] ?? []) . "\n\n";

    // Tampilkan header
    echo "📋 HEADER (Baris 1):\n";
    if (!empty($rows[0])) {
        foreach ($rows[0] as $idx => $col) {
            $col = trim($col);
            echo sprintf("   [%d] %s\n", $idx, $col ?: '(KOSONG)');
        }
    }
    echo "\n";

    // Normalisasi header seperti yang dilakukan controller
    $rawHeader = $rows[0] ?? [];
    $header = [];
    foreach ($rawHeader as $idx => $h) {
        if (empty($h)) {
            $header[$idx] = null;
            continue;
        }
        $normalized = strtolower(trim($h));
        $normalized = str_replace(["\t", "\n", "\r", "  "], '', $normalized);
        $normalized = preg_replace('/\s+/', '', $normalized);
        $header[$idx] = $normalized;
    }

    echo "🔄 HEADER SETELAH NORMALISASI:\n";
    foreach ($header as $idx => $col) {
        echo sprintf("   [%d] %s\n", $idx, $col ?: '(KOSONG)');
    }
    echo "\n";

    // Cek kolom wajib
    $findColumn = function($searchTerms) use ($header) {
        $searchTerms = (array) $searchTerms;
        foreach ($searchTerms as $term) {
            $termNormalized = strtolower(trim($term));
            $termNormalized = preg_replace('/\s+/', '', $termNormalized);
            foreach ($header as $idx => $colName) {
                if ($colName === null) continue;
                if ($colName === $termNormalized) {
                    return $idx;
                }
                if (strpos($colName, $termNormalized) !== false || 
                    strpos($termNormalized, $colName) !== false) {
                    return $idx;
                }
            }
        }
        return null;
    };

    $colName = $findColumn(['nama perangkat', 'namaperangkat', 'nama_perangkat', 'nama barang', 'namabarang']);
    $colQty = $findColumn(['qty', 'jumlah', 'quantity', 'kuantitas']);

    echo "✅ DETEKSI KOLOM WAJIB:\n";
    echo "   Nama Perangkat: " . ($colName !== null ? "✓ Kolom ke-" . $colName : "❌ TIDAK DITEMUKAN") . "\n";
    echo "   QTY/Jumlah: " . ($colQty !== null ? "✓ Kolom ke-" . $colQty : "❌ TIDAK DITEMUKAN") . "\n\n";

    if ($colName === null || $colQty === null) {
        echo "⚠️  MASALAH DITEMUKAN:\n";
        echo "   Kolom wajib tidak ditemukan. Mohon pastikan file Excel memiliki:\n";
        echo "   - Kolom untuk NAMA PERANGKAT (atau variasi nama lainnya)\n";
        echo "   - Kolom untuk QTY/JUMLAH\n\n";
    }

    // Tampilkan data sampel
    echo "📝 SAMPEL DATA (5 baris pertama):\n";
    for ($i = 1; $i <= min(5, count($rows) - 1); $i++) {
        echo "\n   Baris " . ($i + 1) . ":\n";
        foreach ($rows[$i] as $idx => $val) {
            $headerName = $header[$idx] ?? "Kolom-" . $idx;
            echo "      " . $headerName . ": " . (is_null($val) || $val === '' ? '(kosong)' : $val) . "\n";
        }
    }

    // Validasi data
    echo "\n\n🔎 VALIDASI DATA:\n";
    $validRows = 0;
    $invalidRows = 0;
    $emptyRows = 0;

    for ($i = 1; $i < count($rows); $i++) {
        $row = $rows[$i];
        
        if (empty(array_filter($row))) {
            $emptyRows++;
            continue;
        }

        $nama = isset($row[$colName]) ? trim($row[$colName]) : null;
        $qty = isset($row[$colQty]) ? trim($row[$colQty]) : null;

        if (!empty($nama) && is_numeric($qty) && intval($qty) > 0) {
            $validRows++;
        } else {
            $invalidRows++;
        }
    }

    echo "   Baris kosong: " . $emptyRows . "\n";
    echo "   Baris valid: " . $validRows . "\n";
    echo "   Baris invalid: " . $invalidRows . "\n";

    // Cek user & cabang
    $superAdmin = User::where('email', 'admin@simjar.test')->first();
    echo "\n\n👤 INFO USER SUPER ADMIN:\n";
    if ($superAdmin) {
        echo "   Nama: " . $superAdmin->name . "\n";
        echo "   Cabang ID: " . ($superAdmin->cabang_id ?? 'NULL') . "\n";
        echo "   Cabang: " . ($superAdmin->cabang ? $superAdmin->cabang->nama_cabang : 'N/A') . "\n";
    }

    // Simulasi import
    echo "\n\n🚀 SIMULASI IMPORT:\n";
    if ($colName !== null && $colQty !== null) {
        echo "   Status: SIAP UNTUK DIIMPORT\n";
        echo "   Data yang akan ditambah: " . $validRows . " baris\n\n";

        echo "   Rekomendasi:\n";
        echo "   1. Pastikan Anda sudah login dengan akun yang memiliki cabang\n";
        echo "   2. Klik tombol Import di halaman Import Barang Masuk\n";
        echo "   3. File akan diimport dan hasil ditampilkan\n";
    } else {
        echo "   Status: ❌ TIDAK BISA DIIMPORT\n";
        echo "   Alasan: Kolom wajib tidak ditemukan\n\n";
        echo "   Solusi:\n";
        echo "   1. Buat file Excel dengan kolom BENAR\n";
        echo "   2. Gunakan template yang tersedia di halaman Import\n";
        echo "   3. Pastikan nama kolom: 'namaperangkat' dan 'qty'\n";
    }

} catch (\Throwable $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}

echo "\n" . str_repeat("─", 60) . "\n";
echo "Debug selesai\n\n";
?>
