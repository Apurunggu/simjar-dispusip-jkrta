<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

Route::get('/debug-barang-duplicates', function () {
    $cols = Schema::getColumnListing('barang_masuk');
    echo 'columns: ' . implode(',', $cols) . "\n";

    $count = DB::table('barang_masuk')->count();
    echo "count:$count\n";

    $sumJumlah = DB::table('barang_masuk')->sum('jumlah');
    echo "sum_jumlah:$sumJumlah\n";

    $sumStok = DB::table('barang_masuk')->sum('stok');
    echo "sum_stok:$sumStok\n";

    $groupCols = ['kode_barang','nama_barang','nama','barcode','kode','sku'];
    $found = null;
    foreach ($groupCols as $g) {
        if (in_array($g, $cols)) {
            $found = $g;
            break;
        }
    }

    if (!$found) {
        echo "No grouping column found. Showing top 10 rows:\n";
        $rows = DB::table('barang_masuk')->limit(10)->get();
        echo json_encode($rows);
        return;
    }

    echo "Grouping by: $found\n";
    $dups = DB::table('barang_masuk')
        ->select($found, DB::raw('count(*) as c'), DB::raw('sum(jumlah) as sum_jumlah'))
        ->groupBy($found)
        ->having('c', '>', 1)
        ->orderBy('c', 'desc')
        ->limit(20)
        ->get();

    echo "duplicates:\n" . json_encode($dups) . "\n";

    if ($dups->isEmpty()) {
        echo "No duplicates found by $found.\n";
        return;
    }

    $values = $dups->pluck($found)->toArray();
    $examples = DB::table('barang_masuk')->whereIn($found, $values)->limit(20)->get();
    echo "examples:\n" . json_encode($examples) . "\n";
    // List rows with negative stok (indicator problem)
    $neg = DB::table('barang_masuk')->where('stok', '<', 0)->get();
    echo "negatives:\n" . json_encode($neg) . "\n";
})->withoutMiddleware([\App\Http\Middleware\Authenticate::class]);

// Route untuk recompute stok berdasarkan jumlah dan distribusi (abaikan status 'ditolak')
Route::get('/fix-recompute-stok', function () {
    $updated = 0;
    $fixes = [];
    $all = DB::table('barang_masuk')->get();
    foreach ($all as $b) {
        $distributed = DB::table('distribusi_barangs')
            ->where('barang_id', $b->id)
            ->whereNotIn('status', ['ditolak'])
            ->sum('jumlah');

        $newStok = max(0, $b->jumlah - $distributed);
        if ($newStok != $b->stok) {
            DB::table('barang_masuk')->where('id', $b->id)->update(['stok' => $newStok]);
            $updated++;
            $fixes[] = ['id' => $b->id, 'old' => $b->stok, 'new' => $newStok];
        }
    }

    return response()->json(['updated' => $updated, 'fixes' => $fixes], 200);
})->withoutMiddleware([\App\Http\Middleware\Authenticate::class]);

Route::get('/debug-unique-kategori', function () {
    $data = \Illuminate\Support\Facades\DB::table('barang_masuk')
        ->select('kategori', \Illuminate\Support\Facades\DB::raw('count(DISTINCT nama_barang) as unique_count'))
        ->groupBy('kategori')
        ->orderByDesc('unique_count')
        ->get();

    return response()->json($data);
})->withoutMiddleware([\App\Http\Middleware\Authenticate::class]);

Route::get('/assign-null-cabang-to-pusat', function () {
    $pusat = \App\Models\Cabang::where('kode_cabang', 'PUSAT')->first();
    if (!$pusat) {
        return response('Pusat cabang not found', 404);
    }

    $count = \Illuminate\Support\Facades\DB::table('barang_masuk')->whereNull('cabang_id')->count();
    \Illuminate\Support\Facades\DB::table('barang_masuk')->whereNull('cabang_id')->update(['cabang_id' => $pusat->id]);

    return response()->json(['assigned_to_pusat' => $count, 'pusat_id' => $pusat->id]);
})->withoutMiddleware([\App\Http\Middleware\Authenticate::class]);
