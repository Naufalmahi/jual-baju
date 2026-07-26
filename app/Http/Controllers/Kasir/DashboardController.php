<?php

namespace App\Http\Controllers\Kasir;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPesanan = Order::count();
        $pesananMenunggu = Order::whereIn('status', ['Menunggu Pembayaran', 'menunggu pembayaran'])->count();
        $pesananSiap = Order::whereIn('status', ['Siap Diambil', 'siap diambil'])->count();
        $pesananSelesai = Order::whereIn('status', ['Selesai', 'selesai', 'SELESAI'])->count();

        $recentOrders = Order::with('user')->latest()->take(5)->get();

        return view('kasir.dashboard', compact(
            'totalPesanan', 'pesananMenunggu', 'pesananSiap', 'pesananSelesai', 'recentOrders'
        ));
    }
}