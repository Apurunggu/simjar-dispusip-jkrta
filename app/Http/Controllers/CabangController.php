<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CabangController extends Controller
{
    public function index(): View
    {
        $cabangs = Cabang::all();
        return view('cabang.index', compact('cabangs'));
    }

    public function edit($id): View
    {
        $cabang = Cabang::findOrFail($id);
        return view('cabang.edit', compact('cabang'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $cabang = Cabang::findOrFail($id);
        $request->validate([
            'nama_cabang' => 'required',
            'alamat' => 'nullable',
            'kota' => 'nullable',
            'provinsi' => 'nullable',
            'kode_cabang' => 'nullable',
        ]);
        $cabang->update($request->only(['nama_cabang','alamat','kota','provinsi','kode_cabang']));
        return redirect()->route('cabang.index')->with('success', 'Data cabang berhasil diupdate!');
    }
}
