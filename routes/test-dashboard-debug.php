<?php

use Illuminate\Support\Facades\Route;

// Debug dashboard - use debug view
Route::get('/dashboard-debug', function () {
    $data = [
        'totalBarangMasuk' => \App\Models\BarangMasuk::sum('jumlah'),
        'totalStok' => \App\Models\BarangMasuk::sum('stok'),
        'totalUnikBarang' => \App\Models\BarangMasuk::select('nama_barang')->distinct()->count('nama_barang'),
        'totalPerangkatAktif' => \App\Models\PerangkatJaringan::where('status', 'aktif')->count(),
        'totalPerangkatTidakAktif' => \App\Models\PerangkatJaringan::where('status', 'tidak_aktif')->count(),
        'distribusiPending' => \App\Models\DistribusiBarang::where('status', 'pending')->count(),
        'totalTerdistribusi' => \App\Models\DistribusiBarang::whereIn('status', ['dikirim', 'diterima'])->sum('jumlah'),
    ];
    
    return view('dashboard-debug', $data);
})->middleware('auth');
