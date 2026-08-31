# Routes Plan

Rencana pengelompokan dan standar penamaan routing Laravel untuk backend.

```php
<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. Authentication Routes
|--------------------------------------------------------------------------
| Menggunakan Laravel Breeze / Sanctum standar
*/
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthController::class, 'create'])->name('login');
    Route::post('login', [AuthController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'destroy'])->name('logout');
});

/*
|--------------------------------------------------------------------------
| 2. Kader Portal Routes
|--------------------------------------------------------------------------
| Akses khusus Kader Posyandu
*/
Route::middleware(['auth', 'role:kader'])->prefix('kader')->group(function () {
    
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Balita (Resource controller tanpa delete/destroy)
    Route::resource('balita', BalitaController::class)->except(['destroy']);
    
    // Input Pengukuran
    Route::post('balita/{balita}/pengukuran', [PengukuranController::class, 'store'])->name('pengukuran.store');

    // Manajemen Jadwal
    Route::resource('jadwal', JadwalController::class)->except(['destroy', 'edit', 'update']);

    // Laporan
    Route::get('laporan', [LaporanController::class, 'index'])->name('laporan.index');
    Route::post('laporan/generate', [LaporanController::class, 'generate'])->name('laporan.generate');

    // Profil Kader
    Route::get('profil-kader', [KaderController::class, 'profil'])->name('kader.profil');
});

/*
|--------------------------------------------------------------------------
| 3. Puskesmas Portal Routes
|--------------------------------------------------------------------------
| Akses khusus Puskesmas (Regional)
*/
Route::middleware(['auth', 'role:puskesmas'])->prefix('puskesmas')->name('puskesmas.')->group(function () {
    
    Route::get('/', [PuskesmasDashboardController::class, 'index'])->name('dashboard');
    
    // Validasi
    Route::get('validasi', [ValidasiController::class, 'index'])->name('validasi.index');
    Route::post('validasi/{pengukuran}/approve', [ValidasiController::class, 'approve'])->name('validasi.approve');
    Route::post('validasi/{pengukuran}/reject', [ValidasiController::class, 'reject'])->name('validasi.reject');

    // WhatsApp Blast
    Route::post('broadcast', [WhatsAppController::class, 'broadcast'])->name('broadcast');
});

/*
|--------------------------------------------------------------------------
| 4. Ibu Portal Routes (Public Token-Based)
|--------------------------------------------------------------------------
| Akses publik via token WhatsApp (Virtual Auth)
*/
Route::middleware(['verify.ibu.token'])->prefix('ibu')->name('ibu.')->group(function () {
    
    // {token} adalah unique string di URL, misal: nutrigen.com/ibu/abc-123
    Route::get('{token}', [IbuPortalController::class, 'dashboard'])->name('dashboard');
    Route::get('{token}/pertumbuhan/{balita}', [IbuPortalController::class, 'pertumbuhan'])->name('pertumbuhan');
    
});
```

## Standar Penamaan
- **Prefix URL**: Gunakan `kader/`, `puskesmas/`, `ibu/` untuk memisahkan segment.
- **Route Names**: Gunakan dot notation `resource.action` (contoh: `balita.index`). Hal ini sudah diterapkan 100% pada file Blade Frontend Freeze.
- **Route Model Binding**: Selalu gunakan object model parameter (contoh `{balita}` alih-alih `{id}`) agar Laravel otomatis meng-inject instance model Eloquent.
