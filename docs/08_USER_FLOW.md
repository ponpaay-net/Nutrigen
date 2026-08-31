# User Flow (End-to-End)

## 1. Flow Kader Posyandu
```text
[ LOGIN KADER ]
      │
      ▼
[ HOME / DASHBOARD ] ──(Pilih Menu)──┐
      │                              │
      ▼                              ▼
[ DAFTAR BALITA ]               [ JADWAL POSYANDU ]
      │                              │
      ├─► [ Tambah Balita Baru ]     ├─► [ Tambah Jadwal ]
      │                              │
      ▼                              ▼
[ PROFIL BALITA ]               [ DETAIL JADWAL ]
      │                              │
      └─► [ Input Pengukuran ] ◄─────┘
               (Isi Form)
                   │
                   ▼
        (Sistem Hitung Z-Score)
                   │
                   ▼
      [ Data Tersimpan (Pending) ]
```

## 2. Flow Puskesmas
```text
[ LOGIN PUSKESMAS ]
      │
      ▼
[ DASHBOARD REGIONAL ]
 (Melihat statistik seluruh Posyandu di kecamatannya)
      │
      ▼
[ ANTREAN VALIDASI ]
 (Daftar balita yang baru ditimbang kader)
      │
      ├─► [ Approve ] ───► Status Gizi Final
      │
      └─► [ Reject / Rujuk ] ───► Anomali terdeteksi
               │
               ▼
   [ NOTIFIKASI WHATSAPP BLAST ]
 (Kirim hasil ke nomor HP para Ibu)
```

## 3. Flow Ibu (Ibu Balita)
```text
[ WHATSAPP IBU ]
 Terima Pesan: "Hasil timbang Budi sudah keluar."
      │
      ▼
[ KLIK LINK TOKEN ] (Misal: nutrigen.com/ibu/token-123)
      │
      ▼
[ DASHBOARD IBU (Web Browser HP) ]
 (Sistem mendeteksi ibu_id dari token)
      │
      ├─► [ Lihat KMS Digital / Riwayat ]
      │
      └─► [ Lihat Grafik Pertumbuhan WHO ]
               │
               ▼
       [ Tutup Browser ]
```
