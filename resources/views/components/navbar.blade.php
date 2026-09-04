@php
    $greetingName = Auth::user()?->kader?->nama ?? Auth::user()?->name ?? 'Kader';
    $greetIsPush = request()->is('puskesmas*');
    $greetRole = $greetIsPush ? (Auth::user()?->puskesmas?->nama ?? 'Puskesmas') : (Auth::user()?->kader?->posyandu?->nama ?? 'Kader Posyandu');
@endphp

<header
    x-data="{ scrolled: false, showMobileSearch: false }"
    @scroll.passive="scrolled = ($event.target.scrollTop > 8)"
    :class="{
        'bg-white border-b border-slate-200 shadow-sm': scrolled,
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
            <input type="text" name="search" placeholder="Cari data balita..."
                class="w-full h-10 pl-10 pr-12 rounded-xl bg-slate-100/80 hover:bg-slate-100 focus:bg-white border border-transparent focus:border-teal-300 focus:ring-4 focus:ring-teal-500/20 text-sm font-medium text-slate-700 placeholder:text-slate-400 transition-all focus:outline-none shadow-sm" />
            <button type="submit" class="absolute right-1.5 top-1/2 -translate-y-1/2 h-7 px-2.5 rounded-lg text-teal-600 hover:bg-teal-50 text-xs font-bold transition-colors focus:outline-none">Cari</button>
        </div>
    </form>

    {{-- RIGHT: notification + profile --}}
    <div x-data="{ openNotif: false, openProfile: false }" class="flex items-center gap-1.5 lg:gap-2.5 ml-auto shrink-0">

        <!-- Mobile Search Toggle -->
        <button @click="showMobileSearch = !showMobileSearch"
            class="md:hidden relative w-10 h-10 flex items-center justify-center text-slate-600 hover:text-teal-600 hover:bg-teal-50 rounded-xl transition-all focus:outline-none"
            aria-label="Cari">
            <x-icon name="magnifying-glass" weight="bold" class="text-[20px]" />
        </button>

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

        <div class="w-px h-6 bg-slate-200 mx-1 hidden sm:block"></div>

        <!-- Profile Dropdown -->
        <div class="relative">
            <button @click="openProfile = !openProfile" @click.outside="openProfile = false"
                :class="{ 'ring-2 ring-teal-500/50 bg-teal-50 border-teal-300': openProfile }"
                class="flex items-center gap-2 p-1.5 rounded-xl bg-white border border-slate-200 hover:border-teal-300 hover:bg-teal-50 shadow-sm transition-all cursor-pointer focus:outline-none focus:ring-4 focus:ring-teal-500/20 group">
                <span class="w-8 h-8 rounded-lg bg-gradient-to-br from-teal-500 to-teal-600 text-white flex items-center justify-center text-sm font-bold shadow-sm overflow-hidden group-hover:scale-105 transition-transform">{{ strtoupper(substr($greetingName, 0, 1)) }}</span>
                <x-icon name="caret-down" weight="bold" class="text-slate-400 text-xs mr-1 group-hover:text-teal-600 transition-colors" />
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
                <form action="{{ route('logout') }}" method="POST" onsubmit="if(window.NutriAlert && typeof window.NutriAlert.confirm === 'function'){ event.preventDefault(); const form = this; window.NutriAlert.confirm('Keluar dari Akun?', 'Apakah Anda yakin ingin keluar dari aplikasi?', 'Keluar', 'Batal').then((r) => { if(r.isConfirmed) form.submit(); }); return false; } return confirm('Keluar dari aplikasi?');">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2 rounded-xl text-rose-600 hover:bg-rose-50 font-semibold text-[13px] transition-colors text-left cursor-pointer">
                        <x-icon name="sign-out" weight="bold" class="text-[15px] text-rose-400" /> Keluar Aplikasi
                    </button>
                </form>
            </div>
        </div>

        <!-- Notification Modal (template x-teleport — proven working) -->
        <template x-teleport="body">
            <div x-show="openNotif" x-cloak class="fixed inset-0 z-[120] flex items-center justify-center p-3 sm:p-6"
                style="display: none;" role="dialog" aria-modal="true" @keydown.escape.window="openNotif = false">

                {{-- Backdrop Blur --}}
                <div x-show="openNotif" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" @click="openNotif = false" class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity"></div>

                {{-- Modal Box --}}
                <div x-show="openNotif" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-3" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-3" @click.stop class="relative w-full max-w-[560px] max-h-[90vh] sm:max-h-[85vh] bg-white rounded-2xl sm:rounded-[28px] shadow-[0_25px_70px_-15px_rgba(0,0,0,0.25)] border border-slate-100 overflow-hidden flex flex-col z-10">

                    {{-- Header --}}
                    <div class="px-4 pt-4 pb-3 sm:px-6 sm:pt-6 sm:pb-4 flex items-center justify-between border-b border-slate-100/80 shrink-0">
                        <div class="flex items-center gap-2.5 sm:gap-3">
                            <div class="w-9 h-9 sm:w-11 sm:h-11 rounded-full {{ $notificationRole === 'puskesmas' ? 'bg-teal-50 border border-teal-100 text-teal-600' : 'bg-rose-50 border border-rose-100 text-rose-500' }} flex items-center justify-center shrink-0">
                                <x-icon name="bell-ringing" weight="fill" class="text-xl" />
                            </div>
                            <div>
                                <h3 class="text-sm sm:text-[17px] font-bold text-slate-900 tracking-tight leading-tight">{{ $notificationRole === 'puskesmas' ? 'Data Baru untuk Validasi' : 'Revisi dari Puskesmas' }}</h3>
                                <p class="text-[10.5px] sm:text-xs text-slate-400 font-medium mt-0.5">{{ $notificationRole === 'puskesmas' ? 'Pengukuran baru dari kader posyandu' : 'Catatan perbaikan data balita' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 sm:gap-2.5">
                            @if ($notificationRole === 'puskesmas' && ($validationNotifsCount ?? 0) > 0)
                                <span class="bg-teal-50 text-teal-600 border border-teal-200/60 text-[9.5px] sm:text-[10.5px] font-bold px-2.5 py-0.5 sm:px-3.5 sm:py-1 rounded-full uppercase tracking-wider">{{ $validationNotifsCount }} PENDING</span>
                            @elseif(isset($revisiNotifsCount) && $revisiNotifsCount > 0)
                                <span class="bg-rose-50 text-rose-600 border border-rose-200/60 text-[9.5px] sm:text-[10.5px] font-bold px-2.5 py-0.5 sm:px-3.5 sm:py-1 rounded-full uppercase tracking-wider">{{ $revisiNotifsCount }} REVISI</span>
                            @endif
                            <button @click="openNotif = false" class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-slate-100 hover:bg-slate-200 text-slate-400 hover:text-slate-700 flex items-center justify-center transition-colors cursor-pointer" aria-label="Tutup modal">
                                <x-icon name="x" weight="bold" class="text-sm" />
                            </button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="px-4 py-3 sm:px-6 sm:py-4 space-y-3 sm:space-y-4 max-h-[calc(90vh-140px)] sm:max-h-[420px] overflow-y-auto hide-scrollbar divide-y divide-slate-100">
                        @if ($notificationRole === 'puskesmas')
                            @forelse($validationNotifs ?? [] as $notif)
                                <a href="{{ route('puskesmas.validasi', ['tab' => 'pending']) }}" class="flex items-start gap-3 group pt-3 first:pt-0 pb-1 cursor-pointer block">
                                    <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 border border-teal-100 flex items-center justify-center shrink-0 font-bold text-xs">{{ strtoupper(substr($notif['balita_nama'], 0, 2)) }}</div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between gap-2">
                                            <h4 class="text-sm font-bold text-slate-900 group-hover:text-teal-700 transition-colors truncate">{{ Str::title($notif['balita_nama']) }}</h4>
                                            <span class="text-[10px] font-semibold text-slate-400 shrink-0">{{ $notif['tanggal'] }}</span>
                                        </div>
                                        <p class="text-[11px] font-semibold text-slate-600 mt-1">Dikirim oleh {{ $notif['kader_nama'] }}</p>
                                        <div class="mt-2 p-2.5 rounded-xl bg-teal-50/70 border border-teal-100 text-[11px] text-teal-900 font-medium">BB {{ $notif['bb'] }} kg / TB {{ $notif['tb'] }} cm &middot; Menunggu validasi</div>
                                    </div>
                                </a>
                            @empty
                                <div class="p-6 sm:p-8 text-center">
                                    <div class="w-11 h-11 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center mx-auto mb-3"><x-icon name="check-circle" weight="fill" class="text-xl" /></div>
                                    <h4 class="text-sm font-bold text-slate-800">Tidak ada data baru</h4>
                                    <p class="text-xs text-slate-500 mt-1">Semua pengukuran kader sudah diproses.</p>
                                </div>
                            @endforelse
                        @else
                            @if (isset($revisiNotifs) && count($revisiNotifs) > 0)
                                @foreach ($revisiNotifs as $notif)
                                    @php
                                        $palettes = [
                                            0 => ['avatar' => 'bg-rose-100/70 text-rose-700 ring-rose-200/60', 'bubble' => 'bg-rose-50/80 border-rose-100 text-rose-900', 'icon' => 'text-rose-500'],
                                            1 => ['avatar' => 'bg-teal-100/70 text-teal-700 ring-teal-200/60', 'bubble' => 'bg-teal-50/80 border-teal-100 text-teal-900', 'icon' => 'text-teal-500'],
                                            2 => ['avatar' => 'bg-amber-100/70 text-amber-700 ring-amber-200/60', 'bubble' => 'bg-amber-50/80 border-amber-100 text-amber-900', 'icon' => 'text-amber-500'],
                                        ];
                                        $pal = $palettes[$loop->index % 3];
                                    @endphp
                                    <a href="{{ route('balita.ukur', $notif['balita_id']) }}" class="flex items-start gap-2.5 sm:gap-3.5 group pt-3 sm:pt-3.5 first:pt-0 pb-1 cursor-pointer block">
                                        <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full {{ $pal['avatar'] }} ring-2 flex items-center justify-center shrink-0 mt-0.5 font-bold text-[11px] sm:text-xs">{{ strtoupper(substr($notif['balita_nama'], 0, 2)) }}</div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex items-center justify-between gap-1.5 sm:gap-2">
                                                <h4 class="text-[13px] sm:text-sm font-bold text-slate-900 group-hover:text-teal-700 transition-colors truncate">{{ Str::title($notif['balita_nama']) }}</h4>
                                                <div class="flex items-center gap-1 text-[10.5px] sm:text-xs font-semibold text-slate-400 group-hover:text-teal-600 transition-colors shrink-0">
                                                    <span>{{ $notif['tanggal'] }}</span>
                                                    <x-icon name="caret-right" weight="bold" class="text-xs group-hover:translate-x-1 transition-transform" />
                                                </div>
                                            </div>
                                            <p class="text-[11.5px] sm:text-xs font-bold text-slate-700 mt-0.5">BB {{ $notif['bb'] }} kg / TB {{ $notif['tb'] }} cm</p>
                                            <div class="mt-2 sm:mt-2.5 p-2.5 sm:p-3 rounded-xl sm:rounded-2xl {{ $pal['bubble'] }} border text-[11px] sm:text-xs leading-relaxed font-medium">
                                                <div class="flex items-start gap-1.5 sm:gap-2">
                                                    <x-icon name="warning-circle" weight="fill" class="{{ $pal['icon'] }} text-sm shrink-0 mt-0.5" />
                                                    <span class="line-clamp-2">{{ $notif['catatan'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @else
                                <div class="p-6 sm:p-8 text-center">
                                    <div class="w-11 h-11 sm:w-12 sm:h-12 rounded-2xl bg-emerald-50 text-emerald-600 border border-emerald-100 flex items-center justify-center mx-auto mb-2.5 sm:mb-3"><x-icon name="check-circle" weight="fill" class="text-xl" /></div>
                                    <h4 class="text-sm font-bold text-slate-800">Semua Data Valid</h4>
                                    <p class="text-[11px] sm:text-xs text-slate-500 font-normal mt-1 max-w-[260px] mx-auto leading-relaxed">Tidak ada catatan revisi balita dari Puskesmas saat ini.</p>
                                </div>
                            @endif
                        @endif
                    </div>

                    {{-- Footer --}}
                    <div class="bg-slate-50/70 border-t border-slate-100 px-4 py-3 sm:px-6 sm:py-4 flex items-center justify-between gap-2 shrink-0">
                        <div class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs text-slate-600 font-medium min-w-0">
                            <x-icon name="info" weight="bold" class="text-emerald-600 text-sm shrink-0" />
                            <span class="hidden sm:inline truncate">{{ $notificationRole === 'puskesmas' ? 'Periksa pengukuran yang masuk dari kader' : 'Pastikan data sudah diperbaiki agar laporan lebih akurat' }}</span>
                            <span class="sm:hidden text-[10.5px] truncate">{{ $notificationRole === 'puskesmas' ? 'Validasi data baru' : 'Perbaiki data balita' }}</span>
                        </div>
                        <a href="{{ $notificationRole === 'puskesmas' ? route('puskesmas.validasi') : route('balita.index', ['filter' => 'ditolak']) }}" class="h-8 sm:h-9 px-3 sm:px-4 rounded-xl bg-teal-50 hover:bg-teal-100 text-teal-800 font-bold text-[11px] sm:text-xs flex items-center gap-1.5 transition-all cursor-pointer shrink-0">
                            <span>Lihat Semua</span>
                            <x-icon name="arrow-right" weight="bold" class="text-xs" />
                        </a>
                    </div>

                </div>
            </div>
        </template>
    </div>

    <!-- Mobile Search Dropdown -->
    <div x-show="showMobileSearch" 
         x-transition.opacity.duration.200ms
         class="absolute top-full left-0 w-full bg-white border-b border-slate-200 p-3 shadow-sm md:hidden" style="display: none;">
        <form action="{{ route('balita.index') }}" method="GET" class="relative w-full">
            <x-icon name="magnifying-glass" weight="bold" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none" />
            <input type="text" name="search" placeholder="Cari data balita..."
                class="w-full h-11 pl-10 pr-4 rounded-xl bg-slate-100/80 focus:bg-white border border-transparent focus:border-teal-300 focus:ring-4 focus:ring-teal-500/20 text-sm font-medium text-slate-700 placeholder:text-slate-400 transition-all focus:outline-none" />
        </form>
    </div>
</header>
