<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

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
    }
}
