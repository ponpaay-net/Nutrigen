@extends('layouts.app')
@section('page-title', 'Profil Balita')
@section('content')

@php
    $badge = [
        'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'warning' => 'bg-amber-50 text-amber-700 border-amber-200',
        'danger'  => 'bg-rose-50 text-rose-700 border-rose-200',
    ][$status_type] ?? 'bg-slate-50 text-slate-600 border-slate-200';
    $sex = ($gender === 'Laki-laki') ? 'L' : 'P';
    $svc = app(\App\Services\GrowthCalculationService::class);
    $birth = \Carbon\Carbon::parse($birthDateRaw ?? $birthDate);
    $whoRef = [];
    for ($m = 0; $m <= 60; $m++) { $whoRef[$m] = $svc->referenceFor($m, $sex); }
    $pts = collect($measurements)->map(function ($m) use ($birth) {
        return ['month' => (int) $birth->diffInMonths(\Carbon\Carbon::parse($m['raw_date'])), 'w' => $m['weight'], 'h' => $m['height']];
    })->filter(fn($p) => $p['w'] !== null || $p['h'] !== null)->values();
    $childMaxMonth = $pts->max('month') ?? 0;
    $maxMonth = min(60, max(12, (int) (ceil($childMaxMonth / 6) * 6)));
    // WINDOW pertumbuhan: rentang bulan data anak (ber-pad), bukan 0-baru-lahir, agar data normal terbaca + outlier ter-flag
    $dataMonths = $pts->pluck('month')->filter(fn ($m) => $m !== null)->sort()->values();
    $winLo = (int) ($dataMonths->first() ?? 0);
    $winHi = (int) ($dataMonths->last() ?? $maxMonth);
    $winLoPad = max(0, $winLo - 1);
    $winHiPad = max($winLoPad + 2, min(60, $winHi + 1));
    $winSpan = max(1, $winHiPad - $winLoPad);
    $months = range($winLoPad, $winHiPad);
    $monthsTotal = $winSpan;
    function whoBand($whoRef, $months, $field) { $band = []; foreach ($months as $m) { $r = $whoRef[$m]; $band[$m]['mid'] = $r[$field.'_sd0']; $band[$m]['hi'] = $r[$field.'_sd2']; $band[$m]['lo'] = $r[$field.'_sd2n']; } return $band; }
    $bbBand = whoBand($whoRef, $months, 'bb');
    $tbBand = whoBand($whoRef, $months, 'tb');
    $unit = ['w' => 'kg', 'h' => 'cm'];
    function clamp($v, $lo, $hi) { return max($lo, min($hi, $v)); }
    $W = 720; $H = 320; $padL = 46; $padR = 20; $padT = 26; $padB = 38;
    $X0 = $padL; $X1 = $W - $padR; $Y0 = $padT; $Y1 = $H - $padB;
    function xOf($m, $X0, $X1, $lo, $span) { return $X0 + (($m - $lo) / $span) * ($X1 - $X0); }
    function yOf($v, $min, $max, $Y0, $Y1) { return $Y1 - (($v - $min) / max(1e-6, $max - $min)) * ($Y1 - $Y0); }
    function bandPath($band, $X0, $X1, $Y0, $Y1, $lo, $span, $min, $max, $key, $months) { $str = ''; foreach ($months as $i => $m) { $x = xOf($m, $X0, $X1, $lo, $span); $y = yOf($band[$m][$key], $min, $max, $Y0, $Y1); $str .= ($i === 0 ? 'M' : 'L') . round($x, 1) . ' ' . round($y, 1) . ' '; } return trim($str); }
    function bandAreaPath($band, $X0, $X1, $Y0, $Y1, $lo, $span, $min, $max, $months) { $up = bandPath($band, $X0, $X1, $Y0, $Y1, $lo, $span, $min, $max, 'hi', $months); $down = ''; foreach (array_reverse($months) as $m) { $x = xOf($m, $X0, $X1, $lo, $span); $y = yOf($band[$m]['lo'], $min, $max, $Y0, $Y1); $down .= 'L' . round($x,1) . ' ' . round($y,1) . ' '; } return trim($up . ' ' . trim($down) . ' Z'); }
    function chartRange($band, $step) { $lo = INF; $hi = -INF; foreach ($band as $b) { $lo = min($lo, $b['lo']); $hi = max($hi, $b['hi']); } $pad = max(0.5, ($hi - $lo) * 0.08); return [max(0, floor(($lo - $pad) / $step) * $step), ceil(($hi + $pad) / $step) * $step]; }
    function pColor($z) { if ($z === null) return '#94a3b8'; if ($z < -2) return '#e11d48'; if ($z < -1) return '#f59e0b'; return '#10b981'; }
    function pIsAnom($z) { return $z !== null && abs($z) > 3; }
    function buildXTicks($months, $lo, $hi, $span) { $xs = max(1, (int) ceil($span / 7)); $out = []; foreach ($months as $m) { if ((($m - $lo) % $xs) === 0 || $m === $lo || $m === $hi) { $out[] = [$m]; } } return $out; }
    $bbRange = chartRange($bbBand, 2);
    $tbRange = chartRange($tbBand, 4);
    $genXTicks = buildXTicks($months, $winLoPad, $winHiPad, $winSpan);
    // precompute rendered points per field (cx, cy, color, value, anomaly)
    function buildPts($pts, $field, $zk, $min, $max, $X0, $X1, $Y0, $Y1, $winLoPad, $winHiPad, $winSpan) { $out = []; $v = collect($pts)->filter(fn ($p) => $p[$field] !== null)->map(function ($p) use ($field, $zk, $min, $max, $X0, $X1, $Y0, $Y1, $winLoPad, $winHiPad, $winSpan) { $z = $p[$zk] ?? null; $x = xOf(clamp($p['month'], $winLoPad, $winHiPad), $X0, $X1, $winLoPad, $winSpan); $y = yOf(clamp($p[$field], $min, $max), $min, $max, $Y0, $Y1); return ['cx' => $x, 'cy' => $y, 'c' => pColor($z), 'a' => pIsAnom($z), 'val' => $p[$field]]; })->values(); $out['pts'] = $v; $out['line'] = $v->map(fn ($q) => round($q['cx'],1).','.round($q['cy'],1))->join(' '); return $out; }
    $tri = [ 'w' => ['band' => $bbBand, 'min' => $bbRange[0], 'max' => $bbRange[1], 'field' => 'w', 'dec' => 1, 'zk' => 'zb', 'xticks' => $genXTicks, 'pts' => buildPts($pts, 'w', 'zb', $bbRange[0], $bbRange[1], $X0, $X1, $Y0, $Y1, $winLoPad, $winHiPad, $winSpan)], 'h' => ['band' => $tbBand, 'min' => $tbRange[0], 'max' => $tbRange[1], 'field' => 'h', 'dec' => 0, 'zk' => 'zt', 'xticks' => $genXTicks, 'pts' => buildPts($pts, 'h', 'zt', $tbRange[0], $tbRange[1], $X0, $X1, $Y0, $Y1, $winLoPad, $winHiPad, $winSpan)] ];
    $lastZb = $latestMeasure['z_score_bbu'] ?? null;
    $lastZt = $latestMeasure['z_score_tbu'] ?? null;
    function zClass($z) { return $z < -2 ? 'text-rose-600' : ($z < -1 ? 'text-amber-600' : 'text-emerald-600'); }
@endphp

<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 pb-6">
<div x-data="{ tab: 'info' }" class="flex flex-col gap-5 lg:gap-6">

    @if(session('success') || session('advice'))
    <div x-data="{ showToast: true }" x-show="showToast" x-cloak class="flex items-start gap-3 bg-teal-50 border border-teal-200 rounded-2xl px-4 py-3.5">
        <span class="w-9 h-9 shrink-0 rounded-xl bg-teal-600 text-white flex items-center justify-center"><x-icon name="check" weight="bold" class="text-[17px]" /></span>
        <div class="flex-1 min-w-0 text-[13px] text-teal-900">
            <p class="font-bold">{{ session('success') }}</p>
            @if(session('advice'))<p class="mt-0.5 text-teal-800/80">{{ session('advice') }}</p>@endif
        </div>
        <button type="button" @click="showToast = false" aria-label="Tutup" class="ml-auto w-7 h-7 rounded-lg text-teal-700 hover:bg-teal-100 flex items-center justify-center transition-colors"><x-icon name="x" weight="bold" class="text-[14px]" /></button>
    </div>
    @endif

    {{-- HEADER: identity + actions (always) --}}
    <section class="bg-white border border-slate-200 rounded-2xl shadow-[0_1px_3px_rgba(15,23,42,0.06),0_12px_32px_-16px_rgba(15,23,42,0.14)] p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4 min-w-0">
                <div class="w-14 h-14 sm:w-16 sm:h-16 shrink-0 rounded-2xl bg-teal-50 border border-teal-100 text-teal-700 flex items-center justify-center"><span class="text-[26px] sm:text-[32px] font-black">{{ strtoupper(substr($childName, 0, 1)) }}</span></div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight truncate">{{ $childName }}</h2>
                        <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[11.5px] font-bold {{ $badge }}"><x-icon name="{{ $status_type === 'danger' ? 'warning' : ($status_type === 'warning' ? 'activity' : 'check-circle') }}" weight="fill" class="text-[12px]" /> {{ $status }}</span>
                    </div>
                    <div class="flex items-center gap-3 flex-wrap mt-1">
                        <span class="inline-flex items-center gap-1.5 text-[12.5px] font-semibold text-slate-600"><x-icon name="{{ $sex === 'L' ? 'gender-male' : 'gender-female' }}" weight="fill" class="text-[14px] text-slate-400" /> {{ $gender }}</span>
                        <span class="text-slate-300">·</span>
                        <span class="inline-flex items-center gap-1.5 text-[12.5px] text-slate-500"><x-icon name="calendar" weight="bold" class="text-[13px] text-slate-400" /> {{ $age }}</span>
                        <span class="text-slate-300">·</span>
                        <span class="text-[12.5px] text-slate-500">Lahir {{ $birthDate }}</span>
                        @if($latestMeasure && $latestMeasure['date'])<span class="text-slate-300">·</span><span class="inline-flex items-center gap-1 text-[12px] font-medium text-teal-700"><x-icon name="clock" weight="bold" class="text-[12px]" /> Terakhir diukur {{ $latestMeasure['date'] }}</span>@endif
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0 flex-wrap" x-data="{ confirmDelete: false }">
                <a href="{{ route('balita.index') }}" class="inline-flex items-center justify-center gap-1.5 h-10 px-3.5 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 text-[13px] font-semibold transition-colors"><x-icon name="arrow-left" weight="bold" class="text-[15px]" /> Kembali</a>
                <a href="{{ route('balita.edit', $balitaId) }}" class="inline-flex items-center justify-center gap-1.5 h-10 px-3.5 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 text-[13px] font-semibold transition-colors"><x-icon name="pencil-line" weight="bold" class="text-[15px]" /> Edit</a>
                <form id="delete-balita-{{ $balitaId }}" action="{{ route('balita.destroy', $balitaId) }}" method="POST" class="hidden">@csrf @method('DELETE')</form>
                <button type="button" @click="confirmDelete = true" class="inline-flex items-center justify-center gap-1.5 h-10 px-3.5 rounded-xl border border-rose-200 bg-rose-50 text-rose-600 hover:bg-rose-100 text-[13px] font-semibold transition-colors"><x-icon name="trash" weight="bold" class="text-[15px]" /> Hapus</button>
                <a href="{{ route('balita.ukur', $balitaId) }}" class="inline-flex items-center justify-center gap-1.5 h-10 px-4 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-[13px] font-semibold shadow-sm transition-colors"><x-icon name="scales" weight="bold" class="text-[15px]" /> Ukur Sekarang</a>

                {{-- Konfirmasi hapus --}}
                <template x-teleport="body">
                    <div x-show="confirmDelete" x-cloak class="fixed inset-0 z-[70] flex items-center justify-center p-4" x-transition.opacity>
                        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="confirmDelete = false"></div>
                        <div x-show="confirmDelete" x-transition.scale.origin.center class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl p-6">
                            <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto">
                                <x-icon name="warning" weight="fill" class="text-[22px]" />
                            </div>
                            <h3 class="text-center text-[16px] font-bold text-slate-900 mt-3">Hapus Data Balita?</h3>
                            <p class="text-center text-[13px] text-slate-500 mt-1.5 leading-relaxed">Data balita ini <span class="font-semibold text-slate-700">beserta seluruh riwayat pengukuran</span> akan dihapus permanen dan tidak dapat dikembalikan.</p>
                            <div class="grid grid-cols-2 gap-2.5 mt-5">
                                <button type="button" @click="confirmDelete = false" class="h-11 rounded-xl border border-slate-200 bg-white text-slate-700 text-[13.5px] font-semibold hover:bg-slate-50 transition-colors">Batal</button>
                                <button type="button" @click="document.getElementById('delete-balita-{{ $balitaId }}').submit()" class="h-11 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-[13.5px] font-semibold transition-colors inline-flex items-center justify-center gap-2"><x-icon name="trash" weight="bold" class="text-[15px]" /> Ya, Hapus</button>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        {{-- current snapshot (always visible, compact) --}}
        <div class="mt-5 pt-5 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-3 gap-3">
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                <span class="w-9 h-9 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center"><x-icon name="scales" weight="bold" class="text-[16px]" /></span>
                <div class="min-w-0"><p class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">Berat</p><p class="text-[16px] font-bold text-slate-900 tabular-nums leading-tight">{{ $latestMeasure['weight'] ? number_format($latestMeasure['weight'],1,',','.') . ' kg' : '—' }} <span class="text-[11px] font-semibold {{ $lastZb !== null ? zClass($lastZb) : 'text-slate-400' }}">Z-BB/U {{ $lastZb !== null ? round($lastZb,2) . ' SD' : '—' }}</span></p></div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                <span class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center"><x-icon name="ruler" weight="bold" class="text-[16px]" /></span>
                <div class="min-w-0"><p class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">Tinggi</p><p class="text-[16px] font-bold text-slate-900 tabular-nums leading-tight">{{ $latestMeasure['height'] ? number_format($latestMeasure['height'],1,',','.') . ' cm' : '—' }} <span class="text-[11px] font-semibold {{ $lastZt !== null ? zClass($lastZt) : 'text-slate-400' }}">Z-TB/U {{ $lastZt !== null ? round($lastZt,2) . ' SD' : '—' }}</span></p></div>
            </div>
            <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                <span class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center"><x-icon name="user-circle" weight="bold" class="text-[16px]" /></span>
                <div class="min-w-0"><p class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">L. Kepala</p><p class="text-[16px] font-bold text-slate-900 tabular-nums leading-tight">{{ $latestMeasure['head_circ'] ? number_format($latestMeasure['head_circ'],1,',','.') . ' cm' : '—' }} <span class="text-[11px] font-semibold text-slate-400">{{ $latestMeasure['date'] ?? '' }}</span></p></div>
            </div>
        </div>
    </section>

    {{-- status attention banner --}}
    @if($status_type === 'danger' || $status_type === 'warning')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 rounded-2xl border {{ $status_type === 'danger' ? 'bg-rose-50 border-rose-200' : 'bg-amber-50 border-amber-200' }}">
        <div class="flex items-start gap-2.5 min-w-0">
            <x-icon name="{{ $status_type === 'danger' ? 'warning' : 'activity' }}" weight="fill" class="text-[22px] {{ $status_type === 'danger' ? 'text-rose-600' : 'text-amber-600' }} shrink-0 mt-0.5" />
            <div><p class="text-[14px] font-bold {{ $status_type === 'danger' ? 'text-rose-700' : 'text-amber-700' }}">Balita ini memerlukan perhatian</p><p class="text-[12.5px] text-slate-600 mt-0.5">Status gizi: <span class="font-semibold">{{ $status }}</span>. {{ $status_type === 'danger' ? 'Segera tindak lanjuti & rujuk ke Puskesmas.' : 'Lakukan penimbangan ulang rutin bulan ini.' }}</p></div>
        </div>
        <a href="{{ route('balita.ukur', $balitaId) }}" class="shrink-0 inline-flex items-center justify-center gap-1.5 h-10 px-4 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-[13px] font-semibold transition-colors"><x-icon name="scales" weight="bold" class="text-[15px]" /> Ukur Ulang</a>
    </div>
    @endif

    {{-- TAB BAR --}}
    <div class="flex gap-1 p-1 rounded-2xl bg-white border border-slate-200 shadow-sm">
        <button type="button" @click="tab = 'info'" x-bind:class="tab === 'info' ? 'bg-teal-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100'" class="flex-1 inline-flex items-center justify-center gap-1.5 h-10 px-3 sm:px-4 rounded-xl text-[12.5px] sm:text-[13px] font-semibold transition-all min-w-0"><x-icon name="identification-card" weight="bold" class="text-[15px] shrink-0" /><span class="truncate">Identitas</span></button>
        <button type="button" @click="tab = 'riwayat'" x-bind:class="tab === 'riwayat' ? 'bg-teal-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100'" class="flex-1 inline-flex items-center justify-center gap-1.5 h-10 px-3 sm:px-4 rounded-xl text-[12.5px] sm:text-[13px] font-semibold transition-all min-w-0"><x-icon name="chart-line-up" weight="bold" class="text-[15px] shrink-0" /><span class="truncate">Riwayat</span></button>
        <button type="button" @click="tab = 'kurva'" x-bind:class="tab === 'kurva' ? 'bg-teal-600 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-100'" class="flex-1 inline-flex items-center justify-center gap-1.5 h-10 px-3 sm:px-4 rounded-xl text-[12.5px] sm:text-[13px] font-semibold transition-all min-w-0"><x-icon name="chart-line" weight="bold" class="text-[15px] shrink-0" /><span class="truncate">Kurva WHO</span></button>
    </div>

    {{-- TAB: IDENTITAS & ORANG TUA --}}
    <div x-show="tab === 'info'" x-cloak class="grid grid-cols-1 md:grid-cols-2 gap-5 lg:gap-6 items-stretch">
        {{-- Card 1: Identitas Balita (teal) --}}
        <section class="bg-white border border-slate-200 rounded-2xl shadow-[0_1px_3px_rgba(15,23,42,0.06)] overflow-hidden flex flex-col">
            <div class="h-1 bg-teal-500"></div>
            <div class="p-5 flex flex-col gap-0.5 flex-1">
                <div class="flex items-center gap-2.5 pb-3 mb-1 border-b border-slate-100"><span class="w-9 h-9 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center"><x-icon name="identification-card" weight="bold" class="text-[17px]" /></span><h4 class="text-[14px] font-bold text-slate-900">Identitas Balita</h4></div>
                <div class="flex items-center justify-between gap-3 py-2.5 border-b border-slate-100"><span class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 shrink-0">Nama</span><span class="text-[14px] font-semibold text-teal-700 text-right">{{ $childName ?: '—' }}</span></div>
                <div class="flex items-center justify-between gap-3 py-2.5 border-b border-slate-100"><span class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 shrink-0">NIK</span><span class="text-[14px] font-semibold text-slate-800 font-mono tabular-nums text-right break-all">{{ $nik ?: '—' }}</span></div>
                <div class="flex items-center justify-between gap-3 py-2.5 border-b border-slate-100"><span class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 shrink-0">Tgl Lahir</span><span class="text-[14px] font-semibold text-slate-800 text-right">{{ $birthDate }}</span></div>
                <div class="flex items-center justify-between gap-3 py-2.5 border-b border-slate-100"><span class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 shrink-0">Kelamin</span><span class="text-[14px] font-semibold text-slate-800 text-right">{{ $gender }}</span></div>
                <div class="flex items-center justify-between gap-3 py-2.5 border-b border-slate-100"><span class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 shrink-0">No BPJS</span><span class="text-[14px] font-semibold text-slate-800 font-mono tabular-nums text-right">{{ $noBpjs ?: '—' }}</span></div>
                <div class="flex items-center justify-between gap-3 py-2.5"><span class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 shrink-0">Usia</span><span class="text-[14px] font-semibold text-slate-800 text-right">{{ $age }}</span></div>
            </div>
        </section>

        {{-- Card 2: Antropometri Lahir (emerald) --}}
        <section class="bg-white border border-slate-200 rounded-2xl shadow-[0_1px_3px_rgba(15,23,42,0.06)] overflow-hidden flex flex-col">
            <div class="h-1 bg-emerald-500"></div>
            <div class="p-5 flex flex-col gap-2 flex-1">
                <div class="flex items-center gap-2.5 pb-3 mb-1 border-b border-slate-100"><span class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center"><x-icon name="baby" weight="bold" class="text-[17px]" /></span><h4 class="text-[14px] font-bold text-slate-900">Antropometri Lahir</h4></div>
                <div class="flex items-center gap-3 py-3.5"><span class="w-10 h-10 shrink-0 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center"><x-icon name="scales" weight="bold" class="text-[17px]" /></span><div class="flex-1 min-w-0"><p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Berat Badan</p><p class="text-[19px] font-bold text-slate-900 tabular-nums leading-tight">{{ $birthWeight ? $birthWeight . ' kg' : '—' }}</p></div></div>
                <div class="flex items-center gap-3 py-3.5 border-t border-slate-100"><span class="w-10 h-10 shrink-0 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center"><x-icon name="ruler" weight="bold" class="text-[17px]" /></span><div class="flex-1 min-w-0"><p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Panjang Badan</p><p class="text-[19px] font-bold text-slate-900 tabular-nums leading-tight">{{ $birthLength ? $birthLength . ' cm' : '—' }}</p></div></div>
                <div class="flex items-center gap-3 py-3.5 border-t border-slate-100"><span class="w-10 h-10 shrink-0 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center"><x-icon name="circle" weight="bold" class="text-[17px]" /></span><div class="flex-1 min-w-0"><p class="text-[11px] font-semibold uppercase tracking-wide text-slate-500">Lingkar Kepala</p><p class="text-[19px] font-bold text-slate-900 tabular-nums leading-tight">{{ $birthHeadCirc ? $birthHeadCirc . ' cm' : '—' }}</p></div></div>
            </div>
        </section>

        {{-- Card 3: Orang Tua & Domisili (amber) --}}
        <section class="md:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-[0_1px_3px_rgba(15,23,42,0.06)] overflow-hidden flex flex-col">
            <div class="h-1 bg-amber-400"></div>
            <div class="p-5 flex flex-col gap-0.5 flex-1">
                <div class="flex items-center gap-2.5 pb-3 mb-1 border-b border-slate-100"><span class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center"><x-icon name="users" weight="bold" class="text-[17px]" /></span><h4 class="text-[14px] font-bold text-slate-900">Orang Tua & Domisili</h4></div>
                <div class="flex items-center justify-between gap-3 py-2.5 border-b border-slate-100"><span class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 shrink-0">Ibu</span><span class="text-[14px] font-semibold text-slate-800 text-right">{{ $motherName ?: '—' }}</span></div>
                <div class="flex items-center justify-between gap-3 py-2.5 border-b border-slate-100"><span class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 shrink-0">Ayah</span><span class="text-[14px] font-semibold text-slate-800 text-right">{{ $fatherName ?: '—' }}</span></div>
                <div class="flex items-center justify-between gap-3 py-2.5 border-b border-slate-100"><span class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 shrink-0">Kontak Ibu</span><span class="text-[14px] font-semibold text-slate-800 font-mono tabular-nums text-right">{{ $motherPhone ?: '—' }}</span></div>
                <div class="flex items-center justify-between gap-3 py-2.5 border-b border-slate-100"><span class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 shrink-0">Domisili</span><span class="text-[14px] font-semibold text-slate-800 text-right">{{ ($address ?: '—') . ($addressSub ? ', ' . $addressSub : '') }}</span></div>
                <div class="flex items-center justify-between gap-3 py-2.5"><span class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 shrink-0">Posyandu</span><span class="text-[14px] font-semibold text-slate-800 text-right">{{ $posyanduName ?: '—' }}</span></div>
            </div>
        </section>
    </div>

    {{-- TAB: RIWAYAT (table + modal) --}}
    <div x-show="tab === 'riwayat'" x-cloak>
        <section class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 sm:p-6" x-data='{ active: null, items: @json($measurements, 15) }'>
            <div class="flex items-center justify-between mb-4"><div><h4 class="text-[15px] font-bold text-slate-900">Riwayat Pengukuran</h4><p class="text-[12px] text-slate-500 mt-0.5">{{ count($measurements) }} kali, terbaru di atas</p></div></div>
            @if(count($measurements) > 0)
            {{-- Mobile: card list (stack, no scroll) --}}
            <div class="space-y-3 sm:hidden">
                @foreach($measurements as $i => $m)
                    @php $s = $m['status_validasi'] ?? 'pending'; $isRejected = $s === 'rejected'; $badgeText = match($s) { 'rejected' => 'text-rose-600', 'pending' => 'text-amber-600', 'approved' => 'text-emerald-600', default => 'text-slate-500' }; @endphp
                    <button type="button" @click="active = {{ $i }}" class="w-full bg-white border {{ $isRejected ? 'border-rose-200 bg-rose-50/40' : 'border-slate-200' }} rounded-xl p-4 text-left transition-shadow hover:shadow-sm">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2 min-w-0"><span class="w-2 h-2 rounded-full shrink-0 {{ $s === 'rejected' ? 'bg-rose-500' : ($s === 'pending' ? 'bg-amber-400' : 'bg-emerald-500') }}"></span><span class="text-[13.5px] font-bold text-slate-800 whitespace-nowrap">{{ $m['date'] }}</span><span class="text-[11.5px] text-slate-400 whitespace-nowrap">· {{ $m['age_at_measure'] }}</span></div>
                            <span class="text-[12px] font-bold {{ $badgeText }} shrink-0">{{ $m['status'] }}</span>
                        </div>
                        <div class="grid grid-cols-3 gap-2 mt-3">
                            <div class="bg-slate-50 rounded-lg px-2.5 py-1.5"><p class="text-[9.5px] font-semibold text-slate-400 uppercase tracking-wide">BB</p><p class="text-[13px] font-bold text-slate-800 tabular-nums mt-0.5">{{ $m['weight'] ? number_format($m['weight'],1,',','.') . ' kg' : '—' }}</p></div>
                            <div class="bg-slate-50 rounded-lg px-2.5 py-1.5"><p class="text-[9.5px] font-semibold text-slate-400 uppercase tracking-wide">TB</p><p class="text-[13px] font-bold text-slate-800 tabular-nums mt-0.5">{{ $m['height'] ? number_format($m['height'],1,',','.') . ' cm' : '—' }}</p></div>
                            <div class="bg-slate-50 rounded-lg px-2.5 py-1.5"><p class="text-[9.5px] font-semibold text-slate-400 uppercase tracking-wide">LK</p><p class="text-[13px] font-bold text-slate-800 tabular-nums mt-0.5">{{ $m['head_circ'] ? number_format($m['head_circ'],1,',','.') . ' cm' : '—' }}</p></div>
                        </div>
                        <div class="flex items-center justify-end gap-1 mt-2.5 text-[12px] font-semibold text-teal-600"><x-icon name="eye" weight="bold" class="text-[13px]" /> Lihat Detail</div>
                        @if($isRejected && !empty($m['catatan_validator']))
                            <div class="mt-2.5 border border-rose-200 rounded-lg p-2.5 bg-white flex items-start gap-2"><x-icon name="chat-circle-text" weight="fill" class="text-rose-500 text-[16px] shrink-0 mt-0.5" /><p class="text-[12px] text-slate-600 leading-relaxed">{{ $m['catatan_validator'] }}</p></div>
                        @endif
                    </button>
                @endforeach
            </div>
            {{-- Desktop: table --}}
            <div class="overflow-x-auto hide-scrollbar -mx-5 px-5 hidden sm:block">
                <table class="w-full min-w-[640px] text-left">
                    <thead><tr class="text-[10.5px] uppercase tracking-wide text-slate-400 border-b border-slate-100">
                        <th class="py-2.5 pr-3 font-semibold">Tanggal</th><th class="py-2.5 pr-3 font-semibold">Usia</th><th class="py-2.5 pr-3 font-semibold">BB</th><th class="py-2.5 pr-3 font-semibold">TB</th><th class="py-2.5 pr-3 font-semibold hidden sm:table-cell">Z-BB/U</th><th class="py-2.5 pr-3 font-semibold hidden sm:table-cell">Z-TB/U</th><th class="py-2.5 pr-3 font-semibold">Status</th><th class="py-2.5 font-semibold text-right">Aksi</th>
                    </tr></thead>
                    <tbody class="divide-y divide-slate-50">
                        @foreach($measurements as $i => $m)
                            @php $s = $m['status_validasi'] ?? 'pending'; $isRejected = $s === 'rejected'; $badgeText = match($s) { 'rejected' => 'text-rose-600', 'pending' => 'text-amber-600', 'approved' => 'text-emerald-600', default => 'text-slate-500' }; @endphp
                            <tr @click="active = {{ $i }}" class="{{ $isRejected ? 'bg-rose-50/40' : '' }} cursor-pointer transition-colors hover:bg-slate-50">
                                <td class="py-4 pr-3 text-[13px] font-bold text-slate-800 whitespace-nowrap">{{ $m['date'] }}</td>
                                <td class="py-4 pr-3 text-[12.5px] text-slate-500 whitespace-nowrap">{{ $m['age_at_measure'] }}</td>
                                <td class="py-4 pr-3 text-[13px] font-semibold text-slate-700 tabular-nums">{{ $m['weight'] ? number_format($m['weight'],1,',','.') . ' kg' : '—' }}</td>
                                <td class="py-4 pr-3 text-[13px] font-semibold text-slate-700 tabular-nums">{{ $m['height'] ? number_format($m['height'],1,',','.') . ' cm' : '—' }}</td>
                                <td class="py-4 pr-3 text-[13px] font-semibold tabular-nums hidden sm:table-cell {{ $m['z_score_bbu'] !== null ? ($m['z_score_bbu'] < -2 ? 'text-rose-600' : ($m['z_score_bbu'] < -1 ? 'text-amber-600' : 'text-emerald-600')) : 'text-slate-400' }}">{{ $m['z_score_bbu'] !== null ? $m['z_score_bbu'] . ' SD' : '—' }}</td>
                                <td class="py-4 pr-3 text-[13px] font-semibold tabular-nums hidden sm:table-cell {{ $m['z_score_tbu'] !== null ? ($m['z_score_tbu'] < -2 ? 'text-rose-600' : ($m['z_score_tbu'] < -1 ? 'text-amber-600' : 'text-emerald-600')) : 'text-slate-400' }}">{{ $m['z_score_tbu'] !== null ? $m['z_score_tbu'] . ' SD' : '—' }}</td>
                                <td class="py-4 pr-3 whitespace-nowrap"><span class="text-[12.5px] font-semibold {{ $badgeText }}">{{ $m['status'] }}</span></td>
                                <td class="py-4 text-right whitespace-nowrap"><button type="button" @click.stop="active = {{ $i }}" class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:border-teal-300 hover:text-teal-700 text-[12px] font-semibold transition-colors"><x-icon name="eye" weight="bold" class="text-[13px]" /> Detail</button></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <template x-teleport="body">
                <div x-show="active !== null" x-cloak class="fixed inset-0 z-[120] flex items-center justify-center p-3 sm:p-6" @click.self="active = null">
                    <div x-show="active !== null" x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" @click.stop class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
                    <div x-show="active !== null" x-transition:enter="transition ease-out duration-250" x-transition:enter-start="opacity-0 scale-95 translate-y-3" x-transition:enter-end="opacity-100 scale-100 translate-y-0" @click.stop class="relative w-full max-w-[560px] max-h-[88vh] bg-white rounded-2xl border border-slate-100 shadow-[0_25px_70px_-15px_rgba(0,0,0,0.25)] overflow-hidden flex flex-col">
                        <template x-if="active !== null">
                            <div>
                                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
                                    <div class="flex items-center gap-2.5 min-w-0"><span class="w-2.5 h-2.5 rounded-full shrink-0" x-bind:class="items[active]?.status_validasi === 'rejected' ? 'bg-rose-500' : (items[active]?.status_validasi === 'pending' ? 'bg-amber-400' : 'bg-emerald-500')"></span><span class="text-[16px] font-bold text-slate-900" x-text="items[active]?.date"></span><span class="text-[12.5px] text-slate-400" x-text="'· ' + (items[active]?.age_at_measure || '')"></span></div>
                                    <button type="button" @click="active = null" aria-label="Tutup" class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-colors shrink-0"><x-icon name="x" weight="bold" class="text-lg" /></button>
                                </div>
                                <div class="px-6 py-5 flex flex-col gap-5 overflow-y-auto">
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-x-6 gap-y-5">
                                        <div><p class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">Berat Badan</p><p class="text-[16px] font-bold text-slate-900 tabular-nums mt-1" x-text="items[active]?.weight ? items[active].weight + ' kg' : '—'"></p></div>
                                        <div><p class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">Tinggi Badan</p><p class="text-[16px] font-bold text-slate-900 tabular-nums mt-1" x-text="items[active]?.height ? items[active].height + ' cm' : '—'"></p></div>
                                        <div><p class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">L. Kepala</p><p class="text-[16px] font-bold text-slate-900 tabular-nums mt-1" x-text="items[active]?.head_circ ? items[active].head_circ + ' cm' : '—'"></p></div>
                                        <div><p class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">Z-BB/U</p><p class="text-[16px] font-bold tabular-nums mt-1" x-bind:class="items[active]?.z_score_bbu !== null ? (items[active].z_score_bbu < -2 ? 'text-rose-600' : items[active].z_score_bbu < -1 ? 'text-amber-600' : 'text-emerald-600') : 'text-slate-400'" x-text="items[active]?.z_score_bbu !== null ? items[active].z_score_bbu + ' SD' : '—'"></p></div>
                                        <div><p class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">Z-TB/U</p><p class="text-[16px] font-bold tabular-nums mt-1" x-bind:class="items[active]?.z_score_tbu !== null ? (items[active].z_score_tbu < -2 ? 'text-rose-600' : items[active].z_score_tbu < -1 ? 'text-amber-600' : 'text-emerald-600') : 'text-slate-400'" x-text="items[active]?.z_score_tbu !== null ? items[active].z_score_tbu + ' SD' : '—'"></p></div>
                                        <div><p class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">Validasi</p><p class="text-[16px] font-bold mt-1" x-text="items[active]?.status_validasi === 'approved' ? 'Tervalidasi' : (items[active]?.status_validasi === 'rejected' ? 'Perlu Revisi' : 'Menunggu')"></p></div>
                                    </div>
                                    <div class="flex items-center justify-between px-4 py-3 bg-slate-50 border border-slate-100 rounded-xl"><span class="text-[11px] font-semibold uppercase tracking-wide text-slate-500 shrink-0">Status</span><span class="inline-flex items-center gap-1.5 text-[13px] font-bold text-right" x-bind:class="items[active]?.status_validasi === 'rejected' ? 'text-rose-600' : (items[active]?.status_validasi === 'pending' ? 'text-amber-600' : 'text-emerald-600')"><span class="w-1.5 h-1.5 rounded-full" x-bind:class="items[active]?.status_validasi === 'rejected' ? 'bg-rose-500' : (items[active]?.status_validasi === 'pending' ? 'bg-amber-400' : 'bg-emerald-500')"></span><span x-text="items[active]?.status"></span></span></div>
                                    <template x-if="items[active]?.status_validasi === 'rejected' && items[active]?.catatan_validator">
                                        <div class="border border-rose-200 rounded-xl p-4 flex items-start gap-3 bg-rose-50/40"><x-icon name="chat-circle-text" weight="fill" class="text-rose-500 text-[20px] shrink-0 mt-0.5" /><div class="min-w-0"><p class="text-[11px] font-bold text-rose-600 uppercase tracking-wide">Catatan Petugas Gizi Puskesmas</p><p class="text-[13.5px] text-slate-700 mt-1.5 leading-relaxed" x-text="items[active]?.catatan_validator"></p></div></div>
                                    </template>
                                </div>
                                <div class="px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-2 shrink-0">
                                    <button type="button" @click="active = null" class="h-10 px-5 rounded-lg border border-slate-200 text-slate-600 hover:bg-slate-50 text-[13px] font-semibold transition-colors">Tutup</button>
                                    <template x-if="items[active]?.status_validasi === 'rejected'"><a :href="'{{ route('balita.ukur', $balitaId) }}'" class="inline-flex items-center gap-1.5 h-10 px-5 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-[13px] font-semibold transition-colors"><x-icon name="pencil-line" weight="bold" class="text-[14px]" /> Perbaiki Data</a></template>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </template>
            @else
                <div class="py-12 text-center text-[13px] text-slate-400">Belum ada data pengukuran.</div>
            @endif
        </section>
    </div>

    {{-- TAB: KURVA WHO --}}
    <div x-show="tab === 'kurva'" x-cloak>
        <section class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 sm:p-6" x-data="{ chartType: 'w' }">
            <style>
                .chart-svg text { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, -apple-system, sans-serif; }
                .chart-svg .w-band { animation: chartFade .9s ease both; }
                .chart-svg .w-line { stroke-dasharray: 2200; stroke-dashoffset: 2200; animation: chartDraw 1.4s ease .12s forwards; }
                .chart-svg .w-dot { transform-box: fill-box; transform-origin: center; opacity: 0; animation: chartPop .35s cubic-bezier(.2,.8,.3,1.25) forwards; }
                @keyframes chartDraw { to { stroke-dashoffset: 0; } }
                @keyframes chartFade { from { opacity: 0; } to { opacity: 1; } }
                @keyframes chartPop { from { transform: scale(0); opacity: 0; } to { transform: scale(1); opacity: 1; } }
                @media (prefers-reduced-motion: reduce) { .chart-svg .w-line, .chart-svg .w-dot, .chart-svg .w-band { animation: none !important; stroke-dashoffset: 0 !important; opacity: 1 !important; } }
            </style>
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
                <div><h3 class="text-base font-bold text-slate-900">Kurva Pertumbuhan WHO</h3><p class="text-[12px] text-slate-500 mt-0.5">Berat/tinggi badan terhadap standar WHO (+/-2 SD)</p></div>
                <div class="flex items-center gap-1.5 p-1 rounded-xl bg-slate-100">
                    <button type="button" @click="chartType = 'w'" x-bind:class="chartType === 'w' ? 'bg-white text-teal-700 shadow-sm border border-slate-200' : 'text-slate-500 hover:text-slate-700'" class="px-3.5 h-8 rounded-lg text-[12.5px] font-bold transition-all">BB/U</button>
                    <button type="button" @click="chartType = 'h'" x-bind:class="chartType === 'h' ? 'bg-white text-teal-700 shadow-sm border border-slate-200' : 'text-slate-500 hover:text-slate-700'" class="px-3.5 h-8 rounded-lg text-[12.5px] font-bold transition-all">TB/U</button>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-[11.5px] text-slate-500 mb-3">
                <span class="font-semibold text-slate-500 uppercase tracking-wide">Skala z-score:</span>
                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> <span class="font-semibold text-emerald-700">&gt; -1 SD</span> Normal</span>
                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-400"></span> <span class="font-semibold text-amber-700">-1 s/d -2 SD</span> Risiko</span>
                <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-rose-500"></span> <span class="font-semibold text-rose-700">&lt; -2 SD</span> Stunting / Wasting</span>
            </div>
            <div class="relative">
                @foreach($tri as $type => $t)
                <div x-show="chartType === '{{ $type }}'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <svg class="chart-svg w-full h-auto" viewBox="0 0 {{ $W }} {{ $H }}" preserveAspectRatio="xMidYMid meet" role="img" aria-label="Kurva pertumbuhan {{ $type === 'w' ? 'berat badan (kg)' : 'tinggi badan (cm)' }} terhadap standar WHO">
                        <g class="text-slate-100" stroke="currentColor" stroke-width="1">@for($i = 0; $i <= 4; $i++) @php $v = $t['min'] + (($t['max'] - $t['min']) * $i / 4); $y = yOf($v, $t['min'], $t['max'], $Y0, $Y1); @endphp<line x1="{{ $X0 }}" y1="{{ $y }}" x2="{{ $X1 }}" y2="{{ $y }}" />@endfor</g>
                        <path class="w-band" d="{{ bandAreaPath($t['band'], $X0, $X1, $Y0, $Y1, $winLoPad, $winSpan, $t['min'], $t['max'], $months) }}" fill="#0d9488" fill-opacity="0.09" />
                        <path class="w-band" d="{{ bandPath($t['band'], $X0, $X1, $Y0, $Y1, $winLoPad, $winSpan, $t['min'], $t['max'], 'hi', $months) }}" fill="none" stroke="#2dd4bf" stroke-width="1.5" />
                        <path class="w-band" d="{{ bandPath($t['band'], $X0, $X1, $Y0, $Y1, $winLoPad, $winSpan, $t['min'], $t['max'], 'lo', $months) }}" fill="none" stroke="#2dd4bf" stroke-width="1.5" />
                        <path class="w-band" d="{{ bandPath($t['band'], $X0, $X1, $Y0, $Y1, $winLoPad, $winSpan, $t['min'], $t['max'], 'mid', $months) }}" fill="none" stroke="#0d9488" stroke-width="2.5" />
                        <polyline class="w-line" fill="none" stroke="#e11d48" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" points="{{ $t['pts']['line'] }}"/>
                        @foreach($t['pts']['pts'] as $pt)@php $lv = $t['dec'] ? number_format($pt['val'], 1, ',', '') : (string) round($pt['val']); $ly = ($pt['a']) ? max($pt['cy'] - 14, $Y0 + 6) : $pt['cy'] - 10; $lcol = ($pt['a']) ? '#e11d48' : '#475569'; $lsuf = ($pt['a']) ? '?' : ''; @endphp@if($pt['a'])<circle class="w-dot" cx="{{ $pt['cx'] }}" cy="{{ $pt['cy'] }}" r="9" fill="none" stroke="#e11d48" stroke-width="1.5" stroke-dasharray="3 3" style="animation-delay:{{ $loop->index * 80 }}ms" />@endif<circle class="w-dot" cx="{{ $pt['cx'] }}" cy="{{ $pt['cy'] }}" r="4.5" fill="{{ $pt['c'] }}" stroke="#fff" stroke-width="1.6" style="animation-delay:{{ $loop->index * 80 }}ms" /><text x="{{ $pt['cx'] }}" y="{{ $ly }}" text-anchor="middle" font-size="10.5" font-weight="700" fill="{{ $lcol }}">{{ $lv }}{{ $lsuf }}</text>@endforeach
                        <g class="text-slate-400" text-anchor="end" font-size="11"><text x="{{ $X0 - 8 }}" y="{{ $Y0 - 4 }}" class="font-semibold" fill="#94a3b8">{{ $unit[$t['field']] }}</text>@for($i = 0; $i <= 4; $i++) @php $v = $t['min'] + (($t['max'] - $t['min']) * $i / 4); $y = yOf($v, $t['min'], $t['max'], $Y0, $Y1); @endphp<text x="{{ $X0 - 8 }}" y="{{ $y + 4 }}" class="tabular-nums">{{ $t['dec'] ? number_format($v, 1, ',', '') : (string) round($v) }}</text>@endfor</g>
                        <g class="text-slate-400" text-anchor="middle" font-size="11">@foreach($t['xticks'] as $xt)<text x="{{ xOf($xt[0], $X0, $X1, $winLoPad, $winSpan) }}" y="{{ $Y1 + 20 }}" class="tabular-nums">{{ $xt[0] }} bln</text>@endforeach</g>
                    </svg>
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-2 text-[11px] text-slate-500">
                        <span class="inline-flex items-center gap-1.5"><span class="w-3 h-0.5 bg-teal-600 rounded"></span> Median WHO</span>
                        <span class="inline-flex items-center gap-1.5"><span class="w-3 h-0.5 bg-teal-400 opacity-60 rounded"></span> ±2 SD</span>
                        <span class="inline-flex items-center gap-1.5"><span class="w-3 h-0.5 bg-rose-600 rounded"></span> Data balita</span>
                        <span class="inline-flex items-center gap-1.5"><span class="w-2 h-2 rounded-full border-2 border-dashed border-rose-500"></span> Data anomali (cek ulang)</span>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
    </div>

</div>
</div>
@endsection
