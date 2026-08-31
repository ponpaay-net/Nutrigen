@props([
    'description' => 'Platform manajemen stunting end-to-end yang mengintegrasikan data dari Posyandu ke Puskesmas secara real-time. Membangun generasi emas Indonesia.',
])

<footer class="bg-slate-950 relative overflow-hidden border-t border-slate-800/60">
    {{-- Advanced Ambient Mesh Background --}}
    <div class="absolute inset-0 bg-[linear-gradient(to_right,#0f172a_1px,transparent_1px),linear-gradient(to_bottom,#0f172a_1px,transparent_1px)] bg-[size:24px_24px] [mask-image:radial-gradient(ellipse_60%_50%_at_50%_0%,#000_70%,transparent_100%)] opacity-40"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[1000px] h-[400px] bg-emerald-500/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-0 w-[600px] h-[400px] bg-cyan-500/10 rounded-full blur-[120px] pointer-events-none"></div>

    {{-- Animated Top Border --}}
    <div class="absolute top-0 left-0 w-full h-[1px] bg-gradient-to-r from-transparent via-emerald-500/50 to-transparent shadow-[0_0_15px_rgba(16,185,129,0.5)]"></div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 pt-16 lg:pt-24 pb-8 lg:pb-12">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 lg:gap-8 mb-16 lg:mb-20">

            {{-- Brand Column --}}
            <div class="md:col-span-5 flex flex-col items-start">
                <a href="/" class="flex items-center gap-3 mb-6 lg:mb-8 group inline-flex focus:outline-none">
                    <div class="w-14 h-14 lg:w-16 lg:h-16 flex items-center justify-center group-hover:scale-105 transition-all duration-500 -ml-2">
                        <img src="{{ asset('images/logo/logo-nutrigen.png') }}" alt="NutriGen Logo" class="w-full h-full object-contain">
                    </div>
                    <span class="text-2xl lg:text-3xl font-extrabold text-white tracking-tight group-hover:text-emerald-400 transition-colors duration-500">NutriGen</span>
                </a>
                <p class="font-medium text-slate-400 leading-relaxed max-w-sm mb-8 lg:mb-10 text-base lg:text-lg">
                    {{ $description }}
                </p>
                <div class="flex flex-wrap items-center gap-4">
                    {{ $badges ?? '' }}
                </div>
            </div>

            {{-- Platform Links Column --}}
            <div class="md:col-span-3 md:col-start-7">
                <h5 class="text-white font-extrabold mb-8 tracking-widest text-sm uppercase text-slate-200">Platform</h5>
                <ul class="space-y-6">
                    {{ $platformLinks ?? '' }}
                </ul>
            </div>

            {{-- Contact Links Column --}}
            <div class="md:col-span-3">
                <h5 class="text-white font-extrabold mb-8 tracking-widest text-sm uppercase text-slate-200">Kontak</h5>
                <ul class="space-y-6">
                    {{ $contactLinks ?? '' }}
                </ul>
            </div>

        </div>

        {{-- Bottom Bar --}}
        <div class="border-t border-slate-800/80 pt-10 pb-4 flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="text-sm font-medium text-slate-500 leading-relaxed">
                {{ $copyright ?? '' }}
            </div>

            {{-- Social Icons --}}
            <div class="flex items-center gap-4">
                <a href="#" aria-label="GitHub" class="text-slate-500 hover:text-emerald-400 hover:-translate-y-1 hover:shadow-[0_10px_20px_rgba(16,185,129,0.2)] transition-all duration-300 bg-slate-900 w-12 h-12 flex items-center justify-center rounded-2xl border border-slate-800/80 group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd" /></svg>
                </a>
                <a href="#" aria-label="LinkedIn" class="text-slate-500 hover:text-blue-400 hover:-translate-y-1 hover:shadow-[0_10px_20px_rgba(59,130,246,0.2)] transition-all duration-300 bg-slate-900 w-12 h-12 flex items-center justify-center rounded-2xl border border-slate-800/80 group">
                    <svg class="w-5 h-5 group-hover:scale-110 transition-transform duration-300" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd" /></svg>
                </a>
            </div>
        </div>
    </div>
</footer>
