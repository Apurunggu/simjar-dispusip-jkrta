<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BarangMasuk;

echo "=== CHECKING IMPORT RESULTS ===\n\n";

$items = BarangMasuk::where('nama_barang', 'Switch Manageable Final')->get();

echo "Total items with name 'Switch Manageable Final': " . $items->count() . "\n\n";

foreach ($items as $idx => $item) {
    echo "Item " . ($idx+1) . ":\n";
    echo "  ID: {$item->id}\n";
    echo "  Nomor Barang: {$item->nomor_barang}\n";
    echo "  Jumlah: {$item->jumlah}\n";
    echo "  Stok: {$item->stok}\n";
    echo "  Created: {$item->created_at}\n";
    echo "  Updated: {$item->updated_at}\n\n";
}

if ($items->count() == 1) {
    echo "✅ CORRECT - Only 1 record (update worked)\n";
} else if ($items->count() > 1) {
    echo "❌ PROBLEM - Multiple records (duplicate created)\n";
}

echo "\n=== CHECKING ALL IMPORTED DATA ===\n\n";

$allImported = BarangMasuk::whereIn('nomor_barang', ['BRG-NET-0023', 'BRG-CIS-0023'])->get();
echo "All items with BRG-*-0023 nomor:\n";
foreach ($allImported as $item) {
    echo "- {$item->nama_barang} ({$item->nomor_barang})\n";
}

?>
