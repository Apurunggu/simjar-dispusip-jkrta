<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BarangMasuk;
use App\Models\SerialNumber;

class SerialNumberDummySeeder extends Seeder
{
    public function run()
    {
        $barangs = BarangMasuk::all();
        foreach ($barangs as $barang) {
            // Cek jika belum ada serial number
            if ($barang->serialNumbers()->count() == 0) {
                SerialNumber::create([
                    'barang_masuk_id' => $barang->id,
                    'serial_number' => 'SN-' . $barang->id,
                ]);
            }
        }
    }
}
