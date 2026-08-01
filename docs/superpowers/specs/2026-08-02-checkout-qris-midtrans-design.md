# Desain: Penyempurnaan Checkout QRIS Order Siswa (Midtrans)

- Tanggal: 2026-08-02
- Status: Disetujui user
- Scope: Alur checkout order siswa (cash/QRIS), keamanan webhook Midtrans, enforcement setting `enable_qris`, pembersihan artefak debug/Copilot.
- Out of scope: Fitur StudentPayment/SPP (dihapus total, tidak dibangun ulang). Alur kasir/admin tidak diubah. Tidak ada deploy.

## 1. Masalah

1. **CRITICAL — "Bayar palsu":** `Siswa/OrderController@updateStatus` dipanggil dari JS callback Snap di sisi siswa tanpa verifikasi apa pun. Siswa bisa menandai pesanan sendiri sebagai lunas tanpa membayar.
2. **CRITICAL — Webhook tidak aman:** Webhook `POST /api/orders/webhook` tidak memverifikasi signature. Siapa pun bisa mengubah status order. (Pola verifikasi yang benar sudah ada di `StudentPaymentController@verifySignature`: `sha512(order_id.status_code.gross_amount.server_key)` + `hash_equals`.)
3. Webhook tidak memeriksa `fraud_status` untuk transaksi `capture`.
4. `snap.js` di-hardcode mode sandbox di 2 view (checkout & orders).
5. Endpoint debug `/api/debug/midtrans-config` tanpa auth + 5 file debug berisi kredensial sandbox.
6. Tidak ada fallback jika webhook tidak tiba (relevan untuk dev lokal & hosting).
7. Tidak ada test sama sekali.
8. JS checkout redirect ke `/siswa/orders/success` yang routenya tidak ada (404).
9. Setting `enable_qris` sudah ada di UI SuperAdmin tetapi tidak di-enforce di mana pun.

## 2. Alur Saat Ini

Checkout siswa: `CheckoutController@index/store` → buat `Order` (cash/qris) → `POST /siswa/orders/{order}/pay-qris` (`Siswa/OrderController@payQris`) → Snap token → `snap.pay()` di client → callback JS `onSuccess` → `updateStatus` (tidak aman) → webhook `/api/orders/webhook` (tanpa verifikasi).

Status enum: `Menunggu Pembayaran`, `Siap Diambil`, `Selesai`, `Dibatalkan`. Payment method enum: `cash`, `qris`.

## 3. Desain Solusi

### A. Amankan webhook (`Siswa/OrderController@webhook`, route `POST /api/orders/webhook`)
1. Verifikasi signature `sha512(order_id.status_code.gross_amount.server_key)` dengan `hash_equals`.
2. Verifikasi `gross_amount` == `order->total_amount` (cegah manipulasi nominal).
3. `capture` hanya diproses jika `fraud_status === 'accept'`.
4. Mapping status:
   - `settlement` atau `capture(accept)` → **Siap Diambil**, isi `paid_at`.
   - `pending` → **Menunggu Pembayaran**.
   - `deny`, `cancel`, `expire`, `refund` → **Dibatalkan** (pakai enum yang ada, tidak tambah status baru).
5. Simpan `transaction_id` ke kolom baru `midtrans_transaction_id` (migrasi baru).
6. Balas 200 untuk semua notifikasi valid (cegah retry loop). Log setiap status.

### B. Hapus celah "bayar palsu"
- Hapus method `updateStatus` + route `siswa.orders.updateStatus` + seluruh JS callback di `orders/index.blade.php`.
- `onSuccess` Snap hanya redirect ke halaman pesanan; status ditentukan oleh webhook/polling.

### C. Fallback polling status
- Route baru `POST /siswa/orders/{order}/check-status` (auth siswa + ownership):
  - Panggil `MidtransService::getTransactionStatus(order_code)`.
  - Update status lokal sesuai mapping yang sama seperti webhook.
  - Return JSON status terbaru.
- Halaman "Pesanan Saya": auto-check untuk order QRIS berstatus `Menunggu Pembayaran` + tombol "Periksa Status Pembayaran".

### D. Enforce setting `enable_qris`
- `CheckoutController@store`: tolak `payment_method=qris` saat setting mati (validasi server-side wajib).
- View checkout: sembunyikan opsi QRIS saat mati.
- `payQris`: tolak saat mati (defense in depth).
- Halaman orders: sembunyikan tombol bayar saat mati.

### E. Fix environment Snap
- `config/midtrans.php`: tambah kunci `snap_url` (sandbox vs production dari `is_production`).
- Helper `MidtransService::getSnapUrl()`.
- 2 view memakai config, bukan URL hardcoded.

### F. Hapus artefak debug & Copilot
- Endpoint `/api/debug/midtrans-config`.
- File: `public/payment-example.html`, `public/payment-debug.html`, `MIDTRANS_SETUP.md`, `SETUP_CHECKLIST.md`, `PAYMENT_METHODS.md`.
- File StudentPayment: `app/Http/Controllers/StudentPaymentController.php`, `app/Http/Controllers/PaymentController.php`, `app/Models/StudentPayment.php`, migrasi `2026_07_26_000000_create_student_payments_table.php`, seluruh blok route `/api/payments/*` di `routes/api.php`, tabel `student_payments` di DB dev (DROP).
- **Tetap disimpan:** `app/Services/MidtransService.php` (dipakai `OrderController`).
- Ganti `ExampleTest` bawaan (yang gagal) dengan test sungguhan.

### G. Route halaman sukses
- Tambah `GET /siswa/orders/success/{order}` memakai `resources/views/siswa/orders/success.blade.php` yang sudah ada.

### H. Test (PHPUnit, TDD, DB test `jual_baju_test` via `RefreshDatabase`)
- **Webhook:** signature valid → status berubah; signature invalid → tolak, status tidak berubah; `gross_amount` mismatch → tolak; `fraud_status=challenge` → tidak jadi Siap Diambil; mapping `settlement`/`pending`/`deny`/`cancel`/`expire`/`refund`; order tidak ditemukan → 404.
- **Checkout:** `enable_qris` off → `qris` ditolak (422); `payment_method` invalid → 422.
- **payQris:** order milik siswa lain → 403; `enable_qris` off → tolak.
- **check-status:** response `settlement` → order jadi Siap Diambil + `paid_at` terisi.
- **Unit:** `MidtransService::getSnapUrl()` (sandbox vs production).

## 4. Keputusan yang Sudah Disepakati

- Hapus fitur/artefak StudentPayment; `MidtransService` tetap dipakai bersama.
- Status refund memakai enum yang ada: `Dibatalkan`.
- Target pengguna memulai dari checkout order siswa (bukan SPP).
- QRIS harus bisa dimatikan/dinyalakan SuperAdmin (setting `enable_qris`).
- Semua file debug dihapus.
