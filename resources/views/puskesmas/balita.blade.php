@extends('layouts.puskesmas')
@section('page-title', 'Data Balita')
@section('content')

@php
    $q           = $filters['q'] ?? '';
    $posyanduId  = $filters['posyandu_id'] ?? '';
    $statusGizi  = $filters['status_gizi'] ?? '';

    // KPI counts provided by backend
    $totalBalita = $kpis['total'] ?? 0;
    $totalStunting = $kpis['stunting'] ?? 0;
    $totalRisiko   = $kpis['risiko'] ?? 0;
@endphp

<!-- Main Layout Wrapper -->
<div class="space-y-8 flex flex-col">

    <!-- Header Area (No Cards for KPI, just Inline Large Text) -->
    <div class="flex flex-col xl:flex-row xl:items-start justify-between gap-8 relative z-10">
        <div class="flex-1">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 rounded-xl bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-600 shadow-sm">
                    <i class="ph-bold ph-baby text-2xl"></i>
                </div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Direktori Balita</h1>
            </div>
            <p class="text-[14px] text-slate-500 font-medium max-w-2xl leading-relaxed">Rekam medis dan riwayat pertumbuhan balita di seluruh posyandu wilayah Puskesmas. Gunakan fitur pencarian untuk menemukan data anak spesifik dengan cepat.</p>
        </div>

        <!-- Inline KPI Metrics (No Cards) -->
        <div class="flex items-center gap-10 shrink-0 mt-2 xl:mt-0">
            <div class="flex flex-col">
                <div class="flex items-center gap-2 mb-1">
                    <i class="ph-bold ph-users text-slate-400"></i>
                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest">Total Balita</span>
                </div>
                <span class="text-3xl font-black text-slate-800 leading-none">{{ number_format($totalBalita) }}</span>
            </div>
            
            <div class="flex flex-col">
                <div class="flex items-center gap-2 mb-1">
                    <i class="ph-bold ph-warning-circle text-rose-500"></i>
                    <span class="text-[11px] font-bold text-rose-600 uppercase tracking-widest">Stunting</span>
                </div>
                <span class="text-3xl font-black text-rose-700 leading-none">{{ number_format($totalStunting) }}</span>
            </div>

            <div class="flex flex-col">
                <div class="flex items-center gap-2 mb-1">
                    <i class="ph-bold ph-warning text-amber-500"></i>
                    <span class="text-[11px] font-bold text-amber-600 uppercase tracking-widest">Risiko</span>
                </div>
                <span class="text-3xl font-black text-amber-700 leading-none">{{ number_format($totalRisiko) }}</span>
            </div>
        </div>
    </div>

    <!-- Main Card Container -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm flex flex-col flex-1 overflow-hidden">
        
        <!-- Search and Filters Bar -->
        <div class="px-6 py-6 border-b border-slate-100 relative z-20 bg-slate-50/30">
            <form action="{{ route('puskesmas.balita') }}" method="GET" id="filterForm" class="flex flex-col gap-5">
                
                <!-- Search Bar (Now highly prominent at the top) -->
                <div class="relative w-full">
                    <i class="ph-bold ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl"></i>
                    <input type="text" name="q" value="{{ $q }}" id="searchInput"
                        placeholder="Cari nama balita atau NIK untuk melihat rekam medis..."
                        class="w-full pl-12 pr-4 py-3.5 rounded-xl bg-white border border-slate-300 text-slate-800 text-[14px] font-semibold placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 shadow-sm transition-all">
                </div>

                <!-- Secondary Filters -->
                <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
                    
                    <!-- Segmented Control for Status -->
                    <div class="inline-flex bg-slate-100 p-1.5 rounded-xl border border-slate-200/60 w-full lg:w-auto overflow-x-auto hide-scrollbar">
                        <input type="hidden" name="status_gizi" value="{{ $statusGizi }}" id="hiddenStatusGizi">
                        
                        @php
                            $statuses = [
                                '' => ['label' => 'Semua Data', 'icon' => 'ph-list-bullets'],
                                'stunting' => ['label' => 'Stunting', 'icon' => 'ph-warning-circle', 'color' => 'rose'],
                                'risiko' => ['label' => 'Risiko', 'icon' => 'ph-warning', 'color' => 'amber'],
                                'normal' => ['label' => 'Normal', 'icon' => 'ph-check-circle', 'color' => 'emerald'],
                            ];
                        @endphp
                        
                        @foreach($statuses as $val => $s)
                            @php
                                $isActive = $statusGizi === $val;
                                $color = $s['color'] ?? 'teal';
                                $activeClass = $isActive 
                                    ? "bg-white text-{$color}-700 shadow ring-1 ring-slate-900/5" 
                                    : "text-slate-500 hover:text-slate-800 hover:bg-slate-200/50";
                            @endphp
                            <button type="button" 
                                    onclick="document.getElementById('hiddenStatusGizi').value = '{{ $val }}'; document.getElementById('filterForm').submit();"
                                    class="px-5 py-2.5 rounded-lg text-[13px] font-bold flex items-center justify-center gap-2.5 whitespace-nowrap transition-all {{ $activeClass }}">
                                <i class="ph-bold {{ $s['icon'] }} {{ $isActive && $color !== 'teal' ? "text-{$color}-500" : "text-lg" }}"></i>
                                {{ $s['label'] }}
                            </button>
                        @endforeach
                    </div>

                    <!-- Alpine Dropdown for Posyandu -->
                    <div x-data="{ 
                            open: false,
                            selectPosyandu(id) {
                                document.getElementById('hiddenPosyanduId').value = id;
                                document.getElementById('filterForm').submit();
                            }
                        }" 
                        class="relative w-full lg:w-72 shrink-0" 
                        @click.outside="open = false">
                        
                        <input type="hidden" name="posyandu_id" id="hiddenPosyanduId" value="{{ $posyanduId }}">
                        
                        <button type="button" @click="open = !open" 
                            class="flex items-center justify-between w-full px-4 py-3 bg-white border border-slate-300 rounded-xl text-[13px] font-bold text-slate-700 shadow-sm hover:border-slate-400 focus:outline-none focus:ring-4 focus:ring-teal-500/10 transition-all">
                            <div class="flex items-center gap-2.5 truncate">
                                <i class="ph-fill ph-house-line text-slate-400 text-lg"></i>
                                <span class="truncate">
                                    @if($posyanduId)
                                        {{ collect($posyandus)->firstWhere('id', (int)$posyanduId)['nama'] ?? 'Semua Posyandu' }}
                                    @else
                                        Semua Posyandu
                                    @endif
                                </span>
                            </div>
                            <i class="ph-bold ph-caret-down text-slate-400 text-xs"></i>
                        </button>

                        <div x-show="open" 
                            x-transition:enter="transition ease-out duration-100"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-75"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            style="display: none;"
                            class="absolute right-0 top-full mt-2 w-full bg-white border border-slate-200 rounded-xl shadow-xl overflow-hidden z-50 max-h-[300px] overflow-y-auto">
                            
                            <button type="button" @click="selectPosyandu('')" class="w-full text-left px-5 py-3 text-[13px] hover:bg-slate-50 transition-colors {{ !$posyanduId ? 'font-black text-teal-700 bg-teal-50/50' : 'font-semibold text-slate-700' }}">
                                Semua Posyandu
                            </button>
                            
                            @foreach($posyandus as $ps)
                                <button type="button" @click="selectPosyandu('{{ $ps['id'] }}')" class="w-full text-left px-5 py-3 text-[13px] hover:bg-slate-50 transition-colors border-t border-slate-100 {{ (string)$posyanduId === (string)$ps['id'] ? 'font-black text-teal-700 bg-teal-50/50' : 'font-semibold text-slate-700' }}">
                                    {{ $ps['nama'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Data Table Area -->
        <div class="overflow-x-auto bg-white relative z-0">
            @if($balitas->count() > 0)
                <table class="w-full text-left border-collapse min-w-[950px]">
                    <thead class="bg-slate-50/90 sticky top-0 z-10 backdrop-blur-md shadow-[0_1px_0_rgba(203,213,225,1)]">
                        <tr>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Anak & Identitas</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest">Posyandu</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Status Gizi (AI)</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-center">Riwayat Terakhir</th>
                            <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-widest text-right">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($balitas as $child)
                            @php
                                $statusColor = 'emerald';
                                $statusBg = 'bg-emerald-50';
                                $statusBorder = 'border-emerald-200';
                                $statusText = 'text-emerald-700';

                                if (($child['statusType'] ?? '') === 'warning') {
                                    $statusColor = 'amber';
                                    $statusBg = 'bg-amber-50';
                                    $statusBorder = 'border-amber-200/80';
                                    $statusText = 'text-amber-700';
                                }
                                if (($child['statusType'] ?? '') === 'danger') {
                                    $statusColor = 'rose';
                                    $statusBg = 'bg-rose-50';
                                    $statusBorder = 'border-rose-200/80';
                                    $statusText = 'text-rose-700';
                                }

                                $isGirl = in_array(strtolower($child['jenis_kelamin'] ?? ''), ['p', 'perempuan', 'female']);
                                $avatarBg = $isGirl ? 'bg-pink-100 text-pink-600 border-pink-200' : 'bg-sky-100 text-sky-600 border-sky-200';
                                $genderTextClass = $isGirl ? 'text-pink-600' : 'text-sky-600';
                                
                                $latest = count($child['pengukurans'] ?? []) > 0 ? $child['pengukurans'][0] : null;
                                $initials = collect(explode(' ', $child['nama']))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
                            @endphp
                            <tr class="hover:bg-slate-50/80 transition-all group cursor-pointer" onclick="window.location='{{ route('puskesmas.balita.show', $child['id']) }}'">
                                
                                <!-- Col 1: Nama -->
                                <td class="px-6 py-5 border-l-4 border-transparent group-hover:border-teal-500 transition-colors">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full {{ $avatarBg }} flex items-center justify-center font-black text-sm shrink-0 border uppercase shadow-sm">
                                            {{ $initials }}
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <h3 class="text-sm font-bold text-slate-900 group-hover:text-teal-700 transition-colors truncate" title="{{ $child['nama'] }}">{{ $child['nama'] }}</h3>
                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                <span class="text-[12px] font-bold {{ $genderTextClass }}">{{ $isGirl ? 'Perempuan' : 'Laki-laki' }}</span>
                                                @if(!empty($child['nik']))
                                                    <span class="text-slate-300">&bull;</span>
                                                    <span class="text-[12px] font-medium text-slate-600">NIK: {{ $child['nik'] }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>

                                <!-- Col 2: Posyandu -->
                                <td class="px-6 py-5">
                                    <span class="text-sm font-semibold text-slate-700 block truncate">{{ $child['posyandu']['nama'] ?? '-' }}</span>
                                </td>

                                <!-- Col 3: Status Gizi -->
                                <td class="px-6 py-5 text-center">
                                    <span class="inline-flex items-center gap-1.5 text-[11px] font-black uppercase tracking-widest {{ $statusText }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ str_replace('text-', 'bg-', $statusText) }}"></span>
                                        {{ $child['statusLabel'] ?? 'Normal' }}
                                    </span>
                                </td>

                                <!-- Col 4: Pengukuran Terakhir -->
                                <td class="px-6 py-5 text-center">
                                    @if($latest)
                                        <div class="flex flex-col items-center">
                                            <span class="text-[13px] font-bold text-slate-800">{{ date('d M Y', strtotime($latest['created_at'])) }}</span>
                                            <span class="text-[12px] font-medium text-slate-600 mt-0.5">{{ $latest['berat_badan'] }}kg / {{ $latest['tinggi_badan'] }}cm</span>
                                        </div>
                                    @else
                                        <span class="text-[12px] font-medium text-slate-400 italic">Belum ada riwayat</span>
                                    @endif
                                </td>

                                <!-- Col 5: Aksi -->
                                <td class="px-6 py-5 text-right">
                                    <button class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-white border border-slate-200 text-slate-400 group-hover:border-teal-500 group-hover:text-teal-600 group-hover:bg-teal-50 transition-all shadow-sm">
                                        <i class="ph-bold ph-caret-right text-sm"></i>
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Pagination Area -->
                <div class="px-6 py-4 border-t border-slate-200 bg-slate-50">
                    {{ $balitas->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="flex flex-col items-center justify-center h-full min-h-[500px] text-center p-6 bg-slate-50/30">
                    <div class="w-20 h-20 rounded-full bg-white text-slate-300 flex items-center justify-center mb-5 border border-slate-200 shadow-sm">
                        <i class="ph-bold ph-magnifying-glass text-4xl"></i>
                    </div>
                    <h3 class="text-lg font-black text-slate-800 mb-1.5">Data Tidak Ditemukan</h3>
                    <p class="text-sm text-slate-500 max-w-sm mb-8 leading-relaxed">
                        Tidak ada balita yang cocok dengan filter atau kata kunci yang Anda masukkan. Silakan coba penyesuaian lain.
                    </p>
                    <a href="{{ route('puskesmas.balita') }}" class="px-5 py-2.5 rounded-lg bg-white border border-slate-300 text-slate-700 text-[13px] font-bold hover:bg-slate-50 transition-all shadow-sm flex items-center gap-2">
                        <i class="ph-bold ph-arrow-counter-clockwise text-sm"></i>
                        Atur Ulang Pencarian
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
