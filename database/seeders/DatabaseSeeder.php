<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\BarangMasuk;
use App\Models\PerangkatJaringan;
use App\Models\ActivityLog;
use Carbon\Carbon;
use Database\Seeders\SerialNumberDummySeeder;
use Database\Seeders\RoleSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Run RoleSeeder first to create roles, cabangs and users
        $this->call(RoleSeeder::class);

        // Seed Barang Masuk dengan data yang lebih lengkap
        $barangData = [
            ['nomor' => 'BRG-001', 'nama' => 'Router TP-Link TL-WR840N', 'kategori' => 'Router', 'jumlah' => 250, 'keterangan' => 'Router WiFi 300Mbps'],
            ['nomor' => 'BRG-002', 'nama' => 'Switch Netgear GS310TP', 'kategori' => 'Switch', 'jumlah' => 180, 'keterangan' => 'Switch 10 Port dengan PoE'],
            ['nomor' => 'BRG-003', 'nama' => 'Modem ZTE F609', 'kategori' => 'Modem', 'jumlah' => 150, 'keterangan' => 'Modem GPON'],
            ['nomor' => 'BRG-004', 'nama' => 'Access Point Ubiquiti', 'kategori' => 'Access Point', 'jumlah' => 320, 'keterangan' => 'AP Enterprise'],
            ['nomor' => 'BRG-005', 'nama' => 'Kabel UTP Cat6 100m', 'kategori' => 'Kabel', 'jumlah' => 1200, 'keterangan' => 'Kabel Cat6'],
            ['nomor' => 'BRG-006', 'nama' => 'Kabel RJ45', 'kategori' => 'Kabel', 'jumlah' => 2000, 'keterangan' => 'Kabel LAN'],
            ['nomor' => 'BRG-007', 'nama' => 'Fiber Optic Cable', 'kategori' => 'Kabel', 'jumlah' => 500, 'keterangan' => 'Kabel Fiber'],
            ['nomor' => 'BRG-008', 'nama' => 'Patch Panel 24 Port', 'kategori' => 'Rack', 'jumlah' => 80, 'keterangan' => 'Patch Panel'],
            ['nomor' => 'BRG-009', 'nama' => 'Converter RJ45 ke Fiber', 'kategori' => 'Converter', 'jumlah' => 120, 'keterangan' => 'Media Converter'],
            ['nomor' => 'BRG-010', 'nama' => 'PoE Injector', 'kategori' => 'Perangkat Jaringan', 'jumlah' => 300, 'keterangan' => 'Power over Ethernet'],
            ['nomor' => 'BRG-011', 'nama' => 'Network Analyzer', 'kategori' => 'Testing Equipment', 'jumlah' => 15, 'keterangan' => 'Network Analyzer'],
            ['nomor' => 'BRG-012', 'nama' => 'Optical Splitter 1:32', 'kategori' => 'Splitter', 'jumlah' => 650, 'keterangan' => 'Optical Splitter'],
            ['nomor' => 'BRG-013', 'nama' => 'ONU (Optical Network Unit)', 'kategori' => 'ONT', 'jumlah' => 2400, 'keterangan' => 'Optical Network Unit'],
            ['nomor' => 'BRG-014', 'nama' => 'FTTH Terminal Box', 'kategori' => 'Housing', 'jumlah' => 800, 'keterangan' => 'Terminal Box'],
            ['nomor' => 'BRG-015', 'nama' => 'Splitter Cable 1:2', 'kategori' => 'Splitter', 'jumlah' => 1100, 'keterangan' => 'Cable Splitter'],
            ['nomor' => 'BRG-016', 'nama' => 'Connector SC/APC', 'kategori' => 'Connector', 'jumlah' => 3200, 'keterangan' => 'Fiber Connector'],
            ['nomor' => 'BRG-017', 'nama' => 'Network Cabinet', 'kategori' => 'Rack', 'jumlah' => 45, 'keterangan' => 'Server Cabinet'],
            ['nomor' => 'BRG-018', 'nama' => 'Cable Tray', 'kategori' => 'Rack', 'jumlah' => 350, 'keterangan' => 'Kabel Management'],
            ['nomor' => 'BRG-019', 'nama' => 'Outlet Box Wall Mount', 'kategori' => 'Housing', 'jumlah' => 900, 'keterangan' => 'Wall Outlet'],
            ['nomor' => 'BRG-020', 'nama' => 'Test Point Aerial', 'kategori' => 'Testing Equipment', 'jumlah' => 120, 'keterangan' => 'Test Point'],
        ];

        foreach ($barangData as $data) {
            BarangMasuk::firstOrCreate([
                'nomor_barang' => $data['nomor']
            ], [
                'nama_barang' => $data['nama'],
                'kategori' => $data['kategori'],
                'jumlah' => $data['jumlah'],
                'stok' => $data['jumlah'], // Set stok sama dengan jumlah
                'tanggal_masuk' => Carbon::now()->subMonths(rand(1, 6)),
                'keterangan' => $data['keterangan']
            ]);
        }

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
