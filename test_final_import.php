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

echo "=== FINAL TEST IMPORT WITH ALL FIXES ===\n\n";

// Setup
$user = User::where('email', 'admin@simjar.test')->first();
Auth::login($user);

// Create test Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Just 3 columns - minimal required
$sheet->setCellValue('A1', 'namaperangkat');
$sheet->setCellValue('B1', 'qty');
$sheet->setCellValue('C1', 'merk/type');

// Test data - NO nomor, NO stok
$sheet->setCellValue('A2', 'Switch Manageable Final');
$sheet->setCellValue('B2', 20);
$sheet->setCellValue('C2', 'Netgear');

$testFile = 'storage/test_final_import.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($testFile);

echo "✓ Test file created with minimal columns (only: namaperangkat, qty, merk/type)\n\n";

// Import
$file = new UploadedFile($testFile, 'test_final_import.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
$request = new \Illuminate\Http\Request();
$request->files->set('file', $file);
$request->setMethod('POST');

$controller = new BarangMasukController();

echo "Running import with updated controller...\n";
try {
    $response = $controller->import($request);
    echo "✓ Import executed (HTTP " . $response->getStatusCode() . ")\n\n";
} catch (\Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
    exit;
}

// Check result
echo "=== VERIFICATION ===\n\n";
$item = BarangMasuk::where('nama_barang', 'Switch Manageable Final')->first();

if ($item) {
    echo "✅ SUCCESS - Import bekerja!\n\n";
    echo "Data yang diimport:\n";
    echo "  Nama: {$item->nama_barang}\n";
    echo "  Nomor: {$item->nomor_barang} (auto-generated)\n";
    echo "  Kategori: {$item->kategori} (provided)\n";
    echo "  Jumlah: {$item->jumlah}\n";
    echo "  Stok: {$item->stok} (set to jumlah)\n";
    echo "  Cabang ID: {$item->cabang_id}\n";
    echo "  Tanggal: {$item->tanggal_masuk}\n";
    
    // Test 2: Import again
    echo "\n\nTesting duplicate detection (import same data again)...\n";
    
    $file2 = new UploadedFile($testFile, 'test_final_import.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
    $request2 = new \Illuminate\Http\Request();
    $request2->files->set('file', $file2);
    $request2->setMethod('POST');
    
    $countBefore = BarangMasuk::where('nama_barang', 'Switch Manageable Final')->count();
    
    try {
        $response2 = $controller->import($request2);
        
        $countAfter = BarangMasuk::where('nama_barang', 'Switch Manageable Final')->count();
        
        if ($countBefore == $countAfter) {
            echo "✅ GOOD - No duplicate (updated instead of inserting new)\n";
        } else {
            echo "⚠ WARNING - Possible duplicate created\n";
        }
    } catch (\Exception $e) {
        echo "✗ Error on second import: " . $e->getMessage() . "\n";
    }
    
} else {
    echo "❌ FAILED - Data tidak ditemukan di database\n";
    echo "Barang dengan nama 'Switch Manageable Final' tidak ada.\n";
}

?>
