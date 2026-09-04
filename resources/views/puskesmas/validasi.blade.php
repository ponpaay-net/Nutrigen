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

<div x-data="validationQueue" class="space-y-6 pb-12">

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
            
            <!-- Segmented Filter Tabs -->
            <div class="flex items-center gap-1.5 overflow-x-auto hide-scrollbar">
                @php
                    $tabs = [
                        ['id' => 'pending',  'label' => 'Semua Antrean', 'count' => $c_pending],
                        ['id' => 'berisiko', 'label' => 'Indikasi Stunting', 'count' => $c_berisiko],
                        ['id' => 'anomali',  'label' => 'Risiko Gizi', 'count' => $c_anomali],
                        ['id' => 'normal',   'label' => 'Normal', 'count' => $c_normal],
                    ];
                @endphp
                @foreach ($tabs as $t)
                    <a href="?tab={{ $t['id'] }}&posyandu_id={{ urlencode($filters['posyandu_id']) }}"
                       class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-lg text-xs font-semibold transition-all whitespace-nowrap
                       {{ $filters['tab'] === $t['id'] 
                           ? 'bg-teal-700 text-white shadow-sm' 
                           : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }}">
                        <span>{{ $t['label'] }}</span>
                        <span class="text-[11px] px-1.5 py-0.2 rounded-md font-bold {{ $filters['tab'] === $t['id'] ? 'bg-teal-800 text-teal-100' : 'bg-slate-100 text-slate-600' }}">
                            {{ $t['count'] }}
                        </span>
                    </a>
                @endforeach
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
        <div class="w-full overflow-x-auto sm:overflow-x-visible">
            @if(count($children) > 0)
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 bg-slate-50/70 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                            <th class="py-3 px-4 sm:px-5">Identitas Balita</th>
                            <th class="py-3 px-3 text-center">Pengukuran Terkini</th>
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
                                        <div class="w-8 h-8 rounded-full {{ $avatarClass }} border flex items-center justify-center font-bold text-xs shrink-0 shadow-sm">
                                            {{ substr($child['name'], 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <div class="flex items-center gap-1.5 flex-wrap">
                                                <span class="text-xs sm:text-sm font-semibold text-slate-900 group-hover:text-teal-700 transition-colors truncate">
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

                                <!-- Col 2: Anthropometry & Clinical Key (Compact) -->
                                <td class="py-3.5 px-3 text-center">
                                    <div class="inline-flex items-center gap-2.5 px-2.5 py-1 bg-slate-50/90 border border-slate-200/70 rounded-lg text-xs whitespace-nowrap">
                                        <div class="text-left">
                                            <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-0.5">BB</span>
                                            <span class="font-extrabold text-slate-900 text-xs">{{ $child['bb'] }}<span class="text-slate-400 font-normal text-[10px] ml-0.5">kg</span></span>
                                        </div>
                                        <div class="w-px h-5 bg-slate-200"></div>
                                        <div class="text-left">
                                            <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-0.5">TB</span>
                                            <span class="font-extrabold text-slate-900 text-xs">{{ $child['tb'] }}<span class="text-slate-400 font-normal text-[10px] ml-0.5">cm</span></span>
                                        </div>
                                        @if(!empty($child['value']))
                                            <div class="w-px h-5 bg-slate-200"></div>
                                            <div class="text-left">
                                                <span class="block text-[9px] font-bold text-slate-400 uppercase tracking-wider leading-none mb-0.5">TB/U</span>
                                                <span class="inline-block font-black text-[11px] {{ $isDanger ? 'text-rose-600' : ($isWarning ? 'text-amber-600' : 'text-slate-700') }}">
                                                    {{ $child['value'] }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                </td>

                                <!-- Col 3: Status Gizi (Clean Font-Color Only, No Div Wrapper) -->
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

                                <!-- Col 5: Action -->
                                <td class="py-3.5 px-4 sm:px-5 text-right whitespace-nowrap">
                                    <a href="{{ route('puskesmas.validasi.review', $child['id']) }}" 
                                       class="inline-flex items-center justify-center gap-1.5 px-3 py-1.5 rounded-lg border border-teal-200/90 bg-teal-50/80 text-xs font-bold text-teal-800 hover:bg-teal-700 hover:text-white hover:border-teal-700 transition-all shadow-sm group/btn">
                                        <span>Tinjau</span>
                                        <i class="ph-bold ph-arrow-right text-xs text-teal-600 group-hover/btn:text-white group-hover/btn:translate-x-0.5 transition-transform"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <!-- Clean Empty State -->
                <div class="py-20 px-6 text-center">
                    <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-3">
                        <i class="ph-bold ph-check-circle text-2xl text-emerald-600"></i>
                    </div>
                    <h3 class="text-sm font-bold text-slate-900">Semua Antrean Selesai</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto">
                        Tidak ada data pengukuran yang menunggu validasi untuk kriteria filter ini.
                    </p>
                    <div class="mt-4">
                        <a href="{{ route('puskesmas.validasi') }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
                            Reset Filter
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
