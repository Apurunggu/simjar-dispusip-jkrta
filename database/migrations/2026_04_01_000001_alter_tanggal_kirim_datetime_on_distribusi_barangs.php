<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('distribusi_barangs', function (Blueprint $table) {
            $table->dateTime('tanggal_kirim')->change();
        });
    }

    public function down(): void
    {
        Schema::table('distribusi_barangs', function (Blueprint $table) {
            $table->date('tanggal_kirim')->change();
        });
    }
};
