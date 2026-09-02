@extends('layouts.app')

@section('page-title', 'Pusat Laporan Posyandu')

@section('content')
<div class="bg-slate-50 min-h-full">
<div class="max-w-6xl mx-auto w-full px-4 sm:px-6 pt-5 sm:pt-8 pb-28 sm:pb-12">

    {{-- Header + filter periode --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Pusat Laporan Posyandu</h1>
            <p class="text-[13px] text-slate-500 mt-1 flex items-center gap-1.5"><x-icon name="map-pin" weight="bold" class="text-[14px] text-teal-600" /> {{ $posyanduAktif ?? 'Posyandu Kader' }} · Tinjau metrik penimbangan bulanan & ekspor laporan resmi.</p>
        </div>
        <form action="{{ route('laporan.index') }}" method="GET" class="relative w-full sm:w-auto">
            <style>
                .periode-picker-overlay::-webkit-calendar-picker-indicator { position: absolute; top: 0; left: 0; right: 0; bottom: 0; width: 100%; height: 100%; opacity: 0; cursor: pointer; }
                .periode-picker-overlay:hover { cursor: pointer; }
            </style>
            <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Periode</label>
            <div class="inline-flex items-center gap-2 h-11 px-4 rounded-xl border border-slate-200 bg-white shadow-sm">
                <x-icon name="calendar-blank" weight="bold" class="text-[15px] text-slate-400" />
                <span class="text-[14px] font-semibold text-slate-800">{{ $periode ?? '' }}</span>
                <x-icon name="caret-down" weight="bold" class="text-[13px] text-slate-400" />
            </div>
            <input type="month" name="periode" value="{{ $periodeValue }}" onchange="this.form.submit()" class="periode-picker-overlay absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10" title="Ubah Periode" aria-label="Ubah Periode">
        </form>
    </div>

    @if(isset($dataKosong) && $dataKosong)
        {{-- Empty state --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 sm:p-12 text-center flex flex-col items-center">
            <span class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mb-3"><x-icon name="chart-bar" weight="fill" class="text-[26px]" /></span>
            <h2 class="text-base font-bold text-slate-900">Belum Ada Data Penimbangan</h2>
            <p class="text-[13px] text-slate-500 mt-1.5 max-w-sm leading-relaxed">Tidak ada riwayat pengukuran balita yang tercatat pada periode ini. Silakan input penimbangan terlebih dahulu.</p>
            <a href="{{ route('balita.index') }}" class="mt-5 inline-flex items-center gap-2 h-11 px-5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-[14px] font-semibold shadow-md shadow-teal-600/15 transition-colors"><x-icon name="plus" weight="bold" class="text-[16px]" /> Input Pengukuran Balita</a>
        </div>
    @else

        {{-- Rekap + KPI --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 lg:gap-5 mb-8">
            {{-- Rekapitulasi (teal gradient) --}}
            <div class="md:col-span-1 rounded-2xl bg-gradient-to-br from-teal-600 to-teal-700 text-white p-6 flex flex-col justify-between shadow-md shadow-teal-600/10">
                <div>
                    <p class="text-[10.5px] font-bold uppercase tracking-widest text-teal-100">Rekapitulasi Penimbangan</p>
                    <p class="text-[12px] text-teal-50 mt-0.5">{{ $periode ?? '' }}</p>
                </div>
                <div class="flex items-baseline gap-0.5 mt-5">
                    <span class="text-[52px] font-black leading-none tracking-tight">{{ $persentase ?? 0 }}</span><span class="text-2xl font-bold">%</span>
                </div>
                <p class="text-[13px] text-teal-50 font-medium mt-1">{{ $sudahDiukur ?? 0 }} / {{ $totalBalita ?? 0 }} balita terukur</p>
                <div class="mt-4 h-2 w-full bg-white/20 rounded-full overflow-hidden">
                    <div class="h-2 bg-white rounded-full transition-all" style="width: {{ $persentase ?? 0 }}%"></div>
                </div>
                <p class="text-right text-[11px] font-semibold text-teal-50 mt-2">Sesuai target kunjungan</p>
            </div>

            {{-- 4 KPI --}}
            <div class="md:col-span-2 grid grid-cols-2 gap-4">
                @php
                    $kpis = [
                        ['label' => 'Terukur', 'count' => $sudahDiukur ?? 0, 'icon' => 'check-circle', 'tone' => 'teal', 'note' => 'balita diukur'],
                        ['label' => 'Belum Hadir', 'count' => $belumDiukur ?? 0, 'icon' => 'user-minus', 'tone' => 'amber', 'note' => 'belum timbang'],
                        ['label' => 'Pantauan Gizi', 'count' => $perluPerhatian ?? 0, 'icon' => 'chart-line', 'tone' => 'slate', 'note' => 'perlu perhatian'],
                        ['label' => 'Perlu Konfirmasi', 'count' => $berisiko ?? 0, 'icon' => 'warning', 'tone' => 'rose', 'note' => 'perlu validasi'],
                    ];
                @endphp
                @foreach($kpis as $k)
                    <div class="bg-white border border-slate-200 rounded-2xl p-5 flex flex-col justify-between shadow-sm">
                        <div class="flex items-center justify-between">
                            <span class="w-9 h-9 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center"><x-icon name="{{ $k['icon'] }}" weight="bold" class="text-[17px]" /></span>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $k['label'] }}</span>
                        </div>
                        <div class="mt-4">
                            <span class="text-[36px] font-black text-slate-900 leading-none tracking-tight">{{ $k['count'] }}</span>
                            <p class="text-[12px] font-medium {{ $k['tone'] === 'rose' ? 'text-rose-500' : ($k['tone'] === 'amber' ? 'text-amber-600' : 'text-slate-500') }} mt-1">{{ $k['note'] }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Ekspor & Pelaporan --}}
        <section class="mb-8">
            <div class="flex items-center gap-3 mb-4">
                <span class="w-1 h-6 bg-teal-600 rounded-full"></span>
                <h2 class="text-base font-bold text-slate-900">Ekspor & Pelaporan</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 lg:gap-5">
                {{-- PDF --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6 flex flex-col shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h3 class="text-[15px] font-bold text-slate-900 leading-snug">Laporan Resmi Posyandu (PDF)</h3>
                            <p class="text-[12.5px] text-slate-500 mt-1.5 leading-relaxed">Dokumen lengkap siap cetak untuk diserahkan ke Puskesmas dan Kelurahan.</p>
                            <div class="flex flex-wrap gap-1.5 mt-3">
                                <span class="text-[10.5px] font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-md">Kop Surat</span>
                                <span class="text-[10.5px] font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-md">Tanda Tangan</span>
                                <span class="text-[10.5px] font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-md">A4 Landscape</span>
                            </div>
                        </div>
                        <span class="w-11 h-11 shrink-0 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center"><x-icon name="file-pdf" weight="fill" class="text-[20px]" /></span>
                    </div>
                    <form action="{{ route('laporan.generate') }}" method="POST" class="mt-5">
                        @csrf
                        <input type="hidden" name="posyandu_id" value="{{ request('posyandu_id') }}">
                        <input type="hidden" name="periode" value="{{ $periodeValue }}">
                        <button type="submit" class="w-full h-11 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-[14px] font-semibold inline-flex items-center justify-center gap-2 shadow-md shadow-teal-600/15 transition-colors"><x-icon name="printer" weight="bold" class="text-[16px]" /> Cetak PDF Resmi</button>
                    </form>
                </div>
                {{-- Excel --}}
                <div class="bg-white border border-slate-200 rounded-2xl p-6 flex flex-col shadow-sm">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <h3 class="text-[15px] font-bold text-slate-900 leading-snug">Data Tabel Pengukuran (Excel)</h3>
                            <p class="text-[12.5px] text-slate-500 mt-1.5 leading-relaxed">Spreadsheet mentah untuk analisis data lebih lanjut atau rekapitulasi mandiri.</p>
                            <div class="flex flex-wrap gap-1.5 mt-3">
                                <span class="text-[10.5px] font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-md">16 Kolom Lengkap</span>
                                <span class="text-[10.5px] font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-md">Format Spreadsheet</span>
                                <span class="text-[10.5px] font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-md">Arsip Digital</span>
                            </div>
                        </div>
                        <span class="w-11 h-11 shrink-0 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center"><x-icon name="file-xlsx" weight="fill" class="text-[20px]" /></span>
                    </div>
                    <a href="{{ route('laporan.export.excel', ['periode' => $periodeValue]) }}" class="mt-5 w-full h-11 rounded-xl border border-teal-200 bg-teal-50 hover:bg-teal-100 text-teal-700 text-[14px] font-semibold inline-flex items-center justify-center gap-2 transition-colors"><x-icon name="download" weight="bold" class="text-[16px]" /> Export ke Excel (.xls)</a>
                </div>
            </div>
        </section>

        {{-- Pratinjau Data Penimbangan --}}
        <section>
            <div class="flex items-center justify-between gap-3 mb-4">
                <div class="flex items-center gap-3">
                    <span class="w-1 h-6 bg-teal-600 rounded-full"></span>
                    <h2 class="text-base font-bold text-slate-900">Pratinjau Data Penimbangan <span class="font-normal text-slate-500">({{ $periode ?? '' }})</span></h2>
                </div>
                <a href="{{ route('balita.index') }}" class="shrink-0 inline-flex items-center gap-1 text-[12.5px] font-semibold text-teal-600 hover:text-teal-700">Lihat Semua <x-icon name="arrow-right" weight="bold" /></a>
            </div>

            @if(isset($previewBalitas) && $previewBalitas->isNotEmpty())
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50 border-b border-slate-200 text-[10.5px] font-bold text-slate-400 uppercase tracking-wider">
                                <tr>
                                    <th class="py-3 px-4">Balita & NIK</th>
                                    <th class="py-3 px-4">Nama Ibu</th>
                                    <th class="py-3 px-4">Tgl Ukur</th>
                                    <th class="py-3 px-4 text-center">BB (kg)</th>
                                    <th class="py-3 px-4 text-center">TB (cm)</th>
                                    <th class="py-3 px-4 text-center">KMS</th>
                                    <th class="py-3 px-4 text-center">Status / Diagnosa</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-[13.5px] font-medium text-slate-700">
                                @foreach($previewBalitas as $b)
                                    @php
                                        $m = $b->pengukurans->first();
                                        $statusGizi = $m ? $m->status_gizi : '-';
                                        $statusValidasi = $m ? $m->status_validasi : null;
                                        $isApproved = $statusValidasi === 'approved';
                                        $kms = $m ? ($m->status_kenaikan ?? '-') : '-';
                                        $isWarning = false;
                                        if ($m && (strtolower($statusGizi) != 'normal' || str_contains(strtolower($statusGizi), 'kurang'))) { $isWarning = true; }
                                        if ($kms === 'T' || str_contains($kms, 'Turun')) { $isWarning = true; }
                                    @endphp
                                    <tr class="hover:bg-slate-50/60 transition-colors">
                                        <td class="py-3 px-4">
                                            <div class="flex items-center gap-2.5">
                                                <span class="w-9 h-9 rounded-full bg-teal-50 text-teal-700 flex items-center justify-center shrink-0 text-[13px] font-bold border border-teal-100">{{ strtoupper(substr($b->nama, 0, 1)) }}</span>
                                                <div class="min-w-0">
                                                    <span class="block font-semibold text-slate-900 leading-tight truncate max-w-[200px]">{{ $b->nama }}</span>
                                                    <span class="block text-[11.5px] text-slate-400 mt-0.5">{{ $b->nik ?? '-' }}</span>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3 px-4 text-slate-500">{{ $b->orangTua->nama_ibu ?? '-' }}</td>
                                        <td class="py-3 px-4 text-slate-500 whitespace-nowrap">{{ $m ? \Carbon\Carbon::parse($m->tanggal_ukur)->translatedFormat('d M Y') : '-' }}</td>
                                        <td class="py-3 px-4 text-center {{ $isWarning ? 'font-semibold text-amber-600' : 'text-slate-600' }}">{{ $m ? number_format((float)$m->berat_badan, 1) : '-' }}</td>
                                        <td class="py-3 px-4 text-center text-slate-600">{{ $m ? number_format((float)$m->tinggi_badan, 1) : '-' }}</td>
                                        <td class="py-3 px-4 text-center">
                                            @if($kms === 'N' || str_contains($kms, 'Naik'))
                                                <x-icon name="trend-up" weight="bold" class="text-emerald-500 text-[15px] mx-auto" />
                                            @elseif($kms === 'T' || str_contains($kms, 'Tetap'))
                                                <x-icon name="arrow-right" weight="bold" class="text-slate-300 text-[15px] mx-auto" />
                                            @else
                                                <x-icon name="trend-down" weight="bold" class="text-rose-500 text-[15px] mx-auto" />
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 text-center">
                                            @if($isApproved)
                                                @if(strtolower($statusGizi) === 'normal')
                                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 whitespace-nowrap">Normal</span>
                                                @else
                                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 whitespace-nowrap">{{ ucfirst($statusGizi) }}</span>
                                                @endif
                                            @else
                                                @if($isWarning)
                                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 whitespace-nowrap">Pantauan Gizi</span>
                                                @else
                                                    <span class="inline-flex px-2.5 py-1 rounded-full text-[11px] font-bold bg-slate-50 text-slate-500 border border-slate-200 whitespace-nowrap">Menunggu Validasi</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 text-center text-slate-500 text-[13px]">Tidak ada data penimbangan pada periode ini.</div>
            @endif
        </section>

    @endif
</div>
</div>
@endsection
