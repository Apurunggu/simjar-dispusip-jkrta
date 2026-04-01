<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    use HasFactory;

    protected $table = 'cabangs';

    protected $fillable = [
        'nama_cabang',
        'alamat',
        'kota',
        'provinsi',
        'kode_cabang',
        'is_pusat',
    ];

    protected $casts = [
        'is_pusat' => 'boolean',
    ];

    public function distribusiAsal()
    {
        return $this->hasMany(DistribusiBarang::class, 'cabang_asal_id');
    }

    public function distribusiTujuan()
    {
        return $this->hasMany(DistribusiBarang::class, 'cabang_tujuan_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
