<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->string('satuan', 50)->nullable()->after('jumlah');
            $table->integer('sisa_stok')->nullable()->after('satuan');
            $table->string('kepemilikan', 100)->nullable()->after('sisa_stok');
            $table->string('status', 100)->nullable()->after('kepemilikan');
            $table->string('posisi', 255)->nullable()->after('status');
            $table->string('tahun_pengadaan', 10)->nullable()->after('posisi');
            $table->string('barang_masuk', 100)->nullable()->after('tahun_pengadaan');
            $table->string('barang_keluar', 100)->nullable()->after('barang_masuk');
        });
    }

    public function down(): void
    {
        Schema::table('barang_masuk', function (Blueprint $table) {
            $table->dropColumn([
                'satuan',
                'sisa_stok',
                'kepemilikan',
                'status',
                'posisi',
                'tahun_pengadaan',
                'barang_masuk',
                'barang_keluar',
            ]);
        });
    }
};
