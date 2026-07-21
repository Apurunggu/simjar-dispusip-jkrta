<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->text('message');
            $table->string('type')->default('general'); // barang_masuk, perangkat_jaringan, distribusi, laporan, general
            $table->unsignedBigInteger('related_id')->nullable();
            $table->string('related_type')->nullable(); // BarangMasuk, PerangkatJaringan, DistribusiBarang, dll
            $table->string('icon')->default('bi-bell'); // Bootstrap icon class
            $table->string('color')->default('info'); // primary, success, warning, danger, info
            $table->string('action_url')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();

            // Index untuk query yang sering
            $table->index('user_id');
            $table->index('read_at');
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
