# NutriGen — Gizi Balita

Sistem Informasi & Layanan Kesehatan (SILK) untuk pemantauan pertumbuhan dan status gizi balita di Posyandu & Puskesmas. Dibangun dengan pendekatan **B2G** (Business-to-Government) dan memakai standar pengukuran **WHO 2006** sebagai landasan perhitungan z-score.

> Posyandu Bunga Tanjung VII (demo) · 80 balita · 282 pengukuran (WHO Box-Cox)

---

## ✨ Fitur Utama

### Kader Posyandu
- **Dashboard** — ringkasan KPI, grafik status gizi, antrian validasi.
- **Data Balita** — CRUD balita, form edit 2-kolom + right rail, validasi status gizi.
- **Ukur Balita** — input BB/PB/Lingkar kepala dengan auto-format desimal + kalkulasi **z-score WHO 2006** otomatis (BB/U, TB/U, BB/TB, IMT/U).
- **Kurva Pertumbuhan WHO** — grafik pertumbuhan interaktif (BB/U, TB/U, BB/TB, IMT/U) dengan animasi.
- **Jadwal Posyandu** — kelola jadwal, spotlight "Sesi Terdekat" + countdown, **Kirim Notifikasi WhatsApp** (FONTE).
- **Laporan** — KPI + donut chart rekap, 4 KPI ber-sparkline, grafik analitik (line + donut), ekspor **PDF resmi** + **Excel (.xls)**.
- **Profil Kader** — profil & keamanan akun.

### Keamanan (B2G)
- **PII Terenkripsi** — NIK, No. BPJS, No. KK, NIK Ayah/Ibu (`cast: encrypted`).
- **RBAC** — role kader vs admin (CheckRole) + scope anti-IDOR per posyandu.
- **Signed link ibu** — akses tanpa password (hash/validasi).
- **Rate-limit**, email verified, `APP_DEBUG=false`, prevent back-history.

---

## 🧮 Standar Perhitungan Gizi (WHO 2006)

Perhitungan status gizi memakai tabel **WHO Child Growth Standards (LMS)** asli, bukan rumus tiruan:

```
z = { ((X/M)^L − 1) / (L·S)   jika L ≠ 0
    { ln(X/M) / S              jika L = 0
```

Setiap balita dihitung otomatis: **BB/U** (underweight), **TB/U** (stunting), **BB/TB** (wasting), **IMT/U**. Hasil dikategorikan sebagai *Normal · Risiko · Stunting · Kurang* sesuai BUKU KIA.

---

## 🛠️ Teknologi

| Lapisan | Stack |
|---|---|
| Backend | **Laravel 10** · PHP 8.3 |
| Frontend | **Blade** · **Tailwind CSS** · **Alpine.js** · Plus Jakarta Sans · **Phosphor Icons** |
| Chart | **ApexCharts** (line, donut, radial, sparkline) |
| DB | MySQL (`nutrigen_mod`) |
| Notifikasi | WhatsApp via **FONTE** (driver log/fonnte/wablas) |
| UI/UX | Design system TEAL SaaS · WCAG 2.2 AA · mobile-first |

---

## 📦 Instalasi & Menjalankan

```bash
# 1. Dependensi PHP
composer install

# 2. Konfigurasi (copy & edit)
cp .env.example .env
php artisan key:generate

# 3. Dependensi + build frontend
npm install
npm run build        # atau npm run dev (jangan bersamaan dengan serve)

# 4. Database
#    buat DB, isi kredensial di .env, lalu:
php artisan migrate --seed

# 5. Jalankan
php artisan serve
```

### Notifikasi WhatsApp (opsional)
```env
WA_DRIVER=log      # simulasi (default, aman — catat notification_logs)
WA_DRIVER=fonnte   # kirim nyata gratis (butuh FONNTE_TOKEN dari fonnte.com)
```

---

## 🎬 Persiapan Demo (Hackathon)

### Akun demo (hasil seeder)
Semua password default **`password`**, kecuali Super Admin.

| Portal | URL | Email | Password |
|---|---|---|---|
| Super Admin (Kemenkes) | `/super-admin/dashboard` | `kemenkes@nutrigen.go.id` | `Kemenkes2026!` |
| Puskesmas | `/puskesmas/dashboard` | `puskesmas@nutrigen.com` | `password` |
| Kader | `/kader/dashboard` | `kader@nutrigen.com` | `password` |
| Ibu | via **link unik** (WA) atau `/login` | `ibu1@nutrigen.com` | `password` |

### Checklist sebelum presentasi
1. **Hidupkan MySQL** (Laragon/MySQL service) dan pastikan `.env` `DB_DATABASE=nutrigen_mod`.
2. **Pastikan data demo terisi** (52 user, 80 balita, 282 pengukuran):
   ```bash
   php artisan migrate:fresh --seed --force
   ```
3. **Build aset frontend** (jika ada perubahan tampilan):
   ```bash
   npm run build
   ```
4. **Jalankan server**:
   ```bash
   php artisan serve
   ```

> ⚠️ **JANGAN menjalankan `php artisan test` pada database demo.** Perintah test melakukan
> `migrate:fresh` dan akan **mengosongkan** `nutrigen_mod`. Bila terlanjur dijalankan,
> pulihkan dengan `php artisan migrate:fresh --seed --force`.

### Alur demo yang disarankan
1. **Kader** → dashboard → tambah/ukur balita → kirim sesi ke Puskesmas.
2. **Puskesmas** → validasi antrean → Setujui → salin/kirim **tautan Buku KIA** ke ibu.
3. **Ibu** → buka tautan unik → lihat rapor E-KIA (status gizi, riwayat, kurva, jadwal).
4. **Super Admin** → dashboard nasional → laporan, kelola Puskesmas/Kader, log aktivitas, pengaturan.

---

## 🧪 Testing

```bash
php artisan test
```
Suite mencakup CRUD balita, pengukuran z-score, akses portal ibu (anti-IDOR), validasi jadwal + notifikasi, dan profil.

---

## 📁 Struktur (ringkas)

```
app/
├─ Http/Controllers/Kader/   (dashboard, balita, jadwal, laporan, profil)
├─ Models/                   (Balita, Pengukuran, Jadwal, Kader, Ibu, NotificationLog)
├─ Services/                 (GrowthCalculationService [WHO LMS], WhatsAppService)
database/
├─ migrations/
├─ seeders/
resources/views/kader/       (dashboard, jadwal, laporan, profil, ukur, edit)
tests/Feature/               (BalitaCrudTest, JadwalCrudTest, JadwalNotifTest, ...)
```

---

## 📄 Lisensi

Untuk keperluan hackathon / internal. Kredensial demo (login kader) disediakan di seeder.

---

*NutriGen — Pemantauan gizi balita berbasis standar WHO & BUKU KIA.*
