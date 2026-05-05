<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Simple test - just output data
Route::get('/test-api-dashboard', function () {
    $data = [
        'totalBarangMasuk' => \App\Models\BarangMasuk::sum('jumlah'),
        'totalStok' => \App\Models\BarangMasuk::sum('stok'),
        'totalUnikBarang' => \App\Models\BarangMasuk::select('nama_barang')->distinct()->count('nama_barang'),
        'totalPerangkatAktif' => \App\Models\PerangkatJaringan::where('status', 'aktif')->count(),
        'totalPerangkatTidakAktif' => \App\Models\PerangkatJaringan::where('status', 'tidak_aktif')->count(),
        'distribusiPending' => \App\Models\DistribusiBarang::where('status', 'pending')->count(),
        'totalTerdistribusi' => \App\Models\DistribusiBarang::whereIn('status', ['dikirim', 'diterima'])->sum('jumlah'),
    ];
    
    return response()->json($data);
});
