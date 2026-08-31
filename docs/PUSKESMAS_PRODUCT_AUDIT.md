# Portal Puskesmas: Product & Information Architecture Audit

## 1. Executive Summary
Berdasarkan mockup dan dokumen struktur database `NutriGen`, Portal Puskesmas saat ini mengalami *cognitive overload* (terlalu banyak informasi pada satu layar) dan pencampuran fungsionalitas antara operasional (Daily Tasks) dan analitikal (Monthly Tasks). 

Peran utama Puskesmas dalam arsitektur NutriGen **bukanlah sekadar melihat laporan**, melainkan sebagai **Quality Control (Validator)** atas data yang diinput Kader, serta penentu langkah medis (Intervensi) bagi balita berisiko. Oleh karena itu, Portal Puskesmas harus diubah dari konsep *Reporting Dashboard* menjadi *Operational Command Center* yang berbasis tindakan (*Action-Driven*).

---

## 2. Product Audit
Secara konseptual, produk sudah mengakomodasi kebutuhan bisnis (Validasi Z-Score, Monitoring, Anomali). Namun, *mental model* dari mockup belum selaras dengan beban kerja Ahli Gizi di lapangan. 

Ahli Gizi Puskesmas sangat sibuk. Mereka membutuhkan sistem yang menjawab pertanyaan: **"Apa yang harus saya selesaikan hari ini?"** sebelum menjawab **"Bagaimana tren gizi kecamatan bulan ini?"**

## 3. Workflow Audit
- **Apakah workflow efisien?** Belum efisien. Terdapat pemisahan antara "Validasi Data" dan "Anomali Data". Padahal, data anomali pada dasarnya adalah data pengukuran yang **membutuhkan validasi khusus**.
- **Apakah ada workflow yang berulang?** Ya. Adanya interaksi langsung pada tabel di Dashboard ("Validasi Tertunda" dan "Balita Berisiko Prioritas") membuat fungsi halaman Dashboard tumpang tindih dengan halaman menu spesifiknya.
- **Workflow Validasi yang Hilang**: Validasi Z-Score tidak bisa dilakukan hanya dengan melihat satu baris data (BB/TB bulan ini). Ahli Gizi wajib melihat tren/kurva anak tersebut di bulan-bulan sebelumnya. Mockup belum menunjukkan adanya mekanisme ini secara mulus (contoh: *expandable row* atau *side drawer* saat mereview).

## 4. Information Architecture Audit
- **Apakah struktur Portal Puskesmas sudah mendukung backend Laravel dengan Single Database?** Ya. Pendekatan Single Database sangat tepat. Puskesmas hanya perlu menggunakan Global Scope di Laravel (contoh: `whereHas('posyandu', function($q) use($puskesmas_id) { ... })`) untuk mengisolasi data tanpa perlu memisahkan database fisik.
- **Apakah ada halaman yang sebaiknya digabung?** 
  - `Grafik & Analitik` dihapus dan dilebur sepenuhnya ke dalam menu `Laporan`. Halaman Laporan sebaiknya memiliki *tabs*: **Rekap**, **Grafik**, **Trend**, dan **Export**.
  - `Validasi Data` dan `Anomali Data` wajib digabung menjadi `Antrean Validasi`. Menu khusus "Anomali" ditiadakan dan diubah menjadi sekadar tab atau filter di dalam antrean.
- **Apakah ada halaman yang sebaiknya dipisah?** 
  - Tidak ada, justru struktur saat ini perlu dirampingkan.

## 5. Dashboard Audit
- **Apakah Dashboard sudah menjadi command center?** Belum, dashboard saat ini masih berupa *Reporting Center*.
- **Apakah Dashboard terlalu ramai?** Sangat ramai. Terdapat 12 widget/elemen berbeda dalam satu layar yang memecah konsentrasi.
- **Widget apa yang sebaiknya dihapus dari Dashboard?**
  Semua grafik selain pie/donut chart gizi harus dipindahkan ke menu Laporan.
  1. Trend Status Gizi 6 Bulan (Line Chart) -> Pindahkan ke tab Trend di Laporan
  2. Sebaran Status Gizi per Posyandu (Stacked Bar) -> Pindahkan ke tab Grafik di Laporan
  3. Performa Posyandu (Bar Chart) -> Pindahkan ke Laporan
  4. Cakupan Data Bulanan -> Pindahkan ke tab Rekap di Laporan
- **Widget apa yang dipertahankan/ditambahkan?**
  1. **Visualisasi Utama (High-Value Visualization)**: Pertahankan "Distribusi Status Gizi (Donut Chart)" untuk memberi gambaran cepat yang relevan.
  2. **Task Inbox Summary**: Fokus angka besar pada *Actionable Items* ("Ada 46 Pengukuran Menunggu Validasi").
  3. **Shortcut Call-to-Action**: Tombol besar "Mulai Validasi Data".

## 6. Sidebar Audit
- **Apakah sidebar sudah mengikuti urutan kerja Ahli Gizi?** Belum. Urutan sidebar harus mencerminkan prioritas dari *Daily Task* ke *Monthly Task*.
- **Susunan di Mockup**: Dashboard -> Validasi -> Anomali -> Balita -> Posyandu -> Laporan -> Grafik -> Intervensi -> Pengaturan.
- **Rekomendasi Susunan Baru (Workflow-based)**:
  1. Dashboard
  2. Antrean Validasi (Tugas Harian Utama & Riwayat Keputusan)
  3. Direktori Balita (Pencarian & Rekam Medis)
  4. Posyandu & Kader (Manajemen Wilayah)
  5. Laporan (Rekap, Grafik, Trend, Export)
  6. Pengaturan

## 7. MVP Analysis
- **Halaman apa yang wajib untuk MVP?** 
  Dashboard (simplified action-driven), Antrean Validasi (termasuk Riwayat), Direktori Balita, Direktori Posyandu, dan Laporan.
- **Halaman apa yang bisa dipindahkan ke Phase 2?** 
  - **Menu Intervensi**: Karena belum ada struktur tabel yang matang untuk sistem rujukan (Ticketing/Intervensi), untuk MVP cukup gunakan fitur `catatan` saat melakukan validasi.
  - **Anomali Engine Complex**: MVP bisa menggunakan flagging threshold sederhana, tidak perlu algoritma deteksi anomali yang rumit.

---

## 8. Critical Issues
1. **Pemisahan Validasi dan Anomali**: Berisiko fatal secara *UX* dan logika database. Jika sebuah data muncul di `Anomali` lalu ditolak, apakah ia masih menggantung di `Validasi`? Keduanya merujuk pada `status_validasi = 'pending'` di database. Keduanya HARUS disatukan dalam satu UI *Task Inbox*.
2. **Ketiadaan Konteks dalam Validasi**: Ahli gizi berpotensi salah menyetujui data (False Positive) karena UI mockup tidak menampilkan *historical data* (KMS bulan sebelumnya) di layar validasi.

## 9. Medium Issues
1. **Redundansi Reporting**: Menu `Grafik & Analitik` dan `Laporan` akan membuat Backend Developer bekerja dua kali untuk menyajikan data yang esensinya sama. Harus digabung ke dalam satu halaman `Laporan` berbasis tabs.
2. **Dashboard Clutter**: *Load time* dashboard akan lambat karena mengeksekusi terlalu banyak query agregat (Count, Group By Z-Score, dsb) secara bersamaan di satu halaman.

## 10. Low Issues
1. **Menu Intervensi yang Ambigu**: Tanpa kejelasan workflow bisnis rujukan, menu Intervensi kemungkinan hanya akan berisi tabel kosong atau redundan dengan tabel Balita Berisiko. Sebaiknya menu ini disembunyikan untuk MVP.

---

## 11. Final Information Architecture
1. **Dashboard** (Action-driven Task Overview + Donut Chart Distribusi Gizi)
2. **Antrean Validasi** (Dengan Tabs Utama: *Semua Pending*, *Anomali*, *Berisiko*, dan Tabs Sekunder Riwayat: *Approved*, *Rejected*)
3. **Data Balita** (Mendukung: *Search*, *Filter*, *Child Profile*, *Growth History*, *Timeline*, *KMS Graph*)
4. **Data Posyandu & Kader** (Pemantauan faskes tingkat bawah)
5. **Laporan** (Dengan Tabs: *Rekap*, *Grafik*, *Trend*, *Export*)
6. **Pengaturan** (Profil Puskesmas)

## 12. Validation Detail Workspace & Final User Flow
Proses validasi tidak boleh berpindah halaman (*No Navigation/Reload*). Sangat disarankan menggunakan pola **Split View** atau **Side Drawer**. 
Ketika Ahli Gizi memilih satu anak dari antrean validasi, *Workspace* harus memuat informasi berikut secara instan:
- **Identitas Balita** (Nama, Usia, Posyandu, Orang Tua)
- **Hasil Pengukuran Terbaru** (BB, TB, Lingkar Kepala)
- **Growth Chart (Kurva KMS Digital)**
- **Timeline / Riwayat Pengukuran Sebelumnya**
- **Action Buttons**: Tombol besar *Approve* dan *Reject*.
- **Notes Field**: Kolom input wajib untuk catatan/alasan medis.

**Alur Kerja**:
1. Ahli Gizi mendarat di Dashboard -> Klik tombol CTA menuju Antrean Validasi.
2. Di Antrean Validasi, klik satu baris data (misal dari tab 'Anomali').
3. Muncul *Side Drawer* atau *Split View* untuk Validation Workspace.
4. Ahli Gizi meninjau kurva KMS, timeline, dan membandingkan ukur bulan lalu.
5. Klik **Approve** atau **Reject** (dengan catatan rujukan).
6. Menampilkan indikator *Saving...*, diikuti *Success Toast/Message*, lalu sistem secara otomatis memuat (auto-load) data balita berikutnya pada antrean tanpa perlu menutup *Drawer*.

## 13. Backend Audit Trail Recommendations
Untuk mendukung proses QC dan rekam jejak medis, skema tabel di backend untuk fitur validasi harus mencatat *Audit Trail* yang dapat dipertanggungjawabkan (auditable). Sangat direkomendasikan memastikan keberadaan field berikut:
- `validated_by` (ID dari User/Ahli Gizi yang menekan tombol aksi)
- `validated_at` (Timestamp kapan validasi dilakukan)
- `rejected_reason` (Catatan wajib jika pengukuran ditolak/dikoreksi)
- `updated_at` (Pencatatan timestamp untuk menjaga riwayat mutasi)

## 14. Halaman MVP
1. Dashboard (Action-oriented + Donut Chart saja)
2. Antrean Validasi (Menggunakan pola Split View / Drawer Validation Workspace, termasuk Tabs Riwayat)
3. Direktori Balita (Pencarian, Profil Anak, Timeline, dan Grafik KMS)
4. Direktori Posyandu
5. Laporan (Halaman tunggal dengan Tabs: Rekap, Grafik, Trend, Export)

## 15. Halaman Phase 2
1. Sistem Manajemen Intervensi (Ticketing tindak lanjut rujukan ke RSUD)
2. Advanced GIS Dashboard (Peta Sebaran Stunting berbasis peta/koordinat)

## 16. Rekomendasi Implementasi (Urutan Kerja Developer)
1. **Layout & Routing**: Buat struktur layout baru dengan sidebar yang sudah dirampingkan dan siapkan halaman Laporan ber-tab.
2. **Direktori Balita (Read-Only)**: Implementasikan fitur Search, Filter, Profile, Timeline, dan kurva KMS agar fungsi pembacaan data Single Database teruji.
3. **Antrean Validasi & Workspace**: Bangun UI validasi menggunakan komponen *Side Drawer/Split View*, lalu hubungkan dengan backend endpoint Approve/Reject (pastikan Audit Trail bekerja).
4. **Dashboard**: Buat Task Inbox Summary dan pertahankan hanya query untuk Donut Chart Distribusi Status Gizi.
5. **Laporan & Tabulasi**: Pindahkan dan eksekusi sisa visualisasi dari mockup awal (Trend, Rekap, dsb) ke dalam tab-tab di menu Laporan.
