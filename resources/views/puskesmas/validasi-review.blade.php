@extends('layouts.puskesmas')
@section('page-title', 'Tinjau Validasi - ' . $child['name'])
@section('content')

@php
    $isDanger  = ($child['statusType'] === 'danger');
    $isWarning = ($child['statusType'] === 'warning');
    $isBoy     = ($child['gender'] === 'Laki-laki');

    $avatarClass = $isBoy 
        ? 'bg-sky-50 text-sky-700 border-sky-200/80' 
        : 'bg-rose-50 text-rose-700 border-rose-200/80';

    // Calculate growth delta vs last measurement if history exists
    $lastHist = !empty($child['history']) ? $child['history'][0] : null;
    $deltaBb = null;
    $deltaTb = null;
    if ($lastHist) {
        $currBb = (float) $child['bb'];
        $prevBb = (float) $lastHist['bb'];
        $diffBb = $currBb - $prevBb;
        $deltaBb = ($diffBb >= 0 ? '+' : '') . number_format($diffBb, 1) . ' kg';

        $currTb = (float) $child['tb'];
        $prevTb = (float) $lastHist['tb'];
        $diffTb = $currTb - $prevTb;
        $deltaTb = ($diffTb >= 0 ? '+' : '') . number_format($diffTb, 1) . ' cm';
    }
@endphp

<div class="space-y-6 pb-16">

    <!-- Top Navigation & Breadcrumb -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <a href="{{ route('puskesmas.validasi') }}" 
           class="inline-flex items-center gap-2 text-sm font-bold text-slate-500 hover:text-teal-700 transition-colors w-fit">
            <i class="ph-bold ph-arrow-left text-base"></i>
            <span>Kembali ke Antrean Validasi</span>
        </a>

        <!-- Status Queue Pill -->
        <div class="flex items-center gap-2">
            @if($child['status_validasi'] === 'pending')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-800 border border-amber-200/80">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                    <span>Menunggu Validasi Ahli Gizi</span>
                </span>
            @elseif($child['status_validasi'] === 'approved')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/80">
                    <i class="ph-bold ph-check-circle text-emerald-600"></i>
                    <span>Telah Divalidasi</span>
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-800 border border-rose-200/80">
                    <i class="ph-bold ph-x-circle text-rose-600"></i>
                    <span>Ditolak / Perlu Revisi</span>
                </span>
            @endif
        </div>
    </div>

    <!-- Patient Header Card (Clinical Dossier Banner) -->
    <div class="bg-white border border-slate-200/90 rounded-2xl p-5 sm:p-6 shadow-sm relative overflow-hidden">
        <!-- Subtle Top Brand Accent Line -->
        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-teal-500 via-teal-600 to-slate-200"></div>

        <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-5">
            <!-- Left: Identity & Core Metadata -->
            <div class="flex items-start sm:items-center gap-4 sm:gap-5">
                <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-2xl {{ $avatarClass }} border flex items-center justify-center font-extrabold text-xl sm:text-2xl shrink-0 shadow-sm">
                    {{ substr($child['name'], 0, 1) }}
                </div>
                <div>
                    <div class="flex items-center gap-3 flex-wrap">
                        <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                            {{ $child['name'] }}
                        </h1>
                        <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-md border {{ $isBoy ? 'bg-sky-50 text-sky-700 border-sky-200/70' : 'bg-rose-50 text-rose-700 border-rose-200/70' }}">
                            {{ $isBoy ? 'Laki-laki' : 'Perempuan' }} &bull; {{ $child['age'] }}
                        </span>
                    </div>

                    <!-- Meta Tags -->
                    <div class="flex items-center gap-3 text-xs text-slate-500 mt-2 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 text-slate-600">
                            <i class="ph-bold ph-identification-card text-slate-400 text-sm"></i>
                            NIK: <span class="text-slate-800 font-semibold">{{ $child['nik'] ?: '-' }}</span>
                        </span>
                        <span class="text-slate-300 hidden sm:inline">&bull;</span>
                        <span class="inline-flex items-center gap-1.5 text-slate-600">
                            <i class="ph-bold ph-user text-slate-400 text-sm"></i>
                            Ibu: <span class="text-slate-800 font-semibold">{{ $child['parent'] ?: '-' }}</span>
                        </span>
                        <span class="text-slate-300 hidden sm:inline">&bull;</span>
                        <span class="inline-flex items-center gap-1.5 text-slate-600">
                            <i class="ph-bold ph-map-pin text-teal-600 text-sm"></i>
                            <span class="font-semibold">{{ $child['posyandu'] }}</span>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Right: Clinical Diagnosis Tag -->
            <div class="flex items-center gap-3 lg:border-l lg:border-slate-100 lg:pl-6 shrink-0 mt-2 lg:mt-0">
                <div class="text-left lg:text-right">
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-0.5">Kesimpulan Status Gizi</span>
                    <span class="text-xl sm:text-2xl font-extrabold tracking-tight {{ $isDanger ? 'text-rose-600' : ($isWarning ? 'text-amber-600' : 'text-emerald-600') }}">
                        {{ $child['statusLabel'] }}
                    </span>
                    <span class="block text-[11px] text-slate-400 font-medium mt-1">Berdasarkan Standar Kemenkes RI</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Workspace Grid (Two Column Desk) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

        <!-- Left Column: Clinical Evaluation Desk (Col 7 / Col 8) -->
        <div class="lg:col-span-7 xl:col-span-8 space-y-6">

            <!-- Anthropometry & WHO Growth Indices Card -->
            <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm p-5 sm:p-6">
                
                <div class="flex items-center justify-between border-b border-slate-100 pb-4 mb-5">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">Hasil Pengukuran Fisik & Z-Score WHO</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Diperiksa pada {{ $child['date'] }} jam {{ $child['time'] }} WIB</p>
                    </div>
                    <span class="text-xs font-semibold text-slate-600 bg-slate-50 px-3 py-1 rounded-lg border border-slate-200/80">
                        Umur: <strong class="text-slate-900">{{ $child['age'] }}</strong>
                    </span>
                </div>

                <!-- Primary Physical Measurements (BB & TB with Delta) -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                    <!-- Berat Badan -->
                    <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200/80 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200/70 text-emerald-700 flex items-center justify-center shrink-0">
                                <i class="ph-bold ph-scales text-lg"></i>
                            </div>
                            <div>
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block leading-none">Berat Badan</span>
                                <div class="flex items-baseline gap-1 mt-1">
                                    <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ $child['bb'] }}</span>
                                    <span class="text-xs font-semibold text-slate-500">kg</span>
                                </div>
                            </div>
                        </div>
                        @if($deltaBb)
                            <div class="text-right">
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">vs Lalu</span>
                                <span class="inline-flex items-center gap-0.5 text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md border border-emerald-200/70 mt-0.5">
                                    <i class="ph-bold ph-trend-up text-[11px]"></i>
                                    <span>{{ $deltaBb }}</span>
                                </span>
                            </div>
                        @endif
                    </div>

                    <!-- Tinggi Badan -->
                    <div class="p-4 rounded-xl bg-slate-50/80 border border-slate-200/80 flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-teal-50 border border-teal-200/70 text-teal-700 flex items-center justify-center shrink-0">
                                <i class="ph-bold ph-ruler text-lg"></i>
                            </div>
                            <div>
                                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block leading-none">Tinggi / Panjang</span>
                                <div class="flex items-baseline gap-1 mt-1">
                                    <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ $child['tb'] }}</span>
                                    <span class="text-xs font-semibold text-slate-500">cm</span>
                                </div>
                            </div>
                        </div>
                        @if($deltaTb)
                            <div class="text-right">
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">vs Lalu</span>
                                <span class="inline-flex items-center gap-0.5 text-xs font-bold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-md border border-teal-200/70 mt-0.5">
                                    <i class="ph-bold ph-trend-up text-[11px]"></i>
                                    <span>{{ $deltaTb }}</span>
                                </span>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- WHO 4 Growth Indices Grid (High-Density Clinical Matrix) -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Matriks 4 Indikator Standar WHO (Kemenkes)</h3>
                        <span class="text-[11px] text-slate-400 font-medium">Batas deviasi: -2.0 SD s/d +2.0 SD</span>
                    </div>

                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                        @php
                            $indices = [
                                'TB/U' => [
                                    'title' => 'Tinggi / Umur',
                                    'sub' => 'Stunting',
                                    'data' => $child['zscores']['TB/U'] ?? ['val' => '-', 'status' => 'Normal', 'color' => 'slate']
                                ],
                                'BB/U' => [
                                    'title' => 'Berat / Umur',
                                    'sub' => 'Underweight',
                                    'data' => $child['zscores']['BB/U'] ?? ['val' => '-', 'status' => 'Normal', 'color' => 'slate']
                                ],
                                'BB/TB' => [
                                    'title' => 'Berat / Tinggi',
                                    'sub' => 'Wasting',
                                    'data' => $child['zscores']['BB/TB'] ?? ['val' => '-', 'status' => 'Normal', 'color' => 'slate']
                                ],
                                'IMT/U' => [
                                    'title' => 'IMT / Umur',
                                    'sub' => 'Body Mass',
                                    'data' => $child['zscores']['IMT/U'] ?? ['val' => '-', 'status' => 'Normal', 'color' => 'slate']
                                ],
                            ];
                        @endphp

                        @foreach($indices as $code => $idx)
                            @php
                                $val = $idx['data']['val'];
                                $stat = $idx['data']['status'];
                                $color = $idx['data']['color'];

                                $cardBg = 'bg-white border-slate-200';
                                if ($color === 'rose' || in_array($stat, ['Pendek', 'Sangat Pendek', 'Kurus', 'Sangat Kurus', 'Kurang'])) {
                                    $tagBg = 'text-rose-700 bg-rose-50 border-rose-200/80';
                                    $cardBg = 'bg-rose-50/50 border-rose-300';
                                } elseif ($color === 'amber' || in_array($stat, ['Risiko Lebih', 'Lebih'])) {
                                    $tagBg = 'text-amber-700 bg-amber-50 border-amber-200/80';
                                } else {
                                    $tagBg = 'text-emerald-700 bg-emerald-50 border-emerald-200/80';
                                }
                            @endphp
                            <div class="p-3.5 border {{ $cardBg }} rounded-xl flex flex-col justify-between hover:border-slate-300 transition-colors">
                                <div>
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold text-slate-900">{{ $code }}</span>
                                        <span class="text-[10px] text-slate-400 font-medium">{{ $idx['sub'] }}</span>
                                    </div>
                                    <div class="text-lg font-black text-slate-900 mt-1">
                                        {{ $val }} <span class="text-[10px] text-slate-400 font-normal">SD</span>
                                    </div>
                                </div>
                                <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between">
                                    <span class="inline-block px-2 py-0.5 rounded-md text-[11px] font-bold border {{ $tagBg }}">
                                        {{ $stat }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Interactive Growth Trajectory Curve (Chart with WHO Reference Line) -->
            <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm p-5 sm:p-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                    <div>
                        <div class="flex items-center gap-2">
                            <i class="ph-bold ph-chart-line-up text-teal-700 text-base"></i>
                            <h2 class="text-base font-bold text-slate-900">Kurva Riwayat Pertumbuhan Anak</h2>
                        </div>
                        <p class="text-xs text-slate-500 mt-0.5">Grafik perkembangan dari Posyandu dengan garis ambang batas WHO</p>
                    </div>

                    <!-- Metric Switcher Dropdown (Replaces Pills) -->
                    <div x-data="{ 
                            open: false, 
                            active: 'tbu',
                            labels: {
                                'tbu': 'TB/U (Stunting)',
                                'bbu': 'BB/U',
                                'tb': 'TB (cm)',
                                'bb': 'BB (kg)'
                            },
                            selectMetric(key) {
                                this.active = key;
                                this.open = false;
                                setChartMetric(key);
                            }
                         }" 
                         class="relative z-20">
                         
                        <button @click="open = !open" 
                                @click.away="open = false"
                                type="button" 
                                class="flex items-center gap-2 px-3.5 py-2 bg-white border border-slate-200 hover:border-slate-300 hover:bg-slate-50 rounded-xl shadow-sm text-xs font-bold text-slate-800 transition-all focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                            <span x-text="labels[active]">TB/U (Stunting)</span>
                            <i class="ph-bold ph-caret-down text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }"></i>
                        </button>

                        <div x-show="open" 
                             x-transition.opacity.duration.200ms
                             style="display: none;"
                             class="absolute right-0 sm:right-0 mt-2 w-44 bg-white border border-slate-200/90 rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] py-1.5 overflow-hidden">
                            <template x-for="(label, key) in labels" :key="key">
                                <button @click="selectMetric(key)"
                                        type="button"
                                        class="w-full text-left px-4 py-2.5 text-xs font-bold transition-colors flex items-center justify-between"
                                        :class="active === key ? 'bg-teal-50 text-teal-800' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900'">
                                    <span x-text="label"></span>
                                    <i x-show="active === key" class="ph-bold ph-check text-teal-600 text-sm"></i>
                                </button>
                            </template>
                        </div>
                    </div>
                </div>

                <!-- Canvas Chart Wrapper -->
                <div class="w-full h-64 sm:h-72 bg-slate-50/40 rounded-xl border border-slate-100 p-3 relative">
                    <canvas id="growthChart"></canvas>
                </div>

                <!-- Chart Legend Bar -->
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 text-[11px] text-slate-500 mt-3 pt-3 border-t border-slate-100 px-1">
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-1 rounded-full bg-teal-700"></span>
                            <span class="font-medium text-slate-700">Pengukuran Aktual</span>
                        </div>
                        <div class="flex items-center gap-1.5">
                            <span class="w-3 h-0.5 border-t border-dashed border-rose-500"></span>
                            <span class="font-medium text-rose-600">Ambang Batas WHO (-2.0 SD)</span>
                        </div>
                    </div>
                    <span class="text-slate-400">Standar Antropometri Anak 0-59 Bulan</span>
                </div>
            </div>

            <!-- Historical Posyandu Measurements Table -->
            <div class="bg-white border border-slate-200/90 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-4 sm:p-5 border-b border-slate-100 flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-clock-counter-clockwise text-slate-400 text-sm"></i>
                        <h3 class="text-sm font-bold text-slate-900">Riwayat Pengukuran Posyandu Sebelumnya</h3>
                    </div>
                    <span class="text-xs text-slate-500 font-medium">{{ count($child['history']) }} data tersimpan</span>
                </div>

                <div class="overflow-x-auto">
                    @if(!empty($child['history']))
                        <table class="w-full text-left border-collapse text-xs">
                            <thead>
                                <tr class="bg-slate-50/70 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                    <th class="py-3 px-5">Tanggal Ukur</th>
                                    <th class="py-3 px-3">Usia</th>
                                    <th class="py-3 px-3 text-center">BB (kg)</th>
                                    <th class="py-3 px-3 text-center">TB (cm)</th>
                                    <th class="py-3 px-3 text-center">TB/U</th>
                                    <th class="py-3 px-5 text-right">Status Gizi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-slate-700">
                                @foreach($child['history'] as $hist)
                                    @php
                                        $histDanger = str_contains(strtolower($hist['status']), 'stunting');
                                    @endphp
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-3.5 px-5 font-semibold text-slate-900">{{ $hist['date'] }}</td>
                                        <td class="py-3.5 px-3 text-slate-500">{{ $hist['age'] }}</td>
                                        <td class="py-3.5 px-3 text-center font-bold text-slate-800">{{ $hist['bb'] }}</td>
                                        <td class="py-3.5 px-3 text-center font-bold text-slate-800">{{ $hist['tb'] }}</td>
                                        <td class="py-3.5 px-3 text-center font-mono font-medium text-slate-600">{{ $hist['tbu'] ?? '-' }}</td>
                                        <td class="py-3.5 px-5 text-right">
                                            <span class="font-black text-xs {{ $histDanger ? 'text-rose-600' : 'text-emerald-600' }}">
                                                {{ ucfirst($hist['status']) }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="py-10 text-center text-slate-400 text-xs">
                            Belum ada riwayat pengukuran sebelumnya dari Posyandu.
                        </div>
                    @endif
                </div>
            </div>

        </div>

        <!-- Right Column: Decision & Validation Desk (Col 5 / Col 4) -->
        <div class="lg:col-span-5 xl:col-span-4 bg-slate-50/50 rounded-3xl border border-slate-200/60 shadow-inner p-5 sm:p-6 space-y-6">

            <!-- Posyandu Origin & Examiner Info -->
            <div>
                <div class="flex items-center gap-2 mb-3 pb-2 border-b border-slate-200/80">
                    <i class="ph-bold ph-buildings text-teal-700 text-sm"></i>
                    <h3 class="text-xs font-bold text-slate-700 uppercase tracking-wider">Asal Data Posyandu</h3>
                </div>

                <div class="space-y-2 text-xs">
                    <div class="flex items-center justify-between py-1 border-b border-slate-200/40">
                        <span class="text-slate-500">Posyandu:</span>
                        <span class="font-bold text-slate-900">{{ $child['posyandu'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-200/40">
                        <span class="text-slate-500">Kader Pengukur:</span>
                        <span class="font-bold text-slate-900">{{ $child['kader'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1 border-b border-slate-200/40">
                        <span class="text-slate-500">Waktu Ukur:</span>
                        <span class="font-medium text-slate-700">{{ $child['date'] }} &bull; {{ $child['time'] }}</span>
                    </div>
                    <div class="flex items-center justify-between py-1">
                        <span class="text-slate-500">Nama Ibu:</span>
                        <span class="font-bold text-slate-900">{{ $child['parent'] }}</span>
                    </div>
                </div>
            </div>

            <!-- Field Notes from Posyandu Kader (if any) -->
            @if(!empty($child['catatan_kader']))
                <div class="bg-amber-100/40 border border-amber-200/60 rounded-2xl p-4">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-200/50 border border-amber-300/50 text-amber-800 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="ph-bold ph-chat-centered-text text-base"></i>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="block text-[11px] font-bold text-amber-900 uppercase tracking-wider">Catatan Observasi Kader Posyandu</span>
                            <p class="text-xs sm:text-sm text-amber-900/90 font-medium mt-1 leading-relaxed italic">
                                "{{ $child['catatan_kader'] }}"
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Clinical Decision & Validation Desk -->
            <div x-data="{ 
                    notes: '{{ addslashes($child['catatan_validator'] ?? '') }}',
                    showApproveModal: false,
                    showRejectModal: false,
                    rejectReason: '',
                    addNote(text) {
                        if (this.notes.trim()) {
                            this.notes += '; ' + text;
                        } else {
                            this.notes = text;
                        }
                    }
                 }" 
                 class="space-y-4 pt-4 border-t border-slate-200/80">
                
                <div class="pb-2">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold ph-stethoscope text-teal-700 text-base"></i>
                        <h3 class="text-sm font-bold text-slate-900">Diagnosis & Catatan Ahli Gizi</h3>
                    </div>
                    <p class="text-xs text-slate-500 mt-1">Rekomendasi otomatis diteruskan ke buku KIA digital orang tua.</p>
                </div>

                <!-- Quick Recommendation Chips -->
                <div>
                    <span class="block text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-2">Templat Rekomendasi Cepat</span>
                    <div class="flex flex-wrap gap-1.5">
                        <button type="button" @click="addNote('Konseling gizi & PMT protein hewani (telur/ikan)')" 
                                class="px-2.5 py-1 bg-white hover:bg-teal-50 hover:text-teal-800 hover:border-teal-200 rounded-lg border border-slate-200/80 text-[11px] font-medium text-slate-600 transition-colors shadow-sm">
                            + PMT Protein Hewani
                        </button>
                        <button type="button" @click="addNote('Pertumbuhan normal, pertahankan pola makan seimbang')" 
                                class="px-2.5 py-1 bg-white hover:bg-emerald-50 hover:text-emerald-800 hover:border-emerald-200 rounded-lg border border-slate-200/80 text-[11px] font-medium text-slate-600 transition-colors shadow-sm">
                            + Gizi Seimbang
                        </button>
                        <button type="button" @click="addNote('Pantau kenaikan berat badan posyandu bulan depan')" 
                                class="px-2.5 py-1 bg-white hover:bg-teal-50 hover:text-teal-800 hover:border-teal-200 rounded-lg border border-slate-200/80 text-[11px] font-medium text-slate-600 transition-colors shadow-sm">
                            + Pantau Bulan Depan
                        </button>
                        <button type="button" @click="addNote('Rujuk pemeriksaan dokter umum/spesialis Puskesmas')" 
                                class="px-2.5 py-1 bg-white hover:bg-rose-50 hover:text-rose-800 hover:border-rose-200 rounded-lg border border-slate-200/80 text-[11px] font-medium text-slate-600 transition-colors shadow-sm">
                            + Rujuk Dokter
                        </button>
                    </div>
                </div>

                <!-- Textarea for Validator Notes (Enlarged & Spacious) -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="validatorNotes" class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider">Instruksi & Pesan KIA</label>
                    </div>
                    <textarea id="validatorNotes" 
                              x-model="notes" 
                              rows="5" 
                              placeholder="Ketik instruksi pola asuh, anjuran menu makanan bergizi, pemberian suplemen, atau catatan evaluasi untuk orang tua..." 
                              class="w-full px-4 py-3 text-xs sm:text-sm bg-white border border-slate-200/80 rounded-xl text-slate-800 placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all leading-relaxed shadow-sm"></textarea>
                </div>

                <!-- Action Decisions (Modal Triggers) -->
                <div class="pt-4 space-y-3">
                    
                    <button type="button" 
                            @click="showApproveModal = true" 
                            class="w-full py-3.5 px-4 bg-teal-700 hover:bg-teal-800 text-white font-bold text-xs rounded-xl shadow-sm transition-all flex items-center justify-center gap-2">
                        <i class="ph-bold ph-check text-sm"></i>
                        <span>Setujui & Terbitkan Tautan Buku KIA</span>
                    </button>

                    <button type="button" 
                            @click="showRejectModal = true" 
                            class="w-full py-3 px-4 bg-white hover:bg-rose-50 border border-slate-200/80 hover:border-rose-200 text-slate-600 hover:text-rose-700 font-bold text-xs rounded-xl transition-all flex items-center justify-center gap-1.5 shadow-sm">
                        <i class="ph-bold ph-arrow-counter-clockwise text-xs"></i>
                        <span>Tolak / Minta Pengukuran Ulang</span>
                    </button>
                    
                </div>

                <!-- Approve Modal -->
                <div x-show="showApproveModal" 
                     style="display: none;" 
                     class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                    <div x-show="showApproveModal" x-transition.opacity class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showApproveModal = false"></div>
                    <div x-show="showApproveModal" 
                         x-transition.scale.95 
                         class="bg-white rounded-3xl shadow-xl w-full max-w-sm overflow-hidden z-10 p-6 relative">
                        <div class="w-12 h-12 rounded-full bg-teal-100 text-teal-600 flex items-center justify-center mb-4">
                            <i class="ph-bold ph-check-circle text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Terbitkan Validasi?</h3>
                        <p class="text-sm text-slate-500 mb-6">Data ini akan disahkan dan tautan Buku KIA digital akan dikirimkan kepada orang tua.</p>
                        
                        <form action="{{ route('puskesmas.validasi.approve', $child['id']) }}" method="POST">
                            @csrf
                            <input type="hidden" name="catatan_validator" :value="notes">
                            <div class="flex gap-3">
                                <button type="button" @click="showApproveModal = false" class="flex-1 px-4 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-colors">Batal</button>
                                <button type="submit" class="flex-1 px-4 py-3 rounded-xl bg-teal-700 text-white font-bold text-xs hover:bg-teal-800 transition-colors shadow-sm">Ya, Terbitkan</button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Reject Modal -->
                <div x-show="showRejectModal" 
                     style="display: none;" 
                     class="fixed inset-0 z-[100] flex items-center justify-center p-4">
                    <div x-show="showRejectModal" x-transition.opacity class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="showRejectModal = false"></div>
                    <div x-show="showRejectModal" 
                         x-transition.scale.95 
                         class="bg-white rounded-3xl shadow-xl w-full max-w-sm overflow-hidden z-10 p-6 relative">
                        <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mb-4">
                            <i class="ph-bold ph-arrow-counter-clockwise text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-bold text-slate-900 mb-2">Tolak & Kembalikan Data</h3>
                        <p class="text-sm text-slate-500 mb-4">Data akan dikembalikan ke Kader Posyandu untuk dilakukan pengukuran ulang atau perbaikan.</p>
                        
                        <form action="{{ route('puskesmas.validasi.reject', $child['id']) }}" method="POST">
                            @csrf
                            <div class="mb-5">
                                <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-1.5">Alasan Penolakan</label>
                                <textarea name="catatan_validator" 
                                          x-model="rejectReason" 
                                          required 
                                          rows="3" 
                                          placeholder="Jelaskan bagian mana yang perlu diperbaiki oleh kader..." 
                                          class="w-full px-3 py-2.5 text-sm bg-slate-50 border border-slate-200 rounded-xl text-slate-800 focus:outline-none focus:bg-white focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20 resize-none transition-all"></textarea>
                            </div>
                            <div class="flex gap-3">
                                <button type="button" @click="showRejectModal = false" class="flex-1 px-4 py-3 rounded-xl border border-slate-200 text-slate-600 font-bold text-xs hover:bg-slate-50 transition-colors">Batal</button>
                                <button type="submit" class="flex-1 px-4 py-3 rounded-xl bg-rose-600 text-white font-bold text-xs hover:bg-rose-700 transition-colors shadow-sm">Kirim Penolakan</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ── CHARTS CONFIGURATION WITH WHO THRESHOLD REFERENCE LINE ──
    const chartRaw = @json($child['chartData']);
    const ctx = document.getElementById('growthChart');
    let growthChart;

    const metricConfig = {
        tbu: {
            label: 'Z-Score TB/U (Aktual)',
            data: chartRaw.tbu || [],
            color: '#0f766e', // Teal 700
            bgColor: 'rgba(15, 118, 110, 0.08)',
            unit: 'SD',
            threshold: -2.0,
            thresholdLabel: 'Batas Stunting (-2 SD)'
        },
        bbu: {
            label: 'Z-Score BB/U (Aktual)',
            data: chartRaw.bbu || [],
            color: '#2563eb', // Blue 600
            bgColor: 'rgba(37, 99, 235, 0.08)',
            unit: 'SD',
            threshold: -2.0,
            thresholdLabel: 'Batas Berat Kurang (-2 SD)'
        },
        tb: {
            label: 'Tinggi Badan (cm)',
            data: chartRaw.tb || [],
            color: '#d97706', // Amber 600
            bgColor: 'rgba(217, 119, 6, 0.08)',
            unit: 'cm',
            threshold: null
        },
        bb: {
            label: 'Berat Badan (kg)',
            data: chartRaw.bb || [],
            color: '#059669', // Emerald 600
            bgColor: 'rgba(5, 150, 105, 0.08)',
            unit: 'kg',
            threshold: null
        }
    };

    if (ctx && chartRaw.labels && chartRaw.labels.length > 0) {
        const initial = metricConfig.tbu;
        const count = chartRaw.labels.length;

        // Build threshold dataset if available
        const datasets = [];

        if (initial.threshold !== null) {
            datasets.push({
                label: initial.thresholdLabel,
                data: Array(count).fill(initial.threshold),
                borderColor: '#f43f5e', // Rose 500
                borderWidth: 1.5,
                borderDash: [5, 5],
                pointRadius: 0,
                fill: false
            });
        }

        datasets.push({
            label: initial.label,
            data: initial.data,
            borderColor: initial.color,
            backgroundColor: initial.bgColor,
            borderWidth: 2.5,
            fill: true,
            tension: 0.35,
            pointRadius: 4.5,
            pointHoverRadius: 6,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: initial.color,
            pointBorderWidth: 2
        });

        growthChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartRaw.labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: {
                        grid: { color: '#f1f5f9' },
                        border: { display: false },
                        ticks: {
                            color: '#64748b',
                            font: { size: 11 }
                        }
                    },
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: {
                            color: '#64748b',
                            font: { size: 11, weight: 'bold' }
                        }
                    }
                }
            }
        });
    }

    window.setChartMetric = function(key) {
        if (!growthChart || !metricConfig[key]) return;
        const conf = metricConfig[key];
        const count = chartRaw.labels.length;

        // Button active state is now managed reactively by Alpine.js in the dropdown.

        const newDatasets = [];

        if (conf.threshold !== null) {
            newDatasets.push({
                label: conf.thresholdLabel,
                data: Array(count).fill(conf.threshold),
                borderColor: '#f43f5e',
                borderWidth: 1.5,
                borderDash: [5, 5],
                pointRadius: 0,
                fill: false
            });
        }

        newDatasets.push({
            label: conf.label,
            data: conf.data,
            borderColor: conf.color,
            backgroundColor: conf.bgColor,
            borderWidth: 2.5,
            fill: true,
            tension: 0.35,
            pointRadius: 4.5,
            pointHoverRadius: 6,
            pointBackgroundColor: '#ffffff',
            pointBorderColor: conf.color,
            pointBorderWidth: 2
        });

        growthChart.data.datasets = newDatasets;
        growthChart.update();
    };
</script>
@endpush
