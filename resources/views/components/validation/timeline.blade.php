@props(['history', 'measurementId'])

@if(count($history) > 0)
    <div class="overflow-x-auto hide-scrollbar">
        <table class="w-full text-left">
            <thead>
                <tr class="border-b border-slate-100">
                    <th class="pb-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider whitespace-nowrap">Tanggal</th>
                    <th class="pb-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">BB</th>
                    <th class="pb-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">TB</th>
                    <th class="pb-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">TB/U</th>
                    <th class="pb-2 text-[10px] font-semibold text-slate-400 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($history as $i => $h)
                    @php
                        $isProblematic = in_array(strtolower($h['status']), ['stunting', 'pendek', 'risiko', 'kurang', 'kurus', 'sangat kurus', 'sangat pendek']);
                        $isLatest = $i === 0;
                    @endphp
                    <tr class="{{ $isLatest ? '' : 'opacity-70' }} border-b border-slate-50 last:border-0">
                        <td class="py-2 pr-3">
                            <div class="flex items-center gap-1.5">
                                @if($isLatest)
                                    <span class="w-1.5 h-1.5 rounded-full bg-[#00A9C0] shrink-0"></span>
                                @endif
                                <span class="text-[11px] font-semibold text-slate-700 whitespace-nowrap {{ !$isLatest ? 'pl-3' : '' }}">{{ $h['date'] }}</span>
                            </div>
                        </td>
                        <td class="py-2 pr-3 text-[11px] text-slate-600 tabular-nums">{{ number_format((float) $h['bb'], 1) }}</td>
                        <td class="py-2 pr-3 text-[11px] text-slate-600 tabular-nums">{{ number_format((float) $h['tb'], 1) }}</td>
                        <td class="py-2 pr-3 text-[11px] text-slate-600 tabular-nums">{{ number_format((float) $h['tbu'], 2) }}</td>
                        <td class="py-2">
                            <span class="text-[10px] font-bold px-1.5 py-0.5 rounded-md {{ $isProblematic ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600' }}">
                                {{ $h['status'] }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@else
    <p class="text-xs text-slate-400 py-3">Belum ada riwayat pengukuran sebelumnya.</p>
@endif

<a href="{{ route('puskesmas.validasi.riwayat', $measurementId) }}"
    class="inline-flex items-center gap-1 mt-2.5 text-[11px] font-semibold text-[#00A9C0] hover:text-cyan-700 transition-colors">
    Lihat semua riwayat
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3">
        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
    </svg>
</a>
