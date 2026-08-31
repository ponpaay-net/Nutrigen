# Database Design

## 1. Pendekatan Database
NutriGen menggunakan pendekatan **Single Database Architecture**. 
Rekomendasi RDBMS: **MySQL 8+** atau **PostgreSQL 14+**.

### Mengapa Hanya SATU Database?
- **Data Integrity**: Memastikan data balita yang diakses Ibu, diukur Kader, dan divalidasi Puskesmas adalah baris data (row) yang persis sama. Tidak ada duplikasi sinkronisasi antar database.
- **Efisiensi**: Lebih mudah di-maintain, dibackup, dan lebih murah secara operasional server untuk skala MVP.
- **Relasi yang Kuat**: Memungkinkan penggunaan foreign key yang terjamin antar entitas secara langsung.

### Mengapa Tidak Memisahkan Database Berdasarkan Portal?
Memisahkan database berdasarkan portal (Kader, Puskesmas, Ibu) adalah *anti-pattern* untuk MVP. Hal ini akan menimbulkan masalah konsistensi data (Eventual Consistency), kerumitan arsitektur (Microservices/Distributed Database), dan overhead sinkronisasi data antar portal yang berlebihan.

## 2. Konsep Role-Based Access Control (RBAC)

Karena menggunakan satu database, isolasi data murni mengandalkan **Role** dan **Hak Akses (Permissions)** di layer aplikasi.

**Daftar Role:**
1. `admin` : Super administrator sistem (IT Support/Dinkes). Memiliki akses tak terbatas.
2. `puskesmas` : Petugas fasilitas kesehatan. Dapat melihat seluruh Posyandu di bawah naungan wilayah/kecamatannya. Bertugas melakukan validasi akhir status gizi.
3. `kader` : Petugas Posyandu. Hanya dapat melihat, menambah, dan mengukur Balita yang terdaftar di **Posyandu miliknya** saja.
4. `ibu` : (Virtual Role) Ibu tidak login menggunakan sistem auth Laravel (`users` table). Ibu mengakses portal khusus via URL Unik / Token WhatsApp.

**Konsep Single Source of Truth:**
Satu balita `(ID: 10)` di dalam database akan dirender:
- Di layar Kader A: "Balita binaan saya"
- Di layar Puskesmas X: "Balita di Posyandu Melati 1 (Binaan saya)"
- Di HP Ibu: "Anak saya"

Yang membedakan hanyalah: Kader melihatnya melalui route `kader/profil-balita`, Puskesmas via `puskesmas/validasi`, dan Ibu via `ibu/token-abc`.

## 3. Rekomendasi Struktur Tabel (Belum Migration)

Berikut adalah rancangan tabel yang direkomendasikan untuk memenuhi seluruh kebutuhan sistem:

1. `users`
   Menyimpan data autentikasi (email/password) untuk Admin, Kader, dan Puskesmas.
2. `roles` / `permissions`
   Mengelola hak akses (bisa menggunakan package Spatie Laravel Permission).
3. `puskesmas`
   Menyimpan data identitas fasilitas puskesmas (Nama, Alamat, Wilayah).
4. `posyandus`
   Menyimpan data Posyandu. Berelasi `puskesmas_id` (Binaan puskesmas mana).
5. `kaders`
   Data detail profil Kader. Berelasi `user_id` dan `posyandu_id` (Kader ditugaskan di Posyandu mana).
6. `ibus`
   Menyimpan identitas Ibu (Nama, No HP WhatsApp, NIK). *Tidak* berelasi ke `users` karena tidak login sistem.
7. `balitas`
   Data utama balita (Nama, TTL, Jenis Kelamin, BB Lahir, dsb). Berelasi `ibu_id` dan `posyandu_id`.
8. `pengukurans`
   Data riwayat timbang. Berelasi `balita_id` dan `jadwal_id`. (BB, TB, LK, Z-Score, Status Validasi).
9. `jadwals`
   Agenda kegiatan posyandu. Berelasi `posyandu_id`.
10. `validasis`
    Log validasi puskesmas. Berelasi `pengukuran_id` dan `user_id` (Puskesmas).
11. `laporans`
    Arsip rekapitulasi data bulanan (Snapshot).
12. `notifications`
    Log pengiriman notifikasi WhatsApp ke Ibu.
