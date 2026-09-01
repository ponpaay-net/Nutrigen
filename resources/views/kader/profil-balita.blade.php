@extends('layouts.app')
@section('page-title', 'Profil Balita')
@section('content')

@php
    $colorMap = ['success' => 'emerald', 'warning' => 'amber', 'danger' => 'rose'];
    $badge = [
        'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'warning' => 'bg-amber-50 text-amber-700 border-amber-200',
        'danger'  => 'bg-rose-50 text-rose-700 border-rose-200',
    ][$status_type] ?? 'bg-slate-50 text-slate-600 border-slate-200';
    $sex = ($gender === 'Laki-laki') ? 'L' : 'P';
    $svc = app(\App\Services\GrowthCalculationService::class);
    $birth = \Carbon\Carbon::parse($birthDate);

    // WHO reference per month (median + SD)
    $whoRef = [];
    for ($m = 0; $m <= 60; $m++) { $whoRef[$m] = $svc->referenceFor($m, $sex); }

    // measurement points as month + value
    $pts = collect($measurements)->map(function ($m) use ($birth) {
        return [
            'month' => (int) $birth->diffInMonths(\Carbon\Carbon::parse($m['raw_date'])),
            'w' => $m['weight'], 'h' => $m['height'],
        ];
    })->filter(fn($p) => $p['w'] !== null || $p['h'] !== null)->values();

    $maxMonth = max(60, $pts->max('month') ?? 0);
    $months = range(0, (int) $maxMonth);

    function whoBand($whoRef, $months, $field) {
        $band = [];
        foreach ($months as $m) {
            $r = $whoRef[$m];
            $band[$m]['mid'] = $r[$field . '_median'];
            $band[$m]['hi']  = $r[$field . '_median'] + 2 * $r[$field . '_sd'];
            $band[$m]['lo']  = $r[$field . '_median'] - 2 * $r[$field . '_sd'];
        }
        return $band;
    }

    function chartScales($band, $pointsField, $measurements, $field) {
        $vals = [];
        foreach ($band as $b) { $vals[] = $b['hi']; $vals[] = $b['lo']; }
        foreach ($measurements as $m) { if ($m[$field] !== null) $vals[] = $m[$field]; }
        $min = floor(min($vals)) - 2; $max = ceil(max($vals)) + 2;
        return [$min, $max];
    }

    $bbBand = whoBand($whoRef, $months, 'bb');
    $tbBand = whoBand($whoRef, $months, 'tb');
    [$bbMin, $bbMax] = chartScales($bbBand, 'w', $pts, 'w');
    [$tbMin, $tbMax] = chartScales($tbBand, 'h', $pts, 'h');

    // SVG mapping
    $W = 720; $H = 320; $padL = 44; $padR = 16; $padT = 18; $padB = 34;
    $X0 = $padL; $X1 = $W - $padR; $Y0 = $padT; $Y1 = $H - $padB;
    $monthsTotal = $maxMonth > 0 ? $maxMonth : 1;
    function xOf($m, $X0, $X1, $monthsTotal) { return $X0 + ($m / $monthsTotal) * ($X1 - $X0); }
    function yOf($v, $min, $max, $Y0, $Y1) { return $Y1 - (($v - $min) / max(1, $max - $min)) * ($Y1 - $Y0); }

    function bandPath($band, $X0, $X1, $Y0, $Y1, $monthsTotal, $min, $max, $key, $months) {
        $str = '';
        foreach ($months as $i => $m) {
            $x = xOf($m, $X0, $X1, $monthsTotal); $y = yOf($band[$m][$key], $min, $max, $Y0, $Y1);
            $str .= ($i === 0 ? 'M' : 'L') . round($x, 1) . ' ' . round($y, 1) . ' ';
        }
        return trim($str);
    }

    $tri = [ 'w' => ['band' => $bbBand, 'min' => $bbMin, 'max' => $bbMax, 'field' => 'w'],
             'h' => ['band' => $tbBand, 'min' => $tbMin, 'max' => $tbMax, 'field' => 'h'] ];

    $lastZb = $latestMeasure['z_score_bbu'] ?? null;
    $lastZt = $latestMeasure['z_score_tbu'] ?? null;
    function zClass($z) { return $z < -2 ? 'text-rose-700 bg-rose-50 border-rose-200' : ($z < -1 ? 'text-amber-700 bg-amber-50 border-amber-200' : 'text-emerald-700 bg-emerald-50 border-emerald-200'); }
    function zLabel($z) { return $z < -2 ? 'Stunting/Wasting' : ($z < -1 ? 'Risiko' : 'Normal'); }
@endphp

<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 pb-6 flex flex-col gap-5 lg:gap-6">
    <div x-data="{ chartType: 'bb' }" class="flex flex-col gap-5 lg:gap-6">

    {{-- IDENTITY CARD --}}
    <section class="bg-white border border-slate-200 rounded-2xl shadow-[0_1px_3px_rgba(15,23,42,0.06),0_12px_32px_-16px_rgba(15,23,42,0.14)] p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4 min-w-0">
                <div class="w-14 h-14 sm:w-16 sm:h-16 shrink-0 rounded-2xl bg-teal-50 border border-teal-100 text-teal-700 flex items-center justify-center">
                    <span class="text-[26px] sm:text-[32px] font-black">{{ strtoupper(substr($childName, 0, 1)) }}</span>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight truncate">{{ $childName }}</h2>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-[11.5px] font-bold {{ $badge }}">
                            <x-icon name="{{ $status_type === 'danger' ? 'warning' : ($status_type === 'warning' ? 'activity' : 'check-circle') }}" weight="fill" class="text-[12px]" />
                            {{ $status }}
                        </span>
                    </div>
                    <div class="flex items-center gap-3 flex-wrap mt-1">
                        <span class="inline-flex items-center gap-1.5 text-[12.5px] font-semibold text-slate-600">
                            <x-icon name="{{ $sex === 'L' ? 'gender-male' : 'gender-female' }}" weight="fill" class="text-[14px] text-slate-400" /> {{ $gender }}
                        </span>
                        <span class="text-slate-300">·</span>
                        <span class="inline-flex items-center gap-1.5 text-[12.5px] text-slate-500">
                            <x-icon name="calendar" weight="bold" class="text-[13px] text-slate-400" /> {{ $age }}
                        </span>
                        <span class="text-slate-300">·</span>
                        <span class="text-[12.5px] text-slate-500">Lahir {{ $birthDate }}</span>
                        @if($latestMeasure && $latestMeasure['date'])
                            <span class="text-slate-300">·</span>
                            <span class="inline-flex items-center gap-1 text-[12px] font-medium text-teal-700"><x-icon name="clock" weight="bold" class="text-[12px]" /> Terakhir diukur {{ $latestMeasure['date'] }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0 flex-wrap">
                <a href="{{ route('balita.index') }}" class="inline-flex items-center justify-center gap-1.5 h-10 px-3.5 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 text-[13px] font-semibold transition-colors">
                    <x-icon name="arrow-left" weight="bold" class="text-[15px]" /> Kembali
                </a>
                <a href="{{ route('balita.edit', $balitaId) }}" class="inline-flex items-center justify-center gap-1.5 h-10 px-3.5 rounded-xl border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 text-[13px] font-semibold transition-colors">
                    <x-icon name="pencil-line" weight="bold" class="text-[15px]" /> Edit
                </a>
                <form id="delete-balita-{{ $balitaId }}" action="{{ route('balita.destroy', $balitaId) }}" method="POST" class="inline">
                    @csrf @method('DELETE')
                    <button type="button" onclick="if(confirm('Hapus balita ini? Data tidak bisa dikembalikan.')) document.getElementById('delete-balita-{{ $balitaId }}').submit()" class="inline-flex items-center justify-center gap-1.5 h-10 px-3.5 rounded-xl border border-rose-200 bg-rose-50 text-rose-600 hover:bg-rose-100 text-[13px] font-semibold transition-colors">
                        <x-icon name="trash" weight="bold" class="text-[15px]" /> Hapus
                    </button>
                </form>
                <a href="{{ route('balita.show', [$balitaId, 'action' => 'ukur']) }}" class="inline-flex items-center justify-center gap-1.5 h-10 px-4 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-[13px] font-semibold shadow-sm transition-colors">
                    <x-icon name="scales" weight="bold" class="text-[15px]" /> Ukur Sekarang
                </a>
            </div>
        </div>
    </section>

    {{-- STATUS ATTENTION BANNER --}}
    @if($status_type === 'danger' || $status_type === 'warning')
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 p-4 rounded-2xl border {{ $status_type === 'danger' ? 'bg-rose-50 border-rose-200' : 'bg-amber-50 border-amber-200' }}">
        <div class="flex items-start gap-2.5 min-w-0">
            <x-icon name="{{ $status_type === 'danger' ? 'warning' : 'activity' }}" weight="fill" class="text-[22px] {{ $status_type === 'danger' ? 'text-rose-600' : 'text-amber-600' }} shrink-0 mt-0.5" />
            <div>
                <p class="text-[14px] font-bold {{ $status_type === 'danger' ? 'text-rose-700' : 'text-amber-700' }}">Balita ini memerlukan perhatian</p>
                <p class="text-[12.5px] text-slate-600 mt-0.5">Status gizi: <span class="font-semibold">{{ $status }}</span>. {{ $status_type === 'danger' ? 'Segera lakukan tindak lanjut & rujuk ke Puskesmas.' : 'Lakukan penimbangan ulang rutin bulan ini.' }}</p>
            </div>
        </div>
        <a href="{{ route('balita.show', [$balitaId, 'action' => 'ukur']) }}" class="shrink-0 inline-flex items-center justify-center gap-1.5 h-10 px-4 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-[13px] font-semibold transition-colors"><x-icon name="scales" weight="bold" class="text-[15px]" /> Ukur Ulang</a>
    </div>
    @endif

    {{-- LATEST MEASUREMENT METRICS --}}
    <section class="grid grid-cols-1 sm:grid-cols-3 gap-3 lg:gap-4">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center"><x-icon name="scales" weight="bold" class="text-[16px]" /></span>
                    <span class="text-[13px] font-bold text-slate-700">Berat Badan</span>
                </div>
                @if(!empty($latestMeasure['weight_trend']))
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold {{ $latestMeasure['weight_trend'] >= 0 ? 'text-emerald-700 bg-emerald-50' : 'text-rose-700 bg-rose-50' }} px-2 py-0.5 rounded-full">
                        <x-icon name="{{ $latestMeasure['weight_trend'] >= 0 ? 'trend-up' : 'trend-down' }}" weight="bold" class="text-[12px]" /> {{ $latestMeasure['weight_trend'] >= 0 ? '+' : '' }}{{ $latestMeasure['weight_trend'] }} kg
                    </span>
                @endif
            </div>
            <div class="flex items-baseline gap-1">
                <span class="text-4xl font-bold text-slate-900 tabular-nums leading-none">{{ $latestMeasure['weight'] ?? ($birthWeight ?: '-') }}</span>
                <span class="text-[16px] text-slate-500">kg</span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Z-Score BB/U</span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold {{ $lastZb !== null ? zClass($lastZb) : 'bg-slate-100 text-slate-500' }}">
                    {{ $lastZb !== null ? round($lastZb,2) . ' SD' : ($birthWeight ? 'Awal' : '—') }}
                </span>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center justify-between mb-3">
                <div class="flex items-center gap-2.5">
                    <span class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center"><x-icon name="ruler" weight="bold" class="text-[16px]" /></span>
                    <span class="text-[13px] font-bold text-slate-700">Tinggi / Panjang</span>
                </div>
                @if(!empty($latestMeasure['height_trend']))
                    <span class="inline-flex items-center gap-1 text-[11px] font-bold {{ $latestMeasure['height_trend'] >= 0 ? 'text-emerald-700 bg-emerald-50' : 'text-rose-700 bg-rose-50' }} px-2 py-0.5 rounded-full">
                        <x-icon name="{{ $latestMeasure['height_trend'] >= 0 ? 'trend-up' : 'trend-down' }}" weight="bold" class="text-[12px]" /> {{ $latestMeasure['height_trend'] >= 0 ? '+' : '' }}{{ $latestMeasure['height_trend'] }} cm
                    </span>
                @endif
            </div>
            <div class="flex items-baseline gap-1">
                <span class="text-4xl font-bold text-slate-900 tabular-nums leading-none">{{ $latestMeasure['height'] ?? ($birthLength ?: '-') }}</span>
                <span class="text-[16px] text-slate-500">cm</span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Z-Score TB/U</span>
                <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold {{ $lastZt !== null ? zClass($lastZt) : 'bg-slate-100 text-slate-500' }}">
                    {{ $lastZt !== null ? round($lastZt,2) . ' SD' : ($birthLength ? 'Awal' : '—') }}
                </span>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm">
            <div class="flex items-center gap-2.5 mb-3">
                <span class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center"><x-icon name="user-circle" weight="bold" class="text-[16px]" /></span>
                <span class="text-[13px] font-bold text-slate-700">Lingkar Kepala</span>
            </div>
            <div class="flex items-baseline gap-1">
                <span class="text-4xl font-bold text-slate-900 tabular-nums leading-none">{{ $latestMeasure['head_circ'] ?? ($birthHeadCirc ?: '-') }}</span>
                <span class="text-[16px] text-slate-500">cm</span>
            </div>
            <div class="mt-4 pt-3 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide">Pengukuran</span>
                <span class="text-[11px] font-bold text-slate-500">{{ $latestMeasure['date'] ?? '—' }}</span>
            </div>
        </div>
    </section>

    {{-- Z-SCORE SCALE LEGEND --}}
    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-[11.5px] text-slate-500 -mt-1">
        <span class="font-semibold text-slate-500 uppercase tracking-wide">Skala z-score:</span>
        <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-emerald-500"></span> <span class="font-semibold text-emerald-700">&gt; -1 SD</span> Normal</span>
        <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-amber-400"></span> <span class="font-semibold text-amber-700">-1 s/d -2 SD</span> Risiko</span>
        <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-rose-500"></span> <span class="font-semibold text-rose-700">&lt; -2 SD</span> Stunting / Wasting</span>
    </div>

    {{-- WHO GROWTH CURVE --}}
    <section class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5 sm:p-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-5">
            <div>
                <h3 class="text-base font-bold text-slate-900">Kurva Pertumbuhan WHO</h3>
                <p class="text-[12px] text-slate-500 mt-0.5">Berat/tinggi badan terhadap standar WHO (+/-2 SD)</p>
            </div>
            <div class="flex items-center gap-1.5 p-1 rounded-xl bg-slate-100">
                <button type="button" @click="chartType = 'bb'" :class="chartType === 'bb' ? 'bg-white text-teal-700 shadow-sm border border-slate-200' : 'text-slate-500 hover:text-slate-700'" class="px-3.5 h-8 rounded-lg text-[12.5px] font-bold transition-all">BB/U</button>
                <button type="button" @click="chartType = 'tb'" :class="chartType === 'tb' ? 'bg-white text-teal-700 shadow-sm border border-slate-200' : 'text-slate-500 hover:text-slate-700'" class="px-3.5 h-8 rounded-lg text-[12.5px] font-bold transition-all">TB/U</button>
            </div>
        </div>

        <div class="relative">
            @foreach($tri as $type => $t)
            <div x-show="chartType === '{{ $type }}'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-cloak>
                <svg viewBox="0 0 {{ $W }} {{ $H }}" class="w-full h-auto" preserveAspectRatio="xMidYMid meet" role="img" aria-label="Kurva pertumbuhan {{ $type === 'w' ? 'berat badan' : 'tinggi badan' }} terhadap standar WHO (median dan rentang ±2 SD)">
                    {{-- y gridlines --}}
                    <g class="text-slate-200" stroke="currentColor" stroke-width="1">
                        @for($i = 0; $i <= 4; $i++)
                            @php $v = $t['min'] + (($t['max'] - $t['min']) * $i / 4); $y = yOf($v, $t['min'], $t['max'], $Y0, $Y1); @endphp
                            <line x1="{{ $X0 }}" y1="{{ $y }}" x2="{{ $X1 }}" y2="{{ $y }}" />
                        @endfor
                    </g>
                    {{-- y labels --}}
                    <g class="text-slate-400" text-anchor="end" font-size="11">
                        @for($i = 0; $i <= 4; $i++)
                            @php $v = $t['min'] + (($t['max'] - $t['min']) * $i / 4); $y = yOf($v, $t['min'], $t['max'], $Y0, $Y1); @endphp
                            <text x="{{ $X0 - 8 }}" y="{{ $y + 4 }}">{{ round($v, 1) }}</text>
                        @endfor
                    </g>
                    {{-- WHO band lines: median (teal) + +/-2SD --}}
                    <path d="{{ bandPath($t['band'], $X0, $X1, $Y0, $Y1, $monthsTotal, $t['min'], $t['max'], 'mid', $months) }}" fill="none" stroke="#0d9488" stroke-width="2" stroke-dasharray="5 5" />
                    <path d="{{ bandPath($t['band'], $X0, $X1, $Y0, $Y1, $monthsTotal, $t['min'], $t['max'], 'hi', $months) }}" fill="none" stroke="#2dd4bf" stroke-width="1.2" opacity="0.55" />
                    <path d="{{ bandPath($t['band'], $X0, $X1, $Y0, $Y1, $monthsTotal, $t['min'], $t['max'], 'lo', $months) }}" fill="none" stroke="#0f766e" stroke-width="1.2" opacity="0.55" />
                    {{-- child data --}}
                    <polyline fill="none" stroke="#e11d48" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"
                        points="{{ collect($pts)->filter(fn($p) => $p[$t['field']] !== null)->map(fn($p) => round(xOf($p['month'], $X0, $X1, $monthsTotal),1).','.round(yOf($p[$t['field']], $t['min'], $t['max'], $Y0, $Y1),1))->join(' ') }}" />
                    @foreach($pts->filter(fn($p) => $p[$t['field']] !== null) as $p)
                        <circle cx="{{ xOf($p['month'], $X0, $X1, $monthsTotal) }}" cy="{{ yOf($p[$t['field']], $t['min'], $t['max'], $Y0, $Y1) }}" r="3.5" fill="#e11d48" stroke="#fff" stroke-width="1.5" />
                    @endforeach
                    {{-- x labels --}}
                    <g class="text-slate-400" text-anchor="middle" font-size="11">
                        @for($i = 0; $i <= 6; $i++)
                            @php $m = round($monthsTotal * $i / 6); $x = xOf($m, $X0, $X1, $monthsTotal); @endphp
                            <text x="{{ $x }}" y="{{ $Y1 + 20 }}">{{ $m }} bln</text>
                        @endfor
                    </g>
                </svg>
                <div class="flex items-center gap-4 mt-2 text-[11px] text-slate-500">
                    <span class="inline-flex items-center gap-1.5"><span class="w-3 h-0.5 bg-teal-600 rounded"></span> Median WHO</span>
                    <span class="inline-flex items-center gap-1.5"><span class="w-3 h-0.5 bg-teal-400 opacity-60 rounded"></span> ±2 SD</span>
                    <span class="inline-flex items-center gap-1.5"><span class="w-3 h-0.5 bg-rose-600 rounded"></span> Data balita</span>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- DETAIL INFO + RIWAYAT (2 columns) --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 lg:gap-6 items-start">
        {{-- LEFT: identitas & lahir & keluarga --}}
        <div class="lg:col-span-1 flex flex-col gap-5">
            <section class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
                <div class="flex items-center gap-2.5 mb-4">
                    <span class="w-9 h-9 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center"><x-icon name="identification-card" weight="bold" class="text-[16px]" /></span>
                    <h4 class="text-[14px] font-bold text-slate-900">Identitas Balita</h4>
                </div>
                <dl class="space-y-3">
                    <div><dt class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">Nama Lengkap</dt><dd class="text-[13.5px] font-bold text-slate-800">{{ $childName }}</dd></div>
                    <div><dt class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">NIK</dt><dd class="text-[13.5px] font-semibold text-slate-800 font-mono">{{ $nik ?: '-' }}</dd></div>
                    <div class="grid grid-cols-2 gap-3"><div><dt class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">Tanggal Lahir</dt><dd class="text-[13.5px] font-semibold text-slate-800">{{ $birthDate }}</dd></div><div><dt class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">Jenis Kelamin</dt><dd class="text-[13.5px] font-semibold text-slate-800">{{ $gender }}</dd></div></div>
                    <div><dt class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">No BPJS</dt><dd class="text-[13.5px] font-semibold text-slate-800 font-mono">{{ $noBpjs ?: '-' }}</dd></div>
                </dl>
            </section>

            <section class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
                <div class="flex items-center gap-2.5 mb-4">
                    <span class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center"><x-icon name="baby" weight="bold" class="text-[16px]" /></span>
                    <h4 class="text-[14px] font-bold text-slate-900">Antropometri Lahir</h4>
                </div>
                <div class="grid grid-cols-3 gap-2.5">
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 text-center"><span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide block">Berat</span><span class="text-[14px] font-bold text-slate-800">{{ $birthWeight ? $birthWeight . ' kg' : '—' }}</span></div>
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 text-center"><span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide block">Panjang</span><span class="text-[14px] font-bold text-slate-800">{{ $birthLength ? $birthLength . ' cm' : '—' }}</span></div>
                    <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 text-center"><span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide block">L. Kepala</span><span class="text-[14px] font-bold text-slate-800">{{ $birthHeadCirc ? $birthHeadCirc . ' cm' : '—' }}</span></div>
                </div>
            </section>

            <section class="bg-white border border-slate-200 rounded-2xl shadow-sm p-5">
                <div class="flex items-center gap-2.5 mb-4">
                    <span class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center"><x-icon name="users" weight="bold" class="text-[16px]" /></span>
                    <h4 class="text-[14px] font-bold text-slate-900">Orang Tua & Domisili</h4>
                </div>
                <dl class="space-y-3">
                    <div><dt class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">Ibu</dt><dd class="text-[13.5px] font-semibold text-slate-800">{{ $motherName ?: '-' }}</dd></div>
                    <div><dt class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">Ayah</dt><dd class="text-[13.5px] font-semibold text-slate-800">{{ $fatherName ?: '-' }}</dd></div>
                    <div><dt class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">Kontak Ibu</dt><dd class="text-[13.5px] font-semibold text-slate-800">{{ $motherPhone ?: '-' }}</dd></div>
                    <div><dt class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">Domisili</dt><dd class="text-[13.5px] font-semibold text-slate-800">{{ $address ?: '-' }}{{ $addressSub ? ', ' . $addressSub : '' }}</dd></div>
                    <div><dt class="text-[10.5px] font-semibold text-slate-400 uppercase tracking-wide">Posyandu</dt><dd class="text-[13.5px] font-semibold text-slate-800">{{ $posyanduName ?: '-' }}</dd></div>
                </dl>
            </section>
        </div>

        {{-- RIGHT: riwayat pengukuran (list datar, scannable) --}}
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm p-5 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h4 class="text-[15px] font-bold text-slate-900">Riwayat Pengukuran</h4>
                    <p class="text-[12px] text-slate-500 mt-0.5">{{ count($measurements) }} kali, terbaru di atas</p>
                </div>
            </div>

            @forelse($measurements as $m)
                @php
                    $s = $m['status_validasi'] ?? 'pending';
                    $isRejected = $s === 'rejected';
                    $valBadge = match($s) {
                        'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                        'pending'  => 'bg-amber-50 text-amber-700 border-amber-200',
                        'approved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        default    => 'bg-slate-50 text-slate-600 border-slate-200',
                    };
                    $valDot = $s === 'rejected' ? 'bg-rose-500' : ($s === 'pending' ? 'bg-amber-400' : 'bg-emerald-500');
                @endphp
                <div class="{{ $isRejected ? 'bg-rose-50/60 border border-rose-200 rounded-xl px-4 py-3.5' : 'py-3.5 border-b border-slate-100' }}">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <span class="w-2 h-2 rounded-full {{ $valDot }} shrink-0"></span>
                            <span class="text-[13.5px] font-bold text-slate-800 whitespace-nowrap">{{ $m['date'] }}</span>
                            <span class="text-[12px] text-slate-400 whitespace-nowrap">· {{ $m['age_at_measure'] }}</span>
                        </div>
                        <div class="flex items-center gap-4 sm:gap-5">
                            <div class="text-right">
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide leading-none">BB</p>
                                <p class="text-[13.5px] font-bold text-slate-800 tabular-nums mt-1">{{ $m['weight'] ? number_format($m['weight'],1,',','.') . ' kg' : '—' }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide leading-none">TB</p>
                                <p class="text-[13.5px] font-bold text-slate-800 tabular-nums mt-1">{{ $m['height'] ? number_format($m['height'],1,',','.') . ' cm' : '—' }}</p>
                            </div>
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full border text-[11px] font-bold {{ $valBadge }} whitespace-nowrap">{{ $m['status'] }}</span>
                        </div>
                    </div>

                    {{-- Note from Puskesmas nutrition officer (for rejected) --}}
                    @if($isRejected && !empty($m['catatan_validator']))
                        <div class="mt-3 bg-white border border-rose-200 rounded-lg p-3 flex items-start gap-2.5">
                            <x-icon name="chat-circle-text" weight="fill" class="text-rose-500 text-[18px] shrink-0 mt-0.5" />
                            <div class="min-w-0">
                                <p class="text-[11px] font-bold text-rose-600 uppercase tracking-wide">Catatan Petugas Gizi Puskesmas</p>
                                <p class="text-[13px] text-slate-700 mt-1 leading-relaxed">{{ $m['catatan_validator'] }}</p>
                            </div>
                        </div>
                        <div class="mt-2.5 flex justify-end">
                            <a href="{{ route('balita.show', [$balitaId, 'action' => 'ukur']) }}" class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-lg bg-teal-600 hover:bg-teal-700 text-white text-[12.5px] font-semibold transition-colors"><x-icon name="pencil-line" weight="bold" class="text-[14px]" /> Perbaiki Data</a>
                        </div>
                    @endif
                </div>
            @empty
                <div class="py-12 text-center text-[13px] text-slate-400">Belum ada data pengukuran.</div>
            @endforelse
        </div>
    </div>

    </div>
</div>
@endsection
