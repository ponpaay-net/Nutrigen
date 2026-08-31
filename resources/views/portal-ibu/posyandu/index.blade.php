<x-layout.mobile-shell>
    <div x-data="{ state: '{{ $pageState ?? 'normal' }}' }" class="flex-1 overflow-y-auto hide-scrollbar flex flex-col relative pb-[90px] w-full bg-[#F1F8F2]">

        <!-- HEADER (komponen bersama) -->
        <x-navigation.portal-header
            variant="page"
            title="Posyandu"
            :hasBack="false"
            :initials="$user['initials'] ?? 'A'"
            :avatar="$user['avatar'] ?? null"
        />

        <div class="px-5 pb-6 space-y-5 flex-1 flex flex-col pt-5">

            @if(isset($schedule))
            <!-- 1. HERO POSYANDU CARD -->
            <div class="bg-gradient-to-br from-white to-[#F1F8F2] rounded-[28px] p-6 shadow-[0_4px_24px_rgba(46,125,50,0.08)] border border-[#C8E6C9]/50 relative overflow-hidden">
                <!-- Decorative Building Placeholder -->
                <div class="absolute right-[-10px] top-4 w-40 h-28 pointer-events-none z-0 opacity-90">
                    <svg viewBox="0 0 100 100" class="w-full h-full">
                        <rect x="20" y="50" width="60" height="30" fill="#C8E6C9" rx="2"/>
                        <polygon points="10,50 50,25 90,50" fill="#4CAF50"/>
                        <rect x="30" y="60" width="12" height="12" fill="#FFFFFF"/>
                        <rect x="58" y="60" width="12" height="12" fill="#FFFFFF"/>
                        <rect x="44" y="60" width="12" height="20" fill="#2E7D32"/>
                        <circle cx="14" cy="70" r="10" fill="#A5D6A7"/>
                        <rect x="11" y="74" width="6" height="10" fill="#8D6E63"/>
                    </svg>
                </div>

                <div class="relative z-10">
                    <h2 class="text-[21px] font-black text-[#1B5E20] tracking-tight mb-2.5">{{ $schedule['posyanduName'] ?? 'Posyandu Mawar' }}</h2>
                    <div class="inline-flex items-center gap-1.5 bg-[#E8F5E9] text-[#2E7D32] px-3 py-1.5 rounded-full shadow-sm mb-6">
                        <div class="bg-[#4CAF50] rounded-full p-0.5 text-white">
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <span class="text-[11px] font-extrabold">{{ $schedule['countdown'] ?? 'Jadwal sudah tersedia' }}</span>
                    </div>

                    <div class="flex items-center w-full border-t border-[#C8E6C9]/70 pt-5 mb-5">
                        <!-- Date -->
                        <div class="flex items-start gap-2.5 flex-1 border-r border-[#C8E6C9]/70 pr-2">
                            <svg class="w-[18px] h-[18px] text-[#4CAF50] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <div class="flex flex-col">
                                <span class="text-[10px] text-[#2E7D32] font-bold mb-0.5">Hari/Tgl</span>
                                <span class="text-[12px] font-bold text-slate-900 leading-tight">{{ $schedule['date'] ?? '-' }}</span>
                            </div>
                        </div>

                        <!-- Time -->
                        <div class="flex items-start gap-2.5 flex-1 border-r border-[#C8E6C9]/70 px-3">
                            <svg class="w-[18px] h-[18px] text-[#4CAF50] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <div class="flex flex-col">
                                <span class="text-[10px] text-[#2E7D32] font-bold mb-0.5">Jam</span>
                                <span class="text-[12px] font-bold text-slate-900 leading-tight">{{ $schedule['time'] ?? 'Sesuai Jadwal' }}</span>
                            </div>
                        </div>

                        <!-- Location -->
                        <div class="flex items-start gap-2.5 flex-1 pl-3">
                            <svg class="w-[18px] h-[18px] text-[#4CAF50] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <div class="flex flex-col">
                                <span class="text-[10px] text-[#2E7D32] font-bold mb-0.5">Lokasi</span>
                                <span class="text-[12px] font-bold text-slate-900 leading-tight truncate w-14">{{ $schedule['address'] ?? '-' }}</span>
                            </div>
                        </div>
                    </div>

                    <button class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-full border-2 border-[#4CAF50] text-[#2E7D32] font-extrabold text-[12px] active:scale-95 transition-transform bg-white">
                        <svg class="w-3.5 h-3.5 text-[#4CAF50]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        Lihat Lokasi
                        <svg class="w-3.5 h-3.5 text-[#4CAF50]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </button>
                </div>
            </div>
            @endif

            @if(isset($kader))
            <!-- 2. KADER CARD -->
            @php
                $rawWa = isset($kader['whatsapp_url']) ? preg_replace('/[^0-9]/', '', $kader['whatsapp_url']) : '';
                $formattedWa = $rawWa;
                if (str_starts_with($rawWa, '62')) {
                    $formattedWa = '0' . substr($rawWa, 2);
                }
                if (strlen($formattedWa) >= 10) {
                    $formattedWa = substr($formattedWa, 0, 4) . ' ' . substr($formattedWa, 4, 4) . ' ' . substr($formattedWa, 8);
                }
            @endphp
            <div class="bg-white rounded-[28px] p-5 flex items-center justify-between shadow-[0_4px_24px_rgba(46,125,50,0.06)] border border-[#C8E6C9]/40">
                <div class="flex items-center gap-4">
                    <div class="w-[60px] h-[60px] rounded-full bg-[#C8E6C9] border-[3px] border-[#E8F5E9] flex-shrink-0 flex items-center justify-center relative">
                        @if(isset($kader['avatar']) && $kader['avatar'])
                            <img src="{{ $kader['avatar'] }}" alt="Kader" class="w-full h-full object-cover rounded-full">
                        @else
                            <span class="text-[#1B5E20] font-black text-[20px]">{{ strtoupper(substr($kader['name'] ?? 'K', 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="flex flex-col">
                        <span class="text-[11px] font-bold text-[#2E7D32] mb-1">Kader Posyandu</span>
                        <h3 class="text-[17px] font-black text-slate-900 tracking-tight leading-none mb-1.5">{{ $kader['name'] ?? 'Kader' }}</h3>
                        <div class="flex items-center gap-1.5 text-slate-500">
                            <svg class="w-[14px] h-[14px] text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            <span class="text-[12px] font-semibold">{{ $formattedWa ?: '-' }}</span>
                        </div>
                    </div>
                </div>

                <a href="{{ $kader['whatsapp_url'] ?? '#' }}" target="_blank" class="bg-[#25D366] active:bg-[#1DA851] text-white px-3 py-2.5 rounded-[14px] flex items-center gap-1.5 shadow-[0_8px_16px_rgba(37,211,102,0.3)] transition-colors self-center">
                    <svg class="w-[18px] h-[18px]" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    <span class="text-[11px] font-bold">Chat WhatsApp</span>
                </a>
            </div>
            @endif

            <!-- 3. PERSIAPAN SEBELUM DATANG CARD (desain foto 3) -->
            @php
                $defaultChecklist = [
                    ['task' => 'Bawa Buku KIA (KMS Balita)', 'checked' => true],
                    ['task' => 'Pastikan anak dalam kondisi sehat', 'checked' => false],
                    ['task' => 'Bawa fotokopi KK jika ada pembaruan data', 'checked' => false],
                ];
                $activeChecklist = !empty($checklist) ? $checklist : $defaultChecklist;
            @endphp
            <div class="bg-white rounded-[24px] p-6 shadow-[0_6px_28px_rgba(30,42,58,0.06)] border border-slate-100 relative overflow-hidden"
                 x-data="{ items: {{ json_encode($activeChecklist) }} }">

                <div class="flex items-center gap-3.5 mb-5 relative z-10">
                    <!-- Pale lavender rounded-square icon container + purple clipboard -->
                    <div class="w-[44px] h-[44px] rounded-[14px] bg-[#F2EEF5] text-[#7B61E1] flex items-center justify-center shrink-0">
                        <svg class="w-[22px] h-[22px]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                    </div>
                    <h2 class="text-[19px] font-black text-[#1E2A3A] tracking-tight leading-none">Persiapan Sebelum Datang</h2>
                </div>

                <div class="space-y-4 relative z-10 max-w-[75%]">
                    <template x-for="(item, index) in items" :key="index">
                        <div class="flex items-start gap-3 cursor-pointer group" @click="item.checked = !item.checked">
                            <!-- Checkbox -->
                            <div class="w-[22px] h-[22px] rounded-[7px] border-2 flex items-center justify-center transition-colors shrink-0 mt-0.5"
                                 :class="item.checked ? 'bg-[#2EB67D] border-[#2EB67D]' : 'border-[#D0D3DE] group-hover:border-[#2EB67D] bg-white'">
                                <svg x-show="item.checked" class="w-3.5 h-3.5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <!-- Text -->
                            <p class="text-[14px] font-medium leading-snug transition-colors"
                               :class="item.checked ? 'text-[#9A9EB8] line-through' : 'text-[#1E2A3A]'">
                                <span x-text="item.task"></span>
                            </p>
                        </div>
                    </template>
                </div>

                <!-- Decorative Illustration (shopping bag + bottle, flat style) -->
                <div class="absolute right-[6px] bottom-[18px] w-[120px] h-[130px] pointer-events-none opacity-95 z-0">
                    <svg viewBox="0 0 100 110" class="w-full h-full">
                        <!-- Sparkle -->
                        <path d="M82 12 L84 18 L90 20 L84 22 L82 28 L80 22 L74 20 L80 18 Z" fill="#C5F1DE"/>
                        <!-- Accent block -->
                        <rect x="66" y="72" width="22" height="14" rx="5" fill="#F5D06A" transform="rotate(12 77 79)"/>
                        <rect x="10" y="86" width="26" height="12" rx="5" fill="#C5D8FF" transform="rotate(-8 23 92)"/>
                        <!-- Shopping bag -->
                        <path d="M28 45 Q28 40 33 40 L61 40 Q66 40 66 45 L68 82 Q68 88 62 88 L32 88 Q26 88 26 82 Z" fill="#C5F1DE"/>
                        <path d="M38 40 Q38 30 47 30 Q56 30 56 40" fill="none" stroke="#2EB67D" stroke-width="4" stroke-linecap="round"/>
                        <path d="M41 58 A 4 4 0 0 0 37 55 A 4 4 0 0 0 33 58 Q 33 64 37 67 Q 41 64 41 58 Z" fill="#87E6B7"/>
                        <path d="M53 58 A 4 4 0 0 0 49 55 A 4 4 0 0 0 45 58 Q 45 64 49 67 Q 53 64 53 58 Z" fill="#87E6B7"/>
                        <!-- Bottle -->
                        <rect x="76" y="38" width="14" height="42" rx="6" fill="#90A6F8"/>
                        <rect x="79" y="30" width="8" height="8" rx="2" fill="#7060D3"/>
                        <rect x="76" y="50" width="14" height="8" fill="#C5D8FF"/>
                    </svg>
                </div>
            </div>

            <!-- 4. PENGUMUMAN KADER -->
            @if(isset($announcement))
            <div class="bg-[#FFF8E1] rounded-[24px] p-5 border border-[#FFE082]/60 relative overflow-hidden">
                <div class="flex items-start gap-3.5">
                    <div class="w-10 h-10 rounded-full bg-[#FFC107] flex items-center justify-center shrink-0 shadow-[0_4px_12px_rgba(255,193,7,0.3)]">
                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    </div>
                    <div class="flex-1">
                        <span class="inline-block bg-white/80 text-[#B8860B] px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest mb-1.5">{{ $announcement['badge'] ?? 'PENGUMUMAN POSYANDU' }}</span>
                        <h4 class="text-[15px] font-black text-[#3E2723] mb-1">{{ $announcement['title'] ?? '' }}</h4>
                        <p class="text-[12.5px] font-medium text-[#8D6E63] leading-relaxed">{{ $announcement['message'] ?? '' }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- 5. INFORMASI POSYANDU CARD -->
            <div class="bg-[#F1F8F2] rounded-[24px] p-6 shadow-[0_4px_24px_rgba(46,125,50,0.05)] border border-[#C8E6C9]/50">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-[40px] h-[40px] rounded-full bg-[#E8F5E9] text-[#2E7D32] flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h2 class="text-[18px] font-black text-[#1B5E20] tracking-tight">Informasi Posyandu</h2>
                </div>

                <div class="space-y-5">
                    <!-- Alamat -->
                    <div class="flex items-start gap-4">
                        <div class="w-5 h-5 text-[#4CAF50] shrink-0 mt-0.5">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                        </div>
                        <div class="flex-1 border-b border-[#C8E6C9]/60 pb-5">
                            <h4 class="text-[13px] font-bold tracking-tight text-slate-900 mb-1">Alamat</h4>
                            <p class="text-[13px] font-medium text-slate-500 leading-relaxed pr-2">{{ $schedule['address'] ?? '-' }}</p>
                        </div>
                    </div>

                    <!-- Jam Buka -->
                    <div class="flex items-start gap-4">
                        <div class="w-5 h-5 text-[#4CAF50] shrink-0 mt-0.5">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div class="flex-1 border-b border-[#C8E6C9]/60 pb-5">
                            <h4 class="text-[13px] font-bold tracking-tight text-slate-900 mb-1">Jam Buka</h4>
                            <p class="text-[13px] font-medium text-slate-500 leading-relaxed">{{ $schedule['time'] ?? '08.00 - 12.00 WIB' }}</p>
                        </div>
                    </div>

                    <!-- Catatan Kader -->
                    <div class="flex items-start gap-4">
                        <div class="w-5 h-5 text-[#4CAF50] shrink-0 mt-0.5">
                            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-[13px] font-bold tracking-tight text-slate-900 mb-1">Catatan Kader</h4>
                            <p class="text-[13px] font-medium text-slate-500 leading-relaxed pr-2">
                                {{ $announcement['message'] ?? 'Datang lebih awal untuk menghindari antrean, ya Bu 😊' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- BOTTOM NAVIGATION -->
    <x-navigation.bottom-navigation active="posyandu" />
</x-layout.mobile-shell>
