<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Get latest order for current user (for payment flow)
Route::middleware('auth:sanctum')->get('/user-latest-order', function (Request $request) {
    $latestOrder = \App\Models\Order::where('user_id', $request->user()->id)
        ->latest()
        ->first();

    return response()->json([
        'order_id' => $latestOrder->id ?? null,
    ]);
});

// Order Payment Webhook (Midtrans)
Route::prefix('orders')->group(function () {
    // Webhook from Midtrans (Public - No auth required)
    Route::post('/webhook', [\App\Http\Controllers\Siswa\OrderController::class, 'webhook']);
});
