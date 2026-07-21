<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type', // 'barang_masuk', 'perangkat_jaringan', 'distribusi', 'laporan', 'general'
        'related_id', // ID dari entity yang berhubungan (barang_masuk_id, perangkat_id, dll)
        'related_type', // Tipe entity (BarangMasuk, PerangkatJaringan, dll)
        'icon', // Icon untuk notifikasi
        'color', // Warna badge (primary, success, warning, danger, info)
        'read_at',
        'action_url',
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Scope untuk notifikasi yang belum dibaca
    public function scopeUnread($query)
    {
        return $query->whereNull('read_at');
    }

    // Scope untuk notifikasi untuk role tertentu
    public function scopeForRole($query, $role)
    {
        return $query->whereHas('user.role', function ($q) use ($role) {
            $q->where('name', $role);
        });
    }

    // Mark as read
    public function markAsRead()
    {
        $this->update(['read_at' => now()]);
    }

    // Check if notification is read
    public function isRead()
    {
        return $this->read_at !== null;
    }
}
