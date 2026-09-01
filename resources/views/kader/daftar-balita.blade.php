@extends('layouts.app')
@section('page-title', 'Data Balita')
@section('content')

@php
    $isFiltered = request()->filled('filter') || request()->filled('q');
    $balitasCollection = collect($balitas ?? []);
    $priorityBalitas = $isFiltered ? collect([]) : $balitasCollection->filter(fn($b) => in_array($b['status_type'] ?? '', ['danger', 'warning']));
    $displayBalitas  = $isFiltered ? $balitasCollection : $balitasCollection->filter(fn($b) => !in_array($b['status_type'] ?? '', ['danger', 'warning']));
    $sudah      = (int) ($statSelesai ?? 0);
    $belum      = (int) ($statBelum ?? 0);
    $totalAnak  = $sudah + $belum;
    $percentage = $totalAnak > 0 ? round(($sudah / $totalAnak) * 100) : 0;
    $revisi     = (int) ($filterCounts['ditolak'] ?? 0);

    $filters = [
        'belum_diukur'     => ['label' => 'Belum Diukur',      'count' => $filterCounts['belum_diukur'] ?? 0, 'icon' => 'clock',        'dot' => 'bg-amber-400'],
        'absen_bulan_lalu' => ['label' => 'Absen Bulan Lalu',  'count' => $filterCounts['absen_bulan_lalu'] ?? 0, 'icon' => 'calendar-x', 'dot' => 'bg-slate-400'],
        'bayi_6_bln'       => ['label' => 'Bayi < 6 Bln',       'count' => $filterCounts['bayi_6_bln'] ?? 0, 'icon' => 'baby',          'dot' => 'bg-teal-500'],
        'selesai'          => ['label' => 'Sudah Diukur',      'count' => $filterCounts['selesai'] ?? 0, 'icon' => 'check-circle',   'dot' => 'bg-emerald-500'],
        'ditolak'          => ['label' => 'Perlu Revisi',      'count' => $filterCounts['ditolak'] ?? 0, 'icon' => 'warning',        'dot' => 'bg-rose-500'],
    ];
@endphp

<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 pb-6">

    {{-- PAGE HEADER --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-5 lg:mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Data Balita</h1>
            <p class="text-sm text-slate-500 mt-0.5">{{ $posyanduName ?? 'Posyandu' }} · {{ now()->translatedFormat('d F Y') }}</p>
        </div>
        <a href="{{ route('balita.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 h-11 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500/40">
            <x-icon name="plus" weight="bold" class="text-base" /> Balita Baru
        </a>
    </div>

    @if(request('q') && $priorityBalitas->isEmpty() && $displayBalitas->isEmpty())
        {{-- EMPTY STATE (search) --}}
        <div class="flex flex-col items-center justify-center text-center py-20 px-6 gap-2 bg-white border border-slate-200 rounded-2xl shadow-sm">
            <div class="w-12 h-12 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center mb-1">
                <x-icon name="magnifying-glass" weight="bold" class="text-xl text-slate-300" />
            </div>
            <h3 class="text-[15px] font-semibold text-slate-800">Tidak ditemukan</h3>
            <p class="text-[13px] text-slate-400 max-w-xs">Tidak ada balita dengan nama atau NIK "<span class="text-slate-600 font-medium">{{ request('q') }}</span>".</p>
            <a href="{{ route('balita.index') }}" class="mt-1 text-[13px] font-semibold text-teal-600 hover:text-teal-700 transition-colors">Tampilkan semua</a>
        </div>
    @else

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6 items-start">

        {{-- ══ LEFT: CONTROL PANEL (sticky) ══ --}}
        <aside class="lg:col-span-4 xl:col-span-3 space-y-4 lg:sticky lg:top-20">
            {{-- Capaian --}}
            <div class="bg-white border border-slate-200 rounded-xl p-4">
                <div class="flex items-center gap-2 text-slate-500">
                    <x-icon name="scales" weight="bold" class="text-base text-teal-600" />
                    <span class="text-xs font-semibold uppercase tracking-wide">Capaian Pengukuran</span>
                </div>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="text-2xl font-bold tabular-nums text-slate-900 leading-none">{{ $sudah }}</span>
                    <span class="text-sm text-slate-500">dari {{ $totalAnak }} balita</span>
                </div>
                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden mt-3">
                    <div class="h-full rounded-full bg-teal-500" style="width: {{ $percentage }}%"></div>
                </div>
                <div class="mt-2 text-[12px] text-slate-500 font-medium flex justify-between">
                    <span class="inline-flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai {{ $sudah }}</span>
                    <span class="inline-flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-slate-300"></span> Antrean {{ $belum }}</span>
                </div>
                <a href="{{ route('balita.index', ['filter' => 'belum_diukur']) }}" class="mt-3 inline-flex items-center gap-2 h-9 px-3.5 rounded-lg bg-teal-50 text-teal-700 hover:bg-teal-100 font-semibold text-[12.5px] transition-colors">
                    <x-icon name="scales" weight="bold" /> Mulai Timbang
                </a>
            </div>

            {{-- Search --}}
            <div class="bg-white border border-slate-200 rounded-xl p-4">
                <label for="search-balita" class="text-xs font-semibold uppercase tracking-wide text-slate-500">Cari Balita</label>
                <form action="{{ route('balita.index') }}" method="GET" class="relative mt-2">
                    <x-icon name="magnifying-glass" weight="bold" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none" />
                    <input id="search-balita" type="text" name="q" value="{{ request('q') }}" placeholder="Nama atau NIK…"
                           class="w-full h-10 pl-10 pr-4 rounded-lg bg-slate-50 border border-slate-200 focus:border-teal-300 focus:ring-4 focus:ring-teal-500/10 focus:bg-white text-sm text-slate-700 placeholder:text-slate-400 transition-all focus:outline-none">
                    @if(request('filter'))<input type="hidden" name="filter" value="{{ request('filter') }}">@endif
                </form>
            </div>

            {{-- Filters (vertical list) --}}
            <div class="bg-white border border-slate-200 rounded-xl p-2">
                <p class="px-2 pt-2 pb-1 text-xs font-semibold uppercase tracking-wide text-slate-500">Filter</p>
                <div class="flex flex-col gap-0.5">
                    @foreach($filters as $key => $f)
                        @php
                            $isActive = request('filter') === $key || (!request('filter') && $key === 'belum_diukur');
                            $href = $isActive ? route('balita.index') : route('balita.index', ['filter' => $key]);
                        @endphp
                        <a href="{{ $href }}"
                           class="flex items-center gap-2.5 px-2 py-2 rounded-lg text-[13px] font-medium transition-colors {{ $isActive ? 'bg-teal-50 text-teal-700' : 'text-slate-600 hover:bg-slate-50' }}">
                            <span class="w-2 h-2 rounded-full {{ $f['dot'] }} shrink-0"></span>
                            <span class="flex-1 truncate">{{ $f['label'] }}</span>
                            <span class="inline-flex items-center justify-center min-w-[22px] h-[22px] px-1.5 rounded-full text-[11px] font-bold {{ $isActive ? 'bg-teal-600 text-white' : 'bg-slate-100 text-slate-500' }}">{{ $f['count'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        </aside>

        {{-- ══ RIGHT: CONTENT ══ --}}
        <main class="lg:col-span-8 xl:col-span-9 space-y-5 lg:space-y-6">

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

            {{-- DEFAULT / HASIL --}}
            <section class="flex flex-col gap-3">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-base font-bold text-slate-900">{{ $isFiltered ? 'Hasil Pencarian' : 'Daftar Balita' }}</h2>
                        <p class="text-[12px] text-slate-500 mt-0.5">{{ $displayBalitas->count() }} balita ditampilkan</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3 lg:gap-4 items-stretch">
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

        </main>
    </div>

    @endif
</div>
@endsection
