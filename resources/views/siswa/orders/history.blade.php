@extends('layouts.siswa')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat Transaksi')

@section('content')
<div class="mb-5" data-aos="fade-up">
    <h4 class="fw-bold">Riwayat Transaksi</h4>
    <p style="font-size:.85rem;color:var(--neutral-500)">Daftar transaksi baju seragam yang telah selesai kamu ambil</p>
</div>

<div class="d-flex flex-column gap-3">
    @forelse($orders as $order)
    <div class="card-custom" data-aos="fade-up">
        <div class="card-body-custom">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                        <span class="fw-bold" style="color:var(--primary);font-size:.95rem">#{{ $order->order_code }}</span>
                        <span class="badge badge-success">{{ $order->status }}</span>
                        <span style="font-size:.7rem;color:var(--neutral-400);text-transform:uppercase;font-weight:600">({{ $order->payment_method }})</span>
                    </div>
                    <div style="font-size:.8rem;color:var(--neutral-500)">
                        <div>Tanggal Selesai: {{ $order->updated_at ? $order->updated_at->translatedFormat('d F Y, H:i') : '-' }}</div>
                        <div>Total Transaksi: <span class="fw-bold" style="color:var(--neutral-800)">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></div>
                    </div>
                </div>
                <span class="badge badge-success" style="padding:8px 14px;font-size:.75rem"><i class="bi bi-check-circle me-1"></i> Baju Sudah Diambil</span>
            </div>
        </div>
    </div>
    @empty
    <div class="card-custom text-center" style="padding:48px">
        <i class="bi bi-receipt" style="font-size:3rem;color:var(--neutral-300);margin-bottom:12px;display:block"></i>
        <p style="color:var(--neutral-500)">Belum ada riwayat transaksi yang selesai.</p>
    </div>
    @endforelse
</div>
@endsection
