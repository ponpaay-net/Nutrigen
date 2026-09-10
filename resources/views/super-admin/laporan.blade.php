@extends('layouts.app')

@section('page-title', 'Laporan Nasional — NutriGen')

@section('content')
@php
    $monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
    $monthLabel = $monthNames[$currentMonth - 1] ?? '';
    $pct = fn ($n) => $totalMeasured > 0 ? round(($n / $totalMeasured) * 100, 1) : 0;
@endphp

<div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full min-h-screen" style="background:#F7F8FA; font-family:'Plus Jakarta Sans',sans-serif;">

    {{-- Breadcrumb --}}
    <nav class="flex text-sm font-medium text-slate-500 mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center">
            <li><a href="{{ route('super-admin.dashboard') }}" class="inline-flex items-center hover:text-teal-600 transition-colors"><x-icon name="squares-four" weight="fill" class="text-base mr-1.5" />Dashboard</a></li>
            <li class="flex items-center"><x-icon name="caret-right" weight="bold" class="text-sm mx-1.5" /><span class="text-slate-900 font-bold">Laporan Nasional</span></li>
        </ol>
    </nav>

    {{-- Header & Actions --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 mb-8 bg-white p-6 rounded-2xl shadow-sm border border-teal-100/50">
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Laporan Pemantauan Gizi Nasional</h1>
            <p class="text-sm text-slate-500 mt-1">Rekapitulasi kinerja wilayah — periode {{ $monthLabel }} {{ $currentYear }}.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <form method="GET" action="{{ route('super-admin.laporan') }}" class="flex items-center gap-1.5 bg-white border border-slate-200 rounded-xl px-3 py-2 shadow-sm">
                <select name="month" onchange="this.form.submit()" class="text-sm font-semibold text-slate-700 bg-transparent border-none outline-none cursor-pointer">
                    @foreach($monthNames as $i => $nama)<option value="{{ $i+1 }}" {{ $currentMonth == ($i+1) ? 'selected' : '' }}>{{ $nama }}</option>@endforeach
                </select>
                <select name="year" onchange="this.form.submit()" class="text-sm font-semibold text-slate-700 bg-transparent border-none outline-none cursor-pointer">
                    @for($y = date('Y'); $y >= 2024; $y--)<option value="{{ $y }}" {{ $currentYear == $y ? 'selected' : '' }}>{{ $y }}</option>@endfor
                </select>
            </form>
            <a href="{{ route('super-admin.puskesmas.export.csv', ['month'=>$currentMonth,'year'=>$currentYear]) }}" class="inline-flex items-center gap-2 bg-white text-slate-700 hover:bg-slate-50 border border-slate-300 px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all"><x-icon name="file-csv" weight="bold" class="text-lg text-teal-700" /> CSV</a>
            <a href="{{ route('super-admin.puskesmas.export.excel', ['month'=>$currentMonth,'year'=>$currentYear]) }}" class="inline-flex items-center gap-2 bg-white text-slate-700 hover:bg-slate-50 border border-slate-300 px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all"><x-icon name="file-xls" weight="bold" class="text-lg text-emerald-700" /> Excel</a>
            <a href="{{ route('super-admin.export.pdf', ['month'=>$currentMonth,'year'=>$currentYear]) }}" target="_blank" class="inline-flex items-center gap-2 bg-white text-slate-700 hover:bg-slate-50 border border-slate-300 px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm transition-all"><x-icon name="file-pdf" weight="bold" class="text-lg text-rose-600" /> PDF</a>
        </div>
    </div>

    {{-- KPI --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Balita Terverifikasi</p>
            <h3 class="text-3xl font-extrabold text-slate-900 mt-2 tabular-nums">{{ number_format($totalMeasured) }}</h3>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Normal</p>
            <h3 class="text-3xl font-extrabold text-emerald-600 mt-2 tabular-nums">{{ number_format($totalNormal) }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">{{ $pct($totalNormal) }}%</p>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Risiko / Kurang</p>
            <h3 class="text-3xl font-extrabold text-amber-600 mt-2 tabular-nums">{{ number_format($totalRisiko) }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">{{ $pct($totalRisiko) }}%</p>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Stunting</p>
            <h3 class="text-3xl font-extrabold text-rose-600 mt-2 tabular-nums">{{ number_format($totalStunting) }}</h3>
            <p class="text-[11px] text-slate-400 mt-1">{{ $pct($totalStunting) }}%</p>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 col-span-2 lg:col-span-1">
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Prevalensi Stunting</p>
            <h3 class="text-3xl font-extrabold mt-2 tabular-nums {{ $prevalensi > $threshold ? 'text-rose-600' : 'text-teal-600' }}">{{ $prevalensi }}%</h3>
            <p class="text-[11px] text-slate-400 mt-1">Ambang waspada: {{ $threshold }}%</p>
        </div>
    </div>

    {{-- Tabel per Puskesmas --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
            <h2 class="text-base font-extrabold text-slate-900">Rekapitulasi per Puskesmas</h2>
            <span class="text-xs font-semibold text-slate-400">{{ count($puskesmasList) }} faskes</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[860px]">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="py-3 px-5 text-xs font-bold text-slate-500 uppercase tracking-wider">Puskesmas</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Kecamatan</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Posyandu</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Balita</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Terukur</th>
                        <th class="py-3 px-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Stunting</th>
                        <th class="py-3 px-5 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Prevalensi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($puskesmasList as $p)
                        <tr class="hover:bg-slate-50/60 transition-colors">
                            <td class="py-4 px-5">
                                <a href="{{ route('super-admin.puskesmas.show', $p['id']) }}" class="font-bold text-slate-900 hover:text-teal-600 transition-colors">{{ $p['nama'] }}</a>
                                <div class="text-[11px] text-slate-400 font-mono mt-0.5">{{ $p['kode'] }}</div>
                            </td>
                            <td class="py-4 px-4 text-center text-sm text-slate-600">{{ $p['kecamatan'] }}</td>
                            <td class="py-4 px-4 text-center text-sm font-semibold text-slate-700 tabular-nums">{{ $p['posyandu_count'] }}</td>
                            <td class="py-4 px-4 text-center text-sm font-semibold text-slate-700 tabular-nums">{{ $p['balita_count'] }}</td>
                            <td class="py-4 px-4 text-center text-sm font-bold text-slate-900 tabular-nums">{{ $p['total_ukur'] }}</td>
                            <td class="py-4 px-4 text-center text-sm font-bold text-rose-600 tabular-nums">{{ $p['stunting'] }}</td>
                            <td class="py-4 px-5 text-center">
                                @php $warn = $p['prevalensi'] > $threshold; @endphp
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-xs font-bold {{ $warn ? 'bg-rose-50 text-rose-700' : 'bg-teal-50 text-teal-700' }}">
                                    <span class="w-1.5 h-1.5 rounded-full {{ $warn ? 'bg-rose-500' : 'bg-teal-500' }}"></span>
                                    {{ $p['prevalensi'] }}%
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="py-14 text-center text-sm text-slate-500">Belum ada data Puskesmas terdaftar.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection