<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PerangkatJaringan extends Model
{
    use HasFactory;

    protected $table = 'perangkat_jaringan';
    protected $fillable = [
        'nomor_inventaris',
        'nama_perangkat',
        'tipe_perangkat',
        'lokasi',
        'ip_address',
        'mac_address',
        'status',
        'tanggal_pemasangan',
        'keterangan',
    ];

    protected $casts = [
        'tanggal_pemasangan' => 'date',
    ];

    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class, 'perangkat_id');
    }
}
