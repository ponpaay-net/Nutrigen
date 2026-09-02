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
                <span class="w-1 h-6 bg-amber-400 rounded-full"></span>
                <h1 class="text-lg font-bold text-slate-900">Profil Kader</h1>
            </div>
            <p class="text-[13px] text-slate-500 mt-1 ml-4">Kelola informasi dan keamanan akun Anda.</p>
        </div>

        {{-- HERO (kartu putih clean, bold = tombol Edit kuning) --}}
        <section class="mb-5">
            <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm">
                <div class="flex flex-col sm:flex-row gap-5 items-start">
                    {{-- avatar + nama --}}
                    <div class="flex items-center gap-4 flex-1 min-w-0">
                        <div class="shrink-0">
                            <div class="w-14 h-14 sm:w-16 sm:h-16 rounded-xl bg-teal-600 text-white flex items-center justify-center font-black text-xl shadow-sm">{{ $ini }}</div>
                        </div>
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-xl font-bold text-slate-900 leading-tight truncate">{{ $kaderName }}</h2>
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-800 bg-emerald-50 border border-emerald-200 px-2 py-0.5 rounded-full"><x-icon name="check" weight="bold" class="text-[11px]" />{{ $status }}</span>
                            </div>
                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-[12px] font-semibold"><x-icon name="user-circle" weight="bold" class="text-[13px]" />{{ $role }}</span>
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 text-[12px] font-semibold"><x-icon name="map-pin" weight="bold" class="text-[13px]" />{{ $posyanduName }}</span>
                            </div>
                            @if($infoloc && $infoloc !== ($posyanduName ?? ''))
                                <p class="text-[12.5px] text-slate-500 mt-2">{{ $infoloc }}</p>
                            @endif
                        </div>
                    </div>

                    {{-- actions (Edit = KUNING, bold; Keamanan = netral) --}}
                    <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto shrink-0">
                        <a href="{{ route('kader.profil.edit') }}" class="inline-flex items-center justify-center gap-2 h-11 px-5 rounded-xl bg-amber-400 hover:bg-amber-500 text-slate-900 text-[13.5px] font-bold shadow-sm transition-colors"><x-icon name="pencil-line" weight="bold" class="text-[16px]" />Edit Profil</a>
                        <a href="{{ route('kader.keamanan') }}" class="inline-flex items-center justify-center gap-2 h-11 px-4 rounded-xl bg-white border border-slate-300 hover:bg-slate-50 text-slate-700 text-[13.5px] font-semibold transition-colors"><x-icon name="lock" weight="bold" class="text-[16px]" />Keamanan</a>
                    </div>
                </div>

                {{-- statistik (dipakai divider, tile slate-50, icon teal) --}}
                <div class="mt-5 pt-5 border-t border-slate-100 grid grid-cols-2 sm:grid-cols-4 gap-3">
                    @php
                        $stats = [
                            ['label' => 'Bergabung', 'value' => $joinedAt ?? '-', 'icon' => 'calendar'],
                            ['label' => 'Balita Aktif', 'value' => (int)($balitaCount ?? 0), 'icon' => 'baby'],
                            ['label' => 'Pengukuran', 'value' => (int)($pengukuranCount ?? 0), 'icon' => 'ruler'],
                            ['label' => 'Jadwal', 'value' => (int)($jadwalCount ?? 0), 'icon' => 'calendar-blank'],
                        ];
                    @endphp
                    @foreach($stats as $s)
                        <div class="flex items-center gap-3 rounded-xl p-3">
                            <span class="w-9 h-9 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0"><x-icon name="{{ $s['icon'] }}" weight="bold" class="text-[16px]" /></span>
                            <div class="min-w-0">
                                <p class="text-base font-bold text-slate-900 leading-tight">{{ $s['value'] }}</p>
                                <p class="text-[10.5px] font-medium text-slate-400 uppercase tracking-wide truncate">{{ $s['label'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- INFO: kontak + penugasan (satu kartu, 2 kolom) --}}
        <section class="mb-5">
            <div class="bg-white border border-slate-200 rounded-2xl p-5 sm:p-6 shadow-sm">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
                    <div>
                        <h3 class="text-[13px] font-bold text-slate-800 flex items-center gap-2 mb-3"><span class="w-7 h-7 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center"><x-icon name="clipboard-text" weight="bold" class="text-[15px]" /></span>Detail Kontak</h3>
                        <div class="flex flex-col gap-2.5">
                            <div class="flex items-center gap-3.5">
                                <span class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0"><x-icon name="at" weight="bold" class="text-[18px]" /></span>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-medium text-slate-400">Alamat Email</p>
                                    <p class="text-[13.5px] font-semibold text-slate-800 truncate">{{ $email ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3.5">
                                <span class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0"><x-icon name="phone" weight="bold" class="text-[18px]" /></span>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-medium text-slate-400">Nomor WhatsApp</p>
                                    <p class="text-[13.5px] font-semibold text-slate-800 truncate">{{ $phone ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-[13px] font-bold text-slate-800 flex items-center gap-2 mb-3"><span class="w-7 h-7 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center"><x-icon name="map-pin" weight="bold" class="text-[15px]" /></span>Area Penugasan</h3>
                        <div class="flex flex-col gap-2.5">
                            <div class="flex items-center gap-3.5">
                                <span class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0"><x-icon name="map-pin" weight="bold" class="text-[18px]" /></span>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-medium text-slate-400">Cakupan Wilayah</p>
                                    <p class="text-[13.5px] font-semibold text-slate-800 truncate">{{ $infoloc }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-3.5">
                                <span class="w-10 h-10 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0"><x-icon name="first-aid-kit" weight="bold" class="text-[18px]" /></span>
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
