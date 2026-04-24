<?php

use Module\Auth\Interface\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Login')->name('home');
Route::post('/auth/login', [AuthenticationController::class, 'login'])->name('auth.login');

// Endpoint used by Laravel's authentication system to redirect unauthenticated users to the login page
Route::get('/login', function () {
    return redirect()->route('home');
})->name('login');

Route::middleware('auth')->group(function () {
    Route::inertia('/dashboard', 'Dashboard')->name('dashboard');
    Route::inertia('/clients', 'ClientsList')->name('clients');
    Route::post('/auth/logout', [AuthenticationController::class, 'logout'])->name('logout');
});
