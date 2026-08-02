@extends('layouts.siswa')

@section('title', 'Beranda Siswa')
@section('page_title', 'Beranda')

@section('content')
<!-- Hero -->
<div class="hero-section mb-5 fade-in" data-aos="fade-up">
    <div class="position-relative" style="z-index:2">
        <span class="hero-badge"><i class="bi bi-patch-check-fill" style="color:var(--accent)"></i> Koperasi Sekolah</span>
        <h1>Selamat Datang, {{ auth()->user()->name }}!</h1>
        <p>Pesan seragam sekolah kamu dengan mudah, bayar secara Cash di kasir atau langsung via QRIS secara cepat dan praktis.</p>
    </div>
</div>

<!-- Stats -->
<div class="mb-5">
    <h5 class="fw-bold mb-3" data-aos="fade-up">Ringkasan Transaksi Saya</h5>
    <div class="row g-4">
        <div class="col-sm-6 col-xl-3" data-aos="fade-up">
            <div class="stat-card"><div><div class="stat-label">Total Pesanan</div><div class="stat-value">{{ $totalPesanan }}</div></div><div class="stat-icon stat-icon-primary"><i class="bi bi-clipboard-check-fill"></i></div></div>
        </div>
        <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="50">
            <div class="stat-card"><div><div class="stat-label">Menunggu Pembayaran</div><div class="stat-value" style="color:var(--warning)">{{ $pesananMenunggu }}</div></div><div class="stat-icon stat-icon-warning"><i class="bi bi-clock-fill"></i></div></div>
        </div>
        <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="100">
            <div class="stat-card"><div><div class="stat-label">Siap Diambil</div><div class="stat-value" style="color:var(--info)">{{ $pesananSiap }}</div></div><div class="stat-icon stat-icon-info"><i class="bi bi-shop"></i></div></div>
        </div>
        <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="150">
            <div class="stat-card"><div><div class="stat-label">Pesanan Selesai</div><div class="stat-value" style="color:var(--success)">{{ $pesananSelesai }}</div></div><div class="stat-icon stat-icon-success"><i class="bi bi-check-circle-fill"></i></div></div>
        </div>
    </div>
</div>

<!-- Products -->
<div data-aos="fade-up">
    <div class="section-header">
        <h5 class="fw-bold">Rekomendasi Seragam</h5>
        <a href="{{ route('siswa.products.index') }}" style="font-size:.8rem;font-weight:600;color:var(--primary)">Lihat Semua Katalog <i class="bi bi-arrow-right"></i></a>
    </div>
    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 75 }}">
            <div class="product-card">
                <div class="product-image">
                    @if($product->image)
                        <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
                    @else
                        <div class="text-center"><i class="bi bi-bag" style="font-size:2.5rem;color:var(--primary);opacity:.3"></i></div>
                    @endif
                </div>
                <div class="product-body">
                    <span class="product-category">{{ $product->category->name ?? 'Seragam' }}</span>
                    <h5 class="product-name">{{ $product->name }}</h5>
                    <p class="product-price">Rp {{ number_format($product->sell_price ?? $product->price, 0, ',', '.') }}</p>
                    <a href="{{ route('siswa.products.index') }}" class="btn-primary-custom w-100 justify-center btn-sm-custom">Lihat Katalog</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
