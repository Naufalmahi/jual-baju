<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'items.product'])
            ->whereIn('status', ['Selesai', 'selesai', 'SELESAI']);

        if ($request->filled('method') && $request->method !== 'semua') {
            $query->where('payment_method', $request->method);
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

        $orders = $query->latest()->paginate(10);
        $selectedOrder = $request->has('detail_id') ? Order::with(['user', 'items.product'])->find($request->detail_id) : $orders->first();

        return view('kasir.history.index', compact('orders', 'selectedOrder'));
    }

    public function printReceipt(Order $order)
    {
        $order->load(['user', 'items.product']);
        return view('kasir.history.receipt', compact('order'));
    }
}