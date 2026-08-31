# Laporan QA / Uji Fitur — NutriGen

Tanggal  : 29 Agustus 2026
Lingkungan: Laravel lokal (Laragon MySQL) + Vite dev (port 3000) + `php artisan serve` (127.0.0.1:8000),
            diuji lewat browser nyata (Edge headless via CDP). APP_ENV=local.
Method   : QA eksploratif menyeluruh — sweep semua halaman per peran + uji interaksi (navigasi, form,
           validasi, login, pilih-anak, modal approve). Tidak ada data dev yang dimodifikasi selama uji
           (kecuali disengaja pada skenario yang dijalankan sekali pun tidak ada, karena memakai db dev).

## Ringkasan Eksekutif

- 34 halaman/rute GET disweep: **33 HTTP 200**, 1 rute 404 (MENURUT DESAIN).
- **0 error console JavaScript** di semua halaman.
- 0 bug Kritis / Tinggi / Sedang ditemukan.
- Semua fitur inti berjalan: login (peran), navigasi sidebar/topbar, form validasi, link internal
  Portal Ibu (signed URL), modal approve/reject.

Pengujian mencakup: halaman publik, Portal Puskesmas (12 halaman), Portal Kader (12 halaman),
Portal Ibu (5 halaman + link internal + pilih-anak), form kosong (ujian validasi), login dengan
password salah, klik semua menu navigasi, dan interaksi modal approve.

## Ringkasan Berdasarkan Tingkat Keparahan

| Tingkat | Jumlah |
|---------|--------|
| Kritis   | 0 |
| Tinggi   | 0 |
| Sedang   | 0 |
| Rendah   | 0 |
| Info/Desain-dengan-sengaja | 2 |

## Daftar Temuan

### 1. [INFO] `/register` mengembalikan 404 (bukan bug)
- **URL**: `http://127.0.0.1:8000/register`
- **Perilaku**: GET /register → HTTP 404.
- **Kesimpulan**: Registrasi publik sengaja dinonaktifkan di `routes/auth.php` (route dikomentari);
  akun petugas dibuat/dikelola oleh pihak Puskesmas. Perilaku ini memang disengaja dan sudah
  konsisten dengan `RegistrationTest` yang kini memastikan 404. **Bukan cacat.**

### 2. [INFO] Tombol Approve/Reject dirender lewat modal JavaScript
- **URL**: `http://127.0.0.1:8000/puskesmas/validasi/{id}/review`
- **Perilaku**: Halaman review tidak memiliki `<form action=...approve>` statis. Tombol
  "Setujui"/"Tolak" memanggil `openApproveModal()`/`openRejectModal()`, dan aksi POST di-set
  dinamis via `submitApprove()`/`submitReject()` (`actionForm.action = /puskesmas/validasi/{id}/approve`).
- **Hasil uji**: Modal terbuka & tertutup tanpa error JavaScript. Fungsi approve/reject di sisi server
  (ubah status, `validated_by`/`validated_at`, log notifikasi) sudah terbukti lulus lewat
  `ApproveRejectTest` otomatis. **Bukan cacat**, hanya pola UI modal.

## Cakupan yang Diuji (semua PASS)

**Publik**
- `/`, `/login`, `/team`, `/forgot-password` → 200. `/register` → 404 (by design).
- Login password salah → tetap di `/login`, pesan "These credentials do not match".

**Portal Puskesmas (login `puskesmas@nutrigen.com`) — 12 halaman 200**
dashboard, balita, balita.show(`/1`), laporan, validasi, validasi.review(`/3`), validasi.riwayat(`/3`),
posyandu, pengaturan, pengaturan.petugas, pengaturan.keamanan, pengaturan.notifikasi.
- Klik semua menu sidebar → navigasi benar, tanpa error JS.
- Form pengaturan submit kosong → kembali ke halaman (validasi), tidak 500.

**Portal Kader (login `kader@nutrigen.com`) — 12 halaman 200**
dashboard, balita.index, balita.create, balita.show(`/1`), balita.edit(`/1`), pengukuran.create,
jadwal.index, jadwal.create, jadwal.show(`/1`), laporan.index, kader.profil, kader.profil.edit.
- Klik menu navigasi → benar, tanpa error JS.
- Submit form kosong (balita baru/edit, jadwal baru) → kembali ke halaman form (validasi), tidak 500.

**Portal Ibu (via dev bridge `/dev/portal-ibu/{id}/{page?}`) — 5 halaman 200**
home, growth, nutrition, posyandu, pilih-anak.
- Semua link internal `/portal-ibu/*` (signed URL) → 200 (tidak ada 403 invalid-signature =
  TINGGI-01 aman).
- Halaman pilih-anak menampilkan anak; klik pilihan anak → HTTP 200 (signed + `orang_tua`).

## Catatan Implementasi / Lingkungan (bukan bug perangkat lunak)

- **QA harness**: file driver sementara berada di `.hermes-qa/` di root proyek (belum di-commit,
  bisa dihapus / di-gitignore / dipindah ke `scripts/`). Ini tidak menyentuh kode aplikasi.
- **APP_URL**: untuk keperluan pengujian sementara diubah ke `http://127.0.0.1:8000`, lalu
  **dikembalikan** ke nilai asli `http://localhost` setelah uji selesai.
- Server yang dipakai untuk QA: `php artisan serve` (127.0.0.1:8000) + `npm run dev` (Vite, port 3000);
  MySQL via Laragon (datadir `C:\laragon\data\mysql-8`). Kedua proses background dapat dihentikan.

## Kesimpulan

Aplikasi **stabil dan bersih** — seluruh halaman dan fitur yang dapat diuji dari browser berjalan
normal tanpa error konsol, tanpa halaman rusak, dan tanpa kegagalan HTTP (selain `/register` yang
memang sengaja nonaktif). Tidak ada bug yang memerlukan perbaikan. Kondisi ini konsisten dengan
kerja keras sebelumnya pada perbaikan KRITIS/TINGGI/SEDANG dan cakupan test otomatis 46/46 yang hijau.
