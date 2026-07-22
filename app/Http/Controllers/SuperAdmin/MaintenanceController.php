<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\PageMaintenance;
use Illuminate\Http\Request;

class MaintenanceController extends Controller
{
    public function index()
    {
        // Daftar daftar halaman utama yang bisa di-maintenance
        $defaultPages = [
            ['route_name' => 'admin.products.index', 'title' => 'Kelola Barang & Stok'],
            ['route_name' => 'admin.categories.index', 'title' => 'Kelola Kategori Produk'],
            ['route_name' => 'admin.classes.index', 'title' => 'Kelola Kelas & Jurusan'],
            ['route_name' => 'admin.kasir.index', 'title' => 'Kelola Kasir'],
        ];

        // Pastikan route terdaftar di DB
        foreach ($defaultPages as $p) {
            PageMaintenance::firstOrCreate(
                ['route_name' => $p['route_name']],
                ['title' => $p['title'], 'is_maintenance' => false]
            );
        }

        $pages = PageMaintenance::all();

        return view('superadmin.maintenance.index', compact('pages'));
    }

    public function toggle(Request $request, $id)
    {
        $page = PageMaintenance::findOrFail($id);
        $page->is_maintenance = !$page->is_maintenance;
        $page->save();

        $status = $page->is_maintenance ? 'diaktifkan (Maintenance 503)' : 'dinonaktifkan (Normal)';

        return back()->with('success', "Status maintenance untuk halaman {$page->title} berhasil {$status}.");
    }
}