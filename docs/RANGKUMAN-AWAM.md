# HOLIC Barbershop — Apa Saja yang Berubah (21–28 Sep 2026)

Penjelasan sederhana untuk pemilik & karyawan barbershop. Tidak perlu paham teknis.

## Masuk & Daftar Akun

- Pelanggan bisa **daftar, masuk, edit profil, dan reset password sendiri**.
- Reset password & verifikasi email memakai **kode 6 angka yang dikirim ke email** (gratis, sampai ke semua email).
- Kode berlaku **10 menit**, tombol "kirim ulang" baru aktif setelah **60 detik** (agar tidak disalahgunakan).
- Pelanggan **wajib verifikasi email dulu sebelum bisa antre** — jadi data antrean terjamin asli.
- Kalau salah ketik password berkali-kali, akun dikunci sementara (keamanan).

## Antrean & Jam Operasional

- Sistem otomatis **memilihkan barber yang paling senggang** kalau pelanggan tidak pilih sendiri.
- **Di luar jam buka, pelanggan tidak bisa ambil antrean** — tombol berubah jadi "Tutup · Buka 09:00–21:00".
- **Walk-in (daftar di tempat) juga ikut aturan jam buka** — kalau cabang tutup, tidak bisa dibuatkan antrean.
- Di layar loket, **catatan pelanggan tampil** (mis. "mau potong pendek samping"), jadi barber langsung paham.
- Barber yang sedang **nonaktif tapi masih punya antrean** tetap tampil di loket sampai antreannya habis.
- Layar loket **update sendiri tiap 8 detik** tanpa reload, posisi scroll tidak loncat.
- Check-in pakai **scan QR**: kalau kamera bermasalah, ada panduan + bisa **upload foto QR**.

## Tampilan & HP

- Semua halaman sudah **rapi di HP kecil** (tombol besar, tulisan tidak keluar kotak, tabel bisa digeser).
- Daftar riwayat, cabang, barber, layanan bisa **diurutkan (klik judul kolom)** dan **difilter** (mis. hanya yang aktif, per cabang).
- Pesan error seluruh aplikasi sudah **Bahasa Indonesia** ("Silakan pilih layanan terlebih dahulu.").
- Tombol Kembali di detail antrean kembali ke **halaman sebelumnya** (mis. dari loket → kembali ke loket).
- Nama barber sekarang **muncul benar** di detail antrean (dulu tampil strip "-").

## Laporan (Rekap)

- Halaman rekap menampilkan: total antrean, selesai, dilewati, kedaluwarsa, per barber, per layanan, per jam, dan **perkiraan omzet**.
- Dibuka tetap **cepat** walau data sebulan.

## Keamanan & Kecepatan (dapur pacu)

- Data penting (password, token) tidak pernah tampil ke publik.
- Filter tanggal salah ketik tidak lagi bikin error.
- Kode lama yang tidak dipakai **dibersihkan** (14 file), kode kembar digabung jadi satu — aplikasi lebih ringan & mudah dirawat.

## Yang perlu diketahui pemilik

- Email pengirim kode: `barberholic0@gmail.com` (gratis 300 email/hari — cukup untuk operasional).
- Kalau cabang lembur lewat jam tutup dan perlu buat antrean walk-in: **ubah jam tutup cabang sementara** di menu Cabang.
- Menghapus cabang/barber/layanan yang masih punya riwayat antrean **ditolak sistem** — nonaktifkan saja supaya riwayat tidak hilang.
