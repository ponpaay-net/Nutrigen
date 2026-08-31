@props(['child'])

@php
    $initials = collect(explode(' ', $child['nama']))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
    // Hitung umur dalam bulan berdasarkan tanggal lahir
    $birthDate = new DateTime($child['tanggal_lahir']);
    $today = new DateTime(); 
    $diff = $today->diff($birthDate);
    $ageMonths = ($diff->y * 12) + $diff->m;
    
    // Sinkronisasi dengan riwayat pengukuran terakhir agar data dummy konsisten
    $latestMeasurement = count($child['pengukurans']) > 0 ? $child['pengukurans'][0] : null;
    if ($latestMeasurement) {
        $ageMonths = $latestMeasurement['umur_bulan'];
    }
@endphp

<div class="bg-gradient-to-br from-emerald-500 to-emerald-600 border border-emerald-400/50 p-6 lg:p-8 shrink-0 relative overflow-hidden rounded-[2rem] mx-4 lg:mx-auto max-w-4xl mt-4 lg:mt-8 mb-4 shadow-sm border border-slate-200/60 shadow-emerald-500/20 z-10">
    <!-- Abstract subtle background shape -->
    <div class="absolute top-0 right-0 w-96 h-96 bg-gradient-to-bl from-white/20 via-white/5 to-transparent rounded-bl-full pointer-events-none opacity-80"></div>
    <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
    
    <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-6 relative z-10">
        <div class="flex items-start gap-5">
            <!-- Avatar -->
            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md border border-white/30 text-white font-black text-xl flex items-center justify-center shrink-0 shadow-sm transform rotate-3">
                <span class="-rotate-3 drop-shadow-sm border border-slate-200/60">{{ strtoupper($initials) }}</span>
            </div>
            
            <!-- Profile Info -->
            <div class="flex-1 min-w-0 pt-1">
                <h2 class="text-2xl font-black text-white leading-none truncate mb-3 drop-shadow-sm">{{ $child['nama'] }}</h2>
                
                <div class="flex flex-wrap items-center gap-2 text-[12px] font-bold">
                    <!-- NIK Badge -->
                    <div class="flex items-center gap-1.5 bg-white/15 backdrop-blur-md text-white px-3 py-1 rounded-full border border-white/20 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 opacity-80">
                          <path fill-rule="evenodd" d="M14.5 10a4.5 4.5 0 004.284-5.882c-.105-.324-.51-.391-.752-.15L15.34 6.66a.454.454 0 01-.493.11 3.01 3.01 0 01-1.618-1.616.455.455 0 01.11-.494l2.694-2.692c.24-.241.174-.647-.15-.752a4.5 4.5 0 00-5.873 4.575c.055.873-.128 1.808-.8 2.368l-7.23 6.024a2.724 2.724 0 103.837 3.837l1.114-1.114a2.724 2.724 0 00.771-1.92v-1.92a2.724 2.724 0 00-1.92-.772l-1.115-1.114a.724.724 0 01.082-1.112l1.505-1.13c.56-.42 1.493-.755 2.368-.8z" clip-rule="evenodd" />
                        </svg>
                        <span class="uppercase tracking-wider font-semibold">{{ $child['nik'] }}</span>
                    </div>

                    <!-- Gender Badge -->
                    <div class="flex items-center gap-1.5 bg-white/15 backdrop-blur-md text-white px-3 py-1 rounded-full border border-white/20 shadow-sm">
                        @if($child['jenis_kelamin'] == 'L')
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 opacity-80"><path d="M10 2a3 3 0 100 6 3 3 0 000-6zM3 13.5a5.5 5.5 0 1111 0v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4z"/></svg>
                            <span class="font-semibold">Laki-laki</span>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 opacity-80"><path d="M10 2a3 3 0 100 6 3 3 0 000-6zM3 13.5a5.5 5.5 0 1111 0v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4z"/></svg>
                            <span class="font-semibold">Perempuan</span>
                        @endif
                    </div>

                    <!-- Age Badge -->
                    <div class="flex items-center gap-1.5 bg-white/15 backdrop-blur-md text-white px-3 py-1 rounded-full border border-white/20 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 opacity-80">
                          <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-semibold">{{ $ageMonths }} Bulan</span>
                    </div>
                    
                    <!-- DOB Badge -->
                    <div class="flex items-center gap-1.5 bg-white/15 backdrop-blur-md text-white px-3 py-1 rounded-full border border-white/20 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 opacity-80">
                          <path fill-rule="evenodd" d="M5.75 2a.75.75 0 01.75.75V4h7V2.75a.75.75 0 011.5 0V4h.25A2.75 2.75 0 0118 6.75v8.5A2.75 2.75 0 0115.25 18H4.75A2.75 2.75 0 012 15.25v-8.5A2.75 2.75 0 014.75 4H5V2.75A.75.75 0 015.75 2zm-1 5.5c-.69 0-1.25.56-1.25 1.25v6.5c0 .69.56 1.25 1.25 1.25h10.5c.69 0 1.25-.56 1.25-1.25v-6.5c0-.69-.56-1.25-1.25-1.25H4.75z" clip-rule="evenodd" />
                        </svg>
                        <span class="font-semibold">Lahir: {{ date('d M Y', strtotime($child['tanggal_lahir'])) }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meta Info Bar (Ibu & Posyandu) -->
        <div class="flex flex-col gap-2 shrink-0 sm:items-end">
            <div class="inline-flex items-center gap-2 text-[11.5px] bg-white/15 backdrop-blur-md text-white px-4 py-1.5 rounded-full border border-white/20 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 opacity-80">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                </svg>
                <span class="font-bold">Ibu {{ $child['ibu']['nama'] }}</span>
                <span class="text-white/40 mx-1">|</span>
                <a href="https://wa.me/{{ $child['ibu']['no_hp_wa'] }}" target="_blank" class="text-white hover:text-emerald-100 transition-colors flex items-center gap-1.5 font-bold">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 006 3.75v16.5a2.25 2.25 0 002.25 2.25h7.5A2.25 2.25 0 0018 20.25V3.75a2.25 2.25 0 00-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" /></svg>
                    +{{ $child['ibu']['no_hp_wa'] }}
                </a>
            </div>
            
            <div class="inline-flex items-center gap-2 text-[11.5px] font-bold bg-white/15 backdrop-blur-md text-white px-4 py-1.5 rounded-full border border-white/20 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 opacity-80">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                </svg>
                <span>{{ $child['posyandu']['nama'] }}</span>
            </div>
        </div>
    </div>
</div>
