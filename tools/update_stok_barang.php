<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
use App\Models\BarangMasuk;

// Update semua stok barang menjadi 10 jika stok = 0
$updated = BarangMasuk::where('stok', 0)->update(['stok' => 10]);
echo "Updated $updated barang(s) to stok=10\n";
