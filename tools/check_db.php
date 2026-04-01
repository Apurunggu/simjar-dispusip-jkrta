<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\BarangMasuk;
try {
    $count = BarangMasuk::count();
    echo "COUNT=" . $count . "\n";
    $first = BarangMasuk::first();
    if ($first) {
        echo "FIRST_ID=" . $first->id . "\n";
    } else {
        echo "NO_RECORDS\n";
    }
} catch (Exception $e) {
    echo "EXCEPTION: " . $e->getMessage() . "\n";
}
