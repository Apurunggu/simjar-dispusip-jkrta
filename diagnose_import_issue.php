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

echo "=== FINDING THE IMPORT UPDATE ISSUE ===\n\n";

$user = User::where('email', 'admin@simjar.test')->first();
Auth::login($user);

// Test Scenario 1: Import TANPA nomor_barang (hanya nama dan qty)
echo "SCENARIO 1: Import tanpa kolom 'no' (nomor_barang)\n";
echo "=========================================\n\n";

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'namaperangkat');
$sheet->setCellValue('B1', 'qty');
$sheet->setCellValue('C1', 'merk/type');
$sheet->setCellValue('A2', 'Switch Test No Nomor');
$sheet->setCellValue('B2', 5);
$sheet->setCellValue('C2', 'Generic');

$testFile1 = 'storage/test_no_nomor.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($testFile1);

$beforeCount = BarangMasuk::where('nama_barang', 'Switch Test No Nomor')->count();
echo "Before import: " . $beforeCount . " items\n";

$file = new UploadedFile($testFile1, 'test_no_nomor.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
$request = new \Illuminate\Http\Request();
$request->files->set('file', $file);

$controller = new BarangMasukController();
$response = $controller->import($request);

$afterCount1 = BarangMasuk::where('nama_barang', 'Switch Test No Nomor')->count();
echo "After import #1: " . $afterCount1 . " items\n";

// Import lagi dengan data sama
echo "\nImport LAGI dengan data yang SAMA:\n";
$file = new UploadedFile($testFile1, 'test_no_nomor.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);
$request = new \Illuminate\Http\Request();
$request->files->set('file', $file);
$response = $controller->import($request);

$afterCount2 = BarangMasuk::where('nama_barang', 'Switch Test No Nomor')->count();
echo "After import #2: " . $afterCount2 . " items\n";

if ($afterCount1 == $afterCount2) {
    echo "✅ BAIK - Data tidak duplikat (update atau skip)\n";
} else if ($afterCount2 > $afterCount1) {
    echo "❌ MASALAH - Data duplikat! (" . ($afterCount2 - $afterCount1) . " record baru ditambah)\n";
}

// Test Scenario 2: Cek apa yang terjadi dengan nomor_barang kosong
echo "\n\nSCENARIO 2: Checking updateOrCreate behavior with NULL nomor_barang\n";
echo "=====================================================================\n\n";

$items = BarangMasuk::where('nama_barang', 'Switch Test No Nomor')->get();
echo "All items dengan nama 'Switch Test No Nomor':\n";
foreach ($items as $item) {
    echo "- ID: {$item->id}, Nomor: " . ($item->nomor_barang ?? 'NULL') . ", Jumlah: {$item->jumlah}\n";
}

if ($items->count() > 1) {
    echo "\n⚠️ PROBLEM FOUND: Multiple records dengan nomor_barang NULL!\n";
    echo "   Ini terjadi karena updateOrCreate(['nomor_barang' => NULL], ...)\n";
    echo "   setiap kali akan INSERT baru, bukan UPDATE\n";
}

?>
