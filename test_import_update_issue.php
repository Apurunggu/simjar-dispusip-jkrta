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

echo "=== TEST IMPORT UPDATE MECHANISM ===\n\n";

// 1. Get test user
$user = User::where('email', 'admin@simjar.test')->first();
Auth::login($user);

// 2. Check existing data
echo "1. CHECKING EXISTING DATA\n";
$existingItem = BarangMasuk::where('nama_barang', 'Router Update Test')->first();
if ($existingItem) {
    echo "   Found existing: {$existingItem->nama_barang}\n";
    echo "   - ID: {$existingItem->id}\n";
    echo "   - Nomor: {$existingItem->nomor_barang}\n";
    echo "   - Jumlah: {$existingItem->jumlah}\n";
    echo "   - Stok: {$existingItem->stok}\n";
    $originalJumlah = $existingItem->jumlah;
} else {
    echo "   No existing data found\n";
    $originalJumlah = 0;
}

// 3. Create test Excel with SAME nomor_barang but DIFFERENT jumlah
echo "\n2. CREATING TEST EXCEL FILE\n";
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

// Headers
$sheet->setCellValue('A1', 'no');
$sheet->setCellValue('B1', 'namaperangkat');
$sheet->setCellValue('C1', 'merk/type');
$sheet->setCellValue('D1', 'qty');
$sheet->setCellValue('E1', 'sisastok');
$sheet->setCellValue('F1', 'keterangan');

// Data - use same nomor if updating, different if new
$nomor = 'ROUT-UPDATE-001';
$sheet->setCellValue('A2', $nomor);
$sheet->setCellValue('B2', 'Router Update Test');
$sheet->setCellValue('C2', 'TP-Link');
$sheet->setCellValue('D2', 999); // CHANGED from original
$sheet->setCellValue('E2', 999); // CHANGED from original
$sheet->setCellValue('F2', 'Diupdate via import test');

$testFile = 'storage/test_update_import.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($testFile);

echo "   ✓ File created: $testFile\n";
echo "   ✓ Nomor barang: $nomor\n";
echo "   ✓ Jumlah dalam file: 999\n";

// 4. Simulate import
echo "\n3. SIMULATING IMPORT\n";
$file = new UploadedFile($testFile, 'test_update_import.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
$request = new \Illuminate\Http\Request();
$request->files->set('file', $file);

$controller = new BarangMasukController();
try {
    $response = $controller->import($request);
    echo "   ✓ Import executed\n";
    echo "   ✓ Response code: " . $response->getStatusCode() . "\n";
} catch (\Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// 5. Check result
echo "\n4. CHECKING RESULT AFTER IMPORT\n";
$updatedItem = BarangMasuk::where('nomor_barang', $nomor)->first();
if ($updatedItem) {
    echo "   ✓ Item found\n";
    echo "   - Nama: {$updatedItem->nama_barang}\n";
    echo "   - Jumlah: {$updatedItem->jumlah} (sebelumnya: $originalJumlah)\n";
    echo "   - Stok: {$updatedItem->stok}\n";
    echo "   - Keterangan: {$updatedItem->keterangan}\n";
    
    if ($updatedItem->jumlah == 999) {
        echo "\n   ✅ UPDATE BERHASIL - Data diupdate dengan nilai baru!\n";
    } else {
        echo "\n   ❌ UPDATE GAGAL - Data tidak berubah\n";
    }
} else {
    echo "   ✗ Item tidak ditemukan setelah import\n";
}

// 6. Test duplicate detection
echo "\n5. TESTING DUPLICATE HANDLING\n";
$countBefore = BarangMasuk::where('nomor_barang', $nomor)->count();
echo "   Item dengan nomor '$nomor': " . $countBefore . "\n";

if ($countBefore == 1) {
    echo "   ✅ BENAR - Hanya 1 record (update, tidak insert duplikat)\n";
} else if ($countBefore > 1) {
    echo "   ❌ SALAH - Ada " . $countBefore . " records (duplikat!)\n";
} else {
    echo "   ⚠ Tidak ada record\n";
}

?>
