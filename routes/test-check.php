<?php

use Illuminate\Support\Facades\Route;

Route::get('/check-db', function () {
    $barang = \App\Models\BarangMasuk::get();
    $distribusi = \App\Models\DistribusiBarang::get();
    $cabang = \App\Models\Cabang::get();

    echo "===== DATABASE STATUS =====\n\n";
    
    echo "BARANG MASUK:\n";
    foreach ($barang as $b) {
        echo "- {$b->id}: {$b->nama_barang} (Stok: {$b->stok})\n";
    }
    
    echo "\nCABANG:\n";
    foreach ($cabang as $c) {
        echo "- {$c->id}: {$c->nama_cabang}\n";
    }

    echo "\nDISTRIBUSI:\n";
    foreach ($distribusi as $d) {
        echo "- {$d->id}: Barang#{$d->barang_id} dari Cabang#{$d->cabang_asal_id} ke #{$d->cabang_tujuan_id} ({$d->status})\n";
    }

    echo "\n";
    echo "Total Barang: " . $barang->count() . "\n";
    echo "Total Distribusi: " . $distribusi->count() . "\n";
    echo "Total Cabang: " . $cabang->count() . "\n";
});
