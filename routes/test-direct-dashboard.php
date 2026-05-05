<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Direct test - render dashboard tanpa auth
Route::get('/test-dashboard-direct', function () {
    // Get first user
    $user = \App\Models\User::first();
    if (!$user) {
        return "No users found!";
    }
    
    // Set auth
    auth()->setUser($user);
    
    // Call controller
    $controller = new DashboardController();
    $view = $controller->index();
    
    // Get view data
    $data = $view->getData();
    
    $html = "<h2>Dashboard Data Debug</h2>";
    $html .= "<p><strong>Total Barang Masuk:</strong> " . $data['totalBarangMasuk'] . "</p>";
    $html .= "<p><strong>Total Stok:</strong> " . $data['totalStok'] . "</p>";
    $html .= "<p><strong>Total Unik Barang:</strong> " . $data['totalUnikBarang'] . "</p>";
    $html .= "<p><strong>Perangkat Aktif:</strong> " . $data['totalPerangkatAktif'] . "</p>";
    $html .= "<p><strong>Perangkat Tidak Aktif:</strong> " . $data['totalPerangkatTidakAktif'] . "</p>";
    $html .= "<p><strong>Distribusi Pending:</strong> " . $data['distribusiPending'] . "</p>";
    $html .= "<p><strong>Total Terdistribusi:</strong> " . $data['totalTerdistribusi'] . "</p>";
    
    $html .= "<h3>Render Full View:</h3>";
    $html .= "<hr>";
    $html .= $view->render();
    
    return $html;
});
