<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

// Get or create test user
$user = User::firstOrCreate(
    ['email' => 'admin@simjar.test'],
    [
        'name' => 'Super Admin',
        'password' => bcrypt('password123'),
        'email_verified_at' => now()
    ]
);

// Assign super_admin role
if (!$user->hasRole('super_admin')) {
    $user->assignRole('super_admin');
}

// Authenticate
Auth::login($user);

// Create request
$request = \Illuminate\Http\Request::create(
    '/',
    'GET',
    [],
    [],
    [],
    [
        'HTTP_HOST' => '127.0.0.1:8000',
        'SERVER_NAME' => '127.0.0.1',
        'SERVER_PORT' => '8000',
        'REQUEST_SCHEME' => 'http',
    ]
);

app()->instance('request', $request);

// Call Dashboard controller
$controller = new \App\Http\Controllers\DashboardController();
$view = $controller->index();

echo "=== DASHBOARD VIEW DATA ===\n\n";

// Get view data
$data = $view->getData();
foreach ($data as $key => $value) {
    if (is_object($value) && get_class($value) === 'Illuminate\Database\Eloquent\Collection') {
        echo "$key: Collection with " . $value->count() . " items\n";
        if ($value->count() > 0) {
            echo "  First item: " . json_encode($value->first()->toArray()) . "\n";
        }
    } else if (is_string($value)) {
        echo "$key: " . substr($value, 0, 100) . "\n";
    } else {
        echo "$key: " . json_encode($value) . "\n";
    }
}

echo "\n=== VIEW RENDERING TEST ===\n";
echo "View name: " . $view->getName() . "\n";
echo "View exists: " . (view()->exists($view->getName()) ? 'YES' : 'NO') . "\n";

// Try to render
try {
    $rendered = $view->render();
    echo "Rendered size: " . strlen($rendered) . " bytes\n";
    
    // Check for key elements
    $checks = [
        'Dashboard' => 'Selamat datang di SIMJAR',
        'Cards' => 'Total Barang Masuk',
        'Sidebar' => 'MENU UTAMA',
        'Bootstrap' => 'bootstrap',
        'Chart' => 'chart.js'
    ];
    
    echo "\n=== CONTENT CHECKS ===\n";
    foreach ($checks as $name => $search) {
        $found = stripos($rendered, $search) !== false ? 'YES' : 'NO';
        echo "$name ($search): $found\n";
    }
    
    // Save rendered HTML for inspection
    file_put_contents('storage/logs/dashboard_render.html', $rendered);
    echo "\n✓ Full HTML saved to storage/logs/dashboard_render.html\n";
    
} catch (\Exception $e) {
    echo "ERROR rendering view: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
