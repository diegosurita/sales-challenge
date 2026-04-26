<?php

use Illuminate\Support\Facades\Route;
use Module\Auth\Interface\Controllers\AuthenticationController;
use Module\Client\Interface\Controllers\ClientController;
use Module\Product\Interface\Controllers\ProductController;

Route::inertia('/', 'auth/Login')->name('home');
Route::post('/auth/login', [AuthenticationController::class, 'login'])->name('auth.login');

// Endpoint used by Laravel's authentication system to redirect unauthenticated users to the login page
Route::get('/login', function () {
    return redirect()->route('home');
})->name('login');

Route::middleware('auth')->group(function () {
    Route::inertia('/dashboard', 'auth/Dashboard')->name('dashboard');
    Route::resource('/clients', ClientController::class)->except(['show']);
    Route::resource('/products', ProductController::class)->except(['show']);
    Route::post('/auth/logout', [AuthenticationController::class, 'logout'])->name('logout');
});
