<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;

class DatabaseController extends Controller
{
    // Halaman Utama Fitur Pemeliharaan
    public function index()
    {
        return view('superadmin.database.index');
    }

    // 1. Download Backup Database (.sql) - Dengan Proteksi & Fallback Method
    public function downloadBackup()
    {
        // LAYER 1: CEK ROLE & AUTHENTICATION GANDA
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            Log::warning('Akses terlarang mencoba mengunduh backup DB dari User ID: ' . (Auth::id() ?? 'Guest'));
            abort(403, 'Akses Ditolak! Anda tidak memiliki wewenang untuk mengunduh backup database.');
        }

        // LAYER 2: CEK ALLOWED IP (HANYA DARI LOCALHOST)
        $allowedIps = ['127.0.0.1', '::1'];
        if (!in_array(request()->ip(), $allowedIps)) {
            Log::alert('Upaya backup database dari IP tidak terdaftar: ' . request()->ip() . ' oleh User: ' . Auth::user()->name);
            abort(403, 'Akses Ditolak! Fitur ini hanya dapat diakses dari jaringan lokal server.');
        }

        // LAYER 3: RATE LIMITING / COOLDOWN (MAX 1x PER 1 MENIT)
        $cacheKey = 'backup_cooldown_' . Auth::id();
        if (Cache::has($cacheKey)) {
            return back()->with('error', 'Terlalu banyak permintaan! Harap tunggu 1 menit sebelum melakukan backup kembali.');
        }

        // Lock cooldown selama 60 detik
        Cache::put($cacheKey, true, 60);

        // LAYER 4: AUDIT LOGGING
        Log::info('Super Admin [' . Auth::user()->name . '] melakukan backup database pada IP: ' . request()->ip());

        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');
        $dbPass = config('database.connections.mysql.password');
        $dbHost = config('database.connections.mysql.host', '127.0.0.1');

        $fileName = 'backup_' . $dbName . '_' . date('Y-m-d_H-i-s') . '.sql';

        // CARA 1: DENGAN MYSQLDUMP (CLI)
        $mysqldumpPath = 'C:\xampp\mysql\bin\mysqldump.exe';
        if (!file_exists($mysqldumpPath)) {
            $possiblePaths = [        
                'D:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe',
                'D:\laragon\bin\mysql\mysql-5.7.33-winx64\bin\mysqldump.exe',
                'C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe',
                'C:\laragon\bin\mysql\mysql-5.7.33-winx64\bin\mysqldump.exe',
                '/usr/bin/mysqldump', // Support Linux Server / Hosting
            ];

            foreach ($possiblePaths as $path) {
                if (file_exists($path)) {
                    $mysqldumpPath = $path;
                    break;
                }
            }
        }

        if (file_exists($mysqldumpPath)) {
            $passwordOption = !empty($dbPass) ? "--password=\"{$dbPass}\"" : "";
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
        }

        // CARA 2: FALLBACK PHP NATIVE DUMP (Jika mysqldump.exe tidak terdeteksi)
        try {
            $tables = DB::select('SHOW TABLES');
            $tableKey = "Tables_in_" . $dbName;

            $sqlContent = "-- BACKUP FULL DATABASE APPLICATION\n";
            $sqlContent .= "-- Generated: " . date('Y-m-d H:i:s') . "\n\n";
            $sqlContent .= "SET FOREIGN_KEY_CHECKS=0;\n\n";

            foreach ($tables as $table) {
                $tableName = $table->$tableKey;

                // DDL: Struktur Tabel
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $sqlContent .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sqlContent .= $createTable[0]->{'Create Table'} . ";\n\n";

                // DML: Data Tabel
                $rows = DB::table($tableName)->get();
                foreach ($rows as $row) {
                    $arrayRow = (array) $row;
                    $keys = array_map(function ($k) { return "`{$k}`"; }, array_keys($arrayRow));
                    $values = array_map(function ($v) {
                        if (is_null($v)) return "NULL";
                        return "'" . addslashes($v) . "'";
                    }, array_values($arrayRow));

                    if (count($values) > 0) {
                        $sqlContent .= "INSERT INTO `{$tableName}` (" . implode(', ', $keys) . ") VALUES (" . implode(', ', $values) . ");\n";
                    }
                }
                $sqlContent .= "\n";
            }

            $sqlContent .= "SET FOREIGN_KEY_CHECKS=1;\n";

            return response()->streamDownload(function () use ($sqlContent) {
                echo $sqlContent;
            }, $fileName, [
                'Content-Type' => 'application/sql',
            ]);

        } catch (\Exception $e) {
            Log::error('Backup DB Fallback Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal backup database: ' . $e->getMessage());
        }
    }

    // 2. RESTORE DATABASE (UPLOAD FILE .SQL) - SOLUSI ERROR NO ACTIVE TRANSACTION
    public function restoreBackup(Request $request)
    {
        // Check Auth & Role
        if (!Auth::check() || Auth::user()->role !== 'super_admin') {
            abort(403, 'Akses Ditolak!');
        }

        // Validasi Strict File Upload
        $request->validate([
            'backup_file' => 'required|file|max:20480', // Max 20MB
        ], [
            'backup_file.required' => 'Pilih file backup (.sql) terlebih dahulu.',
            'backup_file.max' => 'Ukuran file backup maksimal 20 MB.',
        ]);

        $file = $request->file('backup_file');
        
        // Verifikasi Ekstensi File Manual
        $extension = strtolower($file->getClientOriginalExtension());
        if ($extension !== 'sql') {
            return back()->with('error', 'Format file tidak valid! Wajib mengunggah file ber-ekstensi .sql');
        }

        try {
            $sqlContent = File::get($file->getRealPath());

            if (empty(trim($sqlContent))) {
                return back()->with('error', 'File SQL kosong atau tidak memiliki query valid!');
            }

            // PERBAIKAN: Matikan FK Checks tanpa DB::beginTransaction()
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            
            // Eksekusi seluruh script SQL sekaligus
            DB::unprepared($sqlContent);
            
            // Nyalakan kembali FK Checks
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            // Clear Cache Otomatis setelah Restore
            Artisan::call('cache:clear');
            Artisan::call('view:clear');

            Log::alert('Super Admin [' . Auth::user()->name . '] melakukan RESTORE DATABASE dari IP: ' . request()->ip());

            return back()->with('success', 'Database seluruh sistem berhasil di-restore kembali!');
        } catch (\Exception $e) {
            // Pastikan FK Checks dinyalakan lagi jika eksekusi SQL error
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            
            Log::error('Restore DB Error: ' . $e->getMessage());
            return back()->with('error', 'Gagal merestore database: ' . $e->getMessage());
        }
    }

    // 3. Clear Application Cache
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

    // 4. Reset Data Transaksi (Pergantian Tahun Ajaran)
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

            DB::statement('SET FOREIGN_KEY_CHECKS=0;');

            DB::table('order_items')->truncate();
            DB::table('orders')->truncate();

            DB::statement('SET FOREIGN_KEY_CHECKS=1;');

            DB::commit();

            Log::alert('Super Admin [' . Auth::user()->name . '] melakukan RESET DATA TRANSAKSI dari IP: ' . request()->ip());

            return back()->with('success', 'Seluruh data transaksi lama berhasil di-reset!');
        } catch (\Exception $e) {
            DB::rollBack();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
            return back()->with('error', 'Gagal mereset transaksi: ' . $e->getMessage());
        }
    }
}