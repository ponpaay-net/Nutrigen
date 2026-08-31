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
        'danger'  => ['bar' => 'bg-rose-500',    'avatar' => 'bg-rose-50 text-rose-500',    'dot' => 'bg-rose-500',    'badge' => 'bg-rose-50 text-rose-700 border-rose-200/70',    'text' => 'text-rose-600'],
        'warning' => ['bar' => 'bg-amber-400',   'avatar' => 'bg-amber-50 text-amber-500',  'dot' => 'bg-amber-400',   'badge' => 'bg-amber-50 text-amber-700 border-amber-200/70',  'text' => 'text-amber-600'],
        default   => ['bar' => 'bg-emerald-500', 'avatar' => 'bg-[#E6F8FB] text-[#00A9C0]', 'dot' => 'bg-emerald-400', 'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200/70', 'text' => 'text-emerald-600'],
    };

    $isGirl    = in_array(strtolower($child['jenis_kelamin'] ?? ''), ['p', 'perempuan', 'female']);
    $latest    = count($child['pengukurans'] ?? []) > 0 ? $child['pengukurans'][0] : null;
    $initials  = collect(explode(' ', $child['nama']))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
@endphp

<a href="{{ route('puskesmas.balita.show', $child['id']) }}"
    data-name="{{ strtolower($child['nama']) }}"
    data-nik="{{ strtolower($child['nik'] ?? '') }}"
    class="child-card-wrapper group w-full text-left relative flex flex-col bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs hover:shadow-md hover:-translate-y-0.5 hover:border-slate-300 transition-all duration-200 focus:outline-none">

    {{-- Left status accent bar --}}
    <div class="absolute left-0 top-0 bottom-0 w-[3.5px] {{ $theme['bar'] }}"></div>

    <div class="p-4 pl-5 flex flex-col gap-3 h-full">

        {{-- HEADER: Avatar + Name + Meta --}}
        <div class="flex items-start gap-3">
            <div class="w-10 h-10 rounded-full {{ $theme['avatar'] }} flex items-center justify-center shrink-0 font-black text-sm ring-4 ring-current/10 mt-0.5">
                {{ strtoupper($initials) }}
            </div>
            <div class="flex-1 min-w-0">
                <h4 class="font-bold text-[13.5px] text-slate-900 leading-snug truncate group-hover:text-[#00A9C0] transition-colors">
                    {{ $child['nama'] }}
                </h4>
                <p class="text-[11px] text-slate-500 font-medium mt-0.5 flex items-center gap-1.5 truncate">
                    <span>{{ $isGirl ? 'Perempuan' : 'Laki-laki' }}</span>
                    @if(!empty($child['nik']))
                        <span class="text-slate-300">·</span>
                        <span class="font-mono tracking-wider truncate">{{ $child['nik'] }}</span>
                    @endif
                </p>
            </div>
        </div>

        {{-- STATUS GIZI badge --}}
        <div class="flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full {{ $theme['dot'] }} shrink-0"></span>
            <span class="text-[11px] font-bold {{ $theme['text'] }}">{{ $child['statusLabel'] ?? 'Normal' }}</span>
        </div>

        {{-- MEASUREMENT ROW --}}
        <div class="flex items-center gap-2 p-2.5 rounded-xl bg-slate-50 border border-slate-100">
            @if($latest)
                <div class="flex-1 min-w-0">
                    <span class="text-[9.5px] font-semibold text-slate-400 block">Pengukuran Terakhir</span>
                    <p class="text-[11.5px] font-bold text-slate-800 truncate">{{ date('d M Y', strtotime($latest['created_at'])) }}</p>
                </div>
                <div class="w-px h-6 bg-slate-200 shrink-0"></div>
                <div class="flex-1 min-w-0 pl-1">
                    <span class="text-[9.5px] font-semibold text-slate-400 block">BB / TB</span>
                    <p class="text-[11.5px] font-bold text-slate-800 truncate">{{ $latest['berat_badan'] }} kg / {{ $latest['tinggi_badan'] }} cm</p>
                </div>
            @else
                <p class="text-[11px] text-slate-400 italic">Belum ada pengukuran</p>
            @endif
        </div>

        {{-- FOOTER: Posyandu + ibu --}}
        <div class="flex items-center justify-between gap-2 mt-auto pt-0.5">
            <div class="flex items-center gap-1.5 text-[10.5px] text-slate-500 min-w-0">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 text-slate-400 shrink-0">
                    <path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.145 16.085 16.085 0 001.308-.935c1.137-.92 2.575-2.351 3.602-4.135.283-.498.53-1.02.747-1.553A10.955 10.955 0 0017 10c0-3.866-3.134-7-7-7S3 6.134 3 10c0 1.42.382 2.75 1.049 3.888.248.432.524.848.822 1.244a14.73 14.73 0 002.822 2.701 16.1 16.1 0 001.308.935 5.741 5.741 0 00.281.145l.018.008.006.003zM10 13a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd"/>
                </svg>
                <span class="truncate">{{ $child['posyandu']['nama'] ?? '-' }}</span>
            </div>
            <span class="shrink-0 text-[10px] font-bold text-slate-400 flex items-center gap-1">
                Lihat detail
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3 group-hover:translate-x-0.5 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
                </svg>
            </span>
        </div>

    </div>
</a>
