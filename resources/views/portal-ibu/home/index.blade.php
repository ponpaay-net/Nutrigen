<x-layout.mobile-shell>
    <div class="flex-1 overflow-y-auto hide-scrollbar flex flex-col relative pb-28 pb-safe w-full bg-[#F7F5F0]">

        {{-- ===== HALAMAN RAPOR E-KIA — Portal Ibu ===== --}}

        {{-- HEADER IDENTITAS ANAK (seperti identitas di buku KIA) --}}
        <header class="px-6 pt-7 pb-4 flex items-center justify-between">
            <div class="flex items-center gap-3 min-w-0">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 flex items-center justify-center shadow-[0_8px_18px_-6px_rgba(16,185,129,0.5)] shrink-0">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 21V8l8 5 8-5v13"/>
                        <path d="M7 16v1M12 16v2M17 15v3"/>
                        <path d="M12 3h3l-1 2h1l-1.2 2.5"/>
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[10px] font-black uppercase tracking-[0.22em] text-slate-400 mb-0.5">NutriGen</p>
                    <p class="text-[12px] font-extrabold text-slate-700 leading-tight">Rapor Digital Tumbuh Kembang</p>
                </div>
            </div>
            <span class="shrink-0 inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[10px] font-black uppercase tracking-wider
                {{ ($pageState ?? 'normal') === 'merah' ? 'bg-rose-100 text-rose-700' : (($pageState ?? 'normal') === 'kuning' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                {{ $summary['status'] ?? 'Pertumbuhan' }}
            </span>
        </header>

        <div class="px-5 space-y-5 flex-1 flex flex-col">

            {{-- EMPTY / REDIRECT STATE --}}
            @if(empty($history))
                <div class="mt-16 flex flex-col items-center justify-center text-center px-6 py-14 bg-white rounded-[24px] border border-amber-100 shadow-[0_10px_30px_-14px_rgba(180,83,9,0.25)]">
                    <div class="w-16 h-16 rounded-2xl bg-amber-50 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-amber-500" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18l-1.5 9a1.5 1.5 0 01-1.5 1.3H6a1.5 1.5 0 01-1.5-1.3L3 10zM5 10a7 7 0 0114 0M9 13.5h2m2 0h2"/></svg>
                    </div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Belum ada data</p>
                    <h2 class="text-lg font-black text-slate-800 mb-1.5">Rapor Belum Tersedia</h2>
                    <p class="text-[13px] text-slate-500 leading-relaxed max-w-[280px]">Data pengukuran akan muncul setelah Kader mencatat dan Puskesmas memvalidasi pertumbuhan si Kecil.</p>
                </div>
            @else

                {{-- ===== HERO IDENTITAS & STATUS GIZI ===== --}}
                <section class="rounded-[22px] overflow-hidden relative bg-gradient-to-br {{ ($pageState ?? 'normal') === 'merah' ? 'from-rose-500 to-rose-600' : (($pageState ?? 'normal') === 'kuning' ? 'from-amber-400 to-amber-500' : 'from-emerald-500 to-teal-600') }} shadow-[0_18px_40px_-16px_rgba(16,185,129,0.6)]">
                    <!-- dekorasi -->
                    <div class="absolute -top-12 -right-12 w-44 h-44 rounded-full bg-white/10"></div>
                    <div class="absolute -bottom-14 -left-10 w-36 h-36 rounded-full bg-white/10"></div>

                    <div class="relative p-6">
                        <!-- Identitas Anak -->
                        <div class="flex items-center gap-4 mb-5">
                            <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md border border-white/25 flex items-center justify-center text-white text-2xl font-black shrink-0">
                                {{ collect(explode(' ', $user['child_name'] ?? 'A'))->map(fn($n) => substr($n,0,1))->take(2)->join('') }}
                            </div>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <span class="text-[10px] font-black uppercase tracking-widest text-white/80">{{ $measurement['gender'] ?? ($user['gender'] ?? '') }}</span>
                                    @if(!empty($measurement['age']))<span class="text-white/60">•</span><span class="text-[10px] font-black uppercase tracking-widest text-white/80">{{ $measurement['age'] }}</span>@endif
                                </div>
                                <h1 class="text-[22px] font-black text-white leading-tight tracking-tight drop-shadow-sm">{{ $user['child_name'] ?? 'Si Kecil' }}</h1>
                                <p class="text-[12px] font-medium text-white/85 mt-0.5 flex items-center gap-1.5">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/></svg>
                                    Lahir {{ $measurement['birth_date'] ?? '-' }}
                                </p>
                            </div>
                        </div>

                        <!-- Status Utama + Pesan Otomatis -->
                        <div class="rounded-2xl bg-white/15 backdrop-blur-md border border-white/25 p-4 mb-4">
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="text-[10px] font-black uppercase tracking-widest text-white/85">Status Gizi (Hasil Validasi)</span>
                            </div>
                            <h2 class="text-[24px] font-black text-white leading-tight tracking-tight drop-shadow-sm mb-1">
                                {{ $summary['title'] ?? 'Tumbuh Sesuai Standar' }}
                            </h2>
                            <p class="text-[13px] font-medium text-white/90 leading-relaxed">
                                {{ $summary['message'] ?? '' }}
                            </p>
                        </div>

                        @if(!empty($summary['action']))
                            <div class="rounded-2xl bg-white text-slate-800 p-4 mb-3 shadow-sm">
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Yang perlu dilakukan</p>
                                <p class="text-[13px] font-semibold leading-relaxed">{{ $summary['action'] }}</p>
                            </div>
                        @endif

                        @if(!empty($summary['catatan_validator']))
                            <div class="rounded-2xl bg-white/15 backdrop-blur-md border border-white/25 p-4">
                                <div class="flex items-center gap-1.5 mb-1">
                                    <svg class="w-3.5 h-3.5 text-white/85" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M12 2a10 10 0 100 20 10 10 0 000-20z"/></svg>
                                    <span class="text-[10px] font-black uppercase tracking-widest text-white/85">Catatan Petugas Gizi</span>
                                </div>
                                <p class="text-[13px] font-semibold text-white leading-snug">"{{ $summary['catatan_validator'] }}"</p>
                            </div>
                        @endif
                    </div>
                </section>

                {{-- ===== RINGKASAN TERKINI (BB/TB/LK terkini) ===== --}}
                <section class="bg-white rounded-[20px] p-5 border border-slate-100 shadow-[0_8px_28px_-12px_rgba(15,23,42,0.08)]">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-[15px] font-black text-slate-800">Pengukuran Terkini</h3>
                        <span class="text-[12px] font-bold text-slate-400">{{ $measurement['date'] ?? '-' }}</span>
                    </div>
                    <div class="grid grid-cols-3 gap-2">
                        <div class="rounded-2xl bg-[#F7F5F0] p-3 text-center">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-1">Berat</p>
                            <p class="text-[19px] font-black text-slate-900">{{ $measurement['weight'] ?? '-' }} <span class="text-[11px] font-semibold text-slate-400">kg</span></p>
                            @if(isset($delta['weight']) && $delta['weight'] !== 'Data Awal')<p class="text-[11px] font-bold text-emerald-600 mt-0.5">{{ str_contains($delta['weight'],'Turun')?'▼':'▲' }} {{ $delta['weight'] }}</p>@endif
                        </div>
                        <div class="rounded-2xl bg-[#F7F5F0] p-3 text-center">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-1">Tinggi</p>
                            <p class="text-[19px] font-black text-slate-900">{{ $measurement['height'] ?? '-' }} <span class="text-[11px] font-semibold text-slate-400">cm</span></p>
                            @if(isset($delta['height']) && $delta['height'] !== 'Data Awal')<p class="text-[11px] font-bold text-emerald-600 mt-0.5">{{ str_contains($delta['height'],'Turun')?'▼':'▲' }} {{ $delta['height'] }}</p>@endif
                        </div>
                        <div class="rounded-2xl bg-[#F7F5F0] p-3 text-center">
                            <p class="text-[10px] font-bold uppercase tracking-wide text-slate-400 mb-1">Lingkar Kptr</p>
                            <p class="text-[19px] font-black text-slate-900">{{ $measurement['head_circ'] ?? '-' }} <span class="text-[11px] font-semibold text-slate-400">cm</span></p>
                        </div>
                    </div>
                </section>

                {{-- ===== TAB NAVIGASI : RIWAYAT / KURVA / JADWAL ===== --}}
                <section x-data="{ tab: 'riwayat' }">
                    <div class="inline-flex w-full bg-slate-100 p-1 rounded-2xl mb-4">
                        <button @click="tab='riwayat'" class="flex-1 py-2.5 rounded-xl text-[12px] font-bold transition-all" :class="tab==='riwayat' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500'">Riwayat</button>
                        <button @click="tab='kurva'; renderCurveCharts()" class="flex-1 py-2.5 rounded-xl text-[12px] font-bold transition-all" :class="tab==='kurva' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500'">Kurva</button>
                        <button @click="tab='jadwal'" class="flex-1 py-2.5 rounded-xl text-[12px] font-bold transition-all" :class="tab==='jadwal' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500'">Jadwal</button>
                    </div>

                    {{-- TAB RIWAYAT --}}
                    <div x-show="tab==='riwayat'">
                        <div class="space-y-3">
                            @foreach($history as $idx => $h)
                                @php
                                    $gizi = strtolower((string) $h['status']);
                                    $keyTone = $gizi === 'stunting' ? 'border-rose-300 bg-rose-50' : ($gizi === 'risiko' || $gizi === 'kurang' ? 'border-amber-300 bg-amber-50' : 'border-emerald-200 bg-emerald-50');
                                @endphp
                                <article class="bg-white rounded-2xl p-4 border border-slate-100 shadow-[0_6px_20px_-10px_rgba(15,23,42,0.06)]">
                                    <div class="flex items-center justify-between mb-2.5">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0l3 3m-3-3l-3 3m9-9a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            <span class="text-[13px] font-bold text-slate-700">{{ $h['date'] }}</span>
                                        </div>
                                        <span class="px-2.5 py-1 rounded-full text-[10px] font-extrabold uppercase {{ $keyTone }}">
                                            {{ $h['status'] }}
                                        </span>
                                    </div>
                                    <div class="grid grid-cols-3 gap-2 mb-2">
                                        <div class="rounded-xl bg-slate-50 p-2 text-center"><p class="text-[10px] text-slate-400 font-bold">BB</p><p class="text-[14px] font-black text-slate-800">{{ $h['weight'] }} kg</p></div>
                                        <div class="rounded-xl bg-slate-50 p-2 text-center"><p class="text-[10px] text-slate-400 font-bold">TB</p><p class="text-[14px] font-black text-slate-800">{{ $h['height'] }} cm</p></div>
                                        <div class="rounded-xl bg-slate-50 p-2 text-center"><p class="text-[10px] text-slate-400 font-bold">LK</p><p class="text-[14px] font-black text-slate-800">{{ $h['head_circ'] ?? '-' }} cm</p></div>
                                    </div>
                                    <p class="text-[11px] text-slate-400 font-semibold">Usia saat ukur: {{ $h['age'] }}</p>
                                    @if(!empty($h['catatan_validator']))
                                        <p class="mt-2.5 pt-2.5 border-t border-slate-100 text-[12px] text-slate-500 leading-relaxed">
                                            <span class="font-bold text-slate-600">Catatan gizi:</span> {{ $h['catatan_validator'] }}
                                        </p>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    </div>

                    {{-- TAB KURVA --}}
                    <div x-show="tab==='kurva'" style="display:none;" x-data="{ metric: 'bb' }">
                        <div class="bg-white rounded-[20px] p-5 border border-slate-100 shadow-[0_8px_28px_-12px_rgba(15,23,42,0.08)]">
                            <div class="flex items-center justify-between mb-2">
                                <h3 class="text-[14px] font-black text-slate-800">Kurva Pertumbuhan WHO</h3>
                                <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400">NutriGen Growth</span>
                            </div>
                            <p class="text-[12px] text-slate-400 mb-3">Pertumbuhan si Kecil dari waktu ke waktu.</p>
                            <div class="inline-flex bg-slate-100 p-1 rounded-xl mb-2">
                                <button @click="metric='bb'; renderCurveCharts('bb')" class="px-4 py-1.5 rounded-lg text-[12px] font-bold transition-colors" :class="metric==='bb' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500'">Berat</button>
                                <button @click="metric='tb'; renderCurveCharts('tb')" class="px-4 py-1.5 rounded-lg text-[12px] font-bold transition-colors" :class="metric==='tb' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-500'">Tinggi</button>
                            </div>
                            <div class="h-64 w-full relative" x-ref="curveWrap">
                                <div id="chartBB" x-show="metric === 'bb'" class="absolute inset-0"></div>
                                <div id="chartTB" x-show="metric === 'tb'" class="absolute inset-0" style="display:none;"></div>
                            </div>
                        </div>
                    </div>

                    {{-- TAB JADWAL --}}
                    <div x-show="tab==='jadwal'" style="display:none;">
                        <div class="bg-white rounded-[20px] p-5 border border-slate-100 shadow-[0_8px_28px_-12px_rgba(15,23,42,0.08)]">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-2xl bg-emerald-50 flex items-center justify-center text-emerald-600">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657l-4.243 4.243a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3"/></svg>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400">Posyandu</p>
                                    <h3 class="text-[16px] font-black text-slate-800 leading-tight truncate">{{ $posyandu['name'] ?? 'Posyandu' }}</h3>
                                </div>
                                @if(($posyandu['countdown'] ?? '') === 'HARI INI')
                                    <span class="px-3 py-1 rounded-full bg-rose-100 text-rose-700 text-[11px] font-black">Hari Ini</span>
                                @else
                                    <span class="px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-[11px] font-black">{{ $posyandu['countdown'] ?? '' }}</span>
                                @endif
                            </div>
                            <div class="rounded-2xl bg-[#F7F5F0] p-4 mb-3">
                                <p class="text-[13px] font-semibold text-slate-700">{{ $posyandu['schedule'] ?? 'Sesuai Jadwal' }}</p>
                                <p class="text-[12px] text-slate-500 flex items-center gap-1.5 mt-1">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><circle cx="12" cy="11" r="3"/></svg>
                                    {{ $posyandu['location'] ?? 'Balai Posyandu' }}
                                </p>
                            </div>
                            @if(!empty($posyandu['notes']))
                                <div class="rounded-2xl bg-amber-50 border border-amber-100 p-4">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-amber-700 mb-1">Pengumuman Pendaftar</p>
                                    <p class="text-[13px] font-semibold text-amber-900 leading-relaxed">{{ $posyandu['notes'] }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>

            @endif
        </div>
    </div>

    <!-- BOTTOM NAVIGATION -->
    <x-navigation.bottom-navigation active="home" />

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        // Data chart disuntikkan dari controller (berbasis hasil validasi DB).
        var weightData = @json($chart['weight'] ?? []);
        var heightData = @json($chart['height'] ?? []);
        var curveCharts = {};

        var curveOptions = function(metric) {
            var isBB = metric === 'bb';
            return {
                chart: { type: 'area', height: 230, toolbar: {show:false}, zoom: {enabled:false}, fontFamily:'Nunito, sans-serif', parentHeightOffset:0 },
                series: [{ name: isBB ? 'Berat Badan (kg)' : 'Tinggi Badan (cm)', data: isBB ? weightData : heightData }],
                colors: [isBB ? '#10B981' : '#06B6D4'],
                dataLabels: { enabled: false },
                stroke: { curve: 'smooth', width: 3 },
                fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.03, stops: [0,100] } },
                grid: { borderColor: '#F1F5F9', strokeDashArray: 4, padding: { top:5, right:8, bottom:0, left:8 } },
                xaxis: { type: 'numeric', tickAmount: 6, labels: { formatter: v => v + ' bln', style: { colors: '#94A3B8', fontSize: '10px' } } },
                yaxis: { labels: { style: { colors: '#94A3B8', fontSize: '10px' } } },
                markers: { size: 5, colors: '#fff', strokeColors: isBB ? '#10B981' : '#06B6D4', strokeWidth: 2, hover: { size: 6 } },
                theme: { mode: 'light' }
            };
        };

        window.renderCurveCharts = function(metric) {
            metric = metric || 'bb';
            var elId = metric === 'bb' ? 'chartBB' : 'chartTB';
            var el = document.getElementById(elId);
            if (!el) return;

            // Bersihkan instance lama pada container yang sama
            if (curveCharts[elId]) { curveCharts[elId].destroy(); curveCharts[elId] = null; }
            el.innerHTML = '';

            var data = metric === 'bb' ? weightData : heightData;
            if (data.length === 0) {
                el.innerHTML = '<div class="h-full flex items-center justify-center text-sm text-slate-400 font-bold">Belum ada data pengukuran.</div>';
                return;
            }
            // Pastikan container terlihat (tab kurva aktif) untuk tinggi/width akurat.
            curveCharts[elId] = new ApexCharts(el, curveOptions(metric));
            curveCharts[elId].render();
        };

        // Tidak render otomatis saat DOMContentLoaded karena tab default adalah
        // "Riwayat". Chart dirender saat pengguna membuka tab Kurva.
    </script>
    @endpush
</x-layout.mobile-shell>