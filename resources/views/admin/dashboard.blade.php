@extends('layouts.admin')

@section('title', 'Dashboard Admin Toko')
@section('page_title', 'Dashboard Admin Toko / Koperasi')

@section('content')
<div class="row g-4 mb-5">
    <div class="col-sm-6 col-xl-3" data-aos="fade-up">
        <div class="stat-card">
            <div>
                <div class="stat-label">Total Produk</div>
                <div class="stat-value">{{ $totalProducts }}</div>
            </div>
            <div class="stat-icon stat-icon-success"><i class="bi bi-box-seam-fill"></i></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="50">
        <div class="stat-card">
            <div>
                <div class="stat-label">Stok Menipis (&lt;=5)</div>
                <div class="stat-value">{{ $lowStockProducts->count() }}</div>
            </div>
            <div class="stat-icon stat-icon-warning"><i class="bi bi-exclamation-triangle-fill"></i></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card">
            <div>
                <div class="stat-label">Total Kasir</div>
                <div class="stat-value">{{ $totalKasir }}</div>
            </div>
            <div class="stat-icon stat-icon-info"><i class="bi bi-laptop"></i></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="150">
        <div class="stat-card">
            <div>
                <div class="stat-label">Total Siswa</div>
                <div class="stat-value">{{ $totalSiswa }}</div>
            </div>
            <div class="stat-icon stat-icon-primary"><i class="bi bi-mortarboard-fill"></i></div>
        </div>
    </div>
</div>

@if($lowStockProducts->count() > 0)
<div class="card-custom" data-aos="fade-up">
    <div class="card-body-custom" style="border-left:4px solid var(--warning)">
        <div class="d-flex align-items-start gap-3">
            <div class="stat-icon stat-icon-warning flex-shrink-0"><i class="bi bi-bell-fill"></i></div>
            <div>
                <h6 class="fw-bold mb-2" style="font-size:.85rem">Peringatan Stok Barang Menipis</h6>
                <ul class="mb-0" style="font-size:.8rem;color:var(--neutral-600);padding-left:18px">
                    @foreach($lowStockProducts as $item)
                        <li class="mb-1"><strong>{{ $item->name }}</strong> — Sisa Stok: <span class="fw-bold" style="color:var(--danger)">{{ $item->total_stock }} {{ $item->unit }}</span></li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
