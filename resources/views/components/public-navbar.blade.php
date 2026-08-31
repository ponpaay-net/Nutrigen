@php
    $isTeamPage = request()->routeIs('team');
@endphp

{{-- Navigation Wrapper --}}
<div x-data="{ scrolled: false, mobileMenuOpen: false }" 
     @scroll.window="scrolled = (window.scrollY > 20 || document.documentElement.scrollTop > 20)"
     x-init="scrolled = (window.scrollY > 20 || document.documentElement.scrollTop > 20)">
    {{-- Navigation --}}
    <nav :class="{'nav-glass shadow-[0_4px_20px_rgba(0,0,0,0.02)]': scrolled, 'bg-transparent py-2': !scrolled}"
         class="fixed w-full z-50 transition-all duration-500">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
        <div class="flex justify-between items-center h-20 transition-all duration-500">
            {{-- Logo --}}
            <a href="/" class="flex items-center gap-3.5 group focus:outline-none focus:ring-2 focus:ring-emerald-500/50 rounded-2xl">
                <div class="w-14 h-14 sm:w-16 sm:h-16 flex items-center justify-center transition-transform duration-500 group-hover:scale-105 group-active:scale-95 -ml-2">
                    <img src="{{ asset('images/logo/logo-nutrigen.png') }}" alt="NutriGen Logo" class="w-full h-full object-contain">
                </div>
                <span class="text-2xl font-extrabold tracking-tight text-slate-900 group-hover:text-emerald-700 transition-colors duration-300">NutriGen</span>
            </a>

            {{-- Right Nav --}}
            <div class="flex items-center gap-4 lg:gap-10">
                <div class="hidden lg:flex items-center gap-10">
                    <a href="{{ url('/') }}#how-it-works" class="relative text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors duration-300 group py-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-200 rounded-md leading-relaxed">
                        Cara Kerja
                        <span class="absolute bottom-1 left-0 w-0 h-0.5 bg-slate-900 transition-all duration-300 ease-out group-hover:w-full rounded-full"></span>
                    </a>
                    <a href="{{ url('/') }}#features" class="relative text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors duration-300 group py-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-200 rounded-md leading-relaxed">
                        Fitur
                        <span class="absolute bottom-1 left-0 w-0 h-0.5 bg-slate-900 transition-all duration-300 ease-out group-hover:w-full rounded-full"></span>
                    </a>
                    <a href="{{ url('/') }}#video-demo" class="relative text-sm font-semibold text-slate-500 hover:text-slate-900 transition-colors duration-300 group py-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-slate-200 rounded-md leading-relaxed">
                        Demo
                        <span class="absolute bottom-1 left-0 w-0 h-0.5 bg-slate-900 transition-all duration-300 ease-out group-hover:w-full rounded-full"></span>
                    </a>
                    <a href="{{ route('team') }}" class="relative text-sm font-semibold text-emerald-600 hover:text-emerald-700 transition-colors duration-300 group py-2 focus:outline-none focus-visible:ring-2 focus-visible:ring-emerald-200 rounded-md">
                        Meet Our Team
                        <span class="absolute bottom-1 left-0 {{ $isTeamPage ? 'w-full' : 'w-0 group-hover:w-full' }} h-0.5 bg-emerald-600 transition-all duration-300 ease-out rounded-full"></span>
                    </a>
                </div>

                <div class="hidden lg:block">
                    @auth
                        <a href="{{ route('dashboard') }}" class="text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 px-6 py-2.5 rounded-full transition-all shadow-[0_4px_12px_rgba(0,0,0,0.1)] hover:shadow-[0_6px_20px_rgba(0,0,0,0.15)] hover:-translate-y-0.5 focus:outline-none focus-visible:ring-4 focus-visible:ring-slate-200 inline-block">
                            Buka Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-bold text-slate-700 bg-gradient-to-b from-white to-slate-50 hover:from-slate-50 hover:to-slate-100 border border-slate-200/80 px-7 py-2.5 rounded-full transition-all duration-300 shadow-[0_2px_8px_rgba(0,0,0,0.04)] hover:shadow-[0_4px_12px_rgba(0,0,0,0.06)] focus:outline-none focus-visible:ring-4 focus-visible:ring-slate-200 inline-block">
                            Login Petugas
                        </a>
                    @endauth
                </div>

                {{-- Hamburger Menu (Mobile & Desktop) --}}
                <button @click="mobileMenuOpen = true"
                        type="button"
                        aria-label="Buka menu navigasi"
                        class="p-2 text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-slate-200">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
        </div>
    </nav>

    {{-- Mobile Drawer Backdrop --}}
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 bg-slate-900/60 backdrop-blur-md z-40"
         @click="mobileMenuOpen = false"
         style="display: none;"></div>

    {{-- Mobile Drawer --}}
    <div x-show="mobileMenuOpen"
         x-transition:enter="transition ease-out duration-300 transform"
         x-transition:enter-start="translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="translate-x-full"
         @keydown.escape.window="mobileMenuOpen = false"
         x-effect="document.body.style.overflow = mobileMenuOpen ? 'hidden' : ''"
         role="dialog"
         aria-modal="true"
         aria-label="Menu navigasi mobile"
         class="fixed inset-y-0 right-0 z-50 w-[280px] max-w-[85vw] bg-white/95 backdrop-blur-2xl shadow-[0_0_40px_rgba(0,0,0,0.1)] overflow-y-auto flex flex-col border-l border-white/40"
         style="display: none;">

        <div class="flex items-center justify-between px-6 py-6 border-b border-slate-100/50 sticky top-0 z-10 bg-white/50 backdrop-blur-md">
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 flex items-center justify-center -ml-2">
                    <img src="{{ asset('images/logo/logo-nutrigen.png') }}" alt="NutriGen Logo" class="w-full h-full object-contain">
                </div>
                <span class="text-xl font-extrabold tracking-tight text-slate-900">NutriGen</span>
            </div>
            <button @click="mobileMenuOpen = false"
                    aria-label="Tutup menu navigasi"
                    class="p-2.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-full transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-rose-200">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
            </button>
        </div>

        <div class="px-6 py-8 flex flex-col gap-2 flex-1 relative">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(16,185,129,0.05),transparent_50%)] pointer-events-none"></div>

            <div class="flex flex-col gap-2 relative z-10">
                <a href="{{ url('/') }}#how-it-works" @click="mobileMenuOpen = false" class="flex items-center justify-between px-4 py-3.5 rounded-xl text-base font-bold text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/80 transition-all duration-300 group">
                    <span class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg bg-slate-100/80 text-slate-500 group-hover:bg-emerald-100 group-hover:text-emerald-600 flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </span>
                        Cara Kerja
                    </span>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </a>

                <a href="{{ url('/') }}#features" @click="mobileMenuOpen = false" class="flex items-center justify-between px-4 py-3.5 rounded-xl text-base font-bold text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/80 transition-all duration-300 group">
                    <span class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg bg-slate-100/80 text-slate-500 group-hover:bg-emerald-100 group-hover:text-emerald-600 flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                        </span>
                        Fitur
                    </span>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </a>

                <a href="{{ url('/') }}#video-demo" @click="mobileMenuOpen = false" class="flex items-center justify-between px-4 py-3.5 rounded-xl text-base font-bold text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/80 transition-all duration-300 group">
                    <span class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg bg-slate-100/80 text-slate-500 group-hover:bg-emerald-100 group-hover:text-emerald-600 flex items-center justify-center transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </span>
                        Demo
                    </span>
                    <svg class="w-4 h-4 text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </a>

                <a href="{{ route('team') }}" @click="mobileMenuOpen = false" class="flex items-center justify-between px-4 py-3.5 rounded-xl text-base font-bold transition-all duration-300 group {{ $isTeamPage ? 'bg-emerald-50 text-emerald-700 shadow-sm shadow-emerald-100/50' : 'text-slate-600 hover:text-emerald-700 hover:bg-emerald-50/80' }}">
                    <span class="flex items-center gap-3">
                        <span class="w-9 h-9 rounded-lg flex items-center justify-center transition-colors {{ $isTeamPage ? 'bg-emerald-500 text-white shadow-sm border border-slate-200/60 shadow-emerald-500/20' : 'bg-slate-100/80 text-slate-500 group-hover:bg-emerald-100 group-hover:text-emerald-600' }}">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        </span>
                        Meet Our Team
                    </span>
                    <svg class="w-4 h-4 transition-all {{ $isTeamPage ? 'text-emerald-500' : 'text-slate-300 group-hover:text-emerald-500 group-hover:translate-x-1' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                </a>
            </div>

            <div class="mt-auto pt-8">
                @auth
                    <a href="{{ route('dashboard') }}" class="group flex items-center justify-center w-full gap-2 text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 px-6 py-3.5 rounded-xl transition-all shadow-[0_4px_15px_rgba(0,0,0,0.1)] hover:shadow-[0_8px_25px_rgba(0,0,0,0.2)] hover:-translate-y-1">
                        Buka Dashboard
                        <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" /></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="group flex items-center justify-center w-full gap-2 text-sm font-bold text-white bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 border border-transparent px-7 py-3.5 rounded-xl transition-all shadow-[0_4px_15px_rgba(16,185,129,0.25)] hover:shadow-[0_8px_25px_rgba(16,185,129,0.4)] hover:-translate-y-1">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" /></svg>
                        Login Petugas
                        <svg class="w-5 h-5 opacity-0 -ml-4 group-hover:opacity-100 group-hover:ml-0 group-hover:translate-x-1 transition-all duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" /></svg>
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>
