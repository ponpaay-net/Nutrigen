# Portal Puskesmas: Wireframe & UX Flow V1

Dokumen ini mendefinisikan *Low-Fidelity Text-Based Wireframe* untuk Portal Puskesmas NutriGen. Wireframe ini dirancang secara *Desktop-First* (karena Ahli Gizi mayoritas bekerja menggunakan PC/Laptop Puskesmas), namun tetap *Responsive* untuk akses *Tablet/Mobile* saat di lapangan.

Desain ini sepenuhnya mematuhi hasil audit dari `PUSKESMAS_PRODUCT_AUDIT.md`.

---

## 1. Global Layout (Shell)

Struktur dasar portal yang konsisten di semua halaman.

### Desktop View (Layar Lebar)
```text
+-----------------------+-------------------------------------------------------+
| [Logo NutriGen]       | [Breadcrumb]                   [Notifikasi] [Profil]  |
| Puskesmas Melati      |                                                       |
+-----------------------+-------------------------------------------------------+
| ≡ MAIN MENU           |                                                       |
|                       |                                                       |
| [ ] Dashboard         |   # Main Content Area                                 |
| [ ] Antrean Validasi  |   (Scrollable, margin 24px)                           |
| [ ] Direktori Balita  |                                                       |
| [ ] Posyandu & Kader  |                                                       |
| [ ] Laporan           |                                                       |
| [ ] Pengaturan        |                                                       |
|                       |                                                       |
| [ ] Keluar            |                                                       |
+-----------------------+-------------------------------------------------------+
```

### Mobile / Tablet View
```text
+-------------------------------------------------------+
| [Hamburger ≡]   [Logo NutriGen]   [Notif] [Profil]    |
+-------------------------------------------------------+
|                                                       |
|  # Main Content Area (1 Column Full Width)            |
|  (Scrollable, padding 16px)                           |
|                                                       |
+-------------------------------------------------------+
* Hamburger menu memunculkan sidebar sebagai Full Screen Overlay / Off-canvas.
```

---

## 2. Dashboard (Action-Driven)

Fokus utama adalah CTA Validasi dan sekilas status gizi wilayah.

### Desktop View
```text
+-----------------------------------------------------------------------------+
| Selamat Pagi, Dr. Siti (Ahli Gizi)                                          |
| "Anda memiliki beberapa tugas yang membutuhkan perhatian."                  |
+-----------------------------------------------------------------------------+
|                                                                             |
|  [ TASK INBOX SUMMARY CARD ] (Full width banner - Background Soft Red/Blue) |
|  +-----------------------------------------------------------------------+  |
|  |  [!] 46 Pengukuran Menunggu Validasi                                  |  |
|  |      ↳ 12 Anomali Data (Ekstrem)                                      |  |
|  |      ↳ 5 Balita Berisiko (Gizi Buruk/Stunting)                        |  |
|  |                                                                       |  |
|  |  [ BUTTON: Mulai Validasi Sekarang ]                                  |  |
|  +-----------------------------------------------------------------------+  |
|                                                                             |
|  [ QUICK STATS ] (Grid 4 Kolom)                                             |
|  [ Total Balita: 1.248 ] [ Diukur Bulan Ini: 842 ] [ Valid: 706 ] [ Pending: 136 ]
|                                                                             |
|  [ VISUALISASI UTAMA ] (Grid 1 Kolom atau Center Alignment)                 |
|  +-----------------------------------------------------------------------+  |
|  | Distribusi Status Gizi (Bulan April 2025)                             |  |
|  |                                                                       |  |
|  |           ( DONUT CHART )       Normal: 56%                           |  |
|  |             Hijau/Kuning        Perlu Perhatian: 30%                  |  |
|  |               Merah             Berisiko: 14%                         |  |
|  |                                                                       |  |
|  | [BUTTON: Lihat Laporan Lengkap]                                       |  |
|  +-----------------------------------------------------------------------+  |
+-----------------------------------------------------------------------------+
```
**Mobile Behavior**: Quick Stats menjadi grid 2x2. Donut chart menyusut ukurannya.

---

## 3. Antrean Validasi & Workspace (The Core Feature)

Halaman paling krusial. Menggunakan **Split View / Side Drawer** agar *reviewer* tidak perlu berpindah halaman (Zero Reload).

### Desktop View (Split View / Master-Detail Pattern)
```text
+-----------------------------------------+-----------------------------------+
| TABS: [Semua Pending] [Anomali] [Berisiko] [Approved] [Rejected]            |
| Filter: [Semua Posyandu v] [Bulan Ini v] [Pencarian...]                     |
+-----------------------------------------+-----------------------------------+
| LIST ANTREAN (KIRI - 40% Width)         | WORKSPACE VALIDASI (KANAN - 60%)  |
|                                         |                                   |
| [Card: Rizky Maulana (21 bln)]          | Identitas: Rizky Maulana (L)      |
| BB/U: -2.35 (Kurang) | Melati 1         | Ortu: Ibu Aisyah | Posyandu Melati 1|
| Oleh: Kader Yuni                        | ----------------------------------|
|                                         | Pengukuran Terbaru (April 2025):  |
| [Card: Dinda Amanda (27 bln)] <Active>  | BB: 12.1 kg | TB: 85.0 cm         |
| TB/U: -2.45 (Pendek) | Melati 2         | ----------------------------------|
| Oleh: Kader Siti                        | Grafik Pertumbuhan (KMS BB/U):    |
|                                         | [ LINE CHART / KURVA KMS ]        |
| [Card: Nazwa Aulia (29 bln)]            |   / \                             |
| IMT/U: -2.01 | Melati 3                 |  /   \__x (Skr)                   |
| Oleh: Kader Rina                        | /                                 |
|                                         | ----------------------------------|
| [Card: Alif Pratama (18 bln)]           | Riwayat 3 Bulan Terakhir:         |
| BB/U: -2.18 | Melati 4                  | - Mar: 11.5 kg (Valid)            |
|                                         | - Feb: 11.2 kg (Valid)            |
|                                         | ----------------------------------|
|                                         | Input Catatan Medis (Opsional):   |
|                                         | [Textarea: "Perlu PMT..."]        |
|                                         |                                   |
|                                         | [ REJECT ]       [ APPROVE DATA ] |
+-----------------------------------------+-----------------------------------+
```

### Mobile / Tablet View
```text
+-----------------------------------------+
| [Tabs Dropdown v] [Filter]              |
+-----------------------------------------+
| [Card: Dinda Amanda] (Klik)             |
| [Card: Nazwa Aulia]                     |
| [Card: Alif Pratama]                    |
+-----------------------------------------+

* KETIKA CARD DIKLIK (Mobile Behavior):
  Muncul OVERLAY BOTTOM SHEET (Drawer dari Bawah) yang memenuhi 90% layar.
  Berisi Data Identitas, KMS, dan Tombol Approve/Reject persis seperti Kanan Desktop.
```

### UX Flow Interaksi (Sesuai Final Refinement)
1. User menekan **[ APPROVE DATA ]**.
2. Tombol berubah status: `[ Spinner ] Saving...`
3. Muncul *Success Toast* di pojok kanan atas: `"Data Dinda Amanda disetujui."`
4. Card "Dinda Amanda" menghilang dari List Antrean Kiri dengan animasi *fade-out*.
5. Sistem secara otomatis memuat data "Nazwa Aulia" (baris berikutnya) ke dalam Workspace Kanan.
6. User langsung siap menganalisis data berikutnya tanpa klik ekstra.

---

## 4. Direktori Balita (Pencarian & Rekam Medis)

Pusat pencarian seluruh data balita di wilayah kerja puskesmas.

### Desktop & Mobile View
```text
+-----------------------------------------------------------------------------+
| Filter: [Pencarian Nama/NIK...] [Posyandu v] [Status Gizi v] [Usia v]       |
+-----------------------------------------------------------------------------+
| Tabel Balita (Mobile: Bentuk Card List):                                    |
| NAMA          | USIA | POSYANDU | STATUS TERAKHIR | AKSI                    |
| Rizky Maulana | 21bln| Melati 1 | Gizi Kurang     | [Lihat Detail]          |
| Dinda Amanda  | 27bln| Melati 2 | Pendek          | [Lihat Detail]          |
| Fathir Arkan  | 13bln| Melati 3 | Normal          | [Lihat Detail]          |
+-----------------------------------------------------------------------------+
| < Pagination 1 2 3 >                                                        |
+-----------------------------------------------------------------------------+
```

### Detail Balita (Setelah klik "Lihat Detail")
```text
[ < Kembali ke Direktori ]

+-------------------------+---------------------------------------------------+
| PROFIL ANAK (Kiri/Atas) | REKAM MEDIS & KMS (Kanan/Bawah)                   |
|                         |                                                   |
| [Foto Dummy/Icon]       | Tabs: [Kurva BB/U] [Kurva TB/U] [Kurva BB/TB]     |
| NAMA: Rizky Maulana     |                                                   |
| NIK: 110123...          | [ GRAFIK WHO 2006 (Dengan Background Pita Z-Score)|
| TGL LAHIR: 10 Mei 2023  | Garis Hijau (0), Garis Kuning (-2), Merah (-3)    |
| L/P: Laki-laki          |                                                   |
| ORTU: Ibu Aisyah        |                                                   |
| POSYANDU: Melati 1      |                                                   |
|                         | ------------------------------------------------- |
| [BUTTON: Kontak Ibu]    | TIMELINE PENGUKURAN (List Tabel)                  |
|                         | Bulan  | BB    | TB    | Z-Score | Validasi       |
|                         | Apr 25 | 10.2  | 78.5  | -2.35   | Approved (Dr)  |
|                         | Mar 25 | 9.8   | 78.0  | -2.10   | Approved       |
+-------------------------+---------------------------------------------------+
```

---

## 5. Posyandu & Kader

Manajemen fasilitas tingkat bawah.

```text
+-----------------------------------------------------------------------------+
| Daftar Posyandu Binaan (Grid Cards)                                         |
+-----------------------------------------------------------------------------+
| [ CARD: Melati 1 ]                       | [ CARD: Melati 2 ]               |
| Desa Lampeuneurut                        | Desa Lamreung                    |
| Kader Aktif: 5 Orang                     | Kader Aktif: 4 Orang             |
| Balita Terdaftar: 150 Anak               | Balita Terdaftar: 120 Anak       |
| Cakupan Timbang Bulan Ini: 85%           | Cakupan Timbang Bulan Ini: 72%   |
|                                          |                                  |
| [Lihat Detail Posyandu]                  | [Lihat Detail Posyandu]          |
+-----------------------------------------------------------------------------+
```

---

## 6. Laporan (Reporting & Analytics)

Pusat seluruh data statistik, menggunakan sistem Tab agar halaman tetap bersih.

```text
+-----------------------------------------------------------------------------+
| Judul: Laporan & Analitik Gizi (Filter: [Bulan April 2025 v] [Semua Posyandu v])
+-----------------------------------------------------------------------------+
| TABS: [ Rekap (Tabel) ]  [ Grafik Distribusi ]  [ Trend (Waktu) ]  [ Export ]
+-----------------------------------------------------------------------------+
| (Jika Tab Rekap Aktif)                                                      |
| POSYANDU | SASARAN | DIUKUR | NORMAL | STUNTING | GIZI BURUK | GIZI LEBIH   |
| Melati 1 | 150     | 120    | 100    | 10       | 5          | 5            |
| Melati 2 | 120     | 100    | 80     | 15       | 3          | 2            |
| --------------------------------------------------------------------------- |
| (Jika Tab Trend Aktif)                                                      |
| [ LINE CHART MULTIPLE LINES ] (Menunjukkan naik turunnya angka stunting     |
| di 6 bulan terakhir).                                                       |
| --------------------------------------------------------------------------- |
| (Jika Tab Export Aktif)                                                     |
| Pilih Format: ( ) PDF (Laporan E-PPGBM)  ( ) Excel / CSV Raw Data           |
| [ BUTTON: Generate & Download Laporan ]                                     |
+-----------------------------------------------------------------------------+
```

---

## 7. Pengaturan

Konfigurasi standar aplikasi untuk user Puskesmas.

```text
+-----------------------------------------------------------------------------+
| TABS: [ Profil Puskesmas ]  [ Pengaturan Akun ]                             |
+-----------------------------------------------------------------------------+
| Kode Faskes: P3201010101                                                    |
| Nama Instansi: Puskesmas Melati                                             |
| Kecamatan: Darul Imarah                                                     |
| Kepala Puskesmas: Dr. H. M. Yamin                                           |
|                                                                             |
| [ Simpan Perubahan ]                                                        |
+-----------------------------------------------------------------------------+
```

---

## Catatan UX Tambahan
- **Empty States**: Jika "Antrean Validasi" kosong, tampilkan ilustrasi *Success/Checkmark* besar di tengah layar dengan tulisan: *"Kerja Bagus! Tidak ada data pengukuran yang menunggu validasi saat ini."*
- **Audit Trail UI**: Pada tab *Approved* dan *Rejected* (Riwayat Validasi), setiap baris harus dengan jelas menampilkan label *Pill/Badge* kecil: `Oleh: dr. Siti pada 14 Apr 10:30`.
- **Warna Status (Consistent Design System)**: 
  - Normal (Z-Score > -2): `Hijau (#10B981)`
  - Perlu Perhatian / Anomali: `Kuning / Oranye (#F59E0B)`
  - Berisiko (Stunting/Gizi Buruk <-2/-3): `Merah (#EF4444)`
