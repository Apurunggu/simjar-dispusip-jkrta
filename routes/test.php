<?php

Route::get('/test-dashboard', function() {
    return view('dashboard', [
        'totalBarangMasuk' => 0,
        'totalPerangkatAktif' => 0,
        'totalPerangkatTidakAktif' => 0,
    ]);
});
