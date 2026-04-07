<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Str;

class BarangMasukController extends Controller
{
    public function index(): View
    {
        $query = BarangMasuk::query();

        // Jika user bukan super_admin, filter cabang
        if (!auth()->user()->hasRole('super_admin')) {
            $userCabang = auth()->user()->cabang_id;
            $query->where('cabang_id', $userCabang);
        }

        // Search by nomor, nama, kategori, atau dokumen
        if (request()->filled('q')) {
            $q = request('q');
            $query->where(function ($builder) use ($q) {
                $builder->where('nomor_barang', 'like', "%{$q}%")
                        ->orWhere('nama_barang', 'like', "%{$q}%")
                        ->orWhere('kategori', 'like', "%{$q}%")
                        ->orWhere('dokumen', 'like', "%{$q}%");
            });
        }

        $barangMasuk = $query->orderBy('tanggal_masuk', 'desc')->paginate(15)->withQueryString();
        return view('barang_masuk.index', compact('barangMasuk'));
    }
    /**
     * Generate PDF laporan satuan untuk barang masuk (template sama dengan distribusi barang)
     */
    public function exportPdfLaporan($id)
    {
        $barang = BarangMasuk::with('cabang')->findOrFail($id);
        // Data dummy pihak pertama & kedua, bisa disesuaikan sesuai kebutuhan
        $nomor = '001/BAST-BRG/' . date('Y');
        $hari = date('l', strtotime($barang->tanggal_masuk));
        $tanggal = date('d', strtotime($barang->tanggal_masuk));
        $bulan = date('F', strtotime($barang->tanggal_masuk));
        $tahun = date('Y', strtotime($barang->tanggal_masuk));
        $jam = date('H:i', strtotime($barang->tanggal_masuk));
        $pihak_pertama = [
            'nama' => auth()->user()->name ?? '-',
            'nip' => auth()->user()->nip ?? '-',
            'pangkat' => auth()->user()->pangkat ?? '-',
            'jabatan' => auth()->user()->jabatan ?? '-',
        ];
        $pihak_kedua = [
            'nama' => $barang->cabang->penanggung_jawab ?? '-',
            'nip' => $barang->cabang->nip_penanggung_jawab ?? '-',
            'jabatan' => 'Pihak Kedua',
        ];
        $mengetahui = [
            'nama' => 'Kepala Bidang TI',
            'nip' => '-',
        ];
        $barang = [[
            'nama' => $barang->nomor_barang . ', ' . $barang->nama_barang,
            'jumlah' => $barang->jumlah,
            'keterangan' => $barang->keterangan ?? '-',
        ]];
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('barang_masuk.pdf_laporan', compact('nomor','hari','tanggal','bulan','tahun','jam','pihak_pertama','pihak_kedua','mengetahui','barang'));
        return $pdf->download('laporan-barang-' . $barang[0]['nama'] . '.pdf');
    }

    // ...existing code...

    public function create(): View
    {
        $isSuper = auth()->user()->hasRole('super_admin');
        $cabangs = $isSuper ? Cabang::all() : Cabang::where('id', auth()->user()->cabang_id)->get();
        return view('barang_masuk.create', compact('cabangs', 'isSuper'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nomor_barang' => 'required|string|max:255|unique:barang_masuk,nomor_barang',
            'nama_barang' => 'required|string|max:255',
            'kategori' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'tanggal_masuk' => 'required|date',
            'cabang_id' => 'nullable|exists:cabangs,id',
            'keterangan' => 'nullable|string',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'serial_numbers' => 'nullable|array',
            'serial_numbers.*' => 'nullable|string',
        ]);

        // Determine cabang: super_admin may choose, others must use their cabang
        if (!auth()->user()->hasRole('super_admin')) {
            $validated['cabang_id'] = auth()->user()->cabang_id;
        } else {
            // if super_admin leaves cabang_id null, default to pusat if exists
            if (empty($validated['cabang_id'])) {
                $pusat = Cabang::where('is_pusat', true)->first();
                $validated['cabang_id'] = $pusat->id ?? null;
            }
        }

        // Handle dokumen upload
        if ($request->hasFile('dokumen')) {
            $file = $request->file('dokumen');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/dokumen', $filename);
            $validated['dokumen'] = $filename;
        }

        $barangMasuk = BarangMasuk::create($validated);

        // Handle serial numbers
        if (!empty($validated['serial_numbers'])) {
            foreach ($validated['serial_numbers'] as $serial) {
                if ($serial) {
                    // Cek duplikat serial_number
                    $exists = \App\Models\SerialNumber::where('serial_number', $serial)->exists();
                    if ($exists) {
                        return redirect()->back()
                            ->withInput()
                            ->withErrors(['serial_numbers' => "Serial number '$serial' sudah terdaftar, gunakan yang lain."]);
                    }
                    $barangMasuk->serialNumbers()->create(['serial_number' => $serial]);
                }
            }
        }

        return redirect()->route('barang-masuk.index')->with('success', 'Barang berhasil ditambahkan');
    }

    public function show($id): View
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        if (!auth()->user()->hasRole('super_admin') && $barangMasuk->cabang_id != auth()->user()->cabang_id) {
            abort(403);
        }
        return view('barang_masuk.show', compact('barangMasuk'));
    }

    public function edit($id): View
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        $user = auth()->user();
        
        // Super admin bisa edit semua, admin/staff hanya cabang mereka
        if (!$user->hasRole('super_admin') && $barangMasuk->cabang_id != $user->cabang_id) {
            abort(403);
        }
        
        $isSuper = $user->hasRole('super_admin');
        $cabangs = $isSuper ? Cabang::all() : Cabang::where('id', $user->cabang_id)->get();
        return view('barang_masuk.edit', compact('barangMasuk', 'cabangs', 'isSuper'));
    }

    public function update(Request $request, $id): RedirectResponse
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        if (!auth()->user()->hasRole('super_admin') && $barangMasuk->cabang_id != auth()->user()->cabang_id) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_barang' => 'required|string|max:255',
            'jumlah' => 'required|integer|min:1',
            'tanggal_masuk' => 'required|date',
            'cabang_id' => 'nullable|exists:cabangs,id',
            'keterangan' => 'nullable|string',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx|max:5120',
            'serial_numbers' => 'nullable|array',
            'serial_numbers.*' => 'nullable|string',
        ]);

        if (!auth()->user()->hasRole('super_admin')) {
            $validated['cabang_id'] = auth()->user()->cabang_id;
        }

        // Handle dokumen upload
        if ($request->hasFile('dokumen')) {
            // Hapus file lama jika ada
            if ($barangMasuk->dokumen && \Storage::exists('public/dokumen/' . $barangMasuk->dokumen)) {
                \Storage::delete('public/dokumen/' . $barangMasuk->dokumen);
            }
            $file = $request->file('dokumen');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->storeAs('public/dokumen', $filename);
            $validated['dokumen'] = $filename;
        }

        $barangMasuk->update($validated);

        // Update serial numbers
        if (isset($validated['serial_numbers'])) {
            // Hapus semua serial lama
            $barangMasuk->serialNumbers()->delete();
            // Tambahkan serial baru jika ada
            foreach ($validated['serial_numbers'] as $serial) {
                if ($serial) {
                    $barangMasuk->serialNumbers()->create(['serial_number' => $serial]);
                }
            }
        }

        return redirect()->route('barang-masuk.index')->with('success', 'Barang berhasil diperbarui');
    }

    public function destroy($id): RedirectResponse
    {
        $barangMasuk = BarangMasuk::findOrFail($id);
        if (!auth()->user()->hasRole('super_admin') && $barangMasuk->cabang_id != auth()->user()->cabang_id) {
            abort(403);
        }
        $barangMasuk->delete();
        return redirect()->route('barang-masuk.index')->with('success', 'Barang berhasil dihapus');
    }

    public function exportPdf(): \Symfony\Component\HttpFoundation\Response
    {
        $barangMasuk = BarangMasuk::all();
        $pdf = Pdf::loadView('barang_masuk.pdf', compact('barangMasuk'));
        return $pdf->download('barang_masuk_' . date('Y-m-d') . '.pdf');
    }

    public function importForm(): View
    {
        return view('barang_masuk.import');
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $file = $request->file('file');

        // Load spreadsheet
        $spreadsheet = IOFactory::load($file->getPathname());
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        // Expect header on first row: nomor_barang,nama_barang,kategori,jumlah,tanggal_masuk,keterangan
        $header = array_map('strtolower', array_map('trim', $rows[0] ?? []));

        for ($i = 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            if (empty(array_filter($row))) continue; // skip empty rows

            $data = [];
            // map by header if present, otherwise use columns
            if (in_array('nomor_barang', $header)) {
                $map = array_combine($header, $row);
                $data['nomor_barang'] = Str::upper(trim($map['nomor_barang'] ?? ''));
                $data['nama_barang'] = $map['nama_barang'] ?? '';
                $data['kategori'] = $map['kategori'] ?? '';
                $data['jumlah'] = (int) ($map['jumlah'] ?? 0);
                $data['tanggal_masuk'] = !empty($map['tanggal_masuk']) ? date('Y-m-d', strtotime($map['tanggal_masuk'])) : null;
                $data['keterangan'] = $map['keterangan'] ?? null;
            } else {
                // fallback fixed columns
                $data['nomor_barang'] = Str::upper(trim($row[0] ?? ''));
                $data['nama_barang'] = $row[1] ?? '';
                $data['kategori'] = $row[2] ?? '';
                $data['jumlah'] = (int) ($row[3] ?? 0);
                $data['tanggal_masuk'] = !empty($row[4]) ? date('Y-m-d', strtotime($row[4])) : null;
                $data['keterangan'] = $row[5] ?? null;
            }

            if (empty($data['nomor_barang']) || empty($data['nama_barang'])) continue;

            // set cabang to current user's cabang by default
            $data['cabang_id'] = auth()->user()->cabang_id ?? null;

            // avoid duplicate nomor_barang
            BarangMasuk::updateOrCreate(
                ['nomor_barang' => $data['nomor_barang']],
                $data
            );
        }

        return redirect()->route('barang-masuk.index')->with('success', 'Import selesai');
    }
}
