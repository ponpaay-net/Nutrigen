# Audit Backend - Nutrigen (As-Is)

**Status audit:** 25 Agustus 2026

Dokumen ini menggambarkan implementasi yang saat ini ada di repository, bukan rancangan database atau route yang belum diterapkan.

## 1. Ringkasan Sistem

Nutrigen adalah aplikasi Laravel dengan tiga area utama:

- Portal Kader: `/kader`
- Portal Puskesmas: `/puskesmas`
- Portal Ibu: `/portal-ibu`

Autentikasi Laravel digunakan untuk user Kader dan Puskesmas. Database aktual memakai MySQL dengan model utama `User`, `OrangTua`, `Balita`, `Pengukuran`, `Posyandu`, `Kader`, dan `Jadwal`.

Portal Ibu saat ini tidak memiliki login/form token yang selesai. Route produksinya memakai signed URL, sedangkan shortcut preview lokal tersedia di `/dev/portal-ibu/{balita}`.

## 2. Alur Aktual

```text
Kader menyimpan balita
    -> OrangTua + Balita tersimpan

Kader menyimpan pengukuran
    -> Pengukuran dibuat dengan status pending
    -> KaderService juga mencoba membuat record Validasi

Puskesmas membuka antrean
    -> Menyetujui atau menolak Pengukuran
    -> Saat approve, signed URL dibuat

Ibu membuka Portal Ibu
    -> Controller membaca balita dari query ?balita={id}
    -> Hanya pengukuran approved yang ditampilkan
```

### Status setiap tahap

| Tahap                         | Status            | Catatan                                                                                     |
| ----------------------------- | ----------------- | ------------------------------------------------------------------------------------------- |
| Login Kader/Puskesmas         | Berjalan          | Route memakai `auth` dan `role:kader`/`role:puskesmas`.                                     |
| Pendaftaran balita oleh Kader | Berjalan          | Memakai `OrangTua` dan `orang_tua_id`.                                                      |
| Input pengukuran              | Berisiko gagal    | `KaderService` mereferensikan model/tabel `Validasi` yang tidak ada di implementasi aktual. |
| Antrean Puskesmas             | Berjalan sebagian | Antrean membaca `pengukurans.status_validasi`.                                              |
| Approve/reject                | Berjalan          | Status langsung diubah pada tabel `pengukurans`.                                            |
| Pembuatan signed URL          | Berjalan sebagian | URL dibuat saat approve, tetapi tidak diteruskan atau dikirim.                              |
| Pengiriman WhatsApp           | Belum ada         | Tidak ada service, endpoint gateway, job, atau pemanggilan API WA.                          |
| Portal Ibu                    | Preview berjalan  | Signed route dapat dibuka jika URL lengkap; shortcut lokal dapat dipakai untuk demo.        |
| Isolasi data Ibu              | Belum aman        | `balita` hanya dicari berdasarkan ID, tanpa verifikasi relasi penerima link.                |

## 3. Route Aktual

### Kader

Route Kader berada di [routes/web.php](routes/web.php) dan dilindungi `web`, `auth`, `prevent-back-history`, serta `role:kader`. Fitur yang tersedia mencakup dashboard, CRUD balita, pengukuran, jadwal, laporan, dan profil.

### Puskesmas

Route Puskesmas dilindungi `web`, `auth`, `prevent-back-history`, dan `role:puskesmas`. Fitur yang tersedia mencakup dashboard, antrean validasi, balita, laporan, posyandu, pengaturan, approve, dan reject.

### Portal Ibu

Route produksi saat ini:

| Method | URI                       | Nama                   | Controller                       |
| ------ | ------------------------- | ---------------------- | -------------------------------- |
| GET    | `/portal-ibu/dashboard`   | `portal-ibu.home`      | `PortalIbuController::home`      |
| GET    | `/portal-ibu/profil-anak` | `portal-ibu.posyandu`  | `PortalIbuController::posyandu`  |
| GET    | `/portal-ibu/riwayat`     | `portal-ibu.growth`    | `PortalIbuController::growth`    |
| GET    | `/portal-ibu/grafik`      | `portal-ibu.nutrition` | `PortalIbuController::nutrition` |

Semua route di atas memakai middleware `signed`, bukan `auth.ibu`.

Shortcut lokal:

```text
GET /dev/portal-ibu/{balita}
```

Shortcut hanya didaftarkan ketika `APP_ENV=local` dan ditujukan untuk preview, bukan akses production.

## 4. Struktur Data Aktual

Relasi yang benar di database:

```text
OrangTua 1 --- banyak Balita
Posyandu 1 --- banyak Balita
Posyandu 1 --- banyak Kader
Balita 1 --- banyak Pengukuran
Posyandu 1 --- banyak Jadwal
Kader 1 --- banyak Pengukuran
```

Field penting:

- `balitas.orang_tua_id`
- `balitas.posyandu_id`
- `pengukurans.balita_id`
- `pengukurans.kader_id`
- `pengukurans.status_validasi` dengan nilai `pending`, `approved`, atau `rejected`
- `orang_tuas.no_hp_whatsapp`

Tidak ditemukan pada migration aktual:

- tabel `ibus`
- model `App\\Models\\Ibu`
- kolom `balitas.ibu_id`
- tabel `validasis`
- tabel `notifications`
- kolom `access_token` atau `token_whatsapp`

## 5. Temuan Prioritas

| Prioritas | Temuan                                                                                                                                           | Dampak                                                                                     |
| --------- | ------------------------------------------------------------------------------------------------------------------------------------------------ | ------------------------------------------------------------------------------------------ |
| Kritis    | `KaderService` mengimpor `App\\Models\\Validasi`, tetapi model dan tabel validasi tidak ada.                                                     | Penyimpanan pengukuran melalui service dapat gagal setelah `Pengukuran::create()`.         |
| Kritis    | `PuskesmasController::approve()` membuat `$signedUrl` tetapi tidak mengirim, menyimpan, atau menampilkannya.                                     | Ibu tidak menerima link setelah data disetujui.                                            |
| Kritis    | `PortalIbuAuth`, `PortalIbuService`, factory, dan notification code masih mengacu pada model `Ibu` yang tidak ada.                               | Jalur token/session lama tidak dapat digunakan.                                            |
| Kritis    | Portal menerima ID balita dari query tanpa mencocokkan pemilik atau token dengan `OrangTua`.                                                     | Pemegang signed URL yang valid berpotensi membaca balita lain jika mengetahui ID.          |
| Tinggi    | Signed URL dibuat untuk `portal-ibu.home`, tetapi halaman lanjutan juga memakai middleware signed dan link baru harus dibuat untuk setiap route. | Navigasi internal dapat menghasilkan URL yang tidak valid bila signature tidak diteruskan. |
| Tinggi    | Validasi Puskesmas hanya mengubah `pengukurans.status_validasi`; tidak ada audit record validasi.                                                | Riwayat validator, waktu keputusan, dan catatan terstruktur tidak tersimpan terpisah.      |
| Sedang    | `PortalIbuController::getActiveBalita()` mengambil balita tanpa scope `orang_tua_id`.                                                            | Otorisasi data belum sesuai rancangan read-only per ibu.                                   |
| Sedang    | `PortalIbuService` merupakan implementasi lama dan tidak selaras dengan controller aktif.                                                        | Menambah kebingungan dan risiko perubahan pada jalur yang tidak digunakan.                 |
| Sedang    | Test bawaan masih gagal pada redirect login, registration, dan soft-delete user.                                                                 | Baseline test belum hijau; regresi baru sulit dibedakan dari kegagalan lama.               |

## 6. Hal yang Sudah Berjalan

- Middleware alias `auth`, `role`, `signed`, `auth.ibu`, dan `prevent-back-history` terdaftar di [app/Http/Kernel.php](app/Http/Kernel.php).
- Relasi `Balita::latestPengukuran()` sudah tersedia di [app/Models/Balita.php](app/Models/Balita.php).
- Data approved difilter di Portal Ibu sebelum ditampilkan.
- Kader dapat membuat data orang tua dan balita melalui [KaderController.php](app/Http/Controllers/Kader/KaderController.php).
- Puskesmas membatasi data berdasarkan `puskesmas_id` pada relasi Posyandu.
- Vite berjalan pada `127.0.0.1:3000` karena port default `5173` berada dalam rentang port yang dikecualikan Windows.

## 7. Rekomendasi Perbaikan

### Fase 1 - Pulihkan alur data

1. Pilih satu desain validasi. Untuk implementasi sekarang, gunakan `pengukurans.status_validasi` dan hapus pemanggilan `Validasi::create()`, atau tambahkan migration/model `Validasi` secara lengkap.
2. Tambahkan service pengiriman WhatsApp yang menerima nomor `OrangTua::no_hp_whatsapp` dan signed URL.
3. Simpan log pengiriman dan statusnya agar kegagalan gateway dapat dilacak.
4. Tampilkan URL hasil approve di flash message atau halaman detail sebelum integrasi gateway selesai.

### Fase 2 - Benahi akses Portal Ibu

1. Gunakan model aktual `OrangTua`, bukan `Ibu`.
2. Tambahkan token akses pada `orang_tuas` jika loginless link memang dipilih.
3. Cocokkan token ke `orang_tua_id`, lalu batasi semua query balita berdasarkan relasi tersebut.
4. Gunakan satu strategi URL: token path atau signed URL. Jangan mencampur `auth.ibu`, `signed`, dan shortcut debug.
5. Buat test untuk token invalid, token kedaluwarsa, balita milik ibu lain, dan pengukuran pending.

### Fase 3 - Kualitas operasional

1. Tambahkan audit keputusan approve/reject: validator, waktu, dan catatan.
2. Pindahkan nilai TTL signed URL ke konfigurasi.
3. Tambahkan policy untuk akses balita, edit, dan delete.
4. Perbaiki test autentikasi yang saat ini masih mengharapkan perilaku route lama.
5. Tandai atau hapus service/factory lama yang memakai model `Ibu` setelah migrasi desain diputuskan.

## 8. Cara Uji Lokal Saat Ini

Jalankan dua terminal:

```bash
npm run dev
winpty php.exe artisan serve
```

Asset Vite tersedia di `http://127.0.0.1:3000/`. Laravel biasanya tersedia di `http://127.0.0.1:8000`.

Preview Portal Ibu:

```text
http://127.0.0.1:8000/dev/portal-ibu/{ID_BALITA}
```

Verifikasi route:

```bash
winpty php.exe artisan route:list --path=portal-ibu
winpty php.exe artisan route:list --path=dev/portal-ibu
```

---

**Kesimpulan:** Portal Kader dan Puskesmas sudah memiliki sebagian besar permukaan CRUD dan validasi. Namun alur Kader -> Puskesmas -> Ibu belum selesai: referensi `Validasi` tidak sesuai schema, signed URL belum disalurkan, integrasi WhatsApp belum ada, dan otorisasi Portal Ibu belum mengikat akses ke orang tua yang benar.
