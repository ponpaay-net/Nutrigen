@props(['child'])

@php
    $alertConfig = [
        'danger'  => ['bg' => 'bg-rose-50',    'border' => 'border-rose-100',    'text' => 'text-rose-600',   'icon' => 'text-rose-400',   'msg' => 'Data menunjukkan deviasi dari standar. Periksa kembali sebelum menyetujui.'],
        'warning' => ['bg' => 'bg-amber-50',   'border' => 'border-amber-100',   'text' => 'text-amber-700',  'icon' => 'text-amber-400',  'msg' => 'Ada indikator yang perlu perhatian khusus dari petugas.'],
        'success' => ['bg' => 'bg-emerald-50', 'border' => 'border-emerald-100', 'text' => 'text-emerald-700','icon' => 'text-emerald-500','msg' => 'Semua indikator dalam rentang normal. Data siap divalidasi.'],
    ];
    $ac = $alertConfig[$child['statusType']] ?? $alertConfig['success'];

    $headerBg = match($child['statusType']) {
        'danger'  => 'from-rose-500 to-rose-400',
        'warning' => 'from-amber-500 to-orange-400',
        default   => 'from-[#0097B0] to-[#00C4E0]',
    };

    $initials = collect(explode(' ', $child['name']))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
@endphp

{{-- Compact workspace header --}}
<div class="shrink-0">

    {{-- Gradient identity bar --}}
    <div class="relative overflow-hidden bg-gradient-to-r {{ $headerBg }} px-5 py-4">
        <div class="absolute -top-4 -right-4 w-20 h-20 rounded-full bg-white/10 blur-xl pointer-events-none"></div>

        <div class="relative flex items-center justify-between gap-3">
            {{-- Name + meta --}}
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-10 h-10 rounded-xl bg-white/20 backdrop-blur-sm ring-1 ring-white/30 flex items-center justify-center text-white font-black text-sm shrink-0">
                    {{ strtoupper($initials) }}
                </div>
                <div class="min-w-0">
                    <h2 class="text-[15px] font-bold text-white tracking-tight leading-tight truncate">{{ $child['name'] }}</h2>
                    <p class="text-[10.5px] text-white/70 font-medium truncate mt-0.5">
                        {{ $child['age'] }} · Posyandu {{ $child['posyandu'] }} · Kader {{ $child['kader'] }}
                    </p>
                </div>
            </div>

            {{-- Status + date --}}
            <div class="flex flex-col items-end gap-1 shrink-0">
                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[9.5px] font-bold border border-white/30 bg-white/15 text-white uppercase tracking-widest">
                    <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                    {{ $child['statusLabel'] }}
                </span>
                <span class="text-[10px] text-white/60 font-medium">{{ $child['date'] }}</span>
            </div>
        </div>
    </div>

    {{-- Clinical alert — single slim line --}}
    <div class="px-5 py-2 {{ $ac['bg'] }} {{ $ac['border'] }} border-b flex items-center gap-2">
        @if($child['statusType'] === 'success')
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 {{ $ac['icon'] }} shrink-0">
                <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
            </svg>
        @else
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 {{ $ac['icon'] }} shrink-0">
                <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
            </svg>
        @endif
        <p class="text-[11px] font-medium {{ $ac['text'] }} leading-none">{{ $ac['msg'] }}</p>
    </div>

</div>
