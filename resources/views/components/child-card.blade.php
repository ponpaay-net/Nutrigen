@props(['balita'])

@php
    $statusType = $balita['status_type'] ?? 'warning';
    $valStatus  = $balita['status_validasi'] ?? 'pending';

    $theme = match($statusType) {
        'success' => [
            'bar'    => 'bg-emerald-500',
            'avatar' => 'bg-emerald-50 text-emerald-700 ring-emerald-100',
            'badge'  => 'bg-emerald-50 text-emerald-700 border-emerald-200',
            'dot'    => 'bg-emerald-500',
        ],
        'danger' => [
            'bar'    => 'bg-rose-500',
            'avatar' => 'bg-rose-50 text-rose-700 ring-rose-100',
            'badge'  => 'bg-rose-50 text-rose-700 border-rose-200',
            'dot'    => 'bg-rose-500',
        ],
        default => [
            'bar'    => 'bg-amber-400',
            'avatar' => 'bg-amber-50 text-amber-700 ring-amber-100',
            'badge'  => 'bg-amber-50 text-amber-700 border-amber-200',
            'dot'    => 'bg-amber-400',
        ],
    };

    $isGirl = in_array(strtolower($balita['gender'] ?? ''), ['p', 'perempuan', 'female']);
    $nik = $balita['masked_nik'] ?? $balita['nik'] ?? '-';
    $name = Str::title($balita['name'] ?? 'Balita');
    $initials = strtoupper(substr($name, 0, 2));
@endphp

<div class="group relative flex flex-col h-full bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-[0_1px_3px_rgba(15,23,42,0.05),0_8px_20px_-12px_rgba(15,23,42,0.10)] hover:shadow-[0_2px_6px_rgba(15,23,42,0.07),0_16px_36px_-16px_rgba(15,23,42,0.18)] hover:-translate-y-0.5 hover:border-teal-200 transition-all duration-200">
    <span class="absolute left-0 top-0 bottom-0 w-1 {{ $theme['bar'] }}"></span>

    <div class="flex-1 p-4 pl-5 flex flex-col gap-3">
        {{-- Header: avatar + name + meta --}}
        <div class="flex items-start gap-3">
            <div class="w-11 h-11 shrink-0 rounded-full {{ $theme['avatar'] }} ring-2 flex items-center justify-center font-bold text-[13px]">{{ $initials }}</div>
            <div class="flex-1 min-w-0">
                <a href="{{ route('balita.show', $balita['id'] ?? '') }}" class="flex items-center gap-1.5 font-bold text-slate-900 text-[14.5px] leading-snug truncate group-hover:text-teal-700 transition-colors">
                    <span class="truncate">{{ $name }}</span>
                </a>
                <p class="flex items-center gap-1.5 text-[12.5px] text-slate-500 font-medium mt-0.5">
                    <x-icon name="{{ $isGirl ? 'gender-female' : 'gender-male' }}" weight="fill" class="text-[13px] text-slate-400" />
                    <span class="tabular-nums">{{ $balita['age'] ?? '-' }}</span>
                    <span class="text-slate-300">•</span>
                    <span>{{ $balita['gender_label'] ?? '' }}</span>
                </p>
                <p class="text-[12px] text-slate-400 font-medium tracking-wide truncate mt-0.5">NIK {{ $nik }}</p>
            </div>
        </div>

        {{-- Status badges --}}
        <div class="flex items-center gap-2 flex-wrap">
            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full border text-[12px] font-semibold {{ $theme['badge'] }}">
                <span class="w-1.5 h-1.5 rounded-full {{ $theme['dot'] }}"></span>
                {{ $balita['status'] ?? '—' }}
            </span>
            @if($valStatus === 'approved')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full border text-[11px] uppercase tracking-wide font-bold bg-emerald-50 text-emerald-700 border-emerald-200">
                    <x-icon name="check" weight="bold" class="text-[12px]" /> Approved
                </span>
            @elseif($valStatus === 'rejected')
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full border text-[11px] uppercase tracking-wide font-bold bg-rose-50 text-rose-700 border-rose-200">
                    <x-icon name="x" weight="bold" class="text-[12px]" /> Revisi
                </span>
            @else
                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full border text-[11px] uppercase tracking-wide font-bold bg-amber-50 text-amber-700 border-amber-200">
                    <x-icon name="clock" weight="bold" class="text-[12px]" /> Pending
                </span>
            @endif
        </div>

        {{-- Last measurement --}}
        <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 flex items-center justify-between gap-3">
            <div class="min-w-0">
                <p class="flex items-center gap-1 text-[11px] font-semibold text-slate-400 uppercase tracking-wide">
                    <x-icon name="calendar-dots" weight="bold" class="text-[13px]" /> Pengukuran
                </p>
                <p class="text-[13px] font-bold text-slate-800 mt-0.5 truncate">{{ $balita['last_measure'] ?? 'Belum ada' }}</p>
            </div>
            <div class="text-right shrink-0">
                <p class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">BB / TB</p>
                <p class="text-[13px] font-bold text-slate-800 mt-0.5 tabular-nums">{{ $balita['bb_tb'] ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Actions --}}
    <div class="p-4 pt-0 pl-5 flex items-center gap-2">
        <a href="{{ route('balita.show', $balita['id'] ?? '') }}"
           class="h-11 flex-1 flex items-center justify-center gap-1.5 text-[13px] font-semibold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 rounded-xl transition-all cursor-pointer">
            <x-icon name="user" weight="bold" class="text-[15px]" /> Detail
        </a>
        <a href="{{ route('balita.show', ['id' => $balita['id'] ?? '', 'action' => 'ukur']) }}"
           class="h-11 flex-1 flex items-center justify-center gap-1.5 bg-teal-600 hover:bg-teal-700 text-white text-[13px] font-semibold rounded-xl transition-all shadow-sm active:scale-[0.99] cursor-pointer">
            <x-icon name="scales" weight="bold" class="text-[15px]" /> Ukur
        </a>
    </div>
</div>
