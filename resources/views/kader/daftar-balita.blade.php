@extends('layouts.app')
@section('page-title', 'Data Balita')
@section('content')

@php
    $isFiltered = request()->filled('filter') || request()->filled('q');
    $balitasCollection = collect($balitas ?? []);
    $priorityBalitas = $isFiltered ? collect([]) : $balitasCollection->filter(fn($b) => in_array($b['status_type'] ?? '', ['danger', 'warning']));
    $displayBalitas  = $isFiltered ? $balitasCollection : $balitasCollection->filter(fn($b) => !in_array($b['status_type'] ?? '', ['danger', 'warning']));
    $total      = count($balitas ?? []);
    $sudah      = (int) ($statSelesai ?? 0);
    $belum      = (int) ($statBelum ?? 0);
    $totalAnak  = $sudah + $belum;
    $percentage = $totalAnak > 0 ? round(($sudah / $totalAnak) * 100) : 0;
    $revisi     = (int) ($filterCounts['ditolak'] ?? 0);
    $stats = [
        ['label' => 'Total Balita', 'value' => $total,  'color' => 'teal',    'icon' => 'users'],
        ['label' => 'Sudah Diukur', 'value' => $sudah,  'color' => 'emerald', 'icon' => 'check-circle'],
        ['label' => 'Belum Diukur', 'value' => $belum,  'color' => 'amber',   'icon' => 'clock'],
        ['label' => 'Perlu Revisi', 'value' => $revisi, 'color' => 'rose',    'icon' => 'warning-circle'],
    ];
    $filters = [
        'belum_diukur'     => ['label' => 'Belum Diukur',     'count' => $filterCounts['belum_diukur'] ?? 0, 'icon' => 'clock',        'dot' => 'bg-amber-400'],
        'absen_bulan_lalu' => ['label' => 'Absen Bulan Lalu', 'count' => $filterCounts['absen_bulan_lalu'] ?? 0, 'icon' => 'calendar-x', 'dot' => 'bg-slate-400'],
        'bayi_6_bln'       => ['label' => 'Bayi < 6 Bln',      'count' => $filterCounts['bayi_6_bln'] ?? 0, 'icon' => 'baby',          'dot' => 'bg-teal-500'],
        'selesai'          => ['label' => 'Sudah Diukur',     'count' => $filterCounts['selesai'] ?? 0, 'icon' => 'check-circle',   'dot' => 'bg-emerald-500'],
        'ditolak'          => ['label' => 'Perlu Revisi',     'count' => $filterCounts['ditolak'] ?? 0, 'icon' => 'warning',        'dot' => 'bg-rose-500'],
    ];
@endphp

<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 pb-6 flex flex-col gap-5 lg:gap-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Data Balita</h1>
            <p class="text-sm text-slate-500 mt-0.5">{{ $posyanduName ?? 'Posyandu' }} · {{ now()->translatedFormat('d F Y') }}</p>
        </div>
        <a href="{{ route('balita.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 h-11 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500/40">
            <x-icon name="plus" weight="bold" class="text-base" /> Balita Baru
        </a>
    </div>

    {{-- SUMMARY: DONUT CHART + STATS --}}
    <section class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 flex flex-col sm:flex-row items-center gap-6">
        {{-- Donut --}}
        <div class="relative w-32 h-32 shrink-0">
            <svg viewBox="0 0 36 36" class="w-32 h-32">
                <circle cx="18" cy="18" r="15.9" fill="none" stroke="#e2e8f0" stroke-width="3.4"></circle>
                <circle cx="18" cy="18" r="15.9" fill="none" stroke="#0d9488" stroke-width="3.4" stroke-linecap="round"
                        stroke-dasharray="{{ $percentage }} {{ max(0, 100 - $percentage) }}" pathLength="100"></circle>
            </svg>
            <div class="absolute inset-0 flex flex-col items-center justify-center">
                <span class="text-2xl font-bold text-slate-900 tabular-nums leading-none">{{ $percentage }}%</span>
                <span class="text-[10px] font-semibold text-slate-400 uppercase tracking-wide mt-1">Terukur</span>
            </div>
        </div>

        {{-- Stats --}}
        <div class="flex-1 w-full">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-3">Ringkasan Pengukuran</p>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                @foreach($stats as $stat)
                    <div class="rounded-xl bg-slate-50 border border-slate-100 p-3 flex items-center gap-2.5">
                        <span class="w-9 h-9 shrink-0 rounded-lg bg-{{ $stat['color'] }}-50 text-{{ $stat['color'] }}-600 flex items-center justify-center ring-1 ring-{{ $stat['color'] }}-100">
                            <x-icon name="{{ $stat['icon'] }}" weight="fill" class="text-[16px]" />
                        </span>
                        <div class="min-w-0">
                            <p class="text-xl font-bold tabular-nums leading-none {{ $stat['color'] === 'teal' ? 'text-slate-900' : 'text-' . $stat['color'] . '-600' }}">{{ $stat['value'] }}</p>
                            <p class="text-[10.5px] font-semibold text-slate-500 mt-0.5 truncate">{{ $stat['label'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <a href="{{ route('balita.index', ['filter' => 'belum_diukur']) }}" class="mt-4 inline-flex items-center gap-2 h-10 px-4 rounded-lg bg-teal-600 text-white hover:bg-teal-700 font-semibold text-[13px] transition-colors">
                <x-icon name="scales" weight="bold" /> Mulai Timbang
            </a>
        </div>
    </section>

    {{-- TOOLBAR: search + filter chips --}}
    <div class="flex flex-col lg:flex-row lg:items-center gap-3 lg:gap-4">
        <form action="{{ route('balita.index') }}" method="GET" class="relative w-full lg:max-w-xs">
            <x-icon name="magnifying-glass" weight="bold" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none" />
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau NIK balita…"
                   class="w-full h-11 pl-11 pr-4 rounded-xl bg-white border border-slate-200 focus:border-teal-300 focus:ring-4 focus:ring-teal-500/10 text-sm text-slate-700 placeholder:text-slate-400 transition-all focus:outline-none">
            @if(request('filter'))<input type="hidden" name="filter" value="{{ request('filter') }}">@endif
        </form>

        <div class="flex items-center gap-2 overflow-x-auto hide-scrollbar -mx-1 px-1 pb-1 flex-1 min-w-0">
            @foreach($filters as $key => $f)
                @php
                    $isActive = request('filter') === $key;
                    $href = $isActive ? route('balita.index') : route('balita.index', ['filter' => $key]);
                @endphp
                <a href="{{ $href }}"
                   class="shrink-0 inline-flex items-center gap-2 h-11 px-4 rounded-full text-[13px] font-semibold transition-all duration-200 active:scale-95 {{ $isActive ? 'bg-teal-600 text-white shadow-sm' : 'bg-white text-slate-600 border border-slate-200 hover:border-teal-300 hover:bg-teal-50 hover:text-teal-700' }}">
                    <span class="w-2 h-2 rounded-full {{ $f['dot'] }} shrink-0"></span>
                    {{ $f['label'] }}
                    <span class="inline-flex items-center justify-center min-w-[22px] h-[22px] px-1.5 rounded-full text-[11px] font-bold {{ $isActive ? 'bg-white/20 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $f['count'] }}</span>
                </a>
            @endforeach
        </div>
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
        <section class="flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">Prioritas Hari Ini</h2>
                    <p class="text-[12px] text-slate-500 mt-0.5">Balita yang memerlukan perhatian khusus</p>
                </div>
                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-rose-50 text-rose-700 border border-rose-200 text-[12px] font-semibold">
                    <x-icon name="bell-ringing" weight="fill" class="text-sm" /> {{ $priorityBalitas->count() }}
                </span>
            </div>
            <div class="flex gap-3 overflow-x-auto snap-x hide-scrollbar -mx-1 px-1 pb-1">
                @foreach($priorityBalitas as $balita)
                    <div class="w-[260px] shrink-0 snap-start flex"><x-child-card :balita="$balita" /></div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- DAFTAR BALITA --}}
        <section class="flex flex-col gap-3">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-900">{{ $isFiltered ? 'Hasil Filter' : 'Daftar Balita' }}</h2>
                    <p class="text-[12px] text-slate-500 mt-0.5">{{ $displayBalitas->count() }} balita ditampilkan</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-3 lg:gap-4 items-stretch">
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
