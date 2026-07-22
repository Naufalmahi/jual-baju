@extends('layouts.admin')

@section('title', 'Kelola Kasir')
@section('page_title', 'Kelola Data Kasir Toko')

@section('content')
<div x-data="{ openModal: false, openResetModal: false, resetAction: '' }">

    <div class="flex justify-between items-center mb-6">
        <h2 class="text-sm font-bold text-gray-500 uppercase">Daftar Kasir Aktif</h2>
        <button @click="openModal = true" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Kasir Baru
        </button>
    </div>

    <!-- TABEL KASIR -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-emerald-50 border-b text-emerald-900 font-bold uppercase text-xs">
                    <th class="p-4">Nama Petugas Kasir</th>
                    <th class="p-4">Username Login</th>
                    <th class="p-4 text-center">Status</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($kasirs as $kasir)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-bold text-gray-800">{{ $kasir->name }}</td>
                    <td class="p-4 font-mono text-gray-600">{{ $kasir->username }}</td>
                    <td class="p-4 text-center">
                        <span class="px-2.5 py-1 text-xs font-bold rounded-full {{ $kasir->is_active ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            {{ $kasir->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <button @click="openResetModal = true; resetAction = '{{ route('admin.kasir.resetPassword', $kasir->id) }}'" 
                            class="px-3 py-1 bg-amber-500 text-white rounded text-xs font-bold hover:bg-amber-600 transition">
                            Reset Password
                        </button>

                        <form action="{{ route('admin.kasir.toggleStatus', $kasir->id) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="px-3 py-1 {{ $kasir->is_active ? 'bg-red-600' : 'bg-green-600' }} text-white rounded text-xs font-bold transition">
                                {{ $kasir->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="p-4 text-center text-gray-500">Belum ada akun kasir.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- MODAL TAMBAH KASIR -->
    <div x-show="openModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center" x-cloak>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Tambah Akun Kasir</h3>
            <form action="{{ route('admin.kasir.store') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Lengkap Kasir</label>
                    <input type="text" name="name" required class="w-full p-2 border rounded text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Username Login</label>
                    <input type="text" name="username" required class="w-full p-2 border rounded text-sm">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Password Initial</label>
                    <input type="password" name="password" required class="w-full p-2 border rounded text-sm">
                </div>
                <div class="flex justify-end gap-2 pt-4 border-t">
                    <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700">Simpan Kasir</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL RESET PASSWORD -->
    <div x-show="openResetModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center" x-cloak>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Reset Password Kasir</h3>
            <form :action="resetAction" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Password Baru</label>
                    <input type="password" name="password" required class="w-full p-2 border rounded text-sm">
                </div>
                <div class="flex justify-end gap-2 pt-4 border-t">
                    <button type="button" @click="openResetModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-amber-600 text-white rounded-lg text-sm font-semibold hover:bg-amber-700">Reset Sekarang</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection