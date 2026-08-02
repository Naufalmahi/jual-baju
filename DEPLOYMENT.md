# Panduan Deployment (Hosting)

Aplikasi Koperasi Sekolah — Laravel 10, MySQL 8, PHP 8.1+.

## 1. Persyaratan Server

- PHP 8.1+ dengan ekstensi: `gd`, `zip`, `curl`, `mbstring`, `openssl`, `pdo_mysql`, `fileinfo`
- MySQL 8 (atau MariaDB 10.4+)
- Composer 2
- Web server: Apache / Nginx (arahkan document root ke folder `public/`)
- Storage: wajib dapat ditulis (permintaan 775/755 yang sesuai user web server)

## 2. Upload & Install

```bash
# Di folder aplikasi
composer install --no-dev --optimize-autoloader

cp .env.example .env
php artisan key:generate

# Atur kredensial & URL di .env, lalu:
DB_DATABASE=jual_baju
DB_USERNAME=... DB_PASSWORD=...
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com
MIDTRANS_ENVIRONMENT=production   # wajib + server key produksi sebelum go-live

php artisan migrate --seed
```

> **PENTING**: segera ganti password akun hasil seeder (Super Admin / Admin / Kasir / contoh siswa) melalui menu profil masing-masing.

## 3. Storage Link (untuk foto profil & gambar produk)

```bash
php artisan storage:link
```

## 4. Build Frontend

Build dilakukan di lokal lalu hasilnya di-upload, atau build langsung di server jika ada Node.js:

```bash
npm install
npm run build
```

- Direktori `public/build/` **wajib ter-upload** (ter-commit di git).
- Jangan lupa file yang diubah lewat UI (mis. logo di `public/images/`).

## 5. Optimasi (disarankan)

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

> Jika muncul error saat `route:cache`, pastikan tidak ada closure di file route.
> Setelah mengubah kode atau `.env`, jalankan ulang `php artisan config:clear && php artisan optimize`.

## 6. Cron (opsional)

Jika memakai queue untuk sesuatu, tambahkan ke crontab:

```
* * * * * php /path-ke-aplikasi/artisan schedule:run >> /dev/null 2>&1
```

## 7. Keamanan & Catatan

- Aktifkan HTTPS (forced) di server / `.env` menggunakan `APP_URL` https.
- Matikan `APP_DEBUG=false` di produksi.
- Auto-poll status QRIS terjadi di halaman pesanan siswa (setiap ~20 detik) — cukup wajar.
- Log aplikasi tersimpan di `storage/logs/laravel.log`.
- Jika pindah server, ulangi langkah 1–5.
