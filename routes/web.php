<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================================================
// PUBLIC ROUTES
// ==========================================================================
Route::get('/', function () {
    return view('welcome');
});

Route::get('/refresh-database-nutrigen', function () {
    try {
        Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();
        $tables = ['pengukurans', 'jadwals', 'balitas', 'orang_tuas', 'kaders', 'puskesmas', 'posyandus', 'users'];
        foreach ($tables as $table) {
            if (Illuminate\Support\Facades\Schema::hasTable($table)) {
                Illuminate\Support\Facades\DB::table($table)->truncate();
            }
        }
        Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        Illuminate\Support\Facades\Artisan::call('db:seed', [
            '--class' => 'Database\\Seeders\\DatabaseSeeder',
            '--force' => true,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Database successfully refreshed and reseeded with 80 realistic balitas and posyandu data!',
            'balita_count' => Illuminate\Support\Facades\DB::table('balitas')->count(),
            'user_count' => Illuminate\Support\Facades\DB::table('users')->count(),
            'output' => Illuminate\Support\Facades\Artisan::output(),
        ]);
    } catch (\Throwable $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});

Route::get('/team', function () {
    return view('team');
})->name('team');

Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'super_admin') {
        return redirect()->route('super-admin.dashboard');
    } elseif ($user->role === 'kader') {
        return redirect()->route('kader.dashboard');
    } elseif ($user->role === 'puskesmas') {
        return redirect()->route('puskesmas.dashboard');
    } elseif ($user->role === 'ibu') {
        // KRITIS-02/TINGGI-01: route portal-ibu memakai middleware 'signed',
        // jadi redirect WAJIB memakai temporarySignedRoute + membawa orang_tua.
        $orangTua = \App\Models\OrangTua::where('user_id', $user->id)->first();
        if (!$orangTua || $orangTua->balitas()->count() === 0) {
            return redirect()->route('team')->with('info', 'Belum ada data balita yang tertaut dengan akun Ibu ini.');
        }

        if ($orangTua->balitas()->count() === 1) {
            return redirect()->to(\Illuminate\Support\Facades\URL::temporarySignedRoute(
                'portal-ibu.home',
                now()->addDays(config('portal.link_ttl_days')),
                ['balita' => $orangTua->balitas()->first()->id, 'orang_tua' => $orangTua->id]
            ));
        }

        return redirect()->to(\Illuminate\Support\Facades\URL::temporarySignedRoute(
            'portal-ibu.child-selector',
            now()->addDays(config('portal.link_ttl_days')),
            ['orang_tua' => $orangTua->id]
        ));
    }

    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

// ==========================================================================
// PORTAL SUPER ADMIN (Kemenkes) — Standard Auth + Role Middleware
// ==========================================================================
use App\Http\Controllers\SuperAdmin\SuperAdminController;

Route::prefix('super-admin')->name('super-admin.')->middleware(['web', 'auth', 'prevent-back-history', 'role:super_admin'])->group(function () {
    Route::get('/', function() {
        return redirect()->route('super-admin.dashboard');
    });

    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    // Master Data Puskesmas
    Route::get('/puskesmas', [SuperAdminController::class, 'indexPuskesmas'])->name('puskesmas.index');
    Route::post('/puskesmas', [SuperAdminController::class, 'storePuskesmas'])->name('puskesmas.store');
    Route::put('/puskesmas/{id}', [SuperAdminController::class, 'updatePuskesmas'])->name('puskesmas.update');
    Route::delete('/puskesmas/{id}', [SuperAdminController::class, 'destroyPuskesmas'])->name('puskesmas.destroy');

    // Detail & Export
    Route::get('/puskesmas/export/csv', [SuperAdminController::class, 'exportCsv'])->name('puskesmas.export.csv');
    Route::get('/puskesmas/export/excel', [SuperAdminController::class, 'exportExcel'])->name('puskesmas.export.excel');
    Route::get('/export/pdf', [SuperAdminController::class, 'exportPdf'])->name('export.pdf');
    Route::get('/puskesmas/{id}', [SuperAdminController::class, 'showPuskesmas'])->name('puskesmas.show');
});

// ==========================================================================
// PORTAL PUSKESMAS — Standard Auth + Role Middleware
// ==========================================================================
use App\Http\Controllers\Puskesmas\PuskesmasController;

Route::prefix('puskesmas')->name('puskesmas.')->middleware(['web', 'auth', 'prevent-back-history', 'role:puskesmas'])->group(function () {
    Route::get('/', function() {
        return redirect()->route('puskesmas.dashboard');
    });

    // Dashboard & Metrics
    Route::get('/dashboard', [PuskesmasController::class, 'dashboard'])->name('dashboard');
    Route::get('/balita',    [PuskesmasController::class, 'balita'])->name('balita');
    Route::get('/balita/{id}', [PuskesmasController::class, 'showBalita'])->name('balita.show');
    Route::get('/laporan',   [PuskesmasController::class, 'laporan'])->name('laporan');
    Route::get('/laporan/export-excel', [PuskesmasController::class, 'exportExcel'])->name('laporan.export.excel');
    Route::get('/laporan/export-csv', [PuskesmasController::class, 'exportExcel'])->name('laporan.export.csv');
    Route::get('/laporan/cetak-pdf', [PuskesmasController::class, 'cetakPdf'])->name('laporan.cetak.pdf');
    Route::get('/api/validasi-count', [PuskesmasController::class, 'apiValidasiCount'])->name('api.validasi-count');

    // Legacy mapping (to prevent UI breaking)
    Route::get('/validasi', [PuskesmasController::class, 'validasi'])->name('validasi');
    Route::get('/validasi/{id}/review', [PuskesmasController::class, 'reviewValidasi'])->name('validasi.review');
    Route::get('/validasi/{id}/riwayat', [PuskesmasController::class, 'riwayat'])->name('validasi.riwayat');
    Route::post('/validasi/{id}/approve', [PuskesmasController::class, 'approve'])->name('validasi.approve');
    Route::post('/validasi/{id}/reject', [PuskesmasController::class, 'reject'])->name('validasi.reject');
    Route::get('/posyandu',  [PuskesmasController::class, 'posyandu'])->name('posyandu');
    // Pengaturan Institusi & Petugas
    Route::get('/pengaturan',[PuskesmasController::class, 'pengaturan'])->name('pengaturan');
    Route::put('/pengaturan',[PuskesmasController::class, 'updatePengaturan'])->name('pengaturan.update');
    Route::get('/pengaturan/petugas',[PuskesmasController::class, 'petugas'])->name('pengaturan.petugas');
    Route::put('/pengaturan/petugas',[PuskesmasController::class, 'updatePetugas'])->name('pengaturan.petugas.update');
    Route::get('/pengaturan/keamanan',[PuskesmasController::class, 'keamanan'])->name('pengaturan.keamanan');
    Route::put('/pengaturan/keamanan',[PuskesmasController::class, 'updateKeamanan'])->name('pengaturan.keamanan.update');
    Route::get('/pengaturan/notifikasi',[PuskesmasController::class, 'notifikasi'])->name('pengaturan.notifikasi');
    Route::put('/pengaturan/notifikasi',[PuskesmasController::class, 'updateNotifikasi'])->name('pengaturan.notifikasi.update');
    Route::post('/posyandu', [PuskesmasController::class, 'storePosyandu'])->name('posyandu.store');
    Route::post('/posyandu/{id}/kader', [PuskesmasController::class, 'storeKader'])->name('posyandu.kader.store');
    Route::put('/posyandu/kader/{id}', [PuskesmasController::class, 'updateKader'])->name('posyandu.kader.update');
    Route::delete('/posyandu/kader/{id}', [PuskesmasController::class, 'destroyKader'])->name('posyandu.kader.destroy');
});

// ==========================================================================
// PORTAL KADER — Standard Auth + Role Middleware
// ==========================================================================
use App\Http\Controllers\Kader\KaderController;

Route::prefix('kader')->middleware(['web', 'auth', 'prevent-back-history', 'role:kader'])->group(function () {
    Route::get('/dashboard', [KaderController::class, 'dashboard'])->name('kader.dashboard');

    // Balita CRUD
    Route::get('/balita', [KaderController::class, 'daftarBalita'])->name('balita.index');
    Route::get('/balita/baru', [KaderController::class, 'createBalita'])->name('balita.create');
    Route::post('/balita', [KaderController::class, 'simpanBalita'])->name('balita.store');
    Route::get('/balita/{id}', [KaderController::class, 'profilBalita'])->name('balita.show');
    Route::get('/balita/{id}/ukur', [KaderController::class, 'ukurBalita'])->name('balita.ukur');
    Route::get('/balita/{id}/edit', [KaderController::class, 'editBalita'])->name('balita.edit');
    Route::put('/balita/{id}', [KaderController::class, 'updateBalita'])->name('balita.update');
    Route::delete('/balita/{id}', [KaderController::class, 'hapusBalita'])->name('balita.destroy');

    // Pengukuran CRUD & Validasi Ulang
    Route::get('/pengukuran', [KaderController::class, 'pengukuran'])->name('pengukuran.create');
    Route::post('/pengukuran', [KaderController::class, 'simpanPengukuran'])->name('pengukuran.store');
    Route::put('/pengukuran/{id}', [KaderController::class, 'updatePengukuran'])->name('pengukuran.update');
    Route::delete('/pengukuran/draft/{id}', [KaderController::class, 'hapusPengukuranDraft'])->name('pengukuran.draft.destroy');
    Route::post('/sesi/kirim', [KaderController::class, 'kirimSesiPuskesmas'])->name('sesi.kirim');
    Route::post('/sesi/tarik-kembali', [KaderController::class, 'tarikKembaliSesi'])->name('sesi.tarik-kembali');
    Route::get('/validasi-ulang', [KaderController::class, 'validasiUlang'])->name('kader.validasi-ulang');

    // Jadwal CRUD
    Route::get('/jadwal', [KaderController::class, 'jadwal'])->name('jadwal.index');
    Route::post('/jadwal', [KaderController::class, 'simpanJadwal'])->name('jadwal.store');
    Route::put('/jadwal/{id}', [KaderController::class, 'updateJadwal'])->name('jadwal.update');
    Route::delete('/jadwal/{id}', [KaderController::class, 'hapusJadwal'])->name('jadwal.destroy');
    Route::post('/jadwal/{id}/notif', [KaderController::class, 'kirimNotifikasiJadwal'])->name('jadwal.notif');
    Route::get('/laporan', [KaderController::class, 'laporan'])->name('laporan.index');
    Route::post('/laporan/generate', [KaderController::class, 'generatePdf'])->name('laporan.generate');
    Route::get('/laporan/generate', [KaderController::class, 'generatePdf']); // In case accessed directly
    Route::get('/laporan/export-excel', [KaderController::class, 'exportExcel'])->name('laporan.export.excel');
    Route::get('/profil', [KaderController::class, 'profilKader'])->name('kader.profil');
    Route::get('/profil/edit', [KaderController::class, 'editProfilKader'])->name('kader.profil.edit');
    Route::put('/profil', [KaderController::class, 'updateProfilKader'])->name('kader.profil.update');
    Route::get('/profil/keamanan', [KaderController::class, 'keamanan'])->name('kader.keamanan');
    Route::put('/profil/keamanan', [KaderController::class, 'updateKeamanan'])->name('kader.keamanan.update');
});

// ==========================================================================
// PORTAL IBU — Standard Auth + Role Middleware
// ==========================================================================
use App\Http\Controllers\PortalIbu\PortalIbuController;

Route::prefix('portal-ibu')->name('portal-ibu.')->middleware(['web', 'prevent-back-history', 'signed'])->group(function () {
    // URL mapped to user's requested routes, but Name strictly preserved for UI
    Route::get('/pilih-anak', [PortalIbuController::class, 'childSelector'])->name('child-selector');
    Route::get('/dashboard', [PortalIbuController::class, 'home'])->name('home');
    Route::get('/profil-anak', [PortalIbuController::class, 'posyandu'])->name('posyandu');
    Route::get('/riwayat', [PortalIbuController::class, 'growth'])->name('growth');
    Route::get('/grafik', [PortalIbuController::class, 'nutrition'])->name('nutrition');
});


// Local-only shortcut for previewing the portal without a login link.
// Jembatan redirect: men-generate signed URL (dengan orang_tua) lalu
// melempar browser ke halaman portal yang diminta.
if (app()->environment('local')) {
    Route::get('/dev/portal-ibu/{balita}/{page?}', function ($balita, $page = 'home') {
        $routes = [
            'home'       => 'portal-ibu.home',
            'growth'     => 'portal-ibu.growth',
            'nutrition'  => 'portal-ibu.nutrition',
            'posyandu'   => 'portal-ibu.posyandu',
            'pilih-anak' => 'portal-ibu.child-selector',
        ];
        abort_unless(isset($routes[$page]), 404);

        $b = \App\Models\Balita::findOrFail($balita);
        $params = ['balita' => $b->id, 'orang_tua' => $b->orang_tua_id];
        if ($page === 'pilih-anak') {
            unset($params['balita']);
        }

        return redirect()->to(\Illuminate\Support\Facades\URL::temporarySignedRoute(
            $routes[$page],
            now()->addDays(config('portal.link_ttl_days')),
            $params
        ));
    })->middleware('web')->name('dev.portal-ibu');
}
