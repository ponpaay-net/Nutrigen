<x-layout.mobile-shell>
    <div class="flex-1 overflow-y-auto hide-scrollbar flex flex-col relative pb-24 pb-safe w-full bg-[#F7F5F0]">

        {{-- HEADER IDENTITAS NUTRIGEN --}}
        <header class="px-6 pt-8 pb-4 flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-[0_8px_18px_-6px_rgba(16,185,129,0.5)] shrink-0">
                <svg class="w-6 h-6 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M4 21V8l8 5 8-5v13"/><path d="M7 16v1M12 16v2M17 15v3"/><path d="M12 3h3l-1 2h1l-1.2 2.5"/>
                </svg>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-black uppercase tracking-[0.22em] text-emerald-600 mb-0.5">NutriGen</p>
                <p class="text-[12px] font-extrabold text-slate-700 leading-tight">Rapor Digital Tumbuh Kembang</p>
            </div>
        </header>

        <div class="px-5 flex flex-col flex-1">

            @if(empty($children))
                {{-- EMPTY STATE (tanpa data dummy) --}}
                <div class="flex-1 flex flex-col items-center justify-center text-center py-16">
                    <div class="w-20 h-20 rounded-full bg-emerald-50 flex items-center justify-center mb-5">
                        <svg class="w-10 h-10 text-emerald-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    </div>
                    <h2 class="text-lg font-black text-slate-800 mb-1.5">Belum Ada Data Anak</h2>
                    <p class="text-sm text-slate-500 max-w-[280px] leading-relaxed">Belum ada data anak yang tertaut dengan akun ini. Silakan hubungi kader Posyandu Anda untuk pendaftaran.</p>
                </div>
            @else
                <div class="mb-6 mt-1">
                    <p class="text-[13px] text-emerald-600 font-extrabold mb-0.5 tracking-wide">{{ $greeting ?? 'Selamat pagi, Ibunda' }}</p>
                    <h1 class="text-slate-900 font-black text-[22px] leading-tight">Mari pilih profil si Kecil</h1>
                </div>

                <div class="space-y-3.5">
                    @foreach($children as $child)
                        @php
                            $st = strtolower((string) ($child['status'] ?? ''));
                            $badge = str_contains($st, 'stunting')
                                ? 'bg-rose-100 text-rose-700'
                                : (str_contains($st, 'risiko') || str_contains($st, 'kurang')
                                    ? 'bg-amber-100 text-amber-700'
                                    : (str_contains($st, 'normal') || str_contains($st, 'baik')
                                        ? 'bg-emerald-100 text-emerald-700'
                                        : 'bg-slate-100 text-slate-500'));
                        @endphp
                        <a href="{{ $child['url'] }}" class="block w-full text-left rounded-[22px] bg-white border border-slate-100 shadow-[0_8px_28px_-14px_rgba(15,23,42,0.15)] p-4 flex items-center gap-4 active:scale-[0.98] transition-transform">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white flex items-center justify-center font-black text-lg shrink-0">
                                {{ $child['initials'] ?? 'A' }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="font-black text-[17px] text-slate-900 leading-tight mb-1 truncate">{{ $child['name'] }}</h3>
                                <p class="text-[13px] text-slate-500 font-bold truncate">{{ $child['age'] }}</p>
                                @if(!empty($child['status']))
                                    <span class="inline-flex mt-2 px-2.5 py-0.5 rounded-full text-[11px] font-extrabold {{ $badge }}">{{ ucfirst($child['status']) }}</span>
                                @endif
                            </div>
                            <div class="w-9 h-9 rounded-full flex items-center justify-center text-slate-300 bg-slate-50 shrink-0">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="mt-8 mb-6 text-center">
                    <p class="text-[12px] font-bold text-slate-400">Ada profil anak yang belum terdaftar? Hubungi kader Posyandu Anda.</p>
                </div>
            @endif
        </div>
    </div>
</x-layout.mobile-shell>
