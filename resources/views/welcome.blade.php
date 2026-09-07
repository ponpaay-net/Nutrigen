@extends('layouts.public')

@section('title', 'NutriGen | Platform Monitoring Gizi Balita')

@push('head')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">
<style>
    body, .nutrigen-page { font-family: 'Plus Jakarta Sans', sans-serif; }

    /* Elegant Gradients & Shapes */
    .hero-gradient-blob { background: radial-gradient(circle at 70% 30%, rgba(16,185,129,0.08) 0%, rgba(6,182,212,0.04) 50%, transparent 70%); }
    .hero-shape-right {
        background: linear-gradient(135deg, #10b981 0%, #0d9488 50%, #06b6d4 100%);
        border-radius: 50% 0 0 50% / 50% 0 0 50%;
    }

    /* Pro SaaS Card Hover with Multi-layered Shadows */
    .card-saas {
        transition: all 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.03), 0 2px 4px -2px rgba(0, 0, 0, 0.03);
    }
    .card-saas:hover {
        transform: translateY(-4px);
        box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.1), 0 8px 10px -6px rgba(16, 185, 129, 0.05);
        border-color: rgba(16, 185, 129, 0.2);
    }

    .gradient-text { background: linear-gradient(135deg, #10b981, #0d9488, #06b6d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text; }
    
    /* Sleek Button */
    .btn-primary-saas {
        background: linear-gradient(135deg, #10b981, #06b6d4);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        box-shadow: 0 4px 14px 0 rgba(16, 185, 129, 0.3);
    }
    .btn-primary-saas:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
    }

    .eyebrow-badge { background: rgba(16, 185, 129, 0.05); backdrop-filter: blur(10px); }

    /* Icon Wraps */
    .icon-bg-1 { background: linear-gradient(135deg, #d1fae5, #ecfdf5); }
    .icon-bg-2 { background: linear-gradient(135deg, #fef3c7, #fffbeb); }
    .icon-bg-3 { background: linear-gradient(135deg, #dbeafe, #eff6ff); }
    .icon-bg-4 { background: linear-gradient(135deg, #fce7f3, #fdf2f8); }

    .about-img-card { background: linear-gradient(135deg, #10b981, #06b6d4); }
    
    /* Floating Animations */
    @keyframes floatY { 0%, 100% { transform: translateY(0px); } 50% { transform: translateY(-8px); } }
    .float-anim { animation: floatY 6s ease-in-out infinite; }
    .float-anim-2 { animation: floatY 7s ease-in-out infinite; animation-delay: -2s; }

</style>
@endpush

@section('content')
<div class="nutrigen-page bg-white text-slate-800 overflow-x-hidden selection:bg-emerald-200 selection:text-emerald-900">

    {{-- =============================================
         SECTION 1: HERO
    ============================================== --}}
    <section class="relative min-h-[90vh] flex items-center pt-24 pb-16 overflow-hidden hero-gradient-blob">
        <div class="absolute right-0 top-0 bottom-0 w-[42%] hero-shape-right opacity-[0.85] -z-0 hidden lg:block"></div>
        <div class="absolute inset-0 bg-[radial-gradient(#94a3b8_1px,transparent_1px)] [background-size:24px_24px] opacity-10 -z-10"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 w-full grid grid-cols-1 lg:grid-cols-12 gap-12 items-center relative z-10">

            {{-- Left: Text --}}
            <div class="lg:col-span-7" x-data="{shown:false}" x-intersect="shown=true">
                <div x-show="shown" x-transition:enter="transition duration-700 delay-100" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0"
                     class="inline-flex items-center gap-2 eyebrow-badge border border-emerald-100 text-emerald-700 font-semibold text-xs px-4 py-2 rounded-full mb-6 tracking-wide">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    Sistem Pemantauan Terintegrasi v2.0
                </div>

                <h1 x-show="shown" x-transition:enter="transition duration-700 delay-200" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                    class="text-4xl sm:text-5xl lg:text-6xl font-extrabold leading-[1.15] tracking-tight text-slate-800 mb-6">
                    Digitalisasi Posyandu.<br>
                    <span class="gradient-text">Deteksi Presisi dalam Detik</span>
                </h1>

                <p x-show="shown" x-transition:enter="transition duration-700 delay-300" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                   class="text-lg text-slate-500 leading-relaxed mb-10 max-w-xl font-medium">
                    Pendamping pintar pelengkap Buku KIA. Hubungkan Dinas Kesehatan, Puskesmas, Kader, dan Orang Tua dalam satu ekosistem cerdas dengan perhitungan Z-Score WHO 2006 otomatis dan validasi klinis berjenjang.
                </p>

                <div x-show="shown" x-transition:enter="transition duration-700 delay-400" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0"
                     class="flex flex-col sm:flex-row items-start sm:items-center gap-4">
                    <a href="{{ route('login') }}" class="btn-primary-saas text-white font-bold px-8 py-4 rounded-xl text-base inline-flex items-center gap-2.5">
                        Mulai Sekarang
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                    <a href="#features" class="inline-flex items-center gap-2 text-slate-600 font-semibold px-6 py-4 rounded-xl hover:bg-slate-50 transition-colors duration-300 text-base group">
                        Lihat Fitur
                        <svg class="w-4 h-4 text-slate-400 group-hover:translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    </a>
                </div>
            </div>

            {{-- Right: Illustration Cards --}}
            <div class="lg:col-span-5 relative hidden lg:flex flex-col justify-center h-[500px] z-10 w-full pl-8">
                {{-- Main dashboard card --}}
                <div class="float-anim bg-white/95 backdrop-blur-md rounded-2xl p-6 w-full max-w-[380px] shadow-[0_20px_40px_-15px_rgba(16,185,129,0.15)] border border-white/40 ml-auto mr-12 relative z-20">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest mb-1">Status Validasi</p>
                            <p class="text-3xl font-black text-slate-800">247 <span class="text-sm font-medium text-slate-500">Data</span></p>
                        </div>
                        <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                    </div>
                    {{-- Mini bar chart --}}
                    <div class="flex items-end gap-2 h-16 mb-4">
                        @foreach([30, 55, 40, 75, 45, 85, 60, 95] as $h)
                        <div class="flex-1 rounded-sm bg-gradient-to-t from-emerald-500 to-teal-400 opacity-{{ $h > 70 ? '100' : '40' }} transition-all hover:opacity-100 cursor-pointer" style="height: {{ $h }}%;"></div>
                        @endforeach
                    </div>
                    <div class="h-1.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="h-full bg-emerald-500 rounded-full w-3/4"></div>
                    </div>
                </div>

                {{-- Floating badge: Super Admin --}}
                <div class="float-anim-2 absolute top-12 -left-4 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl shadow-blue-500/10 px-5 py-4 border border-white flex items-center gap-3 z-30">
                    <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center shadow-inner">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">Agregat Dinkes</p>
                        <p class="text-sm font-extrabold text-slate-800">Laporan Wilayah</p>
                    </div>
                </div>

                {{-- Floating badge: Alert --}}
                <div class="float-anim absolute -bottom-2 right-4 bg-white/95 backdrop-blur-md rounded-2xl shadow-xl shadow-rose-500/10 px-5 py-4 border border-white flex items-center gap-3 z-30">
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-rose-400 to-orange-400 flex items-center justify-center shadow">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-bold uppercase tracking-widest mb-0.5">Peringatan Klinis</p>
                        <p class="text-sm font-extrabold text-rose-600">3 Indikasi Stunting</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =============================================
         SECTION 2: SERVICES (4-column ecosystem)
    ============================================== --}}
    <section id="features" class="py-24 bg-white border-y border-slate-50">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16" x-data="{shown:false}" x-intersect="shown=true">
                <div x-show="shown" x-transition:enter="transition duration-600" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                     class="text-sm font-bold uppercase tracking-[0.15em] text-emerald-600 mb-3">Satu Platform, 4 Pilar</div>
                <h2 x-show="shown" x-transition:enter="transition duration-700 delay-100" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0"
                    class="text-3xl sm:text-4xl font-extrabold text-slate-800 leading-tight tracking-tight max-w-3xl mx-auto">
                    Alur Kerja yang Terstruktur & Teraudit
                </h2>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 relative">
                {{-- Decorative connecting line --}}
                <div class="absolute top-1/2 left-0 w-full h-[1px] bg-slate-100 -translate-y-1/2 hidden lg:block z-0"></div>

                @foreach([
                    ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z', 'role' => '1. Kader Posyandu', 'title' => 'Input Lapangan', 'desc' => 'Mencatat TB/BB balita di posyandu dengan sistem validasi eror otomatis.', 'color' => 'from-emerald-400 to-teal-500', 'bg' => 'icon-bg-1'],
                    ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'role' => '2. Puskesmas', 'title' => 'Validasi Klinis', 'desc' => 'Ahli gizi memverifikasi data yang memiliki indikasi gizi buruk/stunting.', 'color' => 'from-teal-400 to-cyan-500', 'bg' => 'icon-bg-3'],
                    ['icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'role' => '3. Dinas Kesehatan', 'title' => 'Manajemen Agregat', 'desc' => 'Memantau data seluruh puskesmas, mengekspor laporan PDF terformat.', 'color' => 'from-cyan-400 to-blue-500', 'bg' => 'icon-bg-2'],
                    ['icon' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'role' => '4. Orang Tua', 'title' => 'Edukasi B2C', 'desc' => 'Akses Magic Link via WA untuk melihat kurva pertumbuhan anak tanpa install aplikasi.', 'color' => 'from-indigo-400 to-purple-500', 'bg' => 'icon-bg-4'],
                ] as $i => $s)
                <div class="bg-white rounded-2xl p-6 border border-emerald-50 card-saas flex flex-col relative z-10" x-data="{shown:false}" x-intersect="shown=true">
                    <div x-show="shown" x-transition:enter="transition duration-700 delay-{{ $i * 100 }}" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">{{ $s['role'] }}</p>
                        <div class="w-14 h-14 {{ $s['bg'] }} rounded-xl flex items-center justify-center mb-6 border border-white">
                            <div class="w-8 h-8 bg-gradient-to-br {{ $s['color'] }} rounded-lg flex items-center justify-center shadow-sm">
                                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $s['icon'] }}"></path></svg>
                            </div>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800 mb-2">{{ $s['title'] }}</h3>
                        <p class="text-slate-500 leading-relaxed text-sm flex-grow mb-6">{{ $s['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =============================================
         SECTION 3: ABOUT / PROBLEM (Light Colors Only)
    ============================================== --}}
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:24px_24px] opacity-[0.03] -z-10"></div>
        <div class="max-w-7xl mx-auto px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-16 items-center">

            {{-- Left: Stacked image cards --}}
            <div class="relative flex justify-center items-center" x-data="{shown:false}" x-intersect="shown=true">
                <div x-show="shown" x-transition:enter="transition duration-900 delay-100" x-transition:enter-start="opacity-0 -translate-x-8" x-transition:enter-end="opacity-100 translate-x-0"
                     class="relative w-full max-w-sm">
                    {{-- Main card - Softened Gradient --}}
                    <div class="bg-gradient-to-br from-teal-400 to-emerald-500 rounded-3xl p-10 text-white shadow-[0_20px_50px_-12px_rgba(16,185,129,0.25)] relative z-20">
                        <div class="absolute top-0 right-0 p-6 opacity-20">
                            <svg class="w-24 h-24 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2zm0 4.2L18.8 19H5.2L12 6.2zM11 11v4h2v-4h-2zm0 5v2h2v-2h-2z"/></svg>
                        </div>
                        <div class="text-xs font-bold text-emerald-50 uppercase tracking-[0.2em] mb-4">Statistik SSGI</div>
                        <div class="text-7xl font-black mb-1 font-mono tracking-tighter text-white">19.8<span class="text-4xl font-bold text-teal-100">%</span></div>
                        <div class="text-base font-semibold text-emerald-50 mb-6 border-b border-emerald-300/50 pb-6">Angka Stunting Nasional</div>
                        <p class="text-sm text-emerald-50 leading-relaxed font-medium pr-24">Pemerintah menargetkan penurunan signifikan. NutriGen memangkas birokrasi agar intervensi lebih cepat.</p>
                    </div>

                    {{-- Floating overlay badge --}}
                    <div class="absolute -bottom-6 -right-6 w-32 h-32 bg-white rounded-2xl flex flex-col items-center justify-center text-slate-800 shadow-xl shadow-emerald-500/10 border border-emerald-50 z-30 float-anim">
                        <div class="text-4xl font-black text-emerald-500 mb-1">0</div>
                        <div class="text-[11px] font-bold text-center text-slate-500 uppercase tracking-wider">Toleransi<br>Error Data</div>
                    </div>
                </div>
            </div>

            {{-- Right: Text and checkpoints --}}
            <div x-data="{shown:false}" x-intersect="shown=true">
                <div x-show="shown" x-transition:enter="transition duration-700 delay-100" x-transition:enter-start="opacity-0 translate-x-8" x-transition:enter-end="opacity-100 translate-x-0">
                    <div class="text-sm font-bold uppercase tracking-[0.15em] text-emerald-600 mb-3">Tantangan Lapangan</div>
                    <h2 class="text-3xl sm:text-4xl font-extrabold text-slate-800 leading-[1.15] tracking-tight mb-6">
                        Keterlambatan Rekap <br>Memperlambat Intervensi
                    </h2>
                    <p class="text-slate-500 text-lg leading-relaxed mb-10 font-medium">
                        Risiko hilangnya catatan fisik, kesalahan pembacaan, serta rekap berjenjang yang memakan waktu berminggu-minggu membuat penanganan gizi rawan terlambat. NutriGen hadir sebagai **pencadangan (backup) digital** *real-time*.
                    </p>

                    <div class="space-y-6">
                        @foreach([
                            ['c' => 'from-emerald-400 to-teal-400', 'title' => 'Algoritma Validasi Z-Score', 'desc' => 'Sistem menolak otomatis input BB/TB yang tidak masuk akal secara medis.'],
                            ['c' => 'from-teal-400 to-cyan-400', 'title' => 'Monitoring Hierarkis', 'desc' => 'Data mengalir bersih dari Kader -> Puskesmas -> Dinas Kesehatan.'],
                            ['c' => 'from-cyan-400 to-blue-400', 'title' => 'Distribusi Otomatis via WhatsApp', 'desc' => 'Orang tua menerima update status gizi anak tanpa birokrasi rumit.'],
                        ] as $c)
                        <div class="flex items-start gap-5">
                            <div class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br {{ $c['c'] }} flex items-center justify-center shadow-sm">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <p class="font-bold text-slate-800 mb-1.5 text-base">{{ $c['title'] }}</p>
                                <p class="text-slate-500 text-sm leading-relaxed">{{ $c['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- =============================================
         SECTION 4: STATS (Vibrant Light Mode)
    ============================================== --}}
    <section class="py-16 bg-gradient-to-r from-emerald-500 to-teal-500 relative overflow-hidden">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.15)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.15)_1px,transparent_1px)] bg-[size:3rem_3rem]"></div>
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-12 text-center divide-x divide-white/20" x-data="{shown:false}" x-intersect="shown=true">
                @foreach([
                    ['num' => '100%', 'label' => 'Paperless', 'sub' => 'Digitalisasi total'],
                    ['num' => '< 3s', 'label' => 'Hitung Z-Score', 'sub' => 'Otomatis presisi'],
                    ['num' => '4', 'label' => 'Aktor Sistem', 'sub' => 'Dinkes s/d Orang Tua'],
                    ['num' => 'WHO', 'label' => 'Standar Gizi', 'sub' => 'Kurva 2006'],
                ] as $i => $s)
                <div x-show="shown" x-transition:enter="transition duration-700 delay-{{ $i * 100 }}" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="px-4 text-white">
                    <div class="text-3xl sm:text-4xl font-black mb-2">{{ $s['num'] }}</div>
                    <div class="font-bold text-sm mb-1 uppercase tracking-wide">{{ $s['label'] }}</div>
                    <div class="text-emerald-50 text-xs font-medium">{{ $s['sub'] }}</div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =============================================
         SECTION 5: VIDEO DEMO
    ============================================== --}}
    <section id="video-demo" class="py-24 bg-white relative">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-12" x-data="{shown:false}" x-intersect="shown=true">
                <div x-show="shown" x-transition:enter="transition duration-600" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                     class="text-sm font-bold uppercase tracking-[0.15em] text-emerald-600 mb-3">Live Demo</div>
                <h2 x-show="shown" x-transition:enter="transition duration-700 delay-100" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0"
                    class="text-3xl sm:text-4xl font-extrabold text-slate-800 tracking-tight">Antarmuka Modern & Cepat</h2>
            </div>

            <div class="relative rounded-2xl overflow-hidden shadow-[0_25px_50px_-12px_rgba(16,185,129,0.15)] border border-emerald-100/60 bg-white" x-data="{shown:false}" x-intersect="shown=true">
                <div x-show="shown" x-transition:enter="transition duration-900 delay-200" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
                    <div class="bg-slate-50/80 backdrop-blur px-4 py-3 flex items-center gap-2 border-b border-slate-100">
                        <div class="w-3 h-3 rounded-full bg-rose-400"></div>
                        <div class="w-3 h-3 rounded-full bg-amber-400"></div>
                        <div class="w-3 h-3 rounded-full bg-emerald-400"></div>
                        <div class="ml-4 flex-1 bg-white rounded-md px-3 py-1.5 text-[11px] text-slate-400 font-medium shadow-sm border border-slate-100 flex items-center gap-2">
                            <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            nutrigen.up.railway.app
                        </div>
                    </div>
                    <div class="aspect-video bg-slate-50">
                        <iframe class="w-full h-full" src="https://www.youtube.com/embed/99Radiqy15c?rel=0" title="Demo NutriGen" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- =============================================
         SECTION 5.5: FAQ (Pertanyaan Umum)
    ============================================== --}}
    <section id="faq" class="py-24 bg-slate-50/50 border-t border-slate-100 relative">
        <div class="absolute inset-0 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:24px_24px] opacity-[0.02] -z-10"></div>
        <div class="max-w-4xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16" x-data="{shown:false}" x-intersect="shown=true">
                <div x-show="shown" x-transition:enter="transition duration-600" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                     class="text-sm font-bold uppercase tracking-[0.15em] text-emerald-600 mb-3">FAQ</div>
                <h2 x-show="shown" x-transition:enter="transition duration-700 delay-100" x-transition:enter-start="opacity-0 translate-y-6" x-transition:enter-end="opacity-100 translate-y-0"
                    class="text-3xl sm:text-4xl font-extrabold text-slate-800 tracking-tight">Pertanyaan yang Sering Diajukan</h2>
            </div>

            <div class="space-y-4" x-data="{active: null, shown: false}" x-intersect="shown=true">
                @foreach([
                    ['q' => 'Apakah saya harus mengunduh aplikasi untuk melihat grafik pertumbuhan anak?', 'a' => 'Tidak. NutriGen menggunakan sistem Magic Link via WhatsApp sehingga Orang Tua bisa langsung melihat data kurva pertumbuhan (Z-Score WHO) melalui browser smartphone tanpa perlu menginstal aplikasi tambahan.'],
                    ['q' => 'Siapa saja yang bisa melakukan validasi stunting?', 'a' => 'Validasi klinis dan penetapan status gizi (Normal/Stunting/Gizi Buruk) hanya bisa dilakukan oleh Ahli Gizi atau petugas terlatih di Puskesmas. Kader di lapangan hanya bertugas menginput data mentah TB/BB.'],
                    ['q' => 'Apakah data balita terjamin keamanannya?', 'a' => 'Sangat terjamin. Kami mematuhi standar privasi data rekam medis. Akses berlapis diterapkan dari level Kader, Puskesmas, hingga Dinas Kesehatan (Super Admin) dengan autentikasi enkripsi standar industri.'],
                    ['q' => 'Bagaimana cara Puskesmas wilayah kami bergabung dengan NutriGen?', 'a' => 'Pendaftaran instansi Puskesmas baru dan wilayah kerjanya dikelola langsung oleh administrator tingkat Dinas Kesehatan (Super Admin). Silakan hubungi perwakilan Dinkes wilayah Anda.'],
                ] as $i => $faq)
                <div x-show="shown" x-transition:enter="transition duration-500 delay-{{ $i * 100 }}" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
                     class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-sm transition-all duration-300 hover:border-emerald-200"
                     :class="{'ring-2 ring-emerald-50 border-emerald-200 shadow-md': active === {{ $i }}}">
                    <button @click="active !== {{ $i }} ? active = {{ $i }} : active = null" 
                            class="w-full px-6 py-5 text-left flex items-center justify-between focus:outline-none">
                        <span class="font-bold text-slate-800 text-base sm:text-lg pr-4" :class="{'text-emerald-700': active === {{ $i }}}">{{ $faq['q'] }}</span>
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center transition-colors duration-300"
                             :class="active === {{ $i }} ? 'bg-emerald-100 text-emerald-600' : 'bg-slate-100 text-slate-400'">
                            <svg class="w-5 h-5 transform transition-transform duration-300" :class="{'rotate-180': active === {{ $i }}}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </button>
                    <div x-show="active === {{ $i }}" x-collapse>
                        <div class="px-6 pb-6 text-slate-500 leading-relaxed font-medium text-sm sm:text-base">
                            {{ $faq['a'] }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- =============================================
         SECTION 6: MEET THE TEAM
    ============================================== --}}
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(#10b981_1px,transparent_1px)] [background-size:24px_24px] opacity-[0.03] -z-10"></div>
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 bg-emerald-50 border border-emerald-200 text-emerald-700 font-bold text-[11px] px-4 py-1.5 rounded-full mb-6 tracking-widest uppercase shadow-sm">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                Tim Pengembang
            </div>
            
            <div class="bg-white rounded-[2rem] p-10 md:p-14 shadow-[0_20px_50px_-12px_rgba(16,185,129,0.15)] border border-emerald-100/60 relative z-10 transition-all duration-300 hover:shadow-[0_20px_50px_-12px_rgba(16,185,129,0.25)]">
                <h2 class="text-3xl md:text-4xl font-extrabold text-slate-800 tracking-tight mb-6">Built by <span class="text-emerald-600">Student Innovators</span></h2>
                <p class="text-slate-500 leading-relaxed font-medium mb-8 max-w-2xl mx-auto">
                    NutriGen dikembangkan oleh mahasiswa lintas universitas yang berkolaborasi dalam Hackathon Digdaya 2026 untuk menghadirkan solusi digital penanganan stunting berbasis Posyandu.
                </p>
                
                <div class="flex flex-wrap justify-center gap-3 mb-10">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-50 text-slate-600 text-xs font-semibold border border-slate-200"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Universitas Syiah Kuala</span>
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-slate-50 text-slate-600 text-xs font-semibold border border-slate-200"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span> UIN Ar-Raniry Banda Aceh</span>
                </div>

                <div class="flex justify-center -space-x-4 mb-10">
                    <div class="w-16 h-16 rounded-full border-4 border-white bg-slate-200 flex items-center justify-center overflow-hidden shadow-sm z-40"><img src="https://ui-avatars.com/api/?name=M&background=0D8ABC&color=fff" alt="Team 1" class="w-full h-full object-cover"></div>
                    <div class="w-16 h-16 rounded-full border-4 border-white bg-slate-200 flex items-center justify-center overflow-hidden shadow-sm z-30"><img src="https://ui-avatars.com/api/?name=A&background=10b981&color=fff" alt="Team 2" class="w-full h-full object-cover"></div>
                    <div class="w-16 h-16 rounded-full border-4 border-white bg-slate-200 flex items-center justify-center overflow-hidden shadow-sm z-20"><img src="https://ui-avatars.com/api/?name=R&background=f59e0b&color=fff" alt="Team 3" class="w-full h-full object-cover"></div>
                    <div class="w-16 h-16 rounded-full border-4 border-white bg-slate-200 flex items-center justify-center overflow-hidden shadow-sm z-10"><img src="https://ui-avatars.com/api/?name=F&background=8b5cf6&color=fff" alt="Team 4" class="w-full h-full object-cover"></div>
                </div>

                <button class="bg-slate-900 hover:bg-slate-800 text-white font-bold py-3 px-8 rounded-full transition-colors duration-300 inline-flex items-center gap-2 text-sm shadow-lg shadow-slate-900/20">
                    Kenali Tim Kami Lebih Dekat <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                </button>
            </div>
        </div>
    </section>

    {{-- =============================================
         SECTION 7: CTA SECTION
    ============================================== --}}
    <section class="bg-white text-slate-800 relative overflow-hidden border-t border-slate-100">
        {{-- Decorative Background Elements --}}
        <div class="absolute inset-0 bg-gradient-to-b from-white via-emerald-50/30 to-teal-50/50 -z-20"></div>
        
        {{-- Soft Glow Blobs --}}
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-emerald-200/30 rounded-full blur-[100px] -translate-x-1/2 -translate-y-1/2 -z-10 pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-[600px] h-[600px] bg-teal-200/20 rounded-full blur-[100px] translate-x-1/3 translate-y-1/3 -z-10 pointer-events-none"></div>

        <div class="py-28 text-center relative z-10" x-data="{shown:false}" x-intersect="shown=true">
            {{-- Floating Decorative Icons --}}
            <div class="absolute top-20 left-[15%] text-emerald-300/40 float-anim hidden md:block">
                <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
            </div>
            <div class="absolute bottom-32 right-[15%] text-teal-300/40 float-anim-2 hidden md:block">
                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
            </div>

            <div x-show="shown" x-transition:enter="transition duration-700" x-transition:enter-start="opacity-0 translate-y-8" x-transition:enter-end="opacity-100 translate-y-0" class="relative">
                <div class="inline-flex items-center gap-2 bg-white/80 backdrop-blur border border-emerald-200 text-emerald-700 font-bold text-[11px] px-4 py-1.5 rounded-full mb-8 tracking-widest uppercase shadow-sm">
                    Mulai Digunakan Hari Ini
                </div>
                <h2 class="text-4xl lg:text-5xl font-extrabold tracking-tight mb-6">
                    Beralih ke <span class="gradient-text">NutriGen</span> Sekarang
                </h2>
                <p class="text-slate-500 max-w-lg mx-auto leading-relaxed mb-10 text-base font-medium">Bergabunglah dengan ekosistem kesehatan modern. Akses sistem pelaporan dan validasi klinis stunting terintegrasi untuk wilayah Anda dengan sekali klik.</p>
                <div class="flex justify-center">
                    <a href="{{ route('login') }}" class="btn-primary-saas text-white font-bold px-12 py-5 rounded-2xl text-lg inline-flex items-center gap-3 shadow-[0_8px_30px_rgb(16,185,129,0.3)] hover:shadow-[0_8px_30px_rgb(16,185,129,0.45)] ring-4 ring-emerald-50">
                        Login ke Dashboard Utama
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- =============================================
         SECTION 8: REAL FOOTER
    ============================================== --}}
    <footer class="bg-white border-t border-slate-200 pt-20 pb-10">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-12 mb-16">
                {{-- Brand & About --}}
                <div class="md:col-span-5">
                    <div class="flex items-center gap-2 mb-6">
                        <svg class="w-8 h-8 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2zm0 4.2L18.8 19H5.2L12 6.2zM11 11v4h2v-4h-2zm0 5v2h2v-2h-2z"/></svg>
                        <span class="text-2xl font-black text-slate-800 tracking-tight">NutriGen</span>
                    </div>
                    <p class="text-slate-500 leading-relaxed font-medium mb-6 text-sm">
                        Platform manajemen stunting end-to-end yang mengintegrasikan data dari Posyandu ke Puskesmas secara real-time. Membangun generasi emas Indonesia.
                    </p>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1 bg-slate-100 text-slate-600 text-xs font-bold rounded-md flex items-center gap-1.5"><svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg> Built in Indonesia</span>
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-xs font-bold rounded-md"><span class="text-emerald-500 mr-1">●</span> Digdaya 2026</span>
                        <span class="px-3 py-1 bg-slate-100 text-slate-500 text-xs font-bold rounded-md">v1.0 MVP</span>
                    </div>
                </div>

                {{-- Links --}}
                <div class="md:col-span-3 md:col-start-7">
                    <h4 class="font-extrabold text-slate-800 tracking-wider text-sm uppercase mb-6">Platform</h4>
                    <ul class="space-y-4">
                        <li><a href="#features" class="text-slate-500 hover:text-emerald-600 font-medium transition-colors text-sm flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-slate-300"></span> Cara Kerja</a></li>
                        <li><a href="#faq" class="text-slate-500 hover:text-emerald-600 font-medium transition-colors text-sm flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-slate-300"></span> FAQ & Bantuan</a></li>
                        <li><a href="#" class="text-emerald-600 font-semibold transition-colors text-sm flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-emerald-500"></span> Meet Our Team</a></li>
                        <li><a href="{{ route('login') }}" class="text-slate-500 hover:text-emerald-600 font-medium transition-colors text-sm flex items-center gap-2"><span class="w-1 h-1 rounded-full bg-slate-300"></span> Portal Petugas</a></li>
                    </ul>
                </div>

                {{-- Contact --}}
                <div class="md:col-span-3">
                    <h4 class="font-extrabold text-slate-800 tracking-wider text-sm uppercase mb-6">Kontak</h4>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="text-slate-500 font-medium text-sm mt-1">teamnutrigen@gmail.com</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            </div>
                            <span class="text-slate-500 font-medium text-sm mt-1">WhatsApp Support</span>
                        </li>
                        <li class="flex items-start gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center flex-shrink-0 mt-0.5">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <span class="text-slate-500 font-medium text-sm mt-1">Banda Aceh, Indonesia</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="pt-8 border-t border-slate-100 flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="text-slate-400 text-sm font-medium">
                    &copy; 2026 <span class="text-emerald-600 font-bold">NutriGen MVP</span> &bull; Hackathon Digdaya 2026
                </div>
                <div class="flex items-center gap-4">
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-50 hover:bg-emerald-50 text-slate-400 hover:text-emerald-600 flex items-center justify-center transition-colors border border-slate-100">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path></svg>
                    </a>
                    <a href="#" class="w-10 h-10 rounded-full bg-slate-50 hover:bg-emerald-50 text-slate-400 hover:text-emerald-600 flex items-center justify-center transition-colors border border-slate-100">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </footer>

</div>
@endsection
