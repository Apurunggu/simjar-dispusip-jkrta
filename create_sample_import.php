<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

echo "=== CREATING SAMPLE IMPORT FILE ===\n\n";

// Create spreadsheet with proper format
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Barang');

// Set column widths
$sheet->getColumnDimension('A')->setWidth(15);
$sheet->getColumnDimension('B')->setWidth(30);
$sheet->getColumnDimension('C')->setWidth(15);
$sheet->getColumnDimension('D')->setWidth(10);
$sheet->getColumnDimension('E')->setWidth(15);
$sheet->getColumnDimension('F')->setWidth(30);

// Add headers
$headers = ['no', 'namaperangkat', 'merk/type', 'qty', 'sisastok', 'keterangan'];
$sheet->fromArray([$headers], null, 'A1');

// Style headers
$headerStyle = [
    'fill' => [
        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
        'startColor' => ['rgb' => '1e3c72'],
    ],
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
    ],
    'alignment' => [
        'horizontal' => 'center',
        'vertical' => 'center',
    ]
];
$sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

// Add sample data
$sampleData = [
    ['R001', 'Router TP-Link TL-WR840N', 'TP-Link', 5, 5, 'Stock baru'],
    ['S001', 'Switch Netgear GS310TP', 'Netgear', 3, 3, 'Pembelian'],
    ['M001', 'Modem ZTE F609', 'ZTE', 2, 2, 'Replacemen'],
    ['K001', 'Kabel UTP Cat5 100m', 'Generic', 10, 10, 'Bulk purchase'],
    ['AP001', 'Access Point Ubiquiti', 'Ubiquiti', 2, 2, 'Warranty included'],
];

$sheet->fromArray($sampleData, null, 'A2');

// Add borders to all cells
$dataRange = 'A1:F' . (count($sampleData) + 1);
$borderStyle = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => 'thin',
            'color' => ['rgb' => 'CCCCCC'],
        ]
    ]
];
$sheet->getStyle($dataRange)->applyFromArray($borderStyle);

// Save file
$filename = 'public/sample_import_barang.xlsx';
$writer = new Xlsx($spreadsheet);
$writer->save($filename);

echo "✓ Sample file created: $filename\n";
echo "  File size: " . filesize($filename) . " bytes\n\n";

// Read it back to show what it contains
echo "=== FILE CONTENTS ===\n";
$readSheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($filename);
$rows = $readSheet->getActiveSheet()->toArray();
echo "Headers: " . implode(', ', $rows[0]) . "\n\n";
echo "Sample data:\n";
for ($i = 1; $i < count($rows); $i++) {
    echo "  Row " . ($i+1) . ": " . implode(' | ', $rows[$i]) . "\n";
}

echo "\n✓ Ready to download and use!\n";

?>
