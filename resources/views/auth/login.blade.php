<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    {{-- Form Heading --}}
    <div class="mb-6 form-animate opacity-0">
        <h1 class="text-2xl sm:text-[26px] font-black text-slate-900 tracking-tight leading-tight">
            Selamat Datang 👋
        </h1>
        <p class="text-xs sm:text-[13px] text-slate-500 font-medium mt-1">
            Masuk untuk mengakses data pemantauan posyandu.
        </p>
    </div>

    <form method="POST" action="{{ route('login') }}" class="space-y-4 sm:space-y-4.5">
        @csrf

        {{-- Email / NIP --}}
        <div class="form-animate opacity-0">
            <label for="email" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                Email / NIP Petugas
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-teal-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                </div>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" 
                    class="input-glow block w-full pl-10 pr-4 py-3 bg-slate-50/90 border border-slate-200/90 {{ $errors->has('email') ? 'border-rose-400 focus:border-rose-500' : 'hover:border-slate-300 focus:border-teal-600' }} rounded-xl text-slate-900 text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none transition-all duration-200 placeholder:text-slate-400 placeholder:font-normal" 
                    placeholder="nama@email.com atau NIP">
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-1.5 text-rose-500 font-bold text-xs" />
        </div>

        {{-- Password --}}
        <div class="form-animate opacity-0">
            <label for="password" class="block text-xs font-bold text-slate-700 mb-1.5 uppercase tracking-wider">
                Kata Sandi
            </label>
            <div class="relative group">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-teal-600 transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <input id="password" type="password" name="password" required autocomplete="current-password" 
                    class="input-glow block w-full pl-10 pr-11 py-3 bg-slate-50/90 border border-slate-200/90 {{ $errors->has('password') ? 'border-rose-400 focus:border-rose-500' : 'hover:border-slate-300 focus:border-teal-600' }} rounded-xl text-slate-900 text-xs sm:text-sm font-semibold focus:bg-white focus:outline-none transition-all duration-200 placeholder:text-slate-400 placeholder:font-normal" 
                    placeholder="••••••••">
                
                {{-- Toggle password visibility --}}
                <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-slate-700 transition-colors cursor-pointer" tabindex="-1" aria-label="Lihat kata sandi">
                    <svg id="eye-closed" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                    </svg>
                    <svg id="eye-open" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 hidden">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-1.5 text-rose-500 font-bold text-xs" />
        </div>

        {{-- Remember Me --}}
        <div class="form-animate opacity-0 flex items-center justify-between pt-0.5">
            <label for="remember_me" class="inline-flex items-center group cursor-pointer select-none">
                <div class="relative flex items-center justify-center">
                    <input id="remember_me" type="checkbox" name="remember" 
                        class="peer h-4 w-4 cursor-pointer appearance-none rounded border-2 border-slate-300 bg-white hover:border-teal-500 checked:border-teal-600 checked:bg-teal-600 focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition-all">
                    <svg class="absolute w-2.5 h-2.5 opacity-0 peer-checked:opacity-100 text-white pointer-events-none transition-opacity" fill="none" stroke="currentColor" stroke-width="3.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                        <polyline points="20 6 9 17 4 12"></polyline>
                    </svg>
                </div>
                <span class="ms-2 text-xs font-semibold text-slate-600 group-hover:text-slate-900 transition-colors">Ingat saya</span>
            </label>
        </div>

        {{-- Submit Button --}}
        <div class="form-animate opacity-0 pt-1.5">
            <button type="submit" 
                class="group w-full relative overflow-hidden bg-gradient-to-r from-teal-600 via-teal-700 to-emerald-700 hover:from-teal-500 hover:via-teal-600 hover:to-emerald-600 text-white font-bold py-3.5 px-6 rounded-xl shadow-[0_4px_16px_rgba(13,148,136,0.25)] hover:shadow-[0_8px_24px_rgba(13,148,136,0.35)] hover:-translate-y-0.5 active:translate-y-0 transition-all duration-200 text-xs sm:text-sm flex justify-center items-center gap-2 focus:outline-none focus:ring-4 focus:ring-teal-500/25 cursor-pointer">
                
                {{-- Subtle shimmer sweep --}}
                <div class="absolute inset-0 -translate-x-full group-hover:translate-x-full transition-transform duration-700 bg-gradient-to-r from-transparent via-white/15 to-transparent"></div>
                
                <span class="relative tracking-wide font-extrabold">Masuk ke Dashboard</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="relative w-4 h-4 group-hover:translate-x-0.5 transition-transform">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                </svg>
            </button>
        </div>
    </form>

    {{-- Divider & Portal Ibu --}}
    <div class="mt-7 pt-5 border-t border-slate-100 form-animate opacity-0">
        <div class="text-center">
            <span class="text-[10.5px] font-bold text-slate-400 uppercase tracking-widest block mb-2.5">Akses Khusus Ibu Balita</span>
            <div class="flex items-center justify-center gap-2.5">
                <a href="{{ url('/portal-ibu') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200/80 rounded-xl text-emerald-800 text-[11.5px] font-bold transition-all hover:-translate-y-0.5 shadow-2xs">
                    <svg class="w-3.5 h-3.5 text-emerald-600" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.347-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.876 1.213 3.074.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                    <span>Magic Link WA</span>
                </a>
                <a href="{{ url('/portal-ibu') }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-50 hover:bg-slate-100 border border-slate-200/80 rounded-xl text-slate-700 text-[11.5px] font-bold transition-all hover:-translate-y-0.5 shadow-2xs">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-slate-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                    </svg>
                    <span>Masuk Web</span>
                </a>
            </div>
        </div>
    </div>

    {{-- Password Toggle Script --}}
    <script>
        function togglePassword() {
            const input = document.getElementById('password');
            const eyeClosed = document.getElementById('eye-closed');
            const eyeOpen = document.getElementById('eye-open');
            if (input.type === 'password') {
                input.type = 'text';
                eyeClosed.classList.add('hidden');
                eyeOpen.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeClosed.classList.remove('hidden');
                eyeOpen.classList.add('hidden');
            }
        }
    </script>
    
    @if($errors->has('email') || $errors->has('password'))
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if(window.NutriAlert) window.NutriAlert.error('Login Gagal', 'Kredensial yang Anda masukkan salah. Silakan coba lagi.');
        });
    </script>
    @endif
</x-guest-layout>
