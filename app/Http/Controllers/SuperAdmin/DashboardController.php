<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Mengambil total count untuk statistik di dashboard
        $totalAdmin = User::where('role', 'admin')->count();
        $totalKasir = User::where('role', 'kasir')->count();
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalKelas = ClassModel::count();

        return view('superadmin.dashboard', compact('totalAdmin', 'totalKasir', 'totalSiswa', 'totalKelas'));
    }
}