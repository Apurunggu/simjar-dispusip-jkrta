<?php

use Illuminate\Support\Facades\Route;

Route::get('/check-sum', function () {
    $total_barang = \App\Models\BarangMasuk::sum('jumlah');
    $total_stok = \App\Models\BarangMasuk::sum('stok');
    $distribusi = \App\Models\DistribusiBarang::get();
    $distribusi_dikirim = \App\Models\DistribusiBarang::whereIn('status', ['dikirim', 'diterima'])->sum('jumlah');
    $distribusi_pending = \App\Models\DistribusiBarang::where('status', 'pending')->count();

    echo "===== SUM CHECKS =====\n";
    echo "Total Barang: $total_barang\n";
    echo "Total Stok: $total_stok\n";
    echo "Terdistribusi (dikirim+diterima): $distribusi_dikirim\n";
    echo "Distribusi Pending: $distribusi_pending\n";
    echo "\nTotal Distribusi Records: " . $distribusi->count() . "\n";
    
    foreach ($distribusi as $d) {
        echo "- Distribusi #$d->id: Barang#$d->barang_id, Jumlah: $d->jumlah, Status: $d->status\n";
    }
});
