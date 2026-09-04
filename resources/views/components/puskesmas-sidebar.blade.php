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
                <span class="text-[17px] font-extrabold tracking-tight text-slate-900 truncate leading-none">NutriGen</span>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest truncate mt-0.5">Puskesmas</span>
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
            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-teal-50 to-emerald-100 border border-teal-200 text-teal-700 flex items-center justify-center font-bold text-sm shrink-0 group-hover:border-teal-400 transition-colors">
                {{ strtoupper(substr(Auth::user()->name ?? 'D', 0, 1)) }}
            </div>
            <div class="flex-1 min-w-0" x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms>
                <p class="text-[13px] font-extrabold text-slate-800 truncate leading-tight">{{ Auth::user()->name ?? 'Dr. Gizi' }}</p>
                <p class="text-[11px] text-slate-500 font-medium truncate mt-0.5">{{ Auth::user()->puskesmas->nama ?? 'Puskesmas Induk' }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 overflow-y-auto hide-scrollbar py-4 px-3 space-y-1.5">
        
        @php
            $menus = [
                'Operasional Harian' => [
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
                ],
                'Sistem & Manajemen' => [
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
                ]
            ];
        @endphp

        <div class="flex flex-col gap-1">
            @foreach($menus as $groupName => $groupItems)
                <div class="mb-1 mt-3 first:mt-0 px-3 transition-opacity duration-200" x-show="!sidebarCollapsed">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.15em]">{{ $groupName }}</span>
                </div>
                
                @foreach($groupItems as $menu)
                    @php 
                        $isActive = request()->is($menu['pattern']); 
                        $activeClass = $isActive 
                            ? 'bg-teal-50 text-teal-800 font-bold shadow-sm border border-teal-100/50' 
                            : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 font-semibold border border-transparent';
                    @endphp
                    <a href="{{ route($menu['route']) }}" 
                       class="relative group flex items-center gap-3.5 rounded-xl px-3 py-2.5 transition-all duration-200 {{ $activeClass }}"
                       :class="{ 'justify-center px-0': sidebarCollapsed }"
                       title="{{ $menu['label'] }}">
                       
                        @if($isActive)
                            <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-teal-500 rounded-r-full transition-opacity duration-200" x-show="!sidebarCollapsed"></div>
                        @endif

                        <i class="ph-bold {{ $menu['icon'] }} text-[20px] {{ $isActive ? 'text-teal-600' : 'text-slate-400' }} group-hover:scale-110 group-active:scale-95 transition-transform duration-200"></i>
                        <span class="text-[13.5px] truncate" x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms>
                            {{ $menu['label'] }}
                        </span>
                        
                        @if($menu['route'] === 'puskesmas.validasi' && ($pendingValidationCount ?? 0) > 0)
                            <span x-show="!sidebarCollapsed" class="ml-auto bg-rose-100 text-rose-600 py-0.5 px-2 rounded-full text-[10px] font-bold">
                                {{ $pendingValidationCount }}
                            </span>
                            <!-- Red dot for collapsed mode -->
                            <span x-show="sidebarCollapsed" class="absolute top-2 right-2 w-2 h-2 bg-rose-500 rounded-full border-2 border-white"></span>
                        @endif
                    </a>
                @endforeach
            @endforeach
        </div>
    </nav>

    <!-- Footer / Collapse & Logout -->
    <div class="p-3 border-t border-slate-100 shrink-0 flex flex-col gap-1.5">
        
        <!-- Toggle Sidebar (Desktop Only) -->
        <button @click="sidebarCollapsed = !sidebarCollapsed" 
                class="group hidden lg:flex items-center gap-3 w-full rounded-xl px-3 py-2.5 text-slate-500 hover:bg-slate-50 hover:text-slate-800 transition-colors"
                :class="{ 'justify-center px-0': sidebarCollapsed }"
                title="Perkecil Menu">
            <i class="ph-bold ph-arrows-left-right text-[18px] group-hover:scale-110 transition-transform duration-200" :class="{ 'rotate-180': sidebarCollapsed }"></i>
            <span class="text-[13px] font-bold truncate" x-show="!sidebarCollapsed">Lipat Menu</span>
        </button>

        <!-- Logout -->
        <form action="{{ route('logout') }}" method="POST" class="w-full" onsubmit="if(window.NutriAlert && typeof window.NutriAlert.confirm === 'function'){ event.preventDefault(); const form = this; window.NutriAlert.confirm('Keluar dari Akun?', 'Apakah Anda yakin ingin keluar dari sistem?', 'Keluar', 'Batal').then((r) => { if(r.isConfirmed) form.submit(); }); return false; } return confirm('Keluar dari sistem?');">
            @csrf
            <button type="submit" 
                    class="group flex items-center gap-3 w-full rounded-xl px-3 py-2.5 text-slate-600 hover:text-rose-600 hover:bg-rose-50 font-bold transition-all duration-200"
                    :class="{ 'justify-center px-0': sidebarCollapsed }"
                    title="Keluar">
                <i class="ph-bold ph-sign-out text-[18px] group-hover:scale-110 group-active:scale-95 transition-transform duration-200"></i>
                <span class="text-[13px] truncate" x-show="!sidebarCollapsed">Keluar Sistem</span>
            </button>
        </form>
    </div>
</aside>
