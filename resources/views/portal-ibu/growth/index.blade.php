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
                                // Gaya resmi WHO: 7 kurva z-score di latar putih.
                                // Sumbu Y = kg bulat (skala tetap 2-25 kg, khas
                                // lembar WHO WFA), X = umur (tahun, grid 6 bln).
                                $kgMin = 2; $kgMax = 25;
                                $ages   = array_column($chartPoints, 0);
                                $minAge = min($ages);
                                $maxAge = max($ages);
                                $W = 100; $H = 66;
                                $sx = function($age) use ($W) {
                                    return round($age / 60 * $W, 2); // 0-60 bln -> 0-100
                                };
                                $sy = function($bb) use ($kgMin, $kgMax, $H) {
                                    return round($H - ($bb - $kgMin) / ($kgMax - $kgMin) * $H, 2);
                                };
                                $pathOf = function(array $series) use ($sx, $sy) {
                                    return collect($series)->map(fn($p, $i) => ($i === 0 ? 'M' : 'L') . $sx($p[0]) . ',' . $sy($p[1]))->implode(' ');
                                };
                                $ptCoords = [];
                                foreach ($chartPoints as $pt) {
                                    $ptCoords[] = ['x' => $sx($pt[0]), 'y' => $sy($pt[1]), 'age' => $pt[0], 'bb' => $pt[1]];
                                }
                                $lastPt = $ptCoords[count($ptCoords) - 1];
                                $linePath = $pathOf($chartPoints);
                                // Kurva WHO 7 seri (jika tersedia)
                                $hasWho = !empty($whoCurve) && isset($whoCurve['neg3'], $whoCurve['med'], $whoCurve['pos3']);
                                $zSeries = $hasWho ? ['neg3','neg2','neg1','med','pos1','pos2','pos3'] : [];
                                $zLabels = ['neg3'=>'-3','neg2'=>'-2','neg1'=>'-1','med'=>'0','pos1'=>'+1','pos2'=>'+2','pos3'=>'+3'];
                            @endphp
                            <div class="relative w-full bg-white border border-slate-200 rounded-lg overflow-hidden" style="aspect-ratio: 10/6.6;">
                                <svg class="w-full h-full" viewBox="0 0 100 66" preserveAspectRatio="none">
                                    <!-- Grid vertikal: 6 bulan tipis, 12 bulan tebal -->
                                    @for($m = 6; $m <= 54; $m += 6)
                                        <line x1="{{ $sx($m) }}" y1="0" x2="{{ $sx($m) }}" y2="66" stroke="{{ $m % 12 === 0 ? '#d4d4d8' : '#f4f4f5' }}" stroke-width="{{ $m % 12 === 0 ? 0.35 : 0.2 }}" />
                                    @endfor
                                    <!-- Grid horizontal: kg tiap 1 -->
                                    @for($kg = $kgMin + 1; $kg < $kgMax; $kg++)
                                        <line x1="0" y1="{{ $sy($kg) }}" x2="100" y2="{{ $sy($kg) }}" stroke="#f4f4f5" stroke-width="0.2" />
                                    @endfor
                                    <!-- Border plot -->
                                    <rect x="0" y="0" width="100" height="66" fill="none" stroke="#d4d4d8" stroke-width="0.35" />
                                    <!-- 7 kurva referensi WHO (abu, median lebih tebal) -->
                                    @foreach($zSeries as $zKey)
                                        <path d="{{ $pathOf($whoCurve[$zKey]) }}" fill="none" stroke="#52525b" stroke-width="{{ $zKey === 'med' ? 0.55 : 0.35 }}" stroke-linejoin="round" />
                                    @endforeach
                                    <!-- Garis pengukuran anak (biru, di atas referensi) -->
                                    <path d="{{ $linePath }}" fill="none" stroke="#0ea5e9" stroke-width="1" stroke-linejoin="round" stroke-linecap="round" />
                                    @foreach($ptCoords as $p)
                                        <circle cx="{{ $p['x'] }}" cy="{{ $p['y'] }}" r="1.3" fill="#0ea5e9" stroke="#fff" stroke-width="0.5" />
                                    @endforeach
                                </svg>
                                <!-- Label z-score di ujung kanan tiap kurva -->
                                @if($hasWho)
                                    <div class="absolute top-0 bottom-0 right-0 w-8 pointer-events-none" aria-hidden="true">
                                        @foreach($zSeries as $zKey)
                                            @php $rowEnd = end($whoCurve[$zKey]); @endphp
                                            <span class="absolute text-[8px] font-semibold text-zinc-600 leading-none" style="right: 2px; top: {{ $sy($rowEnd[1]) / 66 * 100 }}%; transform: translateY(-50%);">{{ $zLabels[$zKey] }}</span>
                                        @endforeach
                                    </div>
                                @endif
                                <!-- Sumbu: tahun (X) & kg (Y) -->
                                <div class="absolute bottom-0 left-0 right-6 h-4 flex pointer-events-none" aria-hidden="true">
                                    @for($t = 0; $t <= 5; $t++)
                                        <span class="absolute text-[8px] font-semibold text-zinc-500" style="left: {{ $sx($t * 12) / 100 * 100 }}%; transform: translateX(-50%);">{{ $t }}</span>
                                    @endfor
                                </div>
                                <div class="absolute bottom-3.5 right-2 text-[8px] font-bold text-zinc-500">Usia (tahun)</div>
                                <div class="absolute left-1 top-0 bottom-4 flex flex-col justify-between py-0.5 pointer-events-none" aria-hidden="true">
                                    <span class="text-[7.5px] font-semibold text-zinc-400">25</span>
                                    <span class="text-[7.5px] font-semibold text-zinc-400">2</span>
                                </div>
                                <div class="absolute -top-0.5 left-1 text-[8px] font-bold text-zinc-500">kg</div>
                            </div>
                            <div class="mt-2 flex items-center justify-between text-[10px] font-bold px-1">
                                <span class="text-zinc-400">Standar: WHO Child Growth Standards 2006 — Weight-for-Age (z-scores)</span>
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
