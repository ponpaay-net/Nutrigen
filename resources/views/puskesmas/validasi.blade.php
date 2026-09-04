@extends('layouts.puskesmas')
@section('page-title', 'Antrean Validasi Data')
@section('content')

@php
    $filters = [
        'tab'         => request('tab', 'pending'),
        'posyandu_id' => request('posyandu_id', ''),
    ];

    $c_pending  = $stats['pending']  ?? 0;
    $c_normal   = $stats['normal']   ?? 0;
    $c_anomali  = $stats['anomali']  ?? 0;
    $c_berisiko = $stats['berisiko'] ?? 0;
    $urgentCount = $c_berisiko + $c_anomali;

    $searchableItems = array_map(function($c) {
        return [
            'id' => $c['id'],
            'searchStr' => strtolower(($c['name'] ?? '') . ' ' . ($c['nik'] ?? '') . ' ' . ($c['kader'] ?? '') . ' ' . ($c['posyandu'] ?? ''))
        ];
    }, $children);
@endphp

<div x-data="validationQueue" class="space-y-6 pb-12 p-4 sm:p-6 lg:p-8">

    <!-- Page Header (Clinical, Contextual, Professional) -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-start gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-teal-50 border border-teal-200/80 text-teal-700 flex items-center justify-center shrink-0 shadow-sm mt-0.5">
                <i class="ph-bold ph-clipboard-text text-xl"></i>
            </div>
            <div>
                <div class="flex items-center gap-3">
                    <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Antrean Validasi Data</h1>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-teal-50 text-teal-800 border border-teal-200/80">
                        {{ $c_pending }} antrean aktif
                    </span>
                </div>
                <p class="text-sm text-slate-500 mt-1">
                    Tinjau antropometri balita dari posyandu dan verifikasi hasil analisis pertumbuhan.
                </p>
            </div>
        </div>

        <!-- Operational Status Badges -->
        <div class="flex items-center gap-3 shrink-0">
            @if($urgentCount > 0)
                <div class="flex items-center gap-2 px-3 py-1.5 bg-rose-50 border border-rose-200/80 rounded-lg text-xs font-semibold text-rose-800">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    <span>{{ $urgentCount }} Kasus Butuh Tindakan</span>
                </div>
            @endif
            <div class="flex items-center gap-2 px-3 py-1.5 bg-emerald-50/70 border border-emerald-200/80 rounded-lg text-xs font-semibold text-emerald-800">
                <i class="ph-bold ph-seal-check text-emerald-600 text-sm"></i>
                <span>{{ number_format($stats['valid'] ?? 0, 0, ',', '.') }} tervalidasi bulan ini</span>
            </div>
        </div>
    </div>

    {{-- Banner Link Portal Sukses (Clean Floating Notification) --}}
    @if(session('portal_link'))
        @php $portalLink = session('portal_link'); @endphp
        <div x-data="{ copied: false, show: true }" 
             x-show="show" 
             class="p-4 bg-emerald-50/90 border border-emerald-200 rounded-xl flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg bg-emerald-600 text-white flex items-center justify-center shrink-0">
                    <i class="ph-bold ph-check text-base"></i>
                </div>
                <div>
                    <h3 class="text-xs font-bold text-emerald-950">Validasi Pengukuran Berhasil Disimpan</h3>
                    <p class="text-xs text-emerald-800 mt-0.5">Tautan buku KIA digital siap dikirim ke Ibunda {{ $portalLink['child_name'] }}.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <input type="text" readonly value="{{ $portalLink['url'] }}" x-ref="portalUrl" class="w-56 text-xs bg-white border border-emerald-300 rounded-lg px-2.5 py-1.5 text-slate-700 focus:outline-none font-mono">
                <button @click="navigator.clipboard.writeText($refs.portalUrl.value); copied = true; setTimeout(() => copied = false, 2000)" 
                        class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors"
                        :class="copied ? 'bg-emerald-700 text-white' : 'bg-white border border-emerald-300 text-emerald-800 hover:bg-emerald-100'">
                    <span x-text="copied ? 'Tersalin' : 'Salin'">Salin</span>
                </button>
                @if(!empty($portalLink['wa_url']))
                    <a href="{{ $portalLink['wa_url'] }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-[#25D366] text-white text-xs font-semibold flex items-center gap-1.5 hover:bg-[#1DA851] transition-colors">
                        <i class="ph-bold ph-whatsapp-logo text-sm"></i>
                        <span>Kirim WA</span>
                    </a>
                @endif
                <button @click="show = false" class="p-1 text-emerald-600 hover:text-emerald-800">
                    <i class="ph-bold ph-x text-sm"></i>
                </button>
            </div>
        </div>
    @endif

    <!-- Main Workspace Container (Card Canvas) -->
    <div class="bg-white border border-slate-200/90 rounded-xl shadow-sm overflow-hidden">
        
        <!-- Filter Toolbar (Clean, Linear-Style Controls) -->
        <div class="p-4 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4 bg-white">
            
            <!-- Modern Filter Dropdown -->
            @php
                $tabs = [
                    'pending'  => ['label' => 'Semua Antrean', 'count' => $c_pending, 'icon' => 'ph-list-bullets', 'iconColor' => 'text-teal-600', 'badgeBg' => 'bg-teal-100', 'badgeText' => 'text-teal-700'],
                    'berisiko' => ['label' => 'Indikasi Stunting', 'count' => $c_berisiko, 'icon' => 'ph-warning', 'iconColor' => 'text-rose-600', 'badgeBg' => 'bg-rose-100', 'badgeText' => 'text-rose-700'],
                    'anomali'  => ['label' => 'Risiko Gizi', 'count' => $c_anomali, 'icon' => 'ph-warning-circle', 'iconColor' => 'text-amber-600', 'badgeBg' => 'bg-amber-100', 'badgeText' => 'text-amber-700'],
                    'normal'   => ['label' => 'Normal', 'count' => $c_normal, 'icon' => 'ph-check-circle', 'iconColor' => 'text-emerald-600', 'badgeBg' => 'bg-emerald-100', 'badgeText' => 'text-emerald-700'],
                ];
                $activeTabId = array_key_exists($filters['tab'], $tabs) ? $filters['tab'] : 'pending';
                $activeTab = $tabs[$activeTabId];
            @endphp
            
            <div x-data="{ openFilter: false }" class="relative w-full lg:w-auto min-w-[220px] shrink-0" @click.outside="openFilter = false">
                <button type="button" @click="openFilter = !openFilter" 
                        class="flex items-center justify-between w-full px-3.5 py-2 min-h-[38px] bg-white border border-slate-200 rounded-lg text-xs font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all shadow-sm">
                    <div class="flex items-center gap-2">
                        <i class="ph-bold {{ $activeTab['icon'] }} {{ $activeTab['iconColor'] }} text-sm"></i>
                        <span>{{ $activeTab['label'] }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-slate-100 text-slate-600 font-black">
                            {{ $activeTab['count'] }}
                        </span>
                        <i class="ph-bold ph-caret-down text-slate-400"></i>
                    </div>
                </button>

                <div x-show="openFilter" 
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     style="display: none;"
                     class="absolute left-0 top-full mt-1.5 w-full bg-white border border-slate-200 rounded-lg shadow-xl z-30 py-1.5 min-w-[240px]">
                    
                    <div class="px-3 py-1.5 mb-1 border-b border-slate-100">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kategori Validasi</span>
                    </div>

                    @foreach ($tabs as $id => $t)
                        <a href="?tab={{ $id }}&posyandu_id={{ urlencode($filters['posyandu_id']) }}"
                           class="flex items-center justify-between w-full px-3 py-2 text-xs transition-colors hover:bg-slate-50
                           {{ $activeTabId === $id ? 'bg-slate-50/80 font-bold text-slate-900' : 'text-slate-600 font-medium' }}">
                            <div class="flex items-center gap-2.5">
                                <i class="{{ $activeTabId === $id ? 'ph-fill' : 'ph-bold' }} {{ $t['icon'] }} {{ $activeTabId === $id ? $t['iconColor'] : 'text-slate-400' }} text-sm"></i>
                                <span>{{ $t['label'] }}</span>
                            </div>
                            <span class="text-[10px] px-1.5 py-0.5 rounded font-black {{ $activeTabId === $id ? $t['badgeBg'] . ' ' . $t['badgeText'] : 'bg-slate-100 text-slate-500' }}">
                                {{ $t['count'] }}
                            </span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Search & Posyandu Selector -->
            <div class="flex items-center gap-2.5 w-full lg:w-auto">
                <!-- Search Feedback Counter Badge -->
                <span x-show="search.trim().length > 0" 
                      style="display: none;"
                      class="text-[11px] font-bold text-teal-800 bg-teal-50 px-2 py-1 rounded-md border border-teal-200/80 shrink-0">
                    <span x-text="matchCount"></span> ditemukan
                </span>

                <!-- Search Input with Ergonomic Clear Target -->
                <div class="relative w-full sm:w-64">
                    <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs"></i>
                    <input type="text" 
                           x-model="search" 
                           placeholder="Cari balita, NIK, kader..." 
                           class="w-full pl-8 pr-8 py-2 min-h-[38px] bg-slate-50 border border-slate-200 rounded-lg text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-500/20 transition-all">
                    <button type="button"
                            x-show="search.length > 0" 
                            @click="search = ''" 
                            class="absolute right-1 top-1/2 -translate-y-1/2 w-7 h-7 flex items-center justify-center text-slate-400 hover:text-slate-700 transition-colors">
                        <i class="ph-bold ph-x text-xs"></i>
                    </button>
                </div>

                <!-- Posyandu Dropdown -->
                <div class="w-full sm:w-52 shrink-0">
                    <form action="{{ route('puskesmas.validasi') }}" method="GET" id="posyanduForm">
                        <input type="hidden" name="tab" value="{{ $filters['tab'] }}">
                        <input type="hidden" name="posyandu_id" id="posyanduInput" value="{{ $filters['posyandu_id'] }}">

                        <div x-data="{ open: false }" class="relative w-full" @click.outside="open = false">
                            <button type="button" @click="open = !open" 
                                    class="flex items-center justify-between w-full px-3 py-2 min-h-[38px] bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 hover:bg-white hover:border-slate-300 focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                                <span class="truncate flex items-center gap-1.5">
                                    <i class="ph-bold ph-map-pin text-teal-600 text-xs shrink-0"></i>
                                    <span class="truncate">{{ $filters['posyandu_id'] ?: 'Semua Posyandu' }}</span>
                                </span>
                                <i class="ph-bold ph-caret-down text-slate-400 text-xs shrink-0 ml-1"></i>
                            </button>

                            <div x-show="open" 
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 style="display: none;"
                                 class="absolute right-0 top-full mt-1.5 w-56 bg-white border border-slate-200 rounded-lg shadow-xl z-30 py-1 max-h-60 overflow-y-auto">
                                
                                <button type="button" 
                                        @click="document.getElementById('posyanduInput').value = ''; document.getElementById('posyanduForm').submit();"
                                        class="w-full text-left px-3 py-2 text-xs hover:bg-slate-50 transition-colors {{ empty($filters['posyandu_id']) ? 'font-bold text-teal-700 bg-teal-50/60' : 'text-slate-700' }}">
                                    Semua Posyandu
                                </button>
                                
                                @foreach ($posyandus as $p)
                                    <button type="button" 
                                            @click="document.getElementById('posyanduInput').value = '{{ $p['nama'] }}'; document.getElementById('posyanduForm').submit();"
                                            class="w-full text-left px-3 py-2 text-xs hover:bg-slate-50 transition-colors {{ $filters['posyandu_id'] === $p['nama'] ? 'font-bold text-teal-700 bg-teal-50/60' : 'text-slate-700' }}">
                                        {{ $p['nama'] }}
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <!-- Work Queue Table (Responsive, Fits 100% Without Horizontal Scroll) -->
        <div class="w-full">
            @if(count($children) > 0)
                <!-- Desktop Table View -->
                <div class="hidden md:block overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/70 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                                <th class="py-3 px-4 sm:px-5">Identitas Balita</th>
                                <th class="py-3 px-3 text-center">Data Antropometri & Z-Score</th>
                                <th class="py-3 px-3 text-center">Hasil Analisis Gizi</th>
                                <th class="py-3 px-3">Posyandu & Kader</th>
                                <th class="py-3 px-4 sm:px-5 text-right">Tindakan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-slate-700">
                            @foreach($children as $child)
                                @php
                                    $isDanger  = ($child['statusType'] === 'danger');
                                    $isWarning = ($child['statusType'] === 'warning');
                                    $isBoy     = ($child['gender'] === 'Laki-laki');

                                    // Soft Gender Avatar Tint (Gentle, Human, Non-slop)
                                    $avatarClass = $isBoy 
                                        ? 'bg-sky-50 text-sky-700 border-sky-200/80' 
                                        : 'bg-rose-50 text-rose-700 border-rose-200/80';
                                @endphp
                                <tr x-show="matches('{{ addslashes($child['name']) }}', '{{ $child['nik'] }}', '{{ addslashes($child['kader']) }}', '{{ addslashes($child['posyandu']) }}')" 
                                    class="hover:bg-slate-50/80 transition-colors group">
                                    
                                    <!-- Col 1: Identity -->
                                    <td class="py-3.5 px-4 sm:px-5">
                                        <div class="flex items-center gap-2.5">
                                            <div class="w-9 h-9 rounded-full {{ $avatarClass }} border flex items-center justify-center font-bold text-sm shrink-0 shadow-sm">
                                                {{ substr($child['name'], 0, 1) }}
                                            </div>
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                    <span class="text-sm font-semibold text-slate-900 group-hover:text-teal-700 transition-colors truncate">
                                                        {{ $child['name'] }}
                                                    </span>
                                                    <span class="text-[9px] font-bold px-1.5 py-0.2 rounded border shrink-0 {{ $isBoy ? 'bg-sky-50 text-sky-700 border-sky-200/60' : 'bg-pink-50 text-pink-700 border-pink-200/60' }}">
                                                        {{ $isBoy ? 'L' : 'P' }} &bull; {{ $child['age'] }}
                                                    </span>
                                                </div>
                                                <div class="text-[11px] text-slate-400 truncate mt-0.5">
                                                    NIK: <span class="text-slate-600 font-medium">{{ $child['nik'] ?: '-' }}</span> &bull; Ibu: <span class="text-slate-600 font-medium">{{ $child['parent'] ?: '-' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Col 2: Anthropometry & Clinical Key (Cleaned Hierarchy) -->
                                    <td class="py-3.5 px-3 text-center">
                                        <div class="flex items-center justify-center gap-3">
                                            <div class="inline-flex items-center gap-3 px-3 py-1 bg-slate-50 border border-slate-100 rounded-lg text-xs whitespace-nowrap">
                                                <div class="text-left">
                                                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-0.5">BB</span>
                                                    <span class="font-extrabold text-slate-700 text-xs">{{ $child['bb'] }}<span class="text-slate-400 font-normal text-[10px] ml-0.5">kg</span></span>
                                                </div>
                                                <div class="w-px h-5 bg-slate-200"></div>
                                                <div class="text-left">
                                                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-0.5">TB</span>
                                                    <span class="font-extrabold text-slate-700 text-xs">{{ $child['tb'] }}<span class="text-slate-400 font-normal text-[10px] ml-0.5">cm</span></span>
                                                </div>
                                            </div>
                                            @if(!empty($child['value']))
                                                <div class="text-left pl-2 border-l border-slate-200">
                                                    <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-0.5">Z-Score TB/U</span>
                                                    <span class="inline-block font-black text-sm {{ $isDanger ? 'text-rose-600' : ($isWarning ? 'text-amber-600' : 'text-slate-800') }}">
                                                        {{ $child['value'] }}
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    <!-- Col 3: Status Gizi -->
                                    <td class="py-3.5 px-3 text-center whitespace-nowrap">
                                        <span class="text-xs font-black tracking-tight {{ $isDanger ? 'text-rose-600' : ($isWarning ? 'text-amber-600' : 'text-emerald-600') }}">
                                            {{ $child['statusLabel'] }}
                                        </span>
                                    </td>

                                    <!-- Col 4: Posyandu & Kader -->
                                    <td class="py-3.5 px-3">
                                        <div class="text-xs">
                                            <div class="font-semibold text-slate-800 flex items-center gap-1">
                                                <i class="ph-bold ph-map-pin text-teal-600 text-xs shrink-0"></i>
                                                <span class="truncate max-w-[130px]">{{ $child['posyandu'] }}</span>
                                            </div>
                                            <div class="text-slate-400 text-[11px] mt-0.5 truncate max-w-[130px]">
                                                {{ $child['kader'] }} &bull; {{ $child['date'] }}
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Col 5: Action (Micro-interaction enhanced) -->
                                    <td class="py-3.5 px-4 sm:px-5 text-right whitespace-nowrap">
                                        <a href="{{ route('puskesmas.validasi.review', $child['id']) }}" 
                                           class="inline-flex items-center justify-center gap-1.5 px-3.5 py-1.5 rounded-lg border border-teal-200/90 bg-teal-50 text-xs font-bold text-teal-800 hover:bg-teal-600 hover:text-white hover:border-teal-600 shadow-sm active:scale-95 transition-all group/btn">
                                            <span>Tinjau</span>
                                            <i class="ph-bold ph-arrow-right text-xs text-teal-600 group-hover/btn:text-white group-hover/btn:translate-x-0.5 transition-transform"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile Card View (No Horizontal Scroll) -->
                <div class="md:hidden divide-y divide-slate-100/80">
                    @foreach($children as $child)
                        @php
                            $isDanger  = ($child['statusType'] === 'danger');
                            $isWarning = ($child['statusType'] === 'warning');
                            $isBoy     = ($child['gender'] === 'Laki-laki');
                            $avatarClass = $isBoy ? 'bg-sky-50 text-sky-700 border-sky-200/80' : 'bg-rose-50 text-rose-700 border-rose-200/80';
                        @endphp
                        <div x-show="matches('{{ addslashes($child['name']) }}', '{{ $child['nik'] }}', '{{ addslashes($child['kader']) }}', '{{ addslashes($child['posyandu']) }}')" 
                             class="p-4 hover:bg-slate-50/50 transition-colors flex flex-col gap-3">
                            
                            <div class="flex justify-between items-start gap-2">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full {{ $avatarClass }} border flex items-center justify-center font-bold text-sm shrink-0 shadow-sm">
                                        {{ substr($child['name'], 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 text-sm truncate">{{ $child['name'] }}</div>
                                        <div class="text-[11px] text-slate-500 mt-0.5 truncate">NIK: {{ $child['nik'] ?: '-' }}</div>
                                    </div>
                                </div>
                                <span class="text-[10px] font-bold px-1.5 py-0.5 rounded border shrink-0 {{ $isBoy ? 'bg-sky-50 text-sky-700 border-sky-200/60' : 'bg-pink-50 text-pink-700 border-pink-200/60' }}">
                                    {{ $isBoy ? 'L' : 'P' }} &bull; {{ $child['age'] }}
                                </span>
                            </div>

                            <div class="bg-slate-50/80 border border-slate-100 rounded-xl p-3 flex flex-col gap-2">
                                <div class="flex items-center justify-between">
                                    <div class="flex gap-4">
                                        <div>
                                            <span class="block text-[9px] font-bold text-slate-400 uppercase">Berat</span>
                                            <span class="font-extrabold text-slate-800 text-xs">{{ $child['bb'] }}<span class="text-slate-400 font-normal text-[10px] ml-0.5">kg</span></span>
                                        </div>
                                        <div>
                                            <span class="block text-[9px] font-bold text-slate-400 uppercase">Tinggi</span>
                                            <span class="font-extrabold text-slate-800 text-xs">{{ $child['tb'] }}<span class="text-slate-400 font-normal text-[10px] ml-0.5">cm</span></span>
                                        </div>
                                        @if(!empty($child['value']))
                                        <div>
                                            <span class="block text-[9px] font-bold text-slate-400 uppercase">Z-Score</span>
                                            <span class="font-black text-xs {{ $isDanger ? 'text-rose-600' : ($isWarning ? 'text-amber-600' : 'text-slate-800') }}">{{ $child['value'] }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="h-px w-full bg-slate-200/60"></div>
                                <div class="flex justify-between items-center text-xs">
                                    <span class="font-semibold text-slate-500">Status Gizi</span>
                                    <span class="font-black {{ $isDanger ? 'text-rose-600' : ($isWarning ? 'text-amber-600' : 'text-emerald-600') }}">{{ $child['statusLabel'] }}</span>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-1 gap-2">
                                <div class="text-[11px] text-slate-500 min-w-0">
                                    <div class="flex items-center gap-1 font-semibold text-slate-700 truncate">
                                        <i class="ph-bold ph-map-pin text-teal-600"></i> <span class="truncate">{{ $child['posyandu'] }}</span>
                                    </div>
                                    <div class="truncate mt-0.5">{{ $child['date'] }}</div>
                                </div>
                                <a href="{{ route('puskesmas.validasi.review', $child['id']) }}" 
                                   class="inline-flex items-center justify-center gap-1.5 px-4 py-2 rounded-lg bg-teal-600 text-white text-[13px] font-bold hover:bg-teal-700 shadow-sm active:scale-95 transition-all shrink-0">
                                    Tinjau <i class="ph-bold ph-arrow-right"></i>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

            @else
                <!-- Better Empty State (Character & Micro-Illustration) -->
                <div class="py-24 px-6 text-center flex flex-col items-center">
                    <div class="relative w-20 h-20 mb-5">
                        <div class="absolute inset-0 bg-teal-100 rounded-full animate-ping opacity-20"></div>
                        <div class="relative w-full h-full bg-gradient-to-br from-teal-50 to-emerald-50 border border-teal-100 rounded-full flex items-center justify-center shadow-inner">
                            <i class="ph-duotone ph-coffee text-4xl text-teal-500 drop-shadow-sm"></i>
                        </div>
                    </div>
                    <h3 class="text-base font-extrabold text-slate-800 tracking-tight">Semua Antrean Selesai!</h3>
                    <p class="text-sm text-slate-500 mt-1.5 max-w-sm mx-auto leading-relaxed">
                        Kerja bagus! Tidak ada data pengukuran yang menunggu validasi. Anda bisa beristirahat sejenak.
                    </p>
                    <div class="mt-6">
                        <a href="{{ route('puskesmas.validasi') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 hover:bg-slate-50 hover:border-slate-300 shadow-sm transition-all group">
                            <i class="ph-bold ph-arrows-clockwise text-slate-400 group-hover:text-teal-600 transition-colors"></i> Muat Ulang Filter
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Footer Info Count -->
        @if(count($children) > 0)
            <div class="px-6 py-3 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between text-xs text-slate-500 font-medium">
                <span>Menampilkan {{ count($children) }} data antrean validasi</span>
                <span class="text-slate-400">Puskesmas NutriGen</span>
            </div>
        @endif

    </div>

</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('validationQueue', () => ({
            search: '',
            selectedPosyandu: '{{ $filters['posyandu_id'] }}',
            items: @json($searchableItems),
            get matchCount() {
                if (!this.search.trim()) return {{ count($children) }};
                const q = this.search.toLowerCase();
                return this.items.filter(i => i.searchStr.includes(q)).length;
            },
            matches(name, nik, kader, posyandu) {
                if (!this.search.trim()) return true;
                const q = this.search.toLowerCase();
                return (name || '').toLowerCase().includes(q) || 
                       (nik || '').toLowerCase().includes(q) || 
                       (kader || '').toLowerCase().includes(q) ||
                       (posyandu || '').toLowerCase().includes(q);
            }
        }));
    });
</script>
@endpush
