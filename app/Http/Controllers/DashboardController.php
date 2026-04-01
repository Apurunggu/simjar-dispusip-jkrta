<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\PerangkatJaringan;
use App\Models\DistribusiBarang;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(): View
    {
        $totalBarangMasuk = (int) BarangMasuk::sum('jumlah');
        $totalUnikBarang = (int) BarangMasuk::select('nama_barang')->distinct()->count('nama_barang');
        $totalStok = (int) BarangMasuk::sum('stok');
        $totalTerdistribusi = (int) DistribusiBarang::whereIn('status', ['dikirim', 'diterima'])->sum('jumlah');
        
        $totalPerangkatAktif = (int) PerangkatJaringan::where('status', 'aktif')->count();
        $totalPerangkatTidakAktif = (int) PerangkatJaringan::where('status', 'tidak_aktif')->count();
        
        $distribusiPending = (int) DistribusiBarang::where('status', 'pending')->count();

        return view('dashboard', [
            'totalBarangMasuk' => $totalBarangMasuk,
            'totalUnikBarang' => $totalUnikBarang,
            // Unique items grouped by kategori
            'uniqueByKategori' => DB::table('barang_masuk')
                ->select('kategori', DB::raw('count(DISTINCT nama_barang) as unique_count'))
                ->groupBy('kategori')
                ->orderByDesc('unique_count')
                ->limit(8)
                ->get(),
            'totalStok' => $totalStok,
            'totalTerdistribusi' => $totalTerdistribusi,
            'totalPerangkatAktif' => $totalPerangkatAktif,
            'totalPerangkatTidakAktif' => $totalPerangkatTidakAktif,
            'distribusiPending' => $distribusiPending,
        ]);
    }
}

