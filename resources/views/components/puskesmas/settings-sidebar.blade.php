@props(['active' => 'profil'])

<!-- LEFT PANEL: Settings Navigation -->
<div class="w-full lg:w-[280px] flex flex-col bg-slate-50 border-r border-slate-200 shrink-0 overflow-y-auto relative z-20">
    <!-- Panel Header -->
    <div class="px-6 py-6 border-b border-slate-200">
        <h2 class="text-lg font-bold text-slate-900">Pengaturan</h2>
        <p class="text-[12px] font-medium text-slate-500 mt-1">Konfigurasi akun dan sistem Puskesmas</p>
    </div>

    <!-- Nav Menu -->
    <nav class="p-4 flex flex-col gap-1">
        <a href="{{ route('puskesmas.pengaturan') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-bold transition-colors {{ $active === 'profil' ? 'bg-white border border-slate-200 text-teal-700 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/50 border border-transparent' }}">
            <i class="ph-bold ph-buildings text-lg {{ $active === 'profil' ? 'text-teal-600' : 'text-slate-400' }}"></i>
            Profil Institusi
        </a>

        <a href="{{ route('puskesmas.pengaturan.petugas') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-bold transition-colors {{ $active === 'petugas' ? 'bg-white border border-slate-200 text-teal-700 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/50 border border-transparent' }}">
            <i class="ph-bold ph-user-circle text-lg {{ $active === 'petugas' ? 'text-teal-600' : 'text-slate-400' }}"></i>
            Profil Petugas
        </a>
        
        <div class="mt-4 mb-2">
            <p class="px-3 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Lainnya</p>
        </div>

        <a href="{{ route('puskesmas.pengaturan.keamanan') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-bold transition-colors {{ $active === 'keamanan' ? 'bg-white border border-slate-200 text-teal-700 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/50 border border-transparent' }}">
            <i class="ph-bold ph-shield-check text-lg {{ $active === 'keamanan' ? 'text-teal-600' : 'text-slate-400' }}"></i>
            Keamanan Akun
        </a>
        
        <a href="{{ route('puskesmas.pengaturan.notifikasi') }}" 
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-[13px] font-bold transition-colors {{ $active === 'notifikasi' ? 'bg-white border border-slate-200 text-teal-700 shadow-sm' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-200/50 border border-transparent' }}">
            <i class="ph-bold ph-bell-ringing text-lg {{ $active === 'notifikasi' ? 'text-teal-600' : 'text-slate-400' }}"></i>
            Notifikasi
        </a>

    </nav>
</div>
