<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth overflow-x-hidden">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'NutriGen')</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 rx=%2220%22 fill=%22%2310B981%22/><text x=%2250%22 y=%2272%22 font-size=%2265%22 font-family=%22Arial%22 font-weight=%22bold%22 fill=%22white%22 text-anchor=%22middle%22>N</text></svg>">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <!-- App Styles & Scripts (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Alpine.js Plugins (must load before core) -->
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/intersect@3.13.3/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.13.3/dist/cdn.min.js"></script>
    <!-- Alpine.js Core -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    @stack('head')
</head>
<body class="antialiased bg-slate-50 text-slate-900 selection:bg-emerald-500 selection:text-white">

    <x-public-navbar />

    @yield('content')

</body>
</html>
