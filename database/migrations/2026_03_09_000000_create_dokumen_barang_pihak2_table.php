<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('dokumen_barang_pihak2', function (Blueprint $table) {
            $table->id();
            $table->string('nama_laporan');
            $table->string('file');
            $table->unsignedBigInteger('cabang_id')->nullable();
            $table->unsignedBigInteger('uploaded_by')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dokumen_barang_pihak2');
    }
};
