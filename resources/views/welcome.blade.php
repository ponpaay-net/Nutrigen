@extends('layouts.public')

@section('title', 'NutriGen | Platform Monitoring Gizi Balita')

@section('content')

    {{-- 1. Hero Section --}}
    <section class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden bg-slate-50">
        {{-- Grid Pattern + Glow --}}
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:24px_24px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] z-0"></div>
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[500px] bg-gradient-to-r from-emerald-100/60 via-cyan-100/60 to-emerald-100/60 rounded-[100%] blur-[120px] opacity-80 pointer-events-none z-0"></div>

        {{-- Floating Info Cards --}}
        <div class="hidden lg:block absolute top-56 left-8 xl:left-20 z-10" data-aos="fade-right" data-aos-delay="400">
            <div class="bg-white/90 backdrop-blur-md border border-slate-100 p-3 rounded-2xl shadow-lg flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-emerald-50 flex items-center justify-center text-emerald-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-800 leading-tight">Validasi Real-time</p>
                    <p class="text-[10px] text-slate-500 font-medium">Oleh Ahli Gizi</p>
                </div>
            </div>
        </div>
        <div class="hidden lg:block absolute top-72 right-8 xl:right-20 z-10" data-aos="fade-left" data-aos-delay="500">
            <div class="bg-white/90 backdrop-blur-md border border-slate-100 p-3 rounded-2xl shadow-lg flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-cyan-50 flex items-center justify-center text-cyan-600 shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                </div>
                <div>
                    <p class="text-xs font-bold text-slate-800 leading-tight">Standar WHO 2006</p>
                    <p class="text-[10px] text-slate-500 font-medium">Z-Score Akurat</p>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-20 text-center flex flex-col items-center">

            {{-- Badge --}}
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white border border-slate-200 text-emerald-700 font-semibold text-xs sm:text-sm mb-8 shadow-sm" data-aos="fade-up">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-500 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                Solusi Digital Stunting Terintegrasi 2026
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold text-slate-900 tracking-tight leading-[1.08] mb-6 max-w-4xl mx-auto" data-aos="fade-up" data-aos-delay="100">
                Bersama Tuntaskan Stunting,<br class="hidden sm:block"> <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-cyan-600">Bangun Generasi Emas</span>
            </h1>

            <p class="text-lg lg:text-xl font-medium text-slate-500 mb-10 max-w-2xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                Ekosistem digital terintegrasi yang menghubungkan Ibu, Kader Posyandu, dan Tenaga Puskesmas untuk pemantauan gizi anak yang presisi, <span class="text-slate-700 font-semibold">real-time</span>, dan berbasis data.
            </p>

            {{-- CTAs --}}
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4 w-full sm:w-auto" data-aos="fade-up" data-aos-delay="300">
                <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white font-bold rounded-2xl shadow-[0_8px_24px_rgba(16,185,129,0.3)] transition-all duration-300 hover:-translate-y-0.5 hover:shadow-[0_12px_28px_rgba(16,185,129,0.4)] active:scale-95 flex items-center justify-center gap-2 group">
                    <span>Masuk ke Sistem</span>
                    <svg class="w-4 h-4 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                </a>
                <a href="#how-it-works" class="w-full sm:w-auto px-8 py-4 bg-white hover:bg-slate-50 text-slate-700 font-bold rounded-2xl shadow-[0_2px_12px_rgba(0,0,0,0.06)] border border-slate-200 transition-all duration-300 hover:shadow-[0_6px_20px_rgba(0,0,0,0.08)] hover:-translate-y-0.5 active:scale-95 flex items-center justify-center gap-2">
                    Pelajari Ekosistem
                </a>
            </div>

            <p class="mt-8 text-xs font-semibold text-slate-400 uppercase tracking-widest" data-aos="fade-up" data-aos-delay="400">Terintegrasi dengan Standar Kurva WHO 2006</p>
        </div>
    </section>

    {{-- 2 & 3. Permasalahan & Statistik --}}
    <section id="problem" class="py-24 lg:py-32 bg-white relative border-y border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-16 items-center">
                {{-- Text Area (Problem) --}}
                <div class="lg:col-span-7" data-aos="fade-up">
                    <h2 class="text-emerald-600 font-extrabold text-sm uppercase tracking-widest mb-4">Realita Saat Ini</h2>
                    <h3 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-6">
                        Pemantauan Manual Meninggalkan Celah Berbahaya.
                    </h3>
                    <p class="text-lg text-slate-500 font-medium leading-relaxed mb-10">
                        Jutaan buku KIA tersimpan di laci tanpa dievaluasi. Data Posyandu memakan waktu berminggu-minggu untuk direkap, menyebabkan keterlambatan intervensi gizi pada masa <em>golden age</em> balita.
                    </p>

                    <div class="space-y-3">
                        <div class="flex items-start gap-4 group p-4 rounded-2xl hover:bg-rose-50/50 border border-transparent hover:border-rose-100 transition-all duration-300" data-aos="fade-up" data-aos-delay="100">
                            <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-rose-50 border border-rose-100 flex items-center justify-center group-hover:scale-110 group-hover:bg-rose-100 transition-all duration-300 shadow-sm">
                                <svg class="w-5 h-5 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-slate-900 font-bold text-base leading-snug mb-1 group-hover:text-rose-700 transition-colors">Data Lambat Diproses</h4>
                                <p class="text-slate-500 text-sm leading-relaxed">Data lambat sampai ke tenaga kesehatan Puskesmas, menunda tindakan preventif.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 group p-4 rounded-2xl hover:bg-amber-50/50 border border-transparent hover:border-amber-100 transition-all duration-300" data-aos="fade-up" data-aos-delay="200">
                            <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-amber-50 border border-amber-100 flex items-center justify-center group-hover:scale-110 group-hover:bg-amber-100 transition-all duration-300 shadow-sm">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-slate-900 font-bold text-base leading-snug mb-1 group-hover:text-amber-700 transition-colors">Kurangnya Edukasi Mandiri</h4>
                                <p class="text-slate-500 text-sm leading-relaxed">Ibu tidak memahami kurva pertumbuhan anaknya secara mandiri di rumah.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 group p-4 rounded-2xl hover:bg-blue-50/50 border border-transparent hover:border-blue-100 transition-all duration-300" data-aos="fade-up" data-aos-delay="300">
                            <div class="flex-shrink-0 w-11 h-11 rounded-xl bg-blue-50 border border-blue-100 flex items-center justify-center group-hover:scale-110 group-hover:bg-blue-100 transition-all duration-300 shadow-sm">
                                <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-slate-900 font-bold text-base leading-snug mb-1 group-hover:text-blue-700 transition-colors">Rawan Human Error</h4>
                                <p class="text-slate-500 text-sm leading-relaxed">Risiko kesalahan perhitungan manual dalam menentukan status stunting.</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Statistik Card — Light with green tones --}}
                <div class="lg:col-span-5" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative bg-gradient-to-br from-emerald-50 via-white to-cyan-50 p-6 sm:p-10 rounded-[1.5rem] sm:rounded-[2.5rem] border border-emerald-100 shadow-[0_20px_60px_rgba(16,185,129,0.1)] overflow-hidden group hover:-translate-y-2 hover:shadow-[0_30px_70px_rgba(16,185,129,0.15)] transition-all duration-500">
                        <div class="absolute top-0 right-0 w-48 h-48 bg-emerald-100/50 rounded-full -translate-y-1/2 translate-x-1/2 blur-3xl pointer-events-none"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-cyan-100/50 rounded-full translate-y-1/2 -translate-x-1/2 blur-3xl pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="flex items-baseline mb-2">
                                <span class="text-6xl sm:text-7xl lg:text-8xl font-extrabold text-slate-900 tracking-tight">21.6</span>
                                <span class="text-3xl sm:text-4xl font-bold text-emerald-500 ml-2">%</span>
                            </div>
                            <h4 class="text-xl font-bold text-slate-800 mb-5 pb-5 border-b border-slate-200">Angka Stunting Nasional</h4>
                            <p class="text-slate-600 font-medium leading-relaxed text-sm">
                                Meskipun menurun, angka ini masih jauh dari target ambang batas WHO (di bawah 20%). Kita butuh revolusi kecepatan data untuk mengejar target 14%.
                            </p>
                            <div class="mt-6 pt-5 border-t border-slate-100">
                                <div class="flex items-center gap-2">
                                    <div class="h-2 flex-1 bg-slate-100 rounded-full overflow-hidden">
                                        <div class="h-full w-[72%] bg-gradient-to-r from-emerald-400 to-cyan-400 rounded-full"></div>
                                    </div>
                                    <span class="text-xs font-bold text-slate-500">Target: 14%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 4. Solusi (Solution) --}}
    <section class="py-24 lg:py-32 relative overflow-hidden bg-white">
        {{-- Subtle background grid --}}
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:32px_32px] pointer-events-none"></div>
        {{-- Soft glow blobs --}}
        <div class="absolute top-0 left-1/4 w-[600px] h-[300px] bg-emerald-100/60 rounded-full blur-[120px] pointer-events-none"></div>
        <div class="absolute bottom-0 right-1/4 w-[400px] h-[250px] bg-cyan-100/60 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="text-center mb-20" data-aos="fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-50 border border-emerald-200 rounded-full text-emerald-700 text-xs font-bold uppercase tracking-widest mb-6">
                    <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                    Solusi NutriGen
                </div>
                <h3 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-[1.1] mb-6 max-w-3xl mx-auto">
                    Satu Sistem. Data Real-Time.<br class="hidden sm:block"> Eksekusi Tepat Sasaran.
                </h3>
                <p class="text-lg text-slate-500 font-medium leading-relaxed max-w-2xl mx-auto">
                    NutriGen bukan sekadar aplikasi pencatat. Ini adalah ekosistem cerdas yang memutus birokrasi data, mengedukasi ibu secara proaktif, dan memberi tenaga medis "mata" ke setiap desa secara instan.
                </p>
            </div>

            {{-- Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8">

                {{-- Card 1: 100% Paperless — Emerald --}}
                <div class="relative group rounded-[28px] overflow-hidden" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-[28px] p-6 sm:p-8 h-full shadow-[0_8px_30px_rgba(16,185,129,0.25)] hover:shadow-[0_20px_50px_rgba(16,185,129,0.35)] hover:-translate-y-2 transition-all duration-400">
                        <div class="text-5xl sm:text-6xl md:text-7xl font-extrabold text-white/15 leading-none mb-4 select-none">01</div>
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-white mb-3 tracking-tight">100% Paperless</h4>
                        <p class="text-white/75 font-medium text-sm leading-relaxed">Buku KIA bertransformasi menjadi dashboard personal di saku tiap Ibu. Aman dan selalu dapat diakses.</p>
                        <div class="mt-6 flex items-center gap-2 text-emerald-100 font-semibold text-xs">
                            <span class="w-6 h-0.5 bg-white/50 rounded-full"></span>
                            <span>Zero kertas</span>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Real-Time — Cyan/Blue --}}
                <div class="relative group rounded-[28px] overflow-hidden" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-gradient-to-br from-cyan-500 to-blue-600 rounded-[28px] p-6 sm:p-8 h-full shadow-[0_8px_30px_rgba(6,182,212,0.25)] hover:shadow-[0_20px_50px_rgba(6,182,212,0.35)] hover:-translate-y-2 transition-all duration-400">
                        <div class="text-5xl sm:text-6xl md:text-7xl font-extrabold text-white/15 leading-none mb-4 select-none">02</div>
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-white mb-3 tracking-tight">Sinkronisasi Real-Time</h4>
                        <p class="text-white/75 font-medium text-sm leading-relaxed">Keputusan intervensi stunting puskesmas diambil dari data bulan ini, bukan rekap tahun lalu.</p>
                        <div class="mt-6 flex items-center gap-2 text-cyan-100 font-semibold text-xs">
                            <span class="w-6 h-0.5 bg-white/50 rounded-full"></span>
                            <span>Detik, bukan bulan</span>
                        </div>
                    </div>
                </div>

                {{-- Card 3: Validasi — Amber --}}
                <div class="relative group rounded-[28px] overflow-hidden" data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-gradient-to-br from-amber-400 to-orange-500 rounded-[28px] p-6 sm:p-8 h-full shadow-[0_8px_30px_rgba(245,158,11,0.25)] hover:shadow-[0_20px_50px_rgba(245,158,11,0.35)] hover:-translate-y-2 transition-all duration-400">
                        <div class="text-5xl sm:text-6xl md:text-7xl font-extrabold text-white/15 leading-none mb-4 select-none">03</div>
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-5 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h4 class="text-xl font-bold text-white mb-3 tracking-tight">Validasi Berlapis</h4>
                        <p class="text-white/75 font-medium text-sm leading-relaxed">Algoritma otomatis mendeteksi anomali penimbangan dan menugaskan petugas gizi untuk verifikasi.</p>
                        <div class="mt-6 flex items-center gap-2 text-amber-100 font-semibold text-xs">
                            <span class="w-6 h-0.5 bg-white/50 rounded-full"></span>
                            <span>AI-powered detection</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>
        {{-- Background texture --}}
        <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.03)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.03)_1px,transparent_1px)] bg-[size:32px_32px]"></div>
        {{-- Glow orbs --}}
        <div class="absolute top-0 left-1/4 w-[500px] h-[300px] bg-emerald-500/10 rounded-full blur-[100px] pointer-events-none"></div>

    {{-- 5. Workflow (Cara Kerja) --}}
    <section id="how-it-works" class="py-24 lg:py-32 bg-white relative overflow-hidden border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20" data-aos="fade-up">
                <h2 class="text-emerald-600 font-extrabold text-sm uppercase tracking-widest mb-4">Cara Kerja</h2>
                <h3 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight mb-4">Kecepatan Menyelamatkan Generasi</h3>
                <p class="text-lg text-slate-500 font-medium max-w-2xl mx-auto leading-relaxed">Data bergerak dari Posyandu ke Ahli Gizi dalam hitungan detik, bukan bulan.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-10 lg:gap-12 relative">
                {{-- Desktop connecting line --}}
                <div class="hidden md:block absolute top-[3.5rem] left-[20%] right-[20%] h-0.5 bg-gradient-to-r from-emerald-200 via-emerald-400 to-emerald-200 z-0 rounded-full"></div>

                <div class="relative z-10 text-center group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-28 h-28 mx-auto bg-emerald-50 border-2 border-emerald-100 rounded-3xl flex items-center justify-center mb-6 group-hover:bg-emerald-100 group-hover:border-emerald-300 group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-emerald-100 transition-all duration-300">
                        <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19l7-7 3 3-7 7-3-3z"/><path d="M18 13l-1.5-7.5L2 2l3.5 14.5L13 18l5-5z"/><path d="M2 2l7.586 7.586"/><circle cx="11" cy="11" r="2"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900 tracking-tight">1. Input Kader</h3>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed max-w-[260px] mx-auto">Kader Posyandu memasukkan data ukur balita melalui Web App ringan dari smartphone saat di lokasi.</p>
                </div>

                <div class="relative z-10 text-center group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-28 h-28 mx-auto bg-white border-2 border-emerald-500 rounded-3xl flex items-center justify-center mb-6 shadow-[0_0_0_8px_rgba(16,185,129,0.08)] group-hover:scale-110 group-hover:shadow-[0_0_0_12px_rgba(16,185,129,0.1)] transition-all duration-300">
                        <svg class="w-10 h-10 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><path d="M3 22h18"/><path d="M6 18H4c-.6 0-1-.4-1-1V5c0-.6.4-1 1-1h4c.6 0 1 .4 1 1v13"/><path d="M14 18h-2V7c0-.6.4-1 1-1h4c.6 0 1 .4 1 1v10c0 .6-.4 1-1 1h-2"/><path d="M10 22V8c0-.6.4-1 1-1h2c.6 0 1 .4 1 1v14"/><path d="M10 12h4"/><path d="M12 10v4"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900 tracking-tight">2. Validasi Klinis</h3>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed max-w-[260px] mx-auto">Sistem mendeteksi anomali stunting dan memasukannya ke antrean validasi Ahli Gizi Puskesmas.</p>
                </div>

                <div class="relative z-10 text-center group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-28 h-28 mx-auto bg-cyan-50 border-2 border-cyan-100 rounded-3xl flex items-center justify-center mb-6 group-hover:bg-cyan-100 group-hover:border-cyan-300 group-hover:scale-110 group-hover:shadow-lg group-hover:shadow-cyan-100 transition-all duration-300">
                        <svg class="w-10 h-10 text-cyan-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round"><rect width="14" height="20" x="5" y="2" rx="2" ry="2"/><path d="M12 18h.01"/></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-3 text-slate-900 tracking-tight">3. Notifikasi Ibu</h3>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed max-w-[260px] mx-auto">Ibu menerima ringkasan kurva & rekomendasi gizi instan melalui WhatsApp secara privat.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- 6 & 7. Ekosistem & Feature Bento Grid --}}
    <section id="features" class="py-24 lg:py-32 bg-slate-50 border-y border-slate-100 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="mb-16" data-aos="fade-up">
                <h2 class="text-emerald-600 font-extrabold text-sm uppercase tracking-widest mb-4">Ekosistem Sinergis</h2>
                <h3 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-[1.1] max-w-3xl">
                    Tiga Aktor, Satu Sumber Kebenaran.
                </h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-6 md:grid-rows-2 gap-6 auto-rows-fr">

                {{-- BENTO 1: Ibu (Large - Span 4) --}}
                <div class="md:col-span-4 md:row-span-1 bg-white rounded-[24px] p-8 lg:p-10 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-slate-100 hover:border-pink-200 hover:shadow-[0_12px_40px_rgba(236,72,153,0.07)] hover:-translate-y-1 transition-all duration-300 flex flex-col justify-center group relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-48 h-48 bg-pink-50/60 rounded-full -translate-y-1/2 translate-x-1/4 blur-2xl pointer-events-none group-hover:opacity-100 opacity-60 transition-opacity"></div>
                    <div class="relative z-10" data-aos="fade-up">
                        <div class="w-12 h-12 bg-pink-50 text-pink-500 rounded-xl flex items-center justify-center mb-6 ring-1 ring-pink-100 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h4 class="text-2xl font-bold text-slate-900 mb-3 tracking-tight">Portal Ibu (B2C)</h4>
                        <p class="text-slate-500 font-medium leading-relaxed max-w-lg">Tidak perlu *install* aplikasi. Ibu cukup klik tautan WhatsApp untuk melihat kurva pertumbuhan standar WHO, evaluasi status gizi, dan rekomendasi menu resep harian.</p>
                    </div>
                </div>

                {{-- BENTO 2: AI (Small - Span 2) — Replaced black with deep emerald --}}
                <div class="md:col-span-2 md:row-span-1 bg-gradient-to-br from-emerald-700 via-emerald-600 to-cyan-600 rounded-[24px] p-8 lg:p-10 shadow-[0_15px_40px_rgba(16,185,129,0.2)] hover:shadow-[0_24px_60px_rgba(16,185,129,0.3)] border border-emerald-500/20 text-white flex flex-col justify-between group relative overflow-hidden transition-all duration-500 hover:-translate-y-1">
                    <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:14px_14px] opacity-40"></div>
                    <div class="absolute -top-10 -right-10 w-32 h-32 bg-white/20 rounded-full blur-3xl opacity-50 group-hover:opacity-70 transition-opacity duration-500"></div>
                    <div class="relative z-10 h-full flex flex-col" data-aos="fade-up" data-aos-delay="100">
                        <div class="w-12 h-12 bg-white/20 backdrop-blur-md border border-white/30 rounded-xl flex items-center justify-center mb-6 group-hover:-translate-y-1 group-hover:shadow-lg transition-all duration-300">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <div class="mt-auto">
                            <h4 class="text-2xl font-bold text-white mb-3 tracking-tight">AI Nutrition</h4>
                            <p class="text-white/75 font-medium text-sm leading-relaxed">Resep dikurasi otomatis berdasarkan status gizi aktual balita.</p>
                        </div>
                    </div>
                </div>

                {{-- BENTO 3: Puskesmas (Small - Span 2) --}}
                <div class="md:col-span-2 md:row-span-1 bg-white rounded-[24px] p-8 lg:p-10 border border-blue-100 flex flex-col justify-between group shadow-[0_4px_20px_rgba(59,130,246,0.05)] hover:border-blue-200 hover:shadow-[0_12px_40px_rgba(59,130,246,0.1)] hover:-translate-y-1 transition-all duration-300">
                    <div class="h-full flex flex-col" data-aos="fade-up" data-aos-delay="200">
                        <div class="w-12 h-12 bg-blue-50 text-blue-600 border border-blue-100 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <div class="mt-auto">
                            <h4 class="text-xl font-bold text-slate-900 mb-3 tracking-tight">Portal Puskesmas</h4>
                            <p class="text-slate-500 font-medium text-sm leading-relaxed">Dashboard agregat untuk validasi klinis tingkat kecamatan.</p>
                        </div>
                    </div>
                </div>

                {{-- BENTO 4: Kader (Large - Span 4) --}}
                <div class="md:col-span-4 md:row-span-1 bg-white rounded-[24px] p-8 lg:p-10 shadow-[0_4px_20px_rgba(0,0,0,0.03)] border border-amber-100 hover:border-amber-200 hover:shadow-[0_12px_40px_rgba(245,158,11,0.08)] hover:-translate-y-1 transition-all duration-300 flex flex-col justify-center group relative overflow-hidden">
                    <div class="absolute bottom-0 right-0 w-40 h-40 bg-amber-50/80 rounded-full translate-y-1/2 translate-x-1/4 blur-2xl pointer-events-none"></div>
                    <div class="relative z-10" data-aos="fade-up" data-aos-delay="300">
                        <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center mb-6 ring-1 ring-amber-100 group-hover:scale-110 transition-transform duration-300">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <h4 class="text-2xl font-bold text-slate-900 mb-3 tracking-tight">Portal Kader Posyandu</h4>
                        <p class="text-slate-500 font-medium leading-relaxed max-w-lg">Form input digital cerdas yang menggantikan buku tulis. Validasi Z-Score bawaan mencegah kesalahan input data antropometri sebelum dikirim ke server pusat.</p>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- 8. Demo Video Section — Smaller, more focused --}}
    <section id="video-demo" class="py-20 lg:py-28 bg-white relative">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-emerald-600 font-extrabold text-sm uppercase tracking-widest mb-4">Demo</h2>
                <h3 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight mb-3">Lihat Bagaimana NutriGen Bekerja</h3>
                <p class="text-base text-slate-500 font-medium max-w-xl mx-auto leading-relaxed">Demo eksklusif alur kerja NutriGen dari posyandu ke puskesmas dalam 1 Menit.</p>
            </div>

            <div class="relative max-w-3xl mx-auto" data-aos="fade-up" data-aos-delay="150">
                <div class="absolute -inset-3 bg-gradient-to-br from-emerald-400/15 to-cyan-400/15 rounded-[2rem] blur-xl opacity-70 pointer-events-none"></div>
                <div class="relative rounded-[20px] border border-slate-200 bg-white p-2 shadow-[0_0_0_1px_rgba(0,0,0,0.04),0_16px_40px_-8px_rgba(0,0,0,0.12)] overflow-hidden">
                    <div class="flex items-center gap-1.5 px-3 py-2.5 bg-slate-50 rounded-t-[16px] border-b border-slate-100">
                        <div class="flex gap-1.5">
                            <div class="w-2.5 h-2.5 rounded-full bg-rose-400"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-amber-400"></div>
                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-400"></div>
                        </div>
                    </div>
                    <div class="aspect-video bg-slate-900 rounded-b-[16px] relative overflow-hidden flex items-center justify-center">
                        <iframe 
                            class="absolute top-0 left-0 w-full h-full"
                            src="https://www.youtube.com/embed/99Radiqy15c" 
                            title="YouTube video player" 
                            frameborder="0" 
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
                            referrerpolicy="strict-origin-when-cross-origin" 
                            allowfullscreen>
                        </iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 9. FAQ --}}
    <section class="py-24 lg:py-32 relative overflow-hidden bg-gradient-to-br from-emerald-700 via-emerald-600 to-cyan-600">
        {{-- Grid overlay --}}
        <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.06)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.06)_1px,transparent_1px)] bg-[size:20px_20px] pointer-events-none"></div>
        {{-- Glow orbs --}}
        <div class="absolute -top-40 -right-40 w-96 h-96 bg-white/10 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute -bottom-40 -left-40 w-96 h-96 bg-cyan-300/20 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-6 lg:px-8 relative z-10">
            {{-- Header --}}
            <div class="text-center mb-14" data-aos="fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/15 backdrop-blur-sm border border-white/25 rounded-full text-white/90 text-xs font-bold uppercase tracking-widest mb-6">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    FAQ
                </div>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-white tracking-tight mb-3">Pertanyaan yang Sering Ditanyakan</h2>
                <p class="text-white/70 font-medium max-w-lg mx-auto leading-relaxed">Semua yang perlu Anda ketahui tentang NutriGen dan cara kerjanya.</p>
            </div>

            {{-- FAQ Items --}}
            <div class="space-y-3">
                <div x-data="{ open: false }" class="group" data-aos="fade-up" data-aos-delay="100">
                    <div class="bg-white/12 backdrop-blur-md border border-white/20 rounded-2xl overflow-hidden transition-all duration-300 hover:bg-white/18 hover:border-white/35" :class="open ? 'bg-white/18 border-white/35' : ''">
                        <button @click="open = !open" class="w-full px-6 py-5 text-left flex items-center gap-4 focus:outline-none">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 transition-all duration-300" :class="open ? 'bg-white text-emerald-600' : 'bg-white/20 text-white'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                            </div>
                            <span class="font-bold text-white text-base flex-1">Apakah Ibu harus mendownload aplikasi NutriGen?</span>
                            <div class="w-8 h-8 rounded-xl bg-white/15 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-white/25">
                                <svg class="w-4 h-4 text-white transition-transform duration-300" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </button>
                        <div x-show="open" x-collapse>
                            <div class="px-6 pb-5 pl-[4.25rem] text-white/80 font-medium leading-relaxed text-sm border-t border-white/15 pt-4">
                                Tidak perlu. NutriGen menggunakan sistem <strong class="text-white font-bold">Magic Link</strong> yang dikirim melalui WhatsApp Bot secara berkala. Ibu cukup klik tautan tersebut untuk membuka Portal Ibu di <em>browser</em> HP dengan lancar — tanpa perlu install apapun.
                            </div>
                        </div>
                    </div>
                </div>

                <div x-data="{ open: false }" class="group" data-aos="fade-up" data-aos-delay="150">
                    <div class="bg-white/12 backdrop-blur-md border border-white/20 rounded-2xl overflow-hidden transition-all duration-300 hover:bg-white/18 hover:border-white/35" :class="open ? 'bg-white/18 border-white/35' : ''">
                        <button @click="open = !open" class="w-full px-6 py-5 text-left flex items-center gap-4 focus:outline-none">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 transition-all duration-300" :class="open ? 'bg-white text-emerald-600' : 'bg-white/20 text-white'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" /></svg>
                            </div>
                            <span class="font-bold text-white text-base flex-1">Bagaimana cara mendaftar?</span>
                            <div class="w-8 h-8 rounded-xl bg-white/15 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-white/25">
                                <svg class="w-4 h-4 text-white transition-transform duration-300" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </button>
                        <div x-show="open" x-collapse>
                            <div class="px-6 pb-5 pl-[4.25rem] text-white/80 font-medium leading-relaxed text-sm border-t border-white/15 pt-4">
                                Registrasi publik ditutup untuk menjaga integritas data medis. Akun Ibu didaftarkan oleh <strong class="text-white font-bold">Kader Posyandu</strong> saat kunjungan pertama, sedangkan akun Kader dan Puskesmas dikelola oleh Administrator Dinas Kesehatan.
                            </div>
                        </div>
                    </div>
                </div>

                <div x-data="{ open: false }" class="group" data-aos="fade-up" data-aos-delay="200">
                    <div class="bg-white/12 backdrop-blur-md border border-white/20 rounded-2xl overflow-hidden transition-all duration-300 hover:bg-white/18 hover:border-white/35" :class="open ? 'bg-white/18 border-white/35' : ''">
                        <button @click="open = !open" class="w-full px-6 py-5 text-left flex items-center gap-4 focus:outline-none">
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 transition-all duration-300" :class="open ? 'bg-white text-emerald-600' : 'bg-white/20 text-white'">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                            </div>
                            <span class="font-bold text-white text-base flex-1">Apakah standar pengukuran sudah sesuai WHO?</span>
                            <div class="w-8 h-8 rounded-xl bg-white/15 flex items-center justify-center shrink-0 transition-all duration-300 group-hover:bg-white/25">
                                <svg class="w-4 h-4 text-white transition-transform duration-300" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                            </div>
                        </button>
                        <div x-show="open" x-collapse>
                            <div class="px-6 pb-5 pl-[4.25rem] text-white/80 font-medium leading-relaxed text-sm border-t border-white/15 pt-4">
                                Ya, sistem <em>backend</em> kami mengimplementasikan standar <strong class="text-white font-bold">Z-Score WHO 2006</strong> untuk menghitung persentil pertumbuhan tinggi dan berat badan balita secara instan dan akurat.
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- 10. About NutriGen --}}
    <section class="relative overflow-hidden" data-aos="fade-up">
        <div class="grid grid-cols-1 lg:grid-cols-2 min-h-[480px]">
            {{-- Left: Vibrant emerald panel --}}
            <div class="relative bg-gradient-to-br from-emerald-700 to-emerald-500 flex flex-col justify-center px-10 lg:px-16 py-20 overflow-hidden">
                <div class="absolute -top-20 -left-20 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute -bottom-20 -right-20 w-64 h-64 bg-cyan-400/20 rounded-full blur-3xl pointer-events-none"></div>
                <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.04)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.04)_1px,transparent_1px)] bg-[size:20px_20px]"></div>

                <div class="relative z-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-white/20 border border-white/30 rounded-full text-white text-xs font-bold uppercase tracking-widest mb-8">
                        <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span>
                        Misi Utama NutriGen
                    </div>
                    <h3 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-white tracking-tight leading-[1.1] mb-6">
                        Generasi<br><span class="text-cyan-200">Bebas Stunting.</span>
                    </h3>
                    <p class="text-white/80 font-medium leading-relaxed text-base max-w-md">
                        NutriGen dibangun dengan satu keyakinan sederhana: data yang akurat dan intervensi yang cepat dapat menyelamatkan masa depan seorang anak.
                    </p>
                </div>
            </div>

            {{-- Right: Light panel with stats --}}
            <div class="bg-slate-50 flex flex-col justify-center px-10 lg:px-16 py-20 relative overflow-hidden">
                <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:24px_24px]"></div>
                <div class="relative z-10">
                    <p class="text-slate-600 font-medium leading-relaxed text-base mb-10">
                        Kami menggabungkan teknologi modern dengan infrastruktur kesehatan masyarakat yang ada, <strong class="text-emerald-700 font-bold">memberdayakan Kader</strong>, memudahkan Puskesmas, dan mengedukasi Ibu secara simultan.
                    </p>

                    {{-- Mini Stats --}}
                    <div class="grid grid-cols-3 gap-2 sm:gap-4">
                        <div class="bg-white rounded-2xl p-3 sm:p-5 border border-emerald-100 shadow-sm text-center">
                            <div class="text-xl sm:text-2xl font-extrabold text-emerald-600 mb-1">3</div>
                            <div class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wide">Portal Pengguna</div>
                        </div>
                        <div class="bg-white rounded-2xl p-3 sm:p-5 border border-blue-100 shadow-sm text-center">
                            <div class="text-xl sm:text-2xl font-extrabold text-blue-600 mb-1">WHO</div>
                            <div class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wide">Standar 2006</div>
                        </div>
                        <div class="bg-white rounded-2xl p-3 sm:p-5 border border-amber-100 shadow-sm text-center">
                            <div class="text-xl sm:text-2xl font-extrabold text-amber-600 mb-1">100%</div>
                            <div class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wide">Paperless</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Final CTA --}}
    <section class="relative py-24 sm:py-32 dark-mesh overflow-hidden border-t border-slate-900">
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="fade-up">
            <h2 class="text-4xl lg:text-5xl font-extrabold text-white tracking-tight mb-6">Siap Merubah Masa Depan?</h2>
            <p class="text-xl text-slate-400 font-medium mb-12 max-w-2xl mx-auto">Bergabunglah dalam revolusi digital penanggulangan stunting di Indonesia.</p>

            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-8 py-4 bg-emerald-500 hover:bg-emerald-400 text-white font-bold rounded-2xl shadow-lg shadow-emerald-500/20 transition-all hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-emerald-500/50">Buka Dashboard Saya</a>
                @else
                    <a href="{{ route('login') }}" class="w-full sm:w-auto px-8 py-4 bg-emerald-500 hover:bg-emerald-400 text-white font-bold rounded-2xl shadow-lg shadow-emerald-500/20 transition-all hover:-translate-y-1 focus:outline-none focus:ring-4 focus:ring-emerald-500/50">Masuk ke Sistem</a>
                @endauth
            </div>
        </div>
    </section>

    {{-- Team Section --}}
    <section class="py-24 lg:py-32 bg-slate-50 relative overflow-hidden border-t border-slate-200" data-aos="fade-up">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:32px_32px]"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-50 rounded-full translate-x-1/2 -translate-y-1/2 blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-cyan-50 rounded-full -translate-x-1/2 translate-y-1/2 blur-[100px] pointer-events-none"></div>

        <div class="max-w-3xl mx-auto px-6 lg:px-8 relative z-10 text-center">
            {{-- Top accent --}}
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-50 border border-emerald-200 rounded-full text-emerald-700 text-xs font-bold uppercase tracking-widest mb-8">
                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                Tim Pengembang
            </div>

            <div class="relative bg-white p-10 lg:p-14 rounded-[3rem] border border-slate-200 shadow-[0_15px_50px_rgba(0,0,0,0.04)] w-full overflow-hidden group">
                {{-- Subtle top line on hover --}}
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[300px] h-1 bg-gradient-to-r from-transparent via-emerald-400 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>

                <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight mb-5">
                    Built by <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-cyan-600">Student Innovators</span>
                </h2>

                <p class="text-lg text-slate-500 font-medium leading-relaxed max-w-2xl mx-auto mb-3">
                    NutriGen dikembangkan oleh mahasiswa lintas universitas yang berkolaborasi dalam Hackathon Digdaya 2026 untuk menghadirkan solusi digital penanganan stunting berbasis Posyandu.
                </p>

                {{-- University badges --}}
                <div class="flex flex-wrap items-center justify-center gap-3 mb-10">
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-full text-xs font-semibold text-slate-600">
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                        Universitas Syiah Kuala
                    </div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 bg-slate-50 border border-slate-200 rounded-full text-xs font-semibold text-slate-600">
                        <div class="w-2 h-2 rounded-full bg-cyan-500"></div>
                        UIN Ar-Raniry Banda Aceh
                    </div>
                </div>

                {{-- Avatars --}}
                <div class="flex items-center justify-center -space-x-4 mb-10">
                    <div class="w-14 h-14 rounded-full border-4 border-white overflow-hidden z-40 relative shadow-md hover:-translate-y-2 hover:scale-110 transition-all duration-300 cursor-pointer">
                        <img src="{{ asset('images/team/member-1.png') }}" alt="Team Member 1" class="w-full h-full object-cover">
                    </div>
                    <div class="w-14 h-14 rounded-full border-4 border-white overflow-hidden z-30 relative shadow-md hover:-translate-y-2 hover:scale-110 transition-all duration-300 cursor-pointer">
                        <img src="{{ asset('images/team/member-2.jpeg') }}" alt="Team Member 2" class="w-full h-full object-cover">
                    </div>
                    <div class="w-14 h-14 rounded-full border-4 border-white overflow-hidden z-20 relative shadow-md hover:-translate-y-2 hover:scale-110 transition-all duration-300 cursor-pointer">
                        <img src="{{ asset('images/team/member-3.jpeg') }}" alt="Team Member 3" class="w-full h-full object-cover">
                    </div>
                    <div class="w-14 h-14 rounded-full border-4 border-white overflow-hidden z-10 relative shadow-md hover:-translate-y-2 hover:scale-110 transition-all duration-300 cursor-pointer">
                        <img src="{{ asset('images/team/member-4.jpeg') }}" alt="Team Member 4" class="w-full h-full object-cover">
                    </div>
                </div>

                <a href="{{ route('team') }}" class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-slate-900 text-white font-bold rounded-full shadow-[0_4px_15px_rgba(15,23,42,0.2)] hover:shadow-[0_8px_25px_rgba(16,185,129,0.3)] hover:bg-emerald-600 transition-all duration-500 hover:-translate-y-1 active:scale-95 group/btn">
                    <span>Kenali Tim Kami Lebih Dekat</span>
                    <svg class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </a>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <x-public-footer description="Platform manajemen stunting end-to-end yang mengintegrasikan data dari Posyandu ke Puskesmas secara real-time. Membangun generasi emas Indonesia.">

        <x-slot name="badges">
            <div class="inline-flex items-center gap-2.5 px-4 py-2 bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 text-sm font-semibold text-slate-300 hover:border-slate-700 transition-colors">
                <svg class="w-5 h-5 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 15s1-1 4-1 5 2 8 2 4-1 4-1V3s-1 1-4 1-5-2-8-2-4 1-4 1z"/><line x1="4" x2="4" y1="22" y2="15"/></svg>
                <span>Built in Indonesia</span>
            </div>
            <div class="inline-flex items-center gap-2.5 px-4 py-2 bg-slate-900/80 backdrop-blur-md rounded-2xl border border-slate-800 text-sm font-semibold text-slate-300 hover:border-slate-700 transition-colors">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_10px_rgba(16,185,129,0.8)]"></span>
                <span>Digdaya 2026</span>
            </div>
            <div class="inline-flex items-center gap-2.5 px-4 py-2 bg-emerald-950/30 backdrop-blur-md rounded-2xl border border-emerald-900/50 text-sm font-semibold text-emerald-400 hover:border-emerald-800/50 transition-colors shadow-[0_0_15px_rgba(16,185,129,0.1)]">
                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                <span>v1.0 MVP</span>
            </div>
        </x-slot>

        <x-slot name="platformLinks">
            <li><a href="#how-it-works" class="text-base font-medium text-slate-400 hover:text-emerald-400 transition-all duration-300 flex items-center gap-3 group hover:translate-x-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-emerald-400 transition-colors duration-300"></span> Cara Kerja</a></li>
            <li><a href="#features" class="text-base font-medium text-slate-400 hover:text-emerald-400 transition-all duration-300 flex items-center gap-3 group hover:translate-x-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-emerald-400 transition-colors duration-300"></span> Ekosistem NutriGen</a></li>
            <li><a href="{{ route('team') }}" class="text-base font-medium text-emerald-500 hover:text-emerald-400 transition-all duration-300 flex items-center gap-3 group hover:translate-x-2"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 group-hover:bg-emerald-400 transition-colors duration-300 shadow-[0_0_10px_rgba(16,185,129,0.5)]"></span> Meet Our Team</a></li>
            <li><a href="{{ route('login') }}" class="text-base font-medium text-slate-400 hover:text-emerald-400 transition-all duration-300 flex items-center gap-3 group hover:translate-x-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-emerald-400 transition-colors duration-300"></span> Portal Petugas</a></li>
        </x-slot>

        <x-slot name="contactLinks">
            <li>
                <a href="mailto:teamnutrigen@gmail.com" class="text-base font-medium text-slate-400 hover:text-emerald-400 transition-all duration-300 flex items-center gap-4 group hover:translate-x-2">
                    <div class="w-10 h-10 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center group-hover:border-emerald-500/50 group-hover:bg-emerald-950/30 transition-all duration-300">
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                    </div>
                    teamnutrigen@gmail.com
                </a>
            </li>
            <li>
                <a href="#" class="text-base font-medium text-slate-400 hover:text-emerald-400 transition-all duration-300 flex items-center gap-4 group hover:translate-x-2">
                    <div class="w-10 h-10 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center group-hover:border-emerald-500/50 group-hover:bg-emerald-950/30 transition-all duration-300">
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" /></svg>
                    </div>
                    WhatsApp Support
                </a>
            </li>
            <li>
                <a href="#" class="text-base font-medium text-slate-400 hover:text-emerald-400 transition-all duration-300 flex items-center gap-4 group hover:translate-x-2">
                    <div class="w-10 h-10 rounded-full bg-slate-900 border border-slate-800 flex items-center justify-center group-hover:border-emerald-500/50 group-hover:bg-emerald-950/30 transition-all duration-300">
                        <svg class="w-4 h-4 text-slate-500 group-hover:text-emerald-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                    </div>
                    Banda Aceh, Indonesia
                </a>
            </li>
        </x-slot>

        <x-slot name="copyright">
            <span class="text-slate-300 font-bold tracking-wide">NutriGen MVP</span> &bull; Hackathon Digdaya 2026 &bull; Version 1.0 &bull; <span class="text-emerald-400">2026</span>
        </x-slot>

    </x-public-footer>

@endsection
