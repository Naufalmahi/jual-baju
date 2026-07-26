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

| AUTHENTICATION ROUTES (TERPISAH SISWA & PETUGAS)

|--------------------------------------------------------------------------

*/



// Default URL (/) langsung ke Halaman Login Siswa

Route::get('/', [AuthController::class, 'showLoginSiswa'])->name('login');



// Portal Login Siswa (Menggunakan NISN/NIS)

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

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('/toggle-maintenance', [DashboardController::class, 'toggleMaintenance'])->name('toggle-maintenance');



    Route::get('/users', [UserController::class, 'index'])->name('users.index');

    Route::post('/users', [UserController::class, 'store'])->name('users.store');

    Route::post('/users/reset-password/{id}', [UserController::class, 'resetPassword'])->name('users.resetPassword');

    Route::patch('/users/toggle-status/{id}', [UserController::class, 'toggleStatus'])->name('users.toggleStatus');



    Route::resource('classes', ClassController::class)->except(['create', 'edit', 'show']);



    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');

    Route::post('/settings', [SettingController::class, 'update'])->name('settings.update');



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

    

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::post('/toggle-maintenance', [AdminDashboardController::class, 'toggleMaintenance'])->name('toggle-maintenance');



    Route::middleware([CheckSystemMaintenance::class . ':categories'])->group(function () {

        Route::resource('categories', CategoryController::class)->only(['index', 'store', 'destroy']);

    });



    Route::middleware([CheckSystemMaintenance::class . ':products'])->group(function () {

        Route::resource('products', ProductController::class)->except(['create', 'edit', 'show']);

    });



    Route::middleware([CheckSystemMaintenance::class . ':classes'])->group(function () {

        Route::resource('classes', AdminClassController::class)->except(['create', 'edit', 'show']);

    });



    Route::middleware([CheckSystemMaintenance::class . ':kasir'])->group(function () {

        Route::get('/kasir', [AdminUserController::class, 'indexKasir'])->name('kasir.index');

        Route::post('/kasir', [AdminUserController::class, 'storeKasir'])->name('kasir.store');

        Route::post('/kasir/reset-password/{id}', [AdminUserController::class, 'resetPasswordKasir'])->name('kasir.resetPassword');

        Route::patch('/kasir/toggle-status/{id}', [AdminUserController::class, 'toggleStatusKasir'])->name('kasir.toggleStatus');

    });



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

        Route::patch('/orders/{order}/complete', [KasirOrderController::class, 'complete'])->name('orders.complete');



        // 3. Riwayat Transaksi & Cetak Struk

        Route::get('/history', [KasirHistoryController::class, 'index'])->name('history.index');

        Route::get('/orders/{order}/receipt', [KasirHistoryController::class, 'printReceipt'])->name('orders.receipt');



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

        Route::post('/cart/{product}', [CartController::class, 'store'])->name('cart.store');

        Route::put('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');

        Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

        Route::post('/buy-now/{product}', [CartController::class, 'buyNow'])->name('buy.now');



        // 4. Checkout

        Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');

        Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');



        // 5. Pesanan Saya & QRIS

        Route::get('/orders', [SiswaOrderController::class, 'index'])->name('orders.index');

        Route::post('/orders/{order}/pay-qris', [SiswaOrderController::class, 'payQris'])->name('orders.payQris');



        // 6. Riwayat Pesanan Selesai

        Route::get('/orders/history', [SiswaOrderController::class, 'history'])->name('orders.history');



        // 7. Profil Saya & Ubah Foto

        Route::get('/profile', [SiswaProfileController::class, 'index'])->name('profile.index');

        Route::post('/profile/photo', [SiswaProfileController::class, 'updatePhoto'])->name('profile.photo');



    });




