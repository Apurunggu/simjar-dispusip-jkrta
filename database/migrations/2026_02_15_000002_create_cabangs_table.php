<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cabangs', function (Blueprint $table) {
            $table->id();
            $table->string('nama_cabang')->unique();
            $table->string('alamat');
            $table->string('kota')->nullable();
            $table->string('provinsi')->nullable();
            $table->string('kode_cabang')->unique()->nullable();
            $table->boolean('is_pusat')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cabangs');
    }
};
