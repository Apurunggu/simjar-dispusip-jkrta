<?php
namespace App\Http\Controllers;

use App\Models\DokumenBarangPihak2;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DokumenBarangPihak2Controller extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = DokumenBarangPihak2::with('cabang', 'uploader');
        if (!$user->hasRole('super_admin')) {
            $query->where('cabang_id', $user->cabang_id);
        }
        $search = request('q');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nama_laporan', 'like', "%$search%")
                  ->orWhere('file', 'like', "%$search%") ;
            });
        }
        $laporans = $query->latest()->get();
        return view('dokumen-barang-pihak2.index', compact('laporans', 'search'));
    }

    public function create()
    {
        $cabangs = Cabang::all();
        return view('dokumen-barang-pihak2.create', compact('cabangs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_laporan' => 'required|string|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx|max:5120',
            'cabang_id' => 'nullable|exists:cabangs,id',
        ]);

        $file = $request->file('file');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->storeAs('public/dokumen_barang_pihak2', $filename);

        DokumenBarangPihak2::create([
            'nama_laporan' => $request->nama_laporan,
            'file' => $filename,
            'cabang_id' => $request->cabang_id,
            'uploaded_by' => Auth::id(),
        ]);

        return redirect()->route('dokumen-barang-pihak2.index')->with('success', 'Laporan berhasil diupload');
    }

    public function download($id)
    {
        $laporan = DokumenBarangPihak2::findOrFail($id);
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && $laporan->cabang_id != $user->cabang_id) {
            abort(403);
        }
        $path = 'public/dokumen_barang_pihak2/' . $laporan->file;
        if (!Storage::exists($path)) {
            abort(404, 'File tidak ditemukan');
        }
        return Storage::download($path, $laporan->nama_laporan . '.' . pathinfo($laporan->file, PATHINFO_EXTENSION));
    }
}
