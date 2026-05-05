<?php
/**
 * View All Branches Data - Comprehensive Dashboard
 * Menampilkan data semua cabang dengan detail
 */
require 'vendor/autoload.php';
$app = require 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Cabang;
use App\Models\User;
use App\Models\BarangMasuk;

echo "\n";
echo "╔═══════════════════════════════════════════════════════════════╗\n";
echo "║     SIMJAR - DATA SEMUA CABANG (All Branches Overview)        ║\n";
echo "╚═══════════════════════════════════════════════════════════════╝\n\n";

// 1. Display all branches
echo "📍 DAFTAR SEMUA CABANG\n";
echo str_repeat("─", 70) . "\n";
$cabangs = Cabang::orderBy('is_pusat', 'desc')->orderBy('nama_cabang')->get();

if ($cabangs->isEmpty()) {
    echo "❌ Tidak ada data cabang\n";
} else {
    foreach ($cabangs as $i => $cabang) {
        $isPusat = $cabang->is_pusat ? '⭐ (PUSAT)' : '';
        echo sprintf(
            "%d. %s %s\n   ID: %d | Kode: %s | Kota: %s\n   Alamat: %s\n\n",
            $i + 1,
            $cabang->nama_cabang,
            $isPusat,
            $cabang->id,
            $cabang->kode_cabang,
            $cabang->kota ?? '-',
            $cabang->alamat ?? '-'
        );
    }
}

// 2. Display users per branch
echo "\n👤 PENGGUNA PER CABANG\n";
echo str_repeat("─", 70) . "\n";
foreach ($cabangs as $cabang) {
    $users = User::where('cabang_id', $cabang->id)->with('role')->get();
    echo "\n📌 Cabang: {$cabang->nama_cabang}\n";
    if ($users->isEmpty()) {
        echo "   ➜ Tidak ada pengguna\n";
    } else {
        foreach ($users as $user) {
            $role = $user->role ? $user->role->label : 'N/A';
            echo sprintf("   ➜ %s (%s) - %s\n", $user->name, $user->email, $role);
        }
    }
}

// 3. Display barang_masuk per branch
echo "\n\n📦 BARANG MASUK PER CABANG\n";
echo str_repeat("─", 70) . "\n";
foreach ($cabangs as $cabang) {
    $barangMasuk = BarangMasuk::where('cabang_id', $cabang->id)->get();
    $totalJumlah = $barangMasuk->sum('jumlah');
    
    echo "\n📌 Cabang: {$cabang->nama_cabang}\n";
    echo "   Total Items: {$barangMasuk->count()} | Total Qty: {$totalJumlah}\n";
    
    if ($barangMasuk->isEmpty()) {
        echo "   ➜ Tidak ada barang\n";
    } else {
        foreach ($barangMasuk as $item) {
            echo sprintf(
                "   ➜ [%s] %s - Qty: %d (%s) - %s\n",
                $item->nomor_barang ?? 'N/A',
                $item->nama_barang ?? 'N/A',
                $item->jumlah ?? 0,
                $item->kategori ?? 'N/A',
                $item->tanggal_masuk ?? 'N/A'
            );
        }
    }
}

// 4. Summary Statistics
echo "\n\n📊 STATISTIK KESELURUHAN\n";
echo str_repeat("─", 70) . "\n";
$totalCabangs = $cabangs->count();
$totalUsers = User::count();
$totalBarang = BarangMasuk::count();
$totalQty = BarangMasuk::sum('jumlah');

echo sprintf("Total Cabang: %d\n", $totalCabangs);
echo sprintf("Total Pengguna: %d\n", $totalUsers);
echo sprintf("Total Item Barang: %d\n", $totalBarang);
echo sprintf("Total Kuantitas Barang: %d\n", $totalQty);

// 5. Role distribution
echo "\n\n🔐 DISTRIBUSI ROLE\n";
echo str_repeat("─", 70) . "\n";
$roleDistribution = User::with('role')
    ->get()
    ->groupBy(function ($user) {
        return $user->role ? $user->role->label : 'N/A';
    })
    ->map(function ($group) {
        return $group->count();
    });

foreach ($roleDistribution as $role => $count) {
    echo sprintf("➜ %s: %d\n", $role, $count);
}

// 6. Users without cabang
echo "\n\n⚠️  PENGGUNA TANPA CABANG\n";
echo str_repeat("─", 70) . "\n";
$usersNoCabang = User::whereNull('cabang_id')->get();
if ($usersNoCabang->isEmpty()) {
    echo "✓ Semua pengguna sudah memiliki cabang\n";
} else {
    foreach ($usersNoCabang as $user) {
        $role = $user->role ? $user->role->label : 'N/A';
        echo sprintf("⚠️  %s (%s) - Role: %s\n", $user->name, $user->email, $role);
    }
}

// 7. Barang masuk without cabang
echo "\n\n⚠️  BARANG MASUK TANPA CABANG\n";
echo str_repeat("─", 70) . "\n";
$barangNoCabang = BarangMasuk::whereNull('cabang_id')->get();
if ($barangNoCabang->isEmpty()) {
    echo "✓ Semua barang sudah memiliki cabang\n";
} else {
    echo sprintf("⚠️  %d barang masuk tanpa cabang (orphan data)\n", $barangNoCabang->count());
    foreach ($barangNoCabang->take(5) as $item) {
        echo sprintf("    • %s - %s\n", $item->nomor_barang ?? 'N/A', $item->nama_barang ?? 'N/A');
    }
    if ($barangNoCabang->count() > 5) {
        echo sprintf("    ... dan %d barang lainnya\n", $barangNoCabang->count() - 5);
    }
}

echo "\n" . str_repeat("─", 70) . "\n";
echo "✓ Script selesai dijalankan\n\n";
?>
