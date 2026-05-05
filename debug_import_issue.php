<?php
// Include Laravel Bootstrap
require __DIR__ . '/bootstrap/app.php';

use App\Models\BarangMasuk;
use App\Models\User;
use Illuminate\Support\Facades\DB;

// Get app instance
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

echo "=== DEBUG IMPORT BARANG MASUK ===\n\n";

// 1. Cek total data di database
echo "1️⃣ TOTAL DATA DI DATABASE:\n";
$totalAll = BarangMasuk::count();
echo "   Total semua barang: $totalAll\n";

// 2. Cek per cabang
echo "\n2️⃣ DATA PER CABANG:\n";
$byCAB = DB::table('barang_masuk')
    ->select('cabang_id', DB::raw('COUNT(*) as total'))
    ->groupBy('cabang_id')
    ->get();

foreach ($byCAB as $row) {
    $cabangName = DB::table('cabangs')->where('id', $row->cabang_id)->value('nama') ?? 'Unknown';
    echo "   Cabang ID {$row->cabang_id} ({$cabangName}): {$row->total} barang\n";
}

// 3. Cek users dan cabang mereka
echo "\n3️⃣ USERS DAN CABANG MEREKA:\n";
$users = DB::table('users')
    ->select('id', 'name', 'cabang_id', 'email')
    ->get();

foreach ($users as $user) {
    $cabangName = DB::table('cabangs')->where('id', $user->cabang_id)->value('nama') ?? 'N/A';
    echo "   - {$user->name} (ID: {$user->id}): cabang_id={$user->cabang_id} ({$cabangName})\n";
}

// 4. Cek data barang terakhir yang diimport
echo "\n4️⃣ DATA BARANG TERAKHIR YANG DIIMPORT:\n";
$latestBarang = BarangMasuk::orderBy('created_at', 'desc')->limit(5)->get();

if ($latestBarang->count() > 0) {
    foreach ($latestBarang as $barang) {
        $cabangName = DB::table('cabangs')->where('id', $barang->cabang_id)->value('nama') ?? 'Unknown';
        echo "   - {$barang->nomor_barang}: {$barang->nama_barang}\n";
        echo "     Kategori: {$barang->kategori}, Qty: {$barang->jumlah}, Stok: {$barang->stok}\n";
        echo "     Cabang: {$cabangName}, Created: {$barang->created_at}\n";
    }
} else {
    echo "   ❌ Tidak ada data!\n";
}

// 5. Cek kolom yang ada di database
echo "\n5️⃣ STRUKTUR TABEL BARANG_MASUK:\n";
$columns = DB::select("SHOW COLUMNS FROM barang_masuk");
echo "   Kolom yang ada:\n";
foreach ($columns as $col) {
    echo "   - {$col->Field} ({$col->Type})\n";
}

// 6. Cek detail session auth jika ada
echo "\n6️⃣ INFORMASI PENTING:\n";
echo "   - Timestamp sekarang: " . date('Y-m-d H:i:s') . "\n";
echo "   - Environment: " . (function_exists('env') ? env('APP_ENV', 'unknown') : 'N/A') . "\n";
echo "   - Database: " . (env('DB_DATABASE') ?? 'unknown') . "\n";

// 7. List template Excel yang ada
echo "\n7️⃣ TEMPLATE EXCEL YANG ADA:\n";
$templateDir = __DIR__ . '/public';
$templates = glob($templateDir . '/*template*.xlsx') + glob($templateDir . '/*template*.xls');
if (empty($templates)) {
    echo "   ❌ Tidak ada template di public/\n";
} else {
    foreach ($templates as $template) {
        echo "   - " . basename($template) . "\n";
    }
}

echo "\n=== END DEBUG ===\n";
?>
