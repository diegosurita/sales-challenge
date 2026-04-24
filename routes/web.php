<?php

use Module\Auth\Interface\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Login')->name('home');
Route::post('/auth/login', [AuthenticationController::class, 'login'])->name('login');

// Endpoint used by Laravel's authentication system to redirect unauthenticated users to the login page
Route::get('/login', function () {
    return redirect()->route('home');
})->name('login.form');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return 'Dashboard';
    })->name('dashboard');
});
