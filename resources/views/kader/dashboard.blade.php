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
            <h2 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Aktivitas Posyandu</h2>
            <p class="text-sm text-slate-500 mt-0.5">Catat hasil penimbangan &amp; pantau status gizi balita hari ini.</p>
        </div>
        <div class="flex items-center gap-2.5">
            <a href="{{ route('balita.create') }}"
               class="inline-flex items-center justify-center gap-2 px-4 h-11 rounded-xl bg-white hover:bg-slate-50 text-slate-700 border border-slate-300 text-sm font-semibold shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-slate-300">
                <x-icon name="user-plus" weight="bold" class="text-base text-slate-500" /> Balita Baru
            </a>
            <a href="{{ route('balita.index') }}"
               class="inline-flex items-center justify-center gap-2 px-5 h-11 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold shadow-md shadow-teal-600/20 transition-all active:scale-[0.98] focus:outline-none focus:ring-2 focus:ring-teal-500/40">
                <x-icon name="scales" weight="bold" class="text-base" /> Mulai Timbang
            </a>
        </div>
    </div>

    {{-- CAPAIAN SESI (kartu PUTIH + teal merata + gradient hidup, bukan blok solid) --}}
    <section class="rounded-2xl bg-white border border-slate-200 shadow-sm p-5 sm:p-7 relative overflow-hidden">
        <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600"></div>
        <div class="relative z-10 grid grid-cols-1 md:grid-cols-2 gap-5 sm:gap-6 items-stretch">
            <div class="flex flex-col justify-center">
                <p class="text-xs font-semibold uppercase tracking-widest text-slate-500">Capaian Sesi Ini</p>
                <div class="flex items-baseline gap-2 mt-2.5">
                    <span class="text-3xl sm:text-4xl font-bold tabular-nums leading-none text-teal-600">{{ $sudah }}</span>
                    <span class="text-slate-600 text-sm sm:text-base font-medium">dari {{ $total }} balita terukur</span>
                </div>
                <p class="mt-3 text-sm text-slate-500">Progres pengukuran bulan ini di posyandu Anda.</p>
                
                <div class="mt-4 flex flex-col sm:flex-row gap-3">
                    @php
                        // Cek apakah bulan ini sesi sudah dikirim
                        $sesiAktif = \App\Models\SesiPosyandu::where('posyandu_id', Auth::user()->kader->posyandu_id)
                            ->where('bulan', \Carbon\Carbon::now()->month)
                            ->where('tahun', \Carbon\Carbon::now()->year)
                            ->first();
                    @endphp
                    @if($sesiAktif && $sesiAktif->status === 'dikirim')
                        <div class="flex items-center gap-2 text-sm font-semibold text-emerald-600 mb-2">
                            <x-icon name="check-circle" weight="fill" /> Sesi Terkirim (Menunggu Verifikasi)
                        </div>
                        <form action="{{ route('sesi.tarik-kembali') }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menarik kembali data ini untuk diperbaiki?');">
                            @csrf
                            <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-sm font-semibold rounded-lg transition-colors border border-slate-300">
                                Tarik Kembali Sesi
                            </button>
                        </form>
                    @elseif($sudah > 0)
                        <!-- Jika ada draft, tampilkan tombol kirim -->
                        <button type="button" onclick="document.getElementById('modalKirimSesi').classList.remove('hidden')" class="inline-flex items-center justify-center px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold rounded-xl transition-all shadow-md shadow-teal-500/20 active:scale-95">
                            Kirim Data ke Puskesmas <x-icon name="paper-plane-right" weight="fill" class="ml-2" />
                        </button>
                    @endif
                </div>
            </div>
            <div class="rounded-2xl bg-gradient-to-br from-teal-50 to-teal-100/60 border border-teal-100 p-5 flex flex-col justify-center">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-semibold uppercase tracking-wide text-teal-700">Progres Sesi</span>
                    <span class="text-lg font-bold tabular-nums text-teal-700">{{ $percent }}%</span>
                </div>
                <div class="w-full h-2.5 bg-teal-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-teal-400 via-teal-500 to-teal-600 transition-all" style="width: {{ $percent }}%"></div>
                </div>
                <div class="flex items-center justify-between mt-3.5 text-sm font-semibold">
                    <span class="inline-flex items-center gap-1.5 text-teal-700"><x-icon name="check-circle" weight="fill" /> Selesai {{ $sudah }}</span>
                    <span class="inline-flex items-center gap-1.5 text-teal-600"><x-icon name="clock" weight="fill" /> Antrean {{ $belum }}</span>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Kirim Sesi -->
    <div id="modalKirimSesi" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="document.getElementById('modalKirimSesi').classList.add('hidden')"></div>
        <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg p-6 animate-[bounceIn_0.3s]">
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
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan/Kendala Lapangan (Opsional)</label>
                    <textarea name="catatan_kader" rows="3" placeholder="Contoh: {{ $belum > 0 ? $belum . ' balita tidak hadir karena di luar kota / sedang sakit' : 'Sesi berjalan lancar' }}" class="w-full rounded-xl border-slate-300 focus:border-teal-500 focus:ring focus:ring-teal-200 focus:ring-opacity-50 text-sm p-3"></textarea>
                    <p class="text-xs text-slate-500 mt-1">Catatan ini akan diteruskan ke petugas gizi Puskesmas.</p>
                </div>

                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" onclick="document.getElementById('modalKirimSesi').classList.add('hidden')" class="px-5 py-2.5 text-sm font-semibold text-slate-600 hover:bg-slate-100 rounded-xl transition-colors">Batal</button>
                    <button type="submit" class="px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-sm font-bold rounded-xl transition-all shadow-md shadow-teal-500/20 active:scale-95">Tetap Kirim ke Puskesmas</button>
                </div>
            </form>
        </div>
    </div>

    {{-- VALIDASI ULANG ALERT --}}
    @if(($statRevisi ?? 0) > 0)
    <div class="rounded-2xl bg-amber-50 border border-amber-200 border-l-4 border-l-amber-500 p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="shrink-0 text-amber-600 mt-0.5"><x-icon name="arrows-counter-clockwise" weight="bold" class="text-xl" /></div>
            <div>
                <h3 class="text-sm font-bold text-amber-950">Perlu Tindakan: <span class="tabular-nums">{{ $statRevisi }}</span> Balita Memerlukan Validasi Ulang</h3>
                <p class="text-xs sm:text-sm text-amber-900/80 mt-0.5">Puskesmas mendeteksi anomali data penimbangan dan meminta pengukuran ulang.</p>
            </div>
        </div>
        <a href="{{ route('kader.validasi-ulang') }}"
           class="inline-flex items-center justify-center gap-1.5 px-4 h-10 bg-amber-500 hover:bg-amber-600 text-white text-sm font-bold rounded-xl transition-all shadow-xs shrink-0 focus:outline-none focus:ring-2 focus:ring-amber-400 active:scale-[0.98]">
            Validasi Ulang Sekarang <x-icon name="arrow-right" weight="bold" />
        </a>
    </div>
    @endif

    {{-- KPI METRICS --}}
    <section class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $kpis = [
                ['label' => 'Total Balita', 'value' => $total, 'sub' => $todayFormatted, 'icon' => 'users', 'bar' => 'bg-teal-500', 'box' => 'bg-teal-50 text-teal-600 ring-teal-100'],
                ['label' => 'Sudah Diukur', 'value' => $sudah, 'sub' => $percent . '% tercapai', 'icon' => 'check-circle', 'bar' => 'bg-emerald-500', 'box' => 'bg-emerald-50 text-emerald-600 ring-emerald-100'],
                ['label' => 'Belum Diukur', 'value' => $belum, 'sub' => 'Antrean', 'icon' => 'clock', 'bar' => 'bg-amber-500', 'box' => 'bg-amber-50 text-amber-600 ring-amber-100'],
                ['label' => 'Perlu Pantauan', 'value' => $statPerlu ?? 0, 'sub' => 'Prioritas', 'icon' => 'activity', 'bar' => 'bg-rose-500', 'box' => 'bg-rose-50 text-rose-600 ring-rose-100'],
            ];
        @endphp
        @foreach($kpis as $kpi)
        <div class="group relative rounded-2xl bg-white border border-slate-200 shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 p-4 sm:p-5 flex flex-col overflow-hidden">
            <span class="absolute top-0 inset-x-0 h-1 {{ $kpi['bar'] }}"></span>
            <span class="w-10 h-10 rounded-xl {{ $kpi['box'] }} flex items-center justify-center ring-1">
                <x-icon name="{{ $kpi['icon'] }}" weight="fill" class="text-lg" />
            </span>
            <p class="mt-3 text-2xl sm:text-3xl font-bold tabular-nums text-slate-900 leading-none">{{ $kpi['value'] }}</p>
            <p class="mt-1 text-xs font-semibold uppercase tracking-wide text-slate-500 truncate">{{ $kpi['label'] }}</p>
            <p class="mt-1.5 text-xs text-slate-500 truncate">{{ $kpi['sub'] }}</p>
        </div>
        @endforeach
    </section>

    {{-- WORKSPACE --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 lg:gap-6 mt-2 sm:mt-3 lg:items-start">

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
                            <a href="{{ route('balita.show', $child->id) }}" class="group flex items-center gap-4 p-4 sm:p-5 hover:bg-slate-50 transition-colors focus:outline-none">
                                <div class="shrink-0 w-11 h-11 rounded-full text-sm font-bold flex items-center justify-center {{ $isDanger ? 'bg-rose-100 text-rose-700 ring-2 ring-rose-200/70' : 'bg-amber-100 text-amber-700 ring-2 ring-amber-200/70' }}">{{ $initials }}</div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-semibold text-slate-900 truncate">{{ Str::title($child->name) }}</h3>
                                        <span class="shrink-0 inline-flex items-center justify-center min-w-[22px] h-[22px] px-1 rounded-md bg-slate-100 text-slate-500 text-[11px] font-bold">
                                            <x-icon name="{{ $isBoy ? 'gender-male' : 'gender-female' }}" weight="fill" class="text-[13px]" />
                                        </span>
                                    </div>
                                    <div class="mt-0.5 text-[13px] text-slate-500 flex items-center gap-1.5 truncate leading-snug">
                                        <span class="truncate">Ibu {{ $child->mother ?? '-' }}</span>
                                        <span class="text-slate-300">•</span>
                                        <span class="shrink-0 tabular-nums">{{ $child->age }}</span>
                                    </div>
                                </div>
                                <div class="shrink-0 flex items-center gap-2">
                                    <span class="inline-flex items-center justify-center gap-1.5 min-w-[118px] px-2.5 py-1 rounded-full {{ $isDanger ? 'bg-rose-50 text-rose-700 border border-rose-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }} text-[11px] font-semibold">
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
                    <a href="{{ route('jadwal.index') }}" class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm group flex items-start gap-4 hover:border-teal-300 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-teal-400">
                        <div class="w-14 h-14 rounded-xl bg-teal-50 text-teal-700 flex flex-col items-center justify-center shrink-0 border border-teal-200 group-hover:bg-teal-100 transition-colors">
                            <span class="text-[11px] font-bold uppercase tracking-wider">{{ $jadwalTerdekat['tgl_bulan'] ?? 'AGT' }}</span>
                            <span class="text-xl font-bold leading-none tabular-nums mt-0.5">{{ $jadwalTerdekat['tgl_nomor'] ?? '23' }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-semibold text-slate-900 leading-snug mb-1.5 group-hover:text-teal-700 transition-colors">{{ $jadwalTerdekat['judul'] }}</h3>
                            <div class="text-[13px] text-slate-500 flex flex-col gap-1.5 mb-3">
                                <div class="flex items-center gap-1.5 truncate"><x-icon name="clock" class="shrink-0 text-slate-400" /> <span class="truncate">{{ $jadwalTerdekat['waktu'] }}</span></div>
                                <div class="flex items-center gap-1.5 truncate"><x-icon name="map-pin" class="shrink-0 text-slate-400" /> <span class="truncate">{{ $jadwalTerdekat['lokasi'] }}</span></div>
                            </div>
                            @php
                                $st = $jadwalTerdekat['status_type'] ?? 'upcoming';
                                $chipCls = $st === 'today' ? 'bg-amber-100 text-amber-800' : ($st === 'past' ? 'bg-slate-100 text-slate-500' : 'bg-teal-600 text-white');
                                $chipIcon = $st === 'today' ? 'warning' : 'hourglass';
                            @endphp
                            <span class="inline-flex items-center gap-1.5 w-fit self-start px-2.5 py-1 rounded-full {{ $chipCls }} text-xs font-bold"><x-icon name="{{ $chipIcon }}" weight="fill" /> {{ $jadwalTerdekat['countdown'] }}</span>
                        </div>
                    </a>
                @else
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 text-center text-slate-500 shadow-sm">
                        <p class="text-sm font-medium text-slate-900">Belum ada agenda jadwal</p>
                        <a href="{{ route('jadwal.index') }}" class="text-sm font-medium text-teal-600 hover:underline mt-1 inline-block focus:outline-none">+ Buat jadwal posyandu</a>
                    </div>
                @endif
            </div>

            <div class="bg-white border border-slate-200 rounded-2xl p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4 shadow-sm hover:shadow-md hover:border-teal-200 transition-all group">
                <div class="flex items-center gap-4">
                    <div class="w-11 h-11 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 group-hover:bg-teal-100 transition-colors"><x-icon name="download-simple" weight="bold" class="text-lg" /></div>
                    <div>
                        <h3 class="text-sm font-semibold text-slate-900 mb-0.5">Rekap Laporan Bulanan</h3>
                        <p class="text-[13px] text-slate-500">Ekspor data untuk Puskesmas.</p>
                    </div>
                </div>
                <a href="{{ route('laporan.index') }}" class="w-full sm:w-auto px-4 h-10 bg-teal-600 hover:bg-teal-700 text-white text-sm font-semibold rounded-xl transition-colors flex items-center justify-center gap-2 shrink-0 focus:ring-2 focus:ring-teal-500 focus:outline-none">Buka <x-icon name="arrow-right" weight="bold" /></a>
            </div>
        </section>

    </div>
</div>
@endsection
