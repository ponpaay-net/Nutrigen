@extends('layouts.app')

@section('page-title', 'Dashboard — NutriGen Kemenkes')

@section('content')
@php
    $monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $currentMonthName = $monthNames[$currentMonth - 1] ?? '';
@endphp

<div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full min-h-screen" style="background:#F7F8FA; font-family:'Plus Jakarta Sans',sans-serif;">

    {{-- ── Header ──────────────────────────────────────────────────────────── --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-start justify-between gap-4">
        <div>
            <p class="text-[11px] font-bold tracking-[0.1em] uppercase text-[#0D9B76] mb-1">Pemantauan Nasional</p>
            <h1 class="text-[22px] font-bold text-slate-900 tracking-tight leading-snug">
                Gizi Balita &mdash; {{ $currentMonthName }} {{ $currentYear }}
            </h1>
            <p class="text-[13px] text-slate-500 mt-0.5">Seluruh wilayah &bull; Data pengukuran terverifikasi</p>
        </div>

        <div class="flex items-center gap-3 flex-shrink-0">
            {{-- Period Filter --}}
            <form method="GET" action="/super-admin/dashboard">
                <div class="flex items-center gap-1.5 bg-white border border-slate-200 rounded-xl px-3.5 py-2.5 shadow-sm">
                    <svg class="w-4 h-4 text-slate-400 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/></svg>
                    <select name="month" onchange="this.form.submit()"
                        class="text-[13px] font-semibold text-slate-700 bg-transparent border-none outline-none cursor-pointer pr-1">
                        @foreach($monthNames as $i => $nama)
                            <option value="{{ $i + 1 }}" {{ $currentMonth == ($i + 1) ? 'selected' : '' }}>{{ $nama }}</option>
                        @endforeach
                    </select>
                    <select name="year" onchange="this.form.submit()"
                        class="text-[13px] font-semibold text-slate-700 bg-transparent border-none outline-none cursor-pointer">
                        @for($y = date('Y'); $y >= 2024; $y--)
                            <option value="{{ $y }}" {{ $currentYear == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </form>
        <!-- Export Buttons: CSV & PDF -->
        <div class="flex items-center gap-2">
            <a href="{{ route('super-admin.puskesmas.export.csv', ['month' => $currentMonth, 'year' => $currentYear]) }}" 
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all active:scale-95" 
               aria-label="Export data as CSV">
                <x-icon name="file-csv" weight="bold" class="text-sm" />
                Export CSV
            </a>
            <a href="{{ route('super-admin.export.pdf', ['month' => $currentMonth, 'year' => $currentYear]) }}" 
               target="_blank"
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold rounded-xl shadow-xs transition-all active:scale-95" 
               aria-label="Export data as PDF">
                <x-icon name="file-pdf" weight="bold" class="text-sm" />
                Export PDF
            </a>
        </div>
        </div>
    </div>

    {{-- ── Attention Banner (shown only when data exists AND there are high-risk puskesmas) --}}
    @if($totalMeasured > 0 && $attentionCount > 0)
    <div class="mb-6 flex items-center gap-3 bg-[#FFF5F5] border border-[#FECACA] rounded-xl px-5 py-3.5">
        <div class="w-8 h-8 rounded-full bg-[#E11D48] flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
        </div>
        <div class="flex-1 min-w-0">
            <span class="text-[13px] font-bold text-slate-800">{{ $attentionCount }} puskesmas</span>
            <span class="text-[13px] text-slate-600"> dengan tingkat stunting tinggi (&gt;15%) butuh perhatian segera.</span>
        </div>
        <a href="#tabel-puskesmas" class="text-[12px] font-bold text-[#E11D48] hover:underline flex-shrink-0">Lihat →</a>
    </div>
    @endif

    {{-- ── Empty State Banner (no data for selected month) ──────────────────── --}}
    @if($totalMeasured === 0)
    <div class="mb-6 flex items-center gap-3 bg-[#F0FDF9] border border-[#A7F3D0] rounded-xl px-5 py-3.5">
        <div class="w-8 h-8 rounded-full bg-[#0D9B76]/10 flex items-center justify-center flex-shrink-0">
            <svg class="w-4 h-4 text-[#0D9B76]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 16v-4m0-4h.01"/></svg>
        </div>
        <div class="flex-1 min-w-0">
            <span class="text-[13px] font-bold text-slate-800">Belum ada pengukuran terverifikasi</span>
            <span class="text-[13px] text-slate-600"> untuk {{ $currentMonthName }} {{ $currentYear }}.
                @if($lastDataMonth)
                    Terakhir ada data: <strong>{{ $lastDataMonth }}</strong>.
                @endif
            </span>
        </div>
    </div>
    @endif

    {{-- ── Row 1: KPI Cards + Bar Chart ─────────────────────────────────────── --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm mb-6 flex flex-col lg:flex-row w-full overflow-hidden">
        {{-- Teal top accent line - NutriGen brand --}}
        <div class="h-1 w-full lg:hidden" style="background:linear-gradient(90deg,#0D9B76,#34D399)"></div>
        <div class="hidden lg:block absolute-none w-1 self-stretch" style="background:linear-gradient(180deg,#0D9B76,#34D399)"></div>

        {{-- Left: 2×2 KPI Grid --}}
        <div class="w-full lg:w-[42%] flex flex-col border-b lg:border-b-0 lg:border-r border-slate-100 relative">
            {{-- brand accent strip --}}
            <div class="absolute top-0 left-0 w-full h-0.5 rounded-t-2xl" style="background:linear-gradient(90deg,#0D9B76,#34D399)"></div>

            {{-- Row 1 --}}
            <div class="flex w-full border-b border-slate-100 flex-1">
                <div class="w-1/2 p-5 border-r border-slate-100 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold tracking-wide uppercase text-slate-400">Puskesmas</span>
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#E6F9F3">
                            <svg class="w-3.5 h-3.5" style="color:#0D9B76" fill="currentColor" viewBox="0 0 20 20"><path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zm0 6a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2zm0 6a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1v-2z"/></svg>
                        </div>
                    </div>
                    <div>
                        <div class="text-[32px] font-bold text-slate-900 leading-none tabular-nums">{{ number_format($totalPuskesmas) }}</div>
                        <div class="text-[12px] text-slate-400 font-medium mt-1">Terdaftar aktif</div>
                    </div>
                </div>
                <div class="w-1/2 p-5 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold tracking-wide uppercase text-slate-400">Posyandu</span>
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#E6F9F3">
                            <svg class="w-3.5 h-3.5" style="color:#0D9B76" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h3a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h3a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/></svg>
                        </div>
                    </div>
                    <div>
                        <div class="text-[32px] font-bold text-slate-900 leading-none tabular-nums">{{ number_format($totalPosyandu) }}</div>
                        <div class="text-[12px] text-slate-400 font-medium mt-1">Beroperasi penuh</div>
                    </div>
                </div>
            </div>

            {{-- Row 2 --}}
            <div class="flex w-full flex-1">
                <div class="w-1/2 p-5 border-r border-slate-100 flex flex-col justify-between">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold tracking-wide uppercase text-slate-400">Total Balita</span>
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center bg-amber-50">
                            <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/></svg>
                        </div>
                    </div>
                    <div>
                        <div class="text-[32px] font-bold text-slate-900 leading-none tabular-nums">{{ number_format($totalBalita) }}</div>
                        <div class="text-[12px] text-slate-400 font-medium mt-1">Di seluruh wilayah</div>
                    </div>
                </div>

                {{-- PRIMARY KPI — Pengukuran Valid (most important metric) --}}
                <div class="w-1/2 p-5 flex flex-col justify-between" style="background:#F0FDF9">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-[11px] font-bold tracking-wide uppercase" style="color:#0D9B76">Terverifikasi</span>
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center" style="background:#0D9B76">
                            <svg class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                    <div>
                        <div class="text-[32px] font-bold leading-none tabular-nums" style="color:#0D9B76">{{ number_format($totalMeasured) }}</div>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-[12px] font-medium text-slate-500">{{ $currentMonthName }}</span>
                            @if($measuredDelta !== null)
                                <span class="text-[11px] font-bold px-1.5 py-0.5 rounded {{ $measuredDelta >= 0 ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
                                    {{ $measuredDeltaSign }}{{ $measuredDelta }} vs bln lalu
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Bar Chart --}}
        <div class="w-full lg:w-[58%] p-6 flex flex-col">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <div class="text-[14px] font-bold text-slate-800">Tren Status Gizi</div>
                    <div class="text-[11px] text-slate-400 font-medium mt-0.5">6 bulan terakhir &bull; Semua wilayah</div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-500">
                        <span class="w-3 h-2 rounded-sm" style="background:#0D9B76;display:inline-block"></span>Normal
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-500">
                        <span class="w-3 h-2 rounded-sm" style="background:#F59E0B;display:inline-block"></span>Risiko
                    </div>
                    <div class="flex items-center gap-1.5 text-[11px] font-semibold text-slate-500">
                        <span class="w-3 h-2 rounded-sm" style="background:#E11D48;display:inline-block"></span>Stunting
                    </div>
                </div>
            </div>
            <div class="w-full relative flex-1" style="height:240px; min-height:200px;">
                <canvas id="barChart"></canvas>
            </div>
        </div>
    </div>

    {{-- ── Row 2: Puskesmas Table + Donut ──────────────────────────────────── --}}
    <div class="flex flex-col lg:flex-row gap-6 w-full">

        {{-- Puskesmas Table --}}
        <div class="w-full lg:w-[60%] flex flex-col" id="tabel-puskesmas">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-[16px] font-bold text-slate-800">Kinerja Wilayah</h2>
                    <p class="text-[12px] text-slate-400 mt-0.5">Diurutkan: risiko stunting tertinggi</p>
                </div>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex-1">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left" style="min-width:480px">
                        <thead>
                            <tr class="border-b border-slate-100" style="background:#F7F8FA">
                                <th class="py-3.5 px-5 text-[11px] font-bold tracking-wide uppercase text-slate-400">Puskesmas</th>
                                <th class="py-3.5 px-4 text-[11px] font-bold tracking-wide uppercase text-slate-400">Posyandu</th>
                                <th class="py-3.5 px-4 text-[11px] font-bold tracking-wide uppercase text-slate-400">Diukur</th>
                                <th class="py-3.5 px-4 text-[11px] font-bold tracking-wide uppercase text-slate-400">Status</th>
                                <th class="py-3.5 px-4 text-[11px] font-bold tracking-wide uppercase text-slate-400">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($puskesmasLeaderboard as $puskesmas)
                            <tr class="border-b border-slate-50 hover:bg-slate-50/60 transition-colors">
                                <td class="py-3.5 px-5">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 text-[12px] font-bold text-white" style="background:#0D9B76">
                                            {{ substr($puskesmas->nama, 0, 1) }}
                                        </div>
                                        <span class="text-[13px] font-semibold text-slate-800">{{ $puskesmas->nama }}</span>
                                    </div>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-[13px] text-slate-600 tabular-nums">{{ $puskesmas->posyandus_count }}</span>
                                </td>
                                <td class="py-3.5 px-4">
                                    <span class="text-[13px] font-semibold text-slate-700 tabular-nums">{{ $puskesmas->measured_count }} <span class="font-normal text-slate-400">anak</span></span>
                                </td>
                                <td class="py-3.5 px-4">
                                    @if($puskesmas->stunting_rate === null)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-slate-100 text-slate-500">
                                            Belum Ada Data
                                        </span>
                                    @elseif($puskesmas->stunting_rate > 15)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-red-50 text-red-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 inline-block"></span>
                                            Tinggi · {{ $puskesmas->stunting_rate }}%
                                        </span>
                                    @elseif($puskesmas->stunting_rate > 5)
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold bg-amber-50 text-amber-600">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-400 inline-block"></span>
                                            Waspada · {{ $puskesmas->stunting_rate }}%
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-lg text-[11px] font-bold text-emerald-700" style="background:#E6F9F3">
                                            <span class="w-1.5 h-1.5 rounded-full inline-block" style="background:#0D9B76"></span>
                                            Aman · {{ $puskesmas->stunting_rate }}%
                                        </span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <a href="{{ route('super-admin.puskesmas.show', $puskesmas->id) }}" class="text-[12px] font-bold hover:underline" style="color:#0D9B76">Detail →</a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="py-12 text-center">
                                    <div class="flex flex-col items-center gap-2">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                        <p class="text-[13px] text-slate-400">Belum ada data puskesmas terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Real Pagination --}}
                @if($totalPuskesmasCount > $perPage)
                <div class="px-5 py-3.5 flex items-center justify-between border-t border-slate-100">
                    <span class="text-[12px] text-slate-400">
                        {{ ($page - 1) * $perPage + 1 }}–{{ min($page * $perPage, $totalPuskesmasCount) }} dari {{ $totalPuskesmasCount }} puskesmas
                    </span>
                    <div class="flex items-center gap-1">
                        @if($page > 1)
                        <a href="?month={{ $currentMonth }}&year={{ $currentYear }}&page={{ $page - 1 }}"
                           class="h-8 px-3 flex items-center justify-center text-[12px] font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                            ← Prev
                        </a>
                        @endif
                        @for($p = max(1, $page - 2); $p <= min($lastPage, $page + 2); $p++)
                        <a href="?month={{ $currentMonth }}&year={{ $currentYear }}&page={{ $p }}"
                           class="h-8 w-8 flex items-center justify-center text-[12px] font-semibold rounded-lg transition {{ $p == $page ? 'text-white' : 'text-slate-500 hover:bg-slate-50 border border-slate-200 bg-white' }}"
                           @if($p == $page) style="background:#0D9B76" @endif>
                            {{ $p }}
                        </a>
                        @endfor
                        @if($page < $lastPage)
                        <a href="?month={{ $currentMonth }}&year={{ $currentYear }}&page={{ $page + 1 }}"
                           class="h-8 px-3 flex items-center justify-center text-[12px] font-semibold text-slate-600 bg-white border border-slate-200 rounded-lg hover:bg-slate-50 transition">
                            Next →
                        </a>
                        @endif
                    </div>
                </div>
                @else
                <div class="px-5 py-3 border-t border-slate-100">
                    <span class="text-[12px] text-slate-400">{{ $totalPuskesmasCount }} puskesmas terdaftar</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Donut Chart + Legend --}}
        <div class="w-full lg:w-[40%] flex flex-col">
            <div class="mb-4">
                <h2 class="text-[16px] font-bold text-slate-800">Distribusi Gizi</h2>
                <p class="text-[12px] text-slate-400 mt-0.5">{{ $currentMonthName }} {{ $currentYear }} &bull; Persentase per kategori</p>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex-1 flex flex-col p-6">
                @if($totalMeasured > 0)
                    {{-- Donut --}}
                    <div class="flex items-center justify-center">
                        <div class="relative flex-shrink-0" style="width:180px;height:180px;">
                            <canvas id="donutChart"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Total</span>
                                <span class="text-[28px] font-bold text-slate-900 leading-none tabular-nums mt-0.5">{{ number_format($totalMeasured) }}</span>
                                <span class="text-[11px] text-slate-400 mt-0.5">anak</span>
                            </div>
                        </div>
                    </div>

                    {{-- Breakdown rows --}}
                    <div class="mt-6 space-y-3 flex-1">
                        @php
                            $cats = [
                                ['label' => 'Normal & Baik', 'count' => $totalNormal,   'color' => '#0D9B76', 'bg' => '#E6F9F3'],
                                ['label' => 'Risiko / Kurang','count' => $totalRisiko,   'color' => '#F59E0B', 'bg' => '#FFFBEB'],
                                ['label' => 'Stunting',       'count' => $totalStunting, 'color' => '#E11D48', 'bg' => '#FFF5F5'],
                            ];
                        @endphp
                        @foreach($cats as $cat)
                        @php $pct = $totalMeasured > 0 ? round(($cat['count'] / $totalMeasured) * 100, 1) : 0; @endphp
                        <div class="flex items-center gap-3">
                            <div class="w-2.5 h-2.5 rounded-sm flex-shrink-0" style="background:{{ $cat['color'] }}"></div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-[12px] font-semibold text-slate-700">{{ $cat['label'] }}</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-[12px] font-bold text-slate-900 tabular-nums">{{ $cat['count'] }}</span>
                                        <span class="text-[11px] font-bold px-1.5 py-0.5 rounded-md" style="background:{{ $cat['bg'] }};color:{{ $cat['color'] }}">{{ $pct }}%</span>
                                    </div>
                                </div>
                                {{-- Mini progress bar --}}
                                <div class="h-1 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full transition-all duration-500" style="width:{{ $pct }}%;background:{{ $cat['color'] }}"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    {{-- Donut empty state --}}
                    <div class="flex-1 flex flex-col items-center justify-center text-center py-6">
                        <div class="relative w-[160px] h-[160px] mb-4">
                            <canvas id="donutChart"></canvas>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-[11px] font-semibold text-slate-400 uppercase tracking-wide">Total</span>
                                <span class="text-[28px] font-bold text-slate-300 leading-none tabular-nums mt-0.5">0</span>
                            </div>
                        </div>

                        <p class="text-[13px] font-bold text-slate-700 mb-1">Belum ada data bulan ini</p>
                        @if($lastDataMonth)
                            <p class="text-[12px] text-slate-400 font-medium">Terakhir ada data: {{ $lastDataMonth }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>

    </div>

    {{-- Trust Signals Footer (Bottom of Page) --}}
    <div class="mt-8 pt-4 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-400">
        <div class="flex items-center gap-2">
            <x-icon name="check-circle" weight="bold" class="text-teal-600 text-sm" />
            <span>Data sinkronisasi: {{ now()->translatedFormat('d F Y H:i') }} WIB</span>
        </div>
        <div>Sumber: Kementerian Kesehatan Republik Indonesia</div>
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (typeof Chart === 'undefined') return;
    Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";

    // ── Bar Chart ────────────────────────────────────────────────────────────
    const ctxBar = document.getElementById('barChart');
    if (ctxBar) {
        try {
            new Chart(ctxBar, {
                type: 'bar',
                data: {
                    labels: {!! json_encode($months) !!},
                    datasets: [
                        {
                            label: 'Normal & Baik',
                            data: {!! json_encode($trendNormal) !!},
                            backgroundColor: '#0D9B76',
                            borderRadius: 5,
                            barPercentage: 0.55,
                            categoryPercentage: 0.75,
                        },
                        {
                            label: 'Risiko',
                            data: {!! json_encode($trendRisiko) !!},
                            backgroundColor: '#F59E0B',
                            borderRadius: 5,
                            barPercentage: 0.55,
                            categoryPercentage: 0.75,
                        },
                        {
                            label: 'Stunting',
                            data: {!! json_encode($trendStunting) !!},
                            backgroundColor: '#E11D48',
                            borderRadius: 5,
                            barPercentage: 0.55,
                            categoryPercentage: 0.75,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0F172A',
                            titleFont: { size: 12, weight: '700' },
                            bodyFont: { size: 12 },
                            padding: 12,
                            cornerRadius: 10,
                        }
                    },
                    scales: {
                        x: {
                            stacked: true,
                            grid: { display: false },
                            ticks: { font: { size: 11, weight: '600' }, color: '#94A3B8' },
                            border: { display: false }
                        },
                        y: {
                            stacked: true,
                            beginAtZero: true,
                            grid: { color: '#F1F5F9' },
                            ticks: { font: { size: 11 }, color: '#94A3B8', padding: 8, maxTicksLimit: 5 },
                            border: { display: false }
                        }
                    }
                }
            });
        } catch(e) { console.error('Bar chart:', e); }
    }

    // ── Donut Chart ──────────────────────────────────────────────────────────
    const ctxDonut = document.getElementById('donutChart');
    if (ctxDonut) {
        try {
            const n = {{ (int)$totalNormal }};
            const r = {{ (int)$totalRisiko }};
            const s = {{ (int)$totalStunting }};
            const total = n + r + s;
            new Chart(ctxDonut, {
                type: 'doughnut',
                data: {
                    labels: ['Normal & Baik', 'Risiko', 'Stunting'],
                    datasets: [{
                        data: total > 0 ? [n, r, s] : [1, 0, 0],
                        backgroundColor: total > 0
                            ? ['#0D9B76', '#F59E0B', '#E11D48']
                            : ['#E2E8F0', '#E2E8F0', '#E2E8F0'],
                        borderWidth: 0,
                        hoverOffset: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            enabled: total > 0,
                            backgroundColor: '#0F172A',
                            padding: 12,
                            cornerRadius: 10,
                        }
                    }
                }
            });
        } catch(e) { console.error('Donut chart:', e); }
    }
});
</script>
@endpush
