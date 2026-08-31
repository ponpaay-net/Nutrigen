# Entity Relationship Diagram (ERD)

Diagram ini menunjukkan hubungan antar entitas (tabel) dalam Single Database Architecture NutriGen.

```text
                               ┌─────────────┐
                               │             │
                               │  PUSKESMAS  │
                               │             │
                               └──────┬──────┘
                                      │ 1
                                      │
                                      │ N
                               ┌──────┴──────┐
                               │             │
                               │  POSYANDU   │
                               │             │
                               └──────┬──────┘
                                      │
                     ┌────────────────┼────────────────┐
                   1 │              1 │                │ 1
                     │                │                │
                   N ▼              N ▼              N ▼
               ┌─────────┐      ┌─────────┐      ┌─────────┐
 ┌──────┐ 1  1 │         │      │         │      │         │
 │ USER ├──────►  KADER  │      │  BALITA ◄──────┤   IBU   │ 1  1 (Virtual / No Auth)
 └──────┘      │         │      │         │      │         │
               └─────────┘      └─────┬───┘      └─────────┘
                                      │ 1
                                      │
                                      │ N
                               ┌──────┴──────┐
               ┌─────────┐ 1 N │             │ 1  1 ┌────────────┐
               │  JADWAL ├─────► PENGUKURAN  ├──────►  VALIDASI  │
               └─────────┘     │             │      └────────────┘
                               └─────────────┘
                                      │ N
                                      │
                                      │ 1
                               ┌──────┴──────┐
                               │   LAPORAN   │
                               └─────────────┘
```

## Relasi Kunci (Foreign Keys)
- `posyandus.puskesmas_id` : Puskesmas membawahi banyak Posyandu.
- `kaders.user_id` : Akun login Kader (1 to 1).
- `kaders.posyandu_id` : Kader ditugaskan di Posyandu tertentu. Pembatasan akses (Policy) didasarkan pada relasi ini. Kader hanya bisa melihat balita dengan `posyandu_id` yang sama dengannya.
- `balitas.ibu_id` : Balita dimiliki oleh Ibu. Nomor HP pada tabel Ibu digunakan untuk mengirim token akses portal Ibu.
- `balitas.posyandu_id` : Balita terdaftar di Posyandu tertentu.
- `pengukurans.balita_id` : Data historis ukur tiap bulan.
- `pengukurans.jadwal_id` : Pengukuran dilakukan pada jadwal kegiatan yang mana.
- `validasis.pengukuran_id` : Bukti bahwa hasil perhitungan ukur ini sudah disetujui (Acc) oleh petugas puskesmas (Relasi 1 ke 1).

## Akses Token Ibu (Access Control Level)
Untuk memungkinkan Ibu mengakses portal tanpa login, sistem akan men-generate URL unik, misalnya `https://nutrigen.com/ibu/token-xxxx`. 

Di database, tabel `ibus` bisa ditambahkan field `access_token`.
- Jika URL token diakses, sistem me-resolve token ke `ibu_id`.
- Menampilkan semua relasi `balitas` yang `ibu_id`-nya cocok.
