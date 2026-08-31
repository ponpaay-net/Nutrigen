@extends('layouts.puskesmas')

@section('page-title', 'Riwayat Pengukuran')
@section('page-mode', 'scroll')

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
            <div>
                <a href="{{ route('puskesmas.validasi') }}"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-cyan-600 hover:text-cyan-700 mb-3">
                    <span aria-hidden="true">&larr;</span>
                    Kembali ke antrean validasi
                </a>
                <h1 class="text-2xl font-bold tracking-tight text-slate-800">Riwayat Pengukuran</h1>
                <p class="text-sm text-slate-500 mt-1">Seluruh catatan pertumbuhan dan status validasi balita.</p>
            </div>
            <div class="text-left sm:text-right">
                <p class="text-lg font-bold text-slate-800">{{ $child->nama }}</p>
                <p class="text-xs text-slate-500">NIK {{ $child->nik ?? '-' }} &middot; {{ $posyandu }}</p>
            </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-sm font-bold text-slate-800">Daftar Pengukuran</h2>
                    <p class="text-xs text-slate-500 mt-0.5">{{ $measurements->count() }} catatan tercatat</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[980px]">
                    <thead class="bg-slate-50">
                        <tr class="border-b border-slate-200">
                            <th class="px-5 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wide">Tanggal</th>
                            <th class="px-5 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wide">Umur</th>
                            <th class="px-5 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wide">BB (kg)</th>
                            <th class="px-5 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wide">TB (cm)</th>
                            <th class="px-5 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wide">BB/U</th>
                            <th class="px-5 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wide">TB/U</th>
                            <th class="px-5 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wide">Status Gizi
                            </th>
                            <th class="px-5 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wide">Validasi</th>
                            <th class="px-5 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wide">Validator
                            </th>
                            <th class="px-5 py-3 text-[11px] font-bold text-slate-500 uppercase tracking-wide">Diputuskan
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($measurements as $measurement)
                            @php
                                $status = strtolower($measurement->status_gizi ?? '');
                                $statusColor = in_array($status, ['stunting', 'pendek', 'risiko', 'kurang'])
                                    ? 'rose'
                                    : 'emerald';
                                $validation = $measurement->status_validasi ?? 'pending';
                                $validationColor = match ($validation) {
                                    'approved' => 'emerald',
                                    'rejected' => 'rose',
                                    default => 'amber',
                                };
                                $validationLabel = match ($validation) {
                                    'approved' => 'Terverifikasi',
                                    'rejected' => 'Perlu revisi',
                                    default => 'Menunggu',
                                };
                            @endphp
                            <tr class="border-b border-slate-100 last:border-0 hover:bg-slate-50/70">
                                <td class="px-5 py-3 text-xs font-semibold text-slate-800 whitespace-nowrap">
                                    {{ \Carbon\Carbon::parse($measurement->tanggal_ukur)->translatedFormat('d M Y') }}</td>
                                <td class="px-5 py-3 text-xs text-slate-600">{{ $measurement->umur_bulan ?? '-' }} bln</td>
                                <td class="px-5 py-3 text-xs text-slate-600">
                                    {{ number_format((float) $measurement->berat_badan, 1) }}</td>
                                <td class="px-5 py-3 text-xs text-slate-600">
                                    {{ number_format((float) $measurement->tinggi_badan, 1) }}</td>
                                <td class="px-5 py-3 text-xs text-slate-600">
                                    {{ number_format((float) $measurement->z_score_bbu, 2) }}</td>
                                <td class="px-5 py-3 text-xs text-slate-600">
                                    {{ number_format((float) $measurement->z_score_tbu, 2) }}</td>
                                <td class="px-5 py-3 text-xs font-bold text-{{ $statusColor }}-600">
                                    {{ $measurement->status_gizi ?? '-' }}</td>
                                <td class="px-5 py-3">
                                    <span
                                        class="inline-flex items-center rounded-full bg-{{ $validationColor }}-50 px-2.5 py-1 text-[10px] font-bold text-{{ $validationColor }}-700">
                                        {{ $validationLabel }}
                                    </span>
                                </td>
                                <td class="px-5 py-3 text-xs text-slate-600">
                                    {{ $measurement->validator?->name ?? '-' }}
                                </td>
                                <td class="px-5 py-3 text-xs text-slate-600 whitespace-nowrap">
                                    {{ $measurement->validated_at?->translatedFormat('d M Y, H:i') ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-5 py-10 text-center text-xs text-slate-400">Belum ada riwayat
                                    pengukuran.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
