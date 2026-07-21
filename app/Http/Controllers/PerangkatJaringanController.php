<?php

namespace App\Http\Controllers;

use App\Models\PerangkatJaringan;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use App\Helpers\NotificationHelper;

class PerangkatJaringanController extends Controller
{
    public function index(Request $request): View
    {
        $query = PerangkatJaringan::query();

        if ($request->has('lokasi') && $request->lokasi != '') {
            $query->where('lokasi', $request->lokasi);
        }

        $perangkat = $query->paginate(15);
        $lokasi = PerangkatJaringan::select('lokasi')->distinct()->pluck('lokasi');

        return view('perangkat_jaringan.index', compact('perangkat', 'lokasi'));
    }

    public function create(): View
    {
        return view('perangkat_jaringan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nomor_inventaris' => 'required|unique:perangkat_jaringan,nomor_inventaris',
            'nama_perangkat' => 'required|string|max:255',
            'tipe_perangkat' => 'required|string|max:100',
            'lokasi' => 'required|string|max:100',
            'ip_address' => 'nullable|string|max:100',
            'mac_address' => 'nullable|string|max:100',
            'tanggal_pemasangan' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $validated['status'] = 'aktif';
        $perangkat = PerangkatJaringan::create($validated);

        ActivityLog::create([
            'perangkat_id' => $perangkat->id,
            'aktivitas' => 'Perangkat Ditambahkan',
            'deskripsi' => 'Perangkat baru ' . $perangkat->nama_perangkat . ' ditambahkan ke sistem',
            'tanggal_aktivitas' => Carbon::now(),
        ]);

        // ===== SEND NOTIFICATIONS =====
        $notifTitle = '🔌 Perangkat Jaringan Baru';
        $notifMessage = "Perangkat '{$perangkat->nama_perangkat}' ({$perangkat->tipe_perangkat}) telah ditambahkan";

        // Notify Super Admin & Admin Cabang
        NotificationHelper::notifyRole(
            'super_admin',
            title: $notifTitle,
            message: $notifMessage,
            type: 'perangkat_jaringan',
            icon: 'bi-router',
            color: 'info',
            actionUrl: route('perangkat-jaringan.show', $perangkat->id)
        );

        NotificationHelper::notifyRole(
            'admin_cabang',
            title: $notifTitle,
            message: $notifMessage,
            type: 'perangkat_jaringan',
            icon: 'bi-router',
            color: 'info',
            actionUrl: route('perangkat-jaringan.show', $perangkat->id)
        );

        return redirect()->route('perangkat-jaringan.index')->with('success', 'Perangkat berhasil ditambahkan');
    }

    public function show($id): View
    {
        $perangkatJaringan = PerangkatJaringan::findOrFail($id);
        $logs = $perangkatJaringan->activityLogs()->orderBy('tanggal_aktivitas', 'desc')->get();
        return view('perangkat_jaringan.show', compact('perangkatJaringan', 'logs'));
    }

    public function edit($id): View
    {
        $perangkatJaringan = PerangkatJaringan::findOrFail($id);
        return view('perangkat_jaringan.edit', compact('perangkatJaringan'));
    }

    public function update(Request $request, PerangkatJaringan $perangkatJaringan): RedirectResponse
    {
        $validated = $request->validate([
            'nomor_inventaris' => 'required|unique:perangkat_jaringan,nomor_inventaris,' . $perangkatJaringan->id,
            'nama_perangkat' => 'required|string|max:255',
            'tipe_perangkat' => 'required|string|max:100',
            'lokasi' => 'required|string|max:100',
            'ip_address' => 'nullable|string|max:100',
            'mac_address' => 'nullable|string|max:100',
            'tanggal_pemasangan' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $perangkatJaringan->update($validated);

        ActivityLog::create([
            'perangkat_id' => $perangkatJaringan->id,
            'aktivitas' => 'Perangkat Diperbarui',
            'deskripsi' => 'Data perangkat ' . $perangkatJaringan->nama_perangkat . ' telah diperbarui',
            'tanggal_aktivitas' => Carbon::now(),
        ]);

        return redirect()->route('perangkat-jaringan.index')->with('success', 'Perangkat berhasil diperbarui');
    }

    public function deactivate(PerangkatJaringan $perangkatJaringan): RedirectResponse
    {
        $perangkatJaringan->update(['status' => 'tidak_aktif']);

        ActivityLog::create([
            'perangkat_id' => $perangkatJaringan->id,
            'aktivitas' => 'Perangkat Dinonaktifkan',
            'deskripsi' => 'Perangkat ' . $perangkatJaringan->nama_perangkat . ' dinonaktifkan',
            'tanggal_aktivitas' => Carbon::now(),
        ]);

        // ===== SEND NOTIFICATIONS =====
        NotificationHelper::notifyRole(
            'super_admin',
            title: '⚠️ Perangkat Dinonaktifkan',
            message: "Perangkat '{$perangkatJaringan->nama_perangkat}' telah dinonaktifkan",
            type: 'perangkat_jaringan',
            icon: 'bi-exclamation-circle',
            color: 'warning',
            actionUrl: route('perangkat-jaringan.show', $perangkatJaringan->id)
        );

        return redirect()->route('perangkat-jaringan.index')->with('success', 'Perangkat berhasil dinonaktifkan');
    }

    public function activate(PerangkatJaringan $perangkatJaringan): RedirectResponse
    {
        $perangkatJaringan->update(['status' => 'aktif']);

        ActivityLog::create([
            'perangkat_id' => $perangkatJaringan->id,
            'aktivitas' => 'Perangkat Diaktifkan',
            'deskripsi' => 'Perangkat ' . $perangkatJaringan->nama_perangkat . ' diaktifkan kembali',
            'tanggal_aktivitas' => Carbon::now(),
        ]);

        // ===== SEND NOTIFICATIONS =====
        NotificationHelper::notifyRole(
            'super_admin',
            title: '✅ Perangkat Diaktifkan',
            message: "Perangkat '{$perangkatJaringan->nama_perangkat}' telah diaktifkan kembali",
            type: 'perangkat_jaringan',
            icon: 'bi-check-circle',
            color: 'success',
            actionUrl: route('perangkat-jaringan.show', $perangkatJaringan->id)
        );

        return redirect()->route('perangkat-jaringan.index')->with('success', 'Perangkat berhasil diaktifkan');
    }

    public function activityLog($id): View
    {
        $perangkatJaringan = PerangkatJaringan::findOrFail($id);
        $logs = $perangkatJaringan->activityLogs()->orderBy('tanggal_aktivitas', 'desc')->paginate(20);
        return view('perangkat_jaringan.activity_log', compact('perangkatJaringan', 'logs'));
    }
}
