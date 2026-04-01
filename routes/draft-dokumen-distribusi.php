<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DraftDokumenDistribusiController;

Route::middleware(['auth'])->group(function () {
    Route::get('/draft-dokumen-distribusi', [DraftDokumenDistribusiController::class, 'index'])->name('draft-dokumen-distribusi.index');
    Route::get('/draft-dokumen-distribusi/upload', [DraftDokumenDistribusiController::class, 'create'])->name('draft-dokumen-distribusi.create');
    Route::post('/draft-dokumen-distribusi', [DraftDokumenDistribusiController::class, 'store'])->name('draft-dokumen-distribusi.store');
    Route::get('/draft-dokumen-distribusi/{id}/download', [DraftDokumenDistribusiController::class, 'download'])->name('draft-dokumen-distribusi.download');
    Route::get('/draft-dokumen-distribusi/{id}', [DraftDokumenDistribusiController::class, 'show'])->name('draft-dokumen-distribusi.show');
    Route::delete('/draft-dokumen-distribusi/{id}', [DraftDokumenDistribusiController::class, 'destroy'])->name('draft-dokumen-distribusi.destroy');
});
