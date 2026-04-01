<?php

use Illuminate\Support\Facades\Route;

Route::get('/setup-sample-data', function () {
    // Update semua barang masuk dengan stok
    try {
        $updated = \DB::update('UPDATE barang_masuk SET stok = jumlah');
        echo "Updated $updated barang masuk records\n";
    } catch (\Exception $e) {
        echo "Error updating barang: " . $e->getMessage() . "\n";
    }
    
    // Verify
    $first = \App\Models\BarangMasuk::first();
    echo "First barang: ID=$first->id, jumlah=$first->jumlah, stok=$first->stok\n";
    
    // Reset distribusi
    \App\Models\DistribusiBarang::truncate();
    
    // Create sample distribusi
    $barang = \App\Models\BarangMasuk::where('stok', '>', 0)->first();
    $pusat = \App\Models\Cabang::where('is_pusat', true)->first();
    $utara = \App\Models\Cabang::where('kode_cabang', 'UTARA')->first();
    $selatan = \App\Models\Cabang::where('kode_cabang', 'SELATAN')->first();
    $staff = \App\Models\User::where('email', 'staff@simjar.test')->first();

    if (!$barang) {
        echo "No barang with stok > 0 found!\n";
        return;
    }
    
    if (!$pusat) echo "Pusat not found!\n";
    if (!$utara) echo "Utara not found!\n";
    if (!$staff) echo "Staff not found!\n";
    
    if ($barang && $pusat && $utara && $staff) {
        try {
            // Distribusi ke Cabang Utara - DITERIMA
            $d1 = \App\Models\DistribusiBarang::create([
                'barang_id' => $barang->id,
                'cabang_asal_id' => $pusat->id,
                'cabang_tujuan_id' => $utara->id,
                'jumlah' => 20,
                'tanggal_kirim' => now()->subDays(5)->toDateString(),
                'tanggal_terima' => now()->subDays(3)->toDateString(),
                'status' => 'diterima',
                'keterangan' => 'Pengiriman untuk stok cabang utara',
                'user_id' => $staff->id,
            ]);
            
            // Kurangi stok
            $barang->decrement('stok', 20);
            echo "- Created distribution 1 (diterima): -20 stok\n";

            // Distribusi ke Cabang Selatan - PENDING
            if ($selatan) {
                $d2 = \App\Models\DistribusiBarang::create([
                    'barang_id' => $barang->id,
                    'cabang_asal_id' => $pusat->id,
                    'cabang_tujuan_id' => $selatan->id,
                    'jumlah' => 15,
                    'tanggal_kirim' => now()->toDateString(),
                    'status' => 'pending',
                    'keterangan' => 'Distribusi baru untuk cabang selatan',
                    'user_id' => $staff->id,
                ]);
                
                // Kurangi stok
                $barang->decrement('stok', 15);
                echo "- Created distribution 2 (pending): -15 stok\n";
            }
        } catch (\Exception $e) {
            echo "Error creating distribusi: " . $e->getMessage() . "\n";
        }
    }
    
    return "Sample data setup complete!";
});


