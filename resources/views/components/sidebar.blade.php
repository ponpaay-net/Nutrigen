<!-- Mobile Sidebar Backdrop -->
<div x-show="mobileSidebarOpen"
     x-transition:enter="transition-opacity ease-linear duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[100] lg:hidden" @click="mobileSidebarOpen = false" style="display: none;"></div>

@php
    $isPush = request()->is('puskesmas*');

    $activeCls    = 'bg-teal-600 text-white shadow-sm';
    $inactiveCls  = 'text-slate-500 hover:bg-teal-50/70 hover:text-teal-600';
    $indicatorCls = 'bg-teal-300';

    $groups = $isPush ? [
        'Menu Utama' => [
            ['label' => 'Dashboard',        'icon' => 'squares-four',   'route' => 'puskesmas.dashboard', 'active' => request()->routeIs('puskesmas.dashboard')],
            ['label' => 'Validasi',         'icon' => 'shield-check',   'route' => 'puskesmas.validasi',  'active' => request()->routeIs('puskesmas.validasi'), 'badge' => ($validationNotifsCount ?? 0)],
            ['label' => 'Data Balita',      'icon' => 'baby',           'route' => 'puskesmas.balita',    'active' => request()->routeIs('puskesmas.balita')],
            ['label' => 'Posyandu & Kader', 'icon' => 'storefront',     'route' => 'puskesmas.posyandu',  'active' => request()->is('puskesmas/posyandu*')],
        ],
        'Lainnya' => [
            ['label' => 'Laporan',          'icon' => 'chart-line-up',  'route' => 'puskesmas.laporan',   'active' => request()->is('puskesmas/laporan*')],
            ['label' => 'Pengaturan',       'icon' => 'gear-six',       'route' => 'puskesmas.pengaturan','active' => request()->is('puskesmas/pengaturan*')],
        ],
    ] : [
        'Menu Utama' => [
            ['label' => 'Dashboard',        'icon' => 'squares-four',   'route' => 'kader.dashboard', 'active' => request()->routeIs('kader.dashboard')],
            ['label' => 'Data Balita',      'icon' => 'baby',           'route' => 'balita.index',    'active' => request()->routeIs('balita.*')],
            ['label' => 'Jadwal Posyandu',  'icon' => 'calendar-check', 'route' => 'jadwal.index',    'active' => request()->routeIs('jadwal.*')],
        ],
        'Lainnya' => [
            ['label' => 'Laporan',          'icon' => 'chart-line-up',  'route' => 'laporan.index',   'active' => request()->routeIs('laporan.*')],
            ['label' => 'Profil Kader',     'icon' => 'user-circle',    'route' => 'kader.profil',    'active' => request()->routeIs('kader.profil*')],
        ],
    ];
@endphp

<!-- Sidebar Container -->
<aside :class="{
        'translate-x-0 w-[264px]': mobileSidebarOpen,
        '-translate-x-full w-[264px]': !mobileSidebarOpen,
        'lg:translate-x-0': true,
        'lg:w-[264px]': sidebarExpanded,
        'lg:w-[80px]': !sidebarExpanded
    }"
    class="fixed lg:static inset-y-0 left-0 z-[110] flex flex-col h-full bg-white border-r border-slate-200 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] overflow-hidden shrink-0">

    <!-- Brand -->
    <div class="h-[72px] flex items-center shrink-0 px-4 sm:px-5 border-b border-slate-100">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 overflow-hidden">
            <div class="w-8 h-8 shrink-0 flex items-center justify-center">
                <img src="{{ asset('images/logo/logo-nutrigen.png') }}" alt="NutriGen Logo" class="w-full h-full object-contain">
            </div>
            <div class="flex flex-col min-w-0 transition-all duration-200" :class="{ 'opacity-100 translate-x-0': sidebarExpanded, 'lg:opacity-0 lg:w-0 lg:overflow-hidden': !sidebarExpanded }">
                <h1 class="text-[19px] font-black text-slate-900 tracking-tight leading-none whitespace-nowrap">NutriGen</h1>
                <span class="text-[10px] font-semibold text-teal-600 tracking-wider uppercase mt-0.5 whitespace-nowrap">Gizi Balita</span>
            </div>
        </a>
    </div>

    <!-- Navigation -->
    <div class="flex-1 overflow-y-auto overflow-x-hidden hide-scrollbar py-4 px-3 flex flex-col gap-1.5">
        @foreach($groups as $groupLabel => $groupItems)
            <div class="mb-1.5 mt-1 first:mt-0 px-2 transition-all duration-200" :class="{ 'opacity-100': sidebarExpanded, 'lg:opacity-0 lg:h-0 lg:overflow-hidden': !sidebarExpanded }">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-[0.12em]">{{ $groupLabel }}</span>
            </div>

            @foreach($groupItems as $item)
                <a href="{{ route($item['route']) }}"
                   class="relative group flex items-center gap-3.5 px-3 py-2.5 rounded-xl font-semibold text-[13.5px] transition-all duration-200 whitespace-nowrap {{ $item['active'] ? $activeCls : $inactiveCls }}">

                    @if($item['active'])
                        <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 rounded-full {{ $indicatorCls }}"></span>
                    @endif

                    <x-icon name="{{ $item['icon'] }}" :weight="$item['active'] ? 'fill' : 'regular'"
                            class="text-[20px] shrink-0 transition-transform duration-200 group-hover:scale-110 group-hover:-rotate-3" />

                    <span class="truncate transition-all duration-200" :class="{ 'opacity-100 translate-x-0': sidebarExpanded, 'lg:opacity-0 lg:w-0 lg:overflow-hidden': !sidebarExpanded }">{{ $item['label'] }}</span>

                    @if(isset($item['badge']) && $item['badge'] > 0)
                        <span class="ml-auto min-w-[18px] h-[18px] px-1 flex items-center justify-center bg-white/25 text-white text-[10px] rounded-full font-bold transition-all duration-200" :class="{ 'opacity-100': sidebarExpanded, 'lg:opacity-0 lg:hidden': !sidebarExpanded }">{{ $item['badge'] }}</span>
                    @endif
                </a>
            @endforeach
        @endforeach
    </div>

    <!-- Footer Actions: Tentang + Keluar -->
    <div class="border-t border-slate-100 p-3 shrink-0">
        <div class="flex flex-col gap-1">
            <button onclick="window.NutriAlert.success('Versi Sistem', 'NutriGen v1.0.0')"
                    class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-500 hover:bg-slate-50 hover:text-teal-600 font-semibold text-[13px] transition-all duration-200 text-left cursor-pointer">
                <x-icon name="info" weight="bold" class="text-[19px] shrink-0 transition-transform duration-200 group-hover:scale-110" />
                <span class="truncate transition-all duration-200" :class="{ 'opacity-100': sidebarExpanded, 'lg:opacity-0 lg:w-0 lg:overflow-hidden': !sidebarExpanded }">Tentang Aplikasi</span>
            </button>

            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit"
                        class="group flex items-center gap-3 px-3 py-2.5 rounded-xl text-rose-600 hover:bg-rose-50 font-semibold text-[13px] transition-all duration-200 text-left cursor-pointer">
                    <x-icon name="sign-out" weight="bold" class="text-[19px] shrink-0 transition-transform duration-200 group-hover:scale-110" />
                    <span class="truncate transition-all duration-200" :class="{ 'opacity-100': sidebarExpanded, 'lg:opacity-0 lg:w-0 lg:overflow-hidden': !sidebarExpanded }">Keluar Aplikasi</span>
                </button>
            </form>
        </div>

        <!-- Collapse Toggle (Desktop, clear icon) -->
        <div class="hidden lg:block mt-2 pt-2 border-t border-slate-100">
            <button @click="sidebarExpanded = !sidebarExpanded" aria-label="Perkecil / perbesar menu" title="Perkecil / perbesar menu"
                    class="group flex items-center gap-3 w-full px-3 justify-center h-10 rounded-xl bg-slate-50 hover:bg-teal-50 text-slate-500 hover:text-teal-600 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                <x-icon name="caret-double-left" weight="bold" class="text-[18px] transition-transform duration-300 group-hover:scale-110" x-bind:class="{ 'rotate-180': !sidebarExpanded }" />
            </button>
        </div>
    </div>
</aside>
