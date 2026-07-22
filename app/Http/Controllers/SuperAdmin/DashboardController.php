<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\User;
use App\Models\ClassModel; // Penyesuaian nama model kelas Anda

class DashboardController extends Controller
{
    public function index()
    {
        $totalAdmin = User::where('role', 'admin')->count();
        $totalKasir = User::where('role', 'kasir')->count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalKelas = ClassModel::count();

        // Ambil status maintenance tiap role/halaman
        $mAdmin = Setting::where('key', 'maintenance_admin')->value('value') ?? '0';
        $mKasir = Setting::where('key', 'maintenance_kasir')->value('value') ?? '0';
        $mSiswa = Setting::where('key', 'maintenance_siswa')->value('value') ?? '0';

        // Fitur spesifik di dalam Admin
        $mCategories = Setting::where('key', 'maintenance_categories')->value('value') ?? '0';
        $mProducts = Setting::where('key', 'maintenance_products')->value('value') ?? '0';
        $mClasses = Setting::where('key', 'maintenance_classes')->value('value') ?? '0';

        return view('superadmin.dashboard', compact(
            'totalAdmin', 'totalKasir', 'totalSiswa', 'totalKelas',
            'mAdmin', 'mKasir', 'mSiswa', 'mCategories', 'mProducts', 'mClasses'
        ));
    }

    public function toggleMaintenance(Request $request)
    {
        $request->validate([
            'target' => 'required|string',
            'status' => 'required|in:0,1',
        ]);

        Setting::updateOrCreate(
            ['key' => 'maintenance_' . $request->target],
            ['value' => $request->status]
        );

        return back()->with('success', 'Status maintenance untuk fitur ' . ucfirst($request->target) . ' berhasil diperbarui.');
    }
}