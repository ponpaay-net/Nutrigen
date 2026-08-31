<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login | NutriGen</title>
    <link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><rect width=%22100%22 height=%22100%22 rx=%2220%22 fill=%22%2310B981%22/><text x=%2250%22 y=%2272%22 font-size=%2265%22 font-family=%22Arial%22 font-weight=%22bold%22 fill=%22white%22 text-anchor=%22middle%22>N</text></svg>">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/motion@12.12.1/dist/motion.js"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Inter', sans-serif; }
        
        .mesh-bg {
            background-color: #f0fdf4;
            background-image: 
                radial-gradient(at 0% 0%, hsla(160,64%,83%,0.5) 0px, transparent 50%),
                radial-gradient(at 98% 1%, hsla(174,62%,70%,0.3) 0px, transparent 50%),
                radial-gradient(at 80% 100%, hsla(189,45%,82%,0.3) 0px, transparent 50%);
        }

        /* Floating background ambient orbs */
        @keyframes float-orb {
            0%, 100% { transform: translate(0,0) scale(1); }
            50% { transform: translate(-5px,-18px) scale(1.03); }
        }
        .orb { animation: float-orb 8s ease-in-out infinite; }
        .orb-2 { animation: float-orb 11s ease-in-out infinite; animation-delay: -4s; }

        /* Dynamic Floating & Breathing Logo Animation */
        @keyframes logoFloat {
            0%, 100% {
                transform: translateY(0px) rotate(0deg) scale(1);
                box-shadow: 0 10px 25px -4px rgba(0, 0, 0, 0.18);
            }
            50% {
                transform: translateY(-6px) rotate(1.5deg) scale(1.03);
                box-shadow: 0 18px 32px -4px rgba(0, 0, 0, 0.28), 0 0 20px rgba(52, 211, 153, 0.35);
            }
        }
        .animate-logo-float {
            animation: logoFloat 4.2s ease-in-out infinite;
        }

        .input-glow:focus {
            box-shadow: 0 0 0 4px rgba(13,148,136,0.14), 0 1px 3px rgba(0,0,0,0.05);
        }

        @media (prefers-reduced-motion: reduce) {
            .orb, .orb-2, .animate-logo-float { animation: none; }
        }
    </style>
</head>
<body class="antialiased min-h-screen mesh-bg relative selection:bg-teal-200 selection:text-teal-900 flex items-center justify-center p-3 sm:p-6 lg:p-8 overflow-x-hidden">
    <x-flash-messages />

    {{-- Floating ambient orbs --}}
    <div class="absolute inset-0 overflow-hidden pointer-events-none hidden sm:block">
        <div class="orb absolute -top-20 -left-20 w-72 h-72 bg-emerald-200/30 rounded-full blur-3xl"></div>
        <div class="orb-2 absolute top-1/3 -right-32 w-80 h-80 bg-teal-200/25 rounded-full blur-3xl"></div>
    </div>

    {{-- Main Card --}}
    <div class="w-full max-w-[980px] relative z-10 bg-white rounded-2xl sm:rounded-[2rem] shadow-[0_20px_60px_rgba(13,148,136,0.08)] border border-slate-200/70 overflow-hidden flex flex-col lg:flex-row" id="login-card">
        
        {{-- ═══ LEFT: Branding Panel ═══ --}}
        <div class="relative lg:w-[42%] bg-gradient-to-br from-teal-800 via-teal-700 to-emerald-800 text-white overflow-hidden" id="brand-panel">
            
            {{-- Decorative Elements --}}
            <div class="absolute inset-0 pointer-events-none">
                <div class="absolute inset-0 opacity-[0.06]" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 20px 20px;"></div>
                <div class="absolute -top-16 -right-16 w-56 h-56 bg-teal-400/20 rounded-full blur-3xl"></div>
                <div class="absolute bottom-8 -left-12 w-48 h-48 bg-emerald-300/15 rounded-full blur-3xl"></div>
            </div>

            {{-- ── MOBILE: Compact hero banner ── --}}
            <div class="relative z-10 lg:hidden p-5 sm:p-6">
                <div class="flex items-center gap-4">
                    {{-- Animated Floating Logo Mobile (Single clean rounded container) --}}
                    <div id="brand-logo-mobile" class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center p-2.5 shrink-0 animate-logo-float shadow-md">
                        <img src="{{ asset('images/logo/logo-nutrigen.png') }}" alt="NutriGen" class="w-full h-full object-contain">
                    </div>
                    {{-- Brand info --}}
                    <div class="min-w-0 flex-1">
                        <div class="flex items-center gap-2">
                            <h1 class="text-xl font-black tracking-tight">NutriGen</h1>
                            <span class="inline-flex items-center px-2 py-0.5 bg-white/20 border border-white/25 rounded-md text-[9px] font-extrabold uppercase tracking-widest text-teal-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse mr-1"></span>
                                v2.0
                            </span>
                        </div>
                        <p class="text-teal-100/90 text-[11.5px] font-medium mt-0.5">Monitoring Gizi Anak Indonesia</p>
                    </div>
                </div>

                {{-- Mini stats row mobile --}}
                <div class="flex items-center gap-3 mt-4 pt-3.5 border-t border-white/15">
                    <div class="flex-1 text-center bg-white/10 rounded-xl py-1.5 px-1 border border-white/10">
                        <span class="text-xs font-black block leading-tight text-white">10rb+</span>
                        <span class="text-[9px] text-teal-100/70 font-semibold">Balita</span>
                    </div>
                    <div class="flex-1 text-center bg-white/10 rounded-xl py-1.5 px-1 border border-white/10">
                        <span class="text-xs font-black block leading-tight text-white">500+</span>
                        <span class="text-[9px] text-teal-100/70 font-semibold">Posyandu</span>
                    </div>
                    <div class="flex-1 text-center bg-white/10 rounded-xl py-1.5 px-1 border border-white/10">
                        <span class="text-xs font-black block leading-tight text-white">34</span>
                        <span class="text-[9px] text-teal-100/70 font-semibold">Provinsi</span>
                    </div>
                </div>
            </div>

            {{-- ── DESKTOP: Full vertical panel ── --}}
            <div class="relative z-10 hidden lg:flex flex-col p-10 h-full">
                
                {{-- Animated Floating Logo Desktop --}}
                <div id="brand-logo" class="inline-block self-start mb-7">
                    <div class="w-[72px] h-[72px] bg-white rounded-[20px] flex items-center justify-center p-3.5 animate-logo-float cursor-pointer">
                        <img src="{{ asset('images/logo/logo-nutrigen.png') }}" alt="NutriGen" class="w-full h-full object-contain">
                    </div>
                </div>

                {{-- Title & Desc --}}
                <div id="brand-text">
                    <div class="flex items-center gap-2.5 mb-2">
                        <h1 class="text-[28px] font-black tracking-tight leading-none">NutriGen</h1>
                        <span class="inline-flex items-center px-2 py-0.5 bg-white/20 border border-white/25 rounded-md text-[9.5px] font-extrabold uppercase tracking-widest text-teal-100 shadow-xs">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse mr-1"></span>
                            v2.0
                        </span>
                    </div>
                    <p class="text-teal-100/90 text-[13px] font-medium leading-relaxed max-w-[270px]">
                        Sistem informasi terintegrasi Posyandu & Puskesmas untuk pencegahan stunting berstandar WHO 2006.
                    </p>
                </div>

                {{-- Stats counters (Interactive Glass Tiles) --}}
                <div class="grid grid-cols-3 gap-2.5 mt-7" id="brand-stats">
                    <div class="bg-white/10 hover:bg-white/20 border border-white/15 rounded-2xl p-3 text-center transition-all duration-300 hover:-translate-y-0.5 shadow-sm">
                        <span class="text-lg font-black block leading-tight text-white">10rb+</span>
                        <span class="text-[10px] text-teal-100/70 font-semibold block mt-0.5">Balita</span>
                    </div>
                    <div class="bg-white/10 hover:bg-white/20 border border-white/15 rounded-2xl p-3 text-center transition-all duration-300 hover:-translate-y-0.5 shadow-sm">
                        <span class="text-lg font-black block leading-tight text-white">500+</span>
                        <span class="text-[10px] text-teal-100/70 font-semibold block mt-0.5">Posyandu</span>
                    </div>
                    <div class="bg-white/10 hover:bg-white/20 border border-white/15 rounded-2xl p-3 text-center transition-all duration-300 hover:-translate-y-0.5 shadow-sm">
                        <span class="text-lg font-black block leading-tight text-white">34</span>
                        <span class="text-[10px] text-teal-100/70 font-semibold block mt-0.5">Provinsi</span>
                    </div>
                </div>

                {{-- Trust indicators (pushed to bottom) --}}
                <div class="flex flex-col gap-3 mt-auto pt-6" id="brand-trust">
                    <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-xl p-2.5">
                        <div class="w-8 h-8 rounded-lg bg-white/15 border border-white/20 flex items-center justify-center shrink-0 text-emerald-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-white block leading-tight">Terenkripsi & Aman</span>
                            <span class="text-[10px] font-medium text-teal-200/70">Data dilindungi standar keamanan tinggi</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 bg-white/5 border border-white/10 rounded-xl p-2.5">
                        <div class="w-8 h-8 rounded-lg bg-white/15 border border-white/20 flex items-center justify-center shrink-0 text-emerald-300">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                            </svg>
                        </div>
                        <div>
                            <span class="text-[11px] font-bold text-white block leading-tight">Standar WHO 2006</span>
                            <span class="text-[10px] font-medium text-teal-200/70">Z-Score antropometri tervalidasi</span>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="mt-5 pt-3.5 border-t border-white/15 flex items-center justify-between">
                    <p class="text-[10px] text-teal-100/60 font-medium">&copy; {{ date('Y') }} NutriGen</p>
                    <span class="text-[9.5px] font-bold text-teal-200/70 uppercase tracking-wider">Kemenkes RI</span>
                </div>
            </div>
        </div>

        {{-- ═══ RIGHT: Form Panel ═══ --}}
        <div class="lg:w-[58%] relative bg-white flex flex-col justify-between" id="form-panel">
            
            {{-- Top Left Back Button (Pinned to Top-Left Corner of White Card) --}}
            <div class="pt-5 px-6 sm:pt-6 sm:px-8 lg:pt-7 lg:px-10 shrink-0">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 text-xs font-bold text-slate-500 hover:text-teal-700 transition-colors group">
                    <div class="w-7 h-7 rounded-lg bg-slate-100 group-hover:bg-teal-50 text-slate-600 group-hover:text-teal-700 flex items-center justify-center transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5 group-hover:-translate-x-0.5 transition-transform">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                        </svg>
                    </div>
                    <span class="tracking-tight">Kembali ke Beranda</span>
                </a>
            </div>

            <div class="px-6 pb-6 pt-3 sm:px-8 sm:pb-8 lg:px-10 lg:pb-10 flex flex-col justify-center flex-1">
                <div class="w-full max-w-sm mx-auto">
                    {{ $slot }}
                </div>
            </div>
        </div>

    </div>

    {{-- Framer Motion --}}
    <script type="module">
        document.addEventListener('DOMContentLoaded', () => {
            if (!window.Motion) return;
            const { animate, stagger, hover } = window.Motion;

            animate('#login-card',
                { opacity: [0, 1], y: [16, 0] },
                { duration: 0.55, easing: [0.16, 1, 0.3, 1] }
            );
            animate('#brand-logo, #brand-logo-mobile',
                { opacity: [0, 1], scale: [0.85, 1] },
                { duration: 0.5, delay: 0.15, easing: [0.16, 1, 0.3, 1] }
            );
            animate('#brand-text',
                { opacity: [0, 1], y: [10, 0] },
                { duration: 0.4, delay: 0.25, easing: [0.16, 1, 0.3, 1] }
            );
            animate('#brand-stats',
                { opacity: [0, 1], y: [8, 0] },
                { duration: 0.4, delay: 0.35, easing: [0.16, 1, 0.3, 1] }
            );
            animate('#brand-trust',
                { opacity: [0, 1], y: [8, 0] },
                { duration: 0.4, delay: 0.45, easing: [0.16, 1, 0.3, 1] }
            );

            const formEls = document.querySelectorAll('.form-animate');
            if (formEls.length) {
                animate(formEls,
                    { opacity: [0, 1], y: [12, 0] },
                    { delay: stagger(0.05, { start: 0.25 }), duration: 0.4, easing: [0.16, 1, 0.3, 1] }
                );
            }
        });
    </script>
</body>
</html>
