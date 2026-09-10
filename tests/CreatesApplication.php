<?php

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Application;

trait CreatesApplication
{
    /**
     * Creates the application.
     */
    public function createApplication(): Application
    {
        // KRITIS: Pastikan environment test memakai database TERPISAH sebelum
        // aplikasi di-bootstrap. Tanpa ini, `php artisan test` membaca .env
        // (DB_DATABASE=nutrigen_mod) dan RefreshDatabase menghapus data
        // aplikasi — menyebabkan seluruh akun (termasuk Super Admin Kemenkes)
        // hilang sehingga tidak bisa login.
        //
        // Nilai di-set eksplisit di sini, tidak hanya mengandalkan <env> pada
        // phpunit.xml yang pada sebagian setup tidak diterapkan.
        $testEnv = [
            'APP_ENV'       => 'testing',
            'DB_CONNECTION' => 'mysql',
            'DB_HOST'       => '127.0.0.1',
            'DB_PORT'       => '3306',
            'DB_DATABASE'   => 'nutrigen_test',
            'DB_USERNAME'   => 'root',
            'DB_PASSWORD'   => '',
            'CACHE_DRIVER'  => 'array',
            'SESSION_DRIVER' => 'array',
            'QUEUE_CONNECTION' => 'sync',
            'MAIL_MAILER'   => 'array',
        ];

        foreach ($testEnv as $key => $value) {
            putenv("{$key}={$value}");
            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }

        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}