@extends('layouts.admin')

@section('title', 'Kelola Data Siswa')
@section('page_title', 'Kelola Data Siswa')

@section('content')
<div x-data="{ 
    openModal: false, 
    openImportModal: false, 
    openResetModal: false, 
    resetAction: '', 
    nisn_nip: '', 
    name: '', 
    class_id: '', 
    password: '' 
}">

    <!-- HEADER, SEARCH, & BUTTONS -->
    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <!-- FORM PENCARIAN & FILTER -->
        <form method="GET" action="{{ route('admin.siswa.index') }}" class="flex flex-wrap items-center gap-2 w-full md:w-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NISN / Nama..." 
                class="px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
            
            <select name="class_id" class="px-4 py-2 border rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                <option value="">-- Semua Kelas & Jurusan --</option>
                @foreach($classes as $cls)
                    <option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>
                        {{ $cls->class_name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" class="px-4 py-2 bg-slate-700 text-white text-sm rounded-lg hover:bg-slate-800 transition">
                <i class="fas fa-search"></i> Cari
            </button>
        </form>

        <!-- TOMBOL AKSI -->
        <div class="flex items-center gap-2 w-full md:w-auto justify-end">
            <button @click="openImportModal = true" 
                class="bg-blue-600 hover:bg-blue-700 text-white font-bold px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
                <i class="fas fa-file-excel"></i> Impor CSV
            </button>

            <button @click="openModal = true; nisn_nip = ''; name = ''; class_id = ''; password = ''" 
                class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
                <i class="fas fa-plus"></i> Tambah Siswa
            </button>
        </div>
    </div>

    <!-- TABEL DATA SISWA -->
    <div class="bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-emerald-50 border-b text-emerald-900 font-bold uppercase text-xs">
                    <th class="p-4 w-12 text-center">No</th>
                    <th class="p-4">NISN / Username</th>
                    <th class="p-4">Nama Siswa</th>
                    <th class="p-4">Kelas / Jurusan</th>
                    <th class="p-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($students as $index => $siswa)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 text-center font-bold text-gray-500">
                        {{ $students->firstItem() + $index }}
                    </td>
                    <td class="p-4 font-mono text-xs font-bold text-gray-700">{{ $siswa->nisn_nip }}</td>
                    <td class="p-4 font-bold text-gray-800">{{ $siswa->name }}</td>
                    <td class="p-4 text-gray-600">
                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-md text-xs font-semibold">
                            {{ $siswa->classModel->class_name ?? '-' }}
                        </span>
                    </td>
                    <td class="p-4 text-center space-x-2">
                        <!-- RESET PASSWORD -->
                        <button @click="openResetModal = true; resetAction = '{{ route('admin.siswa.resetPassword', $siswa->id) }}'" 
                            class="px-3 py-1 bg-amber-500 text-white rounded hover:bg-amber-600 text-xs font-bold transition">
                            <i class="fas fa-key"></i> Reset Pass
                        </button>

                        <!-- HAPUS SISWA -->
                        <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus data siswa ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs font-bold transition">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">Tidak ada data siswa.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <!-- PAGINATION -->
        <div class="p-4 border-t">
            {{ $students->links() }}
        </div>
    </div>

    <!-- MODAL TAMBAH SISWA BARU -->
    <div x-show="openModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center" x-cloak>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-user-plus text-emerald-600 mr-2"></i> Tambah Siswa Baru</h3>
            
            <form action="{{ route('admin.siswa.store') }}" method="POST" class="space-y-4">
                @csrf
                
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">NISN (Username)</label>
                    <input type="text" name="nisn_nip" x-model="nisn_nip" placeholder="Contoh: 0051234567" required class="w-full p-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Lengkap</label>
                    <input type="text" name="name" x-model="name" placeholder="Masukkan nama siswa" required class="w-full p-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Pilih Kelas / Jurusan</label>
                    <select name="class_id" x-model="class_id" required class="w-full p-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none bg-white">
                        <option value="">-- Pilih Kelas --</option>
                        @foreach($classes as $cls)
                            <option value="{{ $cls->id }}">
                                {{ $cls->class_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Password Default</label>
                    <input type="password" name="password" x-model="password" placeholder="Minimal 6 karakter" required class="w-full p-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700">Simpan Siswa</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL IMPOR CSV SISWA -->
    <div x-show="openImportModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center" x-cloak>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-2"><i class="fas fa-file-csv text-blue-600 mr-2"></i> Impor Siswa dari CSV</h3>
            <p class="text-xs text-gray-500 mb-4">Format kolom CSV: <strong>nisn, name, class_id, password</strong></p>

            <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Pilih File CSV</label>
                    <input type="file" name="file" required accept=".csv,.txt" class="w-full text-sm border p-2 rounded">
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <button type="button" @click="openImportModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm font-semibold hover:bg-blue-700">Unggah & Impor</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL RESET PASSWORD SISWA -->
    <div x-show="openResetModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center" x-cloak>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4"><i class="fas fa-key text-amber-500 mr-2"></i> Reset Password Siswa</h3>

            <form :action="resetAction" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Password Baru</label>
                    <input type="password" name="password" required placeholder="Masukkan password baru" class="w-full p-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-amber-500 focus:outline-none">
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <button type="button" @click="openResetModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-amber-500 text-white rounded-lg text-sm font-semibold hover:bg-amber-600">Update Password</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection