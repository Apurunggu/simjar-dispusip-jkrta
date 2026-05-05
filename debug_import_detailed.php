<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BarangMasuk;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

echo "=== DETAILED IMPORT DEBUG ===\n\n";

$user = User::where('email', 'admin@simjar.test')->first();
Auth::login($user);

echo "User: {$user->name}, Cabang ID: {$user->cabang_id}\n\n";

// Create test file
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'namaperangkat');
$sheet->setCellValue('B1', 'qty');
$sheet->setCellValue('A2', 'Router Debug Test');
$sheet->setCellValue('B2', 10);

$testFile = 'storage/test_debug.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($testFile);

echo "Test file created: $testFile\n\n";

// Read file manually
echo "Reading file content:\n";
$readSheet = IOFactory::load($testFile);
$rows = $readSheet->getActiveSheet()->toArray();

echo "Row 0 (header): " . json_encode($rows[0]) . "\n";
echo "Row 1 (data): " . json_encode($rows[1]) . "\n\n";

// Simulate import logic manually
echo "Simulating import logic:\n";

$header = array_map(function($h) {
    return strtolower(str_replace([' ', '\t', '\n', '\r'], '', trim($h)));
}, $rows[0] ?? []);

echo "Normalized headers: " . json_encode($header) . "\n\n";

$mapField = [
    'no' => 'nomor_barang',
    'namaperangkat' => 'nama_barang',
    'merk/type' => 'kategori',
    'qty' => 'jumlah',
    'sisastok' => 'stok',
    'keterangan' => 'keterangan',
];

$requiredCols = ['namaperangkat', 'qty'];

echo "Checking required columns:\n";
foreach ($requiredCols as $col) {
    $found = in_array($col, $header) ? 'YES' : 'NO';
    echo "  - $col: $found\n";
}

$row = $rows[1];
$data = [];
$rowAssoc = [];

foreach ($header as $idx => $col) {
    $rowAssoc[$col] = $row[$idx] ?? null;
}

echo "\nRow associative array:\n";
foreach ($rowAssoc as $k => $v) {
    echo "  $k: " . json_encode($v) . "\n";
}

foreach ($mapField as $excelCol => $dbCol) {
    $data[$dbCol] = isset($rowAssoc[$excelCol]) ? trim($rowAssoc[$excelCol]) : null;
}

$data['tanggal_masuk'] = date('Y-m-d');
$data['cabang_id'] = $user->cabang_id;

echo "\nFinal data for insert/update:\n";
foreach ($data as $k => $v) {
    echo "  $k: " . json_encode($v) . "\n";
}

echo "\nValidation check:\n";
echo "  nama_barang empty? " . (empty($data['nama_barang']) ? 'YES' : 'NO') . "\n";
echo "  jumlah empty? " . (empty($data['jumlah']) ? 'YES' : 'NO') . "\n";

if (!empty($data['nama_barang']) && !empty($data['jumlah'])) {
    echo "\n✓ Validation passed, would insert/update\n";
    echo "  updateOrCreate(['nomor_barang' => " . json_encode($data['nomor_barang']) . "], ...)\n";
    
    // Try the actual operation
    echo "\n  Attempting actual updateOrCreate...\n";
    try {
        $result = BarangMasuk::updateOrCreate(
            ['nomor_barang' => $data['nomor_barang']],
            $data
        );
        echo "  ✓ Operation successful\n";
        echo "  Result ID: {$result->id}\n";
        echo "  Result: {$result->nama_barang} (Jumlah: {$result->jumlah})\n";
    } catch (\Exception $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n";
    }
} else {
    echo "\n✗ Validation failed, would skip this row\n";
}

?>
