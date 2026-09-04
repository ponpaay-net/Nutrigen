@extends('layouts.puskesmas')
@section('page-title', 'Rekam Medis Balita - ' . $childName)
@section('content')

@php
    $statusTypeColorMap = [
        'success' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200', 'icon' => 'ph-check-circle', 'solid' => 'bg-emerald-500'],
        'warning' => ['bg' => 'bg-amber-50',  'text' => 'text-amber-700',  'border' => 'border-amber-200',  'icon' => 'ph-warning', 'solid' => 'bg-amber-500'],
        'danger'  => ['bg' => 'bg-rose-50',   'text' => 'text-rose-700',   'border' => 'border-rose-200',   'icon' => 'ph-warning-circle', 'solid' => 'bg-rose-500'],
    ];
    $primaryStatus = $statusTypeColorMap[$status_type] ?? $statusTypeColorMap['success'];
    $isBoy = $gender === 'Laki-laki';
    $initials = collect(explode(' ', $childName))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
    
    // Theme colors based on gender
    $themeColor = $isBoy ? 'blue' : 'rose';
@endphp

<div class="bg-slate-50/50 min-h-screen pb-16 font-sans">
    
    <!-- Header -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-8 pb-6 flex items-center justify-between">
        <a href="{{ route('puskesmas.balita') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-slate-900 transition-colors font-medium text-sm">
            <i class="ph-bold ph-arrow-left"></i>
            Kembali ke Direktori
        </a>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Added min-w-0 to prevent grid blowout when sidebar opens -->
        <div class="grid grid-cols-1 xl:grid-cols-12 gap-6 lg:gap-8 items-start min-w-0">
            
            <!-- LEFT COLUMN (4/12) -->
            <div class="xl:col-span-4 space-y-6 min-w-0">
                
                <!-- Main Profile Card -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col relative">
                    <!-- Top color accent -->
                    <div class="h-2 w-full {{ $isBoy ? 'bg-blue-500' : 'bg-rose-500' }}"></div>
                    
                    <div class="p-6 flex flex-col items-center text-center border-b border-slate-100">
                        <div class="w-20 h-20 rounded-full {{ $isBoy ? 'bg-blue-50 text-blue-600' : 'bg-rose-50 text-rose-600' }} flex items-center justify-center text-2xl font-bold mb-4 ring-4 ring-slate-50">
                            {{ strtoupper($initials) }}
                        </div>
                        <h1 class="text-xl font-bold text-slate-900 mb-1 leading-tight">{{ $childName }}</h1>
                        <div class="flex items-center gap-2 text-[13px] font-medium">
                            <span class="text-slate-500">{{ $gender }}</span>
                            <span class="text-slate-300">•</span>
                            <span class="text-slate-500">{{ $age }}</span>
                        </div>
                        
                        <!-- Status Badge -->
                        <div class="mt-5 inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full {{ $primaryStatus['bg'] }} {{ $primaryStatus['border'] }} {{ $primaryStatus['text'] }} border">
                            <i class="ph-fill {{ $primaryStatus['icon'] }} text-base"></i>
                            <span class="text-xs font-bold uppercase tracking-widest">{{ $status }}</span>
                        </div>
                    </div>

                    <div class="p-6 bg-slate-50 flex flex-col gap-4">
                        <div class="flex justify-between items-start gap-2">
                            <span class="text-xs font-medium text-slate-500 uppercase tracking-widest shrink-0 mt-0.5">NIK Anak</span>
                            <span class="text-sm font-semibold text-slate-900 font-mono text-right break-all">{{ $nik }}</span>
                        </div>
                        <div class="flex justify-between items-start gap-2">
                            <span class="text-xs font-medium text-slate-500 uppercase tracking-widest shrink-0 mt-0.5">Tgl Lahir</span>
                            <span class="text-sm font-semibold text-slate-900 text-right">{{ $birthDate }}</span>
                        </div>
                        <div class="flex justify-between items-start gap-2">
                            <span class="text-xs font-medium text-slate-500 uppercase tracking-widest shrink-0 mt-0.5">Posyandu</span>
                            <span class="text-sm font-semibold text-teal-700 flex items-start gap-1.5 text-right">
                                <i class="ph-fill ph-house-line mt-0.5 shrink-0"></i> 
                                <span class="leading-tight">{{ $posyanduName }}</span>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Family & Demographics -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-sm font-bold text-slate-900 flex items-center gap-2 mb-5">
                        <i class="ph-bold ph-users text-indigo-500"></i> Informasi Wali
                    </h3>
                    
                    <div class="space-y-4">
                        <div class="flex flex-col">
                            <span class="text-xs font-medium text-slate-500 mb-1">Nama Ibu</span>
                            <span class="text-sm font-semibold text-slate-900">{{ $motherName }} <span class="text-slate-500 font-normal">({{ $motherPhone }})</span></span>
                        </div>
                        @if($fatherName)
                        <div class="flex flex-col">
                            <span class="text-xs font-medium text-slate-500 mb-1">Nama Ayah</span>
                            <span class="text-sm font-semibold text-slate-900">{{ $fatherName }}</span>
                        </div>
                        @endif
                        <div class="flex flex-col">
                            <span class="text-xs font-medium text-slate-500 mb-1">Domisili</span>
                            <span class="text-sm font-semibold text-slate-900 leading-snug">{{ $address }}<br><span class="text-slate-500 font-normal mt-0.5 block">Kec. {{ $addressSub ?: '-' }}</span></span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- RIGHT COLUMN (8/12) -->
            <div class="xl:col-span-8 space-y-6 min-w-0">
                
                @if(!empty($latestMeasure['catatan_validator']))
                <div class="bg-amber-50 border border-amber-200 rounded-2xl p-5 flex items-start gap-4 shadow-sm">
                    <i class="ph-fill ph-warning-circle text-xl text-amber-500 shrink-0 mt-0.5"></i>
                    <div class="flex flex-col min-w-0">
                        <span class="text-sm font-bold text-amber-900">Catatan Ahli Gizi</span>
                        <p class="text-sm text-amber-800 mt-1 leading-relaxed">{{ $latestMeasure['catatan_validator'] }}</p>
                    </div>
                </div>
                @endif

                <!-- KPI Cards -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 sm:gap-6">
                    
                    <!-- Berat Badan -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col hover:border-blue-200 transition-colors">
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-10 h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center border border-blue-100">
                                <i class="ph-bold ph-scales text-lg"></i>
                            </div>
                            @if(!empty($latestMeasure['weight_trend']) && $latestMeasure['weight_trend'] > 0)
                                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-1 rounded-md border border-emerald-200">+{{ $latestMeasure['weight_trend'] }}</span>
                            @elseif(!empty($latestMeasure['weight_trend']) && $latestMeasure['weight_trend'] < 0)
                                <span class="text-[11px] font-bold text-rose-700 bg-rose-50 px-2 py-1 rounded-md border border-rose-200">{{ $latestMeasure['weight_trend'] }}</span>
                            @endif
                        </div>
                        <span class="text-xs font-medium text-slate-500 mb-1">Berat Badan</span>
                        <div class="flex items-baseline gap-1.5 mb-4">
                            <span class="text-3xl font-bold text-slate-900 tracking-tight">{{ $latestMeasure['weight'] ?? ($birthWeight ?: '-') }}</span>
                            <span class="text-sm font-medium text-slate-400">kg</span>
                        </div>
                        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-medium text-slate-400 uppercase tracking-widest">Z-Score (BB/U)</span>
                            <span class="text-xs font-semibold text-slate-700">{{ isset($latestMeasure['z_score_bbu']) ? $latestMeasure['z_score_bbu'] . ' SD' : '-' }}</span>
                        </div>
                    </div>

                    <!-- Tinggi Badan -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col hover:border-purple-200 transition-colors">
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-10 h-10 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center border border-purple-100">
                                <i class="ph-bold ph-ruler text-lg"></i>
                            </div>
                            @if(!empty($latestMeasure['height_trend']) && $latestMeasure['height_trend'] > 0)
                                <span class="text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-1 rounded-md border border-emerald-200">+{{ $latestMeasure['height_trend'] }}</span>
                            @endif
                        </div>
                        <span class="text-xs font-medium text-slate-500 mb-1">Tinggi / Panjang</span>
                        <div class="flex items-baseline gap-1.5 mb-4">
                            <span class="text-3xl font-bold text-slate-900 tracking-tight">{{ $latestMeasure['height'] ?? ($birthLength ?: '-') }}</span>
                            <span class="text-sm font-medium text-slate-400">cm</span>
                        </div>
                        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-medium text-slate-400 uppercase tracking-widest">Z-Score (TB/U)</span>
                            <span class="text-xs font-semibold text-slate-700">{{ isset($latestMeasure['z_score_tbu']) ? $latestMeasure['z_score_tbu'] . ' SD' : '-' }}</span>
                        </div>
                    </div>

                    <!-- Lingkar Kepala -->
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col hover:border-orange-200 transition-colors">
                        <div class="flex items-center justify-between mb-5">
                            <div class="w-10 h-10 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center border border-orange-100">
                                <i class="ph-bold ph-smiley text-lg"></i>
                            </div>
                        </div>
                        <span class="text-xs font-medium text-slate-500 mb-1">Lingkar Kepala</span>
                        <div class="flex items-baseline gap-1.5 mb-4">
                            <span class="text-3xl font-bold text-slate-900 tracking-tight">{{ $latestMeasure['head_circ'] ?? ($birthHeadCirc ?: '-') }}</span>
                            <span class="text-sm font-medium text-slate-400">cm</span>
                        </div>
                        <div class="mt-auto pt-4 border-t border-slate-100 flex items-center justify-between">
                            <span class="text-[10px] font-medium text-slate-400 uppercase tracking-widest">Status Data</span>
                            <span class="text-xs font-semibold text-slate-700">{{ !empty($latestMeasure['head_circ']) ? 'Tercatat' : '-' }}</span>
                        </div>
                    </div>
                </div>

                <!-- Table Container -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col min-w-0">
                    <div class="p-5 sm:p-6 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-slate-50 text-slate-600 border border-slate-200 flex items-center justify-center">
                                <i class="ph-bold ph-list-numbers"></i>
                            </div>
                            <h3 class="text-base font-bold text-slate-900">Riwayat Pengukuran</h3>
                        </div>
                        <span class="text-xs font-medium text-slate-500 bg-slate-50 px-3 py-1.5 rounded-md border border-slate-100">{{ count($measurements) }} Catatan Total</span>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left min-w-[700px]">
                            <thead class="bg-slate-50/80 border-b border-slate-200">
                                <tr>
                                    <th class="px-6 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Tanggal & Umur</th>
                                    <th class="px-6 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Klinis (BB/TB)</th>
                                    <th class="px-6 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Z-Score</th>
                                    <th class="px-6 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Status Gizi</th>
                                    <th class="px-6 py-4 text-[11px] font-semibold text-slate-500 uppercase tracking-wider text-right">Validasi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($measurements as $m)
                                    @php
                                        $sColor = match($m['status_type']) {
                                            'danger' => 'text-rose-700 bg-rose-50 border-rose-200',
                                            'warning' => 'text-amber-700 bg-amber-50 border-amber-200',
                                            default => 'text-emerald-700 bg-emerald-50 border-emerald-200'
                                        };
                                        $vStatus = $m['status_validasi'];
                                        $vColor = match($vStatus) {
                                            'valid' => 'text-teal-700',
                                            'ditolak' => 'text-rose-700',
                                            default => 'text-slate-500'
                                        };
                                        $vIcon = match($vStatus) {
                                            'valid' => 'ph-check-circle',
                                            'ditolak' => 'ph-x-circle',
                                            default => 'ph-clock'
                                        };
                                        $vLabel = match($vStatus) {
                                            'valid' => 'Valid',
                                            'ditolak' => 'Ditolak',
                                            default => 'Pending'
                                        };
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-4 align-top">
                                            <div class="flex flex-col">
                                                <span class="text-sm font-semibold text-slate-900">{{ $m['date'] }}</span>
                                                <span class="text-[13px] text-slate-500 mt-0.5">{{ $m['age_at_measure'] }}</span>
                                                @if($m['asi_eksklusif'])
                                                    <span class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mt-1.5 flex items-center gap-1"><i class="ph-fill ph-drop"></i> ASI Eksklusif</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            <div class="flex flex-col gap-1">
                                                <span class="text-sm font-bold text-slate-800">{{ $m['weight'] ?? '-' }} <span class="text-slate-400 font-normal">kg</span></span>
                                                <span class="text-sm font-bold text-slate-800">{{ $m['height'] ?? '-' }} <span class="text-slate-400 font-normal">cm</span></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            <div class="flex flex-col gap-1.5">
                                                <span class="text-xs font-medium text-slate-500">BB/U: <span class="text-slate-800 font-semibold">{{ $m['z_score_bbu'] ?? '-' }}</span></span>
                                                <span class="text-xs font-medium text-slate-500">TB/U: <span class="text-slate-800 font-semibold">{{ $m['z_score_tbu'] ?? '-' }}</span></span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 align-top">
                                            <span class="inline-flex px-2 py-1 rounded-md text-[10px] font-bold uppercase tracking-widest border {{ $sColor }}">
                                                {{ $m['status'] }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 align-top text-right">
                                            <span class="inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-widest {{ $vColor }}">
                                                <i class="ph-fill {{ $vIcon }} text-sm"></i> {{ $vLabel }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-12 text-center flex flex-col items-center justify-center">
                                            <div class="w-12 h-12 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mb-3 border border-slate-100">
                                                <i class="ph-bold ph-file-dashed text-2xl"></i>
                                            </div>
                                            <span class="text-sm font-bold text-slate-900">Belum Ada Data</span>
                                            <span class="text-sm text-slate-500 mt-1">Belum ada riwayat pengukuran yang diinput.</span>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection
