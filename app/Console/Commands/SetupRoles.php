<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SetupRoles extends Command
{
    protected $signature = 'setup:roles';
    protected $description = 'Setup roles table and seed default roles';

    public function handle()
    {
        // Create users table first if it doesn't exist
        if (!Schema::hasTable('users')) {
            Schema::create('users', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('email')->unique();
                $table->string('password');
                $table->rememberToken();
                $table->timestamps();
            });
            $this->info('Users table created');
        }

        // Create roles table if not exists
        if (!Schema::hasTable('roles')) {
            Schema::create('roles', function ($table) {
                $table->id();
                $table->string('name')->unique();
                $table->string('label');
                $table->text('description')->nullable();
                $table->timestamps();
            });
            $this->info('Roles table created');
        } else {
            $this->info('Roles table already exists');
        }

        // Add role_id to users if not exists
        if (!Schema::hasColumn('users', 'role_id')) {
            Schema::table('users', function ($table) {
                $table->unsignedBigInteger('role_id')->nullable()->after('password');
                $table->foreign('role_id')->references('id')->on('roles')->onDelete('set null');
            });
            $this->info('role_id column added to users table');
        } else {
            $this->info('role_id column already exists in users table');
        }

        // Seed roles
        $roles = [
            ['name' => 'super_admin', 'label' => 'Super Admin', 'description' => 'Lihat semua cabang'],
            ['name' => 'admin_cabang', 'label' => 'Admin Cabang', 'description' => 'Hanya lihat cabangnya'],
            ['name' => 'staff', 'label' => 'Staff', 'description' => 'Input distribusi'],
            ['name' => 'user', 'label' => 'User', 'description' => 'Hanya lihat laporan'],
        ];

        foreach ($roles as $role) {
            DB::table('roles')->updateOrInsert(
                ['name' => $role['name']],
                $role
            );
            $this->info("Role '{$role['name']}' created or updated");
        }

        $this->info('Setup complete!');
    }
}
