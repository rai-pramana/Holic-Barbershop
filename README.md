# 💈 HOLIC Barbershop — Sistem Antrean Online

Aplikasi manajemen antrean barbershop berbasis web menggunakan **Laravel 11**, **Blade**, **MySQL**, dan **Tailwind CSS**.

---

## ✨ Fitur Lengkap

### 👤 Customer
- Register & Login
- Pilih cabang barbershop
- Pilih barber (manual atau otomatis/tercepat)
- Pilih layanan dengan harga & durasi
- Ambil nomor antrean dengan format `Q{MMDD}{XXX}`
- Check-in digital (simulasi QR scan)
- Lihat status antrean real-time (polling 10 detik)
- Estimasi waktu tunggu
- Notifikasi saat dipanggil
- 1 akun = 1 antrean aktif per cabang
- Antrean pending auto-expired setelah 60 menit

### 💈 Barber
- Dashboard khusus barber (dark theme)
- Lihat antrean hari ini yang ditugaskan
- Tombol **Panggil** (Active → Called)
- Tombol **Selesai** (Called → Completed)
- Tombol **Skip** (Called/Active → Skipped)
- Auto-refresh setiap 30 detik

### 🔧 Admin
- Dashboard dengan statistik harian
- **CRUD Cabang** (branch)
- **CRUD Barber** (dengan user account)
- **CRUD Layanan** (dengan durasi & harga)
- **Monitor Antrean** dengan filter cabang/status/tanggal
- Ringkasan status antrean per kategori

---

## 🛠️ Persyaratan Sistem

| Komponen | Versi |
|---|---|
| PHP | 8.2+ |
| Composer | 2.x |
| MySQL | 8.0+ |

---

## 🚀 Cara Menjalankan

### 1. Install PHP & Composer

**Windows** — gunakan [Laragon](https://laragon.org/download/) (sudah termasuk PHP 8.2, MySQL, Composer dalam satu installer):

### 2. Install Dependencies

```bash
cd C:\Users\raipr\Documents\Code\holic-barbershop
composer install
```

### 3. Setup Environment

```bash
copy .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database

Edit file `.env`:
```env
DB_DATABASE=holic_barbershop
DB_USERNAME=root
DB_PASSWORD=        (kosongkan jika Laragon)
```

Buat database di MySQL (via phpMyAdmin atau HeidiSQL):
```sql
CREATE DATABASE holic_barbershop;
```

### 5. Migration & Seed

```bash
php artisan migrate --seed
```

### 6. Jalankan

```bash
php artisan serve
```

Buka: **http://localhost:8000**

---

## 🔑 Akun Demo

| Role | Email | Password |
|---|---|---|
| Admin | admin@holic.com | password |
| Barber 1 | budi@holic.com | password |
| Barber 2 | rizky@holic.com | password |
| Barber 3 | deni@holic.com | password |
| Customer | customer@demo.com | password |

---

## 📁 Struktur Project

```
holic-barbershop/
├── app/
│   ├── Console/Commands/
│   │   ├── AutoSkipCalledQueues.php
│   │   └── ExpirePendingQueues.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/ (Login, Register)
│   │   │   ├── Admin/ (Dashboard, Branch, Barber, Service, Queue)
│   │   │   ├── Barber/ (Dashboard, Queue)
│   │   │   └── Customer/ (Queue)
│   │   └── Middleware/RoleMiddleware.php
│   └── Models/ (User, Branch, Barber, Service, Queue)
├── database/
│   ├── migrations/ (7 files)
│   └── seeders/DatabaseSeeder.php
├── routes/web.php
└── resources/views/
    ├── layouts/ (app, admin, barber)
    ├── auth/ (login, register)
    ├── welcome.blade.php
    ├── customer/ (dashboard, queue/take, queue/status)
    ├── admin/ (dashboard, branches, barbers, services, queues)
    └── barber/ (dashboard)
```

---

## ⚙️ Alur Antrean

```
[Daftar/Login] → [Pilih Cabang + Layanan + Barber]
→ [PENDING] → [Check-in] → [ACTIVE]
→ [Barber Panggil] → [CALLED]
→ [Barber Selesai] → [COMPLETED]
→ [Barber Skip] → [SKIPPED]
→ [60 menit] → [EXPIRED] (otomatis)
```

---

## 🐛 Troubleshooting

| Error | Solusi |
|---|---|
| `No application encryption key` | Jalankan `php artisan key:generate` |
| `Unknown database` | Buat database `holic_barbershop` di MySQL |
| `Class not found` | Jalankan `composer dump-autoload` |
| `403 Forbidden` | Pastikan kolom `role` terisi di tabel `users` |
