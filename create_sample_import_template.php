<?php
require __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

echo "Creating sample Excel file...\n";

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Import Template');

// Set column headers
$headers = [
    'A' => 'namaperangkat',
    'B' => 'qty',
    'C' => 'merk/type',
    'D' => 'no',
    'E' => 'sisastok',
    'F' => 'keterangan'
];

// Add headers
$row = 1;
foreach ($headers as $col => $header) {
    $cell = $sheet->getCell($col . $row);
    $cell->setValue($header);
    $cell->getStyle()->getFont()->setBold(true);
}

// Add sample data rows
$sampleData = [
    ['Router TP-Link TL-WR840N', 5, 'TP-Link', 'R001', 5, 'Stock baru'],
    ['Switch Cisco 24 Port', 3, 'Cisco', 'S001', 3, 'Stock baru'],
    ['Access Point Ubiquiti AC-LR', 2, 'Ubiquiti', 'AP001', 2, 'Donasi'],
    ['Modem DSL Huawei EchoLife HG8245', 4, 'Huawei', 'M001', 4, 'Pengadaan'],
    ['Fiber Optic Cable 100m', 10, 'Generic', 'FO001', 10, 'Kabel cadangan'],
];

$row = 2;
foreach ($sampleData as $data) {
    $col = 'A';
    foreach ($data as $value) {
        $cell = $sheet->getCell($col . $row);
        $cell->setValue($value);
        $col++;
    }
    $row++;
}

// Set column widths
$sheet->getColumnDimension('A')->setWidth(35);
$sheet->getColumnDimension('B')->setWidth(12);
$sheet->getColumnDimension('C')->setWidth(20);
$sheet->getColumnDimension('D')->setWidth(12);
$sheet->getColumnDimension('E')->setWidth(12);
$sheet->getColumnDimension('F')->setWidth(25);

// Add instruction sheet
$instructionSheet = $spreadsheet->createSheet();
$instructionSheet->setTitle('Petunjuk');

$instructions = [
    ['PETUNJUK IMPORT BARANG MASUK', ''],
    ['', ''],
    ['KOLOM YANG WAJIB DIISI:', ''],
    ['1. namaperangkat', 'Nama barang / perangkat (WAJIB)'],
    ['2. qty', 'Jumlah barang (WAJIB)'],
    ['', ''],
    ['KOLOM OPSIONAL:', ''],
    ['3. merk/type', 'Merek atau tipe barang'],
    ['4. no', 'Nomor referensi (akan auto-generate jika kosong)'],
    ['5. sisastok', 'Sisa stok (default = qty)'],
    ['6. keterangan', 'Catatan atau keterangan barang'],
    ['', ''],
    ['PANDUAN PENGGUNAAN:', ''],
    ['- Gunakan sheet "Import Template" untuk data', ''],
    ['- Jangan ubah nama kolom header', ''],
    ['- Baris kosong akan diabaikan', ''],
    ['- Duplikat (nama+merk sama) akan di-update', ''],
    ['- Pastikan qty dan sisastok adalah angka', ''],
];

$row = 1;
foreach ($instructions as $inst) {
    $col = 'A';
    foreach ($inst as $value) {
        $sheet->getCell($col . $row)->setValue($value);
        if ($row === 1) {
            $sheet->getCell($col . $row)->getStyle()->getFont()->setBold(true);
            $sheet->getCell($col . $row)->getStyle()->getFont()->setSize(14);
        }
        $col++;
    }
    $row++;
}

$instructionSheet->getColumnDimension('A')->setWidth(40);
$instructionSheet->getColumnDimension('B')->setWidth(50);

// Save file
$publicPath = __DIR__ . '/public/sample_import_barang.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($publicPath);

echo "✓ Sample file created: $publicPath\n";
echo "✓ File contains:\n";
echo "  - Import Template sheet with examples\n";
echo "  - Petunjuk sheet with instructions\n";
