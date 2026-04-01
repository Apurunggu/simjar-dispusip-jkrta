<?php

namespace App\Http\Controllers;

use App\Models\LaporanTtd;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LaporanTtdController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $query = LaporanTtd::with('cabang', 'uploader');
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
        // Ambil data dokumen pihak ke 2
        $queryPihak2 = \App\Models\DokumenBarangPihak2::with('cabang', 'uploader');
        if (!$user->hasRole('super_admin')) {
            $queryPihak2->where('cabang_id', $user->cabang_id);
        }
        $search2 = request('q2');
        if ($search2) {
            $queryPihak2->where(function($q) use ($search2) {
                $q->where('nama_laporan', 'like', "%$search2%")
                  ->orWhere('file', 'like', "%$search2%") ;
            });
        }
        $laporanPihak2 = $queryPihak2->latest()->get();
        return view('laporan_ttd.index', compact('laporans', 'search', 'laporanPihak2'));
    }

    public function create()
    {
        $cabangs = Cabang::all();
        return view('laporan_ttd.create', compact('cabangs'));
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
        $file->storeAs('public/laporan_ttd', $filename);

        LaporanTtd::create([
            'nama_laporan' => $request->nama_laporan,
            'file' => $filename,
            'cabang_id' => $request->cabang_id,
            'uploaded_by' => Auth::id(),
        ]);

        return redirect()->route('laporan-ttd.index')->with('success', 'Laporan berhasil diupload');
    }

    public function download($id)
    {
        $laporan = LaporanTtd::findOrFail($id);
        $user = Auth::user();
        if (!$user->hasRole('super_admin') && $laporan->cabang_id != $user->cabang_id) {
            abort(403);
        }
        $path = 'public/laporan_ttd/' . $laporan->file;
        if (!Storage::exists($path)) {
            abort(404, 'File tidak ditemukan');
        }
        return Storage::download($path, $laporan->nama_laporan . '.' . pathinfo($laporan->file, PATHINFO_EXTENSION));
    }
}
