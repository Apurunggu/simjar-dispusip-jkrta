<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('distribusi_barangs', function (Blueprint $table) {
            $table->enum('is_terpasang', ['terpasang', 'tidak_terpasang'])->default('tidak_terpasang')->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('distribusi_barangs', function (Blueprint $table) {
            $table->dropColumn('is_terpasang');
        });
    }
};
