<x-guest-layout>
    <x-auth-session-status class="mb-6 stagger-item" style="animation-delay: 0.1s" :status="session('status')" />

    {{-- Form Heading --}}
    <!-- Centered on mobile, left-aligned on desktop for better flow -->
    <div class="mb-8 text-center sm:text-left stagger-item" style="animation-delay: 0.1s">
        <h2 class="text-2xl sm:text-[26px] font-bold text-slate-900 tracking-tight mb-1">
            Selamat Datang
        </h2>
        <p class="text-[13px] text-slate-500 font-light">
            Masuk ke portal NutriGen Anda.
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email / NIP --}}
        <div class="stagger-item" style="animation-delay: 0.2s">
            <label for="email" class="block text-[12px] font-semibold text-slate-700 mb-1.5">
                Email atau NIP
            </label>
            <div class="relative">
                <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                    class="modern-input block w-full px-4 py-3 rounded-xl text-slate-800 text-[14px] placeholder:text-slate-400 focus:outline-none" 
                    placeholder="nama@email.com">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-rose-500 text-xs font-medium" />
        </div>

        {{-- Password --}}
        <div class="stagger-item" style="animation-delay: 0.3s">
            <label for="password" class="block text-[12px] font-semibold text-slate-700 mb-1.5">
                Kata Sandi
            </label>
            <div class="relative">
                <input id="password" type="password" name="password" required autocomplete="current-password" 
                    class="modern-input block w-full px-4 py-3 rounded-xl text-slate-800 text-[14px] placeholder:text-slate-400 pr-12 focus:outline-none" 
                    placeholder="••••••••">
                
                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 hover:text-emerald-500 transition-colors focus:outline-none" tabindex="-1">
                    <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4 hidden">
                        <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>
                    </svg>
                    <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle>
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-rose-500 text-xs font-medium" />
        </div>

        {{-- Remember Me --}}
        <div class="flex items-center justify-between pt-1 stagger-item" style="animation-delay: 0.4s">
            <label for="remember_me" class="flex items-center gap-2.5 cursor-pointer group">
                <div class="relative flex items-center justify-center w-[18px] h-[18px] rounded-md bg-slate-100 border border-slate-300 group-hover:border-emerald-500 transition-colors">
                    <input id="remember_me" type="checkbox" name="remember" class="peer sr-only" checked>
                    <div class="absolute inset-0 bg-emerald-500 rounded-md scale-0 peer-checked:scale-100 transition-transform duration-200 flex items-center justify-center">
                        <svg class="w-3 h-3 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12"></polyline>
                        </svg>
                    </div>
                </div>
                <span class="text-[12px] font-medium text-slate-500 group-hover:text-slate-800 transition-colors">
                    Ingat sesi
                </span>
            </label>
            
            <a href="#" class="text-[11px] font-semibold text-emerald-600 hover:text-emerald-700 transition-colors">
                Lupa sandi?
            </a>
        </div>

        {{-- Action Buttons --}}
        <!-- Grid is 1 column on mobile (stacked), 2 columns on sm+ displays -->
        <div class="pt-5 grid grid-cols-1 sm:grid-cols-2 gap-3 stagger-item" style="animation-delay: 0.5s">
            <button type="submit" 
                class="w-full bg-emerald-500 hover:bg-emerald-600 text-white text-[13px] font-semibold py-3 px-4 rounded-xl shadow-lg shadow-emerald-500/30 transition-all duration-300 hover:-translate-y-1 hover:shadow-emerald-500/40 focus:ring-4 focus:ring-emerald-500/20 focus:outline-none flex justify-center items-center gap-2">
                Masuk
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="w-4 h-4">
                    <line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline>
                </svg>
            </button>
            
            <div class="w-full flex items-center justify-center gap-2 bg-slate-50 border border-slate-200 text-slate-500 text-[11.5px] font-medium py-3 px-4 rounded-xl text-center leading-snug">
                Portal Ibu diakses lewat tautan unik yang dikirim ke WhatsApp Anda.
            </div>
        </div>
    </form>
    
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eyeClosed = document.getElementById('eye-closed');
            const eyeOpen = document.getElementById('eye-open');
            if (input.type === 'password') {
                input.type = 'text';
                eyeClosed.classList.remove('hidden');
                eyeOpen.classList.add('hidden');
            } else {
                input.type = 'password';
                eyeClosed.classList.add('hidden');
                eyeOpen.classList.remove('hidden');
            }
        }
    </script>
</x-guest-layout>
