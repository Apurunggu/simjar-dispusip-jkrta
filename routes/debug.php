<?php

use Illuminate\Support\Facades\Route;

// Debug route - cek database content
Route::get('/test-debug-dashboard', function () {
    try {
        $db = DB::table('barang_masuk');
        $barangCount = $db->count();
        $totalJumlah = $db->sum('jumlah');
        $totalStok = $db->sum('stok');
        
        $perangkatAktif = DB::table('perangkat_jaringan')->where('status', 'aktif')->count();
        $perangkatTidakAktif = DB::table('perangkat_jaringan')->where('status', 'tidak_aktif')->count();
        
        $distribusiPending = DB::table('distribusi_barangs')->where('status', 'pending')->count();
        
        $html = "<h2>Database Debug Info</h2>";
        $html .= "<p><strong>Barang Masuk:</strong> " . $barangCount . " records</p>";
        $html .= "<p><strong>Total Jumlah:</strong> " . ($totalJumlah ?? 0) . "</p>";
        $html .= "<p><strong>Total Stok:</strong> " . ($totalStok ?? 0) . "</p>";
        $html .= "<p><strong>Perangkat Aktif:</strong> " . $perangkatAktif . "</p>";
        $html .= "<p><strong>Perangkat Tidak Aktif:</strong> " . $perangkatTidakAktif . "</p>";
        $html .= "<p><strong>Distribusi Pending:</strong> " . $distribusiPending . "</p>";
        
        // Sample data
        $html .= "<h3>Sample Barang Masuk:</h3>";
        $samples = DB::table('barang_masuk')->limit(3)->get();
        foreach ($samples as $item) {
            $html .= "<div style='border: 1px solid #ccc; padding: 10px; margin: 5px 0;'>";
            $html .= "<p><strong>" . $item->nama_barang . "</strong> - Jumlah: " . $item->jumlah . ", Stok: " . $item->stok . "</p>";
            $html .= "</div>";
        }
        
        return $html;
    } catch (\Exception $e) {
        return "ERROR: " . $e->getMessage();
    }
});
