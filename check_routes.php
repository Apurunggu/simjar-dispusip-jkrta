<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Route;

echo "=== REGISTERED ROUTES ===\n\n";

$routes = Route::getRoutes();
$count = 0;
foreach ($routes as $route) {
    if (preg_match('/dashboard|\//', $route->uri)) {
        echo sprintf(
            "%s\t%s\n",
            implode('|', $route->methods),
            $route->uri
        );
        $count++;
    }
}

echo "\nTotal dashboard-related routes: $count\n";

// Check dashboard route specifically
echo "\n=== CHECKING DASHBOARD ROUTE ===\n";
$dashboardRoute = $routes->getByName('dashboard');
if ($dashboardRoute) {
    echo "✓ Dashboard route found\n";
    echo "  URI: " . $dashboardRoute->uri . "\n";
    echo "  Methods: " . implode(', ', $dashboardRoute->methods) . "\n";
    echo "  Middleware: " . implode(', ', $dashboardRoute->middleware()) . "\n";
} else {
    echo "✗ Dashboard route NOT found\n";
}

// List all named routes
echo "\n=== ALL NAMED ROUTES ===\n";
$namedRoutes = [];
foreach ($routes as $route) {
    if ($route->getName()) {
        $namedRoutes[] = $route->getName();
    }
}
sort($namedRoutes);
foreach (array_slice($namedRoutes, 0, 20) as $name) {
    echo "  - $name\n";
}
if (count($namedRoutes) > 20) {
    echo "  ... and " . (count($namedRoutes) - 20) . " more\n";
}
