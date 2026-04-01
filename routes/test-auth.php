<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

// Test login route
Route::get('/test-login', function () {
    $user = \App\Models\User::where('email', 'admin@simjar.test')->first();
    
    if (!$user) {
        return "User tidak ditemukan!";
    }

    // Gunakan Auth facade yang benar
    auth()->guard('web')->login($user);

    // Return simple HTML dengan info
    return "
    <html>
    <body>
        <h1>Login Test</h1>
        <p>User: " . auth()->user()->name . "</p>
        <p>Role: " . (auth()->user()->role ? auth()->user()->role->label : 'No Role') . "</p>
        <p>Auth check: " . (auth()->check() ? 'TRUE' : 'FALSE') . "</p>
        <a href='/'>Go to Dashboard</a>
    </body>
    </html>
    ";
});

// Test authenticated route (protected by auth middleware)
Route::middleware('auth')->group(function () {
    Route::get('/test-protected', function () {
        return "
        <html>
        <body>
            <h1>Protected Route Test</h1>
            <p>User: " . auth()->user()->name . "</p>
            <p>Email: " . auth()->user()->email . "</p>
            <p>Role: " . (auth()->user()->role ? auth()->user()->role->label : 'No Role') . "</p>
            <a href='/'>Go to Dashboard</a>
        </body>
        </html>
        ";
    });
});
