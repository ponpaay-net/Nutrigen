@extends('layouts.app')
@section('page-title', 'Validasi Ulang Pengukuran')
@section('content')

@php
    $total = (int) ($totalItems ?? count($items ?? []));
@endphp

<div class="w-full max-w-6xl mx-auto px-3 sm:px-6 lg:px-8 pt-4 sm:pt-6 pb-20 sm:pb-16 flex flex-col gap-4 sm:gap-6"
     x-data="{
        selectedItem: null,
        isSubmitting: false,
        showSop: true,
        form: {
            tanggal_ukur: '',
            berat_badan: '',
            tinggi_badan: '',
            lingkar_kepala: '',
            status_kenaikan: '',
            asi_eksklusif: false,
            catatan_kader: ''
        },
        openModal(item) {
            this.selectedItem = item;
            this.isSubmitting = false;
            this.form = {
                tanggal_ukur: item.tanggal_ukur || '{{ date('Y-m-d') }}',
                berat_badan: item.berat_badan !== null ? item.berat_badan : '',
                tinggi_badan: item.tinggi_badan !== null ? item.tinggi_badan : '',
                lingkar_kepala: item.lingkar_kepala !== null ? item.lingkar_kepala : '',
                status_kenaikan: item.status_kenaikan || '',
                asi_eksklusif: !!item.asi_eksklusif,
                catatan_kader: item.catatan_kader || ''
            };
            this.$nextTick(() => {
                const el = document.getElementById('modal-berat-badan');
                if (el) el.focus();
            });
        },
        closeModal() {
            if (this.isSubmitting) return;
            this.selectedItem = null;
        }
     }">

    {{-- 1. TOP HEADER & BREADCRUMB --}}
    <div class="flex flex-col gap-1.5 sm:gap-2">
        <a href="{{ route('kader.dashboard') }}"
           class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-teal-700 transition-colors w-fit group py-0.5">
            <x-icon name="arrow-left" weight="bold" class="text-xs group-hover:-translate-x-0.5 transition-transform" />
            <span>Kembali ke Dashboard</span>
        </a>

        <div class="flex flex-col sm:flex-row sm:items-baseline justify-between gap-2">
            <div class="flex items-center gap-2.5 sm:gap-3 flex-wrap">
                <h1 class="text-lg sm:text-2xl font-bold tracking-tight text-slate-900 leading-snug">Validasi Ulang Pengukuran</h1>
                @if($total > 0)
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 sm:py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-900 border border-amber-200 shadow-2xs">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    {{ $total }} Balita Perlu Koreksi
                </span>
                @else
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 sm:py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                    <x-icon name="check-circle" weight="fill" class="text-emerald-600 text-sm" />
                    Semua Data Tervalidasi
                </span>
                @endif
            </div>

            <div class="inline-flex items-center gap-1.5 text-xs text-slate-500 font-medium">
                <x-icon name="map-pin" weight="fill" class="text-teal-600 text-xs sm:text-sm shrink-0" />
                <span class="truncate max-w-[160px] sm:max-w-none">{{ $posyanduName ?? 'Posyandu' }}</span>
                <span class="text-slate-300">·</span>
                <span class="shrink-0">{{ now()->translatedFormat('d M Y') }}</span>
            </div>
        </div>

        <p class="text-xs sm:text-sm text-slate-600 max-w-3xl leading-relaxed">
            Daftar data penimbangan balita yang dikembalikan oleh Tim Gizi Puskesmas karena terindikasi anomali angka atau salah input. Lakukan konfirmasi atau penimbangan ulang fisik balita, lalu kirim kembali hasil yang akurat.
        </p>
    </div>

    {{-- 2. SOP 3-LANGKAH KERJA KADER (MOBILE-OPTIMIZED COLLAPSIBLE) --}}
    <div class="bg-gradient-to-br from-slate-50 via-teal-50/20 to-slate-50 border border-slate-200 rounded-xl sm:rounded-2xl p-3.5 sm:p-5 shadow-2xs">
        <div class="flex items-center justify-between gap-2 mb-2 sm:mb-3">
            <div class="flex items-center gap-2">
                <div class="w-6 h-6 sm:w-7 sm:h-7 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center shrink-0">
                    <x-icon name="lightbulb" weight="fill" class="text-sm sm:text-base" />
                </div>
                <h2 class="text-xs sm:text-sm font-bold uppercase tracking-wider text-slate-800">
                    Alur Kerja Validasi Ulang
                </h2>
            </div>
            <button type="button" @click="showSop = !showSop"
                    class="text-xs font-semibold text-teal-700 hover:text-teal-800 flex items-center gap-1 py-1 px-2 rounded hover:bg-teal-50 transition-colors">
                <span x-text="showSop ? 'Sembunyikan' : 'Lihat Panduan'"></span>
                <span class="inline-flex transition-transform duration-200" :class="showSop ? 'rotate-180' : ''">
                    <x-icon name="caret-down" weight="bold" class="text-xs" />
                </span>
            </button>
        </div>

        <div x-show="showSop" x-collapse>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-2.5 sm:gap-3 text-xs sm:text-sm pt-1">
                <div class="flex items-start gap-2.5 sm:gap-3 bg-white/90 backdrop-blur-xs p-3 sm:p-3.5 rounded-xl border border-slate-200/80">
                    <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-teal-600 text-white font-bold flex items-center justify-center text-[11px] sm:text-xs shrink-0 mt-0.5">1</span>
                    <div>
                        <strong class="font-bold text-slate-900 block mb-0.5 text-xs sm:text-sm">1. Periksa Catatan</strong>
                        <span class="text-slate-500 text-[11.5px] sm:text-xs leading-relaxed">Baca catatan anomali dari petugas gizi puskesmas pada kartu balita.</span>
                    </div>
                </div>
                <div class="flex items-start gap-2.5 sm:gap-3 bg-white/90 backdrop-blur-xs p-3 sm:p-3.5 rounded-xl border border-slate-200/80">
                    <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-teal-600 text-white font-bold flex items-center justify-center text-[11px] sm:text-xs shrink-0 mt-0.5">2</span>
                    <div>
                        <strong class="font-bold text-slate-900 block mb-0.5 text-xs sm:text-sm">2. Konfirmasi &amp; Ukur</strong>
                        <span class="text-slate-500 text-[11.5px] sm:text-xs leading-relaxed">Hubungi ibu via WhatsApp atau jadwalkan ukur ulang di posyandu.</span>
                    </div>
                </div>
                <div class="flex items-start gap-2.5 sm:gap-3 bg-white/90 backdrop-blur-xs p-3 sm:p-3.5 rounded-xl border border-slate-200/80">
                    <span class="w-5 h-5 sm:w-6 sm:h-6 rounded-full bg-teal-600 text-white font-bold flex items-center justify-center text-[11px] sm:text-xs shrink-0 mt-0.5">3</span>
                    <div>
                        <strong class="font-bold text-slate-900 block mb-0.5 text-xs sm:text-sm">3. Simpan &amp; Kirim</strong>
                        <span class="text-slate-500 text-[11.5px] sm:text-xs leading-relaxed">Masukkan angka hasil koreksi untuk diverifikasi ulang Puskesmas.</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FLASH MESSAGES --}}
    @if(session('success'))
    <div class="rounded-xl bg-emerald-50 border border-emerald-200 p-3.5 sm:p-4 flex items-start gap-3 shadow-2xs">
        <x-icon name="check-circle" weight="fill" class="text-emerald-600 text-base sm:text-lg shrink-0 mt-0.5" />
        <div class="flex-1">
            <h2 class="text-xs sm:text-sm font-bold text-emerald-950">Data Berhasil Diperbarui &amp; Dikirim Ulang</h2>
            <p class="text-xs text-emerald-800 mt-0.5">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(isset($errors) && $errors->any())
    <div class="rounded-xl bg-rose-50 border border-rose-200 p-3.5 sm:p-4 flex items-start gap-3 shadow-2xs">
        <x-icon name="warning-circle" weight="fill" class="text-rose-600 text-base sm:text-lg shrink-0 mt-0.5" />
        <div class="flex-1">
            <h2 class="text-xs sm:text-sm font-bold text-rose-950">Mohon Periksa Kembali Isian Anda</h2>
            <ul class="list-disc list-inside text-xs text-rose-800 mt-1 space-y-0.5 font-medium">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    </div>
    @endif

    {{-- 3. TOOLBAR PENCARIAN (MOBILE OPTIMIZED: FULL WIDTH, EASY TAP) --}}
    <div class="bg-white border border-slate-200 rounded-xl p-3 sm:p-4 shadow-2xs flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-3">
        <form method="GET" action="{{ route('kader.validasi-ulang') }}" class="flex-1 flex flex-col sm:flex-row items-center gap-2">
            <div class="relative flex-1 w-full">
                <x-icon name="magnifying-glass" weight="bold" class="absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-base pointer-events-none" />
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama balita atau NIK…" aria-label="Cari balita"
                       class="w-full h-11 pl-10 pr-4 rounded-xl bg-slate-50 border border-slate-200 focus:bg-white focus:border-teal-600 focus:ring-2 focus:ring-teal-600/15 text-base sm:text-sm text-slate-800 placeholder:text-slate-400 transition-all focus:outline-none">
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 px-4 sm:px-5 h-11 rounded-xl bg-teal-600 hover:bg-teal-700 active:scale-[0.98] text-white text-sm font-semibold transition-colors flex-1 sm:flex-initial shrink-0 shadow-2xs focus:outline-none focus:ring-2 focus:ring-teal-600/20">
                    <x-icon name="magnifying-glass" weight="bold" class="text-base" />
                    <span>Cari Balita</span>
                </button>
                @if(request('q'))
                <a href="{{ route('kader.validasi-ulang') }}"
                   class="inline-flex items-center justify-center gap-1 px-3.5 h-11 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-600 text-xs sm:text-sm font-semibold transition-colors shrink-0">
                    <x-icon name="x" weight="bold" class="text-xs" />
                    <span>Reset</span>
                </a>
                @endif
            </div>
        </form>

        @if($total > 0)
        <div class="text-[11px] sm:text-xs text-slate-500 font-medium px-0.5 sm:text-right shrink-0">
            Menampilkan <strong class="text-slate-800">{{ count($items) }}</strong> antrean data
        </div>
        @endif
    </div>

    {{-- 4. DAFTAR ANTREAN VALIDASI ULANG --}}
    @if(count($items) === 0)
        {{-- EMPTY STATE --}}
        <div class="rounded-2xl bg-white border border-slate-200 p-8 sm:p-14 text-center flex flex-col items-center justify-center shadow-2xs">
            <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mb-3 sm:mb-4 border border-teal-100">
                <x-icon name="check-circle" weight="bold" class="text-2xl sm:text-3xl" />
            </div>
            <h2 class="text-base sm:text-lg font-bold text-slate-900">Tidak Ada Antrean Validasi Ulang</h2>
            <p class="text-xs sm:text-sm text-slate-500 max-w-md mt-1.5 leading-relaxed">
                @if(request('q'))
                    Tidak ditemukan data balita yang memerlukan koreksi dengan kata kunci <strong>"{{ request('q') }}"</strong>.
                @else
                    Luar biasa! Seluruh data penimbangan balita di <strong>{{ $posyanduName }}</strong> dalam status tervalidasi atau tidak memiliki catatan anomali dari Puskesmas.
                @endif
            </p>
            @if(request('q'))
            <a href="{{ route('kader.validasi-ulang') }}" class="mt-4 sm:mt-5 inline-flex items-center gap-2 px-4 sm:px-5 h-11 rounded-xl text-xs sm:text-sm font-semibold text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200 transition-colors">
                <x-icon name="arrow-counter-clockwise" weight="bold" />
                <span>Tampilkan Semua Data</span>
            </a>
            @else
            <a href="{{ route('balita.index') }}" class="mt-4 sm:mt-5 inline-flex items-center gap-2 px-5 h-11 rounded-xl text-xs sm:text-sm font-semibold text-white bg-teal-600 hover:bg-teal-700 transition-colors shadow-2xs">
                <x-icon name="baby" weight="bold" class="text-base" />
                <span>Buka Buku Daftar Balita</span>
            </a>
            @endif
        </div>
    @else
        <div class="flex flex-col gap-3.5 sm:gap-4">
            @foreach($items as $item)
                @php
                    $isGirl = in_array(strtolower($item['gender'] ?? ''), ['p', 'perempuan', 'female']);
                    $genderLabel = $isGirl ? 'Perempuan' : 'Laki-laki';
                    
                    // Format & Dekripsi Nomor Telepon Ibu (Kebal Ciphertext)
                    $phoneInput = $item['phone'] ?? '';
                    if (!empty($phoneInput) && str_starts_with($phoneInput, 'eyJ')) {
                        try {
                            $phoneInput = \Illuminate\Support\Facades\Crypt::decryptString($phoneInput);
                        } catch (\Throwable $e) {
                            $phoneInput = '';
                        }
                    }
                    $digitsOnly = preg_replace('/[^0-9]/', '', $phoneInput);
                    if (str_starts_with($digitsOnly, '0')) {
                        $waPhone = '62' . substr($digitsOnly, 1);
                        $displayPhone = $digitsOnly;
                    } elseif (str_starts_with($digitsOnly, '8')) {
                        $waPhone = '62' . $digitsOnly;
                        $displayPhone = '0' . $digitsOnly;
                    } elseif (str_starts_with($digitsOnly, '62')) {
                        $waPhone = $digitsOnly;
                        $displayPhone = '0' . substr($digitsOnly, 2);
                    } else {
                        $waPhone = $digitsOnly;
                        $displayPhone = $digitsOnly;
                    }

                    $waText = urlencode('Halo Ibu ' . $item['mother_name'] . ', salam dari Kader Posyandu ' . $posyanduName . '. Terkait pemantauan tumbuh kembang ananda ' . $item['child_name'] . ', kami mengundang Ibu untuk penimbangan ulang guna memastikan keakuratan data pertumbuhan ananda. Terima kasih.');

                    // Status Gizi Semantic Colors
                    $statusGizi = $item['status_gizi'] ?? '';
                    $lowerGizi = strtolower($statusGizi);
                    if (str_contains($lowerGizi, 'baik') || str_contains($lowerGizi, 'normal')) {
                        $giziBadgeClass = 'bg-emerald-50 text-emerald-800 border-emerald-200';
                    } elseif (str_contains($lowerGizi, 'kurang') || str_contains($lowerGizi, 'pendek') || str_contains($lowerGizi, 'risiko')) {
                        $giziBadgeClass = 'bg-amber-50 text-amber-900 border-amber-200';
                    } elseif (str_contains($lowerGizi, 'buruk') || str_contains($lowerGizi, 'severely') || str_contains($lowerGizi, 'sangat')) {
                        $giziBadgeClass = 'bg-rose-50 text-rose-900 border-rose-200';
                    } else {
                        $giziBadgeClass = 'bg-slate-100 text-slate-700 border-slate-200';
                    }

                    // KMS Status Semantic Colors
                    $kmsCode = strtoupper($item['status_kenaikan'] ?? '');
                    $kmsBadgeClass = match($kmsCode) {
                        'N' => 'bg-emerald-50 text-emerald-800 border-emerald-200 font-bold',
                        'T' => 'bg-rose-50 text-rose-800 border-rose-200 font-bold',
                        'B' => 'bg-sky-50 text-sky-800 border-sky-200 font-bold',
                        'O' => 'bg-amber-50 text-amber-800 border-amber-200 font-bold',
                        default => 'bg-slate-100 text-slate-600 border-slate-200',
                    };
                @endphp
                <article class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-2xs hover:border-slate-300 transition-all flex flex-col">
                    
                    {{-- HEADER KARTU: IDENTITAS BALITA & WAKTU PENOLAKAN --}}
                    <div class="px-3.5 sm:px-5 py-3 sm:py-4 bg-slate-50/70 border-b border-slate-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2 sm:gap-3">
                        <div class="flex items-center gap-2.5 sm:gap-3">
                            <span class="inline-flex items-center justify-center w-8 h-8 sm:w-9 sm:h-9 rounded-xl {{ $isGirl ? 'bg-rose-50 text-rose-700 border-rose-200' : 'bg-sky-50 text-sky-700 border-sky-200' }} font-bold text-xs sm:text-sm shrink-0 border">
                                {{ $isGirl ? 'P' : 'L' }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="flex items-center gap-1.5 sm:gap-2 flex-wrap">
                                    <h2 class="text-sm sm:text-base lg:text-lg font-bold text-slate-900 leading-tight truncate max-w-[200px] sm:max-w-none">{{ $item['child_name'] }}</h2>
                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] sm:text-[11px] font-semibold {{ $isGirl ? 'bg-rose-50 text-rose-700' : 'bg-sky-50 text-sky-700' }}">
                                        {{ $genderLabel }}
                                    </span>
                                    <span class="text-slate-300 hidden sm:inline">·</span>
                                    <span class="text-[11px] sm:text-xs text-slate-600 font-medium">Usia {{ $item['age_at_measure'] }}</span>
                                </div>
                                <div class="flex items-center gap-1.5 sm:gap-2 text-[11px] sm:text-xs text-slate-500 mt-0.5 sm:mt-1 flex-wrap">
                                    <span>Ibu: <strong class="text-slate-700 font-medium">{{ $item['mother_name'] }}</strong></span>
                                    @if(!empty($displayPhone) && strlen($displayPhone) >= 9)
                                    <span class="text-slate-600 font-mono text-[11px] bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200 inline-flex items-center gap-1">
                                        <x-icon name="phone" class="text-slate-400 text-xs" />
                                        <span>{{ $displayPhone }}</span>
                                    </span>
                                    @endif
                                    <span class="text-slate-300 hidden sm:inline">·</span>
                                    <span class="font-mono text-slate-600 text-[10.5px] sm:text-xs">NIK: {{ $item['child_nik'] ?: '-' }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="inline-flex items-center gap-1.5 text-[11px] sm:text-xs text-amber-900 bg-amber-50 border border-amber-200 px-2.5 sm:px-3 py-1 sm:py-1.5 rounded-lg w-fit shrink-0 font-medium self-start sm:self-auto">
                            <x-icon name="warning-circle" weight="fill" class="text-amber-600 text-xs sm:text-sm" />
                            <span>Ditolak: <strong class="font-bold">{{ $item['rejected_at'] }}</strong></span>
                        </div>
                    </div>

                    {{-- BADAN KARTU: CATATAN PUSKESMAS & DATA PENIMBANGAN SEBELUMNYA --}}
                    <div class="p-3.5 sm:p-5 flex flex-col gap-3.5 sm:gap-4">
                        
                        {{-- CATATAN PETUGAS GIZI PUSKESMAS (CLINICAL CALLOUT DENGAN KONTRAS TINGGI) --}}
                        <div class="rounded-xl bg-amber-50/80 border border-amber-200/90 p-3 sm:p-4 flex flex-col gap-1.5 sm:gap-2">
                            <div class="flex items-center justify-between gap-1 text-[11px] sm:text-xs flex-wrap">
                                <span class="font-bold text-amber-950 flex items-center gap-1.5">
                                    <x-icon name="chat-circle-dots" weight="fill" class="text-amber-700 text-sm sm:text-base shrink-0" />
                                    <span>Catatan Koreksi dari Puskesmas:</span>
                                </span>
                                <span class="text-slate-600 text-[11px] font-medium">
                                    Pemeriksa: <strong class="text-slate-800">{{ $item['validator_name'] }}</strong>
                                </span>
                            </div>
                            <blockquote class="text-xs sm:text-sm text-slate-900 font-semibold leading-relaxed pl-3 sm:pl-5 border-l-2 border-amber-400 my-0.5">
                                “{{ $item['catatan_validator'] }}”
                            </blockquote>
                        </div>

                        {{-- DATA PENGUKURAN LAMA YANG PERLU DIKOREKSI --}}
                        <div>
                            <div class="flex items-center justify-between text-[11px] sm:text-xs text-slate-500 mb-1.5 sm:mb-2 flex-wrap gap-1">
                                <span class="font-bold uppercase tracking-wider text-[10px] sm:text-[11px] text-slate-600 flex items-center gap-1">
                                    <x-icon name="clock-counter-clockwise" weight="bold" class="text-slate-400" />
                                    Data Sebelumnya:
                                </span>
                                <span class="text-slate-600">Tgl: <strong class="text-slate-800">{{ $item['formatted_tanggal_ukur'] }}</strong></span>
                            </div>

                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 sm:gap-3 items-stretch">
                                {{-- Berat Badan --}}
                                <div class="bg-slate-50 rounded-xl p-2.5 sm:p-3 border border-slate-200/80 flex flex-col justify-between">
                                    <span class="text-[11px] sm:text-xs text-slate-500 font-medium block">Berat Badan</span>
                                    <span class="text-slate-900 font-mono font-bold text-sm sm:text-base tabular-nums mt-0.5 block">
                                        {{ number_format((float)$item['berat_badan'], 2, ',', '.') }} <span class="text-[11px] sm:text-xs font-sans text-slate-500 font-normal">kg</span>
                                    </span>
                                </div>

                                {{-- Tinggi / Panjang Badan --}}
                                <div class="bg-slate-50 rounded-xl p-2.5 sm:p-3 border border-slate-200/80 flex flex-col justify-between">
                                    <span class="text-[11px] sm:text-xs text-slate-500 font-medium block">Tinggi / Panjang</span>
                                    <span class="text-slate-900 font-mono font-bold text-sm sm:text-base tabular-nums mt-0.5 block">
                                        {{ number_format((float)$item['tinggi_badan'], 1, ',', '.') }} <span class="text-[11px] sm:text-xs font-sans text-slate-500 font-normal">cm</span>
                                    </span>
                                </div>

                                {{-- Lingkar Kepala --}}
                                <div class="bg-slate-50 rounded-xl p-2.5 sm:p-3 border border-slate-200/80 flex flex-col justify-between">
                                    <span class="text-[11px] sm:text-xs text-slate-500 font-medium block">Lingkar Kepala</span>
                                    <span class="text-slate-900 font-mono font-bold text-sm sm:text-base tabular-nums mt-0.5 block">
                                        @if($item['lingkar_kepala'])
                                            {{ number_format((float)$item['lingkar_kepala'], 1, ',', '.') }} <span class="text-[11px] sm:text-xs font-sans text-slate-500 font-normal">cm</span>
                                        @else
                                            <span class="text-slate-400 text-xs sm:text-sm font-sans font-normal">-</span>
                                        @endif
                                    </span>
                                </div>

                                {{-- Status Gizi & KMS --}}
                                <div class="bg-slate-50 rounded-xl p-2.5 sm:p-3 border border-slate-200/80 flex flex-col justify-between">
                                    <span class="text-[11px] sm:text-xs text-slate-500 font-medium block mb-1">Status Gizi &amp; KMS</span>
                                    <div class="flex items-center gap-1 flex-wrap">
                                        @if($item['status_gizi'])
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] sm:text-[11px] font-bold border {{ $giziBadgeClass }}">
                                            {{ $item['status_gizi'] }}
                                        </span>
                                        @endif
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] sm:text-[11px] border {{ $kmsBadgeClass }}" title="Tren KMS">
                                            KMS: {{ $kmsCode ?: '-' }}
                                        </span>
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] sm:text-[11px] font-semibold {{ $item['asi_eksklusif'] ? 'bg-teal-50 text-teal-800 border border-teal-200' : 'bg-slate-100 text-slate-600 border border-slate-200' }}">
                                            ASI: {{ $item['asi_eksklusif'] ? 'Ya' : 'Tdk' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    {{-- FOOTER KARTU: AKSI KADER (MOBILE-FIRST BUTTON STACKING) --}}
                    <div class="px-3.5 sm:px-5 py-3 sm:py-3.5 bg-white border-t border-slate-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-3">
                        {{-- Tombol Sekunder di Mobile: 2 Kolom Berdampingan Rapi --}}
                        <div class="grid grid-cols-2 sm:flex sm:items-center gap-2 w-full sm:w-auto">
                            @if(!empty($waPhone) && strlen($waPhone) >= 10)
                            <a href="https://wa.me/{{ $waPhone }}?text={{ $waText }}"
                               target="_blank" rel="noopener noreferrer"
                               class="inline-flex items-center justify-center gap-1.5 px-3 h-11 rounded-xl border border-slate-200 bg-white hover:bg-emerald-50 hover:border-emerald-200 hover:text-emerald-800 text-slate-700 text-xs font-semibold transition-colors shadow-2xs text-center">
                                <x-icon name="whatsapp-logo" weight="fill" class="text-base text-emerald-600 shrink-0" />
                                <span class="truncate">Hubungi Ibu</span>
                            </a>
                            @else
                            <span class="inline-flex items-center justify-center gap-1.5 px-3 h-11 rounded-xl bg-slate-100 text-slate-400 text-xs font-medium cursor-not-allowed text-center">
                                <x-icon name="phone-slash" weight="bold" class="text-xs shrink-0" />
                                <span class="truncate">No WA (-)</span>
                            </span>
                            @endif

                            <a href="{{ route('balita.show', $item['balita_id']) }}"
                               class="inline-flex items-center justify-center gap-1.5 px-3 h-11 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-xs font-semibold transition-colors shadow-2xs text-center">
                                <x-icon name="chart-line-up" weight="bold" class="text-sm text-slate-400 shrink-0" />
                                <span class="truncate">Profil Balita</span>
                            </a>
                        </div>

                        {{-- Tombol Utama: Penuh di Mobile untuk Kenyamanan Jempol --}}
                        <button type="button"
                                @click="openModal(@js($item))"
                                class="inline-flex items-center justify-center gap-2 px-5 h-11 rounded-xl bg-teal-600 hover:bg-teal-700 active:scale-[0.98] text-white text-sm font-bold transition-all shadow-sm shadow-teal-600/20 w-full sm:w-auto">
                            <x-icon name="pencil-simple" weight="bold" class="text-base" />
                            <span>Koreksi / Input Ulang</span>
                        </button>
                    </div>

                </article>
            @endforeach
        </div>
    @endif

    {{-- 5. MODAL KOREKSI & UKUR ULANG (MOBILE-OPTIMIZED: NO-ZOOM 16PX INPUTS, BOTTOM-PINNED ACTIONS) --}}
    <template x-teleport="body">
        <div x-show="selectedItem !== null"
             x-cloak
             class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4 md:p-6"
             @keydown.escape.window="closeModal()">

            {{-- Backdrop --}}
            <div x-show="selectedItem !== null"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 @click="closeModal()"
                 class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs"></div>

            {{-- Dialog Window (Bottom-sheet di HP kecil, centered dialog di tablet/desktop) --}}
            <div x-show="selectedItem !== null"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                 class="relative w-full max-w-xl bg-white rounded-t-2xl sm:rounded-2xl shadow-2xl border border-slate-200 flex flex-col max-h-[94vh] sm:max-h-[92vh] overflow-hidden z-10">

                {{-- Seluruh Modal Dibungkus Tag Form Solid --}}
                <form :action="'{{ url('/kader/pengukuran') }}/' + (selectedItem?.id || '')"
                      method="POST"
                      @submit="isSubmitting = true"
                      class="flex flex-col h-full overflow-hidden">
                    @csrf
                    @method('PUT')

                    {{-- Modal Header --}}
                    <div class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50/90 shrink-0">
                        <div class="min-w-0 pr-2">
                            <h2 class="text-sm sm:text-base font-bold text-slate-900 truncate">Koreksi Data Penimbangan</h2>
                            <p class="text-[11px] sm:text-xs text-slate-500 mt-0.5 truncate">
                                <strong class="text-slate-800 font-semibold" x-text="selectedItem?.child_name"></strong>
                                <span class="text-slate-300 mx-1">·</span>
                                Ibu <span class="font-medium text-slate-700" x-text="selectedItem?.mother_name"></span>
                                <span class="text-slate-300 mx-1">·</span>
                                Usia <span x-text="selectedItem?.age_at_measure"></span>
                            </p>
                        </div>
                        <button type="button" @click="closeModal()" aria-label="Tutup dialog"
                                :disabled="isSubmitting"
                                class="w-8 h-8 sm:w-9 sm:h-9 flex items-center justify-center rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 transition-colors shrink-0">
                            <x-icon name="x" weight="bold" class="text-base sm:text-lg" />
                        </button>
                    </div>

                    {{-- Modal Body (Scrollable) --}}
                    <div class="overflow-y-auto p-4 sm:p-6 space-y-4 sm:space-y-5 flex-1 overscroll-contain">
                        
                        {{-- PANEL REFERENSI DATA LAMA YANG DITOLAK (ANTI CONTEXT BLINDNESS) --}}
                        <div class="rounded-xl bg-amber-50/90 border border-amber-200 p-3 sm:p-4 flex flex-col gap-2">
                            <div class="flex items-center gap-1.5 text-[11px] sm:text-xs font-bold text-amber-950">
                                <x-icon name="warning-circle" weight="fill" class="text-amber-600 text-xs sm:text-sm shrink-0" />
                                <span>Alasan Koreksi Puskesmas:</span>
                            </div>
                            <p class="text-xs sm:text-sm text-slate-900 font-semibold italic pl-3 sm:pl-5 leading-relaxed"
                               x-text="'“' + (selectedItem?.catatan_validator || 'Terdeteksi anomali data penimbangan.') + '”'"></p>

                            <div class="pt-2 border-t border-amber-200/70 flex flex-col sm:flex-row sm:items-center justify-between text-[11px] sm:text-xs gap-1 text-slate-600">
                                <span class="font-medium">Data Sebelumnya:</span>
                                <div class="flex items-center gap-2 sm:gap-3 font-mono font-bold text-slate-900 text-xs flex-wrap">
                                    <span>BB: <span x-text="(parseFloat(selectedItem?.berat_badan || 0)).toFixed(2) + ' kg'"></span></span>
                                    <span class="text-slate-300">·</span>
                                    <span>TB: <span x-text="(parseFloat(selectedItem?.tinggi_badan || 0)).toFixed(1) + ' cm'"></span></span>
                                    <span class="text-slate-300">·</span>
                                    <span>LK: <span x-text="selectedItem?.lingkar_kepala ? (parseFloat(selectedItem.lingkar_kepala)).toFixed(1) + ' cm' : '-'"></span></span>
                                </div>
                            </div>
                        </div>

                        {{-- FORM ISIAN DATA BARU --}}
                        <div class="space-y-3.5 sm:space-y-4">
                            
                            {{-- Tanggal Pengukuran Baru --}}
                            <div>
                                <div class="flex items-center justify-between mb-1">
                                    <label for="modal-tanggal-ukur" class="block text-xs font-bold uppercase tracking-wider text-slate-700">
                                        Tanggal Pengukuran Baru <span class="text-rose-500">*</span>
                                    </label>
                                    <button type="button" @click="form.tanggal_ukur = '{{ date('Y-m-d') }}'"
                                            class="text-[11px] font-semibold text-teal-700 hover:text-teal-800 bg-teal-50 hover:bg-teal-100 px-2 py-0.5 rounded transition-colors">
                                        Gunakan Hari Ini
                                    </button>
                                </div>
                                <input type="date" id="modal-tanggal-ukur" name="tanggal_ukur"
                                       x-model="form.tanggal_ukur"
                                       max="{{ date('Y-m-d') }}"
                                       required
                                       class="w-full h-11 px-3.5 rounded-xl border border-slate-300 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/15 text-base sm:text-sm font-medium text-slate-800 shadow-2xs focus:outline-none">
                                <p class="text-[11px] text-slate-500 mt-1">Isi dengan tanggal balita ditimbang ulang secara fisik.</p>
                            </div>

                            {{-- Grid 3 Kolom: Berat, Tinggi, Lingkar Kepala (1 Kolom di Mobile untuk Mencegah Sempit) --}}
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 sm:gap-3.5">
                                {{-- Berat Badan --}}
                                <div>
                                    <label for="modal-berat-badan" class="block text-xs font-bold text-slate-700 mb-1">
                                        Berat Badan <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" step="0.01" min="0.5" max="100" id="modal-berat-badan" name="berat_badan"
                                               inputmode="decimal"
                                               x-model="form.berat_badan"
                                               @input="$event.target.value = $event.target.value.replace(',', '.'); form.berat_badan = $event.target.value"
                                               required placeholder="0.00"
                                               class="w-full h-11 px-3.5 pr-10 rounded-xl border border-slate-300 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/15 text-base sm:text-sm font-bold text-slate-900 shadow-2xs focus:outline-none font-mono tabular-nums">
                                        <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 pointer-events-none">kg</span>
                                    </div>
                                    <span class="text-[10.5px] sm:text-[11px] text-slate-400 mt-0.5 block">Gunakan titik (cth: 9.45)</span>
                                    
                                    {{-- Sanity Check Helper --}}
                                    <template x-if="form.berat_badan && (parseFloat(form.berat_badan) < 1.5 || parseFloat(form.berat_badan) > 30)">
                                        <div class="mt-1.5 text-[11px] text-amber-800 font-medium flex items-center gap-1 bg-amber-50 p-1.5 rounded-lg border border-amber-200">
                                            <x-icon name="warning" weight="bold" class="text-amber-600 shrink-0 text-xs" />
                                            <span>Angka BB di luar rentang normal balita (1.5 - 30 kg).</span>
                                        </div>
                                    </template>
                                </div>

                                {{-- Tinggi Badan --}}
                                <div>
                                    <label for="modal-tinggi-badan" class="block text-xs font-bold text-slate-700 mb-1">
                                        Tinggi / Panjang <span class="text-rose-500">*</span>
                                    </label>
                                    <div class="relative">
                                        <input type="number" step="0.1" min="20" max="200" id="modal-tinggi-badan" name="tinggi_badan"
                                               inputmode="decimal"
                                               x-model="form.tinggi_badan"
                                               @input="$event.target.value = $event.target.value.replace(',', '.'); form.tinggi_badan = $event.target.value"
                                               required placeholder="0.0"
                                               class="w-full h-11 px-3.5 pr-10 rounded-xl border border-slate-300 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/15 text-base sm:text-sm font-bold text-slate-900 shadow-2xs focus:outline-none font-mono tabular-nums">
                                        <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 pointer-events-none">cm</span>
                                    </div>
                                    <span class="text-[10.5px] sm:text-[11px] text-slate-400 mt-0.5 block">Contoh: 76.2</span>

                                    {{-- Sanity Check Helper --}}
                                    <template x-if="form.tinggi_badan && (parseFloat(form.tinggi_badan) < 35 || parseFloat(form.tinggi_badan) > 135)">
                                        <div class="mt-1.5 text-[11px] text-amber-800 font-medium flex items-center gap-1 bg-amber-50 p-1.5 rounded-lg border border-amber-200">
                                            <x-icon name="warning" weight="bold" class="text-amber-600 shrink-0 text-xs" />
                                            <span>Angka TB di luar rentang umum balita (35 - 135 cm).</span>
                                        </div>
                                    </template>
                                </div>

                                {{-- Lingkar Kepala --}}
                                <div>
                                    <label for="modal-lingkar-kepala" class="block text-xs font-bold text-slate-700 mb-1">
                                        Lingkar Kepala
                                    </label>
                                    <div class="relative">
                                        <input type="number" step="0.1" min="10" max="99.9" id="modal-lingkar-kepala" name="lingkar_kepala"
                                               inputmode="decimal"
                                               x-model="form.lingkar_kepala"
                                               @input="$event.target.value = $event.target.value.replace(',', '.'); form.lingkar_kepala = $event.target.value"
                                               placeholder="0.0"
                                               class="w-full h-11 px-3.5 pr-10 rounded-xl border border-slate-300 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/15 text-base sm:text-sm font-bold text-slate-900 shadow-2xs focus:outline-none font-mono tabular-nums">
                                        <span class="absolute right-3.5 top-1/2 -translate-y-1/2 text-xs font-bold text-slate-400 pointer-events-none">cm</span>
                                    </div>
                                    <span class="text-[10.5px] sm:text-[11px] text-slate-400 mt-0.5 block">Opsional</span>
                                </div>
                            </div>

                            {{-- Tren KMS & ASI Eksklusif --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-3.5 pt-0.5">
                                <div>
                                    <label for="modal-status-kenaikan" class="block text-xs font-bold text-slate-700 mb-1">
                                        Tren Kenaikan Berat Badan (KMS)
                                    </label>
                                    <select id="modal-status-kenaikan" name="status_kenaikan"
                                            x-model="form.status_kenaikan"
                                            class="w-full h-11 px-3 rounded-xl border border-slate-300 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/15 text-base sm:text-sm font-medium text-slate-800 shadow-2xs focus:outline-none bg-white">
                                        <option value="">-- Pilih Tren KMS --</option>
                                        <option value="N">Naik (N) - Sesuai Garis Pertumbuhan</option>
                                        <option value="T">Tidak Naik (T) - Tetap / Turun</option>
                                        <option value="B">Baru Pertama Kali Ditimbang (B)</option>
                                        <option value="O">Bulan Lalu Tidak Hadir (O)</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-700 mb-1">
                                        Status Pemberian ASI
                                    </label>
                                    <label class="relative flex items-center gap-3 px-3.5 h-11 rounded-xl border border-slate-300 bg-slate-50/70 hover:bg-slate-100/70 transition-colors cursor-pointer">
                                        <input type="checkbox" name="asi_eksklusif" value="1"
                                               x-model="form.asi_eksklusif"
                                               class="w-4 h-4 rounded text-teal-600 focus:ring-teal-500 border-slate-300">
                                        <span class="text-xs font-semibold text-slate-800">ASI Eksklusif (&lt; 6 bulan)</span>
                                    </label>
                                </div>
                            </div>

                            {{-- Catatan Klarifikasi / Konfirmasi Kader --}}
                            <div>
                                <label for="modal-catatan-kader" class="block text-xs font-bold text-slate-700 mb-1">
                                    Catatan Konfirmasi Kader <span class="text-slate-400 font-normal">(opsional)</span>
                                </label>
                                <textarea id="modal-catatan-kader" name="catatan_kader" rows="2"
                                          x-model="form.catatan_kader"
                                          placeholder="Contoh: Sudah ditimbang ulang dengan timbangan digital posyandu. Angka sebelumnya salah ketik."
                                          maxlength="500"
                                          class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:border-teal-600 focus:ring-2 focus:ring-teal-600/15 text-base sm:text-sm font-medium text-slate-800 shadow-2xs focus:outline-none placeholder:text-slate-400"></textarea>
                                <p class="text-[10.5px] sm:text-[11px] text-slate-400 mt-1">Berikan penjelasan singkat agar petugas Puskesmas mengetahui latar belakang perbaikan data.</p>
                            </div>

                        </div>

                    </div>

                    {{-- Modal Footer (Mobile Stack: Primary on top, Secondary below, Easy Reach) --}}
                    <div class="px-4 sm:px-6 py-3.5 sm:py-4 bg-slate-50 border-t border-slate-200 flex flex-col-reverse sm:flex-row sm:items-center justify-between gap-2.5 sm:gap-3 shrink-0">
                        <div class="text-[11px] sm:text-xs text-slate-500 flex items-center justify-center sm:justify-start gap-1.5 text-center">
                            <x-icon name="shield-check" weight="fill" class="text-teal-600 text-sm sm:text-base shrink-0" />
                            <span>Data akan diverifikasi ulang oleh Tim Puskesmas</span>
                        </div>

                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <button type="button" @click="closeModal()"
                                    :disabled="isSubmitting"
                                    class="px-4 h-11 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs sm:text-sm font-semibold transition-colors flex-1 sm:flex-initial">
                                Batal
                            </button>
                            <button type="submit"
                                    :disabled="isSubmitting"
                                    class="inline-flex items-center justify-center gap-2 px-5 h-11 rounded-xl bg-teal-600 hover:bg-teal-700 active:scale-[0.98] disabled:opacity-60 disabled:cursor-not-allowed text-white text-xs sm:text-sm font-bold transition-all shadow-sm shadow-teal-600/20 flex-2 sm:flex-initial">
                                <template x-if="!isSubmitting">
                                    <span class="inline-flex items-center gap-1.5 sm:gap-2">
                                        <x-icon name="paper-plane-right" weight="bold" class="text-sm sm:text-base" />
                                        <span>Simpan &amp; Kirim</span>
                                    </span>
                                </template>
                                <template x-if="isSubmitting">
                                    <span class="inline-flex items-center gap-1.5 sm:gap-2">
                                        <x-icon name="spinner" weight="bold" class="text-sm sm:text-base animate-spin" />
                                        <span>Menyimpan...</span>
                                    </span>
                                </template>
                            </button>
                        </div>
                    </div>

                </form>

            </div>
        </div>
    </template>

</div>
@endsection
