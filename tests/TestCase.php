<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\ThrottleRequests;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Tidak ada build Vite di lingkungan test (public/build/manifest.json
        // tidak dibangun), jadi semua tampilan yang memakai @vite harus distub
        // agar halaman error/halaman normal bisa dirender tanpa 500.
        // (Solusi standar Laravel untuk feature test tanpa asset ter-build.)
        $this->withoutVite();

        // Form POST/PUT/DELETE di test memakai Laravel HTTP test tanpa token
        // CSRF. Midleware VerifyCsrfToken yang aktif adalah SUBCLASS turunan
        // (App\Http\Middleware\VerifyCsrfToken), bukan base-nya, jadi harus
        // dinonaktifkan via class turunan tersebut.
        $this->withoutMiddleware(VerifyCsrfToken::class);

        // Throttle (rate limit login) TIDAK dinonaktifkan di produksi, tetapi
        // di test banyak request POST yang sama datang berturut-turut dalam
        // hitungan detik dan akan mematikan batas 5/menit (HTTP 429). Agar
        // seluruh suite dapat berjalan, lepas middleware throttle saat testing.
        $this->withoutMiddleware(ThrottleRequests::class);
    }
}
