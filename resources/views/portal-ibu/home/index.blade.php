<x-layout.mobile-shell>
    <div x-data="{ state: '{{ $pageState ?? 'normal' }}', isPending: {{ isset($hasPending) && $hasPending ? 'true' : 'false' }} }" class="flex-1 overflow-y-auto hide-scrollbar flex flex-col relative pb-[120px] pb-safe w-full bg-[#F1F8F2]">

        <!-- DECORATIVE LEAF BLOBS -->
        <div class="absolute top-[-50px] right-[-50px] w-[190px] h-[190px] bg-[#DCEDDD] rounded-full blur-2xl opacity-60 pointer-events-none"></div>
        <div class="absolute top-[130px] left-[-60px] w-[170px] h-[170px] bg-[#E8F5E9] rounded-full blur-2xl opacity-70 pointer-events-none"></div>

        <!-- MAIN CONTENT CONTAINER -->
        <div class="relative z-10 flex flex-col flex-1 px-5 pt-7 pb-6">

            <!-- 1. HEADER (komponen bersama) -->
            <x-navigation.portal-header
                variant="greeting"
                :name="$user['child_name'] ?? 'Ibu'"
                :avatar="$user['avatar'] ?? null"
            />

            <!-- PENDING BANNER -->
            <template x-if="isPending">
                <x-feedback.pending-banner message="Data pengukuran terbaru sedang dikonfirmasi oleh Puskesmas." class="rounded-2xl shadow-sm mb-4" />
            </template>

            <!-- EMPTY & ERROR STATES -->
            <template x-if="state === 'error'">
                <x-feedback.error-state />
            </template>
            <template x-if="state === 'empty'">
                <div class="space-y-6">
                    <x-feedback.empty-state title="Belum Ada Rekam Medis" message="Yuk bawa si Kecil ke Posyandu terdekat." actionText="Cari Jadwal Posyandu" />
                </div>
            </template>

            <!-- 2. STATUS PERTUMBUHAN CARD -->
            <div x-show="['normal', 'kuning', 'merah'].includes(state)"
                 class="bg-white rounded-[24px] p-5 shadow-[0_4px_20px_rgba(46,125,50,0.06)] border border-[#C8E6C9]/40 cursor-pointer active:scale-[0.99] transition-transform"
                 x-on:click="window.location.href='{!! \Illuminate\Support\Facades\URL::temporarySignedRoute('portal-ibu.growth', now()->addDays(config('portal.link_ttl_days')), ['balita' => request('balita'), 'orang_tua' => request('orang_tua')]) !!}'">

                <div class="flex gap-4">
                    <!-- Left Illustration -->
                    <div class="w-[104px] rounded-2xl shrink-0 flex items-end justify-center pt-4 relative overflow-hidden"
                         :class="{ 'bg-[#C8E6C9]': state === 'normal', 'bg-[#FFE9B8]': state === 'kuning', 'bg-[#FFD1D9]': state === 'merah' }">
                        <!-- Height ruler -->
                        <div class="absolute left-2.5 top-3 bottom-3 w-[3px] rounded-full"
                             :class="{ 'bg-[#A5D6A7]': state === 'normal', 'bg-[#FFD54F]': state === 'kuning', 'bg-[#FF9DA9]': state === 'merah' }"></div>
                        <div class="absolute left-[7px] top-4 space-y-[22px]">
                            @for ($i = 0; $i < 6; $i++)
                                <div class="w-2.5 h-[2px] rounded-full" :class="{ 'bg-[#A5D6A7]': state === 'normal', 'bg-[#FFD54F]': state === 'kuning', 'bg-[#FF9DA9]': state === 'merah' }"></div>
                            @endfor
                        </div>
                        <!-- Child (celebrating: curly hair, arms up, heart-check shirt) + sprout ruler -->
                        <svg viewBox="0 0 80 92" class="w-full h-full relative z-10">
                        <!-- Sprout on top of ruler -->
                        <path d="M12 6 Q8 1 2 2 Q4 8 11 8 Z" fill="#66BB6A"/>
                        <path d="M13 6 Q17 1 23 2 Q21 8 14 8 Z" fill="#4CAF50"/>
                        <path d="M12.5 7 L12.5 12" stroke="#2E7D32" stroke-width="1.5" stroke-linecap="round"/>
                        <!-- Darker green curved backdrop -->
                        <path d="M62 84 Q60 40 40 34 Q70 30 78 52 L78 84 Z" :fill="state === 'normal' ? '#A5D6A7' : (state === 'kuning' ? '#FFD54F' : '#FF9DA9')" opacity="0.55"/>
                        <!-- Raised arms (behind torso) -->
                        <path d="M34 44 Q26 36 24 26" stroke="#FFE0BD" stroke-width="5.5" fill="none" stroke-linecap="round"/>
                        <path d="M54 44 Q62 36 64 26" stroke="#FFE0BD" stroke-width="5.5" fill="none" stroke-linecap="round"/>
                        <!-- Head -->
                        <circle cx="44" cy="22" r="12" fill="#FFE0BD"/>
                        <!-- Ears -->
                        <circle cx="32.5" cy="23" r="2.5" fill="#FFE0BD"/>
                        <circle cx="55.5" cy="23" r="2.5" fill="#FFE0BD"/>
                        <!-- Curly hair -->
                        <circle cx="36" cy="13" r="5" :fill="state === 'normal' ? '#6D4C41' : '#5D4037'"/>
                        <circle cx="44" cy="10" r="5.5" :fill="state === 'normal' ? '#6D4C41' : '#5D4037'"/>
                        <circle cx="52" cy="13" r="5" :fill="state === 'normal' ? '#6D4C41' : '#5D4037'"/>
                        <circle cx="33" cy="18" r="3.5" :fill="state === 'normal' ? '#6D4C41' : '#5D4037'"/>
                        <circle cx="55" cy="18" r="3.5" :fill="state === 'normal' ? '#6D4C41' : '#5D4037'"/>
                        <!-- Happy squint eyes -->
                        <path d="M38 22 Q40 20 42 22" stroke="#4E342E" stroke-width="1.8" fill="none" stroke-linecap="round"/>
                        <path d="M46 22 Q48 20 50 22" stroke="#4E342E" stroke-width="1.8" fill="none" stroke-linecap="round"/>
                        <!-- Big open smile -->
                        <path d="M38 27 Q44 34 50 27 Q47 29 44 29 Q41 29 38 27 Z" fill="#5D4037"/>
                        <path d="M39 28 Q44 32.5 49 28" fill="#B06A5A"/>
                        <!-- Torso: green tee -->
                        <path d="M35 35 Q44 32 53 35 L56 56 Q44 60 32 56 Z" :fill="state === 'normal' ? '#66BB6A' : (state === 'kuning' ? '#FFCA28' : '#EF5350')"/>
                        <!-- Heart + check print -->
                        <path d="M44 42 A 3.2 3.2 0 0 0 40 39.5 A 3.2 3.2 0 0 0 36 42 Q 36 47 40 50 Q 44 47 44 42 Z" transform="translate(4 -2)" fill="#FFFFFF"/>
                        <path d="M42 41 L43.6 42.8 L46.4 39" stroke="#43A047" stroke-width="1.6" fill="none" stroke-linecap="round" stroke-linejoin="round" transform="translate(0 -1)"/>
                        <!-- Shorts -->
                        <rect x="34" y="55" width="20" height="10" rx="3" fill="#37474F"/>
                        <!-- Legs -->
                        <rect x="36.5" y="64" width="6" height="17" rx="3" fill="#FFE0BD"/>
                        <rect x="45.5" y="64" width="6" height="17" rx="3" fill="#FFE0BD"/>
                        <!-- Shoes -->
                        <ellipse cx="39" cy="82.5" rx="5.5" ry="3" :fill="state === 'normal' ? '#2E7D32' : (state === 'kuning' ? '#F57F17' : '#C62828')"/>
                        <ellipse cx="49" cy="82.5" rx="5.5" ry="3" :fill="state === 'normal' ? '#2E7D32' : (state === 'kuning' ? '#F57F17' : '#C62828')"/>
                        </svg>
                    </div>

                    <!-- Right Content -->
                    <div class="flex-1 min-w-0">
                        <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[9px] font-black uppercase tracking-widest mb-2"
                              :class="{
                                  'bg-[#E8F5E9] text-[#2E7D32]': state === 'normal',
                                  'bg-[#FFF3CD] text-[#B8860B]': state === 'kuning',
                                  'bg-[#FFEBEE] text-[#C62828]': state === 'merah'
                              }">
                            ✅ {{ $summary['status'] ?? 'Pertumbuhan Normal' }}
                        </span>
                        <h2 class="text-[18px] font-black leading-tight tracking-tight mb-1.5"
                            :class="{
                                'text-[#1B5E20]': state === 'normal',
                                'text-[#8a6d00]': state === 'kuning',
                                'text-[#B71C1C]': state === 'merah'
                            }">
                            {{ $summary['title'] ?? 'Sesuai Standar Usia' }}
                        </h2>
                        <p class="text-[11.5px] font-medium text-slate-500 leading-relaxed line-clamp-3">
                            {{ $summary['message'] ?? 'Berdasarkan standar penilaian WHO, berat dan tinggi badan anak berada pada kurva pertumbuhan yang ideal.' }}
                        </p>
                    </div>
                </div>

                <!-- Stats Row -->
                <div class="grid grid-cols-3 gap-2 mt-4">
                    <div class="bg-[#F5F7F5] rounded-xl px-2 py-3 text-center">
                        <svg class="w-4 h-4 mx-auto text-[#4CAF50] mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Terakhir diukur</p>
                        <p class="text-[12px] font-black text-slate-800">{{ $measurement['date'] ?? '-' }}</p>
                    </div>
                    <div class="bg-[#F5F7F5] rounded-xl px-2 py-3 text-center">
                        <svg class="w-4 h-4 mx-auto text-[#4CAF50] mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l6 6a4 4 0 006 0l6-6M3 6v12M21 6v12M3 18h18"></path></svg>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Berat Badan</p>
                        <p class="text-[12px] font-black text-slate-800">{{ $measurement['weight'] ?? '-' }} kg</p>
                    </div>
                    <div class="bg-[#F5F7F5] rounded-xl px-2 py-3 text-center">
                        <svg class="w-4 h-4 mx-auto text-[#4CAF50] mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2 8l20 8M2 8l20 8M2 8v8M22 8v8"></path></svg>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-wide mb-0.5">Tinggi Badan</p>
                        <p class="text-[12px] font-black text-slate-800">{{ $measurement['height'] ?? '-' }} cm</p>
                    </div>
                </div>
            </div>

            <!-- 3. IDE BEKAL BERGIZI CARD (YELLOW) -->
            <div x-show="state !== 'empty' && state !== 'error'" class="bg-[#FFF8E1] rounded-[24px] p-5 mt-5 relative overflow-hidden border border-[#FFE082]/50">
                <!-- Floating food illustration -->
                <div class="absolute right-[-14px] bottom-[-14px] w-[110px] h-[110px] pointer-events-none opacity-95">
                    <svg viewBox="0 0 100 100" class="w-full h-full">
                        <circle cx="50" cy="55" r="34" fill="#FFFFFF"/>
                        <circle cx="50" cy="55" r="26" fill="#FFF3E0"/>
                        <path d="M30 52 Q38 40 50 44 Q64 40 70 52 Q60 60 50 58 Q40 60 30 52Z" fill="#FFFFFF"/>
                        <circle cx="38" cy="48" r="6" fill="#A5D6A7"/>
                        <circle cx="35" cy="45" r="4" fill="#81C784"/>
                        <path d="M58 46 L68 42 L66 50 Z" fill="#FF8A65"/>
                        <rect x="55" y="58" width="12" height="4" rx="2" fill="#FFB74D" transform="rotate(-12 61 60)"/>
                        <circle cx="44" cy="53" r="1.2" fill="#5D4037"/>
                        <circle cx="52" cy="53" r="1.2" fill="#5D4037"/>
                        <path d="M45 57 Q48 60 51 57" stroke="#5D4037" stroke-width="1.4" fill="none" stroke-linecap="round"/>
                    </svg>
                </div>

                <div class="relative z-10 w-[72%]">
                    <div class="w-11 h-11 rounded-full bg-[#FFC107] flex items-center justify-center shadow-[0_4px_12px_rgba(255,193,7,0.35)] mb-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                    </div>
                    <h3 class="text-[17px] font-black text-[#3E2723] mb-1.5 tracking-tight">Butuh ide bekal bergizi?</h3>
                    <p class="text-[12px] font-medium text-[#8D6E63] leading-relaxed mb-4">Temukan resep bernutrisi yang dirancang khusus untuk mendukung masa emas si Kecil.</p>
                    <button class="inline-flex items-center gap-2 bg-[#FF9800] active:bg-[#F57C00] text-white font-extrabold pl-4 pr-3 py-3 rounded-full shadow-[0_6px_16px_rgba(255,152,0,0.35)] transition-colors text-[13px] focus:outline-none"
                            x-on:click="window.location.href='{!! \Illuminate\Support\Facades\URL::temporarySignedRoute('portal-ibu.nutrition', now()->addDays(config('portal.link_ttl_days')), ['balita' => request('balita'), 'orang_tua' => request('orang_tua')]) !!}'">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/></svg>
                        Lihat Rekomendasi Menu
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>

            <!-- 4. AKSES CEPAT -->
            <div class="mt-6">
                <h3 class="text-[16px] font-black text-slate-800 tracking-tight mb-3">Akses Cepat</h3>
                <div class="grid grid-cols-2 gap-3">
                    <!-- Grafik Pertumbuhan -->
                    <div class="bg-white rounded-2xl p-4 flex flex-col items-center gap-2.5 shadow-[0_2px_12px_rgba(46,125,50,0.06)] border border-slate-100/70 cursor-pointer active:scale-95 transition-transform"
                         x-on:click="window.location.href='{!! \Illuminate\Support\Facades\URL::temporarySignedRoute('portal-ibu.growth', now()->addDays(config('portal.link_ttl_days')), ['balita' => request('balita'), 'orang_tua' => request('orang_tua')]) !!}'">
                        <div class="w-12 h-12 rounded-full bg-[#4CAF50] flex items-center justify-center shadow-[0_4px_12px_rgba(76,175,80,0.3)]">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        </div>
                        <span class="text-[12px] font-bold text-slate-600 text-center leading-tight">Grafik<br>Pertumbuhan</span>
                    </div>
                    <!-- Riwayat Pengukuran -->
                    <div class="bg-white rounded-2xl p-4 flex flex-col items-center gap-2.5 shadow-[0_2px_12px_rgba(46,125,50,0.06)] border border-slate-100/70 cursor-pointer active:scale-95 transition-transform"
                         x-on:click="window.location.href='{!! \Illuminate\Support\Facades\URL::temporarySignedRoute('portal-ibu.growth', now()->addDays(config('portal.link_ttl_days')), ['balita' => request('balita'), 'orang_tua' => request('orang_tua')]) !!}'">
                        <div class="w-12 h-12 rounded-full bg-[#2196F3] flex items-center justify-center shadow-[0_4px_12px_rgba(33,150,243,0.3)]">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <span class="text-[12px] font-bold text-slate-600 text-center leading-tight">Riwayat<br>Pengukuran</span>
                    </div>
                    <!-- Edukasi Gizi -->
                    <div class="bg-white rounded-2xl p-4 flex flex-col items-center gap-2.5 shadow-[0_2px_12px_rgba(46,125,50,0.06)] border border-slate-100/70 cursor-pointer active:scale-95 transition-transform"
                         x-on:click="window.location.href='{!! \Illuminate\Support\Facades\URL::temporarySignedRoute('portal-ibu.nutrition', now()->addDays(config('portal.link_ttl_days')), ['balita' => request('balita'), 'orang_tua' => request('orang_tua')]) !!}'">
                        <div class="w-12 h-12 rounded-full bg-[#9C27B0] flex items-center justify-center shadow-[0_4px_12px_rgba(156,39,176,0.3)]">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                        </div>
                        <span class="text-[12px] font-bold text-slate-600 text-center leading-tight">Edukasi<br>Gizi</span>
                    </div>
                    <!-- Tanya Ahli -->
                    <div class="bg-white rounded-2xl p-4 flex flex-col items-center gap-2.5 shadow-[0_2px_12px_rgba(46,125,50,0.06)] border border-slate-100/70 cursor-pointer active:scale-95 transition-transform"
                         x-on:click="window.location.href='{!! \Illuminate\Support\Facades\URL::temporarySignedRoute('portal-ibu.posyandu', now()->addDays(config('portal.link_ttl_days')), ['balita' => request('balita'), 'orang_tua' => request('orang_tua')]) !!}'">
                        <div class="w-12 h-12 rounded-full bg-[#E91E63] flex items-center justify-center shadow-[0_4px_12px_rgba(233,30,99,0.3)]">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                        </div>
                        <span class="text-[12px] font-bold text-slate-600 text-center leading-tight">Tanya<br>Ahli</span>
                    </div>
                </div>
            </div>

            <!-- 5. JADWAL POSYANDU CARD -->
            <div class="mt-5 bg-white rounded-[24px] p-5 shadow-[0_4px_20px_rgba(46,125,50,0.06)] border border-[#C8E6C9]/40 relative overflow-hidden">
                <!-- Decorative building -->
                <div class="absolute right-[-8px] bottom-[-6px] w-[130px] h-[90px] pointer-events-none opacity-90">
                    <svg viewBox="0 0 120 100" class="w-full h-full">
                        <rect x="35" y="45" width="60" height="40" fill="#C8E6C9" rx="3"/>
                        <polygon points="20,45 65,28 110,45" fill="#4CAF50"/>
                        <rect x="55" y="60" width="20" height="25" fill="#F1F8F2"/>
                        <rect x="42" y="55" width="10" height="12" fill="#E8F5E9"/>
                        <rect x="78" y="55" width="10" height="12" fill="#E8F5E9"/>
                        <circle cx="18" cy="72" r="13" fill="#A5D6A7"/>
                        <rect x="15" y="78" width="6" height="14" fill="#8D6E63"/>
                    </svg>
                </div>

                <div class="relative z-10">
                    <div class="inline-flex items-center gap-1.5 bg-[#E8F5E9] text-[#2E7D32] px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest mb-3">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        {{ $posyandu['countdown'] ?? 'SESUAI JADWAL KADER' }}
                    </div>
                    <h3 class="text-[18px] font-black text-slate-800 mb-1 max-w-[60%] leading-tight">{{ $posyandu['name'] ?? 'Posyandu' }}</h3>
                    <div class="flex items-start gap-1.5 mb-4 text-slate-500 max-w-[62%]">
                        <svg class="w-4 h-4 shrink-0 mt-0.5 text-[#4CAF50]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        <p class="text-[12px] font-medium leading-snug">
                            <span class="font-bold text-slate-700">{{ $posyandu['schedule'] ?? 'Sesuai Jadwal' }}</span><br>
                            <span class="text-[11px] text-slate-500">{{ $posyandu['location'] ?? 'Balai Posyandu' }}</span>
                        </p>
                    </div>
                    <button class="inline-flex items-center justify-center gap-1.5 px-4 py-2.5 rounded-full border-2 border-[#4CAF50] text-[#2E7D32] bg-white font-extrabold text-[12.5px] focus:outline-none active:scale-95 transition-transform"
                            x-on:click="window.location.href='{!! \Illuminate\Support\Facades\URL::temporarySignedRoute('portal-ibu.posyandu', now()->addDays(config('portal.link_ttl_days')), ['balita' => request('balita'), 'orang_tua' => request('orang_tua')]) !!}'">
                        Lihat Jadwal Posyandu
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- BOTTOM NAVIGATION -->
    <x-navigation.bottom-navigation active="home" />
</x-layout.mobile-shell>
