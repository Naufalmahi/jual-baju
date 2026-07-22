@extends('layouts.superadmin')

@section('title', 'Master Kelas')
@section('page_title', 'Kelola Data Kelas & Jurusan')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- FORM TAMBAH KELAS -->
    <div class="bg-white p-6 rounded-xl shadow h-fit">
        <h3 class="text-md font-bold text-gray-800 mb-4">Tambah Kelas Baru</h3>
        <form action="{{ route('superadmin.classes.store') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Nama Kelas</label>
                <input type="text" name="class_name" placeholder="Contoh: XI RPL 1" required class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Tingkat</label>
                <select name="grade" required class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-500">
                    <option value="X">X</option>
                    <option value="XI">XI</option>
                    <option value="XII">XII</option>
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-600 mb-1">Jurusan</label>
                <input type="text" name="major" placeholder="Contoh: Rekayasa Perangkat Lunak" required class="w-full border rounded-lg p-2 text-sm focus:ring-2 focus:ring-indigo-500">
            </div>

            <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg text-sm transition">
                Simpan Kelas
            </button>
        </form>
    </div>

    <!-- TABEL DAFTAR KELAS -->
    <div class="md:col-span-2 bg-white rounded-xl shadow overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-50 border-b">
                <tr>
                    <th class="p-4 text-xs font-bold text-gray-500 uppercase">Nama Kelas</th>
                    <th class="p-4 text-xs font-bold text-gray-500 uppercase">Tingkat</th>
                    <th class="p-4 text-xs font-bold text-gray-500 uppercase">Jurusan</th>
                    <th class="p-4 text-xs font-bold text-gray-500 uppercase">Siswa</th>
                    <th class="p-4 text-xs font-bold text-gray-500 uppercase text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y text-sm">
                @forelse($classes as $class)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 font-semibold text-gray-800">{{ $class->class_name }}</td>
                    <td class="p-4 text-gray-600">{{ $class->grade }}</td>
                    <td class="p-4 text-gray-600">{{ $class->major }}</td>
                    <td class="p-4 text-gray-600">{{ $class->students_count }} Siswa</td>
                    <td class="p-4 text-center">
                        <form action="{{ route('superadmin.classes.destroy', $class->id) }}" method="POST" onsubmit="return confirm('Hapus kelas ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-semibold">
                                Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">Belum ada data kelas.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection