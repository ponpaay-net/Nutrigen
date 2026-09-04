@extends('layouts.puskesmas')
@section('page-title', 'Laporan Evaluasi Gizi')
@section('page-mode', 'app')
@section('content')

<div class="flex-1 overflow-y-auto overflow-x-hidden w-full bg-slate-50 font-sans">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-16">
    
        <!-- Header & Actions -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
            <div class="flex flex-col">
                <div class="flex items-center gap-2 text-slate-500 mb-1">
                    <span class="text-xs font-bold uppercase tracking-widest">Portal Puskesmas</span>
                    <i class="ph-bold ph-caret-right text-[10px]"></i>
                    <span class="text-xs font-bold uppercase tracking-widest text-slate-900">Laporan Evaluasi Gizi</span>
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Rekapitulasi Gizi & Klinis</h1>
            </div>

            <!-- Export Actions -->
            <div class="flex flex-wrap items-center gap-3 mt-4 md:mt-0">
                @if($stats['pending_validasi'] > 0)
                <a href="{{ route('puskesmas.validasi') }}" class="inline-flex items-center gap-2 bg-amber-100 hover:bg-amber-200 text-amber-800 px-4 py-2.5 rounded-xl font-bold text-sm transition-all border border-amber-300">
                    <i class="ph-bold ph-clipboard-text text-lg"></i>
                    Validasi Data
                    <span class="bg-amber-500 text-white text-[10px] px-2 py-0.5 rounded-full">{{ number_format($stats['pending_validasi']) }}</span>
                </a>
                @endif
                <a href="{{ route('puskesmas.laporan.export.excel', request()->all()) }}" class="inline-flex items-center gap-2 bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm shadow-emerald-600/20 transition-all border border-emerald-700">
                    <i class="ph-bold ph-microsoft-excel-logo text-lg"></i>
                    Export CSV
                </a>
                <a href="{{ route('puskesmas.laporan.cetak.pdf', request()->all()) }}" target="_blank" class="inline-flex items-center gap-2 bg-white hover:bg-rose-50 text-rose-700 px-4 py-2.5 rounded-xl font-bold text-sm shadow-sm border border-slate-200 hover:border-rose-300 transition-all">
                    <i class="ph-bold ph-printer text-lg"></i>
                    Cetak A4 / PDF
                </a>
            </div>
        </div>

        <!-- Filter Bar -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 mb-8">
            <form action="{{ route('puskesmas.laporan') }}" method="GET" class="flex flex-wrap items-center gap-4">
                <div class="flex items-center gap-2">
                    <div class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100 shrink-0">
                        <i class="ph-bold ph-funnel text-lg"></i>
                    </div>
                    <span class="text-sm font-bold text-slate-700">Filter Data:</span>
                </div>
                
                <select name="bulan" class="bg-slate-50 border-slate-200 text-slate-700 text-sm font-bold rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 outline-none">
                    @for ($i = 1; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $filters['bulan'] == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                            {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                        </option>
                    @endfor
                </select>

                <select name="tahun" class="bg-slate-50 border-slate-200 text-slate-700 text-sm font-bold rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 outline-none">
                    @php $currentYear = date('Y'); @endphp
                    @for ($i = $currentYear; $i >= $currentYear - 3; $i--)
                        <option value="{{ $i }}" {{ $filters['tahun'] == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>

                <select name="posyandu_id" class="bg-slate-50 border-slate-200 text-slate-700 text-sm font-bold rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block p-2.5 outline-none">
                    <option value="semua">Semua Posyandu</option>
                    @foreach($posyandus as $p)
                        <option value="{{ $p['id'] }}" {{ $filters['posyandu_id'] == $p['id'] ? 'selected' : '' }}>
                            {{ $p['nama'] }}
                        </option>
                    @endforeach
                </select>

                <button type="submit" class="bg-slate-900 hover:bg-slate-800 text-white px-5 py-2.5 rounded-lg font-bold text-sm shadow-sm ml-auto sm:ml-0 transition-colors">
                    Terapkan
                </button>
            </form>
        </div>

        <!-- KPI SUMMARY CARDS -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-xl border border-slate-200 border-l-4 border-l-slate-400 p-6 flex flex-col">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Total Sasaran</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-bold font-mono text-slate-900 tracking-tight">{{ number_format($stats['total_balita']) }}</span>
                    <span class="text-sm font-semibold text-slate-500">Balita</span>
                </div>
            </div>
            
            <div class="bg-white rounded-xl border border-slate-200 border-l-4 border-l-emerald-500 p-6 flex flex-col">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Gizi Normal</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-bold font-mono text-slate-900 tracking-tight">{{ number_format($stats['normal']) }}</span>
                    <span class="text-sm font-semibold text-slate-500">Balita</span>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 px-2 py-1 rounded-md">STATUS SEHAT</span>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 border-l-4 border-l-rose-500 p-6 flex flex-col">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Berisiko & Stunting</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-bold font-mono text-rose-600 tracking-tight">{{ number_format($stats['berisiko']) }}</span>
                    <span class="text-sm font-semibold text-slate-500">Balita</span>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <span class="text-[10px] font-bold text-rose-700 bg-rose-50 border border-rose-200 px-2 py-1 rounded-md">BUTUH INTERVENSI</span>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-slate-200 border-l-4 border-l-amber-500 p-6 flex flex-col">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-2">Validasi Tertunda</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-4xl font-bold font-mono text-slate-900 tracking-tight">{{ number_format($stats['pending_validasi']) }}</span>
                    <span class="text-sm font-semibold text-slate-500">Antrian Data</span>
                </div>
                @if($stats['pending_validasi'] == 0)
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <span class="text-[10px] font-bold text-slate-500 bg-slate-50 border border-slate-200 px-2 py-1 rounded-md">TIDAK ADA ANTRIAN</span>
                </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 xl:grid-cols-12 gap-8 items-start min-w-0">
            <!-- Data Recap Table (left 8) -->
            <div class="xl:col-span-8 bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col overflow-hidden min-w-0">
                <div class="p-6 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 border border-slate-200 flex items-center justify-center shadow-sm">
                            <i class="ph-bold ph-table text-lg"></i>
                        </div>
                        <h3 class="text-lg font-black text-slate-900 tracking-tight">Rincian per Posyandu</h3>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left min-w-[750px]">
                        <thead class="bg-slate-100/80 border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest">Nama Posyandu</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest text-center">Sasaran</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest text-center">Diukur</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest text-center">Normal</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest text-center">Risiko</th>
                                <th class="px-6 py-4 text-[11px] font-bold text-slate-500 uppercase tracking-widest text-right">Kehadiran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($reports as $row)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    <td class="px-6 py-5 align-middle">
                                        <div class="flex items-center gap-3">
                                            <i class="ph-fill ph-house-line text-slate-400 text-lg"></i>
                                            <span class="font-bold text-slate-900 text-[14px]">{{ $row['nama_posyandu'] }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5 align-middle text-center font-semibold text-slate-600 text-[14px]">{{ number_format($row['sasaran']) }}</td>
                                    <td class="px-6 py-5 align-middle text-center font-bold text-indigo-700 text-[14px]">{{ number_format($row['diukur']) }}</td>
                                    <td class="px-6 py-5 align-middle text-center">
                                        <span class="inline-flex px-2 py-1 bg-emerald-50 text-emerald-700 font-bold text-[13px] rounded-md border border-emerald-200">
                                            {{ number_format($row['normal']) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 align-middle text-center">
                                        <span class="inline-flex px-2 py-1 bg-rose-50 text-rose-700 font-bold text-[13px] rounded-md border border-rose-200">
                                            {{ number_format($row['berisiko']) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5 align-middle text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <span class="w-10 text-right font-black text-slate-900 text-[14px]">{{ $row['persentase_hadir'] }}</span>
                                            <div class="w-16 h-2 bg-slate-100 rounded-full overflow-hidden">
                                                @php
                                                    $pct = floatval(str_replace('%', '', $row['persentase_hadir']));
                                                    $color = $pct >= 80 ? 'bg-emerald-500' : ($pct >= 50 ? 'bg-amber-500' : 'bg-rose-500');
                                                @endphp
                                                <div class="h-full {{ $color }} rounded-full" style="width: {{ $pct }}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-16">
                                        <div class="flex flex-col items-center justify-center text-center">
                                            <i class="ph-bold ph-clipboard-text text-4xl text-slate-300 mb-3"></i>
                                            <h4 class="text-sm font-bold text-slate-700 mb-1">Belum Ada Data</h4>
                                            <p class="text-sm text-slate-500 max-w-sm">Belum ada data evaluasi gizi di periode ini. Pastikan kader posyandu telah menginput data.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Distribution Chart Component (right 4) -->
            <div class="xl:col-span-4 bg-white border border-slate-200 rounded-2xl shadow-sm p-6 min-w-0">
                <x-report.distribution-chart :distribution="$distribution" />
            </div>
        </div>

        <!-- Trend Component -->
        <div class="mt-8">
            <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
                <x-report.trend-chart :trends="$trends" />
            </div>
        </div>

    </div>
</div>

@endsection
