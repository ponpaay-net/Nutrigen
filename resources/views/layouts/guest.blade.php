<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Login | NutriGen</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            /* Premium ambient brand-colored mesh gradient instead of dull gray */
            background-color: #ecfdf5; /* Very soft emerald tint */
            background-image: 
                radial-gradient(at 0% 0%, hsla(160, 84%, 85%, 0.7) 0px, transparent 50%),
                radial-gradient(at 100% 0%, hsla(175, 70%, 80%, 0.6) 0px, transparent 50%),
                radial-gradient(at 100% 100%, hsla(150, 60%, 90%, 0.8) 0px, transparent 50%),
                radial-gradient(at 0% 100%, hsla(190, 80%, 85%, 0.5) 0px, transparent 50%);
            background-attachment: fixed;
            margin: 0;
            overflow-x: hidden;
            /* Allow vertical scrolling on mobile */
            overflow-y: auto; 
        }

        .brand-panel {
            background: linear-gradient(135deg, #10b981 0%, #0d9488 100%);
            position: relative;
            overflow: hidden;
        }

        .pattern-overlay {
            position: absolute;
            inset: 0;
            background-image: radial-gradient(rgba(255,255,255,0.15) 1.5px, transparent 1.5px);
            background-size: 24px 24px;
            opacity: 0.6;
        }
        
        .glass-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.05) 0%, rgba(255,255,255,0) 100%);
            backdrop-filter: blur(10px);
            z-index: 2;
        }

        @keyframes revealCard {
            0% { opacity: 0; transform: translateY(40px) scale(0.97); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        @keyframes slideInRight {
            0% { opacity: 0; transform: translateX(20px); }
            100% { opacity: 1; transform: translateX(0); }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-5px); }
        }
        @keyframes float-slow {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(10deg); }
        }
        @keyframes float-slower {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(30px) rotate(-15deg); }
        }

        .animate-reveal {
            opacity: 0;
            animation: revealCard 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .stagger-item {
            opacity: 0;
            animation: slideInRight 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
        .animate-logo {
            animation: float 6s ease-in-out infinite;
        }
        
        /* Utility animation classes for mobile */
        .animate-float-slow { animation: float-slow 8s ease-in-out infinite; }
        .animate-float-slower { animation: float-slower 12s ease-in-out infinite; }

        /* Desktop Ornaments */
        .ornament-1 {
            position: absolute;
            top: -15%;
            left: -15%;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
            animation: float-slow 8s ease-in-out infinite;
            z-index: 1;
        }
        .ornament-2 {
            position: absolute;
            bottom: -10%;
            right: -20%;
            width: 350px;
            height: 350px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(52,211,153,0.3) 0%, rgba(13,148,136,0) 70%);
            animation: float-slower 12s ease-in-out infinite;
            z-index: 1;
        }
        .ornament-3 {
            position: absolute;
            top: 35%;
            right: -10%;
            width: 120px;
            height: 120px;
            border: 1.5px solid rgba(255,255,255,0.15);
            border-radius: 50%;
            backdrop-filter: blur(4px);
            animation: float-slow 10s ease-in-out infinite reverse;
            z-index: 3;
        }

        .modern-input {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            background: #f8fafc;
            border: 1px solid #e2e8f0;
        }
        .modern-input:focus {
            background: #ffffff;
            border-color: #10b981;
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.15);
        }
    </style>
</head>
<body class="antialiased lg:p-8 flex items-center justify-center min-h-screen">
    
    <!-- 
      LAYOUT MAGIC:
      On mobile: It's a FULL BLEED Native App style design (no margins).
      On desktop: It's a gorgeous floating card (max-w-[850px], rounded-[2rem]).
    -->
    <div class="w-full min-h-screen lg:min-h-[500px] lg:max-w-[850px] bg-white lg:rounded-[2.5rem] lg:shadow-[0_30px_80px_-15px_rgba(16,185,129,0.2)] flex flex-col lg:flex-row relative z-10 lg:overflow-hidden lg:animate-reveal">
        
        <!-- LEFT / TOP Panel (Brand Header on Mobile) -->
        <!-- On mobile: min-h-[35vh], pushes content down gracefully -->
        <div class="relative lg:w-[45%] brand-panel text-white flex flex-col justify-center lg:justify-between px-8 pt-16 pb-20 lg:p-12 z-0 min-h-[35vh] lg:min-h-0">
            
            <div class="pattern-overlay"></div>
            
            <!-- Desktop-only heavy ornaments (to keep mobile extremely clean) -->
            <div class="ornament-1 hidden lg:block"></div>
            <div class="ornament-2 hidden lg:block"></div>
            <div class="ornament-3 hidden lg:block"></div>
            
            <!-- Mobile-only optimized lightweight ornaments -->
            <div class="absolute top-[-10%] left-[-20%] w-[250px] h-[250px] rounded-full bg-white/10 blur-3xl lg:hidden animate-float-slow z-1"></div>
            <div class="absolute bottom-16 right-[-10%] w-24 h-24 border-2 border-white/10 rounded-full lg:hidden animate-float-slower z-1"></div>
            
            <div class="glass-overlay"></div>
            
            <!-- Content -->
            <div class="relative z-10 flex flex-col items-center justify-center h-full text-center mt-2 lg:mt-0">
                <!-- Mobile Logo (Slightly smaller to save vertical space) -->
                <div class="w-16 h-16 lg:w-20 lg:h-20 bg-white/10 backdrop-blur-md rounded-[1rem] lg:rounded-[1.5rem] flex items-center justify-center p-3 lg:p-4 border border-white/20 shadow-xl mb-5 lg:mb-8 animate-logo">
                    <img src="{{ asset('images/logo/logo-nutrigen.png') }}" alt="Logo" class="w-full h-full object-contain filter drop-shadow-md">
                </div>
                
                <h1 class="text-3xl lg:text-4xl font-bold tracking-tight text-white mb-2 lg:mb-4">
                    NutriGen
                </h1>
                
                <!-- Subtext (Different verbosity for desktop vs mobile) -->
                <p class="text-white/80 text-[13px] font-light leading-relaxed max-w-[220px] mx-auto hidden lg:block">
                    Akselerasi penanganan stunting melalui integrasi data yang presisi.
                </p>
                <p class="text-white/80 text-[12px] font-light max-w-[200px] mx-auto lg:hidden">
                    Sistem Terintegrasi Posyandu
                </p>
            </div>
            
            <!-- Hidden on mobile to save vertical space -->
            <div class="relative z-10 mt-auto pt-8 hidden lg:flex justify-center">
                <div class="px-4 py-1.5 rounded-full bg-black/10 border border-white/20 backdrop-blur-md flex items-center gap-2 shadow-lg hover:bg-black/20 transition-colors cursor-default">
                    <span class="w-1.5 h-1.5 rounded-full bg-white shadow-[0_0_8px_rgba(255,255,255,0.8)] animate-pulse"></span>
                    <span class="text-[9px] font-semibold text-white uppercase tracking-widest">Sistem Aktif</span>
                </div>
            </div>
        </div>

        <!-- RIGHT / BOTTOM Panel (The Form) -->
        <!-- 
            MOBILE MAGIC: 
            -mt-10 and rounded-t-[2.5rem] creates the ultra-modern "Bottom Sheet" overlay effect!
            The white form physically overlaps the green header on mobile. 
        -->
        <div class="lg:w-[55%] flex-1 flex flex-col justify-center px-8 py-10 lg:px-14 lg:py-16 bg-white relative z-10 rounded-t-[2.5rem] lg:rounded-none -mt-10 lg:mt-0 shadow-[0_-15px_40px_rgba(0,0,0,0.12)] lg:shadow-none">
            
            <!-- Mobile "Drag Handle" indicator (purely aesthetic for that native app feel) -->
            <div class="absolute top-4 left-1/2 -translate-x-1/2 w-12 h-1.5 bg-slate-200 rounded-full lg:hidden"></div>

            <div class="w-full max-w-[340px] mx-auto mt-2 lg:mt-0">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>
</html>
