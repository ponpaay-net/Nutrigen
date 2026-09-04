@props(['active' => 'profil'])

<!-- LEFT PANEL: Settings Navigation -->
<div class="w-full lg:w-[280px] flex flex-col bg-slate-50 border-r border-slate-200 shrink-0 overflow-y-auto relative z-20">
    <!-- Panel Header -->
    <div class="px-6 py-6 border-b border-slate-200">
        <h2 class="text-lg font-bold text-slate-900">Pengaturan</h2>
        <p class="text-[12px] font-medium text-slate-500 mt-1">Konfigurasi akun dan sistem Puskesmas</p>
    </div>

    <!-- Nav Menu -->
    <nav class="p-4 flex flex-col gap-1.5">
        <a href="{{ route('puskesmas.pengaturan') }}" 
           class="group relative flex items-center gap-3 px-4 py-3 rounded-xl text-[13px] font-bold transition-all overflow-hidden {{ $active === 'profil' ? 'bg-white shadow-[0_4px_12px_-4px_rgba(0,0,0,0.05)] border border-slate-100 text-teal-700' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-200/50 border border-transparent' }}">
            @if($active === 'profil')
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-teal-500 to-emerald-500 rounded-l-xl"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-teal-50/80 to-transparent"></div>
            @endif
            <i class="ph-bold ph-buildings text-[18px] relative z-10 {{ $active === 'profil' ? 'text-teal-600' : 'text-slate-400 group-hover:text-slate-500' }}"></i>
            <span class="relative z-10">Profil Institusi</span>
        </a>

        <a href="{{ route('puskesmas.pengaturan.petugas') }}" 
           class="group relative flex items-center gap-3 px-4 py-3 rounded-xl text-[13px] font-bold transition-all overflow-hidden {{ $active === 'petugas' ? 'bg-white shadow-[0_4px_12px_-4px_rgba(0,0,0,0.05)] border border-slate-100 text-teal-700' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-200/50 border border-transparent' }}">
            @if($active === 'petugas')
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-teal-500 to-emerald-500 rounded-l-xl"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-teal-50/80 to-transparent"></div>
            @endif
            <i class="ph-bold ph-user-circle text-[18px] relative z-10 {{ $active === 'petugas' ? 'text-teal-600' : 'text-slate-400 group-hover:text-slate-500' }}"></i>
            <span class="relative z-10">Profil Petugas</span>
        </a>
        
        <div class="mt-5 mb-2 px-3 flex items-center gap-2 opacity-60">
            <div class="h-px bg-slate-300 flex-1"></div>
            <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Keamanan</p>
            <div class="h-px bg-slate-300 flex-1"></div>
        </div>

        <a href="{{ route('puskesmas.pengaturan.keamanan') }}" 
           class="group relative flex items-center gap-3 px-4 py-3 rounded-xl text-[13px] font-bold transition-all overflow-hidden {{ $active === 'keamanan' ? 'bg-white shadow-[0_4px_12px_-4px_rgba(0,0,0,0.05)] border border-slate-100 text-teal-700' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-200/50 border border-transparent' }}">
            @if($active === 'keamanan')
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-teal-500 to-emerald-500 rounded-l-xl"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-teal-50/80 to-transparent"></div>
            @endif
            <i class="ph-bold ph-shield-check text-[18px] relative z-10 {{ $active === 'keamanan' ? 'text-teal-600' : 'text-slate-400 group-hover:text-slate-500' }}"></i>
            <span class="relative z-10">Keamanan Akun</span>
        </a>
        
        <a href="{{ route('puskesmas.pengaturan.notifikasi') }}" 
           class="group relative flex items-center gap-3 px-4 py-3 rounded-xl text-[13px] font-bold transition-all overflow-hidden {{ $active === 'notifikasi' ? 'bg-white shadow-[0_4px_12px_-4px_rgba(0,0,0,0.05)] border border-slate-100 text-teal-700' : 'text-slate-500 hover:text-slate-900 hover:bg-slate-200/50 border border-transparent' }}">
            @if($active === 'notifikasi')
                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-gradient-to-b from-teal-500 to-emerald-500 rounded-l-xl"></div>
                <div class="absolute inset-0 bg-gradient-to-r from-teal-50/80 to-transparent"></div>
            @endif
            <i class="ph-bold ph-bell-ringing text-[18px] relative z-10 {{ $active === 'notifikasi' ? 'text-teal-600' : 'text-slate-400 group-hover:text-slate-500' }}"></i>
            <span class="relative z-10">Notifikasi</span>
        </a>

    </nav>
</div>
