<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== EXCEL IMPORT DIAGNOSTICS ===\n\n";

// 1. Check PhpSpreadsheet
echo "1. Checking PhpSpreadsheet installation:\n";
try {
    $spreadsheetClass = '\PhpOffice\PhpSpreadsheet\Spreadsheet';
    if (class_exists($spreadsheetClass)) {
        echo "   ✓ PhpSpreadsheet class found\n";
    } else {
        echo "   ✗ PhpSpreadsheet class NOT found\n";
    }
    
    $ioFactoryClass = '\PhpOffice\PhpSpreadsheet\IOFactory';
    if (class_exists($ioFactoryClass)) {
        echo "   ✓ IOFactory class found\n";
    } else {
        echo "   ✗ IOFactory class NOT found\n";
    }
} catch (\Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// 2. Check imports in controller
echo "\n2. Checking BarangMasukController imports:\n";
$controllerFile = 'app/Http/Controllers/BarangMasukController.php';
$controllerContent = file_get_contents($controllerFile);
if (strpos($controllerContent, 'use PhpOffice\\PhpSpreadsheet\\IOFactory') !== false) {
    echo "   ✓ IOFactory import found in controller\n";
} else {
    echo "   ✗ IOFactory import NOT found in controller\n";
    echo "   ℹ Checking for any IOFactory usage...\n";
    if (strpos($controllerContent, 'IOFactory') !== false) {
        echo "   ⚠ IOFactory is used but not imported!\n";
    }
}

// 3. Check composer.json
echo "\n3. Checking composer.json dependencies:\n";
$composerJson = json_decode(file_get_contents('composer.json'), true);
if (isset($composerJson['require']['phpoffice/phpspreadsheet'])) {
    echo "   ✓ phpoffice/phpspreadsheet version: " . $composerJson['require']['phpoffice/phpspreadsheet'] . "\n";
} else {
    echo "   ✗ phpoffice/phpspreadsheet NOT in composer.json\n";
}

// 4. Test creating test Excel file
echo "\n4. Creating test Excel file:\n";
try {
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'namaperangkat');
    $sheet->setCellValue('B1', 'qty');
    $sheet->setCellValue('C1', 'no');
    $sheet->setCellValue('D1', 'merk/type');
    $sheet->setCellValue('E1', 'sisastok');
    
    $sheet->setCellValue('A2', 'Router Test');
    $sheet->setCellValue('B2', 10);
    $sheet->setCellValue('C2', 'R001');
    $sheet->setCellValue('D2', 'TP-Link');
    $sheet->setCellValue('E2', 10);
    
    $testFile = 'storage/test_import.xlsx';
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
    $writer->save($testFile);
    
    echo "   ✓ Test file created: storage/test_import.xlsx\n";
    echo "   ✓ File size: " . filesize($testFile) . " bytes\n";
    
} catch (\Exception $e) {
    echo "   ✗ Error creating test file: " . $e->getMessage() . "\n";
}

// 5. Test reading Excel file
echo "\n5. Testing Excel file reading:\n";
try {
    $testFile = 'storage/test_import.xlsx';
    if (file_exists($testFile)) {
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($testFile);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();
        
        echo "   ✓ File read successfully\n";
        echo "   ✓ Total rows: " . count($rows) . "\n";
        echo "   ✓ Header: " . implode(', ', $rows[0]) . "\n";
        echo "   ✓ First data row: " . implode(', ', $rows[1]) . "\n";
    } else {
        echo "   ✗ Test file not found\n";
    }
} catch (\Exception $e) {
    echo "   ✗ Error reading file: " . $e->getMessage() . "\n";
}

// 6. Check import route
echo "\n6. Checking import route:\n";
try {
    $routes = \Illuminate\Support\Facades\Route::getRoutes();
    $importRoute = null;
    foreach ($routes as $route) {
        if ($route->uri === 'barang-masuk/import') {
            $importRoute = $route;
            break;
        }
    }
    
    if ($importRoute) {
        echo "   ✓ Import route found\n";
        echo "   ✓ Methods: " . implode(', ', $importRoute->methods) . "\n";
        echo "   ✓ Action: " . ($importRoute->getActionName() ?? 'N/A') . "\n";
    } else {
        echo "   ✗ Import route NOT found\n";
    }
} catch (\Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// 7. Check user cabang
echo "\n7. Checking user cabang setup:\n";
$users = \App\Models\User::limit(3)->get();
foreach ($users as $user) {
    echo "   User: {$user->name}\n";
    echo "   - cabang_id: " . ($user->cabang_id ?? 'NULL') . "\n";
    if (!$user->cabang_id) {
        echo "   ⚠ WARNING: User has no cabang_id - import will fail!\n";
    }
}

echo "\n=== END DIAGNOSTICS ===\n";

?>
