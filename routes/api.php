<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Order Payment Webhook (Midtrans)
Route::prefix('orders')->group(function () {
    // Webhook from Midtrans (Public - No auth required)
    Route::post('/webhook', [\App\Http\Controllers\Siswa\OrderController::class, 'webhook']);
});
