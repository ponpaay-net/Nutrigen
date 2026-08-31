@props(['kaders', 'posyanduId'])

<div class="flex flex-col h-full relative">
    <div class="pb-4 flex items-center justify-between shrink-0">
        <h3 class="font-extrabold text-slate-800 flex items-center gap-2 text-[15px]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                class="w-5 h-5 text-slate-400">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
            </svg>
            Daftar Kader
        </h3>
        <span class="bg-emerald-50 text-emerald-600 px-2.5 py-1 rounded-full text-[11px] font-bold">{{ count($kaders) }} Kader Terdaftar</span>
    </div>

    <div class="flex-1 overflow-y-auto hide-scrollbar pb-2">
        <div class="flex flex-col gap-3">
            @forelse($kaders as $kader)
                <div class="bg-white rounded-[24px] p-5 flex items-center justify-between shadow-sm border border-slate-100 transition-all group relative overflow-hidden gap-2 hover:shadow-md hover:border-slate-200">
                    <div class="flex items-center gap-4 min-w-0">
                        @php
                            $colors = ['teal', 'emerald', 'blue', 'indigo', 'rose', 'amber'];
                            $colorIndex = crc32($kader['nama']) % count($colors);
                            $color = $colors[$colorIndex];
                            $bgClass = "bg-{$color}-100/80 text-{$color}-700 shadow-inner shadow-{$color}-200/50 ring-1 ring-slate-900/5";
                        @endphp
                        <div class="w-12 h-12 rounded-full {{ $bgClass }} font-black text-lg flex items-center justify-center shrink-0">
                            {{ substr($kader['nama'], 0, 1) }}
                        </div>
                        <div class="min-w-0">
                            <h4 class="text-[14px] font-extrabold text-slate-800 truncate" title="{{ $kader['nama'] }}">
                                {{ $kader['nama'] }}
                            </h4>
                            <div class="mt-1">
                                <span class="text-[9px] text-emerald-600 font-bold bg-emerald-50 px-2 py-0.5 rounded-full uppercase tracking-widest whitespace-nowrap">
                                    Kader Aktif
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        <div class="hidden sm:flex flex-col items-end text-right">
                            @if (($kader['aktivitas_bulan_ini'] ?? 0) > 0)
                                <span class="text-[15px] font-bold text-emerald-600 leading-none whitespace-nowrap">
                                    {{ $kader['aktivitas_bulan_ini'] }}
                                </span>
                                <span class="text-[10px] font-bold text-slate-500 mt-1 whitespace-nowrap">
                                    Pengukuran
                                </span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap">
                                    Bulan Ini
                                </span>
                            @else
                                <span class="text-[15px] font-bold text-slate-400 leading-none whitespace-nowrap">0</span>
                                <span class="text-[10px] font-bold text-slate-400 mt-1 whitespace-nowrap">Pengukuran</span>
                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap">Bulan Ini</span>
                            @endif
                        </div>

                        @if (!empty($kader['no_hp']))
                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kader['no_hp']) }}" target="_blank"
                                class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center transition-all duration-300 hover:bg-emerald-100 shrink-0"
                                title="Hubungi via WhatsApp">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                    <path fill-rule="evenodd" d="M4.804 21.644A6.707 6.707 0 006 21.75a6.721 6.721 0 003.583-1.029c.774.182 1.584.279 2.417.279 5.322 0 9.75-3.97 9.75-9 0-5.03-4.428-9-9.75-9s-9.75 3.97-9.75 9c0 2.409 1.025 4.587 2.674 6.192.232.226.277.428.254.543a3.73 3.73 0 01-.814 1.686.75.75 0 00.44 1.223zM8.25 10.875a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25zM10.875 12a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0zm4.875-1.125a1.125 1.125 0 100 2.25 1.125 1.125 0 000-2.25z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="flex flex-col items-center justify-center p-8 bg-white rounded-2xl border border-slate-100 border-dashed text-slate-400 gap-3 h-32">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-10 h-10 opacity-50">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                    <span class="text-sm font-bold">Belum ada kader terdaftar.</span>
                </div>
            @endforelse
        </div>

        <!-- Tambah Button -->
        <div class="pt-4 shrink-0">
            <button type="button" data-open-modal="kaderModal"
                class="w-full py-3.5 rounded-2xl bg-transparent text-emerald-600 font-bold text-sm hover:bg-emerald-50 transition-colors flex items-center justify-center gap-2 border border-emerald-300 border-dashed">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5"
                    stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Tambah Kader Baru
            </button>
        </div>
    </div>
</div>
