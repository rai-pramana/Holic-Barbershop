# HOLIC Barbershop — Rangkuman Pembaruan (6d8a1dd → HEAD)

Periode: 21–28 Sep 2026 · 70 commit · Production: `holic-barbershop-production-52f2.up.railway.app`

## Audit Final (28 Sep 2026, pasca-deploy)

| Pemeriksaan | Hasil |
|---|---|
| `php -l` seluruh `app/ routes/ config/ database/ lang/` | Bersih, 0 error |
| Route terdaftar | 73, tanpa nama yatim |
| Relasi berantai di Blade (`barber->user` dkk) | Bersih |
| Flash ganda layout vs child | Bersih |
| `Carbon::parse` atas input mentah | Bersih (semua tervalidasi `date`) |
| Pagination tanpa tiebreaker `id` | Bersih (5 titik) |
| Halaman prod: 6 guest + 9 admin + 3 customer | Semua 200 |

## 1. Autentikasi & Verifikasi

- **Profil + lupa password** (4861a12): controller, notifikasi, view, route, nav.
- **OTP 6 digit via Brevo** (60565be → de55a00 → a3c3921): ganti SMTP Gmail (diblokir Railway, timeout 60s) → Resend (trial dinding) → Fonnte WA (limit paket Free, hanya nomor device sendiri tembus) → **Brevo HTTP API, gratis 300/hari, semua tujuan**. Fonnte/Resend/SMTP dicabut total dari kode + env.
- **Verifikasi email wajib sebelum antre** (4afdb0f): middleware `verified.email`, OTP langsung dikirim saat daftar, cooldown kirim ulang 60 dtk (server + countdown JS), pesan per-purpose (verifikasi vs reset).
- **Keamanan auth**: throttle login/register/forgot, pesan forgot generik anti-enumerasi, halaman 419 ramah (029b268).

## 2. Antrean & Operasional

- **Auto-assign barber** (6708dd8, f843f7f): antrean menunggu tersedikit, tie-break paling sedikit melayani hari ini; label & aksi pakai aturan sama.
- **Guard jam operasional** (605511b, dc18997): `Branch::isOpen()` sentral (dukung lewat tengah malam); customer `take/store` + walk-in `create/store` ditolak saat tutup; tombol dashboard nonaktif "Tutup · Buka HH:MM–HH:MM".
- **Loket**: catatan pelanggan di kartu aktif + list (67b0cda); barber nonaktif yang masih berantrean tetap tampil + badge (ad56345); hapus reload 15 dtk → poll halus 8 dtk + hook `live-content-updated` regenerate QR (d53d569).
- **Check-in QR**: `Permissions-Policy camera=(self)`, deteksi secure-context, panduan error per-jenis, fallback upload foto (93d2fec, 2ea7b23).
- **Push HP**: null-safe job, retain 403 + self-heal re-register, validasi subscribe, hapus badge 404 (c9e438b, 55f32a7, d3deaa5). Route/view diagnosa sementara dihapus (b3cc067).

## 3. Data & Listing

- **Pagination deterministik** (dffb771 + b3cc067): tiebreaker `id` di semua paginate (riwayat customer/admin, barber, layanan, cabang) — sembuh dari baris dobel antar halaman.
- **Sort header + filter** (4c53029, b8c50e8, ad56345): trait `Sortable` whitelist + kualifikasi + tiebreaker; sort relasi via join; filter status/cabang kustom satu tema; kolom konten `flex-1` + badge `whitespace-nowrap` agar lebar kartu seragam.
- **Filter 500 ambiguous** (3a5b3e1): qualify `queues.*` pasca-join.
- **Validasi**: tanggal `date` + `after_or_equal` (anti-500), phone regex digit, whitelist filter FK/enum, **seluruh pesan validasi Bahasa Indonesia** via `lang/id/validation.php` (d53d569, 78954b8).
- **Rekap N+1 → 2 query agregat** (b3cc067): rekap sebulan prod ~0,5 dtk.

## 4. UI / Responsif

- Tombol Keluar sidebar samakan `text-sm` (2a895aa); tombol Kembali detail ikut halaman sebelumnya + fallback (fe4d296); nama barber tampil di detail antrean (091f8ae, dulu `barber->user` selalu "—").
- Riwayat customer: badge di samping nama cabang, tinggi kartu seragam, responsif 360px (8b92a63, 2133f74, c20c246).
- Admin 360px: header wrap, filter lentur, tabel truncate, grid collapse, tombol 44px, kartu stat vertikal (58c1ef4, ce590c0, 1adab60).
- Dropdown native → komponen kustom satu tema + halaman tambah center + header breadcrumb+hero konsisten + sapu BOM (a9bfca9, 0ad96ad, cb83d8d, 7d1d434).
- Ikon PWA + favicon dari logo, live-status polling 15 dtk, security headers, OG absolut (3164b2b).

## 5. Finalisasi — Dead-code dihapus (b3cc067)

`CheckinController@index` + view loket lama, `components/select`, `ResetPasswordOtpNotification`, 8 view pagination vendor, route/view `push/diagnose`, route redirect `checkin.index`, accessor mati (`status_color`, `status_badge_class`, `qr_checkin_url`, `estimated_wait_minutes`), `Barber::activeQueues()`, `PushSubscription::user()`. Duplikat diekstrak: `qr-scanner`, `otp-resend` partials.

## Catatan operasional

- Env Railway: Brevo-only (`BREVO_API_KEY`, `BREVO_FROM_EMAIL`); `MAIL_*/RESEND_*/FONNTE_*/VITE_APP_NAME/SESSION_DOMAIN` dihapus; `APP_URL` tanpa bungkus.
- Antrean lama tanpa barber tampil "—" (null-safe), bukan 500.
- Guard hapus cabang/layanan/barber bila masih punya antrean (nonaktifkan saja).
