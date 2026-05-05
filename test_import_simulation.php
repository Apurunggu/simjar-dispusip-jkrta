<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use App\Http\Controllers\BarangMasukController;
use App\Models\BarangMasuk;

echo "=== SIMULATING EXCEL IMPORT ===\n\n";

// 1. Get user
$user = User::where('email', 'admin@simjar.test')->first();
if (!$user) {
    echo "✗ User not found\n";
    exit;
}
echo "✓ Found user: {$user->name}\n";
echo "  Cabang ID: {$user->cabang_id}\n\n";

// 2. Authenticate
Auth::login($user);

// 3. Create fake request with test file
$testFile = 'storage/test_import.xlsx';
if (!file_exists($testFile)) {
    echo "✗ Test file not found: $testFile\n";
    exit;
}

echo "✓ Test file found: $testFile\n";
echo "  File size: " . filesize($testFile) . " bytes\n\n";

// 4. Create UploadedFile instance
$file = new UploadedFile($testFile, 'test_import.xlsx', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', null, true);

// 5. Create request
$request = new Request();
$request->files->set('file', $file);
$request->setMethod('POST');

echo "=== RUNNING IMPORT SIMULATION ===\n\n";

// 6. Get current barang count
$beforeCount = BarangMasuk::count();
echo "Barang count before import: $beforeCount\n\n";

// 7. Call controller
try {
    $controller = new BarangMasukController();
    $response = $controller->import($request);
    
    echo "✓ Import completed\n";
    echo "  Response type: " . class_basename($response) . "\n";
    echo "  Response status: " . $response->getStatusCode() . "\n";
    
    // Check if redirect
    if (method_exists($response, 'getTargetUrl')) {
        echo "  Redirect to: " . $response->getTargetUrl() . "\n";
    }
    
    // Check session messages
    if ($request->session()) {
        if ($request->session()->has('success')) {
            echo "  Session success: " . $request->session()->get('success') . "\n";
        }
        if ($request->session()->has('error')) {
            echo "  Session error: " . $request->session()->get('error') . "\n";
        }
    }
    
} catch (\Exception $e) {
    echo "✗ Error during import: " . $e->getMessage() . "\n";
    echo "\nTrace:\n" . $e->getTraceAsString() . "\n";
}

// 8. Check after count
$afterCount = BarangMasuk::count();
echo "\nBarang count after import: $afterCount\n";
echo "Items added: " . ($afterCount - $beforeCount) . "\n\n";

// 9. Show imported data
if ($afterCount > $beforeCount) {
    echo "=== IMPORTED DATA ===\n";
    $newItems = BarangMasuk::orderByDesc('id')->limit($afterCount - $beforeCount)->get();
    foreach ($newItems as $item) {
        echo "- {$item->nama_barang} (Qty: {$item->jumlah}, Stok: {$item->stok})\n";
    }
}

?>
