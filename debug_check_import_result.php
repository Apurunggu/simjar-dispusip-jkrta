<?php
/**
 * Debug - Cek hasil import di database
 */
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\BarangMasuk;
use App\Models\User;

echo "\n";
echo "╔════════════════════════════════════════════════════════════╗\n";
echo "║        DEBUG - CEK HASIL IMPORT DI DATABASE                ║\n";
echo "╚════════════════════════════════════════════════════════════╝\n\n";

// Cek user yang sedang login (yang mengupload)
$superAdmin = User::where('email', 'admin@simjar.test')->first();
echo "👤 USER YANG UPLOAD:\n";
echo "   Email: " . $superAdmin->email . "\n";
echo "   Nama: " . $superAdmin->name . "\n";
echo "   Cabang ID: " . $superAdmin->cabang_id . "\n";
echo "   Cabang: " . ($superAdmin->cabang ? $superAdmin->cabang->nama_cabang : 'NULL') . "\n\n";

// Cek total barang masuk
$totalBarang = BarangMasuk::count();
echo "📊 STATISTIK BARANG MASUK:\n";
echo "   Total barang di database: " . $totalBarang . "\n\n";

// Cek barang per cabang
echo "📦 BARANG PER CABANG:\n";
$cabangs = \App\Models\Cabang::all();
foreach ($cabangs as $cabang) {
    $count = BarangMasuk::where('cabang_id', $cabang->id)->count();
    $total = BarangMasuk::where('cabang_id', $cabang->id)->sum('jumlah');
    echo sprintf("   %s (ID: %d): %d item, qty: %s\n", 
        $cabang->nama_cabang, 
        $cabang->id, 
        $count,
        number_format($total)
    );
}

// Cek barang tanpa cabang
$noCabang = BarangMasuk::whereNull('cabang_id')->count();
echo "   (Tanpa cabang): " . $noCabang . "\n\n";

// Cek barang terbaru
echo "🕐 BARANG MASUK TERBARU (5 terakhir):\n";
$latest = BarangMasuk::latest('created_at')->limit(5)->get();
foreach ($latest as $item) {
    echo sprintf("   [%s] %s - Qty: %d - Cabang ID: %s - %s\n",
        $item->nomor_barang,
        $item->nama_barang,
        $item->jumlah,
        $item->cabang_id ?? 'NULL',
        $item->created_at->format('d/m/Y H:i:s')
    );
}

// Cek nama barang yang mungkin ter-import
echo "\n\n🔍 CARI BARANG DENGAN KEYWORD 'SWITCH' atau 'ROUTER':\n";
$switches = BarangMasuk::where('nama_barang', 'LIKE', '%Switch%')->get();
$routers = BarangMasuk::where('nama_barang', 'LIKE', '%Router%')->get();

if ($switches->count() > 0) {
    echo "   Switch ditemukan: " . $switches->count() . " item\n";
    foreach ($switches->take(3) as $item) {
        echo "      - " . $item->nama_barang . " (Qty: " . $item->jumlah . ")\n";
    }
} else {
    echo "   ❌ Tidak ada Switch\n";
}

if ($routers->count() > 0) {
    echo "   Router ditemukan: " . $routers->count() . " item\n";
    foreach ($routers->take(3) as $item) {
        echo "      - " . $item->nama_barang . " (Qty: " . $item->jumlah . ")\n";
    }
} else {
    echo "   ❌ Tidak ada Router\n";
}

echo "\n\n💡 DIAGNOSTIK:\n";
if ($totalBarang === 0) {
    echo "   ⚠️  Database kosong - mungkin belum ada import sama sekali\n";
} elseif ($noCabang > 0) {
    echo "   ⚠️  Ada " . $noCabang . " barang tanpa cabang (orphan data)\n";
} else {
    echo "   ✓ Semua barang memiliki cabang\n";
}

echo "\n" . str_repeat("─", 60) . "\n";
echo "Debug selesai\n\n";
?>
