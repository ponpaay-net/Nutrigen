@props(['measurement', 'isLast' => false, 'isLatest' => false])

{{--
|--------------------------------------------------------------------------
| x-timeline-item
|--------------------------------------------------------------------------
| Expected $measurement array shape:
|   date           (string) — formatted date, e.g. "10 Mei 2026"
|   age_at_measure (string) — e.g. "1 Thn 11 Bln" or "23 Bulan"
|   weight         (float)  — weight in kg
|   weight_trend   (float)  — delta from previous measurement (positive = gain)
|   height         (float)  — height in cm
|   height_trend   (float)  — delta from previous measurement
|   z_score_bbu    (float)  — Z-score BB/U
|   z_score_tbu    (float)  — Z-score TB/U
|   head_circ      (float)  — head circumference in cm
|   status         (string) — display label, e.g. "Normal", "Risiko Stunting"
|   status_type    (string) — 'success' | 'warning' | 'danger'
|   status_validasi (string) — 'approved' | 'pending' | 'rejected'
|   catatan_validator (string)
|   isLast         (bool)   — hides connecting timeline line
|   isLatest       (bool)   — highlights latest measurement
--}}

@php
    $colorMap = [
        'success' => [
            'ring'   => 'border-emerald-300 text-emerald-600',
            'dot'    => 'bg-emerald-500',
            'badge'  => 'bg-emerald-50/80 text-emerald-800 border-emerald-200/70',
        ],
        'warning' => [
            'ring'   => 'border-amber-300 text-amber-600',
            'dot'    => 'bg-amber-500',
            'badge'  => 'bg-amber-50/80 text-amber-800 border-amber-200/70',
        ],
        'danger'  => [
            'ring'   => 'border-rose-300 text-rose-600',
            'dot'    => 'bg-rose-500',
            'badge'  => 'bg-rose-50/80 text-rose-800 border-rose-200/70',
        ],
    ];

    $colors = $colorMap[$measurement['status_type']] ?? [
        'ring'  => 'border-slate-300 text-slate-500',
        'dot'   => 'bg-slate-400',
        'badge' => 'bg-slate-50 text-slate-700 border-slate-200/80',
    ];

    $statusValidasi = $measurement['status_validasi'] ?? 'pending';
    $valConfig = match($statusValidasi) {
        'approved' => [
            'badge' => 'bg-emerald-50/70 text-emerald-700 border-emerald-200/60',
            'label' => 'Terverifikasi Puskesmas',
            'icon'  => '<svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>'
        ],
        'rejected' => [
            'badge' => 'bg-rose-50/70 text-rose-700 border-rose-200/60',
            'label' => 'Perlu Revisi',
            'icon'  => '<svg class="w-3.5 h-3.5 text-rose-600 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>'
        ],
        default => [
            'badge' => 'bg-slate-50 text-slate-600 border-slate-200/80',
            'label' => 'Menunggu Validasi',
            'icon'  => '<svg class="w-3.5 h-3.5 text-slate-400 shrink-0" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" /></svg>'
        ]
    };
@endphp

<div x-data="{ open: {{ ($measurement['status_validasi'] ?? '') === 'rejected' ? 'true' : 'false' }} }" class="relative pl-8 sm:pl-10 pb-4 group">
    <!-- Timeline Track Line -->
    @unless($isLast)
        <div class="absolute left-[13px] sm:left-[15px] top-6 bottom-0 w-[2px] bg-slate-200/80 group-hover:bg-teal-200/80 transition-colors"></div>
    @endunless
    
    <!-- Timeline Node Indicator -->
    <div class="absolute left-0 top-3 w-[26px] h-[26px] sm:w-[30px] sm:h-[30px] rounded-full bg-white border {{ $statusValidasi === 'rejected' ? 'border-rose-400 text-rose-600 ring-4 ring-rose-100' : $colors['ring'] }} shadow-2xs flex items-center justify-center transition-all group-hover:scale-105">
        <div class="w-2.5 h-2.5 sm:w-3 sm:h-3 rounded-full {{ $statusValidasi === 'rejected' ? 'bg-rose-500 animate-pulse' : $colors['dot'] }}"></div>
    </div>
    
    <!-- Collapsible Card Container -->
    <div class="bg-white border {{ $statusValidasi === 'rejected' ? 'border-rose-300 border-l-4 border-l-rose-500 shadow-[0_4px_20px_-4px_rgba(244,63,94,0.12)]' : ($isLatest ? 'border-teal-200/90 shadow-[0_4px_16px_rgba(13,148,136,0.05)]' : 'border-slate-200/70 shadow-2xs') }} rounded-2xl transition-all duration-200 overflow-hidden hover:border-slate-300/80">
        
        <!-- Summary Header Bar (Always Visible & Clickable) -->
        <button type="button" @click="open = !open" class="w-full text-left p-3 sm:p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-4 hover:bg-slate-50/60 transition-colors cursor-pointer select-none">
            
            <!-- Left Side: Date, Age, Badges & Quick Metric Preview -->
            <div class="flex items-center flex-wrap gap-2 sm:gap-2.5">
                <span class="text-[13.5px] sm:text-[14px] font-bold text-slate-700 tracking-tight">{{ $measurement['date'] }}</span>
                
                @if(isset($measurement['age_at_measure']))
                    <span class="px-2 py-0.5 bg-slate-100/70 text-slate-600 rounded-md text-[10.5px] sm:text-[11px] font-medium border border-slate-200/40">
                        Usia {{ $measurement['age_at_measure'] }}
                    </span>
                @endif

                @if($isLatest)
                    <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10.5px] font-semibold bg-teal-50 text-teal-700 border border-teal-200/60">
                        <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                        Terbaru
                    </span>
                @endif

                <!-- Quick Inline Summary (BB & TB) -->
                <div class="flex items-center gap-1.5 px-2.5 py-0.5 bg-slate-50 border border-slate-200/60 rounded-md text-[11px] text-slate-500 font-normal">
                    <span>BB: <strong class="text-slate-700 font-semibold">{{ $measurement['weight'] }} kg</strong></span>
                    <span class="text-slate-300">•</span>
                    <span>TB: <strong class="text-slate-700 font-semibold">{{ $measurement['height'] }} cm</strong></span>
                </div>
            </div>
            
            <!-- Right Side: Status Badges & Toggle Chevron Button -->
            <div class="flex items-center justify-between sm:justify-end gap-2 w-full sm:w-auto pt-1 sm:pt-0 border-t border-slate-100 sm:border-0">
                <div class="flex items-center flex-wrap gap-1.5">
                    @if($statusValidasi === 'rejected')
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-rose-100 text-rose-800 border border-rose-200 shadow-2xs">
                            <span class="w-2 h-2 rounded-full bg-rose-500 animate-ping"></span>
                            Perlu Revisi Kader
                        </span>
                    @else
                        <!-- Status Gizi Badge -->
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $colors['badge'] }}">
                            {{ $measurement['status'] }}
                        </span>
                        
                        <!-- Status Validasi Badge -->
                        <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold border {{ $valConfig['badge'] }}">
                            {!! $valConfig['icon'] !!}
                            <span>{{ $valConfig['label'] }}</span>
                        </span>
                    @endif
                </div>

                <!-- Animated Chevron -->
                <div class="w-6.5 h-6.5 rounded-lg bg-slate-50 hover:bg-slate-100 text-slate-400 flex items-center justify-center shrink-0 ml-1 transition-all duration-200 border border-slate-200/50" :class="{ 'rotate-180 bg-teal-50 text-teal-700 border-teal-200/60': open }">
                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                </div>
            </div>
        </button>
        
        <!-- Expanded Details Content (Opened on Click) -->
        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2" class="px-4 pb-4 sm:px-5 sm:pb-5 pt-3 border-t border-slate-100 bg-slate-50/40">
            
            <!-- Measurement Metrics Grid -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2.5 sm:gap-3">
                
                <!-- Berat Badan (BB) -->
                @php
                    $isWeightAnomaly = isset($measurement['weight_trend']) && abs($measurement['weight_trend']) >= 2.0;
                @endphp
                <div class="bg-white border {{ $isWeightAnomaly ? 'border-rose-300 ring-2 ring-rose-100 bg-rose-50/30' : 'border-slate-200/70' }} rounded-xl p-3 flex flex-col justify-between shadow-xs">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Berat Badan</span>
                        <div class="w-5 h-5 rounded-md {{ $isWeightAnomaly ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }} flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3"><path fill-rule="evenodd" d="M10 2a.75.75 0 01.75.75v1.543a6.502 6.502 0 014.71 4.71h1.54a.75.75 0 010 1.5h-1.54a6.502 6.502 0 01-4.71 4.71v1.543a.75.75 0 01-1.5 0v-1.543a6.502 6.502 0 01-4.71-4.71H2.75a.75.75 0 010-1.5h1.54a6.502 6.502 0 014.71-4.71V2.75A.75.75 0 0110 2zm0 3.5a5 5 0 100 10 5 5 0 000-10z" clip-rule="evenodd" /></svg>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1">
                        <span class="text-[16px] font-semibold text-slate-800">{{ $measurement['weight'] }}</span>
                        <span class="text-[12px] font-medium text-slate-500">kg</span>
                    </div>
                    <div class="mt-1.5 flex items-center">
                        @if(isset($measurement['weight_trend']) && $measurement['weight_trend'] > 0)
                            <span class="inline-flex items-center gap-0.5 text-[11px] font-semibold {{ $isWeightAnomaly ? 'text-rose-700 bg-rose-100/90 border border-rose-200 font-bold' : 'text-emerald-600 bg-emerald-50 border border-emerald-200/50' }} px-1.5 py-0.5 rounded">
                                <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" /></svg>
                                +{{ $measurement['weight_trend'] }} kg
                            </span>
                        @elseif(isset($measurement['weight_trend']) && $measurement['weight_trend'] < 0)
                            <span class="inline-flex items-center gap-0.5 text-[11px] font-semibold text-rose-600 bg-rose-50 px-1.5 py-0.5 rounded border border-rose-200/50">
                                <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z" clip-rule="evenodd" /></svg>
                                {{ $measurement['weight_trend'] }} kg
                            </span>
                        @elseif(isset($measurement['weight_trend']) && $measurement['weight_trend'] == 0)
                            <span class="text-[11px] font-medium text-slate-400">Tetap (0 kg)</span>
                        @else
                            <span class="text-[11px] font-medium text-slate-400">Pengukuran awal</span>
                        @endif
                    </div>
                </div>
                
                <!-- Tinggi Badan (TB) -->
                <div class="bg-white border border-slate-200/70 rounded-xl p-3 flex flex-col justify-between shadow-xs">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Tinggi Badan</span>
                        <div class="w-5 h-5 rounded-md bg-amber-100 text-amber-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3"><path fill-rule="evenodd" d="M10 3a.75.75 0 01.75.75v10.638l3.96-4.158a.75.75 0 111.08 1.04l-5.25 5.5a.75.75 0 01-1.08 0l-5.25-5.5a.75.75 0 111.08-1.04l3.96 4.158V3.75A.75.75 0 0110 3z" clip-rule="evenodd" /></svg>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1">
                        <span class="text-[16px] font-semibold text-slate-800">{{ $measurement['height'] }}</span>
                        <span class="text-[12px] font-medium text-slate-500">cm</span>
                    </div>
                    <div class="mt-1.5 flex items-center">
                        @if(isset($measurement['height_trend']) && $measurement['height_trend'] > 0)
                            <span class="inline-flex items-center gap-0.5 text-[11px] font-semibold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200/50">
                                <svg class="w-3 h-3" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 17a.75.75 0 01-.75-.75V5.612L5.29 9.77a.75.75 0 01-1.08-1.04l5.25-5.5a.75.75 0 011.08 0l5.25 5.5a.75.75 0 11-1.08 1.04l-3.96-4.158V16.25A.75.75 0 0110 17z" clip-rule="evenodd" /></svg>
                                +{{ $measurement['height_trend'] }} cm
                            </span>
                        @elseif(isset($measurement['height_trend']) && $measurement['height_trend'] == 0)
                            <span class="text-[11px] font-medium text-slate-400">Tetap (0 cm)</span>
                        @else
                            <span class="text-[11px] font-medium text-slate-400">Pengukuran awal</span>
                        @endif
                    </div>
                </div>

                <!-- Lingkar Kepala (LK) -->
                <div class="bg-white border border-slate-200/70 rounded-xl p-3 flex flex-col justify-between shadow-xs">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Lingkar Kepala</span>
                        <div class="w-5 h-5 rounded-md bg-teal-100 text-teal-600 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm0-2a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd" /></svg>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1">
                        <span class="text-[16px] font-semibold text-slate-800">{{ $measurement['head_circ'] ?? '-' }}</span>
                        <span class="text-[12px] font-medium text-slate-500">cm</span>
                    </div>
                    <div class="mt-1.5 flex items-center">
                        @if(!empty($measurement['asi_eksklusif']))
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold text-teal-700 bg-teal-50 px-1.5 py-0.5 rounded border border-teal-200/50">
                                ASI Eksklusif
                            </span>
                        @else
                            <span class="text-[11px] font-medium text-slate-400">
                                {{ !empty($measurement['head_circ']) ? 'Tercatat' : 'Tidak diukur' }}
                            </span>
                        @endif
                    </div>
                </div>

                <!-- Indeks Z-Score & KMS -->
                <div class="bg-white border border-slate-200/70 rounded-xl p-3 flex flex-col justify-between shadow-xs">
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Status KMS & Z-Score</span>
                        @if(!empty($measurement['status_kenaikan']))
                            <span class="px-1.5 py-0.5 text-[10px] font-bold rounded {{ $measurement['status_kenaikan'] === 'N' ? 'bg-emerald-100 text-emerald-700' : ($measurement['status_kenaikan'] === 'T' ? 'bg-rose-100 text-rose-700' : 'bg-slate-200 text-slate-700') }}">
                                KMS: {{ $measurement['status_kenaikan'] }}
                            </span>
                        @endif
                    </div>
                    <div class="flex flex-col gap-0.5 mt-0.5">
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-slate-500 font-medium">BB/U:</span>
                            <span class="font-semibold {{ isset($measurement['z_score_bbu']) && $measurement['z_score_bbu'] < -2 ? 'text-amber-600 font-bold' : 'text-slate-700' }}">
                                {{ $measurement['z_score_bbu'] !== null ? $measurement['z_score_bbu'] . ' SD' : '-' }}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-[11px]">
                            <span class="text-slate-500 font-medium">TB/U:</span>
                            <span class="font-semibold {{ isset($measurement['z_score_tbu']) && $measurement['z_score_tbu'] < -2 ? 'text-amber-600 font-bold' : 'text-slate-700' }}">
                                {{ $measurement['z_score_tbu'] !== null ? $measurement['z_score_tbu'] . ' SD' : '-' }}
                            </span>
                        </div>
                    </div>
                </div>
                
            </div>

            @if(!empty($measurement['catatan_kader']))
                {{-- Catatan Kader Callout --}}
                <div class="mt-3 p-3 bg-white border border-slate-200/70 rounded-xl flex items-start gap-2.5 text-slate-700 shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-teal-600 shrink-0 mt-0.5">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                    </svg>
                    <div class="flex-1">
                        <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider block mb-0.5">Catatan Kader Saat Pengukuran:</span>
                        <p class="text-[12.5px] font-medium text-slate-800 leading-relaxed">{{ $measurement['catatan_kader'] }}</p>
                    </div>
                </div>
            @endif

            @if(isset($measurement['status_validasi']) && $measurement['status_validasi'] === 'rejected')
                @if(auth()->check() && auth()->user()->role === 'kader')
                    <!-- ── ADVANCED REVISION COMMAND CARD FOR KADER ── -->
                    <div class="mt-3.5 bg-gradient-to-br from-rose-50/90 via-rose-50/50 to-amber-50/40 border border-rose-200 rounded-2xl p-4 sm:p-5 relative overflow-hidden shadow-xs">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                            <div class="flex items-start gap-3 min-w-0">
                                <div class="w-9 h-9 rounded-xl bg-rose-100 border border-rose-200 text-rose-600 flex items-center justify-center shrink-0 shadow-2xs">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                    </svg>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2 mb-1">
                                        <span class="text-[11px] font-bold uppercase tracking-wider text-rose-800 bg-rose-100/90 px-2 py-0.5 rounded-md border border-rose-200">
                                            Arahan Petugas Gizi Puskesmas
                                        </span>
                                    </div>
                                    <div class="p-3 bg-white/90 border border-rose-100 rounded-xl mt-1.5 shadow-2xs">
                                        <p class="text-[13px] text-rose-950 font-semibold leading-relaxed">
                                            "{{ $measurement['catatan_validator'] ?? 'Terdapat anomali data ukur. Mohon verifikasi atau timbang ulang balita.' }}"
                                        </p>
                                    </div>
                                    
                                    {{-- SOP Panduan Tindakan Kader --}}
                                    <div class="mt-2.5 flex items-center gap-2 text-[11.5px] text-slate-600 font-medium">
                                        <span class="text-rose-600 font-bold">Langkah Kader:</span>
                                        <span>Timbang ulang balita atau koreksi salah ketik, lalu klik tombol perbaikan.</span>
                                    </div>
                                </div>
                            </div>

                            {{-- Direct Action Button --}}
                            <div class="shrink-0 flex sm:flex-col justify-end">
                                <button type="button" 
                                        onclick="document.getElementById('editModal-{{ $measurement['id'] }}').classList.remove('hidden')" 
                                        class="w-full sm:w-auto px-5 py-2.5 bg-rose-600 hover:bg-rose-700 active:scale-[0.99] text-white text-xs font-bold rounded-xl shadow-sm hover:shadow transition-all flex items-center justify-center gap-2 cursor-pointer">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                    <span>Perbaiki Data Ukur</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- ── REFINED REVISION MODAL POPUP ── -->
                    <div id="editModal-{{ $measurement['id'] }}" class="fixed inset-0 z-[110] hidden opacity-100 transition-opacity duration-300">
                        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-xs" onclick="document.getElementById('editModal-{{ $measurement['id'] }}').classList.add('hidden')"></div>
                        <div class="absolute inset-0 flex items-center justify-center p-4 md:p-6 pointer-events-none">
                            <div class="w-full max-w-lg bg-white rounded-2xl sm:rounded-3xl shadow-2xl border border-slate-200 flex flex-col pointer-events-auto overflow-hidden">
                                
                                {{-- Modal Header --}}
                                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/80">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-lg bg-rose-50 border border-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" /></svg>
                                        </div>
                                        <div>
                                            <h3 class="text-base font-bold tracking-tight text-slate-800">Perbaikan Data Pengukuran</h3>
                                            <p class="text-[11px] text-slate-500 font-medium">Revisi hasil penimbangan dan kirim ulang ke Puskesmas</p>
                                        </div>
                                    </div>
                                    <button type="button" onclick="document.getElementById('editModal-{{ $measurement['id'] }}').classList.add('hidden')" class="w-8 h-8 rounded-full bg-slate-200/70 hover:bg-slate-200 text-slate-500 hover:text-slate-800 flex items-center justify-center transition-colors cursor-pointer shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                                    </button>
                                </div>
                                
                                {{-- Form Body --}}
                                <div class="p-6 overflow-y-auto max-h-[75vh]">
                                    {{-- Catatan Puskesmas Reminder --}}
                                    <div class="mb-4 p-3 bg-rose-50/80 border border-rose-200/80 rounded-xl text-xs">
                                        <span class="text-[10.5px] font-bold text-rose-800 uppercase tracking-wider block mb-0.5">Catatan Puskesmas:</span>
                                        <p class="text-rose-900 font-semibold">{{ $measurement['catatan_validator'] ?? 'Periksa kembali angka penimbangan.' }}</p>
                                    </div>

                                    <form action="{{ route('pengukuran.update', $measurement['id']) }}" method="POST" class="space-y-4 text-xs">
                                        @csrf
                                        @method('PUT')
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                                            <div class="flex flex-col gap-1.5 sm:col-span-2">
                                                <label class="text-xs font-semibold text-slate-700">Tanggal Pengukuran <span class="text-rose-500">*</span></label>
                                                <input type="date" name="tanggal_ukur" value="{{ $measurement['raw_date'] ?? '' }}" required class="w-full h-11 bg-slate-50/80 border border-slate-200 focus:bg-white focus:border-teal-600 focus:ring-3 focus:ring-teal-500/15 rounded-xl px-3.5 text-xs sm:text-sm font-semibold text-slate-800 transition-all outline-none">
                                            </div>
                                            
                                            <div class="flex flex-col gap-1.5">
                                                <label class="text-xs font-semibold text-slate-700">Berat Badan (kg) <span class="text-rose-500">*</span></label>
                                                <input type="text" inputmode="decimal" name="berat_badan" value="{{ $measurement['weight'] }}" required placeholder="Contoh: 7.90" class="w-full h-11 bg-slate-50/80 border border-slate-200 focus:bg-white focus:border-teal-600 focus:ring-3 focus:ring-teal-500/15 rounded-xl px-3.5 text-xs sm:text-sm font-semibold text-slate-800 transition-all outline-none">
                                            </div>
                                            
                                            <div class="flex flex-col gap-1.5">
                                                <label class="text-xs font-semibold text-slate-700">Tinggi Badan (cm) <span class="text-rose-500">*</span></label>
                                                <input type="text" inputmode="decimal" name="tinggi_badan" value="{{ $measurement['height'] }}" required placeholder="Contoh: 68.7" class="w-full h-11 bg-slate-50/80 border border-slate-200 focus:bg-white focus:border-teal-600 focus:ring-3 focus:ring-teal-500/15 rounded-xl px-3.5 text-xs sm:text-sm font-semibold text-slate-800 transition-all outline-none">
                                            </div>

                                            <div class="flex flex-col gap-1.5 sm:col-span-2">
                                                <label class="text-xs font-semibold text-slate-700">Lingkar Kepala (cm) <span class="text-slate-400 font-normal">(Opsional)</span></label>
                                                <input type="text" inputmode="decimal" name="lingkar_kepala" value="{{ $measurement['head_circ'] ?? '' }}" placeholder="Contoh: 42.5" class="w-full h-11 bg-slate-50/80 border border-slate-200 focus:bg-white focus:border-teal-600 focus:ring-3 focus:ring-teal-500/15 rounded-xl px-3.5 text-xs sm:text-sm font-semibold text-slate-800 transition-all outline-none">
                                            </div>

                                            <div class="flex flex-col gap-1.5 sm:col-span-2">
                                                <label class="text-xs font-semibold text-slate-700">Catatan Perbaikan Kader <span class="text-slate-400 font-normal">(Tanggapan untuk Puskesmas)</span></label>
                                                <textarea name="catatan_kader" rows="2" placeholder="Contoh: Sudah ditimbang ulang di Posyandu dengan timbangan digital, berat valid." class="w-full bg-slate-50/80 border border-slate-200 focus:bg-white focus:border-teal-600 focus:ring-3 focus:ring-teal-500/15 rounded-xl px-3.5 py-2.5 text-xs sm:text-sm font-medium text-slate-800 transition-all outline-none resize-none">{{ $measurement['catatan_kader'] ?? '' }}</textarea>
                                            </div>
                                        </div>
                                        
                                        <div class="mt-6 pt-3 border-t border-slate-100 flex justify-end gap-2.5">
                                            <button type="button" onclick="document.getElementById('editModal-{{ $measurement['id'] }}').classList.add('hidden')" class="h-10 sm:h-11 px-5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 font-semibold text-xs transition-colors cursor-pointer">Batal</button>
                                            <button type="submit" class="h-10 sm:h-11 px-6 bg-rose-600 hover:bg-rose-700 text-white rounded-xl font-semibold text-xs shadow-xs hover:shadow-sm transition-all focus:outline-none cursor-pointer flex items-center gap-1.5">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                                <span>Simpan & Kirim Perbaikan</span>
                                            </button>
                                        </div>
                                    </form>
                                </div>

                            </div>
                        </div>
                    </div>
                @else
                    <!-- ── PUSKESMAS/AHLI GIZI VIEW FOR REJECTED MEASUREMENT ── -->
                    <div class="mt-3.5 bg-rose-50/80 border border-rose-200/80 rounded-2xl p-4 sm:p-5 relative overflow-hidden shadow-xs">
                        <div class="flex items-start gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-xl bg-white border border-rose-200 text-rose-600 flex items-center justify-center shrink-0 shadow-2xs">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[11px] font-bold uppercase tracking-wider text-rose-800 bg-rose-100/90 px-2 py-0.5 rounded-md border border-rose-200">
                                        Menunggu Revisi Kader
                                    </span>
                                </div>
                                <div class="p-3 bg-white/90 border border-rose-100 rounded-xl mt-1.5 shadow-2xs">
                                    <p class="text-[13px] text-rose-950 font-semibold leading-relaxed">
                                        "{{ $measurement['catatan_validator'] ?? 'Terdapat anomali data ukur. Mohon verifikasi atau timbang ulang balita.' }}"
                                    </p>
                                </div>
                                <div class="mt-2.5 flex items-center gap-2 text-[11.5px] text-slate-600 font-medium">
                                    <span class="text-rose-600 font-bold">Status:</span>
                                    <span>Menunggu Kader menimbang ulang balita atau mengoreksi data.</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif

        </div>
    </div>
</div>
