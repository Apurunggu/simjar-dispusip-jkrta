<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Cabang;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create roles
        $roles = [
            ['name' => 'super_admin', 'label' => 'Super Admin', 'description' => 'Lihat semua cabang'],
            ['name' => 'admin_cabang', 'label' => 'Admin Cabang', 'description' => 'Hanya lihat cabangnya'],
            ['name' => 'staff', 'label' => 'Staff', 'description' => 'Input distribusi'],
            ['name' => 'user', 'label' => 'User', 'description' => 'Hanya lihat laporan'],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }

        // Create cabangs
        $cabangs = [
            ['nama_cabang' => 'DISPUSIP Pusat', 'alamat' => 'Jl. Pusat', 'kota' => 'Jakarta', 'provinsi' => 'DKI Jakarta', 'kode_cabang' => 'PUSAT', 'is_pusat' => true],
            ['nama_cabang' => 'Cabang Utara', 'alamat' => 'Jl. Utara', 'kota' => 'Bekasi', 'provinsi' => 'Jawa Barat', 'kode_cabang' => 'UTARA', 'is_pusat' => false],
            ['nama_cabang' => 'Admin Cabang 2', 'alamat' => 'Jl. Selatan', 'kota' => 'Tangerang', 'provinsi' => 'Banten', 'kode_cabang' => 'SELATAN', 'is_pusat' => false],
            ['nama_cabang' => 'Admin Cabang 3', 'alamat' => 'Jl. Timur', 'kota' => 'Bogor', 'provinsi' => 'Jawa Barat', 'kode_cabang' => 'TIMUR', 'is_pusat' => false],
            ['nama_cabang' => 'Admin Cabang 4', 'alamat' => 'Jl. Barat', 'kota' => 'Depok', 'provinsi' => 'Jawa Barat', 'kode_cabang' => 'BARAT', 'is_pusat' => false],
            ['nama_cabang' => 'Admin Cabang 5', 'alamat' => 'Jl. Selatan 2', 'kota' => 'Bekasi', 'provinsi' => 'Jawa Barat', 'kode_cabang' => 'SELATAN2', 'is_pusat' => false],
        ];

        foreach ($cabangs as $cabang) {
            Cabang::updateOrCreate(['kode_cabang' => $cabang['kode_cabang']], $cabang);
        }

        // Create default users
        $superAdminRole = Role::where('name', 'super_admin')->first();
        $adminCabangRole = Role::where('name', 'admin_cabang')->first();
        $staffRole = Role::where('name', 'staff')->first();
        $userRole = Role::where('name', 'user')->first();

        $pusat = Cabang::where('kode_cabang', 'PUSAT')->first();
        $utara = Cabang::where('kode_cabang', 'UTARA')->first();
        $selatan = Cabang::where('kode_cabang', 'SELATAN')->first();
        $timur = Cabang::where('kode_cabang', 'TIMUR')->first();
        $barat = Cabang::where('kode_cabang', 'BARAT')->first();
        $selatan2 = Cabang::where('kode_cabang', 'SELATAN2')->first();

        // Super Admin (Pusat)
        User::firstOrCreate(
            ['email' => 'admin@simjar.test'],
            [
                'name' => 'Super Admin',
                'email' => 'admin@simjar.test',
                'password' => Hash::make('password123'),
                'role_id' => $superAdminRole->id,
                'cabang_id' => $pusat->id,
            ]
        );

        // Admin Cabang 2
        User::updateOrCreate(
            ['email' => 'admin2@simjar.test'],
            [
                'name' => 'Admin Cabang 2',
                'email' => 'admin2@simjar.test',
                'password' => Hash::make('password123'),
                'role_id' => $adminCabangRole->id,
                'cabang_id' => $utara ? $utara->id : null,
            ]
        );

        // Admin Cabang 3
        User::updateOrCreate(
            ['email' => 'admin3@simjar.test'],
            [
                'name' => 'Admin Cabang 3',
                'email' => 'admin3@simjar.test',
                'password' => Hash::make('password123'),
                'role_id' => $adminCabangRole->id,
                'cabang_id' => $selatan ? $selatan->id : null,
            ]
        );

        // Admin Cabang 4 - Timur
        User::updateOrCreate(
            ['email' => 'admin4@simjar.test'],
            [
                'name' => 'Admin Cabang Timur',
                'email' => 'admin4@simjar.test',
                'password' => Hash::make('password123'),
                'role_id' => $adminCabangRole->id,
                'cabang_id' => $timur ? $timur->id : null,
            ]
        );

        // Admin Cabang 5 - Barat
        User::updateOrCreate(
            ['email' => 'admin5@simjar.test'],
            [
                'name' => 'Admin Cabang Barat',
                'email' => 'admin5@simjar.test',
                'password' => Hash::make('password123'),
                'role_id' => $adminCabangRole->id,
                'cabang_id' => $barat ? $barat->id : null,
            ]
        );

        // Admin Cabang Utara
        User::firstOrCreate(
            ['email' => 'admin.utara@simjar.test'],
            [
                'name' => 'Admin Cabang Utara',
                'email' => 'admin.utara@simjar.test',
                'password' => Hash::make('password'),
                'role_id' => $adminCabangRole->id,
                'cabang_id' => $utara->id,
            ]
        );

        // Admin Cabang Selatan
        User::firstOrCreate(
            ['email' => 'admin.selatan@simjar.test'],
            [
                'name' => 'Admin Cabang Selatan',
                'email' => 'admin.selatan@simjar.test',
                'password' => Hash::make('password'),
                'role_id' => $adminCabangRole->id,
                'cabang_id' => $selatan->id,
            ]
        );

        // Staff (Pusat)
        User::firstOrCreate(
            ['email' => 'staff@simjar.test'],
            [
                'name' => 'Staff Pusat',
                'email' => 'staff@simjar.test',
                'password' => Hash::make('password'),
                'role_id' => $staffRole->id,
                'cabang_id' => $pusat->id,
            ]
        );

        // User (Viewer)
        User::firstOrCreate(
            ['email' => 'user@simjar.test'],
            [
                'name' => 'User Laporan',
                'email' => 'user@simjar.test',
                'password' => Hash::make('password'),
                'role_id' => $userRole->id,
                'cabang_id' => $pusat->id,
            ]
        );
    }
}
