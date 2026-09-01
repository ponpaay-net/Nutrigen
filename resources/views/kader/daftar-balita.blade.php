@extends('layouts.app')
@section('page-title', 'Data Balita')
@section('content')

@php
    $isFiltered = request()->filled('filter') || request()->filled('q');
    $balitasCollection = collect($balitas ?? []);
    $priorityBalitas = $isFiltered ? collect([]) : $balitasCollection->filter(fn($b) => in_array($b['status_type'] ?? '', ['danger', 'warning']));
    $displayBalitas  = $isFiltered ? $balitasCollection : $balitasCollection->filter(fn($b) => !in_array($b['status_type'] ?? '', ['danger', 'warning']));
    $total      = count($balitas ?? []);
    $displayCount = $displayBalitas->count();
    $sudah      = (int) ($statSelesai ?? 0);
    $belum      = (int) ($statBelum ?? 0);
    $totalAnak  = $sudah + $belum;
    $percentage = $totalAnak > 0 ? round(($sudah / $totalAnak) * 100) : 0;
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

    {{-- CAPAIAN / PROGRESS (flat, profesional, teal hanya aksen) --}}
    <section class="bg-white border border-slate-200 rounded-xl p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-6">
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-2 text-slate-500">
                <x-icon name="scales" weight="bold" class="text-base text-teal-600" />
                <span class="text-xs font-semibold uppercase tracking-wide">Capaian Pengukuran</span>
            </div>
            <div class="flex items-baseline gap-2 mt-1.5">
                <span class="text-2xl sm:text-3xl font-bold tabular-nums text-slate-900 leading-none">{{ $sudah }}</span>
                <span class="text-sm text-slate-500">dari {{ $totalAnak }} balita terukur</span>
            </div>
        </div>
        <div class="sm:w-64 w-full shrink-0">
            <div class="flex items-center justify-between text-xs font-semibold text-slate-500 mb-1.5">
                <span>Progres</span>
                <span class="text-slate-900 tabular-nums">{{ $percentage }}%</span>
            </div>
            <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                <div class="h-full rounded-full bg-teal-500 transition-all" style="width: {{ $percentage }}%"></div>
            </div>
            <div class="flex items-center justify-between mt-2 text-[12px] font-medium text-slate-500">
                <span class="inline-flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai {{ $sudah }}</span>
                <span class="inline-flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span> Antrean {{ $belum }}</span>
            </div>
            <a href="{{ route('balita.index', ['filter' => 'belum_diukur']) }}" class="mt-3 inline-flex items-center gap-2 h-9 px-3.5 rounded-lg bg-teal-50 text-teal-700 hover:bg-teal-100 font-semibold text-[12.5px] transition-colors">
                <x-icon name="scales" weight="bold" /> Mulai Timbang
            </a>
        </div>
    </section>

    {{-- SEARCH + FILTER TIER --}}
    <div class="flex flex-col lg:flex-row lg:items-center gap-3 lg:gap-4">
        <form action="{{ route('balita.index') }}" method="GET" class="relative w-full lg:max-w-sm">
            <x-icon name="magnifying-glass" weight="bold" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none" />
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau NIK balita…"
                   class="w-full h-11 pl-11 pr-4 rounded-xl bg-white border border-slate-200 focus:border-teal-300 focus:ring-4 focus:ring-teal-500/10 text-sm text-slate-700 placeholder:text-slate-400 transition-all focus:outline-none">
            @if(request('filter'))<input type="hidden" name="filter" value="{{ request('filter') }}">@endif
        </form>

        <div class="flex items-center gap-2 overflow-x-auto hide-scrollbar -mx-1 px-1 pb-1 flex-1 min-w-0">
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
                <span class="text-[13px] font-semibold text-slate-500">{{ $displayCount }} balita</span>
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
