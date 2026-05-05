<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Models\BarangMasuk;
use App\Models\User;
use App\Models\Cabang;
use PhpOffice\PhpSpreadsheet\IOFactory;

echo "=== DEBUG IMPORT BARANG MASUK ===\n\n";

// Cek database connection
try {
    $connection = DB::connection()->getPdo();
    echo "✓ Database connected\n";
} catch (\Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
    exit;
}

// Cek table
$tableExists = DB::select("SELECT 1 FROM information_schema.tables WHERE table_schema = ? AND table_name = ?", 
    [env('DB_DATABASE'), 'barang_masuk']);
echo ($tableExists ? "✓" : "✗") . " Table 'barang_masuk' " . ($tableExists ? "exists" : "not found") . "\n";

// Cek columns in table
$columns = DB::select("SELECT COLUMN_NAME FROM information_schema.COLUMNS WHERE TABLE_NAME = 'barang_masuk' AND TABLE_SCHEMA = ?", 
    [env('DB_DATABASE')]);
echo "\nTable columns:\n";
foreach ($columns as $col) {
    echo "  - " . $col->COLUMN_NAME . "\n";
}

// Cek existing data
$count = BarangMasuk::count();
echo "\nCurrent data in barang_masuk: " . $count . " records\n";

// Cek user & cabang
$users = User::with('cabang')->limit(5)->get();
echo "\nUsers & their cabang:\n";
foreach ($users as $user) {
    echo "  - " . $user->name . " (cabang_id: " . ($user->cabang_id ?? 'NULL') . ")\n";
}

// Cek cabang
$cabangs = Cabang::all();
echo "\nAvailable cabang: " . count($cabangs) . "\n";
foreach ($cabangs as $c) {
    echo "  - ID:" . $c->id . " Name: " . $c->nama . "\n";
}

// Test with sample Excel
$sampleFile = storage_path('app/sample_import.xlsx');
if (file_exists($sampleFile)) {
    echo "\n=== Testing with sample Excel ===\n";
    try {
        $spreadsheet = IOFactory::load($sampleFile);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        
        echo "File found! Rows: " . count($rows) . "\n";
        echo "\nHeader row:\n";
        $header = $rows[0] ?? [];
        foreach ($header as $idx => $h) {
            echo "  [$idx] " . ($h ?? 'EMPTY') . "\n";
        }
        
        echo "\nFirst data row (after header):\n";
        if (isset($rows[1])) {
            foreach ($rows[1] as $idx => $val) {
                echo "  [$idx] " . ($val ?? 'EMPTY') . "\n";
            }
        }
    } catch (\Exception $e) {
        echo "Error loading sample file: " . $e->getMessage() . "\n";
    }
} else {
    echo "\nSample file not found: $sampleFile\n";
}

echo "\n=== END DEBUG ===\n";
