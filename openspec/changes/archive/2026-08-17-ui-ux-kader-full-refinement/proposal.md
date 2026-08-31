# Proposal: UI/UX Kader Full Refinement & Standar Buku KIA/KMS

## Overview
Penyempurnaan visual, konsistensi desain pro-max, dan integrasi penuh bidang data standar Buku KIA / KMS pada seluruh modul Portal Kader NutriGen.

## Scope of Changes
1. **Visual & Color Hygiene**:
   - Menghapus anomali warna non-standar (blue/purple/sky) pada kartu dashboard, jadwal, dan notifikasi.
   - Standardisasi ke palet resmi NutriGen: Teal (primer), Emerald (selesai/positif), Amber (perhatian/waktu), Rose (risiko/alert), dan Slate (netral).
2. **Standardisasi Komponen Notifikasi**:
   - Standardisasi toast card vertikal bertingkat compact (300-400px) dengan timer progress bar dan glassmorphism surface.
3. **Integrasi Data Buku KIA / KMS**:
   - Tabel `orang_tuas`: `no_kk`, `nik_ayah`, `nik_ibu`, `nama_ayah`, `pekerjaan_ayah`, `pekerjaan_ibu`.
   - Tabel `balitas`: `no_bpjs`, `berat_lahir`, `panjang_lahir`, `lingkar_kepala_lahir`.
   - Tabel `pengukurans`: `lingkar_kepala`, `asi_eksklusif`, `status_kenaikan`.
4. **CRUD & Form Experience**:
   - Pembaruan formulir Tambah/Edit Balita dan Modal Aksi Cepat Pengukuran dengan field grouping rapi.
   - Validasi data di controller dan penyesuaian mass-assignment `$fillable` di model Eloquent.
