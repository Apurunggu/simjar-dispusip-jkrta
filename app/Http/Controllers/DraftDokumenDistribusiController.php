<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\DistribusiBarang;

class DraftDokumenDistribusiController extends Controller
{
    public function index(Request $request)
    {
        $query = DistribusiBarang::whereNotNull('dokumen_pdf');
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->whereHas('barang', function($b) use ($search) {
                    $b->where('nama_barang', 'like', "%$search%")
                      ->orWhere('nomor_barang', 'like', "%$search%");
                })
                ->orWhereHas('cabangTujuan', function($c) use ($search) {
                    $c->where('nama_cabang', 'like', "%$search%");
                })
                ->orWhere('jumlah', 'like', "%$search%")
                ->orWhere('keterangan', 'like', "%$search%");
            });
        }
        $drafts = $query->orderByDesc('updated_at')->get();
        return view('draft-dokumen-distribusi.index', compact('drafts'));
    }

    public function create()
    {
        $distribusi = DistribusiBarang::orderByDesc('updated_at')->get();
        return view('draft-dokumen-distribusi.create', compact('distribusi'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'distribusi_id' => 'required|exists:distribusi_barangs,id',
            'dokumen_pdf' => 'required|file|mimes:pdf|max:5120',
        ]);
        $distribusi = DistribusiBarang::findOrFail($request->distribusi_id);
        $path = $request->file('dokumen_pdf')->store('dokumen/distribusi', 'public');
        $distribusi->dokumen_pdf = $path;
        $distribusi->save();
        return redirect()->route('draft-dokumen-distribusi.index')->with('success', 'Draft dokumen berhasil diupload!');
    }

    public function download($id)
    {
        $distribusi = DistribusiBarang::findOrFail($id);
        if (!$distribusi->dokumen_pdf || !Storage::disk('public')->exists($distribusi->dokumen_pdf)) {
            abort(404);
        }
        return Storage::disk('public')->download($distribusi->dokumen_pdf);
    }

    public function show($id)
    {
        $draft = DistribusiBarang::with(['barang', 'cabangTujuan'])->findOrFail($id);
        return view('draft-dokumen-distribusi.show', compact('draft'));
    }

    public function destroy($id)
    {
        $distribusi = DistribusiBarang::findOrFail($id);
        // Hapus file PDF jika ada
        if ($distribusi->dokumen_pdf && Storage::disk('public')->exists($distribusi->dokumen_pdf)) {
            Storage::disk('public')->delete($distribusi->dokumen_pdf);
        }
        $distribusi->delete();
        return redirect()->route('draft-dokumen-distribusi.index')->with('success', 'Draft dokumen distribusi berhasil dihapus!');
    }
}
