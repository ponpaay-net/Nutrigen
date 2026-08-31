<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, viewport-fit=cover">

    {{-- page-title is defined per-view via @section('page-title', '...') --}}
    <title>@yield('page-title', 'Beranda') — NutriGen</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <!-- Phosphor Icons (Industry Standard SaaS Icons) -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css"/>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/bold/style.css"/>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/fill/style.css"/>

    @stack('styles')
</head>

<body class="bg-slate-50 text-slate-800 antialiased font-sans overflow-x-hidden">
    
    <!-- Responsive App Container -->
    <div x-data="{ sidebarExpanded: true, mobileSidebarOpen: false }" class="flex h-[100dvh] overflow-hidden">
        
        <!-- Sidebar -->
        <x-sidebar />

        <!-- Main Wrapper -->
        <div x-data="{ scrolled: false }" class="flex flex-col flex-1 w-full min-w-0 overflow-hidden relative bg-slate-50">
            <x-navbar />

            <!-- Main Content Area -->
            <main @scroll.passive="scrolled = ($event.target.scrollTop > 10)" class="flex-1 overflow-y-auto overflow-x-hidden pb-[80px] lg:pb-0 w-full relative">
                <div class="w-full h-full">
                    @yield('content')
                </div>
            </main>

            <!-- Bottom Navigation (Mobile Only) -->
            <x-footer />
        </div>
        
    </div>

    <x-flash-messages />
    @stack('modals')
    @stack('scripts')
</body>
</html>
