<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangMasuk extends Model
{
    use HasFactory;

    protected $table = 'barang_masuk';
    protected $fillable = [
        'nomor_barang',
        'nama_barang',
        'kategori',
        'jumlah',
        'stok',
        'cabang_id',
        'tanggal_masuk',
        'keterangan',
        'dokumen', // dokumen file (pdf/word)
    ];

    protected $casts = [
        'tanggal_masuk' => 'date',
    ];

    public function distribusi()
    {
        return $this->hasMany(DistribusiBarang::class, 'barang_id');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

        public function serialNumbers()
        {
            return $this->hasMany(SerialNumber::class, 'barang_masuk_id');
        }
}

