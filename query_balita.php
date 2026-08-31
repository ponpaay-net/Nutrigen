<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$balita = \App\Models\Balita::where('nama', 'like', '%Zhafran Khalid Bambang%')->with(['posyandu.kaders.user', 'pengukurans.kader.user'])->first();
if ($balita) {
    echo "Balita Found: " . $balita->nama . "\n";
    echo "Posyandu: " . ($balita->posyandu ? $balita->posyandu->nama : 'N/A') . "\n";
    
    echo "\nKader di Posyandu ini:\n";
    if ($balita->posyandu) {
        foreach ($balita->posyandu->kaders as $kader) {
            echo "- " . ($kader->user ? $kader->user->name : $kader->nama) . " (ID: " . $kader->id . ")\n";
        }
    }
    
    echo "\nDiukur oleh:\n";
    foreach ($balita->pengukurans as $pengukuran) {
        echo "- Tanggal: " . $pengukuran->tanggal_ukur . " oleh " . ($pengukuran->kader ? ($pengukuran->kader->user ? $pengukuran->kader->user->name : $pengukuran->kader->nama) : 'Unknown') . "\n";
    }
} else {
    echo "Balita not found\n";
}
