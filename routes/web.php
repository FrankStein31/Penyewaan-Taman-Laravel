<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PemesananController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TamanController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\FasilitasController;
use App\Http\Controllers\PembayaranController;
use Illuminate\Support\Facades\Route;

// Route untuk halaman utama
Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

// Route untuk autentikasi
Route::middleware('guest')->group(function () {
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);
    
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);
});

// Route yang memerlukan autentikasi
Route::middleware('auth')->group(function () {
    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
    
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Profile
    Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('profile/password', [ProfileController::class, 'password'])->name('profile.password');
    
    Route::resource('users', UserController::class);
    Route::resource('taman', TamanController::class);
    Route::resource('fasilitas', FasilitasController::class);

    // Pemesanan
    Route::get('/pemesanan/export', [PemesananController::class, 'export'])->name('pemesanan.export');
    Route::resource('pemesanan', PemesananController::class);
    Route::put('pemesanan/{pemesanan}/approve', [PemesananController::class, 'approve'])->name('pemesanan.approve');
    Route::put('pemesanan/{pemesanan}/reject', [PemesananController::class, 'reject'])->name('pemesanan.reject');
    Route::delete('/pemesanan/{pemesanan}', [PemesananController::class, 'destroy'])->name('pemesanan.destroy');
    Route::put('/pemesanan/{id}/selesai', [PemesananController::class, 'selesai'])->name('pemesanan.selesai');
    
    // Pembayaran
    Route::get('pembayaran/create/{pemesanan}', [PembayaranController::class, 'create'])->name('pembayaran.create');
    Route::post('pembayaran/verifikasi/{pembayaran}', [PembayaranController::class, 'verifikasi'])->name('pembayaran.verifikasi');
    Route::post('pembayaran/callback', [PembayaranController::class, 'callback'])->name('pembayaran.callback');
    Route::resource('pembayaran', PembayaranController::class)->except(['create']);
});

// Route untuk password reset (jika diperlukan)
// Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
// Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
// Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
// Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');
