@extends('layouts.public')

@section('title', 'Meet Our Team | NutriGen')

@section('content')

    {{-- SECTION 1: Hero --}}
    <section class="relative pt-32 pb-24 lg:pt-48 lg:pb-32 overflow-hidden bg-gradient-to-br from-emerald-700 via-emerald-600 to-cyan-600">
        {{-- Grid texture --}}
        <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:24px_24px]"></div>
        {{-- Glow --}}
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[500px] bg-white/10 rounded-[100%] blur-[100px] pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 bg-cyan-300/20 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-5xl mx-auto px-6 lg:px-8 relative z-10 text-center flex flex-col items-center">

            <div class="inline-flex items-center gap-3 px-4 py-2 bg-white/20 backdrop-blur-sm rounded-full border border-white/30 shadow-sm mb-8" data-aos="fade-up" data-aos-delay="50">
                <span class="w-2 h-2 rounded-full bg-white animate-pulse"></span>
                <span class="text-xs font-bold text-white tracking-wide uppercase">Project Status</span>
                <div class="w-px h-3 bg-white/40 mx-1"></div>
                <span class="text-xs font-bold text-cyan-200 tracking-wide uppercase">Version 1.0 MVP</span>
            </div>

            <h1 class="text-4xl sm:text-5xl lg:text-7xl font-extrabold tracking-tight text-white leading-[1.05] mb-6" data-aos="fade-up">
                Meet The <span class="text-cyan-300">Team</span>
            </h1>
            <p class="text-lg lg:text-xl font-medium text-emerald-50 mb-10 max-w-2xl mx-auto leading-relaxed" data-aos="fade-up" data-aos-delay="100">
                Behind NutriGen is a multidisciplinary student team committed to building impactful digital healthcare solutions untuk Indonesia.
            </p>

            <div class="flex flex-wrap items-center justify-center gap-4" data-aos="fade-up" data-aos-delay="150">
                <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm border border-white/30 px-5 py-2.5 rounded-2xl">
                    <span class="text-white font-extrabold text-xl tracking-tight">4</span>
                    <span class="text-white/75 font-bold text-sm">Students</span>
                </div>
                <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm border border-white/30 px-5 py-2.5 rounded-2xl">
                    <span class="text-white font-extrabold text-xl tracking-tight">2</span>
                    <span class="text-white/75 font-bold text-sm">Universities</span>
                </div>
                <div class="flex items-center gap-2 bg-white/20 backdrop-blur-sm border border-white/30 px-5 py-2.5 rounded-2xl">
                    <span class="text-white font-extrabold text-xl tracking-tight">1</span>
                    <span class="text-white/75 font-bold text-sm">Mission</span>
                </div>
            </div>
        </div>

        {{-- Wave divider --}}
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full" preserveAspectRatio="none">
                <path d="M0 60L1440 60L1440 20C1200 60 960 0 720 20C480 40 240 0 0 20L0 60Z" fill="white"/>
            </svg>
        </div>
    </section>

    {{-- SECTION: Our Mission & Values --}}
    <section class="py-24 bg-white border-b border-slate-100">
        <div class="max-w-5xl mx-auto px-6 lg:px-8 text-center">

            <div class="max-w-3xl mx-auto mb-16" data-aos="fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-50 border border-emerald-200 rounded-full text-emerald-700 text-xs font-bold uppercase tracking-widest mb-6">Our Mission</div>
                <p class="text-2xl md:text-3xl font-medium text-slate-900 leading-snug">
                    NutriGen was created to help Posyandu and Puskesmas monitor child nutrition more efficiently through digital collaboration, evidence-based decisions, and accessible technology.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5" data-aos="fade-up" data-aos-delay="100">
                
                <div class="bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-2xl p-6 text-left shadow-lg hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-10 h-10 mb-4 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-white text-sm">Human-Centered Design</h3>
                </div>

                <div class="bg-gradient-to-br from-sky-500 to-blue-600 rounded-2xl p-6 text-left shadow-lg hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-10 h-10 mb-4 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="font-bold text-white text-sm">Evidence-Based Healthcare</h3>
                </div>

                <div class="bg-gradient-to-br from-indigo-500 to-purple-700 rounded-2xl p-6 text-left shadow-lg hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-10 h-10 mb-4 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    </div>
                    <h3 class="font-bold text-white text-sm">Collaborative Innovation</h3>
                </div>

                <div class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-2xl p-6 text-left shadow-lg hover:-translate-y-1 transition-transform duration-300">
                    <div class="w-10 h-10 mb-4 bg-white/20 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="font-bold text-white text-sm">Scalable Technology</h3>
                </div>

            </div>

        </div>
    </section>

    {{-- SECTION 2: Team Members Grid --}}
    <section class="py-24 lg:py-32 bg-gradient-to-b from-slate-50 to-white border-b border-slate-100 relative overflow-hidden">
        <div class="absolute top-0 left-0 w-[500px] h-[500px] bg-emerald-100/50 rounded-full blur-[120px] -translate-x-1/2 -translate-y-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-[400px] h-[400px] bg-cyan-100/50 rounded-full blur-[100px] translate-x-1/3 translate-y-1/3 pointer-events-none"></div>
        <div class="absolute inset-0 bg-[linear-gradient(to_right,#8080800a_1px,transparent_1px),linear-gradient(to_bottom,#8080800a_1px,transparent_1px)] bg-[size:32px_32px]"></div>

        <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">

            <div class="text-center mb-16" data-aos="fade-up">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-50 border border-emerald-200 rounded-full text-emerald-700 text-xs font-bold uppercase tracking-widest mb-4">The People</div>
                <h2 class="text-3xl lg:text-4xl font-extrabold text-slate-900 tracking-tight">Our Team Members</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-10 items-stretch">

                {{-- Member 1: Lead Developer --}}
                <div class="relative group rounded-[2rem] overflow-hidden flex flex-col" data-aos="fade-up">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-500 to-cyan-400 z-10"></div>
                    <div class="bg-white border border-slate-200 rounded-[2rem] p-8 lg:p-10 shadow-[0_10px_30px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_50px_rgba(16,185,129,0.12)] hover:-translate-y-1 hover:border-emerald-200 transition-all duration-400 flex flex-col flex-1">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-6">
                            <div class="w-28 h-28 shrink-0 rounded-2xl overflow-hidden relative ring-4 ring-emerald-100 shadow-lg">
                                <img src="{{ asset('images/team/member-1.png') }}" alt="Muhammad Naufal Alifaturafif" class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105" loading="lazy">
                            </div>
                            <div class="text-center sm:text-left">
                                <h3 class="text-2xl font-bold tracking-tight text-slate-900 leading-tight mb-2">Muhammad Naufal<br>Alifaturafif</h3>
                                <span class="inline-flex px-3 py-1 bg-emerald-500 text-white rounded-full text-xs font-bold shadow-sm">Backend Developer</span>
                            </div>
                        </div>
                        <p class="text-slate-600 font-medium leading-relaxed text-sm mb-6">
                            Architected and developed the robust backend infrastructure, RESTful APIs, and database models.
                        </p>
                        <div class="mt-auto pt-5 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 bg-emerald-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">UIN Ar-Raniry</p>
                                    <p class="text-[10px] text-slate-500">Teknologi Informasi</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="rounded-lg bg-emerald-50 border border-emerald-100 text-xs font-medium text-emerald-700 px-2.5 py-1">System Architecture</span>
                                <span class="rounded-lg bg-emerald-50 border border-emerald-100 text-xs font-medium text-emerald-700 px-2.5 py-1">Backend API</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Member 2: UI/UX Designer --}}
                <div class="relative group rounded-[2rem] overflow-hidden flex flex-col" data-aos="fade-up" data-aos-delay="100">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-sky-400 to-cyan-400 z-10"></div>
                    <div class="bg-white border border-slate-200 rounded-[2rem] p-8 lg:p-10 shadow-[0_10px_30px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_50px_rgba(6,182,212,0.12)] hover:-translate-y-1 hover:border-sky-200 transition-all duration-400 flex flex-col flex-1">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-6">
                            <div class="w-28 h-28 shrink-0 rounded-2xl overflow-hidden relative ring-4 ring-sky-100 shadow-lg">
                                <img src="{{ asset('images/team/member-2.jpeg') }}" alt="Bintang Naufal Fayazzi" class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105" loading="lazy">
                            </div>
                            <div class="text-center sm:text-left">
                                <h3 class="text-2xl font-bold tracking-tight text-slate-900 leading-tight mb-2">Bintang Naufal<br>Fayazzi</h3>
                                <span class="inline-flex px-3 py-1 bg-sky-500 text-white rounded-full text-xs font-bold shadow-sm">Frontend Developer</span>
                            </div>
                        </div>
                        <p class="text-slate-600 font-medium leading-relaxed text-sm mb-6">
                            Developed the responsive, interactive, and user-friendly web interface using Tailwind CSS and Blade.
                        </p>
                        <div class="mt-auto pt-5 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 bg-sky-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-sky-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">Universitas Syiah Kuala</p>
                                    <p class="text-[10px] text-slate-500">D3 Manajemen Informatika</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="rounded-lg bg-sky-50 border border-sky-100 text-xs font-medium text-sky-700 px-2.5 py-1">Frontend Dev</span>
                                <span class="rounded-lg bg-sky-50 border border-sky-100 text-xs font-medium text-sky-700 px-2.5 py-1">Responsive UI</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Member 3: AI Engineer --}}
                <div class="relative group rounded-[2rem] overflow-hidden flex flex-col" data-aos="fade-up" data-aos-delay="200">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-violet-500 to-purple-500 z-10"></div>
                    <div class="bg-white border border-slate-200 rounded-[2rem] p-8 lg:p-10 shadow-[0_10px_30px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_50px_rgba(139,92,246,0.12)] hover:-translate-y-1 hover:border-violet-200 transition-all duration-400 flex flex-col flex-1">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-6">
                            <div class="w-28 h-28 shrink-0 rounded-2xl overflow-hidden relative ring-4 ring-violet-100 shadow-lg">
                                <img src="{{ asset('images/team/member-3.jpeg') }}" alt="Riyan Arya Syahputra" class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105" loading="lazy">
                            </div>
                            <div class="text-center sm:text-left">
                                <h3 class="text-2xl font-bold tracking-tight text-slate-900 leading-tight mb-2">Riyan Arya<br>Syahputra</h3>
                                <span class="inline-flex px-3 py-1 bg-violet-500 text-white rounded-full text-xs font-bold shadow-sm">Project Lead & UI/UX Designer</span>
                            </div>
                        </div>
                        <p class="text-slate-600 font-medium leading-relaxed text-sm mb-6">
                            Spearheaded the project vision and designed intuitive, human-centered workflows and interfaces.
                        </p>
                        <div class="mt-auto pt-5 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 bg-violet-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-violet-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">UIN Ar-Raniry</p>
                                    <p class="text-[10px] text-slate-500">Teknologi Informasi</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="rounded-lg bg-violet-50 border border-violet-100 text-xs font-medium text-violet-700 px-2.5 py-1">Project Mgmt</span>
                                <span class="rounded-lg bg-violet-50 border border-violet-100 text-xs font-medium text-violet-700 px-2.5 py-1">UI/UX Design</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Member 4: Data Analyst --}}
                <div class="relative group rounded-[2rem] overflow-hidden flex flex-col" data-aos="fade-up" data-aos-delay="300">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-400 to-orange-400 z-10"></div>
                    <div class="bg-white border border-slate-200 rounded-[2rem] p-8 lg:p-10 shadow-[0_10px_30px_rgba(0,0,0,0.05)] hover:shadow-[0_20px_50px_rgba(245,158,11,0.12)] hover:-translate-y-1 hover:border-amber-200 transition-all duration-400 flex flex-col flex-1">
                        <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-6">
                            <div class="w-28 h-28 shrink-0 rounded-2xl overflow-hidden relative ring-4 ring-amber-100 shadow-lg">
                                <img src="{{ asset('images/team/member-4.jpeg') }}" alt="Risky Husnaa Mulyadi" class="w-full h-full object-cover object-top transition-transform duration-500 group-hover:scale-105" loading="lazy">
                            </div>
                            <div class="text-center sm:text-left">
                                <h3 class="text-2xl font-bold tracking-tight text-slate-900 leading-tight mb-2">Risky Husnaa<br>Mulyadi</h3>
                                <span class="inline-flex px-3 py-1 bg-amber-500 text-white rounded-full text-xs font-bold shadow-sm">Business Analyst & Domain Expert</span>
                            </div>
                        </div>
                        <p class="text-slate-600 font-medium leading-relaxed text-sm mb-6">
                            Bridged healthcare requirements with technical solutions, ensuring alignment with WHO standards.
                        </p>
                        <div class="mt-auto pt-5 border-t border-slate-100 flex flex-wrap items-center justify-between gap-3">
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 bg-amber-50 rounded-lg flex items-center justify-center">
                                    <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.697 50.697 0 0112 13.489a50.702 50.702 0 017.74-3.342M6.75 15a.75.75 0 100-1.5.75.75 0 000 1.5zm0 0v-3.675A55.378 55.378 0 0112 8.443m-7.007 11.55A5.981 5.981 0 006.75 15.75v-1.5"/></svg>
                                </div>
                                <div>
                                    <p class="text-xs font-bold text-slate-800">UIN Ar-Raniry</p>
                                    <p class="text-[10px] text-slate-500">Teknologi Informasi</p>
                                </div>
                            </div>
                            <div class="flex flex-wrap gap-2">
                                <span class="rounded-lg bg-amber-50 border border-amber-100 text-xs font-medium text-amber-700 px-2.5 py-1">Business Analysis</span>
                                <span class="rounded-lg bg-amber-50 border border-amber-100 text-xs font-medium text-amber-700 px-2.5 py-1">Domain Expert</span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- SECTION 3: Timeline Journey --}}
    <section class="py-24 bg-white border-b border-slate-100 overflow-hidden">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="mb-16 md:mb-20 flex flex-col md:flex-row md:items-end justify-between gap-6">
                <div class="max-w-2xl" data-aos="fade-up">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-50 border border-emerald-200 rounded-full text-emerald-700 text-xs font-bold uppercase tracking-widest mb-4">Our Journey</div>
                    <h3 class="text-3xl lg:text-4xl font-bold tracking-tight text-slate-900">Perjalanan NutriGen</h3>
                </div>
                <div data-aos="fade-up" data-aos-delay="100">
                    <p class="text-slate-500 font-medium max-w-sm md:text-right leading-relaxed">Dari sebuah ide sederhana hingga menjadi platform komprehensif untuk Indonesia bebas stunting.</p>
                </div>
            </div>

            <div class="relative" data-aos="fade-up" data-aos-delay="200">
                <div class="hidden md:block absolute top-6 left-0 w-full h-1 bg-gradient-to-r from-emerald-200 via-cyan-300 to-emerald-200 z-0 rounded-full"></div>

                <div class="grid grid-cols-1 md:grid-cols-5 gap-8 md:gap-4 relative z-10">
                    @php
                        $steps = [
                            ['label' => 'Problem', 'desc' => 'Mengidentifikasi celah dalam pencatatan dan sinkronisasi data stunting di Posyandu dan Puskesmas.', 'color' => 'emerald', 'active' => false],
                            ['label' => 'Research', 'desc' => 'Mengkaji standar buku KIA, pedoman WHO, serta alur operasional di lapangan.', 'color' => 'cyan', 'active' => false],
                            ['label' => 'Design', 'desc' => 'Merancang wireframe, antarmuka interaktif, dan arsitektur basis data.', 'color' => 'violet', 'active' => false],
                            ['label' => 'Development', 'desc' => 'Membangun sistem inti terintegrasi menggunakan Laravel dan Tailwind CSS.', 'color' => 'amber', 'active' => false],
                            ['label' => 'Impact', 'desc' => 'Menghasilkan platform digital yang berdampak nyata bagi kesehatan balita Indonesia.', 'color' => 'emerald', 'active' => true],
                        ];
                        $colorMap = [
                            'emerald' => ['dot' => 'bg-emerald-500', 'ring' => 'ring-emerald-200', 'text' => 'text-emerald-700', 'title' => 'text-emerald-700'],
                            'cyan'    => ['dot' => 'bg-cyan-500',    'ring' => 'ring-cyan-200',    'text' => 'text-cyan-700',    'title' => 'text-slate-900'],
                            'violet'  => ['dot' => 'bg-violet-500',  'ring' => 'ring-violet-200',  'text' => 'text-violet-700',  'title' => 'text-slate-900'],
                            'amber'   => ['dot' => 'bg-amber-500',   'ring' => 'ring-amber-200',   'text' => 'text-amber-700',   'title' => 'text-slate-900'],
                        ];
                    @endphp

                    @foreach($steps as $step)
                        @php $c = $colorMap[$step['color']]; @endphp
                        <div class="flex flex-row md:flex-col items-center md:items-start text-left gap-6 group">
                            <div class="w-12 h-12 rounded-full flex items-center justify-center shadow-sm shrink-0 relative
                                {{ $step['active'] ? 'bg-emerald-50 ring-4 ring-emerald-200' : 'bg-white border-4 border-slate-100 group-hover:border-emerald-100 group-hover:bg-emerald-50' }}
                                hover:-translate-y-1 hover:shadow-xl transition-all duration-300">
                                @if($step['active'])
                                    <div class="absolute inset-0 rounded-full ring-4 ring-emerald-400/30 animate-pulse"></div>
                                    <div class="w-4 h-4 {{ $c['dot'] }} rounded-full"></div>
                                @else
                                    <div class="w-3 h-3 bg-slate-300 rounded-full group-hover:{{ $c['dot'] }} transition-colors"></div>
                                @endif
                            </div>
                            <div>
                                <h4 class="text-lg font-bold mb-1 {{ $step['active'] ? $c['title'] : 'text-slate-900' }}">{{ $step['label'] }}</h4>
                                <p class="text-sm text-slate-500 font-medium leading-relaxed">{{ $step['desc'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 4: Cross University --}}
    <section class="py-24 bg-slate-50 border-b border-slate-100">
        <div class="max-w-6xl mx-auto px-6 lg:px-8 text-center">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-50 border border-emerald-200 rounded-full text-emerald-700 text-xs font-bold uppercase tracking-widest mb-6" data-aos="fade-up">Cross-University Collaboration</div>
            <p class="text-slate-500 font-medium text-lg max-w-2xl mx-auto mb-16 leading-relaxed" data-aos="fade-up" data-aos-delay="50">
                Built through collaboration between Universitas Islam Negeri Ar-Raniry and Universitas Syiah Kuala to create impactful digital healthcare solutions.
            </p>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 items-stretch">
                <div class="relative group rounded-[2.5rem] overflow-hidden flex flex-col" data-aos="fade-up" data-aos-delay="100">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-500 to-cyan-400"></div>
                    <div class="bg-white border border-slate-200 rounded-[2.5rem] p-10 lg:p-14 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_20px_50px_rgba(16,185,129,0.1)] hover:-translate-y-1 transition-all duration-500 flex flex-col flex-1 items-center">
                        <div class="w-28 h-28 bg-emerald-50 rounded-3xl flex items-center justify-center mb-8 border border-emerald-100 shadow-sm group-hover:scale-110 group-hover:-rotate-3 transition-all duration-500">
                            <img src="{{ asset('images/universities/uin-arraniry.svg') }}" alt="UIN Ar-Raniry Logo" class="w-20 h-20 max-w-full max-h-full object-contain" loading="lazy" decoding="async">
                        </div>
                        <h3 class="text-2xl lg:text-3xl font-bold tracking-tight text-slate-900 mb-3 group-hover:text-emerald-700 transition-colors">Universitas Islam Negeri Ar-Raniry</h3>
                        <p class="text-slate-500 font-medium mb-10 text-lg leading-relaxed">Program Studi Teknologi Informasi</p>
                        <div class="mt-auto px-6 py-2.5 bg-emerald-500 text-white font-bold rounded-2xl text-sm shadow-[0_4px_12px_rgba(16,185,129,0.3)]">
                            3 Students
                        </div>
                    </div>
                </div>

                <div class="relative group rounded-[2.5rem] overflow-hidden flex flex-col" data-aos="fade-up" data-aos-delay="200">
                    <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-sky-400 to-cyan-400"></div>
                    <div class="bg-white border border-slate-200 rounded-[2.5rem] p-10 lg:p-14 shadow-[0_4px_20px_rgba(0,0,0,0.03)] hover:shadow-[0_20px_50px_rgba(6,182,212,0.1)] hover:-translate-y-1 transition-all duration-500 flex flex-col flex-1 items-center">
                        <div class="w-28 h-28 bg-sky-50 rounded-3xl flex items-center justify-center mb-8 border border-sky-100 shadow-sm group-hover:scale-110 group-hover:rotate-3 transition-all duration-500">
                            <img src="{{ asset('images/universities/usk.svg') }}" alt="Universitas Syiah Kuala Logo" class="w-20 h-20 max-w-full max-h-full object-contain" loading="lazy" decoding="async">
                        </div>
                        <h3 class="text-2xl lg:text-3xl font-bold tracking-tight text-slate-900 mb-3 group-hover:text-sky-700 transition-colors">Universitas Syiah Kuala</h3>
                        <p class="text-slate-500 font-medium mb-10 text-lg leading-relaxed">Program Studi D3 Manajemen Informatika</p>
                        <div class="mt-auto px-6 py-2.5 bg-sky-500 text-white font-bold rounded-2xl text-sm shadow-[0_4px_12px_rgba(14,165,233,0.3)]">
                            1 Student
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- SECTION 5: Tech Stack --}}
    <section class="py-24 bg-white border-b border-slate-100">
        <div class="max-w-5xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-50 border border-emerald-200 rounded-full text-emerald-700 text-xs font-bold uppercase tracking-widest mb-4" data-aos="fade-up">Modern Technologies</div>
                <h3 class="text-3xl lg:text-4xl font-bold tracking-tight text-slate-900" data-aos="fade-up" data-aos-delay="100">Tech Stack & Tools</h3>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-5">
                @php
                    $techs = [
                        ['name' => 'Laravel', 'desc' => 'Backend Framework', 'color' => 'from-red-500 to-red-700', 'bg' => 'bg-red-50', 'border' => 'border-red-100', 'icon' => '<path d="M20.573 5.483c-1.396-1.127-3.155-1.579-5.187-1.336L5.05 5.56C2.26 5.955.332 8.528.727 11.317c.395 2.79 2.968 4.718 5.758 4.322l3.413-.483v2.85c0 2.21 1.79 4 4 4 2.21 0 4-1.79 4-4V7.525c0-.792-.256-1.547-.725-2.042z"/>'],
                        ['name' => 'Tailwind CSS', 'desc' => 'UI Framework', 'color' => 'from-cyan-500 to-blue-600', 'bg' => 'bg-cyan-50', 'border' => 'border-cyan-100', 'icon' => '<path d="M12.001 4.8c-3.2 0-5.2 1.6-6 4.8 1.2-1.6 2.6-2.2 4.2-1.8.913.228 1.565.89 2.288 1.624C13.666 10.618 15.027 12 18.001 12c3.2 0 5.2-1.6 6-4.8-1.2 1.6-2.6 2.2-4.2 1.8-.913-.228-1.565-.89-2.288-1.624C16.337 6.182 14.976 4.8 12.001 4.8zm-6 7.2c-3.2 0-5.2 1.6-6 4.8 1.2-1.6 2.6-2.2 4.2-1.8.913.228 1.565.89 2.288 1.624 1.177 1.194 2.538 2.576 5.512 2.576 3.2 0 5.2-1.6 6-4.8-1.2 1.6-2.6 2.2-4.2 1.8-.913-.228-1.565-.89-2.288-1.624C7.666 13.382 6.305 12 6.001 12z"/>'],
                        ['name' => 'MySQL', 'desc' => 'Database', 'color' => 'from-blue-500 to-blue-700', 'bg' => 'bg-blue-50', 'border' => 'border-blue-100', 'icon' => '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8z"/>'],
                        ['name' => 'Figma', 'desc' => 'UI & UX Design', 'color' => 'from-purple-500 to-pink-600', 'bg' => 'bg-purple-50', 'border' => 'border-purple-100', 'icon' => '<path d="M14.5 2C16.985 2 19 4.015 19 6.5s-2.015 4.5-4.5 4.5h-5V6.5C9.5 4.015 11.515 2 14.5 2zM9.5 11h5c2.485 0 4.5 2.015 4.5 4.5s-2.015 4.5-4.5 4.5h-5v-9zm0 9c-2.485 0-4.5-2.015-4.5-4.5s2.015-4.5 4.5-4.5h5v9h-5z"/>'],
                        ['name' => 'JavaScript', 'desc' => 'Interactive Experience', 'color' => 'from-amber-400 to-yellow-500', 'bg' => 'bg-amber-50', 'border' => 'border-amber-100', 'icon' => null, 'label' => 'JS'],
                        ['name' => 'Responsive', 'desc' => 'Cross Device Support', 'color' => 'from-emerald-500 to-teal-600', 'bg' => 'bg-emerald-50', 'border' => 'border-emerald-100', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>'],
                    ];
                @endphp

                @foreach($techs as $i => $tech)
                    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4 hover:border-emerald-200 hover:shadow-md hover:-translate-y-0.5 transition-all duration-300 group" data-aos="fade-up" data-aos-delay="{{ 100 + ($i * 50) }}">
                        <div class="w-12 h-12 rounded-xl bg-gradient-to-br {{ $tech['color'] }} flex items-center justify-center shrink-0 shadow-sm group-hover:scale-105 transition-transform duration-300">
                            @if(isset($tech['label']))
                                <span class="font-bold text-white text-sm">{{ $tech['label'] }}</span>
                            @elseif(str_contains($tech['icon'] ?? '', 'stroke-linecap'))
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $tech['icon'] !!}</svg>
                            @else
                                <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24">{!! $tech['icon'] !!}</svg>
                            @endif
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-900 text-sm">{{ $tech['name'] }}</h4>
                            <p class="text-slate-500 text-xs font-medium mt-0.5">{{ $tech['desc'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- SECTION: What We Believe --}}
    <section class="py-24 bg-gradient-to-br from-emerald-700 to-cyan-600 relative overflow-hidden">
        <div class="absolute inset-0 bg-[linear-gradient(to_right,rgba(255,255,255,0.04)_1px,transparent_1px),linear-gradient(to_bottom,rgba(255,255,255,0.04)_1px,transparent_1px)] bg-[size:32px_32px]"></div>
        <div class="absolute top-0 right-0 w-64 h-64 bg-white/10 rounded-full translate-x-1/2 -translate-y-1/2 blur-3xl pointer-events-none"></div>
        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-white/20 border border-white/30 rounded-full text-white text-xs font-bold uppercase tracking-widest mb-8" data-aos="fade-up">What We Believe</div>
            <div class="space-y-6" data-aos="fade-up" data-aos-delay="100">
                <p class="text-3xl md:text-4xl font-bold tracking-tight text-white leading-tight">
                    We believe technology should empower health workers, not replace them.
                </p>
                <p class="text-xl md:text-2xl font-medium text-emerald-50/90 leading-relaxed max-w-3xl mx-auto">
                    NutriGen is designed to simplify nutrition monitoring while keeping health professionals at the center of every decision.
                </p>
            </div>
        </div>
    </section>

    {{-- SECTION 6: Final CTA --}}
    <section class="py-32 bg-white relative overflow-hidden border-t border-slate-100">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-emerald-50 rounded-full blur-[100px] -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-cyan-50 rounded-full blur-[100px] translate-y-1/2 -translate-x-1/3 pointer-events-none"></div>

        <div class="max-w-4xl mx-auto px-6 lg:px-8 text-center relative z-10" data-aos="fade-up">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-emerald-50 border border-emerald-200 rounded-full text-emerald-700 text-xs font-bold uppercase tracking-widest mb-8">Our Vision</div>
            <h2 class="text-3xl md:text-4xl lg:text-5xl font-extrabold text-slate-900 tracking-tight leading-tight mb-6">
                Indonesia Bebas Stunting<br>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-600 to-cyan-600">Dimulai dari Data</span>
            </h2>
            <p class="text-lg text-slate-500 font-medium max-w-2xl mx-auto mb-12 leading-relaxed">
                Bergabunglah bersama kami dalam misi membangun sistem kesehatan digital yang merata dan berdampak untuk seluruh Indonesia.
            </p>
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-gradient-to-r from-emerald-600 to-cyan-600 hover:from-emerald-500 hover:to-cyan-500 text-white font-bold rounded-2xl shadow-[0_8px_24px_rgba(16,185,129,0.3)] hover:shadow-[0_12px_32px_rgba(16,185,129,0.4)] hover:-translate-y-0.5 transition-all duration-300">
                    Lihat Platform
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                </a>
            </div>
        </div>
    </section>

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
            <li><a href="{{ url('/') }}#how-it-works" class="text-base font-medium text-slate-400 hover:text-emerald-400 transition-all duration-300 flex items-center gap-3 group hover:translate-x-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-emerald-400 transition-colors duration-300"></span> Cara Kerja</a></li>
            <li><a href="{{ url('/') }}#features" class="text-base font-medium text-slate-400 hover:text-emerald-400 transition-all duration-300 flex items-center gap-3 group hover:translate-x-2"><span class="w-1.5 h-1.5 rounded-full bg-slate-700 group-hover:bg-emerald-400 transition-colors duration-300"></span> Ekosistem NutriGen</a></li>
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
