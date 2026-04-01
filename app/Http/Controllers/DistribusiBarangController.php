<?php

namespace App\Http\Controllers;

use App\Models\DistribusiBarang;
use App\Models\DistribusiActivityLog;
use App\Models\BarangMasuk;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

use PhpOffice\PhpWord\PhpWord;


class DistribusiBarangController extends Controller {
    public function updateStatus(Request $request, DistribusiBarang $distribusi): RedirectResponse
    {
        $user = auth()->user();
        if (!$user->hasAnyRole(['super_admin', 'admin_cabang'])) {
            abort(403);
        }
        // Jika request dari tombol pemasangan
        if ($request->has('is_terpasang')) {
            $val = $request->input('is_terpasang');
            if (!in_array($val, ['terpasang', 'tidak_terpasang'])) {
                return back()->withErrors(['error' => 'Pilihan pemasangan tidak valid']);
            }
            $distribusi->is_terpasang = $val;
            $distribusi->save();
            return back()->with('success', 'Status pemasangan berhasil diperbarui');
        }
        // Jika request dari select status
        $statusBaru = $request->input('status');
        $allowed = ['pending', 'dikirim', 'diterima', 'ditolak'];
        if (!in_array($statusBaru, $allowed)) {
            return back()->withErrors(['error' => 'Status tidak valid']);
        }
        $distribusi->status = $statusBaru;
        $distribusi->save();
        return back()->with('success', 'Status distribusi berhasil diperbarui');
    }

    /**
     * Upload foto distribusi barang (aksi terpisah)
     */
    public function uploadFoto(Request $request, DistribusiBarang $distribusiBarang): RedirectResponse
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif',
        ]);
        $path = $request->file('foto')->store('foto', 'public');
        $distribusiBarang->foto = $path;
        $distribusiBarang->save();
        return back()->with('success', 'Foto berhasil diupload');
    }

    /**
     * Laporan Aktivitas Distribusi
     */
    public function activityReport(Request $request)
    {
        // Filter
        $tanggal_awal = $request->input('tanggal_awal');
        $tanggal_akhir = $request->input('tanggal_akhir');
        $status = $request->input('status');
        $search = $request->input('search');

        $query = DistribusiActivityLog::with(['distribusi', 'distribusi.barang', 'distribusi.cabangAsal', 'distribusi.cabangTujuan', 'user']);

        if ($tanggal_awal) {
            $query->whereDate('created_at', '>=', $tanggal_awal);
        }
        if ($tanggal_akhir) {
            $query->whereDate('created_at', '<=', $tanggal_akhir);
        }
        if ($status) {
            $query->where('status_baru', $status);
        }
        if ($search) {
            $query->whereHas('distribusi', function($q) use ($search) {
                $q->where('jumlah', 'like', "%$search%")
                  ->orWhereHas('barang', function($qb) use ($search) {
                      $qb->where('nama_barang', 'like', "%$search%");
                  });
            });
        }

        $logs = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('distribusi.activity-report', compact('logs', 'tanggal_awal', 'tanggal_akhir', 'status', 'search'));
    }
    // Export PDF dan Word laporan aktivitas distribusi: gunakan data distribusi terbaru dan template sama seperti distribusi barang

    public function exportActivityPdf(Request $request)
    {
        return $this->exportPdf($request);
    }

    public function exportPdf(Request $request)
    {
        // Panggil method yang sama dengan export distribusi barang
        return $this->exportPdfLaporan($request);
    }

    // Restore exportPdfLaporan logic
    public function exportPdfLaporan(Request $request)
    {
        // Ambil distribusi terbaru
        $distribusi = \App\Models\DistribusiBarang::with(['barang', 'cabangAsal', 'cabangTujuan', 'user'])->latest()->first();
        if (!$distribusi) {
            return back()->withErrors(['error' => 'Data distribusi tidak ditemukan']);
        }
        $type = $request->input('type', 'serah-terima');
        $nomor = '004/ KI.03.01';
        $hari = date('l', strtotime($distribusi->tanggal_kirim));
        $tanggal = date('d', strtotime($distribusi->tanggal_kirim));
        $bulan = date('F', strtotime($distribusi->tanggal_kirim));
        $tahun = date('Y', strtotime($distribusi->tanggal_kirim));
        $jam = date('H:i', strtotime($distribusi->tanggal_kirim));
        $pihak_pertama = [
            'nama' => $distribusi->user->name ?? '-',
            'nip' => $distribusi->user->nip ?? '-',
            'pangkat' => $distribusi->user->pangkat ?? '-',
            'jabatan' => $distribusi->user->jabatan ?? '-',
        ];
        $pihak_kedua = [
            'nama' => $distribusi->cabangTujuan->penanggung_jawab ?? '-',
            'nip' => $distribusi->cabangTujuan->nip_penanggung_jawab ?? '-',
            'jabatan' => 'Pihak Kedua',
        ];
        $mengetahui = [
            'nama' => 'koihuk', // Ganti dengan data Kepala Bidang TI jika ada
            'nip' => '98789',
        ];
        $barang = [
            [
                'nama' => $distribusi->barang->kode_barang . ', ' . $distribusi->barang->nama_barang,
                'jumlah' => $distribusi->jumlah,
                'keterangan' => $distribusi->keterangan ?? '-',
            ]
        ];
        $view = $type === 'pinjam' ? 'distribusi.pdf_laporan_pinjam' : 'distribusi.pdf_laporan';
        $pdf = \PDF::loadView($view, compact('nomor','hari','tanggal','bulan','tahun','jam','pihak_pertama','pihak_kedua','mengetahui','barang'));
        $filename = $type === 'pinjam' ? 'berita-acara-serah-terima-pinjam-barang.pdf' : 'berita-acara-serah-terima-barang.pdf';
        return $pdf->download($filename);
    }

    public function exportActivityWord(Request $request)
    {
        // Gunakan logika yang sama dengan exportWord()
        return $this->exportWord($request);
    }
    public function exportWord(Request $request)
    {
        // Silakan sesuaikan logic jika ingin berbeda, default panggil exportWordLaporan
        return $this->exportWordLaporan($request);
    }
    public function exportWordLaporan(Request $request)
    {
        $distribusi = \App\Models\DistribusiBarang::with(['barang', 'cabangAsal', 'cabangTujuan', 'user'])->latest()->first();
        if (!$distribusi) {
            return back()->withErrors(['error' => 'Data distribusi tidak ditemukan']);
        }
        $nomor = '004/ KI.03.01';
        $hari = date('l', strtotime($distribusi->tanggal_kirim));
        $tanggal = date('d', strtotime($distribusi->tanggal_kirim));
        $bulan = date('F', strtotime($distribusi->tanggal_kirim));
        $tahun = date('Y', strtotime($distribusi->tanggal_kirim));
        $jam = date('H:i', strtotime($distribusi->tanggal_kirim));
        $pihak_pertama = [
            'nama' => $distribusi->user->name ?? '-',
            'nip' => $distribusi->user->nip ?? '-',
            'pangkat' => $distribusi->user->pangkat ?? '-',
            'jabatan' => $distribusi->user->jabatan ?? '-',
        ];
        $pihak_kedua = [
            'nama' => $distribusi->cabangTujuan->penanggung_jawab ?? '-',
            'nip' => $distribusi->cabangTujuan->nip_penanggung_jawab ?? '-',
            'jabatan' => 'Pihak Kedua',
        ];
        $mengetahui = [
            'nama' => 'koihuk', // Ganti dengan data Kepala Bidang TI jika ada
            'nip' => '98789',
        ];
        $barang = [
            [
                'nama' => $distribusi->barang->kode_barang . ', ' . $distribusi->barang->nama_barang,
                'jumlah' => $distribusi->jumlah,
                'keterangan' => $distribusi->keterangan ?? '-',
            ]
        ];
        // Gunakan template yang sama dengan PDF, render blade ke HTML lalu convert ke Word
        $html = view('distribusi.word_laporan', compact('nomor','hari','tanggal','bulan','tahun','jam','pihak_pertama','pihak_kedua','mengetahui','barang'))->render();
        // Bersihkan tag <br> bertumpuk dan pastikan HTML valid
        $html = str_replace(['<br><br>', '<br />'], '<br/>', $html);
        $html = preg_replace('/<br\s*\/?>/', '<br/>', $html);
        // Hapus tag <body> jika ada
        $html = str_replace(['<body>', '</body>'], '', $html);
        // Hapus tag <h2> jika ada nested <br> di dalamnya
        $html = preg_replace('/<h2([^>]*)>(.*?)<br\s*\/>(.*?)<\/h2>/i', '<h2$1>$2 $3</h2>', $html);
        $phpWord = new \PhpOffice\PhpWord\PhpWord();
        $section = $phpWord->addSection();
        // Header: logo kiri, teks tengah, garis bawah
        $table = $section->addTable(['alignment' => \PhpOffice\PhpWord\SimpleType\JcTable::CENTER, 'width' => 100 * 50]);
        $logoPath = public_path('images/jakarta.jpg');
        $cellLogoWidth = 1000; // ~1.5cm
        $cellTextWidth = 8000; // ~12cm
        $table->addRow(1000);
        // Kolom logo
        $cellLogo = $table->addCell($cellLogoWidth, ['valign' => 'center']);
        if (file_exists($logoPath)) {
            $cellLogo->addImage($logoPath, [
                'width' => 80,
                'height' => 80,
                'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::LEFT
            ]);
        }
        // Kolom teks header
        $cellText = $table->addCell($cellTextWidth, ['valign' => 'center']);
        $cellText->addText(
            "PEMERINTAH PROVINSI DAERAH KHUSUS IBUKOTA JAKARTA",
            ['bold' => true, 'size' => 14],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );
        $cellText->addText(
            "DINAS PERPUSTAKAAN DAN KEARSIPAN",
            ['bold' => true, 'size' => 14],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );
        $cellText->addText(
            "Jalan Perintis Kemerdekaan No. 1 Pulogadung Jakarta Timur",
            ['size' => 11],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );
        $cellText->addText(
            "Telp. 021.47860095 Fax. 021 47865922 Website dispusip.jakarta.go.id",
            ['size' => 11],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );
        $cellText->addText(
            "JAKARTA      Kode Pos 13260",
            ['bold' => true, 'size' => 11],
            ['alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]
        );
        // Garis horizontal
        $section->addLine(900, ['weight' => 2, 'color' => '000000', 'alignment' => \PhpOffice\PhpWord\SimpleType\Jc::CENTER]);
        // Spasi bawah header
        $section->addTextBreak(1);
        // Sisipkan isi dokumen dari blade
        \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html);
        $fileName = 'berita-acara-serah-terima-barang.docx';
        $tempFile = storage_path('app/tmp/' . $fileName);
        $phpWord->save($tempFile, 'Word2007');
        return response()->download($tempFile)->deleteFileAfterSend(true);
    }
    public function index(): View
    {
        $distribusi = DistribusiBarang::with(['barang', 'cabangAsal', 'cabangTujuan', 'user'])->latest()->paginate(10);
        
        return view('distribusi.index', compact('distribusi'));
    }

    public function create(): View
    {
        // Get current user's cabang
        $userCabang = auth()->user()->cabang_id;
        $isSuperAdmin = auth()->user()->hasRole('super_admin');

        // Hanya Super Admin yang bisa kirim dari mana saja
        if ($isSuperAdmin) {
            $cabangAsal = Cabang::orderBy('is_pusat', 'desc')->get();
        } else {
            $cabangAsal = Cabang::where('id', $userCabang)->get();
        }

        $cabangTujuan = Cabang::where('id', '!=', $userCabang)->get();
        $barangMasuk = BarangMasuk::where('stok', '>', 0)->get();

        return view('distribusi.create', compact('cabangAsal', 'cabangTujuan', 'barangMasuk'));
    }

    public function store(Request $request): RedirectResponse
    {

        $validated = $request->validate([
            'barang_id' => 'required|exists:barang_masuk,id',
            'cabang_asal_id' => 'required|exists:cabangs,id',
            'cabang_tujuan_id' => 'required|exists:cabangs,id|different:cabang_asal_id',
            'jumlah' => 'required|integer|min:1',
            // 'tanggal_kirim' diisi otomatis, tidak perlu validasi input user
            'keterangan' => 'nullable|string',
        ]);

        // Cek stok
        $barang = BarangMasuk::findOrFail($validated['barang_id']);
        if ($barang->stok < $validated['jumlah']) {
            return back()->withErrors(['jumlah' => 'Stok tidak cukup. Stok tersedia: ' . $barang->stok]);
        }


        // Create distribusi
        $data = $validated;
        $data['status'] = 'pending';
        $data['user_id'] = auth()->id();
        $data['tanggal_kirim'] = now(); // Set tanggal_kirim otomatis real-time

        // ...hapus upload foto...

        $distribusi = DistribusiBarang::create($data);

        // Catat aktivitas distribusi
        \App\Models\DistribusiActivityLog::create([
            'distribusi_id' => $distribusi->id,
            'aktivitas' => 'Membuat distribusi baru',
            'status_awal' => null,
            'status_baru' => 'pending',
            'user_id' => auth()->id(),
            'catatan' => $validated['keterangan'] ?? null,
            'tanggal_aktivitas' => now(),
        ]);

        // Kurangi stok barang
        $barang->decrement('stok', $validated['jumlah']);

        return redirect()->route('distribusi.index')->with('success', 'Distribusi berhasil dibuat');
    }

    public function show(DistribusiBarang $distribusi): View
    {
        // Authorization: allow super_admin or users from the source/destination cabang
        $user = auth()->user();
        if (!$user->hasRole('super_admin') && 
            !($user->cabang_id === $distribusi->cabang_asal_id || $user->cabang_id === $distribusi->cabang_tujuan_id)) {
            abort(403, 'Tidak memiliki akses ke distribusi ini.');
        }

        $distribusi->load(['barang', 'cabangAsal', 'cabangTujuan', 'user']);

        return view('distribusi.show', compact('distribusi'));
    }


    public function activityLog(DistribusiBarang $distribusi): View
    {
        $logs = $distribusi->activityLogs()->with('user')->orderBy('tanggal_aktivitas', 'desc')->paginate(20);
        $distribusi->load(['barang', 'cabangAsal', 'cabangTujuan', 'user']);
        
        return view('distribusi.activity-log', compact('distribusi', 'logs'));
    }

    public function destroy(DistribusiBarang $distribusi): RedirectResponse
    {
        if ($distribusi->status !== 'pending') {
            return back()->withErrors(['error' => 'Hanya distribusi yang pending yang bisa dihapus']);
        }

        // Return stok if deleting
        $distribusi->barang->increment('stok', $distribusi->jumlah);

        $distribusi->delete();

        return redirect()->route('distribusi.index')->with('success', 'Distribusi berhasil dihapus');
    }

    /**
     * Endpoint AJAX: Info Barang
     */
    public function infoBarang($id)
    {
        $barang = \App\Models\BarangMasuk::find($id);
        if (!$barang) {
            return response()->json(['error' => 'Barang tidak ditemukan'], 404);
        }
        return response()->json([
            'satuan' => $barang->satuan,
            'stok' => $barang->stok,
            'kategori' => $barang->kategori,
            'posisi' => $barang->posisi,
            'status' => $barang->status,
            'tahun_pengadaan' => $barang->tahun_pengadaan,
        ]);
    }
}
