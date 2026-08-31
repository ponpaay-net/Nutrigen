@extends('layouts.app')
@section('page-title', 'Dashboard')
@section('content')

@php
    $total = (int) ($statTotal ?? 0);
    $sudah = (int) ($statSudah ?? 0);
    $belum = (int) ($statBelum ?? max(0, $total - $sudah));
    $percent = $total > 0 ? min(100, round(($sudah / $total) * 100)) : 0;
    $todayFormatted = \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y');
    $todayShort = \Carbon\Carbon::now()->locale('id')->translatedFormat('d M Y');

    // Clean greeting + dedup role from name
    $cleanName = preg_replace('/\s*\(.*?\)/', '', $kaderName ?? 'Ibu Kader');
    preg_match('/\((.*?)\)/', $kaderName ?? '', $roleMatch);
    $roleText = $roleMatch[1] ?? null;

    // Time-based greeting
    $hour = (int) now()->hour;
    $greeting = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
@endphp

<div class="w-full min-h-screen bg-slate-50 pb-24 sm:pb-20 lg:pb-10 text-slate-800 antialiased font-sans selection:bg-cyan-100 selection:text-cyan-900">
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-5 sm:py-8 flex flex-col gap-5 sm:gap-6">

        {{-- 1. HERO GREETING (balanced) --}}
        <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-cyan-500 via-cyan-600 to-cyan-800 p-6 sm:p-7 text-white shadow-lg shadow-cyan-700/20">
            <div class="absolute -top-16 -right-10 w-52 h-52 bg-white/10 rounded-full blur-2xl pointer-events-none"></div>

            <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6 items-center">
                {{-- Left: greeting + actions --}}
                <div>
                    <div class="flex items-center gap-2 text-cyan-100 text-sm font-medium mb-3">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/20">
                            <x-icon name="calendar-blank" class="text-cyan-100/90" /> {{ $todayFormatted }}
                        </span>
                    </div>

                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-white leading-tight">
                        {{ $activityLocation ?? 'Posyandu' }}
                    </h1>

                    @if($roleText)
                        <span class="inline-flex mt-2.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-white/15 text-white border border-white/20">{{ $roleText }}</span>
                    @endif

                    <div class="flex flex-col sm:flex-row gap-2.5 sm:items-center mt-5">
                        <a href="{{ route('balita.index') }}" class="inline-flex items-center justify-center gap-2 px-4 min-h-[48px] bg-white text-cyan-700 hover:bg-cyan-50 rounded-full text-sm font-semibold transition-colors focus:ring-2 focus:ring-white/50 focus:outline-none shadow-sm">
                            <x-icon name="scales" weight="bold" class="text-cyan-600" /> Mulai Timbang
                        </a>
                        <a href="{{ route('balita.create') }}" class="inline-flex items-center justify-center gap-2 px-4 min-h-[48px] bg-white/10 hover:bg-white/20 text-white border border-white/30 rounded-full text-sm font-semibold transition-colors focus:ring-2 focus:ring-white/40 focus:outline-none">
                            <x-icon name="user-plus" weight="bold" class="text-white" /> Balita Baru
                        </a>
                    </div>
                </div>

                {{-- Right: session completion panel (fills space, useful) --}}
                <div class="rounded-2xl bg-white/10 border border-white/15 backdrop-blur p-5 sm:p-6">
                    <div class="flex items-center justify-between mb-2">
                        <span class="text-xs font-semibold uppercase tracking-wide text-cyan-100">Capaian Sesi Ini</span>
                        <span class="text-sm font-bold text-white tabular-nums">{{ $percent }}%</span>
                    </div>
                    <div class="flex items-baseline gap-2 mb-3">
                        <span class="text-3xl font-bold text-white tabular-nums leading-none">{{ $sudah }}</span>
                        <span class="text-cyan-100/80 text-sm font-medium">dari {{ $total }} balita</span>
                    </div>
                    <div class="w-full h-2.5 bg-white/20 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-gradient-to-r from-emerald-300 to-white transition-all" style="width: {{ $percent }}%"></div>
                    </div>
                    <div class="flex items-center justify-between mt-3 text-sm font-medium">
                        <span class="inline-flex items-center gap-1.5 text-emerald-200"><x-icon name="check-circle" weight="fill" /> Selesai {{ $sudah }}</span>
                        <span class="inline-flex items-center gap-1.5 text-amber-200"><x-icon name="clock" weight="fill" /> Antrean {{ $belum }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- 2. ALERT REVISI --}}
        @if(isset($statRevisi) && $statRevisi > 0)
        <div class="rounded-2xl bg-amber-50 border border-amber-200 p-4 flex items-start sm:items-center justify-between gap-4 border-l-4 border-l-amber-500">
            <div class="flex items-start gap-3">
                <div class="shrink-0 text-amber-600"><x-icon name="warning-circle" weight="fill" class="text-xl" /></div>
                <div>
                    <h3 class="text-sm font-semibold text-amber-900 mb-0.5">Perlu Tindakan: <span class="tabular-nums">{{ $statRevisi }}</span> Data Balita Perlu Koreksi</h3>
                    <p class="text-sm text-amber-800">Puskesmas memberikan catatan verifikasi. Tinjau &amp; perbaiki data penimbangan.</p>
                </div>
            </div>
            <a href="{{ route('balita.index', ['filter' => 'ditolak']) }}" class="inline-flex items-center justify-center gap-1.5 px-4 min-h-[44px] bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-xl transition-colors shrink-0 focus:ring-2 focus:ring-amber-400 focus:outline-none">
                Tinjau Catatan <x-icon name="arrow-right" weight="bold" />
            </a>
        </div>
        @endif

        {{-- 3. KPI METRICS (full, rich cards) --}}
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">

            {{-- Total --}}
            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="h-1 bg-cyan-500"></div>
                <div class="p-4 sm:p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 truncate">Total Balita</p>
                            <p class="mt-2 text-3xl sm:text-4xl font-bold text-cyan-700 tabular-nums leading-none">{{ $total }}</p>
                        </div>
                        <span class="shrink-0 w-10 h-10 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center transition-colors"><x-icon name="users" weight="fill" class="text-lg" /></span>
                    </div>
                    <p class="mt-3 pt-3 border-t border-slate-100 text-xs text-slate-500">{{ $todayShort }}</p>
                </div>
            </div>

            {{-- Sudah Diukur --}}
            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="h-1 bg-emerald-500"></div>
                <div class="p-4 sm:p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-wide text-emerald-600 truncate">Sudah Diukur</p>
                            <p class="mt-2 text-3xl sm:text-4xl font-bold text-emerald-600 tabular-nums leading-none">{{ $sudah }}<span class="text-base font-semibold text-slate-400">/{{ $total }}</span></p>
                        </div>
                        <span class="shrink-0 w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center"><x-icon name="check-circle" weight="fill" class="text-lg" /></span>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100">
                        <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full bg-gradient-to-r from-emerald-400 to-emerald-600 transition-all" style="width: {{ $percent }}%"></div>
                        </div>
                        <p class="mt-1.5 text-xs font-semibold text-emerald-600 tabular-nums">{{ $percent }}% tercapai</p>
                    </div>
                </div>
            </div>

            {{-- Belum Diukur --}}
            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="h-1 bg-amber-500"></div>
                <div class="p-4 sm:p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-wide text-amber-600 truncate">Belum Diukur</p>
                            <p class="mt-2 text-3xl sm:text-4xl font-bold text-amber-600 tabular-nums leading-none">{{ $belum }}</p>
                        </div>
                        <span class="shrink-0 w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center"><x-icon name="clock" weight="fill" class="text-lg" /></span>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100">
                        <a href="{{ route('balita.index', ['filter' => 'belum_diukur']) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-cyan-600 hover:text-cyan-700 focus:outline-none focus:underline">Lihat antrean <x-icon name="arrow-right" weight="bold" /></a>
                    </div>
                </div>
            </div>

            {{-- Perlu Pantauan --}}
            <div class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden flex flex-col">
                <div class="h-1 bg-rose-500"></div>
                <div class="p-4 sm:p-5 flex flex-col flex-1">
                    <div class="flex items-start justify-between gap-2">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold uppercase tracking-wide text-rose-600 truncate">Perlu Pantauan</p>
                            <p class="mt-2 text-3xl sm:text-4xl font-bold text-rose-600 tabular-nums leading-none">{{ $statPerlu ?? count($priorityChildren ?? []) }}</p>
                        </div>
                        <span class="shrink-0 w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center"><x-icon name="activity" weight="fill" class="text-lg" /></span>
                    </div>
                    <div class="mt-3 pt-3 border-t border-slate-100">
                        <a href="{{ route('balita.index', ['filter' => 'absen_bulan_lalu']) }}" class="inline-flex items-center gap-1 text-xs font-semibold text-cyan-600 hover:text-cyan-700 focus:outline-none focus:underline">Daftar pantau <x-icon name="arrow-right" weight="bold" /></a>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. WORKSPACE --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 sm:gap-6">

            {{-- Prioritas Pemantauan Gizi (7-col) --}}
            <div class="lg:col-span-7 flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-semibold text-slate-900">Prioritas Pemantauan Gizi</h2>
                        <p class="text-sm text-slate-500 mt-0.5">Balita dengan catatan gizi khusus yang memerlukan pendampingan</p>
                    </div>
                    <a href="{{ route('balita.index') }}" class="hidden sm:inline-flex text-sm font-medium text-cyan-600 hover:text-cyan-700 items-center gap-1 focus:outline-none focus:underline">Semua balita <x-icon name="arrow-right" weight="bold" /></a>
                </div>

                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden flex flex-col">
                    <ul class="divide-y divide-slate-100">
                        @forelse($priorityChildren ?? [] as $child)
                            @php
                                $isDanger = ($child->statusType ?? 'warning') === 'danger';
                                $isBoy = ($child->gender ?? 'L') === 'L';
                                $initials = strtoupper(substr($child->name ?? 'AN', 0, 2));
                            @endphp
                            <li>
                                <a href="{{ route('balita.show', $child->id) }}" class="flex items-center gap-4 p-4 sm:p-5 hover:bg-slate-50 transition-colors focus:bg-slate-50 focus:outline-none">
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
                    <div class="border-t border-slate-100 bg-slate-50/50 sm:hidden mt-auto">
                        <a href="{{ route('balita.index') }}" class="flex items-center justify-center gap-1.5 w-full min-h-[48px] text-sm font-semibold text-cyan-600 focus:outline-none focus:bg-slate-100 active:bg-slate-100 transition-colors">Semua balita <x-icon name="arrow-right" weight="bold" /></a>
                    </div>
                </div>
            </div>

            {{-- Right (5-col): Agenda + Export --}}
            <div class="lg:col-span-5 flex flex-col gap-5 sm:gap-6">
                <div class="flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <h2 class="text-lg font-semibold text-slate-900">Agenda Posyandu</h2>
                            <p class="text-sm text-slate-500 mt-0.5">Sesi penimbangan terdekat</p>
                        </div>
                        <a href="{{ route('jadwal.index') }}" class="text-sm font-medium text-cyan-600 hover:text-cyan-700 flex items-center gap-1 focus:outline-none focus:underline">Semua <x-icon name="arrow-right" weight="bold" /></a>
                    </div>

                    @if(isset($jadwalTerdekat) && $jadwalTerdekat)
                        <a href="{{ route('jadwal.show', $jadwalTerdekat['id']) }}" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm group flex items-start gap-4 hover:border-cyan-300 focus:outline-none focus:ring-2 focus:ring-cyan-400">
                            <div class="w-14 h-14 rounded-xl bg-gradient-to-br from-cyan-50 to-cyan-100 text-cyan-700 flex flex-col items-center justify-center shrink-0 border border-cyan-200">
                                <span class="text-[11px] font-bold uppercase tracking-wider">{{ $jadwalTerdekat['tgl_bulan'] ?? 'AGT' }}</span>
                                <span class="text-xl font-bold leading-none tabular-nums mt-0.5">{{ $jadwalTerdekat['tgl_nomor'] ?? '23' }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-slate-900 leading-snug mb-1.5 group-hover:text-cyan-700 transition-colors">{{ $jadwalTerdekat['judul'] }}</h3>
                                <div class="text-[13px] text-slate-500 flex flex-col gap-1.5 mb-3">
                                    <div class="flex items-center gap-1.5 truncate"><x-icon name="clock" class="shrink-0 text-slate-400" /> <span class="truncate">{{ $jadwalTerdekat['waktu'] }}</span></div>
                                    <div class="flex items-center gap-1.5 truncate"><x-icon name="map-pin" class="shrink-0 text-slate-400" /> <span class="truncate">{{ $jadwalTerdekat['lokasi'] }}</span></div>
                                </div>
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-cyan-600 text-white text-xs font-bold rounded-full"><x-icon name="hourglass" weight="bold" /> {{ $jadwalTerdekat['countdown'] }}</span>
                            </div>
                        </a>
                    @else
                        <div class="bg-white border border-slate-200 rounded-2xl p-6 text-center text-slate-500 shadow-sm">
                            <p class="text-sm font-medium text-slate-900">Belum ada agenda jadwal</p>
                            <a href="{{ route('jadwal.create') }}" class="text-sm font-medium text-cyan-600 hover:underline mt-1 inline-block focus:outline-none">+ Buat jadwal posyandu</a>
                        </div>
                    @endif
                </div>

                {{-- Quick Export --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center shrink-0"><x-icon name="download-simple" weight="bold" class="text-lg" /></div>
                        <div>
                            <h3 class="text-sm font-semibold text-slate-900 mb-0.5">Rekap Laporan Bulanan</h3>
                            <p class="text-[13px] text-slate-500">Ekspor data untuk Puskesmas.</p>
                        </div>
                    </div>
                    <a href="{{ route('laporan.index') }}" class="w-full sm:w-auto px-4 min-h-[44px] bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-sm font-semibold rounded-xl transition-colors flex items-center justify-center gap-2 shrink-0 focus:ring-2 focus:ring-cyan-500 focus:outline-none">Buka <x-icon name="arrow-right" weight="bold" /></a>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
