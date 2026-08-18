<div align="center">

# Toko Online Koperasi Sekolah

**Sistem e-commerce penjualan seragam & baju untuk koperasi sekolah** berbasis Laravel 10 — lengkap dengan multi-role, keranjang belanja, pembayaran QRIS & Midtrans, hingga mode pemeliharaan.

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red?style=for-the-badge&logo=laravel&logoColor=white)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4?style=for-the-badge&logo=php&logoColor=white)](https://php.net)
[![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)](https://mysql.com)
[![Vite](https://img.shields.io/badge/Vite-5-646CFF?style=for-the-badge&logo=vite&logoColor=white)](https://vitejs.dev)
[![License: MIT](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)](https://opensource.org/licenses/MIT)

</div>

---

## Fitur Utama

| Area | Fitur |
|---|---|
| **Multi-role** | 4 level akses: Super Admin, Admin Toko, Kasir, dan Siswa |
| **Katalog produk** | Kelola kategori, produk, ukuran (size), foto, dan stok |
| **Keranjang & Checkout** | Keranjang belanja siswa, validasi stok real-time |
| **Pembayaran** | QRIS manual (bukti transfer) dan Midtrans: Virtual Account, E-Wallet, QRIS, Bank Transfer |
| **Pesanan** | Riwayat pesanan siswa, antrian pesanan kasir, struk cetak + barcode |
| **Laporan** | Rekap penjualan dan laporan kasir |
| **Keamanan** | Proteksi brute-force (throttle login), deteksi intrusi & honeypot, log keamanan |
| **Manajemen data** | Import siswa via Excel, backup/restore database, mode pemeliharaan global |
| **Tampilan** | Halaman error kustom, halaman maintenance, desain responsif |

## Peran Pengguna

| Role | Kemampuan Utama |
|---|---|
| **Super Admin** | Kelola semua pengguna & kelas, pengaturan sistem, backup DB, mode maintenance, keamanan |
| **Admin Toko** | Kelola kategori, produk, stok, siswa, dan kelas |
| **Kasir** | Proses pesanan, konfirmasi pembayaran, cetak struk, laporan penjualan |
| **Siswa** | Lihat katalog, belanja, checkout, bayar (QRIS / Midtrans), riwayat pesanan |

## Teknologi

- **Backend:** Laravel 10, PHP 8.1+, MySQL 8
- **Frontend:** Blade + Tailwind CSS (via Vite 5)
- **Pembayaran:** Midtrans Core API (VA, E-Wallet, QRIS, Bank Transfer)
- **Lainnya:** Laravel Sanctum, maatwebsite/excel (import siswa), picqer/php-barcode-generator

## Instalasi

**Persyaratan:** PHP 8.1+, Composer 2, MySQL 8 (atau MariaDB 10.4+), Node.js.

```bash
# 1. Clone repositori
git clone https://github.com/Naufalmahi/jual-baju.git
cd jual-baju

# 2. Install dependensi
composer install
npm install && npm run build

# 3. Konfigurasi environment
cp .env.example .env
php artisan key:generate
# atur DB_DATABASE, DB_USERNAME, DB_PASSWORD di .env

# 4. Migrasi & seed data awal
php artisan migrate --seed

# 5. Jalankan
php artisan serve
```

> Kredensial login awal dibuat oleh seeder (`database/seeders`). Ganti password sebelum digunakan di produksi.

### Setup Pembayaran Midtrans (Opsional)

Tanpa konfigurasi Midtrans, checkout tetap berjalan dengan pembayaran QRIS manual.

```env
MIDTRANS_SERVER_KEY=your-server-key
MIDTRANS_CLIENT_KEY=your-client-key
MIDTRANS_MERCHANT_ID=your-merchant-id
MIDTRANS_IS_PRODUCTION=false   # true untuk produksi
MIDTRANS_VA_BANKS=bca,bni,bri,mandiri
MIDTRANS_EWALLET_PROVIDERS=gojek,ovo,shopeepay,dana
```

## Deployment

Lihat panduan lengkap di [DEPLOYMENT.md](DEPLOYMENT.md) — mencakup persyaratan server, migrasi produksi, dan konfigurasi web server.

## Struktur Direktori

```
app/
├── Http/Controllers/
│   ├── SuperAdmin/    # Manajemen sistem & keamanan
│   ├── Admin/         # Katalog produk & data master
│   ├── Kasir/         # Pesanan & laporan
│   └── Siswa/         # Belanja & checkout
├── Middleware/        # Maintenance, deteksi intrusi, throttle
├── Models/
├── Services/          # MidtransService, dll.
└── Support/
resources/views/       # Blade templates per role
routes/web.php         # Seluruh routing aplikasi
database/seeders/      # Seeder akun awal
```

## Lisensi

Proyek ini dilisensikan di bawah [MIT License](https://opensource.org/licenses/MIT).