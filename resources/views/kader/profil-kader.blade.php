@extends('layouts.app')

@section('page-title', 'Profil Kader')

@section('content')
@php
    $ini = strtoupper(collect(explode(' ', $kaderName ?? 'Kader'))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->implode(''));
    $kec = ($kecamatan ?? '') !== '-' ? $kecamatan : '';
    $desaC = trim($desa ?? ''); if ($desaC === '-') $desaC = '';
    $lokasi = trim($desaC . ($kec ? ', Kec. ' . $kec : ''));
    $infoloc = $lokasi ?: ($posyanduName ?? '');
@endphp

<div class="w-full pb-28 sm:pb-12">
    <div class="max-w-5xl mx-auto">

        {{-- Page header --}}
        <div class="mb-5">
            <div class="flex items-center gap-3">
                <span class="w-1 h-6 bg-teal-600 rounded-full"></span>
                <h1 class="text-lg font-bold text-slate-900">Profil Kader</h1>
            </div>
            <p class="text-[13px] text-slate-500 mt-1 ml-4">Kelola informasi dan keamanan akun Anda.</p>
        </div>

        {{-- HERO: identitas + statistik (satu kartu, teal gradient rich tanpa blur) --}}
        <section class="mb-5">
            <div class="bg-gradient-to-br from-teal-600 via-teal-600 to-teal-800 rounded-2xl p-5 sm:p-7 text-white shadow-md shadow-teal-900/10">
                <div class="flex flex-col sm:flex-row gap-5 items-center sm:items-start">
                    {{-- avatar --}}
                    <div class="shrink-0">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-white/20 border border-white/30 text-white flex items-center justify-center font-black text-xl sm:text-2xl shadow-inner">{{ $ini }}</div>
                    </div>

                    {{-- nama + role --}}
                    <div class="flex-1 text-center sm:text-left min-w-0">
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2">
                            <h2 class="text-xl font-bold leading-tight truncate">{{ $kaderName }}</h2>
                            <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-600 bg-white px-2 py-0.5 rounded-full shadow-sm"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>{{ $status }}</span>
                        </div>
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-2.5">
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/15 border border-white/15 text-white text-[12px] font-semibold"><x-icon name="user-circle" weight="bold" class="text-[13px]" />{{ $role }}</span>
                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-white/15 border border-white/15 text-white text-[12px] font-semibold"><x-icon name="map-pin" weight="bold" class="text-[13px]" />{{ $posyanduName }}</span>
                        </div>
                        @if($infoloc && $infoloc !== ($posyanduName ?? ''))
                            <p class="text-[12.5px] text-teal-50/95 mt-2.5">{{ $infoloc }}</p>
                        @endif
                    </div>

                    {{-- actions (solid, jelas) --}}
                    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto shrink-0">
                        <a href="{{ route('kader.profil.edit') }}" class="inline-flex items-center justify-center gap-2 h-11 px-4 rounded-xl bg-white hover:bg-teal-50 text-teal-700 text-[13.5px] font-bold shadow-sm transition-all"><x-icon name="pencil-line" weight="bold" class="text-[16px]" />Edit Profil</a>
                        <a href="{{ route('kader.keamanan') }}" class="inline-flex items-center justify-center gap-2 h-11 px-4 rounded-xl bg-white/20 hover:bg-white/30 border border-white/30 text-white text-[13.5px] font-bold transition-colors"><x-icon name="lock" weight="bold" class="text-[16px]" />Keamanan</a>
                    </div>
                </div>

                {{-- statistik inline --}}
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6">
                    @php
                        $stats = [
                            ['label' => 'Bergabung', 'value' => $joinedAt ?? '-', 'icon' => 'calendar'],
                            ['label' => 'Balita Aktif', 'value' => (int)($balitaCount ?? 0), 'icon' => 'baby'],
                            ['label' => 'Pengukuran', 'value' => (int)($pengukuranCount ?? 0), 'icon' => 'ruler'],
                            ['label' => 'Jadwal', 'value' => (int)($jadwalCount ?? 0), 'icon' => 'calendar-blank'],
                        ];
                    @endphp
                    @foreach($stats as $s)
                        <div class="bg-white/15 border border-white/15 rounded-xl p-3 text-center">
                            <span class="w-7 h-7 mx-auto rounded-lg bg-white/25 text-white flex items-center justify-center mb-1.5"><x-icon name="{{ $s['icon'] }}" weight="bold" class="text-[13px]" /></span>
                            <p class="text-base font-bold leading-tight">{{ $s['value'] }}</p>
                            <p class="text-[10.5px] font-medium text-teal-50/90 uppercase tracking-wide">{{ $s['label'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- INFO: kontak + penugasan (satu kartu, 2 kolom desktop) --}}
        <section class="mb-5">
            <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                    {{-- Detail Kontak --}}
                    <div>
                        <h3 class="text-[13px] font-bold text-slate-800 flex items-center gap-2 mb-3"><span class="w-7 h-7 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center"><x-icon name="clipboard-text" weight="bold" class="text-[15px]" /></span>Detail Kontak</h3>
                        <div class="flex flex-col gap-2.5">
                            <div class="flex items-center gap-3.5">
                                <span class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 border border-teal-100"><x-icon name="at" weight="bold" class="text-[18px]" /></span>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-medium text-slate-400">Alamat Email</p>
                                    <p class="text-[13.5px] font-semibold text-slate-800 truncate">{{ $email ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3.5">
                                <span class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 border border-teal-100"><x-icon name="phone" weight="bold" class="text-[18px]" /></span>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-medium text-slate-400">Nomor WhatsApp</p>
                                    <p class="text-[13.5px] font-semibold text-slate-800 truncate">{{ $phone ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Area Penugasan --}}
                    <div>
                        <h3 class="text-[13px] font-bold text-slate-800 flex items-center gap-2 mb-3"><span class="w-7 h-7 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center"><x-icon name="map-pin" weight="bold" class="text-[15px]" /></span>Area Penugasan</h3>
                        <div class="flex flex-col gap-2.5">
                            <div class="flex items-center gap-3.5">
                                <span class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 border border-teal-100"><x-icon name="map-pin" weight="bold" class="text-[18px]" /></span>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-medium text-slate-400">Cakupan Wilayah</p>
                                    <p class="text-[13.5px] font-semibold text-slate-800 truncate">{{ $infoloc }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3.5">
                                <span class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 border border-teal-100"><x-icon name="first-aid-kit" weight="bold" class="text-[18px]" /></span>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-medium text-slate-400">Fasilitas Rujukan</p>
                                    <p class="text-[13.5px] font-semibold text-slate-800 truncate">{{ $puskesmas ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- KEAMANAN / logout --}}
        <section class="mb-6">
            <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <span class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0"><x-icon name="sign-out" weight="bold" class="text-[18px]" /></span>
                        <div>
                            <h3 class="text-[13.5px] font-bold text-slate-800">Keamanan Sesi</h3>
                            <p class="text-[12px] text-slate-500 mt-0.5">Akhiri sesi Anda sekarang untuk menjaga keamanan data Posyandu.</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="w-full sm:w-auto" onsubmit="if(window.NutriAlert && typeof window.NutriAlert.confirm === 'function'){ event.preventDefault(); const form = this; window.NutriAlert.confirm('Keluar dari Akun?', 'Apakah Anda yakin ingin keluar dari Portal Kader?', 'Keluar', 'Batal').then((r) => { if(r.isConfirmed) form.submit(); }); return false; } return confirm('Keluar dari Portal Kader?');">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-11 px-5 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-[13.5px] font-bold shadow-sm transition-colors"><x-icon name="sign-out" weight="bold" class="text-[16px]" />Keluar Perangkat</button>
                    </form>
                </div>
            </div>
        </section>

    </div>
</div>
@endsection
