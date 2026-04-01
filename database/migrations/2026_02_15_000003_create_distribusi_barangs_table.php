<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distribusi_barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('barang_id')->constrained('barang_masuk')->onDelete('cascade');
            $table->foreignId('cabang_asal_id')->constrained('cabangs')->onDelete('restrict');
            $table->foreignId('cabang_tujuan_id')->constrained('cabangs')->onDelete('restrict');
            $table->integer('jumlah');
            $table->date('tanggal_kirim');
            $table->date('tanggal_terima')->nullable();
            $table->enum('status', ['pending', 'dikirim', 'diterima', 'ditolak'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribusi_barangs');
    }
};
