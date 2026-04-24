<?php

use Module\Auth\Interface\Controllers\AuthenticationController;
use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Login')->name('home');
Route::post('/login', [AuthenticationController::class, 'login'])->name('login');
Route::get('/login', function () {
    return redirect()->route('home');
})->name('login.form');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return 'Dashboard';
    })->name('dashboard');
});
