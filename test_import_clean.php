<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BarangMasuk;

echo "=== CLEANUP & FRESH TEST ===\n\n";

// Delete old test data
echo "Deleting old test data...\n";
$deleted = BarangMasuk::where('nama_barang', 'Switch Manageable Final')->delete();
echo "✓ Deleted $deleted records\n\n";

// Now run import test
echo "Running fresh import test...\n\n";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\BarangMasukController;

$user = User::where('email', 'admin@simjar.test')->first();
Auth::login($user);

// Create test file
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'namaperangkat');
$sheet->setCellValue('B1', 'qty');
$sheet->setCellValue('C1', 'merk/type');
$sheet->setCellValue('A2', 'Modem FRESH TEST');
$sheet->setCellValue('B2', 25);
$sheet->setCellValue('C2', 'ZTE');

$testFile = 'storage/test_fresh.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($testFile);

// Import 1
echo "IMPORT 1:\n";
$file = new UploadedFile($testFile, 'test_fresh.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
$request = new \Illuminate\Http\Request();
$request->files->set('file', $file);
$request->setMethod('POST');

$controller = new BarangMasukController();
$controller->import($request);

$count1 = BarangMasuk::where('nama_barang', 'Modem FRESH TEST')->count();
echo "  Count after import 1: $count1\n";

$item = BarangMasuk::where('nama_barang', 'Modem FRESH TEST')->first();
if ($item) {
    echo "  Jumlah: {$item->jumlah}, Updated: {$item->updated_at}\n";
}

// Import 2 - same data dengan jumlah BERBEDA
echo "\nIMPORT 2 (same name, different qty):\n";

$spreadsheet2 = new Spreadsheet();
$sheet2 = $spreadsheet2->getActiveSheet();
$sheet2->setCellValue('A1', 'namaperangkat');
$sheet2->setCellValue('B1', 'qty');
$sheet2->setCellValue('C1', 'merk/type');
$sheet2->setCellValue('A2', 'Modem FRESH TEST'); // SAMA
$sheet2->setCellValue('B2', 50);  // BERBEDA
$sheet2->setCellValue('C2', 'ZTE');

$testFile2 = 'storage/test_fresh2.xlsx';
$writer2 = new Xlsx($spreadsheet2);
$writer2->save($testFile2);

$file2 = new UploadedFile($testFile2, 'test_fresh2.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
$request2 = new \Illuminate\Http\Request();
$request2->files->set('file', $file2);
$request2->setMethod('POST');

$controller->import($request2);

$count2 = BarangMasuk::where('nama_barang', 'Modem FRESH TEST')->count();
echo "  Count after import 2: $count2\n";

$item = BarangMasuk::where('nama_barang', 'Modem FRESH TEST')->first();
if ($item) {
    echo "  Jumlah: {$item->jumlah} (changed from 25 to 50?)\n";
    echo "  Updated: {$item->updated_at}\n";
}

echo "\n=== RESULT ===\n";
if ($count1 == 1 && $count2 == 1) {
    echo "✅ SUCCESS - Import works correctly!\n";
    echo "  - First import created 1 record\n";
    echo "  - Second import updated same record (no duplicate)\n";
    if ($item->jumlah == 50) {
        echo "  - Data was updated (jumlah changed to 50)\n";
    }
} else {
    echo "❌ FAIL - Duplicate or other issue\n";
}

?>
