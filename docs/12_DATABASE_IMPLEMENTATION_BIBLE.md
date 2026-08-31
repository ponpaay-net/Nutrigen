# Database Implementation Bible
**Target Audience:** Backend Developer (Bintang)  
**Dokumen Referensi Utama:** `11_DATABASE_SCHEMA_COMPLETE.md` & `03_DATABASE_DESIGN.md`

---

## ARCHITECTURAL DECISION RECORD (ADR): Relasi Petugas Puskesmas

Sebelum memulai implementasi, Technical Lead telah melakukan evaluasi terhadap _Missing Link_ antara Petugas Puskesmas (User) dengan Entitas Instansinya (Puskesmas). Terdapat dua opsi arsitektur:

### Opsi A: Menambahkan `puskesmas_id` ke tabel `users`
*   **Kelebihan:** Implementasi cepat, tidak perlu membuat tabel tambahan.
*   **Kekurangan:** Mengotori tabel `users` (tabel otentikasi inti Laravel) dengan _domain logic_. Karena tidak semua User adalah petugas Puskesmas (ada Admin dan Kader), kolom ini harus *Nullable*. Ini melanggar prinsip normalisasi ketat dan mengganggu skalabilitas.

### Opsi B: Membuat Tabel Baru `petugas_puskesmas`
Tabel bridging yang menghubungkan `user_id` dengan `puskesmas_id`.
*   **Kelebihan:** *Separation of Concerns* yang sangat rapi. Tabel `users` tetap suci murni sebagai tabel Auth. Menjaga simetri desain dengan tabel `kaders` (yang juga menjembatani `user_id` dan `posyandu_id`). Laravel Best Practice.
*   **Kekurangan:** Menambah 1 Model dan 1 Migration.

### Keputusan (Architectural Verdict)
Berdasarkan kelayakan MVP dan kemudahan *maintenance* di masa depan, kita **MEMILIH OPSI B**. Kita akan membuat tabel `petugas_puskesmas`. Ini akan menciptakan konsistensi pola desain (*Design Symmetry*) yang sempurna:
- User (Role: Kader) -> Profil: tabel `kaders` -> Bekerja di: `posyandus`
- User (Role: Puskesmas) -> Profil: tabel `petugas_puskesmas` -> Bekerja di: `puskesmas`

---

## PANDUAN IMPLEMENTASI (PHASE 1)

Kerjakan panduan ini selangkah demi selangkah. Jangan melompat.

### STEP 0: Persiapan Environment

*   **Tujuan:** Menyiapkan konfigurasi dasar koneksi database.
*   **Penjelasan:** Laravel membutuhkan koneksi ke RDBMS (MySQL/PostgreSQL) agar semua migrasi bisa berjalan.
*   **Kenapa langkah ini diperlukan:** Jika `.env` belum dikonfigurasi, perintah artisan migrate akan gagal dengan error *Connection Refused*.
*   **Prerequisite:** Telah menginstal XAMPP/MAMP/Docker, MySQL Server berjalan, dan database kosong dengan nama `nutrigen` sudah dibuat di DBMS.

**Perintah Terminal / Composer / Artisan:**
*(Tidak ada perintah spesifik, ubah manual file .env)*

**Isi file yang diubah:**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=nutrigen
DB_USERNAME=root
DB_PASSWORD=
```
**Lokasi file:** `/.env`

**Checklist:**
- [ ] Database `nutrigen` sudah terbuat di phpMyAdmin / DBeaver.
- [ ] File `.env` sudah di-update.

**Cara Verifikasi:**
Jalankan perintah `php artisan db:show`. Jika muncul tabel informasi database, koneksi berhasil.

**Kemungkinan Error:** `SQLSTATE[HY000] [1049] Unknown database 'nutrigen'`
**Cara Mengatasinya:** Buat databasenya terlebih dahulu secara manual di client SQL Anda (`CREATE DATABASE nutrigen;`).

---

### STEP 1: Install Dependency

*   **Tujuan:** Mempersiapkan *package* pihak ketiga yang menjadi tulang punggung keamanan dan otorisasi.
*   **Penjelasan:** Sistem ini akan membutuhkan otentikasi login dan pengaturan Role (Spatie).
*   **Kenapa langkah ini diperlukan:** Kita tidak membuat sistem otentikasi dan sistem Role dari nol. Laravel ecosystem menyediakan *Breeze* dan *Spatie* yang sudah di-audit keamanannya.
*   **Prerequisite:** Environment (STEP 0) sudah beres.

**Perintah Composer / Artisan:**
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```

**Checklist:**
- [ ] Laravel Breeze terinstal (Blade stack).
- [ ] Spatie Laravel Permission terinstal dan konfigurasinya sudah di-publish.

**Cara Verifikasi:**
Periksa apakah ada file `config/permission.php` dan file migrasi baru berawalan `...create_permission_tables.php` di folder `database/migrations/`.

**Kemungkinan Error:** Bentrok versi Composer.
**Cara Mengatasinya:** Pastikan menggunakan PHP >= 8.2 dan Laravel 11/12 (sesuai composer.json). Gunakan `composer update` jika perlu.

---

### STEP 2: Authentication

*   **Tujuan:** Merapikan tabel `users` dasar.
*   **Penjelasan:** Pada hasil audit sebelumnya, disepakati bahwa kita **TIDAK** menggunakan tipe data ENUM untuk role pada tabel `users`. Kita akan mengandalkan Spatie.
*   **Kenapa langkah ini diperlukan:** Mencegah redundansi State.
*   **Prerequisite:** STEP 1 Selesai.

**Isi file yang diubah:**
Buka file migrasi bawaan Laravel `0001_01_01_000000_create_users_table.php` (atau sejenisnya).
Pastikan Schema users HANYA berisi:
```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
    $table->softDeletes(); // Tambahkan ini
});
```
**Lokasi file:** `database/migrations/0001_01_01_000000_create_users_table.php`

**Perintah Artisan:**
```bash
php artisan migrate
```

**Checklist:**
- [ ] Kolom `deleted_at` sudah ditambahkan (`$table->softDeletes()`).
- [ ] Tidak ada kolom `role` pada tabel `users`.

**Cara Verifikasi:**
Cek di DBeaver/phpMyAdmin, struktur tabel `users` sudah memiliki kolom `deleted_at`.

---

### STEP 3: Role & Permission (Spatie)

*   **Tujuan:** Mengimplementasikan Role Based Access Control.
*   **Penjelasan:** Membuat migrasi dan konfigurasi awal untuk *Role* agar bisa membedakan Admin, Puskesmas, dan Kader.
*   **Kenapa langkah ini diperlukan:** Mencegah akses silang. Kader tidak boleh membuka halaman Puskesmas, begitu pula sebaliknya.
*   **Prerequisite:** STEP 2 Selesai, dan tabel permission Spatie sudah di-publish di STEP 1.

**Perintah Artisan:**
*(Tabel migrasinya otomatis dibuat oleh Spatie di STEP 1)*
```bash
php artisan migrate
```
Lalu buat sebuah Seeder untuk menanamkan Master Role:
```bash
php artisan make:seeder RoleSeeder
```

**Isi file yang dibuat (RoleSeeder.php):**
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Buat 3 Master Role
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'puskesmas']);
        Role::create(['name' => 'kader']);
    }
}
```
**Lokasi file:** `database/seeders/RoleSeeder.php`

**Tambahkan di DatabaseSeeder.php:**
```php
public function run(): void
{
    $this->call([
        RoleSeeder::class,
    ]);
}
```
**Lokasi file:** `database/seeders/DatabaseSeeder.php`

**Perintah Eksekusi:**
```bash
php artisan db:seed --class=RoleSeeder
```

**Checklist:**
- [ ] Tabel `roles`, `permissions`, `model_has_roles`, dll sudah terbuat di database.
- [ ] `RoleSeeder` berhasil dieksekusi.
- [ ] Di dalam tabel `roles` sudah ada 3 baris: admin, puskesmas, kader.

**Cara Verifikasi:**
Buka *MySQL CLI* atau *phpMyAdmin*, jalankan query referensi berikut:
```sql
SELECT * FROM roles;
```
Hasilnya harus menampilkan 3 row: admin, puskesmas, dan kader dengan guard_name = 'web'.

**Catatan Technical Lead:**
Pastikan Model `User` (`app/Models/User.php`) sudah meng-include *trait* dari spatie yaitu `HasRoles`.
```php
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
    use HasRoles, SoftDeletes;
    // ...
}
```

---

### STEP 4: Migration (Schema Definitions)

*   **Tujuan:** Menerjemahkan seluruh desain tabel, kolom, dan tipe data ke dalam Laravel Migration.
*   **Penjelasan:** Laravel menggunakan satu file migrasi per tabel. Di dalam file inilah **STEP 5 (Constraint)**, **STEP 6 (Foreign Key)**, dan **STEP 7 (Index)** didefinisikan secara serentak menggunakan `Illuminate\Database\Schema\Blueprint`.
*   **Kenapa langkah ini diperlukan:** Ini adalah fondasi fisik database. Tanpa ini, tidak ada tempat untuk menyimpan data.
*   **Prerequisite:** STEP 3 Selesai.

**Perintah Artisan (Jalankan satu per satu berurutan):**
```bash
php artisan make:migration create_puskesmas_table
php artisan make:migration create_posyandus_table
php artisan make:migration create_petugas_puskesmas_table
php artisan make:migration create_kaders_table
php artisan make:migration create_ibus_table
php artisan make:migration create_balitas_table
php artisan make:migration create_jadwals_table
php artisan make:migration create_pengukurans_table
php artisan make:migration create_validasis_table
php artisan make:migration create_notifications_table
```

**Isi file yang dibuat (Isi method `up()` pada masing-masing file migrasi):**

**1. `create_puskesmas_table`**
```php
Schema::create('puskesmas', function (Blueprint $table) {
    $table->id();
    $table->string('kode_faskes', 50)->unique();
    $table->string('nama', 255);
    $table->text('alamat');
    $table->string('kecamatan', 100);
    $table->string('kepala_puskesmas', 255)->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

**2. `create_posyandus_table`**
```php
Schema::create('posyandus', function (Blueprint $table) {
    $table->id();
    $table->foreignId('puskesmas_id')->constrained('puskesmas')->restrictOnDelete();
    $table->string('nama', 255);
    $table->text('alamat');
    $table->string('desa', 100);
    $table->timestamps();
    $table->softDeletes();
});
```

**3. `create_petugas_puskesmas_table` (Sesuai ADR)**
```php
Schema::create('petugas_puskesmas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('puskesmas_id')->constrained('puskesmas')->restrictOnDelete();
    $table->string('nip', 50)->unique()->nullable();
    $table->string('no_hp', 20)->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

**4. `create_kaders_table`**
```php
Schema::create('kaders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
    $table->foreignId('posyandu_id')->constrained('posyandus')->restrictOnDelete();
    $table->string('nik', 16)->unique();
    $table->string('no_hp', 20)->nullable();
    $table->string('foto_profil', 255)->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

**5. `create_ibus_table`**
```php
Schema::create('ibus', function (Blueprint $table) {
    $table->id();
    $table->string('nik', 16)->unique();
    $table->string('nama', 255);
    $table->string('no_hp_wa', 20);
    $table->text('alamat')->nullable();
    $table->string('access_token', 64)->unique();
    $table->timestamps();
    $table->softDeletes();
});
```

**6. `create_balitas_table`**
```php
Schema::create('balitas', function (Blueprint $table) {
    $table->id();
    $table->foreignId('posyandu_id')->constrained('posyandus')->restrictOnDelete();
    $table->foreignId('ibu_id')->constrained('ibus')->restrictOnDelete();
    $table->string('nik', 16)->unique()->nullable();
    $table->string('nama', 255)->index();
    $table->date('tanggal_lahir');
    $table->enum('jenis_kelamin', ['L', 'P']);
    $table->float('berat_lahir')->nullable();
    $table->float('tinggi_lahir')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

**7. `create_jadwals_table`**
```php
Schema::create('jadwals', function (Blueprint $table) {
    $table->id();
    $table->foreignId('posyandu_id')->constrained('posyandus')->restrictOnDelete();
    $table->string('judul', 255);
    $table->date('tanggal')->index();
    $table->time('waktu_mulai');
    $table->time('waktu_selesai');
    $table->string('lokasi', 255);
    $table->text('catatan')->nullable();
    $table->timestamps();
    $table->softDeletes();
});
```

**8. `create_pengukurans_table`**
```php
Schema::create('pengukurans', function (Blueprint $table) {
    $table->id();
    $table->foreignId('balita_id')->constrained('balitas')->restrictOnDelete();
    $table->foreignId('jadwal_id')->constrained('jadwals')->restrictOnDelete();
    $table->foreignId('kader_id')->constrained('kaders')->restrictOnDelete();
    $table->integer('umur_bulan');
    $table->float('berat_badan');
    $table->float('tinggi_badan');
    $table->float('lingkar_kepala')->nullable();
    $table->float('z_score_bb_u');
    $table->float('z_score_tb_u');
    $table->float('z_score_bb_tb');
    $table->string('status_gizi', 50)->index();
    $table->enum('status_validasi', ['pending', 'approved', 'rejected'])->default('pending')->index();
    $table->text('catatan')->nullable();
    $table->timestamps();
    $table->softDeletes();
    
    // Constraint: 1 balita hanya bisa diukur 1 kali di 1 jadwal kegiatan
    $table->unique(['balita_id', 'jadwal_id']);
});
```

**9. `create_validasis_table`**
```php
Schema::create('validasis', function (Blueprint $table) {
    $table->id();
    $table->foreignId('pengukuran_id')->constrained('pengukurans')->cascadeOnDelete();
    $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
    $table->enum('status', ['approved', 'rejected'])->index();
    $table->text('catatan')->nullable();
    $table->timestamps();
    
    // Constraint: 1 pengukuran hanya divalidasi 1 kali (final state)
    $table->unique('pengukuran_id');
});
```

**10. `create_notifications_table`**
```php
Schema::create('notifications', function (Blueprint $table) {
    $table->id();
    $table->foreignId('ibu_id')->constrained('ibus')->cascadeOnDelete();
    $table->string('type', 50)->index();
    $table->text('message');
    $table->enum('status', ['pending', 'sent', 'failed'])->default('pending')->index();
    $table->timestamps();
});
```
> **NOTE:** Tabel `notifications` ini adalah tabel **Business Notification khusus aplikasi NutriGen**, bukan tabel bawaan fitur *Laravel Notification* (`notifications` default Laravel yang dibuat via `notifications:table`). Tabel ini murni untuk menampung riwayat pesan WA/Sistem untuk Ibu. Jangan campuradukkan dengan implementasi notifikasi *broadcast* bawaan framework.

**Lokasi file:** `database/migrations/YYYY_MM_DD_HHMMSS_create_*_table.php`

**Checklist:**
- [ ] 10 file migration terbuat berurutan.
- [ ] Tipe data sesuai dengan yang dituliskan.
- [ ] *Chaining methods* (seperti `->unique()`, `->nullable()`) telah diterapkan.

**Cara Verifikasi:**
Buka setiap file migration, pastikan *Blueprint* sudah ter-paste dengan benar tanpa sintaks error.

**Kemungkinan Error:** `Class 'Blueprint' not found`
**Cara Mengatasinya:** Migrasi Laravel secara default sudah melakukan auto-import `use Illuminate\Database\Schema\Blueprint;`. Jika hilang, pastikan line tersebut ada di atas class migrasi.

---

### STEP 5: Constraint (Unique & Nullable)

*   **Tujuan:** Mencegah redundansi data spesifik (Unique) dan membolehkan kekosongan (Nullable).
*   **Penjelasan:** Pada kode migrasi di STEP 4, fungsi `->unique()` (seperti pada NIK) dan `->nullable()` (seperti pada Foto Profil) telah disertakan. Khusus untuk tabel `pengukurans` dan `validasis`, dibuat *Composite Unique Key* dan *Unique Key*.
*   **Cara Verifikasi:**
    Cek di DBeaver atau table info, pastikan kolom yang ditandai *Nullable* memiliki bendera `YES` pada properties Null, dan *Unique Key* terdaftar di DDL index.

---

### STEP 6: Foreign Key & Referential Integrity

*   **Tujuan:** Menjamin keutuhan relasi tabel (ACID Compliance).
*   **Penjelasan:** Laravel menggunakan fungsi `->constrained('nama_tabel')`.
    *   `cascadeOnDelete()`: Jika Parent dihapus, Anak ikut terhapus. (Contoh: `users` dihapus -> `kaders` terhapus, `pengukurans` dihapus -> `validasis` terhapus).
    *   `restrictOnDelete()`: Parent tidak bisa dihapus selama masih ada Anak. (Contoh: `posyandus` **tidak bisa** dihapus jika masih ada `balitas` di dalamnya).
*   **Kemungkinan Error:** `General error: 1215 Cannot add foreign key constraint`
*   **Cara Mengatasinya:** Pastikan urutan tanggal migrasi benar! Tabel `users` dan `puskesmas` harus di-migrate **SEBELUM** tabel `posyandus` dan `kaders`.

---

### STEP 7: Index (Optimization)

*   **Tujuan:** Mempercepat proses baca (Read/Query) di database, terutama untuk pencarian dan filter.
*   **Penjelasan:** Berdasarkan audit akses query pada Dashboard, Validasi, dan Laporan, fungsi `->index()` hanya disisakan pada kolom yang **benar-benar sering dijadikan Where Clause** (seperti `status_gizi`, `status_validasi`, `tanggal` jadwal, dan nama balita untuk pencarian). Indeks pada kolom kardinalitas rendah seperti jenis kelamin telah dihapus untuk menghemat memori RDBMS.

**Perintah Eksekusi STEP 4-7 Serentak:**
```bash
php artisan migrate
```

**Cara Verifikasi Akhir (SQL Murni):**
Jalankan query ini di MySQL client Anda untuk melihat daftar foreign key yang aktif:
```sql
SELECT TABLE_NAME, COLUMN_NAME, CONSTRAINT_NAME, REFERENCED_TABLE_NAME, REFERENCED_COLUMN_NAME
FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
WHERE REFERENCED_TABLE_SCHEMA = 'nutrigen';
```

**Catatan Technical Lead:**
- Jangan mengubah constraint `restrictOnDelete` menjadi `cascade` pada entitas medis utama (`balitas`, `pengukurans`). Menghapus Balita secara kaskade adalah malapraktik rekam medis digital.
- Jika urutan timestamp *filename* migration terbalik sehingga gagal *migrate*, ubah angka _timestamp_ pada nama file (YYYY_MM_DD_...) secara manual dari OS Explorer agar urutannya logis.

---

### STEP 8: Model & Trait SoftDeletes

*   **Tujuan:** Membuat class representasi tabel database untuk berinteraksi via Eloquent ORM.
*   **Penjelasan:** Kita perlu membuat 10 model dasar. Semua model harus menggunakan standard *Fillable/Guarded*, *Casts*, *SoftDeletes*, dan *Relationships*.
*   **Kenapa langkah ini diperlukan:** Model adalah *core* MVC Laravel. Tanpa model, kita harus menggunakan Query Builder mentah (`DB::table(...)`) yang rentan *error* dan sulit dibaca.

**Perintah Artisan:**
```bash
php artisan make:model Puskesmas
php artisan make:model Posyandu
php artisan make:model PetugasPuskesmas
php artisan make:model Kader
php artisan make:model Ibu
php artisan make:model Balita
php artisan make:model Jadwal
php artisan make:model Pengukuran
php artisan make:model Validasi
php artisan make:model Notification
```

**Lokasi file:** `app/Models/NamaModel.php`

**Contoh Struktur Model Sempurna (`Balita.php`):**
```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Balita extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id']; // Mass Assignment Protection

    protected $casts = [
        'tanggal_lahir' => 'date',
        'berat_lahir' => 'float',
        'tinggi_lahir' => 'float',
    ];

    public function posyandu(): BelongsTo { return $this->belongsTo(Posyandu::class); }
    public function ibu(): BelongsTo { return $this->belongsTo(Ibu::class); }
    public function pengukurans(): HasMany { return $this->hasMany(Pengukuran::class); }
}
```

**Checklist:**
- [ ] `use Illuminate\Database\Eloquent\SoftDeletes;` sudah ditambahkan di **SEMUA** file Model kecuali `Notification` dan `Validasi`.
- [ ] Properti `$guarded = ['id'];` atau `$fillable` didefinisikan secara eksplisit.
- [ ] Properti `$casts` didefinisikan untuk kolom date, datetime, boolean, dan float.

---

### STEP 9: Relationship (Eloquent ORM)

*   **Tujuan:** Mendefinisikan jembatan relasi antar model agar mudah melakukan *Eager Loading* (`with()`).
*   **Prerequisite:** STEP 8 Selesai.

**Isi file yang diubah:**

*   **User.php**
    ```php
    public function kader(): \Illuminate\Database\Eloquent\Relations\HasOne { return $this->hasOne(Kader::class); }
    public function petugasPuskesmas(): \Illuminate\Database\Eloquent\Relations\HasOne { return $this->hasOne(PetugasPuskesmas::class); }
    ```
*   **Puskesmas.php**
    ```php
    public function posyandus(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(Posyandu::class); }
    public function petugas(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(PetugasPuskesmas::class); }
    ```
*   **Posyandu.php**
    ```php
    public function puskesmas(): \Illuminate\Database\Eloquent\Relations\BelongsTo { return $this->belongsTo(Puskesmas::class); }
    public function kaders(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(Kader::class); }
    public function balitas(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(Balita::class); }
    public function jadwals(): \Illuminate\Database\Eloquent\Relations\HasMany { return $this->hasMany(Jadwal::class); }
    ```
*   **Pengukuran.php**
    ```php
    public function balita(): \Illuminate\Database\Eloquent\Relations\BelongsTo { return $this->belongsTo(Balita::class); }
    public function jadwal(): \Illuminate\Database\Eloquent\Relations\BelongsTo { return $this->belongsTo(Jadwal::class); }
    public function kader(): \Illuminate\Database\Eloquent\Relations\BelongsTo { return $this->belongsTo(Kader::class); }
    public function validasi(): \Illuminate\Database\Eloquent\Relations\HasOne { return $this->hasOne(Validasi::class); }
    ```

**Cara Verifikasi:**
Gunakan Laravel Tinker (`php artisan tinker`):
```php
$balita = \App\Models\Balita::first();
$balita->ibu; // Harus mengembalikan object Ibu tanpa error
```

---

### STEP 10: Factory (Data Generator)

*   **Tujuan:** Menyiapkan *blueprint* data acak untuk testing dan Seeder yang **saling berelasi secara logis**, BUKAN *orphan data*.
*   **Kenapa langkah ini diperlukan:** Kita butuh hirarki data *dummy* balita dan pengukuran untuk mengetes performa.

**Perintah Artisan:**
```bash
php artisan make:factory PuskesmasFactory
php artisan make:factory PosyanduFactory
php artisan make:factory IbuFactory
php artisan make:factory BalitaFactory
php artisan make:factory JadwalFactory
php artisan make:factory PengukuranFactory
```

**Isi contoh `BalitaFactory.php` (Manajemen Relasi):**
> **SANGAT PENTING (ATURAN FACTORY):**
> - Jika Factory ini dipanggil mandiri untuk *Unit Testing* (`Balita::factory()->create()`), diperbolehkan menggunakan `Posyandu::factory()` dan `Ibu::factory()` agar relasi terbentuk otomatis secara ajaib.
> - Namun, jika dipanggil dari `DatabaseSeeder` utama, Backend Developer **WAJIB** meng-override *foreign key* tersebut (melemparkan id yang sudah dibuat sebelumnya). Tujuannya agar Seeder bisa mengontrol relasi secara sadar dan tidak menciptakan *Random Orphan Data* (data hantu yang tidak punya induk Puskesmas).

```php
public function definition(): array
{
    return [
        'posyandu_id' => \App\Models\Posyandu::factory(), // Akan ter-override oleh Seeder
        'ibu_id' => \App\Models\Ibu::factory(), // Ibu di-generate 1:1 jika tidak di-override
        'nik' => $this->faker->nik(),
        'nama' => $this->faker->name(),
        'tanggal_lahir' => $this->faker->dateTimeBetween('-3 years', '-1 months'),
        'jenis_kelamin' => $this->faker->randomElement(['L', 'P']),
        'berat_lahir' => $this->faker->randomFloat(1, 2.5, 4.0),
        'tinggi_lahir' => $this->faker->randomFloat(1, 45, 55),
    ];
}
```

---

### STEP 11: Seeder (Initial Data & Scenario)

*   **Tujuan:** Menyuntikkan data *dummy* hirarkis sesuai skenario nyata sistem NutriGen (Dari Admin hingga Validasi).

**Perintah Artisan:**
```bash
php artisan make:seeder DatabaseSeeder
```

**Isi `DatabaseSeeder.php`:**
```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\{User, Puskesmas, PetugasPuskesmas, Posyandu, Kader, Ibu, Balita, Jadwal, Pengukuran, Validasi};

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(RoleSeeder::class);

        // 1. Admin
        $admin = User::factory()->create(['name' => 'Super Admin', 'email' => 'admin@nutrigen.com']);
        $admin->assignRole('admin');

        // 2. Puskesmas & Petugas
        $puskesmas = Puskesmas::create(['kode_faskes' => 'P123', 'nama' => 'Puskesmas Melati', 'alamat' => 'Jl. A', 'kecamatan' => 'Kota']);
        $userPkm = User::factory()->create(['name' => 'Dr. Anita', 'email' => 'puskesmas@nutrigen.com']);
        $userPkm->assignRole('puskesmas');
        PetugasPuskesmas::create(['user_id' => $userPkm->id, 'puskesmas_id' => $puskesmas->id, 'nip' => '198011112005012001']);

        // 3. Posyandu & Kader
        $posyandu = Posyandu::create(['puskesmas_id' => $puskesmas->id, 'nama' => 'Posyandu Melati 1', 'alamat' => 'Jl. B', 'desa' => 'Desa C']);
        $userKader = User::factory()->create(['name' => 'Siti (Kader)', 'email' => 'kader@nutrigen.com']);
        $userKader->assignRole('kader');
        $kader = Kader::create(['user_id' => $userKader->id, 'posyandu_id' => $posyandu->id, 'nik' => '3200100000000001']);

        // 4. Ibu & Balita (Relasional, bukan Orphan)
        $ibu = Ibu::factory()->create(['nama' => 'Ibu Budi', 'no_hp_wa' => '08123456789']);
        $balita = Balita::factory()->create([
            'posyandu_id' => $posyandu->id, 
            'ibu_id' => $ibu->id, 
            'nama' => 'Budi Kecil',
            'tanggal_lahir' => now()->subMonths(12)
        ]);

        // 5. Jadwal Kegiatan
        $jadwal = Jadwal::create([
            'posyandu_id' => $posyandu->id,
            'judul' => 'Penimbangan Bulan Ini',
            'tanggal' => now()->format('Y-m-d'),
            'waktu_mulai' => '08:00:00',
            'waktu_selesai' => '12:00:00',
            'lokasi' => 'Balai Desa'
        ]);

        // 6. Pengukuran
        $pengukuran = Pengukuran::create([
            'balita_id' => $balita->id,
            'jadwal_id' => $jadwal->id,
            'kader_id' => $kader->id,
            'umur_bulan' => 12,
            'berat_badan' => 9.5,
            'tinggi_badan' => 75.2,
            'lingkar_kepala' => 45.0,
            'z_score_bb_u' => 0.5,
            'z_score_tb_u' => 0.2,
            'z_score_bb_tb' => 0.6,
            'status_gizi' => 'Gizi Baik',
            'status_validasi' => 'approved' // Diset untuk skenario sudah divalidasi
        ]);

        // 7. Validasi oleh Puskesmas
        Validasi::create([
            'pengukuran_id' => $pengukuran->id,
            'user_id' => $userPkm->id, // Petugas Puskesmas yang memvalidasi
            'status' => 'approved',
            'catatan' => 'Data sesuai standar antropometri.'
        ]);
        
        // (Opsional) Generate 50 Balita extra untuk load test
        Balita::factory(50)->create(['posyandu_id' => $posyandu->id]);
    }
}
```

**Perintah Eksekusi:**
```bash
php artisan migrate:fresh --seed
```

---

## FINAL CHECKLIST (BEFORE GIT PUSH)
Backend Developer (Bintang) **WAJIB** mencentang seluruh daftar ini sebelum melakukan *commit* dan integrasi ke Frontend:

- [ ] `php artisan migrate:fresh --seed` berjalan sukses tanpa error relasi (Foreign Key Error).
- [ ] Model memiliki `$guarded` atau `$fillable`.
- [ ] Model memiliki array `$casts` untuk tipe data `date` dan `float`.
- [ ] SoftDeletes berjalan (Data Balita yang dihapus tidak menghilang dari tabel `balitas`, melainkan `deleted_at` terisi).
- [ ] `User::find($id)->hasRole('puskesmas')` via Spatie berfungsi.
- [ ] Petugas Puskesmas benar-benar terhubung ke tabel `puskesmas` melalui `petugas_puskesmas`.
- [ ] Laporan bulanan dapat di-*query* secara *realtime* (Tabel `laporans` dipastikan tidak ada di database).

=========================================================
**STATUS DOKUMEN: FINAL VERSION (DATABASE FOUNDATION)**
=========================================================
