<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

// Controller Super Admin
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\UserController;
use App\Http\Controllers\SuperAdmin\ClassController;
use App\Http\Controllers\SuperAdmin\SettingController;
use App\Http\Controllers\SuperAdmin\DatabaseController;

// Controller Admin Toko / Koperasi
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\ClassController as AdminClassController;

// Middleware Maintenance
use App\Http\Middleware\CheckSystemMaintenance;

/*
|--------------------------------------------------------------------------
| AUTHENTICATION ROUTES (PROTEKSI BRUTE-FORCE THROTTLE)
|--------------------------------------------------------------------------
*/

// Default URL (/) langsung ke Halaman Login Siswa
Route::get('/', [AuthController::class, 'showLoginSiswa'])->name('login');

// Portal Login Siswa (Throttle: Max 5 percobaan per menit)
Route::get('/login-siswa', [AuthController::class, 'showLoginSiswa'])->name('login.siswa');
Route::post('/login-siswa', [AuthController::class, 'loginSiswa'])
    ->middleware('throttle:5,1')
    ->name('login.siswa.process');

// Portal Login Petugas (Throttle: Max 5 percobaan per menit)
Route::get('/login-petugas', [AuthController::class, 'showLoginPetugas'])->name('login.petugas');
Route::post('/login-petugas', [AuthController::class, 'loginPetugas'])
    ->middleware('throttle:5,1')
    ->name('login.petugas.process');

// Process Logout (Hanya User Terautentikasi)
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');


/*
|--------------------------------------------------------------------------
| SUPER ADMIN ROUTES (SUPER PROTECTED)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:super_admin'])->prefix('super-admin')->name('superadmin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/toggle-maintenance', [DashboardController::class, 'toggleMaintenance'])->name('toggle-maintenance');

    // Manajemen Akun Admin
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    
    // Parameter Constraint: ID Harus Angka Murni
    Route::post('/users/reset-password/{id}', [UserController::class, 'resetPassword'])
        ->whereNumber('id')
        ->name('users.resetPassword');
        
    Route::patch('/users/toggle-status/{id}', [UserController::class, 'toggleStatus'])
        ->whereNumber('id')
        ->name('users.toggleStatus');

    // Data Master Kelas & Jurusan
    Route::resource('classes', ClassController::class)
        ->except(['create', 'edit', 'show'])
        ->whereNumber('class');

    // Setting Aplikasi & Sekolah
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');

    // Pemeliharaan Database (Sangat Sensitif - Proteksi CSRF & Rate Limit Ketat)
    Route::get('/database', [DatabaseController::class, 'index'])->name('database.index');
    
    // Backup Database
    Route::post('/database/backup', [DatabaseController::class, 'downloadBackup'])
        ->middleware('throttle:2,1')
        ->name('database.backup');
        
    // Clear Cache
    Route::post('/database/clear-cache', [DatabaseController::class, 'clearCache'])
        ->middleware('throttle:3,1')
        ->name('database.clear-cache');
        
    // Restore Database SQL
    Route::post('/database/restore', [DatabaseController::class, 'restoreBackup'])
        ->middleware('throttle:2,1')
        ->name('database.restore');

    // Reset Data Transaksi
    Route::post('/database/reset-transactions', [DatabaseController::class, 'resetTransactions'])
        ->middleware('throttle:2,1')
        ->name('database.reset-transactions');
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
        Route::resource('categories', CategoryController::class)
            ->only(['index', 'store', 'destroy'])
            ->whereNumber('category');
    });

    // 2. Master Produk / Barang
    Route::middleware([CheckSystemMaintenance::class . ':products'])->group(function () {
        Route::resource('products', ProductController::class)
            ->except(['create', 'edit', 'show'])
            ->whereNumber('product');
    });

    // 3. Master Kelas & Jurusan
    Route::middleware([CheckSystemMaintenance::class . ':classes'])->group(function () {
        Route::resource('classes', AdminClassController::class)
            ->except(['create', 'edit', 'show'])
            ->whereNumber('class');
    });

    // 4. Kelola Kasir
    Route::middleware([CheckSystemMaintenance::class . ':kasir'])->group(function () {
        Route::get('/kasir', [AdminUserController::class, 'indexKasir'])->name('kasir.index');
        Route::post('/kasir', [AdminUserController::class, 'storeKasir'])->name('kasir.store');
        Route::post('/kasir/reset-password/{id}', [AdminUserController::class, 'resetPasswordKasir'])
            ->whereNumber('id')
            ->name('kasir.resetPassword');
        Route::patch('/kasir/toggle-status/{id}', [AdminUserController::class, 'toggleStatusKasir'])
            ->whereNumber('id')
            ->name('kasir.toggleStatus');
    });

    // 5. Kelola Siswa
    Route::middleware([CheckSystemMaintenance::class . ':siswa'])->group(function () {
        Route::get('/siswa', [AdminUserController::class, 'indexSiswa'])->name('siswa.index');
        Route::post('/siswa', [AdminUserController::class, 'storeSiswa'])->name('siswa.store');
        Route::post('/siswa/import', [AdminUserController::class, 'importSiswa'])->name('siswa.import');
        Route::post('/siswa/reset-password/{id}', [AdminUserController::class, 'resetPasswordSiswa'])
            ->whereNumber('id')
            ->name('siswa.resetPassword');
        Route::delete('/siswa/{id}', [AdminUserController::class, 'destroySiswa'])
            ->whereNumber('id')
            ->name('siswa.destroy');
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
        // Route khusus kasir di sini
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
        // Route khusus siswa di sini
});