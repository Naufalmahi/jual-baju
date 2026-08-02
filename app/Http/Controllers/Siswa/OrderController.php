<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Setting;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    // Pesanan Saya (Belum Selesai)
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['Menunggu Pembayaran', 'menunggu pembayaran', 'Siap Diambil', 'siap diambil'])
            ->latest()
            ->get();

        $qrisEnabled = $this->qrisEnabled();

        return view('siswa.orders.index', compact('orders', 'qrisEnabled'));
    }

    // Riwayat Pesanan (Sudah Selesai)
    public function history()
    {
        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->whereIn('status', ['Selesai', 'selesai', 'SELESAI', 'Dibatalkan', 'dibatalkan'])
            ->latest()
            ->get();

        return view('siswa.orders.history', compact('orders'));
    }

    public function payQris(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($order->payment_method !== 'qris') {
            return response()->json(['success' => false, 'message' => 'Invalid payment method'], 400);
        }

        if (!$this->qrisEnabled()) {
            return response()->json(['success' => false, 'message' => 'QRIS sedang nonaktif'], 403);
        }

        try {
            $midtransService = app(MidtransService::class);

            // Create transaction data for Midtrans
            $transactionData = [
                'transaction_details' => [
                    'order_id' => $order->order_code,
                    'gross_amount' => (int) $order->total_amount,
                ],
                'customer_details' => [
                    'first_name' => Auth::user()->name,
                    'email' => Auth::user()->email,
                ],
                'item_details' => [
                    [
                        'id' => 'order-' . $order->id,
                        'price' => (int) $order->total_amount,
                        'quantity' => 1,
                        'name' => 'Order #' . $order->order_code,
                    ]
                ]
            ];

            // Create Snap transaction
            $response = $midtransService->createSnapTransaction($transactionData);

            if (isset($response['token'])) {
                // Store transaction token in order
                $order->update([
                    'snap_token' => $response['token'],
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Payment token generated successfully',
                    'data' => [
                        'order_id' => $order->order_code,
                        'token' => $response['token'],
                        'redirect_url' => $response['redirect_url'] ?? null,
                        'client_key' => $midtransService->getClientKey(),
                        'snap_url' => $midtransService->getSnapUrl(),
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to generate payment token',
                    'error' => $response,
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Payment error: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Fallback pengecekan status pembayaran (jika webhook tidak tiba)
    public function checkStatus(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($order->payment_method !== 'qris') {
            return response()->json(['success' => false, 'message' => 'Invalid payment method'], 400);
        }

        try {
            $midtransService = app(MidtransService::class);
            $status = $midtransService->getTransactionStatus($order->order_code);

            if (isset($status['gross_amount']) && !$this->grossAmountMatches($order, $status['gross_amount'])) {
                return response()->json(['success' => false, 'message' => 'Nominal tidak cocok'], 400);
            }

            $this->applyMidtransStatus($order, $status);

            return response()->json([
                'success' => true,
                'status' => $order->fresh()->status,
            ]);
        } catch (\Exception $e) {
            Log::warning('Midtrans check status failed', [
                'order_code' => $order->order_code,
                'message' => $e->getMessage(),
            ]);

            return response()->json(['success' => false, 'message' => 'Gagal memeriksa status pembayaran'], 500);
        }
    }

    // Halaman sukses pembayaran
    public function success(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        return view('siswa.orders.success', compact('order'));
    }

    // Midtrans Webhook Handler (terverifikasi signature)
    public function webhook(Request $request)
    {
        try {
            $notification = $request->all();
            $orderId = $notification['order_id'] ?? null;
            $statusCode = $notification['status_code'] ?? null;
            $grossAmount = $notification['gross_amount'] ?? null;
            $signatureKey = $notification['signature_key'] ?? null;

            if (!$orderId) {
                return response()->json(['status' => 'error', 'message' => 'Invalid order_id'], 400);
            }

            if (!$this->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
                Log::warning('Invalid Midtrans webhook signature', ['order_id' => $orderId]);

                return response()->json(['status' => 'error', 'message' => 'Invalid signature'], 403);
            }

            $order = Order::where('order_code', $orderId)->first();
            if (!$order) {
                return response()->json(['status' => 'error', 'message' => 'Order not found'], 404);
            }

            if (!$this->grossAmountMatches($order, $grossAmount)) {
                Log::warning('Midtrans webhook gross amount mismatch', [
                    'order_id' => $orderId,
                    'expected' => $order->total_amount,
                    'received' => $grossAmount,
                ]);

                return response()->json(['status' => 'error', 'message' => 'Gross amount mismatch'], 403);
            }

            $this->applyMidtransStatus($order, $notification);

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Midtrans webhook error', ['message' => $e->getMessage()]);

            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    // Update status order berdasarkan notifikasi/status Midtrans
    private function applyMidtransStatus(Order $order, array $notification): void
    {
        $transactionStatus = $notification['transaction_status'] ?? null;
        $transactionId = $notification['transaction_id'] ?? null;

        if (in_array($transactionStatus, ['capture', 'settlement'])) {
            if ($transactionStatus === 'capture' && ($notification['fraud_status'] ?? null) !== 'accept') {
                return;
            }

            $order->update([
                'status' => 'Siap Diambil',
                'paid_at' => now(),
                'midtrans_transaction_id' => $transactionId,
            ]);
        } elseif ($transactionStatus === 'pending') {
            $order->update(['status' => 'Menunggu Pembayaran']);
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire', 'refund'])) {
            $order->update(['status' => 'Dibatalkan']);
        }
    }

    // Verifikasi signature webhook Midtrans
    private function verifySignature($orderId, $statusCode, $grossAmount, $signatureKey): bool
    {
        if (!$signatureKey) {
            return false;
        }

        $signature = hash('sha512', $orderId . $statusCode . $grossAmount . config('midtrans.server_key'));

        return hash_equals($signature, $signatureKey);
    }

    private function grossAmountMatches(Order $order, $grossAmount): bool
    {
        return abs((float) $order->total_amount - (float) $grossAmount) < 0.01;
    }

    // Setting enable_qris (default aktif bila belum diatur)
    private function qrisEnabled(): bool
    {
        return Setting::where('key', 'enable_qris')->value('value') !== '0';
    }
}
