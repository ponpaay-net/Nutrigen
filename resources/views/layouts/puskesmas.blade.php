<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('page-title', 'Pusat Komando') — Portal Puskesmas NutriGen</title>

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    <!-- Phosphor Icons (SaaS style clean icons) -->
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css"/>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/bold/style.css"/>
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/fill/style.css"/>

    @stack('styles')
</head>

<body class="h-full overflow-hidden text-slate-800 antialiased font-sans" 
      x-data="{ 
          mobileSidebarOpen: false, 
          sidebarCollapsed: localStorage.getItem('puskesmasSidebarCollapsed') === 'true',
          scrolled: false
      }" 
      x-init="$watch('sidebarCollapsed', val => localStorage.setItem('puskesmasSidebarCollapsed', val))">
    
    <div class="flex h-screen w-full bg-slate-50">
        
        <!-- Sidebar -->
        <x-puskesmas-sidebar />

        <!-- Main Column -->
        <div class="flex-1 flex flex-col min-w-0 transition-all duration-300"
             :class="{ 'lg:pl-[72px]': sidebarCollapsed, 'lg:pl-64': !sidebarCollapsed }">
             
            <!-- Topbar (Shared Navbar component) -->
            <x-navbar />

            <!-- Main Content Area -->
            @if(View::hasSection('page-mode') && View::getSection('page-mode') === 'app')
                {{-- APP MODE: Full-height, edge-to-edge, no outer wrapper --}}
                <main class="flex-1 overflow-hidden relative flex flex-col">
                    @yield('content')
                </main>
            @else
                {{-- SCROLL MODE: Padded, scrollable container --}}
                <main @scroll.passive="scrolled = ($event.target.scrollTop > 10)" class="flex-1 overflow-y-auto relative bg-slate-50">
                    <div class="w-full px-4 py-6 sm:px-6 lg:px-8 mx-auto max-w-[1400px]">
                        @yield('content')
                    </div>
                </main>
            @endif

        </div>
        
    </div>

    <x-flash-messages />
    
    <!-- Real-time Validation Checker -->
    <div x-data="{
            currentCount: null,
            newCount: 0,
            showToast: false,
            init() {
                this.checkCount();
                setInterval(() => this.checkCount(), 30000);
            },
            async checkCount() {
                try {
                    const response = await fetch('/puskesmas/api/validasi-count');
                    const data = await response.json();
                    
                    if (this.currentCount === null) {
                        this.currentCount = data.pending;
                    } else if (data.pending > this.currentCount) {
                        this.newCount = data.pending - this.currentCount;
                        this.currentCount = data.pending;
                        this.showToast = true;
                    }
                } catch (error) {
                    console.error('Failed to fetch validasi count', error);
                }
            }
         }"
         class="fixed bottom-6 right-6 z-[99]">
         
         <div x-show="showToast" 
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 translate-y-4"
              x-transition:enter-end="opacity-100 translate-y-0"
              x-transition:leave="transition ease-in duration-200"
              x-transition:leave-start="opacity-100 translate-y-0"
              x-transition:leave-end="opacity-0 translate-y-4"
              class="bg-white border-l-4 border-l-amber-500 shadow-xl shadow-slate-200/50 rounded-xl p-4 flex items-start gap-4 w-80"
              style="display: none;">
             
             <div class="w-10 h-10 rounded-full bg-amber-50 border border-amber-100 flex items-center justify-center shrink-0">
                 <i class="ph-bold ph-bell-ringing text-amber-600 text-xl animate-pulse"></i>
             </div>
             
             <div class="flex-1">
                 <h4 class="text-sm font-bold text-slate-800">Antrean Baru!</h4>
                 <p class="text-xs text-slate-500 mt-1">Ada <span x-text="newCount" class="font-bold text-amber-600"></span> data baru masuk dari Kader Posyandu.</p>
                 <div class="mt-3 flex gap-2">
                     <button @click="window.location.reload()" class="text-xs bg-slate-900 hover:bg-slate-800 text-white font-bold py-2 px-3 rounded-lg transition-colors flex-1">
                         Muat Ulang
                     </button>
                     <button @click="showToast = false" class="text-xs bg-slate-100 hover:bg-slate-200 text-slate-600 font-bold py-2 px-3 rounded-lg transition-colors">
                         Tutup
                     </button>
                 </div>
             </div>
         </div>
    </div>

    @stack('modals')
    @stack('scripts')
</body>
</html>
