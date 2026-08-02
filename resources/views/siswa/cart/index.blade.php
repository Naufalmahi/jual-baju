@extends('layouts.siswa')

@section('title', 'Keranjang Belanja')
@section('page_title', 'Keranjang')

@section('content')
<div class="mb-4" data-aos="fade-up">
    <h4 class="fw-bold" style="font-size:1.2rem">Keranjang Belanja</h4>
    <p style="font-size:.82rem;color:var(--neutral-500)">Kelola seragam yang ingin kamu beli sebelum checkout</p>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="d-flex flex-column gap-3">
            @forelse($carts as $cart)
            <div class="card-custom" data-aos="fade-up">
                <div class="card-body-custom">
                    <div class="d-flex align-items-center justify-content-between gap-3">
                        <div class="d-flex align-items-center gap-3">
                            <div style="width:72px;height:72px;background:var(--primary-lighter);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;flex-shrink:0;overflow:hidden">
                                @if($cart->product && $cart->product->image)
                                    <img src="{{ asset('storage/'.$cart->product->image) }}" alt="{{ $cart->product->name }}" style="max-height:100%;object-fit:contain;padding:4px">
                                @else
                                    <i class="bi bi-bag" style="font-size:1.5rem;color:var(--primary);opacity:.3"></i>
                                @endif
                            </div>
                            <div>
                                <h6 class="fw-bold mb-1" style="font-size:.88rem">{{ $cart->product->name ?? 'Produk' }}</h6>
                                <div style="font-size:.75rem;color:var(--neutral-500)">Ukuran: <span class="fw-bold" style="color:var(--primary)">{{ $cart->display_size }}</span></div>
                                <div class="fw-bold mt-1" style="font-size:.88rem;color:var(--primary)">Rp {{ number_format($cart->item_price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <div class="d-flex align-items-center" style="border:1px solid var(--neutral-200);border-radius:var(--radius-sm);overflow:hidden">
                                <form action="{{ route('siswa.cart.update', $cart->id) }}" method="POST">@csrf @method('PUT')<input type="hidden" name="quantity" value="{{ max(1, $cart->display_qty - 1) }}"><button type="submit" class="btn btn-sm" style="border:none;border-right:1px solid var(--neutral-200);border-radius:0;background:var(--neutral-50);padding:6px 12px;font-weight:600;color:var(--neutral-600)" {{ $cart->display_qty <= 1 ? 'disabled' : '' }}>&minus;</button></form>
                                <span class="fw-bold text-center" style="width:40px;font-size:.85rem">{{ $cart->display_qty }}</span>
                                <form action="{{ route('siswa.cart.update', $cart->id) }}" method="POST">@csrf @method('PUT')<input type="hidden" name="quantity" value="{{ $cart->display_qty + 1 }}"><button type="submit" class="btn btn-sm" style="border:none;border-left:1px solid var(--neutral-200);border-radius:0;background:var(--neutral-50);padding:6px 12px;font-weight:600;color:var(--neutral-600)">&plus;</button></form>
                            </div>
                            <form action="{{ route('siswa.cart.destroy', $cart->id) }}" method="POST">@csrf @method('DELETE')<button type="submit" class="btn btn-sm" style="border:1px solid #fecaca;border-radius:var(--radius-sm);background:#fef2f2;color:var(--danger);padding:6px 10px;transition:var(--transition)"><i class="bi bi-trash3"></i></button></form>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="card-custom">
                <div class="card-body-custom">
                    @include('components.empty-state', [
                        'icon' => 'bi-cart',
                        'title' => 'Keranjang Kosong',
                        'message' => 'Kamu belum menambahkan seragam ke keranjang. Yuk, lihat katalog produk!',
                        'bg' => 'var(--primary-lighter)',
                        'color' => 'var(--primary)',
                        'actionUrl' => route('siswa.products.index'),
                        'actionLabel' => 'Lihat Katalog',
                        'actionIcon' => 'bi-bag',
                    ])
                </div>
            </div>
            @endforelse
        </div>
    </div>

    @if($carts->isNotEmpty())
    <div class="col-lg-4">
        <div class="card-custom" style="position:sticky;top:80px" data-aos="fade-up">
            <div class="card-body-custom">
                <h6 class="fw-bold mb-3 pb-3" style="border-bottom:1px solid var(--neutral-100);font-size:.9rem">Ringkasan Belanja</h6>
                <div class="d-flex justify-content-between mb-2" style="font-size:.82rem">
                    <span style="color:var(--neutral-600)">Total Items</span>
                    <span class="fw-bold">{{ $totalItems }} Pcs</span>
                </div>
                <div class="d-flex justify-content-between pt-3 mb-4" style="border-top:1px solid var(--neutral-100);font-size:.95rem">
                    <span class="fw-bold">Total Pembayaran</span>
                    <span class="fw-bold" style="color:var(--primary);font-size:1.05rem">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</span>
                </div>
                <a href="{{ route('siswa.checkout') }}" class="btn-primary-custom w-100 justify-center" style="padding:12px;font-size:.88rem">
                    Lanjut ke Checkout <i class="bi bi-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection