<?php
/**
 * Create sample distribusi for each cabang.
 * Usage: php tools/create_test_distribusi.php
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DistribusiBarang;
use App\Models\BarangMasuk;
use App\Models\Cabang;
use App\Models\User;

$pusat = Cabang::where('is_pusat', true)->first();
if (!$pusat) {
    echo "Error: Cabang PUSAT not found\n";
    exit(1);
}

$cabangs = Cabang::where('is_pusat', false)->get();
$barangs = BarangMasuk::where('stok', '>', 0)->limit(4)->get();

if ($barangs->isEmpty()) {
    echo "Error: No barang with stok > 0\n";
    exit(1);
}

foreach ($cabangs as $cabang) {
    foreach ($barangs as $barang) {
        $user = User::where('cabang_id', $pusat->id)->first();
        
        $distribusi = DistribusiBarang::create([
            'barang_id' => $barang->id,
            'cabang_asal_id' => $pusat->id,
            'cabang_tujuan_id' => $cabang->id,
            'jumlah' => min(2, $barang->stok),
            'tanggal_kirim' => now()->toDateString(),
            'status' => 'pending',
            'user_id' => $user->id ?? 1,
            'keterangan' => "Sample distribusi ke {$cabang->nama}",
        ]);

        // Decrement stok
        $barang->decrement('stok', $distribusi->jumlah);

        echo "Created distribusi ID {$distribusi->id}: {$barang->nama_barang} -> {$cabang->nama}\n";
    }
}

echo "\nTest distribusi created successfully\n";
exit(0);
