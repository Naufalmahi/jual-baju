<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!Auth::check()) {
            return redirect()->route(
                str_starts_with($request->path(), 'siswa') ? 'login.siswa' : 'login.petugas'
            );
        }

        $user = Auth::user();

        // Cek apakah role user ada dalam daftar role yang diizinkan
        if (in_array($user->role, $roles) && $user->is_active) {
            return $next($request);
        }

        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}