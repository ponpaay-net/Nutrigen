@props(['child'])

{{--
  Puskesmas Detail Panel — adopts kader profil-balita visual style.
  Shown inside the slide-in drawer on puskesmas/balita.

  Data shape (from PuskesmasController@balita):
    child['id'], child['nama'], child['nik'], child['jenis_kelamin'],
    child['tanggal_lahir'], child['statusType'], child['statusLabel'],
    child['posyandu']['nama'], child['ibu']['nama'], child['ibu']['no_hp_wa'],
    child['pengukurans'][0..n] — each: berat_badan, tinggi_badan, umur_bulan,
       z_score_bb_u, z_score_tb_u, status_gizi, created_at,
       validasi? { validator_name, created_at, catatan }
--}}

@php
    $initials   = collect(explode(' ', $child['nama']))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
    $isGirl     = in_array(strtolower($child['jenis_kelamin'] ?? ''), ['p', 'perempuan', 'female']);
    $latest     = count($child['pengukurans'] ?? []) > 0 ? $child['pengukurans'][0] : null;
    $statusType = $child['statusType'] ?? 'success';

    $birthDate  = isset($child['tanggal_lahir']) ? date('d M Y', strtotime($child['tanggal_lahir'])) : '-';
    try {
        $bd       = new DateTime($child['tanggal_lahir'] ?? 'now');
        $diff     = (new DateTime())->diff($bd);
        $ageMonths = ($diff->y * 12) + $diff->m;
        if ($latest) $ageMonths = $latest['umur_bulan'];
    } catch(\Exception $e) { $ageMonths = $latest ? $latest['umur_bulan'] : 0; }

    // Status theming
    $statusBg   = match($statusType) {
        'danger'  => 'bg-rose-50 border-rose-100',
        'warning' => 'bg-amber-50 border-amber-100',
        default   => 'bg-emerald-50 border-emerald-100',
    };
    $statusIcon = match($statusType) { 'danger' => 'text-rose-500', 'warning' => 'text-amber-500', default => 'text-emerald-500' };
    $statusTxt  = match($statusType) { 'danger' => 'text-rose-700', 'warning' => 'text-amber-700', default => 'text-emerald-700' };

    // Validation badge
    $valStatus = $latest ? ($latest['validasi']['status'] ?? 'approved') : null;
    $valBadge  = match($valStatus) {
        'pending'  => 'bg-amber-100 text-amber-800 border-amber-200',
        'rejected' => 'bg-rose-600 text-white border-rose-600',
        default    => 'bg-emerald-100 text-emerald-800 border-emerald-200',
    };
    $valLabel  = match($valStatus) {
        'pending'  => 'Menunggu Validasi',
        'rejected' => 'Ditolak Puskesmas',
        default    => 'Terverifikasi',
    };

    // Metric cards
    $bbTrend = null;
    $tbTrend = null;
    if ($latest && count($child['pengukurans']) > 1) {
        $prev    = $child['pengukurans'][1];
        $bbTrend = round($latest['berat_badan'] - $prev['berat_badan'], 1);
        $tbTrend = round($latest['tinggi_badan'] - $prev['tinggi_badan'], 1);
    }
@endphp

<div class="flex flex-col min-h-full bg-slate-50/50">

    {{-- ═══════════════════════════════════════
         HERO PROFILE CARD (kader style)
    ═══════════════════════════════════════ --}}
    <div class="bg-white border-b border-slate-100 px-5 py-5">

        {{-- Identity row --}}
        <div class="flex items-center gap-4">
            {{-- Avatar --}}
            <div class="w-14 h-14 rounded-2xl bg-[#d7f4f2] text-[#006064] flex items-center justify-center shrink-0 font-black text-xl">
                {{ strtoupper($initials) }}
            </div>

            {{-- Name + badges --}}
            <div class="flex-1 min-w-0">
                <h2 class="text-[20px] font-black text-slate-900 tracking-tight leading-tight truncate">{{ $child['nama'] }}</h2>
                <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-bold {{ $isGirl ? 'bg-rose-50 text-rose-600' : 'bg-sky-50 text-sky-700' }}">
                        {{ $isGirl ? 'Perempuan' : 'Laki-laki' }}
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-slate-100 text-slate-600 rounded-full text-[11px] font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 text-slate-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ $ageMonths }} Bulan
                    </span>
                    <span class="inline-flex items-center gap-1 px-2.5 py-0.5 bg-slate-100 text-slate-600 rounded-full text-[11px] font-medium">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3 h-3 text-slate-400">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                        Lahir: {{ $birthDate }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Status + Validation banner --}}
        @if($latest)
        <div class="mt-4 p-3 rounded-2xl {{ $statusBg }} border flex items-center justify-between gap-3">
            <div class="flex items-center gap-2 min-w-0">
                @if($statusType === 'success')
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 {{ $statusIcon }} shrink-0">
                        <path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd"/>
                    </svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 {{ $statusIcon }} shrink-0">
                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                @endif
                <span class="text-[12.5px] font-bold {{ $statusTxt }}">Status Gizi: {{ $child['statusLabel'] }}</span>
            </div>
            @if($valStatus)
            <span class="shrink-0 inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg text-[10.5px] font-bold border {{ $valBadge }}">
                @if($valStatus === 'rejected')
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                @else
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3 h-3"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                @endif
                {{ $valLabel }}
            </span>
            @endif
        </div>
        @endif

        {{-- Bio strip: Ibu, NIK, Posyandu --}}
        <div class="grid grid-cols-3 gap-2.5 mt-4">
            @php
                $bioItems = [
                    ['label' => 'Ibu', 'val' => $child['ibu']['nama'] ?? '-', 'icon' => 'M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z'],
                    ['label' => 'NIK', 'val' => $child['nik'] ?? '-', 'icon' => 'M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z'],
                    ['label' => 'Posyandu', 'val' => $child['posyandu']['nama'] ?? '-', 'icon' => 'M15 10.5a3 3 0 11-6 0 3 3 0 016 0zM19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z'],
                ];
            @endphp
            @foreach($bioItems as $bio)
            <div class="flex flex-col p-3 rounded-xl border border-slate-200 bg-white gap-1.5">
                <div class="w-7 h-7 rounded-lg bg-[#f0f9fa] flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-[#086a7c]">
                        <path stroke-linecap="round" stroke-linejoin="round" d="{{ $bio['icon'] }}" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <p class="text-[9.5px] font-bold text-slate-400 uppercase tracking-wider">{{ $bio['label'] }}</p>
                    <p class="font-bold text-slate-800 text-[11px] truncate leading-snug mt-0.5" title="{{ $bio['val'] }}">{{ $bio['val'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- ═══════════════════════════════════════
         TAB NAVIGATION
    ═══════════════════════════════════════ --}}
    <div class="bg-white border-b border-slate-100 sticky top-0 z-10">
        <nav class="flex overflow-x-auto hide-scrollbar px-5" id="tabs-{{ $child['id'] }}">
            @php
                $tabs = [
                    ['id' => 'ringkasan', 'label' => 'Ringkasan', 'icon' => 'M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z'],
                    ['id' => 'riwayat',  'label' => 'Riwayat',   'icon' => 'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['id' => 'grafik',   'label' => 'Grafik',    'icon' => 'M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z'],
                ];
            @endphp
            @foreach($tabs as $i => $tab)
            <button onclick="switchDetailTab('{{ $child['id'] }}', '{{ $tab['id'] }}')"
                id="dtab-{{ $child['id'] }}-{{ $tab['id'] }}"
                class="detail-tab shrink-0 inline-flex items-center gap-1.5 py-3 px-1 mr-5 border-b-2 font-medium text-[13px] transition-all whitespace-nowrap cursor-pointer
                {{ $i === 0 ? 'border-[#086a7c] text-[#086a7c] font-bold' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="{{ $tab['icon'] }}" />
                </svg>
                {{ $tab['label'] }}
            </button>
            @endforeach
        </nav>
    </div>

    {{-- ═══════════════════════════════════════
         TAB CONTENT
    ═══════════════════════════════════════ --}}
    <div class="flex-1 px-5 py-5 flex flex-col gap-5">

        {{-- ── TAB: RINGKASAN ────────────────────────────────── --}}
        <div id="dtcontent-{{ $child['id'] }}-ringkasan" class="detail-tab-content flex flex-col gap-5">

            @if($latest)
            {{-- Date header --}}
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-[16px] font-bold text-slate-900">Pengukuran Terakhir</h3>
                    <p class="text-[12px] text-slate-500 mt-0.5 font-medium">Hasil antropometri terkini</p>
                </div>
                <div class="inline-flex items-center gap-1.5 text-slate-600 bg-white border border-slate-200 px-3 py-1.5 rounded-full shadow-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-slate-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                    <span class="text-[11px] font-bold font-mono tracking-wide">{{ date('d M Y', strtotime($latest['created_at'])) }}</span>
                </div>
            </div>

            {{-- 3 Big metric cards (kader style) --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">

                {{-- Berat Badan --}}
                <div class="bg-white border border-slate-200 rounded-[20px] p-5 flex flex-col justify-between relative overflow-hidden">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-[#086a7c] text-white flex items-center justify-center shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4.5 h-4.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                                </svg>
                            </div>
                            <span class="text-[13px] font-bold text-slate-800">Berat Badan</span>
                        </div>
                        @if($bbTrend !== null)
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $bbTrend > 0 ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                                {{ $bbTrend > 0 ? '+' : '' }}{{ $bbTrend }} kg
                            </span>
                        @endif
                    </div>
                    <div class="flex items-baseline gap-1 mb-6">
                        <span class="text-[40px] font-black text-slate-900 leading-none">{{ $latest['berat_badan'] }}</span>
                        <span class="text-[18px] font-medium text-slate-400">kg</span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <span class="text-[9.5px] font-bold text-slate-400 uppercase tracking-widest">Z-SCORE BB/U</span>
                        <span class="font-bold text-[11px] text-slate-700 bg-slate-100 px-2 py-0.5 rounded-md">{{ $latest['z_score_bb_u'] ?? '-' }} SD</span>
                    </div>
                </div>

                {{-- Tinggi Badan --}}
                <div class="bg-[#fffbf5] border border-[#fef0dd] rounded-[20px] p-5 flex flex-col justify-between relative overflow-hidden">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-amber-400 text-white flex items-center justify-center shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                </svg>
                            </div>
                            <span class="text-[13px] font-bold text-slate-800">Tinggi Badan</span>
                        </div>
                        @if($tbTrend !== null && $tbTrend > 0)
                            <span class="text-[10px] font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-700">+{{ $tbTrend }} cm</span>
                        @endif
                    </div>
                    <div class="flex items-baseline gap-1 mb-6">
                        <span class="text-[40px] font-black text-slate-900 leading-none">{{ $latest['tinggi_badan'] }}</span>
                        <span class="text-[18px] font-medium text-slate-400">cm</span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-amber-200/50">
                        <span class="text-[9.5px] font-bold text-slate-400 uppercase tracking-widest">Z-SCORE TB/U</span>
                        <span class="font-bold text-[11px] text-amber-800 bg-amber-100 px-2 py-0.5 rounded-md">{{ $latest['z_score_tb_u'] ?? ($latest['z_score_bb_u'] ?? '-') }} SD</span>
                    </div>
                </div>

                {{-- Umur saat ukur --}}
                <div class="bg-[#f8fafc] border border-slate-200/70 rounded-[20px] p-5 flex flex-col justify-between relative overflow-hidden">
                    <div class="flex items-start justify-between mb-6">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-slate-600 text-white flex items-center justify-center shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <span class="text-[13px] font-bold text-slate-800">Umur Ukur</span>
                        </div>
                    </div>
                    <div class="flex items-baseline gap-1 mb-6">
                        <span class="text-[40px] font-black text-slate-900 leading-none">{{ $latest['umur_bulan'] }}</span>
                        <span class="text-[18px] font-medium text-slate-400">bln</span>
                    </div>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <span class="text-[9.5px] font-bold text-slate-400 uppercase tracking-widest">Status Gizi</span>
                        @php
                            $giziLow = strtolower($latest['status_gizi'] ?? '');
                            $gcls    = in_array($giziLow, ['stunting','gizi buruk','sangat kurus'])
                                ? 'bg-rose-100 text-rose-700'
                                : (in_array($giziLow, ['kurang','kurus','risiko'])
                                    ? 'bg-amber-100 text-amber-700'
                                    : 'bg-emerald-100 text-emerald-700');
                        @endphp
                        <span class="font-bold text-[11px] {{ $gcls }} px-2 py-0.5 rounded-md">{{ $latest['status_gizi'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Catatan validator (if available) --}}
            @if(isset($latest['validasi']['catatan']) && $latest['validasi']['catatan'])
            <div class="flex items-start gap-3 p-4 bg-[#f0f9fa] border border-[#d7eff4] rounded-2xl">
                <div class="w-8 h-8 rounded-xl bg-[#086a7c] flex items-center justify-center shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-white">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div>
                    <p class="text-[10px] font-bold text-[#086a7c] uppercase tracking-wider">Catatan Validator · {{ $latest['validasi']['validator_name'] ?? 'Puskesmas' }}</p>
                    <p class="text-[12.5px] text-slate-700 font-medium mt-0.5 leading-relaxed">"{{ $latest['validasi']['catatan'] }}"</p>
                </div>
            </div>
            @endif

            @else
            <div class="flex flex-col items-center justify-center py-12 text-center gap-3">
                <div class="w-14 h-14 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-slate-300">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9h16.5m-16.5 6.75h16.5" />
                    </svg>
                </div>
                <p class="text-sm font-bold text-slate-600">Belum Ada Data Pengukuran</p>
                <p class="text-xs text-slate-400 max-w-xs leading-relaxed">Data pengukuran akan muncul setelah kader melakukan penimbangan.</p>
            </div>
            @endif

        </div>

        {{-- ── TAB: RIWAYAT ──────────────────────────────────── --}}
        <div id="dtcontent-{{ $child['id'] }}-riwayat" class="detail-tab-content hidden flex flex-col gap-4">

            <h3 class="text-[15px] font-bold text-slate-900">Riwayat Pengukuran & Validasi</h3>

            {{-- Timeline --}}
            <div class="relative pl-5 border-l-2 border-slate-100 flex flex-col gap-5 ml-2">
                @forelse($child['pengukurans'] as $i => $p)
                    @php
                        $pGizi  = strtolower($p['status_gizi'] ?? '');
                        $pDanger = in_array($pGizi, ['stunting','gizi buruk','sangat kurus','obesitas']);
                        $pWarn   = in_array($pGizi, ['kurang','kurus','risiko lebih','risiko']);
                        $pDot    = $pDanger ? 'bg-rose-500 ring-rose-100' : ($pWarn ? 'bg-amber-400 ring-amber-100' : 'bg-emerald-500 ring-emerald-100');
                        $pValStatus = $p['validasi']['status'] ?? 'approved';
                        $pValCls = match($pValStatus) {
                            'rejected' => 'bg-rose-50 text-rose-700 border-rose-200',
                            'pending'  => 'bg-amber-50 text-amber-700 border-amber-200',
                            default    => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                        };
                        $pValLabel = match($pValStatus) {
                            'rejected' => 'Ditolak',
                            'pending'  => 'Pending',
                            default    => 'Valid',
                        };
                    @endphp
                    <div class="relative">
                        {{-- Timeline dot --}}
                        <div class="absolute -left-[27px] top-1.5 w-3 h-3 rounded-full {{ $pDot }} ring-4"></div>

                        <div class="bg-white border border-slate-200 rounded-[18px] p-4 flex flex-col gap-3 shadow-xs">
                            {{-- Header row --}}
                            <div class="flex items-center justify-between gap-2 border-b border-slate-100 pb-3">
                                <div>
                                    <span class="text-[13px] font-black text-slate-800">{{ date('d M Y', strtotime($p['created_at'])) }}</span>
                                    <span class="text-[11px] text-slate-400 font-medium ml-2">Umur: {{ $p['umur_bulan'] }} bln</span>
                                </div>
                                <span class="text-[10px] font-bold px-2.5 py-1 rounded-md border {{ $pValCls }}">{{ $pValLabel }}</span>
                            </div>

                            {{-- Metrics grid --}}
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 bg-slate-50/80 rounded-xl p-3 border border-slate-100">
                                @foreach([['BB', $p['berat_badan'].' kg'], ['TB', $p['tinggi_badan'].' cm'], ['Z-BB/U', $p['z_score_bb_u'] ?? '-'], ['Status', $p['status_gizi']]] as $m)
                                <div class="flex flex-col gap-0.5">
                                    <span class="text-[9.5px] font-bold text-slate-400 uppercase tracking-widest">{{ $m[0] }}</span>
                                    <span class="font-black text-slate-800 text-[13px]">{{ $m[1] }}</span>
                                </div>
                                @endforeach
                            </div>

                            {{-- Audit trail --}}
                            @if(isset($p['validasi']))
                            <div class="p-2.5 bg-slate-50 border border-slate-100 rounded-xl text-[11px] flex flex-col gap-0.5">
                                <div class="flex items-center gap-1.5 text-slate-500 font-semibold">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5 text-slate-400">
                                        <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd"/>
                                    </svg>
                                    Audit Trail Validasi
                                </div>
                                <div class="flex flex-col sm:flex-row sm:gap-4 text-slate-600 pl-5">
                                    <span><span class="text-slate-400">Oleh:</span> {{ $p['validasi']['validator_name'] ?? '-' }}</span>
                                    <span><span class="text-slate-400">Pada:</span> {{ isset($p['validasi']['created_at']) ? date('d M Y H:i', strtotime($p['validasi']['created_at'])) : '-' }}</span>
                                </div>
                                @if(!empty($p['validasi']['catatan']))
                                <p class="pl-5 italic text-slate-500 mt-0.5">"{{ $p['validasi']['catatan'] }}"</p>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-slate-400 italic ml-2">Belum ada riwayat pengukuran.</p>
                @endforelse
            </div>
        </div>

        {{-- ── TAB: GRAFIK ───────────────────────────────────── --}}
        <div id="dtcontent-{{ $child['id'] }}-grafik" class="detail-tab-content hidden flex flex-col gap-4">
            <h3 class="text-[15px] font-bold text-slate-900">Grafik Pertumbuhan KMS</h3>
            <div class="bg-white border border-slate-200 rounded-[20px] p-5">
                <x-balita.growth-chart :child="$child" />
            </div>
        </div>

    </div>{{-- end tab content wrapper --}}
</div>{{-- end detail panel --}}
