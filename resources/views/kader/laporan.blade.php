@extends('layouts.app')

@section('page-title', 'Pusat Laporan Posyandu')

@section('content')
<div class="flex flex-col w-full bg-[#f8fafc] min-h-screen relative mx-auto pb-28 lg:pb-16 font-sans">
    
    <!-- 1. Header Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-6 w-full">
        <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
            
            <!-- Title & Description -->
            <div class="max-w-2xl relative z-10">
                <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-2">
                    <h1 class="text-2xl sm:text-[28px] font-bold text-slate-900 tracking-tight">Pusat Laporan Posyandu</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-600 uppercase tracking-widest mt-1 sm:mt-0">
                        INTEGRATED
                    </span>
                </div>
                <p class="text-[14px] text-slate-500 font-normal leading-relaxed max-w-xl">
                    Tinjau metrik penimbangan bulanan, pantau status perkembangan balita, dan ekspor laporan resmi untuk arsip desa dan puskesmas.
                </p>
            </div>

            <!-- Filter Controls -->
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 relative z-10 w-full lg:w-auto mt-4 lg:mt-0">
                <!-- Location Filter -->
                <div class="bg-white border border-slate-200/70 hover:border-slate-300 rounded-2xl px-4 py-3 flex flex-col w-full sm:w-auto min-w-[220px] shadow-[0_2px_10px_rgba(0,0,0,0.02)] transition-all cursor-default">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">LOKASI POSYANDU</span>
                    <div class="flex items-center gap-2.5 text-slate-800 font-medium text-[14px]">
                        <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <span class="truncate">{{ $posyanduAktif ?? 'Posyandu Bunga Tanjung VII' }}</span>
                    </div>
                </div>
                
                <!-- Period Filter (Interactive) -->
                <style>
                    .periode-picker-overlay::-webkit-calendar-picker-indicator {
                        position: absolute;
                        top: 0; left: 0; right: 0; bottom: 0;
                        width: 100%; height: 100%;
                        opacity: 0;
                        cursor: pointer;
                    }
                </style>
                <form id="form-laporan-filter" action="{{ route('laporan.index') }}" method="GET" class="relative group w-full sm:w-auto">
                    <div class="bg-white border border-slate-200/70 group-hover:border-[#086a7c]/40 rounded-2xl px-4 py-3 flex flex-col w-full sm:w-auto min-w-[180px] shadow-[0_2px_10px_rgba(0,0,0,0.02)] group-hover:shadow-md transition-all cursor-pointer">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">PERIODE</span>
                        <div class="flex items-center gap-2.5 text-slate-800 font-medium text-[14px]">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span>{{ $periode ?? 'Agustus 2026' }}</span>
                            <svg class="w-4 h-4 ml-auto text-slate-400 group-hover:text-[#086a7c] transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    <input type="month" name="periode" value="{{ $periodeValue }}" onchange="this.form.submit()" class="periode-picker-overlay absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" title="Ubah Periode">
                </form>
            </div>

        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full flex flex-col gap-8">

        @if(isset($dataKosong) && $dataKosong)
            <!-- Empty State -->
            <div class="bg-white rounded-3xl p-14 border border-slate-200 shadow-sm flex flex-col items-center justify-center text-center max-w-3xl mx-auto w-full my-8 relative overflow-hidden">
                <div class="absolute inset-0 pointer-events-none">
                    <div class="absolute left-10 top-10 w-24 h-24 bg-slate-50 rounded-full blur-xl"></div>
                    <div class="absolute right-10 bottom-10 w-32 h-32 bg-sky-50/50 rounded-full blur-2xl"></div>
                </div>
                <div class="relative z-10 w-16 h-16 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-400 mb-5">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"></path></svg>
                </div>
                <h2 class="relative z-10 text-xl font-bold text-slate-900">Belum Ada Data Penimbangan</h2>
                <p class="relative z-10 text-[14px] text-slate-500 font-normal max-w-md mt-2 mb-6">Tidak ada riwayat pengukuran balita yang tercatat pada periode ini. Silakan input penimbangan balita terlebih dahulu.</p>
                <a href="{{ route('balita.index') }}" class="relative z-10 h-10 px-6 bg-[#086a7c] hover:bg-[#065b6b] text-white rounded-xl font-semibold text-[14px] shadow-sm flex items-center gap-2">
                    Input Pengukuran Balita
                </a>
            </div>
        @else

            <!-- 2. Metrics Grid Section -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
                
                <!-- Main Progress Card -->
                <div class="group lg:col-span-4 bg-white hover:bg-slate-50/30 rounded-3xl p-6 sm:p-7 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] transition-all duration-300 flex flex-col justify-between relative overflow-hidden cursor-default">
                    
                    <!-- Decorative Background for Main Card -->
                    <div class="absolute inset-0 pointer-events-none overflow-hidden">
                        <!-- Abstract soft blurs -->
                        <div class="absolute -right-16 -top-16 w-64 h-64 bg-slate-100/60 rounded-full blur-3xl transition-transform duration-700 group-hover:scale-110"></div>
                        <div class="absolute right-10 -bottom-10 w-40 h-40 bg-[#086a7c]/[0.05] rounded-full blur-2xl transition-transform duration-700 group-hover:-translate-y-4"></div>
                        <!-- Little Beads (Manik-manik) -->
                        <div class="absolute right-12 top-8 w-4 h-4 border-[3px] border-slate-200/80 rounded-full"></div>
                        <div class="absolute right-6 top-20 w-2 h-2 bg-slate-300/80 rounded-full"></div>
                        <div class="absolute right-24 top-24 w-2.5 h-2.5 bg-[#086a7c]/20 rounded-full"></div>
                    </div>

                    <div class="relative z-10">
                        <h3 class="text-[17px] sm:text-[18px] font-bold text-slate-900 tracking-tight">Rekapitulasi Penimbangan</h3>
                        <p class="text-[14px] font-medium text-slate-500 mt-1">{{ $periode ?? 'Agustus 2026' }}</p>
                    </div>
                    
                    <div class="relative z-10 mt-8">
                        <div class="flex items-end justify-between mb-4">
                            <div class="flex items-baseline">
                                <span class="text-[48px] sm:text-[56px] font-extrabold text-[#086a7c] leading-none tracking-tight">{{ $persentase ?? 0 }}</span>
                                <span class="text-2xl sm:text-3xl font-bold text-[#086a7c] ml-1">%</span>
                            </div>
                            <span class="text-[14px] font-semibold text-slate-700 mb-1">{{ $sudahDiukur ?? 0 }} / {{ $totalBalita ?? 0 }} Balita</span>
                        </div>
                        <div class="h-3.5 w-full bg-slate-100/80 rounded-full overflow-hidden shadow-inner">
                            <div class="h-full bg-gradient-to-r from-[#086a7c] to-[#0ba3c0] rounded-full relative overflow-hidden shadow-[inset_0_2px_4px_rgba(255,255,255,0.2)]" style="width: {{ $persentase ?? 0 }}%">
                                <!-- Shimmer effect -->
                                <div class="absolute top-0 left-0 right-0 bottom-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full animate-[shimmer_2s_infinite]"></div>
                            </div>
                        </div>
                        <p class="text-right text-[12px] font-medium text-[#086a7c] mt-3">Sesuai Target Kunjungan</p>
                    </div>
                </div>

                <!-- 4 Sub Metric Cards -->
                <div class="lg:col-span-8 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    
                    <!-- Terukur -->
                    <div class="group bg-white hover:bg-slate-50/30 rounded-3xl p-5 sm:p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden flex flex-col justify-between cursor-default">
                        
                        <!-- Decorative Beads & Blobs -->
                        <div class="absolute inset-0 pointer-events-none">
                            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-gradient-to-br from-emerald-50 to-emerald-100/50 rounded-full opacity-80 group-hover:scale-[1.3] transition-transform duration-700 ease-out"></div>
                            <div class="absolute right-6 bottom-6 w-14 h-14 border-[3px] border-emerald-200/50 rounded-full opacity-0 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500 delay-75"></div>
                            <div class="absolute right-4 top-8 w-2 h-2 bg-emerald-300/60 rounded-full"></div>
                            <div class="absolute right-10 top-12 w-3 h-3 border-2 border-emerald-300/80 rounded-full"></div>
                        </div>

                        <div class="absolute left-0 top-0 bottom-0 w-[6px] bg-gradient-to-b from-emerald-400 to-emerald-500 z-10"></div>
                        <div class="relative z-10 flex justify-between items-start mb-5 sm:mb-6">
                            <div class="w-10 h-10 rounded-full bg-emerald-50 group-hover:bg-emerald-100 group-hover:scale-110 transition-all duration-300 flex items-center justify-center text-emerald-500 shadow-sm shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-[11px] font-extrabold text-slate-600 uppercase tracking-widest group-hover:text-slate-800 transition-colors">TOTAL</span>
                        </div>
                        <div class="relative z-10">
                            <h4 class="text-[40px] sm:text-[44px] font-extrabold text-slate-900 leading-none tracking-tight group-hover:text-emerald-700 transition-colors">{{ $sudahDiukur ?? 0 }}</h4>
                            <p class="text-[15px] font-semibold text-slate-600 mt-2">Terukur</p>
                        </div>
                    </div>

                    <!-- Belum Hadir -->
                    <div class="group bg-white hover:bg-slate-50/30 rounded-3xl p-5 sm:p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden flex flex-col justify-between cursor-default">
                        
                        <!-- Decorative Beads & Blobs -->
                        <div class="absolute inset-0 pointer-events-none">
                            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-gradient-to-br from-amber-50 to-amber-100/50 rounded-full opacity-80 group-hover:scale-[1.3] transition-transform duration-700 ease-out"></div>
                            <div class="absolute right-6 bottom-6 w-14 h-14 border-[3px] border-amber-200/50 rounded-full opacity-0 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500 delay-75"></div>
                            <div class="absolute right-4 top-8 w-2 h-2 bg-amber-300/60 rounded-full"></div>
                            <div class="absolute right-10 top-12 w-3 h-3 border-2 border-amber-300/80 rounded-full"></div>
                        </div>

                        <div class="absolute left-0 top-0 bottom-0 w-[6px] bg-gradient-to-b from-amber-400 to-amber-500 z-10"></div>
                        <div class="relative z-10 flex justify-between items-start mb-5 sm:mb-6">
                            <div class="w-10 h-10 rounded-full bg-amber-50 group-hover:bg-amber-100 group-hover:scale-110 transition-all duration-300 flex items-center justify-center text-amber-500 shadow-sm shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                            </div>
                            <span class="text-[11px] font-extrabold text-slate-600 uppercase tracking-widest group-hover:text-slate-800 transition-colors">SISA</span>
                        </div>
                        <div class="relative z-10">
                            <h4 class="text-[40px] sm:text-[44px] font-extrabold text-slate-900 leading-none tracking-tight group-hover:text-amber-600 transition-colors">{{ $belumDiukur ?? 0 }}</h4>
                            <p class="text-[15px] font-semibold text-slate-600 mt-2">Belum Hadir</p>
                        </div>
                    </div>

                    <!-- Pantauan Gizi -->
                    <div class="group bg-white hover:bg-slate-50/30 rounded-3xl p-5 sm:p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden flex flex-col justify-between cursor-default">
                        
                        <!-- Decorative Beads & Blobs -->
                        <div class="absolute inset-0 pointer-events-none">
                            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-gradient-to-br from-sky-50 to-sky-100/50 rounded-full opacity-80 group-hover:scale-[1.3] transition-transform duration-700 ease-out"></div>
                            <div class="absolute right-6 bottom-6 w-14 h-14 border-[3px] border-sky-200/50 rounded-full opacity-0 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500 delay-75"></div>
                            <div class="absolute right-4 top-8 w-2 h-2 bg-sky-300/60 rounded-full"></div>
                            <div class="absolute right-10 top-12 w-3 h-3 border-2 border-sky-300/80 rounded-full"></div>
                        </div>

                        <div class="absolute left-0 top-0 bottom-0 w-[6px] bg-gradient-to-b from-sky-400 to-sky-500 z-10"></div>
                        <div class="relative z-10 flex justify-between items-start mb-5 sm:mb-6">
                            <div class="w-10 h-10 rounded-full bg-sky-50 group-hover:bg-sky-100 group-hover:scale-110 transition-all duration-300 flex items-center justify-center text-sky-500 shadow-sm shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                            </div>
                            <span class="text-[11px] font-extrabold text-slate-600 uppercase tracking-widest group-hover:text-slate-800 transition-colors">TINDAKAN</span>
                        </div>
                        <div class="relative z-10">
                            <h4 class="text-[40px] sm:text-[44px] font-extrabold text-slate-900 leading-none tracking-tight group-hover:text-sky-600 transition-colors">{{ $perluPerhatian ?? 0 }}</h4>
                            <p class="text-[15px] font-semibold text-slate-600 mt-2">Pantauan Gizi</p>
                        </div>
                    </div>

                    <!-- Perlu Konfirmasi -->
                    <div class="group bg-white hover:bg-slate-50/30 rounded-3xl p-5 sm:p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.08)] hover:-translate-y-1 transition-all duration-300 relative overflow-hidden flex flex-col justify-between cursor-default">
                        
                        <!-- Decorative Beads & Blobs -->
                        <div class="absolute inset-0 pointer-events-none">
                            <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-gradient-to-br from-rose-50 to-rose-100/50 rounded-full opacity-80 group-hover:scale-[1.3] transition-transform duration-700 ease-out"></div>
                            <div class="absolute right-6 bottom-6 w-14 h-14 border-[3px] border-rose-200/50 rounded-full opacity-0 group-hover:opacity-100 group-hover:scale-110 transition-all duration-500 delay-75"></div>
                            <div class="absolute right-4 top-8 w-2 h-2 bg-rose-300/60 rounded-full"></div>
                            <div class="absolute right-10 top-12 w-3 h-3 border-2 border-rose-300/80 rounded-full"></div>
                        </div>

                        <div class="absolute left-0 top-0 bottom-0 w-[6px] bg-gradient-to-b from-rose-400 to-rose-500 z-10"></div>
                        <div class="relative z-10 flex justify-between items-start mb-5 sm:mb-6">
                            <div class="w-10 h-10 rounded-full bg-rose-50 group-hover:bg-rose-100 group-hover:scale-110 transition-all duration-300 flex items-center justify-center text-rose-500 shadow-sm shrink-0">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <span class="text-[11px] font-extrabold text-slate-600 uppercase tracking-widest group-hover:text-slate-800 transition-colors">URGENT</span>
                        </div>
                        <div class="relative z-10">
                            <h4 class="text-[40px] sm:text-[44px] font-extrabold text-slate-900 leading-none tracking-tight group-hover:text-rose-600 transition-colors">{{ $berisiko ?? 0 }}</h4>
                            <p class="text-[15px] font-semibold text-slate-600 mt-2">Perlu Konfirmasi</p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- 3. Ekspor & Pelaporan Section -->
            <div class="mt-4">
                <div class="flex items-center gap-2 mb-4">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                    <h2 class="text-[17px] font-bold text-slate-900">Ekspor & Pelaporan</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    
                    <!-- PDF Card -->
                    <div class="group bg-white hover:bg-slate-50/30 rounded-3xl border border-slate-100 shadow-[0_2px_10px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:-translate-y-0.5 transition-all duration-300 p-5 sm:p-6 relative overflow-hidden flex flex-col justify-between min-h-[220px]">
                        <!-- Large Soft Circle Background Shape -->
                        <div class="absolute right-0 top-0 translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-gradient-to-br from-slate-50 to-slate-100/50 rounded-full group-hover:scale-110 transition-transform duration-700"></div>
                        
                        <div class="relative z-10 flex justify-between items-start">
                            <div class="max-w-[75%]">
                                <h3 class="text-[16px] font-bold text-slate-900 tracking-tight">Laporan Resmi Posyandu (PDF)</h3>
                                <p class="text-[13px] text-slate-500 font-normal mt-2 leading-relaxed">
                                    Dokumen lengkap siap cetak untuk diserahkan ke Puskesmas dan Kelurahan.
                                </p>
                                
                                <div class="flex flex-wrap gap-2 mt-5 mb-5">
                                    <span class="text-[11px] font-medium text-slate-600 bg-white border border-slate-200 px-2.5 py-1 rounded-md shadow-sm">Kop Surat</span>
                                    <span class="text-[11px] font-medium text-slate-600 bg-white border border-slate-200 px-2.5 py-1 rounded-md shadow-sm">Tanda Tangan</span>
                                    <span class="text-[11px] font-medium text-slate-600 bg-white border border-slate-200 px-2.5 py-1 rounded-md shadow-sm">A4 Landscape</span>
                                </div>
                            </div>
                            
                            <div class="w-12 h-12 rounded-2xl bg-rose-50 flex items-center justify-center text-rose-500 shrink-0">
                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <rect x="8" y="12" width="8" height="5" rx="1" />
                                    <path d="M10 14.5h4" />
                                </svg>
                            </div>
                        </div>

                        <form action="{{ route('laporan.generate') }}" method="POST" class="relative z-10 w-full mt-auto">
                            @csrf
                            <input type="hidden" name="posyandu_id" value="{{ request('posyandu_id') }}">
                            <input type="hidden" name="periode" value="{{ $periodeValue }}">
                            <button type="submit" class="w-full h-[44px] bg-[#086a7c] hover:bg-[#065b6b] text-white rounded-xl font-semibold text-[14px] shadow-sm hover:shadow transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                <span>Cetak PDF Resmi</span>
                            </button>
                        </form>
                    </div>

                    <!-- Excel Card -->
                    <div class="group bg-white hover:bg-slate-50/30 rounded-3xl border border-slate-100 shadow-[0_2px_10px_rgba(0,0,0,0.04)] hover:shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:-translate-y-0.5 transition-all duration-300 p-5 sm:p-6 relative overflow-hidden flex flex-col justify-between min-h-[220px]">
                        <!-- Large Soft Circle Background Shape -->
                        <div class="absolute right-0 top-0 translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-gradient-to-br from-emerald-50/50 to-emerald-100/30 rounded-full group-hover:scale-110 transition-transform duration-700"></div>
                        
                        <div class="relative z-10 flex justify-between items-start">
                            <div class="max-w-[75%]">
                                <h3 class="text-[16px] font-bold text-slate-900 tracking-tight">Data Tabel Pengukuran (Excel)</h3>
                                <p class="text-[13px] text-slate-500 font-normal mt-2 leading-relaxed">
                                    Spreadsheet mentah untuk analisis data lebih lanjut atau rekapitulasi mandiri.
                                </p>
                                
                                <div class="flex flex-wrap gap-2 mt-5 mb-5">
                                    <span class="text-[11px] font-medium text-slate-600 bg-white border border-slate-200 px-2.5 py-1 rounded-md shadow-sm">16 Kolom Lengkap</span>
                                    <span class="text-[11px] font-medium text-slate-600 bg-white border border-slate-200 px-2.5 py-1 rounded-md shadow-sm">Format Spreadsheet</span>
                                    <span class="text-[11px] font-medium text-slate-600 bg-white border border-slate-200 px-2.5 py-1 rounded-md shadow-sm">Arsip Digital</span>
                                </div>
                            </div>
                            
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-500 shrink-0">
                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M14.5 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7.5L14.5 2z"/>
                                    <polyline points="14 2 14 8 20 8"/>
                                    <path d="M6 13h12" />
                                    <path d="M6 17h12" />
                                    <path d="M10 13v8" />
                                    <path d="M14 13v8" />
                                </svg>
                            </div>
                        </div>

                        <a href="{{ route('laporan.export.excel', ['periode' => $periodeValue]) }}" class="w-full h-[44px] bg-white border-2 border-[#086a7c] text-[#086a7c] hover:bg-slate-50 rounded-xl font-bold text-[14px] shadow-sm hover:shadow transition-all flex items-center justify-center gap-2 relative z-10 mt-auto">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            <span>Export ke Excel (.xls)</span>
                        </a>
                    </div>

                </div>
            </div>

            <!-- 4. Pratinjau Data Penimbangan Section -->
            <div class="mt-8 mb-6 relative z-10">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                        <h2 class="text-[17px] font-bold text-slate-900">Pratinjau Data Penimbangan <span class="font-normal text-slate-500">({{ $periode ?? 'Agustus 2026' }})</span></h2>
                    </div>
                    <a href="{{ route('balita.index') }}" class="text-[14px] font-bold text-[#086a7c] hover:underline flex items-center gap-1">
                        Lihat Semua Data
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>

                @if(isset($previewBalitas) && $previewBalitas->isNotEmpty())
                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                        <div class="overflow-x-auto">
                            <table class="w-full text-left border-collapse">
                                <thead class="bg-slate-50/80 border-b border-slate-200 text-[11px] font-extrabold text-slate-500 uppercase tracking-widest">
                                    <tr>
                                        <th class="py-4 px-5">BALITA & NIK</th>
                                        <th class="py-4 px-4">NAMA IBU</th>
                                        <th class="py-4 px-4">TGL UKUR</th>
                                        <th class="py-4 px-4 text-center">BB (KG)</th>
                                        <th class="py-4 px-4 text-center">TB (CM)</th>
                                        <th class="py-4 px-4 text-center">KMS</th>
                                        <th class="py-4 px-5 text-center">STATUS/DIAGNOSA</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-[14px] font-medium text-slate-700">
                                    @foreach($previewBalitas as $b)
                                        @php
                                            $m = $b->pengukurans->first();
                                            $statusGizi = $m ? $m->status_gizi : '-';
                                            $statusValidasi = $m ? $m->status_validasi : null;
                                            $isApproved = $statusValidasi === 'approved';
                                            $kms = $m ? ($m->status_kenaikan ?? '-') : '-';
                                            
                                            // Mock logic to match screenshot colors if needed, 
                                            // but using actual data logic is better.
                                            $isWarning = false;
                                            if ($m && (strtolower($statusGizi) != 'normal' || str_contains(strtolower($statusGizi), 'kurang'))) {
                                                $isWarning = true;
                                            }
                                            if ($kms === 'T' || str_contains($kms, 'Turun')) {
                                                $isWarning = true;
                                            }
                                        @endphp
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="py-3 px-5">
                                                <div class="flex items-center gap-3">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($b->nama) }}&background=f1f5f9&color=64748b&bold=true&rounded=true" alt="Avatar" class="w-9 h-9 rounded-full object-cover">
                                                    <div class="flex flex-col">
                                                        <span class="font-bold text-slate-900">{{ $b->nama }}</span>
                                                        <span class="text-[12px] text-slate-400 font-medium mt-0.5">{{ $b->nik ?? '-' }}</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="py-3 px-4 text-slate-500">{{ $b->orangTua->nama_ibu ?? '-' }}</td>
                                            <td class="py-3 px-4 text-slate-500">{{ $m ? \Carbon\Carbon::parse($m->tanggal_ukur)->translatedFormat('d M Y') : '-' }}</td>
                                            <td class="py-3 px-4 text-center">
                                                <span class="{{ $isWarning ? 'text-amber-500 font-normal' : 'text-slate-600' }}">
                                                    {{ $m ? number_format((float)$m->berat_badan, 1) : '-' }}
                                                </span>
                                            </td>
                                            <td class="py-3 px-4 text-center text-slate-600">{{ $m ? number_format((float)$m->tinggi_badan, 1) : '-' }}</td>
                                            <td class="py-3 px-4 text-center">
                                                @if($kms === 'N' || str_contains($kms, 'Naik'))
                                                    <svg class="w-4 h-4 mx-auto text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                                @elseif($kms === 'T' || str_contains($kms, 'Tetap'))
                                                    <svg class="w-4 h-4 mx-auto text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                                                @else
                                                    <svg class="w-4 h-4 mx-auto text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path></svg>
                                                @endif
                                            </td>
                                            <td class="py-3 px-5 text-center">
                                                @if($isApproved)
                                                    @if(strtolower($statusGizi) === 'normal')
                                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-bold bg-white text-slate-600 border border-slate-200 shadow-sm">
                                                            Normal
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-bold bg-orange-50 text-orange-600 border border-orange-200 shadow-sm">
                                                            {{ ucfirst($statusGizi) }}
                                                        </span>
                                                    @endif
                                                @else
                                                    @if($isWarning)
                                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-bold bg-orange-50 text-orange-600 border border-orange-200 shadow-sm">
                                                            Pantauan Gizi
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-bold bg-slate-50 text-slate-600 border border-slate-200 shadow-sm">
                                                            Menunggu Validasi
                                                        </span>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>

        @endif
    </div>

    <!-- Footer Space -->
    <div class="mt-auto max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full py-6 flex flex-col sm:flex-row justify-between items-center text-[12px] text-slate-500 font-medium border-t border-slate-200 relative z-10">
        <p>&copy; {{ date('Y') }} NutriGen Digital Healthcare. All rights reserved.</p>
        <div class="flex gap-4 mt-2 sm:mt-0">
            <a href="#" class="hover:text-slate-700">Privacy Policy</a>
            <a href="#" class="hover:text-slate-700">Support</a>
        </div>
    </div>

</div>
@endsection
