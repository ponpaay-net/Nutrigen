@props(['posyandu', 'isActive' => false])

<a href="{{ route('puskesmas.posyandu', ['id' => $posyandu['id']]) }}" 
    class="block text-left w-full transition-all duration-300 relative rounded-2xl border 
    {{ $isActive 
        ? 'bg-white border-[#06667A]/30 border-l-[4px] border-l-[#06667A] shadow-lg shadow-[#06667A]/5 ring-1 ring-[#06667A]/10' 
        : 'bg-white border-slate-200 hover:border-[#06667A]/30 hover:shadow-md hover:shadow-[#06667A]/5' }}">

    <div class="p-5 flex justify-between items-start">
        <div class="flex-1 min-w-0 pr-4 flex flex-col justify-between h-full">
            
            <div>
                {{-- Title & Status --}}
                <div class="flex items-center justify-between mb-1">
                    <h3 class="text-[15px] font-bold text-slate-900 truncate pr-2">
                        {{ $posyandu['nama'] }}
                    </h3>
                    @if($isActive)
                        <span class="shrink-0 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-600 text-[9px] font-bold uppercase tracking-widest border border-emerald-100">
                            Aktif
                        </span>
                    @endif
                </div>
                
                {{-- Location --}}
                <div class="flex items-center gap-1.5 text-[11px] text-slate-500 mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-slate-400 shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                    </svg>
                    <span class="truncate">Desa {{ $posyandu['desa'] }}</span>
                </div>
            </div>

            {{-- Kader Count Pill --}}
            <div class="flex items-center gap-1.5 bg-slate-50 border border-slate-100 px-3 py-1.5 rounded-lg w-max text-[11px] font-semibold text-slate-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-slate-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
                {{ $posyandu['kader_count'] ?? count($posyandu['kaders'] ?? []) }} Kader
            </div>

        </div>
        
        {{-- Total Balita --}}
        <div class="flex flex-col items-center justify-end h-full pt-8 shrink-0 pl-2">
            <span class="text-[26px] font-bold text-slate-900 leading-none mb-1">
                {{ $posyandu['balita_count'] }}
            </span>
            <span class="text-[10px] text-slate-500 font-bold uppercase tracking-widest">
                Balita
            </span>
        </div>
    </div>
</a>
