@extends('layouts.siswa')

@section('title', 'Pesanan Saya')
@section('page_title', 'Pesanan Saya')

@section('content')
<div class="mb-5" data-aos="fade-up">
    <h4 class="fw-bold">Pesanan Saya</h4>
    <p style="font-size:.85rem;color:var(--neutral-500)">Daftar pesanan kamu yang belum selesai</p>
</div>

<div class="d-flex flex-column gap-3">
    @forelse($orders as $order)
    <div class="card-custom" data-aos="fade-up">
        <div class="card-body-custom">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-3">
                <div>
                    <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
                        <span class="fw-bold" style="color:var(--primary);font-size:.95rem">#{{ $order->order_code }}</span>
                        <span class="badge {{ in_array(strtolower($order->status), ['menunggu pembayaran']) ? 'badge-warning' : 'badge-info' }}">{{ $order->status }}</span>
                        <span style="font-size:.7rem;color:var(--neutral-400);text-transform:uppercase;font-weight:600">({{ $order->payment_method }})</span>
                    </div>
                    <div style="font-size:.8rem;color:var(--neutral-500)">
                        <div>Jumlah Produk: {{ $order->items->sum('quantity') }} Pcs</div>
                        <div>Total Tagihan: <span class="fw-bold" style="color:var(--neutral-800)">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span></div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2 flex-wrap">
                    @if($qrisEnabled && strtolower($order->payment_method) === 'qris' && strtolower($order->status) === 'menunggu pembayaran')
                        <button type="button" onclick="payWithMidtrans('{{ route('siswa.orders.payQris', $order->id) }}')" class="btn-success-custom btn-sm-custom">
                            <i class="bi bi-qr-code"></i> Bayar Sekarang
                        </button>
                        <button type="button" onclick="checkStatus('{{ route('siswa.orders.checkStatus', $order->id) }}')" class="btn-outline-custom btn-sm-custom">
                            <i class="bi bi-arrow-clockwise"></i> Periksa Status Pembayaran
                        </button>
                    @elseif(strtolower($order->payment_method) === 'qris' && strtolower($order->status) === 'menunggu pembayaran')
                        <button type="button" onclick="checkStatus('{{ route('siswa.orders.checkStatus', $order->id) }}')" class="btn-outline-custom btn-sm-custom">
                            <i class="bi bi-arrow-clockwise"></i> Periksa Status Pembayaran
                        </button>
                    @elseif(strtolower($order->payment_method) === 'cash' && strtolower($order->status) === 'menunggu pembayaran')
                        <span class="badge badge-warning" style="padding:8px 14px;font-size:.75rem"><i class="bi bi-info-circle me-1"></i> Bayar di Kasir</span>
                    @elseif(strtolower($order->status) === 'siap diambil')
                        <span class="badge badge-info" style="padding:8px 14px;font-size:.75rem"><i class="bi bi-shop me-1"></i> Tunjukkan kode ke Kasir</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="card-custom text-center" style="padding:48px">
        <i class="bi bi-box-seam" style="font-size:3rem;color:var(--neutral-300);margin-bottom:12px;display:block"></i>
        <p style="color:var(--neutral-500)">Tidak ada pesanan aktif saat ini.</p>
    </div>
    @endforelse
</div>

<script type="text/javascript" src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
<script type="text/javascript">
function payWithMidtrans(payUrl) {
    fetch(payUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' } })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.data.token) {
            window.snap.pay(data.data.token, {
                onSuccess: function() { location.reload(); },
                onPending: function() { alert("Menunggu pembayaran."); location.reload(); },
                onError: function() { alert("Pembayaran gagal!"); },
                onClose: function() { alert('Kamu menutup halaman pembayaran.'); }
            });
        } else { alert('Gagal: ' + (data.message || 'Terjadi kesalahan')); }
    })
    .catch(error => { console.error('Error:', error); alert('Terjadi kesalahan koneksi.'); });
}

function checkStatus(checkUrl) {
    fetch(checkUrl, { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' } })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.status === 'Siap Diambil') {
            alert("Pembayaran berhasil! Pesanan siap diambil.");
            location.reload();
        } else if (data.success) {
            alert("Status pesanan: " + data.status);
        } else {
            alert('Gagal: ' + (data.message || 'Terjadi kesalahan'));
        }
    })
    .catch(error => { console.error('Error:', error); alert('Terjadi kesalahan koneksi.'); });
}

// Auto-cek status pembayaran untuk pesanan QRIS yang masih menunggu
document.addEventListener('DOMContentLoaded', function () {
    var pendingOrderIds = JSON.parse('{!! json_encode($orders->where("payment_method", "qris")->where("status", "Menunggu Pembayaran")->pluck("id")) !!}');
    pendingOrderIds.forEach(function (orderId) {
        fetch('/siswa/orders/' + orderId + '/check-status', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json', 'Accept': 'application/json' } })
        .then(response => response.json())
        .then(data => { if (data.success && data.status === 'Siap Diambil') { location.reload(); } })
        .catch(() => {});
    });
});
</script>
@endsection
