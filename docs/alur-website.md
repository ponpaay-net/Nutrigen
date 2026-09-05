# Alur Website NutriGen

Dokumen ini merangkum alur dan fungsi yang tersedia pada NutriGen saat pemeriksaan
lokal **4 September 2026**.

## Hasil pemeriksaan

| Pemeriksaan | Hasil |
| --- | --- |
| `php artisan route:list --except-vendor` | Berhasil; seluruh route terdaftar |
| `npm.cmd run build` | Berhasil; Vite production build selesai |
| Beranda `/` | Berhasil, HTTP 200 |
| Halaman tim `/team` | Berhasil, HTTP 200 |
| Login `/login` | Berhasil, HTTP 200 |
| Lupa password `/forgot-password` | Berhasil, HTTP 200 |
| Akses tanpa login ke dashboard terlindungi | Dialihkan ke login |
| `/portal-ibu` tanpa signed URL | HTTP 404 |
| `/portal-ibu/pilih-anak` tanpa signed URL | HTTP 403 |
| `php artisan test` | Berhasil: 55 test passed, 147 assertions |

Pengujian browser dijalankan pada `http://127.0.0.1:8000`. Route refresh database
tidak dipanggil karena bersifat destruktif (truncate dan seed ulang).

### Uji login akun demo

| Akun | Hasil |
| --- | --- |
| `puskesmas@nutrigen.com` | Berhasil masuk ke `/puskesmas/dashboard` |
| `kader1@nutrigen.com` | Berhasil masuk ke `/kader/dashboard` |
| `kader2@nutrigen.com` | Berhasil masuk ke `/kader/dashboard` |
| `kader3@nutrigen.com` | Berhasil masuk ke `/kader/dashboard` |

Password demo yang digunakan untuk seluruh akun di atas: `password`.

Dengan sesi Puskesmas aktif, seluruh halaman GET utama berikut merespons HTTP
200: dashboard, validasi, data balita, posyandu, laporan, pengaturan, pengaturan
petugas, keamanan, dan notifikasi. Dengan sesi Kader aktif, seluruh halaman GET
utama berikut merespons HTTP 200: dashboard, data balita, tambah balita,
pengukuran, jadwal, laporan, profil, edit profil, dan keamanan akun.

## Alur umum

```text
Beranda
  ├─ Cara Kerja / Ekosistem / Demo / Team
  └─ Masuk ke Sistem
       └─ Login
            ├─ role kader     -> Portal Kader
            ├─ role puskesmas -> Portal Puskesmas
            └─ role ibu       -> signed link portal Ibu
```

## Alur Kader Posyandu

1. Login menggunakan akun terverifikasi dengan role `kader`.
2. Sistem mengarahkan ke **Dashboard Kader** untuk KPI, status gizi, prioritas
   balita, dan antrean validasi.
3. **Data Balita**:
   - melihat daftar dan mencari/filter balita;
   - menambah balita baru;
   - melihat profil dan riwayat pengukuran;
   - mengubah data balita;
   - menghapus balita beserta relasi yang diizinkan.
4. **Ukur Balita**:
   - memilih balita;
   - memasukkan tanggal, berat badan, panjang/tinggi badan, dan lingkar kepala;
   - sistem menghitung z-score WHO 2006 (BB/U, TB/U, BB/TB, IMT/U);
   - pengukuran dikirim untuk validasi Puskesmas.
5. **Jadwal Posyandu**:
   - membuat, mengubah, dan menghapus jadwal;
   - melihat sesi terdekat dan countdown;
   - mengirim notifikasi WhatsApp ke orang tua (driver `log` pada konfigurasi lokal).
6. **Laporan**:
   - melihat KPI, tren, distribusi status gizi;
   - mengekspor Excel;
   - menghasilkan/cetak laporan PDF.
7. **Profil Kader**:
   - melihat dan mengubah profil;
   - mengubah kata sandi.
8. Logout mengakhiri sesi dan mencegah akses melalui tombol kembali browser.

## Alur Puskesmas

1. Login menggunakan akun terverifikasi dengan role `puskesmas`.
2. **Dashboard Puskesmas** menampilkan metrik agregat wilayah dan aktivitas
   terbaru.
3. **Validasi Data**:
   - membuka antrean pengukuran;
   - meninjau detail dan riwayat;
   - menyetujui atau menolak pengukuran dengan catatan;
   - sistem mencatat validator dan log notifikasi.
4. **Direktori Balita** menampilkan data balita wilayah Puskesmas dan detail
   balita.
5. **Posyandu & Kader**:
   - melihat Posyandu;
   - menambah Posyandu;
   - menambah, mengubah, dan menghapus kader.
6. **Laporan**:
   - melihat analitik;
   - ekspor Excel;
   - cetak PDF.
7. **Pengaturan**:
   - mengubah data institusi;
   - mengelola petugas;
   - mengubah keamanan/kata sandi;
   - mengatur preferensi notifikasi.
8. Logout mengakhiri sesi.

## Alur Portal Ibu

1. Ibu menerima tautan WhatsApp bertanda tangan digital (signed URL) yang
   memuat identitas balita dan orang tua.
2. Jika memiliki satu anak, tautan membuka **Dashboard Ibu**. Jika lebih dari
   satu anak, tautan membuka **Pilih Anak**.
3. Portal menyediakan:
   - **Dashboard**: ringkasan status dan rekomendasi;
   - **Profil Anak/Posyandu**: identitas layanan dan kontak kader;
   - **Riwayat**: histori pengukuran dan pertumbuhan;
   - **Grafik/Nutrisi**: kurva pertumbuhan WHO dan rekomendasi menu/resep.
4. Middleware `signed` dan pembatasan relasi orang tua-balita mencegah perubahan
   ID atau akses ke anak milik keluarga lain.

## Route fungsi

### Publik dan autentikasi

- `/` — landing page.
- `/team` — halaman tim.
- `/login`, `/logout` — masuk dan keluar.
- `/forgot-password`, `/reset-password/{token}` — pemulihan kata sandi.
- `/verify-email`, `/confirm-password` — verifikasi dan konfirmasi keamanan.
- `/profile` — profil akun pengguna terautentikasi.

### Kader

- `/kader/dashboard`
- `/kader/balita`, `/kader/balita/baru`, `/kader/balita/{id}`,
  `/kader/balita/{id}/edit`, `/kader/balita/{id}/ukur`
- `/kader/pengukuran`
- `/kader/jadwal`
- `/kader/laporan`, `/kader/laporan/export-excel`,
  `/kader/laporan/generate`
- `/kader/profil`, `/kader/profil/edit`, `/kader/profil/keamanan`

### Puskesmas

- `/puskesmas/dashboard`, `/puskesmas/balita`, `/puskesmas/balita/{id}`
- `/puskesmas/validasi`, `/puskesmas/validasi/{id}/review`,
  `/puskesmas/validasi/{id}/riwayat`
- `/puskesmas/posyandu`
- `/puskesmas/laporan`, `/puskesmas/laporan/export-excel`,
  `/puskesmas/laporan/cetak-pdf`
- `/puskesmas/pengaturan`, `/puskesmas/pengaturan/petugas`,
  `/puskesmas/pengaturan/keamanan`, `/puskesmas/pengaturan/notifikasi`

### Portal Ibu

- `/portal-ibu/pilih-anak`
- `/portal-ibu/dashboard`
- `/portal-ibu/profil-anak`
- `/portal-ibu/riwayat`
- `/portal-ibu/grafik`

Semua route Portal Ibu memerlukan signed URL yang valid; URL polos seperti
`/portal-ibu` memang tidak terdaftar.

## Status form

Setelah database `nutrigen_test` dibuat, seluruh test otomatis untuk form dan
mutasi data berhasil dijalankan: autentikasi, verifikasi email, reset/password,
profil, CRUD balita, input pengukuran, CRUD jadwal, notifikasi jadwal, validasi
approve/reject Puskesmas, policy akses, dan portal Ibu.

## Catatan yang perlu ditindaklanjuti

- Dua tautan akses Ibu pada halaman login saat ini mengarah ke `/portal-ibu`,
  sehingga berakhir 404. Tautan tersebut perlu diarahkan ke signed URL yang
  dibuat setelah balita/orang tua dipilih, atau ke halaman bantuan yang sesuai.

## Harus Ditindak Saat Production

Sebelum NutriGen dipublikasikan ke production, tindakan berikut wajib
diselesaikan:

1. **Hapus atau kunci endpoint refresh database.** Route
   `/refresh-database-nutrigen` saat ini dapat mengosongkan tabel dan melakukan
   seed ulang. Route ini tidak boleh tersedia untuk publik. Hapus dari
   production atau batasi secara eksplisit hanya untuk local/development.
2. **Nonaktifkan akun demo dan ganti semua password.** Jangan memakai akun
   seeded dengan password `password` di production. Buat akun operator baru
   dengan password kuat dan aktifkan MFA bila tersedia.
3. **Pastikan konfigurasi production aman.** Gunakan `APP_ENV=production`,
   `APP_DEBUG=false`, HTTPS, secret aplikasi production yang unik, dan jangan
   memasukkan token atau kredensial ke repository.
4. **Lindungi signed link Portal Ibu.** Pertahankan validasi kepemilikan
   balita-orang tua, gunakan HTTPS, jangan log URL lengkap, dan pertimbangkan
   TTL lebih pendek dari default 7 hari sesuai kebijakan privasi.
   Akses tanpa login ini memang disengaja: token hanya boleh membuka halaman
   anak yang tertaut dan tidak boleh dapat dipakai untuk mengakses balita lain.
   Uji ulang manipulasi parameter `balita`, `orang_tua`, signature kedaluwarsa,
   serta kebocoran URL melalui referrer/analytics sebelum production.
5. **Konfigurasi gateway WhatsApp secara aman.** Jika pengiriman nyata
   diaktifkan, isi token Fonnte/Wablas melalui secret manager atau environment
   production, bukan melalui source code.
6. **Jalankan migrasi dan seluruh test pada database production-like** sebelum
   rilis, lalu lakukan pemeriksaan ulang authorization, backup, logging, dan
   rate limiting.
