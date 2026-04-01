<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistribusiBarang extends Model
{
    use HasFactory;

    protected $table = 'distribusi_barangs';

    protected $fillable = [
        'barang_id',
        'cabang_asal_id',
        'cabang_tujuan_id',
        'jumlah',
        'tanggal_kirim',
        'tanggal_terima',
        'status',
        'is_terpasang',
        'keterangan',
        'user_id',
        'foto',
    ];

    protected $casts = [
        'tanggal_kirim' => 'date',
        'tanggal_terima' => 'date',
    ];

    public function barang()
    {
        return $this->belongsTo(BarangMasuk::class, 'barang_id');
    }

    public function cabangAsal()
    {
        return $this->belongsTo(Cabang::class, 'cabang_asal_id');
    }

    public function cabangTujuan()
    {
        return $this->belongsTo(Cabang::class, 'cabang_tujuan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function activityLogs()
    {
        return $this->hasMany(DistribusiActivityLog::class, 'distribusi_id');
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'pending' => 'warning',
            'dikirim' => 'info',
            'diterima' => 'success',
            'ditolak' => 'danger',
            default => 'secondary',
        };
    }
}
