<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SchoolWear - Seragam Kualitas, Prestasi Bangsa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>

    {{-- Navbar --}}
    <nav class="top-navbar" style="position:fixed;width:100%;background:rgba(255,255,255,.92);backdrop-filter:blur(16px);border-bottom:1px solid var(--neutral-200);z-index:1050">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-bag-fill" style="font-size:1.3rem;color:var(--primary)"></i>
            <span style="font-weight:800;font-size:1rem;color:var(--neutral-900)">SchoolWear</span>
        </div>
        <div class="topbar-right">
            <a href="{{ route('login.siswa') }}" class="btn-outline-custom btn-sm-custom me-2">
                <i class="bi bi-person"></i> Masuk Siswa
            </a>
            <a href="{{ route('login.petugas') }}" class="btn-primary-custom btn-sm-custom">
                <i class="bi bi-shield-lock"></i> Portal Petugas
            </a>
        </div>
    </nav>

    <div style="padding-top:calc(var(--topbar-height) + 16px)">

        {{-- Hero Section --}}
        <div class="page-content">
            <div class="hero-section fade-in" data-aos="fade-up">
                <div class="position-relative" style="z-index:2">
                    <div class="hero-badge">
                        <i class="bi bi-patch-check-fill" style="color:var(--accent)"></i>
                        Koperasi Resmi SMKN 17 Jakarta
                    </div>
                    <h1>Seragam Sekolah<br>Kualitas Terbaik</h1>
                    <p>Pesan seragam sekolah berkualitas dengan mudah. Pilih model, ukuran, dan bayar langsung di kasir atau via QRIS.</p>
                    <div class="d-flex flex-wrap gap-3 mt-4">
                        <a href="{{ route('login.siswa') }}" class="hero-btn-primary">
                            <i class="bi bi-cart-fill"></i> Mulai Belanja
                        </a>
                        <a href="#fitur" class="hero-btn-secondary">
                            <i class="bi bi-info-circle"></i> Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Features Section --}}
        <div class="page-content" id="fitur">
            <div class="section-header justify-content-center text-center mb-4" data-aos="fade-up">
                <div>
                    <h2 class="fw-extrabold">Mengapa Pilih SchoolWear?</h2>
                    <p style="color:var(--neutral-500);font-size:.9rem">Kemudahan dan kualitas yang bisa kamu andalkan</p>
                </div>
            </div>
            <div class="row g-4 mb-5">
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="0">
                    <div class="feature-card hover-lift">
                        <div class="feature-icon stat-icon-primary">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h4>Resmi & Terpercaya</h4>
                        <p>Koperasi resmi sekolah dengan seragam berkualitas standar nasional.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                    <div class="feature-card hover-lift">
                        <div class="feature-icon stat-icon-success">
                            <i class="bi bi-phone"></i>
                        </div>
                        <h4>Pesan Online</h4>
                        <p>Pilih seragam lewat katalog online, pilih ukuran, dan langsung pesan tanpa antri.</p>
                    </div>
                </div>
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                    <div class="feature-card hover-lift">
                        <div class="feature-icon stat-icon-warning">
                            <i class="bi bi-wallet2"></i>
                        </div>
                        <h4>Bayar Fleksibel</h4>
                        <p>Bayar langsung di kasir sekolah atau scan QRIS dari GoPay, OVO, Dana, ShopeePay.</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Categories --}}
        @if($categories->count() > 0)
        <div class="page-content" data-aos="fade-up">
            <div class="section-header">
                <div>
                    <h2 class="fw-extrabold">Kategori Produk</h2>
                    <p style="color:var(--neutral-500);font-size:.85rem">Jelajahi seragam berdasarkan kategori</p>
                </div>
            </div>
            <div class="row g-3 mb-5">
                @foreach($categories as $cat)
                    <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 50 }}">
                        <div class="card-custom p-4 text-center hover-lift cursor-pointer">
                            <div class="stat-icon stat-icon-primary mx-auto mb-3" style="width:52px;height:52px;font-size:1.2rem">
                                <i class="bi bi-tag-fill"></i>
                            </div>
                            <h6 class="fw-bold mb-1" style="font-size:.85rem">{{ $cat->name }}</h6>
                            <span class="badge badge-primary">{{ $cat->products_count }} Produk</span>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Featured Products --}}
        @if($featuredProducts->count() > 0)
        <div class="page-content" data-aos="fade-up">
            <div class="section-header">
                <div>
                    <h2 class="fw-extrabold">Produk Terbaru</h2>
                    <p style="color:var(--neutral-500);font-size:.85rem">Seragam terbaru yang tersedia di koperasi</p>
                </div>
            </div>
            <div class="row g-4 mb-5">
                @foreach($featuredProducts as $product)
                    @php
                        $totalStok = isset($product->sizes) ? $product->sizes->sum('stock') : ($product->stock ?? 0);
                        $isHabis = $totalStok <= 0;
                    @endphp
                    <div class="col-sm-6 col-lg-3" data-aos="fade-up" data-aos-delay="{{ $loop->index * 75 }}">
                        <div class="product-card">
                            <div class="product-image">
                                @if($product->image)
                                    <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}">
                                @else
                                    <div class="text-center p-4">
                                        <i class="bi bi-bag" style="font-size:2.5rem;color:var(--primary);opacity:.3"></i>
                                    </div>
                                @endif
                                @if($isHabis)
                                    <span class="position-absolute top-0 end-0 badge badge-danger m-3">Stok Habis</span>
                                @endif
                            </div>
                            <div class="product-body">
                                <span class="product-category">{{ $product->category->name ?? 'Seragam' }}</span>
                                <h5 class="product-name">{{ $product->name }}</h5>
                                <p class="product-price">Rp {{ number_format($product->sell_price ?? $product->price, 0, ',', '.') }}</p>
                                <div class="product-footer">
                                    @if(!$isHabis)
                                        <a href="{{ route('login.siswa') }}" class="btn-primary-custom w-100 justify-center btn-sm-custom">
                                            <i class="bi bi-cart-plus"></i> Beli Sekarang
                                        </a>
                                    @else
                                        <button class="btn-outline-custom w-100 justify-center btn-sm-custom" disabled>
                                            Stok Habis
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- FAQ Section --}}
        <div class="page-content" data-aos="fade-up">
            <div class="section-header justify-content-center text-center mb-4">
                <div>
                    <h2 class="fw-extrabold">Pertanyaan Umum</h2>
                    <p style="color:var(--neutral-500);font-size:.9rem">Jawaban untuk pertanyaan yang sering ditanyakan</p>
                </div>
            </div>
            <div class="mx-auto" style="max-width:640px">
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-question">
                        <span>Bagaimana cara memesan seragam?</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Login menggunakan NISN siswa, pilih seragam dari katalog, pilih ukuran dan jumlah, lalu tambahkan ke keranjang. Setelah itu lanjut ke checkout.
                    </div>
                </div>
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-question">
                        <span>Metode pembayaran apa saja yang diterima?</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Kami menerima pembayaran cash/tunai di kasir koperasi sekolah, serta QRIS online yang bisa dibayar melalui GoPay, OVO, Dana, ShopeePay, atau Mobile Banking.
                    </div>
                </div>
                <div class="faq-item" data-aos="fade-up">
                    <div class="faq-question">
                        <span>Di mana saya mengambil seragam?</span>
                        <i class="bi bi-chevron-down"></i>
                    </div>
                    <div class="faq-answer">
                        Setelah pesanan selesai diproses, seragam bisa diambil langsung di Koperasi SMKN 17 Jakarta. Tunjukkan kode pesanan kepada kasir.
                    </div>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <footer class="footer-custom" style="margin-top:80px">
            <div class="page-content">
                <div class="row g-5 mb-4">
                    <div class="col-lg-4">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <i class="bi bi-bag-fill" style="font-size:1.3rem;color:var(--accent)"></i>
                            <span class="fw-bold" style="font-size:1.05rem">SchoolWear</span>
                        </div>
                        <p style="font-size:.85rem;line-height:1.7">Koperasi resmi SMKN 17 Jakarta menyediakan seragam sekolah berkualitas untuk seluruh siswa.</p>
                    </div>
                    <div class="col-lg-4">
                        <h5>Menu</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="#fitur">Fitur</a></li>
                            <li class="mb-2"><a href="{{ route('login.siswa') }}">Login Siswa</a></li>
                            <li class="mb-2"><a href="{{ route('login.petugas') }}">Portal Petugas</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-4">
                        <h5>Kontak</h5>
                        <ul class="list-unstyled" style="font-size:.85rem">
                            <li class="mb-2"><i class="bi bi-geo-alt me-2"></i> SMKN 17 Jakarta</li>
                            <li class="mb-2"><i class="bi bi-envelope me-2"></i> koperasi@smkn17.sch.id</li>
                            <li class="mb-2"><i class="bi bi-telephone me-2"></i> (021) 123456</li>
                        </ul>
                    </div>
                </div>
                <div class="footer-bottom">
                    &copy; {{ date('Y') }} Koperasi SMKN 17 Jakarta. Semua hak dilindungi.
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        if(typeof AOS !== 'undefined') { AOS.init({duration:600,easing:'ease-out-cubic',once:true,offset:40}); }
        document.querySelectorAll('.faq-item .faq-question').forEach(function(q){
            q.addEventListener('click', function(){ this.closest('.faq-item').classList.toggle('active'); });
        });
    </script>
</body>
</html>
