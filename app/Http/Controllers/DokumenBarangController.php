<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LaporanTtd;
use App\Models\DokumenBarangPihak2;

class DokumenBarangController extends Controller
{
    public function pihak1(Request $request)
    {
        $q = $request->q;
        $query = LaporanTtd::with('cabang', 'uploader');
        if ($q) {
            $query->where('nama_laporan', 'like', "%$q%");
        }
        $laporans = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        return view('dokumen.pihak1', compact('laporans', 'q'));
    }

    public function pihak2(Request $request)
    {
        $q = $request->q;
        $query = DokumenBarangPihak2::with('cabang', 'uploader');
        if ($q) {
            $query->where('nama_laporan', 'like', "%$q%");
        }
        $laporans = $query->orderByDesc('created_at')->paginate(10)->withQueryString();
        return view('dokumen.pihak2', compact('laporans', 'q'));
    }
}
