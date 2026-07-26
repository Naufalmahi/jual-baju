<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        $request->validate([
            'app_logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'tax_percentage' => 'nullable|numeric|min:0|max:100',
            'max_debt_limit' => 'nullable|numeric|min:0',
            'stock_warning_limit' => 'nullable|integer|min:0',
            'point_multiplier' => 'nullable|numeric|min:0',
        ]);

        // Handing upload logo jika ada file baru yang diunggah
        if ($request->hasFile('app_logo')) {
            $oldLogo = Setting::where('key', 'app_logo')->value('value');
            if ($oldLogo && Storage::disk('public')->exists($oldLogo)) {
                Storage::disk('public')->delete($oldLogo);
            }

            $logoPath = $request->file('app_logo')->store('settings', 'public');
            Setting::updateOrCreate(['key' => 'app_logo'], ['value' => $logoPath]);
        }

        // Ambil semua data inputan form selain token & logo
        $inputs = $request->except(['_token', 'app_logo']);

        // Set nilai default 0 untuk checkbox jika tidak dicentang oleh user
        $checkboxes = ['enable_qris', 'allow_debt'];
        foreach ($checkboxes as $cb) {
            if (!array_key_exists($cb, $inputs)) {
                $inputs[$cb] = '0';
            }
        }

        // Simpan ke database
        foreach ($inputs as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value ?? '']
            );
        }

        return redirect()->back()->with('success', 'Pengaturan aplikasi berhasil diperbarui!');
    }
}