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

echo "=== SIMULATING IMPORT WITH AUTO-NOMOR LOGIC ===\n\n";

$user = User::where('email', 'admin@simjar.test')->first();
Auth::login($user);

// Create test file
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'namaperangkat');
$sheet->setCellValue('B1', 'qty');
$sheet->setCellValue('C1', 'merk/type');
$sheet->setCellValue('A2', 'Router Auto Final Test');
$sheet->setCellValue('B2', 15);
$sheet->setCellValue('C2', 'Cisco');

$testFile = 'storage/test_auto_final.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($testFile);

// Read and process like controller does
$spreadsheet = IOFactory::load($testFile);
$sheet = $spreadsheet->getActiveSheet();
$rows = $sheet->toArray();

echo "File content:\n";
echo "Headers: " . implode(', ', $rows[0]) . "\n";
echo "Data: " . implode(', ', $rows[1]) . "\n\n";

// Normalize headers
$header = array_map(function($h) {
    return strtolower(str_replace([' ', '\t', '\n', '\r'], '', trim($h)));
}, $rows[0] ?? []);

$mapField = [
    'no' => 'nomor_barang',
    'namaperangkat' => 'nama_barang',
    'merk/type' => 'kategori',
    'qty' => 'jumlah',
    'sisastok' => 'stok',
    'keterangan' => 'keterangan',
];

$userCabangId = $user->cabang_id;
$imported = 0;
$errors = [];

for ($i = 1; $i < count($rows); $i++) {
    $row = $rows[$i];
    if (empty(array_filter($row))) {
        echo "Row $i: Skipped (empty)\n";
        continue;
    }

    $data = [];
    try {
        // Map
        $rowAssoc = [];
        foreach ($header as $idx => $col) {
            $rowAssoc[$col] = $row[$idx] ?? null;
        }
        
        foreach ($mapField as $excelCol => $dbCol) {
            $data[$dbCol] = isset($rowAssoc[$excelCol]) ? trim($rowAssoc[$excelCol]) : null;
        }
        
        $data['tanggal_masuk'] = date('Y-m-d');
        $data['cabang_id'] = $userCabangId;

        echo "Row $i mapped data:\n";
        foreach ($data as $k => $v) {
            echo "  $k: " . json_encode($v) . "\n";
        }

        // Validate
        if (empty($data['nama_barang']) || empty($data['jumlah'])) {
            echo "  ⚠ Validation failed - skipping\n\n";
            continue;
        }

        // Generate nomor_barang if empty
        if (empty($data['nomor_barang'])) {
            $kategoriPrefix = !empty($data['kategori']) 
                ? strtoupper(substr($data['kategori'], 0, 3))
                : 'UNK';
            $nextId = BarangMasuk::max('id') + 1;
            $data['nomor_barang'] = 'BRG-' . $kategoriPrefix . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            echo "  Generated nomor_barang: {$data['nomor_barang']}\n";
        }

        echo "  Performing updateOrCreate...\n";
        $result = BarangMasuk::updateOrCreate(
            ['nomor_barang' => $data['nomor_barang']],
            $data
        );
        
        echo "  ✓ Success! ID: {$result->id}\n\n";
        $imported++;
        
    } catch (\Throwable $e) {
        echo "  ✗ Error: " . $e->getMessage() . "\n\n";
        $errors[] = 'Row ' . ($i+1) . ': ' . $e->getMessage();
    }
}

echo "=== RESULT ===\n";
echo "Imported: $imported\n";
if ($errors) {
    echo "Errors: " . count($errors) . "\n";
    foreach ($errors as $err) {
        echo "  - $err\n";
    }
}

// Verify
$check = BarangMasuk::where('nama_barang', 'Router Auto Final Test')->first();
if ($check) {
    echo "\n✅ Data successfully created:\n";
    echo "  ID: {$check->id}\n";
    echo "  Nama: {$check->nama_barang}\n";
    echo "  Nomor: {$check->nomor_barang}\n";
    echo "  Jumlah: {$check->jumlah}\n";
}

?>
