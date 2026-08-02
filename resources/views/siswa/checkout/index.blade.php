@extends('layouts.siswa')

@section('title', 'Checkout Pesanan')
@section('page_title', 'Checkout')

@section('content')
<div class="mb-4" data-aos="fade-up">
    <h4 class="fw-bold" style="font-size:1.2rem">Checkout Pesanan</h4>
    <p style="font-size:.82rem;color:var(--neutral-500)">Periksa pesanan kamu dan pilih metode pembayaran</p>
</div>

<form action="{{ route('siswa.checkout.store') }}" method="POST" id="paymentForm">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <div class="form-card mb-4" data-aos="fade-up">
                <div class="form-card-title">Item Yang Dibeli</div>
                @foreach($items as $item)
                    <div class="d-flex justify-content-between align-items-center py-3 {{ !$loop->last ? 'border-bottom' : '' }}" style="border-color:var(--neutral-100) !important">
                        <div>
                            <h6 class="fw-bold mb-1" style="font-size:.88rem">{{ $item->product->name }}</h6>
                            <div style="font-size:.75rem;color:var(--neutral-500)">Ukuran: <span class="fw-bold" style="color:var(--primary)">{{ $item->size }}</span> | <span class="fw-bold">{{ $item->quantity }} Pcs</span> x Rp {{ number_format($item->price, 0, ',', '.') }}</div>
                        </div>
                        <span class="fw-bold" style="color:var(--primary);font-size:.92rem">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</span>
                    </div>
                @endforeach
            </div>

            <div class="form-card" data-aos="fade-up">
                <div class="form-card-title">Pilih Metode Pembayaran</div>
                <div class="d-flex flex-column gap-3">
                    <label class="d-flex align-items-center justify-content-between p-3" style="border:1.5px solid var(--neutral-200);border-radius:var(--radius);cursor:pointer;transition:var(--transition);background:var(--white)">
                        <div class="d-flex align-items-center gap-3">
                            <input type="radio" name="payment_method" value="cash" checked class="form-check-input" style="accent-color:var(--primary)">
                            <div>
                                <div class="fw-bold" style="font-size:.82rem">Bayar Cash / Tunai</div>
                                <div style="font-size:.73rem;color:var(--neutral-500)">Bayar langsung di kasir Koperasi saat mengambil baju seragam</div>
                            </div>
                        </div>
                        <i class="bi bi-cash-stack" style="font-size:1.3rem;color:var(--success)"></i>
                    </label>
                    @if($qrisEnabled)
                    <label class="d-flex align-items-center justify-content-between p-3" style="border:1.5px solid var(--neutral-200);border-radius:var(--radius);cursor:pointer;transition:var(--transition);background:var(--white)">
                        <div class="d-flex align-items-center gap-3">
                            <input type="radio" name="payment_method" value="qris" class="form-check-input" style="accent-color:var(--primary)">
                            <div>
                                <div class="fw-bold" style="font-size:.82rem">QRIS Online (Midtrans)</div>
                                <div style="font-size:.73rem;color:var(--neutral-500)">Scan QRIS pake GoPay, OVO, Dana, ShopeePay, atau Mobile Banking</div>
                            </div>
                        </div>
                        <i class="bi bi-qr-code" style="font-size:1.3rem;color:var(--primary)"></i>
                    </label>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card-custom" style="position:sticky;top:80px" data-aos="fade-up">
                <div class="card-body-custom">
                    <h6 class="fw-bold mb-3 pb-3" style="border-bottom:1px solid var(--neutral-100);font-size:.9rem">Ringkasan Pembayaran</h6>
                    <div class="d-flex justify-content-between mb-2" style="font-size:.82rem;color:var(--neutral-600)">
                        <span>Subtotal Produk</span>
                        <span>Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3" style="font-size:.73rem;color:var(--neutral-400)">
                        <span>Biaya Ambil di Koperasi</span>
                        <span>Gratis</span>
                    </div>
                    <div class="d-flex justify-content-between pt-3 mb-4" style="border-top:1px solid var(--neutral-100);font-size:.95rem">
                        <span class="fw-bold">Total Pembayaran</span>
                        <span class="fw-bold" style="color:var(--primary);font-size:1.05rem">Rp {{ number_format($totalAmount, 0, ',', '.') }}</span>
                    </div>
                    <button type="submit" class="btn-primary-custom w-100 justify-center" id="submitBtn" style="padding:14px;font-size:.88rem">
                        <i class="bi bi-bag-check"></i> Buat Pesanan Sekarang
                    </button>
                    <div class="spinner-inline justify-content-center mt-2" id="loadingIndicator" style="display:none">
                        <div class="spinner-custom"></div>
                        <span>Memproses pesanan...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

@if($qrisEnabled)
<script src="{{ config('midtrans.snap_url') }}" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endif
<script>
    const form = document.getElementById('paymentForm');
    const submitBtn = document.getElementById('submitBtn');
    const loadingIndicator = document.getElementById('loadingIndicator');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const paymentMethod = document.querySelector('input[name="payment_method"]:checked').value;
        if (paymentMethod === 'cash') { 
            form.submit(); 
        } else if (paymentMethod === 'qris') { 
            await handleQrisPayment(); 
        }
    });

    async function handleQrisPayment() {
        const originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.style.display = 'none';
        loadingIndicator.style.display = 'flex';

        try {
            const csrfToken = document.querySelector('input[name="_token"]')?.value;
            const formData = new FormData(form);
            const createResponse = await fetch(form.action, { 
                method: 'POST', 
                body: formData, 
                headers: { 
                    'X-Requested-With': 'XMLHttpRequest', 
                    'Accept': 'application/json' 
                } 
            });

            if (!createResponse.ok) throw new Error('Order creation failed');
            const orderData = await createResponse.json();
            if (!orderData.success || !orderData.order_id) throw new Error(orderData.message || 'Gagal membuat pesanan');

            const payResponse = await fetch(`/siswa/orders/${orderData.order_id}/pay-qris`, {
                method: 'POST', 
                headers: { 
                    'Accept': 'application/json', 
                    'X-CSRF-TOKEN': csrfToken, 
                    'X-Requested-With': 'XMLHttpRequest' 
                }
            });

            const payData = await payResponse.json();
            if (!payData.success || !payData.data || !payData.data.token) throw new Error(payData.message || 'Token pembayaran tidak ditemukan');

            loadingIndicator.style.display = 'none';
            submitBtn.style.display = 'flex';
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;

            snap.pay(payData.data.token, {
                onSuccess: function() { window.location.href = '/siswa/orders/success/' + orderData.order_id; },
                onPending: function() { window.location.href = '/siswa/orders'; },
                onError: function() { alert('Pembayaran gagal! Silakan coba lagi.'); },
                onClose: function() {}
            });
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan: ' + error.message);
            loadingIndicator.style.display = 'none';
            submitBtn.style.display = 'flex';
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }
</script>
@endsection