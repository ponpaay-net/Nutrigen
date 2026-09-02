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
        <div class="mb-6">
            <div class="flex items-center gap-3">
                <span class="w-1 h-6 bg-amber-400 rounded-full"></span>
                <h1 class="text-lg font-bold text-slate-900">Profil Kader</h1>
            </div>
            <p class="text-[13px] text-slate-500 mt-1 ml-4">Kelola informasi dan keamanan akun Anda.</p>
        </div>

        {{-- HERO (white, soft elevation, aksen teal, CTA kuning) --}}
        <section class="mb-6">
            <div class="bg-white ring-1 ring-slate-200/70 rounded-2xl shadow-sm shadow-slate-200/60 overflow-hidden">
                <div class="flex flex-col sm:flex-row gap-6 p-6 sm:p-8">
                    {{-- left: avatar + nama --}}
                    <div class="flex items-center gap-5 flex-1 min-w-0">
                        <div class="relative shrink-0">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-teal-600 text-white flex items-center justify-center font-bold text-xl sm:text-2xl ring-2 ring-white shadow-md shadow-teal-700/30">{{ $ini }}</div>
                            <span class="absolute bottom-0 right-0 w-4 h-4 rounded-full bg-emerald-500 ring-2 ring-white"></span>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-xl sm:text-2xl font-bold text-slate-900 leading-tight truncate">{{ $kaderName }}</h2>
                            <div class="flex flex-wrap items-center gap-x-2 gap-y-1 mt-1.5 text-[13px] text-slate-500 font-medium">
                                <span class="inline-flex items-center gap-1.5"><x-icon name="user-circle" weight="bold" class="text-[14px] text-teal-600" />{{ $role }}</span>
                                <span class="text-slate-300">•</span>
                                <span class="inline-flex items-center gap-1.5"><x-icon name="map-pin" weight="bold" class="text-[14px] text-teal-600" />{{ $posyanduName }}</span>
                            </div>
                            @if($infoloc && $infoloc !== ($posyanduName ?? ''))
                                <p class="text-[12.5px] text-slate-400 mt-1.5">{{ $infoloc }}</p>
                            @endif
                            <div class="mt-2">
                                <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-full"><x-icon name="check-circle" weight="fill" class="text-[12px]" />{{ $status }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- right: actions --}}
                    <div class="flex flex-col sm:flex-row items-stretch gap-2.5 w-full sm:w-auto shrink-0">
                        <a href="{{ route('kader.profil.edit') }}" class="inline-flex items-center justify-center gap-2 h-11 px-6 rounded-xl bg-amber-400 hover:bg-amber-500 text-slate-900 text-[13.5px] font-bold shadow-md shadow-amber-400/30 transition-all hover:shadow-lg hover:shadow-amber-500/30 active:scale-[0.98]"><x-icon name="pencil-line" weight="bold" class="text-[16px]" />Edit Profil</a>
                        <a href="{{ route('kader.keamanan') }}" class="inline-flex items-center justify-center gap-2 h-11 px-5 rounded-xl bg-white border border-slate-300 text-slate-700 hover:border-teal-300 hover:text-teal-700 hover:bg-teal-50/40 text-[13.5px] font-semibold transition-colors"><x-icon name="lock" weight="bold" class="text-[16px]" />Keamanan</a>
                    </div>
                </div>

                {{-- statistik (divider solid + tile slate-50 + icon teal) --}}
                <div class="border-t border-slate-100 grid grid-cols-2 sm:grid-cols-4 gap-3 p-6 sm:px-8 sm:py-6 bg-slate-50">
                    @php
                        $stats = [
                            ['label' => 'Bergabung', 'value' => $joinedAt ?? '-', 'icon' => 'user-plus'],
                            ['label' => 'Balita Aktif', 'value' => (int)($balitaCount ?? 0), 'icon' => 'baby'],
                            ['label' => 'Pengukuran', 'value' => (int)($pengukuranCount ?? 0), 'icon' => 'ruler'],
                            ['label' => 'Jadwal', 'value' => (int)($jadwalCount ?? 0), 'icon' => 'calendar-blank'],
                        ];
                    @endphp
                    @foreach($stats as $s)
                        <div class="flex items-center gap-3 rounded-xl bg-white border border-slate-200 shadow-sm p-3.5">
                            <span class="w-10 h-10 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center shrink-0"><x-icon name="{{ $s['icon'] }}" weight="bold" class="text-[18px]" /></span>
                            <div class="min-w-0">
                                <p class="text-lg font-bold text-slate-900 leading-none">{{ $s['value'] }}</p>
                                <p class="text-[11px] font-medium text-slate-500 mt-1 truncate">{{ $s['label'] }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        {{-- INFO: kontak + penugasan (satu kartu, 2 kolom) --}}
        <section class="mb-6">
            <div class="bg-white ring-1 ring-slate-200/70 rounded-2xl shadow-sm shadow-slate-200/50 p-6 sm:p-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-10">
                    <div>
                        <h3 class="text-[13px] font-bold text-slate-800 flex items-center gap-2 mb-4"><span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center"><x-icon name="clipboard-text" weight="bold" class="text-[16px]" /></span>Detail Kontak</h3>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-4 py-3">
                                <span class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 flex items-center justify-center shrink-0"><x-icon name="at" weight="bold" class="text-[18px]" /></span>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-medium text-slate-400">Alamat Email</p>
                                    <p class="text-[13.5px] font-semibold text-slate-800 truncate">{{ $email ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="h-px bg-slate-100"></div>
                            <div class="flex items-center gap-4 py-3">
                                <span class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 flex items-center justify-center shrink-0"><x-icon name="phone" weight="bold" class="text-[18px]" /></span>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-medium text-slate-400">Nomor WhatsApp</p>
                                    <p class="text-[13.5px] font-semibold text-slate-800 truncate">{{ $phone ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div>
                        <h3 class="text-[13px] font-bold text-slate-800 flex items-center gap-2 mb-4"><span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center"><x-icon name="map-pin" weight="bold" class="text-[16px]" /></span>Area Penugasan</h3>
                        <div class="flex flex-col">
                            <div class="flex items-center gap-4 py-3">
                                <span class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 flex items-center justify-center shrink-0"><x-icon name="map-pin" weight="bold" class="text-[18px]" /></span>
                                <div class="min-w-0">
                                    <p class="text-[11px] font-medium text-slate-400">Cakupan Wilayah</p>
                                    <p class="text-[13.5px] font-semibold text-slate-800 truncate">{{ $infoloc }}</p>
                                </div>
                            </div>
                            <div class="h-px bg-slate-100"></div>
                            <div class="flex items-center gap-4 py-3">
                                <span class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 flex items-center justify-center shrink-0"><x-icon name="first-aid-kit" weight="bold" class="text-[18px]" /></span>
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

        {{-- KEAMANAN / logout (soft danger) --}}
        <section class="mb-6">
            <div class="bg-white ring-1 ring-slate-200/70 rounded-2xl shadow-sm shadow-slate-200/50 p-6">
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-5">
                    <div class="flex items-center gap-4">
                        <span class="w-11 h-11 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0"><x-icon name="sign-out" weight="bold" class="text-[19px]" /></span>
                        <div>
                            <h3 class="text-[14px] font-bold text-slate-800">Keamanan Sesi</h3>
                            <p class="text-[12.5px] text-slate-500 mt-0.5">Akhiri sesi Anda sekarang untuk menjaga keamanan data Posyandu.</p>
                        </div>
                    </div>
                    <form action="{{ route('logout') }}" method="POST" class="w-full sm:w-auto" onsubmit="if(window.NutriAlert && typeof window.NutriAlert.confirm === 'function'){ event.preventDefault(); const form = this; window.NutriAlert.confirm('Keluar dari Akun?', 'Apakah Anda yakin ingin keluar dari Portal Kader?', 'Keluar', 'Batal').then((r) => { if(r.isConfirmed) form.submit(); }); return false; } return confirm('Keluar dari Portal Kader?');">
                        @csrf
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-11 px-5 rounded-xl bg-rose-50 border border-rose-200 text-rose-600 hover:bg-rose-600 hover:text-white hover:border-rose-600 text-[13.5px] font-bold transition-colors"><x-icon name="sign-out" weight="bold" class="text-[16px]" />Keluar Perangkat</button>
                    </form>
                </div>
            </div>
        </section>

    </div>
</div>
@endsection
