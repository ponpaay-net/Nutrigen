<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // TINGGI-02 — Gateway WhatsApp.
    // Driver default 'log' TIDAK butuh akun/token/nomor apa pun: pesan hanya
    // dicatat ke notification_logs + log aplikasi (cocok untuk demo tanpa
    // setup eksternal). Untuk kirim sungguhan, isi WA_DRIVER + token di .env:
    //   WA_DRIVER=fonnte  + FONNTE_TOKEN=...
    //   WA_DRIVER=wablas  + WABLAS_TOKEN=...
    'wa' => [
        'driver' => env('WA_DRIVER', 'log'),   // log | fonnte | wablas
        'fonnte_token' => env('FONNTE_TOKEN'),
        'wablas_token' => env('WABLAS_TOKEN'),
        // Pengamanan anti-ban Fonnte / WhatsApp (official + unofficial gateway).
        // Kirim terlalu cepat/identik ke banyak nomor = risiko throttling/ban.
        'throttle' => [
            'delay_seconds'          => env('WA_DELAY_SECONDS', 5),            // jeda antar pesan (detik)
            'batch_size'             => env('WA_BATCH_SIZE', 8),              // kirim N lalu istirahat
            'batch_pause_seconds'    => env('WA_BATCH_PAUSE_SECONDS', 60),    // istirahat antar batch (detik)
            'max_per_number_per_day' => env('WA_MAX_PER_NUMBER_PER_DAY', 1),  // cooldown harian per nomor
        ],
    ],

];
