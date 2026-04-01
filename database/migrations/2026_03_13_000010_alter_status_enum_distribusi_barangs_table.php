<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE distribusi_barangs MODIFY status ENUM('pending', 'dikirim', 'diterima', 'ditolak', 'terpasang', 'tidak_terpasang') DEFAULT 'pending'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE distribusi_barangs MODIFY status ENUM('pending', 'dikirim', 'diterima', 'ditolak') DEFAULT 'pending'");
    }
};
