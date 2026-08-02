@extends('layouts.admin')

@section('title', 'Kelola Kasir')
@section('page_title', 'Kelola Data Kasir Toko')

@section('content')
<div x-data="{ openModal: false, openResetModal: false, resetAction: '' }">
    <div class="section-header" data-aos="fade-up">
        <h5 class="fw-bold">Daftar Kasir Aktif</h5>
        <button @click="openModal = true" class="btn-primary-custom"><i class="bi bi-plus-lg"></i> Tambah Kasir</button>
    </div>

    <div class="table-custom" data-aos="fade-up">
        <div class="table-responsive">
            <table class="table-custom mb-0">
                <thead>
                    <tr>
                        <th>Nama Petugas Kasir</th>
                        <th>Username Login</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kasirs as $kasir)
                    <tr>
                        <td class="fw-bold">{{ $kasir->name }}</td>
                        <td style="font-family:monospace;font-size:.8rem;color:var(--neutral-600)">{{ $kasir->username }}</td>
                        <td class="text-center">
                            <span class="badge {{ $kasir->is_active ? 'badge-success' : 'badge-danger' }}">{{ $kasir->is_active ? 'Aktif' : 'Nonaktif' }}</span>
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <button @click="openResetModal = true; resetAction = '{{ route('admin.kasir.resetPassword', $kasir->id) }}'" class="btn-warning-custom btn-sm-custom">Reset Password</button>
                                <form action="{{ route('admin.kasir.toggleStatus', $kasir->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="{{ $kasir->is_active ? 'btn-danger-custom' : 'btn-success-custom' }} btn-sm-custom">{{ $kasir->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center" style="padding:32px;color:var(--neutral-400)">Belum ada akun kasir.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Kasir -->
    <div x-show="openModal" x-cloak style="position:fixed;inset:0;z-index:1060;background:rgba(0,0,0,.4);display:flex;align-items:center;justify-content:center;padding:16px" @keydown.escape.window="openModal = false">
        <div class="card-custom" style="max-width:440px;width:100%" @click.outside="openModal = false" x-transition>
            <div class="card-body-custom">
                <h5 class="fw-bold mb-4">Tambah Akun Kasir</h5>
                <form action="{{ route('admin.kasir.store') }}" method="POST">
                    @csrf
                    <div class="mb-3"><label class="form-label-custom">Nama Lengkap</label><input type="text" name="name" required class="form-control-custom w-100"></div>
                    <div class="mb-3"><label class="form-label-custom">Username Login</label><input type="text" name="username" required class="form-control-custom w-100"></div>
                    <div class="mb-3"><label class="form-label-custom">Password Initial</label><input type="password" name="password" required class="form-control-custom w-100"></div>
                    <div class="d-flex justify-content-end gap-2 pt-3" style="border-top:1px solid var(--neutral-100)">
                        <button type="button" @click="openModal = false" class="btn-outline-custom">Batal</button>
                        <button type="submit" class="btn-primary-custom">Simpan Kasir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Reset Password -->
    <div x-show="openResetModal" x-cloak style="position:fixed;inset:0;z-index:1060;background:rgba(0,0,0,.4);display:flex;align-items:center;justify-content:center;padding:16px" @keydown.escape.window="openResetModal = false">
        <div class="card-custom" style="max-width:440px;width:100%" @click.outside="openResetModal = false" x-transition>
            <div class="card-body-custom">
                <h5 class="fw-bold mb-4">Reset Password Kasir</h5>
                <form :action="resetAction" method="POST">
                    @csrf
                    <div class="mb-3"><label class="form-label-custom">Password Baru</label><input type="password" name="password" required class="form-control-custom w-100"></div>
                    <div class="d-flex justify-content-end gap-2 pt-3" style="border-top:1px solid var(--neutral-100)">
                        <button type="button" @click="openResetModal = false" class="btn-outline-custom">Batal</button>
                        <button type="submit" class="btn-warning-custom">Reset Sekarang</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
