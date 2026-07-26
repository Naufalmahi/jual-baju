<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return view('siswa.orders.index', compact('orders'));
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
        if ($order->user_id !== Auth::id()) abort(403);

        $order->update([
            'status' => 'Siap Diambil',
            'paid_at' => now()
        ]);

        return back()->with('success', 'Pembayaran QRIS Berhasil! Status berubah menjadi Siap Diambil.');
    }
}