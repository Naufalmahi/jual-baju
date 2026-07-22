<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;

class DatabaseController extends Controller
{
    // Halaman Utama Fitur Pemeliharaan
    public function index()
    {
        return view('superadmin.database.index');
    }

    // 1. Download Backup Database (.sql)
    public function downloadBackup()
    {
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
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        return back()->with('success', 'Berhasil membersihkan cache sistem (Cache, Config, Route, & View)!');
    }

    // 3. Reset Data Transaksi (Pergantian Tahun Ajaran)
    public function resetTransactions(Request $request)
    {
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

            return back()->with('success', 'Seluruh data transaksi lama berhasil di-reset!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal mereset transaksi: ' . $e->getMessage());
        }
    }
}