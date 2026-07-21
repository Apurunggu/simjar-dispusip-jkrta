<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Cabang;
use App\Models\BarangMasuk;

echo "=== CABANGS ===\n";
Cabang::all()->each(function($c) {
    echo "ID: {$c->id}, Nama: {$c->nama_cabang}\n";
});

echo "\n=== BARANG MASUK BY CABANG ===\n";
$barang = BarangMasuk::with('cabang')->get();
if($barang->isEmpty()) {
    echo "Tidak ada barang masuk!\n";
} else {
    $barang->each(function($b) {
        $cabang = $b->cabang ? $b->cabang->nama_cabang : 'NULL';
        echo "ID: {$b->id}, Barang: {$b->nama_barang}, Cabang ID: {$b->cabang_id} ({$cabang}), Stok: {$b->stok}\n";
    });
}

echo "\n=== BARANG TIMUR (cabang_id=4) ===\n";
$timur = BarangMasuk::where('cabang_id', 4)->get();
echo "Total: " . $timur->count() . "\n";
$timur->each(function($b) {
    echo "- {$b->nama_barang} (stok: {$b->stok})\n";
});
