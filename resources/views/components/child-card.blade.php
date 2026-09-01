@props(['balita'])

@php
    $statusType = $balita['status_type'] ?? 'warning';
    $valStatus  = $balita['status_validasi'] ?? 'pending';
    $statusDot  = ['success' => 'bg-emerald-500', 'danger' => 'bg-rose-500', 'warning' => 'bg-amber-400'][$statusType] ?? 'bg-amber-400';
    $statusText = ['success' => 'text-emerald-700', 'danger' => 'text-rose-700', 'warning' => 'text-amber-700'][$statusType] ?? 'text-amber-700';
    $isGirl = in_array(strtolower($balita['gender'] ?? ''), ['p', 'perempuan', 'female']);
    $nik = $balita['masked_nik'] ?? $balita['nik'] ?? '-';
    $name = Str::title($balita['name'] ?? 'Balita');
    $initials = strtoupper(substr($name, 0, 2));
@endphp

<div class="group h-full bg-white border border-slate-200 rounded-xl p-4 flex flex-col gap-3 hover:border-teal-300 hover:shadow-sm transition-all duration-150">
    {{-- Header --}}
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 shrink-0 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center text-slate-600 font-bold text-[12px]">{{ $initials }}</div>
        <div class="min-w-0">
            <a href="{{ route('balita.show', $balita['id'] ?? '') }}" class="text-sm font-semibold text-slate-900 truncate block hover:text-teal-700 transition-colors">{{ $name }}</a>
            <p class="flex items-center gap-1.5 text-xs text-slate-500 mt-0.5">
                <x-icon name="{{ $isGirl ? 'gender-female' : 'gender-male' }}" weight="fill" class="text-[12px] text-slate-400" />
                <span class="tabular-nums">{{ $balita['age'] ?? '-' }}</span>
                <span class="text-slate-300">·</span>
                <span>{{ $balita['gender_label'] ?? '' }}</span>
            </p>
        </div>
    </div>

    {{-- Status + validation --}}
    <div class="flex items-center gap-2 flex-wrap">
        <span class="inline-flex items-center gap-1.5 text-[12px] font-semibold {{ $statusText }}">
            <span class="w-1.5 h-1.5 rounded-full {{ $statusDot }}"></span>
            {{ $balita['status'] ?? '—' }}
        </span>
        @if($valStatus === 'rejected')
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full border border-rose-200 bg-rose-50 text-rose-700 text-[11px] font-semibold"><x-icon name="x" weight="bold" class="text-[12px]" /> Revisi</span>
        @elseif($valStatus === 'approved')
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full border border-emerald-200 bg-emerald-50 text-emerald-700 text-[11px] font-semibold"><x-icon name="check" weight="bold" class="text-[12px]" /> Valid</span>
        @else
            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full border border-slate-200 bg-slate-50 text-slate-500 text-[11px] font-semibold"><x-icon name="clock" weight="bold" class="text-[12px]" /> Pending</span>
        @endif
    </div>

    {{-- NIK --}}
    <p class="text-[11px] text-slate-400 font-medium tracking-wide truncate">NIK {{ $nik }}</p>

    {{-- Measurement --}}
    <div class="pt-3 mt-auto border-t border-slate-100 grid grid-cols-2 gap-2">
        <div>
            <p class="text-[10px] uppercase tracking-wide text-slate-400 font-semibold">BB / TB</p>
            <p class="text-[13px] font-semibold text-slate-800 truncate tabular-nums">{{ $balita['bb_tb'] ?? '-' }}</p>
        </div>
        <div class="text-right">
            <p class="text-[10px] uppercase tracking-wide text-slate-400 font-semibold">Pengukuran</p>
            <p class="text-[13px] font-semibold text-slate-800 truncate">{{ $balita['last_measure'] ?? '—' }}</p>
        </div>
    </div>

    {{-- Actions --}}
    <div class="grid grid-cols-2 gap-2">
        <a href="{{ route('balita.show', $balita['id'] ?? '') }}" class="h-9 inline-flex items-center justify-center gap-1.5 text-[12.5px] font-semibold text-slate-600 border border-slate-200 rounded-lg hover:bg-slate-50 hover:border-slate-300 transition-colors cursor-pointer"><x-icon name="user" weight="bold" class="text-[14px]" /> Detail</a>
        <a href="{{ route('balita.show', ['id' => $balita['id'] ?? '', 'action' => 'ukur']) }}" class="h-9 inline-flex items-center justify-center gap-1.5 text-[12.5px] font-semibold text-white bg-teal-600 hover:bg-teal-700 rounded-lg transition-colors shadow-sm cursor-pointer"><x-icon name="scales" weight="bold" class="text-[14px]" /> Ukur</a>
    </div>
</div>
