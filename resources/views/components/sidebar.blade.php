<!-- Mobile Sidebar Backdrop -->
<div x-show="mobileSidebarOpen"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-[100] lg:hidden"
     @click="mobileSidebarOpen = false"
     style="display: none;"></div>

@php
    $isPush = request()->is('puskesmas*');

    // Brand class strings (kept as literals so Tailwind picks them up)
    $activeCls    = $isPush ? 'bg-teal-600 text-white' : 'bg-teal-600 text-white';
    $inactiveCls  = $isPush ? 'text-slate-500 hover:bg-slate-50 hover:text-teal-600' : 'text-slate-500 hover:bg-teal-50 hover:text-teal-600';
    $indicatorCls = $isPush ? 'bg-teal-400' : 'bg-teal-500';
    $brandGrad    = $isPush ? 'from-teal-400 to-teal-600' : 'from-teal-500 to-teal-600';

    $items = $isPush ? [
        ['label' => 'Dashboard',        'icon' => 'squares-four',   'route' => 'puskesmas.dashboard', 'active' => request()->routeIs('puskesmas.dashboard')],
        ['label' => 'Validasi',         'icon' => 'check-circle',   'route' => 'puskesmas.validasi',  'active' => request()->routeIs('puskesmas.validasi'), 'badge' => ($validationNotifsCount ?? 0)],
        ['label' => 'Data Balita',      'icon' => 'users',          'route' => 'puskesmas.balita',    'active' => request()->routeIs('puskesmas.balita')],
        ['label' => 'Posyandu & Kader', 'icon' => 'storefront',     'route' => 'puskesmas.posyandu',  'active' => request()->is('puskesmas/posyandu*')],
        ['label' => 'Laporan',          'icon' => 'chart-bar',      'route' => 'puskesmas.laporan',   'active' => request()->is('puskesmas/laporan*')],
        ['label' => 'Pengaturan',       'icon' => 'gear',           'route' => 'puskesmas.pengaturan','active' => request()->is('puskesmas/pengaturan*')],
    ] : [
        ['label' => 'Dashboard',        'icon' => 'squares-four',   'route' => 'kader.dashboard', 'active' => request()->routeIs('kader.dashboard')],
        ['label' => 'Data Balita',      'icon' => 'users',          'route' => 'balita.index',    'active' => request()->routeIs('balita.*')],
        ['label' => 'Jadwal Posyandu',  'icon' => 'calendar-blank', 'route' => 'jadwal.index',    'active' => request()->routeIs('jadwal.*')],
        ['label' => 'Laporan',          'icon' => 'chart-bar',      'route' => 'laporan.index',   'active' => request()->routeIs('laporan.*')],
        ['label' => 'Profil Kader',     'icon' => 'user',           'route' => 'kader.profil',    'active' => request()->routeIs('kader.profil*')],
    ];
@endphp

<!-- Sidebar Container -->
<aside :class="{
        'translate-x-0 w-[260px]': mobileSidebarOpen,
        '-translate-x-full w-[260px]': !mobileSidebarOpen,
        'lg:translate-x-0': true,
        'lg:w-[260px]': sidebarExpanded,
        'lg:w-[88px]': !sidebarExpanded
    }"
    class="fixed lg:static inset-y-0 left-0 z-[110] flex flex-col h-full bg-white border-r border-slate-200 transition-all duration-300 ease-[cubic-bezier(0.16,1,0.3,1)] overflow-hidden shrink-0">

    <!-- Top Brand Area -->
    <div class="h-[76px] flex items-center shrink-0 px-4 sm:px-5 border-b border-slate-100">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 overflow-hidden">
            <div class="w-8 h-8 shrink-0 flex items-center justify-center">
                <img src="{{ asset('images/logo/logo-nutrigen.png') }}" alt="NutriGen Logo" class="w-full h-full object-contain">
            </div>
            <div class="flex flex-col min-w-0 transition-opacity duration-200"
                 :class="{'opacity-100': sidebarExpanded, 'lg:opacity-0 lg:hidden': !sidebarExpanded}">
                <h1 class="text-[19px] font-black text-slate-900 tracking-tight leading-none whitespace-nowrap">NutriGen</h1>
            </div>
        </a>
    </div>

    <!-- Navigation Area -->
    <div class="flex-1 overflow-y-auto overflow-x-hidden hide-scrollbar py-4 px-3 flex flex-col gap-1.5">
        <div class="mb-2 px-2 transition-opacity duration-200"
             :class="{'opacity-100': sidebarExpanded, 'lg:opacity-0 lg:h-0 lg:overflow-hidden': !sidebarExpanded}">
            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Menu Utama</span>
        </div>

        @foreach($items as $item)
            <a href="{{ route($item['route']) }}"
               class="relative flex items-center gap-3.5 px-3 py-2.5 rounded-xl font-semibold text-[13.5px] transition-all group whitespace-nowrap {{ $item['active'] ? $activeCls : $inactiveCls }}">

                {{-- Left vertical indicator (reference style) --}}
                @if($item['active'])
                    <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-5 rounded-full {{ $indicatorCls }}"></span>
                @endif

                <x-icon name="{{ $item['icon'] }}" :weight="$item['active'] ? 'fill' : 'bold'" class="text-[20px] shrink-0" />
                <span class="truncate transition-opacity duration-200" :class="{'opacity-100': sidebarExpanded, 'lg:opacity-0 lg:w-0 lg:overflow-hidden': !sidebarExpanded}">{{ $item['label'] }}</span>

                @if(isset($item['badge']) && $item['badge'] > 0)
                    <span class="ml-auto bg-white/25 text-white text-[10px] px-1.5 py-0.5 rounded-full font-bold transition-opacity duration-200" :class="{'opacity-100': sidebarExpanded, 'lg:opacity-0 lg:hidden': !sidebarExpanded}">{{ $item['badge'] }}</span>
                @endif
            </a>
        @endforeach
    </div>

    <!-- Collapse Toggle (Desktop Only) -->
    <div class="hidden lg:flex px-4 py-3 border-t border-slate-100 shrink-0">
        <button @click="sidebarExpanded = !sidebarExpanded"
                class="flex items-center gap-3 text-slate-400 hover:text-slate-700 transition-colors w-full p-2 rounded-xl hover:bg-slate-50 focus:outline-none">
            <x-icon name="caret-left" weight="bold" class="text-[18px] shrink-0 transition-transform duration-300" x-bind:class="{ 'rotate-180': !sidebarExpanded }" />
            <span class="text-sm font-semibold whitespace-nowrap transition-opacity duration-200" :class="{'opacity-100': sidebarExpanded, 'lg:opacity-0 lg:w-0 lg:overflow-hidden': !sidebarExpanded}">Collapse Menu</span>
        </button>
    </div>

    <!-- User Profile Area -->
    <div class="p-4 border-t border-slate-100 shrink-0">
        <div class="relative w-full" x-data="{ openProfileMenu: false }">
            <button @click="openProfileMenu = !openProfileMenu" @click.outside="openProfileMenu = false"
                    class="flex items-center gap-3 w-full p-1.5 hover:bg-slate-50 rounded-xl transition-all duration-200 group text-left border border-transparent hover:border-slate-200 cursor-pointer focus:outline-none focus:ring-2 focus:ring-teal-500/20"
                    :class="{ 'justify-center': !sidebarExpanded && window.innerWidth >= 1024 }">

                <div class="w-9 h-9 rounded-full bg-gradient-to-br {{ $brandGrad }} flex items-center justify-center text-white shrink-0 shadow-sm border-2 border-white group-hover:scale-105 transition-all overflow-hidden">
                    <x-icon name="user" weight="fill" class="text-lg" />
                </div>

                <div class="flex flex-col min-w-0 flex-1 transition-opacity duration-200"
                     :class="{'opacity-100': sidebarExpanded, 'lg:opacity-0 lg:w-0 lg:hidden': !sidebarExpanded}">
                    <span class="text-[13px] font-bold text-slate-800 leading-tight truncate transition-colors">{{ Auth::user()->name ?? 'Ibu Kader' }}</span>
                    <span class="text-[11px] font-medium text-slate-500 truncate">{{ $isPush ? (Auth::user()->puskesmas->nama ?? 'Puskesmas') : ($posyanduName ?? 'Posyandu') }}</span>
                </div>

                <x-icon name="dots-three-vertical" weight="bold" class="text-slate-400 shrink-0 transition-opacity duration-200" x-bind:class="{'opacity-100': sidebarExpanded, 'lg:opacity-0 lg:hidden': !sidebarExpanded}" />
            </button>

            <!-- Popup Menu -->
            <div x-show="openProfileMenu"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                 x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                 class="absolute bottom-full left-0 mb-2 w-56 bg-white rounded-2xl shadow-[0_10px_30px_-10px_rgba(0,0,0,0.15)] ring-1 ring-slate-100 p-2 z-[120]"
                 style="display: none;">

                <a href="javascript:void(0)" onclick="window.NutriAlert.success('Versi Sistem', 'NutriGen v1.0.0')" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-emerald-600 font-bold text-[13px] transition-colors group">
                    <x-icon name="info" weight="bold" class="text-[16px] text-slate-400 group-hover:text-emerald-500" />
                    Tentang Aplikasi
                </a>

                <div class="h-px w-full bg-slate-100 my-1"></div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-rose-600 hover:bg-rose-50 font-bold text-[13px] transition-colors group text-left cursor-pointer">
                        <x-icon name="sign-out" weight="bold" class="text-[16px] text-rose-400 group-hover:text-rose-600" />
                        Keluar Aplikasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>
