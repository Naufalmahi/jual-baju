@extends('layouts.superadmin')

@section('title', 'Kelola Admin Toko')
@section('page_title', 'Kelola Akun Admin Toko / Koperasi')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- FORM TAMBAH ADMIN BARU -->
    <div class="bg-white p-6 rounded-xl shadow h-fit">
        <h3 class="text-md font-bold text-gray-800 mb-4">
            <i class="fas fa-user-plus mr-1 text-indigo-600"></i> Buat Akun Admin Baru
        </h3>
        <form action="{{ route('superadmin.users.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Nama Lengkap</label>
                <input type="text" name="name" placeholder="Contoh: Pak Budi" required class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Username</label>
                <input type="text" name="username" placeholder="Contoh: admin_budi" required class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">NIP / Identitas (Opsional)</label>
                <input type="text" name="nip" placeholder="Contoh: 198203..." class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Password</label>
                <input type="password" name="password" placeholder="••••••••" required class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2.5 rounded-lg text-sm transition shadow-sm">
                Simpan Akun Admin
            </button>
        </form>
    </div>

    <!-- TABEL DAFTAR ADMIN -->
    <div class="md:col-span-2 bg-white rounded-xl shadow overflow-hidden">
        <div class="p-4 border-b bg-gray-50 flex justify-between items-center">
            <h3 class="text-sm font-bold text-gray-700">Daftar Admin Terdaftar</h3>
            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 font-bold text-xs rounded-full">Total: {{ $admins->count() }} Admin</span>
        </div>
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="p-4 text-xs font-bold text-gray-500 uppercase">Nama & NIP</th>
                    <th class="p-4 text-xs font-bold text-gray-500 uppercase">Username</th>
                    <th class="p-4 text-xs font-bold text-gray-500 uppercase">Status</th>
                    <th class="p-4 text-xs font-bold text-gray-500 uppercase text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y text-sm">
                @forelse($admins as $admin)
                <tr class="hover:bg-gray-50">
                    <td class="p-4">
                        <div class="font-semibold text-gray-800">{{ $admin->name }}</div>
                        <div class="text-xs text-gray-400">NIP: {{ $admin->nisn_nip ?? '-' }}</div>
                    </td>
                    <td class="p-4 text-gray-600 font-mono text-xs">{{ $admin->username }}</td>
                    <td class="p-4">
                        <span class="px-2 py-1 text-xs rounded-full font-bold {{ $admin->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $admin->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="p-4 flex justify-center space-x-2">
                        <!-- Reset Password -->
                        <form action="{{ route('superadmin.users.resetPassword', $admin->id) }}" method="POST" onsubmit="return confirm('Reset password akun Admin ini ke password123?')">
                            @csrf
                            <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white px-3 py-1.5 rounded text-xs font-semibold">
                                <i class="fas fa-key"></i> Reset Pass
                            </button>
                        </form>

                        <!-- Toggle Status -->
                        <form action="{{ route('superadmin.users.toggleStatus', $admin->id) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="bg-slate-600 hover:bg-slate-700 text-white px-3 py-1.5 rounded text-xs font-semibold">
                                {{ $admin->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-6 text-center text-gray-500">Belum ada akun Admin Toko yang dibuat. Silakan tambah di form sebelah kiri.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection