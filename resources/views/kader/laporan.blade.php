@extends('layouts.app')

@section('page-title', 'Pusat Laporan Posyandu')

@section('content')
<div class="bg-slate-50 min-h-full">
<div class="max-w-6xl mx-auto w-full px-4 sm:px-6 pt-5 sm:pt-8 pb-28 sm:pb-12">

    {{-- Header + filter periode --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Pusat Laporan Posyandu</h1>
            <p class="text-[13px] text-slate-500 mt-1 flex items-center gap-1.5"><x-icon name="map-pin" weight="bold" class="text-[14px] text-teal-600" /> {{ $posyanduAktif ?? 'Posyandu Kader' }} · Tinjau metrik penimbangan bulanan & ekspor laporan resmi.</p>
        </div>
        <form action="{{ route('laporan.index') }}" method="GET" class="relative w-full sm:w-auto">
            <style>
                .periode-picker-overlay::-webkit-calendar-picker-indicator { position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
                .periode-picker-overlay:hover { cursor: pointer; }
            </style>
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Periode</label>
            <div class="inline-flex items-center gap-2 h-11 px-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <x-icon name="calendar-blank" weight="bold" class="text-[15px] text-slate-400" />
                <span class="text-[14px] font-semibold text-slate-800">{{ $periode ?? '' }}</span>
                <x-icon name="caret-down" weight="bold" class="text-[13px] text-slate-400" />
            </div>
            <input type="month" name="periode" value="{{ $periodeValue }}" onchange="this.form.submit()" class="periode-picker-overlay absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" title="Ubah Periode" aria-label="Ubah Periode">
        </form>
    </div>

    @if(isset($dataKosong) && $dataKosong)
        {{-- Empty state --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 sm:p-12 text-center flex flex-col items-center">
            <span class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mb-3"><x-icon name="chart-bar" weight="fill" class="text-[26px]" /></span>
            <h2 class="text-base font-bold text-slate-900">Belum Ada Data Penimbangan</h2>
            <p class="text-[13px] text-slate-500 mt-1.5 max-w-sm leading-relaxed">Tidak ada riwayat pengukuran balita yang tercatat pada periode ini. Silakan input penimbangan terlebih dahulu.</p>
            <a href="{{ route('balita.index') }}" class="mt-5 inline-flex items-center gap-2 h-11 px-5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-[14px] font-semibold shadow-md shadow-teal-600/15 transition-colors"><x-icon name="plus" weight="bold" class="text-[16px]" /> Input Pengukuran Balita</a>
        </div>
    @else

        {{-- Rekap + KPI --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-5 mb-8">
            {{-- Rekapitulasi (teal gradient) --}}
            <div class="md:col-span-1 bg-white border border-slate-200 rounded-2xl p-6 flex flex-col shadow-sm">
                <div>
                    <p class="text-[10.5px] font-bold uppercase tracking-widest text-slate-400">Rekapitulasi Penimbangan</p>
                    <p class="text-[13px] text-slate-800 font-semibold mt-0.5">{{ $periode ?? '' }}</p>
                </div>
                <div class="flex justify-center my-4">
                    <div id="rekap-gauge" data-pct="{{ round($persentase ?? 0) }}" class="w-44 h-44"></div>
                </div>
                <p class="text-center text-[12.5px] text-slate-500 font-medium">{{ $sudahDiukur ?? 0 }}/{{ $totalBalita ?? 0 }} balita terukur · <span class="font-semibold text-teal-600">Sesuai target</span></p>
            </div>

            {{-- 4 KPI --}}
            <div class="md:col-span-2 grid grid-cols-2 gap-4">
                @php
                    $kpis = [
                        ['label' => 'Terukur', 'count' => $sudahDiukur ?? 0, 'icon' => 'check-circle', 'tone' => 'teal', 'note' => 'balita diukur', 'spark' => 'count'],
                        ['label' => 'Belum Hadir', 'count' => $belumDiukur ?? 0, 'icon' => 'user-minus', 'tone' => 'amber', 'note' => 'belum timbang', 'spark' => 'belum'],
                        ['label' => 'Pantauan Gizi', 'count' => $perluPerhatian ?? 0, 'icon' => 'heart', 'tone' => 'slate', 'note' => 'perlu perhatian', 'spark' => 'pantauan'],
                        ['label' => 'Perlu Konfirmasi', 'count' => $berisiko ?? 0, 'icon' => 'warning', 'tone' => 'rose', 'note' => 'perlu validasi', 'spark' => 'konfirmasi'],
                    ];
                    $toneStyles = [
                        'teal' => ['icon' => 'bg-teal-50 text-teal-600', 'bar' => 'from-teal-500 to-teal-600', 'txt' => 'text-teal-600', 'spark' => '#0d9488'],
                        'amber' => ['icon' => 'bg-amber-50 text-amber-600', 'bar' => 'from-amber-400 to-amber-500', 'txt' => 'text-amber-600', 'spark' => '#f59e0b'],
                        'slate' => ['icon' => 'bg-slate-100 text-slate-500', 'bar' => 'from-slate-300 to-slate-400', 'txt' => 'text-slate-500', 'spark' => '#94a3b8'],
                        'rose' => ['icon' => 'bg-rose-50 text-rose-600', 'bar' => 'from-rose-400 to-rose-500', 'txt' => 'text-rose-600', 'spark' => '#e11d48'],
                    ];
                @endphp
                @foreach($kpis as $k)
                    @php $ts = $toneStyles[$k['tone']]; @endphp
                    <div class="relative overflow-hidden bg-white border border-slate-200 rounded-2xl p-4 sm:p-5 shadow-sm hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200 flex flex-col">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r {{ $ts['bar'] }}"></div>
                        <div class="flex items-center justify-between">
                            <span class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl {{ $ts['icon'] }} flex items-center justify-center shadow-sm"><x-icon name="{{ $k['icon'] }}" weight="fill" class="text-[17px] sm:text-[19px]" /></span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $k['label'] }}</span>
                        </div>
                        <div class="mt-3 flex items-end justify-between gap-2">
                            <div class="min-w-0">
                                <span class="text-[30px] sm:text-[34px] font-black text-slate-900 leading-none tracking-tight">{{ $k['count'] }}</span>
                                <p class="text-[11.5px] font-medium {{ $ts['txt'] }} mt-1">{{ $k['note'] }}</p>
                            </div>
                            <div id="kpi-spark-{{ $loop->index }}" data-spark="{{ $k['spark'] }}" data-color="{{ $ts['spark'] }}" class="kpi-spark shrink-0 w-[72px] h-9"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Ekspor & Pelaporan --}}
        <section class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <span class="w-1 h-6 bg-teal-600 rounded-full"></span>
                <h2 class="text-base font-bold text-slate-900">Ekspor & Pelaporan</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-5">
                {{-- PDF --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6 flex flex-col shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h3 class="text-[15px] font-bold text-slate-900 leading-snug">Laporan Resmi Posyandu (PDF)</h3>
                            <p class="text-[12.5px] text-slate-500 mt-1.5 leading-relaxed">Dokumen lengkap siap cetak untuk diserahkan ke Puskesmas dan Kelurahan.</p>
                            <div class="flex flex-wrap gap-1.5 mt-3">
                                <span class="text-[10.5px] font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-md">Kop Surat</span>
                                <span class="text-[10.5px] font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-md">Tanda Tangan</span>
                                <span class="text-[10.5px] font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-md">A4 Landscape</span>
                            </div>
                        </div>
                        <span class="w-11 h-11 shrink-0 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center"><x-icon name="file-pdf" weight="fill" class="text-[20px]" /></span>
                    </div>
                    <form action="{{ route('laporan.generate') }}" method="POST" class="mt-5">
                        @csrf
                        <input type="hidden" name="posyandu_id" value="{{ request('posyandu_id') }}">
                        <input type="hidden" name="periode" value="{{ $periodeValue }}">
                        <button type="submit" class="w-full h-11 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-[14px] font-semibold inline-flex items-center justify-center gap-2 shadow-md shadow-teal-600/15 transition-colors"><x-icon name="printer" weight="bold" class="text-[16px]" /> Cetak PDF Resmi</button>
                    </form>
                </div>
                {{-- Excel --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6 flex flex-col shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h3 class="text-[15px] font-bold text-slate-900 leading-snug">Data Tabel Pengukuran (Excel)</h3>
                            <p class="text-[12.5px] text-slate-500 mt-1.5 leading-relaxed">Spreadsheet mentah untuk analisis data lebih lanjut atau rekapitulasi mandiri.</p>
                            <div class="flex flex-wrap gap-1.5 mt-3">
                                <span class="text-[10.5px] font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-md">16 Kolom Lengkap</span>
                                <span class="text-[10.5px] font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-md">Format Spreadsheet</span>
                                <span class="text-[10.5px] font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-md">Arsip Digital</span>
                            </div>
                        </div>
                        <span class="w-11 h-11 shrink-0 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center"><x-icon name="file-xlsx" weight="fill" class="text-[20px]" /></span>
                    </div>
                    <a href="{{ route('laporan.export.excel', ['periode' => $periodeValue]) }}" class="mt-5 w-full h-11 rounded-xl border border-teal-200 bg-teal-50 hover:bg-teal-100 text-teal-700 text-[14px] font-semibold inline-flex items-center justify-center gap-2 transition-colors"><x-icon name="download" weight="bold" class="text-[16px]" /> Export ke Excel (.xls)</a>
                </div>
            </div>
        </section>

        {{-- Grafik Analitik (Line + Donut) --}}
        <section class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <span class="w-1 h-6 bg-teal-600 rounded-full"></span>
                <h2 class="text-base font-bold text-slate-900">Analitik & Visualisasi</h2>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-5">
                {{-- Line chart: tren kunjungan --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="min-w-0">
                            <h3 class="text-[15px] font-bold text-slate-900 leading-snug">Tren Kunjungan Penimbangan</h3>
                            <p class="text-[12.5px] text-slate-500 mt-1 leading-relaxed">Persentase balita yang ditimbang per bulan (6 bulan terakhir; bulan kosong = belum ada data).</p>
                        </div>
                        <span class="w-10 h-10 shrink-0 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center"><x-icon name="chart-line" weight="fill" class="text-[19px]" /></span>
                    </div>
                    <div id="chart-tren" class="w-full"></div>
                </div>
                {{-- Donut chart: komposisi status gizi --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm">
                    <div class="flex items-start justify-between gap-3 mb-4">
                        <div class="min-w-0">
                            <h3 class="text-[15px] font-bold text-slate-900 leading-snug">Komposisi Status Gizi</h3>
                            <p class="text-[12.5px] text-slate-500 mt-1 leading-relaxed">Distribusi status gizi balita terukur pada periode <span class="font-semibold text-slate-700">{{ $periode ?? '' }}</span>.</p>
                        </div>
                        <span class="w-10 h-10 shrink-0 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center"><x-icon name="chart-donut" weight="fill" class="text-[19px]" /></span>
                    </div>
                    <div id="chart-donut" class="w-full"></div>
                </div>
            </div>
        </section>

        <script>
        @php
            $tr = $chartTren ?? ['label'=>[], 'pct'=>[], 'count'=>[]];
            $dn = $chartDonut ?? ['normal'=>0,'risiko'=>0,'stunting'=>0,'kurang'=>0];
        @endphp
        function initLaporanCharts() {
            if (!window.ApexCharts) { setTimeout(initLaporanCharts, 300); return; }
            var tren = @json($tr);
            var donut = @json($dn);

            var elT = document.getElementById('chart-tren');
            if (elT && !elT._apex) {
                elT._apex = new window.ApexCharts(elT, {
                    chart: { type: 'area', height: 260, toolbar: { show: false }, fontFamily: 'Plus Jakarta Sans, sans-serif', foreColor: '#64748b' },
                    series: [{ name: 'Terukur (%)', data: tren.pct }],
                    stroke: { curve: 'straight', width: 3, colors: ['#0d9488'] },
                    fill: { type: 'gradient', gradient: { opacityFrom: 0.25, opacityTo: 0.02, shadeIntensity: 1 } },
                    colors: ['#0d9488'],
                    dataLabels: { enabled: false },
                    xaxis: { categories: tren.label, axisBorder: { show: false }, axisTicks: { show: false }, labels: { style: { fontSize: '11px' } } },
                    yaxis: { max: 100, labels: { formatter: function(v){ return v + '%'; }, style: { fontSize: '11px' } } },
                    grid: { borderColor: '#e2e8f0', strokeDashArray: 4 },
                    tooltip: { y: { formatter: function(val, opts){ return val + '% (' + (tren.count[opts.dataPointIndex] || 0) + ' balita)'; } } },
                    markers: { size: 4, colors: ['#0d9488'], strokeColors: '#fff', strokeWidth: 2 }
                });
                elT._apex.render();
            }

            // Rekap donut chart (kartu putih)
            var elR = document.getElementById('rekap-gauge');
            if (elR && !elR._apex) {
                var rpct = Math.min(100, Math.max(0, parseFloat(elR.getAttribute('data-pct')) || 0));
                elR._apex = new window.ApexCharts(elR, {
                    chart: { type: 'donut', height: 176, fontFamily: 'Plus Jakarta Sans, sans-serif' },
                    labels: ['Terukur', 'Belum'],
                    series: [rpct, Math.max(0, 100 - rpct)],
                    colors: ['#0d9488', '#e2e8f0'],
                    stroke: { width: 0 },
                    dataLabels: { enabled: false },
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '72%',
                                labels: {
                                    show: true,
                                    name: { show: false },
                                    value: { show: true, fontSize: '30px', fontWeight: '800', color: '#0f172a', offsetY: -4 },
                                    total: { show: true, label: 'terukur', fontSize: '12px', color: '#94a3b8', formatter: function(){ return Math.round(rpct) + '%'; } }
                                }
                            }
                        }
                    },
                    legend: { show: false },
                    tooltip: { enabled: false }
                });
                elR._apex.render();
            }

            var elD = document.getElementById('chart-donut');
            if (elD && !elD._apex) {
                var categories = ['Normal', 'Risiko', 'Stunting', 'Kurang'];
                var series = [donut.normal, donut.risiko, donut.stunting, donut.kurang];
                elD._apex = new window.ApexCharts(elD, {
                    chart: { type: 'donut', height: 260, fontFamily: 'Plus Jakarta Sans, sans-serif', foreColor: '#64748b' },
                    labels: categories,
                    series: series,
                    colors: ['#0d9488', '#f59e0b', '#e11d48', '#0ea5e9'],
                    legend: { position: 'bottom', fontSize: '12px' },
                    dataLabels: { enabled: true, formatter: function(v){ return Math.round(v) + '%'; }, style: { fontSize: '11px', fontWeight: '600' } },
                    plotOptions: { pie: { donut: { size: '68%', labels: { show: true, name: { fontSize: '11px' }, value: { fontSize: '20px', fontWeight: '800', color: '#0f172a' }, total: { show: true, label: 'Terukur', fontSize: '11px', color: '#64748b' } } } } },
                    tooltip: { y: { formatter: function(v){ return v + ' balita'; } } }
                });
                elD._apex.render();
            }

            // Mini sparklines pada 4 kartu KPI
            document.querySelectorAll('.kpi-spark').forEach(function (el) {
                if (el._apex) return;
                var key = el.getAttribute('data-spark');
                var color = el.getAttribute('data-color') || '#0d9488';
                var series;
                if (key === 'count') series = tren.count;
                else if (key === 'belum') series = tren.total.map(function(t, i){ return Math.max(0, t - tren.count[i]); });
                else if (key === 'pantauan') series = tren.risiko.map(function(x, i){ return x + (tren.stunting[i] || 0) + (tren.kurang[i] || 0); });
                else series = tren.stunting;
                if (!series || series.length === 0) series = [0, 0];
                el._apex = new window.ApexCharts(el, {
                    chart: { type: 'area', height: 36, sparkline: { enabled: true }, fontFamily: 'Plus Jakarta Sans, sans-serif' },
                    series: [{ name: key, data: series }],
                    stroke: { curve: 'smooth', width: 2.5, colors: [color] },
                    fill: { type: 'gradient', gradient: { opacityFrom: 0.3, opacityTo: 0.02 } },
                    colors: [color],
                    tooltip: { enabled: false },
                    xaxis: { labels: { show: false } }
                });
                el._apex.render();
            });
        }
        document.addEventListener('DOMContentLoaded', initLaporanCharts);
        </script>

        {{-- Pratinjau Data Penimbangan --}}
        <section>
            <div class="flex items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-3">
                    <span class="w-1 h-6 bg-teal-600 rounded-full"></span>
                    <h2 class="text-base font-bold text-slate-900">Pratinjau Data Penimbangan <span class="font-normal text-slate-500">({{ $periode ?? '' }})</span></h2>
                </div>
                <a href="{{ route('balita.index') }}" class="shrink-0 inline-flex items-center gap-1 text-[12.5px] font-semibold text-teal-600 hover:text-teal-700">Lihat Semua <x-icon name="arrow-right" weight="bold" /></a>
            </div>

            @if(isset($previewBalitas) && $previewBalitas->isNotEmpty())
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 border-b border-slate-200 text-[10.5px] font-bold text-slate-400 uppercase tracking-wider">
                                <tr>
                                    <th class="py-3 px-4">Balita & NIK</th>
                                    <th class="py-3 px-4">Nama Ibu</th>
                                    <th class="py-3 px-4">Tgl Ukur</th>
                                    <th class="py-3 px-4 text-center">BB (kg)</th>
                                    <th class="py-3 px-4 text-center">TB (cm)</th>
                                    <th class="py-3 px-4 text-center">KMS</th>
                                    <th class="py-3 px-4 text-center">Status / Diagnosa</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-[13.5px] font-medium text-slate-700">
                                @foreach($previewBalitas as $b)
                                    @php
                                        $m = $b->pengukurans->first();
                                        $statusGizi = $m ? $m->status_gizi : '-';
                                        $statusValidasi = $m ? $m->status_validasi : null;
                                        $isApproved = $statusValidasi === 'approved';
                                        $kms = $m ? ($m->status_kenaikan ?? '-') : '-';
                                        $isWarning = false;
                                        if ($m && (strtolower($statusGizi) != 'normal' || str_contains(strtolower($statusGizi), 'kurang'))) { $isWarning = true; }
                                        if ($kms === 'T' || str_contains($kms, 'Turun')) { $isWarning = true; }
                                    @endphp
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-2.5">
                                                <span class="w-9 h-9 rounded-full bg-teal-50 text-teal-700 flex items-center justify-center shrink-0 text-[13px] font-bold border border-teal-100">{{ strtoupper(substr($b->nama, 0, 1)) }}</span>
                                                <div class="min-w-0">
                                                    <span class="block font-semibold text-slate-900 leading-tight truncate max-w-[200px]">{{ $b->nama }}</span>
                                                    <span class="block text-[11.5px] text-slate-400 mt-0.5">{{ $b->nik ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500">{{ $b->orangTua->nama_ibu ?? '-' }}</td>
                                        <td class="py-3 px-4 text-slate-500 whitespace-nowrap">{{ $m ? \Carbon\Carbon::parse($m->tanggal_ukur)->translatedFormat('d M Y') : '-' }}</td>
                                        <td class="py-3 px-4 text-center {{ $isWarning ? 'font-semibold text-amber-600' : 'text-slate-600' }}">{{ $m ? number_format((float)$m->berat_badan, 1) : '-' }}</td>
                                        <td class="py-3 px-4 text-center text-slate-600">{{ $m ? number_format((float)$m->tinggi_badan, 1) : '-' }}</td>
                                        <td class="py-3 px-4 text-center">
                                            @if($kms === 'N' || str_contains($kms, 'Naik'))
                                                <x-icon name="trend-up" weight="bold" class="text-emerald-500 text-[15px] mx-auto" />
                                            @elseif($kms === 'T' || str_contains($kms, 'Tetap'))
                                                <x-icon name="arrow-right" weight="bold" class="text-slate-300 text-[15px] mx-auto" />
                                            @else
                                                <x-icon name="trend-down" weight="bold" class="text-rose-500 text-[15px] mx-auto" />
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            @if($isApproved)
                                                @if(strtolower($statusGizi) === 'normal')
                                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">Normal</span>
                                                @else
                                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 whitespace-nowrap">{{ ucfirst($statusGizi) }}</span>
                                                @endif
                                            @else
                                                @if($isWarning)
                                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 whitespace-nowrap">Pantauan Gizi</span>
                                                @else
                                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-50 text-slate-500 border border-slate-200 whitespace-nowrap">Menunggu Validasi</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 text-center text-slate-500 text-[13px]">Tidak ada data penimbangan pada periode ini.</div>
            @endif
        </section>

    @endif
</div>
</div>
@endsection
