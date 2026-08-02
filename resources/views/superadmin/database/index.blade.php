@extends('layouts.superadmin')

@section('title', 'Pemeliharaan & Backup Database')
@section('page_title', 'Pemeliharaan Database')

@section('content')
<div class="row g-4">
    <!-- Backup DB -->
    <div class="col-md-6 col-xl-3" data-aos="fade-up">
        <div class="card-custom h-100" style="border-top:3px solid var(--info)">
            <div class="card-body-custom d-flex flex-column">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon stat-icon-info"><i class="bi bi-database"></i></div>
                    <div><h6 class="fw-bold mb-0" style="font-size:.85rem">Backup DB</h6><span style="font-size:.7rem;color:var(--neutral-500)">Unduh cadangan data</span></div>
                </div>
                <p style="font-size:.75rem;color:var(--neutral-600);flex:1">Unduh seluruh struktur data dalam format <code style="background:var(--neutral-100);padding:2px 6px;border-radius:4px;color:var(--danger);font-size:.7rem">.sql</code></p>
                <form action="{{ route('superadmin.database.backup') }}" method="POST">@csrf
                    <button type="submit" class="btn-primary-custom w-100 justify-center" style="background:var(--info)"><i class="bi bi-download"></i> Download SQL</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Restore DB -->
    <div class="col-md-6 col-xl-3" data-aos="fade-up" data-aos-delay="50">
        <div class="card-custom h-100" style="border-top:3px solid var(--accent)">
            <div class="card-body-custom d-flex flex-column">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon stat-icon-warning"><i class="bi bi-cloud-upload"></i></div>
                    <div><h6 class="fw-bold mb-0" style="font-size:.85rem">Restore DB</h6><span style="font-size:.7rem;color:var(--neutral-500)">Upload file backup</span></div>
                </div>
                <p style="font-size:.75rem;color:var(--neutral-600);flex:1">Kembalikan data dari file <code style="background:var(--neutral-100);padding:2px 6px;border-radius:4px;color:var(--accent);font-size:.7rem">.sql</code></p>
                <form action="{{ route('superadmin.database.restore') }}" method="POST" enctype="multipart/form-data" onsubmit="return confirm('Yakin ingin menimpa database saat ini?')">
                    @csrf
                    <div class="mb-2"><input type="file" name="backup_file" accept=".sql" required class="form-control-custom w-100" style="font-size:.7rem"></div>
                    <button type="submit" class="btn-accent-custom w-100 justify-center"><i class="bi bi-upload"></i> Restore Data</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Clear Cache -->
    <div class="col-md-6 col-xl-3" data-aos="fade-up" data-aos-delay="100">
        <div class="card-custom h-100" style="border-top:3px solid var(--success)">
            <div class="card-body-custom d-flex flex-column">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon stat-icon-success"><i class="bi bi-arrow-clockwise"></i></div>
                    <div><h6 class="fw-bold mb-0" style="font-size:.85rem">Clear Cache</h6><span style="font-size:.7rem;color:var(--neutral-500)">Optimalkan performa</span></div>
                </div>
                <p style="font-size:.75rem;color:var(--neutral-600);flex:1">Bersihkan cache route, config, dan tampilan Blade.</p>
                <form action="{{ route('superadmin.database.clear-cache') }}" method="POST">@csrf
                    <button type="submit" class="btn-success-custom w-100 justify-center"><i class="bi bi-arrow-clockwise"></i> Bersihkan Cache</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Reset Transaksi -->
    <div class="col-md-6 col-xl-3" data-aos="fade-up" data-aos-delay="150">
        <div class="card-custom h-100" style="border-top:3px solid var(--danger)">
            <div class="card-body-custom d-flex flex-column">
                <div class="d-flex align-items-center gap-3 mb-3">
                    <div class="stat-icon stat-icon-danger"><i class="bi bi-trash3"></i></div>
                    <div><h6 class="fw-bold mb-0" style="font-size:.85rem">Reset Transaksi</h6><span style="font-size:.7rem;color:var(--neutral-500)">Pergantian Tahun</span></div>
                </div>
                <p style="font-size:.75rem;color:var(--neutral-600);flex:1">Hapus seluruh riwayat transaksi. <span class="fw-bold" style="color:var(--danger)">Tidak bisa dibatalkan!</span></p>
                <button type="button" onclick="document.getElementById('modalReset').style.display='flex'" class="btn-danger-custom w-100 justify-center"><i class="bi bi-exclamation-triangle"></i> Reset Transaksi</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Reset -->
<div id="modalReset" style="position:fixed;inset:0;z-index:1060;background:rgba(0,0,0,.4);display:none;align-items:center;justify-content:center;padding:16px">
    <div class="card-custom" style="max-width:440px;width:100%">
        <div class="card-body-custom text-center">
            <div class="mx-auto mb-3" style="width:48px;height:48px;border-radius:50%;background:#fee2e2;color:var(--danger);display:flex;align-items:center;justify-content:center;font-size:1.3rem"><i class="bi bi-exclamation-triangle"></i></div>
            <h5 class="fw-bold mb-1">Konfirmasi Reset Data</h5>
            <p style="font-size:.75rem;color:var(--neutral-500);margin-bottom:16px">Aksi ini akan menghapus semua riwayat transaksi kasir secara permanen.</p>
            <form action="{{ route('superadmin.database.reset-transactions') }}" method="POST">
                @csrf
                <div class="mb-3 text-start"><label class="form-label-custom">Ketik <span class="fw-bold" style="color:var(--danger);font-family:monospace">HAPUS TRANSAKSI</span>:</label><input type="text" name="confirm_text" class="form-control-custom w-100" placeholder="HAPUS TRANSAKSI" required autocomplete="off"></div>
                <div class="d-flex gap-2">
                    <button type="button" onclick="document.getElementById('modalReset').style.display='none'" class="btn-outline-custom flex-fill">Batal</button>
                    <button type="submit" class="btn-danger-custom flex-fill">Ya, Reset Sekarang</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
