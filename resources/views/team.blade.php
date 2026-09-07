@extends('layouts.public')

@section('title', 'Meet Our Team | NutriGen')

@section('content')

    {{-- SECTION 1: HERO (Dark & Vibrant to eliminate white space) --}}
    <section class="relative pt-32 pb-48 lg:pt-48 lg:pb-64 overflow-hidden bg-slate-950">
        {{-- Complex Animated Glow Background --}}
        <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-emerald-500 to-transparent opacity-50"></div>
        <div class="absolute -top-48 -right-48 w-[800px] h-[800px] bg-emerald-600/30 rounded-full blur-[120px] animate-[spin_60s_linear_infinite] pointer-events-none"></div>
        <div class="absolute -bottom-48 -left-48 w-[600px] h-[600px] bg-cyan-600/20 rounded-full blur-[100px] animate-[spin_40s_linear_infinite_reverse] pointer-events-none"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4wNykiLz48L3N2Zz4=')] opacity-50"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 text-center">
            <div class="inline-flex items-center gap-2 bg-slate-900/50 border border-slate-700/50 backdrop-blur-md text-emerald-300 font-bold text-[11px] px-5 py-2 rounded-full mb-8 tracking-widest uppercase shadow-2xl" data-aos="fade-down">
                <span class="w-2 h-2 rounded-full bg-emerald-400 shadow-[0_0_8px_rgba(52,211,153,0.8)] animate-pulse"></span>
                Digdaya Hackathon 2026
            </div>

            <h1 class="text-5xl sm:text-6xl lg:text-8xl font-black tracking-tighter text-white leading-[1.1] mb-8" data-aos="fade-up" data-aos-delay="100">
                The Minds Behind<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-400 via-teal-300 to-cyan-400">NutriGen.</span>
            </h1>
            
            <p class="text-lg lg:text-2xl font-medium text-slate-400 mb-12 max-w-3xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="200">
                Empat mahasiswa dari dua universitas berbeda, bersatu dalam satu misi: membangun ekosistem digital untuk membebaskan anak Indonesia dari stunting.
            </p>
        </div>
    </section>

    {{-- SECTION 2: THE TEAM (Overlapping the hero section for depth) --}}
    <section class="relative z-20 -mt-32 lg:-mt-48 pb-24 bg-transparent">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">

                {{-- Member 1 --}}
                <div class="bg-slate-900 border border-slate-700/50 rounded-[2rem] p-2 hover:border-emerald-500/50 transition-colors duration-500 group shadow-2xl shadow-black/50" data-aos="fade-up" data-aos-delay="300">
                    <div class="bg-gradient-to-b from-slate-800 to-slate-900 rounded-[1.75rem] p-8 lg:p-10 h-full flex flex-col relative overflow-hidden">
                        {{-- Decorative Glow --}}
                        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/10 rounded-full blur-[60px] group-hover:bg-emerald-500/20 transition-colors duration-500"></div>
                        
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-8 relative z-10">
                            <div class="w-32 h-32 shrink-0 rounded-2xl overflow-hidden relative border border-slate-700 shadow-xl bg-slate-800">
                                <img src="{{ asset('images/team/member-1.png') }}" alt="Naufal" class="w-full h-full object-cover object-top filter contrast-125 brightness-110 group-hover:scale-110 transition-transform duration-700" loading="lazy">
                                <div class="absolute inset-0 ring-1 ring-inset ring-white/10 rounded-2xl"></div>
                            </div>
                            <div class="text-center sm:text-left mt-2">
                                <h3 class="text-2xl lg:text-3xl font-black tracking-tight text-white mb-2">M. Naufal<br>Alifaturafif</h3>
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 rounded-lg text-sm font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path></svg>
                                    Backend Developer
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-slate-400 font-medium leading-relaxed text-sm lg:text-base mb-8 flex-grow relative z-10">
                            Arsitek di balik ketahanan dan kecepatan NutriGen. Merancang struktur database yang kompleks dan API berkinerja tinggi untuk sinkronisasi data seketika antara Posyandu dan Puskesmas.
                        </p>
                        
                        <div class="pt-6 border-t border-slate-700/50 flex items-center gap-3 relative z-10">
                            <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center p-2">
                                <img src="{{ asset('images/universities/uin-arraniry.svg') }}" class="w-full h-full object-contain filter brightness-200">
                            </div>
                            <div>
                                <p class="text-white font-bold text-sm">UIN Ar-Raniry</p>
                                <p class="text-emerald-400 text-xs font-semibold">Teknologi Informasi</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Member 2 --}}
                <div class="bg-slate-900 border border-slate-700/50 rounded-[2rem] p-2 hover:border-sky-500/50 transition-colors duration-500 group shadow-2xl shadow-black/50" data-aos="fade-up" data-aos-delay="400">
                    <div class="bg-gradient-to-b from-slate-800 to-slate-900 rounded-[1.75rem] p-8 lg:p-10 h-full flex flex-col relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-sky-500/10 rounded-full blur-[60px] group-hover:bg-sky-500/20 transition-colors duration-500"></div>
                        
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-8 relative z-10">
                            <div class="w-32 h-32 shrink-0 rounded-2xl overflow-hidden relative border border-slate-700 shadow-xl bg-slate-800">
                                <img src="{{ asset('images/team/member-2.jpeg') }}" alt="Bintang" class="w-full h-full object-cover object-top filter contrast-125 brightness-110 group-hover:scale-110 transition-transform duration-700" loading="lazy">
                                <div class="absolute inset-0 ring-1 ring-inset ring-white/10 rounded-2xl"></div>
                            </div>
                            <div class="text-center sm:text-left mt-2">
                                <h3 class="text-2xl lg:text-3xl font-black tracking-tight text-white mb-2">Bintang Naufal<br>Fayazzi</h3>
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-sky-500/10 border border-sky-500/20 text-sky-400 rounded-lg text-sm font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    Frontend Developer
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-slate-400 font-medium leading-relaxed text-sm lg:text-base mb-8 flex-grow relative z-10">
                            Mengubah kompleksitas sistem menjadi antarmuka yang indah dan intuitif. Memastikan setiap interaksi kader Posyandu terasa mulus, responsif, dan mudah dipahami di semua perangkat.
                        </p>
                        
                        <div class="pt-6 border-t border-slate-700/50 flex items-center gap-3 relative z-10">
                            <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center p-2">
                                <img src="{{ asset('images/universities/usk.svg') }}" class="w-full h-full object-contain filter brightness-200">
                            </div>
                            <div>
                                <p class="text-white font-bold text-sm">Universitas Syiah Kuala</p>
                                <p class="text-sky-400 text-xs font-semibold">Manajemen Informatika</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Member 3 --}}
                <div class="bg-slate-900 border border-slate-700/50 rounded-[2rem] p-2 hover:border-violet-500/50 transition-colors duration-500 group shadow-2xl shadow-black/50" data-aos="fade-up" data-aos-delay="500">
                    <div class="bg-gradient-to-b from-slate-800 to-slate-900 rounded-[1.75rem] p-8 lg:p-10 h-full flex flex-col relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-violet-500/10 rounded-full blur-[60px] group-hover:bg-violet-500/20 transition-colors duration-500"></div>
                        
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-8 relative z-10">
                            <div class="w-32 h-32 shrink-0 rounded-2xl overflow-hidden relative border border-slate-700 shadow-xl bg-slate-800">
                                <img src="{{ asset('images/team/member-3.jpeg') }}" alt="Riyan" class="w-full h-full object-cover object-top filter contrast-125 brightness-110 group-hover:scale-110 transition-transform duration-700" loading="lazy">
                                <div class="absolute inset-0 ring-1 ring-inset ring-white/10 rounded-2xl"></div>
                            </div>
                            <div class="text-center sm:text-left mt-2">
                                <h3 class="text-2xl lg:text-3xl font-black tracking-tight text-white mb-2">Riyan Arya<br>Syahputra</h3>
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-violet-500/10 border border-violet-500/20 text-violet-400 rounded-lg text-sm font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                                    Project Lead & UI/UX
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-slate-400 font-medium leading-relaxed text-sm lg:text-base mb-8 flex-grow relative z-10">
                            Mengatur visi dan strategi platform. Meneliti kebutuhan pengguna secara mendalam dan merancang alur pengalaman (UX) yang paling sesuai dengan kebiasaan operasional di Puskesmas.
                        </p>
                        
                        <div class="pt-6 border-t border-slate-700/50 flex items-center gap-3 relative z-10">
                            <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center p-2">
                                <img src="{{ asset('images/universities/uin-arraniry.svg') }}" class="w-full h-full object-contain filter brightness-200">
                            </div>
                            <div>
                                <p class="text-white font-bold text-sm">UIN Ar-Raniry</p>
                                <p class="text-violet-400 text-xs font-semibold">Teknologi Informasi</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Member 4 --}}
                <div class="bg-slate-900 border border-slate-700/50 rounded-[2rem] p-2 hover:border-amber-500/50 transition-colors duration-500 group shadow-2xl shadow-black/50" data-aos="fade-up" data-aos-delay="600">
                    <div class="bg-gradient-to-b from-slate-800 to-slate-900 rounded-[1.75rem] p-8 lg:p-10 h-full flex flex-col relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-amber-500/10 rounded-full blur-[60px] group-hover:bg-amber-500/20 transition-colors duration-500"></div>
                        
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-8 relative z-10">
                            <div class="w-32 h-32 shrink-0 rounded-2xl overflow-hidden relative border border-slate-700 shadow-xl bg-slate-800">
                                <img src="{{ asset('images/team/member-4.jpeg') }}" alt="Risky" class="w-full h-full object-cover object-top filter contrast-125 brightness-110 group-hover:scale-110 transition-transform duration-700" loading="lazy">
                                <div class="absolute inset-0 ring-1 ring-inset ring-white/10 rounded-2xl"></div>
                            </div>
                            <div class="text-center sm:text-left mt-2">
                                <h3 class="text-2xl lg:text-3xl font-black tracking-tight text-white mb-2">Risky Husnaa<br>Mulyadi</h3>
                                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-amber-500/10 border border-amber-500/20 text-amber-400 rounded-lg text-sm font-bold">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                                    Business Analyst
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-slate-400 font-medium leading-relaxed text-sm lg:text-base mb-8 flex-grow relative z-10">
                            Menerjemahkan aturan medis dan standar WHO ke dalam logika pemrograman. Memastikan setiap perhitungan gizi balita di NutriGen akurat, valid, dan dapat dipertanggungjawabkan secara klinis.
                        </p>
                        
                        <div class="pt-6 border-t border-slate-700/50 flex items-center gap-3 relative z-10">
                            <div class="w-10 h-10 rounded-xl bg-slate-800 border border-slate-700 flex items-center justify-center p-2">
                                <img src="{{ asset('images/universities/uin-arraniry.svg') }}" class="w-full h-full object-contain filter brightness-200">
                            </div>
                            <div>
                                <p class="text-white font-bold text-sm">UIN Ar-Raniry</p>
                                <p class="text-amber-400 text-xs font-semibold">Teknologi Informasi</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- SECTION 3: BENTO BOX MISSION (Dense, colorful, minimal white space) --}}
    <section class="py-12 bg-slate-900 border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4 auto-rows-[240px]">
                
                {{-- Big Mission Box --}}
                <div class="md:col-span-2 lg:col-span-2 row-span-1 lg:row-span-2 bg-emerald-600 rounded-3xl p-8 lg:p-10 relative overflow-hidden flex flex-col justify-end text-white group" data-aos="zoom-in">
                    <div class="absolute top-0 right-0 w-full h-full bg-gradient-to-br from-emerald-400 to-emerald-700 opacity-50"></div>
                    <svg class="absolute top-6 right-6 w-24 h-24 text-emerald-300/30 group-hover:scale-110 transition-transform duration-700" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2zm0 4.2L18.8 19H5.2L12 6.2z"/></svg>
                    <div class="relative z-10">
                        <h2 class="text-3xl lg:text-5xl font-black mb-4 leading-tight">Membangun<br>Generasi Emas.</h2>
                        <p class="text-emerald-50 font-medium text-lg leading-relaxed max-w-sm">
                            Bukan sekadar aplikasi, kami membangun ekosistem untuk memastikan setiap anak tumbuh optimal.
                        </p>
                    </div>
                </div>

                {{-- Feature 1 --}}
                <div class="col-span-1 bg-slate-800 rounded-3xl p-8 relative overflow-hidden border border-slate-700 hover:border-sky-500 transition-colors group" data-aos="zoom-in" data-aos-delay="100">
                    <div class="w-12 h-12 bg-sky-500 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Fast Sync</h3>
                    <p class="text-slate-400 text-sm">Sinkronisasi data kader ke Puskesmas terjadi dalam hitungan milidetik.</p>
                </div>

                {{-- Feature 2 --}}
                <div class="col-span-1 bg-slate-800 rounded-3xl p-8 relative overflow-hidden border border-slate-700 hover:border-amber-500 transition-colors group" data-aos="zoom-in" data-aos-delay="200">
                    <div class="w-12 h-12 bg-amber-500 rounded-full flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Validasi Medis</h3>
                    <p class="text-slate-400 text-sm">Perhitungan z-score otomatis berdasarkan algoritma medis WHO.</p>
                </div>

                {{-- Feature 3 --}}
                <div class="md:col-span-2 col-span-1 lg:col-span-2 bg-slate-200 rounded-3xl p-8 relative overflow-hidden flex items-center gap-6 group hover:bg-white transition-colors" data-aos="zoom-in" data-aos-delay="300">
                    <div class="absolute inset-0 bg-[linear-gradient(45deg,transparent_25%,rgba(0,0,0,0.02)_50%,transparent_75%,transparent_100%)] bg-[length:20px_20px]"></div>
                    <div class="relative z-10 w-full flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl lg:text-3xl font-black text-slate-900 mb-2">Kolaborasi Hebat</h3>
                            <p class="text-slate-600 font-medium">Lintas disiplin ilmu. Lintas universitas.</p>
                        </div>
                        <div class="hidden sm:flex gap-4">
                            <img src="{{ asset('images/universities/uin-arraniry.svg') }}" class="w-16 h-16 object-contain">
                            <img src="{{ asset('images/universities/usk.svg') }}" class="w-16 h-16 object-contain">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- SECTION 4: INFINITE TECH MARQUEE (Very dense, high energy) --}}
    <section class="py-16 bg-emerald-600 overflow-hidden relative border-y border-emerald-700">
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyMCIgaGVpZ2h0PSIyMCI+PGNpcmNsZSBjeD0iMSIgY3k9IjEiIHI9IjEiIGZpbGw9InJnYmEoMjU1LDI1NSwyNTUsMC4xNSkiLz48L3N2Zz4=')]"></div>
        
        <div class="relative z-10 w-full flex overflow-hidden whitespace-nowrap mask-image-fade">
            <div class="animate-marquee inline-flex gap-8 items-center shrink-0 pr-8">
                @php
                    $tools = ['LARAVEL', 'TAILWIND CSS', 'LIVEWIRE', 'ALPINE.JS', 'MYSQL', 'FIGMA', 'GITHUB', 'RAILWAY', 'WHO STANDARDS'];
                @endphp
                @foreach(range(1, 4) as $iteration)
                    @foreach($tools as $tool)
                        <div class="flex items-center gap-8">
                            <span class="text-3xl lg:text-5xl font-black text-emerald-950/80 uppercase tracking-tighter">{{ $tool }}</span>
                            <span class="text-3xl lg:text-5xl text-emerald-400">✧</span>
                        </div>
                    @endforeach
                @endforeach
            </div>
        </div>
    </section>

    <style>
        .mask-image-fade {
            mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
            -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
        }
        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }
        .animate-marquee {
            animation: marquee 30s linear infinite;
        }
    </style>

    {{-- FOOTER --}}
    @include('partials.public-footer')

@endsection
