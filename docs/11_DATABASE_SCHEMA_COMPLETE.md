# Database Schema Blueprint: NutriGen

**Dokumen Analisis & Desain Database (Software Design Document - SDD)**
Target Pembaca: Backend Developer Laravel

---

## 1. Database Principle

**Pendekatan: Single Database Architecture**

Project NutriGen menggunakan pendekatan **SATU DATABASE** tunggal untuk seluruh portal (Admin, Kader, Puskesmas, dan Ibu). Kami **TIDAK** memisahkan database antar portal.

**Alasan Teknis & Justifikasi:**
1. **Single Source of Truth (SSOT)**: Data balita, pengukuran, dan status gizi adalah entitas medis yang harus konsisten. Jika dipisah, risiko inkonsistensi data (Eventual Consistency) akan sangat tinggi dan sinkronisasi antar database akan menambah *overhead* server.
2. **Relational Integrity**: Dengan satu database, kita dapat menggunakan *Foreign Key Constraints* secara native di level RDBMS. Misalnya, jika data Posyandu dihapus, data Jadwal terkait bisa dikelola (cascade/restrict) dengan aman tanpa harus menulis cron job sinkronisasi.
3. **Efisiensi Skala MVP**: Arsitektur microservices/multi-database merupakan *over-engineering* untuk tahap MVP. Satu database (MySQL/PostgreSQL) sudah sangat mumpuni untuk menangani jutaan baris data, asalkan struktur dan indeksnya dioptimalkan.
4. **Isolasi Logis (Bukan Fisik)**: Pemisahan data antar pengguna tidak dilakukan dengan memisah tabel/database, melainkan menggunakan pola isolasi *Multi-tenancy logic* melalui **Role, Permission, Middleware, dan Global Scopes (Policy)** di Laravel.

**Konsep Role & Akses:**
- **Ibu**: Tidak login. Mengakses portal `Read-Only` menggunakan URL unik bertoken via WhatsApp. Token tersebut me-resolve ke `ibu_id`.
- **Kader**: Memiliki akun `users` yang terelasi ke tabel `kaders`. Tabel `kaders` memiliki `posyandu_id`. Kader hanya bisa Query data balita dengan `posyandu_id` yang sama.
- **Puskesmas**: Memiliki akun `users` tingkat regional. Membaca data seluruh `posyandus` yang memiliki `puskesmas_id` miliknya.
- **Admin**: *God-mode*, akses penuh.

---

## 2. Table Definitions

### TABLE `roles`

#### Purpose
Menyimpan daftar role (hak akses tingkat tinggi) yang tersedia di sistem untuk mendukung Spatie Permission atau RBAC manual. Diakses oleh sistem autentikasi dan middleware.

#### Columns
| Field | Type | Nullable | Default | Unique | Index | FK | Keterangan |
| :--- | :--- | :---: | :--- | :---: | :---: | :---: | :--- |
| `id` | BIGINT (UNSIGNED) | No | Auto Increment | Yes | Yes | - | Primary Key |
| `name` | VARCHAR(255) | No | - | Yes | - | - | Contoh: 'admin', 'kader', 'puskesmas' |
| `guard_name` | VARCHAR(255) | No | 'web' | - | - | - | Standard Laravel guard |
| `created_at` | TIMESTAMP | Yes | NULL | - | - | - | - |
| `updated_at` | TIMESTAMP | Yes | NULL | - | - | - | - |

#### Constraints
- **PRIMARY KEY**: `id`
- **UNIQUE**: `name`, `guard_name`

#### Relationships
- `Role` `hasMany` `User` (Jika menggunakan tabel pivot `model_has_roles`, mengikuti Spatie)

#### Who Can Access
- **Admin**: CRUD
- **Kader**: No Access
- **Puskesmas**: No Access
- **Ibu**: No Access

#### Created By
Admin (via Seeder)

#### Updated By
Admin

#### Deleted By
Hard Delete (Data statis sistem, tidak boleh dihapus sembarangan).

#### Seeder Recommendation
**Wajib**. Harus di-seed pada saat instalasi sistem.

#### Validation Rules
- `name`: `required|string|unique:roles,name`

#### Business Rules
- Nama role tidak boleh diubah setelah sistem berjalan karena terkait dengan hardcoded middleware `role:kader` dsb.

#### Example Data
| id | name | guard_name |
|---|---|---|
| 1 | admin | web |
| 2 | puskesmas | web |
| 3 | kader | web |

---

### TABLE `users`

#### Purpose
Tabel inti autentikasi Laravel. Hanya digunakan oleh entitas yang bisa *Login* ke dashboard (Admin, Kader, Puskesmas). **Ibu tidak masuk ke tabel ini.**

#### Columns
| Field | Type | Nullable | Default | Unique | Index | FK | Keterangan |
| :--- | :--- | :---: | :--- | :---: | :---: | :---: | :--- |
| `id` | BIGINT (UNSIGNED) | No | Auto Increment | Yes | Yes | - | Primary Key |
| `name` | VARCHAR(255) | No | - | - | - | - | Nama lengkap user |
| `email` | VARCHAR(255) | No | - | Yes | Yes | - | Email untuk login |
| `password` | VARCHAR(255) | No | - | - | - | - | Hashed (Bcrypt/Argon2) |
| `role` | ENUM | No | - | - | Yes | - | 'admin', 'kader', 'puskesmas' |
| `remember_token` | VARCHAR(100) | Yes | NULL | - | - | - | Untuk fitur "Remember Me" |
| `created_at` | TIMESTAMP | Yes | NULL | - | - | - | - |
| `updated_at` | TIMESTAMP | Yes | NULL | - | - | - | - |
| `deleted_at` | TIMESTAMP | Yes | NULL | - | - | - | Soft delete aktif |

#### Constraints
- **PRIMARY KEY**: `id`
- **UNIQUE**: `email`
- **INDEX**: `role`

#### Relationships
- `User` `hasOne` `Kader`
- `User` `hasMany` `Validasi` (Jika user adalah petugas puskesmas)
- `User` `hasMany` `ActivityLog`

#### Who Can Access
- **Admin**: CRUD
- **Kader**: Read (Self), Update (Self Profile)
- **Puskesmas**: Read (Self), Update (Self Profile)
- **Ibu**: No Access

#### Created By
Admin / Sistem

#### Updated By
Admin, Self (Pemilik akun)

#### Deleted By
**Soft Delete**. Akun tidak boleh dihapus permanen agar *Audit Trail* / Log validasi masa lalu tidak terhapus (Orphan data).

#### Seeder Recommendation
**Wajib**. Minimal 1 Akun Admin, 1 Akun Puskesmas dummy, 1 Akun Kader dummy untuk testing.

#### Validation Rules
- `name`: `required|string|max:255`
- `email`: `required|email|unique:users,email`
- `password`: `required|min:8`
- `role`: `required|in:admin,kader,puskesmas`

#### Business Rules
- Email tidak boleh sama.
- Password wajib di-hash menggunakan `Hash::make()`.

#### Example Data
| id | name | email | role |
|---|---|---|---|
| 1 | Super Admin | admin@nutrigen.com | admin |
| 2 | Dr. Anita (Puskesmas) | pkm.melati@gmail.com | puskesmas |
| 3 | Ibu Ratna (Kader) | ratna@posyandu.com | kader |

---

### TABLE `puskesmas`

#### Purpose
Menyimpan entitas master fasilitas kesehatan tingkat kecamatan/wilayah. Mengelompokkan banyak Posyandu di bawah satu pengawasan.

#### Columns
| Field | Type | Nullable | Default | Unique | Index | FK | Keterangan |
| :--- | :--- | :---: | :--- | :---: | :---: | :---: | :--- |
| `id` | BIGINT (UNSIGNED) | No | Auto Increment | Yes | Yes | - | Primary Key |
| `kode_faskes` | VARCHAR(50) | No | - | Yes | Yes | - | Kode resmi Kemenkes |
| `nama` | VARCHAR(255) | No | - | - | - | - | Nama puskesmas |
| `alamat` | TEXT | No | - | - | - | - | Alamat lengkap |
| `kecamatan` | VARCHAR(100) | No | - | - | Yes | - | Wilayah kerja |
| `kepala_puskesmas`| VARCHAR(255) | Yes| NULL | - | - | - | Nama penanggung jawab |
| `created_at` | TIMESTAMP | Yes | NULL | - | - | - | - |
| `updated_at` | TIMESTAMP | Yes | NULL | - | - | - | - |
| `deleted_at` | TIMESTAMP | Yes | NULL | - | - | - | Soft delete |

#### Constraints
- **PRIMARY KEY**: `id`
- **UNIQUE**: `kode_faskes`

#### Relationships
- `Puskesmas` `hasMany` `Posyandu`

#### Who Can Access
- **Admin**: CRUD
- **Puskesmas**: Read, Update (Profil Instansi)
- **Kader**: Read
- **Ibu**: No Access

#### Created By
Admin

#### Updated By
Admin, Puskesmas

#### Deleted By
**Soft Delete**. Agar relasi historis ke posyandu tidak hilang.

#### Seeder Recommendation
**Wajib** untuk testing wilayah.

#### Validation Rules
- `kode_faskes`: `required|string|unique:puskesmas,kode_faskes`
- `nama`: `required|string|max:255`

#### Business Rules
- Kode faskes harus unik secara nasional.

#### Example Data
| id | kode_faskes | nama | kecamatan |
|---|---|---|---|
| 1 | P3201010101 | Puskesmas Darul Imarah | Darul Imarah |

---

### TABLE `posyandus`

#### Purpose
Menyimpan entitas Posyandu. Menjadi titik pusat (hub) dari Kader, Balita, dan Jadwal. Ini adalah batas akses mutlak bagi Kader.

#### Columns
| Field | Type | Nullable | Default | Unique | Index | FK | Keterangan |
| :--- | :--- | :---: | :--- | :---: | :---: | :---: | :--- |
| `id` | BIGINT (UNSIGNED) | No | Auto Increment | Yes | Yes | - | Primary Key |
| `puskesmas_id` | BIGINT (UNSIGNED) | No | - | - | Yes | Yes| Merujuk ke `puskesmas.id` |
| `nama` | VARCHAR(255) | No | - | - | - | - | Misal: 'Melati 1' |
| `alamat` | TEXT | No | - | - | - | - | Alamat posyandu |
| `desa` | VARCHAR(100) | No | - | - | Yes | - | Kelurahan/Desa |
| `created_at` | TIMESTAMP | Yes | NULL | - | - | - | - |
| `updated_at` | TIMESTAMP | Yes | NULL | - | - | - | - |
| `deleted_at` | TIMESTAMP | Yes | NULL | - | - | - | Soft delete |

#### Constraints
- **PRIMARY KEY**: `id`
- **FOREIGN KEY**: `puskesmas_id` merujuk ke `puskesmas.id` (ON DELETE RESTRICT)

#### Relationships
- `Posyandu` `belongsTo` `Puskesmas`
- `Posyandu` `hasMany` `Kader`
- `Posyandu` `hasMany` `Balita`
- `Posyandu` `hasMany` `Jadwal`
- `Posyandu` `hasMany` `Laporan`

#### Who Can Access
- **Admin**: CRUD
- **Puskesmas**: Read (hanya posyandu di bawah `puskesmas_id` miliknya)
- **Kader**: Read (hanya posyandunya sendiri)
- **Ibu**: No Access

#### Created By
Admin

#### Updated By
Admin, Puskesmas

#### Deleted By
**Soft Delete**. Mencegah `balita` dan `kader` kehilangan referensi posyandunya.

#### Seeder Recommendation
**Wajib**. Buat 2-3 posyandu per puskesmas.

#### Validation Rules
- `puskesmas_id`: `required|exists:puskesmas,id`
- `nama`: `required|string|max:255`

#### Business Rules
- Posyandu tidak bisa dihapus permanen jika masih ada Balita aktif di dalamnya.

#### Example Data
| id | puskesmas_id | nama | desa |
|---|---|---|---|
| 1 | 1 | Posyandu Melati 1 | Lampeuneurut |
| 2 | 1 | Posyandu Mawar 2 | Lamreung |

---

### TABLE `kaders`

#### Purpose
Menyimpan profil detail relawan Posyandu. Menghubungkan akun login (`users`) dengan batas wilayah kerja (`posyandus`).

#### Columns
| Field | Type | Nullable | Default | Unique | Index | FK | Keterangan |
| :--- | :--- | :---: | :--- | :---: | :---: | :---: | :--- |
| `id` | BIGINT (UNSIGNED) | No | Auto Increment | Yes | Yes | - | Primary Key |
| `user_id` | BIGINT (UNSIGNED) | No | - | Yes | Yes | Yes| Akun login (`users.id`) |
| `posyandu_id` | BIGINT (UNSIGNED) | No | - | - | Yes | Yes| Tempat tugas |
| `nik` | VARCHAR(16) | No | - | Yes | Yes | - | NIK Kader |
| `no_hp` | VARCHAR(20) | Yes | NULL | - | - | - | Untuk kontak / WA |
| `foto_profil` | VARCHAR(255) | Yes | NULL | - | - | - | URL Path file |
| `created_at` | TIMESTAMP | Yes | NULL | - | - | - | - |
| `updated_at` | TIMESTAMP | Yes | NULL | - | - | - | - |
| `deleted_at` | TIMESTAMP | Yes | NULL | - | - | - | Soft delete |

#### Constraints
- **PRIMARY KEY**: `id`
- **UNIQUE**: `user_id`, `nik`
- **FOREIGN KEY**: `user_id` merujuk ke `users.id` (ON DELETE CASCADE)
- **FOREIGN KEY**: `posyandu_id` merujuk ke `posyandus.id` (ON DELETE RESTRICT)

#### Relationships
- `Kader` `belongsTo` `User`
- `Kader` `belongsTo` `Posyandu`
- `Kader` `hasMany` `Pengukuran` (Kader mana yang mencatat)

#### Who Can Access
- **Admin**: CRUD
- **Puskesmas**: Read
- **Kader**: Read (Semua teman sekadernya di posyandu sama), Update (Profil Sendiri)
- **Ibu**: No Access

#### Created By
Admin / Puskesmas

#### Updated By
Admin, Puskesmas, Self (Kader ybs)

#### Deleted By
**Soft Delete**. Jika kader pensiun, riwayat siapa yang menimbang balita harus tetap ada.

#### Seeder Recommendation
**Wajib**.

#### Validation Rules
- `user_id`: `required|exists:users,id|unique:kaders,user_id`
- `posyandu_id`: `required|exists:posyandus,id`
- `nik`: `required|digits:16|unique:kaders,nik`

#### Business Rules
- Satu User hanya boleh berelasi dengan 1 baris di tabel Kader (1:1).

#### Example Data
| id | user_id | posyandu_id | nik | no_hp |
|---|---|---|---|---|
| 1 | 3 | 1 | 11710123... | 08123456789 |

---

### TABLE `ibus`

#### Purpose
Menyimpan data wali/Ibu dari balita. Tabel ini berdiri sendiri dan **TIDAK TERHUBUNG** ke tabel `users`. Akses portal didapat menggunakan `access_token` yang dikirim via WhatsApp.

#### Columns
| Field | Type | Nullable | Default | Unique | Index | FK | Keterangan |
| :--- | :--- | :---: | :--- | :---: | :---: | :---: | :--- |
| `id` | BIGINT (UNSIGNED) | No | Auto Increment | Yes | Yes | - | Primary Key |
| `nik` | VARCHAR(16) | No | - | Yes | Yes | - | NIK Ibu |
| `nama` | VARCHAR(255) | No | - | - | - | - | Nama lengkap |
| `no_hp_wa` | VARCHAR(20) | No | - | - | Yes | - | Nomor WhatsApp aktif |
| `alamat` | TEXT | Yes | NULL | - | - | - | Alamat domisili |
| `access_token`| VARCHAR(64) | No | Random | Yes | Yes | - | Token unik untuk Loginless URL |
| `created_at` | TIMESTAMP | Yes | NULL | - | - | - | - |
| `updated_at` | TIMESTAMP | Yes | NULL | - | - | - | - |
| `deleted_at` | TIMESTAMP | Yes | NULL | - | - | - | Soft delete |

#### Constraints
- **PRIMARY KEY**: `id`
- **UNIQUE**: `nik`, `access_token`

#### Relationships
- `Ibu` `hasMany` `Balita`
- `Ibu` `hasMany` `Notification`

#### Who Can Access
- **Admin**: CRUD
- **Puskesmas**: Read, Update (Koreksi)
- **Kader**: Create, Read, Update (Hanya ibu di posyandunya)
- **Ibu**: Read (Hanya dirinya sendiri via Token)

#### Created By
Kader (Saat pendaftaran balita pertama kali)

#### Updated By
Kader, Puskesmas, Admin

#### Deleted By
**Soft Delete**.

#### Seeder Recommendation
Ya, buat beberapa data dummy untuk mengetes relasi Balita.

#### Validation Rules
- `no_hp_wa`: `required|numeric|min_digits:10`
- `access_token`: Harus digenerate secara otomatis (misal: `Str::random(40)`) via Observer/Mutator saat data Ibu dibuat.

#### Business Rules
- Nomor WhatsApp sangat krusial, pastikan menggunakan format internasional (62) agar API Gateway bisa langsung memproses.
- Access Token tidak boleh expired secara default, tetapi bisa di-reset jika HP ibu hilang.

#### Example Data
| id | nik | nama | no_hp_wa | access_token |
|---|---|---|---|---|
| 1 | 320101... | Siti Aminah | 628123456789 | a1b2c3d4e5f6... |

---

### TABLE `balitas`

#### Purpose
Master data anak/balita. Ini adalah entitas paling penting dalam sistem (Core Entity).

#### Columns
| Field | Type | Nullable | Default | Unique | Index | FK | Keterangan |
| :--- | :--- | :---: | :--- | :---: | :---: | :---: | :--- |
| `id` | BIGINT (UNSIGNED) | No | Auto Increment | Yes | Yes | - | Primary Key |
| `posyandu_id` | BIGINT (UNSIGNED) | No | - | - | Yes | Yes| Lokasi posyandu |
| `ibu_id` | BIGINT (UNSIGNED) | No | - | - | Yes | Yes| Wali balita |
| `nik` | VARCHAR(16) | Yes | NULL | Yes | Yes | - | Bisa Null jika belum punya NIK |
| `nama` | VARCHAR(255) | No | - | - | Yes | - | Nama lengkap balita |
| `tanggal_lahir`| DATE | No | - | - | Yes | - | Dasar perhitungan Z-Score (Umur) |
| `jenis_kelamin`| ENUM | No | - | - | Yes | - | 'L', 'P' (Penting untuk grafik WHO) |
| `berat_lahir` | FLOAT | Yes | NULL | - | - | - | Kg (Opsional tapi disarankan) |
| `tinggi_lahir`| FLOAT | Yes | NULL | - | - | - | cm |
| `created_at` | TIMESTAMP | Yes | NULL | - | - | - | - |
| `updated_at` | TIMESTAMP | Yes | NULL | - | - | - | - |
| `deleted_at` | TIMESTAMP | Yes | NULL | - | - | - | Soft delete |

#### Constraints
- **PRIMARY KEY**: `id`
- **UNIQUE**: `nik`
- **FOREIGN KEY**: `posyandu_id` (ON DELETE RESTRICT)
- **FOREIGN KEY**: `ibu_id` (ON DELETE RESTRICT)

#### Relationships
- `Balita` `belongsTo` `Posyandu`
- `Balita` `belongsTo` `Ibu`
- `Balita` `hasMany` `Pengukuran`

#### Who Can Access
- **Admin**: CRUD
- **Puskesmas**: Read, Update (Koreksi medis)
- **Kader**: Create, Read, Update (Dibatasi Policy `posyandu_id` kader == `posyandu_id` balita)
- **Ibu**: Read (Dibatasi Policy `ibu_id` balita == `ibu_id` dari Token WA)

#### Created By
Kader

#### Updated By
Kader, Puskesmas

#### Deleted By
**Soft Delete**. Sangat terlarang di-hard delete karena seluruh grafik KMS digital akan lenyap.

#### Seeder Recommendation
**Wajib**. Minimal 30 data dengan variasi umur dan status gizi.

#### Validation Rules
- `tanggal_lahir`: `required|date|before_or_equal:today`
- `jenis_kelamin`: `required|in:L,P`

#### Business Rules
- `jenis_kelamin` dan `tanggal_lahir` bersifat **IMMUTABLE** (Sulit diubah) bagi Kader setelah disimpan, karena mengubah data ini akan membatalkan seluruh grafik Z-Score masa lalu. Perubahan hanya bisa dilakukan oleh Puskesmas/Admin.

#### Example Data
| id | ibu_id | posyandu_id | nama | tanggal_lahir | jenis_kelamin |
|---|---|---|---|---|---|
| 1 | 1 | 1 | Aisyah Putri | 2024-05-10 | P |

---

### TABLE `pengukurans`

#### Purpose
Menyimpan riwayat transaksional timbang berat badan dan ukur tinggi badan tiap bulan. Semua Z-Score disimpan secara *snapshot* di sini.

#### Columns
| Field | Type | Nullable | Default | Unique | Index | FK | Keterangan |
| :--- | :--- | :---: | :--- | :---: | :---: | :---: | :--- |
| `id` | BIGINT (UNSIGNED) | No | Auto Increment | Yes | Yes | - | Primary Key |
| `balita_id` | BIGINT (UNSIGNED) | No | - | - | Yes | Yes| Balita yang diukur |
| `jadwal_id` | BIGINT (UNSIGNED) | No | - | - | Yes | Yes| Di bulan & kegiatan apa |
| `kader_id` | BIGINT (UNSIGNED) | No | - | - | Yes | Yes| Siapa yang input |
| `umur_bulan` | INT | No | - | - | - | - | Umur balita SAAT diukur |
| `berat_badan` | FLOAT | No | - | - | - | - | Kg (BB) |
| `tinggi_badan` | FLOAT | No | - | - | - | - | cm (TB / PB) |
| `lingkar_kepala`| FLOAT | Yes | NULL | - | - | - | cm |
| `z_score_bb_u` | FLOAT | No | - | - | Yes | - | Nilai Z-Score BB/U |
| `z_score_tb_u` | FLOAT | No | - | - | Yes | - | Nilai Z-Score TB/U |
| `z_score_bb_tb`| FLOAT | No | - | - | Yes | - | Nilai Z-Score BB/TB |
| `status_gizi` | VARCHAR(50) | No | - | - | Yes | - | Contoh: 'Normal', 'Stunting' |
| `status_validasi`| ENUM | No | 'pending' | - | Yes | - | 'pending', 'approved', 'rejected' |
| `catatan` | TEXT | Yes | NULL | - | - | - | Pesan Kader |
| `created_at` | TIMESTAMP | Yes | NULL | - | - | - | - |
| `updated_at` | TIMESTAMP | Yes | NULL | - | - | - | - |
| `deleted_at` | TIMESTAMP | Yes | NULL | - | - | - | Soft delete |

#### Constraints
- **PRIMARY KEY**: `id`
- **FOREIGN KEY**: `balita_id` (RESTRICT), `jadwal_id` (RESTRICT), `kader_id` (RESTRICT)
- **UNIQUE**: Gabungan `balita_id` dan `jadwal_id` (Satu balita hanya bisa diukur maksimal 1 kali dalam 1 jadwal kegiatan).

#### Relationships
- `Pengukuran` `belongsTo` `Balita`
- `Pengukuran` `belongsTo` `Jadwal`
- `Pengukuran` `belongsTo` `Kader`
- `Pengukuran` `hasOne` `Validasi`

#### Who Can Access
- **Admin**: CRUD
- **Puskesmas**: Read, Update (Koreksi angka salah timbang)
- **Kader**: Create, Read. (Hanya Update jika `status_validasi` == 'pending').
- **Ibu**: Read (Riwayat di KMS)

#### Created By
Kader

#### Updated By
Kader (saat masih pending), Puskesmas (overriding medis)

#### Deleted By
**Soft Delete**. 

#### Seeder Recommendation
**Wajib**. Buat kurva buatan untuk 5 balita (masing-masing 3-4 bulan riwayat ke belakang).

#### Validation Rules
- `berat_badan`: `required|numeric|min:1|max:30` (Mencegah typo kader input 100kg)
- `tinggi_badan`: `required|numeric|min:30|max:130`

#### Business Rules
- `z_score_*` dihitung oleh Backend (Service Layer), **BUKAN** dari inputan user frontend.
- `umur_bulan` disimpan secara *snapshot* agar perhitungan Z-Score masa lalu tidak berubah meskipun script diakses setahun kemudian.
- Selama `status_validasi` == `pending`, Ibu belum bisa melihat data bulan tersebut di portalnya.

#### Example Data
| balita_id | jadwal_id | berat_badan | tinggi_badan | z_score_bb_u | status_gizi | status_validasi |
|---|---|---|---|---|---|---|
| 1 | 1 | 10.5 | 82.3 | -0.4 | Normal | approved |

---

### TABLE `validasis`

#### Purpose
Menyimpan jejak audit (*Audit Trail*) persetujuan Puskesmas terhadap suatu data Pengukuran.

#### Columns
| Field | Type | Nullable | Default | Unique | Index | FK | Keterangan |
| :--- | :--- | :---: | :--- | :---: | :---: | :---: | :--- |
| `id` | BIGINT (UNSIGNED) | No | Auto Increment | Yes | Yes | - | Primary Key |
| `pengukuran_id`| BIGINT (UNSIGNED) | No | - | Yes | Yes | Yes| 1 to 1 relasi |
| `user_id` | BIGINT (UNSIGNED) | No | - | - | Yes | Yes| Akun Puskesmas yang validasi |
| `status` | ENUM | No | - | - | Yes | - | 'approved', 'rejected' |
| `catatan` | TEXT | Yes | NULL | - | - | - | Pesan rujukan / teguran medis |
| `created_at` | TIMESTAMP | Yes | NULL | - | - | - | Tanggal divaidasi |
| `updated_at` | TIMESTAMP | Yes | NULL | - | - | - | - |

#### Constraints
- **PRIMARY KEY**: `id`
- **FOREIGN KEY**: `pengukuran_id` (CASCADE), `user_id` (RESTRICT)
- **UNIQUE**: `pengukuran_id` (Setiap pengukuran hanya butuh 1 final validasi).

#### Relationships
- `Validasi` `belongsTo` `Pengukuran`
- `Validasi` `belongsTo` `User`

#### Who Can Access
- **Admin**: Read
- **Puskesmas**: Create, Read
- **Kader**: Read (Melihat catatan dari Puskesmas)
- **Ibu**: No Access

#### Created By
Puskesmas (Saat menekan tombol Approve/Reject)

#### Updated By
No one (Immutable Audit Trail)

#### Deleted By
Dihapus otomatis jika pengukuran dihapus (CASCADE).

#### Seeder Recommendation
Opsional.

#### Validation Rules
- `status`: `required|in:approved,rejected`
- `catatan`: `required_if:status,rejected`

#### Business Rules
- Saat data dimasukkan ke tabel `validasis`, backend menggunakan Laravel Model Observer (Event) untuk secara otomatis mengubah field `status_validasi` di tabel `pengukurans`.

---

### TABLE `jadwals`

#### Purpose
Mengelola kegiatan bulanan posyandu. Semua form pengukuran akan dikaitkan ke jadwal agar mudah mengelompokkan data (Laporan Bulan X).

#### Columns
| Field | Type | Nullable | Default | Unique | Index | FK | Keterangan |
| :--- | :--- | :---: | :--- | :---: | :---: | :---: | :--- |
| `id` | BIGINT (UNSIGNED) | No | Auto Increment | Yes | Yes | - | Primary Key |
| `posyandu_id` | BIGINT (UNSIGNED) | No | - | - | Yes | Yes| Pelaksana jadwal |
| `judul` | VARCHAR(255) | No | - | - | - | - | Misal: 'Penimbangan Bulan Juni' |
| `tanggal` | DATE | No | - | - | Yes | - | - |
| `waktu_mulai` | TIME | No | - | - | - | - | - |
| `waktu_selesai`| TIME | No | - | - | - | - | - |
| `lokasi` | VARCHAR(255) | No | - | - | - | - | - |
| `catatan` | TEXT | Yes | NULL | - | - | - | Info bawa KMS, dll. |
| `created_at` | TIMESTAMP | Yes | NULL | - | - | - | - |
| `updated_at` | TIMESTAMP | Yes | NULL | - | - | - | - |
| `deleted_at` | TIMESTAMP | Yes | NULL | - | - | - | Soft delete |

#### Constraints
- **PRIMARY KEY**: `id`
- **FOREIGN KEY**: `posyandu_id` (RESTRICT)

#### Relationships
- `Jadwal` `belongsTo` `Posyandu`
- `Jadwal` `hasMany` `Pengukuran`

#### Who Can Access
- **Admin**: CRUD
- **Puskesmas**: Read
- **Kader**: Create, Read, Update, Delete (hanya posyandunya)
- **Ibu**: No Access

#### Created By
Kader

#### Business Rules
- Kader tidak bisa menghapus Jadwal jika di dalamnya sudah ada `Pengukuran` (Sistem Restrict). Harus dibatalkan pengukurannya dulu.

---

### TABLE `laporans`

#### Purpose
Tabel Snapshot / Archiving. Karena perhitungan Laporan Bulanan (Rekap Cakupan) sangat berat jika dihitung secara query runtime setiap saat (Count, Grouping Z-Score), maka hasil akhirnya disimpan secara permanen di tabel ini setiap tutup buku bulan.

#### Columns
| Field | Type | Nullable | Default | Unique | Index | FK | Keterangan |
| :--- | :--- | :---: | :--- | :---: | :---: | :---: | :--- |
| `id` | BIGINT (UNSIGNED) | No | Auto Increment | Yes | Yes | - | Primary Key |
| `posyandu_id` | BIGINT (UNSIGNED) | No | - | - | Yes | Yes| Laporan Posyandu |
| `bulan` | INT | No | - | - | Yes | - | 1 - 12 |
| `tahun` | INT | No | - | - | Yes | - | Contoh: 2026 |
| `total_balita` | INT | No | 0 | - | - | - | Snapshot total sasaran |
| `diukur` | INT | No | 0 | - | - | - | Snapshot yang datang |
| `stunting` | INT | No | 0 | - | - | - | Snapshot jumlah stunting |
| `file_pdf_path`| VARCHAR(255) | Yes| NULL | - | - | - | Link download arsip |
| `created_at` | TIMESTAMP | Yes | NULL | - | - | - | Tanggal di-generate |
| `updated_at` | TIMESTAMP | Yes | NULL | - | - | - | - |

#### Constraints
- **PRIMARY KEY**: `id`
- **FOREIGN KEY**: `posyandu_id` (RESTRICT)
- **UNIQUE**: Gabungan `posyandu_id`, `bulan`, `tahun` (Mencegah laporan ganda di 1 bulan).

#### Relationships
- `Laporan` `belongsTo` `Posyandu`

#### Business Rules
- Laporan hanya dapat digenerate setelah seluruh `pengukurans` di bulan tersebut memiliki `status_validasi` == `approved`.

---

### TABLE `notifications`

#### Purpose
Menyimpan antrean pesan (Queue) dan riwayat blast WhatsApp untuk Ibu.

#### Columns
| Field | Type | Nullable | Default | Unique | Index | FK | Keterangan |
| :--- | :--- | :---: | :--- | :---: | :---: | :---: | :--- |
| `id` | BIGINT (UNSIGNED) | No | Auto Increment | Yes | Yes | - | Primary Key |
| `ibu_id` | BIGINT (UNSIGNED) | No | - | - | Yes | Yes| Penerima pesan |
| `type` | VARCHAR(50) | No | - | - | Yes | - | 'jadwal', 'hasil_ukur' |
| `message` | TEXT | No | - | - | - | - | Isi pesan text WA |
| `status` | ENUM | No | 'pending' | - | Yes | - | 'pending','sent','failed'|
| `created_at` | TIMESTAMP | Yes | NULL | - | - | - | - |
| `updated_at` | TIMESTAMP | Yes | NULL | - | - | - | Waktu dikirim |

#### Constraints
- **PRIMARY KEY**: `id`
- **FOREIGN KEY**: `ibu_id` (CASCADE)

#### Business Rules
- Data ini dibaca oleh cron job (Laravel Scheduler) / Laravel Horizon untuk dieksekusi ke API Watzap / Fonnte secara asynchronous.

---

### TABLE `activity_logs` (Optional / Spatie Activitylog)

#### Purpose
Audit Trail umum. Merekam "Siapa melakukan apa kapan".

#### Columns
Mengikuti struktur standar bawaan package `spatie/laravel-activitylog` (log_name, description, subject_type, subject_id, causer_type, causer_id, properties, created_at).

---

### TABLE `personal_access_tokens`, `password_reset_tokens`, `sessions`
**Purpose**: Tabel standar bawaan instalasi framework Laravel untuk kebutuhan Auth, API Tokens, dan Session File/DB. Dibiarkan *as-is* sesuai bawaan vendor.

---

## 3. ENTITY RELATIONSHIP (ASCII Diagram)

```text
 ┌────────────────────────────────────────────────────────────────────────┐
 │                              SINGLE DATABASE                           │
 │                                                                        │
 │    ┌─────────────┐                                                     │
 │    │    users    │◄──────┐ (1 to 1)                                    │
 │    └──────┬──────┘       │                                             │
 │           │ 1            │                                             │
 │           │              │                                             │
 │           │ N            │                                             │
 │    ┌──────┴──────┐       │                ┌─────────────┐              │
 │    │  puskesmas  │       │                │    ibus     │ (Virtual)    │
 │    └──────┬──────┘       │                └──────┬──────┘              │
 │           │ 1            │                       │ 1                   │
 │           │              │                       │                     │
 │           │ N            │                       │ N                   │
 │    ┌──────┴──────┐ 1   N │                ┌──────┴──────┐              │
 │    │  posyandus  ├───────┴──────┐         │   balitas   │              │
 │    └──────┬──────┘    kaders    │         └──────┬──────┘              │
 │           │ 1                   │                │ 1                   │
 │           │                     │                │                     │
 │           ├─────────────────────┼────────────────┘                     │
 │           │ 1                   │ 1              N                     │
 │           │ N                   │ N              ┌─────────────┐       │
 │    ┌──────┴──────┐ 1   N ┌──────┴──────┐ 1     1 │  validasis  │       │
 │    │   jadwals   ├───────► pengukurans ├─────────►             │       │
 │    └─────────────┘       └─────────────┘         └─────────────┘       │
 │                                                                        │
 └────────────────────────────────────────────────────────────────────────┘
```

---

## 4. DATA FLOW (End-To-End Integrasi)

### KADER FLOW (Insert Data)
```text
Kader Login (Auth: User -> Kader -> Posyandu)
↓
Akses Tambah Pengukuran (Hanya Balita di Posyandunya)
↓
Kirim Form (BB, TB, LK) ke Controller
↓
Controller memanggil ZScoreService::calculate()
↓
[INSERT] tabel `pengukurans`
(z_score terisi otomatis, status_validasi = 'pending')
↓
Kader melihat dashboard (Data Belum Final)
```

### PUSKESMAS FLOW (Validation)
```text
Puskesmas Login (Melihat seluruh Posyandu di kecamatannya)
↓
Membuka Menu Validasi Antrean
↓
[READ] tabel `pengukurans` WHERE status_validasi = 'pending'
↓
Klik 'Approve'
↓
[INSERT] tabel `validasis` (catatan ACC)
↓
[UPDATE] tabel `pengukurans` (status_validasi = 'approved')
↓
Sistem memicu event WhatsAppBlastJob
↓
[INSERT] tabel `notifications` (status = 'pending')
```

### IBU FLOW (Consumer)
```text
Ibu menerima WhatsApp (Worker mengeksekusi tabel notifications)
↓
Klik Link: https://nutrigen.com/ibu/abC123XyZ (Sistem membaca Access Token)
↓
Middleware me-resolve Token -> ibu_id = 5
↓
[READ] tabel `balitas` WHERE ibu_id = 5
↓
[READ] tabel `pengukurans` WHERE balita_id = X AND status_validasi = 'approved'
↓
Tampil Grafik KMS Digital
```

---

## 5. CRUD MATRIX LENGKAP

| MODULE / TABEL | ADMIN | KADER | PUSKESMAS | IBU (Token) |
| :--- | :--- | :--- | :--- | :--- |
| **users** | C R U D | - | - | - |
| **puskesmas** | C R U D | R (Self), U (Profil) | - | - |
| **posyandus** | C R U D | R (Self) | R (Wilayah) | - |
| **kaders** | C R U D | R (Self), U (Profil) | R (Wilayah) | - |
| **ibus** | C R U D | C R U (Posyandu) | R, U (Koreksi) | R (Self) |
| **balitas** | C R U D | C R U (Posyandu) | R, U (Medis) | R (Anak) |
| **jadwals** | C R U D | C R U D (Posyandu) | R (Wilayah) | - |
| **pengukurans** | C R U D | C R U* (Jika Pending)| R, U (Validasi)| R (Approved) |
| **validasis** | R | R (Lihat Catatan) | C R U | - |
| **laporans** | C R D | R, Generate | R, Generate | - |

*(Singkatan: C=Create, R=Read, U=Update, D=Delete)*

---

## 6. LARAVEL MODEL RELATIONSHIP (Kode Rujukan Bintang)

Silakan pasang kode berikut pada file Model Laravel (`app/Models/`):

```php
// User.php
public function kader() { return $this->hasOne(Kader::class); }
public function puskesmas() { return $this->belongsTo(Puskesmas::class); } // Opsional jika admin puskesmas digabung

// Puskesmas.php
public function posyandus() { return $this->hasMany(Posyandu::class); }

// Posyandu.php
public function puskesmas() { return $this->belongsTo(Puskesmas::class); }
public function balitas() { return $this->hasMany(Balita::class); }
public function kaders() { return $this->hasMany(Kader::class); }
public function jadwals() { return $this->hasMany(Jadwal::class); }

// Kader.php
public function user() { return $this->belongsTo(User::class); }
public function posyandu() { return $this->belongsTo(Posyandu::class); }

// Ibu.php
public function balitas() { return $this->hasMany(Balita::class); }

// Balita.php
public function ibu() { return $this->belongsTo(Ibu::class); }
public function posyandu() { return $this->belongsTo(Posyandu::class); }
public function pengukurans() { return $this->hasMany(Pengukuran::class); }

// Pengukuran.php
public function balita() { return $this->belongsTo(Balita::class); }
public function jadwal() { return $this->belongsTo(Jadwal::class); }
public function kader() { return $this->belongsTo(Kader::class); }
public function validasi() { return $this->hasOne(Validasi::class); }
```

---

## 7. MIGRATION ORDER (Urutan Run Artisan Migrate)

Urutan migration tidak boleh sembarangan karena tabel bawah bergantung pada *Foreign Key* tabel di atasnya. File tanggal Y_m_d migration **WAJIB** diurutkan secara kronologis seperti ini:

1. `2014_10_12_000000_create_users_table.php` (Core)
2. `..._create_roles_table.php` (Core)
3. `..._create_puskesmas_table.php` (Master Region)
4. `..._create_posyandus_table.php` (Master Hub, FK: puskesmas_id)
5. `..._create_kaders_table.php` (FK: user_id, posyandu_id)
6. `..._create_ibus_table.php` (Independen)
7. `..._create_balitas_table.php` (FK: posyandu_id, ibu_id)
8. `..._create_jadwals_table.php` (FK: posyandu_id)
9. `..._create_pengukurans_table.php` (FK: balita_id, jadwal_id, kader_id)
10. `..._create_validasis_table.php` (FK: pengukuran_id, user_id)
11. `..._create_laporans_table.php` (FK: posyandu_id)
12. `..._create_notifications_table.php` (FK: ibu_id)

---

## 8. IMPLEMENTATION CHECKLIST (Backend Developer)

Tandai ini secara bertahap saat Anda mulai menulis kode PHP.

- [ ] **Setup & Database**
  - [ ] Generate Migrations sesuai urutan.
  - [ ] Terapkan SoftDeletes pada tabel yang ditentukan.
  - [ ] Buat Seeder lengkap dengan relasinya.
- [ ] **Models**
  - [ ] Buat Eloquent Models.
  - [ ] Tentukan `$fillable`.
  - [ ] Pasang seluruh Relationship Functions.
- [ ] **Security & Access**
  - [ ] Install Laravel Breeze / Fortify untuk Auth Kader & Puskesmas.
  - [ ] Buat custom Middleware `RoleMiddleware`.
  - [ ] Buat Laravel Policy (Cth: `BalitaPolicy`, `JadwalPolicy`) untuk mengecek `posyandu_id` Kader.
- [ ] **Business Logic (Controllers)**
  - [ ] Buat `FormRequest` validation rules (Contoh: `StoreBalitaRequest`).
  - [ ] Wiring controller ke Frontend Kader sesuai dokumen `06_FRONTEND_BACKEND_CONTRACT.md`.
  - [ ] Integrasikan `ZScoreService` ke metode store Pengukuran.
- [ ] **Puskesmas & Validasi**
  - [ ] Buat list antrean *Pending Validation*.
  - [ ] Action logic Approve / Reject (Observer).
- [ ] **Queue & Worker (Opsional namun sangat disarankan)**
  - [ ] Setup Redis / Database Queue.
  - [ ] Buat Job untuk push Notifikasi WhatsApp di *background*.
- [ ] **Portal Ibu (Read-only System)**
  - [ ] Buat Token Middleware (`VerifyIbuToken`).
  - [ ] Kirim JSON Data ke Chart.js.

*(End of SDD - Database Architecture Blueprint)*
