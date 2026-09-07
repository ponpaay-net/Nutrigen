@extends('layouts.app')

@section('page-title', $puskesmas->nama . ' — NutriGen')

@section('content')
<div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full min-h-screen" style="background:#F7F8FA; font-family:'Plus Jakarta Sans',sans-serif;">
    
    {{-- Breadcrumb Navigation --}}
    <nav class="flex items-center justify-between text-sm font-medium text-slate-500 mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-2 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('super-admin.dashboard') }}" class="inline-flex items-center hover:text-teal-600 transition-colors">
                    <x-icon name="squares-four" weight="fill" class="text-base mr-1.5" />
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <x-icon name="caret-right" weight="bold" class="text-sm mx-1" />
                    <a href="{{ route('super-admin.puskesmas.index') }}" class="ml-1 md:ml-2 hover:text-teal-600 transition-colors">Master Data Puskesmas</a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <x-icon name="caret-right" weight="bold" class="text-sm mx-1" />
                    <span class="ml-1 md:ml-2 text-slate-900 font-bold">Detail</span>
                </div>
            </li>
        </ol>
        
        {{-- Tombol Kembali di Pojok Kanan Atas --}}
        <a href="{{ route('super-admin.puskesmas.index') }}" class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 hover:text-slate-900 transition-colors font-bold text-xs shadow-sm">
            <x-icon name="arrow-left" weight="bold" class="text-sm mr-1.5" />
            Kembali
        </a>
    </nav>

    {{-- Profil Header (Distinctive Banner) --}}
    <div class="relative rounded-2xl shadow-sm overflow-hidden mb-8 border border-slate-100">
        {{-- Background Pattern/Gradient --}}
        <div class="absolute inset-0 bg-gradient-to-br from-teal-600 to-emerald-800 opacity-100">
            {{-- Watermark Icon --}}
            <x-icon name="heartbeat" weight="fill" class="absolute -right-8 -bottom-12 text-[256px] text-white opacity-5 transform -rotate-12" />
        </div>
        
        <div class="relative p-6 sm:p-8 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="flex items-start sm:items-center gap-5 text-white">
                <div class="w-20 h-20 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center shrink-0 shadow-inner">
                    <x-icon name="buildings" weight="fill" class="text-[48px] text-white leading-none" />
                </div>
                <div>
                    <h2 class="text-2xl sm:text-3xl font-extrabold tracking-tight mb-2">{{ $puskesmas->nama }}</h2>
                    <div class="flex flex-wrap items-center gap-3">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-white/20 text-white font-mono backdrop-blur-sm border border-white/10">
                            {{ $puskesmas->kode_faskes }}
                        </span>
                        @php
                            $lokasi = $puskesmas->kecamatan ? $puskesmas->kecamatan.($puskesmas->kabupaten_kota ? ', '.$puskesmas->kabupaten_kota : '') : ($puskesmas->kabupaten_kota ?? null);
                        @endphp
                        @if($lokasi)
                            <span class="text-sm font-medium text-teal-50 opacity-90 flex items-center">
                                <x-icon name="map-pin" weight="fill" class="text-base mr-1.5 opacity-75" />
                                {{ $lokasi }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Tombol Edit langsung mengarah ke modal edit puskesmas ini --}}
            <a href="{{ route('super-admin.puskesmas.index', ['edit' => $puskesmas->id]) }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-white text-teal-700 hover:bg-teal-50 transition-colors font-bold text-sm shadow-sm ring-1 ring-inset ring-white/20 active:scale-95">
                <x-icon name="pencil-simple" weight="bold" class="text-base mr-2" />
                Edit Data
            </a>
        </div>
        
        {{-- Info Bar Bawah --}}
        <div class="relative bg-white p-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 flex items-center">
                    <x-icon name="user-circle" weight="fill" class="text-sm mr-1.5 text-slate-400" />
                    Kepala Puskesmas
                </p>
                <p class="text-sm font-semibold text-slate-800">{{ $puskesmas->kepala_puskesmas ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 flex items-center">
                    <x-icon name="envelope" weight="fill" class="text-sm mr-1.5 text-slate-400" />
                    Email Login
                </p>
                <p class="text-sm font-semibold text-slate-800">{{ optional($puskesmas->user)->email ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 flex items-center">
                    <x-icon name="phone" weight="fill" class="text-sm mr-1.5 text-slate-400" />
                    No. Telepon
                </p>
                <p class="text-sm font-semibold text-slate-800">{{ $puskesmas->no_telp ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1 flex items-center">
                    <x-icon name="map-trifold" weight="fill" class="text-sm mr-1.5 text-slate-400" />
                    Alamat Lengkap
                </p>
                <p class="text-sm font-semibold text-slate-800 line-clamp-2" title="{{ $puskesmas->alamat }}">{{ $puskesmas->alamat ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Mini Dashboard (KPIs) dipindah ke bawah Profil --}}
    <h2 class="text-lg font-extrabold text-slate-900 mb-4">Ringkasan Cakupan</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                <x-icon name="buildings" weight="fill" class="text-[28px] leading-none" />
            </div>
            <div>
                <p class="text-sm font-bold text-slate-500">Total Posyandu</p>
                <h3 class="text-2xl font-extrabold text-slate-900">{{ number_format($kpis['total_posyandu']) }}</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <x-icon name="users" weight="fill" class="text-[28px] leading-none" />
            </div>
            <div>
                <p class="text-sm font-bold text-slate-500">Kader Aktif</p>
                <h3 class="text-2xl font-extrabold text-slate-900">{{ number_format($kpis['total_kader']) }}</h3>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4 hover:shadow-md transition-shadow">
            <div class="w-14 h-14 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0">
                <x-icon name="baby" weight="fill" class="text-[28px] leading-none" />
            </div>
            <div>
                <p class="text-sm font-bold text-slate-500">Balita Terdaftar</p>
                <h3 class="text-2xl font-extrabold text-slate-900">{{ number_format($kpis['total_balita']) }}</h3>
            </div>
        </div>
    </div>

    {{-- Tabel Posyandu --}}
    <div class="mb-6">
        <h2 class="text-lg font-extrabold text-slate-900 mb-4">Daftar Posyandu Wilayah Kerja</h2>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Posyandu</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Desa / Kelurahan</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Alamat</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Kader</th>
                            <th class="py-4 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Balita</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($posyandus as $posyandu)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="py-4 px-6">
                                    {{-- Mengubah nama menjadi seolah clickable untuk melatih mental model UX --}}
                                    <div class="font-semibold text-slate-900 group-hover:text-teal-600 transition-colors cursor-pointer flex items-center gap-2">
                                        {{ $posyandu->nama }}
                                        <x-icon name="arrow-up-right" weight="bold" class="text-sm text-slate-300 group-hover:text-teal-500 opacity-0 group-hover:opacity-100 transition-all" />
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-sm font-medium text-slate-700">{{ $posyandu->desa_kelurahan ?? '-' }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    {{-- Kontras dan ukuran font alamat diperbaiki dari 11px text-slate-500 ke text-sm text-slate-600 --}}
                                    <div class="text-sm text-slate-600 truncate max-w-xs" title="{{ $posyandu->alamat }}">{{ $posyandu->alamat ?? '-' }}</div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $posyandu->kaders_count > 0 ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $posyandu->kaders_count }} Kader
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $posyandu->balitas_count > 0 ? 'bg-rose-50 text-rose-700' : 'bg-slate-100 text-slate-500' }}">
                                        {{ $posyandu->balitas_count }} Balita
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-16 text-center">
                                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-50 border border-slate-100 text-slate-400 mb-4 shadow-sm">
                                        <x-icon name="buildings" weight="fill" class="text-4xl" />
                                    </div>
                                    <h3 class="text-lg font-bold text-slate-900 mb-1">Belum ada Posyandu</h3>
                                    <p class="text-sm text-slate-500 max-w-sm mx-auto">Puskesmas ini belum memiliki Posyandu terdaftar di bawahnya. Tambahkan data Posyandu melalui halaman utama Puskesmas.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
