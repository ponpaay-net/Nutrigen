@extends('layouts.app')
@section('page-title', 'Data Balita')
@section('content')

@php
    $isFiltered = request()->filled('filter') || request()->filled('q');
    $balitasCollection = collect($balitas ?? []);
    $priorityBalitas = $isFiltered ? collect([]) : $balitasCollection->filter(fn($b) => in_array($b['status_type'] ?? '', ['danger', 'warning']));
    $displayBalitas  = $isFiltered ? $balitasCollection : $balitasCollection->filter(fn($b) => !in_array($b['status_type'] ?? '', ['danger', 'warning']));
    $totalAnak  = (int) ($statSelesai ?? 0) + (int) ($statBelum ?? 0);
    $sudah      = (int) ($statSelesai ?? 0);
    $belum      = (int) ($statBelum ?? 0);
    $revisi     = (int) ($filterCounts['ditolak'] ?? 0);
    $total      = count($balitas ?? []);
    $percentage = $totalAnak > 0 ? round(($sudah / $totalAnak) * 100) : 0;
    $kpis = [
        ['label' => 'Total Balita',   'value' => $total,     'icon' => 'users',          'color' => 'teal',    'sub' => 'Terdaftar aktif'],
        ['label' => 'Sudah Diukur',   'value' => $sudah,     'icon' => 'check-circle',   'color' => 'emerald', 'sub' => 'Bulan ini'],
        ['label' => 'Belum Diukur',   'value' => $belum,     'icon' => 'clock',          'color' => 'amber',   'sub' => 'Antrean'],
        ['label' => 'Perlu Revisi',   'value' => $revisi,    'icon' => 'warning-circle', 'color' => 'rose',    'sub' => 'Perhatian'],
    ];
@endphp

<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 pb-6 flex flex-col gap-5 lg:gap-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Data Balita</h1>
            <p class="text-sm text-slate-500 mt-0.5">{{ $posyanduName ?? 'Posyandu' }} · {{ now()->translatedFormat('d F Y') }}</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('balita.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 h-11 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500/40">
                <x-icon name="plus" weight="bold" class="text-base" /> Balita Baru
            </a>
        </div>
    </div>

    {{-- SUMMARY KPI ROW --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 lg:gap-4">
        @foreach($kpis as $kpi)
            <div class="group relative rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 p-4 sm:p-5 flex flex-col overflow-hidden">
                <span class="absolute top-0 inset-x-0 h-1 bg-{{ $kpi['color'] }}-500"></span>
                <div class="flex items-center justify-between">
                    <span class="w-9 h-9 rounded-xl bg-{{ $kpi['color'] }}-50 text-{{ $kpi['color'] }}-600 flex items-center justify-center ring-1 ring-{{ $kpi['color'] }}-100">
                        <x-icon name="{{ $kpi['icon'] }}" weight="fill" class="text-lg" />
                    </span>
                    <span class="w-7 h-7 rounded-full bg-slate-50 text-slate-300 group-hover:bg-{{ $kpi['color'] }}-50 group-hover:text-{{ $kpi['color'] }}-600 flex items-center justify-center transition-colors">
                        <x-icon name="arrow-up-right" weight="bold" class="text-sm" />
                    </span>
                </div>
                <p class="mt-3 text-2xl sm:text-3xl font-bold tabular-nums text-slate-900 leading-none">{{ $kpi['value'] }}</p>
                <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-500 truncate">{{ $kpi['label'] }}</p>
                <p class="mt-1 text-[11px] text-slate-400 truncate">{{ $kpi['sub'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- PROGRESS FEATURE CARD + SEARCH --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 lg:gap-6">
        <div class="lg:col-span-8 rounded-2xl bg-gradient-to-br from-teal-600 to-teal-700 text-white p-5 sm:p-6 relative overflow-hidden shadow-sm">
            <div class="relative z-10 flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
                <div class="flex-1">
                    <p class="text-xs font-semibold uppercase tracking-widest text-teal-100">Progress Pengukuran</p>
                    <div class="flex items-baseline gap-2 mt-1.5">
                        <span class="text-3xl sm:text-4xl font-bold tabular-nums leading-none">{{ $percentage }}%</span>
                        <span class="text-sm text-teal-100 font-medium">{{ $sudah }} dari {{ $totalAnak }} balita terukur</span>
                    </div>
                </div>
                <div class="sm:w-56 w-full">
                    <div class="w-full h-2.5 bg-white/20 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-white transition-all" style="width: {{ $percentage }}%"></div>
                    </div>
                    <div class="flex items-center justify-between mt-2.5 text-[12px] font-semibold">
                        <span class="inline-flex items-center gap-1.5 text-teal-50"><x-icon name="check-circle" weight="fill" /> Selesai {{ $sudah }}</span>
                        <span class="inline-flex items-center gap-1.5 text-teal-100"><x-icon name="clock" weight="fill" /> Antrean {{ $belum }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-4 rounded-2xl bg-white border border-slate-200 shadow-sm p-5 sm:p-6 flex flex-col justify-center gap-1">
            <label for="search-balita" class="text-sm font-semibold text-slate-700">Cari Balita</label>
            <p class="text-[12px] text-slate-500 -mt-1 mb-2">Cari berdasarkan nama atau NIK balita.</p>
            <form action="{{ route('balita.index') }}" method="GET" class="relative">
                <x-icon name="magnifying-glass" weight="bold" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none" />
                <input id="search-balita" type="text" name="q" value="{{ request('q') }}" placeholder="Nama atau NIK…"
                       class="w-full h-11 pl-11 pr-4 rounded-xl bg-white border border-slate-200 focus:border-teal-300 focus:ring-4 focus:ring-teal-500/10 text-sm text-slate-700 placeholder:text-slate-400 transition-all focus:outline-none">
                @if(request('filter'))<input type="hidden" name="filter" value="{{ request('filter') }}">@endif
            </form>
        </div>
    </div>

    {{-- FILTER CHIPS --}}
    <div class="flex items-center gap-2 overflow-x-auto hide-scrollbar -mx-1 px-1 pb-1">
        @php
            $filters = [
                'belum_diukur'     => ['label' => 'Belum Diukur',      'count' => $filterCounts['belum_diukur'] ?? 0, 'icon' => 'clock'],
                'absen_bulan_lalu' => ['label' => 'Absen Bulan Lalu',  'count' => $filterCounts['absen_bulan_lalu'] ?? 0, 'icon' => 'calendar-x'],
                'bayi_6_bln'       => ['label' => 'Bayi < 6 Bln',       'count' => $filterCounts['bayi_6_bln'] ?? 0, 'icon' => 'baby'],
                'selesai'          => ['label' => 'Sudah Diukur',      'count' => $filterCounts['selesai'] ?? 0, 'icon' => 'check-circle'],
                'ditolak'          => ['label' => 'Perlu Revisi',      'count' => $filterCounts['ditolak'] ?? 0, 'icon' => 'warning'],
            ];
        @endphp
        @foreach($filters as $key => $f)
            @php
                $isActive = request('filter') === $key || (!request('filter') && $key === 'belum_diukur');
                $href = $isActive ? route('balita.index') : route('balita.index', ['filter' => $key]);
            @endphp
            <a href="{{ $href }}"
               class="shrink-0 inline-flex items-center gap-2 h-11 px-4 rounded-full text-[13px] font-semibold transition-all duration-200 active:scale-95 {{ $isActive ? 'bg-teal-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:border-teal-300 hover:bg-teal-50 hover:text-teal-700' }}">
                <x-icon name="{{ $f['icon'] }}" weight="bold" class="text-[15px]" />
                {{ $f['label'] }}
                <span class="inline-flex items-center justify-center min-w-[22px] h-[22px] px-1.5 rounded-full text-[11px] font-bold {{ $isActive ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $f['count'] }}</span>
            </a>
        @endforeach
    </div>

    @if(request('q') && $priorityBalitas->isEmpty() && $displayBalitas->isEmpty())
        {{-- EMPTY STATE (search) --}}
        <div class="flex flex-col items-center justify-center text-center py-16 px-6 gap-2 bg-white border border-slate-200 rounded-2xl shadow-sm">
            <div class="w-12 h-12 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mb-1">
                <x-icon name="magnifying-glass" weight="bold" class="text-xl text-slate-300" />
            </div>
            <h3 class="text-[15px] font-semibold text-slate-800">Tidak ditemukan</h3>
            <p class="text-[13px] text-slate-400 max-w-xs">Tidak ada balita dengan nama atau NIK "<span class="text-slate-600 font-medium">{{ request('q') }}</span>".</p>
            <a href="{{ route('balita.index') }}" class="mt-1 text-[13px] font-semibold text-teal-600 hover:text-teal-700 transition-colors">Tampilkan semua</a>
        </div>
    @else

        {{-- PRIORITAS --}}
        @if($priorityBalitas->isNotEmpty())
        <section class="flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Prioritas Hari Ini</h2>
                    <p class="text-[13px] text-slate-500 mt-0.5">Balita yang memerlukan perhatian khusus</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-[12px] font-semibold">
                    <x-icon name="bell-ringing" weight="fill" class="text-sm" /> {{ $priorityBalitas->count() }} anak
                </span>
            </div>
            <div class="flex gap-3.5 overflow-x-auto snap-x hide-scrollbar -mx-1 px-1 pb-1">
                @foreach($priorityBalitas as $balita)
                    <div class="w-[280px] sm:w-[300px] shrink-0 snap-start flex"><x-child-card :balita="$balita" /></div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- SEMUA BALITA --}}
        <section class="flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">{{ $isFiltered ? 'Hasil Pencarian' : 'Antrean Pengukuran' }}</h2>
                    <p class="text-[13px] text-slate-500 mt-0.5">{{ $isFiltered ? 'Balita yang sesuai filter / kata kunci' : 'Kelola antrean balita sesuai status hari ini' }}</p>
                </div>
                <span class="text-[13px] font-semibold text-slate-500">{{ $displayBalitas->count() }} balita</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 items-stretch">
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
                    <div class="col-span-full flex flex-col items-center justify-center text-center py-16 px-6 gap-2.5 bg-white border border-slate-200 rounded-2xl shadow-sm">
                        <div class="w-12 h-12 rounded-2xl bg-teal-50 border border-teal-100 flex items-center justify-center mb-1 text-teal-600">
                            <x-icon name="check-circle" weight="fill" class="text-2xl" />
                        </div>
                        <p class="text-sm font-bold text-slate-800">{{ $emptyTitle }}</p>
                        <p class="text-xs font-medium text-slate-400 max-w-sm leading-relaxed">{{ $emptySub }}</p>
                        @if($activeFilter)
                            <a href="{{ route('balita.index') }}" class="mt-1 text-xs font-bold text-teal-600 hover:text-teal-700 bg-teal-50 hover:bg-teal-100 px-4 py-2 rounded-xl transition-colors">Tampilkan Semua Balita</a>
                        @endif
                    </div>
                @endforelse
            </div>
        </section>

    @endif
</div>
@endsection
