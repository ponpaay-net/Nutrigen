@extends('layouts.app')
@section('page-title', 'Data Balita')
@section('content')

@php
    $isFiltered = request()->filled('filter') || request()->filled('q');
    $priorityBalitas = collect($priorityBalitas ?? []);
    $displayBalitas  = collect($balitas->items());      // kartu pada halaman ini
    $totalShown      = (int) $balitas->total();          // total semua halaman
    $sudah      = (int) ($statSelesai ?? 0);
    $belum      = (int) ($statBelum ?? 0);
    $totalAnak  = $sudah + $belum;   // total balita asli (selesai + belum)
    $total      = $totalAnak;
    $percentage = $totalAnak > 0 ? round(($sudah / $totalAnak) * 100) : 0;
    $revisi     = (int) ($filterCounts['ditolak'] ?? 0);
    $stats = [
        ['label' => 'Total Balita', 'value' => $total,  'color' => 'teal',    'icon' => 'users',                    'bg' => 'bg-teal-50',    'text' => 'text-teal-600',    'url' => route('balita.index')],
        ['label' => 'Sudah Diukur', 'value' => $sudah,  'color' => 'emerald', 'icon' => 'check-circle',             'bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'url' => route('balita.index', ['filter' => 'selesai'])],
        ['label' => 'Belum Diukur', 'value' => $belum,  'color' => 'amber',   'icon' => 'clock',                    'bg' => 'bg-amber-50',   'text' => 'text-amber-600',   'url' => route('balita.index', ['filter' => 'belum_diukur'])],
        ['label' => 'Validasi Ulang', 'value' => $revisi, 'color' => 'amber', 'icon' => 'arrows-counter-clockwise', 'bg' => 'bg-amber-50',   'text' => 'text-amber-600',   'url' => route('balita.index', ['filter' => 'ditolak'])],
    ];
    $filters = [
        'belum_diukur'     => ['label' => 'Belum Diukur',     'count' => $filterCounts['belum_diukur'] ?? 0, 'icon' => 'clock',        'dot' => 'bg-amber-400'],
        'absen_bulan_lalu' => ['label' => 'Absen Bulan Lalu', 'count' => $filterCounts['absen_bulan_lalu'] ?? 0, 'icon' => 'calendar-x', 'dot' => 'bg-slate-400'],
        'bayi_6_bln'       => ['label' => 'Bayi < 6 Bln',      'count' => $filterCounts['bayi_6_bln'] ?? 0, 'icon' => 'baby',          'dot' => 'bg-teal-500'],
        'selesai'          => ['label' => 'Sudah Diukur',     'count' => $filterCounts['selesai'] ?? 0, 'icon' => 'check-circle',   'dot' => 'bg-emerald-500'],
        'ditolak'          => ['label' => 'Validasi Ulang',   'count' => $filterCounts['ditolak'] ?? 0, 'icon' => 'arrows-counter-clockwise', 'dot' => 'bg-amber-500'],
    ];
@endphp

<div class="w-full mx-auto px-3.5 sm:px-6 lg:px-8 pt-4 sm:pt-6 pb-16 flex flex-col gap-4 sm:gap-6">

    {{-- PAGE HEADER --}}
    <div class="flex items-center justify-between gap-3">
        <div class="min-w-0">
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Data Balita</h1>
            <p class="text-xs sm:text-sm font-medium text-slate-500 mt-0.5 truncate">
                {{ $posyanduName ?? 'Posyandu' }} <span class="text-slate-300 mx-1">·</span> {{ now()->translatedFormat('d F Y') }}
            </p>
        </div>
        <a href="{{ route('balita.create') }}"
           class="inline-flex items-center justify-center gap-1.5 sm:gap-2 px-3.5 sm:px-5 h-10 sm:h-11 shrink-0 rounded-xl bg-teal-600 hover:bg-teal-700 active:scale-[0.98] text-white text-xs sm:text-sm font-bold shadow-sm shadow-teal-600/20 transition-all focus:outline-none focus:ring-2 focus:ring-teal-500/40">
            <x-icon name="plus" weight="bold" class="text-base" />
            <span>Tambah Balita</span>
        </a>
    </div>

    {{-- TOOLBAR: search + filter dropdown (Dropdown sembunyi di HP, digantikan Quick Filter Pills agar tidak double filter) --}}
    <div class="flex flex-col gap-2.5 sm:gap-3">
        <form action="{{ route('balita.index') }}" method="GET" class="flex flex-col sm:flex-row gap-2.5 sm:gap-3 sm:items-center">
            @if(request('filter'))
                <input type="hidden" name="filter" value="{{ request('filter') }}" class="sm:hidden">
            @endif
            <div class="relative w-full sm:flex-1">
                <x-icon name="magnifying-glass" weight="bold" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base pointer-events-none" />
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik nama anak atau NIK balita…" aria-label="Cari balita berdasarkan nama atau NIK"
                       class="w-full h-11 sm:h-12 pl-10 pr-4 rounded-xl bg-white border border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/15 text-base sm:text-sm text-slate-800 placeholder:text-slate-400 shadow-2xs transition-all focus:outline-none">
            </div>

            <div class="relative hidden sm:block w-auto shrink-0">
                <x-icon name="funnel" weight="bold" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-base pointer-events-none transition-colors {{ request('filter') ? 'text-teal-600' : 'text-slate-400' }}" />
                <select name="filter" onchange="this.form.submit()" aria-label="Filter balita"
                        class="w-[240px] h-11 sm:h-12 pl-10 pr-10 rounded-xl bg-white border border-slate-200 focus:border-teal-500 focus:ring-2 focus:ring-teal-500/15 text-sm font-semibold text-slate-700 appearance-none cursor-pointer shadow-2xs transition-all focus:outline-none">
                    <option value="">Semua Kategori ({{ $total }})</option>
                    @foreach($filters as $key => $f)
                        <option value="{{ $key }}" @if(request('filter') === $key) selected @endif>{{ $f['label'] }} ({{ $f['count'] }})</option>
                    @endforeach
                </select>
                <x-icon name="caret-down" weight="bold" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-sm pointer-events-none" />
            </div>
        </form>

        {{-- Quick Tap Filter Pills (Horizontal Scroll Smooth edge-to-edge di Mobile) --}}
        <div class="flex items-center gap-2 overflow-x-auto touch-pan-x [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden py-0.5 -mx-3.5 px-3.5 sm:mx-0 sm:px-0">
            <a href="{{ route('balita.index', ['q' => request('q')]) }}" 
               class="inline-flex items-center gap-1.5 h-8 sm:h-9 px-3 sm:px-3.5 rounded-full text-xs font-semibold border transition-all shrink-0 {{ !request('filter') ? 'bg-teal-600 border-teal-600 text-white shadow-2xs' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                Semua ({{ $total }})
            </a>
            @foreach($filters as $key => $f)
                @php $isActive = request('filter') === $key; @endphp
                <a href="{{ route('balita.index', ['filter' => $key, 'q' => request('q')]) }}" 
                   class="inline-flex items-center gap-1.5 h-8 sm:h-9 px-3 sm:px-3.5 rounded-full text-xs font-semibold border transition-all shrink-0 {{ $isActive ? 'bg-teal-600 border-teal-600 text-white shadow-2xs' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    <span class="w-2 h-2 rounded-full {{ $isActive ? 'bg-white' : $f['dot'] }} shrink-0"></span>
                    <span>{{ $f['label'] }}</span>
                    <span class="text-[11px] px-1.5 py-0.2 rounded-full {{ $isActive ? 'bg-teal-700 text-teal-100' : 'bg-slate-100 text-slate-600' }}">{{ $f['count'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    {{-- SUMMARY: RINGKASAN PROGRESS BULAN INI --}}
    <section class="bg-white border border-slate-200 rounded-2xl shadow-2xs p-3.5 sm:p-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3.5 sm:gap-4">
            <div class="flex items-center gap-3 sm:gap-4">
                {{-- Progress Gauge Circle (Ukuran pasti 56px, anti-collapse) --}}
                <div style="width: 56px; height: 56px; min-width: 56px; min-height: 56px;" class="relative shrink-0 rounded-full bg-teal-50 flex items-center justify-center border border-teal-100 shadow-2xs">
                    <svg viewBox="0 0 36 36" style="width: 56px; height: 56px;" class="-rotate-90 transform">
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#ccfbf1" stroke-width="3.2"></circle>
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#0d9488" stroke-width="3.2" stroke-linecap="{{ $percentage > 0 ? 'round' : 'butt' }}"
                                stroke-dasharray="{{ $percentage }} {{ max(0, 100 - $percentage) }}" pathLength="100"></circle>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
                        <span class="text-[13px] sm:text-sm font-extrabold text-teal-800 tabular-nums leading-none">{{ $percentage }}%</span>
                    </div>
                </div>
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900 leading-tight">Progres Penimbangan Bulan Ini</h2>
                    <p class="text-[11.5px] sm:text-xs text-slate-500 mt-1 leading-normal">
                        <span class="font-bold text-teal-700">{{ $sudah }}</span> dari {{ $total }} balita telah terukur 
                        (<span class="font-semibold text-amber-600">{{ $belum }}</span> balita belum diukur).
                    </p>
                </div>
            </div>

            {{-- 4 Stat Cards: Grid 2-Kolom di HP, Flex di Tablet/Desktop (Interaktif & Indah) --}}
            <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 sm:gap-2.5 w-full sm:w-auto pt-1 sm:pt-0">
                @foreach($stats as $stat)
                    <a href="{{ $stat['url'] }}"
                       class="group px-3 py-2.5 rounded-xl bg-slate-50 hover:bg-white border border-slate-200/80 hover:border-teal-300 shadow-2xs hover:shadow-sm flex items-center gap-2.5 min-w-0 transition-all active:scale-[0.98]">
                        <div class="w-8 h-8 rounded-lg {{ $stat['bg'] }} flex items-center justify-center shrink-0 group-hover:scale-105 transition-transform">
                            <x-icon name="{{ $stat['icon'] }}" weight="fill" class="text-base {{ $stat['text'] }}" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-bold text-slate-900 leading-tight tabular-nums">{{ $stat['value'] }}</p>
                            <p class="text-[11px] font-medium text-slate-500 truncate mt-0.5 group-hover:text-slate-700">{{ $stat['label'] }}</p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    @if(request('filter') || request('q'))
    <div class="flex flex-wrap items-center gap-2 text-xs text-slate-500">
        @php $activeFilterKey = request('filter'); @endphp
        <span class="inline-flex items-center gap-1.5 font-medium">
            <x-icon name="funnel" weight="bold" class="text-xs text-slate-400" />
            Menampilkan:
        </span>
        @if($activeFilterKey && isset($filters[$activeFilterKey]))
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-teal-50 border border-teal-100 text-teal-700 font-bold">{{ $filters[$activeFilterKey]['label'] }}</span>
        @endif
        @if(request('q'))
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-600 font-bold">"{{ request('q') }}"</span>
        @endif
        <a href="{{ route('balita.index') }}" class="ml-1 inline-flex items-center gap-1 font-semibold text-slate-400 hover:text-rose-600 transition-colors">
            <x-icon name="x" weight="bold" class="text-xs" /> Hapus filter
        </a>
    </div>
    @endif

    @if(request('q') && $priorityBalitas->isEmpty() && $displayBalitas->isEmpty())
        {{-- EMPTY STATE (search) --}}
        <div class="flex flex-col items-center justify-center text-center py-12 sm:py-20 px-4 sm:px-6 gap-3 bg-slate-50/50 border border-slate-200 rounded-2xl shadow-2xs border-dashed">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-center mb-1">
                <x-icon name="magnifying-glass" weight="bold" class="text-2xl text-slate-400" />
            </div>
            <div>
                <h3 class="text-base font-bold text-slate-800">Tidak Ditemukan</h3>
                <p class="text-xs sm:text-sm text-slate-500 max-w-xs mt-1">Tidak ada balita dengan nama atau NIK "<span class="text-slate-700 font-semibold">{{ request('q') }}</span>".</p>
            </div>
            <a href="{{ route('balita.index') }}" class="mt-2 text-xs sm:text-sm font-semibold text-teal-600 hover:text-teal-700 bg-teal-50 hover:bg-teal-100 px-4 py-2 rounded-xl transition-colors inline-flex items-center gap-2">
                <x-icon name="arrow-left" weight="bold" class="text-xs" /> Kembali ke Semua Data
            </a>
        </div>
    @else

        {{-- PRIORITAS HARI INI --}}
        @if($priorityBalitas->isNotEmpty())
        <section class="flex flex-col gap-2.5 sm:gap-3">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900">Prioritas Hari Ini</h2>
                    <p class="text-[11px] sm:text-xs text-slate-500 mt-0.5">Balita yang memerlukan perhatian khusus</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-xs font-semibold">
                    <x-icon name="bell-ringing" weight="fill" class="text-xs" /> {{ $priorityBalitas->count() }}
                </span>
            </div>
            <div class="flex gap-3 overflow-x-auto snap-x touch-pan-x [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden -mx-3.5 px-3.5 sm:mx-0 sm:px-0 pb-1">
                @foreach($priorityBalitas as $balita)
                    <div class="w-[270px] sm:w-[280px] shrink-0 snap-start flex"><x-child-card :balita="$balita" /></div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- DAFTAR BALITA --}}
        <section class="flex flex-col gap-2.5 sm:gap-3">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm sm:text-base font-bold text-slate-900">{{ $isFiltered ? 'Hasil Filter' : 'Daftar Balita' }}</h2>
                    <p class="text-[11px] sm:text-xs text-slate-500 mt-0.5">
                        @if(!$isFiltered && $priorityBalitas->isNotEmpty())
                            {{ $totalShown }} balita lainnya
                        @else
                            {{ $totalShown }} balita
                        @endif
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 sm:gap-4 items-stretch">
                @forelse($displayBalitas as $balita)
                    <x-child-card :balita="$balita" />
                @empty
                    @php
                        $activeFilter = request('filter');
                        $emptyTitle = match($activeFilter) {
                            'ditolak', 'revisi' => 'Tidak Ada Balita Perlu Revisi',
                            'belum_diukur' => 'Semua Balita Sudah Diukur!',
                            'absen_bulan_lalu' => 'Tidak Ada Balita Absen',
                            'bayi_6_bln' => 'Tidak Ada Bayi < 6 Bulan',
                            'selesai' => 'Belum Ada Pengukuran Selesai',
                            default => 'Tidak Ada Data Balita'
                        };
                        $emptySub = match($activeFilter) {
                            'ditolak', 'revisi' => 'Semua data pengukuran telah valid atau belum ada catatan perbaikan dari Puskesmas.',
                            'belum_diukur' => 'Seluruh balita terdaftar telah selesai diukur pada periode ini.',
                            'absen_bulan_lalu' => 'Seluruh balita hadir pada penimbangan bulan lalu.',
                            'bayi_6_bln' => 'Seluruh balita yang terdaftar saat ini berusia di atas 6 bulan.',
                            'selesai' => 'Lakukan pengukuran balita untuk mencatat data penimbangan bulan ini.',
                            default => 'Tidak ada balita yang sesuai dengan filter atau pencarian saat ini.'
                        };
                    @endphp
                    <div class="col-span-full flex flex-col items-center justify-center text-center py-12 sm:py-20 px-4 sm:px-6 gap-3 bg-slate-50/50 border border-slate-200 rounded-2xl shadow-2xs border-dashed">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-white border border-slate-200 shadow-2xs flex items-center justify-center mb-1 text-teal-600">
                            <x-icon name="check-circle" weight="fill" class="text-2xl sm:text-3xl" />
                        </div>
                        <div>
                            <p class="text-sm sm:text-base font-bold text-slate-800">{{ $emptyTitle }}</p>
                            <p class="text-xs sm:text-sm font-medium text-slate-500 max-w-sm leading-relaxed mt-1">{{ $emptySub }}</p>
                        </div>
                        @if($activeFilter)
                            <a href="{{ route('balita.index') }}" class="mt-2 text-xs sm:text-sm font-semibold text-teal-600 hover:text-teal-700 bg-teal-50 hover:bg-teal-100 px-4 py-2 rounded-xl transition-colors inline-flex items-center gap-2">
                                <x-icon name="arrow-left" weight="bold" class="text-xs" /> Hapus Filter
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            @if($balitas->hasPages())
                <div class="flex justify-center pt-2">
                    {{ $balitas->links('partials.pagination') }}
                </div>
            @endif
        </section>

    @endif
</div>
@endsection
