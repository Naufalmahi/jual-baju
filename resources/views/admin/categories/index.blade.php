@extends('layouts.admin')

@section('title', 'Kategori Produk')
@section('page_title', 'Kelola Kategori Produk & Ukuran')

@section('content')
<div x-data="{ openModal: false, name: '' }">

    <!-- HEADER & BUTTON TAMBAH -->
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-lg font-bold text-gray-800">Daftar Kategori Produk</h2>
            <p class="text-xs text-gray-500">Kelola kelompok barang seperti Seragam, Aksesoris, Atribut, dll.</p>
        </div>

        <button @click="openModal = true; name = ''" 
            class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2 rounded-lg text-sm transition flex items-center gap-2">
            <i class="fas fa-plus"></i> Tambah Kategori
        </button>
    </div>

    <!-- TABEL DATA KATEGORI -->
    <div class="bg-white rounded-xl shadow overflow-hidden max-w-4xl">
        <table class="w-full text-left border-collapse text-sm">
            <thead>
                <tr class="bg-emerald-50 border-b text-emerald-900 font-bold uppercase text-xs">
                    <th class="p-4 w-16 text-center">No</th>
                    <th class="p-4">Nama Kategori</th>
                    <th class="p-4">Slug</th>
                    <th class="p-4 text-center">Jumlah Produk</th>
                    <th class="p-4 text-center w-28">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($categories as $index => $category)
                <tr class="hover:bg-gray-50">
                    <td class="p-4 text-center font-bold text-gray-500">{{ $index + 1 }}</td>
                    <td class="p-4 font-bold text-gray-800">{{ $category->name }}</td>
                    <td class="p-4 text-gray-500 font-mono text-xs">{{ $category->slug }}</td>
                    <td class="p-4 text-center">
                        <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 rounded-full text-xs font-bold">
                            {{ $category->products_count ?? 0 }} Produk
                        </span>
                    </td>
                    <td class="p-4 text-center">
                        <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus kategori ini? Semua produk di dalamnya mungkin akan terpengaruh.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700 text-xs font-bold transition">
                                <i class="fas fa-trash"></i> Hapus
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="p-4 text-center text-gray-500">Belum ada kategori produk.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- MODAL TAMBAH KATEGORI -->
    <div x-show="openModal" class="fixed inset-0 z-50 bg-black/50 flex items-center justify-center" x-cloak>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-md p-6">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Tambah Kategori Baru</h3>
            
            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nama Kategori</label>
                    <input type="text" name="name" x-model="name" placeholder="Contoh: Seragam Olahraga, Aksesoris, Baju Pramuka" required class="w-full p-2.5 border rounded-lg text-sm focus:ring-2 focus:ring-emerald-500 focus:outline-none">
                </div>

                <div class="flex justify-end gap-2 pt-4 border-t">
                    <button type="button" @click="openModal = false" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm font-semibold">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection