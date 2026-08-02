@extends('layouts.kasir')

@section('title', 'Dashboard Kasir')
@section('page_title', 'Dashboard Kasir')

@section('content')
<div class="mb-5" data-aos="fade-up">
    <h4 class="fw-bold">Dashboard Kasir</h4>
    <p style="font-size:.85rem;color:var(--neutral-500)">Selamat datang kembali, Kasir!</p>
</div>

<!-- Stat Cards -->
<div class="row g-4 mb-5">
    <div class="col-sm-6 col-xl-3" data-aos="fade-up">
        <div class="stat-card">
            <div><div class="stat-label">Total Pesanan</div><div class="stat-value">{{ $totalPesanan }}</div></div>
            <div class="stat-icon stat-icon-accent"><i class="bi bi-bag-check-fill"></i></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="50">
        <div class="stat-card">
            <div><div class="stat-label">Menunggu Pembayaran</div><div class="stat-value" style="color:var(--warning)">{{ $pesananMenunggu }}</div></div>
            <div class="stat-icon stat-icon-warning"><i class="bi bi-clock-fill"></i></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card">
            <div><div class="stat-label">Siap Diambil</div><div class="stat-value" style="color:var(--info)">{{ $pesananSiap }}</div></div>
            <div class="stat-icon stat-icon-info"><i class="bi bi-box-seam"></i></div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="150">
        <div class="stat-card">
            <div><div class="stat-label">Pesanan Selesai</div><div class="stat-value" style="color:var(--success)">{{ $pesananSelesai }}</div></div>
            <div class="stat-icon stat-icon-success"><i class="bi bi-check-circle-fill"></i></div>
        </div>
    </div>
</div>

<!-- Recent Orders + Chart -->
<div class="row g-4">
    <div class="col-lg-8" data-aos="fade-up">
        <div class="card-custom h-100">
            <div class="card-body-custom">
                <h6 class="fw-bold mb-4">Pesanan Terbaru</h6>
                <div class="table-responsive">
                    <table class="table-custom mb-0">
                        <thead>
                            <tr>
                                <th>No. Pesanan</th>
                                <th>Siswa</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td class="fw-bold">#{{ $order->order_code }}</td>
                                <td>
                                    <div class="fw-semibold" style="font-size:.85rem">{{ $order->user->name ?? 'Siswa' }}</div>
                                    <div style="font-size:.7rem;color:var(--neutral-400)">{{ $order->user->classModel->class_name ?? '' }}</div>
                                </td>
                                <td class="fw-bold">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                <td>
                                    <span class="badge {{ $order->status === 'Siap Diambil' ? 'badge-info' : ($order->status === 'Selesai' ? 'badge-success' : 'badge-warning') }}">{{ $order->status }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <a href="{{ route('kasir.orders.index') }}" class="btn-outline-custom w-100 justify-center mt-4">Lihat Semua Pesanan <i class="bi bi-arrow-right"></i></a>
            </div>
        </div>
    </div>
    <div class="col-lg-4" data-aos="fade-up" data-aos-delay="100">
        <div class="card-custom h-100">
            <div class="card-body-custom text-center">
                <h6 class="fw-bold mb-4">Ringkasan Pesanan</h6>
                <div style="max-width:220px;margin:0 auto;height:220px">
                    <canvas id="orderChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('orderChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Menunggu Pembayaran', 'Siap Diambil', 'Selesai'],
            datasets: [{
                data: [{{ $pesananMenunggu }}, {{ $pesananSiap }}, {{ $pesananSelesai }}],
                backgroundColor: ['#F59E0B', '#3B82F6', '#10B981']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
</script>
@endsection
