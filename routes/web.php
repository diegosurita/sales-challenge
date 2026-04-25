<?php

use Module\Auth\Interface\Controllers\AuthenticationController;
use Module\Client\Interface\Controllers\ClientController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'auth/Login')->name('home');
Route::post('/auth/login', [AuthenticationController::class, 'login'])->name('auth.login');

// Endpoint used by Laravel's authentication system to redirect unauthenticated users to the login page
Route::get('/login', function () {
    return redirect()->route('home');
})->name('login');

Route::middleware('auth')->group(function () {
    Route::inertia('/dashboard', 'auth/Dashboard')->name('dashboard');
    Route::resource('/clients', ClientController::class)->names([
        'index' => 'clients.index',
        'create' => 'clients.create',
    ]);
    Route::post('/auth/logout', [AuthenticationController::class, 'logout'])->name('logout');
});
