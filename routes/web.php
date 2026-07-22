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
use App\Http\Controllers\Admin\ClassController as AdminClassController;

// Middleware Maintenance
use App\Http\Middleware\CheckSystemMaintenance;
use App\Http\Controllers\SuperAdmin\DatabaseController;

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
    Route::post('/toggle-maintenance', [DashboardController::class, 'toggleMaintenance'])->name('toggle-maintenance');

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

    // Route Pemeliharaan Database
    Route::get('/database', [DatabaseController::class, 'index'])->name('database.index');
    Route::get('/database/backup', [DatabaseController::class, 'downloadBackup'])->name('database.backup');
    Route::post('/database/clear-cache', [DatabaseController::class, 'clearCache'])->name('database.clear-cache');
    Route::post('/database/reset-transactions', [DatabaseController::class, 'resetTransactions'])->name('database.reset-transactions');
});


/*
|--------------------------------------------------------------------------
| ADMIN TOKO / KOPERASI ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin', CheckSystemMaintenance::class . ':admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
    
    // Dashboard Admin Toko
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/toggle-maintenance', [AdminDashboardController::class, 'toggleMaintenance'])->name('toggle-maintenance');

    // 1. Master Kategori Produk
    Route::middleware([CheckSystemMaintenance::class . ':categories'])->group(function () {
        Route::resource('categories', CategoryController::class)->only(['index', 'store', 'destroy']);
    });

    // 2. Master Produk / Barang
    Route::middleware([CheckSystemMaintenance::class . ':products'])->group(function () {
        Route::resource('products', ProductController::class)->except(['create', 'edit', 'show']);
    });

    // 3. Master Kelas & Jurusan
    Route::middleware([CheckSystemMaintenance::class . ':classes'])->group(function () {
        Route::resource('classes', AdminClassController::class)->except(['create', 'edit', 'show']);
    });

    // 4. Kelola Kasir
    Route::middleware([CheckSystemMaintenance::class . ':kasir'])->group(function () {
        Route::get('/kasir', [AdminUserController::class, 'indexKasir'])->name('kasir.index');
        Route::post('/kasir', [AdminUserController::class, 'storeKasir'])->name('kasir.store');
        Route::post('/kasir/reset-password/{id}', [AdminUserController::class, 'resetPasswordKasir'])->name('kasir.resetPassword');
        Route::patch('/kasir/toggle-status/{id}', [AdminUserController::class, 'toggleStatusKasir'])->name('kasir.toggleStatus');
    });

    // 5. Kelola Siswa
    Route::middleware([CheckSystemMaintenance::class . ':siswa'])->group(function () {
        Route::get('/siswa', [AdminUserController::class, 'indexSiswa'])->name('siswa.index');
        Route::post('/siswa', [AdminUserController::class, 'storeSiswa'])->name('siswa.store');
        Route::post('/siswa/import', [AdminUserController::class, 'importSiswa'])->name('siswa.import');
        Route::post('/siswa/reset-password/{id}', [AdminUserController::class, 'resetPasswordSiswa'])->name('siswa.resetPassword');
        Route::delete('/siswa/{id}', [AdminUserController::class, 'destroySiswa'])->name('siswa.destroy');
    });
});


/*
|--------------------------------------------------------------------------
| KASIR ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:kasir', CheckSystemMaintenance::class . ':kasir'])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {
        
    // Masukkan route milik Kasir di sini (transaksi, cetak struk, dll.)
    // Contoh:
    // Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');
    // Route::get('/transaksi', [TransactionController::class, 'index'])->name('transaksi.index');

});


/*
|--------------------------------------------------------------------------
| SISWA ROUTES
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:siswa', CheckSystemMaintenance::class . ':siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {
        
    // Masukkan route milik Siswa di sini (katalog barang, riwayat belanja, dll.)
    // Contoh:
    // Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');

});