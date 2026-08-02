@extends('layouts.kasir')

@section('title', 'Kelola Pesanan')
@section('page_title', 'Kelola Pesanan')

@section('content')
<div class="mb-5" data-aos="fade-up">
    <h4 class="fw-bold">Kelola Pesanan</h4>
    <p style="font-size:.85rem;color:var(--neutral-500)">Kelola dan proses pesanan yang dibuat siswa.</p>
</div>

<!-- Filter Tabs -->
<div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4" data-aos="fade-up">
    <div class="d-flex gap-2 flex-wrap">
        <a href="{{ route('kasir.orders.index') }}" class="btn {{ !request('status') ? 'btn-primary-custom' : 'btn-outline-custom' }} btn-sm-custom">Semua</a>
        <a href="{{ route('kasir.orders.index', ['status' => 'Menunggu Pembayaran']) }}" class="btn {{ request('status') === 'Menunggu Pembayaran' ? 'btn-accent-custom' : 'btn-outline-custom' }} btn-sm-custom">Menunggu Pembayaran</a>
        <a href="{{ route('kasir.orders.index', ['status' => 'Siap Diambil']) }}" class="btn {{ request('status') === 'Siap Diambil' ? 'btn-primary-custom' : 'btn-outline-custom' }} btn-sm-custom">Siap Diambil</a>
        <a href="{{ route('kasir.orders.index', ['status' => 'Selesai']) }}" class="btn {{ request('status') === 'Selesai' ? 'btn-success-custom' : 'btn-outline-custom' }} btn-sm-custom">Selesai</a>
    </div>
    <form action="{{ route('kasir.orders.index') }}" method="GET" class="d-flex" style="width:280px">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa atau no. pesanan..." class="form-control-custom me-2" style="flex:1">
        <button type="submit" class="btn-outline-custom btn-sm-custom"><i class="bi bi-search"></i></button>
    </form>
</div>

<!-- Table -->
<div class="table-custom" data-aos="fade-up">
    <div class="table-responsive">
        <table class="table-custom mb-0">
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Siswa</th>
                    <th>Kelas</th>
                    <th>Total</th>
                    <th>Metode</th>
                    <th>Status</th>
                    <th class="text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr>
                    <td class="fw-bold">#{{ $order->order_code }}</td>
                    <td class="fw-semibold">{{ $order->user->name ?? 'Siswa' }}</td>
                    <td style="font-size:.75rem;color:var(--neutral-500)">{{ $order->user->classModel->class_name ?? '' }}</td>
                    <td class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="badge {{ strtolower($order->payment_method) === 'qris' ? 'badge-info' : 'badge-success' }}">
                            <i class="bi {{ strtolower($order->payment_method) === 'qris' ? 'bi-qr-code' : 'bi-cash' }} me-1"></i>{{ $order->payment_method }}
                        </span>
                    </td>
                    <td>
                        <span class="badge {{ $order->status === 'Siap Diambil' ? 'badge-info' : ($order->status === 'Selesai' ? 'badge-success' : 'badge-warning') }}">{{ $order->status }}</span>
                    </td>
                    <td class="text-center">
                        @if(in_array($order->status, ['Siap Diambil', 'Menunggu Pembayaran']))
                            <form action="{{ route('kasir.orders.complete', $order->id) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button type="submit" class="btn-success-custom btn-sm-custom"><i class="bi bi-check-lg"></i> Selesai</button>
                            </form>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center" style="padding:48px;color:var(--neutral-400)">Belum ada pesanan masuk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
