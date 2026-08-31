@extends('layouts.puskesmas')
@section('page-title', 'Data Balita')
@section('page-mode', 'default')
@section('content')

{{--
    Backend Contract:
    Controller: PuskesmasController@balita
    Variables: $children (list), $posyandus, $filters['q', 'posyandu_id', 'status_gizi']
--}}

@php
    $q           = $filters['q'] ?? '';
    $posyanduId  = $filters['posyandu_id'] ?? '';
    $statusGizi  = $filters['status_gizi'] ?? '';

    $collection  = collect($children);
    $prioritized = $collection->filter(fn($c) => in_array($c['statusType'] ?? '', ['danger', 'warning']));
    $normal      = $collection->filter(fn($c) => !in_array($c['statusType'] ?? '', ['danger', 'warning']));

    $isFiltered  = filled($q) || filled($posyanduId) || filled($statusGizi);
    if ($isFiltered) {
        $prioritized = collect([]);
        $normal      = $collection;
    }

    // KPI counts
    $totalBalita = $collection->count();
    $totalStunting = $collection->filter(fn($c) => ($c['statusType'] ?? '') === 'danger')->count();
    $totalRisiko   = $collection->filter(fn($c) => ($c['statusType'] ?? '') === 'warning')->count();
    $totalNormal   = $collection->filter(fn($c) => ($c['statusType'] ?? '') === 'success')->count();
@endphp

<div class="min-h-screen bg-slate-50/50 w-full pb-16">

    {{-- ══════════════════════════════════════════
         HERO HEADER
    ══════════════════════════════════════════ --}}
    <div class="px-4 pt-5 pb-0 lg:px-6 lg:pt-6 max-w-7xl mx-auto">
        <div class="relative overflow-hidden bg-gradient-to-br from-[#0097B0] via-[#00A9C0] to-[#00C4E0] rounded-3xl shadow-lg shadow-cyan-300/20">

            {{-- Decorative background dots --}}
            <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#fff 1.5px, transparent 1.5px); background-size: 20px 20px;"></div>
            <div class="absolute -bottom-12 -right-12 w-48 h-48 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>

            <div class="relative z-10 px-5 py-6 lg:px-8 lg:py-7">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">

                    {{-- Left: Title --}}
                    <div>
                        <p class="text-[10.5px] font-bold text-cyan-200 uppercase tracking-widest mb-1">Portal Puskesmas</p>
                        <h1 class="text-2xl lg:text-3xl font-extrabold text-white leading-tight tracking-tight">Direktori Balita</h1>
                        <p class="text-cyan-100/80 text-sm mt-1 font-medium">Rekam medis dan riwayat pertumbuhan seluruh balita</p>
                    </div>

                    {{-- Right: KPI pills + search --}}
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 w-full lg:w-auto">

                        {{-- KPI row --}}
                        <div class="flex items-center gap-2">
                            @php
                                $kpis = [
                                    ['val' => $totalBalita,   'label' => 'Total',    'bg' => 'bg-white/15 border-white/20'],
                                    ['val' => $totalStunting, 'label' => 'Stunting', 'bg' => 'bg-rose-400/30 border-rose-300/30'],
                                    ['val' => $totalRisiko,   'label' => 'Risiko',   'bg' => 'bg-amber-400/30 border-amber-300/30'],
                                    ['val' => $totalNormal,   'label' => 'Normal',   'bg' => 'bg-emerald-400/30 border-emerald-300/30'],
                                ];
                            @endphp
                            @foreach($kpis as $kpi)
                                <div class="flex flex-col items-center px-3.5 py-2 rounded-2xl border backdrop-blur-sm {{ $kpi['bg'] }}">
                                    <span class="text-xl font-black text-white leading-none">{{ $kpi['val'] }}</span>
                                    <span class="text-[9px] font-bold text-white/70 uppercase tracking-wider mt-0.5">{{ $kpi['label'] }}</span>
                                </div>
                            @endforeach
                        </div>

                        {{-- Search bar --}}
                        <form action="{{ route('puskesmas.balita') }}" method="GET" id="filterForm" class="flex gap-2 flex-1 lg:w-72">
                            <input type="hidden" name="posyandu_id" value="{{ $posyanduId }}">
                            <input type="hidden" name="status_gizi" value="{{ $statusGizi }}">
                            <div class="relative flex-1">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="absolute left-3.5 top-1/2 -translate-y-1/2 w-3.5 h-3.5 text-slate-400">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                                </svg>
                                <input type="text" name="q" value="{{ $q }}"
                                    placeholder="Cari nama balita atau NIK..."
                                    class="w-full h-[42px] pl-10 pr-4 rounded-full bg-white text-slate-700 text-[13px] font-medium placeholder-slate-400 border-none outline-none focus:ring-4 focus:ring-white/30 shadow-sm transition-all">
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         FILTER + CONTENT AREA
    ══════════════════════════════════════════ --}}
    <div class="px-4 lg:px-6 max-w-7xl mx-auto mt-5 flex flex-col gap-5">

        {{-- Filter row: posyandu + status --}}
        <div class="flex flex-wrap items-center gap-2">
            {{-- Posyandu Custom Dropdown Filter (Alpine) --}}
            <form action="{{ route('puskesmas.balita') }}" method="GET" class="contents" id="posyanduFilterForm">
                <input type="hidden" name="q" value="{{ $q }}">
                <input type="hidden" name="status_gizi" value="{{ $statusGizi }}">
                <input type="hidden" name="posyandu_id" id="hiddenPosyanduId" value="{{ $posyanduId }}">

                <div x-data="{ 
                        open: false,
                        selectPosyandu(id) {
                            document.getElementById('hiddenPosyanduId').value = id;
                            document.getElementById('posyanduFilterForm').submit();
                        }
                    }" 
                    class="relative shrink-0" 
                    @click.outside="open = false">
                    
                    <button type="button" @click="open = !open" 
                        class="flex items-center justify-between h-9 pl-4 pr-3.5 rounded-full text-[12px] font-bold outline-none transition-all shadow-sm cursor-pointer border min-w-[160px]
                        {{ $posyanduId ? 'bg-[#00A9C0] text-white border-[#0097B0] focus:ring-2 focus:ring-cyan-300' : 'bg-white text-slate-700 border-slate-200 hover:border-cyan-300 focus:ring-2 focus:ring-cyan-100' }}">
                        <span class="mr-2 truncate max-w-[180px]">
                            @if($posyanduId)
                                {{ collect($posyandus)->firstWhere('id', (int)$posyanduId)['nama'] ?? 'Semua Posyandu' }}
                            @else
                                Semua Posyandu
                            @endif
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5 shrink-0 transition-transform duration-200" :class="{'rotate-180': open}">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <div x-show="open" 
                        x-transition:enter="transition ease-out duration-100"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-75"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        style="display: none;"
                        class="absolute left-0 top-full mt-2 w-72 bg-white border border-slate-100 rounded-2xl shadow-xl overflow-hidden z-[60] py-1.5 flex flex-col max-h-[60vh] overflow-y-auto hide-scrollbar">
                        
                        <button type="button" @click="selectPosyandu('')" class="w-full text-left px-4 py-3 text-[12px] font-semibold transition-colors hover:bg-slate-50 flex items-center justify-between {{ !$posyanduId ? 'text-[#00A9C0] bg-[#f0f9fa]' : 'text-slate-700' }}">
                            <span>Semua Posyandu</span>
                            @if(!$posyanduId)
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                            @endif
                        </button>
                        
                        @foreach($posyandus as $ps)
                            <button type="button" @click="selectPosyandu('{{ $ps['id'] }}')" class="w-full text-left px-4 py-3 text-[12px] font-semibold transition-colors hover:bg-slate-50 flex items-center justify-between border-t border-slate-50 {{ (string)$posyanduId === (string)$ps['id'] ? 'text-[#00A9C0] bg-[#f0f9fa]' : 'text-slate-700' }}">
                                <span>{{ $ps['nama'] }}</span>
                                @if((string)$posyanduId === (string)$ps['id'])
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/></svg>
                                @endif
                            </button>
                        @endforeach
                    </div>
                </div>
            </form>

            <div class="w-px h-5 bg-slate-200 mx-1"></div>

            {{-- Status gizi filter chips --}}
            @php
                $statusFilters = [
                    ''         => ['label' => 'Semua Status', 'classes' => ''],
                    'stunting' => ['label' => 'Stunting / Gizi Buruk', 'classes' => '!bg-rose-500 !text-white !border-rose-400'],
                    'risiko'   => ['label' => 'Risiko', 'classes' => '!bg-amber-400 !text-white !border-amber-300'],
                    'normal'   => ['label' => 'Normal', 'classes' => '!bg-emerald-500 !text-white !border-emerald-400'],
                ];
            @endphp
            @foreach($statusFilters as $val => $sf)
                <a href="{{ route('puskesmas.balita', array_filter(['q' => $q, 'posyandu_id' => $posyanduId, 'status_gizi' => $val])) }}"
                    class="shrink-0 flex items-center gap-1.5 px-3.5 h-9 rounded-full text-[12px] font-bold transition-all
                    {{ $statusGizi === $val && $val !== ''
                        ? $sf['classes']
                        : ($statusGizi === $val
                            ? 'bg-[#00A9C0] text-white shadow-sm'
                            : 'bg-white text-slate-600 border border-slate-200 hover:border-cyan-300 hover:text-[#00A9C0]') }}">
                    {{ $sf['label'] }}
                </a>
            @endforeach
        </div>

        {{-- ── EMPTY STATE ──────────────────────────────────────── --}}
        @if($prioritized->isEmpty() && $normal->isEmpty())
            <div class="flex flex-col items-center justify-center py-20 gap-3 bg-white border border-slate-200/70 rounded-3xl shadow-xs text-center">
                <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center mb-1">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-slate-300">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                    </svg>
                </div>
                <p class="text-[15px] font-bold text-slate-700">Tidak ada data ditemukan</p>
                <p class="text-sm text-slate-400 max-w-xs leading-relaxed">Coba ubah filter atau kata kunci pencarian.</p>
                <a href="{{ route('puskesmas.balita') }}" class="mt-1 text-sm font-bold text-[#00A9C0] hover:text-cyan-600 transition-colors">Tampilkan semua</a>
            </div>

        @else

            {{-- ── SECTION 1: PRIORITAS ────────────────────────────── --}}
            @if($prioritized->isNotEmpty())
                <section>
                    <div class="bg-white border border-slate-200/70 rounded-3xl p-5 lg:p-6 shadow-xs">

                        {{-- Section header --}}
                        <div class="flex items-start sm:items-center justify-between gap-3 mb-5">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-orange-100 text-orange-500 flex items-center justify-center shrink-0 border border-orange-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="text-[16px] font-bold text-slate-900">Perlu Perhatian</h2>
                                    <p class="text-[11px] text-slate-500 mt-0.5 font-medium">Balita dengan status stunting atau risiko gizi</p>
                                </div>
                            </div>
                            <span class="shrink-0 bg-rose-50 text-rose-600 border border-rose-200 px-3 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full animate-pulse"></span>
                                {{ $prioritized->count() }} anak
                            </span>
                        </div>

                        {{-- Horizontal scroll priority cards --}}
                        <div class="relative">
                            <div class="absolute right-0 top-0 bottom-0 w-10 bg-gradient-to-l from-white to-transparent pointer-events-none z-10 lg:hidden rounded-r-3xl"></div>
                            <div class="flex overflow-x-auto gap-3 pb-2 snap-x hide-scrollbar -mx-1 px-1">
                                @foreach($prioritized as $child)
                                    <div class="w-[260px] sm:w-[280px] shrink-0 snap-start">
                                        <x-balita.child-card :child="$child" />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </section>
            @endif

            {{-- ── SECTION 2: SEMUA BALITA ─────────────────────────── --}}
            <section>
                <div class="bg-white border border-slate-200/70 rounded-3xl p-5 lg:p-6 shadow-xs">

                    {{-- Section header --}}
                    <div class="flex items-center gap-3 mb-5">
                        <div class="w-10 h-10 rounded-xl bg-[#E6F8FB] text-[#00A9C0] flex items-center justify-center shrink-0 border border-[#B3E9F2]">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                                <path d="M7 8a3 3 0 100-6 3 3 0 000 6zM14.5 9a2.5 2.5 0 100-5 2.5 2.5 0 000 5zM1.615 16.428a1.224 1.224 0 01-.569-1.175 6.002 6.002 0 0111.908 0c.058.467-.172.92-.57 1.174A9.953 9.953 0 017 17a9.953 9.953 0 01-5.385-1.572zM14.5 16h-.106c.07-.297.088-.611.048-.933a7.47 7.47 0 00-1.588-3.755 4.502 4.502 0 015.874 2.636.818.818 0 01-.36.98A7.465 7.465 0 0114.5 16z"/>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-[16px] font-bold text-slate-900">
                                {{ $isFiltered ? 'Hasil Pencarian' : 'Semua Balita' }}
                            </h2>
                            <p class="text-[11px] text-slate-500 mt-0.5 font-medium">
                                {{ $normal->count() }} balita {{ $isFiltered ? 'ditemukan' : 'terdaftar' }}
                            </p>
                        </div>
                    </div>

                    {{-- Grid --}}
                    @if($normal->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4">
                            @foreach($normal as $child)
                                <x-balita.child-card :child="$child" />
                            @endforeach
                        </div>
                    @else
                        <div class="flex flex-col items-center justify-center py-12 gap-2 text-center">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-50 border border-emerald-100 flex items-center justify-center mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-6 h-6 text-emerald-500">
                                    <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <p class="text-sm font-bold text-slate-700">Semua balita sudah di-filter ke bagian atas</p>
                        </div>
                    @endif

                </div>
            </section>
        @endif

    </div>
</div>

@push('scripts')
<script>
    // ── CLIENT-SIDE SEARCH ───────────────────────────────────────────
    const searchInput = document.getElementById('searchInputLive');
    if (searchInput) {
        searchInput.addEventListener('input', () => {
            const q = searchInput.value.toLowerCase().trim();
            document.querySelectorAll('.child-card-wrapper').forEach(btn => {
                const name = (btn.dataset.name || '').toLowerCase();
                const nik  = (btn.dataset.nik  || '').toLowerCase();
                const show = !q || name.includes(q) || nik.includes(q);
                btn.style.display = show ? '' : 'none';
            });
        });
    }
</script>
@endpush

@endsection
