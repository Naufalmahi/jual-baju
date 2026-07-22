<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\User;
use App\Models\Setting;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Data Statistik Dashboard
        $totalProducts    = Product::count();
        $lowStockProducts = Product::where('stock', '<=', 5)->get();
        $totalKasir       = User::where('role', 'kasir')->count();
        $totalSiswa       = User::where('role', 'siswa')->count(); // Tambahkan baris ini

        // 2. Status Maintenance Modul
        $mCategories = Setting::where('key', 'maintenance_categories')->value('value') ?? '0';
        $mProducts   = Setting::where('key', 'maintenance_products')->value('value') ?? '0';
        $mClasses    = Setting::where('key', 'maintenance_classes')->value('value') ?? '0';
        $mKasir      = Setting::where('key', 'maintenance_kasir')->value('value') ?? '0';
        $mSiswa      = Setting::where('key', 'maintenance_siswa')->value('value') ?? '0';

        return view('admin.dashboard', compact(
            'totalProducts',
            'lowStockProducts',
            'totalKasir',
            'totalSiswa', // Masukkan ke compact
            'mCategories',
            'mProducts',
            'mClasses',
            'mKasir',
            'mSiswa'
        ));
    }

    public function toggleMaintenance(Request $request)
    {
        $request->validate([
            'target' => 'required|in:categories,products,classes,kasir,siswa',
            'status' => 'required|in:0,1',
        ]);

        Setting::updateOrCreate(
            ['key' => 'maintenance_' . $request->target],
            ['value' => $request->status]
        );

        return redirect()->back()->with('success', 'Status maintenance modul berhasil diperbarui!');
    }
}