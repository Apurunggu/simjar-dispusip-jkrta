<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Test route dengan login
Route::get('/test-login-dashboard', function () {
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
