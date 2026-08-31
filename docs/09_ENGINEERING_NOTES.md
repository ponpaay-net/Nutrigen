# Engineering Notes & Best Practices

Dokumen ini berisi panduan *best practice* yang wajib diikuti oleh Backend Developer (Bintang) saat mengimplementasikan logic Laravel pada project NutriGen. Hal ini bertujuan untuk menjaga *maintainability* dan *clean architecture*.

## 1. Gunakan Eloquent Relationship secara Ketat
Jangan gunakan query builder manual (`DB::table()`) untuk relasi data. Pastikan semua model saling terhubung dengan relasi yang benar (`belongsTo`, `hasMany`).
* **Alasan**: Mencegah *Spaghetti Code*, memudahkan eager loading, dan memastikan integritas data.

## 2. Hindari N+1 Query (Gunakan Eager Loading)
Saat merender `Daftar Balita` atau `Detail Jadwal`, pastikan menggunakan `with()`.
Contoh yang **SALAH**:
```php
$balitas = Balita::all();
foreach($balitas as $b) { echo $b->ibu->nama; } // N+1 Query!
```
Contoh yang **BENAR**:
```php
$balitas = Balita::with('ibu', 'pengukurans')->where('posyandu_id', $user->posyandu_id)->get();
```
* **Alasan**: Menghemat beban database secara drastis saat data mencapai ribuan baris.

## 3. Route Model Binding
Ganti parameter ID pada controller dengan *type-hint* Model secara langsung.
Contoh yang **BENAR**:
```php
public function show(Balita $balita) {
    return view('kader.profil-balita', compact('balita'));
}
```
* **Alasan**: Laravel akan otomatis mengecek keberadaan ID dan me-return 404 jika tidak ditemukan, menghemat 2 baris kode `findOrFail`.

## 4. Gunakan Form Request Validation
Jangan menulis logika validasi di dalam controller (`$request->validate()`). Pindahkan semuanya ke class `FormRequest` khusus.
Contoh: `php artisan make:request StoreBalitaRequest`
* **Alasan**: Controller menjadi sangat bersih dan hanya fokus pada *business logic*. Reusable untuk API jika nanti diperlukan.

## 5. Middleware & Policy Authorization
Gunakan Laravel Policy untuk mengecek Hak Akses, jangan menggunakan logic `if/else` di dalam controller.
Contoh pembuatan policy: `php artisan make:policy BalitaPolicy`
Di Controller: `$this->authorize('view', $balita);`
* **Alasan**: Mencegah *Insecure Direct Object Reference (IDOR)*. Memastikan Kader A tidak bisa mengedit data Balita milik Kader B dengan memanipulasi parameter ID di URL.

## 6. Service Layer Pattern (Z-Score Logic)
Pindahkan seluruh rumus perhitungan matematika WHO Z-Score ke dalam Service Class khusus, misalnya `app/Services/ZScoreCalculator.php`.
* **Alasan**: Kalkulasi Z-Score sangat rumit (berdasarkan usia dalam bulan, jenis kelamin, dan konstanta standar WHO). Jangan menaruh hitungan ini di Controller atau Model. Jika algoritma berubah, cukup perbaiki di 1 file (Single Responsibility Principle).

## 7. Soft Deletes
Gunakan *Soft Deletes* pada model `Balita`, `Ibu`, dan `Pengukuran`.
Tambahkan trait `use SoftDeletes;` pada model dan `$table->softDeletes()` pada migration.
* **Alasan**: Data rekam medis/kesehatan tidak boleh dihapus secara permanen untuk keperluan pelacakan dan audit medis. Jika terjadi salah hapus, data masih dapat direstorasi.

## 8. Gunakan Pagination
Untuk halaman laporan dan daftar balita, jangan pernah menggunakan `->get()` tanpa batasan. Gunakan `->paginate(15)`.
* **Alasan**: Memastikan kecepatan *load* stabil meskipun data telah mencapai puluhan ribu baris.

## 9. 100% Named Routes
Frontend saat ini telah di-*freeze* menggunakan Named Routes (`route('nama.route')`).
* **Alasan**: Backend bebas mengubah URL `('/daftar-balita' menjadi '/balita/list')` kapanpun tanpa perlu mengedit 1 baris pun file Blade. Backend hanya perlu menyesuaikan di `routes/web.php`.
