<?php
// Test Controller dengan bootstrap lengkap

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';

// Bind dan boot aplikasi
$kernel = $app->make(\Illuminate\Contracts\Http\Kernel::class);

try {
    // Get first user
    $user = \App\Models\User::first();
    
    if (!$user) {
        echo "No users found in database!\n";
        exit(1);
    }

    // Manually set authenticated user in auth guard
    app('auth')->guard('web')->setUser($user);
    
    echo "Logged in as: " . $user->name . "\n";
    echo "Role: " . ($user->role ? $user->role->label : 'No Role') . "\n";
    echo "===============================================\n\n";

    // Now call the controller
    $controller = new \App\Http\Controllers\DashboardController();
    
    echo "Calling DashboardController::index()...\n";
    $view = $controller->index();

    echo "✓ View returned successfully!\n";
    echo "View name: " . $view->getName() . "\n";
    echo "Data passed: \n";
    
    $data = $view->getData();
    foreach ($data as $key => $value) {
        if (is_scalar($value)) {
            echo "  - $key: $value\n";
        } else {
            echo "  - $key: " . gettype($value) . "\n";
        }
    }

    echo "\n✓ Controller test completed successfully!\n";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "\nStack trace:\n";
    echo $e->getTraceAsString() . "\n";
}
?>
