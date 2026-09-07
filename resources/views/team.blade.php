@extends('layouts.public')

@section('title', 'Meet Our Team | NutriGen')

@section('content')

    {{-- SECTION 1: HERO (Clean, airy, with massive vibrant gradient shape like the reference) --}}
    <section class="relative pt-32 pb-24 lg:pt-48 lg:pb-32 overflow-hidden bg-white">
        {{-- Huge Gradient Shape bleeding off the right edge (Reference 1 style) --}}
        <div class="absolute top-0 right-0 w-[600px] h-[600px] lg:w-[1000px] lg:h-[1000px] bg-gradient-to-br from-emerald-400 via-teal-500 to-cyan-500 rounded-full translate-x-1/3 -translate-y-1/4 opacity-90 hidden md:block"></div>
        {{-- Mobile version of the shape --}}
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-gradient-to-br from-emerald-400 via-teal-500 to-cyan-500 rounded-full translate-x-1/2 -translate-y-1/2 opacity-90 md:hidden"></div>
        
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

            {{-- Right Content (Floating Cards simulating the reference layout) --}}
            <div class="w-full lg:w-1/2 mt-16 lg:mt-0 relative hidden lg:block" data-aos="fade-left" data-aos-delay="400">
                <div class="relative w-full aspect-square max-w-[600px] ml-auto">
                    {{-- Decorative floating card 1 --}}
                    <div class="absolute top-10 left-10 bg-white/90 backdrop-blur-xl p-6 rounded-3xl shadow-[0_20px_40px_rgba(0,0,0,0.1)] border border-white w-64 animate-[bounce_10s_infinite_alternate]">
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
                            <img class="w-10 h-10 rounded-full border-2 border-white object-cover" src="{{ asset('images/team/member-1.png') }}">
                            <img class="w-10 h-10 rounded-full border-2 border-white object-cover" src="{{ asset('images/team/member-2.jpeg') }}">
                            <img class="w-10 h-10 rounded-full border-2 border-white object-cover" src="{{ asset('images/team/member-3.jpeg') }}">
                            <img class="w-10 h-10 rounded-full border-2 border-white object-cover" src="{{ asset('images/team/member-4.jpeg') }}">
                        </div>
                    </div>

                    {{-- Decorative floating card 2 --}}
                    <div class="absolute bottom-20 right-10 bg-white/90 backdrop-blur-xl p-6 rounded-3xl shadow-[0_20px_40px_rgba(0,0,0,0.1)] border border-white w-72 animate-[bounce_12s_infinite_alternate-reverse]">
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

    {{-- SECTION 3: THE TEAM (Soft, floating cards) --}}
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

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">

                {{-- Member 1 --}}
                <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-slate-100 hover:shadow-[0_30px_60px_rgba(16,185,129,0.08)] hover:-translate-y-2 transition-all duration-500 flex flex-col sm:flex-row gap-8 items-center sm:items-start group" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-40 h-40 shrink-0 relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-[2rem] rotate-6 group-hover:rotate-12 transition-transform duration-500 opacity-20"></div>
                        <img src="{{ asset('images/team/member-1.png') }}" class="absolute inset-0 w-full h-full object-cover object-top rounded-[2rem] shadow-lg bg-slate-100" alt="Naufal">
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <div class="inline-flex px-3 py-1 bg-emerald-50 text-emerald-600 font-bold text-[10px] uppercase tracking-widest rounded-full mb-4">Backend Developer</div>
                        <h3 class="text-2xl font-black text-slate-900 mb-3">M. Naufal Alifaturafif</h3>
                        <p class="text-slate-500 font-medium text-sm leading-relaxed mb-6">
                            Arsitek di balik ketahanan sistem. Merancang struktur database dan API berkinerja tinggi untuk sinkronisasi data seketika.
                        </p>
                        <div class="flex items-center justify-center sm:justify-start gap-3">
                            <img src="{{ asset('images/universities/uin-arraniry.svg') }}" class="w-8 h-8 object-contain">
                            <div class="text-left">
                                <p class="text-slate-900 font-bold text-xs">UIN Ar-Raniry</p>
                                <p class="text-slate-400 text-[10px]">Teknologi Informasi</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Member 2 --}}
                <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-slate-100 hover:shadow-[0_30px_60px_rgba(6,182,212,0.08)] hover:-translate-y-2 transition-all duration-500 flex flex-col sm:flex-row gap-8 items-center sm:items-start group" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-40 h-40 shrink-0 relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-400 to-sky-500 rounded-[2rem] rotate-6 group-hover:rotate-12 transition-transform duration-500 opacity-20"></div>
                        <img src="{{ asset('images/team/member-2.jpeg') }}" class="absolute inset-0 w-full h-full object-cover object-top rounded-[2rem] shadow-lg bg-slate-100" alt="Bintang">
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <div class="inline-flex px-3 py-1 bg-cyan-50 text-cyan-600 font-bold text-[10px] uppercase tracking-widest rounded-full mb-4">Frontend Developer</div>
                        <h3 class="text-2xl font-black text-slate-900 mb-3">Bintang Naufal F.</h3>
                        <p class="text-slate-500 font-medium text-sm leading-relaxed mb-6">
                            Mengubah kompleksitas menjadi antarmuka yang indah. Memastikan interaksi terasa mulus, responsif, dan mudah dipahami.
                        </p>
                        <div class="flex items-center justify-center sm:justify-start gap-3">
                            <img src="{{ asset('images/universities/usk.svg') }}" class="w-8 h-8 object-contain">
                            <div class="text-left">
                                <p class="text-slate-900 font-bold text-xs">Universitas Syiah Kuala</p>
                                <p class="text-slate-400 text-[10px]">Manajemen Informatika</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Member 3 --}}
                <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-slate-100 hover:shadow-[0_30px_60px_rgba(139,92,246,0.08)] hover:-translate-y-2 transition-all duration-500 flex flex-col sm:flex-row gap-8 items-center sm:items-start group" data-aos="fade-up" data-aos-delay="300">
                    <div class="w-40 h-40 shrink-0 relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-violet-400 to-purple-500 rounded-[2rem] rotate-6 group-hover:rotate-12 transition-transform duration-500 opacity-20"></div>
                        <img src="{{ asset('images/team/member-3.jpeg') }}" class="absolute inset-0 w-full h-full object-cover object-top rounded-[2rem] shadow-lg bg-slate-100" alt="Riyan">
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <div class="inline-flex px-3 py-1 bg-violet-50 text-violet-600 font-bold text-[10px] uppercase tracking-widest rounded-full mb-4">Project Lead & UI/UX</div>
                        <h3 class="text-2xl font-black text-slate-900 mb-3">Riyan Arya Syahputra</h3>
                        <p class="text-slate-500 font-medium text-sm leading-relaxed mb-6">
                            Mengatur visi strategi platform dan merancang alur pengalaman (UX) yang paling sesuai dengan operasional di Puskesmas.
                        </p>
                        <div class="flex items-center justify-center sm:justify-start gap-3">
                            <img src="{{ asset('images/universities/uin-arraniry.svg') }}" class="w-8 h-8 object-contain">
                            <div class="text-left">
                                <p class="text-slate-900 font-bold text-xs">UIN Ar-Raniry</p>
                                <p class="text-slate-400 text-[10px]">Teknologi Informasi</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Member 4 --}}
                <div class="bg-white rounded-[2.5rem] p-8 sm:p-10 shadow-[0_20px_50px_rgba(0,0,0,0.04)] border border-slate-100 hover:shadow-[0_30px_60px_rgba(245,158,11,0.08)] hover:-translate-y-2 transition-all duration-500 flex flex-col sm:flex-row gap-8 items-center sm:items-start group" data-aos="fade-up" data-aos-delay="400">
                    <div class="w-40 h-40 shrink-0 relative">
                        <div class="absolute inset-0 bg-gradient-to-br from-amber-400 to-orange-500 rounded-[2rem] rotate-6 group-hover:rotate-12 transition-transform duration-500 opacity-20"></div>
                        <img src="{{ asset('images/team/member-4.jpeg') }}" class="absolute inset-0 w-full h-full object-cover object-top rounded-[2rem] shadow-lg bg-slate-100" alt="Risky">
                    </div>
                    <div class="flex-1 text-center sm:text-left">
                        <div class="inline-flex px-3 py-1 bg-amber-50 text-amber-600 font-bold text-[10px] uppercase tracking-widest rounded-full mb-4">Business Analyst</div>
                        <h3 class="text-2xl font-black text-slate-900 mb-3">Risky Husnaa Mulyadi</h3>
                        <p class="text-slate-500 font-medium text-sm leading-relaxed mb-6">
                            Menerjemahkan aturan medis ke dalam logika pemrograman. Memastikan setiap algoritma akurat dan valid secara klinis.
                        </p>
                        <div class="flex items-center justify-center sm:justify-start gap-3">
                            <img src="{{ asset('images/universities/uin-arraniry.svg') }}" class="w-8 h-8 object-contain">
                            <div class="text-left">
                                <p class="text-slate-900 font-bold text-xs">UIN Ar-Raniry</p>
                                <p class="text-slate-400 text-[10px]">Teknologi Informasi</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- SECTION 4: STATS & PARTNERS (Reference 2 style) --}}
    <section class="py-20 bg-slate-50 border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 divide-x divide-slate-200">
                <div class="text-center px-4" data-aos="fade-up" data-aos-delay="100">
                    <h4 class="text-4xl lg:text-5xl font-black text-slate-900 mb-2">4 <span class="text-emerald-500">+</span></h4>
                    <p class="text-slate-500 font-medium text-sm">Dedicated Students</p>
                </div>
                <div class="text-center px-4" data-aos="fade-up" data-aos-delay="200">
                    <h4 class="text-4xl lg:text-5xl font-black text-slate-900 mb-2">2 <span class="text-emerald-500">+</span></h4>
                    <p class="text-slate-500 font-medium text-sm">Top Universities</p>
                </div>
                <div class="text-center px-4" data-aos="fade-up" data-aos-delay="300">
                    <h4 class="text-4xl lg:text-5xl font-black text-slate-900 mb-2">1 <span class="text-emerald-500">+</span></h4>
                    <p class="text-slate-500 font-medium text-sm">Shared Mission</p>
                </div>
                <div class="text-center px-4" data-aos="fade-up" data-aos-delay="400">
                    <h4 class="text-4xl lg:text-5xl font-black text-slate-900 mb-2">100 <span class="text-emerald-500">%</span></h4>
                    <p class="text-slate-500 font-medium text-sm">Commitment</p>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 5: CTA --}}
    <section class="py-24 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-gradient-to-bl from-emerald-100 to-transparent rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-gradient-to-tr from-cyan-100 to-transparent rounded-full blur-3xl"></div>
        
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="zoom-in">
            <span class="text-emerald-600 font-bold tracking-widest uppercase text-xs mb-4 block">Ready To Start?</span>
            <h2 class="text-3xl md:text-5xl font-black text-slate-900 mb-6">Experience The Future Of<br>Posyandu Management</h2>
            <p class="text-slate-500 font-medium text-lg mb-10 max-w-2xl mx-auto">
                Bergabunglah bersama kami dalam misi membangun sistem kesehatan digital yang merata dan berdampak untuk seluruh Indonesia.
            </p>
            <a href="{{ route('login') }}" class="inline-flex items-center gap-2 px-10 py-5 bg-slate-900 hover:bg-emerald-600 text-white font-bold rounded-full shadow-[0_10px_30px_rgba(15,23,42,0.2)] hover:shadow-[0_15px_40px_rgba(16,185,129,0.3)] hover:-translate-y-1 transition-all duration-300">
                Akses Portal
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
            </a>
        </div>
    </section>

    @include('partials.public-footer')

@endsection
