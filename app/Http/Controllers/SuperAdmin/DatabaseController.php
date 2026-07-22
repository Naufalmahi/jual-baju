<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class DatabaseController extends Controller
{
    // Halaman Utama Fitur Pemeliharaan
    public function index()
    {
        return view('superadmin.database.index');
    }

    // 1. Download Backup Database (.sql) - Dengan Proteksi Berlapis
    public function downloadBackup()
    {
        // ==========================================
        // LAYER 1: CEK ROLE & AUTHENTICATION GANDA
        // ==========================================
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            Log::warning('Akses terlarang mencoba mengunduh backup DB dari User ID: ' . (Auth::id() ?? 'Guest'));
            abort(403, 'Akses Ditolak! Anda tidak memiliki wewenang untuk mengunduh backup database.');
        }

        // ==========================================
        // LAYER 2: CEK ALLOWED IP (HANYA DARI LOCALHOST)
        // ==========================================
        $allowedIps = ['127.0.0.1', '::1'];
        if (!in_array(request()->ip(), $allowedIps)) {
            Log::alert('Upaya backup database dari IP tidak terdaftar: ' . request()->ip() . ' oleh User: ' . Auth::user()->name);
            abort(403, 'Akses Ditolak! Fitur ini hanya dapat diakses dari jaringan lokal server.');
        }

        // ==========================================
        // LAYER 3: RATE LIMITING / COOLDOWN (MAX 1x PER 1 MENIT)
        // ==========================================
        $cacheKey = 'backup_cooldown_' . Auth::id();
        if (Cache::has($cacheKey)) {
            return back()->with('error', 'Terlalu banyak permintaan! Harap tunggu 1 menit sebelum melakukan backup kembali.');
        }

        // Lock cooldown selama 60 detik
        Cache::put($cacheKey, true, 60);

        // ==========================================
        // LAYER 4: AUDIT LOGGING (JEJAK DIGITAL)
        // ==========================================
        Log::info('Super Admin [' . Auth::user()->name . '] melakukan backup database pada IP: ' . request()->ip());

        // ==========================================
        // PROSES DUMP DATABASE
        // ==========================================
        $dbName = env('DB_DATABASE');
        $dbUser = env('DB_USERNAME');
        $dbPass = env('DB_PASSWORD');
        $dbHost = env('DB_HOST', '127.0.0.1');

        // Default path mysqldump di XAMPP / Laragon Windows
        $mysqldumpPath = 'C:\xampp\mysql\bin\mysqldump.exe';

        // Jika pakai Laragon atau lokasi XAMPP di D:
        if (!file_exists($mysqldumpPath)) {
            $possiblePaths = [        
                'D:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe',
                'D:\laragon\bin\mysql\mysql-5.7.33-winx64\bin\mysqldump.exe',
            ];

            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $mysqldumpPath = $path;
                    break;
                }
            }
        }

        $fileName = 'backup_' . $dbName . '_' . date('Y-m-d_H-i-s') . '.sql';

        // Format Password (jika di phpMyAdmin/XAMPP defaultnya kosong, aman)
        $passwordOption = !empty($dbPass) ? "--password=\"{$dbPass}\"" : "";

        // Susun command dengan tanda petik ganda pada path
        $command = "\"{$mysqldumpPath}\" --user={$dbUser} {$passwordOption} --host={$dbHost} {$dbName}";

        $output = [];
        $returnVar = null;
        exec($command, $output, $returnVar);

        if ($returnVar === 0 && !empty($output)) {
            $sqlContent = implode("\n", $output);
            return response()->streamDownload(function () use ($sqlContent) {
                echo $sqlContent;
            }, $fileName, [
                'Content-Type' => 'application/sql',
            ]);
        }

        return back()->with('error', 'Gagal backup! Path mysqldump tidak ditemukan di ' . $mysqldumpPath);
    }

    // 2. Clear Application Cache
    public function clearCache()
    {
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            abort(403);
        }

        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        return back()->with('success', 'Berhasil membersihkan cache sistem (Cache, Config, Route, & View)!');
    }

    // 3. Reset Data Transaksi (Pergantian Tahun Ajaran)
    public function resetTransactions(Request $request)
    {
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            abort(403);
        }

        $request->validate([
            'confirm_text' => 'required|in:HAPUS TRANSAKSI',
        ], [
            'confirm_text.in' => 'Teks konfirmasi salah. Ketik "HAPUS TRANSAKSI" untuk melanjutkan.'
        ]);

        try {
            DB::beginTransaction();

            // Matikan sementara foreign key checks agar proses truncate lancar
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            // Ganti nama tabel sesuai struktur DB Restoran/Kantin kamu
            DB::table('transaction_details')->truncate(); 
            DB::table('transactions')->truncate(); 

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            DB::commit();

            Log::alert('Super Admin [' . Auth::user()->name . '] melakukan RESET DATA TRANSAKSI dari IP: ' . request()->ip());

            return back()->with('success', 'Seluruh data transaksi lama berhasil di-reset!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mereset transaksi: ' . $e->getMessage());
        }
    }
}