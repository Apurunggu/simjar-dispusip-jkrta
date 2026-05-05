<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "=== CHECKING ALL BARANG-MASUK ROUTES ===\n\n";

$routes = \Illuminate\Support\Facades\Route::getRoutes();

$barangRoutes = [];
foreach ($routes as $route) {
    if (strpos($route->uri, 'barang-masuk') !== false) {
        $barangRoutes[] = [
            'uri' => $route->uri,
            'methods' => implode('|', $route->methods),
            'action' => $route->getActionName(),
            'name' => $route->getName() ?? 'N/A'
        ];
    }
}

echo "Total barang-masuk routes: " . count($barangRoutes) . "\n\n";

foreach ($barangRoutes as $route) {
    echo "Route: {$route['uri']}\n";
    echo "  Methods: {$route['methods']}\n";
    echo "  Action: {$route['action']}\n";
    echo "  Name: {$route['name']}\n\n";
}

// Specifically check import routes
echo "\n=== IMPORT ROUTES DETAILED ===\n";
foreach ($routes as $route) {
    if (strpos($route->uri, 'barang-masuk/import') !== false) {
        echo "Route URI: " . $route->uri . "\n";
        echo "Methods: " . implode(', ', $route->methods) . "\n";
        echo "Action: " . $route->getActionName() . "\n";
        echo "Name: " . ($route->getName() ?? 'N/A') . "\n";
        echo "Middleware: " . implode(', ', $route->middleware()) . "\n\n";
    }
}

?>
