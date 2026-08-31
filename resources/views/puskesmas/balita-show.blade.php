@extends('layouts.puskesmas')

@section('page-title', 'Profil Balita')

@section('content')

@php
    $colorMap = [
        'success' => 'emerald',
        'warning' => 'amber',
        'danger'  => 'rose',
    ];
    $colorClass = $colorMap[$status_type] ?? 'slate';

    $statusBadgeClasses = [
        'success' => 'bg-emerald-50 text-emerald-800 border border-emerald-200/80',
        'warning' => 'bg-amber-50 text-amber-800 border border-amber-200/80',
        'danger'  => 'bg-rose-50 text-rose-800 border border-rose-200/80',
    ];
    $badgeClasses = $statusBadgeClasses[$status_type] ?? 'bg-slate-50 text-slate-700 border border-slate-200/80';
    $badgeIcon = match($status_type) {
        'danger' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-rose-600"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2.25m0 2.625h.008v.008H12v-.008zM12 3a9 9 0 100 18 9 9 0 000-18z" /></svg>',
        'warning' => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-amber-600"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2.25m0 2.625h.008v.008H12v-.008zM12 3a9 9 0 100 18 9 9 0 000-18z" /></svg>',
        default => '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-emerald-600"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>'
    };
@endphp

<!-- MAIN CANVAS -->
<div class="bg-slate-50/50 min-h-screen relative w-full pb-[calc(5rem+env(safe-area-inset-bottom))] lg:pb-16 font-sans">
    
    {{-- Framer Motion-like Smooth Reveal Animations --}}
    <style>
        .reveal-init {
            opacity: 0;
            transform: translateY(30px) scale(0.98);
            transition: opacity 0.7s cubic-bezier(0.16, 1, 0.3, 1), transform 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }
        .reveal-active {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                let delay = 0;
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('reveal-active');
                        }, delay);
                        delay += 75; // Stagger effect
                        observer.unobserve(entry.target);
                    }
                });
            }, { rootMargin: '0px 0px -50px 0px', threshold: 0.1 });

            // Apply init class and observe all elements with .motion-card
            document.querySelectorAll('.motion-card').forEach(el => {
                el.classList.add('reveal-init');
                observer.observe(el);
            });
        });
    </script>
    
    <!-- Top Header Actions -->
    <div class="max-w-7xl mx-auto px-4 lg:px-8 pt-6 pb-6 flex items-center justify-between">
        <a href="{{ route('puskesmas.balita') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-800 transition-all font-semibold text-[14px]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
              <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
            Daftar Balita
        </a>
        
    </div>

    <div class="max-w-7xl mx-auto px-4 lg:px-8 space-y-7">
        
        <!-- MASTER CHILD PROFILE & HEALTH INSIGHT CARD -->
        <div class="bg-white rounded-[24px] p-6 lg:p-8 shadow-sm border border-slate-200 relative overflow-hidden motion-card opacity-0">
            
            <!-- Top Identity & Actions Row -->
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6">
                
                <!-- Avatar & Identity Details -->
                <div class="flex items-center gap-4 sm:gap-6 min-w-0">
                    
                    <!-- Clean Avatar -->
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-[24px] sm:rounded-[28px] bg-[#d7f4f2] text-[#006064] flex items-center justify-center shrink-0">
                        <span class="text-[24px] sm:text-[32px] font-black select-none">
                            {{ strtoupper(substr($childName, 0, 1)) }}
                        </span>
                    </div>

                    <!-- Name, Gender & Age -->
                    <div class="flex flex-col min-w-0">
                        <h1 class="text-[24px] sm:text-[28px] font-bold text-slate-900 tracking-tight leading-tight truncate mb-2">{{ $childName }}</h1>
                        
                        <div class="flex items-center gap-3 flex-wrap">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold {{ $gender === 'L' ? 'bg-sky-50 text-sky-700' : 'bg-rose-50 text-rose-600' }}">
                                {{ $gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                            </span>
                            
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 text-slate-600 rounded-full text-[12px] font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-slate-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span>{{ $age }}</span>
                            </span>
                        </div>
                    </div>

                </div>

                </div>

            <!-- Integrated Growth Status & Screening Banner -->
            @php
                $statusBannerBg = $status_type == 'danger' 
                    ? 'bg-rose-50 border-rose-100' 
                    : ($status_type == 'warning' 
                        ? 'bg-amber-50 border-amber-100' 
                        : 'bg-emerald-50 border-emerald-100');
                
                $statusIconColor = $status_type == 'danger' ? 'text-rose-500' : ($status_type == 'warning' ? 'text-amber-500' : 'text-emerald-500');
                $statusTextColor = $status_type == 'danger' ? 'text-rose-600' : ($status_type == 'warning' ? 'text-amber-600' : 'text-emerald-600');
                
                $valBadgeStyle = match($latestMeasure['status_validasi'] ?? '') {
                    'pending' => 'bg-amber-100 text-amber-800 border-amber-200',
                    'approved' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                    'rejected' => 'bg-rose-600 text-white border-rose-600 shadow-sm',
                    default => 'bg-slate-100 text-slate-700 border-slate-200'
                };
            @endphp
            <div class="mt-6 p-4 rounded-[16px] {{ $statusBannerBg }} border flex flex-col sm:flex-row sm:items-center justify-between gap-3 relative z-10">
                <div class="flex items-start sm:items-center gap-2.5 min-w-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 {{ $statusIconColor }} shrink-0 mt-0.5 sm:mt-0">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2.25m0 2.625h.008v.008H12v-.008zM12 3a9 9 0 100 18 9 9 0 000-18z" />
                    </svg>
                    <div class="flex flex-col sm:flex-row sm:items-center gap-1.5 sm:gap-2">
                        <span class="font-bold text-[14px] {{ $statusTextColor }}">Status Gizi: {{ $status }}</span>
                        <span class="hidden sm:inline text-slate-400">•</span>
                        <span class="text-[13px] text-slate-600 font-medium">{{ $latestMeasure['education'] ?? 'Lakukan pengukuran rutin setiap bulan untuk memantau tumbuh kembang.' }}</span>
                    </div>
                </div>

                @if(!empty($latestMeasure['status_validasi']))
                    <div class="shrink-0 flex items-center">
                        <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-[10px] text-[12px] font-bold border {{ $valBadgeStyle }}">
                            @if($latestMeasure['status_validasi'] === 'rejected')
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            @endif
                            <span>{{ $latestMeasure['status_validasi'] === 'approved' ? 'Terverifikasi Puskesmas' : ($latestMeasure['status_validasi'] === 'rejected' ? 'Ditolak Puskesmas' : 'Menunggu Validasi') }}</span>
                        </span>
                    </div>
                @endif
            </div>

            <!-- Key Bio Information Strip -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 relative z-10">
                
                <!-- Ibu -->
                <div class="flex flex-col p-4 rounded-2xl border border-slate-200 bg-white">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-[#f0f9fa] text-[#086a7c] flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                        </div>
                        <div class="flex flex-col overflow-hidden w-full">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">IBU</span>
                            <span class="font-bold text-slate-800 text-[14px] truncate" title="{{ $motherName }}">{{ $motherName }}</span>
                        </div>
                    </div>
                </div>

                <!-- NIK -->
                <div class="flex flex-col p-4 rounded-2xl border border-slate-200 bg-white">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-[#f0f9fa] text-[#086a7c] flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" /></svg>
                        </div>
                        <div class="flex flex-col overflow-hidden w-full relative">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">NIK</span>
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-slate-800 font-mono text-[14px] tracking-wider truncate">{{ $nik }}</span>
                                <button onclick="navigator.clipboard.writeText('{{ $nik }}'); window.NutriAlert.toast('Berhasil!', 'NIK disalin', 'success');" class="text-slate-400 hover:text-slate-700 transition-colors cursor-pointer shrink-0 ml-2" title="Salin NIK">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" /></svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Posyandu -->
                <div class="flex flex-col p-4 rounded-2xl border border-slate-200 bg-white">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-[#f0f9fa] text-[#086a7c] flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                        </div>
                        <div class="flex flex-col overflow-hidden w-full">
                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">POSYANDU</span>
                            <span class="font-bold text-slate-800 text-[14px] truncate" title="{{ $posyanduName }}">{{ $posyanduName }}</span>
                        </div>
                    </div>
                </div>

            </div>

        </div>

    </div>

    <!-- Modern Clean Tab Navigation -->
    <div class="max-w-7xl mx-auto px-4 lg:px-8 mt-7 mb-7 motion-card opacity-0" id="profile-tabs">
        <div class="border-b border-slate-200">
            <nav class="flex space-x-5 sm:space-x-8 overflow-x-auto hide-scrollbar" aria-label="Tabs">
                <button onclick="switchTab('ringkasan')" id="tab-ringkasan" class="border-[#086a7c] text-[#086a7c] border-b-2 font-bold text-[14px] py-3 px-1 inline-flex items-center gap-2 whitespace-nowrap cursor-pointer transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z" /></svg>
                    <span>Ringkasan</span>
                </button>
                <button onclick="switchTab('detail')" id="tab-detail" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 border-b-2 font-medium text-[14px] py-3 px-1 inline-flex items-center gap-2 whitespace-nowrap cursor-pointer transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                    <span>Detail Informasi</span>
                </button>
                <button onclick="switchTab('riwayat')" id="tab-riwayat" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 border-b-2 font-medium text-[14px] py-3 px-1 inline-flex items-center gap-2 whitespace-nowrap cursor-pointer transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    <span>Riwayat Pengukuran</span>
                </button>
                <button onclick="switchTab('grafik')" id="tab-grafik" class="border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300 border-b-2 font-medium text-[14px] py-3 px-1 inline-flex items-center gap-2 whitespace-nowrap cursor-pointer transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A2.25 2.25 0 013 18.75v-5.625zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a2.25 2.25 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a2.25 2.25 0 01-1.125-1.125V4.125z" /></svg>
                    <span>Grafik Pertumbuhan</span>
                </button>
            </nav>
        </div>
    </div>

    <!-- Content Area -->
    <div class="max-w-7xl mx-auto px-4 lg:px-8 pb-10 motion-card opacity-0">
        
        <!-- TAB 1: RINGKASAN (Clean, Focused Growth Metrics) -->
        <div id="content-ringkasan" class="tab-content flex flex-col gap-6">
            
            <div class="bg-white rounded-[24px] p-6 lg:p-8 border border-slate-200 shadow-sm flex flex-col">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-8 border-b border-slate-100 pb-5">
                    <div>
                        <h2 class="text-[20px] font-bold text-slate-900 tracking-tight">Pengukuran Terakhir</h2>
                        <p class="text-[13px] text-slate-500 mt-1 font-medium">Hasil penimbangan dan pengukuran antropometri terkini</p>
                    </div>
                    <div class="flex items-center gap-2">
                        @if($latestMeasure)
                            @if($latestMeasure['status_validasi'] === 'valid')
                                <div class="flex items-center gap-1.5 text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-full border border-emerald-200 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                                    <span class="text-xs font-bold font-mono uppercase">Tervalidasi</span>
                                </div>
                            @elseif($latestMeasure['status_validasi'] === 'ditolak')
                                <div class="flex items-center gap-1.5 text-rose-700 bg-rose-50 px-3 py-1.5 rounded-full border border-rose-200 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/></svg>
                                    <span class="text-xs font-bold font-mono uppercase">Ditolak</span>
                                </div>
                            @else
                                <div class="flex items-center gap-1.5 text-amber-700 bg-amber-50 px-3 py-1.5 rounded-full border border-amber-200 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd"/></svg>
                                    <span class="text-xs font-bold font-mono uppercase">Belum Divalidasi</span>
                                </div>
                            @endif
                        @endif

                        <div class="flex items-center gap-1.5 text-slate-700 bg-white px-3 py-1.5 rounded-full border border-slate-200 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-slate-500"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                            <span class="text-xs font-bold font-mono tracking-widest uppercase">{{ $latestMeasure['date'] ?? ($birthDate ? $birthDate : 'BELUM ADA') }}</span>
                        </div>
                    </div>
                </div>

                <!-- 3 Metrik Utama -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                    
                    <!-- Berat Badan -->
                    <div class="bg-white border border-slate-200 rounded-[20px] p-6 flex flex-col justify-between relative overflow-hidden group">
                        <div class="flex items-start justify-between mb-8">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-[10px] bg-[#086a7c] text-white flex items-center justify-center shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 2.25v19.5m0 0l-3-3m3 3l3-3M6.75 19.5a4.5 4.5 0 01-1.41-8.775 5.25 5.25 0 0110.233-2.33 3 3 0 013.758 3.848A3.748 3.748 0 0118 19.5H6.75z" /></svg>
                                </div>
                                <span class="text-[14px] font-bold text-slate-900">Berat Badan</span>
                            </div>
                            @if(!empty($latestMeasure['weight_trend']) && $latestMeasure['weight_trend'] > 0)
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-[#047857] bg-[#d1fae5] px-2.5 py-1 rounded-full">
                                    +{{ $latestMeasure['weight_trend'] }} kg
                                </span>
                            @elseif(!empty($latestMeasure['weight_trend']) && $latestMeasure['weight_trend'] < 0)
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-rose-700 bg-rose-100 px-2.5 py-1 rounded-full">
                                    {{ $latestMeasure['weight_trend'] }} kg
                                </span>
                            @endif
                        </div>
                        <div class="flex items-baseline gap-1.5 mb-8">
                            <span class="text-[36px] sm:text-[44px] font-black text-slate-900 tracking-tight leading-none">{{ $latestMeasure['weight'] ?? ($birthWeight ?: '-') }}</span>
                            <span class="text-[18px] sm:text-[20px] font-medium text-slate-500">kg</span>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-slate-100/80">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Z-SCORE BB/U</span>
                            <span class="font-bold text-[11px] text-slate-700 bg-slate-100 px-2.5 py-1 rounded-md">{{ isset($latestMeasure['z_score_bbu']) && $latestMeasure['z_score_bbu'] !== null ? $latestMeasure['z_score_bbu'] . ' SD' : ($birthWeight ? 'Awal Lahir' : '-') }}</span>
                        </div>
                    </div>
                    
                    <!-- Tinggi / Panjang Badan -->
                    <div class="bg-[#fffbf5] border border-[#fef0dd] rounded-[20px] p-6 flex flex-col justify-between relative overflow-hidden group">
                        <div class="flex items-start justify-between mb-8">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-[10px] bg-[#f59e0b] text-white flex items-center justify-center shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" /></svg>
                                </div>
                                <span class="text-[14px] font-bold text-slate-900">Tinggi / Panjang</span>
                            </div>
                            @if(!empty($latestMeasure['height_trend']) && $latestMeasure['height_trend'] > 0)
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-[#047857] bg-[#d1fae5] px-2.5 py-1 rounded-full">
                                    +{{ $latestMeasure['height_trend'] }} cm
                                </span>
                            @endif
                        </div>
                        <div class="flex items-baseline gap-1.5 mb-8">
                            <span class="text-[36px] sm:text-[44px] font-black text-slate-900 tracking-tight leading-none">{{ $latestMeasure['height'] ?? ($birthLength ?: '-') }}</span>
                            <span class="text-[18px] sm:text-[20px] font-medium text-slate-500">cm</span>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-amber-200/40">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Z-SCORE TB/U</span>
                            <span class="font-bold text-[11px] text-[#92400e] bg-[#fef3c7] px-2.5 py-1 rounded-md">{{ isset($latestMeasure['z_score_tbu']) && $latestMeasure['z_score_tbu'] !== null ? $latestMeasure['z_score_tbu'] . ' SD' : ($birthLength ? 'Awal Lahir' : '-') }}</span>
                        </div>
                    </div>
                    
                    <!-- Lingkar Kepala -->
                    <div class="bg-[#f8fafc] border border-slate-200/60 rounded-[20px] p-6 flex flex-col justify-between relative overflow-hidden group">
                        <div class="flex items-start justify-between mb-8">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-[10px] bg-[#475569] text-white flex items-center justify-center shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418" /></svg>
                                </div>
                                <span class="text-[14px] font-bold text-slate-900">Lingkar Kepala</span>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-1.5 mb-8">
                            <span class="text-[36px] sm:text-[44px] font-black text-slate-900 tracking-tight leading-none">{{ $latestMeasure['head_circ'] ?? ($birthHeadCirc ?: '-') }}</span>
                            <span class="text-[18px] sm:text-[20px] font-medium text-slate-500">cm</span>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-slate-200/70">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">STATUS PENGUKURAN</span>
                            <span class="font-bold text-[11px] text-[#047857] bg-[#d1fae5] px-2.5 py-1 rounded-md">{{ !empty($latestMeasure['head_circ']) ? 'Tercatat Sesuai KIA' : ($birthHeadCirc ? 'Lahir KIA' : 'Belum Ada Data') }}</span>
                        </div>
                    </div>
                </div>
                
                @if(!empty($latestMeasure['catatan_validator']))
                <div class="mb-8 p-5 bg-[#fff8eb] border border-[#fde6b3] rounded-2xl flex items-start gap-4 shadow-sm relative overflow-hidden group">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-400"></div>
                    <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-600 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2.25m0 1.5h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>
                    <div class="flex flex-col flex-1">
                        <span class="text-[13px] font-bold text-amber-900">Catatan Ahli Gizi (Validator)</span>
                        <p class="text-[13.5px] text-amber-800/90 mt-1 leading-relaxed">{{ $latestMeasure['catatan_validator'] }}</p>
                    </div>
                </div>
                @endif

                <!-- Action Strip: Riwayat & Analisis Pertumbuhan -->
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-5 sm:p-6 bg-white rounded-2xl border border-slate-200 shadow-[0_2px_10px_rgba(0,0,0,0.02)]">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-[12px] bg-[#e0f2f1] text-[#086a7c] flex items-center justify-center font-bold shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3v11.25A2.25 2.25 0 006 16.5h2.25M3.75 3h-1.5m1.5 0h16.5m0 0h1.5m-1.5 0v11.25A2.25 2.25 0 0118 16.5h-2.25m-7.5 0h7.5m-7.5 0l-1 3m8.5-3l1 3m0 0l.5 1.5m-.5-1.5h-9.5m0 0l-.5 1.5M9 11.25v1.5M12 9v3.75m3-6v6" /></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[16px] font-bold text-slate-900">Riwayat & Analisis Pertumbuhan</span>
                            <p class="text-[13px] text-slate-500 mt-0.5">Lihat grafik kenaikan berat/tinggi badan dan data historis balita ini.</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 sm:gap-3 w-full sm:w-auto mt-4 sm:mt-0">
                        <button onclick="switchTab('riwayat')" class="flex-1 sm:flex-none px-4 sm:px-5 py-3 sm:py-3 bg-white border border-slate-200 hover:border-slate-300 text-slate-700 text-[12px] sm:text-[13px] font-bold rounded-xl transition-all shadow-sm whitespace-nowrap">
                            Buka Riwayat
                        </button>
                        
                    </div>
                </div>

            </div>
        </div>

        <!-- TAB 2: DETAIL INFORMASI (Comprehensive 2-Column KMS Master View) -->
        <div id="content-detail" class="tab-content hidden flex flex-col gap-6">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- COLUMN 1: LEFT SIDE (Identitas & Domisili) -->
                <div class="flex flex-col gap-6 lg:col-span-1">
                    
                    <!-- Card: Identitas Diri -->
                    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-sm p-6 relative overflow-hidden">
                        <!-- Left blue strip -->
                        <div class="absolute left-0 top-6 bottom-6 w-1 bg-cyan-400 rounded-r-lg"></div>

                        <div class="flex items-center gap-3 mb-6 ml-2">
                            <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" /></svg>
                            </div>
                            <h3 class="text-[16px] font-bold text-slate-900">Identitas Diri</h3>
                        </div>

                        <div class="flex flex-col gap-5 ml-2">
                            <!-- Nama Lengkap -->
                            <div>
                                <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Nama Lengkap</span>
                                <span class="font-extrabold text-slate-900 text-[15px]">{{ $childName }}</span>
                            </div>

                            <!-- NIK Balita -->
                            <div class="min-w-0">
                                <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">NIK Balita</span>
                                <div class="flex items-center justify-between gap-2">
                                    <div class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-[13px] sm:text-[14px] font-bold text-slate-800 tracking-wide font-mono truncate min-w-0 flex-1">
                                        {{ $nik }}
                                    </div>
                                    <button onclick="navigator.clipboard.writeText('{{ $nik }}'); window.NutriAlert.toast('Berhasil!', 'NIK disalin', 'success');" class="p-2.5 rounded-lg border border-slate-200 text-slate-400 hover:text-slate-600 hover:bg-slate-50 transition-colors shrink-0" title="Salin NIK">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" /></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- No. BPJS -->
                            <div>
                                <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">No. BPJS / JKN</span>
                                <div class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-2 text-[14px] font-bold text-slate-800 tracking-wide font-mono inline-block">
                                    {{ $noBpjs ?: '-' }}
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <!-- Tanggal Lahir -->
                                <div>
                                    <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Tanggal Lahir</span>
                                    <span class="font-semibold text-slate-800 text-[14px]">{{ $birthDate ?? '-' }}</span>
                                </div>

                                <!-- Jenis Kelamin -->
                                <div>
                                    <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Jenis Kelamin</span>
                                    <span class="font-semibold text-slate-800 text-[14px]">{{ $gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Card: Antropometri Saat Lahir (KIA) -->
                    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-sm p-6 relative overflow-hidden">
                        <!-- Left green strip -->
                        <div class="absolute left-0 top-6 bottom-6 w-1 bg-emerald-400 rounded-r-lg"></div>

                        <div class="flex items-center gap-3 mb-5 ml-2">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-100 flex items-center justify-center text-emerald-600">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 7.5a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5z" /><path stroke-linecap="round" stroke-linejoin="round" d="M1.5 4.875C1.5 3.839 2.34 3 3.375 3h17.25c1.035 0 1.875.84 1.875 1.875v9.75c0 1.036-.84 1.875-1.875 1.875H3.375A1.875 1.875 0 011.5 14.625v-9.75zM8.25 9.75a3.75 3.75 0 117.5 0 3.75 3.75 0 01-7.5 0zM18.75 9a.75.75 0 00-.75.75v.008c0 .414.336.75.75.75h.008a.75.75 0 00.75-.75V9.75a.75.75 0 00-.75-.75h-.008zM4.5 9.75A.75.75 0 015.25 9h.008a.75.75 0 01.75.75v.008a.75.75 0 01-.75.75H5.25a.75.75 0 01-.75-.75V9.75z" /></svg>
                            </div>
                            <h3 class="text-[16px] font-bold text-slate-900 leading-tight">Antropometri Lahir <br><span class="text-[12px] font-medium text-slate-500 font-normal">Data KIA</span></h3>
                        </div>

                        <div class="grid grid-cols-3 gap-3 ml-2">
                            <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 flex flex-col justify-center text-center">
                                <span class="text-[9px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Berat</span>
                                <span class="font-extrabold text-slate-900 text-[14px]">{{ $birthWeight ? $birthWeight . ' kg' : '-' }}</span>
                            </div>
                            <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 flex flex-col justify-center text-center">
                                <span class="text-[9px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Panjang</span>
                                <span class="font-extrabold text-slate-900 text-[14px]">{{ $birthLength ? $birthLength . ' cm' : '-' }}</span>
                            </div>
                            <div class="bg-slate-50 border border-slate-100 rounded-xl p-3 flex flex-col justify-center text-center">
                                <span class="text-[9px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">L. Kepala</span>
                                <span class="font-extrabold text-slate-900 text-[14px]">{{ $birthHeadCirc ? $birthHeadCirc . ' cm' : '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card: Posyandu & Domisili -->
                    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-sm relative overflow-hidden">
                        <!-- Top Map Image -->
                        <div class="h-32 w-full relative bg-[#e8f4f8]">
                            <!-- A real map image background -->
                            <img src="https://static-maps.yandex.ru/1.x/?lang=en-US&ll=95.3175,5.5500&z=13&l=map&size=600,200" alt="Map" class="absolute inset-0 w-full h-full object-cover opacity-80" />
                            
                            <!-- Gradient overlay to blend with content -->
                            <div class="absolute inset-0 bg-gradient-to-t from-white via-white/50 to-transparent"></div>
                        </div>

                        <!-- Left blue strip -->
                        <div class="absolute left-0 top-32 bottom-6 w-1 bg-cyan-400 rounded-r-lg z-10"></div>

                        <div class="p-6 pt-0 relative z-10 -mt-6">
                            <div class="flex items-center gap-3 mb-6 ml-2">
                                <div class="w-12 h-12 rounded-full bg-white border border-orange-100 flex items-center justify-center text-orange-500 shadow-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                </div>
                                <h3 class="text-[16px] font-bold text-slate-900 mt-4">Posyandu & Domisili</h3>
                            </div>

                            <div class="flex flex-col gap-5 ml-2">
                                <!-- Posyandu -->
                                <div>
                                    <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Posyandu Binaan</span>
                                    <div class="flex items-center gap-1.5 text-[#086a7c] font-bold text-[14px]">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        {{ $posyanduName }}
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <!-- Desa -->
                                    <div>
                                        <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Desa / Kelurahan</span>
                                        <span class="font-semibold text-slate-800 text-[14px]">{{ $address }}</span>
                                    </div>

                                    <!-- Kec -->
                                    <div>
                                        <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Kecamatan</span>
                                        <span class="font-semibold text-slate-800 text-[14px]">{{ $addressSub ?: '-' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COLUMN 2: RIGHT SIDE (Informasi Keluarga) -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-sm relative h-full">
                        <!-- Top Gradient Border -->
                        <div class="absolute top-0 left-4 right-4 h-1 bg-gradient-to-r from-cyan-400 via-indigo-400 to-purple-400 rounded-b-lg"></div>

                        <div class="p-6 lg:p-8">
                            <!-- Header Row -->
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" /></svg>
                                    </div>
                                    <h3 class="text-[20px] font-bold text-slate-900">Informasi Keluarga</h3>
                                </div>
                                
                                <div class="flex items-center gap-2">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No. KK</span>
                                    <div class="bg-slate-50 border border-slate-200 rounded-lg px-3 py-1.5 flex items-center gap-2">
                                        <span class="text-[14px] font-bold text-slate-800 font-mono tracking-wider">{{ $noKk ?: '-' }}</span>
                                        @if($noKk)
                                        <button onclick="navigator.clipboard.writeText('{{ $noKk }}'); window.NutriAlert.toast('Berhasil!', 'No. KK disalin', 'success');" class="text-slate-400 hover:text-slate-600 transition-colors" title="Salin No. KK">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 01-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 011.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 00-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 01-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 00-3.375-3.375h-1.5a1.125 1.125 0 01-1.125-1.125v-1.5a3.375 3.375 0 00-3.375-3.375H9.75" /></svg>
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <!-- Inner Cards Grid -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Ibu Kandung Card -->
                                <div class="border border-teal-100 bg-teal-50/30 rounded-[20px] p-6 relative">
                                    <div class="flex items-center gap-3 mb-6">
                                        <div class="w-10 h-10 rounded-full bg-pink-100 flex items-center justify-center text-pink-600">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                        </div>
                                        <h4 class="text-[15px] font-bold text-slate-900">Data Ibu Kandung</h4>
                                    </div>

                                    <div class="flex flex-col gap-5">
                                        <div>
                                            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Nama Lengkap</span>
                                            <span class="font-extrabold text-slate-900 text-[14px]">{{ $motherName }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">NIK</span>
                                            <span class="font-bold text-slate-800 text-[14px] font-mono tracking-wide">{{ $motherNik ?: '-' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Pekerjaan</span>
                                            <span class="font-semibold text-slate-800 text-[14px]">{{ $motherJob ?: '-' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Kontak</span>
                                            @if($motherPhone)
                                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $motherPhone) }}" target="_blank" class="inline-flex items-center gap-2 bg-white border border-slate-200 rounded-lg px-3 py-2 text-[14px] font-bold text-slate-700 hover:border-teal-300 transition-colors font-mono">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" class="w-4 h-4 fill-emerald-500"><path d="M380.9 97.1C339 55.1 283.2 32 223.9 32c-122.4 0-222 99.6-222 222 0 39.1 10.2 77.3 29.6 111L0 480l117.7-30.9c32.4 17.7 68.9 27 106.1 27h.1c122.3 0 224.1-99.6 224.1-222 0-59.3-25.2-115-67.1-157.1zm-157 341.6c-33.2 0-65.7-8.9-94-25.7l-6.7-4-69.8 18.3L72 359.2l-4.4-7c-18.5-29.4-28.2-63.3-28.2-98.2 0-101.7 82.8-184.5 184.6-184.5 49.3 0 95.6 19.2 130.4 54.1 34.8 34.9 56.2 81.2 56.1 130.5 0 101.8-84.9 184.6-186.6 184.6zm101.2-138.2c-5.5-2.8-32.8-16.2-37.9-18-5.1-1.9-8.8-2.8-12.5 2.8-3.7 5.6-14.3 18-17.6 21.8-3.2 3.7-6.5 4.2-12 1.4-32.6-16.3-54-29.1-75.5-66-5.7-9.8 5.7-9.1 16.3-30.3 1.8-3.7.9-6.9-.5-9.7-1.4-2.8-12.5-30.1-17.1-41.2-4.5-10.8-9.1-9.3-12.5-9.5-3.2-.2-6.9-.2-10.6-.2-3.7 0-9.7 1.4-14.8 6.9-5.1 5.6-19.4 19-19.4 46.3 0 27.3 19.9 53.7 22.6 57.4 2.8 3.7 39.1 59.7 94.8 83.8 35.2 15.2 49 16.5 66.6 13.9 10.7-1.6 32.8-13.4 37.4-26.4 4.6-13 4.6-24.1 3.2-26.4-1.3-2.5-5-3.9-10.5-6.6z"/></svg>
                                                {{ $motherPhone }}
                                            </a>
                                            @else
                                            <div class="inline-flex items-center px-3 py-2 bg-slate-50 border border-slate-200 border-dashed rounded-lg text-slate-400 italic text-[13px]">
                                                Tidak tersedia
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- Ayah Card -->
                                <div class="border border-slate-200 bg-white rounded-[20px] p-6 relative">
                                    <div class="flex items-center justify-between mb-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                            </div>
                                            <h4 class="text-[15px] font-bold text-slate-900">Data Ayah</h4>
                                        </div>
                                        @if($fatherName)
                                        <span class="px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-full text-[10px] font-bold uppercase tracking-widest flex items-center gap-1.5">
                                            <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full block"></span>
                                            Aktif
                                        </span>
                                        @endif
                                    </div>

                                    <div class="flex flex-col gap-5">
                                        <div>
                                            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Nama Lengkap</span>
                                            <span class="font-extrabold text-slate-900 text-[14px]">{{ $fatherName ?: '-' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">NIK</span>
                                            <span class="font-bold text-slate-800 text-[14px] font-mono tracking-wide">{{ $fatherNik ?: '-' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Pekerjaan</span>
                                            <span class="font-semibold text-slate-800 text-[14px]">{{ $fatherJob ?: '-' }}</span>
                                        </div>
                                        <div>
                                            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Kontak</span>
                                            <div class="inline-flex items-center px-3 py-2 bg-slate-50 border border-slate-200 border-dashed rounded-lg text-slate-400 italic text-[13px]">
                                                Tidak tersedia
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: RIWAYAT -->
        <div id="content-riwayat" class="tab-content hidden flex flex-col p-4 sm:p-6 lg:p-7 bg-white border border-slate-200/80 rounded-[24px] shadow-sm">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-6">
                <div>
                    <h2 class="text-[16px] font-black text-slate-900 tracking-tight">Riwayat Pengukuran</h2>
                    <p class="text-[13px] text-slate-500 mt-0.5">Catatan historis tumbuh kembang dan riwayat validasi puskesmas</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-3 py-1 bg-teal-50 text-teal-700 border border-teal-200/60 rounded-full text-[12px] font-bold">
                        {{ count($measurements) }} Pengukuran
                    </span>
                </div>
            </div>

            @if(count($measurements) > 0)
                @php
                    $totalCount = count($measurements);
                    $firstMeasure = $measurements[$totalCount - 1];
                    $latestMeasureItem = $measurements[0];
                    
                    $totalWeightGain = ($firstMeasure && $latestMeasureItem && $firstMeasure['weight'] && $latestMeasureItem['weight']) 
                        ? round($latestMeasureItem['weight'] - $firstMeasure['weight'], 2) 
                        : null;
                        
                    $totalHeightGain = ($firstMeasure && $latestMeasureItem && $firstMeasure['height'] && $latestMeasureItem['height']) 
                        ? round($latestMeasureItem['height'] - $firstMeasure['height'], 1) 
                        : null;
                @endphp

                <!-- Summary Highlight Cards -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-4 mb-6 sm:mb-8">
                    <!-- Total Weight Progression -->
                    <div class="bg-white rounded-[16px] sm:rounded-[20px] p-4 sm:p-5 border border-slate-200/70 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="absolute right-0 top-0 w-24 h-24 bg-emerald-50 rounded-full blur-2xl -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
                        <div class="flex items-center gap-3 sm:gap-4 relative z-10">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-gradient-to-br from-emerald-400 to-teal-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-emerald-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0l-3.75-3.75M12 20.25l3.75-3.75M3 12h18" /></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-0.5 sm:mb-1">Total Kenaikan BB</span>
                                <span class="text-[18px] sm:text-[20px] font-black {{ $totalWeightGain >= 0 ? 'text-slate-800' : 'text-rose-600' }} leading-none">
                                    {{ $totalWeightGain !== null ? ($totalWeightGain > 0 ? '+' . $totalWeightGain : $totalWeightGain) . ' kg' : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Total Height Progression -->
                    <div class="bg-white rounded-[16px] sm:rounded-[20px] p-4 sm:p-5 border border-slate-200/70 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="absolute right-0 top-0 w-24 h-24 bg-amber-50 rounded-full blur-2xl -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
                        <div class="flex items-center gap-3 sm:gap-4 relative z-10">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-gradient-to-br from-amber-400 to-orange-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-amber-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" /></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-0.5 sm:mb-1">Total Kenaikan TB</span>
                                <span class="text-[18px] sm:text-[20px] font-black text-slate-800 leading-none">
                                    {{ $totalHeightGain !== null ? ($totalHeightGain > 0 ? '+' . $totalHeightGain : $totalHeightGain) . ' cm' : '-' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Observation Period -->
                    <div class="bg-white rounded-[16px] sm:rounded-[20px] p-4 sm:p-5 border border-slate-200/70 shadow-sm relative overflow-hidden group hover:shadow-md transition-shadow">
                        <div class="absolute right-0 top-0 w-24 h-24 bg-indigo-50 rounded-full blur-2xl -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
                        <div class="flex items-center gap-3 sm:gap-4 relative z-10">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl sm:rounded-2xl bg-gradient-to-br from-indigo-400 to-blue-500 text-white flex items-center justify-center shrink-0 shadow-md shadow-indigo-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 sm:w-6 sm:h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-widest text-slate-400 mb-0.5 sm:mb-1">Periode Pantau</span>
                                <span class="text-[13px] sm:text-[14px] font-black text-slate-800 leading-none mt-0.5 sm:mt-1">
                                    {{ $firstMeasure['date'] ?? '-' }}<br>
                                    <span class="text-[11px] sm:text-[12px] font-semibold text-slate-500">s/d {{ $latestMeasureItem['date'] ?? '-' }}</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex flex-col mt-2">
                @forelse($measurements as $measure)
                    <x-timeline-item :measurement="$measure" :is-last="$loop->last" :is-latest="$loop->first" />
                @empty
                    <div class="py-12 flex flex-col items-center justify-center text-center">
                        <div class="w-16 h-16 bg-slate-50 text-slate-300 rounded-full flex items-center justify-center mb-5">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-8 h-8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <span class="text-[14px] font-bold text-slate-900">Belum Ada Riwayat</span>
                        <p class="text-[13px] text-slate-500 mt-1 mb-4">Anak belum pernah diukur.</p>
                        
                    </div>
                @endforelse
            </div>
        </div>

        <!-- TAB: GRAFIK -->
        <div id="content-grafik" class="tab-content hidden flex flex-col" x-data="{ chartType: 'bb' }" @change-chart.window="chartType = $event.detail">
            <div class="bg-white border border-slate-200/80 rounded-[24px] shadow-sm overflow-hidden group">
                
                <!-- Header & Segmented Control -->
                <div class="p-5 sm:p-6 lg:p-8 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-5 relative overflow-hidden">
                    <!-- decorative blur background -->
                    <div class="absolute -right-10 -top-10 w-40 h-40 bg-teal-50 rounded-full blur-3xl transition-transform duration-700 group-hover:scale-150"></div>
                    
                    <div class="relative z-10">
                        <h2 class="text-[18px] sm:text-[20px] font-black text-slate-900 tracking-tight flex items-center gap-2.5">
                            <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center border border-teal-100/50 shadow-2xs">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" /></svg>
                            </div>
                            Visualisasi Pertumbuhan
                        </h2>
                        <p class="text-[13px] text-slate-500 mt-1.5 font-medium ml-10">Pantau tren perkembangan fisik anak dari waktu ke waktu.</p>
                    </div>

                    @if(count($measurements) > 0)
                    <!-- Premium Segmented Control -->
                    <div class="relative z-10 w-full lg:w-auto overflow-x-auto hide-scrollbar rounded-xl sm:rounded-full">
                        <div class="inline-flex w-max bg-slate-100/80 p-1 rounded-xl sm:rounded-full border border-slate-200/60 shadow-inner">
                            <button type="button" @click="$dispatch('change-chart', 'bb')" 
                                class="px-4 py-2 sm:py-1.5 rounded-lg sm:rounded-full text-[12px] sm:text-[13px] font-bold transition-all duration-300 whitespace-nowrap"
                                :class="chartType === 'bb' ? 'bg-white text-emerald-600 shadow-sm ring-1 ring-slate-200/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50'">
                                Berat Badan (BB/U)
                            </button>
                            <button type="button" @click="$dispatch('change-chart', 'tb')" 
                                class="px-4 py-2 sm:py-1.5 rounded-lg sm:rounded-full text-[12px] sm:text-[13px] font-bold transition-all duration-300 whitespace-nowrap"
                                :class="chartType === 'tb' ? 'bg-white text-amber-600 shadow-sm ring-1 ring-slate-200/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50'">
                                Tinggi Badan (TB/U)
                            </button>
                            <button type="button" @click="$dispatch('change-chart', 'lk')" 
                                class="px-4 py-2 sm:py-1.5 rounded-lg sm:rounded-full text-[12px] sm:text-[13px] font-bold transition-all duration-300 whitespace-nowrap"
                                :class="chartType === 'lk' ? 'bg-white text-indigo-600 shadow-sm ring-1 ring-slate-200/50' : 'text-slate-500 hover:text-slate-700 hover:bg-slate-200/50'">
                                Lingkar Kepala (LK/U)
                            </button>
                        </div>
                    </div>
                    @endif
                </div>

                @if(count($measurements) > 0)
                <!-- Chart Canvas Area -->
                <div class="p-4 sm:p-6 lg:p-8 bg-slate-50/30 relative">
                    <!-- Subtle Grid Pattern CSS inside container -->
                    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 24px 24px;"></div>
                    
                    <div class="relative h-[320px] sm:h-[400px] w-full">
                        <canvas id="growthChart" class="w-full h-full relative z-10"></canvas>
                    </div>
                </div>
                @else
                <!-- Empty State -->
                <div class="py-16 flex flex-col items-center text-center px-4 bg-slate-50/50">
                    <div class="w-20 h-20 rounded-full bg-white border border-slate-100 shadow-sm flex items-center justify-center mb-5 relative">
                        <div class="absolute inset-0 rounded-full bg-slate-100 animate-ping opacity-20"></div>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 text-slate-300">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                    </div>
                    <span class="text-[16px] font-black text-slate-900 tracking-tight">Belum Ada Data Grafik</span>
                    <p class="text-[13px] text-slate-500 mt-2 max-w-[280px] mx-auto font-medium">Lakukan pengukuran pertama untuk melihat visualisasi kurva pertumbuhan anak.</p>
                </div>
                @endif
            </div>
        </div>
        
    </div>
</div>


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const rawData = @json($measurements ?? []);
        if (!Array.isArray(rawData) || rawData.length === 0) return;

        const chartData = [...rawData].reverse();
        
        // Use age for the X-axis label, fallback to date if age isn't available
        const labels = chartData.map(d => d.age_at_measure ? d.age_at_measure : d.date);
        
        const ctx = document.getElementById('growthChart');
        if (!ctx) return;
        
        const canvasCtx = ctx.getContext('2d');

        // Helper to create beautiful gradients
        const createGradient = (colorStart, colorEnd) => {
            let gradient = canvasCtx.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, colorStart);
            gradient.addColorStop(1, colorEnd);
            return gradient;
        };

        const chartConfigs = {
            bb: {
                label: 'Berat Badan per Umur (BB/U)',
                data: chartData.map(d => d.weight),
                unit: 'kg',
                borderColor: '#10b981', // emerald-500
                backgroundColor: createGradient('rgba(16, 185, 129, 0.35)', 'rgba(16, 185, 129, 0.0)'),
                pointColor: '#047857' // emerald-700
            },
            tb: {
                label: 'Tinggi Badan per Umur (TB/U)',
                data: chartData.map(d => d.height),
                unit: 'cm',
                borderColor: '#f59e0b', // amber-500
                backgroundColor: createGradient('rgba(245, 158, 11, 0.35)', 'rgba(245, 158, 11, 0.0)'),
                pointColor: '#b45309' // amber-700
            },
            lk: {
                label: 'Lingkar Kepala per Umur (LK/U)',
                data: chartData.map(d => d.head_circ),
                unit: 'cm',
                borderColor: '#6366f1', // indigo-500
                backgroundColor: createGradient('rgba(99, 102, 241, 0.35)', 'rgba(99, 102, 241, 0.0)'),
                pointColor: '#4338ca' // indigo-700
            }
        };

        let currentType = 'bb';

        // Add soft drop shadow to the line itself
        Chart.defaults.elements.line.borderCapStyle = 'round';
        Chart.defaults.elements.line.borderJoinStyle = 'round';

        const growthChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: chartConfigs[currentType].label,
                    data: chartConfigs[currentType].data,
                    borderColor: chartConfigs[currentType].borderColor,
                    backgroundColor: chartConfigs[currentType].backgroundColor,
                    borderWidth: 4,
                    pointBackgroundColor: '#ffffff',
                    pointBorderColor: chartConfigs[currentType].pointColor,
                    pointBorderWidth: 3,
                    pointRadius: 5,
                    pointHoverRadius: 8,
                    pointHoverBackgroundColor: chartConfigs[currentType].borderColor,
                    pointHoverBorderColor: '#ffffff',
                    pointHoverBorderWidth: 3,
                    fill: true,
                    tension: 0.45, // Smooth organic curve
                    spanGaps: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    intersect: false,
                    mode: 'index'
                },
                plugins: {
                    legend: {
                        display: false // We use our own custom UI header, so hide native legend
                    },
                    tooltip: {
                        backgroundColor: 'rgba(255, 255, 255, 0.95)',
                        titleColor: '#64748b',
                        bodyColor: '#0f172a',
                        titleFont: { size: 12, family: "'Inter', sans-serif", weight: '600' },
                        bodyFont: { size: 15, family: "'Inter', sans-serif", weight: '900' },
                        padding: 16,
                        cornerRadius: 16,
                        displayColors: false,
                        borderColor: 'rgba(226, 232, 240, 1)',
                        borderWidth: 1,
                        caretSize: 6,
                        caretPadding: 10,
                        boxShadow: '0 10px 25px -5px rgba(0, 0, 0, 0.1)',
                        callbacks: {
                            title: function(context) {
                                const dataIndex = context[0].dataIndex;
                                const dataPoint = chartData[dataIndex];
                                const ageStr = dataPoint.age_at_measure ? dataPoint.age_at_measure : '-';
                                return 'Usia: ' + ageStr + ' (' + dataPoint.date + ')';
                            },
                            label: function(context) {
                                const val = context.parsed.y;
                                if (val === null || val === undefined || isNaN(val)) return 'Belum diukur';
                                return `${val} ${chartConfigs[currentType].unit}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        grid: { 
                            color: 'rgba(226, 232, 240, 0.6)', 
                            borderDash: [5, 5], 
                            drawBorder: false,
                            tickLength: 0
                        },
                        ticks: {
                            font: { family: "'Inter', sans-serif", size: 12, weight: '600' },
                            color: '#94a3b8',
                            padding: 12,
                            callback: function(value) {
                                return value + ' ' + chartConfigs[currentType].unit;
                            }
                        },
                        border: { display: false }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                        ticks: { 
                            font: { family: "'Inter', sans-serif", size: 12, weight: '600' }, 
                            color: '#94a3b8',
                            padding: 10
                        },
                        border: { display: false }
                    }
                }
            }
        });

        // Listen for the AlpineJS custom event from our new Segmented Control
        window.addEventListener('change-chart', function(e) {
            const selectedKey = e.detail;
            if (!chartConfigs[selectedKey]) return;

            currentType = selectedKey;
            const targetConfig = chartConfigs[selectedKey];

            growthChart.data.datasets[0].label = targetConfig.label;
            growthChart.data.datasets[0].data = targetConfig.data;
            growthChart.data.datasets[0].borderColor = targetConfig.borderColor;
            growthChart.data.datasets[0].backgroundColor = targetConfig.backgroundColor;
            growthChart.data.datasets[0].pointBorderColor = targetConfig.pointColor;
            growthChart.data.datasets[0].pointHoverBackgroundColor = targetConfig.borderColor;

            growthChart.update();
        });
    });
</script>
<script>
    function switchTab(tabId) {
        document.querySelectorAll('.tab-content').forEach(el => {
            el.classList.add('hidden');
        });
        
        const activeClasses = ['border-[#086a7c]', 'text-[#086a7c]', 'font-bold'];
        const inactiveClasses = ['border-transparent', 'text-slate-500', 'hover:text-slate-700', 'hover:border-slate-300', 'font-medium'];
        
        ['ringkasan', 'detail', 'riwayat', 'grafik'].forEach(id => {
            const btn = document.getElementById('tab-' + id);
            if(btn) {
                btn.classList.remove(...activeClasses);
                btn.classList.add(...inactiveClasses);
            }
        });
        
        const selectedBtn = document.getElementById('tab-' + tabId);
        if(selectedBtn) {
            selectedBtn.classList.remove(...inactiveClasses);
            selectedBtn.classList.add(...activeClasses);
        }
        
        const content = document.getElementById('content-' + tabId);
        if(content) {
            content.classList.remove('hidden');
        }
    }
    
    // Initialize tab
    document.addEventListener('DOMContentLoaded', () => {
        switchTab('ringkasan');
    });
</script>
@endpush

@endsection
