<?php

use Illuminate\Support\Facades\Route;
use Module\Auth\Interface\Controllers\AuthenticationController;
use Module\Client\Interface\Controllers\ClientController;
use Module\Shared\Interface\Controllers\DashboardController;
use Module\Product\Interface\Controllers\ProductController;
use Module\Sale\Interface\Controllers\SaleController;
use Module\Service\Interface\Controllers\ServiceController;

Route::inertia('/', 'Auth/Login')->name('home');
Route::post('/auth/login', [AuthenticationController::class, 'login'])->name('auth.login');

// Endpoint used by Laravel's authentication system to redirect unauthenticated users to the login page
Route::get('/login', function () {
    return redirect()->route('home');
})->name('login');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('/clients', ClientController::class)->except(['show']);
    Route::resource('/products', ProductController::class)->except(['show']);
    Route::resource('/sales', SaleController::class)->except(['edit', 'update', 'destroy']);
    Route::resource('/services', ServiceController::class)->except(['show']);
    Route::post('/auth/logout', [AuthenticationController::class, 'logout'])->name('logout');
});
