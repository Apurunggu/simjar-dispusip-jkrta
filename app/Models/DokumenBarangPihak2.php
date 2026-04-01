<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DokumenBarangPihak2 extends Model
{
    use HasFactory;

    protected $table = 'dokumen_barang_pihak2';
    protected $fillable = [
        'nama_laporan',
        'file',
        'cabang_id',
        'uploaded_by',
    ];

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
