<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Menampilkan daftar khusus Admin Toko / Koperasi
    public function index()
    {
        $admins = User::where('role', 'admin')->latest()->get();
        return view('superadmin.users.index', compact('admins'));
    }

    // Menambah akun Admin baru oleh Super Admin
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'password' => 'required|string|min:6',
            'nip'      => 'nullable|string|max:50',
        ]);

        User::create([
            'name'      => $request->name,
            'username'  => $request->username,
            'password'  => Hash::make($request->password),
            'nisn_nip'  => $request->nip,
            'role'      => 'admin',
            'is_active' => true,
        ]);

        return redirect()->back()->with('success', 'Akun Admin Toko berhasil dibuat!');
    }

    // Reset Password Admin
    public function resetPassword($id)
    {
        $user = User::where('role', 'admin')->findOrFail($id);
        $user->update([
            'password' => Hash::make('password123'), // Default reset password
        ]);

        return redirect()->back()->with('success', "Password untuk {$user->name} berhasil di-reset menjadi: password123");
    }

    // Toggle Status Aktif/Nonaktif Admin
    public function toggleStatus($id)
    {
        $user = User::where('role', 'admin')->findOrFail($id);
        $user->update([
            'is_active' => !$user->is_active,
        ]);

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';
        return redirect()->back()->with('success', "Akun Admin {$user->name} berhasil {$status}.");
    }
}