@extends('layouts.app')

@section('page-title', 'Detail Jadwal Posyandu')

@section('content')

<div class="flex flex-col w-full bg-slate-50/50 min-h-screen pb-24 lg:pb-16 selection:bg-teal-100 selection:text-teal-900">

    {{-- ── 1. HEADER SECTION (Sticky & Branded) ── --}}
    <div class="bg-white px-5 pt-5 pb-4 shadow-sm border-b border-slate-100 sticky top-0 z-20">
        <div class="max-w-3xl mx-auto w-full flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('jadwal.index') }}"
                   class="flex flex-shrink-0 items-center justify-center w-11 h-11 -ml-2 text-slate-500 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 border border-slate-200/80 rounded-2xl transition-all focus:outline-none focus:ring-2 focus:ring-teal-500/20"
                   aria-label="Kembali ke Jadwal">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                    </svg>
                </a>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl font-extrabold text-slate-800 tracking-tight truncate">Detail Jadwal</h1>
                    <p class="text-xs text-slate-500 font-medium mt-0.5">{{ $jadwal['hari'] }}, {{ $jadwal['tanggal'] }}</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                @php
                    $badgeClasses = match($jadwal['status_type']) {
                        'today' => 'bg-amber-50 text-amber-700 border-amber-200/80',
                        'upcoming' => 'bg-teal-50 text-teal-700 border-teal-200/80',
                        'past' => 'bg-slate-100 text-slate-600 border-slate-200',
                        default => 'bg-slate-50 text-slate-600 border-slate-200'
                    };
                @endphp
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-extrabold uppercase tracking-wider border {{ $badgeClasses }} shadow-sm">
                    {{ $jadwal['status'] }}
                </span>

                <a href="{{ route('jadwal.edit', $jadwal['id']) }}" 
                   class="inline-flex items-center gap-1 text-[12px] font-bold text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200/60 px-3.5 py-1.5 rounded-xl transition-all">
                    Edit
                </a>
            </div>
        </div>
    </div>

    {{-- ── 2. MAIN DETAIL BODY ── --}}
    <div class="max-w-3xl mx-auto w-full px-4 sm:px-6 py-6 flex flex-col gap-6">

        {{-- Info Utama Jadwal Card --}}
        <div class="bg-white rounded-[28px] p-6 sm:p-8 border border-slate-200/80 shadow-[0_4px_24px_rgba(0,0,0,0.03)] flex flex-col gap-6">

            {{-- Judul Kegiatan --}}
            <div>
                <span class="inline-flex items-center px-2 py-0.5 bg-teal-50 text-teal-700 text-[10px] font-extrabold uppercase tracking-widest rounded-md border border-teal-200/60 mb-2">
                    {{ $jadwal['posyandu_nama'] ?? 'Posyandu' }}
                </span>
                <h2 class="text-2xl font-black text-slate-800 leading-tight">{{ $jadwal['judul'] }}</h2>
            </div>

            <div class="border-t border-slate-100"></div>

            {{-- Detail Waktu & Lokasi Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                
                {{-- Tanggal --}}
                <div class="flex items-center gap-3.5 bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-teal-600 flex-shrink-0 border border-slate-200 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider">Tanggal Kegiatan</p>
                        <p class="text-[14px] font-bold text-slate-800 mt-0.5">{{ $jadwal['hari'] }}, {{ $jadwal['tanggal'] }}</p>
                    </div>
                </div>

                {{-- Waktu --}}
                <div class="flex items-center gap-3.5 bg-slate-50/80 p-4 rounded-2xl border border-slate-100">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-teal-600 flex-shrink-0 border border-slate-200 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider">Rentang Waktu</p>
                        <p class="text-[14px] font-bold text-slate-800 mt-0.5">{{ $jadwal['waktu'] }}</p>
                    </div>
                </div>

                {{-- Lokasi / Tempat --}}
                <div class="flex items-start gap-3.5 bg-slate-50/80 p-4 rounded-2xl border border-slate-100 sm:col-span-2">
                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-teal-600 flex-shrink-0 border border-slate-200 shadow-sm mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider">Tempat Pelaksanaan</p>
                        <p class="text-[14px] font-bold text-slate-800 mt-0.5 leading-snug">{{ $jadwal['lokasi'] }}</p>
                        <p class="text-[12px] text-slate-500 font-medium mt-0.5">{{ $jadwal['desa'] }}</p>
                    </div>
                </div>

            </div>

            {{-- Catatan Khusus --}}
            @if(!empty($jadwal['catatan']))
                <div class="bg-amber-50/70 border border-amber-200/80 rounded-2xl p-4 flex items-start gap-3">
                    <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-[11px] font-extrabold text-amber-800 uppercase tracking-wider">Petunjuk untuk Orang Tua Balita</h4>
                        <p class="text-[13px] text-amber-900/90 font-medium leading-relaxed mt-0.5">{{ $jadwal['catatan'] }}</p>
                    </div>
                </div>
            @endif

            {{-- Action CTA: Buka Daftar Balita / Mulai Ukur --}}
            <div class="pt-2 flex flex-col sm:flex-row items-center gap-3">
                <a href="{{ route('balita.index') }}" 
                   class="w-full sm:flex-1 flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-500 active:bg-teal-700 text-white py-3.5 px-6 rounded-2xl font-bold text-[14px] shadow-sm shadow-teal-500/25 transition-all">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                    Catat Pengukuran Balita
                </a>

                <a href="{{ route('jadwal.index') }}" 
                   class="w-full sm:w-auto px-6 py-3.5 rounded-2xl border border-slate-200 text-slate-700 hover:bg-slate-50 font-bold text-[14px] transition-all text-center">
                    Kembali ke Jadwal
                </a>
            </div>

        </div>

    </div>

</div>

@endsection
