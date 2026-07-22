<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $lowStockProducts = Product::where('stock', '<=', 5)->get(); // Warning stok tipis
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalKasir = User::where('role', 'kasir')->count();

        return view('admin.dashboard', compact('totalProducts', 'lowStockProducts', 'totalSiswa', 'totalKasir'));
    }
}