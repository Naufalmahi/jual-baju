@extends('layouts.superadmin')

@section('title', 'Pengaturan Aplikasi')
@section('page_title', 'Pengaturan Sistem & Koperasi')

@section('content')
<form action="{{ route('superadmin.settings.update') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Profil Sekolah -->
            <div class="card-custom mb-4" data-aos="fade-up" style="border-top:3px solid var(--primary)">
                <div class="card-body-custom">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-3" style="border-bottom:1px solid var(--neutral-100)">
                        <div class="stat-icon stat-icon-primary" style="width:40px;height:40px"><i class="bi bi-building"></i></div>
                        <div><h6 class="fw-bold mb-0" style="font-size:.85rem">Profil Sekolah & Koperasi</h6><span style="font-size:.7rem;color:var(--neutral-400)">Identitas lembaga untuk laporan & struk</span></div>
                    </div>
                    <div class="mb-3"><label class="form-label-custom">Nama Koperasi / Toko Sekolah</label><input type="text" name="school_name" value="{{ $settings['school_name'] ?? '' }}" class="form-control-custom w-100"></div>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label-custom">No. Telepon / WA</label><input type="text" name="school_phone" value="{{ $settings['school_phone'] ?? '' }}" class="form-control-custom w-100"></div>
                        <div class="col-md-6"><label class="form-label-custom">Email Resmi</label><input type="email" name="school_email" value="{{ $settings['school_email'] ?? '' }}" class="form-control-custom w-100"></div>
                    </div>
                    <div class="mt-3"><label class="form-label-custom">Alamat Lengkap</label><textarea name="school_address" rows="2" class="form-control-custom w-100">{{ $settings['school_address'] ?? '' }}</textarea></div>
                </div>
            </div>

            <!-- Kebijakan Pembayaran -->
            <div class="card-custom mb-4" data-aos="fade-up" style="border-top:3px solid var(--success)">
                <div class="card-body-custom">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-3" style="border-bottom:1px solid var(--neutral-100)">
                        <div class="stat-icon stat-icon-success" style="width:40px;height:40px"><i class="bi bi-cash-register"></i></div>
                        <div><h6 class="fw-bold mb-0" style="font-size:.85rem">Kebijakan Pembayaran & Kasir</h6><span style="font-size:.7rem;color:var(--neutral-400)">Aturan batasan kredit dan fitur kasir</span></div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6"><label class="form-label-custom">Batas Maksimal Kasbon (Rp)</label><input type="number" name="max_debt_limit" value="{{ $settings['max_debt_limit'] ?? '50000' }}" class="form-control-custom w-100"><p style="font-size:.65rem;color:var(--neutral-400);margin-top:4px">Set 0 jika tidak mengizinkan kasbon.</p></div>
                        <div class="col-md-6"><label class="form-label-custom">Batas Warning Stok</label><input type="number" name="stock_warning_limit" value="{{ $settings['stock_warning_limit'] ?? '5' }}" class="form-control-custom w-100"><p style="font-size:.65rem;color:var(--neutral-400);margin-top:4px">Peringatan jika stok di bawah angka ini.</p></div>
                    </div>
                    <div class="row g-3 mt-2">
                        <div class="col-sm-6"><label class="d-flex align-items-center gap-2 p-2" style="border:1px solid var(--neutral-200);border-radius:var(--radius-sm);cursor:pointer"><input type="checkbox" name="enable_qris" value="1" {{ ($settings['enable_qris'] ?? '1') == '1' ? 'checked' : '' }}><div><span class="d-block fw-bold" style="font-size:.8rem">Terima QRIS / E-Wallet</span><span class="d-block" style="font-size:.65rem;color:var(--neutral-400)">Tampilkan opsi QRIS saat checkout</span></div></label></div>
                        <div class="col-sm-6"><label class="d-flex align-items-center gap-2 p-2" style="border:1px solid var(--neutral-200);border-radius:var(--radius-sm);cursor:pointer"><input type="checkbox" name="allow_debt" value="1" {{ ($settings['allow_debt'] ?? '0') == '1' ? 'checked' : '' }}><div><span class="d-block fw-bold" style="font-size:.8rem">Izinkan Kasbon Siswa</span><span class="d-block" style="font-size:.65rem;color:var(--neutral-400)">Kasir bisa mencatat transaksi hutang</span></div></label></div>
                    </div>
                </div>
            </div>

            <!-- Struk -->
            <div class="card-custom" data-aos="fade-up" style="border-top:3px solid var(--accent)">
                <div class="card-body-custom">
                    <div class="d-flex align-items-center gap-3 mb-4 pb-3" style="border-bottom:1px solid var(--neutral-100)">
                        <div class="stat-icon stat-icon-warning" style="width:40px;height:40px"><i class="bi bi-receipt"></i></div>
                        <div><h6 class="fw-bold mb-0" style="font-size:.85rem">Pengaturan Struk Cetak</h6><span style="font-size:.7rem;color:var(--neutral-400)">Pesan penutup struk thermal</span></div>
                    </div>
                    <div class="mb-3"><label class="form-label-custom">Footer Struk Baris 1</label><input type="text" name="receipt_footer_1" value="{{ $settings['receipt_footer_1'] ?? 'Terima Kasih Atas Kunjungan Anda!' }}" class="form-control-custom w-100"></div>
                    <div class="mb-3"><label class="form-label-custom">Footer Struk Baris 2</label><input type="text" name="receipt_footer_2" value="{{ $settings['receipt_footer_2'] ?? 'Barang yang sudah dibeli tidak dapat dikembalikan.' }}" class="form-control-custom w-100"></div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Logo -->
            <div class="card-custom mb-4" data-aos="fade-up" style="border-top:3px solid var(--info)">
                <div class="card-body-custom text-center">
                    <h6 class="fw-bold mb-3" style="font-size:.85rem">Logo Sekolah / Koperasi</h6>
                    @if(!empty($settings['app_logo']))
                        <img src="{{ asset('storage/' . $settings['app_logo']) }}" alt="Logo" class="mb-3" style="width:100px;height:100px;object-fit:contain;border:1px solid var(--neutral-200);border-radius:var(--radius);padding:8px;background:var(--neutral-50)">
                    @else
                        <div class="mx-auto mb-3" style="width:100px;height:100px;border:2px dashed var(--neutral-300);border-radius:var(--radius);display:flex;align-items:center;justify-content:center;color:var(--neutral-400)"><i class="bi bi-building" style="font-size:2rem"></i></div>
                    @endif
                    <input type="file" name="app_logo" accept="image/*" class="form-control-custom w-100" style="font-size:.75rem">
                </div>
            </div>

            <!-- Pajak & Poin -->
            <div class="card-custom" data-aos="fade-up" style="border-top:3px solid #8b5cf6">
                <div class="card-body-custom">
                    <h6 class="fw-bold mb-3" style="font-size:.85rem">Pajak & Reward</h6>
                    <div class="mb-3"><label class="form-label-custom">PPN (%)</label><input type="number" step="0.1" name="tax_percentage" value="{{ $settings['tax_percentage'] ?? '0' }}" class="form-control-custom w-100"><p style="font-size:.65rem;color:var(--neutral-400);margin-top:4px">Set 0 jika tidak dikenakan PPN.</p></div>
                    <div class="mb-3"><label class="form-label-custom">Minimal Belanja 1 Poin (Rp)</label><input type="number" name="point_multiplier" value="{{ $settings['point_multiplier'] ?? '10000' }}" class="form-control-custom w-100"><p style="font-size:.65rem;color:var(--neutral-400);margin-top:4px">Set 0 jika fitur poin tidak aktif.</p></div>
                    <div class="pt-3" style="border-top:1px solid var(--neutral-100)">
                        <button type="submit" class="btn-primary-custom w-100 justify-center"><i class="bi bi-save"></i> Simpan Semua Pengaturan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
