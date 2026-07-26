<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();

        $totalPesanan = Order::where('user_id', $userId)->count();
        $pesananMenunggu = Order::where('user_id', $userId)->whereIn('status', ['Menunggu Pembayaran', 'menunggu pembayaran'])->count();
        $pesananSiap = Order::where('user_id', $userId)->whereIn('status', ['Siap Diambil', 'siap diambil'])->count();
        $pesananSelesai = Order::where('user_id', $userId)->whereIn('status', ['Selesai', 'selesai', 'SELESAI'])->count();

        $products = Product::with('sizes')->latest()->take(4)->get();

        return view('siswa.dashboard', compact(
            'totalPesanan', 
            'pesananMenunggu', 
            'pesananSiap', 
            'pesananSelesai',
            'products'
        ));
    }
}