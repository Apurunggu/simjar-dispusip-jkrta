<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DistribusiActivityLog extends Model
{
    protected $fillable = [
        'distribusi_id',
        'aktivitas',
        'status_awal',
        'status_baru',
        'user_id',
        'catatan',
        'tanggal_aktivitas',
    ];

    protected $casts = [
        'tanggal_aktivitas' => 'datetime',
    ];

    public function distribusi(): BelongsTo
    {
        return $this->belongsTo(DistribusiBarang::class, 'distribusi_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
