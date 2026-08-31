@extends('layouts.puskesmas')
@section('page-title', 'Dashboard')
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

    // Donut Chart logic
    $p_normal = $distribution['normal']['percentage'] ?? 0;
    $p_perhatian = $distribution['perlu_perhatian']['percentage'] ?? 0;
    $p_berisiko = $distribution['berisiko']['percentage'] ?? 0;

    $total_p = $p_normal + $p_perhatian + $p_berisiko;
    if ($total_p > 0 && $total_p != 100) {
        $diff = 100 - $total_p;
        $max_p = max($p_normal, $p_perhatian, $p_berisiko);
        if ($max_p == $p_normal) $p_normal += $diff;
        elseif ($max_p == $p_perhatian) $p_perhatian += $diff;
        else $p_berisiko += $diff;
    }
@endphp

<div class="w-full pb-8 font-sans relative">
    <div class="flex flex-col gap-6 lg:gap-8">
        
        <!-- ==================== HEADER SECTION ==================== -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-8">
            
            <!-- Welcome Card (Col 8) -->
            <div class="lg:col-span-8 bg-gradient-to-br from-white via-teal-50/10 to-teal-100/30 rounded-3xl p-6 sm:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)] ring-1 ring-slate-100/80 flex flex-col justify-between relative overflow-hidden group">
                <!-- Background decoration -->
                <div class="absolute -top-32 -right-32 w-[400px] h-[400px] bg-gradient-to-br from-teal-200/40 to-emerald-100/20 rounded-full blur-[80px] pointer-events-none group-hover:scale-110 transition-transform duration-700"></div>
                <div class="absolute -bottom-20 -left-20 w-[300px] h-[300px] bg-gradient-to-tr from-sky-100/40 to-transparent rounded-full blur-[60px] pointer-events-none"></div>

                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-4">
                        <div class="relative flex h-2.5 w-2.5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-teal-500"></span>
                        </div>
                        <span class="text-[12px] font-extrabold tracking-[0.2em] text-teal-600 uppercase">Pusat Komando</span>
                    </div>
                    
                    <h1 class="text-3xl sm:text-[36px] text-slate-800 tracking-tight mb-3">
                        <span class="font-medium">{{ $greeting }},</span> <span class="font-black text-slate-900 bg-gradient-to-r from-teal-700 to-sky-700 bg-clip-text text-transparent">{{ $doctorName }}</span>
                    </h1>
                    <p class="text-slate-500 text-[15px] sm:text-[16px] font-medium max-w-2xl leading-relaxed">
                        Pantau indikator status gizi balita dan tinjau efektivitas layanan posyandu di seluruh wilayah kerja Anda secara real-time.
                    </p>
                </div>

                <!-- Alert/Action Box inside Welcome Card -->
                <div class="mt-8 relative z-10">
                    @if($stats['pending'] > 0)
                    <div class="bg-white/80 backdrop-blur-xl rounded-[20px] p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-5 ring-1 ring-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_8px_30px_rgba(0,0,0,0.08)] transition-all group/alert border-l-4 border-l-rose-500">
                        <div class="flex items-center gap-4 pl-1">
                            <div class="w-12 h-12 rounded-[14px] bg-gradient-to-br from-rose-50 to-rose-100 text-rose-600 flex items-center justify-center shrink-0 ring-1 ring-rose-200/50 shadow-sm group-hover/alert:rotate-12 transition-transform duration-300">
                                <i class="ph-bold ph-bell-ringing text-[24px]"></i>
                            </div>
                            <div>
                                <h3 class="text-[16px] font-bold text-slate-900">{{ $stats['pending'] }} Antrean Validasi</h3>
                                <p class="text-[13px] text-slate-500 font-medium mt-0.5">Butuh peninjauan ahli gizi segera</p>
                            </div>
                        </div>
                        <a href="{{ route('puskesmas.validasi') }}" class="w-full sm:w-auto text-center px-6 py-3 bg-gradient-to-r from-teal-600 to-teal-500 hover:from-teal-700 hover:to-teal-600 text-white text-[14px] font-bold rounded-xl transition-all shadow-[0_4px_15px_rgba(13,148,136,0.3)] shrink-0 active:scale-95 flex items-center justify-center gap-2 group/btn">
                            Validasi Sekarang <i class="ph-bold ph-arrow-right group-hover/btn:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                    @else
                    <div class="bg-emerald-50/50 backdrop-blur-xl rounded-[20px] p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-5 ring-1 ring-emerald-100/50 shadow-sm border-l-4 border-l-emerald-500">
                        <div class="flex items-center gap-4 pl-1">
                            <div class="w-12 h-12 rounded-[14px] bg-emerald-100/50 text-emerald-600 flex items-center justify-center shrink-0 ring-1 ring-emerald-200 shadow-inner">
                                <i class="ph-bold ph-check-circle text-[24px]"></i>
                            </div>
                            <div>
                                <h3 class="text-[16px] font-bold text-slate-900">Semua Data Tervalidasi</h3>
                                <p class="text-[13px] text-slate-500 font-medium mt-0.5">Tidak ada antrean tertunda saat ini</p>
                            </div>
                        </div>
                        <div class="w-full sm:w-auto text-center px-6 py-3 bg-white text-emerald-600 text-[14px] font-bold rounded-xl ring-1 ring-emerald-100 shadow-sm shrink-0 flex items-center justify-center gap-2 cursor-default">
                            <i class="ph-bold ph-checks"></i> Terbarui
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Chart Widget (Col 4) -->
            <div class="lg:col-span-4 bg-gradient-to-br from-slate-900 via-teal-900 to-slate-900 rounded-3xl p-6 sm:p-8 shadow-[0_15px_40px_rgb(15,118,110,0.4)] flex flex-col justify-between relative overflow-hidden group">
                <!-- Decorative Elements -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-teal-400 rounded-full blur-[90px] opacity-20 mix-blend-screen pointer-events-none group-hover:opacity-40 transition-opacity duration-700"></div>
                <div class="absolute -bottom-10 -left-10 w-48 h-48 bg-sky-400 rounded-full blur-[70px] opacity-20 mix-blend-screen pointer-events-none"></div>

                <div class="relative z-10 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-[11px] font-extrabold tracking-[0.2em] text-teal-200/80 uppercase">Kinerja Bulanan</span>
                        <div class="w-8 h-8 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center text-white ring-1 ring-white/20 shadow-[0_0_15px_rgba(255,255,255,0.1)]">
                            <i class="ph-bold ph-trend-up text-[16px]"></i>
                        </div>
                    </div>
                    <h2 class="text-[28px] font-black text-white leading-[1.1] tracking-tight">
                        Laporan<br>Kesehatan
                    </h2>
                </div>

                <!-- Abstract Bar Chart Visual -->
                <div class="relative z-10 w-full mt-auto">
                    <div class="bg-white/5 rounded-[20px] p-5 ring-1 ring-white/10 backdrop-blur-xl shadow-inner border border-white/5">
                        <div class="flex items-end justify-between gap-2.5 h-[80px] pt-4">
                            @php
                                $bars = [35, 50, 45, 65, 55, 85];
                            @endphp
                            @foreach($bars as $bar)
                            <div class="w-full bg-gradient-to-t from-teal-500/20 to-teal-400/60 rounded-t-lg hover:from-teal-400/40 hover:to-teal-300/80 transition-all cursor-pointer group/bar relative border-t border-white/20" style="height: {{ $bar }}%">
                                <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-white text-teal-900 text-[11px] font-extrabold py-1 px-2.5 rounded-lg opacity-0 group-hover/bar:opacity-100 transition-opacity pointer-events-none shadow-[0_4px_15px_rgba(0,0,0,0.2)] transform -translate-y-1 group-hover/bar:translate-y-0 duration-200">
                                    {{ $bar }}
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="mt-5 text-center">
                        <span class="text-[11px] font-bold text-teal-100/50 uppercase tracking-[0.2em]">Trend 6 Bulan Terakhir</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- ==================== 4 KPI CARDS ==================== -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
            
            <!-- 1. Total Balita -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[24px] p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] ring-1 ring-slate-100/80 flex flex-col justify-between hover:-translate-y-1.5 hover:shadow-[0_12px_30px_rgb(0,0,0,0.06)] transition-all duration-300 group border-b-4 border-indigo-500">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 rounded-[16px] bg-gradient-to-br from-indigo-50 to-indigo-100/50 ring-1 ring-indigo-200/50 flex items-center justify-center text-indigo-600 shadow-sm group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                        <i class="ph-fill ph-baby text-[24px]"></i>
                    </div>
                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-bold uppercase tracking-wider rounded-md ring-1 ring-emerald-100">Aktif</span>
                </div>
                <div>
                    <h3 class="text-[34px] sm:text-[38px] font-black text-slate-900 tracking-tight leading-none">{{ number_format($stats['total_balita'], 0, ',', '.') }}</h3>
                    <p class="text-[14px] font-bold text-slate-500 mt-2 uppercase tracking-wide">Total Balita</p>
                </div>
            </div>

            <!-- 2. Balita Diukur -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[24px] p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] ring-1 ring-slate-100/80 flex flex-col justify-between hover:-translate-y-1.5 hover:shadow-[0_12px_30px_rgb(0,0,0,0.06)] transition-all duration-300 group border-b-4 border-orange-500">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 rounded-[16px] bg-gradient-to-br from-orange-50 to-orange-100/50 ring-1 ring-orange-200/50 flex items-center justify-center text-orange-600 shadow-sm group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                        <i class="ph-fill ph-scales text-[24px]"></i>
                    </div>
                    <span class="px-2.5 py-1 bg-slate-50 text-slate-500 text-[10px] font-bold uppercase tracking-wider rounded-md ring-1 ring-slate-200">Bulan Ini</span>
                </div>
                <div>
                    <div class="flex items-center gap-3">
                        <h3 class="text-[34px] sm:text-[38px] font-black text-slate-900 tracking-tight leading-none">{{ number_format($stats['diukur'], 0, ',', '.') }}</h3>
                        <span class="bg-emerald-50 text-emerald-600 text-[11px] font-bold px-2 py-0.5 rounded-md mt-1 ring-1 ring-emerald-200/60 flex items-center gap-0.5">
                            <i class="ph-bold ph-trend-up"></i> 12%
                        </span>
                    </div>
                    <p class="text-[14px] font-bold text-slate-500 mt-2 uppercase tracking-wide">Balita Diukur</p>
                </div>
            </div>

            <!-- 3. Antrean Validasi -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[24px] p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] ring-1 ring-slate-100/80 flex flex-col justify-between hover:-translate-y-1.5 hover:shadow-[0_12px_30px_rgb(0,0,0,0.06)] transition-all duration-300 group border-b-4 border-rose-500">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 rounded-[16px] bg-gradient-to-br from-rose-50 to-rose-100/50 ring-1 ring-rose-200/50 flex items-center justify-center text-rose-600 shadow-sm group-hover:scale-110 group-hover:-rotate-3 transition-transform duration-300">
                        <i class="ph-fill ph-hourglass-high text-[24px]"></i>
                    </div>
                    <span class="px-2.5 py-1 bg-rose-50 text-rose-600 text-[10px] font-bold uppercase tracking-wider rounded-md ring-1 ring-rose-200/50">Tindakan</span>
                </div>
                <div>
                    <h3 class="text-[34px] sm:text-[38px] font-black text-slate-900 tracking-tight leading-none">{{ number_format($stats['pending'], 0, ',', '.') }}</h3>
                    <p class="text-[14px] font-bold text-slate-500 mt-2 uppercase tracking-wide">Antrean Validasi</p>
                </div>
            </div>

            <!-- 4. Data Terverifikasi -->
            <div class="bg-white/80 backdrop-blur-xl rounded-[24px] p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] ring-1 ring-slate-100/80 flex flex-col justify-between hover:-translate-y-1.5 hover:shadow-[0_12px_30px_rgb(0,0,0,0.06)] transition-all duration-300 group border-b-4 border-sky-500">
                <div class="flex items-center justify-between mb-6">
                    <div class="w-12 h-12 rounded-[16px] bg-gradient-to-br from-sky-50 to-sky-100/50 ring-1 ring-sky-200/50 flex items-center justify-center text-sky-600 shadow-sm group-hover:scale-110 group-hover:rotate-3 transition-transform duration-300">
                        <i class="ph-fill ph-seal-check text-[24px]"></i>
                    </div>
                    <span class="px-2.5 py-1 bg-slate-50 text-slate-500 text-[10px] font-bold uppercase tracking-wider rounded-md ring-1 ring-slate-200">Bulan Ini</span>
                </div>
                <div>
                    <div class="flex items-baseline gap-1">
                        <h3 class="text-[34px] sm:text-[38px] font-black text-slate-900 tracking-tight leading-none">{{ number_format($stats['valid'], 0, ',', '.') }}</h3>
                        <span class="text-[18px] font-bold text-slate-400">/ {{ number_format($stats['diukur'], 0, ',', '.') }}</span>
                    </div>
                    <p class="text-[14px] font-bold text-slate-500 mt-2 uppercase tracking-wide">Terverifikasi</p>
                </div>
            </div>

        </div>

        <!-- ==================== SPLIT: AKTIVITAS & DISTRIBUSI ==================== -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 lg:gap-8">
            
            <!-- Aktivitas Posyandu Terbaru (Col 7) -->
            <div class="xl:col-span-7 flex flex-col">
                <div class="bg-white rounded-[32px] shadow-[0_8px_40px_rgb(0,0,0,0.04)] ring-1 ring-slate-100/80 flex flex-col h-full overflow-hidden">
                    
                    <!-- Card Header -->
                    <div class="flex items-center justify-between p-6 sm:px-8 sm:pt-8 sm:pb-5 border-b border-slate-50 bg-gradient-to-b from-slate-50/50 to-white">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-[16px] bg-gradient-to-br from-teal-50 to-teal-100/50 text-teal-600 flex items-center justify-center ring-1 ring-teal-200/50 shadow-sm">
                                <i class="ph-bold ph-activity text-[24px]"></i>
                            </div>
                            <h2 class="text-[20px] sm:text-[22px] font-black bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">Aktivitas Terbaru</h2>
                        </div>
                        <a href="{{ route('puskesmas.balita') }}" class="text-[13px] font-bold text-teal-700 hover:text-white flex items-center gap-1.5 group bg-teal-50 hover:bg-teal-600 px-4 py-2.5 rounded-[14px] transition-all duration-300 ring-1 ring-teal-200/60 hover:shadow-[0_4px_15px_rgba(13,148,136,0.3)]">
                            Lihat Semua <i class="ph-bold ph-arrow-right group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>

                    <!-- Card Body / Card-Table -->
                    <div class="flex-1 overflow-y-auto w-full rounded-b-[32px] bg-slate-50/40 p-4 sm:p-5 relative">
                        
                        <!-- Header Row (Grid) -->
                        <div class="hidden sm:grid grid-cols-12 gap-4 px-4 mb-4 text-[10px] font-extrabold text-slate-400 uppercase tracking-[0.2em]">
                            <div class="col-span-5 pl-1">Balita & Posyandu</div>
                            <div class="col-span-3">Pengukuran</div>
                            <div class="col-span-2 text-center">Status</div>
                            <div class="col-span-2 text-right pr-1">Waktu Masuk</div>
                        </div>

                        <!-- Rows -->
                        <div class="flex flex-col gap-3.5 relative z-10">
                            @forelse($recentActivities as $activity)
                            @php
                                // Base Status Gizi Style
                                $gizi = strtoupper($activity->status_gizi ?? 'gizi baik');
                                $giziStyle = 'bg-emerald-50 text-emerald-600 ring-emerald-200/50 shadow-[0_2px_10px_rgba(16,185,129,0.1)]';
                                if(str_contains(strtolower($gizi), 'buruk') || str_contains(strtolower($gizi), 'stunting')) $giziStyle = 'bg-rose-50 text-rose-600 ring-rose-200/50 shadow-[0_2px_10px_rgba(244,63,94,0.1)]';
                                elseif(str_contains(strtolower($gizi), 'kurang') || str_contains(strtolower($gizi), 'risiko')) $giziStyle = 'bg-amber-50 text-amber-600 ring-amber-200/50 shadow-[0_2px_10px_rgba(245,158,11,0.1)]';
                                elseif(str_contains(strtolower($gizi), 'lebih') || str_contains(strtolower($gizi), 'obesitas')) $giziStyle = 'bg-sky-50 text-sky-600 ring-sky-200/50 shadow-[0_2px_10px_rgba(14,165,233,0.1)]';

                                // Validasi Style
                                $val = strtolower($activity->status_validasi ?? 'pending');
                                $valBorder = 'border-amber-400';
                                if ($val === 'valid') {
                                    $valIcon = 'ph-check-circle text-emerald-500';
                                    $valText = 'Valid';
                                    $valBorder = 'border-emerald-400';
                                } elseif ($val === 'revisi' || $val === 'ditolak') {
                                    $valIcon = 'ph-warning-circle text-rose-500';
                                    $valText = 'Intervensi';
                                    $valBorder = 'border-rose-400';
                                } else {
                                    $valIcon = 'ph-clock text-amber-500';
                                    $valText = 'Pending';
                                    $valBorder = 'border-amber-400';
                                }

                                $initials = substr($activity->balita->nama ?? 'B', 0, 2);
                            @endphp
                            
                            <!-- Single Row Card with Left Status Border -->
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-y-4 sm:gap-x-4 items-center bg-white py-4 px-5 rounded-[20px] ring-1 ring-slate-100 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_10px_40px_rgba(0,0,0,0.08)] hover:ring-slate-200 hover:-translate-y-1 transition-all duration-300 group border-l-[5px] {{ $valBorder }}">
                                
                                <!-- Col 1: Balita -->
                                <div class="sm:col-span-5 flex items-center gap-4 pl-1">
                                    <div class="relative shrink-0">
                                        <div class="w-12 h-12 rounded-[14px] bg-gradient-to-tr from-slate-50 to-slate-100 text-slate-700 flex items-center justify-center font-black text-[15px] uppercase shadow-sm ring-1 ring-slate-200/80 group-hover:scale-105 group-hover:text-teal-600 group-hover:ring-teal-200 transition-all duration-300">
                                            {{ $initials }}
                                        </div>
                                        <!-- Validation Indicator Dot -->
                                        <div class="absolute -bottom-1 -right-1 w-5 h-5 rounded-full bg-white flex items-center justify-center shadow-sm">
                                            <div class="w-3 h-3 rounded-full {{ str_replace('text-', 'bg-', $valIcon) }}"></div>
                                        </div>
                                    </div>
                                    <div class="flex flex-col min-w-0">
                                        <h4 class="text-[15px] font-bold text-slate-900 truncate group-hover:text-teal-700 transition-colors">{{ $activity->balita->nama ?? 'Budi Santoso' }}</h4>
                                        <div class="flex items-center gap-1.5 text-[12px] font-medium text-slate-500 mt-0.5 truncate">
                                            <i class="ph-fill ph-map-pin text-slate-400"></i>
                                            <span class="truncate">{{ $activity->balita->posyandu->nama ?? 'Posyandu' }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Col 2: Pengukuran (Premium Pills) -->
                                <div class="sm:col-span-3 flex items-center gap-2">
                                    <div class="flex flex-col gap-2 w-full max-w-[140px]">
                                        <div class="flex items-center justify-between bg-gradient-to-r from-blue-50/80 to-transparent rounded-lg px-2.5 py-1 ring-1 ring-blue-100/50">
                                            <span class="text-[10px] font-extrabold text-blue-400/80 uppercase tracking-wider">BB</span>
                                            <div class="text-blue-700 text-[13px] font-black flex items-baseline gap-1">
                                                {{ $activity->berat_badan ?? '-' }} <span class="text-[10px] text-blue-500/70 font-bold">kg</span>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between bg-gradient-to-r from-purple-50/80 to-transparent rounded-lg px-2.5 py-1 ring-1 ring-purple-100/50">
                                            <span class="text-[10px] font-extrabold text-purple-400/80 uppercase tracking-wider">TB</span>
                                            <div class="text-purple-700 text-[13px] font-black flex items-baseline gap-1">
                                                {{ $activity->tinggi_badan ?? '-' }} <span class="text-[10px] text-purple-500/70 font-bold">cm</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Col 3: Status Gizi -->
                                <div class="sm:col-span-2 flex items-center justify-start sm:justify-center">
                                    <span class="inline-flex items-center px-3.5 py-2 rounded-xl text-[10px] font-black uppercase tracking-[0.1em] ring-1 inset-ring {{ $giziStyle }}">
                                        {{ $gizi }}
                                    </span>
                                </div>

                                <!-- Col 4: Waktu & Validasi -->
                                <div class="sm:col-span-2 flex flex-col items-start sm:items-end justify-center gap-2 pr-1">
                                    <span class="text-[12px] font-extrabold text-slate-400">{{ $activity->created_at->diffForHumans() }}</span>
                                    <span class="flex items-center gap-1.5 text-[11px] font-bold text-slate-600 bg-white shadow-sm px-2.5 py-1 rounded-lg ring-1 ring-slate-100/80">
                                        <i class="ph-fill {{ $valIcon }} text-[14px]"></i> {{ $valText }}
                                    </span>
                                </div>

                            </div>
                            @empty
                            <div class="p-16 text-center bg-white rounded-[24px] ring-1 ring-slate-100 flex flex-col items-center justify-center relative overflow-hidden">
                                <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-slate-50/50 via-white to-white pointer-events-none"></div>
                                <div class="relative z-10 flex flex-col items-center">
                                    <div class="w-20 h-20 rounded-full bg-slate-50/80 text-slate-300 flex items-center justify-center mb-5 ring-1 ring-slate-100 shadow-inner">
                                        <i class="ph-fill ph-folder-open text-[40px]"></i>
                                    </div>
                                    <p class="text-[16px] font-bold text-slate-700">Belum ada aktivitas terbaru</p>
                                    <p class="text-[14px] font-medium text-slate-400 mt-1 max-w-[250px] mx-auto">Data pengukuran dari posyandu akan langsung terhubung ke sini.</p>
                                </div>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Distribusi Status Gizi (Col 5) -->
            <div class="xl:col-span-5 flex flex-col">
                <div class="bg-white rounded-[32px] shadow-[0_8px_40px_rgb(0,0,0,0.04)] ring-1 ring-slate-100/80 flex flex-col h-full overflow-hidden">
                    
                    <!-- Card Header -->
                    <div class="flex items-center p-6 sm:px-8 sm:pt-8 sm:pb-5 border-b border-slate-50 bg-gradient-to-b from-slate-50/50 to-white shrink-0">
                        <div class="flex items-center gap-3.5">
                            <div class="w-12 h-12 rounded-[16px] bg-gradient-to-br from-orange-50 to-orange-100/50 text-orange-600 flex items-center justify-center ring-1 ring-orange-200/50 shadow-sm">
                                <i class="ph-bold ph-chart-pie-slice text-[24px]"></i>
                            </div>
                            <h2 class="text-[20px] sm:text-[22px] font-black bg-gradient-to-r from-slate-900 to-slate-700 bg-clip-text text-transparent">Distribusi Gizi</h2>
                        </div>
                    </div>
                    
                    <!-- Card Body -->
                    <div class="flex flex-col items-center justify-center flex-1 p-6 sm:p-8 relative">
                        <!-- Abstract Background Decoration -->
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-slate-50 rounded-full blur-[60px] pointer-events-none opacity-50"></div>
                        
                        <!-- Donut Chart UI component -->
                        <div class="relative w-48 h-48 sm:w-56 sm:h-56 mb-10 shrink-0 flex items-center justify-center group/chart">
                            <svg viewBox="0 0 36 36" class="w-full h-full transform -rotate-90 drop-shadow-md group-hover/chart:scale-105 transition-transform duration-500">
                                <!-- Background ring -->
                                <path class="text-slate-100/50" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="2.5"></path>
                                
                                <!-- Normal -->
                                <path class="text-[#10B981]" stroke-linecap="round" stroke-dasharray="{{ max(0, $p_normal) }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3.5"></path>
                                <!-- Perhatian -->
                                <path class="text-[#F59E0B]" stroke-linecap="round" stroke-dasharray="{{ max(0, $p_perhatian) }}, 100" stroke-dashoffset="-{{ $p_normal }}" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3.5"></path>
                                <!-- Berisiko -->
                                <path class="text-[#EF4444]" stroke-linecap="round" stroke-dasharray="{{ max(0, $p_berisiko) }}, 100" stroke-dashoffset="-{{ $p_normal + $p_perhatian }}" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" fill="none" stroke="currentColor" stroke-width="3.5"></path>
                            </svg>
                            
                            <!-- Center Data -->
                            <div class="absolute flex flex-col items-center justify-center bg-white w-[135px] h-[135px] sm:w-[155px] sm:h-[155px] rounded-full shadow-[0_8px_30px_rgba(0,0,0,0.08)] inset-0 m-auto z-10 ring-1 ring-slate-100/50">
                                <span class="text-[40px] sm:text-[46px] font-black bg-gradient-to-b from-slate-900 to-slate-700 bg-clip-text text-transparent leading-none tracking-tight">{{ $distribution['total_diukur'] }}</span>
                                <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-[0.2em] mt-2">Total Balita</span>
                            </div>
                        </div>

                        <!-- Legend Items -->
                        <div class="w-full flex flex-col gap-3.5 relative z-10 px-2">
                            <!-- Normal -->
                            <div class="flex items-center justify-between p-4 rounded-[20px] bg-gradient-to-r from-emerald-50/50 to-transparent hover:from-emerald-50 hover:to-white transition-all ring-1 ring-emerald-100/50 hover:shadow-[0_4px_20px_rgba(16,185,129,0.05)] cursor-default">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-[14px] bg-emerald-100/50 ring-1 ring-emerald-200/60 flex items-center justify-center shrink-0 text-emerald-600 shadow-sm">
                                        <i class="ph-fill ph-smiley text-[22px]"></i>
                                    </div>
                                    <span class="text-[16px] font-extrabold text-slate-800">Gizi Baik</span>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-[20px] font-black text-emerald-600 leading-none">{{ $distribution['normal']['percentage'] }}%</span>
                                    <span class="text-[13px] font-bold text-slate-400 mt-1.5">{{ $distribution['normal']['count'] }} anak</span>
                                </div>
                            </div>
                            
                            <!-- Perhatian -->
                            <div class="flex items-center justify-between p-4 rounded-[20px] bg-gradient-to-r from-amber-50/50 to-transparent hover:from-amber-50 hover:to-white transition-all ring-1 ring-amber-100/50 hover:shadow-[0_4px_20px_rgba(245,158,11,0.05)] cursor-default">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-[14px] bg-amber-100/50 ring-1 ring-amber-200/60 flex items-center justify-center shrink-0 text-amber-600 shadow-sm">
                                        <i class="ph-fill ph-warning-circle text-[22px]"></i>
                                    </div>
                                    <span class="text-[16px] font-extrabold text-slate-800">Perhatian</span>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-[20px] font-black text-amber-600 leading-none">{{ $distribution['perlu_perhatian']['percentage'] }}%</span>
                                    <span class="text-[13px] font-bold text-slate-400 mt-1.5">{{ $distribution['perlu_perhatian']['count'] }} anak</span>
                                </div>
                            </div>
                            
                            <!-- Berisiko -->
                            <div class="flex items-center justify-between p-4 rounded-[20px] bg-gradient-to-r from-rose-50/50 to-transparent hover:from-rose-50 hover:to-white transition-all ring-1 ring-rose-100/50 hover:shadow-[0_4px_20px_rgba(244,63,94,0.05)] cursor-default">
                                <div class="flex items-center gap-4">
                                    <div class="w-11 h-11 rounded-[14px] bg-rose-100/50 ring-1 ring-rose-200/60 flex items-center justify-center shrink-0 text-rose-600 shadow-sm">
                                        <i class="ph-fill ph-shield-warning text-[22px]"></i>
                                    </div>
                                    <span class="text-[16px] font-extrabold text-slate-800">Berisiko</span>
                                </div>
                                <div class="flex flex-col items-end">
                                    <span class="text-[20px] font-black text-rose-600 leading-none">{{ $distribution['berisiko']['percentage'] }}%</span>
                                    <span class="text-[13px] font-bold text-slate-400 mt-1.5">{{ $distribution['berisiko']['count'] }} anak</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>

        </div>

        <!-- ==================== MANAJEMEN POSYANDU ==================== -->
        <div class="mt-6 flex flex-col gap-5">
            <div class="flex items-center px-1">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center ring-1 ring-indigo-100">
                        <i class="ph-bold ph-buildings text-[18px]"></i>
                    </div>
                    <h2 class="text-xl font-extrabold text-slate-900">Manajemen Fasilitas</h2>
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Kelola Posyandu -->
                <a href="{{ route('puskesmas.posyandu') }}" class="bg-white rounded-[24px] p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] ring-1 ring-slate-100 flex items-center gap-5 hover:-translate-y-1.5 hover:shadow-[0_15px_40px_rgb(0,0,0,0.06)] transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center text-slate-600 ring-1 ring-slate-200 group-hover:from-teal-600 group-hover:to-teal-500 group-hover:text-white group-hover:ring-teal-600 transition-all shadow-sm">
                        <i class="ph-fill ph-house-line text-[28px] group-hover:scale-110 transition-transform"></i>
                    </div>
                    <div>
                        <h3 class="text-[17px] font-bold text-slate-900 group-hover:text-teal-700 transition-colors">Kelola Posyandu</h3>
                        <p class="text-[14px] font-medium text-slate-500 mt-0.5">12 Posyandu terdaftar</p>
                    </div>
                </a>

                <!-- Kelola Kader -->
                <a href="{{ route('puskesmas.posyandu') }}" class="bg-white rounded-[24px] p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] ring-1 ring-slate-100 flex items-center gap-5 hover:-translate-y-1.5 hover:shadow-[0_15px_40px_rgb(0,0,0,0.06)] transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center text-slate-600 ring-1 ring-slate-200 group-hover:from-teal-600 group-hover:to-teal-500 group-hover:text-white group-hover:ring-teal-600 transition-all shadow-sm">
                        <i class="ph-fill ph-users-three text-[28px] group-hover:scale-110 transition-transform"></i>
                    </div>
                    <div>
                        <h3 class="text-[17px] font-bold text-slate-900 group-hover:text-teal-700 transition-colors">Kelola Kader</h3>
                        <p class="text-[14px] font-medium text-slate-500 mt-0.5">48 Kader aktif</p>
                    </div>
                </a>

                <!-- Jadwal Pengukuran -->
                <a href="{{ route('puskesmas.laporan') }}" class="bg-white rounded-[24px] p-6 shadow-[0_4px_20px_rgb(0,0,0,0.03)] ring-1 ring-slate-100 flex items-center gap-5 hover:-translate-y-1.5 hover:shadow-[0_15px_40px_rgb(0,0,0,0.06)] transition-all duration-300 group">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-slate-50 to-slate-100 flex items-center justify-center text-slate-600 ring-1 ring-slate-200 group-hover:from-teal-600 group-hover:to-teal-500 group-hover:text-white group-hover:ring-teal-600 transition-all shadow-sm">
                        <i class="ph-fill ph-calendar-check text-[28px] group-hover:scale-110 transition-transform"></i>
                    </div>
                    <div>
                        <h3 class="text-[17px] font-bold text-slate-900 group-hover:text-teal-700 transition-colors">Jadwal Pengukuran</h3>
                        <p class="text-[14px] font-medium text-slate-500 mt-0.5">Atur jadwal bulanan</p>
                    </div>
                </a>
            </div>
        </div>

    </div>
</div>

<!-- Simple Footer -->
<div class="border-t border-slate-200 mt-16 py-8 flex flex-col md:flex-row items-center justify-between gap-4 text-[14px] font-medium text-slate-500 w-full">
    <p>&copy; 2024 NutriGen Puskesmas Portal. Kementerian Kesehatan RI.</p>
    <div class="flex items-center gap-6">
        <a href="#" class="hover:text-slate-900 transition-colors">Panduan Penggunaan</a>
        <a href="#" class="hover:text-slate-900 transition-colors">Pusat Bantuan</a>
        <a href="#" class="hover:text-slate-900 transition-colors">Privasi Data</a>
    </div>
</div>

@endsection
