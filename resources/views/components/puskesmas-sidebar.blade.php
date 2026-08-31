<!-- Sidebar Overlay (Mobile & Desktop) -->
<div id="sidebarOverlay"
    class="fixed inset-0 bg-slate-900/50 z-40 hidden opacity-0 transition-opacity duration-300 lg:hidden"></div>

<aside id="sidebar"
    class="fixed inset-y-0 left-0 w-72 bg-white z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300 ease-in-out flex flex-col h-screen overflow-hidden shadow-2xl border-r border-slate-200">

    <!-- Header: Logo -->
    <div class="flex items-center justify-between px-6 py-6 border-b border-slate-200 bg-white flex-shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-14 h-14 flex items-center justify-center flex-shrink-0 -ml-2">
                <img src="{{ asset('images/logo/logo-nutrigen.png') }}" alt="NutriGen Logo"
                    class="w-full h-full object-contain">
            </div>
            <div class="flex flex-col justify-center">
                <h2 class="text-[20px] font-extrabold tracking-tight leading-none">
                    <span class="text-[#10B981]">Nutri</span><span class="text-slate-900">Gen</span>
                </h2>
                <p class="text-[9px] font-bold text-slate-500 uppercase tracking-[0.2em] mt-1">Portal Puskesmas</p>
            </div>
        </div>
        <button id="closeSidebar" class="p-2 -mr-2 text-slate-400 hover:text-slate-600 transition-colors lg:hidden"
            aria-label="Tutup menu">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <!-- Identity Panel (Profil Petugas) -->
    <div
        class="mx-5 mt-6 mb-4 rounded-2xl bg-gradient-to-b from-slate-50 to-slate-100/50 border border-slate-200/60 shadow-sm flex flex-col overflow-hidden group hover:shadow-sm border border-slate-200/60 transition-shadow duration-300">
        <!-- Top row: Avatar & Identity -->
        <div class="p-4 flex gap-3.5 items-start bg-transparent">
            <div
                class="w-11 h-11 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white flex-shrink-0 shadow-sm border border-slate-200/60 border-2 border-white mt-0.5 transform group-hover:scale-105 transition-transform duration-300">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                    <path fill-rule="evenodd"
                        d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z"
                        clip-rule="evenodd" />
                </svg>
            </div>
            <div class="flex flex-col min-w-0 flex-1">
                <div class="flex items-center gap-2">
                    <span
                        class="font-bold text-slate-800 text-sm truncate leading-tight group-hover:text-emerald-700 transition-colors">{{ Auth::user()->name }}</span>
                </div>
                <div class="flex items-center gap-1.5 mt-1.5">
                    <div
                        class="flex items-center gap-1.5 bg-emerald-50 px-2 py-0.5 rounded-full border border-emerald-200/60 shrink-0 shadow-sm">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span class="text-[9px] font-bold text-emerald-700 uppercase tracking-wider">Online</span>
                    </div>
                    <span class="text-[10px] font-medium text-slate-500 truncate ml-0.5">Petugas
                        {{ Auth::user()->role }}</span>
                </div>
            </div>
        </div>

        <!-- Bottom row: Institusi -->
        <div
            class="px-4 py-3 flex flex-col gap-2.5 bg-white border-t border-slate-200/60 group-hover:bg-slate-50/50 transition-colors duration-300">
            <div class="flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                    </svg>
                </div>
                <span
                    class="text-xs text-slate-700 font-semibold truncate">{{ Auth::user()->puskesmas->nama ?? 'Puskesmas' }}</span>
            </div>
            <div class="flex items-center gap-2.5">
                <div class="w-6 h-6 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-3.5 h-3.5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" />
                    </svg>
                </div>
                <span class="text-[11px] text-slate-500 font-medium tracking-wide">Kode:
                    {{ Auth::user()->puskesmas->kode_faskes ?? '-' }}</span>
            </div>
        </div>
    </div>

    <!-- Menu Utama -->
    <div class="flex flex-col gap-1.5 px-4 py-2 overflow-y-auto flex-1 hide-scrollbar">

        <h3 class="px-3 text-[10px] font-bold tracking-tight text-slate-400 uppercase tracking-widest mb-1 mt-2">Menu
            Utama</h3>

        @php
            $baseClass =
                'flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-300 text-sm group overflow-hidden ';
            $activeClass =
                'bg-gradient-to-r from-emerald-50 to-emerald-100/30 text-emerald-700 font-bold shadow-sm relative border border-emerald-100/50';
            $inactiveClass = 'text-slate-500 hover:text-slate-800 hover:bg-slate-50 font-medium hover:shadow-sm';
        @endphp

        <!-- Dashboard -->
        @php $isActive = request()->routeIs('puskesmas.dashboard'); @endphp
        <a href="{{ route('puskesmas.dashboard') }}"
            class="{{ $baseClass }} {{ $isActive ? $activeClass : $inactiveClass }}">
            @if ($isActive)
                <div
                    class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-500 rounded-r-md shadow-[0_0_10px_rgba(16,185,129,0.5)]">
                </div>
            @endif
            <svg xmlns="http://www.w3.org/2000/svg"
                fill="{{ request()->is('puskesmas', 'puskesmas/dashboard') ? 'currentColor' : 'none' }}"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform duration-300 {{ $isActive ? 'text-emerald-500' : '' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
            </svg>
            <span class="group-hover:translate-x-1 transition-transform duration-300">Dashboard</span>
        </a>

        <!-- Antrean Validasi -->
        @php $isActive = request()->routeIs('puskesmas.validasi'); @endphp
        <a href="{{ route('puskesmas.validasi') }}"
            class="{{ $baseClass }} {{ $isActive ? $activeClass : $inactiveClass }} group flex justify-between items-center w-full">
            @if ($isActive)
                <div
                    class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-500 rounded-r-md shadow-[0_0_10px_rgba(16,185,129,0.5)]">
                </div>
            @endif
            <div class="flex items-center gap-3">
                <svg xmlns="http://www.w3.org/2000/svg"
                    fill="{{ request()->is('puskesmas/validasi*') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24"
                    stroke-width="1.5" stroke="currentColor"
                    class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform duration-300 {{ $isActive ? 'text-emerald-500' : '' }}">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.801 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621.504-1.125 1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                </svg>
                <span class="group-hover:translate-x-1 transition-transform duration-300">Antrean Validasi</span>
            </div>
            <!-- Badge Pending -->
            <div
                class="bg-red-50 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full border border-red-100 group-hover:bg-red-100 group-hover:scale-110 transition-all shadow-sm">
                {{ $pendingValidationCount ?? 0 }}
            </div>
        </a>

        <!-- Direktori Balita -->
        @php $isActive = request()->routeIs('puskesmas.balita'); @endphp
        <a href="{{ route('puskesmas.balita') }}"
            class="{{ $baseClass }} {{ $isActive ? $activeClass : $inactiveClass }}">
            @if ($isActive)
                <div
                    class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-500 rounded-r-md shadow-[0_0_10px_rgba(16,185,129,0.5)]">
                </div>
            @endif
            <svg xmlns="http://www.w3.org/2000/svg"
                fill="{{ request()->is('puskesmas/balita*') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor"
                class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform duration-300 {{ $isActive ? 'text-emerald-500' : '' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            <span class="group-hover:translate-x-1 transition-transform duration-300">Data Balita</span>
        </a>

        <!-- Posyandu & Kader -->
        @php $isActive = request()->is('puskesmas/posyandu*'); @endphp
        <a href="{{ route('puskesmas.posyandu') ?? '/puskesmas/posyandu' }}"
            class="{{ $baseClass }} {{ $isActive ? $activeClass : $inactiveClass }}">
            @if ($isActive)
                <div
                    class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-500 rounded-r-md shadow-[0_0_10px_rgba(16,185,129,0.5)]">
                </div>
            @endif
            <svg xmlns="http://www.w3.org/2000/svg"
                fill="{{ request()->is('puskesmas/posyandu*') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor"
                class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform duration-300 {{ $isActive ? 'text-emerald-500' : '' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
            </svg>
            <span class="group-hover:translate-x-1 transition-transform duration-300">Posyandu & Kader</span>
        </a>

        <!-- Laporan -->
        @php $isActive = request()->is('puskesmas/laporan*'); @endphp
        <a href="{{ route('puskesmas.laporan') ?? '/puskesmas/laporan' }}"
            class="{{ $baseClass }} {{ $isActive ? $activeClass : $inactiveClass }}">
            @if ($isActive)
                <div
                    class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-500 rounded-r-md shadow-[0_0_10px_rgba(16,185,129,0.5)]">
                </div>
            @endif
            <svg xmlns="http://www.w3.org/2000/svg"
                fill="{{ request()->is('puskesmas/laporan*') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor"
                class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform duration-300 {{ $isActive ? 'text-emerald-500' : '' }}">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
            </svg>
            <span class="group-hover:translate-x-1 transition-transform duration-300">Laporan</span>
        </a>

        <h3 class="px-3 text-[10px] font-bold tracking-tight text-slate-400 uppercase tracking-widest mb-1 mt-4">Sistem
        </h3>

        <!-- Pengaturan -->
        @php $isActive = request()->is('puskesmas/pengaturan*'); @endphp
        <a href="{{ route('puskesmas.pengaturan') ?? '/puskesmas/pengaturan' }}"
            class="{{ $baseClass }} {{ $isActive ? $activeClass : $inactiveClass }}">
            @if ($isActive)
                <div
                    class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-emerald-500 rounded-r-md shadow-[0_0_10px_rgba(16,185,129,0.5)]">
                </div>
            @endif
            <svg xmlns="http://www.w3.org/2000/svg"
                fill="{{ request()->is('puskesmas/pengaturan*') ? 'currentColor' : 'none' }}" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="currentColor"
                class="w-5 h-5 flex-shrink-0 group-hover:scale-110 transition-transform duration-300 {{ $isActive ? 'text-emerald-500' : '' }}">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            <span class="group-hover:translate-x-1 transition-transform duration-300">Pengaturan</span>
        </a>
    </div>

    <!-- Logout — Bottom -->
    <div class="p-4 border-t border-slate-200/80 bg-white flex-shrink-0">
        <form action="{{ route('logout') }}" method="POST"
            onsubmit="if(window.NutriAlert && typeof window.NutriAlert.confirm === 'function'){ event.preventDefault(); const form = this; window.NutriAlert.confirm('Keluar dari Akun?', 'Apakah Anda yakin ingin keluar dari akun NutriGen?', 'Keluar', 'Batal').then((r) => { if(r.isConfirmed) form.submit(); }); return false; } return confirm('Keluar dari Akun NutriGen?');">
            @csrf
            <button type="submit"
                class="w-full flex items-center justify-center gap-3 px-3 py-2.5 rounded-xl text-slate-500 hover:text-red-600 hover:bg-red-50 font-bold transition-all duration-300 focus:outline-none group/logout">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor"
                    class="w-5 h-5 flex-shrink-0 group-hover/logout:-translate-x-1 transition-transform duration-300">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" />
                </svg>
                <span>Keluar dari Sistem</span>
            </button>
        </form>
    </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const toggleBtn = document.getElementById('sidebarToggle');
        const closeBtn = document.getElementById('closeSidebar');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        function toggleSidebar() {
            sidebar.classList.toggle('-translate-x-full');

            if (sidebar.classList.contains('-translate-x-full')) {
                overlay.classList.remove('opacity-100');
                overlay.classList.add('opacity-0');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            } else {
                overlay.classList.remove('hidden');
                setTimeout(() => {
                    overlay.classList.remove('opacity-0');
                    overlay.classList.add('opacity-100');
                }, 10);
            }
        }

        if (toggleBtn) toggleBtn.addEventListener('click', toggleSidebar);
        if (closeBtn) closeBtn.addEventListener('click', toggleSidebar);
        if (overlay) overlay.addEventListener('click', toggleSidebar);

    });
</script>
