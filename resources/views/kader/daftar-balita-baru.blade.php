@extends('layouts.app')

@section('page-title', isset($isEdit) && $isEdit ? 'Edit Data Anak' : 'Daftar Balita Baru')

@section('content')
<div class="min-h-[100dvh] bg-[#f4f7f6] pb-28 lg:pb-32 selection:bg-teal-100 selection:text-teal-900 font-sans" x-data="editForm()">

    {{-- Main Container --}}
    <div class="max-w-6xl mx-auto w-full px-4 sm:px-6 lg:px-8 pt-4 lg:pt-10">
        
        {{-- Header Area --}}
        <div class="mb-6 lg:mb-10 flex flex-col md:flex-row md:items-end justify-between gap-4">
            <div>
                <a href="{{ !empty($isEdit) ? route('balita.show', $balitaId ?? '') : route('balita.index') }}" 
                   class="inline-flex items-center gap-1.5 lg:gap-2 text-[10px] lg:text-[11px] font-extrabold text-slate-500 hover:text-slate-800 uppercase tracking-widest transition-colors mb-3 lg:mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5 lg:w-4 lg:h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Kembali
                </a>
                <h1 class="text-[24px] lg:text-[32px] font-extrabold text-slate-900 tracking-tight leading-none">
                    {{ isset($isEdit) && $isEdit ? 'Edit Data Anak' : 'Daftar Balita Baru' }}
                </h1>
            </div>
            
            @if(isset($isEdit) && $isEdit)
            <div class="flex items-center justify-center lg:justify-start gap-2 bg-white px-4 py-2.5 rounded-full border border-slate-200 shadow-[0_2px_10px_rgba(0,0,0,0.02)] self-start md:self-auto">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-slate-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="text-[11px] lg:text-[12px] text-slate-500">Terakhir diubah: <span class="font-bold text-slate-800">{{ \Carbon\Carbon::now()->format('d Okt Y, H:i') }}</span></span>
            </div>
            @endif
        </div>

        {{-- Form Wrapper --}}
        <form id="balitaForm" action="{{ isset($isEdit) && $isEdit ? route('balita.update', $balitaId) : route('balita.store') }}" method="POST">
            @csrf
            @if(isset($isEdit) && $isEdit)
                @method('PUT')
            @endif

            <div class="flex flex-col lg:flex-row gap-4 lg:gap-8 items-start relative">
                
                {{-- Left Sidebar --}}
                <div class="w-full lg:w-[260px] shrink-0 lg:sticky lg:top-8 z-10 flex flex-col gap-3 lg:gap-5 self-start">
                    
                    {{-- Profile Summary (Only in Edit) --}}
                    @if(isset($isEdit) && $isEdit)
                    <div class="bg-white rounded-3xl lg:rounded-[32px] p-4 lg:p-6 shadow-sm flex flex-row lg:flex-col items-center gap-4 lg:text-center">
                        <div class="w-12 h-12 lg:w-16 lg:h-16 rounded-full bg-[#dcf2f1] text-[#08a2b5] flex items-center justify-center shrink-0 lg:mb-3">
                            <span class="text-xl lg:text-[26px] font-black select-none">
                                {{ strtoupper(substr($childName, 0, 1)) }}
                            </span>
                        </div>
                        <div class="flex-1 lg:w-full flex flex-col items-start lg:items-center">
                            <div class="flex items-center gap-2 mb-1 lg:mb-2 flex-wrap">
                                <h2 class="text-[15px] lg:text-[18px] font-bold text-slate-800 leading-tight line-clamp-1 lg:line-clamp-none">{{ $childName }}</h2>
                                <div class="flex items-center gap-1 px-2 lg:px-3 py-0.5 lg:py-1 bg-emerald-50 rounded-full">
                                    <div class="w-1 h-1 lg:w-1.5 lg:h-1.5 rounded-full bg-emerald-500 shadow-sm"></div>
                                    <span class="text-[9px] lg:text-[10px] font-bold text-emerald-600 uppercase tracking-wider">Aktif</span>
                                </div>
                            </div>
                            
                            {{-- Mobile Stats --}}
                            <div class="flex lg:hidden items-center gap-2 text-[11px] text-slate-500 font-medium">
                                <span>@php
                                        $dob = \Carbon\Carbon::parse($birthDate);
                                        $now = \Carbon\Carbon::now();
                                        $diff = $dob->diff($now);
                                        $usiaStr = '';
                                        if ($diff->y > 0) $usiaStr .= $diff->y . 'Thn ';
                                        $usiaStr .= $diff->m . 'Bln';
                                        echo $usiaStr;
                                    @endphp</span>
                                <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                                <span class="font-mono">{{ substr($nik, 0, 4) . '...' . substr($nik, -4) }}</span>
                            </div>

                            {{-- Desktop Stats --}}
                            <div class="hidden lg:flex w-full bg-[#f8fafc] rounded-2xl p-4 flex-col gap-3 border border-slate-100">
                                <div class="flex justify-between items-center text-[12px]">
                                    <span class="text-slate-500 font-bold">Usia</span>
                                    <span class="font-extrabold text-slate-800">
                                        @php echo $usiaStr; @endphp
                                    </span>
                                </div>
                                <div class="flex justify-between items-center text-[12px]">
                                    <span class="text-slate-500 font-bold">NIK</span>
                                    <span class="font-extrabold text-slate-800 font-mono tracking-wider">
                                        {{ substr($nik, 0, 4) . '...' . substr($nik, -4) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    {{-- Navigation Menu (Sticky on Mobile too!) --}}
                    <div class="sticky top-0 z-40 bg-[#f4f7f6] pt-1 pb-3 -mx-4 px-4 sm:-mx-6 sm:px-6 lg:mx-0 lg:px-0 lg:static lg:bg-transparent lg:p-0 border-b border-slate-200/50 lg:border-none shadow-[0_4px_10px_-5px_rgba(0,0,0,0.05)] lg:shadow-none mb-2 lg:mb-0 transition-all">
                        <nav x-ref="navContainer" 
                             @mousedown="startDrag" @mouseleave="endDrag" @mouseup="endDrag" @mousemove="drag"
                             :class="isDown ? 'cursor-grabbing' : 'cursor-grab'"
                             class="flex flex-row lg:flex-col gap-2.5 lg:gap-3 overflow-x-auto hide-scrollbar snap-x scroll-smooth pb-1 lg:pb-0">
                            
                            {{-- IDENTITAS --}}
                            <a href="#identitas" @click.prevent="scrollTo('identitas')" 
                               :class="activeSection === 'identitas' ? 'bg-[#08a2b5] text-white shadow-md' : 'bg-white text-slate-600 shadow-sm border border-slate-50 lg:hover:shadow-md'"
                               class="snap-start shrink-0 lg:shrink-1 flex items-center justify-center lg:justify-between px-5 py-2.5 lg:p-4 rounded-full lg:rounded-[24px] transition-all cursor-pointer">
                                <div class="flex items-center gap-2 lg:gap-3.5">
                                    <div :class="activeSection === 'identitas' ? 'bg-white/20 text-white' : 'bg-slate-50 text-slate-400'" class="hidden lg:flex w-11 h-11 rounded-2xl items-center justify-center shrink-0 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" /></svg>
                                    </div>
                                    <svg class="w-4 h-4 lg:hidden" :class="activeSection === 'identitas' ? 'text-white' : 'text-slate-400'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" /></svg>
                                    <div>
                                        <div class="font-bold text-[12px] lg:text-[13px]" :class="activeSection === 'identitas' ? 'text-white' : 'text-slate-800'">Identitas Anak</div>
                                        <div :class="activeSection === 'identitas' ? 'text-cyan-100' : 'text-slate-400'" class="hidden lg:block text-[11px] font-medium mt-0.5">Data dasar</div>
                                    </div>
                                </div>
                                <svg x-show="activeSection === 'identitas'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5 hidden lg:block"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                            </a>
                            
                            {{-- KELAHIRAN --}}
                            <a href="#kelahiran" @click.prevent="scrollTo('kelahiran')"
                               :class="activeSection === 'kelahiran' ? 'bg-[#08a2b5] text-white shadow-md' : 'bg-white text-slate-600 shadow-sm border border-slate-50 lg:hover:shadow-md'"
                               class="snap-start shrink-0 lg:shrink-1 flex items-center justify-center lg:justify-between px-5 py-2.5 lg:p-4 rounded-full lg:rounded-[24px] transition-all cursor-pointer">
                                <div class="flex items-center gap-2 lg:gap-3.5">
                                    <div :class="activeSection === 'kelahiran' ? 'bg-white/20 text-white' : 'bg-slate-50 text-slate-400'" class="hidden lg:flex w-11 h-11 rounded-2xl items-center justify-center shrink-0 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.315 48.315 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" /></svg>
                                    </div>
                                    <svg class="w-4 h-4 lg:hidden" :class="activeSection === 'kelahiran' ? 'text-white' : 'text-slate-400'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.315 48.315 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" /></svg>
                                    <div>
                                        <div class="font-bold text-[12px] lg:text-[13px]" :class="activeSection === 'kelahiran' ? 'text-white' : 'text-slate-800'">Kelahiran</div>
                                        <div :class="activeSection === 'kelahiran' ? 'text-cyan-100' : 'text-slate-400'" class="hidden lg:block text-[11px] font-medium mt-0.5">Antropometri lahir</div>
                                    </div>
                                </div>
                                <svg x-show="activeSection === 'kelahiran'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5 hidden lg:block"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                            </a>

                            {{-- ORANG TUA --}}
                            <a href="#orangtua" @click.prevent="scrollTo('orangtua')"
                               :class="activeSection === 'orangtua' ? 'bg-[#08a2b5] text-white shadow-md' : 'bg-white text-slate-600 shadow-sm border border-slate-50 lg:hover:shadow-md'"
                               class="snap-start shrink-0 lg:shrink-1 flex items-center justify-center lg:justify-between px-5 py-2.5 lg:p-4 rounded-full lg:rounded-[24px] transition-all cursor-pointer">
                                <div class="flex items-center gap-2 lg:gap-3.5">
                                    <div :class="activeSection === 'orangtua' ? 'bg-white/20 text-white' : 'bg-slate-50 text-slate-400'" class="hidden lg:flex w-11 h-11 rounded-2xl items-center justify-center shrink-0 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                                    </div>
                                    <svg class="w-4 h-4 lg:hidden" :class="activeSection === 'orangtua' ? 'text-white' : 'text-slate-400'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                                    <div>
                                        <div class="font-bold text-[12px] lg:text-[13px]" :class="activeSection === 'orangtua' ? 'text-white' : 'text-slate-800'">Orang Tua / Wali</div>
                                        <div :class="activeSection === 'orangtua' ? 'text-cyan-100' : 'text-slate-400'" class="hidden lg:block text-[11px] font-medium mt-0.5">Informasi wali</div>
                                    </div>
                                </div>
                                <svg x-show="activeSection === 'orangtua'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5 hidden lg:block"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                            </a>

                            {{-- LOKASI --}}
                            <a href="#lokasi" @click.prevent="scrollTo('lokasi')"
                               :class="activeSection === 'lokasi' ? 'bg-[#08a2b5] text-white shadow-md' : 'bg-white text-slate-600 shadow-sm border border-slate-50 lg:hover:shadow-md'"
                               class="snap-start shrink-0 lg:shrink-1 flex items-center justify-center lg:justify-between px-5 py-2.5 lg:p-4 rounded-full lg:rounded-[24px] transition-all cursor-pointer">
                                <div class="flex items-center gap-2 lg:gap-3.5">
                                    <div :class="activeSection === 'lokasi' ? 'bg-white/20 text-white' : 'bg-slate-50 text-slate-400'" class="hidden lg:flex w-11 h-11 rounded-2xl items-center justify-center shrink-0 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                    </div>
                                    <svg class="w-4 h-4 lg:hidden" :class="activeSection === 'lokasi' ? 'text-white' : 'text-slate-400'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                    <div>
                                        <div class="font-bold text-[12px] lg:text-[13px]" :class="activeSection === 'lokasi' ? 'text-white' : 'text-slate-800'">Posyandu</div>
                                        <div :class="activeSection === 'lokasi' ? 'text-cyan-100' : 'text-slate-400'" class="hidden lg:block text-[11px] font-medium mt-0.5">Domisili & Posyandu</div>
                                    </div>
                                </div>
                                <svg x-show="activeSection === 'lokasi'" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="3" stroke="currentColor" class="w-3.5 h-3.5 hidden lg:block"><path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" /></svg>
                            </a>
                        </nav>
                    </div>
                </div>
                
                {{-- Right Content Area --}}
                <div class="w-full lg:flex-1 bg-white rounded-[28px] lg:rounded-[40px] shadow-sm mb-16 px-5 py-6 sm:p-10 lg:p-12">
                    
                    {{-- 1. IDENTITAS ANAK --}}
                    <section id="identitas" class="scroll-mt-32 lg:scroll-mt-32 mb-10 lg:mb-16">
                        <div class="flex items-center gap-3 lg:gap-4 mb-6 lg:mb-8">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-[#e6f6f8] text-[#08a2b5] flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 lg:w-6 lg:h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" /></svg>
                            </div>
                            <div>
                                <h2 class="text-[18px] lg:text-[20px] font-extrabold text-slate-900 leading-tight">Identitas Dasar</h2>
                                <p class="text-[12px] lg:text-[13px] text-slate-500 mt-0.5">Perbarui informasi identitas anak.</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6 lg:gap-y-8">
                            
                            {{-- Nama Lengkap --}}
                            <div class="flex flex-col gap-2 group md:col-span-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1">NAMA LENGKAP ANAK</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 lg:pl-5 flex items-center pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 lg:w-5 lg:h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    </div>
                                    <input type="text" name="nama" value="{{ old('nama', $childName ?? '') }}" required placeholder="Contoh: Aisyah Putri"
                                        class="w-full bg-[#f8fafc] border border-slate-100 text-slate-900 rounded-full pl-11 lg:pl-14 pr-4 lg:pr-5 py-3.5 lg:py-4 focus:ring-4 focus:ring-[#08a2b5]/10 focus:border-[#08a2b5] focus:bg-white hover:bg-slate-100/50 transition-all text-[14px] lg:text-[15px] font-medium text-slate-800 outline-none shadow-sm">
                                </div>
                                @error('nama') <p class="text-xs text-rose-500 font-medium ml-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- NIK --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1">NIK ANAK</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 lg:pl-5 flex items-center pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 lg:w-5 lg:h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" /></svg>
                                    </div>
                                    <input type="text" name="nik" value="{{ old('nik', $nik ?? '') }}" required placeholder="16 digit NIK" maxlength="16" inputmode="numeric"
                                        class="w-full bg-[#f8fafc] border border-slate-100 text-slate-900 rounded-full pl-11 lg:pl-14 pr-4 lg:pr-5 py-3.5 lg:py-4 focus:ring-4 focus:ring-[#08a2b5]/10 focus:border-[#08a2b5] focus:bg-white hover:bg-slate-100/50 transition-all text-[14px] lg:text-[15px] font-medium text-slate-800 outline-none shadow-sm">
                                </div>
                                <div class="flex items-center gap-1.5 ml-1 mt-0.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5 text-slate-400"><path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" /></svg>
                                    <p class="text-[10px] lg:text-[11px] text-slate-500 font-medium">Sesuai Kartu Keluarga</p>
                                </div>
                                @error('nik') <p class="text-xs text-rose-500 font-medium ml-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- BPJS --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1 flex justify-between items-center">
                                    NO. BPJS <span class="text-[8px] lg:text-[9px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">OPSIONAL</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 lg:pl-5 flex items-center pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 lg:w-5 lg:h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                    </div>
                                    <input type="text" name="no_bpjs" value="{{ old('no_bpjs', $noBpjs ?? '') }}" placeholder="Nomor BPJS"
                                        class="w-full bg-[#f8fafc] border border-slate-100 text-slate-900 rounded-full pl-11 lg:pl-14 pr-4 lg:pr-5 py-3.5 lg:py-4 focus:ring-4 focus:ring-[#08a2b5]/10 focus:border-[#08a2b5] focus:bg-white hover:bg-slate-100/50 transition-all text-[14px] lg:text-[15px] font-medium text-slate-800 outline-none shadow-sm">
                                </div>
                            </div>

                            {{-- Tanggal Lahir --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1">TANGGAL LAHIR</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 lg:pl-5 flex items-center pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 lg:w-5 lg:h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" /></svg>
                                    </div>
                                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $birthDate ?? '') }}" required
                                        class="w-full bg-[#f8fafc] border border-slate-100 text-slate-900 rounded-full pl-11 lg:pl-14 pr-4 lg:pr-5 py-3.5 lg:py-4 focus:ring-4 focus:ring-[#08a2b5]/10 focus:border-[#08a2b5] focus:bg-white hover:bg-slate-100/50 transition-all text-[14px] lg:text-[15px] font-medium text-slate-800 outline-none appearance-none shadow-sm">
                                </div>
                                @error('tanggal_lahir') <p class="text-xs text-rose-500 font-medium ml-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Jenis Kelamin Segmented Control --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1">JENIS KELAMIN</label>
                                <div class="flex bg-[#f8fafc] p-1.5 rounded-full border border-slate-100 h-[48px] lg:h-[56px]">
                                    <label class="flex-1 relative">
                                        <input type="radio" name="jenis_kelamin" value="L" required class="peer sr-only" {{ old('jenis_kelamin', $gender ?? '') === 'L' ? 'checked' : '' }}>
                                        <div class="absolute inset-0 flex items-center justify-center gap-1.5 lg:gap-2 rounded-full text-[13px] lg:text-[14px] font-bold text-slate-500 peer-checked:bg-white peer-checked:text-[#08a2b5] peer-checked:shadow-[0_2px_8px_rgba(0,0,0,0.06)] cursor-pointer transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                            Laki-laki
                                        </div>
                                    </label>
                                    <label class="flex-1 relative">
                                        <input type="radio" name="jenis_kelamin" value="P" required class="peer sr-only" {{ old('jenis_kelamin', $gender ?? '') === 'P' ? 'checked' : '' }}>
                                        <div class="absolute inset-0 flex items-center justify-center gap-1.5 lg:gap-2 rounded-full text-[13px] lg:text-[14px] font-bold text-slate-500 peer-checked:bg-white peer-checked:text-[#08a2b5] peer-checked:shadow-[0_2px_8px_rgba(0,0,0,0.06)] cursor-pointer transition-all">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                            Perempuan
                                        </div>
                                    </label>
                                </div>
                                @error('jenis_kelamin') <p class="text-xs text-rose-500 font-medium ml-1">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    <div class="w-full border-b-[3px] border-dashed border-slate-200 my-10 lg:my-14"></div>

                    {{-- 2. KELAHIRAN --}}
                    <section id="kelahiran" class="scroll-mt-32 lg:scroll-mt-32 mb-10 lg:mb-16">
                        <div class="flex items-center gap-3 lg:gap-4 mb-6 lg:mb-8">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-[#e6f6f8] text-[#08a2b5] flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 lg:w-6 lg:h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21v-8.25M15.75 21v-8.25M8.25 21v-8.25M3 9l9-6 9 6m-1.5 12V10.332A48.315 48.315 0 0012 9.75c-2.551 0-5.056.2-7.5.582V21M3 21h18M12 6.75h.008v.008H12V6.75z" /></svg>
                            </div>
                            <div>
                                <h2 class="text-[18px] lg:text-[20px] font-extrabold text-slate-900 leading-tight">Kelahiran</h2>
                                <p class="text-[12px] lg:text-[13px] text-slate-500 mt-0.5">Antropometri & data lahir.</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-6 lg:gap-y-8">
                            {{-- Berat Lahir --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1">BERAT LAHIR</label>
                                <div class="relative">
                                    <input type="text" inputmode="decimal" name="berat_lahir" value="{{ old('berat_lahir', $birthWeight ?? '') }}" placeholder="3.20"
                                        class="w-full bg-[#f8fafc] border border-slate-100 text-slate-900 rounded-full px-5 py-3.5 lg:py-4 pr-12 focus:ring-4 focus:ring-[#08a2b5]/10 focus:border-[#08a2b5] focus:bg-white hover:bg-slate-100/50 transition-all text-[14px] lg:text-[15px] font-medium text-slate-800 outline-none text-right shadow-sm">
                                    <div class="absolute inset-y-0 right-0 pr-4 lg:pr-5 flex items-center pointer-events-none text-slate-400 font-bold text-sm">
                                        kg
                                    </div>
                                </div>
                            </div>

                            {{-- Panjang Lahir --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1">PANJANG LAHIR</label>
                                <div class="relative">
                                    <input type="text" inputmode="decimal" name="panjang_lahir" value="{{ old('panjang_lahir', $birthLength ?? '') }}" placeholder="49.5"
                                        class="w-full bg-[#f8fafc] border border-slate-100 text-slate-900 rounded-full px-5 py-3.5 lg:py-4 pr-12 focus:ring-4 focus:ring-[#08a2b5]/10 focus:border-[#08a2b5] focus:bg-white hover:bg-slate-100/50 transition-all text-[14px] lg:text-[15px] font-medium text-slate-800 outline-none text-right shadow-sm">
                                    <div class="absolute inset-y-0 right-0 pr-4 lg:pr-5 flex items-center pointer-events-none text-slate-400 font-bold text-sm">
                                        cm
                                    </div>
                                </div>
                            </div>

                            {{-- Lingkar Kepala --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1">LINGKAR KEPALA</label>
                                <div class="relative">
                                    <input type="text" inputmode="decimal" name="lingkar_kepala_lahir" value="{{ old('lingkar_kepala_lahir', $birthHeadCirc ?? '') }}" placeholder="33.0"
                                        class="w-full bg-[#f8fafc] border border-slate-100 text-slate-900 rounded-full px-5 py-3.5 lg:py-4 pr-12 focus:ring-4 focus:ring-[#08a2b5]/10 focus:border-[#08a2b5] focus:bg-white hover:bg-slate-100/50 transition-all text-[14px] lg:text-[15px] font-medium text-slate-800 outline-none text-right shadow-sm">
                                    <div class="absolute inset-y-0 right-0 pr-4 lg:pr-5 flex items-center pointer-events-none text-slate-400 font-bold text-sm">
                                        cm
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="w-full border-b-[3px] border-dashed border-slate-200 my-10 lg:my-14"></div>

                    {{-- 3. ORANG TUA --}}
                    <section id="orangtua" class="scroll-mt-32 lg:scroll-mt-32 mb-10 lg:mb-16">
                        <div class="flex items-center gap-3 lg:gap-4 mb-6 lg:mb-8">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-[#e6f6f8] text-[#08a2b5] flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 lg:w-6 lg:h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" /></svg>
                            </div>
                            <div>
                                <h2 class="text-[18px] lg:text-[20px] font-extrabold text-slate-900 leading-tight">Orang Tua / Wali</h2>
                                <p class="text-[12px] lg:text-[13px] text-slate-500 mt-0.5">Informasi identitas wali.</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6 lg:gap-y-8">
                            {{-- NO KK --}}
                            <div class="flex flex-col gap-2 md:col-span-2 mb-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1 flex justify-between items-center">
                                    NO. KARTU KELUARGA <span class="text-[8px] lg:text-[9px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">OPSIONAL</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 lg:pl-5 flex items-center pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 lg:w-5 lg:h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" /></svg>
                                    </div>
                                    <input type="text" name="no_kk" value="{{ old('no_kk', $noKk ?? '') }}" placeholder="16 digit Nomor Kartu Keluarga" maxlength="16" inputmode="numeric"
                                        class="w-full bg-[#f8fafc] border border-slate-100 text-slate-900 rounded-full pl-11 lg:pl-14 pr-4 lg:pr-5 py-3.5 lg:py-4 focus:ring-4 focus:ring-[#08a2b5]/10 focus:border-[#08a2b5] focus:bg-white hover:bg-slate-100/50 transition-all text-[14px] lg:text-[15px] font-medium text-slate-800 outline-none shadow-sm">
                                </div>
                            </div>

                            <div class="md:col-span-2">
                                <span class="text-[10px] lg:text-[11px] font-bold text-[#08a2b5] uppercase tracking-widest bg-[#e6f6f8] px-3 py-1.5 rounded-full inline-block">Identitas Ibu</span>
                            </div>

                            {{-- Nama Ibu --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1">NAMA IBU</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 lg:pl-5 flex items-center pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 lg:w-5 lg:h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    </div>
                                    <input type="text" name="nama_ibu" value="{{ old('nama_ibu', $motherName ?? '') }}" required placeholder="Nama lengkap ibu"
                                        class="w-full bg-[#f8fafc] border border-slate-100 text-slate-900 rounded-full pl-11 lg:pl-14 pr-4 lg:pr-5 py-3.5 lg:py-4 focus:ring-4 focus:ring-[#08a2b5]/10 focus:border-[#08a2b5] focus:bg-white hover:bg-slate-100/50 transition-all text-[14px] lg:text-[15px] font-medium text-slate-800 outline-none shadow-sm">
                                </div>
                                @error('nama_ibu') <p class="text-xs text-rose-500 font-medium ml-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- NIK Ibu --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1 flex justify-between items-center">
                                    NIK IBU <span class="text-[8px] lg:text-[9px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">OPSIONAL</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 lg:pl-5 flex items-center pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 lg:w-5 lg:h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" /></svg>
                                    </div>
                                    <input type="text" name="nik_ibu" value="{{ old('nik_ibu', $motherNik ?? '') }}" placeholder="16 digit NIK" maxlength="16" inputmode="numeric"
                                        class="w-full bg-[#f8fafc] border border-slate-100 text-slate-900 rounded-full pl-11 lg:pl-14 pr-4 lg:pr-5 py-3.5 lg:py-4 focus:ring-4 focus:ring-[#08a2b5]/10 focus:border-[#08a2b5] focus:bg-white hover:bg-slate-100/50 transition-all text-[14px] lg:text-[15px] font-medium text-slate-800 outline-none shadow-sm">
                                </div>
                            </div>

                            {{-- No HP --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1">NO WHATSAPP IBU</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 lg:pl-5 flex items-center pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 lg:w-5 lg:h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-2.896-1.596-5.48-4.18-7.076-7.076l1.293-.97c.362-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" /></svg>
                                    </div>
                                    <input type="tel" name="no_hp" value="{{ old('no_hp', $motherPhone ?? '') }}" required placeholder="Contoh: 08123456789" inputmode="tel"
                                        class="w-full bg-[#f8fafc] border border-slate-100 text-slate-900 rounded-full pl-11 lg:pl-14 pr-4 lg:pr-5 py-3.5 lg:py-4 focus:ring-4 focus:ring-[#08a2b5]/10 focus:border-[#08a2b5] focus:bg-white hover:bg-slate-100/50 transition-all text-[14px] lg:text-[15px] font-medium text-slate-800 outline-none shadow-sm">
                                </div>
                                @error('no_hp') <p class="text-xs text-rose-500 font-medium ml-1">{{ $message }}</p> @enderror
                            </div>

                            {{-- Pekerjaan Ibu --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1 flex justify-between items-center">
                                    PEKERJAAN IBU <span class="text-[8px] lg:text-[9px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">OPSIONAL</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 lg:pl-5 flex items-center pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 lg:w-5 lg:h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    </div>
                                    <input type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu', $motherJob ?? '') }}" placeholder="Ibu Rumah Tangga"
                                        class="w-full bg-[#f8fafc] border border-slate-100 text-slate-900 rounded-full pl-11 lg:pl-14 pr-4 lg:pr-5 py-3.5 lg:py-4 focus:ring-4 focus:ring-[#08a2b5]/10 focus:border-[#08a2b5] focus:bg-white hover:bg-slate-100/50 transition-all text-[14px] lg:text-[15px] font-medium text-slate-800 outline-none shadow-sm">
                                </div>
                            </div>

                            <div class="md:col-span-2 border-t border-slate-50 pt-5 lg:pt-6 mt-1 lg:mt-2">
                                <span class="text-[10px] lg:text-[11px] font-bold text-[#08a2b5] uppercase tracking-widest bg-[#e6f6f8] px-3 py-1.5 rounded-full inline-block">Identitas Ayah</span>
                            </div>

                            {{-- Nama Ayah --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1 flex justify-between items-center">
                                    NAMA AYAH <span class="text-[8px] lg:text-[9px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">OPSIONAL</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 lg:pl-5 flex items-center pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 lg:w-5 lg:h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                                    </div>
                                    <input type="text" name="nama_ayah" value="{{ old('nama_ayah', $fatherName ?? '') }}" placeholder="Nama lengkap ayah"
                                        class="w-full bg-[#f8fafc] border border-slate-100 text-slate-900 rounded-full pl-11 lg:pl-14 pr-4 lg:pr-5 py-3.5 lg:py-4 focus:ring-4 focus:ring-[#08a2b5]/10 focus:border-[#08a2b5] focus:bg-white hover:bg-slate-100/50 transition-all text-[14px] lg:text-[15px] font-medium text-slate-800 outline-none shadow-sm">
                                </div>
                            </div>

                            {{-- NIK Ayah --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1 flex justify-between items-center">
                                    NIK AYAH <span class="text-[8px] lg:text-[9px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">OPSIONAL</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 lg:pl-5 flex items-center pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 lg:w-5 lg:h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" /></svg>
                                    </div>
                                    <input type="text" name="nik_ayah" value="{{ old('nik_ayah', $fatherNik ?? '') }}" placeholder="16 digit NIK" maxlength="16" inputmode="numeric"
                                        class="w-full bg-[#f8fafc] border border-slate-100 text-slate-900 rounded-full pl-11 lg:pl-14 pr-4 lg:pr-5 py-3.5 lg:py-4 focus:ring-4 focus:ring-[#08a2b5]/10 focus:border-[#08a2b5] focus:bg-white hover:bg-slate-100/50 transition-all text-[14px] lg:text-[15px] font-medium text-slate-800 outline-none shadow-sm">
                                </div>
                            </div>

                            {{-- Pekerjaan Ayah --}}
                            <div class="flex flex-col gap-2 md:col-span-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1 flex justify-between items-center">
                                    PEKERJAAN AYAH <span class="text-[8px] lg:text-[9px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">OPSIONAL</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 lg:pl-5 flex items-center pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 lg:w-5 lg:h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M20.25 14.15v4.25c0 1.094-.787 2.036-1.872 2.18-2.087.277-4.216.42-6.378.42s-4.291-.143-6.378-.42c-1.085-.144-1.872-1.086-1.872-2.18v-4.25m16.5 0a2.18 2.18 0 00.75-1.661V8.706c0-1.081-.768-2.015-1.837-2.175a48.114 48.114 0 00-3.413-.387m4.5 8.006c-.194.165-.42.295-.673.38A23.978 23.978 0 0112 15.75c-2.648 0-5.195-.429-7.577-1.22a2.016 2.016 0 01-.673-.38m0 0A2.18 2.18 0 013 12.489V8.706c0-1.081.768-2.015 1.837-2.175a48.111 48.111 0 013.413-.387m7.5 0V5.25A2.25 2.25 0 0013.5 3h-3a2.25 2.25 0 00-2.25 2.25v.894m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                                    </div>
                                    <input type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $fatherJob ?? '') }}" placeholder="Wiraswasta"
                                        class="w-full bg-[#f8fafc] border border-slate-100 text-slate-900 rounded-full pl-11 lg:pl-14 pr-4 lg:pr-5 py-3.5 lg:py-4 focus:ring-4 focus:ring-[#08a2b5]/10 focus:border-[#08a2b5] focus:bg-white hover:bg-slate-100/50 transition-all text-[14px] lg:text-[15px] font-medium text-slate-800 outline-none shadow-sm">
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="w-full border-b-[3px] border-dashed border-slate-200 my-10 lg:my-14"></div>

                    {{-- 4. LOKASI --}}
                    <section id="lokasi" class="scroll-mt-32">
                        <div class="flex items-center gap-3 lg:gap-4 mb-6 lg:mb-8">
                            <div class="w-10 h-10 lg:w-12 lg:h-12 rounded-full bg-[#e6f6f8] text-[#08a2b5] flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 lg:w-6 lg:h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                            </div>
                            <div>
                                <h2 class="text-[18px] lg:text-[20px] font-extrabold text-slate-900 leading-tight">Lokasi & Posyandu</h2>
                                <p class="text-[12px] lg:text-[13px] text-slate-500 mt-0.5">Domisili tempat tinggal saat ini.</p>
                            </div>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6 lg:gap-y-8">
                            {{-- Desa / Kelurahan --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1 flex justify-between items-center">
                                    DESA / KELURAHAN <span class="text-[8px] lg:text-[9px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">OPSIONAL</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 lg:pl-5 flex items-center pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 lg:w-5 lg:h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" /></svg>
                                    </div>
                                    <input type="text" name="desa" value="{{ old('desa', $address ?? '') }}" placeholder="Nama desa"
                                        class="w-full bg-[#f8fafc] border border-slate-100 text-slate-900 rounded-full pl-11 lg:pl-14 pr-4 lg:pr-5 py-3.5 lg:py-4 focus:ring-4 focus:ring-[#08a2b5]/10 focus:border-[#08a2b5] focus:bg-white hover:bg-slate-100/50 transition-all text-[14px] lg:text-[15px] font-medium text-slate-800 outline-none shadow-sm">
                                </div>
                            </div>

                            {{-- Kecamatan --}}
                            <div class="flex flex-col gap-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1 flex justify-between items-center">
                                    KECAMATAN <span class="text-[8px] lg:text-[9px] text-slate-400 bg-slate-100 px-2 py-0.5 rounded-full">OPSIONAL</span>
                                </label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 lg:pl-5 flex items-center pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 lg:w-5 lg:h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" /></svg>
                                    </div>
                                    <input type="text" name="kecamatan" value="{{ old('kecamatan', $addressSub ?? '') }}" placeholder="Nama kecamatan"
                                        class="w-full bg-[#f8fafc] border border-slate-100 text-slate-900 rounded-full pl-11 lg:pl-14 pr-4 lg:pr-5 py-3.5 lg:py-4 focus:ring-4 focus:ring-[#08a2b5]/10 focus:border-[#08a2b5] focus:bg-white hover:bg-slate-100/50 transition-all text-[14px] lg:text-[15px] font-medium text-slate-800 outline-none shadow-sm">
                                </div>
                            </div>

                            {{-- Posyandu --}}
                            <div class="flex flex-col gap-2 md:col-span-2">
                                <label class="text-[10px] lg:text-[11px] font-bold text-slate-600 uppercase tracking-widest ml-1">POSYANDU PENDAFTAR</label>
                                <div class="relative opacity-70">
                                    <div class="absolute inset-y-0 left-0 pl-4 lg:pl-5 flex items-center pointer-events-none text-[#08a2b5]">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 lg:w-5 lg:h-5"><path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
                                    </div>
                                    <input type="text" value="{{ $posyanduName ?? 'Posyandu Kader' }}" disabled readonly
                                        class="w-full bg-slate-100/80 border border-slate-200/80 text-slate-600 rounded-full pl-11 lg:pl-14 pr-4 lg:pr-5 py-3.5 lg:py-4 text-[14px] lg:text-[15px] font-bold cursor-not-allowed outline-none shadow-inner">
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            {{-- Fixed Bottom Action Bar --}}
            <div class="fixed bottom-0 inset-x-0 bg-white border-t border-slate-200/60 p-3 lg:p-4 z-50 shadow-[0_-4px_10px_rgba(0,0,0,0.02)]">
                <div class="max-w-6xl mx-auto flex items-center justify-between gap-4 px-2 lg:px-4">
                    <div class="text-[13px] font-bold text-slate-500 hidden md:flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5 text-[#08a2b5]">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        Periksa kembali data sebelum menyimpan.
                    </div>
                    <div class="flex flex-row items-center gap-2.5 lg:gap-3 w-full md:w-auto">
                        <a href="{{ !empty($isEdit) ? route('balita.show', $balitaId ?? '') : route('balita.index') }}" 
                           class="w-[35%] sm:flex-none px-4 lg:px-8 py-3.5 lg:py-4 rounded-full border border-slate-200 text-slate-700 font-bold text-[13px] lg:text-[14px] hover:bg-slate-50 transition-all text-center">
                            Batal
                        </a>
                        <button type="submit" 
                                class="w-[65%] sm:flex-none px-4 lg:px-8 py-3.5 lg:py-4 rounded-full bg-[#08a2b5] text-white font-bold text-[13px] lg:text-[14px] hover:bg-[#078898] transition-all flex items-center justify-center gap-2 text-center shadow-md shadow-cyan-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            Simpan Data
                        </button>
                    </div>
                </div>
            </div>

        </form>
    </div>
</div>

<style>
/* Hide scrollbar for Chrome, Safari and Opera */
.hide-scrollbar::-webkit-scrollbar {
  display: none;
}
/* Hide scrollbar for IE, Edge and Firefox */
.hide-scrollbar {
  -ms-overflow-style: none;  /* IE and Edge */
  scrollbar-width: none;  /* Firefox */
}
</style>

{{-- Script for Scroll Spy & Navigation --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('editForm', () => ({
            activeSection: 'identitas',
            isDown: false,
            startX: 0,
            scrollLeft: 0,
            
            startDrag(e) {
                // Only act on left click
                if (e.button !== 0) return;
                this.isDown = true;
                const nav = this.$refs.navContainer;
                // Temporarily disable smooth scroll while dragging
                nav.classList.remove('scroll-smooth');
                this.startX = e.pageX - nav.offsetLeft;
                this.scrollLeft = nav.scrollLeft;
            },
            endDrag() {
                this.isDown = false;
                const nav = this.$refs.navContainer;
                // Re-enable smooth scroll
                nav.classList.add('scroll-smooth');
            },
            drag(e) {
                if(!this.isDown) return;
                e.preventDefault();
                const nav = this.$refs.navContainer;
                const x = e.pageX - nav.offsetLeft;
                const walk = (x - this.startX) * 1.5; // Drag speed multiplier
                nav.scrollLeft = this.scrollLeft - walk;
            },

            init() {
                // Scrollspy setup for <main> wrapper 
                const mainWrapper = document.querySelector('main');
                const observerOptions = {
                    root: mainWrapper,
                    rootMargin: '-100px 0px -60% 0px',
                    threshold: 0
                };
                
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            this.activeSection = entry.target.id;
                            // Optionally auto-scroll the mobile nav container to keep active item in view
                            const navEl = document.querySelector('nav');
                            if(navEl && window.innerWidth < 1024) {
                                const activeLink = navEl.querySelector(`a[href="#${entry.target.id}"]`);
                                if (activeLink) {
                                    const scrollPos = activeLink.offsetLeft - (navEl.clientWidth / 2) + (activeLink.clientWidth / 2);
                                    navEl.scrollTo({ left: scrollPos, behavior: 'smooth' });
                                }
                            }
                        }
                    });
                }, observerOptions);

                const sections = ['identitas', 'kelahiran', 'orangtua', 'lokasi'];
                sections.forEach(id => {
                    const el = document.getElementById(id);
                    if(el) observer.observe(el);
                });
            },
            
            scrollTo(id) {
                const el = document.getElementById(id);
                if (el) {
                    const mainWrapper = document.querySelector('main');
                    if(mainWrapper) {
                        const yOffset = window.innerWidth < 1024 ? -100 : -40; // larger margin top on mobile because of sticky nav
                        const y = el.getBoundingClientRect().top + mainWrapper.scrollTop - mainWrapper.getBoundingClientRect().top + yOffset;
                        mainWrapper.scrollTo({top: y, behavior: 'smooth'});
                    } else {
                        // Fallback
                        const yOffset = -120; 
                        const y = el.getBoundingClientRect().top + window.pageYOffset + yOffset;
                        window.scrollTo({top: y, behavior: 'smooth'});
                    }
                    this.activeSection = id;
                }
            }
        }));
    });
</script>
@endsection
