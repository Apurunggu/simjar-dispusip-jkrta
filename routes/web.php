<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangMasukController;
use App\Http\Controllers\PerangkatJaringanController;
use App\Http\Controllers\DistribusiBarangController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\NotificationController;

// Test Routes (untuk development saja - hanya di environment local)
if (app()->environment('local')) {
    require __DIR__ . '/test-check.php';
    require __DIR__ . '/test-sum.php';
    require __DIR__ . '/test-setup.php';
    require __DIR__ . '/test-dashboard-final.php';
    require __DIR__ . '/test-direct-dashboard.php';
    require __DIR__ . '/test-api-dashboard.php';
    require __DIR__ . '/test-dashboard-debug.php';
    require __DIR__ . '/debug.php';
    require __DIR__ . '/test-dashboard.php';
}

// Auth Routes (Public)
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1')->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:3,1')->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {
    require __DIR__ . '/draft-dokumen-distribusi.php';
        // Route custom untuk akses file storage
        Route::get('/storage/{path}', function ($path) {
            $file = storage_path('app/public/' . $path);
            if (!file_exists($file)) abort(404);
            return response()->file($file);
        })->where('path', '.*');
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Modul Dokumen Barang (Laporan TTD)
    Route::prefix('dokumen-barang')->name('laporan-ttd.')->group(function () {
        Route::get('/', [\App\Http\Controllers\LaporanTtdController::class, 'index'])->name('index');
        Route::get('/upload', [\App\Http\Controllers\LaporanTtdController::class, 'create'])->name('create');
        Route::post('/upload', [\App\Http\Controllers\LaporanTtdController::class, 'store'])->name('store');
        Route::get('/download/{id}', [\App\Http\Controllers\LaporanTtdController::class, 'download'])->name('download');
    });
        // Modul Dokumen Barang Pihak ke 2
        Route::prefix('dokumen-barang-pihak2')->name('dokumen-barang-pihak2.')->group(function () {
            Route::get('/', [App\Http\Controllers\DokumenBarangPihak2Controller::class, 'index'])->name('index');
            Route::get('/create', [App\Http\Controllers\DokumenBarangPihak2Controller::class, 'create'])->name('create');
            Route::post('/upload', [App\Http\Controllers\DokumenBarangPihak2Controller::class, 'store'])->name('store');
            Route::get('/download/{id}', [App\Http\Controllers\DokumenBarangPihak2Controller::class, 'download'])->name('download');
        });

    // Routes untuk Barang Masuk
    Route::prefix('barang-masuk')->name('barang-masuk.')->group(function () {
        Route::get('/', [BarangMasukController::class, 'index'])->name('index');
        Route::get('/{id}/pdf-laporan', [BarangMasukController::class, 'exportPdfLaporan'])->name('pdfLaporan');
        Route::get('/create', [BarangMasukController::class, 'create'])->name('create')->middleware('role:super_admin,admin_cabang,staff');
        Route::post('/', [BarangMasukController::class, 'store'])->name('store')->middleware('role:super_admin,admin_cabang,staff');
        Route::get('/export/pdf', [BarangMasukController::class, 'exportPdf'])->name('exportPdf');
        Route::get('/import', [BarangMasukController::class, 'importForm'])->name('importForm');
        Route::post('/import', [BarangMasukController::class, 'import'])->name('import');
        Route::get('/{id}', [BarangMasukController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [BarangMasukController::class, 'edit'])->name('edit')->middleware('role:super_admin,admin_cabang,staff');
        Route::put('/{id}', [BarangMasukController::class, 'update'])->name('update')->middleware('role:super_admin,admin_cabang,staff');
        Route::delete('/{id}', [BarangMasukController::class, 'destroy'])->name('destroy')->middleware('role:super_admin');
    });
require __DIR__.'/debug-barang.php';

    // Routes untuk Perangkat Jaringan
    Route::prefix('perangkat-jaringan')->name('perangkat-jaringan.')->group(function () {
        Route::get('/', [PerangkatJaringanController::class, 'index'])->name('index');
        Route::get('/create', [PerangkatJaringanController::class, 'create'])->name('create')->middleware('role:super_admin,admin_cabang');
        Route::post('/', [PerangkatJaringanController::class, 'store'])->name('store')->middleware('role:super_admin,admin_cabang');
        Route::get('/{perangkatJaringan}', [PerangkatJaringanController::class, 'show'])->name('show');
        Route::get('/{perangkatJaringan}/edit', [PerangkatJaringanController::class, 'edit'])->name('edit')->middleware('role:super_admin,admin_cabang');
        Route::put('/{perangkatJaringan}', [PerangkatJaringanController::class, 'update'])->name('update')->middleware('role:super_admin,admin_cabang');
        Route::post('/{perangkatJaringan}/deactivate', [PerangkatJaringanController::class, 'deactivate'])->name('deactivate')->middleware('role:super_admin,admin_cabang');
        Route::post('/{perangkatJaringan}/activate', [PerangkatJaringanController::class, 'activate'])->name('activate')->middleware('role:super_admin,admin_cabang');
        Route::get('/{perangkatJaringan}/activity-log', [PerangkatJaringanController::class, 'activityLog'])->name('activity-log');
    });

    // Routes untuk Distribusi Barang
    Route::prefix('distribusi-barang')->name('distribusi.')->group(function () {
            // Upload foto distribusi (super admin & admin cabang)
            Route::post('/{distribusiBarang}/upload-foto', [DistribusiBarangController::class, 'uploadFoto'])->name('uploadFoto')->middleware('role:super_admin,admin_cabang');
        // Laporan Aktivitas Distribusi
        Route::get('/activity-report', [DistribusiBarangController::class, 'activityReport'])->name('activity-report');
        Route::get('/activity-report/export/excel', [DistribusiBarangController::class, 'exportActivityExcel'])->name('activity-report.export.excel');
        Route::get('/activity-report/export/pdf', [DistribusiBarangController::class, 'exportActivityPdf'])->name('activity-report.export.pdf');
        Route::get('/activity-report/export/word', [DistribusiBarangController::class, 'exportActivityWord'])->name('activity-report.export.word');

        Route::get('/info/{id}', [DistribusiBarangController::class, 'infoBarang'])->name('distribusi-barang.info');

        Route::get('/', [DistribusiBarangController::class, 'index'])->name('index');
        Route::get('/export/pdf', [DistribusiBarangController::class, 'exportPdf'])->name('exportPdf');
        Route::get('/export/word', [DistribusiBarangController::class, 'exportWord'])->name('exportWord');
        Route::get('/create', [DistribusiBarangController::class, 'create'])->name('create')->middleware('role:super_admin,admin_cabang,staff');
        Route::post('/', [DistribusiBarangController::class, 'store'])->name('store')->middleware('role:super_admin,admin_cabang,staff');
        Route::get('/{distribusiBarang}', [DistribusiBarangController::class, 'show'])->name('show');
        Route::post('/{distribusiBarang}/update-status', [DistribusiBarangController::class, 'updateStatus'])->name('distribusi.updateStatus');
        Route::get('/{distribusiBarang}/activity-log', [DistribusiBarangController::class, 'activityLog'])->name('activity-log');
        Route::delete('/{distribusiBarang}', [DistribusiBarangController::class, 'destroy'])->name('destroy')->middleware('role:super_admin,admin_cabang');
    });

    // Dokumen Barang Split
    Route::get('/dokumen/pihak-1', [\App\Http\Controllers\DokumenBarangController::class, 'pihak1'])->name('dokumen.pihak1');
    Route::get('/dokumen/pihak-2', [\App\Http\Controllers\DokumenBarangController::class, 'pihak2'])->name('dokumen.pihak2');

    // Modul Manajemen User (khusus superadmin)
    Route::middleware(['auth', 'role:super_admin'])->prefix('user-management')->name('user-management.')->group(function () {
        Route::get('/', [\App\Http\Controllers\UserManagementController::class, 'index'])->name('index');
        Route::delete('/{id}', [\App\Http\Controllers\UserManagementController::class, 'destroy'])->name('destroy');
        Route::get('/{id}', [\App\Http\Controllers\UserManagementController::class, 'show'])->name('show');
        Route::post('/{id}/reset-password', [\App\Http\Controllers\UserManagementController::class, 'resetPassword'])->name('reset-password');
    });

        // Modul Manajemen Cabang (khusus superadmin)
        Route::middleware(['auth', 'role:super_admin'])->prefix('cabang')->name('cabang.')->group(function () {
            Route::get('/', [CabangController::class, 'index'])->name('index');
            Route::get('/{id}/edit', [CabangController::class, 'edit'])->name('edit');
            Route::post('/{id}/edit', [CabangController::class, 'update'])->name('update');
        });

        // Notification Routes
        Route::prefix('notifications')->name('notifications.')->group(function () {
            Route::get('/', [NotificationController::class, 'index'])->name('index');
            Route::get('/dropdown', [NotificationController::class, 'dropdown'])->name('dropdown');
            Route::post('/{id}/mark-as-read', [NotificationController::class, 'markAsRead'])->name('markAsRead');
            Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('markAllAsRead');
            Route::delete('/{id}', [NotificationController::class, 'delete'])->name('delete');
            Route::delete('/', [NotificationController::class, 'deleteAll'])->name('deleteAll');
        });
});

