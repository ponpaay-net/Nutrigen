<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);
$kernel->bootstrap();

$request = \Illuminate\Http\Request::create('/kader/laporan/generate', 'GET', ['periode' => '2026-09']);
$app->instance('request', $request);

$user = \App\Models\User::where('role', 'kader')->first();
auth()->login($user);
$request->setUserResolver(fn() => $user);

$response = $kernel->handle($request);
$html = $response->getContent();

file_put_contents(__DIR__ . '/preview_laporan.html', $html);
echo "HTML written to preview_laporan.html (" . strlen($html) . " bytes)\n";
