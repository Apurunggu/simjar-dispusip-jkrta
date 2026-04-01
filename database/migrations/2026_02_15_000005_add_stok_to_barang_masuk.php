<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->integer('stok')->default(0)->after('jumlah');
        });

        // Set stok = jumlah untuk semua barang yang sudah ada
        \DB::statement('UPDATE barang_masuk SET stok = jumlah WHERE stok = 0');
    }

    public function down(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropColumn('stok');
        });
    }
};
