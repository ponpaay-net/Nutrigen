<x-layout.mobile-shell>
    <div x-data="{ state: '{{ $pageState ?? 'normal' }}', openRecipe: false }"
        class="flex-1 overflow-y-auto hide-scrollbar flex flex-col relative pb-[120px] pb-safe w-full bg-[#F1F8F2]">

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
                <!-- EMPTY STATE: Ide Resep Hari Ini (soft green gradient card) -->
                <div
                    class="bg-gradient-to-br from-[#E8F5E9] to-white rounded-[28px] p-5 shadow-[0_6px_28px_rgba(46,125,50,0.06)] border border-[#C8E6C9]/60 relative overflow-hidden">
                    <div class="relative z-10 w-[72%]">
                        <div class="flex items-start justify-between">
                            <div
                                class="w-11 h-11 rounded-full bg-[#A5D6A7] text-[#1B5E20] flex items-center justify-center shadow-[0_4px_12px_rgba(76,175,80,0.25)]">
                                <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z">
                                    </path>
                                </svg>
                            </div>
                            <div
                                class="w-9 h-9 rounded-full bg-[#1E2A3A] text-white flex items-center justify-center shadow-md mt-1">
                                <svg class="w-[18px] h-[18px]" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                        d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </div>
                        </div>
                        <h3 class="text-[17.5px] font-black text-slate-900 mt-3 mb-1 tracking-tight">Ide Resep Hari Ini
                        </h3>
                        <p class="text-[12px] font-medium text-slate-500 leading-relaxed">Resep praktis dan bernutrisi
                            untuk si Kecil sedang disiapkan. Cek kembali nanti ya, Bu!</p>
                    </div>
                    <!-- Food bowl illustration -->
                    <div
                        class="absolute right-[-10px] bottom-[-14px] w-[110px] h-[110px] pointer-events-none opacity-95">
                        <svg viewBox="0 0 100 100" class="w-full h-full">
                            <rect x="8" y="66" width="84" height="26" rx="6" fill="#DCEDC8"
                                transform="rotate(-4 50 79)" />
                            <ellipse cx="50" cy="56" rx="30" ry="9" fill="#C8E6C9" />
                            <path d="M20 46 Q20 62 50 62 Q80 62 80 46 Z" fill="#66BB6A" />
                            <ellipse cx="50" cy="46" rx="30" ry="9" fill="#E8F5E9" />
                            <circle cx="40" cy="43" r="6" fill="#FFFFFF" />
                            <path d="M55 38 Q66 34 71 42 Q63 49 55 44 Z" fill="#FFAB91" />
                            <circle cx="63" cy="36" r="4" fill="#AED581" />
                        </svg>
                    </div>
                </div>
            @endif

            <!-- 3. TIPS NUTRISI SI KECIL (desain foto 2: ilustrasi kiri + tombol ungu kanan) -->
            <div
                class="bg-white rounded-[28px] p-5 shadow-[0_4px_20px_rgba(123,97,225,0.07)] border border-purple-100/60 flex gap-4 items-center relative overflow-hidden">
                <!-- Purple book illustration LEFT -->
                <div class="w-[96px] h-[110px] shrink-0 pointer-events-none">
                    <svg viewBox="0 0 100 115" class="w-full h-full">
                        <!-- Leaves accent -->
                        <path d="M14 22 Q26 10 36 22 Q26 34 14 22 Z" fill="#C5F1DE" />
                        <!-- Book -->
                        <path d="M22 38 Q22 30 30 30 L50 30 L50 96 Q35 88 24 94 Q20 95 20 90 Z" fill="#7B61E1" />
                        <path d="M78 38 Q78 30 70 30 L50 30 L50 96 Q65 88 76 94 Q80 95 80 90 Z" fill="#9B85F0" />
                        <line x1="50" y1="30" x2="50" y2="96" stroke="#5B3FC4"
                            stroke-width="2.5" />
                        <!-- Heart on cover -->
                        <path d="M50 52 A 5 5 0 0 0 42 49 A 5 5 0 0 0 34 52 Q 34 62 42 67 Q 50 62 50 52 Z"
                            transform="translate(8 -4)" fill="#FFE082" />
                        <!-- Page lines -->
                        <line x1="27" y1="44" x2="44" y2="44" stroke="#C5B8F5"
                            stroke-width="2.5" stroke-linecap="round" />
                        <line x1="27" y1="52" x2="41" y2="52" stroke="#C5B8F5"
                            stroke-width="2.5" stroke-linecap="round" />
                        <line x1="56" y1="44" x2="73" y2="44" stroke="#DDD3F9"
                            stroke-width="2.5" stroke-linecap="round" />
                        <line x1="56" y1="52" x2="70" y2="52" stroke="#DDD3F9"
                            stroke-width="2.5" stroke-linecap="round" />
                    </svg>
                </div>
                <!-- Text RIGHT -->
                <div class="flex-1 min-w-0">
                    <h3 class="text-[17px] font-black text-slate-900 tracking-tight mb-1.5">Tips Nutrisi Si Kecil</h3>
                    <p class="text-[12px] font-medium text-slate-500 leading-relaxed mb-4">Rekomendasi resep
                        personalisasi khusus untuk anak Anda sedang dalam tahap pengembangan MVP V3.</p>
                    <button
                        class="inline-flex items-center gap-2 bg-[#7B61E1] active:bg-[#5B3FC4] text-white font-extrabold pl-4 pr-3 py-2.5 rounded-full shadow-[0_6px_16px_rgba(123,97,225,0.35)] text-[12.5px] focus:outline-none transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                            </path>
                        </svg>
                        Lihat Tips & Resep
                        <svg class="w-3.5 h-3.5 opacity-80" fill="none" stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- 4. KATEGORI EDUKASI (desain foto 2) -->
            <div>
                <div class="flex items-center justify-between mb-3.5">
                    <h3 class="text-[17px] font-black text-slate-900 tracking-tight">Kategori Edukasi</h3>
                    <button
                        class="inline-flex items-center gap-1 text-[12.5px] font-extrabold text-[#4CAF50] focus:outline-none">
                        Lihat Semua
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7">
                            </path>
                        </svg>
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <!-- Gizi Bayi -->
                    <div
                        class="bg-white rounded-2xl p-4 shadow-[0_2px_12px_rgba(46,125,50,0.06)] border border-slate-100/70 cursor-pointer active:scale-95 transition-transform">
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
                        class="bg-white rounded-2xl p-4 shadow-[0_2px_12px_rgba(46,125,50,0.06)] border border-slate-100/70 cursor-pointer active:scale-95 transition-transform">
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
                        class="bg-white rounded-2xl p-4 shadow-[0_2px_12px_rgba(46,125,50,0.06)] border border-slate-100/70 cursor-pointer active:scale-95 transition-transform">
                        <div class="w-11 h-11 rounded-full bg-[#E3F2FD] flex items-center justify-center mb-3">
                            <svg class="w-[22px] h-[22px] text-[#1976D2]" fill="none" stroke="currentColor"
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
                        class="bg-white rounded-2xl p-4 shadow-[0_2px_12px_rgba(46,125,50,0.06)] border border-slate-100/70 cursor-pointer active:scale-95 transition-transform">
                        <div class="w-11 h-11 rounded-full bg-[#FCE4EC] flex items-center justify-center mb-3">
                            <svg class="w-[22px] h-[22px] text-[#D81B60]" fill="none" stroke="currentColor"
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
