@extends('layouts.admin')

@section('title', 'Kategori Produk')
@section('page_title', 'Kelola Kategori Produk & Ukuran')

@section('content')
<div x-data="{ openModal: false, name: '' }">
    <div class="section-header" data-aos="fade-up">
        <div>
            <h2 class="fw-bold">Daftar Kategori Produk</h2>
            <p style="font-size:.8rem;color:var(--neutral-500)">Kelola kelompok barang seperti Seragam, Aksesoris, Atribut, dll.</p>
        </div>
        <button @click="openModal = true; name = ''" class="btn-primary-custom">
            <i class="bi bi-plus-lg"></i> Tambah Kategori
        </button>
    </div>

    <div class="table-custom" data-aos="fade-up">
        <div class="table-responsive">
            <table class="table-custom mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width:60px">No</th>
                        <th>Nama Kategori</th>
                        <th>Slug</th>
                        <th class="text-center">Jumlah Produk</th>
                        <th class="text-center" style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $index => $category)
                    <tr>
                        <td class="text-center fw-bold" style="color:var(--neutral-400)">{{ $index + 1 }}</td>
                        <td class="fw-bold">{{ $category->name }}</td>
                        <td style="font-family:monospace;font-size:.75rem;color:var(--neutral-500)">{{ $category->slug }}</td>
                        <td class="text-center">
                            <span class="badge badge-success">{{ $category->products_count ?? 0 }} Produk</span>
                        </td>
                        <td class="text-center">
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-danger-custom btn-sm-custom">
                                    <i class="bi bi-trash3"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center" style="padding:32px;color:var(--neutral-400)">Belum ada kategori produk.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Kategori -->
    <div x-show="openModal" x-cloak class="modal-backdrop-custom" style="position:fixed;inset:0;z-index:1060;background:rgba(0,0,0,.4);display:flex;align-items:center;justify-content:center;padding:16px" @keydown.escape.window="openModal = false">
        <div class="card-custom" style="max-width:440px;width:100%" @click.outside="openModal = false" x-transition>
            <div class="card-body-custom">
                <h5 class="fw-bold mb-4">Tambah Kategori Baru</h5>
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="form-label-custom">Nama Kategori</label>
                        <input type="text" name="name" x-model="name" placeholder="Contoh: Seragam Olahraga, Aksesoris" required class="form-control-custom w-100">
                    </div>
                    <div class="d-flex justify-content-end gap-2 pt-3" style="border-top:1px solid var(--neutral-100)">
                        <button type="button" @click="openModal = false" class="btn-outline-custom">Batal</button>
                        <button type="submit" class="btn-primary-custom">Simpan Kategori</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
