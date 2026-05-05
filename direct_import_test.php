<?php
/**
 * Direct Import Test - Simulasi upload file Excel
 * Script ini membaca file Excel dan langsung import ke database
 */
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\BarangMasuk;
use App\Models\User;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║        DIRECT IMPORT - SIMULASI UPLOAD FILE EXCEL         ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Cari file Excel di folder mana saja
$possiblePaths = [
    "C:\\xampp\\htdocs\\Simjar_dispusip\\DATA DUKUNG APLIKASI STOK BARANG.xlsx",
    "C:\\xampp\\htdocs\\Simjar_dispusip\\Downloads\\DATA DUKUNG APLIKASI STOK BARANG.xlsx",
    "C:\\Users\\gepen\\Downloads\\DATA DUKUNG APLIKASI STOK BARANG.xlsx",
];

$excelFile = null;
foreach ($possiblePaths as $path) {
    if (file_exists($path)) {
        $excelFile = $path;
        break;
    }
}

// Cari file .xlsx terbaru di sistem
if (!$excelFile) {
    echo "🔍 Mencari file Excel terbaru...\n";
    exec('dir /b /s "C:\\xampp\\htdocs\\"  "*.xlsx" 2>nul', $output);
    if (!empty($output)) {
        $excelFile = trim($output[0]);
        echo "✓ File ditemukan: " . basename($excelFile) . "\n\n";
    }
}

if (!$excelFile || !file_exists($excelFile)) {
    echo "❌ File Excel tidak ditemukan!\n";
    echo "Silakan tempatkan file di salah satu lokasi:\n";
    foreach ($possiblePaths as $path) {
        echo "  - " . $path . "\n";
    }
    echo "\n";
    exit;
}

echo "✓ File Excel: " . basename($excelFile) . "\n";
echo "  Ukuran: " . number_format(filesize($excelFile) / 1024, 2) . " KB\n";
echo "  Path: " . $excelFile . "\n\n";

// User yang import
$userCabangId = 1; // Super Admin - DISPUSIP Pusat
echo "👤 User Import: Super Admin (Cabang ID: 1)\n\n";

try {
    echo "📂 Loading Excel file...\n";
    $spreadsheet = IOFactory::load($excelFile);
    $sheet = $spreadsheet->getActiveSheet();
    $rows = $sheet->toArray();
    
    echo "✓ Berhasil membaca " . count($rows) . " baris\n\n";
    
    if (count($rows) < 2) {
        echo "❌ File kosong atau hanya header\n";
        exit;
    }
    
    // Cari baris header
    $headerRowIndex = 0;
    for ($h = 0; $h < min(10, count($rows)); $h++) {
        $rowStr = implode("|", $rows[$h] ?? []);
        if ((stripos($rowStr, 'nama') !== false || stripos($rowStr, 'perangkat') !== false) && 
            (stripos($rowStr, 'qty') !== false || stripos($rowStr, 'jumlah') !== false)) {
            $headerRowIndex = $h;
            break;
        }
    }
    
    echo "✓ Header ditemukan di baris " . ($headerRowIndex + 1) . "\n\n";
    
    // Normalisasi header
    $rawHeader = $rows[$headerRowIndex] ?? [];
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
    
    // Cari kolom
    $findColumn = function($searchTerms) use ($header) {
        $searchTerms = (array) $searchTerms;
        foreach ($searchTerms as $term) {
            $termNormalized = strtolower(trim($term));
            $termNormalized = preg_replace('/\s+/', '', $termNormalized);
            foreach ($header as $idx => $colName) {
                if ($colName === null) continue;
                if ($colName === $termNormalized || strpos($colName, $termNormalized) !== false) {
                    return $idx;
                }
            }
        }
        return null;
    };
    
    $colName = $findColumn(['namaperangkat', 'namabarang', 'namaperengkat']);
    $colQty = $findColumn(['qty', 'jumlah']);
    $colMerk = $findColumn(['merktype', 'merk', 'type']);
    $colNo = $findColumn(['no', 'nomor']);
    $colStok = $findColumn(['sisastok', 'stok', 'sisa']);
    $colKet = $findColumn(['keterangan', 'ket']);
    
    echo "🔍 Deteksi Kolom:\n";
    echo "   Nama Perangkat: " . ($colName !== null ? "✓ Kolom " . $colName : "❌ TIDAK DITEMUKAN") . "\n";
    echo "   QTY: " . ($colQty !== null ? "✓ Kolom " . $colQty : "❌ TIDAK DITEMUKAN") . "\n";
    echo "   Merk/Type: " . ($colMerk !== null ? "✓ Kolom " . $colMerk : "❌") . "\n";
    echo "   No: " . ($colNo !== null ? "✓ Kolom " . $colNo : "❌") . "\n\n";
    
    if ($colName === null || $colQty === null) {
        echo "❌ Kolom wajib tidak lengkap!\n";
        exit;
    }
    
    // Process import
    echo "⚙️  Processing data...\n";
    $imported = 0;
    $updated = 0;
    $errors = [];
    
    for ($i = 1; $i < count($rows); $i++) {
        $row = $rows[$i];
        
        if (empty(array_filter($row))) continue;
        
        try {
            $nama = isset($row[$colName]) ? trim($row[$colName]) : null;
            $qty = isset($row[$colQty]) ? trim($row[$colQty]) : null;
            $merk = isset($row[$colMerk]) ? trim($row[$colMerk]) : null;
            $no = isset($row[$colNo]) ? trim($row[$colNo]) : null;
            $stok = isset($row[$colStok]) ? trim($row[$colStok]) : null;
            $ket = isset($row[$colKet]) ? trim($row[$colKet]) : null;
            
            if (empty($nama)) continue;
            
            $qty = is_numeric($qty) ? intval($qty) : 0;
            if ($qty <= 0) continue;
            
            $kategori = !empty($merk) ? $merk : 'Uncategorized';
            $stok = !empty($stok) && is_numeric($stok) ? intval($stok) : $qty;
            
            // Cek duplikat
            $existing = BarangMasuk::where('nama_barang', $nama)
                ->where('kategori', $kategori)
                ->where('cabang_id', $userCabangId)
                ->first();
            
            $data = [
                'nama_barang' => $nama,
                'kategori' => $kategori,
                'jumlah' => $qty,
                'stok' => $stok,
                'keterangan' => $ket,
                'tanggal_masuk' => date('Y-m-d'),
                'cabang_id' => $userCabangId,
            ];
            
            if ($existing) {
                $existing->update($data);
                $updated++;
            } else {
                if (empty($no)) {
                    $no = 'BRG-' . strtoupper(substr($kategori, 0, 3)) . '-' . str_pad(rand(1000, 9999), 4, '0', STR_PAD_LEFT);
                }
                $data['nomor_barang'] = $no;
                BarangMasuk::create($data);
                $imported++;
            }
            
        } catch (\Throwable $e) {
            $errors[] = 'Baris ' . ($i+1) . ': ' . $e->getMessage();
        }
    }
    
    echo "   Baris diproses: " . ($i - 1) . "\n";
    echo "   Data baru: " . $imported . " ✓\n";
    echo "   Data update: " . $updated . " ✓\n";
    echo "   Error: " . count($errors) . "\n\n";
    
    if (count($errors) > 0) {
        echo "⚠️  Error details:\n";
        foreach (array_slice($errors, 0, 5) as $err) {
            echo "   - " . $err . "\n";
        }
        echo "\n";
    }
    
    // Verifikasi
    echo "✅ VERIFIKASI HASIL:\n";
    $totalNow = BarangMasuk::where('cabang_id', 1)->count();
    $qtyNow = BarangMasuk::where('cabang_id', 1)->sum('jumlah');
    echo "   Total barang Cabang 1: " . $totalNow . " item\n";
    echo "   Total qty: " . number_format($qtyNow) . "\n\n";
    
    if ($imported > 0 || $updated > 0) {
        echo "🎉 Import BERHASIL!\n";
        echo "   Silakan refresh halaman Barang Masuk untuk melihat data terbaru.\n";
    } else {
        echo "⚠️  Tidak ada data yang di-import.\n";
    }
    
} catch (\Throwable $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    echo "   File: " . $e->getFile() . "\n";
    echo "   Line: " . $e->getLine() . "\n";
}

echo "\n" . str_repeat("─", 60) . "\n";
echo "Test selesai\n\n";
?>
