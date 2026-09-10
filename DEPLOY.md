# 🚀 Panduan Deploy NutriGen

Dokumen ini untuk **tim yang men-deploy** (VPS/domain sudah disiapkan).
Ikuti berurutan. Bagian yang ditandai ⚠️ adalah penyebab umum aplikasi gagal jalan.

---

## 0. Ringkas alur

1. Merge branch `perbaikan-terakhir-nutrigen` → `main`.
2. Siapkan database MySQL + user di VPS.
3. Isi **Environment Variables** (bagian 2) — ini yang paling sering bikin error.
4. Deploy (Docker **atau** manual).
5. Cek aplikasi (bagian 6).

> ✅ Data demo terisi **otomatis sekali** saat pertama dijalankan
> (52 user, 80 balita, 282 pengukuran). Setelah itu langsung bisa login & demo.

---

## 1. Setelah merge (di repo)

Branch perbaikan: **`perbaikan-terakhir-nutrigen`**

- Buka PR → merge ke `main`:
  `https://github.com/ponpaay-net/Nutrigen/pull/new/perbaikan-terakhir-nutrigen`
- Pastikan `main` sudah berisi commit perbaikan sebelum deploy.

---

## 2. Environment Variables (WAJIB lengkap)

Salin dari `.env.example`, lalu isi nilai berikut. **Jangan commit file `.env`.**

| Variable | Nilai | Catatan |
|---|---|---|
| `APP_NAME` | `NutriGen` | |
| `APP_ENV` | `production` | ⚠️ **wajib**. Kalau `local`, route dev portal ibu terbuka untuk umum. |
| `APP_KEY` | `base64:...` | ⚠️ **wajib**. Kalau kosong → app error (enkripsi PII & session gagal). Generate: `php artisan key:generate --show` |
| `APP_DEBUG` | `false` | Jangan `true` di publik. |
| `APP_URL` | `https://domain-kamu.com` | Sesuai domain. |
| `LOG_CHANNEL` | `stack` | |
| `LOG_LEVEL` | `warning` | |
| `DB_CONNECTION` | `mysql` | |
| `DB_HOST` | `127.0.0.1` (atau host DB) | |
| `DB_PORT` | `3306` | |
| `DB_DATABASE` | nama DB kamu | Buat DB baru, jangan pakai DB lama. |
| `DB_USERNAME` | user DB kamu | ⚠️ Jangan `root` di produksi. |
| `DB_PASSWORD` | password DB kamu | |
| `SESSION_DRIVER` | `file` | Boleh `database`/`redis` bila tersedia. |
| `QUEUE_CONNECTION` | `sync` | |
| `CACHE_DRIVER` | `file` | |
| `WA_DRIVER` | `log` | Notifikasi WhatsApp **disimulasikan** (tidak kirim nyata). Sesuai kesepakatan. |
| `NIK_HASH_KEY` | string acak 64 char | Opsional; untuk hashing NIK. Biarkan default bila tidak yakin. |

---

## 3. Siapkan Database (VPS)

```sql
CREATE DATABASE nutrigen CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'nutrigen'@'localhost' IDENTIFIED BY 'PASSWORD_KUAT';
GRANT ALL PRIVILEGES ON nutrigen.* TO 'nutrigen'@'localhost';
FLUSH PRIVILEGES;
```

Isi `DB_DATABASE=nutrigen`, `DB_USERNAME=nutrigen`, `DB_PASSWORD=PASSWORD_KUAT`.

---

## 4. Deploy

### Opsi A — Docker (disarankan, sudah disiapkan)

Repo sudah punya `Dockerfile` + `docker/entrypoint.sh`. Saat container start otomatis:

```
APP_KEY guard → migrate → seed data demo (sekali) → storage:link → clear cache → serve di $PORT
```

Cukup set Environment Variables (bagian 2) lalu build & run. Port default **8080**.

```bash
docker build -t nutrigen .
docker run -d --name nutrigen -p 8080:8080 --env-file .env nutrigen
```

### Opsi B — Manual (tanpa Docker)

Jalankan berurutan di folder aplikasi:

```bash
# 1. Dependensi PHP (tanpa dev)
composer install --no-dev --optimize-autoloader --no-interaction

# 2. Build aset frontend (Tailwind/Vite)
npm ci
npm run build

# 3. Struktur database
php artisan migrate --force

# 4. Isi data demo SEKALI (aman diulang; dilewati bila DB sudah berisi)
php artisan nutrigen:seed-if-empty

# 5. Link storage + bersihkan cache
php artisan storage:link
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 6. Jalankan
php artisan serve --host=0.0.0.0 --port=8080
```

**Syarat server:** PHP 8.3 + ekstensi `pdo_mysql`, `mbstring`, `zip`, `intl`, `bcmath`.

---

## 5. ⚠️ Penting untuk Produksi

1. **Jangan pakai `php artisan serve` untuk trafik nyata.** Itu server bawaan (single-thread, tidak stabil). Untuk VPS sungguhan gunakan **Nginx + PHP-FPM**. `artisan serve` hanya cocok untuk demo cepat.
2. **Pastikan `APP_ENV=production`.** Kalau `local`, route `/dev/portal-ibu/*` aktif → siapa pun bisa membuka data anak.
3. **Data demo hanya terisi bila database KOSONG.** Kalau DB sudah ada isinya, seed dilewati (memang disengaja agar tidak duplikat). Untuk reset: kosongkan DB, lalu restart/seed ulang.
4. **Arahkan domain ke port aplikasi** (mis. reverse proxy Nginx `:80/:443` → `:8080`) dan aktifkan **HTTPS** (link portal ibu memakai signed URL).
5. **Password akun demo masih default** (lihat bagian 7). Ganti bila ingin lebih aman.
6. **Notifikasi WhatsApp tidak berjalan** (`WA_DRIVER=log`) — memang diskip untuk demo.

---

## 6. Cek Setelah Deploy

| Cek | Harapan |
|---|---|
| Buka `/login` | Halaman login tampil (bukan 500 / blank) |
| Login Super Admin | Masuk ke `/super-admin/dashboard` |
| Login Puskesmas | Masuk ke `/puskesmas/dashboard` |
| Login Kader | Masuk ke `/kader/dashboard` |
| Buka `/refresh-database-nutrigen` | **404** (route berbahaya sudah dihapus — ini benar) |

Kalau muncul error 500: cek **`APP_KEY`** dan **kredensial DB** lebih dulu.

---

## 7. Akun Demo (password default)

Semua password **`password`**, kecuali Super Admin.

| Portal | URL | Email | Password |
|---|---|---|---|
| Super Admin (Kemenkes) | `/super-admin/dashboard` | `kemenkes@nutrigen.go.id` | `Kemenkes2026!` |
| Puskesmas | `/puskesmas/dashboard` | `puskesmas@nutrigen.com` | `password` |
| Kader | `/kader/dashboard` | `kader@nutrigen.com` | `password` |
| Ibu | via **link unik** (WA) — atau `/login` | `ibu1@nutrigen.com` | `password` |

> Portal Ibu diakses lewat **tautan unik** (signed URL) yang dikirim ke WhatsApp ibu
> setelah Puskesmas memvalidasi pengukuran — bukan lewat menu login biasa.

---

## 8. Alur Demo yang Disarankan

1. **Kader** → dashboard → tambah/ukur balita → **Kirim Sesi** ke Puskesmas.
2. **Puskesmas** → Validasi → **Setujui** → salin/kirim **tautan Buku KIA** ke ibu.
3. **Ibu** → buka tautan unik → lihat rapor E-KIA (status gizi, riwayat, kurva, jadwal).
4. **Super Admin** → dashboard nasional → laporan, kelola Puskesmas/Kader, log aktivitas, pengaturan.

---

## 9. Perintah Berguna

```bash
php artisan nutrigen:seed-if-empty   # isi data demo bila DB masih kosong
php artisan migrate --force          # jalankan migrasi
php artisan config:clear             # bersihkan cache konfigurasi
php artisan route:clear              # bersihkan cache route
php artisan view:clear               # bersihkan cache view
php artisan test                     # jalankan test (97 test, 300 assertions)
```

> ⚠️ **Jangan jalankan `php artisan test` pada database demo/DB produksi** —
> test melakukan `migrate:fresh` dan dapat mengosongkan database. Test memakai
> database terpisah (`nutrigen_test`) sesuai `phpunit.xml`; pastikan konfigurasi
> test mengarah ke DB terpisah.

---

## 10. Troubleshooting Singkat

| Gejala | Penyebab umum | Solusi |
|---|---|---|
| 500 di semua halaman | `APP_KEY` kosong / DB gagal konek | Isi `APP_KEY`, cek kredensial DB |
| Halaman blank, tombol tanpa warna | Aset frontend belum di-build | `npm run build` |
| Tidak bisa login | Database kosong (belum di-seed) | `php artisan nutrigen:seed-if-empty` |
| Tautan portal ibu 403 | URL kedaluwarsa (default 7 hari) | Setujui ulang di Puskesmas → link baru |
| Perubahan config tidak berefek | Cache lama | `php artisan config:clear route:clear view:clear` |

---

*NutriGen — Pemantauan gizi balita berbasis standar WHO & BUKU KIA.*
