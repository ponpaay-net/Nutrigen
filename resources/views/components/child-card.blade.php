@props(['balita'])

@php
    $statusType = $balita['status_type'] ?? 'warning';
    $valStatus = $balita['status_validasi'] ?? 'pending';

    // Theme color mapping based on status
    $theme = match($statusType) {
        'success' => [
            'bar'       => 'bg-emerald-500',
            'avatar'    => 'bg-emerald-50 text-emerald-600 ring-4 ring-emerald-50/60',
            'badge_bg'  => 'bg-emerald-50 text-emerald-700 border-emerald-200/60',
            'text'      => 'text-emerald-700',
            'dot'       => 'bg-emerald-500',
        ],
        'danger' => [
            'bar'       => 'bg-rose-500',
            'avatar'    => 'bg-rose-50 text-rose-600 ring-4 ring-rose-50/60',
            'badge_bg'  => 'bg-rose-50 text-rose-700 border-rose-200/60',
            'text'      => 'text-rose-700',
            'dot'       => 'bg-rose-500',
        ],
        default => [
            'bar'       => 'bg-amber-400',
            'avatar'    => 'bg-amber-50 text-amber-600 ring-4 ring-amber-50/60',
            'badge_bg'  => 'bg-amber-50 text-amber-700 border-amber-200/60',
            'text'      => 'text-amber-700',
            'dot'       => 'bg-amber-400',
        ],
    };

    $isGirl = in_array(strtolower($balita['gender'] ?? ''), ['p', 'perempuan', 'female']);
    $genderText = $balita['gender_label'] ?? ($isGirl ? 'Perempuan' : 'Laki-laki');
    $nik = $balita['nik'] ?? '-';
@endphp

<div class="group relative flex flex-col justify-between bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-xs hover:shadow-md hover:border-slate-300 transition-all duration-150 h-full w-full">

    {{-- Accent Bar Kiri --}}
    <div class="absolute left-0 top-0 bottom-0 w-[3.5px] {{ $theme['bar'] }}"></div>

    {{-- Card Body (Ringkas & Proporsional di Layar Mobile) --}}
    <div class="p-3.5 sm:p-4 pl-4 sm:pl-4.5 flex flex-col justify-between h-full gap-2.5 sm:gap-3">

        <div class="space-y-2 sm:space-y-2.5">
            
            {{-- 1. HEADER: AVATAR + NAMA + USIA/GENDER + NIK --}}
            <div class="flex items-start gap-2.5 sm:gap-3">
                {{-- Avatar Icon --}}
                <div class="w-9 h-9 sm:w-10 sm:h-10 rounded-full {{ $theme['avatar'] }} flex items-center justify-center shrink-0 mt-0.5">
                    <svg class="w-4.5 h-4.5 sm:w-5 sm:h-5 fill-current" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </div>

                {{-- Info Anak (Kontras Jelas, NIK Lengkap) --}}
                <div class="flex-1 min-w-0">
                    <a href="{{ route('balita.show', $balita['id'] ?? '') }}" class="font-bold text-slate-900 text-[13.5px] sm:text-[14.5px] leading-snug truncate block group-hover:text-teal-700 transition-colors">
                        {{ Str::title($balita['name']) }}
                    </a>
                    
                    {{-- Usia & Gender --}}
                    <p class="text-[11px] sm:text-[11.5px] text-slate-700 font-semibold truncate flex items-center gap-1 mt-0.5">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-slate-500 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        <span>{{ $balita['age'] }}</span>
                        <span class="text-slate-300">•</span>
                        <span>{{ $genderText }}</span>
                    </p>

                    {{-- NIK --}}
                    <p class="text-[11px] sm:text-[11.5px] text-slate-600 font-medium tracking-wide truncate mt-0.5">
                        NIK: {{ $nik }}
                    </p>
                </div>
            </div>

            {{-- 2. BADGE STATUS GIZI & VALIDASI --}}
            @if($valStatus === 'approved')
                <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap pt-0.5">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg border text-[10.5px] sm:text-xs font-bold {{ $theme['badge_bg'] }}">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" /></svg>
                        <span>{{ $balita['status'] }}</span>
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg border text-[10px] sm:text-[11px] font-bold uppercase tracking-wide bg-emerald-50 text-emerald-700 border-emerald-200/60">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-emerald-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                        <span>APPROVED</span>
                    </span>
                </div>
            @elseif($valStatus === 'rejected')
                <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap pt-0.5">
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg border text-[10.5px] sm:text-xs font-bold bg-rose-50 text-rose-700 border-rose-200/60">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 fill-current" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                        <span>Perlu Revisi Kader</span>
                    </span>
                    <span class="inline-flex items-center gap-1 px-2 py-0.5 sm:px-2.5 sm:py-1 rounded-md sm:rounded-lg border text-[10px] sm:text-[11px] font-bold uppercase tracking-wide bg-rose-50 text-rose-600 border-rose-200/60">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-rose-600" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" /></svg>
                        <span>REJECTED</span>
                    </span>
                </div>
            @else
                {{-- Status Pending: Bullet dan Teks Status SELALU Sinkron Sesuai $theme['text'] --}}
                <div class="space-y-1 pt-0.5">
                    <div class="flex items-center gap-1.5 text-[11.5px] sm:text-xs font-bold {{ $theme['text'] }}">
                        <span class="w-1.5 h-1.5 sm:w-2 sm:h-2 rounded-full {{ $theme['dot'] }} shrink-0"></span>
                        <span class="truncate">{{ $balita['status'] }}</span>
                    </div>
                    <div class="flex items-center gap-1 text-[10px] sm:text-[11px] font-bold text-amber-600 uppercase tracking-wider">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd" /></svg>
                        <span>PENDING</span>
                    </div>
                </div>
            @endif

            {{-- 3. CONTAINER PENGUKURAN TERAKHIR (BB / TB) --}}
            <div class="p-2 sm:p-2.5 rounded-xl {{ $valStatus === 'rejected' ? 'bg-rose-50/70 border border-rose-100' : 'bg-slate-50/90 border border-slate-100' }} flex items-center justify-between">
                {{-- Tanggal Ukur --}}
                <div class="flex-1 min-w-0 pr-1.5 sm:pr-2">
                    <div class="flex items-center gap-1 text-[9.5px] sm:text-[10px] font-semibold text-slate-400">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        <span class="truncate">Pengukuran terakhir</span>
                    </div>
                    <p class="text-[11.5px] sm:text-xs font-bold text-slate-800 mt-0.5 truncate">{{ $balita['last_measure'] }}</p>
                </div>

                {{-- Divider Vertikal --}}
                <div class="w-px h-6 sm:h-7 bg-slate-200 shrink-0"></div>

                {{-- BB / TB --}}
                <div class="flex-1 min-w-0 pl-2 sm:pl-3">
                    <span class="text-[9.5px] sm:text-[10px] font-semibold text-slate-400 block">BB / TB</span>
                    <p class="text-[11.5px] sm:text-xs font-bold text-slate-800 mt-0.5 truncate">{{ $balita['bb_tb'] ?? '-' }}</p>
                </div>
            </div>

        </div>

        {{-- 4. TOMBOL AKSI KADER (Detail + Ukur) --}}
        <div class="flex items-center gap-2 pt-0.5 sm:pt-1">
            <a href="{{ route('balita.show', $balita['id'] ?? '') }}"
               class="h-[32px] sm:h-[35px] flex-1 flex items-center justify-center text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 hover:border-slate-300 rounded-lg sm:rounded-xl transition-all shadow-2xs cursor-pointer">
                Detail
            </a>
            <a href="{{ route('balita.show', ['id' => $balita['id'] ?? '', 'action' => 'ukur']) }}"
               class="h-[32px] sm:h-[35px] flex-1 flex items-center justify-center bg-teal-700 hover:bg-teal-800 active:scale-[0.99] text-white text-xs font-bold rounded-lg sm:rounded-xl transition-all shadow-2xs cursor-pointer flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5 fill-none stroke-current" viewBox="0 0 24 24" stroke-width="2.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Ukur</span>
            </a>
        </div>

    </div>
</div>
