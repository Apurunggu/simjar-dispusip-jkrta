<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BarangMasuk;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\BarangMasukController;

echo "=== TESTING IMPORT WITH AUTO NOMOR_BARANG ===\n\n";

$user = User::where('email', 'admin@simjar.test')->first();
Auth::login($user);

// Create test file WITHOUT nomor_barang column
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'namaperangkat');
$sheet->setCellValue('B1', 'qty');
$sheet->setCellValue('C1', 'merk/type');
$sheet->setCellValue('A2', 'Router Auto Test');
$sheet->setCellValue('B2', 5);
$sheet->setCellValue('C2', 'TP-Link');

$testFile = 'storage/test_auto_nomor.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($testFile);

echo "Test file created (WITHOUT nomor_barang column)\n\n";

// Import using controller
$file = new UploadedFile($testFile, 'test_auto_nomor.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
$request = new \Illuminate\Http\Request();
$request->files->set('file', $file);

$controller = new BarangMasukController();

echo "Running import...\n";
try {
    $response = $controller->import($request);
    echo "✓ Import completed with status: " . $response->getStatusCode() . "\n\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n\n";
}

// Check if data was created
echo "Checking results:\n";
$item = BarangMasuk::where('nama_barang', 'Router Auto Test')->first();

if ($item) {
    echo "✅ SUCCESS - Data berhasil diimport!\n";
    echo "  Nama: {$item->nama_barang}\n";
    echo "  Nomor (auto-generated): {$item->nomor_barang}\n";
    echo "  Jumlah: {$item->jumlah}\n";
    echo "  Kategori: {$item->kategori}\n";
    echo "  Cabang ID: {$item->cabang_id}\n";
    echo "  Created: {$item->created_at}\n";
} else {
    echo "❌ FAILED - Data tidak ditemukan\n";
}

// Test 2: Update dengan import yang sama
echo "\n\nTesting import again with SAME data...\n";

$file2 = new UploadedFile($testFile, 'test_auto_nomor.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
$request2 = new \Illuminate\Http\Request();
$request2->files->set('file', $file2);

$beforeUpdate = BarangMasuk::where('nama_barang', 'Router Auto Test')->count();
echo "Count before second import: $beforeUpdate\n";

try {
    $response2 = $controller->import($request2);
    echo "✓ Second import completed\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
}

$afterUpdate = BarangMasuk::where('nama_barang', 'Router Auto Test')->count();
echo "Count after second import: $afterUpdate\n";

if ($beforeUpdate == $afterUpdate) {
    echo "✅ GOOD - No duplicate created (update detected)\n";
} else {
    echo "⚠ Duplicate may have been created\n";
}

?>
