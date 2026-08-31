# Next Backend Task (Roadmap)

Dokumen ini adalah *checklist* pekerjaan Backend Developer (Bintang) untuk menyelesaikan integrasi NutriGen.
Silakan kerjakan secara berurutan (*step-by-step*).

## FASE 1: Fondasi Database & Autentikasi
- [ ] **STEP 1: Database Design & Migration**
      Buat seluruh file migration berdasarkan `03_DATABASE_DESIGN.md`. Atur tipe data, relasi (foreign key constraints), dan *soft deletes*.
- [ ] **STEP 2: Model & Relationship**
      Buat semua class Model. Definisikan `fillable` dan fungsi relasi (`belongsTo`, `hasMany`) di setiap model. Tambahkan trait SoftDeletes.
- [ ] **STEP 3: Seeder & Factory**
      Buat data dummy yang representatif untuk User (Admin, Kader, Puskesmas), Posyandu, Ibu, dan Balita. Ini penting agar bisa melakukan testing tampilan frontend.
- [ ] **STEP 4: Authentication Setup**
      Install Laravel Breeze. Sesuaikan mekanisme login agar Kader dan Puskesmas bisa masuk dengan Role masing-masing. (Abaikan registrasi, akun dibuatkan oleh Admin).

## FASE 2: Portal Kader (Core Features)
- [ ] **STEP 5: Controller & Routing Setup**
      Ganti semua *placeholder route* di `web.php` dengan Controller sungguhan (`BalitaController`, `JadwalController`, dsb).
- [ ] **STEP 6: Form Validation (Requests)**
      Buat *FormRequest* untuk Create/Update Balita, Input Pengukuran, dan Tambah Jadwal.
- [ ] **STEP 7: Service Layer Z-Score**
      Tulis algoritma perhitungan Z-Score WHO. (Saran: test algoritmanya menggunakan Tinker sebelum dihubungkan ke controller).
- [ ] **STEP 8: CRUD Balita**
      Integrasikan logika Create, Read, Update, dan Hapus (Soft Delete) Balita. Hapus blok data dummy `@php` di file Blade.
- [ ] **STEP 9: Input Pengukuran**
      Implementasikan form pada modal profil balita agar data yang disubmit memanggil *Z-Score Service*, lalu tersimpan ke tabel `pengukurans`.
- [ ] **STEP 10: Manajemen Jadwal**
      Integrasikan pembuatan jadwal Posyandu dan tampilkan daftarnya di kalender.
- [ ] **STEP 11: Policy & Authorization (Security)**
      Buat `BalitaPolicy` dan `JadwalPolicy`. Kunci akses agar Kader hanya bisa mengelola data dari `posyandu_id` miliknya.

## FASE 3: Laporan & Penyelesaian Portal Kader
- [ ] **STEP 12: Dashboard Kader (Statistik)**
      Tarik data nyata (Total Balita, Cakupan Ukur) menggunakan Eager Loading dan Aggregates.
- [ ] **STEP 13: Laporan & Export PDF**
      Fungsikan tombol filter periode dan tombol *Generate Laporan* menggunakan library PDF (misal: `barryvdh/laravel-dompdf`).

## FASE 4: Portal Puskesmas & Ibu (Selanjutnya)
- [ ] **STEP 14: Validasi Puskesmas**
      Buat list pengukuran yang statusnya "Pending", lalu buat tombol Acc/Reject.
- [ ] **STEP 15: WhatsApp Gateway (Token Ibu)**
      Gunakan API WA (seperti Fonnte/Watzap) untuk men-generate link unik dan mem-blast notifikasi.
- [ ] **STEP 16: Portal Ibu (Read-only)**
      Buat sistem pembacaan akses tanpa login (middleware khusus berbasis URL Token).
- [ ] **STEP 17: Grafik Chart.js**
      Hubungkan data `pengukurans` menjadi array berurutan untuk di-render oleh Chart.js pada KMS Digital.

---
**Catatan untuk Bintang:**
Mulailah dari FASE 1 & FASE 2. Frontend untuk **Portal Kader** dan **Portal Puskesmas** sudah **Freeze** dan 100% siap di-wiring menggunakan dokumen `06_FRONTEND_BACKEND_CONTRACT.md`. Selamat bekerja!
