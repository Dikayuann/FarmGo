<?php

use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\GoogleAuthController;
use Illuminate\Support\Facades\Route;

//Route::get('/', function () {
//    return view('welcome');
//});

Route::get('/', function () {
    return view('landing.index');
});

Route::get('/dashboard', fn() => view('dashboard'))->name('dashboard');


Route::get('/ternak', fn() => view('ternak'))->name('ternak');
Route::get('/kesehatan', fn() => view('kesehatan'))->name('kesehatan');
Route::get('/reproduksi', fn() => view('reproduksi'))->name('reproduksi');

Route::get('/login', [LoginController::class, 'view']);
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::get('/register', [RegisterController::class, 'view'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

// Google OAuth routes
Route::get('/auth/google', [GoogleAuthController::class, 'redirectToGoogle'])->name('auth.google');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback'])->name('auth.google.callback');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');  // Redirect ke halaman login setelah logout
})->name('logout');


//Route::get('/login', function () {
//    return view('login');
//})->name('login');

