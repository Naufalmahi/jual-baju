# Implementation Plan: Penyempurnaan Checkout QRIS Order Siswa (Midtrans)

- Desain: `docs/superpowers/specs/2026-08-02-checkout-qris-midtrans-design.md`
- Metode: TDD — setiap task dimulai dengan menulis test yang gagal, lalu implementasi minimal.
- Setup test: phpunit.xml → `DB_DATABASE=jual_baju_test` (MySQL, sudah dibuat & di-migrate).

## Task 1 — Migrasi kolom `midtrans_transaction_id` di `orders`
- **Mengapa:** webhook perlu menyimpan ID transaksi Midtrans.
- **Perubahan:** migrasi baru `add_midtrans_transaction_id_to_orders_table` (nullable string, setelah `snap_token`).
- **Test:** feature test memakai `RefreshDatabase` → `Schema::hasColumn('orders', 'midtrans_transaction_id')` true.

## Task 2 — `config/midtrans.php` `snap_url` + `MidtransService::getSnapUrl()`
- **Mengapa:** menghapus URL snap.js yang di-hardcode di view.
- **Perubahan:** kunci `snap_url` di config (sandbox: `https://app.sandbox.midtrans.com/snap/snap.js`, production: `https://app.midtrans.com/snap/snap.js` berdasarkan `is_production`); method `getSnapUrl()`.
- **Test (unit):** getSnapUrl mengembalikan URL sandbox saat `is_production=false` dan URL production saat true (override config di test).

## Task 3 — Webhook aman (`Siswa/OrderController@handleWebhook`)
- **Mengapa:** webhook saat ini tanpa verifikasi (CRITICAL).
- **Perubahan:**
  - `verifySignature($orderId, $statusCode, $grossAmount, $serverKey)` — `sha512` + `hash_equals`.
  - Cek `gross_amount == order->total_amount`.
  - Mapping: `settlement`/`capture(accept)` → `Siap Diambil` + `paid_at`; `pending` → `Menunggu Pembayaran`; `deny`/`cancel`/`expire`/`refund` → `Dibatalkan`.
  - Simpan `midtrans_transaction_id`. Log status. Selalu respon 200 untuk notifikasi valid (signature ok) walau payload tidak relevan.
- **Test (feature, RefreshDatabase + mock Midtrans? tidak perlu — endpoint langsung):**
  - signature valid + `settlement` → status `Siap Diambil`, `paid_at` terisi, `midtrans_transaction_id` tersimpan, respon 200.
  - signature salah → respon 403, status tidak berubah.
  - `gross_amount` tidak sama → 403.
  - `capture` + `fraud_status=challenge` → tetap `Menunggu Pembayaran`.
  - `deny` → `Dibatalkan`; `pending` → tetap `Menunggu Pembayaran`.
  - order tidak ditemukan → 404.
  - signature hash dibuat dengan `config('midtrans.server_key')`.

## Task 4 — Hapus `updateStatus` (celah "bayar palsu")
- **Mengapa:** siswa bisa menandai pesanan sendiri lunas (CRITICAL).
- **Perubahan:** hapus method `updateStatus`, route `siswa.orders.updateStatus`, JS callback di `orders/index.blade.php` (biarkan `onSuccess` hanya redirect), hapus referensi di `checkout/index.blade.php`.
- **Test:** cek route tidak terdaftar (`Route::has` false); test checkout tetap hijau.

## Task 5 — Fallback polling: `check-status`
- **Mengapa:** webhook bisa telat/tidak tiba (dev lokal, hosting).
- **Perubahan:**
  - Route `POST /siswa/orders/{order}/check-status` (auth + ownership via controller check).
  - Method: panggil `MidtransService::getTransactionStatus($order->order_code)`, update status pakai mapping yang sama (ekstrak ke method private `applyMidtransStatus($order, $status, $fraudStatus)` agar dipakai webhook & polling).
  - View orders: auto-poll order QRIS `Menunggu Pembayaran` + tombol "Periksa Status Pembayaran".
- **Test (feature):** siswa lain akses order orang lain → 403; response `settlement` → order `Siap Diambil` + `paid_at`; status tersimpan ke DB.
- **Catatan:** di test, mock `MidtransService::getTransactionStatus` (patch method) agar tidak hit API nyata.

## Task 6 — Enforce `enable_qris`
- **Mengapa:** setting ada tapi tidak di-enforce.
- **Perubahan:**
  - `CheckoutController@store`: `payment_method` dinilai valid hanya `cash`/`qris` dan `qris` hanya jika setting aktif.
  - View checkout: sembunyikan radio qris jika mati.
  - `payQris`: tolak jika mati (403/422).
  - View orders: sembunyikan tombol bayar jika mati.
- **Test (feature):** setting off + checkout `qris` → 422; setting off + `payQris` → tolak; setting on → normal.

## Task 7 — Route halaman sukses
- **Mengapa:** redirect `/siswa/orders/success` 404.
- **Perubahan:** route `GET /siswa/orders/success/{order}` → view `success.blade.php`.
- **Test (feature):** siswa pemilik → 200; siswa lain → 403/404.

## Task 8 — Hapus artefak debug & StudentPayment
- **Mengapa:** kredensial sandbox bocor via file publik; kode Copilot tak terpakai.
- **Perubahan:**
  - Hapus endpoint `/api/debug/midtrans-config`.
  - Hapus: `public/payment-example.html`, `public/payment-debug.html`, `MIDTRANS_SETUP.md`, `SETUP_CHECKLIST.md`, `PAYMENT_METHODS.md`.
  - Hapus: `StudentPaymentController.php`, `PaymentController.php`, `StudentPayment.php`, migrasi `2026_07_26_000000_create_student_payments_table.php`, blok `/api/payments/*` di `routes/api.php`.
  - DROP TABLE `student_payments` di DB dev.
  - Ganti `tests/Feature/ExampleTest.php` + `tests/Unit/ExampleTest.php` dengan test tugas ini.
- **Test:** `route:list` tidak memuat `/api/debug/*` & `/api/payments/*`; seluruh suite hijau.

## Task 9 — Full suite + review
- Jalankan `php artisan test` penuh; perbaiki hingga hijau.
- Self-review kode vs desain; jalankan `php artisan route:list` untuk memastikan route bersih.
- Commit per task (gaya repo, pesan Bahasa Indonesia).
