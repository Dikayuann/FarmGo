<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\TernakController;
use App\Http\Controllers\KesehatanController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;



Route::get('/', function () {
    return view('landing.index');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    // Ternak Resource Routes
    Route::resource('ternak', TernakController::class);

    // Kesehatan Resource Routes
    Route::resource('kesehatan', KesehatanController::class);

    Route::get('/reproduksi', fn() => view('reproduksi'))->name('reproduksi');
});

Route::get('/login', [LoginController::class, 'view']);
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Route untuk clear lockout (jika user terkunci)
Route::get('/login/clear-lockout', [LoginController::class, 'clearLockout'])->name('login.clear');

Route::get('/register', [RegisterController::class, 'view'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Google OAuth routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

// Secure logout route with POST method
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

