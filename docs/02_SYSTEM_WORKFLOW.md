# System Workflow Lengkap

Dokumen ini memetakan alur kerja sistem NutriGen secara keseluruhan (End-to-End) mulai dari Kader menginput data hingga Ibu melihat hasil pertumbuhannya.

## Alur Data Pengukuran (The Core Loop)

```text
  [ 1. KADER LOGIN ]
         │
         ▼
  [ 2. DASHBOARD KADER ]
  Memilih menu Balita atau Jadwal
         │
         ├──────────────────────────────────────────┐
         ▼                                          ▼
  [ 3A. TAMBAH BALITA ]                      [ 3B. DAFTAR BALITA ]
  Jika balita baru, daftarkan                Pilih profil balita yang sudah ada
  (Disimpan ke DB, status: terdaftar)               │
         │                                          │
         └───────────────────┬──────────────────────┘
                             ▼
                  [ 4. PROFIL BALITA ]
                  Klik tombol "Ukur"
                             │
                             ▼
                 [ 5. INPUT PENGUKURAN ]
                 Isi form BB, TB, LK (Modal)
                             │
                             ▼
                 [ 6. HITUNG Z-SCORE ]
                 Backend memproses algoritma WHO
                             │
                             ▼
                 [ 7. SIMPAN KE DATABASE ]
                 Status: "Pending Validasi Puskesmas"
                             │
                             ▼
           [ 8. MASUK DASHBOARD PUSKESMAS ]
           Pihak puskesmas melihat antrean pengukuran
                             │
                             ▼
                     [ 9. VALIDASI ]
           Puskesmas meninjau hasil (Acc/Reject)
           Jika stunting -> Tindak Lanjut
                             │
                             ▼
                   [ 10. STATUS FINAL ]
           Status gizi resmi ditetapkan di database
                             │
                             ▼
             [ 11. NOTIFIKASI WHATSAPP ]
           Kirim pesan ke nomor HP Ibu berisi:
           "Data bulan ini selesai. Klik Link unik."
                             │
                             ▼
                   [ 12. PORTAL IBU ]
           Ibu klik link token via WA (tanpa login akun)
                             │
                             ├──────────────────────────┐
                             ▼                          ▼
                     [ 13. GRAFIK ]             [ 14. RIWAYAT ]
                Melihat kurva WHO balita    Melihat data BB/TB tiap bulan
                             │
                             ▼
                        [ SELESAI ]
```

## Penjelasan Teknis Flow
1. **Input Pengukuran (Kader)**: Kader memasukkan data mentah. Kader *tidak bisa* merubah status stunting/normal secara final.
2. **Hitung Z-Score (Backend)**: Perhitungan dilakukan *server-side* setiap kali data pengukuran disimpan.
3. **Validasi (Puskesmas)**: Mencegah kesalahan input oleh kader (human error). Data yang anomali bisa dikoreksi oleh petugas medis Puskesmas.
4. **Distribusi Akses (Ibu)**: Ibu tidak perlu mengingat username/password. URL dikirim via WhatsApp API menggunakan Token URL (contoh: `nutrigen.com/ibu/token-abc-123`) yang mengarah khusus ke data balita miliknya.
