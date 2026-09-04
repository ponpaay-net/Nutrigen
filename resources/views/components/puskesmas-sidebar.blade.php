<!-- Mobile Overlay -->
<div x-show="mobileSidebarOpen" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="mobileSidebarOpen = false"
     class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-sm lg:hidden" 
     style="display: none;"></div>

<!-- Sidebar -->
<aside :class="{ 
            'translate-x-0': mobileSidebarOpen, 
            '-translate-x-full': !mobileSidebarOpen,
            'w-64': !sidebarCollapsed,
            'w-[72px]': sidebarCollapsed
       }"
       class="fixed inset-y-0 left-0 z-50 flex flex-col bg-white border-r border-slate-200 transition-all duration-300 ease-in-out lg:translate-x-0 overflow-hidden shadow-sm lg:shadow-none">

    <!-- Header / Logo -->
    <div class="flex items-center justify-between h-16 shrink-0 px-4 border-b border-slate-100">
        <div class="flex items-center gap-3 overflow-hidden">
            <div class="w-8 h-8 rounded shrink-0 bg-teal-600 text-white flex items-center justify-center font-bold text-lg shadow-sm">
                N
            </div>
            <div class="flex flex-col min-w-0" x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms>
                <span class="text-sm font-extrabold tracking-tight text-slate-900 truncate">NutriGen</span>
                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest truncate">Puskesmas</span>
            </div>
        </div>
        
        <!-- Mobile Close Button -->
        <button @click="mobileSidebarOpen = false" class="lg:hidden p-1.5 text-slate-400 hover:text-slate-700 hover:bg-slate-100 rounded-md">
            <i class="ph-bold ph-x text-lg"></i>
        </button>
    </div>

    <!-- Identity Panel -->
    <div class="p-3 border-b border-slate-100 shrink-0 group">
        <div class="flex items-center gap-3 rounded-lg p-2 transition-colors"
             :class="{ 'hover:bg-slate-50 cursor-pointer': !sidebarCollapsed, 'justify-center': sidebarCollapsed }">
            <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600 font-bold text-xs shrink-0 group-hover:border-teal-300 transition-colors">
                {{ strtoupper(substr(Auth::user()->name ?? 'D', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0" x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms>
                <p class="text-xs font-bold text-slate-800 truncate">{{ Auth::user()->name ?? 'Dr. Gizi' }}</p>
                <p class="text-[10px] text-slate-500 font-medium truncate">{{ Auth::user()->puskesmas->nama ?? 'Puskesmas Induk' }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 overflow-y-auto hide-scrollbar py-4 px-3 space-y-1.5">
        
        @php
            $menus = [
                [
                    'route' => 'puskesmas.dashboard',
                    'icon' => 'ph-squares-four',
                    'label' => 'Dashboard',
                    'pattern' => 'puskesmas/dashboard*'
                ],
                [
                    'route' => 'puskesmas.validasi',
                    'icon' => 'ph-check-square-offset',
                    'label' => 'Validasi Data',
                    'pattern' => 'puskesmas/validasi*'
                ],
                [
                    'route' => 'puskesmas.balita',
                    'icon' => 'ph-baby',
                    'label' => 'Direktori Balita',
                    'pattern' => 'puskesmas/balita*'
                ],
                [
                    'route' => 'puskesmas.posyandu',
                    'icon' => 'ph-buildings',
                    'label' => 'Fasilitas & Kader',
                    'pattern' => 'puskesmas/posyandu*'
                ],
                [
                    'route' => 'puskesmas.laporan',
                    'icon' => 'ph-chart-line-up',
                    'label' => 'Laporan',
                    'pattern' => 'puskesmas/laporan*'
                ],
                [
                    'route' => 'puskesmas.pengaturan',
                    'icon' => 'ph-gear',
                    'label' => 'Pengaturan',
                    'pattern' => 'puskesmas/pengaturan*'
                ],
            ];
        @endphp

        @foreach($menus as $menu)
            @php 
                $isActive = request()->is($menu['pattern']); 
                $activeClass = $isActive 
                    ? 'bg-teal-50 text-teal-700 font-semibold shadow-[inset_2px_0_0_0_#0d9488]' 
                    : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-medium';
            @endphp
            <a href="{{ route($menu['route']) }}" 
               class="flex items-center gap-3 rounded-md px-2.5 py-2 transition-colors {{ $activeClass }}"
               :class="{ 'justify-center px-0': sidebarCollapsed }"
               title="{{ $menu['label'] }}">
                <i class="ph-bold {{ $menu['icon'] }} text-lg {{ $isActive ? 'text-teal-600' : 'text-slate-400' }}"></i>
                <span class="text-xs truncate" x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms>
                    {{ $menu['label'] }}
                </span>
                
                @if($menu['route'] === 'puskesmas.validasi' && ($pendingValidationCount ?? 0) > 0)
                    <span x-show="!sidebarCollapsed" class="ml-auto bg-rose-100 text-rose-600 py-0.5 px-2 rounded-full text-[9px] font-bold">
                        {{ $pendingValidationCount }}
                    </span>
                    <!-- Red dot for collapsed mode -->
                    <span x-show="sidebarCollapsed" class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full border-2 border-white"></span>
                @endif
            </a>
        @endforeach
    </nav>

    <!-- Footer / Collapse & Logout -->
    <div class="p-3 border-t border-slate-100 shrink-0 flex flex-col gap-1.5">
        
        <!-- Toggle Sidebar (Desktop Only) -->
        <button @click="sidebarCollapsed = !sidebarCollapsed" 
                class="hidden lg:flex items-center gap-3 w-full rounded-md px-2.5 py-2 text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition-colors"
                :class="{ 'justify-center px-0': sidebarCollapsed }"
                title="Perkecil Menu">
            <i class="ph-bold ph-arrows-left-right text-lg transition-transform" :class="{ 'rotate-180': sidebarCollapsed }"></i>
            <span class="text-xs font-medium truncate" x-show="!sidebarCollapsed">Lipat Menu</span>
        </button>

        <!-- Logout -->
        <form action="{{ route('logout') }}" method="POST" class="w-full">
            @csrf
            <button type="submit" 
                    class="flex items-center gap-3 w-full rounded-md px-2.5 py-2 text-rose-600 hover:bg-rose-50 font-medium transition-colors"
                    :class="{ 'justify-center px-0': sidebarCollapsed }"
                    title="Keluar">
                <i class="ph-bold ph-sign-out text-lg"></i>
                <span class="text-xs truncate" x-show="!sidebarCollapsed">Keluar Sistem</span>
            </button>
        </form>
    </div>
</aside>
