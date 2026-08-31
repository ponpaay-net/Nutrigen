# Backend Handoff Document

## 1. Gambaran Singkat Project
NutriGen adalah sistem informasi Posyandu terpadu yang dirancang untuk mengatasi stunting melalui pemantauan gizi berkelanjutan. Sistem ini menghubungkan Kader Posyandu, Puskesmas, dan Ibu balita dalam satu ekosistem data tunggal.

## 2. Tujuan Sistem
- Mendigitalkan proses pencatatan pengukuran balita di Posyandu.
- Menghitung Z-Score secara presisi untuk deteksi dini stunting.
- Memfasilitasi validasi data pengukuran oleh pihak Puskesmas.
- Memberikan transparansi data gizi kepada Ibu melalui integrasi WhatsApp (tanpa perlu akun login).

## 3. Arsitektur Project
NutriGen mengadopsi arsitektur **Monolith** menggunakan ekosistem Laravel modern:
- **Single Database**: Semua data (Kader, Puskesmas, Ibu, Balita) berada dalam satu database relasional. Data diisolasi menggunakan *Role-Based Access Control (RBAC)*.
- **Server-Side Rendering (SSR)**: Menggunakan Laravel Blade template engine untuk rendering UI.
- **Utility-first CSS**: Styling menggunakan Tailwind CSS.

## 4. Scope MVP (Minimum Viable Product)
- **Portal Kader**: Dashboard, Manajemen Balita (Daftar, Tambah, Profil, Input Pengukuran), Manajemen Jadwal, Laporan, Profil Kader.
- **Portal Puskesmas**: Dashboard pemantauan wilayah, Validasi data pengukuran kader (Acc/Reject status gizi).
- **Portal Ibu**: Tampilan read-only (Riwayat, Grafik Pertumbuhan) yang diakses melalui URL unik bertoken via WhatsApp.
- **Sistem Inti**: RBAC, Perhitungan Z-Score standar WHO.

## 5. Tech Stack
- **Framework Core**: Laravel 12
- **Frontend Template**: Laravel Blade
- **CSS Framework**: Tailwind CSS (via Vite)
- **Database**: MySQL / PostgreSQL (bebas dipilih oleh backend, disarankan MySQL)
- **Autentikasi**: Laravel Breeze / Sanctum (untuk Portal Kader & Puskesmas)

## 6. Struktur Folder Terkait Frontend
```text
resources/
├── views/
│   ├── components/      # Reusable UI components (buttons, cards, modals)
│   ├── layouts/         # Master layouts (app.blade.php)
│   ├── kader/           # View khusus Portal Kader
│   ├── puskesmas/       # (Mockup) View khusus Portal Puskesmas
│   └── ibu/             # (Mockup) View khusus Portal Ibu
```

## 7. Status Frontend (Frontend Freeze)
**Kondisi Saat Ini**: Frontend **Portal Kader** dan **Portal Puskesmas** sudah selesai 100% dan dalam status **FROZEN**. Segala bentuk struktur HTML, komponen Blade, ID class Tailwind, dan arsitektur form sudah disiapkan untuk integrasi.

## 8. Halaman yang Sudah Selesai
**Portal Kader:**
1. Dashboard (`kader/dashboard.blade.php`)
2. Daftar Balita (`kader/daftar-balita.blade.php`)
3. Tambah / Edit Balita (`kader/daftar-balita-baru.blade.php`)
4. Profil Balita & Modal Pengukuran (`kader/profil-balita.blade.php`)
5. Jadwal Posyandu (`kader/jadwal.blade.php`)
6. Detail Jadwal (`kader/detail-jadwal.blade.php`)
7. Tambah Jadwal (`kader/tambah-jadwal.blade.php`)
8. Laporan (`kader/laporan.blade.php`)
9. Profil Kader (`kader/profil-kader.blade.php`)

**Portal Puskesmas:**
1. Dashboard (`puskesmas/dashboard.blade.php`)
2. Antrean Validasi (`puskesmas/validasi.blade.php`)
3. Data Balita (`puskesmas/balita.blade.php`)
4. Posyandu & Kader (`puskesmas/posyandu.blade.php`)
5. Laporan Evaluasi (`puskesmas/laporan.blade.php`)
6. Pengaturan (`puskesmas/pengaturan.blade.php`)

## 9. Halaman yang Belum Selesai (Mockup Stage)
Halaman berikut masih berupa kerangka/mockup dan akan dikembangkan selanjutnya:
- Portal Ibu (`ibu/dashboard`, `ibu/pertumbuhan`)
- Integrasi Chart.js (Grafik Pertumbuhan KMS Digital)
- Halaman Login (Autentikasi)

## 10. Catatan Frontend Freeze
- Semua `<form>` pada portal kader sudah dipasangi atribut `action`, `method`, `name` pada input, dan `<button type="submit">`.
- Semua URL sudah diganti menggunakan `route('nama.route')`.
- Variabel dummy di file Blade telah dikelompokkan ke dalam blok `@php /* DEMO DATA */ @endphp` dengan dokumentasi lengkap mengenai *contract variable* yang dibutuhkan dari controller.

## 11. Aturan Modifikasi Backend
**BOLEH DIUBAH:**
- Menghapus blok `@php /* DEMO DATA */ @endphp`.
- Mengganti variabel dummy dengan variabel dari controller (misal `$balita->nama`).
- Menambahkan `@csrf` dan `@method` pada form.
- Menambahkan direktif `@error` untuk menampilkan validasi.
- Mengubah route di `routes/web.php` ke controller asli.

**TIDAK BOLEH DIUBAH:**
- UI Design, layouting, margin, padding, typography (Tailwind classes).
- User Flow / Informasi arsitektur yang sudah ada.
- Menghapus komponen Blade (`x-child-card`, dll) tanpa konfirmasi.
