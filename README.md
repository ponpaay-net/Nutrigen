# NutriGen — Gizi Balita

Sistem Informasi & Layanan Kesehatan (SILK) untuk pemantauan pertumbuhan dan status gizi balita di Posyandu & Puskesmas. Dibangun dengan pendekatan **B2G** (Business-to-Government) dan memakai standar pengukuran **WHO 2006** sebagai landasan perhitungan z-score.

> Posyandu Bunga Tanjung VII (demo) · 80 balita · 283 pengukuran (WHO Box-Cox)

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
