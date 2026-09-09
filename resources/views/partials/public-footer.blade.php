<footer class="bg-slate-50 border-t border-slate-200 pt-20 pb-10 relative overflow-hidden">
    {{-- Subtle Footer Pattern --}}
    <div class="absolute inset-0 bg-[linear-gradient(rgba(148,163,184,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(148,163,184,0.05)_1px,transparent_1px)] bg-[size:20px_20px] pointer-events-none"></div>
    
    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-12 mb-16">
            {{-- Brand & About --}}
            <div class="md:col-span-5">
                <div class="flex items-center gap-2 mb-6">
                    <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shadow-inner">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 22h20L12 2zm0 4.2L18.8 19H5.2L12 6.2zM11 11v4h2v-4h-2zm0 5v2h2v-2h-2z"/></svg>
                    </div>
                    <span class="text-2xl font-black text-slate-800 tracking-tight">NutriGen</span>
                </div>
                <p class="text-slate-500 leading-relaxed font-medium mb-8 text-sm max-w-sm">
                    Platform manajemen stunting end-to-end yang mengintegrasikan data dari Posyandu ke Puskesmas secara real-time. Membangun generasi emas Indonesia.
                </p>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1.5 bg-white border border-slate-200 text-slate-600 text-xs font-bold rounded-lg shadow-sm flex items-center gap-1.5"><svg class="w-3.5 h-3.5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 21v-4m0 0V5a2 2 0 012-2h6.5l1 1H21l-3 6 3 6h-8.5l-1-1H5a2 2 0 00-2 2zm9-13.5V9"></path></svg> Built in Indonesia</span>
                    <span class="px-3 py-1.5 bg-emerald-50 border border-emerald-100 text-emerald-700 text-xs font-bold rounded-lg shadow-sm flex items-center gap-1.5"><span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Digdaya 2026</span>
                    <span class="px-3 py-1.5 bg-white border border-slate-200 text-slate-500 text-xs font-bold rounded-lg shadow-sm">v1.0 MVP</span>
                </div>
            </div>

            {{-- Links --}}
            <div class="md:col-span-3 md:col-start-7">
                <h4 class="font-extrabold text-slate-800 tracking-wider text-sm uppercase mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-slate-300"></span> Platform
                </h4>
                <ul class="space-y-4">
                    <li><a href="{{ url('/') }}#how-it-works" class="text-slate-500 hover:text-emerald-600 font-medium transition-all duration-300 text-sm flex items-center gap-2 hover:translate-x-1"><span class="w-1.5 h-1.5 rounded-full bg-slate-200"></span> Cara Kerja</a></li>
                    <li><a href="{{ url('/') }}#faq" class="text-slate-500 hover:text-emerald-600 font-medium transition-all duration-300 text-sm flex items-center gap-2 hover:translate-x-1"><span class="w-1.5 h-1.5 rounded-full bg-slate-200"></span> FAQ & Bantuan</a></li>
                    <li><a href="{{ route('team') }}" class="text-emerald-600 font-bold transition-all duration-300 text-sm flex items-center gap-2 hover:translate-x-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shadow-[0_0_8px_rgba(16,185,129,0.5)]"></span> Meet Our Team</a></li>
                    <li><a href="{{ route('login') }}" class="text-slate-500 hover:text-emerald-600 font-medium transition-all duration-300 text-sm flex items-center gap-2 hover:translate-x-1"><span class="w-1.5 h-1.5 rounded-full bg-slate-200"></span> Portal Petugas</a></li>
                </ul>
            </div>

            {{-- Contact --}}
            <div class="md:col-span-3">
                <h4 class="font-extrabold text-slate-800 tracking-wider text-sm uppercase mb-6 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-slate-300"></span> Kontak
                </h4>
                <ul class="space-y-5">
                    <li class="flex items-center gap-3 group cursor-pointer">
                        <div class="w-9 h-9 rounded-full bg-white border border-slate-200 flex items-center justify-center flex-shrink-0 group-hover:border-emerald-300 group-hover:bg-emerald-50 transition-all duration-300 shadow-sm">
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-slate-500 font-medium text-sm group-hover:text-emerald-600 transition-colors">teamnutrigen@gmail.com</span>
                    </li>
                    <li class="flex items-center gap-3 group cursor-pointer">
                        <div class="w-9 h-9 rounded-full bg-white border border-slate-200 flex items-center justify-center flex-shrink-0 group-hover:border-emerald-300 group-hover:bg-emerald-50 transition-all duration-300 shadow-sm">
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-slate-500 font-medium text-sm group-hover:text-emerald-600 transition-colors">WhatsApp Support</span>
                    </li>
                    <li class="flex items-center gap-3 group cursor-pointer">
                        <div class="w-9 h-9 rounded-full bg-white border border-slate-200 flex items-center justify-center flex-shrink-0 group-hover:border-emerald-300 group-hover:bg-emerald-50 transition-all duration-300 shadow-sm">
                            <svg class="w-4 h-4 text-slate-400 group-hover:text-emerald-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </div>
                        <span class="text-slate-500 font-medium text-sm group-hover:text-emerald-600 transition-colors">Banda Aceh, Indonesia</span>
                    </li>
                </ul>
            </div>
        </div>

        <div class="pt-8 border-t border-slate-200 flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="text-slate-400 text-sm font-medium text-center md:text-left">
                &copy; 2026 <span class="text-emerald-600 font-bold">NutriGen 19JLP Team</span><br class="md:hidden"> 
                <span class="hidden md:inline"> &bull; </span>Hackathon Digdaya 2026
            </div>
            <div class="flex items-center gap-4">
                <a href="#" class="w-10 h-10 rounded-full bg-white hover:bg-emerald-50 hover:-translate-y-1 text-slate-400 hover:text-emerald-600 flex items-center justify-center transition-all duration-300 border border-slate-200 shadow-sm">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z" clip-rule="evenodd"></path></svg>
                </a>
                <a href="#" class="w-10 h-10 rounded-full bg-white hover:bg-emerald-50 hover:-translate-y-1 text-slate-400 hover:text-emerald-600 flex items-center justify-center transition-all duration-300 border border-slate-200 shadow-sm">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path fill-rule="evenodd" d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" clip-rule="evenodd"></path></svg>
                </a>
            </div>
        </div>
    </div>
</footer>
