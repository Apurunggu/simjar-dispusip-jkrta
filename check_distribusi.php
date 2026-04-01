<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DistribusiBarang;

$distribusi = DistribusiBarang::all();
echo "Total distribusi: " . count($distribusi) . "\n";
echo json_encode($distribusi->toArray(), JSON_PRETTY_PRINT) . "\n";
