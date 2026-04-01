<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Test route - dapat diakses tanpa login
Route::get('/test-dashboard-direct', function () {
    // Siapkan user admin untuk testing
    $user = \App\Models\User::where('email', 'admin@simjar.test')->first();
    
    if (!$user) {
        return "User tidak ditemukan!";
    }

    // Set user sebagai authenticated
    \Illuminate\Support\Facades\Auth::guard('web')->setUser($user);

    // Call controller
    $controller = new DashboardController();
    
    try {
        $view = $controller->index();
        
        // Render view
        return $view->render();
    } catch (\Exception $e) {
        return "<pre>Error: " . $e->getMessage() . "\n\n" . $e->getTraceAsString() . "</pre>";
    }
});

// Render template test
Route::get('/test-dashboard-render', function () {
    $data = [
        'totalBarangMasuk' => 101,
        'totalPerangkatAktif' => 5,
        'totalPerangkatTidakAktif' => 0,
    ];
    
    try {
        return view('dashboard', $data)->render();
    } catch (\Exception $e) {
        return "<pre>Error: " . $e->getMessage() . "\n\n" . $e->getTraceAsString() . "</pre>";
    }
});

// Test layout render
Route::get('/test-layout', function () {
    try {
        return view('layout')->render();
    } catch (\Exception $e) {
        return "<pre>Error: " . $e->getMessage() . "\n\n" . $e->getTraceAsString() . "</pre>";
    }
});
