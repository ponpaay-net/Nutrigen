<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$kaders = \App\Models\User::where('role', 'kader')
    ->whereIn('name', ['Nurul Fauziah', 'Cut Malahayati'])
    ->get();

foreach ($kaders as $user) {
    echo "Nama: " . $user->name . "\n";
    echo "Email/Username: " . $user->email . "\n";
    echo "---\n";
}
