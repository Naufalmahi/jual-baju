<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSystemMaintenance
{
    public function handle(Request $request, Closure $next, string $feature = null): Response
    {
        // 1. Super Admin selalu bebas dari Maintenance Mode
        if (Auth::check() && Auth::user()->role === 'super_admin') {
            return $next($request);
        }

        if ($feature) {
            $isMaintenance = Setting::where('key', 'maintenance_' . $feature)->value('value');

            if ($isMaintenance === '1') {
                // Tentukan layout berdasarkan role pengguna saat ini
                $layout = 'layouts.admin';
                if (Auth::check()) {
                    if (Auth::user()->role === 'kasir') {
                        $layout = 'layouts.kasir';
                    } elseif (Auth::user()->role === 'siswa') {
                        $layout = 'layouts.siswa';
                    }
                }

                // Kembalikan View Blade khusus Maintenance (HTTP Status 503)
                return response()->view('errors.page_maintenance', [
                    'feature' => $feature,
                    'layout'  => $layout
                ], 503);
            }
        }

        return $next($request);
    }
}