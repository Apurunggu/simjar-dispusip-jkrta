<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BarangMasuk;
use App\Models\PerangkatJaringan;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Database\Seeders\SerialNumberDummySeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed Barang Masuk (idempotent)
        BarangMasuk::firstOrCreate([
            'nomor_barang' => 'BRG-001'
        ],[
            'nama_barang' => 'Router TP-Link TL-WR840N',
            'kategori' => 'Router',
            'jumlah' => 2,
            'tanggal_masuk' => Carbon::now()->subMonths(3),
            'keterangan' => 'Router WiFi 300Mbps untuk kantor'
        ]);

        BarangMasuk::firstOrCreate([
            'nomor_barang' => 'BRG-002'
        ],[
            'nama_barang' => 'Switch Netgear GS310TP',
            'kategori' => 'Switch',
            'jumlah' => 1,
            'tanggal_masuk' => Carbon::now()->subMonths(3),
            'keterangan' => 'Managed Switch 10 Port dengan PoE'
        ]);

        BarangMasuk::firstOrCreate([
            'nomor_barang' => 'BRG-003'
        ],[
            'nama_barang' => 'Modem ZTE F609',
            'kategori' => 'Modem',
            'jumlah' => 3,
            'tanggal_masuk' => Carbon::now()->subMonths(2),
            'keterangan' => 'Modem GPON untuk koneksi ISP'
        ]);

        BarangMasuk::firstOrCreate([
            'nomor_barang' => 'BRG-004'
        ],[
            'nama_barang' => 'Access Point Ubiquiti Unifi',
            'kategori' => 'Access Point',
            'jumlah' => 4,
            'tanggal_masuk' => Carbon::now()->subMonths(2),
            'keterangan' => 'Access Point Enterprise dengan PoE'
        ]);

        BarangMasuk::firstOrCreate([
            'nomor_barang' => 'BRG-005'
        ],[
            'nama_barang' => 'Kabel UTP Cat6 100m',
            'kategori' => 'Kabel',
            'jumlah' => 10,
            'tanggal_masuk' => Carbon::now()->subMonth(),
            'keterangan' => 'Kabel jaringan berstandar Cat6 outdoor'
        ]);

        // Seed Perangkat Jaringan
        $perangkat1 = PerangkatJaringan::firstOrCreate([
            'nomor_inventaris' => 'INV-NET-001'
        ],[
            'nama_perangkat' => 'Router Lantai 1',
            'tipe_perangkat' => 'Router',
            'lokasi' => 'Ruang Server',
            'ip_address' => '192.168.1.1',
            'mac_address' => '00:1A:2B:3C:4D:5E',
            'status' => 'aktif',
            'tanggal_pemasangan' => Carbon::now()->subMonths(4),
            'keterangan' => 'Router utama untuk jaringan kantor'
        ]);

        $perangkat2 = PerangkatJaringan::firstOrCreate([
            'nomor_inventaris' => 'INV-NET-002'
        ],[
            'nama_perangkat' => 'Switch Utama',
            'tipe_perangkat' => 'Switch',
            'lokasi' => 'Ruang Server',
            'ip_address' => '192.168.1.2',
            'mac_address' => '00:1A:2B:3C:4D:5F',
            'status' => 'aktif',
            'tanggal_pemasangan' => Carbon::now()->subMonths(4),
            'keterangan' => 'Switch managed 24 port sebagai backbone'
        ]);

        $perangkat3 = PerangkatJaringan::firstOrCreate([
            'nomor_inventaris' => 'INV-NET-003'
        ],[
            'nama_perangkat' => 'AP Lantai 1',
            'tipe_perangkat' => 'Access Point',
            'lokasi' => 'Lantai 1',
            'ip_address' => '192.168.1.10',
            'mac_address' => '00:1A:2B:3C:4D:60',
            'status' => 'aktif',
            'tanggal_pemasangan' => Carbon::now()->subMonths(3),
            'keterangan' => 'Access point untuk coverage WiFi lantai 1'
        ]);

        $perangkat4 = PerangkatJaringan::firstOrCreate([
            'nomor_inventaris' => 'INV-NET-004'
        ],[
            'nama_perangkat' => 'AP Lantai 2',
            'tipe_perangkat' => 'Access Point',
            'lokasi' => 'Lantai 2',
            'ip_address' => '192.168.1.11',
            'mac_address' => '00:1A:2B:3C:4D:61',
            'status' => 'aktif',
            'tanggal_pemasangan' => Carbon::now()->subMonths(3),
            'keterangan' => 'Access point untuk coverage WiFi lantai 2'
        ]);

        $perangkat5 = PerangkatJaringan::firstOrCreate([
            'nomor_inventaris' => 'INV-NET-005'
        ],[
            'nama_perangkat' => 'Modem ISP',
            'tipe_perangkat' => 'Modem',
            'lokasi' => 'Ruang Server',
            'ip_address' => '192.168.0.1',
            'mac_address' => '00:1A:2B:3C:4D:62',
            'status' => 'aktif',
            'tanggal_pemasangan' => Carbon::now()->subMonths(6),
            'keterangan' => 'Modem koneksi internet dari ISP'
        ]);

        // Seed Activity Logs
        ActivityLog::create([
            'perangkat_id' => $perangkat1->id,
            'aktivitas' => 'Perangkat Ditambahkan',
            'deskripsi' => 'Perangkat Router Lantai 1 ditambahkan ke sistem',
            'tanggal_aktivitas' => Carbon::now()->subMonths(4)
        ]);

        ActivityLog::create([
            'perangkat_id' => $perangkat1->id,
            'aktivitas' => 'Perangkat Diperbarui',
            'deskripsi' => 'IP Address diubah dari 192.168.1.5 menjadi 192.168.1.1',
            'tanggal_aktivitas' => Carbon::now()->subMonths(3)
        ]);

        ActivityLog::create([
            'perangkat_id' => $perangkat2->id,
            'aktivitas' => 'Perangkat Ditambahkan',
            'deskripsi' => 'Perangkat Switch Utama ditambahkan ke sistem',
            'tanggal_aktivitas' => Carbon::now()->subMonths(4)
        ]);

        ActivityLog::create([
            'perangkat_id' => $perangkat3->id,
            'aktivitas' => 'Perangkat Ditambahkan',
            'deskripsi' => 'Perangkat AP Lantai 1 ditambahkan ke sistem',
            'tanggal_aktivitas' => Carbon::now()->subMonths(3)
        ]);

        ActivityLog::create([
            'perangkat_id' => $perangkat3->id,
            'aktivitas' => 'Perangkat Diperbarui',
            'deskripsi' => 'MAC Address diperbarui',
            'tanggal_aktivitas' => Carbon::now()->subDays(7)
        ]);

        ActivityLog::create([
            'perangkat_id' => $perangkat4->id,
            'aktivitas' => 'Perangkat Ditambahkan',
            'deskripsi' => 'Perangkat AP Lantai 2 ditambahkan ke sistem',
            'tanggal_aktivitas' => Carbon::now()->subMonths(3)
        ]);

        ActivityLog::create([
            'perangkat_id' => $perangkat5->id,
            'aktivitas' => 'Perangkat Ditambahkan',
            'deskripsi' => 'Perangkat Modem ISP ditambahkan ke sistem',
            'tanggal_aktivitas' => Carbon::now()->subMonths(6)
        ]);

        $this->call(SerialNumberDummySeeder::class);

        $this->command->info('Database seeding completed successfully!');
    }
}
