@extends('layouts.siswa')

@section('title', 'Pesanan Berhasil')
@section('page_title', 'Pesanan Berhasil')

@section('content')
<div class="order-success-wrapper" data-aos="fade-up">
    <div class="success-icon">
        <i class="bi bi-check-lg"></i>
    </div>
    <h3>Pesanan Berhasil Dibuat!</h3>
    @if(isset($order))
    <div class="order-code">{{ $order->order_code }}</div>
    @endif
    <p>Terima kasih! Pesanan seragam kamu sudah tercatat. Silakan simpan kode pesanan untuk pengambilan di koperasi.</p>

    @if(isset($order))
    <div class="order-info">
        <div class="info-row">
            <span class="info-label">Kode Pesanan</span>
            <span class="info-value">{{ $order->order_code }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Status</span>
            <span class="badge-custom badge-primary" style="font-size:.72rem">{{ ucfirst($order->status) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Metode Bayar</span>
            <span class="info-value">{{ ucfirst($order->payment_method) }}</span>
        </div>
        <div class="info-row">
            <span class="info-label">Total</span>
            <span class="info-value" style="color:var(--primary)">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
        </div>
    </div>
    @endif

    <div class="d-flex flex-wrap gap-3 justify-content-center">
        <a href="{{ route('siswa.orders.index') }}" class="btn-primary-custom" style="padding:12px 28px;font-size:.88rem;border-radius:var(--radius)">
            <i class="bi bi-box"></i> Lihat Pesanan Saya
        </a>
        <a href="{{ route('siswa.products.index') }}" class="btn-outline-custom" style="padding:12px 28px;font-size:.88rem;border-radius:var(--radius)">
            <i class="bi bi-bag"></i> Belanja Lagi
        </a>
    </div>
</div>
@endsection