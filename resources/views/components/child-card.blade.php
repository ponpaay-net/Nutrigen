@props(['balita'])

@php
    $statusType = $balita['status_type'] ?? 'warning';
    $valStatus  = $balita['status_validasi'] ?? 'pending';

    $theme = match($statusType) {
        'success' => ['bar' => 'bg-emerald-400',  'avatar' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',  'badge' => 'bg-emerald-50 text-emerald-700 border-emerald-200',  'dot' => 'bg-emerald-500'],
        'danger'  => ['bar' => 'bg-rose-400',     'avatar' => 'bg-rose-50 text-rose-700 ring-rose-100',            'badge' => 'bg-rose-50 text-rose-700 border-rose-200',            'dot' => 'bg-rose-500'],
        default   => ['bar' => 'bg-amber-400',    'avatar' => 'bg-amber-50 text-amber-700 ring-amber-100',         'badge' => 'bg-amber-50 text-amber-700 border-amber-200',         'dot' => 'bg-amber-400'],
    };

    $isGirl    = in_array(strtolower($balita['gender'] ?? ''), ['p', 'perempuan', 'female']);
    $nik       = $balita['masked_nik'] ?? $balita['nik'] ?? '-';
    $name      = Str::title($balita['name'] ?? 'Balita');
    $initials  = strtoupper(substr($name, 0, 2));
    $gender    = $balita['gender_label'] ?? ($isGirl ? 'Perempuan' : 'Laki-laki');

    $valMeta = match($valStatus) {
        'approved' => ['label' => 'Tervalidasi',    'cls' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'icon' => 'check-circle'],
        'rejected' => ['label' => 'Perlu Revisi',   'cls' => 'bg-rose-50 text-rose-700 border-rose-200',           'icon' => 'warning'],
        default    => ['label' => 'Menunggu Validasi', 'cls' => 'bg-amber-50 text-amber-700 border-amber-200',    'icon' => 'clock'],
    };
@endphp

<div class="group relative flex flex-col h-full bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-[0_1px_3px_rgba(15,23,42,0.05),0_8px_20px_-12px_rgba(15,23,42,0.10)] hover:shadow-[0_2px_6px_rgba(15,23,42,0.07),0_16px_36px_-16px_rgba(15,23,42,0.18)] hover:-translate-y-0.5 hover:border-teal-200 transition-all duration-200">
    <span class="absolute left-0 top-0 bottom-0 w-[3px] {{ $theme['bar'] }}"></span>

    <div class="flex-1 p-4 pl-[18px] flex flex-col gap-3">
        {{-- Header: avatar + identity --}}
        <div class="flex items-start gap-3">
            <div class="w-11 h-11 shrink-0 rounded-full {{ $theme['avatar'] }} ring-2 flex items-center justify-center font-bold text-[13px]">{{ $initials }}</div>
            <div class="flex-1 min-w-0">
                <a href="{{ route('balita.show', $balita['id'] ?? '') }}" class="block font-bold text-slate-900 text-[14.5px] leading-snug truncate group-hover:text-teal-700 transition-colors">{{ $name }}</a>
                <p class="flex items-center gap-1.5 text-[12.5px] text-slate-500 font-medium mt-0.5">
                    <x-icon name="{{ $isGirl ? 'gender-female' : 'gender-male' }}" weight="fill" class="text-[13px] text-slate-400 shrink-0" />
                    <span class="tabular-nums truncate">{{ $balita['age'] ?? '-' }}</span>
                    <span class="text-slate-300 shrink-0">•</span>
                    <span class="truncate">{{ $gender }}</span>
                </p>
                <p class="text-[12px] text-slate-400 font-medium tracking-wide truncate mt-0.5">NIK {{ $nik }}</p>
            </div>
        </div>

        {{-- Status --}}
        <div class="flex items-center gap-2 flex-wrap">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border text-[12px] font-semibold {{ $theme['badge'] }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $theme['dot'] }} shrink-0"></span>
                <span class="truncate">{{ $balita['status'] ?? '—' }}</span>
            </span>
            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full border text-[11px] font-semibold {{ $valMeta['cls'] }}">
                <x-icon name="{{ $valMeta['icon'] }}" weight="fill" class="text-[12px] shrink-0" />
                {{ $valMeta['label'] }}
            </span>
        </div>

        {{-- Last measurement --}}
        <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 flex items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="flex items-center gap-1 text-[11px] font-semibold text-slate-400 uppercase tracking-wide">
                    <x-icon name="calendar" weight="bold" class="text-[13px] shrink-0" /> Pengukuran Terakhir
                </p>
                <p class="text-[13px] font-bold text-slate-800 mt-1 truncate">{{ $balita['last_measure'] ?? 'Belum ada' }}</p>
            </div>
            <div class="text-right shrink-0">
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">BB / TB</p>
                <p class="text-[13px] font-bold text-slate-800 mt-1 tabular-nums">{{ $balita['bb_tb'] ?? '—' }}</p>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="p-4 pt-0 pl-[18px] flex items-center gap-2">
        <a href="{{ route('balita.show', $balita['id'] ?? '') }}"
           class="h-11 flex-1 flex items-center justify-center gap-1.5 text-[13px] font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 rounded-xl transition-all">
            <x-icon name="user" weight="bold" class="text-[15px]" /> Detail
        </a>
        <a href="{{ route('balita.show', ['id' => $balita['id'] ?? '', 'action' => 'ukur']) }}"
           class="h-11 flex-1 flex items-center justify-center gap-1.5 bg-teal-600 hover:bg-teal-700 text-white text-[13px] font-semibold rounded-xl transition-all shadow-sm active:scale-[0.99]">
            <x-icon name="scales" weight="bold" class="text-[15px]" /> Ukur
        </a>
    </div>
</div>
