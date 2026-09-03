@props(['child'])

{{--
  Puskesmas version of the child card.
  Data shape: child['id'], child['nama'], child['nik'], child['jenis_kelamin'],
              child['statusType'] (success|warning|danger), child['statusLabel'],
              child['posyandu']['nama'], child['pengukurans'][0],
              child['ibu']['nama']
--}}

@php
    $statusType = $child['statusType'] ?? 'success';

    $theme = match($statusType) {
        'danger'  => ['avatar' => 'bg-rose-50 text-rose-600',    'dot' => 'bg-rose-500',    'badge' => 'bg-rose-50 text-rose-700 border-rose-200/70'],
        'warning' => ['avatar' => 'bg-amber-50 text-amber-600',  'dot' => 'bg-amber-500',   'badge' => 'bg-amber-50 text-amber-700 border-amber-200/70'],
        default   => ['avatar' => 'bg-teal-50 text-teal-600', 'dot' => 'bg-teal-500', 'badge' => 'bg-teal-50 text-teal-700 border-teal-200/70'],
    };

    $isGirl    = in_array(strtolower($child['jenis_kelamin'] ?? ''), ['p', 'perempuan', 'female']);
    $latest    = count($child['pengukurans'] ?? []) > 0 ? $child['pengukurans'][0] : null;
    $initials  = collect(explode(' ', $child['nama']))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
    
    // Auto-detect route based on segment to support both Puskesmas and Kader
    $showRoute = request()->segment(1) === 'puskesmas' ? route('puskesmas.balita.show', $child['id']) : route('balita.show', $child['id']);
@endphp

<a href="{{ $showRoute }}"
    data-name="{{ strtolower($child['nama']) }}"
    data-nik="{{ strtolower($child['nik'] ?? '') }}"
    class="group w-full text-left flex flex-col bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-0.5 hover:border-teal-600/30 transition-all duration-200 focus:outline-none">

    <div class="p-5 flex flex-col gap-4 h-full">

        {{-- HEADER: Avatar + Name + Meta --}}
        <div class="flex items-start gap-3.5">
            <div class="w-11 h-11 rounded-full {{ $theme['avatar'] }} flex items-center justify-center shrink-0 font-black text-[13px] ring-1 ring-slate-100 shadow-sm mt-0.5">
                {{ strtoupper($initials) }}
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-bold text-[14px] text-slate-900 leading-snug truncate group-hover:text-teal-700 transition-colors">
                    {{ $child['nama'] }}
                </h4>
                <p class="text-[11.5px] text-slate-500 font-medium mt-1 flex items-center gap-1.5 truncate">
                    <span>{{ $isGirl ? 'Perempuan' : 'Laki-laki' }}</span>
                    @if(!empty($child['nik']))
                        <span class="text-slate-300">·</span>
                        <span class="font-mono tracking-wider truncate text-slate-400">{{ $child['nik'] }}</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- STATUS GIZI badge --}}
        <div class="inline-flex items-center gap-1.5 mr-auto px-2.5 py-1 rounded-md {{ $theme['badge'] }} border">
            <span class="w-1.5 h-1.5 rounded-full {{ $theme['dot'] }} shrink-0"></span>
            <span class="text-[10px] font-bold uppercase tracking-wider">{{ $child['statusLabel'] ?? 'Normal' }}</span>
        </div>

        {{-- MEASUREMENT ROW --}}
        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50/50 border border-slate-100/60 mt-1">
            @if($latest)
                <div class="flex-1 min-w-0">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">Terakhir</span>
                    <p class="text-[12px] font-semibold text-slate-800 truncate">{{ date('d M Y', strtotime($latest['created_at'])) }}</p>
                </div>
                <div class="w-px h-7 bg-slate-200/80 shrink-0"></div>
                <div class="flex-1 min-w-0">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-0.5">BB / TB</span>
                    <p class="text-[12px] font-semibold text-slate-800 truncate">{{ $latest['berat_badan'] }} <span class="text-[10.5px] text-slate-500">kg</span> <span class="text-slate-300 mx-0.5">/</span> {{ $latest['tinggi_badan'] }} <span class="text-[10.5px] text-slate-500">cm</span></p>
                </div>
            @else
                <p class="text-[12px] font-medium text-slate-500 w-full text-center py-1">Belum ada data pengukuran</p>
            @endif
        </div>

        {{-- FOOTER: Posyandu + ibu --}}
        <div class="flex items-center justify-between gap-2 mt-auto pt-2 border-t border-slate-100">
            <div class="flex items-center gap-1.5 text-[11px] font-medium text-slate-500 min-w-0">
                <x-icon name="map-pin" weight="fill" class="text-[13px] text-slate-400 shrink-0" />
                <span class="truncate">{{ $child['posyandu']['nama'] ?? '-' }}</span>
            </div>
            <span class="shrink-0 w-6 h-6 rounded-full bg-slate-50 text-slate-400 group-hover:bg-teal-50 group-hover:text-teal-600 flex items-center justify-center transition-colors">
                <x-icon name="caret-right" weight="bold" class="text-[12px]" />
            </span>
        </div>

    </div>
</a>
