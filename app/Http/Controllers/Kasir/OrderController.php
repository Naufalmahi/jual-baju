<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product']);

        if ($request->filled('status') && $request->status !== 'semua') {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($u) use ($search) {
                      $u->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $orders = $query->latest()->get();
        return view('kasir.orders.index', compact('orders'));
    }

    // Mengubah status pesanan menjadi Selesai (Siswa ambil baju & bayar)
    public function complete(Order $order)
    {
        if ($order->status === 'Dibatalkan') {
            return back()->with('error', 'Pesanan #' . $order->order_code . ' sudah dibatalkan dan tidak bisa diselesaikan.');
        }

        $order->update([
            'status' => 'Selesai',
            'paid_at' => $order->paid_at ?? now(),
            'picked_up_at' => now(),
        ]);

        return back()->with('success', 'Pesanan #' . $order->order_code . ' berhasil diubah menjadi SELESAI!');
    }
}