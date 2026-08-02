@extends('layouts.kasir')

@section('title', 'Laporan Penjualan')
@section('page_title', 'Laporan Penjualan')

@section('content')
<div class="mb-5" data-aos="fade-up">
    <h4 class="fw-bold">Laporan Penjualan</h4>
    <p style="font-size:.85rem;color:var(--neutral-500)">Lihat ringkasan penjualan dalam periode tertentu.</p>
</div>

<!-- Filter & Download -->
<form action="{{ route('kasir.reports.index') }}" method="GET" class="card-custom mb-5" data-aos="fade-up">
    <div class="card-body-custom">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
            <div class="d-flex flex-wrap align-items-end gap-3">
                <div>
                    <label class="form-label-custom">Mulai Tanggal</label>
                    <input type="date" name="start_date" value="{{ $startDate }}" class="form-control-custom">
                </div>
                <div>
                    <label class="form-label-custom">Sampai Tanggal</label>
                    <input type="date" name="end_date" value="{{ $endDate }}" class="form-control-custom">
                </div>
                <button type="submit" class="btn-primary-custom">Filter</button>
            </div>
            <a href="{{ route('kasir.reports.exportExcel', ['start_date' => $startDate, 'end_date' => $endDate]) }}" class="btn-accent-custom">
                <i class="bi bi-file-earmark-spreadsheet"></i> Download Excel
            </a>
        </div>
    </div>
</form>

<!-- Stat Cards -->
<div class="row g-4 mb-5">
    <div class="col-sm-6 col-xl-3" data-aos="fade-up">
        <div class="stat-card"><div><div class="stat-label">Total Transaksi</div><div class="stat-value">{{ $totalTransaksi }}</div></div><div class="stat-icon stat-icon-primary"><i class="bi bi-receipt"></i></div></div>
    </div>
    <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="50">
        <div class="stat-card"><div><div class="stat-label">Total Pendapatan</div><div class="stat-value" style="color:var(--success);font-size:1.2rem">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div></div><div class="stat-icon stat-icon-success"><i class="bi bi-cash-stack"></i></div></div>
    </div>
    <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="100">
        <div class="stat-card"><div><div class="stat-label">Rata-rata Transaksi</div><div class="stat-value" style="font-size:1.2rem">Rp {{ number_format($rataRata, 0, ',', '.') }}</div></div><div class="stat-icon stat-icon-info"><i class="bi bi-graph-up"></i></div></div>
    </div>
    <div class="col-sm-6 col-xl-3" data-aos="fade-up" data-aos-delay="150">
        <div class="stat-card"><div><div class="stat-label">Produk Terjual</div><div class="stat-value" style="color:var(--info)">{{ $totalProdukTerjual }} Pcs</div></div><div class="stat-icon stat-icon-warning"><i class="bi bi-box"></i></div></div>
    </div>
</div>

<!-- Top Products -->
<div class="card-custom" data-aos="fade-up">
    <div class="card-body-custom">
        <h6 class="fw-bold mb-4">Produk Terlaris Periode Ini</h6>
        <div class="table-responsive">
            <table class="table-custom mb-0">
                <thead>
                    <tr>
                        <th>Produk</th>
                        <th>Jumlah Terjual</th>
                        <th>Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($topProducts as $tp)
                    <tr>
                        <td class="fw-bold">{{ $tp->product->name ?? 'Produk' }}</td>
                        <td class="fw-semibold" style="color:var(--info)">{{ $tp->total_qty }} Pcs</td>
                        <td class="fw-bold" style="color:var(--success)">Rp {{ number_format($tp->total_revenue, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="text-center" style="padding:32px;color:var(--neutral-400)">Belum ada transaksi di periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
