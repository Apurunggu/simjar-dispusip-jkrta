<?php
require __DIR__ . '/vendor/autoload.php';
require __DIR__ . '/bootstrap/app.php';

use App\Models\BarangMasuk;
use App\Models\User;
use App\Models\Cabang;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

echo "\n=== COMPLETE IMPORT TEST ===\n\n";

// Step 1: Setup
echo "STEP 1: Setup\n";
echo "- Creating sample Excel file\n";

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Create test data
$headers = ['namaperangkat', 'qty', 'merk/type', 'no', 'sisastok', 'keterangan'];
$sheet->fromArray($headers, NULL, 'A1');

$testData = [
    ['Router TESTING', 3, 'Test Merk', '', '', 'Test import'],
    ['Switch TESTING 2', 5, 'Test Merk 2', 'TEST-001', 5, 'Another test'],
    ['AP TESTING', 2, '', '', 2, 'No merk'],
];

foreach ($testData as $i => $row) {
    $sheet->fromArray([$row], NULL, 'A' . ($i + 2));
}

$testFile = storage_path('app/test_import.xlsx');
$writer = new Xlsx($spreadsheet);
$writer->save($testFile);
echo "✓ Test file created: $testFile\n";

// Step 2: Read and display Excel content
echo "\nSTEP 2: Read Excel Content\n";
$readSpreadsheet = IOFactory::load($testFile);
$readSheet = $readSpreadsheet->getActiveSheet();
$rows = $readSheet->toArray();

echo "- Header row:\n";
foreach ($rows[0] as $idx => $h) {
    echo "  [$idx] '$h'\n";
}

echo "- Data rows: " . (count($rows) - 1) . " rows\n";
foreach (array_slice($rows, 1) as $i => $row) {
    echo "  Row " . ($i + 1) . ":\n";
    foreach ($row as $idx => $val) {
        echo "    [$idx] '$val'\n";
    }
}

// Step 3: Test header normalization
echo "\nSTEP 3: Test Header Normalization\n";
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

echo "- Normalized headers:\n";
foreach ($header as $idx => $h) {
    echo "  [$idx] '$h'\n";
}

// Step 4: Test column detection
echo "\nSTEP 4: Test Column Detection\n";

$findColumn = function($searchTerms) use ($header) {
    $searchTerms = (array) $searchTerms;
    foreach ($searchTerms as $term) {
        $term = strtolower(trim($term));
        foreach ($header as $idx => $colName) {
            if ($colName === null) continue;
            if ($colName === $term || strpos($colName, $term) !== false) {
                return $idx;
            }
        }
    }
    return null;
};

$colName = $findColumn(['nama perangkat', 'namaperangkat', 'nama_perangkat']);
$colQty = $findColumn(['qty', 'jumlah', 'quantity']);
$colMerk = $findColumn(['merk/type', 'merktype', 'merk', 'type', 'jenis']);
$colNo = $findColumn(['no', 'nomor', 'nomor_barang']);
$colStok = $findColumn(['sisa stok', 'sisastok', 'stok', 'sisa_stok']);
$colKeterangan = $findColumn(['keterangan', 'ket']);

echo "- Column indices:\n";
echo "  namaperangkat: " . ($colName !== null ? $colName : 'NOT FOUND') . "\n";
echo "  qty: " . ($colQty !== null ? $colQty : 'NOT FOUND') . "\n";
echo "  merk/type: " . ($colMerk !== null ? $colMerk : 'NOT FOUND') . "\n";
echo "  no: " . ($colNo !== null ? $colNo : 'NOT FOUND') . "\n";
echo "  stok: " . ($colStok !== null ? $colStok : 'NOT FOUND') . "\n";
echo "  keterangan: " . ($colKeterangan !== null ? $colKeterangan : 'NOT FOUND') . "\n";

// Step 5: Test data extraction
echo "\nSTEP 5: Test Data Extraction\n";

if ($colName !== null && $colQty !== null) {
    foreach (array_slice($rows, 1) as $i => $row) {
        echo "- Row " . ($i + 1) . ":\n";
        
        $nama = isset($row[$colName]) ? trim($row[$colName]) : null;
        $qty = isset($row[$colQty]) ? trim($row[$colQty]) : null;
        $merk = isset($row[$colMerk]) ? trim($row[$colMerk]) : null;
        $no = isset($row[$colNo]) ? trim($row[$colNo]) : null;
        $stok = isset($row[$colStok]) ? trim($row[$colStok]) : null;
        $ket = isset($row[$colKeterangan]) ? trim($row[$colKeterangan]) : null;
        
        echo "  nama: '$nama'\n";
        echo "  qty: '$qty'\n";
        echo "  merk: '$merk'\n";
        echo "  no: '$no'\n";
        echo "  stok: '$stok'\n";
        echo "  ket: '$ket'\n";
        
        // Validate
        if (empty($nama)) {
            echo "  ✗ SKIP: nama kosong\n";
            continue;
        }
        
        $qty_num = is_numeric($qty) ? intval($qty) : 0;
        if ($qty_num <= 0) {
            echo "  ✗ SKIP: qty tidak valid\n";
            continue;
        }
        
        echo "  ✓ Valid data\n";
    }
} else {
    echo "✗ Required columns not found!\n";
}

// Step 6: Check database setup
echo "\nSTEP 6: Database & User Check\n";

try {
    $firstUser = User::whereNotNull('cabang_id')->first();
    if ($firstUser) {
        echo "✓ Found user with cabang:\n";
        echo "  - Name: " . $firstUser->name . "\n";
        echo "  - Cabang ID: " . $firstUser->cabang_id . "\n";
        echo "  - Roles: " . $firstUser->getRoleNames()->implode(', ') . "\n";
    } else {
        echo "⚠ No users with cabang found\n";
    }
    
    $existingCount = BarangMasuk::count();
    echo "- Current barang_masuk records: $existingCount\n";
} catch (\Exception $e) {
    echo "✗ Database error: " . $e->getMessage() . "\n";
}

echo "\n=== TEST COMPLETE ===\n";
echo "\nNext steps:\n";
echo "1. Run: php create_sample_import_template.php\n";
echo "2. Check: public/sample_import_barang.xlsx\n";
echo "3. Use the template to import data\n";
