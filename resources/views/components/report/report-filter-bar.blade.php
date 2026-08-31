@props(['filters', 'posyandus' => [], 'inline' => false])

@php
    $months = [
        '01'=>'Januari', '02'=>'Februari', '03'=>'Maret', '04'=>'April', 
        '05'=>'Mei', '06'=>'Juni', '07'=>'Juli', '08'=>'Agustus', 
        '09'=>'September', '10'=>'Oktober', '11'=>'November', '12'=>'Desember'
    ];
    $currentMonthName = $months[$filters['bulan']] ?? 'Bulan';
    $currentPosyanduName = $filters['posyandu_id'] === 'semua' ? 'Semua Posyandu' : 
        collect($posyandus)->firstWhere('id', $filters['posyandu_id'])['nama'] ?? 'Pilih Posyandu';
@endphp

<form action="{{ route('puskesmas.laporan') }}" method="GET" id="filterForm" class="flex flex-wrap items-center gap-3">
    
    <!-- Custom Dropdown: Bulan -->
    <div class="relative z-30" x-data="{ open: false }">
        <input type="hidden" name="bulan" value="{{ $filters['bulan'] }}" id="input_bulan">
        
        <button type="button" @click="open = !open" @click.away="open = false"
            class="flex items-center justify-between w-40 bg-white border border-slate-200/80 text-slate-700 font-bold text-[13px] rounded-[12px] py-2.5 pl-10 pr-4 shadow-sm hover:shadow hover:border-slate-300 transition-all focus:outline-none focus:ring-4 focus:ring-[#06667A]/15 focus:border-[#06667A]"
            :class="{ 'ring-4 ring-[#06667A]/15 border-[#06667A]': open }">
            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-slate-400" :class="{ 'text-[#06667A]': open }">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                </svg>
            </div>
            <span class="truncate">{{ $currentMonthName }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </button>
        
        <div x-show="open" style="display: none;"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-2 scale-95"
            class="absolute left-0 mt-2 w-48 bg-white border border-slate-100 rounded-[16px] shadow-[0_10px_40px_rgba(0,0,0,0.08)] py-2 z-50 overflow-hidden max-h-64 overflow-y-auto custom-scrollbar">
            
            @foreach($months as $num => $name)
                <button type="button" 
                    onclick="document.getElementById('input_bulan').value = '{{ $num }}'; document.getElementById('filterForm').submit()"
                    class="w-full text-left px-4 py-2 text-[13px] hover:bg-slate-50 transition-colors flex items-center justify-between {{ $filters['bulan'] === $num ? 'font-extrabold text-[#06667A] bg-slate-50' : 'font-semibold text-slate-600' }}">
                    {{ $name }}
                    @if($filters['bulan'] === $num)
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-[#06667A]">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    <!-- Custom Dropdown: Tahun -->
    <div class="relative z-20" x-data="{ open: false }">
        <input type="hidden" name="tahun" value="{{ $filters['tahun'] }}" id="input_tahun">
        
        <button type="button" @click="open = !open" @click.away="open = false"
            class="flex items-center justify-between w-28 bg-white border border-slate-200/80 text-slate-700 font-bold text-[13px] rounded-[12px] py-2.5 px-4 shadow-sm hover:shadow hover:border-slate-300 transition-all focus:outline-none focus:ring-4 focus:ring-[#06667A]/15 focus:border-[#06667A]"
            :class="{ 'ring-4 ring-[#06667A]/15 border-[#06667A]': open }">
            <span>{{ $filters['tahun'] }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </button>
        
        <div x-show="open" style="display: none;"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-2 scale-95"
            class="absolute left-0 mt-2 w-full bg-white border border-slate-100 rounded-[16px] shadow-[0_10px_40px_rgba(0,0,0,0.08)] py-2 z-50">
            
            @foreach(['2026', '2025'] as $year)
                <button type="button" 
                    onclick="document.getElementById('input_tahun').value = '{{ $year }}'; document.getElementById('filterForm').submit()"
                    class="w-full text-left px-4 py-2 text-[13px] hover:bg-slate-50 transition-colors flex items-center justify-between {{ $filters['tahun'] == $year ? 'font-extrabold text-[#06667A] bg-slate-50' : 'font-semibold text-slate-600' }}">
                    {{ $year }}
                    @if($filters['tahun'] == $year)
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-[#06667A]">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </button>
            @endforeach
        </div>
    </div>

    <div class="w-px h-5 bg-slate-200 mx-1 hidden sm:block"></div>

    <!-- Custom Dropdown: Posyandu -->
    <div class="relative z-10" x-data="{ open: false }">
        <input type="hidden" name="posyandu_id" value="{{ $filters['posyandu_id'] }}" id="input_posyandu">
        
        <button type="button" @click="open = !open" @click.away="open = false"
            class="flex items-center justify-between w-64 bg-white border border-slate-200/80 text-[#06667A] font-extrabold text-[13px] rounded-[12px] py-2.5 pl-10 pr-4 shadow-sm hover:shadow hover:border-slate-300 transition-all focus:outline-none focus:ring-4 focus:ring-[#06667A]/15 focus:border-[#06667A]"
            :class="{ 'ring-4 ring-[#06667A]/15 border-[#06667A]': open }">
            <div class="absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none text-[#06667A]">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
            </div>
            <span class="truncate">{{ $currentPosyanduName }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }">
                <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
            </svg>
        </button>
        
        <div x-show="open" style="display: none;"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-2 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-2 scale-95"
            class="absolute left-0 mt-2 w-72 bg-white border border-slate-100 rounded-[16px] shadow-[0_10px_40px_rgba(0,0,0,0.08)] py-2 z-50 overflow-hidden max-h-64 overflow-y-auto custom-scrollbar">
            
            <button type="button" 
                onclick="document.getElementById('input_posyandu').value = 'semua'; document.getElementById('filterForm').submit()"
                class="w-full text-left px-4 py-2.5 text-[13px] hover:bg-slate-50 transition-colors flex items-center justify-between {{ $filters['posyandu_id'] === 'semua' ? 'font-extrabold text-[#06667A] bg-slate-50' : 'font-semibold text-slate-600' }}">
                Semua Posyandu
                @if($filters['posyandu_id'] === 'semua')
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-[#06667A]">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                    </svg>
                @endif
            </button>
            
            <div class="px-4 py-2 my-1 bg-slate-50 border-y border-slate-100 text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">
                Daftar Posyandu
            </div>

            @foreach($posyandus as $posyandu)
                <button type="button" 
                    onclick="document.getElementById('input_posyandu').value = '{{ $posyandu['id'] }}'; document.getElementById('filterForm').submit()"
                    class="w-full text-left px-4 py-2.5 text-[13px] hover:bg-slate-50 transition-colors flex items-center justify-between {{ $filters['posyandu_id'] == $posyandu['id'] ? 'font-extrabold text-[#06667A] bg-slate-50' : 'font-semibold text-slate-600' }}">
                    {{ $posyandu['nama'] }}
                    @if($filters['posyandu_id'] == $posyandu['id'])
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-[#06667A]">
                            <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" />
                        </svg>
                    @endif
                </button>
            @endforeach
        </div>
    </div>
</form>

<style>
    /* Custom Scrollbar for Dropdowns */
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    .custom-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #cbd5e1;
        border-radius: 10px;
    }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb {
        background-color: #94a3b8;
    }
</style>
