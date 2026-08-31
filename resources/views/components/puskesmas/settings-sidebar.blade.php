@props(['active' => 'profil'])

<!-- LEFT PANEL: Settings Navigation -->
<div class="w-full lg:w-[280px] flex flex-col bg-white border-r border-slate-200/80 shrink-0 overflow-y-auto relative z-20 shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
    <!-- Panel Header with subtle gradient -->
    <div class="relative overflow-hidden px-6 pt-8 pb-6 border-b border-slate-100">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-teal-50 rounded-full blur-2xl pointer-events-none"></div>
        <p class="text-[11px] font-semibold text-teal-600 uppercase tracking-wider mb-1.5 flex items-center gap-2 relative z-10">
            <span class="w-1.5 h-1.5 rounded-full bg-teal-400"></span>
            Puskesmas
        </p>
        <h2 class="text-xl font-bold tracking-tight text-slate-800 relative z-10">Pengaturan</h2>
    </div>

    <!-- Nav Menu -->
    <nav class="p-4 flex flex-col gap-1.5">
        <a href="{{ route('puskesmas.pengaturan') }}" 
           class="group relative flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm transition-all duration-300 {{ $active === 'profil' ? 'bg-gradient-to-r from-teal-50 to-white border border-teal-100/50 shadow-sm text-teal-800 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50 font-medium' }}">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors duration-300 {{ $active === 'profil' ? 'bg-teal-100 text-teal-600 shadow-inner' : 'bg-slate-100 text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600' }}">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="{{ $active === 'profil' ? 'currentColor' : 'none' }}" stroke="{{ $active === 'profil' ? 'none' : 'currentColor' }}" stroke-width="{{ $active === 'profil' ? '0' : '1.5' }}" class="w-5 h-5">
                    <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6zm14.25 6a.75.75 0 01-.75.75h-2.25v2.25a.75.75 0 01-1.5 0v-2.25H10.5v2.25a.75.75 0 01-1.5 0v-2.25H6.75a.75.75 0 010-1.5h2.25V6.75a.75.75 0 011.5 0v2.25h2.25v-2.25a.75.75 0 011.5 0v2.25h2.25a.75.75 0 01.75.75z" clip-rule="evenodd" />
                </svg>
            </div>
            <span>Profil Institusi</span>
            @if($active === 'profil')
                <div class="absolute inset-y-0 left-0 w-1 bg-teal-500 rounded-r-full shadow-[0_0_8px_rgba(20,184,166,0.6)]"></div>
            @endif
        </a>

        <a href="{{ route('puskesmas.pengaturan.petugas') }}" 
           class="group relative flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm transition-all duration-300 {{ $active === 'petugas' ? 'bg-gradient-to-r from-teal-50 to-white border border-teal-100/50 shadow-sm text-teal-800 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50 font-medium' }}">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors duration-300 {{ $active === 'petugas' ? 'bg-teal-100 text-teal-600 shadow-inner' : 'bg-slate-100 text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="{{ $active === 'petugas' ? 'currentColor' : 'none' }}" viewBox="0 0 24 24" stroke-width="{{ $active === 'petugas' ? '0' : '1.5' }}" stroke="{{ $active === 'petugas' ? 'none' : 'currentColor' }}" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
            </div>
            <span>Profil Petugas</span>
            @if($active === 'petugas')
                <div class="absolute inset-y-0 left-0 w-1 bg-teal-500 rounded-r-full shadow-[0_0_8px_rgba(20,184,166,0.6)]"></div>
            @endif
        </a>
        
        <div class="mt-6 mb-2">
            <p class="px-4 text-[11px] font-semibold text-slate-400 uppercase tracking-wider">Lainnya</p>
        </div>

        <a href="{{ route('puskesmas.pengaturan.keamanan') }}" 
           class="group relative flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm transition-all duration-300 {{ $active === 'keamanan' ? 'bg-gradient-to-r from-teal-50 to-white border border-teal-100/50 shadow-sm text-teal-800 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50 font-medium' }}">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors duration-300 {{ $active === 'keamanan' ? 'bg-teal-100 text-teal-600 shadow-inner' : 'bg-slate-100 text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="{{ $active === 'keamanan' ? '2' : '1.5' }}" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                </svg>
            </div>
            <span>Keamanan Akun</span>
            @if($active === 'keamanan')
                <div class="absolute inset-y-0 left-0 w-1 bg-teal-500 rounded-r-full shadow-[0_0_8px_rgba(20,184,166,0.6)]"></div>
            @endif
        </a>
        
        <a href="{{ route('puskesmas.pengaturan.notifikasi') }}" 
           class="group relative flex items-center gap-3.5 px-4 py-3.5 rounded-2xl text-sm transition-all duration-300 {{ $active === 'notifikasi' ? 'bg-gradient-to-r from-teal-50 to-white border border-teal-100/50 shadow-sm text-teal-800 font-semibold' : 'text-slate-600 hover:text-slate-800 hover:bg-slate-50 font-medium' }}">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center transition-colors duration-300 {{ $active === 'notifikasi' ? 'bg-teal-100 text-teal-600 shadow-inner' : 'bg-slate-100 text-slate-400 group-hover:bg-slate-200 group-hover:text-slate-600' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="{{ $active === 'notifikasi' ? '2' : '1.5' }}" stroke="currentColor" class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
            </div>
            <span>Notifikasi</span>
            @if($active === 'notifikasi')
                <div class="absolute inset-y-0 left-0 w-1 bg-teal-500 rounded-r-full shadow-[0_0_8px_rgba(20,184,166,0.6)]"></div>
            @endif
        </a>

    </nav>
</div>
