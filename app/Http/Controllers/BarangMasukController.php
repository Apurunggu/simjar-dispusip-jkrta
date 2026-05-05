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
use Illuminate\Support\Facades\DB;

class BarangMasukController extends Controller
{
    public function index(): View
    {
        $query = BarangMasuk::query();

        // Jika user bukan super_admin, filter cabang
        if (!auth()->user()->hasRole('super_admin')) {
            $userCabang = auth()->user()->cabang_id;
            // Handle NULL cabang_id - gunakan whereNull atau where
            if ($userCabang) {
                $query->where('cabang_id', $userCabang);
            } else {
                // Jika user tidak punya cabang, tampilkan data kosong (aman)
                $query->whereNull('cabang_id');
            }
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
            'cabang_id' => auth()->user()->hasRole('super_admin') ? 'required|exists:cabangs,id' : 'nullable|exists:cabangs,id',
            'keterangan' => 'nullable|string',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:5120',
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
            'cabang_id' => auth()->user()->hasRole('super_admin') ? 'required|exists:cabangs,id' : 'nullable|exists:cabangs,id',
            'keterangan' => 'nullable|string',
            'dokumen' => 'nullable|file|mimes:pdf,doc,docx|mimetypes:application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document|max:5120',
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
            'file' => 'required|file|mimes:xlsx,xls,csv|mimetypes:application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,text/csv',
        ]);

        // Validasi cabang user
        $userCabangId = auth()->user()->cabang_id;
        if (empty($userCabangId)) {
            return redirect()->back()->with('error', '❌ Akun Anda tidak memiliki cabang yang valid. Hubungi administrator untuk menetapkan cabang.');
        }

        // Wrap entire import dalam transaction untuk data consistency
        return DB::transaction(function () use ($request, $userCabangId) {
        try {
            $file = $request->file('file');
            $spreadsheet = IOFactory::load($file->getPathname());
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

        if (count($rows) < 2) {
            return redirect()->back()->with('error', 'File Excel kosong atau hanya memiliki header.');
        }

        // Cari baris header (baris yang berisi "nama" dan "qty")
        $headerRowIndex = 0;
        for ($i = 0; $i < min(10, count($rows)); $i++) {
            $rowStr = implode("|", $rows[$i] ?? []);
            if ((stripos($rowStr, 'nama') !== false || stripos($rowStr, 'perangkat') !== false) && 
                (stripos($rowStr, 'qty') !== false || stripos($rowStr, 'jumlah') !== false)) {
                $headerRowIndex = $i;
                break;
            }
        }

        // Normalisasi header: hilangkan spasi, lowercase, remove special chars
        $rawHeader = $rows[$headerRowIndex] ?? [];
        $header = [];
        $headerMap = []; // mapping original index ke normalized name
        
        foreach ($rawHeader as $idx => $h) {
            if (empty($h)) {
                $header[$idx] = null;
                continue;
            }
            $normalized = strtolower(trim($h));
            // Hapus spasi, tab, newline tapi preserve slash untuk "merk/type"
            $normalized = str_replace(["\t", "\n", "\r", "  "], '', $normalized);
            $normalized = preg_replace('/\s+/', '', $normalized); // Hapus semua whitespace
            $header[$idx] = $normalized;
            $headerMap[$normalized] = $h; // simpan original nama
        }

        // Helper function untuk mencari kolom dengan fuzzy matching lebih ketat
        $findColumn = function($searchTerms) use ($header) {
            $searchTerms = (array) $searchTerms;
            foreach ($searchTerms as $term) {
                // Normalize search term (hapus spasi, lowercase)
                $termNormalized = strtolower(trim($term));
                $termNormalized = preg_replace('/\s+/', '', $termNormalized);
                
                foreach ($header as $idx => $colName) {
                    if ($colName === null) continue;
                    
                    // Exact match
                    if ($colName === $termNormalized) {
                        return $idx;
                    }
                    
                    // Partial match (term di dalam colName atau sebaliknya)
                    if (strpos($colName, $termNormalized) !== false || 
                        strpos($termNormalized, $colName) !== false) {
                        return $idx;
                    }
                }
            }
            return null;
        };

        // Deteksi kolom penting (fuzzy matching dengan lebih banyak variasi)
        $colName = $findColumn([
            'nama perangkat', 'namaperangkat', 'nama_perangkat', 
            'nama barang', 'namabarang', 'nama_barang'
        ]);
        $colQty = $findColumn([
            'qty', 'jumlah', 'quantity', 'jumah',
            'kuantitas', 'kuantitas'
        ]);
        $colMerk = $findColumn([
            'merk/type', 'merktype', 'merk', 'type', 'jenis',
            'merk type', 'merktype', 'merk/jenis'
        ]);
        $colNo = $findColumn([
            'no', 'nomor', 'nomor_barang', 'nomorbarang',
            'nomor barang', 'no barang', 'nobarang'
        ]);
        $colStok = $findColumn([
            'sisa stok', 'sisastok', 'stok', 'sisa_stok',
            'stock', 'sisa stock', 'sisastock'
        ]);
        $colKeterangan = $findColumn([
            'keterangan', 'ket', 'catatan', 'notes',
            'deskripsi', 'deskripsi'
        ]);
        $colTahun = $findColumn([
            'tahun pengadaan', 'tahunpengadaan', 'tahun_pengadaan',
            'tahun', 'tahun masuk', 'tahunmasuk'
        ]);
        $colSatuan = $findColumn([
            'satuan', 'unit', 'satuan barang'
        ]);
        $colKepemilikan = $findColumn([
            'kepemilikan', 'pemilik', 'ownership',
            'status kepemilikan', 'statuskepemilikan'
        ]);
        $colStatus = $findColumn([
            'status', 'kondisi', 'status barang'
        ]);
        $colPosisi = $findColumn([
            'posisi', 'lokasi', 'tempat',
            'lokasi barang', 'lokasibarang', 'posisi barang'
        ]);

        // Validasi kolom wajib
        if ($colName === null || $colQty === null) {
            $missing = [];
            if ($colName === null) $missing[] = 'NAMA PERANGKAT atau nama_barang';
            if ($colQty === null) $missing[] = 'QTY atau Jumlah';
            return redirect()->back()->with('error', 'Kolom wajib tidak ditemukan: ' . implode(', ', $missing));
        }

        $imported = 0;
        $updated = 0;
        $errors = [];

        for ($i = $headerRowIndex + 1; $i < count($rows); $i++) {
            $row = $rows[$i];
            
            // Skip empty rows
            if (empty(array_filter($row))) continue;

            try {
                // Extract data from row
                $nama = isset($row[$colName]) ? trim($row[$colName]) : null;
                $qty = isset($row[$colQty]) ? trim($row[$colQty]) : null;
                $merk = isset($row[$colMerk]) ? trim($row[$colMerk]) : null;
                $no = isset($row[$colNo]) ? trim($row[$colNo]) : null;
                $stok = isset($row[$colStok]) ? trim($row[$colStok]) : null;
                $ket = isset($row[$colKeterangan]) ? trim($row[$colKeterangan]) : null;
                $tahun = isset($row[$colTahun]) ? trim($row[$colTahun]) : null;

                // Validasi data minimal
                if (empty($nama)) continue;
                
                // Convert qty to number
                $qty = is_numeric($qty) ? intval($qty) : 0;
                if ($qty <= 0) continue;

                // Set kategori dari merk/type atau default
                $kategori = !empty($merk) ? $merk : 'Uncategorized';

                // Set stok default ke qty jika tidak ada
                $stok = !empty($stok) && is_numeric($stok) ? intval($stok) : $qty;

                // Cek duplikat berdasarkan nama_barang + kategori + cabang
                $existingItem = BarangMasuk::where('nama_barang', $nama)
                    ->where('kategori', $kategori)
                    ->where('cabang_id', $userCabangId)
                    ->first();

                $data = [
                    'nama_barang' => $nama,
                    'kategori' => $kategori,
                    'jumlah' => $qty,
                    'stok' => $stok,
                    'keterangan' => $ket,
                    'tanggal_masuk' => date('Y-m-d'),
                    'cabang_id' => $userCabangId,
                ];

                if ($existingItem) {
                    // Update existing item
                    $existingItem->update($data);
                    $updated++;
                } else {
                    // Generate nomor_barang otomatis jika tidak ada
                    if (empty($no)) {
                        $kategoriPrefix = strtoupper(substr($kategori, 0, 3));
                        $lastNum = BarangMasuk::where('nomor_barang', 'LIKE', 'BRG-' . $kategoriPrefix . '%')
                            ->where('cabang_id', $userCabangId)
                            ->max(DB::raw("CAST(SUBSTRING(nomor_barang, -4) AS UNSIGNED)")) ?? 0;
                        $nextNum = $lastNum + 1;
                        $no = 'BRG-' . $kategoriPrefix . '-' . str_pad($nextNum, 4, '0', STR_PAD_LEFT);
                    }
                    
                    $data['nomor_barang'] = $no;
                    BarangMasuk::create($data);
                    $imported++;
                }
            } catch (\Throwable $e) {
                $errors[] = 'Baris ' . ($i+1) . ': ' . $e->getMessage();
            }
        }

        $total = $imported + $updated;
        if ($total === 0) {
            $msg = 'Tidak ada data yang berhasil diimport.';
            if ($errors) $msg .= ' Error: ' . implode(' | ', array_slice($errors, 0, 3));
            return redirect()->back()->with('error', $msg);
        }

        $msg = "Import selesai! {$imported} data baru dibuat, {$updated} data diupdate.";
        if ($errors) $msg .= ' (' . count($errors) . ' baris gagal)';
        return redirect()->route('barang-masuk.index')->with('success', $msg);

        } catch (\Throwable $e) {
            // Rollback akan otomatis jika ada exception dalam transaction
            return redirect()->back()->with('error', 'Error saat membaca file Excel: ' . $e->getMessage());
        }
        }); // End of DB::transaction
    }
}
