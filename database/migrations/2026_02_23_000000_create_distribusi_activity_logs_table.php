<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distribusi_activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('distribusi_id')->constrained('distribusi_barangs')->onDelete('cascade');
            $table->string('aktivitas'); // e.g., 'Status Diubah'
            $table->string('status_awal')->nullable(); // e.g., 'pending'
            $table->string('status_baru'); // e.g., 'dikirim'
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('catatan')->nullable();
            $table->timestamp('tanggal_aktivitas');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distribusi_activity_logs');
    }
};
