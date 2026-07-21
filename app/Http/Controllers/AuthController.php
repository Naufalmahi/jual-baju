<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Form Login Petugas (Admin/Kasir/Super Admin)
    public function showLoginPetugas()
    {
        if (Auth::check()) {
            return $this->redirectUserByRole(Auth::user()->role);
        }
        return view('auth.login-petugas');
    }

    // Form Login Siswa
    public function showLoginSiswa()
    {
        if (Auth::check()) {
            return $this->redirectUserByRole(Auth::user()->role);
        }
        return view('auth.login-siswa');
    }

    // Process Login Petugas
    public function loginPetugas(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Cegah siswa login lewat pintu petugas
            if ($user->role === 'siswa') {
                Auth::logout();
                return redirect()->back()->withErrors(['username' => 'Akses ditolak. Silakan login lewat portal siswa.']);
            }

            if (!$user->is_active) {
                Auth::logout();
                return redirect()->back()->withErrors(['username' => 'Akun kamu nonaktif.']);
            }

            $request->session()->regenerate();
            return $this->redirectUserByRole($user->role);
        }

        return redirect()->back()->withErrors(['username' => 'Username atau password salah!']);
    }

    // Process Login Siswa (Pakai NISN)
    public function loginSiswa(Request $request)
    {
        $request->validate([
            'nisn' => 'required|string',
            'password' => 'required|string',
        ]);

        // Login menggunakan field nisn_nip
        if (Auth::attempt(['nisn_nip' => $request->nisn, 'password' => $request->password])) {
            $user = Auth::user();

            if ($user->role !== 'siswa') {
                Auth::logout();
                return redirect()->back()->withErrors(['nisn' => 'Akun ini bukan akun siswa.']);
            }

            if (!$user->is_active) {
                Auth::logout();
                return redirect()->back()->withErrors(['nisn' => 'Akun siswa nonaktif. Hubungi Admin.']);
            }

            $request->session()->regenerate();
            return $this->redirectUserByRole($user->role);
        }

        return redirect()->back()->withErrors(['nisn' => 'NISN atau password salah!']);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.siswa')->with('success', 'Berhasil keluar.');
    }

    private function redirectUserByRole($role)
    {
        return match ($role) {
            'super_admin' => redirect()->route('superadmin.dashboard'),
            'admin'       => redirect()->route('admin.dashboard'),
            'kasir'       => redirect()->route('kasir.dashboard'),
            'siswa'       => redirect()->route('siswa.dashboard'),
            default       => redirect('/'),
        };
    }
}