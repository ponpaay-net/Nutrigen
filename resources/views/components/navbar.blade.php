@php
    $greetingName = Auth::user()?->kader?->nama ?? Auth::user()?->name ?? 'Kader';
    $greetIsPush = request()->is('puskesmas*');
    $greetRole = $greetIsPush ? (Auth::user()?->puskesmas?->nama ?? 'Puskesmas') : (Auth::user()?->kader?->posyandu?->nama ?? 'Kader Posyandu');
@endphp

<header
    x-data="{ scrolled: false }"
    @scroll.passive="scrolled = ($event.target.scrollTop > 8)"
    :class="{
        'bg-white/90 backdrop-blur-md border-b border-slate-200/70 shadow-[0_2px_14px_rgba(15,23,42,0.04)]': scrolled,
        'bg-white border-b border-slate-100': !scrolled
    }"
    class="sticky top-0 z-40 flex items-center gap-3 lg:gap-6 px-4 lg:px-8 h-16 lg:h-[72px] w-full transition-all duration-200">

    {{-- LEFT: hamburger + page title --}}
    <div class="flex items-center gap-2.5 lg:gap-3 min-w-0 shrink-0">
        <button @click="mobileSidebarOpen = true"
            class="p-2 -ml-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-xl transition-all lg:hidden focus:outline-none"
            aria-label="Buka menu">
            <x-icon name="list" weight="bold" class="text-2xl" />
        </button>
        <div class="flex flex-col min-w-0 leading-tight">
            <h1 class="text-[15px] lg:text-[17px] font-bold text-slate-900 tracking-tight truncate">@yield('page-title', 'Dashboard')</h1>
        </div>
    </div>

    {{-- CENTER: search (fills space, functional) --}}
    <form action="{{ route('balita.index') }}" method="GET" class="hidden md:flex flex-1 min-w-0 justify-center">
        <div class="relative w-full max-w-md">
            <x-icon name="magnifying-glass" weight="bold" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none" />
            <input type="text" name="search" placeholder="Cari nama balita…"
                class="w-full h-10 pl-10 pr-12 rounded-xl bg-slate-100/80 hover:bg-slate-100 focus:bg-white border border-transparent focus:border-teal-300 focus:ring-4 focus:ring-teal-500/10 text-sm text-slate-700 placeholder:text-slate-400 transition-all focus:outline-none" />
            <button type="submit" class="absolute right-1.5 top-1/2 -translate-y-1/2 h-7 px-2.5 rounded-lg text-teal-600 hover:bg-teal-50 text-xs font-semibold focus:outline-none">Cari</button>
        </div>
    </form>

    {{-- RIGHT: notification + profile --}}
    <div x-data="{ openNotif: false, openProfile: false }" class="flex items-center gap-2 lg:gap-2.5 ml-auto shrink-0">

        <!-- Notification -->
        <button @click="openNotif = true"
            class="relative w-10 h-10 flex items-center justify-center text-slate-600 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-all group focus:outline-none focus:ring-2 focus:ring-teal-500/20"
            aria-label="Notifikasi">
            <x-icon name="bell" weight="bold" class="text-[21px]" />
            @if ($notificationRole === 'puskesmas' && ($validationNotifsCount ?? 0) > 0)
                <span class="absolute top-1.5 right-1.5 min-w-[18px] h-[18px] px-1 bg-teal-500 text-white text-[10px] font-extrabold rounded-full flex items-center justify-center border-2 border-white shadow"> {{ $validationNotifsCount }}</span>
            @elseif(isset($revisiNotifsCount) && $revisiNotifsCount > 0)
                <span class="absolute top-1.5 right-1.5 min-w-[18px] h-[18px] px-1 bg-rose-500 text-white text-[10px] font-extrabold rounded-full flex items-center justify-center border-2 border-white shadow"> {{ $revisiNotifsCount }}</span>
            @endif
        </button>

        <!-- Profile -->
        <div class="relative">
            <button @click="openProfile = !openProfile" @click.outside="openProfile = false"
                class="flex items-center gap-2.5 pl-1.5 pr-2 py-1.5 rounded-xl bg-white border border-slate-200 hover:border-teal-300 shadow-sm transition-colors cursor-pointer focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                <span class="w-9 h-9 rounded-full bg-gradient-to-br from-teal-500 to-teal-600 text-white flex items-center justify-center text-sm font-bold border-2 border-white shadow-sm overflow-hidden">
                    {{ strtoupper(substr($greetingName, 0, 1)) }}
                </span>
                <span class="hidden sm:flex flex-col text-left leading-tight">
                    <span class="text-[12.5px] font-bold text-slate-800 truncate max-w-[130px]">{{ $greetingName }}</span>
                    <span class="text-[10.5px] text-slate-500 font-medium truncate max-w-[130px]">{{ $greetRole }}</span>
                </span>
                <x-icon name="caret-down" weight="bold" class="text-slate-400 text-xs" />
            </button>

            <div x-show="openProfile"
                 x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 translate-y-1 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                 class="absolute right-0 top-full mt-2 w-52 bg-white rounded-2xl shadow-lg ring-1 ring-slate-100 p-1.5 z-[130]" style="display: none;">
                <div class="px-3 py-2 border-b border-slate-100 mb-1">
                    <p class="text-[13px] font-bold text-slate-900 truncate">{{ $greetingName }}</p>
                    <p class="text-[11px] text-slate-500 truncate">{{ $greetRole }}</p>
                </div>
                <a href="{{ $greetIsPush ? route('puskesmas.pengaturan') : route('kader.profil') }}" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-teal-700 font-semibold text-[13px] transition-colors">
                    <x-icon name="user" weight="bold" class="text-[15px] text-slate-400" /> Profil Saya
                </a>
                <a href="javascript:void(0)" onclick="window.NutriAlert.success('Versi Sistem', 'NutriGen v1.0.0')" class="flex items-center gap-2.5 px-3 py-2 rounded-xl text-slate-600 hover:bg-slate-50 hover:text-emerald-700 font-semibold text-[13px] transition-colors">
                    <x-icon name="info" weight="bold" class="text-[15px] text-slate-400" /> Tentang Aplikasi
                </a>
                <div class="h-px bg-slate-100 my-1"></div>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-rose-600 hover:bg-rose-50 font-semibold text-[13px] transition-colors text-left cursor-pointer">
                        <x-icon name="sign-out" weight="bold" class="text-[15px] text-rose-400" /> Keluar Aplikasi
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
