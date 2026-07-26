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

// Controller Kasir
use App\Http\Controllers\Kasir\DashboardController as KasirDashboardController;
use App\Http\Controllers\Kasir\OrderController as KasirOrderController;
use App\Http\Controllers\Kasir\HistoryController as KasirHistoryController;
use App\Http\Controllers\Kasir\ReportController as KasirReportController;

// Controller Siswa
use App\Http\Controllers\Siswa\DashboardController as SiswaDashboardController;
use App\Http\Controllers\Siswa\ProductController as SiswaProductController;
use App\Http\Controllers\Siswa\CartController;
use App\Http\Controllers\Siswa\CheckoutController;
use App\Http\Controllers\Siswa\OrderController as SiswaOrderController;
use App\Http\Controllers\Siswa\ProfileController as SiswaProfileController;

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
    
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::post('/toggle-maintenance', [AdminDashboardController::class, 'toggleMaintenance'])->name('toggle-maintenance');

    Route::middleware([CheckSystemMaintenance::class . ':categories'])->group(function () {
        Route::resource('categories', CategoryController::class)
            ->only(['index', 'store', 'destroy'])
            ->whereNumber('category');
    });

    Route::middleware([CheckSystemMaintenance::class . ':products'])->group(function () {
        Route::resource('products', ProductController::class)
            ->except(['create', 'edit', 'show'])
            ->whereNumber('product');
    });

    Route::middleware([CheckSystemMaintenance::class . ':classes'])->group(function () {
        Route::resource('classes', AdminClassController::class)
            ->except(['create', 'edit', 'show'])
            ->whereNumber('class');
    });

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
| KASIR ROUTES (LENGKAP)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:kasir', CheckSystemMaintenance::class . ':kasir'])
    ->prefix('kasir')
    ->name('kasir.')
    ->group(function () {

        // 1. Dashboard Kasir
        Route::get('/dashboard', [KasirDashboardController::class, 'index'])->name('dashboard');

        // 2. Kelola Pesanan (Proses ubah status ke Selesai)
        Route::get('/orders', [KasirOrderController::class, 'index'])->name('orders.index');
        Route::patch('/orders/{order}/complete', [KasirOrderController::class, 'complete'])
            ->whereNumber('order')
            ->name('orders.complete');

        // 3. Riwayat Transaksi & Cetak Struk
        Route::get('/history', [KasirHistoryController::class, 'index'])->name('history.index');
        Route::get('/orders/{order}/receipt', [KasirHistoryController::class, 'printReceipt'])
            ->whereNumber('order')
            ->name('orders.receipt');

        // 4. Laporan Penjualan & Download Excel
        Route::get('/reports', [KasirReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/export-excel', [KasirReportController::class, 'exportExcel'])->name('reports.exportExcel');

    });


/*
|--------------------------------------------------------------------------
| SISWA ROUTES (LENGKAP)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:siswa', CheckSystemMaintenance::class . ':siswa'])
    ->prefix('siswa')
    ->name('siswa.')
    ->group(function () {

        // 1. Dashboard / Beranda
        Route::get('/dashboard', [SiswaDashboardController::class, 'index'])->name('dashboard');

        // 2. Katalog Produk
        Route::get('/products', [SiswaProductController::class, 'index'])->name('products.index');

        // 3. Keranjang & Bayar Sekarang
        Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
        Route::post('/cart/{product}', [CartController::class, 'store'])
            ->whereNumber('product')
            ->name('cart.store');
        Route::put('/cart/{cart}', [CartController::class, 'update'])
            ->whereNumber('cart')
            ->name('cart.update');
        Route::delete('/cart/{cart}', [CartController::class, 'destroy'])
            ->whereNumber('cart')
            ->name('cart.destroy');
        Route::post('/buy-now/{product}', [CartController::class, 'buyNow'])
            ->whereNumber('product')
            ->name('buy.now');

        // 4. Checkout
        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

        // 5. Pesanan Saya & QRIS
        Route::get('/orders', [SiswaOrderController::class, 'index'])->name('orders.index');
        Route::post('/orders/{order}/pay-qris', [SiswaOrderController::class, 'payQris'])
            ->whereNumber('order')
            ->name('orders.payQris');

        // 6. Riwayat Pesanan Selesai
        Route::get('/orders/history', [SiswaOrderController::class, 'history'])->name('orders.history');

        // 7. Profil Saya & Ubah Foto
        Route::get('/profile', [SiswaProfileController::class, 'index'])->name('profile.index');
        Route::post('/profile/photo', [SiswaProfileController::class, 'updatePhoto'])->name('profile.photo');

    });