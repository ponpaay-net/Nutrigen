# Frontend-Backend Contract

Dokumen ini memetakan halaman frontend (Blade) yang telah di-*freeze* dengan spesifikasi Backend (Controller, Route, dan Variabel) yang wajib diimplementasikan. Mencakup **Portal Kader** dan **Portal Puskesmas**.

---

### 1. Dashboard
- **Route Name**: `dashboard`
- **URI**: `GET /`
- **Controller**: `DashboardController@index`
- **Blade File**: `kader.dashboard`
- **Data yang harus dikirim (to Blade)**:
  - `$totalBalita` (int)
  - `$sudahDiukur` (int)
  - `$belumDiukur` (int)
  - `$perluPerhatian` (int) - Balita status warning/danger
  - `$priorityChildren` (Collection) - Balita dengan gizi kurang/stunting
- **Notes**: Pastikan data di-filter berdasarkan `posyandu_id` kader yang login.

---

### 2. Daftar Balita
- **Route Name**: `balita.index`
- **URI**: `GET /daftar-balita`
- **Controller**: `BalitaController@index`
- **Blade File**: `kader.daftar-balita`
- **Data yang harus dikirim (to Blade)**:
  - `$balitas` (Collection) - Tiap item butuh key: `id`, `name`, `age`, `mother`, `nik`, `last_measure`, `status`, `status_type` (danger|warning|success).
- **Notes**: Frontend sudah membuat logika *collection filter* (memisahkan antrean vs prioritas). Backend cukup passing 1 variabel `$balitas`.

---

### 3. Tambah Balita (Create)
- **Route Name**: `balita.create`
- **URI**: `GET /daftar-balita-baru`
- **Controller**: `BalitaController@create`
- **Blade File**: `kader.daftar-balita-baru`
- **Data yang harus dikirim (to Blade)**: Tidak ada (kosong).
- **Submit Action**: `POST` ke `route('balita.store')`
- **Data yang dikirim (from Form)**: `name`, `nik`, `birth_date`, `gender`, `birth_weight`, `birth_height`, `mother_name`, `mother_nik`, `mother_phone`, `address`, `address_sub`.

---

### 4. Edit Balita (Update)
- **Route Name**: `balita.edit`
- **URI**: `GET /edit-balita/{id}` (Ubah placeholder di `web.php`)
- **Controller**: `BalitaController@edit`
- **Blade File**: `kader.daftar-balita-baru` (Reuses create form)
- **Data yang harus dikirim (to Blade)**:
  - `$isEdit` (boolean) = true
  - `$balita` (Model)
- **Submit Action**: `POST` dengan `@method('PUT')` ke `route('balita.update', $id)`

---

### 5. Profil Balita & Input Pengukuran
- **Route Name**: `balita.show`
- **URI**: `GET /profil-balita/{id}`
- **Controller**: `BalitaController@show`
- **Blade File**: `kader.profil-balita`
- **Data yang harus dikirim (to Blade)**:
  - `$balitaId`, `$childName`, `$gender`, `$age`, `$nik`, `$motherName`, `$motherPhone`, `$posyanduName`, `$address`, `$addressSub`, `$status`, `$status_type`
  - `$measurements` (Collection) - Untuk timeline dan grafik. Tiap item butuh key: `date`, `weight`, `weight_trend`, `height`, `head_circ`, `status`, `status_type`.
  - `$latestMeasure` (Array/Model) - Data pengukuran bulan terakhir.
- **Input Pengukuran Modal (Submit Action)**:
  - `POST` ke `route('pengukuran.store', $balitaId)`
  - **Data yang dikirim**: `weight` (float), `height` (float), `head_circ` (float), `jadwal_id` (int), `catatan` (text).

---

### 6. Jadwal Posyandu (Daftar)
- **Route Name**: `jadwal.index`
- **URI**: `GET /jadwal`
- **Controller**: `JadwalController@index`
- **Blade File**: `kader.jadwal`
- **Data yang harus dikirim (to Blade)**:
  - `$jadwals` (Collection)

---

### 7. Detail Jadwal
- **Route Name**: `jadwal.show`
- **URI**: `GET /detail-jadwal/{id}`
- **Controller**: `JadwalController@show`
- **Blade File**: `kader.detail-jadwal`
- **Data yang harus dikirim (to Blade)**:
  - `$jadwalJudul`, `$tanggal`, `$waktu`, `$lokasi`, `$kecamatan`, `$status`, `$statusType` (today|done|upcoming), `$catatan`, `$petugas`.
  - `$balitaList` (Collection) - List balita yang dijadwalkan hadir.

---

### 8. Tambah Jadwal
- **Route Name**: `jadwal.create`
- **URI**: `GET /tambah-jadwal`
- **Controller**: `JadwalController@create`
- **Blade File**: `kader.tambah-jadwal`
- **Data yang harus dikirim (to Blade)**:
  - `$nearestPosyandu` (string), `$nearestTime` (string) - Untuk panel info.
- **Submit Action**: `POST` ke `route('jadwal.store')`
- **Data yang dikirim**: `lokasi`, `tanggal`, `waktu_mulai`, `waktu_selesai`, `catatan`. (Input `posyandu_id` di-handle controller via Auth).

---

### 9. Laporan
- **Route Name**: `laporan.index`
- **URI**: `GET /laporan`
- **Controller**: `LaporanController@index`
- **Blade File**: `kader.laporan`
- **Data yang harus dikirim (to Blade)**:
  - Berdasarkan query params `?posyandu_id=X&periode=Y-m`
  - `$posyanduAktif`, `$periode`, `$totalBalita`, `$sudahDiukur`, `$belumDiukur`, `$perluPerhatian`, `$berisiko`, `$persentase`, `$dataKosong` (bool).
- **Submit Generate (Tombol Generate PDF)**:
  - Frontend freeze mengatur form filter sebagai GET. Tombol Generate harus di-binding via AJAX/POST action ke `route('laporan.generate')` saat integrasi.

---

### 10. Profil Kader
- **Route Name**: `kader.profil`
- **URI**: `GET /profil-kader`
- **Controller**: `KaderController@profil`
- **Blade File**: `kader.profil-kader`
- **Data yang harus dikirim (to Blade)**:
  - `$kaderName`, `$role`, `$email`, `$phone`, `$status`, `$avatarUrl`, `$posyanduName`, `$desa`, `$puskesmas`, `$kecamatan`.
- **Logout Action**: Menggunakan `POST` ke `route('logout')`. (Hapus route placeholder GET logout di `web.php`).

---

## BAGIAN II: PORTAL PUSKESMAS (FRONTEND FREEZE)

### 11. Dashboard Puskesmas
- **Route Name**: `puskesmas.dashboard`
- **URI**: `GET /puskesmas`
- **Controller**: `PuskesmasDashboardController@index`
- **Blade File**: `puskesmas.dashboard`
- **Data yang harus dikirim (to Blade)**:
  - `$stats`, `$posyandus`

### 12. Antrean Validasi
- **Route Name**: `puskesmas.validasi`
- **URI**: `GET /puskesmas/validasi`
- **Controller**: `PuskesmasValidasiController@index`
- **Blade File**: `puskesmas.validasi`
- **Data yang harus dikirim (to Blade)**:
  - `$children` (Eager load `posyandu`, `orang_tua`, `pengukurans` limit terakhir beserta z-scorenya).
  - `$filters` (tab, posyandu_id).
- **Submit Action (Process Validasi)**: `POST` ke `route('puskesmas.validasi.process', $id)` (Action Approve/Reject).

### 13. Data Balita (Direktori)
- **Route Name**: `puskesmas.balita`
- **URI**: `GET /puskesmas/balita`
- **Controller**: `PuskesmasBalitaController@index`
- **Blade File**: `puskesmas.balita`
- **Data yang harus dikirim (to Blade)**:
  - `$children`, `$filters`

### 14. Posyandu & Kader
- **Route Name**: `puskesmas.posyandu`
- **URI**: `GET /puskesmas/posyandu`
- **Controller**: `PuskesmasPosyanduController@index`
- **Blade File**: `puskesmas.posyandu`
- **Data yang harus dikirim (to Blade)**:
  - `$posyandus` (Collection list sidebar).
  - `$selectedPosyandu` (Detail posyandu di panel utama, eager load `kaders`).
  - `$filters`

### 15. Laporan Evaluasi
- **Route Name**: `puskesmas.laporan`
- **URI**: `GET /puskesmas/laporan`
- **Controller**: `PuskesmasLaporanController@index`
- **Blade File**: `puskesmas.laporan`
- **Data yang harus dikirim (to Blade)**:
  - `$stats`, `$reports` (agregat per posyandu), `$distribution`, `$trends`, `$filters`.

### 16. Pengaturan
- **Route Name**: `puskesmas.pengaturan`
- **URI**: `GET /puskesmas/pengaturan`
- **Controller**: `PuskesmasPengaturanController@index`
- **Blade File**: `puskesmas.pengaturan`
- **Data yang harus dikirim (to Blade)**:
  - `$puskesmas`, `$user`
