# INVENTARIS FITUR NUTRIGEN — Status & Prioritas

> Terakhir diperbarui: 26 Agustus 2026
> Basis data: `routes/web.php`, controller, view — diverifikasi ke source code,
> sebagian diuji runtime (render test tinker + browser CDP, lihat catatan bawah).
>
> **Status**: ✅ JALAN · ⚠️ JALAN DENGAN CATATAN · ❌ TIDAK JALAN / STUB
> **Prioritas**: 🔴 PENTING (alur inti bisnis) · 🟠 PENTING-SEDERHANA · 🟡 SEDANG · ⚪ OPSIONAL

---

## 1. PUBLIK & AUTH (Semua Role)

| Fitur | Fungsi | Status | Pri |
|-------|--------|--------|-----|
| Landing page `/` | Profil produk untuk pengunjung/juri | ✅ | 🟡 |
| Halaman Tim `/team` | Daftar anggota tim | ✅ | ⚪ |
| Register / Login / Logout (Breeze) | Akun untuk kader, puskesmas, ibu + email verification | ✅ | 🔴 |
| Router dashboard per role `/dashboard` | Arahkan user ke portal sesuai role; role ibu kini lewat signed URL (perbaikan 26 Ags) | ✅ | 🔴 |
| Profil user `/profile` | Lihat/ubah profil, hapus akun | ✅ | 🟡 |
| Reset DB `/refresh-database-nutrigen` | Kosongkan & reseed data demo tanpa artisan | ⚠️ endpoint terbuka tanpa proteksi — bahaya jika production | ⚪ |
| Preview dev Portal Ibu `/dev/portal-ibu/{balita}/{page?}` | Jembatan redirect signed URL untuk demo/uji lokal (hanya APP_ENV=local) | ✅ | ⚪ |

## 2. PORTAL KADER (Posyandu) — prefix `/kader`

### 2A. Penting — alur inti posyandu 🔴

| Fitur | Fungsi | Status |
|-------|--------|--------|
| Dashboard kader | Ringkasan antrean ukur, statistik bulan berjalan | ✅ |
| Daftar balita + filter (belum diukur/sudah/rejected/revisi) | Manajemen data anak per posyandu | ✅ |
| Tambah / edit / hapus balita | CRUD data anak beserta data orang tua | ✅ |
| Input pengukuran (berat, tinggi, lingkar kepala) | Inti kerja kader; Z-score & status gizi dihitung otomatis, tersimpan `pending` | ✅ |
| Update pengukuran | Koreksi data sebelum divalidasi puskesmas | ✅ |
| Jadwal posyandu CRUD | Jadwal yang dibuat kader tampil otomatis di portal Ibu & countdown-nya | ✅ |
| Data ditolak (`rejectedData`) | Kader melihat pengukuran yang ditolak puskesmas untuk diperbaiki | ✅ |

### 2B. Sedang 🟡

| Fitur | Fungsi | Status |
|-------|--------|--------|
| Halaman laporan bulanan | Rekap pengukuran per periode | ✅ |
| Generate PDF laporan | Cetak rekap bulanan | ⚠️ berbasis print-view browser (tidak ada paket PDF); hasil bergantung dialog cetak |
| Export Excel laporan | Unduh data rekap | ⚠️ format tabel/CSV sederhana (tanpa paket Excel) |

### 2C. Opsional ⚪

| Fitur | Fungsi | Status |
|-------|--------|--------|
| Profil kader + edit | Kelola data diri kader | ✅ |

## 3. PORTAL PUSKESMAS — prefix `/puskesmas`

### 3A. Penting — rantai validasi 🔴

| Fitur | Fungsi | Status |
|-------|--------|--------|
| Dashboard puskesmas | Statistik antrean validasi (total/stunting/risiko/normal) | ✅ |
| Antrean validasi + tab filter | Daftar pengukuran `pending` per posyandu | ✅ |
| Review pengukuran | Bandingkan data baru vs riwayat sebelumnya sebelum memutuskan | ✅ |
| Approve pengukuran | Sahkan data → muncul di Portal Ibu; **kini menampilkan banner Salin Link + Kirim WhatsApp** (perbaikan 26 Ags) | ✅ |
| Reject pengukuran | Tolak data + catatan validator untuk kader | ✅ |
| Riwayat validasi | Jejak keputusan per pengukuran | ✅ |

### 3B. Penting-sederhana 🟠

| Fitur | Fungsi | Status |
|-------|--------|--------|
| Daftar & detail balita | Konsultasi data anak lintas posyandu | ✅ |
| Laporan puskesmas | Rekap level puskesmas | ✅ |

### 3C. Sedang 🟡

| Fitur | Fungsi | Status |
|-------|--------|--------|
| CRUD posyandu & kader | Kelola lokasi posyandu dan petugas kader | ✅ |
| Pengaturan institusi | Data profil puskesmas | ✅ |
| Pengaturan petugas | Kelola akun petugas | ✅ |
| Pengaturan keamanan (ganti sandi) | Keamanan akun | ✅ |
| Pengaturan notifikasi | Preferensi notifikasi | ⚠️ form tersimpan, tapi belum ada sistem notifikasi di belakangnya |

## 4. PORTAL IBU — prefix `/portal-ibu` (signed URL)

### 4A. Penting — informasi tumbuh kembang 🔴 *(semua diuji runtime browser, 26 Ags)*

| Fitur | Fungsi | Status |
|-------|--------|--------|
| Home/Dashboard anak | Ringkasan status gizi, pengukuran terakhir, delta naik/turun, countdown posyandu | ✅ |
| Pilih Anak (`pilih-anak`) | Pindah antar anak; kartu bernavigasi signed URL (perbaikan 26 Ags) | ✅ |
| Riwayat Pengukuran (`riwayat`) | Timeline pengukuran + grafik pertumbuhan WHO | ✅ |
| Navigasi internal signed | Semua tombol/tab antar halaman membawa `balita` + `orang_tua` (anti-403, anti-IDOR) | ✅ |
| Proteksi akses antar keluarga | Scope `orang_tua_id`; tamper ID → 403, cross-parent → empty state | ✅ |

### 4B. Penting-sederhana 🟠

| Fitur | Fungsi | Status |
|-------|--------|--------|
| Rekomendasi personal | Saran edukasi/diet/tindak lanjut dari RecommendationService sesuai status gizi | ✅ |
| Halaman Posyandu (`profil-anak`) | Jadwal mendatang, pengumuman kader, checklist persiapan | ✅ |
| Chat Kader (WhatsApp) | Hubungi kader langsung; nomor dinormalisasi ke format 62 (perbaikan 26 Ags) | ✅ |

### 4C. Sedang / Opsional 🟡⚪

| Fitur | Fungsi | Status |
|-------|--------|--------|
| Gizi & Menu (`grafik`) | Edukasi gizi + ide resep harian | ⚠️ banner saran dinamis ✅, tapi Ide Resep & Alternatif masih kosong/statis |
| Bel notifikasi di header | Pusat notifikasi ibu | ❌ dekoratif saja — tanpa handler, titik merah selalu tampil |
| Checklist persiapan posyandu | Pengingat bawaan sebelum datang | ⚠️ masih hardcoded (tidak tersimpan per ibu) |
| Info pengukuran ditolak | Ibu tahu datanya ditolak & harus ukur ulang | ❌ belum ada — ibu hanya melihat kosong/"Belum Ada Data" |

## 5. LINTAS FITUR (Services, Infrastruktur, Teknis)

### 5A. Aktif & jalan ✅

| Komponen | Fungsi |
|----------|--------|
| GrowthCalculationService | Perhitungan Z-score BB/U, TB/U, BB/TB & status gizi |
| RecommendationService | Mesin rekomendasi konten per status gizi |
| StatisticsService | Agregasi statistik dashboard kader & puskesmas |
| DashboardService | Data ringkasan beranda tiap portal |
| Signed URL + config `portal.link_ttl_days` | Akses Portal Ibu berbatas waktu 7 hari (bisa diubah via .env) |
| Seeder 80 balita + relasi lengkap | Data demo realistis untuk presentasi |
| Middleware `role:` & `prevent-back-history` | Otorisasi per role + UX back-button |

### 5B. Belum ada / tidak jalan ❌

| Item | Kebutuhan | Rujukan |
|------|-----------|---------|
| Gateway WhatsApp + log pengiriman | Kirim link portal otomatis; saat ini manual via wa.me | TINGGI-02 |
| Kolom `validated_by` / `validated_at` | Jejak siapa & kapan approve/reject | TINGGI-03 |
| Feature test alur inti | Uji simpan ukur, approve, akses portal, isolasi antar keluarga | SEDANG-02 |
| BalitaPolicy (Gate/Policy) | Otorisasi terpusat | SEDANG-04 |
| Notifikasi in-app | Bel ibu & preferensi notifikasi puskesmas | — |

### 5C. Teknis menunggu pembersihan ⚠️

| Item | Masalah | Rujukan |
|------|---------|---------|
| Service lama: `KaderService`, `PuskesmasService` (x2), `PortalIbuService` (x2) | Dead code yang memanggil model `Validasi` yang tidak ada — bom waktu | KRITIS-01, SEDANG-01 |
| Middleware `PortalIbuAuth` + alias `auth.ibu` | Mereferensi model `Ibu` yang tidak ada; tak terpakai | KRITIS-03 |
| `NotificationService` | Import 3 model yang tidak ada | KRITIS-03 |
| Skrip one-off di root project | `*.cjs`, `*.py`, `query_*.php` berserakan | BERSIH-01 |

---

## REKAP

| Kategori | ✅ Jalan | ⚠️ Catatan | ❌ Tidak jalan |
|----------|---------|------------|----------------|
| Publik & Auth | 6 | 1 | 0 |
| Portal Kader | 9 | 2 | 0 |
| Portal Puskesmas | 12 | 1 | 0 |
| Portal Ibu | 8 | 2 | 2 |
| Services/Teknis | 7 | 4 | 5 |
| **Total** | **42** | **10** | **7** |

**Cara verifikasi**: render test semua route Portal Ibu + uji klik nyata via Edge headless/CDP (26 Ags);
tamper signature → 403; cross-parent → empty; compile check seluruh Blade; `php -l` controller.
Fitur di luar Portal Ibu diverifikasi level kode (route→method→view ada, tanpa defect audit yang diketahui).
