<x-layout.mobile-shell>
    <div x-data="{ state: '{{ $pageState ?? 'normal' }}' }" class="flex-1 overflow-y-auto hide-scrollbar flex flex-col relative pb-[100px] pb-safe w-full">
        
        <!-- TOP BAR (komponen bersama) -->
        <x-navigation.portal-header
            variant="page"
            title="Pertumbuhan Anak"
            :hasBack="true"
            :backUrl="\Illuminate\Support\Facades\URL::temporarySignedRoute('portal-ibu.home', now()->addDays(config('portal.link_ttl_days')), ['balita' => request('balita'), 'orang_tua' => request('orang_tua')])"
            :initials="$initials ?? 'A'"
            :avatar="$avatar ?? null"
        />

        <div class="px-5 pt-6 pb-6 space-y-6 flex-1 flex flex-col">
            <!-- LOADING OVERLAY -->
            <template x-if="state === 'loading'">
                <x-feedback.loading-state class="top-20" />
            </template>

            <!-- ERROR STATE -->
            <template x-if="state === 'error'">
                <div class="flex-1 flex items-center justify-center -mt-20">
                    <x-feedback.error-state />
                </div>
            </template>

            <!-- EMPTY STATE -->
            <template x-if="state === 'empty'">
                <div class="flex-1 flex flex-col items-center justify-center -mt-10">
                    <x-feedback.empty-state 
                        title="Belum Ada Grafik" 
                        message="Grafik pertumbuhan akan muncul setelah ada minimal dua pencatatan." 
                        actionText="Lihat Jadwal Posyandu">
                        <x-slot name="icon">
                            <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        </x-slot>
                    </x-feedback.empty-state>
                </div>
            </template>

            <!-- NORMAL STATE -->
            <div x-show="state === 'normal'" style="display: none;" class="space-y-6" x-transition>
                
                <!-- 1. GROWTH STORY -->
                <x-ui.section-title title="Rapor {{ $childName ?? 'Si Kecil' }}" subtitle="Kesimpulan pengukuran bulan ini." />
                
                <div class="rounded-[32px] p-7 text-center shadow-[0_16px_40px_-12px_rgba(5,150,105,0.4)] transition-all duration-500 relative overflow-hidden flex flex-col items-center"
                     :class="{
                        'bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-[0_16px_40px_-12px_rgba(5,150,105,0.4)]': '{{ $story['state'] ?? 'normal' }}' === 'normal',
                        'bg-gradient-to-br from-amber-400 to-amber-500 shadow-[0_16px_40px_-12px_rgba(245,158,11,0.4)]': '{{ $story['state'] ?? 'normal' }}' === 'kuning',
                        'bg-gradient-to-br from-rose-500 to-rose-600 shadow-[0_16px_40px_-12px_rgba(225,29,72,0.4)]': '{{ $story['state'] ?? 'normal' }}' === 'merah',
                        'bg-gradient-to-br from-slate-500 to-slate-600 shadow-[0_16px_40px_-12px_rgba(100,116,139,0.4)]': !['normal', 'kuning', 'merah'].includes('{{ $story['state'] ?? 'normal' }}')
                     }">

                    <div class="mt-2 mb-4">
                        <span class="px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-widest bg-white/20 text-white backdrop-blur-md">
                            {{ $story['status'] ?? 'Belum Ada Data' }}
                        </span>
                    </div>

                    <h2 class="text-[20px] font-black text-white leading-tight tracking-tight mb-2.5 z-10 drop-shadow-sm px-4">
                        {{ $story['title'] ?? 'Tumbuh Kejar Ideal' }}
                    </h2>
                    
                    <p class="text-[14px] font-medium text-white/90 max-w-[290px] mx-auto mb-6 leading-relaxed z-10 break-words drop-shadow-sm">
                        {{ $story['message'] ?? 'Grafik pertumbuhan si Kecil terus menunjukkan tren positif bulan ini. Lanjutkan pola makan seimbangnya ya, Bu.' }}
                    </p>
                </div>

                <!-- 2. COMPARISON CARD -->
                @if(isset($comparison))
                    <x-domain.comparison-card 
                        message="{{ $comparison['message'] ?? 'Anak Ibu lebih tinggi dari 80% anak seusianya!' }}"
                        icon="{{ $comparison['icon'] ?? '🏆' }}" 
                    />
                @endif

                <!-- 3. WHO GROWTH CURVE (SVG, gaya puskesmas — zona SD + titik pengukuran asli) -->
                <div class="mt-8">
                    <x-ui.section-title title="Kurva Pertumbuhan WHO" subtitle="Jejak pertumbuhan si Kecil dari waktu ke waktu." />
                    <x-ui.card padding="p-4" class="bg-white border border-slate-100 shadow-sm mt-3 relative overflow-hidden">
                        @if(count($chartPoints ?? []) >= 2)
                            @php
                                // Skala grafik: X = umur (bulan), Y = berat (kg).
                                // Rentang Y mencakup kurva WHO + data anak, agar
                                // kurva referensi tidak terpotong di tepi.
                                $ages   = array_column($chartPoints, 0);
                                $bbs    = array_column($chartPoints, 1);
                                $allBbs = $bbs;
                                if (!empty($whoCurve)) {
                                    foreach (['neg2','median','pos2'] as $k) {
                                        $allBbs = array_merge($allBbs, array_column($whoCurve[$k], 1));
                                    }
                                }
                                $minAge = min($ages);
                                $maxAge = max($ages);
                                $minBb  = min($allBbs);
                                $maxBb  = max($allBbs);
                                // Padding sumbu: 1 bulan kiri/kanan, 0.5 kg bawah/atas
                                $x0 = $minAge - 1;
                                $x1 = max($maxAge + 1, $minAge + 2);
                                $y0 = max(0, $minBb - 0.5);
                                $y1 = $maxBb + 0.5;
                                $W = 100; $H = 60; // viewBox
                                $sx = function($age) use ($x0, $x1, $W) {
                                    return round(($age - $x0) / ($x1 - $x0) * $W, 2);
                                };
                                $sy = function($bb) use ($y0, $y1, $H) {
                                    return round($H - ($bb - $y0) / ($y1 - $y0) * $H, 2);
                                };
                                $pathOf = function(array $series) use ($sx, $sy) {
                                    return collect($series)->map(fn($p, $i) => ($i === 0 ? 'M' : 'L') . $sx($p[0]) . ',' . $sy($p[1]))->implode(' ');
                                };
                                $ptCoords = [];
                                foreach ($chartPoints as $pt) {
                                    $ptCoords[] = ['x' => $sx($pt[0]), 'y' => $sy($pt[1]), 'age' => $pt[0], 'bb' => $pt[1]];
                                }
                                $firstPt = $ptCoords[0];
                                $lastPt  = $ptCoords[count($ptCoords) - 1];
                                $linePath = $pathOf($chartPoints);
                                $areaPath = $linePath . ' L' . $lastPt['x'] . ',' . $H . ' L' . $firstPt['x'] . ',' . $H . ' Z';
                                // Kurva WHO (jika tersedia)
                                $hasWho = !empty($whoCurve) && isset($whoCurve['neg2'], $whoCurve['median'], $whoCurve['pos2']);
                            @endphp
                            <div class="relative w-full bg-slate-50 border border-slate-100 rounded-lg overflow-hidden" style="aspect-ratio: 5/3;">
                                <svg class="w-full h-full" viewBox="0 0 100 60" preserveAspectRatio="none">
                                    <!-- Zona SD (gaya KIA) -->
                                    <rect x="0" y="0" width="100" height="20" fill="#f0fdf4" />
                                    <rect x="0" y="20" width="100" height="20" fill="#fffbeb" />
                                    <rect x="0" y="40" width="100" height="20" fill="#fff1f2" />
                                    <!-- Grid horizontal -->
                                    <line x1="0" y1="20" x2="100" y2="20" stroke="#e2e8f0" stroke-width="0.4" stroke-dasharray="2 2" />
                                    <line x1="0" y1="40" x2="100" y2="40" stroke="#e2e8f0" stroke-width="0.4" stroke-dasharray="2 2" />
                                    @if($hasWho)
                                        <!-- Kurva referensi WHO BB/U (standar WHO 2006) -->
                                        <path d="{{ $pathOf($whoCurve['pos2']) }}" fill="none" stroke="#f59e0b" stroke-width="0.9" stroke-dasharray="3 2" stroke-linecap="round" />
                                        <path d="{{ $pathOf($whoCurve['median']) }}" fill="none" stroke="#10b981" stroke-width="1" stroke-dasharray="4 2.5" stroke-linecap="round" />
                                        <path d="{{ $pathOf($whoCurve['neg2']) }}" fill="none" stroke="#ef4444" stroke-width="0.9" stroke-dasharray="3 2" stroke-linecap="round" />
                                    @endif
                                    <!-- Area + garis pengukuran anak -->
                                    <path d="{{ $areaPath }}" fill="rgba(14,165,233,0.12)" />
                                    <path d="{{ $linePath }}" fill="none" stroke="#0ea5e9" stroke-width="1.4" stroke-linejoin="round" stroke-linecap="round" />
                                    @foreach($ptCoords as $p)
                                        <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="1.6" fill="#0ea5e9" stroke="#fff" stroke-width="0.6" />
                                    @endforeach
                                </svg>
                                <!-- Label SD kiri (overlay) -->
                                <div class="absolute left-1.5 top-0 bottom-0 flex flex-col text-[9px] font-bold tracking-wider text-slate-400 pointer-events-none" aria-hidden="true">
                                    <span class="flex-1 flex items-center">+2 SD</span>
                                    <span class="flex-1 flex items-center">0 SD</span>
                                    <span class="flex-1 flex items-center">-2 SD</span>
                                </div>
                                <!-- Legend -->
                                @if($hasWho)
                                    <div class="absolute top-1.5 left-7 flex items-center gap-2.5 text-[8.5px] font-bold text-slate-500 bg-white/70 backdrop-blur-sm px-1.5 py-0.5 rounded pointer-events-none">
                                        <span class="inline-flex items-center gap-0.5"><span class="w-3 h-0.5 rounded bg-[#10b981]"></span>Median WHO</span>
                                        <span class="inline-flex items-center gap-0.5"><span class="w-3 border-t border-dashed border-[#f59e0b]"></span>+2SD</span>
                                        <span class="inline-flex items-center gap-0.5"><span class="w-3 border-t border-dashed border-[#ef4444]"></span>−2SD</span>
                                    </div>
                                @endif
                                <!-- Label sumbu -->
                                <div class="absolute bottom-1 right-2 text-[9px] font-bold text-slate-400">Umur (bulan)</div>
                                <div class="absolute top-2 right-2 text-[9px] font-bold text-slate-400">Berat (kg)</div>
                            </div>
                            <div class="mt-2 flex items-center justify-between text-[10px] font-bold text-slate-400 px-1">
                                <span>{{ $firstPt['age'] }} bln ({{ $firstPt['bb'] }} kg)</span>
                                <span class="text-sky-600">{{ $lastPt['age'] }} bln ({{ $lastPt['bb'] }} kg)</span>
                            </div>
                        @else
                            <div class="h-48 flex flex-col items-center justify-center text-center gap-1.5">
                                <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                                <p class="text-[12.5px] font-bold text-slate-400">Belum ada cukup data pengukuran.</p>
                                <p class="text-[11px] font-medium text-slate-400">Kurva muncul setelah ada minimal dua pengukuran tervalidasi.</p>
                            </div>
                        @endif
                    </x-ui.card>
                </div>

                <!-- 4. TIMELINE MILESTONES -->
                <div class="mt-8">
                    <x-ui.section-title title="Catatan Posyandu" subtitle="Riwayat pengukuran." />
                    
                    <x-ui.card padding="p-5" class="mt-3" id="riwayat">
                        @forelse($timeline ?? [] as $index => $item)
                            <x-domain.growth-timeline-item 
                                date="{{ $item['date'] }}"
                                age="{{ $item['age'] }}"
                                weight="{{ $item['weight'] }}"
                                height="{{ $item['height'] }}"
                                status="{{ $item['status'] ?? 'normal' }}"
                                :isLast="$loop->last"
                            />
                        @empty
                            <!-- Dummy data fallback for local dev -->
                            <x-domain.growth-timeline-item 
                                date="Senin, 12 Ags 2026" age="2 Tahun 4 Bulan" weight="10.2" height="85" status="normal"
                            />
                            <x-domain.growth-timeline-item 
                                date="Senin, 12 Jul 2026" age="2 Tahun 3 Bulan" weight="9.9" height="84" status="normal" :isLast="true"
                            />
                        @endforelse
                    </x-ui.card>
                </div>
                
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
</x-layout.mobile-shell>
