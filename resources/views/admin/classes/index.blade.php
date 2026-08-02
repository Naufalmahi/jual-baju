@extends('layouts.admin')

@section('title', 'Kelola Kelas & Jurusan')
@section('page_title', 'Kelola Kelas & Jurusan')

@section('content')
<div x-data="{
    openModal: false, isEdit: false,
    actionUrl: '{{ route('admin.classes.store') }}',
    kelas: '', jurusan: '', nama_kelas: '',
    editClass(item) {
        this.isEdit = true; this.actionUrl = '{{ url('admin/classes') }}/' + item.id;
        this.kelas = item.grade; this.jurusan = item.major; this.nama_kelas = item.class_name;
        this.openModal = true;
    },
    addClass() {
        this.isEdit = false; this.actionUrl = '{{ route('admin.classes.store') }}';
        this.kelas = ''; this.jurusan = ''; this.nama_kelas = '';
        this.openModal = true;
    }
}">
    <div class="section-header" data-aos="fade-up">
        <h5 class="fw-bold">Daftar Kelas & Jurusan</h5>
        <button @click="addClass()" class="btn-primary-custom"><i class="bi bi-plus-lg"></i> Tambah Kelas</button>
    </div>

    <div class="table-custom" data-aos="fade-up">
        <div class="table-responsive">
            <table class="table-custom mb-0">
                <thead>
                    <tr>
                        <th class="text-center" style="width:60px">No</th>
                        <th>Tingkat Kelas</th>
                        <th>Jurusan</th>
                        <th>Nama Kelas Lengkap</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($classes as $index => $cls)
                    <tr>
                        <td class="text-center fw-bold" style="color:var(--neutral-400)">{{ $classes->firstItem() + $index }}</td>
                        <td class="fw-semibold">{{ $cls->grade }}</td>
                        <td class="fw-semibold">{{ $cls->major }}</td>
                        <td><span class="badge badge-primary">{{ $cls->class_name }}</span></td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-1">
                                <button @click="editClass({{ json_encode($cls) }})" class="btn-warning-custom btn-sm-custom"><i class="bi bi-pencil"></i> Edit</button>
                                <form action="{{ route('admin.classes.destroy', $cls->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus data kelas ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-danger-custom btn-sm-custom"><i class="bi bi-trash3"></i> Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center" style="padding:32px;color:var(--neutral-400)">Belum ada data kelas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3" style="border-top:1px solid var(--neutral-100)">{{ $classes->links() }}</div>
    </div>

    <!-- Modal Tambah/Edit Kelas -->
    <div x-show="openModal" x-cloak style="position:fixed;inset:0;z-index:1060;background:rgba(0,0,0,.4);display:flex;align-items:center;justify-content:center;padding:16px" @keydown.escape.window="openModal = false">
        <div class="card-custom" style="max-width:440px;width:100%" @click.outside="openModal = false" x-transition>
            <div class="card-body-custom">
                <h5 class="fw-bold mb-4" x-text="isEdit ? 'Edit Kelas & Jurusan' : 'Tambah Kelas Baru'"></h5>
                <form :action="actionUrl" method="POST">
                    @csrf
                    <template x-if="isEdit"><input type="hidden" name="_method" value="PUT"></template>
                    <div class="mb-3">
                        <label class="form-label-custom">Tingkat Kelas</label>
                        <select name="kelas" x-model="kelas" required class="form-select-custom w-100">
                            <option value="">-- Pilih Tingkat --</option>
                            <option value="X">X (Sepuluh)</option>
                            <option value="XI">XI (Sebelas)</option>
                            <option value="XII">XII (Dua Belas)</option>
                        </select>
                    </div>
                    <div class="mb-3"><label class="form-label-custom">Jurusan</label><input type="text" name="jurusan" x-model="jurusan" placeholder="Contoh: RPL, TKJ, AKL" required class="form-control-custom w-100"></div>
                    <div class="mb-3"><label class="form-label-custom">Nama Kelas Lengkap (Opsional)</label><input type="text" name="nama_kelas" x-model="nama_kelas" placeholder="Contoh: XI RPL 1 (Kosongkan jika auto)" class="form-control-custom w-100"></div>
                    <div class="d-flex justify-content-end gap-2 pt-3" style="border-top:1px solid var(--neutral-100)">
                        <button type="button" @click="openModal = false" class="btn-outline-custom">Batal</button>
                        <button type="submit" class="btn-primary-custom" x-text="isEdit ? 'Update' : 'Simpan'"></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
