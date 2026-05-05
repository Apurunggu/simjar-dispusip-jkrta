<?php
// Direct test - cek apakah dashboard view render dengan data
require __DIR__ . '/vendor/autoload.php';

$app = require __DIR__ . '/bootstrap/app.php';
$app->boot();

// Get user dan set auth
$user = \App\Models\User::first();
auth()->setUser($user);

// Call controller
$controller = new \App\Http\Controllers\DashboardController();
$view = $controller->index();

// Get rendered HTML
$html = $view->render();

// Check if content is there
if (strpos($html, 'Dashboard') !== false) {
    echo "✓ View contains 'Dashboard' text\n";
} else {
    echo "✗ View does NOT contain 'Dashboard' text\n";
}

if (strpos($html, 'Barang Masuk') !== false) {
    echo "✓ View contains 'Barang Masuk' text\n";
} else {
    echo "✗ View does NOT contain 'Barang Masuk' text\n";
}

// Count sections
$cardCount = substr_count($html, '<div class="card"');
echo "Card count in HTML: $cardCount\n";

// Show snippet
echo "\n=== First 500 chars of body content ===\n";
$body_start = strpos($html, '<body');
if ($body_start !== false) {
    $body_content_start = strpos($html, '>', $body_start) + 1;
    echo substr($html, $body_content_start, 1000);
} else {
    echo "No body tag found! Content:\n";
    echo substr($html, 0, 1000);
}
?>
