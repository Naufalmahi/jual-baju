@extends('layouts.admin')

@section('title', 'Kelola Data Siswa')
@section('page_title', 'Kelola Data Siswa')

@section('content')
<div x-data="{ openModal: false, openImportModal: false, openResetModal: false, resetAction: '', nisn_nip: '', name: '', class_id: '', password: '' }">

    <div class="section-header flex-column flex-md-row" data-aos="fade-up">
        <form method="GET" action="{{ route('admin.siswa.index') }}" class="d-flex flex-wrap gap-2 w-100 w-md-auto">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari NISN / Nama..." class="form-control-custom" style="width:200px">
            <select name="class_id" class="form-select-custom" style="width:180px">
                <option value="">-- Semua Kelas --</option>
                @foreach($classes as $cls)<option value="{{ $cls->id }}" {{ request('class_id') == $cls->id ? 'selected' : '' }}>{{ $cls->class_name }}</option>@endforeach
            </select>
            <button type="submit" class="btn-outline-custom"><i class="bi bi-search"></i> Cari</button>
        </form>
        <div class="d-flex gap-2 mt-2 mt-md-0">
            <button @click="openImportModal = true" class="btn-primary-custom" style="background:var(--info)"><i class="bi bi-file-earmark-spreadsheet"></i> Impor CSV</button>
            <button @click="openModal = true; nisn_nip=''; name=''; class_id=''; password=''" class="btn-primary-custom"><i class="bi bi-plus-lg"></i> Tambah Siswa</button>
        </div>
    </div>

    <div class="table-custom" data-aos="fade-up">
        <div class="table-responsive">
            <table class="table-custom mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width:60px">No</th>
                        <th>NISN / Username</th>
                        <th>Nama Siswa</th>
                        <th>Kelas / Jurusan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $index => $siswa)
                    <tr>
                        <td class="text-center fw-bold" style="color:var(--neutral-400)">{{ $students->firstItem() + $index }}</td>
                        <td style="font-family:monospace;font-size:.75rem" class="fw-bold">{{ $siswa->nisn_nip }}</td>
                        <td class="fw-bold">{{ $siswa->name }}</td>
                        <td><span class="badge badge-primary">{{ $siswa->classModel->class_name ?? '-' }}</span></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <button @click="openResetModal = true; resetAction = '{{ route('admin.siswa.resetPassword', $siswa->id) }}'" class="btn-warning-custom btn-sm-custom"><i class="bi bi-key"></i> Reset</button>
                                <form action="{{ route('admin.siswa.destroy', $siswa->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data siswa ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger-custom btn-sm-custom"><i class="bi bi-trash3"></i></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center" style="padding:32px;color:var(--neutral-400)">Tidak ada data siswa.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3" style="border-top:1px solid var(--neutral-100)">{{ $students->links() }}</div>
    </div>

    <!-- Modal Tambah Siswa -->
    <div x-show="openModal" x-cloak style="position:fixed;inset:0;z-index:1060;background:rgba(0,0,0,.4);display:flex;align-items:center;justify-content:center;padding:16px" @keydown.escape.window="openModal = false">
        <div class="card-custom" style="max-width:440px;width:100%" @click.outside="openModal = false" x-transition>
            <div class="card-body-custom">
                <h5 class="fw-bold mb-4"><i class="bi bi-person-plus me-2" style="color:var(--success)"></i> Tambah Siswa Baru</h5>
                <form action="{{ route('admin.siswa.store') }}" method="POST">
                    @csrf
                    <div class="mb-3"><label class="form-label-custom">NISN (Username)</label><input type="text" name="nisn_nip" x-model="nisn_nip" placeholder="Contoh: 0051234567" required class="form-control-custom w-100"></div>
                    <div class="mb-3"><label class="form-label-custom">Nama Lengkap</label><input type="text" name="name" x-model="name" required class="form-control-custom w-100"></div>
                    <div class="mb-3">
                        <label class="form-label-custom">Pilih Kelas / Jurusan</label>
                        <select name="class_id" x-model="class_id" required class="form-select-custom w-100">
                            <option value="">-- Pilih Kelas --</option>
                            @foreach($classes as $cls)<option value="{{ $cls->id }}">{{ $cls->class_name }}</option>@endforeach
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label-custom">Password Default</label><input type="password" name="password" x-model="password" placeholder="Minimal 6 karakter" required class="form-control-custom w-100"></div>
                    <div class="d-flex justify-content-end gap-2 pt-3" style="border-top:1px solid var(--neutral-100)">
                        <button type="button" @click="openModal = false" class="btn-outline-custom">Batal</button>
                        <button type="submit" class="btn-primary-custom">Simpan Siswa</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Impor CSV -->
    <div x-show="openImportModal" x-cloak style="position:fixed;inset:0;z-index:1060;background:rgba(0,0,0,.4);display:flex;align-items:center;justify-content:center;padding:16px" @keydown.escape.window="openImportModal = false">
        <div class="card-custom" style="max-width:440px;width:100%" @click.outside="openImportModal = false" x-transition>
            <div class="card-body-custom">
                <h5 class="fw-bold mb-1"><i class="bi bi-file-earmark-spreadsheet me-2" style="color:var(--info)"></i> Impor Siswa dari CSV</h5>
                <p style="font-size:.75rem;color:var(--neutral-500);margin-bottom:16px">Format kolom CSV: <strong>nisn, name, class_id, password</strong></p>
                <form action="{{ route('admin.siswa.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3"><label class="form-label-custom">Pilih File CSV</label><input type="file" name="file" required accept=".csv,.txt" class="form-control-custom w-100"></div>
                    <div class="d-flex justify-content-end gap-2 pt-3" style="border-top:1px solid var(--neutral-100)">
                        <button type="button" @click="openImportModal = false" class="btn-outline-custom">Batal</button>
                        <button type="submit" class="btn-primary-custom" style="background:var(--info)"><i class="bi bi-upload"></i> Unggah & Impor</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Reset Password -->
    <div x-show="openResetModal" x-cloak style="position:fixed;inset:0;z-index:1060;background:rgba(0,0,0,.4);display:flex;align-items:center;justify-content:center;padding:16px" @keydown.escape.window="openResetModal = false">
        <div class="card-custom" style="max-width:440px;width:100%" @click.outside="openResetModal = false" x-transition>
            <div class="card-body-custom">
                <h5 class="fw-bold mb-4"><i class="bi bi-key me-2" style="color:var(--warning)"></i> Reset Password Siswa</h5>
                <form :action="resetAction" method="POST">
                    @csrf
                    <div class="mb-3"><label class="form-label-custom">Password Baru</label><input type="password" name="password" required placeholder="Masukkan password baru" class="form-control-custom w-100"></div>
                    <div class="d-flex justify-content-end gap-2 pt-3" style="border-top:1px solid var(--neutral-100)">
                        <button type="button" @click="openResetModal = false" class="btn-outline-custom">Batal</button>
                        <button type="submit" class="btn-warning-custom">Update Password</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
