<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    // Menampilkan halaman pengaturan
    public function index()
    {
        $settings = Setting::pluck('value', 'key')->toArray();
        return view('superadmin.settings.index', compact('settings'));
    }

    // Menyimpan / memperbarui pengaturan
    public function update(Request $request)
    {
        foreach ($request->except('_token') as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui!');
    }
}