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

    {{-- PAGE HEADER (judulnya ada di topbar; di sini subtitle + aksi utama) --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <p class="text-sm font-medium text-slate-500">{{ $posyanduName ?? 'Posyandu' }} <span class="text-slate-300 mx-1">·</span> {{ now()->translatedFormat('d F Y') }}</p>
            <p class="text-[13px] text-slate-400 mt-0.5">Kelola data & pantau pengukuran balita di posyandu Anda.</p>
        </div>
        <a href="{{ route('balita.create') }}"
           class="inline-flex items-center justify-center gap-2 px-4 h-11 w-full sm:w-auto rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500/40">
            <x-icon name="plus" weight="bold" class="text-base" /> Balita Baru
        </a>
    </div>

    {{-- TOOLBAR: search + filter dropdown (Naik ke paling atas untuk kecepatan akses lapangan) --}}
    <div class="flex flex-col gap-3">
        <form action="{{ route('balita.index') }}" method="GET" class="flex flex-col sm:flex-row gap-3 sm:items-center">
            <div class="relative w-full sm:flex-1">
                <x-icon name="magnifying-glass" weight="bold" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none" />
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Ketik nama anak atau NIK balita…" aria-label="Cari balita berdasarkan nama atau NIK"
                       class="w-full h-12 pl-11 pr-4 rounded-xl bg-white border border-slate-200 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/15 text-[14.5px] text-slate-800 placeholder:text-slate-400 shadow-sm transition-all focus:outline-none">
            </div>

            <div class="relative w-full sm:w-auto">
                <x-icon name="funnel" weight="bold" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-lg pointer-events-none transition-colors {{ request('filter') ? 'text-teal-600' : 'text-slate-400' }}" />
                <select name="filter" onchange="this.form.submit()" aria-label="Filter balita"
                        class="w-full sm:w-[240px] h-12 pl-11 pr-12 rounded-xl bg-white border border-slate-200 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/15 text-sm font-semibold text-slate-700 appearance-none cursor-pointer shadow-sm transition-all focus:outline-none">
                    <option value="">Semua Kategori ({{ $total }})</option>
                    @foreach($filters as $key => $f)
                        <option value="{{ $key }}" @if(request('filter') === $key) selected @endif>{{ $f['label'] }} ({{ $f['count'] }})</option>
                    @endforeach
                </select>
                <x-icon name="caret-down" weight="bold" class="absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base pointer-events-none" />
            </div>
        </form>

        {{-- Quick Tap Filter Pills (Memudahkan jempol HP kader tanpa harus buka select dropdown) --}}
        <div class="flex items-center gap-2 overflow-x-auto hide-scrollbar py-0.5 -mx-1 px-1">
            <a href="{{ route('balita.index') }}" 
               class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-full text-[12.5px] font-semibold border transition-all shrink-0 {{ !request('filter') ? 'bg-teal-600 border-teal-600 text-white shadow-sm' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                Semua ({{ $total }})
            </a>
            @foreach($filters as $key => $f)
                @php $isActive = request('filter') === $key; @endphp
                <a href="{{ route('balita.index', ['filter' => $key, 'q' => request('q')]) }}" 
                   class="inline-flex items-center gap-1.5 h-9 px-3.5 rounded-full text-[12.5px] font-semibold border transition-all shrink-0 {{ $isActive ? 'bg-teal-600 border-teal-600 text-white shadow-sm' : 'bg-white border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    <span class="w-2 h-2 rounded-full {{ $isActive ? 'bg-white' : $f['dot'] }} shrink-0"></span>
                    <span>{{ $f['label'] }}</span>
                    <span class="text-[11px] px-1.5 py-0.2 rounded-full {{ $isActive ? 'bg-teal-700 text-teal-100' : 'bg-slate-100 text-slate-600' }}">{{ $f['count'] }}</span>
                </a>
            @endforeach
        </div>
    </div>

    {{-- SUMMARY: RINGKASAN PROGRESS BULAN INI --}}
    <section class="bg-white border border-slate-200 rounded-2xl shadow-sm p-4 sm:p-5">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="relative w-16 h-16 shrink-0 rounded-full bg-teal-50 flex items-center justify-center">
                    <svg viewBox="0 0 36 36" class="w-16 h-16">
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#ccfbf1" stroke-width="3.6"></circle>
                        <circle cx="18" cy="18" r="15.9" fill="none" stroke="#0d9488" stroke-width="3.6" stroke-linecap="{{ $percentage > 0 ? 'round' : 'butt' }}"
                                stroke-dasharray="{{ $percentage }} {{ max(0, 100 - $percentage) }}" pathLength="100"></circle>
                    </svg>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-xs font-bold text-slate-900 tabular-nums leading-none">{{ $percentage }}%</span>
                    </div>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Progres Penimbangan Bulan Ini</h3>
                    <p class="text-[12.5px] text-slate-500 mt-0.5"><span class="font-semibold text-teal-700">{{ $sudah }}</span> dari {{ $total }} balita telah terukur (<span class="font-semibold text-amber-600">{{ $belum }}</span> balita masih antre).</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                @foreach($stats as $stat)
                    <div class="flex-1 sm:flex-initial px-3 py-2 rounded-xl bg-slate-50 border border-slate-100 flex items-center gap-2">
                        <x-icon name="{{ $stat['icon'] }}" weight="fill" class="text-sm text-{{ $stat['color'] }}-600" />
                        <div>
                            <p class="text-xs font-bold text-slate-800 leading-none tabular-nums">{{ $stat['value'] }}</p>
                            <p class="text-[10px] text-slate-500 mt-0.5 truncate">{{ $stat['label'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    @if(request('filter') || request('q'))
    <div class="flex flex-wrap items-center gap-2 text-[12px] text-slate-500">
        @php $activeFilterKey = request('filter'); @endphp
        <span class="inline-flex items-center gap-1.5 font-medium">
            <x-icon name="funnel" weight="bold" class="text-[13px] text-slate-400" />
            Menampilkan:
        </span>
        @if($activeFilterKey && isset($filters[$activeFilterKey]))
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-teal-50 border border-teal-100 text-teal-700 font-bold">{{ $filters[$activeFilterKey]['label'] }}</span>
        @endif
        @if(request('q'))
            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-100 border border-slate-200 text-slate-600 font-bold">"{{ request('q') }}"</span>
        @endif
        <a href="{{ route('balita.index') }}" class="ml-1 inline-flex items-center gap-1 font-semibold text-slate-400 hover:text-rose-600 transition-colors">
            <x-icon name="x" weight="bold" class="text-[13px]" /> Hapus filter
        </a>
    </div>
    @endif

    @if(request('q') && $priorityBalitas->isEmpty() && $displayBalitas->isEmpty())
        {{-- EMPTY STATE (search) --}}
        <div class="flex flex-col items-center justify-center text-center py-20 px-6 gap-3 bg-slate-50/50 border border-slate-200/60 rounded-3xl shadow-sm border-dashed">
            <div class="w-14 h-14 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center mb-1">
                <x-icon name="magnifying-glass" weight="bold" class="text-2xl text-slate-400" />
            </div>
            <div>
                <h3 class="text-[16px] font-bold text-slate-800">Tidak ditemukan</h3>
                <p class="text-[13px] text-slate-500 max-w-xs mt-1">Tidak ada balita dengan nama atau NIK "<span class="text-slate-700 font-semibold">{{ request('q') }}</span>".</p>
            </div>
            <a href="{{ route('balita.index') }}" class="mt-2 text-[13px] font-semibold text-teal-600 hover:text-teal-700 bg-teal-50 hover:bg-teal-100 px-4 py-2 rounded-xl transition-colors inline-flex items-center gap-2">
                <x-icon name="arrow-left" weight="bold" class="text-[12px]" /> Kembali ke Semua Data
            </a>
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
                    <p class="text-[12px] text-slate-500 mt-0.5">
                        @if(!$isFiltered && $priorityBalitas->isNotEmpty())
                            {{ $totalShown }} balita lainnya
                        @else
                            {{ $totalShown }} balita
                        @endif
                    </p>
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
                    <div class="col-span-full flex flex-col items-center justify-center text-center py-20 px-6 gap-3 bg-slate-50/50 border border-slate-200/60 rounded-3xl shadow-sm border-dashed">
                        <div class="w-14 h-14 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center justify-center mb-1 text-teal-600">
                            <x-icon name="check-circle" weight="fill" class="text-3xl" />
                        </div>
                        <div>
                            <p class="text-[16px] font-bold text-slate-800">{{ $emptyTitle }}</p>
                            <p class="text-[13px] font-medium text-slate-500 max-w-sm leading-relaxed mt-1">{{ $emptySub }}</p>
                        </div>
                        @if($activeFilter)
                            <a href="{{ route('balita.index') }}" class="mt-2 text-[13px] font-semibold text-teal-600 hover:text-teal-700 bg-teal-50 hover:bg-teal-100 px-4 py-2 rounded-xl transition-colors inline-flex items-center gap-2">
                                <x-icon name="arrow-left" weight="bold" class="text-[12px]" /> Hapus Filter
                            </a>
                        @endif
                    </div>
                @endforelse
            </div>

            @if($balitas->hasPages())
                <div class="flex justify-center pt-1">
                    {{ $balitas->links('partials.pagination') }}
                </div>
            @endif
        </section>

    @endif
</div>
@endsection
