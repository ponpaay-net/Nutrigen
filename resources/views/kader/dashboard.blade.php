@extends('layouts.app')
@section('page-title', 'Dashboard Kader')
@section('content')

@php
    $total = (int) ($statTotal ?? 0);
    $sudah = (int) ($statSudah ?? 0);
    $belum = (int) ($statBelum ?? max(0, $total - $sudah));
    $percent = $total > 0 ? min(100, round(($sudah / $total) * 100)) : 0;
    $todayFormatted = \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y');
    $cleanName = preg_replace('/\s*\(.*?\)/', '', $kaderName ?? 'Kader');
    $posyanduName = Auth::user()->kader?->posyandu?->nama ?? 'Posyandu';
    $sesiAktif = \App\Models\SesiPosyandu::where('posyandu_id', Auth::user()->kader?->posyandu_id)
        ->where('bulan', \Carbon\Carbon::now()->month)
        ->where('tahun', \Carbon\Carbon::now()->year)
        ->first();
@endphp

<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 pt-7 sm:pt-9 pb-8 flex flex-col gap-6">

    {{-- ============================================================
         HEADER
        ============================================================ --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <p class="text-[11px] font-bold uppercase tracking-[0.18em] text-teal-600 mb-1">{{ $todayFormatted }}</p>
            <h1 class="text-2xl sm:text-[28px] font-extrabold tracking-tight text-slate-900 leading-tight">
                Aktivitas Posyandu
            </h1>
            <p class="text-sm text-slate-500 mt-1">Catat hasil penimbangan &amp; pantau status gizi balita hari ini.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('balita.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 h-11 rounded-xl bg-white text-slate-700 border border-slate-300 text-sm font-semibold shadow-sm transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-teal-500/30">
                <x-icon name="user-plus" weight="bold" class="text-base text-teal-600" /> Balita Baru
            </a>
            <a href="{{ route('balita.index') }}"
               class="inline-flex items-center justify-center gap-2 px-5 h-11 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold shadow-sm shadow-teal-600/20 transition-all active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-teal-500/40">
                <x-icon name="scales" weight="bold" class="text-base" /> Mulai Timbang
            </a>
        </div>
    </div>

    {{-- ============================================================
         CAPAIAN SESI — satu kartu bersih, satu aksen
        ============================================================ --}}
    <section class="rounded-2xl bg-white border border-slate-200 shadow-sm overflow-hidden">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
            {{-- Kiri: angka utama --}}
            <div class="p-6 sm:p-7 flex flex-col justify-center">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-500">Capaian Sesi Ini</p>
                <div class="flex items-baseline gap-2 mt-2.5">
                    <span class="text-4xl font-extrabold tabular-nums text-teal-600">{{ $sudah }}</span>
                    <span class="text-slate-500 text-sm font-medium">dari {{ $total }} balita</span>
                </div>
                <p class="mt-3 text-sm text-slate-500">Progres pengukuran bulan ini di posyandu Anda.</p>

                <div class="mt-5 flex flex-col sm:flex-row gap-3">
                    @if($sesiAktif && $sesiAktif->status === 'dikirim')
                        <div class="inline-flex items-center gap-2 text-sm font-semibold text-teal-600">
                            <x-icon name="check-circle" weight="fill" /> Sesi Terkirim (Verifikasi)
                        </div>
                        <form action="{{ route('sesi.tarik-kembali') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menarik kembali data ini untuk diperbaiki?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg border border-slate-300 transition-colors">Tarik Kembali Sesi</button>
                        </form>
                    @elseif($sudah > 0)
                        <button type="button" onclick="document.getElementById('modalKirimSesi').classList.remove('hidden')" class="inline-flex items-center justify-center px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold rounded-xl transition-all shadow-sm active:scale-95">
                            Kirim ke Puskesmas <x-icon name="paper-plane-right" weight="fill" class="ml-2" />
                        </button>
                    @endif
                </div>
            </div>

            {{-- Kanan: progress bar satu tone --}}
            <div class="md:border-l border-slate-100 p-6 sm:p-7 flex flex-col justify-center md:col-span-1">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wide text-slate-500">Progres Sesi</span>
                    <span class="text-lg font-bold tabular-nums text-teal-600">{{ $percent }}%</span>
                </div>
                <div class="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full bg-teal-500 transition-all" style="width: {{ $percent }}%"></div>
                </div>
                <div class="flex items-center justify-between mt-4 text-sm font-medium">
                    <span class="inline-flex items-center gap-1.5 text-slate-600"><x-icon name="check-circle" weight="fill" class="text-teal-500" /> Selesai {{ $sudah }}</span>
                    <span class="inline-flex items-center gap-1.5 text-slate-400"><x-icon name="clock" weight="fill" /> Sisa {{ $belum }}</span>
                </div>
            </div>

            {{-- Petugas --}}
            <div class="md:border-l border-slate-100 p-6 sm:p-7 hidden lg:flex lg:flex-col justify-center">
                <p class="text-xs font-bold uppercase tracking-widest text-slate-500 mb-1.5">Kader</p>
                <p class="text-lg font-bold text-slate-900 leading-tight">{{ $cleanName }}</p>
                <p class="text-sm text-slate-400 mt-0.5">{{ $posyanduName }}</p>
            </div>
        </div>
    </section>

    <!-- Modal Kirim Sesi -->
    <div id="modalKirimSesi" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('modalKirimSesi').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-bold text-slate-900">Konfirmasi Kirim Sesi</h3>
                <button type="button" onclick="document.getElementById('modalKirimSesi').classList.add('hidden')" class="text-slate-400 hover:text-slate-600"><x-icon name="x" weight="bold" class="text-xl" /></button>
            </div>

            @if($belum > 0)
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-5">
                <div class="flex gap-3">
                    <div class="text-amber-500 mt-0.5"><x-icon name="warning-circle" weight="fill" class="text-xl" /></div>
                    <div>
                        <p class="text-sm font-bold text-amber-900">Perhatian: Target belum 100%</p>
                        <p class="text-xs text-amber-700 mt-1">Masih ada <span class="font-bold">{{ $belum }} balita</span> yang belum ditimbang pada sesi ini. Data ini akan dilaporkan sebagai balita yang mangkir.</p>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('sesi.kirim') }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan / Kendala Lapangan (Opsional)</label>
                    <textarea name="catatan_kader" rows="3" placeholder="Contoh: {{ $belum > 0 ? $belum . ' balita tidak hadir karena di luar kota / sedang sakit' : 'Sesi berjalan lancar' }}" class="w-full rounded-xl border-slate-300 focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 text-sm p-3"></textarea>
                    <p class="text-xs text-slate-500 mt-1">Catatan ini akan diteruskan ke petugas gizi Puskesmas.</p>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('modalKirimSesi').classList.add('hidden')" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold rounded-xl transition-all active:scale-95">Tetap Kirim ke Puskesmas</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================
         ALERT VALIDASI ULANG
        ============================================================ --}}
    @if(($statRevisi ?? 0) > 0)
    <div class="rounded-2xl bg-amber-50 border border-amber-200 p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="shrink-0 text-amber-600 mt-0.5"><x-icon name="arrows-counter-clockwise" weight="bold" class="text-xl" /></div>
            <div>
                <h3 class="text-sm font-bold text-amber-950"><span class="tabular-nums">{{ $statRevisi }}</span> Balita Memerlukan Validasi Ulang</h3>
                <p class="text-xs sm:text-sm text-amber-900/80 mt-0.5">Puskesmas mendeteksi anomali data penimbangan dan meminta pengukuran ulang.</p>
            </div>
        </div>
        <a href="{{ route('kader.validasi-ulang') }}" class="inline-flex items-center justify-center gap-1.5 px-4 h-10 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition-all shrink-0 active:scale-[0.98]">
            Validasi Ulang Sekarang <x-icon name="arrow-right" weight="bold" />
        </a>
    </div>
    @endif

    {{-- ============================================================
         KPI — semua dalam satu tone aksen (teal), tenang & kohesif
        ============================================================ --}}
    <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $kpis = [
                ['label' => 'Total Balita', 'value' => $total, 'sub' => 'Sasaran'],
                ['label' => 'Sudah Diukur', 'value' => $sudah, 'sub' => $percent . '% tercapai'],
                ['label' => 'Belum Diukur', 'value' => $belum, 'sub' => 'Antrean'],
                ['label' => 'Perlu Pantauan', 'value' => $statPerlu ?? 0, 'sub' => 'Prioritas'],
            ];
        @endphp
        @foreach($kpis as $kpi)
        <div class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5 flex flex-col">
            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">{{ $kpi['label'] }}</p>
            <p class="mt-3 text-3xl font-extrabold tabular-nums text-slate-900 leading-none">{{ $kpi['value'] }}</p>
            <p class="mt-2 text-xs text-teal-600 font-semibold">{{ $kpi['sub'] }}</p>
        </div>
        @endforeach
    </section>

    {{-- ============================================================
         WORKSPACE
        ============================================================ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6 items-start">

        {{-- PRIORITAS PEMANTAUAN GIZI --}}
        <section class="lg:col-span-7 flex flex-col gap-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-bold text-slate-900">Prioritas Pemantauan Gizi</h2>
                    <p class="text-sm text-slate-500 mt-0.5">Balita dengan catatan gizi khusus yang perlu pendampingan</p>
                </div>
                <a href="{{ route('balita.index') }}" class="hidden sm:inline-flex text-sm font-semibold text-teal-600 hover:text-teal-700 items-center gap-1">Semua balita <x-icon name="arrow-right" weight="bold" /></a>
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
                            <a href="{{ route('balita.show', $child->id) }}" class="group flex items-center gap-4 p-4 sm:p-5 hover:bg-slate-50 transition-colors">
                                <div class="shrink-0 w-11 h-11 rounded-full text-sm font-bold flex items-center justify-center {{ $isDanger ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-600' }}">{{ $initials }}</div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-semibold text-slate-900 truncate">{{ Str::title($child->name) }}</h3>
                                        <span class="shrink-0 inline-flex items-center justify-center min-w-[22px] h-[22px] px-1 rounded-md bg-slate-100 text-slate-500 text-[11px] font-bold">
                                            <x-icon name="{{ $isBoy ? 'gender-male' : 'gender-female' }}" weight="fill" class="text-[13px]" />
                                        </span>
                                    </div>
                                    <div class="mt-0.5 text-[13px] text-slate-500 flex items-center gap-1.5 leading-snug">
                                        <span class="truncate">Ibu {{ $child->mother ?? '-' }}</span>
                                        <span class="text-slate-300">•</span>
                                        <span class="shrink-0 tabular-nums">{{ $child->age }}</span>
                                    </div>
                                </div>
                                <div class="shrink-0 flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1.5 min-w-[118px] px-2.5 py-1 rounded-full {{ $isDanger ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }} text-[11px] font-semibold">
                                        <x-icon name="{{ $isDanger ? 'warning' : 'activity' }}" weight="fill" class="text-xs" />
                                        {{ $child->shortStatus ?? 'Gizi' }}
                                    </span>
                                    <x-icon name="caret-right" weight="bold" class="text-slate-300 group-hover:text-teal-500 group-hover:translate-x-0.5 transition-all" />
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
                    <a href="{{ route('balita.index') }}" class="flex items-center justify-center gap-1.5 w-full h-12 text-sm font-semibold text-teal-600">Semua balita <x-icon name="arrow-right" weight="bold" /></a>
                </div>
            </div>
        </section>

        {{-- AGENDA + REKAP --}}
        <section class="lg:col-span-5 flex flex-col gap-5">
            <div class="flex flex-col gap-4">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-lg font-bold text-slate-900">Agenda Posyandu</h2>
                        <p class="text-sm text-slate-500 mt-0.5">Sesi penimbangan terdekat</p>
                    </div>
                    <a href="{{ route('jadwal.index') }}" class="text-sm font-semibold text-teal-600 hover:text-teal-700 flex items-center gap-1">Semua <x-icon name="arrow-right" weight="bold" /></a>
                </div>

                @if(isset($jadwalTerdekat) && $jadwalTerdekat)
                    <a href="{{ route('jadwal.index') }}" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm flex items-start gap-4 hover:border-teal-300 hover:shadow-md transition-all">
                        <div class="w-14 h-14 rounded-xl bg-teal-50 text-teal-700 flex flex-col items-center justify-center shrink-0 border border-teal-100">
                            <span class="text-[11px] font-bold uppercase tracking-wider">{{ $jadwalTerdekat['tgl_bulan'] ?? 'AGT' }}</span>
                            <span class="text-xl font-bold leading-none tabular-nums mt-0.5">{{ $jadwalTerdekat['tgl_nomor'] ?? '23' }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-semibold text-slate-900 leading-snug mb-1.5">{{ $jadwalTerdekat['judul'] }}</h3>
                            <div class="text-[13px] text-slate-500 flex flex-col gap-1.5 mb-3">
                                <div class="flex items-center gap-1.5"><x-icon name="clock" class="text-slate-400" /> <span class="truncate">{{ $jadwalTerdekat['waktu'] }}</span></div>
                                <div class="flex items-center gap-1.5"><x-icon name="map-pin" class="text-slate-400" /> <span class="truncate">{{ $jadwalTerdekat['lokasi'] }}</span></div>
                            </div>
                            @php
                                $st = $jadwalTerdekat['status_type'] ?? 'upcoming';
                                $chipCls = $st === 'today' ? 'bg-amber-100 text-amber-800' : ($st === 'past' ? 'bg-slate-100 text-slate-500' : 'bg-teal-600 text-white');
                                $chipIcon = $st === 'today' ? 'warning' : 'hourglass';
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $chipCls }} text-xs font-bold"><x-icon name="{{ $chipIcon }}" weight="fill" /> {{ $jadwalTerdekat['countdown'] }}</span>
                        </div>
                    </a>
                @else
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 text-center text-slate-500 shadow-sm">
                        <p class="text-sm font-medium text-slate-900">Belum ada agenda jadwal</p>
                        <a href="{{ route('jadwal.index') }}" class="text-sm font-medium text-teal-600 hover:underline mt-1 inline-block">+ Buat jadwal posyandu</a>
                    </div>
                @endif
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center"><x-icon name="download-simple" weight="bold" class="text-lg" /></div>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 mb-0.5">Rekap Laporan Bulanan</h3>
                        <p class="text-[13px] text-slate-500">Ekspor data untuk Puskesmas.</p>
                    </div>
                </div>
                <a href="{{ route('laporan.index') }}" class="w-full sm:w-auto px-4 h-10 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-xl flex items-center justify-center gap-2 shrink-0">Buka <x-icon name="arrow-right" weight="bold" /></a>
            </div>
        </section>
    </div>
</div>
@endsection