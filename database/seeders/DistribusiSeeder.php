<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DistribusiBarang;
use App\Models\BarangMasuk;
use App\Models\Cabang;
use App\Models\User;
use Carbon\Carbon;

class DistribusiSeeder extends Seeder
{
    public function run(): void
    {
        $barang = BarangMasuk::first();
        $pusat = Cabang::where('is_pusat', true)->first();
        $utara = Cabang::where('kode_cabang', 'UTARA')->first();
        $selatan = Cabang::where('kode_cabang', 'SELATAN')->first();
        $staff = User::where('email', 'staff@simjar.test')->first();

        if ($barang && $pusat && $utara && $staff) {
            // Distribusi ke Cabang Utara
            DistribusiBarang::create([
                'barang_id' => $barang->id,
                'cabang_asal_id' => $pusat->id,
                'cabang_tujuan_id' => $utara->id,
                'jumlah' => 20,
                'tanggal_kirim' => Carbon::now()->subDays(5),
                'tanggal_terima' => Carbon::now()->subDays(3),
                'status' => 'diterima',
                'keterangan' => 'Pengiriman untuk stok cabang utara',
                'user_id' => $staff->id,
            ]);

            // Distribusi ke Cabang Selatan (pending)
            DistribusiBarang::create([
                'barang_id' => $barang->id,
                'cabang_asal_id' => $pusat->id,
                'cabang_tujuan_id' => $selatan->id,
                'jumlah' => 15,
                'tanggal_kirim' => Carbon::now(),
                'status' => 'pending',
                'keterangan' => 'Distribusi baru untuk cabang selatan',
                'user_id' => $staff->id,
            ]);
        }
    }
}
