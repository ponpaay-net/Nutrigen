# CRUD Access Matrix

Dokumen ini menjelaskan batas akses (Permissions) untuk setiap Role di sistem terhadap Module (Tabel/Entitas) yang ada.

**Keterangan Simbol:**
- **[ ✔ ]** = Akses Penuh / Bisa
- **[ L ]** = Akses Terbatas (Limited). Hanya bisa pada entitas di Posyandu miliknya.
- **[ V ]** = Hanya View / Read-only.
- **[ - ]** = Tidak memiliki akses sama sekali.

| MODULE / ENTITY | ADMIN | PUSKESMAS | KADER | IBU |
| :--- | :---: | :---: | :---: | :---: |
| **User & Role** | ✔ | - | - | - |
| **Puskesmas** | ✔ | V | - | - |
| **Posyandu** | ✔ | V (di wilayahnya) | V (posyandunya) | - |
| **Kader** | ✔ | V | Update (Profil) | - |
| **Ibu** | ✔ | V | L (Create, Edit) | - |
| **Balita** | ✔ | V | L (Create, Edit) | V (Anaknya) |
| **Jadwal** | ✔ | V | L (Create, Edit) | - |
| **Pengukuran** | ✔ | V | L (Input) | V (Anaknya) |
| **Validasi Gizi** | ✔ | ✔ | V (Lihat Hasil) | - |
| **Laporan** | ✔ | Generate | Generate (L) | - |
| **WhatsApp Blast**| ✔ | ✔ | - | - |

## Penjabaran Hak Akses (Policy)

### 1. Kader (Posyandu Level Access)
- **Create**: Bisa menambah Balita, Ibu, Jadwal, dan input Pengukuran.
- **Read**: Hanya bisa melihat data yang berelasi dengan `posyandu_id` miliknya. Jika Kader bertugas di Posyandu Melati 1, dia tidak bisa mengakses URL profil balita dari Posyandu Melati 2 (harus throw 403 Forbidden).
- **Update**: Bisa mengedit profil Balita, Jadwal yang belum lewat, dan profilnya sendiri.
- **Validasi**: Kader **TIDAK BISA** mengubah status Z-Score secara final. Status otomatis *Pending Validasi* saat diinput.

### 2. Puskesmas (Regional Level Access)
- **Read**: Bisa melihat SEMUA Posyandu, Kader, Balita, dan Pengukuran yang berelasi dengan `puskesmas_id` miliknya.
- **Validasi (Approve/Reject)**: Merupakan tugas utama Puskesmas. Meninjau data ukur kader, melakukan verifikasi anomali, dan meng-Acc data sehingga menjadi Final.
- **WhatsApp**: Dapat memicu (trigger) tombol "Kirim Hasil via WhatsApp" untuk seluruh balita di wilayahnya secara massal.

### 3. Ibu (Virtual Role Access)
- **Read**: Hanya dapat membaca data (Riwayat & Grafik) dari `balita_id` yang memiliki relasi dengan `ibu_id` miliknya. Di-resolve melalui Access Token pada URL.

### 4. Admin (Root)
- Memiliki bypass policy untuk mengelola seluruh data master dan maintenance sistem.
