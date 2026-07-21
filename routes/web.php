<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\ClassController;
use App\Http\Controllers\SuperAdmin\SettingController;

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES (TERPISAH SISWA & PETUGAS)
|--------------------------------------------------------------------------
*/

// Default URL (/) langsung ke Halaman Login Siswa
Route::get('/', [AuthController::class, 'showLoginSiswa'])->name('login');

// Portal Login Siswa (Menggunakan NISN)
Route::get('/login-siswa', [AuthController::class, 'showLoginSiswa'])->name('login.siswa');
Route::post('/login-siswa', [AuthController::class, 'loginSiswa'])->name('login.siswa.process');

// Portal Login Petugas (Super Admin, Admin, Kasir)
Route::get('/login-petugas', [AuthController::class, 'showLoginPetugas'])->name('login.petugas');
Route::post('/login-petugas', [AuthController::class, 'loginPetugas'])->name('login.petugas.process');

// Process Logout
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


/*
|--------------------------------------------------------------------------
| SUPER ADMIN ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('superadmin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Khusus Manajemen Akun Admin
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::post('/users/reset-password/{id}', [UserController::class, 'resetPassword'])->name('users.resetPassword');
    Route::patch('/users/toggle-status/{id}', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');

    // Data Master Kelas & Jurusan
    Route::resource('classes', ClassController::class)->except(['create', 'edit', 'show']);

    // Setting Aplikasi & Sekolah
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');
});