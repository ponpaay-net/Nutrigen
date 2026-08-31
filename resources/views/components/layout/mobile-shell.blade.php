<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>{{ $title ?? 'Portal Ibu - NutriGen' }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F4F7F9] sm:bg-slate-200 text-slate-800 font-sans antialiased flex justify-center min-h-[100dvh]">
    
    <!-- Mobile First Container -->
    <main class="w-full sm:max-w-[480px] bg-[#F4F7F9] h-[100dvh] relative overflow-hidden flex flex-col sm:shadow-2xl sm:ring-1 sm:ring-slate-200/50">
        {{ $slot }}
    </main>
    
    @stack('scripts')
</body>
</html>
