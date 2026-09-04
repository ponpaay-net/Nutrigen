@extends('layouts.puskesmas')
@section('page-title', 'Dashboard')

@push('styles')
<style>
    /* ApexCharts customizations for cleaner look */
    .apexcharts-tooltip {
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) !important;
        border: 1px solid #e2e8f0 !important;
        border-radius: 0.5rem !important;
        font-family: inherit !important;
    }
    .apexcharts-tooltip-title {
        background: #f8fafc !important;
        border-bottom: 1px solid #e2e8f0 !important;
        font-weight: 700 !important;
        padding: 0.5rem 0.75rem !important;
    }
    .apexcharts-text {
        font-family: inherit !important;
    }
</style>
@endpush

@section('content')

@php
    $hour = date('H');
    $greeting = 'Selamat Pagi';
    if ($hour >= 12 && $hour < 15) $greeting = 'Selamat Siang';
    elseif ($hour >= 15 && $hour < 18) $greeting = 'Selamat Sore';
    elseif ($hour >= 18) $greeting = 'Selamat Malam';

    // Parse user name
    $doctorName = trim(explode(' ', $stats['user_name'] ?? 'Dokter')[0]);
    if (strpos(strtolower($stats['user_name']), 'dr.') !== false) {
        $doctorName = 'dr. ' . ucwords(trim(str_replace(['dr.', 'Dr.', 'DR.'], '', $stats['user_name'])));
    }

    // Chart Data JSON
    $donutData = [
        $distribution['normal']['count'] ?? 0,
        $distribution['perlu_perhatian']['count'] ?? 0,
        $distribution['berisiko']['count'] ?? 0
    ];
@endphp

<div class="w-full pb-12 p-4 sm:p-6 lg:p-8 space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-slate-900 tracking-tight">
                {{ $greeting }}, {{ $doctorName }}
            </h1>
            <p class="text-sm text-slate-500 mt-1 font-medium">
                Tinjauan indikator gizi dan aktivitas validasi Puskesmas hari ini.
            </p>
        </div>
        
        <div class="flex items-center gap-3 mt-4 md:mt-0">
            <a href="{{ route('puskesmas.laporan') }}" class="inline-flex items-center justify-center gap-2 px-3.5 py-2 bg-white border border-slate-200/80 shadow-sm rounded-lg text-xs font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 hover:text-slate-900 transition-all">
                <i class="ph-bold ph-printer text-sm text-slate-500"></i>
                Cetak Laporan
            </a>
            @if($stats['pending'] > 0)
            <a href="{{ route('puskesmas.validasi') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-teal-700 shadow-sm rounded-lg text-xs font-bold text-white hover:bg-teal-800 transition-all">
                <i class="ph-bold ph-check-square-offset text-sm"></i>
                <span>Mulai Validasi ({{ $stats['pending'] }})</span>
            </a>
            @endif
        </div>
    </div>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Metric 1 -->
        <div class="bg-white border border-slate-200 border-l-4 border-l-indigo-500 rounded-lg p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Total Balita</h3>
                <div class="w-8 h-8 rounded bg-indigo-50 flex items-center justify-center text-indigo-600 border border-indigo-100">
                    <i class="ph-fill ph-users text-lg"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['total_balita'], 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Metric 2 -->
        <div class="bg-white border border-slate-200 border-l-4 border-l-teal-500 rounded-lg p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Diukur (Bulan Ini)</h3>
                <div class="w-8 h-8 rounded bg-teal-50 flex items-center justify-center text-teal-600 border border-teal-100">
                    <i class="ph-fill ph-scales text-lg"></i>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['diukur'], 0, ',', '.') }}</span>
                @if($stats['total_balita'] > 0)
                <span class="text-[10px] font-bold text-teal-700 bg-teal-100 px-2 py-1 rounded-md">{{ round(($stats['diukur'] / $stats['total_balita']) * 100) }}% cakupan</span>
                @endif
            </div>
        </div>

        <!-- Metric 3 -->
        <div class="bg-white border border-slate-200 border-l-4 border-l-emerald-500 rounded-lg p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Verifikasi Selesai</h3>
                <div class="w-8 h-8 rounded bg-emerald-50 flex items-center justify-center text-emerald-600 border border-emerald-100">
                    <i class="ph-fill ph-seal-check text-lg"></i>
                </div>
            </div>
            <div class="flex items-baseline gap-2">
                <span class="text-3xl font-black text-slate-900 tracking-tight">{{ number_format($stats['valid'], 0, ',', '.') }}</span>
                <span class="text-sm font-medium text-slate-400">/ {{ number_format($stats['diukur'], 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Metric 4 (Interactive Entry Point) -->
        <a href="{{ route('puskesmas.validasi') }}" class="group bg-white border {{ $stats['pending'] > 0 ? 'border-rose-200 border-l-4 border-l-rose-500 ring-1 ring-rose-50' : 'border-slate-200 border-l-4 border-l-slate-400' }} rounded-lg p-5 shadow-sm hover:shadow-md transition-all block">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-[11px] font-bold {{ $stats['pending'] > 0 ? 'text-rose-600' : 'text-slate-500' }} uppercase tracking-widest flex items-center gap-1">
                    Menunggu Validasi
                    <i class="ph-bold ph-arrow-up-right text-xs opacity-60 group-hover:opacity-100 transition-opacity"></i>
                </h3>
                <div class="w-8 h-8 rounded {{ $stats['pending'] > 0 ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-slate-50 text-slate-400 border-slate-100' }} flex items-center justify-center border group-hover:scale-105 transition-transform">
                    <i class="ph-fill ph-hourglass-high text-lg"></i>
                </div>
            </div>
            <div class="flex items-baseline justify-between">
                <span class="text-3xl font-black {{ $stats['pending'] > 0 ? 'text-rose-600' : 'text-slate-900' }} tracking-tight">{{ number_format($stats['pending'], 0, ',', '.') }}</span>
                @if($stats['pending'] > 0)
                <span class="text-[11px] font-bold text-rose-600 group-hover:underline flex items-center gap-0.5">Buka Antrean &rarr;</span>
                @endif
            </div>
        </a>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left: Trend Chart -->
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-lg shadow-sm flex flex-col overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between">
                <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="ph-fill ph-trend-up text-teal-600 text-lg"></i>
                    Tren Pengukuran & Kasus (6 Bulan)
                </h2>
            </div>
            <div class="p-5 flex-1 w-full min-h-[250px] overflow-hidden">
                <div id="trendChart" class="w-full h-full"></div>
            </div>
        </div>

        <!-- Right: Donut Chart -->
        <div class="bg-white border border-slate-200 rounded-lg shadow-sm flex flex-col overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-100">
                <h2 class="text-sm font-bold text-slate-800 flex items-center gap-2">
                    <i class="ph-fill ph-chart-pie-slice text-teal-600 text-lg"></i>
                    Distribusi Status Gizi
                </h2>
            </div>
            <div class="p-5 flex-1 flex flex-col justify-between overflow-hidden">
                <div id="donutChart" class="w-full min-h-[200px] flex items-center justify-center mb-3 overflow-hidden"></div>
                
                <div class="space-y-1">
                    <div class="flex items-center justify-between text-[11px] hover:bg-slate-50 px-2 py-1.5 rounded-md transition-colors">
                        <div class="flex items-center gap-2.5">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 shadow-sm shadow-emerald-200"></span>
                            <span class="font-bold text-slate-600">Gizi Baik</span>
                        </div>
                        <span class="font-black text-slate-900">{{ number_format($distribution['normal']['count'] ?? 0, 0, ',', '.') }} <span class="text-slate-400 font-bold text-[10px] ml-1">({{ $distribution['normal']['percentage'] ?? 0 }}%)</span></span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] hover:bg-slate-50 px-2 py-1.5 rounded-md transition-colors">
                        <div class="flex items-center gap-2.5">
                            <span class="w-3 h-3 rounded-full bg-amber-500 shadow-sm shadow-amber-200"></span>
                            <span class="font-bold text-slate-600">Perhatian / Risiko</span>
                        </div>
                        <span class="font-black text-slate-900">{{ number_format($distribution['perlu_perhatian']['count'] ?? 0, 0, ',', '.') }} <span class="text-slate-400 font-bold text-[10px] ml-1">({{ $distribution['perlu_perhatian']['percentage'] ?? 0 }}%)</span></span>
                    </div>
                    <div class="flex items-center justify-between text-[11px] hover:bg-slate-50 px-2 py-1.5 rounded-md transition-colors">
                        <div class="flex items-center gap-2.5">
                            <span class="w-3 h-3 rounded-full bg-rose-500 shadow-sm shadow-rose-200"></span>
                            <span class="font-bold text-slate-600">Berisiko Tinggi</span>
                        </div>
                        <span class="font-black text-slate-900">{{ number_format($distribution['berisiko']['count'] ?? 0, 0, ',', '.') }} <span class="text-slate-400 font-bold text-[10px] ml-1">({{ $distribution['berisiko']['percentage'] ?? 0 }}%)</span></span>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Recent Activity Table -->
    <div x-data="{ searchQuery: '' }" class="bg-white border border-slate-200 rounded-lg shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-slate-50/70">
            <div class="flex items-center gap-2">
                <i class="ph-fill ph-clock-counter-clockwise text-teal-600 text-lg"></i>
                <h2 class="text-sm font-bold text-slate-800">Aktivitas Validasi Terbaru</h2>
            </div>
            <div class="flex items-center gap-3">
                <div class="relative w-full sm:w-60">
                    <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" x-model="searchQuery" placeholder="Cari nama balita..." class="w-full pl-8 pr-3 py-1.5 bg-white border border-slate-200 rounded-md text-xs font-medium text-slate-700 placeholder-slate-400 focus:outline-none focus:ring-1 focus:ring-teal-500 transition-all">
                </div>
                <a href="{{ route('puskesmas.validasi') }}" class="text-xs font-bold text-teal-700 hover:text-teal-800 shrink-0 inline-flex items-center gap-1 transition-colors">Semua Antrean Validasi &rarr;</a>
            </div>
        </div>
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr class="border-b-2 border-slate-100 bg-slate-50/50">
                        <th class="px-5 py-3 text-[10px] font-extrabold text-slate-600 uppercase tracking-wider">Data Balita</th>
                        <th class="px-5 py-3 text-[10px] font-extrabold text-slate-600 uppercase tracking-wider text-center">Antropometri</th>
                        <th class="px-5 py-3 text-[10px] font-extrabold text-slate-600 uppercase tracking-wider text-center">Status Gizi</th>
                        <th class="px-5 py-3 text-[10px] font-extrabold text-slate-600 uppercase tracking-wider text-right">Waktu & Validasi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentActivities as $activity)
                        @php
                            $gizi = strtoupper($activity->status_gizi ?? 'GIZI BAIK');
                            
                            $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                            $avatarClass = 'bg-gradient-to-br from-emerald-400 to-emerald-500 text-white shadow-sm shadow-emerald-200';
                            
                            if(str_contains(strtolower($gizi), 'buruk') || str_contains(strtolower($gizi), 'stunting')) {
                                $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                                $avatarClass = 'bg-gradient-to-br from-rose-400 to-rose-500 text-white shadow-sm shadow-rose-200';
                            }
                            elseif(str_contains(strtolower($gizi), 'kurang') || str_contains(strtolower($gizi), 'risiko')) {
                                $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                                $avatarClass = 'bg-gradient-to-br from-amber-400 to-amber-500 text-white shadow-sm shadow-amber-200';
                            }
                            elseif(str_contains(strtolower($gizi), 'lebih') || str_contains(strtolower($gizi), 'obesitas')) {
                                $badgeClass = 'bg-sky-50 text-sky-700 border-sky-200';
                                $avatarClass = 'bg-gradient-to-br from-sky-400 to-sky-500 text-white shadow-sm shadow-sky-200';
                            }

                            $val = strtolower($activity->status_validasi ?? 'pending');
                            if ($val === 'valid') {
                                $vIcon = 'ph-check-circle text-emerald-500';
                                $vText = 'Tervalidasi';
                                $vColor = 'text-emerald-700';
                            } elseif ($val === 'revisi' || $val === 'ditolak') {
                                $vIcon = 'ph-warning-circle text-rose-500';
                                $vText = 'Intervensi/Revisi';
                                $vColor = 'text-rose-700';
                            } else {
                                $vIcon = 'ph-clock text-amber-500';
                                $vText = 'Menunggu';
                                $vColor = 'text-amber-700';
                            }
                        @endphp
                        <tr x-show="!searchQuery || '{{ strtolower($activity->balita->nama ?? '') }}'.includes(searchQuery.toLowerCase())" class="hover:bg-slate-50/80 transition-colors group">
                            <td class="px-5 py-3.5">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-full {{ $avatarClass }} flex items-center justify-center font-bold text-sm shrink-0 uppercase border border-white/20">
                                        {{ substr($activity->balita->nama ?? 'B', 0, 1) }}
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <span class="text-[13px] font-bold text-slate-900 group-hover:text-teal-700 transition-colors truncate">{{ $activity->balita->nama ?? 'Budi Santoso' }}</span>
                                        <span class="text-[11px] font-medium text-slate-500 truncate">{{ $activity->balita->posyandu->nama ?? 'Posyandu' }}</span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <div class="inline-flex items-center gap-4 px-3 py-1.5 bg-slate-50 border border-slate-100 rounded-lg">
                                    <div class="text-left">
                                        <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider leading-none mb-0.5">BB</span>
                                        <span class="text-xs font-bold text-slate-800">{{ $activity->berat_badan ?? '-' }}<span class="text-[10px] font-semibold text-slate-400 ml-0.5">kg</span></span>
                                    </div>
                                    <div class="w-px h-5 bg-slate-200"></div>
                                    <div class="text-left">
                                        <span class="block text-[10px] text-slate-400 font-bold uppercase tracking-wider leading-none mb-0.5">TB</span>
                                        <span class="text-xs font-bold text-slate-800">{{ $activity->tinggi_badan ?? '-' }}<span class="text-[10px] font-semibold text-slate-400 ml-0.5">cm</span></span>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3.5 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[11px] font-extrabold uppercase tracking-wider border {{ $badgeClass }}">
                                    {{ $gizi }}
                                </span>
                            </td>
                            <td class="px-5 py-3.5 text-right">
                                <div class="flex flex-col items-end gap-1">
                                    <span class="flex items-center gap-1.5 text-xs font-bold {{ $vColor }}">
                                        <i class="ph-fill {{ $vIcon }} text-sm"></i> {{ $vText }}
                                    </span>
                                    <span class="text-[11px] text-slate-500 font-medium">{{ $activity->created_at->diffForHumans() }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-5 py-16 text-center">
                                <div class="relative w-16 h-16 mx-auto mb-4">
                                    <div class="absolute inset-0 bg-slate-100 rounded-full animate-pulse opacity-50"></div>
                                    <div class="relative w-full h-full bg-slate-50 border border-slate-200 rounded-full flex items-center justify-center">
                                        <i class="ph-duotone ph-folder-open text-2xl text-slate-400"></i>
                                    </div>
                                </div>
                                <p class="text-sm font-bold text-slate-700">Belum Ada Aktivitas</p>
                                <p class="text-xs text-slate-500 mt-1">Data pengukuran posyandu terbaru akan otomatis muncul di sini.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="md:hidden divide-y divide-slate-100">
            @forelse($recentActivities as $activity)
                @php
                    $gizi = strtoupper($activity->status_gizi ?? 'GIZI BAIK');
                    
                    $badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                    $avatarClass = 'bg-gradient-to-br from-emerald-400 to-emerald-500 text-white shadow-sm shadow-emerald-200';
                    
                    if(str_contains(strtolower($gizi), 'buruk') || str_contains(strtolower($gizi), 'stunting')) {
                        $badgeClass = 'bg-rose-50 text-rose-700 border-rose-200';
                        $avatarClass = 'bg-gradient-to-br from-rose-400 to-rose-500 text-white shadow-sm shadow-rose-200';
                    }
                    elseif(str_contains(strtolower($gizi), 'kurang') || str_contains(strtolower($gizi), 'risiko')) {
                        $badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                        $avatarClass = 'bg-gradient-to-br from-amber-400 to-amber-500 text-white shadow-sm shadow-amber-200';
                    }
                    elseif(str_contains(strtolower($gizi), 'lebih') || str_contains(strtolower($gizi), 'obesitas')) {
                        $badgeClass = 'bg-sky-50 text-sky-700 border-sky-200';
                        $avatarClass = 'bg-gradient-to-br from-sky-400 to-sky-500 text-white shadow-sm shadow-sky-200';
                    }

                    $val = strtolower($activity->status_validasi ?? 'pending');
                    if ($val === 'valid') {
                        $vIcon = 'ph-check-circle text-emerald-500';
                        $vText = 'Tervalidasi';
                        $vColor = 'text-emerald-700';
                    } elseif ($val === 'revisi' || $val === 'ditolak') {
                        $vIcon = 'ph-warning-circle text-rose-500';
                        $vText = 'Intervensi/Revisi';
                        $vColor = 'text-rose-700';
                    } else {
                        $vIcon = 'ph-clock text-amber-500';
                        $vText = 'Menunggu';
                        $vColor = 'text-amber-700';
                    }
                @endphp
                <div x-show="!searchQuery || '{{ strtolower($activity->balita->nama ?? '') }}'.includes(searchQuery.toLowerCase())" class="p-4 hover:bg-slate-50/50 transition-colors flex flex-col gap-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full {{ $avatarClass }} flex items-center justify-center font-bold text-sm shrink-0 uppercase border border-white/20">
                                {{ substr($activity->balita->nama ?? 'B', 0, 1) }}
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-sm font-bold text-slate-900 truncate">{{ $activity->balita->nama ?? 'Budi Santoso' }}</span>
                                <span class="text-[11px] font-medium text-slate-500 truncate">{{ $activity->balita->posyandu->nama ?? 'Posyandu' }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-slate-50/80 border border-slate-100 rounded-xl p-3 flex flex-col gap-2.5">
                        <div class="flex items-center justify-between">
                            <div class="flex gap-4">
                                <div>
                                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Berat</span>
                                    <span class="font-extrabold text-slate-800 text-xs">{{ $activity->berat_badan ?? '-' }}<span class="text-slate-400 font-normal text-[10px] ml-0.5">kg</span></span>
                                </div>
                                <div>
                                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Tinggi</span>
                                    <span class="font-extrabold text-slate-800 text-xs">{{ $activity->tinggi_badan ?? '-' }}<span class="text-slate-400 font-normal text-[10px] ml-0.5">cm</span></span>
                                </div>
                            </div>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wider border {{ $badgeClass }}">
                                {{ $gizi }}
                            </span>
                        </div>
                        <div class="h-px w-full bg-slate-200/60"></div>
                        <div class="flex justify-between items-center text-xs">
                            <span class="font-semibold text-slate-500 flex items-center gap-1.5"><i class="ph-bold ph-clock text-slate-400"></i> {{ $activity->created_at->diffForHumans() }}</span>
                            <span class="font-bold flex items-center gap-1 {{ $vColor }}"><i class="ph-fill {{ $vIcon }}"></i> {{ $vText }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="py-16 px-6 text-center">
                    <div class="relative w-16 h-16 mx-auto mb-4">
                        <div class="absolute inset-0 bg-slate-100 rounded-full animate-pulse opacity-50"></div>
                        <div class="relative w-full h-full bg-slate-50 border border-slate-200 rounded-full flex items-center justify-center">
                            <i class="ph-duotone ph-folder-open text-2xl text-slate-400"></i>
                        </div>
                    </div>
                    <p class="text-sm font-bold text-slate-700">Belum Ada Aktivitas</p>
                    <p class="text-xs text-slate-500 mt-1">Data pengukuran posyandu terbaru akan otomatis muncul di sini.</p>
                </div>
            @endforelse
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    document.addEventListener('alpine:init', () => {
        // Trend Data from Controller
        const trendData = @json($trendData);
        
        // 1. Trend Line/Area Chart
        const trendOptions = {
            series: [{
                name: 'Total Pengukuran',
                type: 'area',
                data: trendData.total
            }, {
                name: 'Risiko / Kurang',
                type: 'line',
                data: trendData.risiko
            }, {
                name: 'Berisiko Tinggi (Stunting)',
                type: 'line',
                data: trendData.stunting
            }],
            chart: {
                height: 250,
                type: 'line',
                fontFamily: 'inherit',
                toolbar: { show: false },
                zoom: { enabled: false },
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 500,
                    animateGradually: { enabled: false },
                    dynamicAnimation: { enabled: false }
                },
                dropShadow: {
                    enabled: true,
                    color: '#0f172a',
                    top: 10,
                    left: 0,
                    blur: 10,
                    opacity: 0.05
                }
            },
            colors: ['#0f766e', '#f59e0b', '#e11d48'], // Teal-700, Amber-500, Rose-600
            stroke: {
                curve: 'smooth',
                width: [4, 2, 2],
                dashArray: [0, 4, 0] // solid, dashed, solid
            },
            fill: {
                type: ['gradient', 'solid', 'solid'],
                gradient: {
                    shade: 'light',
                    type: 'vertical',
                    shadeIntensity: 1,
                    opacityFrom: 0.45,
                    opacityTo: 0.0,
                    stops: [0, 100]
                }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                xaxis: { lines: { show: true } },
                yaxis: { lines: { show: true } },
                padding: { top: 0, right: 0, bottom: 0, left: 10 }
            },
            tooltip: {
                theme: 'light',
                y: { formatter: function (val) { return val + " Pengukuran" } }
            },
            dataLabels: { enabled: false },
            xaxis: {
                categories: trendData.labels,
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: {
                    style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 }
                }
            },
            yaxis: {
                labels: {
                    style: { colors: '#94a3b8', fontSize: '11px', fontWeight: 600 },
                    formatter: function(val) { return Math.round(val); }
                }
            },
            legend: {
                position: 'top',
                horizontalAlign: 'right',
                markers: { radius: 2 },
                itemMargin: { horizontal: 10, vertical: 0 },
                labels: { colors: '#475569' }
            },
            markers: { size: 0, hover: { size: 5 } }
        };

        const trendChart = new ApexCharts(document.querySelector("#trendChart"), trendOptions);
        trendChart.render();

        // 2. Donut Chart
        const donutData = @json($donutData);
        // Jika data semuanya 0, ApexCharts kadang bermasalah merender ukuran. Kita siapkan penanganan.
        const isEmpty = donutData.every(item => item === 0);
        
        const donutOptions = {
            series: isEmpty ? [1] : donutData,
            chart: {
                type: 'donut',
                height: 210,
                parentHeightOffset: 0,
                fontFamily: 'inherit',
                animations: {
                    enabled: true,
                    easing: 'easeinout',
                    speed: 400,
                    animateGradually: { enabled: false },
                    dynamicAnimation: { enabled: false }
                }
            },
            labels: isEmpty ? ['Belum Ada Data'] : ['Gizi Baik', 'Perlu Perhatian', 'Berisiko Tinggi'],
            colors: isEmpty ? ['#f1f5f9'] : ['#10b981', '#f59e0b', '#f43f5e'], // Emerald, Amber, Rose
            plotOptions: {
                pie: {
                    donut: {
                        size: '75%',
                        labels: {
                            show: true,
                            name: {
                                fontSize: '11px',
                                fontWeight: 700,
                                color: '#64748b',
                                offsetY: -5
                            },
                            value: {
                                fontSize: '28px',
                                fontWeight: 900,
                                color: '#0f172a',
                                offsetY: 5,
                                formatter: function (val) { return isEmpty ? 0 : val }
                            },
                            total: {
                                show: true,
                                label: 'TOTAL DATA',
                                color: '#94a3b8',
                                fontSize: '10px',
                                fontWeight: 800,
                                formatter: function (w) {
                                    return isEmpty ? 0 : w.globals.seriesTotals.reduce((a, b) => a + b, 0)
                                }
                            }
                        }
                    }
                }
            },
            dataLabels: { enabled: false },
            legend: { show: false },
            stroke: { show: true, width: 2, colors: ['#ffffff'] },
            tooltip: {
                y: { formatter: function(val) { return isEmpty ? "0 Balita" : val + " Balita" } }
            }
        };

        const donutChart = new ApexCharts(document.querySelector("#donutChart"), donutOptions);
        donutChart.render();
    });
</script>
@endpush
