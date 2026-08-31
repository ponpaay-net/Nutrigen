# NutriGen - Project Context & Master Overview

Dokumen ini ditujukan sebagai **panduan utama (Master Overview)** bagi developer maupun AI Assistant lain agar dapat dengan cepat memahami konteks, bisnis proses, alur, dan status pengembangan dari project NutriGen.

## 1. Apa Itu NutriGen?
NutriGen adalah sistem informasi manajemen gizi balita terintegrasi yang dibangun menggunakan framework Laravel. Sistem ini tidak hanya berfungsing sebagai pelaporan, tetapi dirancang sebagai *Operational Command Center* (pusat kendali operasional) berbasis tindakan. Tujuannya adalah memastikan data pertumbuhan balita (Berat Badan, Tinggi Badan, Lingkar Kepala) yang dicatat di lapangan memiliki kualitas yang tervalidasi secara medis (Quality Control) sebelum menjadi rujukan penanganan stunting atau gizi buruk.

NutriGen terbagi menjadi **3 Portal Utama**:
1. **Portal Kader (Operasional Lapangan):** Tempat Kader Posyandu melakukan input data mentah hasil pengukuran.
2. **Portal Puskesmas (Quality Control & Validasi):** Tempat Ahli Gizi meninjau pengukuran yang masuk, menyetujuinya, atau menolaknya jika ada anomali.
3. **Portal Ibu (Monitoring Publik):** Halaman *read-only* bagi orang tua balita untuk melihat Grafik KMS Digital tanpa perlu membuat akun/login.

---

## 2. Siapa Saja Penggunanya (User Personas)?
*   **Kader Posyandu (User Level 1):** Masyarakat di tingkat desa/kelurahan yang bertugas menimbang dan mengukur balita secara berkala. Mereka bertugas memasukkan data, namun **tidak memiliki wewenang** untuk menentukan status akhir (normal/stunting).
*   **Ahli Gizi / Puskesmas (User Level 2):** Petugas medis profesional yang memonitor seluruh Posyandu di bawah wilayahnya. Merekalah yang memegang wewenang medis untuk melakukan validasi (*Approve/Reject*) dan memberikan rujukan (*Intervensi*).
*   **Ibu Balita (User Level 3 / End-User):** Orang tua yang ingin memantau perkembangan anaknya setiap bulan secara praktis melalui smartphone.
*   **Admin (User Level 4):** Superuser yang mengelola data master (Data Posyandu, Akun Puskesmas, Akun Kader).

---

## 3. Bagaimana Mekanisme & Alur Kerjanya (User Flow)?
Arsitektur NutriGen menggunakan sistem *Single Database* dengan *Global Scope* (Laravel) untuk membatasi akses antar faskes/wilayah. Alur kerja utamanya adalah sebagai berikut:

### A. Tahap 1: Input Pengukuran (Kader)
1. Kader login ke Portal Kader.
2. Membuka profil Balita (berdasarkan jadwal posyandu saat itu).
3. Melakukan input hasil ukur: Berat Badan (BB), Tinggi Badan (TB), dan Lingkar Kepala (LK).
4. **Sistem Backend (Z-Score System):** Saat data disubmit, sistem akan otomatis menghitung nilai Z-Score (status gizi) menggunakan standar WHO (di background).
5. Data masuk ke database dengan status **"Pending Validasi Puskesmas"**.

### B. Tahap 2: Validasi & Action (Puskesmas)
1. Ahli Gizi login ke Portal Puskesmas dan melihat metrik di **Dashboard Utama**.
2. Ahli Gizi membuka halaman **Antrean Validasi** (Task Inbox).
3. Melalui fitur *Split View / Side Drawer*, petugas membandingkan data ukur bulan ini dengan grafik kurva bulan-bulan sebelumnya (Historical Data).
4. **Action:** Jika wajar, klik **Approve** (status gizi menjadi final). Jika ada anomali ukuran/salah input, klik **Reject/Koreksi** dengan memberikan catatan.

### C. Tahap 3: Distribusi Hasil (Ibu)
1. Setelah data di-Approve oleh Puskesmas, sistem otomatis memicu **WhatsApp Gateway**.
2. Ibu menerima pesan WA yang berisi pengumuman singkat dan sebuah *Token Link* (contoh: `nutrigen.com/ibu/token-abc-123`).
3. Ibu mengklik link tersebut dan langsung diarahkan ke browser HP (Portal Ibu).
4. Ibu dapat memantau **KMS Digital** (berbasis Chart.js) milik anaknya tanpa harus melakukan proses pendaftaran, mengingat username, atau login.

---

## 4. Status Progress Saat Ini (What is Done)
Fase pengerjaan saat ini difokuskan pada penyiapan tampilan antar muka (Front-End) sebelum masuk ke fase integrasi logika sistem (Back-End).

**Yang Sudah Selesai (Frontend Status: FREEZE & READY):**
*   Keseluruhan UI/UX untuk Portal Kader dan Portal Puskesmas sudah di-slicing dan distandardisasi.
*   Penyempurnaan arsitektur UI Puskesmas (memusatkan validasi di "Antrean Validasi" dengan side-panel).
*   Perbaikan logic interaksi UI: Filter sinkron pada left panel dan daftar balita, pembersihan desain (menghilangkan tombol redundan), pengaktifan fungsionalitas visual seperti tombol tambah kader/posyandu, dan riwayat pengukuran anak.
*   Seluruh *Mockup* Frontend siap untuk disambungkan ke database nyata.

---

## 5. Target yang Belum Dikerjakan (Next Backend Tasks / Roadmap)
Tugas selanjutnya adalah memfungsikan Frontend tersebut ke dalam arsitektur Laravel secara utuh (*Wiring*). 

**Yang Belum Dikerjakan (Target Backend):**
1.  **Fondasi Data:** Pembuatan Migration Database (tabel relasi balita, pengukuran, wilayah, users), Setup Model, Seeder, dan Authentication Role-based (Admin, Puskesmas, Kader).
2.  **Service Layer Z-Score:** Pembuatan fungsi algoritma perhitungan Gizi WHO (BB/U, TB/U, BB/TB) pada Backend Controller agar otomatis mengkalkulasi hasil input kader.
3.  **Logika CRUD Portal Kader:** Mengganti data dummy (hardcoded blade) dengan fungsi Create, Read, Update, Delete balita dan pengukuran sungguhan dari database (Eager Loading).
4.  **Logika Validasi Portal Puskesmas:** Pembuatan backend logic untuk menampilkan List "Pending Validasi", fitur Audit Trail penyetujuan (Approve/Reject data), dan penarikan data metrik asli untuk Dashboard Puskesmas.
5.  **WhatsApp Gateway & Portal Ibu:** Integrasi API WA (seperti Fonnte) untuk *blast message*, serta pembuatan mekanisme *URL Middleware* khusus (Token System) agar Ibu dapat melihat KMS Digital (berbasis data real Chart.js) tanpa authentikasi.
6.  **Reporting:** Pembuatan fungsionalitas filter tanggal dan integrasi library export PDF (contoh: dompdf) untuk kebutuhan cetak laporan rekap puskesmas bulanan.
