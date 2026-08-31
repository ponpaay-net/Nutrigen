<?php

// Konfigurasi Portal Ibu (signed URL access untuk Orang Tua)
return [

    // Masa berlaku link portal yang dikirim ke Ibu setelah pengukuran disetujui (hari)
    'link_ttl_days' => env('PORTAL_LINK_TTL_DAYS', 7),

];
