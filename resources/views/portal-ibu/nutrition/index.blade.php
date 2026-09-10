<x-layout.mobile-shell>
    <div x-data="{ state: '{{ $pageState ?? 'normal' }}', openRecipe: false }"
        class="flex-1 overflow-y-auto hide-scrollbar flex flex-col relative pb-[120px] pb-safe w-full bg-[#F7F5F0]">

        <!-- HEADER (komponen bersama) -->
        <x-navigation.portal-header variant="page" eyebrow="Edukasi" title="Gizi & Menu" :hasBack="true"
            :backUrl="\Illuminate\Support\Facades\URL::temporarySignedRoute(
                'portal-ibu.home',
                now()->addDays(config('portal.link_ttl_days')),
                ['balita' => request('balita'), 'orang_tua' => request('orang_tua')],
            )" :initials="$user['initials'] ?? 'A'" :avatar="$user['avatar'] ?? null" />

        <div class="px-5 pt-5 pb-6 space-y-6 flex-1 flex flex-col">

            <!-- 1. HERO BANNER: INFORMASI PENTING (senada beranda) -->
            <div
                class="bg-white rounded-[24px] p-5 shadow-[0_4px_20px_rgba(46,125,50,0.06)] border border-[#C8E6C9]/50 relative overflow-hidden">
                <!-- 3D Bowl Illustration -->
                <div class="absolute right-[-12px] bottom-[-12px] w-[130px] h-[130px] pointer-events-none opacity-95">
                    <svg viewBox="0 0 100 100" class="w-full h-full">
                        <!-- Leaves behind -->
                        <path d="M62 30 Q78 14 88 30 Q76 46 62 30 Z" fill="#A5D6A7" />
                        <path d="M72 24 Q84 12 92 26 Q82 40 72 24 Z" fill="#81C784" />
                        <!-- Bowl -->
                        <ellipse cx="48" cy="70" rx="32" ry="10" fill="#B3E5FC" />
                        <path d="M16 58 Q16 80 48 80 Q80 80 80 58 Z" fill="#4FC3F7" />
                        <ellipse cx="48" cy="58" rx="32" ry="10" fill="#E1F5FE" />
                        <!-- Egg -->
                        <circle cx="34" cy="52" r="12" fill="#FFFFFF" />
                        <circle cx="36" cy="54" r="5" fill="#FFCA28" />
                        <!-- Salmon -->
                        <path d="M50 44 Q66 38 74 50 Q64 60 50 52 Z" fill="#FF8A80" />
                        <path d="M55 47 L68 51 M58 50 L66 54" stroke="#FFFFFF" stroke-width="1.5"
                            stroke-linecap="round" />
                    </svg>
                </div>

                <div class="relative z-10 w-[74%]">
                    <div class="flex items-center gap-2 mb-3">
                        <div
                            class="w-7 h-7 rounded-full bg-[#2E7D32] text-white flex items-center justify-center shadow-[0_4px_10px_rgba(46,125,50,0.35)]">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M11.3 1.046A1 1 0 0112 2v5h4a1 1 0 01.82 1.573l-7 10A1 1 0 018 18v-5H4a1 1 0 01-.82-1.573l7-10a1 1 0 011.12-.381z"
                                    clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <span
                            class="bg-[#E8F5E9] text-[#2E7D32] border border-[#C8E6C9] px-3 py-1 rounded-full text-[9.5px] font-black uppercase tracking-widest">Informasi
                            Penting</span>
                    </div>
                    <h2 class="text-[19px] font-black text-slate-900 mb-1.5 leading-tight tracking-tight">Khusus untuk
                        si Kecil</h2>
                    <div class="w-16 h-[3px] bg-[#4CAF50] rounded-full mb-2.5"></div>
                    <p class="text-[12px] font-medium text-slate-500 leading-relaxed">
                        {{ $trustBannerMessage ?? 'Lanjutkan pemberian nutrisi seimbang (Karbohidrat, Protein, Serat) sesuai porsi harian sesuai usia si Kecil.' }}
                    </p>
                    <!-- Feature tags -->
                    <div class="flex flex-wrap gap-x-4 gap-y-2 mt-4">
                        <span class="inline-flex items-center gap-1.5 text-[10.5px] font-bold text-[#2E7D32]">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Gizi Seimbang
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[10.5px] font-bold text-[#2E7D32]">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Porsi Harian
                        </span>
                        <span class="inline-flex items-center gap-1.5 text-[10.5px] font-bold text-[#2E7D32]">
                            <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            Dukungan Terbaik
                        </span>
                    </div>
                </div>
            </div>

            <!-- 2. IDE RESEP HARI INI (desain foto 2: gradient hijau + panah gelap) -->
            @if (!empty($heroMeal))
                <div class="relative w-full rounded-[28px] overflow-hidden shadow-[0_4px_20px_rgba(46,125,50,0.06)] border border-[#C8E6C9]/50 group cursor-pointer active:scale-[0.99] transition-transform"
                    x-on:click="openRecipe = true" x-data>
                    <div class="h-56 w-full bg-slate-100 relative">
                        <img src="{{ asset('images/menu/' . ($heroMeal['image'] ?? 'placeholder.jpg')) }}"
                            alt="{{ $heroMeal['title'] ?? 'Menu Utama' }}" class="w-full h-full object-cover">
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/75 via-black/15 to-transparent opacity-95">
                        </div>
                        <!-- Green gradient accent left -->
                        <div
                            class="absolute inset-0 bg-gradient-to-r from-[#2E7D32]/45 via-transparent to-transparent pointer-events-none">
                        </div>
                    </div>
                    <div class="absolute top-4 left-4 flex items-center gap-2.5">
                        <div
                            class="w-10 h-10 rounded-full bg-[#A5D6A7] text-[#1B5E20] flex items-center justify-center shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                </path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-[17px] font-black text-white drop-shadow tracking-tight leading-tight">
                                {{ $heroMeal['title'] ?? 'Ide Resep Hari Ini' }}</h3>
                            <p class="text-[11px] font-semibold text-white/85 drop-shadow">Resep praktis dan bernutrisi
                                untuk si Kecil</p>
                        </div>
                    </div>
                    <!-- Dark circular arrow -->
                    <div
                        class="absolute bottom-4 right-4 w-11 h-11 rounded-full bg-[#1E2A3A] text-white flex items-center justify-center shadow-lg group-active:scale-90 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </div>
                </div>
            @else
                <!-- EMPTY STATE: Ide Resep Hari Ini (matches card style above, clear contrast) -->
                <div
                    class="bg-[#E8F5E9] rounded-[28px] p-5 shadow-[0_6px_28px_rgba(46,125,50,0.10)] border border-[#A5D6A7] relative overflow-hidden flex items-center gap-4">
                    <!-- Icon bulb -->
                    <div
                        class="w-14 h-14 rounded-2xl bg-white text-[#2E7D32] flex items-center justify-center shadow-[0_4px_12px_rgba(76,175,80,0.25)] shrink-0">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                            </path>
                        </svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-[17.5px] font-black text-slate-900 mb-1 tracking-tight">Ide Resep Hari Ini
                        </h3>
                        <p class="text-[12px] font-medium text-slate-600 leading-relaxed">Resep praktis dan bernutrisi
                            untuk si Kecil sedang disiapkan. Cek kembali nanti ya, Bu!</p>
                    </div>
                    <!-- Circular arrow: bottom-right corner -->
                    <div
                        class="absolute bottom-3 right-3 w-9 h-9 rounded-full bg-[#1E2A3A] text-white flex items-center justify-center shadow-md opacity-50">
                        <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </div>
                </div>
            @endif

            <!-- 3. TENTANG REKOMENDASI MENU (narasi tenaga gizi — antisipasi pertanyaan juri) -->
            <div
                class="bg-white rounded-[28px] p-5 shadow-[0_4px_20px_rgba(46,125,50,0.06)] border border-[#C8E6C9]/60 flex gap-4 items-start relative overflow-hidden">
                <!-- Shield-check illustration LEFT -->
                <div class="w-[60px] h-[60px] shrink-0 pointer-events-none mt-0.5">
                    <svg viewBox="0 0 100 100" class="w-full h-full">
                        <circle cx="50" cy="50" r="46" fill="#E8F5E9" />
                        <path d="M50 16 L80 29 V54 C80 73 65 85 50 90 C35 85 20 73 20 54 V29 Z" fill="#2E7D32" />
                        <path d="M37 50 L46 59 L64 39" stroke="#FFFFFF" stroke-width="6.5" fill="none"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <!-- Text RIGHT -->
                <div class="flex-1 min-w-0">
                    <div
                        class="inline-flex items-center gap-1.5 bg-[#E8F5E9] text-[#2E7D32] px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest mb-2">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Telaah Tenaga Gizi
                    </div>
                    <h3 class="text-[16px] font-black text-slate-900 tracking-tight mb-1.5">Tentang Rekomendasi
                        Menu</h3>
                    <p class="text-[12px] font-medium text-slate-500 leading-relaxed">
                        Rekomendasi menu disusun &amp; <span class="font-bold text-[#2E7D32]">ditinjau oleh Tenaga
                            Gizi Puskesmas/Posyandu</span> — bukan keputusan kecerdasan buatan yang berlepas tangan.
                        Kebutuhan gizi balita sangat sensitif &amp; fluktuatif, sehingga tenaga kesehatan tetap
                        menjadi penentu akhir.
                    </p>
                </div>
            </div>

            <!-- 4. KATEGORI EDUKASI (desain foto 2) -->
            <div>
                <div class="flex items-center justify-between mb-3.5">
                    <div class="flex items-center gap-2">
                        <h3 class="text-[17px] font-black text-slate-900 tracking-tight">Kategori Edukasi</h3>
                        <span class="inline-flex items-center gap-1 bg-[#E8F5E9] text-[#2E7D32] px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Segera Hadir
                        </span>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <!-- Gizi Bayi -->
                    <div
                        class="bg-white rounded-2xl p-4 shadow-[0_2px_12px_rgba(46,125,50,0.06)] border border-slate-100/70 cursor-not-allowed opacity-60">
                        <div class="w-11 h-11 rounded-full bg-[#FFF3CD] flex items-center justify-center mb-3">
                            <svg class="w-[22px] h-[22px] text-[#F9A825]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 8v13m0-13V3m0 5a4 4 0 00-4 4c0 1 .5 2 1.5 2.5S11 15 12 15m0-7a4 4 0 014 4c0 1-.5 2-1.5 2.5S13 15 12 15m-6 6h12">
                                </path>
                            </svg>
                        </div>
                        <h4 class="text-[13px] font-black text-slate-800 leading-snug">Gizi Bayi<br>0 – 12 bulan</h4>
                    </div>
                    <!-- Makanan Sehat -->
                    <div
                        class="bg-white rounded-2xl p-4 shadow-[0_2px_12px_rgba(46,125,50,0.06)] border border-slate-100/70 cursor-not-allowed opacity-60">
                        <div class="w-11 h-11 rounded-full bg-[#E8F5E9] flex items-center justify-center mb-3">
                            <svg class="w-[22px] h-[22px] text-[#2E7D32]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 6c-2 0-3 1.5-3 3-2 0-4 1-4 3.5S7 17 8.5 17c.5 1.5 1.8 3 3.5 3s3-1.5 3.5-3c1.5 0 3.5-1 3.5-4.5S17 9 15 9c0-1.5-1-3-3-3zm0 3v11">
                                </path>
                            </svg>
                        </div>
                        <h4 class="text-[13px] font-black text-slate-800 leading-snug">Makanan Sehat Tips & Panduan
                        </h4>
                    </div>
                    <!-- Kesehatan & Imunitas -->
                    <div
                        class="bg-white rounded-2xl p-4 shadow-[0_2px_12px_rgba(46,125,50,0.06)] border border-slate-100/70 cursor-not-allowed opacity-60">
                        <div class="w-11 h-11 rounded-full bg-[#E8F5E9] flex items-center justify-center mb-3">
                            <svg class="w-[22px] h-[22px] text-[#2E7D32]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0zM12 9v6m-3-3h6">
                                </path>
                            </svg>
                        </div>
                        <h4 class="text-[13px] font-black text-slate-800 leading-snug">Kesehatan Imunitas & Tumbuh</h4>
                    </div>
                    <!-- Resep Praktis -->
                    <div
                        class="bg-white rounded-2xl p-4 shadow-[0_2px_12px_rgba(46,125,50,0.06)] border border-slate-100/70 cursor-not-allowed opacity-60">
                        <div class="w-11 h-11 rounded-full bg-[#FFF3CD] flex items-center justify-center mb-3">
                            <svg class="w-[22px] h-[22px] text-[#F9A825]" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 3a5 5 0 00-5 5c0 1.1.4 2.1 1 2.8V13h8v-2.2c.6-.7 1-1.7 1-2.8a5 5 0 00-5-5zM9 16h6m-5 3h4">
                                </path>
                            </svg>
                        </div>
                        <h4 class="text-[13px] font-black text-slate-800 leading-snug">Resep Praktis & Bergizi</h4>
                    </div>
                </div>
            </div>

        </div>

        <!-- Recipe Bottom Sheet Component -->
        <x-domain.recipe-bottom-sheet :recipe="$heroMeal ?? []" />
    </div>

    <!-- BOTTOM NAVIGATION -->
    <x-navigation.bottom-navigation active="nutrition" />
</x-layout.mobile-shell>
