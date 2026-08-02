@extends('layouts.kasir')

@section('title', 'Riwayat Transaksi')
@section('page_title', 'Riwayat Transaksi')

@section('content')
<div class="mb-5" data-aos="fade-up">
    <h4 class="fw-bold">Riwayat Transaksi</h4>
    <p style="font-size:.85rem;color:var(--neutral-500)">Daftar transaksi yang sudah selesai</p>
</div>

<div class="row g-4">
    <!-- Tabel Utama -->
    <div class="{{ isset($selectedOrder) ? 'col-lg-8' : 'col-12' }}" data-aos="fade-up">
        <div class="card-custom">
            <div class="table-responsive">
                <table class="table-custom mb-0">
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Siswa</th>
                            <th>Tanggal</th>
                            <th>Total</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr style="{{ isset($selectedOrder) && $selectedOrder->id == $order->id ? 'background:var(--primary-lighter)' : '' }}">
                            <td class="fw-bold">{{ $order->order_code }}</td>
                            <td class="fw-semibold">{{ $order->user->name ?? 'Siswa' }}</td>
                            <td style="font-size:.75rem;color:var(--neutral-500)">{{ $order->updated_at->format('d M Y H:i') }}</td>
                            <td class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="{{ route('kasir.history.index', ['detail_id' => $order->id]) }}" class="btn-outline-custom btn-sm-custom">Detail</a>
                                    <a href="{{ route('kasir.orders.receipt', $order->id) }}" target="_blank" class="btn-primary-custom btn-sm-custom"><i class="bi bi-printer"></i> Struk</a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Panel Struk -->
    @if(isset($selectedOrder))
    <div class="col-lg-4" data-aos="fade-up">
        <div class="card-custom" style="position:sticky;top:80px">
            <div class="card-body-custom">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-3" style="border-bottom:1px solid var(--neutral-100)">
                    <span class="badge badge-success">Selesai</span>
                    <span class="fw-bold">{{ $selectedOrder->order_code }}</span>
                </div>
                <div class="mb-3" style="font-size:.8rem;color:var(--neutral-600)">
                    <div class="mb-1"><span style="color:var(--neutral-400)">Siswa:</span> <strong style="color:var(--neutral-800)">{{ $selectedOrder->user->name ?? '-' }}</strong></div>
                    <div class="mb-1"><span style="color:var(--neutral-400)">Metode:</span> <strong style="color:var(--neutral-800);text-transform:uppercase">{{ $selectedOrder->payment_method }}</strong></div>
                    <div><span style="color:var(--neutral-400)">Tanggal:</span> <strong style="color:var(--neutral-800)">{{ $selectedOrder->updated_at->format('d M Y H:i') }}</strong></div>
                </div>
                <div class="pt-3 mb-3" style="border-top:1px solid var(--neutral-100)">
                    <h6 class="fw-bold mb-2" style="font-size:.75rem">Daftar Produk</h6>
                    @foreach($selectedOrder->items as $item)
                        <div class="d-flex justify-content-between mb-1" style="font-size:.8rem">
                            <span>{{ $item->product->name ?? 'Produk' }} ({{ $item->size }}) x{{ $item->quantity }}</span>
                            <span class="fw-bold">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</span>
                        </div>
                    @endforeach
                </div>
                <div class="pt-3 mb-4" style="border-top:1px solid var(--neutral-100)">
                    <div class="d-flex justify-content-between fw-bold" style="font-size:.95rem">
                        <span>Total</span>
                        <span style="color:var(--primary)">Rp {{ number_format($selectedOrder->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
                <a href="{{ route('kasir.orders.receipt', $selectedOrder->id) }}" target="_blank" class="btn-primary-custom w-100 justify-center">
                    <i class="bi bi-printer"></i> Cetak Struk
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
