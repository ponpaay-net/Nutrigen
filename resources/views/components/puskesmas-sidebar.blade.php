<!-- Mobile Overlay -->
<div x-show="mobileSidebarOpen" 
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     @click="mobileSidebarOpen = false"
     class="fixed inset-0 z-40 bg-slate-900/50 backdrop-blur-[2px] lg:hidden" 
     style="display: none;"></div>

<!-- Sidebar -->
<aside :class="{ 
            'translate-x-0': mobileSidebarOpen, 
            '-translate-x-full': !mobileSidebarOpen,
            'w-64': !sidebarCollapsed,
            'w-[74px]': sidebarCollapsed
       }"
       class="fixed inset-y-0 left-0 z-50 flex flex-col bg-white border-r border-slate-200/90 transition-all duration-300 ease-in-out lg:translate-x-0 overflow-hidden shadow-xl lg:shadow-none select-none">

    <!-- Header / Brand Identity -->
    <div class="flex items-center justify-between h-16 shrink-0 px-4 border-b border-slate-100/90 bg-white">
        <a href="{{ route('puskesmas.dashboard') }}" class="flex items-center gap-3 overflow-hidden group">
            <!-- Bespoke Brand Mark: Health & Nutrition Growth Shield -->
            <div class="w-9 h-9 rounded-xl shrink-0 bg-gradient-to-br from-teal-600 via-teal-600 to-emerald-500 text-white flex items-center justify-center shadow-sm shadow-teal-600/25 group-hover:scale-105 transition-transform duration-200">
                <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.3" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
                    <path d="M12 8v8"/>
                    <path d="M8 12h8"/>
                </svg>
            </div>
            <div class="flex flex-col min-w-0" x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms>
                <div class="flex items-center gap-1.5">
                    <span class="text-[17px] font-extrabold tracking-tight text-slate-900 truncate leading-none">NutriGen</span>
                    <span class="text-[9px] font-bold text-teal-700 bg-teal-50 border border-teal-200/70 uppercase tracking-wider px-1.5 py-0.5 rounded">Hub</span>
                </div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest truncate mt-1">Portal Puskesmas</span>
            </div>
        </a>
        
        <!-- Mobile Close Button (Accessible 44px tap area) -->
        <button @click="mobileSidebarOpen = false" 
                class="lg:hidden w-10 h-10 flex items-center justify-center text-slate-400 hover:text-slate-800 hover:bg-slate-100 rounded-xl transition-colors focus:outline-none"
                aria-label="Tutup Menu">
            <i class="ph-bold ph-x text-lg"></i>
        </button>
    </div>

    <!-- Identity Card / Profile Quick Access -->
    <div class="p-3 border-b border-slate-100/90 shrink-0 bg-slate-50/40">
        <a href="{{ route('puskesmas.pengaturan.petugas') }}" 
           class="flex items-center gap-3 rounded-xl p-2 transition-all duration-200 group hover:bg-white hover:border-teal-200 hover:shadow-xs border border-transparent"
           :class="{ 'justify-center': sidebarCollapsed }"
           title="Lihat Profil Petugas">
            <div class="relative shrink-0">
                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-teal-600 to-emerald-600 text-white flex items-center justify-center font-bold text-sm shadow-xs ring-2 ring-white">
                    {{ strtoupper(substr(Auth::user()->name ?? 'P', 0, 1)) }}
                </div>
                <!-- Status indicator: Connected online -->
                <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-500 ring-2 ring-white"></span>
            </div>
            <div class="flex-1 min-w-0" x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms>
                <div class="flex items-center justify-between gap-1">
                    <p class="text-[13px] font-bold text-slate-800 truncate leading-tight group-hover:text-teal-700 transition-colors">
                        {{ Auth::user()->name ?? 'Petugas Puskesmas' }}
                    </p>
                    <i class="ph-bold ph-caret-right text-slate-300 text-xs group-hover:text-teal-600 group-hover:translate-x-0.5 transition-all"></i>
                </div>
                <p class="text-[11px] text-slate-500 font-medium truncate mt-0.5">
                    {{ Auth::user()->puskesmas->nama ?? 'Puskesmas Induk' }}
                </p>
            </div>
        </a>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 overflow-y-auto hide-scrollbar py-3 px-3 space-y-4">
        @php
            $menus = [
                'Operasional Utama' => [
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
                        'pattern' => 'puskesmas/validasi*',
                        'is_urgent' => true
                    ],
                    [
                        'route' => 'puskesmas.balita',
                        'icon' => 'ph-baby',
                        'label' => 'Data Balita',
                        'pattern' => 'puskesmas/balita*'
                    ],
                ],
                'Manajemen & Analisis' => [
                    [
                        'route' => 'puskesmas.posyandu',
                        'icon' => 'ph-buildings',
                        'label' => 'Posyandu & Kader',
                        'pattern' => 'puskesmas/posyandu*'
                    ],
                    [
                        'route' => 'puskesmas.laporan',
                        'icon' => 'ph-chart-line-up',
                        'label' => 'Laporan & Tren',
                        'pattern' => 'puskesmas/laporan*'
                    ],
                    [
                        'route' => '#',
                        'icon' => 'ph-gear-six',
                        'label' => 'Pengaturan',
                        'pattern' => 'puskesmas/pengaturan*',
                        'submenus' => [
                            [
                                'route' => 'puskesmas.pengaturan',
                                'label' => 'Profil Institusi',
                                'pattern' => 'puskesmas/pengaturan'
                            ],
                            [
                                'route' => 'puskesmas.pengaturan.petugas',
                                'label' => 'Profil Petugas',
                                'pattern' => 'puskesmas/pengaturan/petugas*'
                            ],
                            [
                                'route' => 'puskesmas.pengaturan.keamanan',
                                'label' => 'Keamanan Akun',
                                'pattern' => 'puskesmas/pengaturan/keamanan*'
                            ],
                            [
                                'route' => 'puskesmas.pengaturan.notifikasi',
                                'label' => 'Notifikasi Sistem',
                                'pattern' => 'puskesmas/pengaturan/notifikasi*'
                            ]
                        ]
                    ],
                ]
            ];
        @endphp

        @foreach($menus as $groupName => $groupItems)
            <div class="flex flex-col gap-1">
                <!-- Group Label (WCAG AA Compliant contrast) -->
                <div class="px-3 pt-1 pb-1 transition-opacity duration-200" x-show="!sidebarCollapsed">
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">{{ $groupName }}</span>
                </div>

                @foreach($groupItems as $menu)
                    @php 
                        $isActive = request()->is($menu['pattern']); 
                        $hasSubmenus = isset($menu['submenus']);
                    @endphp

                    @if($hasSubmenus)
                        <div x-data="{ open: {{ $isActive ? 'true' : 'false' }} }">
                            <button @click="open = !open; if(sidebarCollapsed) sidebarCollapsed = false;" 
                                    class="w-full relative group flex items-center justify-between gap-3 rounded-xl px-3 py-2.5 min-h-[44px] transition-all duration-200 border {{ $isActive ? 'bg-teal-50/70 text-teal-800 font-bold border-teal-200/70 shadow-xs' : 'text-slate-600 hover:bg-slate-100/70 hover:text-slate-900 font-semibold border-transparent' }}"
                                    :class="{ 'justify-center px-0': sidebarCollapsed }"
                                    title="{{ $menu['label'] }}">
                                <div class="flex items-center gap-3">
                                    <i class="ph-bold {{ $menu['icon'] }} text-[20px] {{ $isActive ? 'text-teal-600' : 'text-slate-500 group-hover:text-slate-700' }} group-hover:scale-105 transition-all duration-200 shrink-0"></i>
                                    <span class="text-[13.5px] truncate" x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms>
                                        {{ $menu['label'] }}
                                    </span>
                                </div>
                                <i class="ph-bold ph-caret-down text-slate-400 text-xs transition-transform duration-200 shrink-0" 
                                   :class="{ 'rotate-180 text-teal-600': open }" 
                                   x-show="!sidebarCollapsed"></i>
                            </button>
                            
                            <!-- Submenu Accordion Panel -->
                            <div x-show="open && !sidebarCollapsed" 
                                 x-collapse>
                                <div class="border-l-2 border-slate-200 ml-5 pl-3 my-1 py-1 space-y-1">
                                    @foreach($menu['submenus'] as $sub)
                                        @php
                                            $isSubActive = request()->is($sub['pattern']) && ($sub['pattern'] !== 'puskesmas/pengaturan' || request()->path() === 'puskesmas/pengaturan');
                                        @endphp
                                        <a href="{{ route($sub['route']) }}" 
                                           class="group flex items-center gap-2 text-[12.5px] py-2 px-3 rounded-lg min-h-[36px] transition-all duration-150 {{ $isSubActive ? 'bg-teal-50 text-teal-800 font-bold border border-teal-200/60 shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70 font-medium' }}">
                                            <span class="w-1.5 h-1.5 rounded-full shrink-0 transition-colors {{ $isSubActive ? 'bg-teal-600' : 'bg-slate-300 group-hover:bg-slate-400' }}"></span>
                                            <span class="truncate">{{ $sub['label'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @else
                        @php
                            $activeClass = $isActive 
                                ? 'bg-teal-50 text-teal-800 font-bold shadow-xs border-teal-200/70' 
                                : 'text-slate-600 hover:bg-slate-100/70 hover:text-slate-900 font-semibold border-transparent';
                        @endphp
                        <a href="{{ route($menu['route']) }}" 
                           class="relative group flex items-center gap-3 rounded-xl px-3 py-2.5 min-h-[44px] transition-all duration-200 border {{ $activeClass }}"
                           :class="{ 'justify-center px-0': sidebarCollapsed }"
                           title="{{ $menu['label'] }}">
                           
                            @if($isActive)
                                <div class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-teal-600 rounded-r-full transition-opacity duration-200" x-show="!sidebarCollapsed"></div>
                            @endif

                            <i class="ph-bold {{ $menu['icon'] }} text-[20px] {{ $isActive ? 'text-teal-600' : 'text-slate-500 group-hover:text-slate-700' }} group-hover:scale-105 transition-all duration-200 shrink-0"></i>
                            
                            <span class="text-[13.5px] truncate" x-show="!sidebarCollapsed" x-transition.opacity.duration.200ms>
                                {{ $menu['label'] }}
                            </span>
                            
                            {{-- Validasi Queue Urgency Badge --}}
                            @if($menu['route'] === 'puskesmas.validasi' && ($pendingValidationCount ?? 0) > 0)
                                <span x-show="!sidebarCollapsed" 
                                      class="ml-auto bg-rose-500 text-white py-0.5 px-2 rounded-full text-[10.5px] font-extrabold tracking-wide shadow-xs shadow-rose-300 animate-pulse">
                                    {{ $pendingValidationCount }}
                                </span>
                                <!-- Red dot indicator for collapsed mode -->
                                <span x-show="sidebarCollapsed" class="absolute top-2.5 right-2.5 w-2.5 h-2.5 bg-rose-500 rounded-full ring-2 ring-white animate-pulse"></span>
                            @endif
                        </a>
                    @endif
                @endforeach
            </div>
        @endforeach
    </nav>

    <!-- Footer / Collapse & Logout -->
    <div class="p-3 border-t border-slate-100/90 shrink-0 flex flex-col gap-1 bg-slate-50/40">
        <!-- Toggle Collapse Sidebar (Desktop Only) -->
        <button @click="sidebarCollapsed = !sidebarCollapsed" 
                class="group hidden lg:flex items-center gap-3 w-full rounded-xl px-3 py-2 min-h-[40px] text-slate-500 hover:bg-slate-100 hover:text-slate-800 transition-colors focus:outline-none"
                :class="{ 'justify-center px-0': sidebarCollapsed }"
                :title="sidebarCollapsed ? 'Perluas Menu' : 'Sembunyikan Menu'">
            <i class="ph-bold ph-sidebar-simple text-[19px] group-hover:text-slate-800 transition-transform duration-200" :class="{ 'rotate-180': sidebarCollapsed }"></i>
            <span class="text-[13px] font-semibold truncate" x-show="!sidebarCollapsed">Lipat Menu</span>
        </button>

        <!-- Logout Form (De-emphasized, Clean & Accessible) -->
        <form action="{{ route('logout') }}" method="POST" class="w-full" onsubmit="if(window.NutriAlert && typeof window.NutriAlert.confirm === 'function'){ event.preventDefault(); const form = this; window.NutriAlert.confirm('Keluar dari Akun?', 'Apakah Anda yakin ingin keluar dari sistem?', 'Keluar', 'Batal').then((r) => { if(r.isConfirmed) form.submit(); }); return false; } return confirm('Keluar dari sistem?');">
            @csrf
            <button type="submit" 
                    class="group flex items-center gap-3 w-full rounded-xl px-3 py-2 min-h-[40px] text-slate-500 hover:text-rose-600 hover:bg-rose-50/80 font-semibold transition-all duration-200 focus:outline-none"
                    :class="{ 'justify-center px-0': sidebarCollapsed }"
                    title="Keluar dari Sistem">
                <i class="ph-bold ph-sign-out text-[19px] text-slate-400 group-hover:text-rose-600 transition-colors"></i>
                <span class="text-[13px] truncate" x-show="!sidebarCollapsed">Keluar Sistem</span>
            </button>
        </form>
    </div>
</aside>
