<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Controller Super Admin
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\ClassController;
use App\Http\Controllers\SuperAdmin\SettingController;

// Controller Admin Toko / Koperasi
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ClassController as AdminClassController; // Impor ClassController Admin

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


/*
|--------------------------------------------------------------------------
| ADMIN TOKO / KOPERASI ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    
    // Dashboard Admin Toko
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Master Kategori Produk
    Route::resource('categories', CategoryController::class)->only(['index', 'store', 'destroy']);

    // Master Produk / Barang
    Route::resource('products', ProductController::class)->except(['create', 'edit', 'show']);

    // Master Kelas & Jurusan (Admin Toko)
    Route::resource('classes', AdminClassController::class)->except(['create', 'edit', 'show']);

    // Kelola Kasir
    Route::get('/kasir', [AdminUserController::class, 'indexKasir'])->name('kasir.index');
    Route::post('/kasir', [AdminUserController::class, 'storeKasir'])->name('kasir.store');
    Route::post('/kasir/reset-password/{id}', [AdminUserController::class, 'resetPasswordKasir'])->name('kasir.resetPassword');
    Route::patch('/kasir/toggle-status/{id}', [AdminUserController::class, 'toggleStatusKasir'])->name('kasir.toggleStatus');

    // Kelola Siswa
    Route::get('/siswa', [AdminUserController::class, 'indexSiswa'])->name('siswa.index');
    Route::post('/siswa', [AdminUserController::class, 'storeSiswa'])->name('siswa.store');
    Route::post('/siswa/import', [AdminUserController::class, 'importSiswa'])->name('siswa.import');
    Route::post('/siswa/reset-password/{id}', [AdminUserController::class, 'resetPasswordSiswa'])->name('siswa.resetPassword');
    Route::delete('/siswa/{id}', [AdminUserController::class, 'destroySiswa'])->name('siswa.destroy');
});