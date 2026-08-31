<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | NutriGen</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 antialiased h-screen flex flex-col items-center justify-center p-6">
    <div class="text-center max-w-md">
        <h1 class="text-9xl font-black text-slate-200 tracking-tighter">404</h1>
        <h2 class="text-2xl font-bold text-slate-800 mt-4 mb-2">Halaman Tidak Ditemukan</h2>
        <p class="text-slate-500 mb-8">Maaf, halaman yang Anda tuju tidak tersedia atau telah dipindahkan.</p>
        <a href="{{ url('/') }}" class="inline-flex items-center justify-center h-12 px-6 rounded-full bg-sky-600 text-white font-bold hover:bg-sky-700 transition-colors shadow-lg shadow-sky-600/20">
            Kembali ke Beranda
        </a>
    </div>
</body>
</html>
