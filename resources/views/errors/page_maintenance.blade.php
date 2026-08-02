@extends($layout)

@section('title', 'Pemeliharaan - Sedang Dalam Perbaikan')
@section('page_title', 'Pemeliharaan Sistem')

@section('content')
<div class="flex flex-col items-center justify-center" style="min-height:calc(100vh - var(--topbar-height) - 80px)">
    <div class="text-center fade-in" style="max-width:460px;margin:0 auto;padding:24px">
        <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
            <i class="bi bi-bag-fill" style="font-size:1.2rem;color:var(--primary)"></i>
            <span style="font-weight:800;font-size:.85rem;color:var(--neutral-900);letter-spacing:-.02em">SchoolWear</span>
        </div>
        <div class="d-flex align-items-center justify-content-center mx-auto mb-3" style="width:80px;height:80px;border-radius:50%;background:var(--primary-lighter);color:var(--primary);font-size:1.75rem;animation:float 3s ease-in-out infinite">
            <i class="bi bi-tools"></i>
        </div>
        <div style="font-size:4rem;font-weight:900;line-height:1;color:var(--primary);margin-bottom:4px;text-shadow:0 4px 12px rgba(0,0,0,.08)">503</div>
        <h2 style="font-size:1.25rem;margin-bottom:8px">Halaman Dalam Pemeliharaan</h2>
        <p style="color:var(--neutral-500);font-size:.85rem;margin-bottom:20px;line-height:1.7">
            Akses untuk fitur <strong class="text-capitalize" style="color:var(--neutral-700)">{{ $feature }}</strong> saat ini sedang dinonaktifkan sementara oleh Super Admin. Silakan kembali lagi beberapa saat.
        </p>
        <div style="display:flex;flex-direction:column;gap:10px;align-items:center">
            <span class="badge-custom badge-info" style="padding:10px 24px;font-size:.8rem">
                <i class="bi bi-hourglass-split me-1"></i> Mohon Tunggu Sebentar
            </span>
            <a href="javascript:history.back()" class="btn-outline-custom" style="width:100%;justify-content:center;padding:12px 20px;border-radius:var(--radius);font-size:.85rem;font-weight:600">
                <i class="bi bi-arrow-left"></i> Kembali ke Halaman Sebelumnya
            </a>
        </div>
    </div>
</div>
@endsection