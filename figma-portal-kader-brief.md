# FIGMA BRIEF — Portal Kader NutriGen (design prompt)

Paste ini sebagai instruksi order ke Figma AI (First Draft / Studio) atau ke designer.
Target: desain UI modern, profesional, clean-tapi-berwarna, mobile-first.

---

## 1. KONTEKS PRODUK

**NutriGen** = aplikasi web penanganan & pencegahan stunting untuk Posyandu, dipakai oleh 3 aktor:
Kader Posyandu, Puskesmas (validasi), dan Ibu (via link). Yang kamu desain di sini: **PORTAL KADER**.

**Kader** = relawan kesehatan masyarakat (kebanyakan ibu, usia dewasa–senior). Dia menimbang
balita, catat data gizi, jadwalkan sesi, lapor ke puskesmas. **Sering pakai HP** → prioritas mobile,
tapi tetap rapi di desktop.

**Bahasa UI: Indonesia.** Gaya: health-tech profesional (seperti aplikasi kesehatan nasional),
**clean tapi tidak pucat/abu-abu doang** — harus hidup & tepercaya.

---

## 2. BRAND & DESIGN SYSTEM (wajib dipakai konsisten)

### Warna
- **Brand primary — TEAL**: `#0d9488` (teal-600) untuk tombol utama/link/aksen; deep `#0f766e`
  (teal-700) untuk hover/gradient; tint `#ccfbf1`/`#f0fdfa` untuk chip/surface.
- **Netral (basis ~80% layar)**: bg `#f8fafc` (slate-50), teks `#0f172a`/`#475569`/`#94a3b8`,
  border `#e2e8f0`. Dilarang bg putih murni vs kartu putih (harus beda & ber-shadow).
- **Status (harmonis dengan teal)**: Sukses `emerald-600 #059669` + tint `#ecfdf5`;
  Warning `amber-600 #d97706` + tint `#fffbeb`; Bahaya `rose-600 #e11d48` + tint `#fff1f2`.
- Prinsip: satu jangkar teal, warna status hanya buat data penting (< 10% area). JANGAN pelangi / bikin mata sakit.

### Tipografi
- Display (greeting/judul): `font-semibold` (bukan font-black), ukuran 24–32px.
- Angka KPI: `font-bold`, `tabular-nums`.
- Body: 14–16px (`font-medium`), line-height nyaman.
- Label/utility: 12px, `uppercase tracking-wide`.
- **Scale wajib**: 12 / 14 / 16 / 20 / 24 / 32 — jangan campur ukuran random seperti 9/9.5/11.5/17/21.

### Spacing & bentuk
- Grid 8px: 4 / 8 / 12 / 16 / 20 / 24 / 32 / 40 / 48 / 64. Konsisten antar section.
- Kartu: `rounded-2xl` (16px). Hero: `rounded-3xl` (24px). Chip/pill: `rounded-full`.
- Shadow lembut berlapis: `shadow-sm` default, hero `shadow-lg`, kartu penting `shadow-md`.
- Border jelas (`border-slate-200`) + shadow → kartu terpisah dari background (anti-flat).

### Ikon
- **Phosphor Icons** (line/bold/fill), satu set konsisten. Ikon status harus akurat konteksnya
  (pemantauan gizi → ikon tumbuh/pulse `activity`, BUKAN ikon hati).

---

## 3. STRUKTUR APP (responsive)

**Desktop (≥1024px):** Sidebar kiri (logo + menu) + top navbar + konten.
**Mobile (<768px):** Sidebar jadi drawer/toggle, top navbar, + **bottom navigation bar**.
- Menu portalkader: Dashboard, Balita, Jadwal, Laporan, Profil.
- Touch target ≥ 44px. Focus state jelas (ring). Contras minimum WCAG AA.

---

## 4. INVENTARIS HALAMAN (desain semua)

1. **Dashboard** — lihat detail di bagian 5.
2. **Data Balita** (list): tabel/kartu daftar balita + search + filter status gizi + tombol "+ Balita Baru".
3. **Detail Balita** (profil): info anak + tabel riwayat pengukuran + kurva tumbuh (chart).
4. **Tambah/Edit Balita + Pengukuran**: form data anak (nama, NIK, tanggal lahir, jenis kelamin, ortu) + form ukur (BB, TB, umur).
5. **Jadwal Posyandu** (list + kartu detail + form tambah): tanggal, waktu, lokasi, judul, countdown H-X.
6. **Laporan**: pilih bulan → rekap tabel + ekspor PDF/Excel.
7. **Profil Kader**: foto/avatar + info + tombol Edit Profil & Keamanan (ganti password).

---

## 5. SPESIFIKASI HALAMAN DASHBOARD (paling penting — detail)

**Tujuan:** kader langsung tahu "sesi hari ini jalan apa" dalam sekali pandang di HP.

**Bagian wajib (urutan atas → bawah):**
1. **Hero greeting** — pancaran teal (gradient teal-700→cyan-800), teks putih:
   - Lokasi posyandu + tanggal (lengkap "Minggu, 30 Agustus 2026").
   - Sapaan berbasis waktu: "Selamat Pagi/Siang/Sore/Malam, [Nama Kader]" + badge peran.
   - **Panel "Capaian Sesi Ini"** (di kanan di desktop, bawah di mobile): progress bar %
     + "Selesai X dari Y balita" + Antrean Z.
   - Tombol aksi (konsisten): **primary** "Mulai Timbang" (solid) + **secondary** "Balita Baru" (outline/ghost).
2. **Alert "Perlu Revisi"** (kalau ada, warna amber): jumlah data perlu koreksi + tombol "Tinjau Catatan".
3. **4 Kartu KPI** (2×2 di mobile, 4 kolom di desktop), masing-masing dengan:
   - accent bar warna (teal/emerald/amber/rose) di atas kartu
   - label uppercase + angka besar (tabular) + icon chip berwarna di pojok
   - footer dipisah garis: tanggal / progress bar / link aksi
   1) Total Balita 2) Sudah Diukur (dengan progress %) 3) Belum Diukur (link "Lihat antrean") 4) Perlu Pantauan (link "Daftar pantau").
4. **Prioritas Pemantauan Gizi** (list, 2/3 isi): avatar inisial berwarna (kelas status) + nama anak +
   jenis kelamin + "Ibu [nama]" + umur + badge status ("Pantauan Gizi"/"Konfirmasi TB") + chevron.
5. **Agenda Posyandu** (kartu): kalender mini (bulan+tanggal) + judul + waktu + lokasi + badge countdown "X Hari Lagi".
6. **Rekap Laporan Bulanan** (kartu aksi): icon + judul + tombol "Buka".

**Visual:** hidup tapi rapi. Kartu punya shadow+border jelas, accent warna terukur, teks kontras.
Tanpa dekorasi yang nggak perlu (blob gradien kosong, kartun, pelangi).

---

## 6. ATURAN ANTI-"AI SLOP" (WAJIB)
- Dilarang: bg krem + serif kontras + accent terracotta; bg hitam + accent neon; layout "broadsheet"
  (radius 0, hairline); badge 01/02/03 tanpa fungsi; kartu ikon+judul+3 baris diulang polos;
  gradien blob dekoratif; copy generik ("Empowering..."); pelangi warna.
- Kalau ada elemen yang cuma "hiasan" tanpa fungsi → hapus.
- Animasi cukup / tidak berlebihan. Fokus: skannabilitas & konsistensi.

## 7. DELIVERABLE
Buat frame: 1 set mobile (375×812: Dashboard, Balita list, Detail Balita, Jadwal list, Laporan, Profil)
+ 1 set desktop (1440×900: Dashboard & Balita list minimal). Sertakan design-token (color/style)
di halaman terpisah. Cantumkan spacing grid 8px & type-scale pada style library.
