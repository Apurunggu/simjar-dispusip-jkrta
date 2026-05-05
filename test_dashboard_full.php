<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

echo "=== TESTING DASHBOARD ACCESS ===\n\n";

// Get admin user
$user = User::where('email', 'admin@simjar.test')->first();
if (!$user) {
    echo "❌ Admin user not found\n";
    exit;
}

echo "✓ Found user: " . $user->name . " ({$user->email})\n\n";

// Create a fake request
$request = Request::create('/', 'GET', [], [], [], [
    'HTTP_HOST' => '127.0.0.1:8000',
    'SERVER_NAME' => '127.0.0.1',
    'SERVER_PORT' => '8000',
    'REQUEST_SCHEME' => 'http',
]);

app()->instance('request', $request);

// Authenticate user
Auth::login($user);

if (Auth::check()) {
    echo "✓ User authenticated successfully\n";
    echo "  Authenticated user: " . Auth::user()->name . "\n\n";
    
    // Call DashboardController
    $controller = new \App\Http\Controllers\DashboardController();
    try {
        $view = $controller->index();
        $data = $view->getData();
        
        echo "✓ Dashboard Controller executed successfully\n\n";
        echo "=== DASHBOARD DATA ===\n";
        echo "Total Barang Masuk: " . ($data['totalBarangMasuk'] ?? 'N/A') . "\n";
        echo "Total Stok: " . ($data['totalStok'] ?? 'N/A') . "\n";
        echo "Total Unik Barang: " . ($data['totalUnikBarang'] ?? 'N/A') . "\n";
        echo "Total Perangkat Aktif: " . ($data['totalPerangkatAktif'] ?? 'N/A') . "\n";
        echo "Total Perangkat Tidak Aktif: " . ($data['totalPerangkatTidakAktif'] ?? 'N/A') . "\n";
        echo "Distribusi Pending: " . ($data['distribusiPending'] ?? 'N/A') . "\n";
        echo "Total Terdistribusi: " . ($data['totalTerdistribusi'] ?? 'N/A') . "\n";
        
        if (isset($data['uniqueByKategori'])) {
            echo "\nUnik per Kategori: " . $data['uniqueByKategori']->count() . " kategori\n";
        }
        
        // Try to render
        echo "\n=== RENDERING ===\n";
        $rendered = $view->render();
        echo "✓ View rendered successfully (" . strlen($rendered) . " bytes)\n";
        
        // Check content
        $checks = [
            'SIMJAR Branding' => 'SIMJAR',
            'Dashboard Title' => 'Dashboard',
            'Sidebar' => 'MENU UTAMA',
            'Total Barang Masuk Card' => 'Total Barang Masuk',
            'Bootstrap CSS' => 'bootstrap.min.css',
            'Chart.js' => 'chart.js'
        ];
        
        echo "\n=== CONTENT VERIFICATION ===\n";
        $allGood = true;
        foreach ($checks as $name => $search) {
            $found = stripos($rendered, $search) !== false ? '✓' : '❌';
            echo "$found $name\n";
            if ($found === '❌') $allGood = false;
        }
        
        if ($allGood) {
            echo "\n✅ DASHBOARD IS WORKING CORRECTLY!\n";
        }
        
    } catch (\Exception $e) {
        echo "❌ Error: " . $e->getMessage() . "\n";
        echo $e->getTraceAsString();
    }
    
} else {
    echo "❌ User authentication failed\n";
}

?>
