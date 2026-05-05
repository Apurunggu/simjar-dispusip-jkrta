<?php
require 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\BarangMasuk;
use App\Models\PerangkatJaringan;
use App\Models\DistribusiBarang;
use Illuminate\Support\Facades\DB;

echo "=== DASHBOARD DATA CHECK ===\n\n";

echo "1. BarangMasuk Records:\n";
echo "   Total count: " . BarangMasuk::count() . "\n";
echo "   Total jumlah: " . BarangMasuk::sum('jumlah') . "\n";
echo "   Total stok: " . BarangMasuk::sum('stok') . "\n";
echo "   Unique nama_barang: " . BarangMasuk::distinct('nama_barang')->count('nama_barang') . "\n";

$barangSample = BarangMasuk::limit(3)->get();
if ($barangSample->count() > 0) {
    echo "\n   Sample records:\n";
    foreach ($barangSample as $b) {
        echo "   - ID: {$b->id}, Nama: {$b->nama_barang}, Kategori: {$b->kategori}, Jumlah: {$b->jumlah}, Stok: {$b->stok}\n";
    }
} else {
    echo "   ⚠️  NO DATA FOUND\n";
}

echo "\n2. PerangkatJaringan Records:\n";
echo "   Total count: " . PerangkatJaringan::count() . "\n";
echo "   Aktif: " . PerangkatJaringan::where('status', 'aktif')->count() . "\n";
echo "   Tidak Aktif: " . PerangkatJaringan::where('status', 'tidak_aktif')->count() . "\n";

$perangkatSample = PerangkatJaringan::limit(3)->get();
if ($perangkatSample->count() > 0) {
    echo "\n   Sample records:\n";
    foreach ($perangkatSample as $p) {
        echo "   - ID: {$p->id}, Nama: {$p->nama_perangkat}, Status: {$p->status}\n";
    }
} else {
    echo "   ⚠️  NO DATA FOUND\n";
}

echo "\n3. DistribusiBarang Records:\n";
echo "   Total count: " . DistribusiBarang::count() . "\n";
echo "   Pending: " . DistribusiBarang::where('status', 'pending')->count() . "\n";
echo "   Dikirim/Diterima: " . DistribusiBarang::whereIn('status', ['dikirim', 'diterima'])->sum('jumlah') . "\n";

$distribusiSample = DistribusiBarang::limit(3)->get();
if ($distribusiSample->count() > 0) {
    echo "\n   Sample records:\n";
    foreach ($distribusiSample as $d) {
        echo "   - ID: {$d->id}, Status: {$d->status}, Jumlah: {$d->jumlah}\n";
    }
} else {
    echo "   ⚠️  NO DATA FOUND\n";
}

echo "\n4. Kategori Distribution:\n";
$kategoris = DB::table('barang_masuk')
    ->select('kategori', DB::raw('count(DISTINCT nama_barang) as unique_count'))
    ->groupBy('kategori')
    ->orderByDesc('unique_count')
    ->get();

if ($kategoris->count() > 0) {
    foreach ($kategoris as $k) {
        echo "   - {$k->kategori}: {$k->unique_count} jenis\n";
    }
} else {
    echo "   ⚠️  NO KATEGORI DATA FOUND\n";
}

echo "\n5. Checking Database Tables:\n";
$tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' ORDER BY name");
if (empty($tables)) {
    $tables = DB::select("SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE()");
}
foreach ($tables as $table) {
    $tableName = $table->name ?? $table->TABLE_NAME;
    if (!in_array($tableName, ['sqlite_sequence'])) {
        echo "   - $tableName\n";
    }
}

echo "\n=== END CHECK ===\n";
