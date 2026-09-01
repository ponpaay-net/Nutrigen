@extends('layouts.app')
@section('page-title', 'Dashboard Kader')
@section('content')

@php
    $total = (int) ($statTotal ?? 0);
    $sudah = (int) ($statSudah ?? 0);
    $belum = (int) ($statBelum ?? max(0, $total - $sudah));
    $percent = $total > 0 ? min(100, round(($sudah / $total) * 100)) : 0;
    $todayFormatted = \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y');
    $cleanName = preg_replace('/\s*\(.*?\)/', '', $kaderName ?? 'Ibu Kader');
@endphp

<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 pt-6 sm:pt-8 pb-6 sm:pb-6 flex flex-col gap-5 lg:gap-6">

    {{-- PAGE HEADER (section) --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-lg sm:text-xl font-bold tracking-tight text-slate-900">Aktivitas Posyandu</h2>
            <p class="text-sm text-slate-500 mt-0.5">Catat pengukuran &amp; pantau status gizi balita.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('balita.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 h-11 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500/40">
                <x-icon name="user-plus" weight="bold" class="text-base" /> Balita Baru
            </a>
            <a href="{{ route('balita.index') }}"
               class="inline-flex items-center justify-center gap-2 px-4 h-11 rounded-xl bg-white hover:bg-teal-50 text-teal-700 border border-teal-200 text-sm font-semibold shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500/30">
                <x-icon name="scales" weight="bold" class="text-base" /> Mulai Timbang
            </a>
        </div>
    </div>

    {{-- CAPAIAN SESI (feature banner — TEAL) --}}
    <section class="rounded-2xl bg-teal-600 text-white p-5 sm:p-7 relative overflow-hidden shadow-sm">
        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6 items-center">
            <div>
                <p class="text-xs font-semibold uppercase tracking-widest text-teal-100">Capaian Sesi Ini</p>
                <div class="flex items-baseline gap-2 mt-2.5">
                    <span class="text-3xl sm:text-4xl font-bold tabular-nums leading-none">{{ $sudah }}</span>
                    <span class="text-teal-100 text-sm sm:text-base font-medium">dari {{ $total }} balita terukur</span>
                </div>
                <p class="mt-3 text-sm text-teal-50/90">Progres pengukuran bulan ini di posyandu Anda.</p>
            </div>
            <div class="rounded-2xl bg-white/10 border border-white/15 backdrop-blur p-5">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide text-teal-100">Progres Sesi</span>
                    <span class="text-lg font-bold tabular-nums">{{ $percent }}%</span>
                </div>
                <div class="w-full h-2.5 bg-white/20 rounded-full overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-teal-300 to-white transition-all" style="width: {{ $percent }}%"></div>
                </div>
                <div class="flex items-center justify-between mt-3.5 text-sm font-semibold">
                    <span class="inline-flex items-center gap-1.5 text-teal-50"><x-icon name="check-circle" weight="fill" /> Selesai {{ $sudah }}</span>
                    <span class="inline-flex items-center gap-1.5 text-teal-100"><x-icon name="clock" weight="fill" /> Antrean {{ $belum }}</span>
                </div>
            </div>
        </div>
    </section>

    {{-- REVISI ALERT --}}
    @if(($statRevisi ?? 0) > 0)
    <div class="rounded-2xl bg-amber-50 border border-amber-200 border-l-4 border-l-amber-500 p-4 flex items-start sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="shrink-0 text-amber-600"><x-icon name="warning-circle" weight="fill" class="text-xl" /></div>
            <div>
                <h3 class="text-sm font-semibold text-amber-900">Perlu Tindakan: <span class="tabular-nums">{{ $statRevisi }}</span> Data Balita Perlu Koreksi</h3>
                <p class="text-sm text-amber-800 mt-0.5">Puskesmas memberikan catatan verifikasi. Tinjau &amp; perbaiki.</p>
            </div>
        </div>
        <a href="{{ route('balita.index', ['filter' => 'ditolak']) }}"
           class="inline-flex items-center justify-center gap-1.5 px-4 h-10 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl transition-colors shrink-0 focus:outline-none focus:ring-2 focus:ring-amber-400">
            Tinjau Catatan <x-icon name="arrow-right" weight="bold" />
        </a>
    </div>
    @endif

    {{-- KPI METRICS --}}
    <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $kpis = [
                ['label' => 'Total Balita', 'value' => $total, 'sub' => $todayFormatted, 'icon' => 'users', 'color' => 'teal'],
                ['label' => 'Sudah Diukur', 'value' => $sudah, 'sub' => $percent . '% tercapai', 'icon' => 'check-circle', 'color' => 'emerald'],
                ['label' => 'Belum Diukur', 'value' => $belum, 'sub' => 'Antrean', 'icon' => 'clock', 'color' => 'amber'],
                ['label' => 'Perlu Pantauan', 'value' => $statPerlu ?? 0, 'sub' => 'Prioritas', 'icon' => 'activity', 'color' => 'rose'],
            ];
        @endphp
        @foreach($kpis as $kpi)
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-4 sm:p-5 flex flex-col">
            <div class="flex items-center justify-between">
                <span class="w-10 h-10 rounded-xl bg-{{ $kpi['color'] }}-50 text-{{ $kpi['color'] }}-600 flex items-center justify-center">
                    <x-icon name="{{ $kpi['icon'] }}" weight="fill" class="text-lg" />
                </span>
                <x-icon name="arrow-up-right" weight="bold" class="text-slate-300" />
            </div>
            <p class="mt-3 text-2xl sm:text-3xl font-bold tabular-nums text-slate-900 leading-none">{{ $kpi['value'] }}</p>
            <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-500 truncate">{{ $kpi['label'] }}</p>
            <p class="mt-1.5 text-xs text-slate-400 truncate">{{ $kpi['sub'] }}</p>
        </div>
        @endforeach
    </section>

    {{-- WORKSPACE --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6">

        {{-- PRIORITAS PEMANTAUAN GIZI --}}
        <section class="lg:col-span-7 flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Prioritas Pemantauan Gizi</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Balita dengan catatan gizi khusus yang perlu pendampingan</p>
                </div>
                <a href="{{ route('balita.index') }}" class="hidden sm:inline-flex text-sm font-medium text-teal-600 hover:text-teal-700 items-center gap-1 focus:outline-none focus:underline">
                    Semua balita <x-icon name="arrow-right" weight="bold" />
                </a>
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <ul class="divide-y divide-slate-100">
                    @forelse($priorityChildren ?? [] as $child)
                        @php
                            $isDanger = ($child->statusType ?? 'warning') === 'danger';
                            $isBoy = ($child->gender ?? 'L') === 'L';
                            $initials = strtoupper(substr($child->name ?? 'AN', 0, 2));
                        @endphp
                        <li>
                            <a href="{{ route('balita.show', $child->id) }}" class="flex items-center gap-4 p-4 sm:p-5 hover:bg-slate-50 transition-colors focus:outline-none">
                                <div class="shrink-0 w-11 h-11 rounded-full text-sm font-bold flex items-center justify-center {{ $isDanger ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">{{ $initials }}</div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-semibold text-slate-900 truncate">{{ Str::title($child->name) }}</h3>
                                        <span class="shrink-0 px-1.5 py-0.5 rounded-md bg-slate-100 text-slate-600 text-[11px] font-semibold">{{ $isBoy ? 'L' : 'P' }}</span>
                                    </div>
                                    <div class="mt-0.5 text-[13px] text-slate-500 flex items-center gap-1.5 truncate">
                                        <span class="truncate">Ibu {{ $child->mother ?? '-' }}</span>
                                        <span class="text-slate-300">•</span>
                                        <span class="shrink-0 tabular-nums">{{ $child->age }}</span>
                                    </div>
                                </div>
                                <div class="shrink-0 flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $isDanger ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }} text-[11px] font-semibold">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $isDanger ? 'bg-rose-500' : 'bg-amber-500' }}"></span>
                                        {{ $child->shortStatus ?? 'Gizi' }}
                                    </span>
                                    <x-icon name="caret-right" weight="bold" class="text-slate-300" />
                                </div>
                            </a>
                        </li>
                    @empty
                        <li class="p-10 text-center">
                            <div class="w-10 h-10 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 text-xl"><x-icon name="check-circle" weight="fill" /></div>
                            <p class="text-sm font-semibold text-slate-900">Seluruh balita terpantau baik</p>
                            <p class="text-sm text-slate-500 mt-1">Tidak ada balita yang memerlukan tindakan gizi khusus saat ini.</p>
                        </li>
                    @endforelse
                </ul>
                <div class="border-t border-slate-100 bg-slate-50/50 sm:hidden">
                    <a href="{{ route('balita.index') }}" class="flex items-center justify-center gap-1.5 w-full h-12 text-sm font-semibold text-teal-600 focus:outline-none active:bg-slate-100 transition-colors">Semua balita <x-icon name="arrow-right" weight="bold" /></a>
                </div>
            </div>
        </section>

        {{-- RIGHT: AGENDA + REKAP --}}
        <section class="lg:col-span-5 flex flex-col gap-5">
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Agenda Posyandu</h2>
                        <p class="text-sm text-slate-500 mt-0.5">Sesi penimbangan terdekat</p>
                    </div>
                    <a href="{{ route('jadwal.index') }}" class="text-sm font-medium text-teal-600 hover:text-teal-700 flex items-center gap-1 focus:outline-none focus:underline">Semua <x-icon name="arrow-right" weight="bold" /></a>
                </div>

                @if(isset($jadwalTerdekat) && $jadwalTerdekat)
                    <a href="{{ route('jadwal.show', $jadwalTerdekat['id']) }}" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm group flex items-start gap-4 hover:border-teal-300 focus:outline-none focus:ring-2 focus:ring-teal-400">
                        <div class="w-14 h-14 rounded-xl bg-teal-50 text-teal-700 flex flex-col items-center justify-center shrink-0 border border-teal-200">
                            <span class="text-[11px] font-bold uppercase tracking-wider">{{ $jadwalTerdekat['tgl_bulan'] ?? 'AGT' }}</span>
                            <span class="text-xl font-bold leading-none tabular-nums mt-0.5">{{ $jadwalTerdekat['tgl_nomor'] ?? '23' }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-semibold text-slate-900 leading-snug mb-1.5 group-hover:text-teal-700 transition-colors">{{ $jadwalTerdekat['judul'] }}</h3>
                            <div class="text-[13px] text-slate-500 flex flex-col gap-1.5 mb-3">
                                <div class="flex items-center gap-1.5 truncate"><x-icon name="clock" class="shrink-0 text-slate-400" /> <span class="truncate">{{ $jadwalTerdekat['waktu'] }}</span></div>
                                <div class="flex items-center gap-1.5 truncate"><x-icon name="map-pin" class="shrink-0 text-slate-400" /> <span class="truncate">{{ $jadwalTerdekat['lokasi'] }}</span></div>
                            </div>
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-teal-600 text-white text-xs font-bold rounded-full"><x-icon name="hourglass" weight="bold" /> {{ $jadwalTerdekat['countdown'] }}</span>
                        </div>
                    </a>
                @else
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 text-center text-slate-500 shadow-sm">
                        <p class="text-sm font-medium text-slate-900">Belum ada agenda jadwal</p>
                        <a href="{{ route('jadwal.create') }}" class="text-sm font-medium text-teal-600 hover:underline mt-1 inline-block focus:outline-none">+ Buat jadwal posyandu</a>
                    </div>
                @endif
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0"><x-icon name="download-simple" weight="bold" class="text-lg" /></div>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 mb-0.5">Rekap Laporan Bulanan</h3>
                        <p class="text-[13px] text-slate-500">Ekspor data untuk Puskesmas.</p>
                    </div>
                </div>
                <a href="{{ route('laporan.index') }}" class="w-full sm:w-auto px-4 h-10 bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-sm font-semibold rounded-xl transition-colors flex items-center justify-center gap-2 shrink-0 focus:ring-2 focus:ring-teal-500 focus:outline-none">Buka <x-icon name="arrow-right" weight="bold" /></a>
            </div>
        </section>

    </div>
</div>
@endsection
