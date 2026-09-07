@extends('layouts.public')

@section('title', 'Meet Our Team | NutriGen')

@section('content')

    {{-- SECTION 1: HERO (Clean, airy, with massive vibrant gradient shape like the reference) --}}
    <section class="relative pt-32 pb-24 lg:pt-48 lg:pb-32 overflow-hidden bg-white">
        {{-- Subtle dot pattern --}}
        <div class="absolute inset-0 bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] [background-size:24px_24px] opacity-50 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 flex flex-col lg:flex-row items-center">
            
            {{-- Left Content --}}
            <div class="w-full lg:w-1/2 max-w-2xl lg:pr-12">
                <div class="inline-flex items-center gap-2 px-5 py-2 bg-white/80 backdrop-blur-md border border-slate-200/60 rounded-full text-slate-700 text-sm font-bold shadow-sm mb-8" data-aos="fade-up">
                    <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Digdaya Hackathon 2026
                </div>
                
                <h1 class="text-5xl sm:text-6xl lg:text-7xl font-black text-slate-900 tracking-tight leading-[1.1] mb-6" data-aos="fade-up" data-aos-delay="100">
                    New Generation Of <br>
                    <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-cyan-500">Healthcare Builders</span>
                </h1>
                
                <p class="text-lg text-slate-500 font-medium leading-relaxed mb-10 max-w-xl" data-aos="fade-up" data-aos-delay="200">
                    Empat mahasiswa dari dua universitas berbeda, bersatu dalam satu misi: membangun ekosistem digital modern untuk membebaskan anak Indonesia dari stunting.
                </p>
                
                <div class="flex flex-wrap gap-4" data-aos="fade-up" data-aos-delay="300">
                    <a href="#team" class="px-8 py-4 bg-slate-900 hover:bg-emerald-600 text-white text-sm font-bold rounded-full shadow-[0_8px_20px_rgba(15,23,42,0.15)] hover:shadow-[0_8px_20px_rgba(16,185,129,0.3)] transition-all duration-300 hover:-translate-y-1">
                        Meet The Team
                    </a>
                    <a href="{{ url('/') }}" class="px-8 py-4 bg-white text-slate-700 hover:text-emerald-600 text-sm font-bold rounded-full border border-slate-200 shadow-sm hover:border-emerald-200 hover:bg-emerald-50 transition-all duration-300">
                        Back to Home
                    </a>
                </div>
            </div>

            {{-- Right Content (Image with floating cards) --}}
            <div class="w-full lg:w-1/2 mt-16 lg:mt-0 relative hidden lg:block" data-aos="fade-left" data-aos-delay="400">
                <div class="relative w-full aspect-[4/3] max-w-[650px] ml-auto rounded-[3rem] overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.15)] border-4 border-white group">
                    <img src="{{ asset('images/team/team2.jpeg') }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000 ease-out" alt="NutriGen Team">
                    <div class="absolute inset-0 bg-emerald-900/5 mix-blend-overlay pointer-events-none"></div>
                </div>

                {{-- Decorative floating card 1 --}}
                <div class="absolute top-10 -left-6 bg-white/90 backdrop-blur-xl p-6 rounded-3xl shadow-[0_20px_40px_rgba(0,0,0,0.08)] border border-white w-64 animate-[bounce_10s_infinite_alternate] z-10">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-full bg-emerald-100 flex items-center justify-center">
                            <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                        <div>
                            <div class="text-sm font-bold text-slate-900">Collaboration</div>
                            <div class="text-xs text-slate-500">Cross-University</div>
                        </div>
                    </div>
                    <div class="flex -space-x-3">
                        <img class="w-10 h-10 rounded-full border-2 border-white object-cover grayscale" src="{{ asset('images/team/member-1.png') }}">
                        <img class="w-10 h-10 rounded-full border-2 border-white object-cover grayscale" src="{{ asset('images/team/member-2.jpeg') }}">
                        <img class="w-10 h-10 rounded-full border-2 border-white object-cover grayscale" src="{{ asset('images/team/member-3.jpeg') }}">
                        <img class="w-10 h-10 rounded-full border-2 border-white object-cover grayscale" src="{{ asset('images/team/member-4.jpeg') }}">
                    </div>
                </div>

                {{-- Decorative floating card 2 --}}
                <div class="absolute bottom-10 -right-6 bg-white/90 backdrop-blur-xl p-6 rounded-3xl shadow-[0_20px_40px_rgba(0,0,0,0.08)] border border-white w-72 animate-[bounce_12s_infinite_alternate-reverse] z-10">
                    <div class="text-slate-900 font-bold mb-4">Project Status</div>
                    <div class="space-y-3">
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-gradient-to-r from-emerald-400 to-cyan-400 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                        <div class="flex justify-between text-xs font-bold">
                            <span class="text-slate-500">Development</span>
                            <span class="text-emerald-600">85% MVP</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 2: VALUES (Reference 1 "Services" style) --}}
    <section class="py-24 bg-slate-50 relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="text-center mb-16" data-aos="fade-up">
                <span class="text-emerald-600 font-bold tracking-widest uppercase text-xs mb-2 block">Our Focus</span>
                <h2 class="text-3xl md:text-4xl font-black text-slate-900 mb-4">Feel The Power Of<br>Modern Healthcare Tech</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                
                {{-- Value 1 --}}
                <div class="bg-white rounded-[2rem] p-10 shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-slate-100 text-center hover:-translate-y-2 hover:shadow-[0_20px_50px_rgba(16,185,129,0.08)] transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-emerald-100 to-teal-50 rounded-2xl flex items-center justify-center mb-8 relative group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full shadow-lg flex items-center justify-center text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Human-Centered</h3>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed">
                        We provide the best software service for any type of Posyandu operation, focusing on ease of use.
                    </p>
                </div>

                {{-- Value 2 --}}
                <div class="bg-white rounded-[2rem] p-10 shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-slate-100 text-center hover:-translate-y-2 hover:shadow-[0_20px_50px_rgba(6,182,212,0.08)] transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-cyan-100 to-sky-50 rounded-2xl flex items-center justify-center mb-8 relative group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-gradient-to-br from-cyan-400 to-cyan-600 rounded-full shadow-lg flex items-center justify-center text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                        </div>
                        <svg class="w-10 h-10 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Data Reliability</h3>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed">
                        Ensuring every child's growth data is synced securely and calculated accurately against WHO standards.
                    </p>
                </div>

                {{-- Value 3 --}}
                <div class="bg-white rounded-[2rem] p-10 shadow-[0_10px_40px_rgba(0,0,0,0.03)] border border-slate-100 text-center hover:-translate-y-2 hover:shadow-[0_20px_50px_rgba(139,92,246,0.08)] transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-20 h-20 mx-auto bg-gradient-to-br from-violet-100 to-purple-50 rounded-2xl flex items-center justify-center mb-8 relative group-hover:scale-110 transition-transform duration-300">
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-gradient-to-br from-violet-400 to-violet-600 rounded-full shadow-lg flex items-center justify-center text-white">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <svg class="w-10 h-10 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-4">Fast Integration</h3>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed">
                        Seamless integration from Posyandu mobile input to Puskesmas dashboard in real-time.
                    </p>
                </div>

            </div>
        </div>
    </section>

    {{-- SECTION 3: THE TEAM (Soft, floating cards with centered avatars) --}}
    <section id="team" class="py-24 bg-white relative overflow-hidden">
        {{-- Soft abstract background shapes --}}
        <div class="absolute top-1/4 right-0 w-[500px] h-[500px] bg-gradient-to-br from-emerald-50 to-teal-50 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-gradient-to-tr from-cyan-50 to-sky-50 rounded-full blur-3xl translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
            <div class="text-center mb-20" data-aos="fade-up">
                <span class="text-emerald-600 font-bold tracking-widest uppercase text-xs mb-2 block">Our Team</span>
                <h2 class="text-4xl md:text-5xl font-black text-slate-900 mb-6">We're Best In Software<br>Development</h2>
                <p class="text-slate-500 font-medium max-w-2xl mx-auto leading-relaxed">
                    Scale your healthcare operations through a custom engineering team. Meet the makers behind NutriGen's core technologies.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">

                {{-- Member 1 --}}
                <div class="bg-white rounded-[3rem] p-8 shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-slate-100 hover:shadow-[0_40px_80px_rgba(16,185,129,0.08)] hover:-translate-y-2 transition-all duration-500 flex flex-col items-center text-center group" data-aos="fade-up" data-aos-delay="100">
                    <div class="relative w-36 h-36 mb-8">
                        <div class="absolute inset-0 bg-emerald-100 rounded-full scale-110 group-hover:scale-125 transition-transform duration-500 opacity-50"></div>
                        <img src="{{ asset('images/team/member-1.png') }}" class="absolute inset-0 w-full h-full object-cover rounded-full border-4 border-white shadow-lg filter grayscale group-hover:grayscale-0 transition-all duration-500" alt="Naufal">
                    </div>
                    <div class="inline-flex px-4 py-1.5 bg-emerald-50 text-emerald-600 font-bold text-[10px] uppercase tracking-widest rounded-full mb-5">Frontend Developer</div>
                    <h3 class="text-xl font-black text-slate-900 mb-3 leading-tight">M. Naufal<br>Alifaturafif</h3>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed mb-8 flex-grow">
                        Mengubah kompleksitas menjadi antarmuka yang indah, responsif, dan mudah dipahami.
                    </p>
                    <div class="flex items-center gap-3 pt-6 border-t border-slate-100 w-full justify-center">
                        <img src="{{ asset('images/universities/uin-arraniry.svg') }}" class="w-8 h-8 object-contain filter grayscale group-hover:grayscale-0 transition-all duration-500">
                        <div class="text-left">
                            <p class="text-slate-900 font-bold text-xs">UIN Ar-Raniry</p>
                            <p class="text-slate-400 text-[10px]">Teknologi Informasi</p>
                        </div>
                    </div>
                </div>

                {{-- Member 2 --}}
                <div class="bg-white rounded-[3rem] p-8 shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-slate-100 hover:shadow-[0_40px_80px_rgba(6,182,212,0.08)] hover:-translate-y-2 transition-all duration-500 flex flex-col items-center text-center group" data-aos="fade-up" data-aos-delay="200">
                    <div class="relative w-36 h-36 mb-8">
                        <div class="absolute inset-0 bg-cyan-100 rounded-full scale-110 group-hover:scale-125 transition-transform duration-500 opacity-50"></div>
                        <img src="{{ asset('images/team/member-2.jpeg') }}" class="absolute inset-0 w-full h-full object-cover object-top rounded-full border-4 border-white shadow-lg filter grayscale group-hover:grayscale-0 transition-all duration-500" alt="Bintang">
                    </div>
                    <div class="inline-flex px-4 py-1.5 bg-cyan-50 text-cyan-600 font-bold text-[10px] uppercase tracking-widest rounded-full mb-5">Backend Developer</div>
                    <h3 class="text-xl font-black text-slate-900 mb-3 leading-tight">Bintang Naufal<br>Fayazzi</h3>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed mb-8 flex-grow">
                        Arsitek di balik ketahanan sistem. Merancang struktur database dan API berkinerja tinggi.
                    </p>
                    <div class="flex items-center gap-3 pt-6 border-t border-slate-100 w-full justify-center">
                        <img src="{{ asset('images/universities/usk.svg') }}" class="w-8 h-8 object-contain filter grayscale group-hover:grayscale-0 transition-all duration-500">
                        <div class="text-left">
                            <p class="text-slate-900 font-bold text-xs">Universitas Syiah Kuala</p>
                            <p class="text-slate-400 text-[10px]">Manajemen Informatika</p>
                        </div>
                    </div>
                </div>

                {{-- Member 3 --}}
                <div class="bg-white rounded-[3rem] p-8 shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-slate-100 hover:shadow-[0_40px_80px_rgba(139,92,246,0.08)] hover:-translate-y-2 transition-all duration-500 flex flex-col items-center text-center group" data-aos="fade-up" data-aos-delay="300">
                    <div class="relative w-36 h-36 mb-8">
                        <div class="absolute inset-0 bg-violet-100 rounded-full scale-110 group-hover:scale-125 transition-transform duration-500 opacity-50"></div>
                        <img src="{{ asset('images/team/member-3.jpeg') }}" class="absolute inset-0 w-full h-full object-cover object-top rounded-full border-4 border-white shadow-lg filter grayscale group-hover:grayscale-0 transition-all duration-500" alt="Riyan">
                    </div>
                    <div class="inline-flex px-4 py-1.5 bg-violet-50 text-violet-600 font-bold text-[10px] uppercase tracking-widest rounded-full mb-5">Project Lead & UI/UX</div>
                    <h3 class="text-xl font-black text-slate-900 mb-3 leading-tight">Riyan Arya<br>Syahputra</h3>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed mb-8 flex-grow">
                        Mengatur visi strategi platform dan merancang alur pengalaman (UX) yang paling sesuai.
                    </p>
                    <div class="flex items-center gap-3 pt-6 border-t border-slate-100 w-full justify-center">
                        <img src="{{ asset('images/universities/uin-arraniry.svg') }}" class="w-8 h-8 object-contain filter grayscale group-hover:grayscale-0 transition-all duration-500">
                        <div class="text-left">
                            <p class="text-slate-900 font-bold text-xs">UIN Ar-Raniry</p>
                            <p class="text-slate-400 text-[10px]">Teknologi Informasi</p>
                        </div>
                    </div>
                </div>

                {{-- Member 4 --}}
                <div class="bg-white rounded-[3rem] p-8 shadow-[0_20px_50px_rgba(0,0,0,0.03)] border border-slate-100 hover:shadow-[0_40px_80px_rgba(245,158,11,0.08)] hover:-translate-y-2 transition-all duration-500 flex flex-col items-center text-center group" data-aos="fade-up" data-aos-delay="400">
                    <div class="relative w-36 h-36 mb-8">
                        <div class="absolute inset-0 bg-amber-100 rounded-full scale-110 group-hover:scale-125 transition-transform duration-500 opacity-50"></div>
                        <img src="{{ asset('images/team/member-4.jpeg') }}" class="absolute inset-0 w-full h-full object-cover object-top rounded-full border-4 border-white shadow-lg filter grayscale group-hover:grayscale-0 transition-all duration-500" alt="Risky">
                    </div>
                    <div class="inline-flex px-4 py-1.5 bg-amber-50 text-amber-600 font-bold text-[10px] uppercase tracking-widest rounded-full mb-5">Business Analyst</div>
                    <h3 class="text-xl font-black text-slate-900 mb-3 leading-tight">Risky Husnaa<br>Mulyadi</h3>
                    <p class="text-slate-500 font-medium text-sm leading-relaxed mb-8 flex-grow">
                        Menerjemahkan aturan medis ke dalam logika. Memastikan setiap algoritma valid secara klinis.
                    </p>
                    <div class="flex items-center gap-3 pt-6 border-t border-slate-100 w-full justify-center">
                        <img src="{{ asset('images/universities/uin-arraniry.svg') }}" class="w-8 h-8 object-contain filter grayscale group-hover:grayscale-0 transition-all duration-500">
                        <div class="text-left">
                            <p class="text-slate-900 font-bold text-xs">UIN Ar-Raniry</p>
                            <p class="text-slate-400 text-[10px]">Teknologi Informasi</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>



    {{-- SECTION 4: STATS (Floating Cards) --}}
    <section class="py-24 bg-slate-50 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                
                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 text-center hover:shadow-md hover:-translate-y-1 hover:border-emerald-200 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-14 h-14 bg-emerald-50 text-emerald-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:bg-emerald-100 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h4 class="text-4xl lg:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-cyan-500 mb-2">4+</h4>
                    <p class="text-slate-500 font-bold text-sm">Dedicated Students</p>
                </div>

                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 text-center hover:shadow-md hover:-translate-y-1 hover:border-sky-200 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-14 h-14 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:bg-sky-100 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                    </div>
                    <h4 class="text-4xl lg:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-sky-600 to-blue-500 mb-2">2+</h4>
                    <p class="text-slate-500 font-bold text-sm">Top Universities</p>
                </div>

                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 text-center hover:shadow-md hover:-translate-y-1 hover:border-violet-200 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-14 h-14 bg-violet-50 text-violet-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:bg-violet-100 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h4 class="text-4xl lg:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-violet-600 to-fuchsia-500 mb-2">1+</h4>
                    <p class="text-slate-500 font-bold text-sm">Shared Mission</p>
                </div>

                <div class="bg-white rounded-3xl p-8 shadow-sm border border-slate-100 text-center hover:shadow-md hover:-translate-y-1 hover:border-amber-200 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 group-hover:bg-amber-100 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="text-4xl lg:text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-amber-500 to-orange-500 mb-2">100%</h4>
                    <p class="text-slate-500 font-bold text-sm">Commitment</p>
                </div>

            </div>
        </div>
    </section>

    {{-- SECTION 5: CTA (Massive anchor box) --}}
    <section class="py-24 bg-white relative">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="bg-gradient-to-br from-slate-900 via-slate-800 to-slate-900 rounded-[3rem] p-12 lg:p-20 text-center relative overflow-hidden shadow-2xl" data-aos="zoom-in">
                
                {{-- Decorative Glows inside the CTA --}}
                <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-emerald-500/20 rounded-full blur-[80px] -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-[500px] h-[500px] bg-cyan-500/20 rounded-full blur-[80px] translate-y-1/2 -translate-x-1/3 pointer-events-none"></div>
                
                <div class="relative z-10">
                    <span class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm border border-white/10 rounded-full text-emerald-300 font-bold tracking-widest uppercase text-xs mb-8">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                        Ready To Start?
                    </span>
                    
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-8 tracking-tight">
                        Experience The Future Of<br>Posyandu Management
                    </h2>
                    
                    <p class="text-slate-300 font-medium text-lg lg:text-xl mb-12 max-w-2xl mx-auto leading-relaxed">
                        Bergabunglah bersama kami dalam misi membangun sistem kesehatan digital yang merata dan berdampak untuk seluruh Indonesia.
                    </p>
                    
                    <a href="{{ route('login') }}" class="inline-flex items-center gap-3 px-10 py-5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white font-bold text-lg rounded-full shadow-[0_10px_30px_rgba(16,185,129,0.3)] hover:shadow-[0_15px_40px_rgba(16,185,129,0.5)] hover:-translate-y-1 transition-all duration-300 group">
                        Akses Portal Sekarang
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
    </section>

    @include('partials.public-footer')

@endsection
