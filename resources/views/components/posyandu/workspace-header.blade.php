@props(['posyandu'])

<div class="bg-[#06667A] rounded-[24px] p-6 lg:p-8 flex flex-col relative overflow-hidden shadow-lg shadow-[#06667A]/10">
    
    <!-- Mobile Back Button -->
    <a href="{{ route('puskesmas.posyandu') }}" class="lg:hidden absolute top-6 right-6 flex items-center justify-center w-10 h-10 rounded-xl bg-white/10 text-white hover:bg-white/20 transition-colors border border-white/20">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
        </svg>
    </a>

    <div class="flex items-start md:items-center gap-5 lg:gap-6 mb-6">
        <!-- Icon -->
        <div class="w-14 h-14 lg:w-16 lg:h-16 rounded-2xl bg-white flex items-center justify-center text-[#06667A] shrink-0 shadow-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-7 h-7 lg:w-8 lg:h-8">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
            </svg>
        </div>
        
        <!-- Info -->
        <div class="flex-1 min-w-0 pr-10 lg:pr-0">
            <div class="flex flex-wrap items-center gap-3 mb-2">
                <h1 class="text-2xl lg:text-3xl font-bold text-white tracking-tight leading-tight truncate">
                    {{ $posyandu['nama'] }}
                </h1>
                <span class="px-2.5 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-[10px] font-bold uppercase tracking-widest border border-emerald-500/30">
                    Aktif
                </span>
            </div>
            
            @php
                $total_balita = $posyandu['stats']['total_balita'] ?? 0;
                $diukur = $posyandu['stats']['diukur_bulan_ini'] ?? 0;
            @endphp
            <p class="text-white/80 text-[13px] leading-relaxed max-w-2xl">
                Posyandu aktif dengan <strong class="text-white">{{ $total_balita }} balita terdaftar</strong> dan mencatat <strong class="text-white">{{ $diukur }} aktivitas pengukuran</strong> bulan ini.
            </p>
        </div>
    </div>
    
    <!-- Badges / Info Pills -->
    <div class="flex flex-wrap gap-3">
        <div class="flex items-center gap-2 bg-white/10 border border-white/20 rounded-xl px-4 py-2.5 text-white text-[12px] font-medium backdrop-blur-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-white/70">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
            </svg>
            Desa {{ $posyandu['desa'] }}
            @if($posyandu['alamat'])
                , {{ $posyandu['alamat'] }}
            @endif
        </div>
        
        <div class="flex items-center gap-2 bg-white/10 border border-white/20 rounded-xl px-4 py-2.5 text-white text-[12px] font-medium backdrop-blur-sm">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-white/70">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
            </svg>
            Berdiri: 12 Jan 2020
        </div>
        
        <div class="flex items-center gap-2 bg-white/10 border border-white/20 rounded-xl px-4 py-2.5 text-white text-[12px] font-medium backdrop-blur-sm">
            <span class="text-white/70 font-bold text-sm leading-none">#</span>
            Kode: POS-{{ str_pad($posyandu['id'], 3, '0', STR_PAD_LEFT) }}
        </div>
    </div>
</div>
