@extends('layouts.app')

@section('page-title', 'Jadwal Posyandu')

@section('content')

{{-- Script for Framer Motion --}}
<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        if(window.Motion) {
            const { animate, stagger, hover } = window.Motion;
            
            animate('.motion-card', 
                { opacity: [0, 1], y: [12, 0] }, 
                { delay: stagger(0.04), duration: 0.3, easing: "ease-out" }
            );

            document.querySelectorAll('.motion-hover').forEach(el => {
                hover(el, () => {
                    animate(el, { scale: 1.015, y: -1.5 }, { duration: 0.15 });
                    return () => animate(el, { scale: 1, y: 0 }, { duration: 0.15 });
                });
            });
        }
    });
</script>

<div class="flex flex-col min-h-screen bg-slate-50/50 pb-28 lg:pb-16 w-full selection:bg-teal-100 selection:text-teal-900">

    {{-- ── HERO CARD (Aligned with Daftar Balita Pro-Max Standards) ── --}}
    <div class="px-4 pt-5 pb-1 lg:px-0 lg:pt-6 lg:pb-0 max-w-7xl lg:mx-auto w-full">
        <div class="bg-gradient-to-br from-teal-700 via-teal-600 to-teal-800 rounded-[24px] shadow-[0_8px_30px_rgb(13,148,136,0.12)] relative overflow-hidden motion-card opacity-0">

            {{-- Decorative dotted background (CSS pattern) --}}
            <div class="absolute inset-0 opacity-10 pointer-events-none" style="background-image: radial-gradient(#ffffff 1.5px, transparent 1.5px); background-size: 20px 20px;"></div>

            <div class="relative z-10 px-5 py-6 lg:px-8 lg:py-8">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                    
                    {{-- Left block info --}}
                    <div class="flex flex-col min-w-0">
                        <div class="flex items-center gap-2 mb-1 sm:mb-2 text-teal-100">
                            <x-icon name="calendar-blank" weight="bold" class="text-[12px] sm:text-sm" />
                            <span class="text-[10px] sm:text-xs font-bold tracking-widest uppercase">
                                Operasional • {{ $posyanduName ?? 'Posyandu Kader' }}
                            </span>
                        </div>
                        <h1 class="text-[20px] sm:text-[24px] lg:text-[28px] font-extrabold text-white leading-tight truncate">
                            Jadwal Posyandu
                        </h1>
                        <p class="text-[12px] sm:text-[13px] text-teal-100/90 font-medium mt-1">Agenda terbit otomatis ke beranda Portal Ibu.</p>
                    </div>

                    {{-- Right block actions --}}
                    <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3 sm:gap-4 w-full lg:w-auto mt-2 lg:mt-0">
                        {{-- Stat pill --}}
                        <div class="flex items-center justify-center sm:justify-start gap-2 bg-white/10 px-5 py-2.5 sm:py-3 rounded-full border border-white/20">
                            <div class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></div>
                            <span class="text-[12px] sm:text-[13px] text-white font-bold">{{ $jadwalMendatang ?? 0 }} Sesi Mendatang</span>
                        </div>

                        {{-- Action Button (Open Form Modal) --}}
                        <button type="button" 
                                onclick="openCreateJadwalModal()"
                                class="flex-shrink-0 flex items-center justify-center gap-2 h-[42px] bg-white hover:bg-teal-50 text-teal-700 w-full sm:w-auto px-6 rounded-full font-bold text-[13px] transition-all duration-200 active:scale-95 shadow-sm group/btn cursor-pointer">
                            <x-icon name="plus" weight="bold" class="text-[14px] group-hover/btn:rotate-90 transition-transform duration-300" />
                            <span>Tambah Jadwal</span>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- ── SUCCESS ALERT (Compact) ── --}}
    @if(session('success'))
    <div class="max-w-7xl mx-auto w-full px-3 sm:px-4 lg:px-0 mt-2.5">
        <div class="bg-emerald-50 border border-emerald-200/80 rounded-xl p-2.5 sm:p-3 flex items-center gap-2 text-emerald-800 text-[11.5px] sm:text-[12px] font-bold shadow-xs">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4 text-emerald-600 shrink-0" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
            </svg>
            <span class="leading-tight">{{ session('success') }}</span>
        </div>
    </div>
    @endif

    {{-- ── SEPARATE SCHEDULES INTO GROUPS ── --}}
    @php
        $upcomingJadwals = [];
        $pastJadwals = [];
        if (!empty($jadwals)) {
            foreach($jadwals as $j) {
                if ($j['status_type'] === 'upcoming' || $j['status_type'] === 'today') {
                    $upcomingJadwals[] = $j;
                } else {
                    $pastJadwals[] = $j;
                }
            }
        }
    @endphp

    {{-- ── COMPACT INFORMATIVE SCHEDULE CARDS ── --}}
    <div class="max-w-7xl mx-auto w-full px-4 lg:px-0 mt-6 sm:mt-8 space-y-8 sm:space-y-10">
        
        @if(!empty($jadwals) && count($jadwals) > 0)
            
            {{-- ── UPCOMING SCHEDULES ── --}}
            @if(count($upcomingJadwals) > 0)
            <div>
                {{-- Section Title --}}
                <div class="flex items-center gap-3 mb-4 sm:mb-5">
                    <div class="w-1 h-6 bg-teal-600 rounded-full"></div>
                    <h2 class="text-[17px] sm:text-lg font-bold text-slate-800 tracking-tight">Jadwal Mendatang</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    @foreach($upcomingJadwals as $j)
                        @php
                            $isToday = $j['status_type'] === 'today';
                            $accentBar = $isToday ? 'bg-amber-400' : 'bg-teal-500';
                            $dateHeaderBg = $isToday ? 'bg-amber-500 text-white' : 'bg-slate-100 text-teal-700';
                            $badgeClasses = $isToday ? 'bg-amber-50 text-amber-700' : 'bg-teal-50 text-teal-700';
                        @endphp

                        <div class="group relative flex flex-col justify-between bg-white border border-slate-200/80 hover:border-teal-300 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-all duration-200 ease-out motion-card opacity-0">
                            {{-- Left Accent Strip --}}
                            <div class="absolute left-0 top-0 bottom-0 w-1 {{ $accentBar }}"></div>

                            {{-- Card Header & Body --}}
                            <div class="p-4 sm:p-5 pl-5 sm:pl-6">
                                <div class="flex items-start gap-3.5">
                                    {{-- Date Badge --}}
                                    <div class="flex flex-col items-center justify-center w-[46px] rounded-xl border border-slate-200/80 bg-white flex-shrink-0 overflow-hidden">
                                        <div class="w-full py-0.5 text-center text-[9px] font-black uppercase tracking-wider {{ $dateHeaderBg }}">
                                            {{ $j['tgl_bulan_singkat'] }}
                                        </div>
                                        <div class="py-1 flex flex-col items-center">
                                            <span class="text-[16px] font-black text-slate-800 leading-none">{{ $j['tgl_nomor'] }}</span>
                                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider mt-0.5">{{ substr($j['hari'], 0, 3) }}</span>
                                        </div>
                                    </div>

                                    {{-- Title + Meta --}}
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-bold text-slate-800 text-[14px] leading-snug group-hover:text-teal-700 transition-colors line-clamp-2">
                                            {{ $j['judul'] }}
                                        </h3>
                                        <div class="flex flex-col gap-1 mt-1.5 text-[11.5px] text-slate-500 font-medium">
                                            <span class="inline-flex items-center gap-1.5 text-slate-600">
                                                <x-icon name="clock" weight="regular" class="text-slate-400 text-[14px]" />
                                                {{ $j['waktu'] }}
                                            </span>
                                            <span class="inline-flex items-start gap-1.5 text-slate-600">
                                                <x-icon name="map-pin" weight="regular" class="text-slate-400 text-[14px] shrink-0 mt-0.5" />
                                                <span class="line-clamp-1">{{ $j['lokasi'] }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Note --}}
                                @if(!empty($j['catatan']))
                                <div class="mt-3.5 px-3 py-2 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 flex items-start gap-2">
                                    <x-icon name="info" weight="regular" class="text-teal-600 text-[14px] mt-0.5 shrink-0" />
                                    <p class="text-[11px] font-medium leading-relaxed line-clamp-2">
                                        {{ $j['catatan'] }}
                                    </p>
                                </div>
                                @endif
                            </div>

                            {{-- Divider --}}
                            <div class="w-full h-px bg-slate-100"></div>

                            {{-- Bottom Actions --}}
                            <div class="p-3 pl-5 sm:pl-6 flex items-center justify-between gap-2 bg-white">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $badgeClasses }}">
                                        <div class="w-1.5 h-1.5 rounded-full {{ $isToday ? 'bg-amber-500 animate-ping' : 'bg-teal-500' }}"></div>
                                        {{ $isToday ? 'HARI INI' : 'AKAN DATANG' }}
                                    </span>
                                </div>
                                <div class="flex items-center gap-1.5">
                                    <button onclick="openDetailJadwalModal({{ json_encode($j) }})" class="h-8 px-3 flex items-center justify-center text-[11.5px] font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-lg transition-all">Detail</button>
                                    <button onclick="openEditJadwalModal({{ json_encode($j) }})" class="h-8 px-3 flex items-center justify-center text-[11.5px] font-bold text-teal-700 bg-white border border-slate-200 hover:bg-teal-50 hover:border-teal-200 rounded-lg transition-all">Edit</button>
                                    <form action="{{ route('jadwal.destroy', $j['id']) }}" method="POST" onsubmit="if(window.NutriAlert && typeof window.NutriAlert.confirm === 'function'){ event.preventDefault(); const form = this; window.NutriAlert.confirm('Hapus Jadwal?', 'Jadwal ini tidak akan tampil lagi di Portal Ibu.', 'Hapus', 'Batal').then((r) => { if(r.isConfirmed) form.submit(); }); return false; } return confirm('Hapus Jadwal Posyandu ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="h-8 w-8 flex items-center justify-center text-[11.5px] font-bold text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 hover:border-rose-200 rounded-lg transition-all"><x-icon name="trash" weight="bold" class="text-[14px]" /></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- ── PAST SCHEDULES ── --}}
            @if(count($pastJadwals) > 0)
            <div>
                {{-- Section Title --}}
                <div class="flex items-center gap-3 mb-4 sm:mb-5">
                    <div class="w-1 h-6 bg-slate-300 rounded-full"></div>
                    <h2 class="text-[17px] sm:text-lg font-bold text-slate-800 tracking-tight">Jadwal Selesai</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    @foreach($pastJadwals as $j)
                        <div class="group relative flex flex-col justify-between bg-white border border-slate-200/80 rounded-2xl overflow-hidden shadow-sm motion-card opacity-0">
                            {{-- Card Header & Body --}}
                            <div class="p-4 sm:p-5 pl-5 sm:pl-6">
                                <div class="flex items-start gap-3.5">
                                    {{-- Date Badge --}}
                                    <div class="flex flex-col items-center justify-center w-[46px] bg-slate-50 rounded-xl overflow-hidden shrink-0">
                                        <div class="py-1 flex flex-col items-center opacity-60">
                                            <span class="text-[9px] font-black uppercase tracking-wider text-slate-500">{{ $j['tgl_bulan_singkat'] }}</span>
                                            <span class="text-[16px] font-black text-slate-800 leading-none my-0.5">{{ $j['tgl_nomor'] }}</span>
                                            <span class="text-[8px] font-bold text-slate-400 uppercase tracking-wider">{{ substr($j['hari'], 0, 3) }}</span>
                                        </div>
                                    </div>

                                    {{-- Title + Meta --}}
                                    <div class="flex-1 min-w-0 opacity-70">
                                        <h3 class="font-bold text-slate-700 text-[14px] leading-snug line-clamp-2">
                                            {{ $j['judul'] }}
                                        </h3>
                                        <div class="flex flex-col gap-1 mt-1.5 text-[11.5px] text-slate-500 font-medium">
                                            <span class="inline-flex items-center gap-1.5 text-slate-500">
                                                <x-icon name="clock" weight="regular" class="text-[14px]" />
                                                {{ $j['waktu'] }}
                                            </span>
                                            <span class="inline-flex items-start gap-1.5 text-slate-500">
                                                <x-icon name="map-pin" weight="regular" class="text-[14px] shrink-0 mt-0.5" />
                                                <span class="line-clamp-1">{{ $j['lokasi'] }}</span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                
                                {{-- Fake completion stat for visual reference --}}
                                <div class="mt-3.5 px-3 py-2 rounded-xl bg-slate-50 border border-slate-100 text-slate-600 flex items-start gap-2">
                                    <x-icon name="check-circle" weight="regular" class="text-slate-500 text-[14px] mt-0.5 shrink-0" />
                                    <p class="text-[11px] font-medium leading-relaxed text-slate-500">
                                        Kegiatan selesai dilaksanakan. (Arsip)
                                    </p>
                                </div>
                            </div>

                            {{-- Divider --}}
                            <div class="w-full h-px bg-slate-100"></div>

                            {{-- Bottom Actions --}}
                            <div class="p-3 pl-5 sm:pl-6 flex items-center justify-between gap-2 bg-slate-50/50">
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-200/50 text-slate-500">
                                    <div class="w-1.5 h-1.5 rounded-full bg-slate-400"></div>
                                    SELESAI
                                </span>
                                <div class="flex items-center gap-1.5">
                                    <button onclick="openDetailJadwalModal({{ json_encode($j) }})" class="h-8 px-3 flex items-center justify-center text-[11.5px] font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-lg transition-all">Detail</button>
                                    <a href="{{ route('laporan.index') }}" class="h-8 px-3 flex items-center justify-center text-[11.5px] font-bold text-slate-600 bg-white border border-slate-200 hover:bg-slate-50 rounded-lg transition-all">Laporan</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

        @else
            {{-- Empty State (Compact & Elegant) --}}
            <div class="bg-white rounded-2xl p-6 sm:p-8 text-center border border-slate-200/80 shadow-sm flex flex-col items-center justify-center max-w-sm mx-auto motion-card opacity-0 my-4">
                <div class="w-12 h-12 rounded-2xl bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-600 mb-3 shadow-xs">
                    <x-icon name="calendar-plus" weight="fill" class="text-[24px]" />
                </div>
                <h3 class="text-[15px] font-bold text-slate-800 tracking-tight mb-1">Belum Ada Jadwal Posyandu</h3>
                <p class="text-[11.5px] text-slate-500 font-medium leading-relaxed mb-4 max-w-[260px]">Buat jadwal pertama agar para Ibu menerima pengingat penimbangan di aplikasi.</p>
                <button type="button" 
                        onclick="openCreateJadwalModal()"
                        class="inline-flex items-center gap-1.5 h-[38px] px-4 bg-teal-600 hover:bg-teal-500 active:scale-95 text-white rounded-full font-bold text-[12px] shadow-sm shadow-teal-500/20 transition-all cursor-pointer">
                    <x-icon name="plus" weight="bold" class="text-[14px]" />
                    Buat Jadwal Pertama
                </button>
            </div>
        @endif
        {{-- ── 1. MODAL FORM: EDIT & TAMBAH JADWAL (Clean, Spacious & Responsive) ── --}}
<div id="modal-jadwal-wrapper" 
     class="fixed inset-0 z-[100] hidden opacity-0 transition-opacity duration-200">
    
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs" onclick="closeJadwalModal()"></div>

    {{-- Modal Box Container --}}
    <div class="fixed inset-0 z-10 flex items-end sm:items-center justify-center p-0 sm:p-4 md:p-6 pointer-events-none">
        <div id="modal-jadwal-box" 
             class="bg-white rounded-[24px] shadow-2xl border border-slate-200/60 w-full max-w-xl max-h-[90vh] flex flex-col transform transition-all scale-95 duration-200 overflow-hidden pointer-events-auto"
             onclick="event.stopPropagation()">
            
            {{-- Clean Header --}}
            <div class="px-6 py-5 border-b border-slate-100/50 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                        <x-icon name="pencil-simple" weight="bold" class="text-[18px]" />
                    </div>
                    <div>
                        <h2 id="modal-title" class="text-[16px] font-bold text-slate-800 tracking-tight leading-tight">
                            Edit Jadwal Posyandu
                        </h2>
                    </div>
                </div>

                <button type="button" 
                        onclick="closeJadwalModal()"
                        class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors shrink-0"
                        aria-label="Tutup popup">
                    <x-icon name="x" weight="bold" class="text-[18px]" />
                </button>
            </div>

            {{-- Scrollable Form Body with Clean Spacing --}}
            <form id="form-jadwal-modal" action="" method="POST" class="px-6 pt-2 pb-6 flex-1 min-h-0 overflow-y-auto overscroll-contain space-y-5 text-xs">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="POST">

                {{-- Posyandu Info Strip --}}
                <div class="relative overflow-hidden rounded-[16px] border border-teal-50 p-4 bg-teal-50/20">
                    <div class="flex items-center gap-3 relative z-10">
                        <div class="w-10 h-10 rounded-full bg-teal-100/50 text-teal-700 flex items-center justify-center shrink-0">
                            <x-icon name="hospital" weight="bold" class="text-[18px]" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="text-[9px] text-slate-500 font-bold uppercase tracking-widest block mb-0.5">Posyandu Penyelenggara</span>
                            <span class="text-[13px] font-bold text-slate-800 truncate block">{{ $posyanduName ?? 'Posyandu Kader' }}</span>
                        </div>
                    </div>
                </div>

                {{-- Judul Kegiatan --}}
                <div class="flex flex-col gap-2">
                    <label for="modal-input-judul" class="text-[11px] font-bold text-slate-700 uppercase tracking-widest">
                        Nama / Judul Kegiatan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="modal-input-judul" name="judul" required
                           placeholder="Contoh: Layanan Penimbangan Rutin Balita & Imunisasi"
                           class="w-full h-11 bg-white border border-slate-200 hover:border-slate-300 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 rounded-[12px] px-4 text-[13px] font-semibold text-slate-800 placeholder:text-slate-400 placeholder:font-medium transition-all outline-none shadow-sm">
                </div>

                {{-- Lokasi / Tempat --}}
                <div class="flex flex-col gap-2">
                    <label for="modal-input-lokasi" class="text-[11px] font-bold text-slate-700 uppercase tracking-widest">
                        Tempat / Lokasi Pelaksanaan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" id="modal-input-lokasi" name="lokasi" required
                           placeholder="Contoh: Balai Posyandu RW 01, Jl. Melati"
                           class="w-full h-11 bg-white border border-slate-200 hover:border-slate-300 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 rounded-[12px] px-4 text-[13px] font-semibold text-slate-800 placeholder:text-slate-400 placeholder:font-medium transition-all outline-none shadow-sm">
                </div>

                {{-- Tanggal & Waktu Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Tanggal --}}
                    <div class="flex flex-col gap-2">
                        <label for="modal-input-tanggal" class="text-[11px] font-bold text-slate-700 uppercase tracking-widest">
                            Tanggal Kegiatan <span class="text-rose-500">*</span>
                        </label>
                        <input type="date" id="modal-input-tanggal" name="tanggal" required
                               class="w-full h-11 bg-white border border-slate-200 hover:border-slate-300 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 rounded-[12px] px-4 text-[13px] font-semibold text-slate-800 transition-all outline-none cursor-pointer shadow-sm">
                    </div>

                    {{-- Jam Mulai & Selesai --}}
                    <div class="flex flex-col gap-2">
                        <label class="text-[11px] font-bold text-slate-700 uppercase tracking-widest">
                            Rentang Waktu <span class="text-rose-500">*</span>
                        </label>
                        <div class="flex items-center gap-2">
                            <input type="time" id="modal-input-mulai" name="waktu_mulai" required
                                   class="w-full h-11 bg-white border border-slate-200 hover:border-slate-300 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 rounded-[12px] px-3 text-[13px] font-semibold text-slate-800 transition-all outline-none cursor-pointer shadow-sm"
                                   title="Jam Mulai">
                            <span class="text-slate-400 font-bold">-</span>
                            <input type="time" id="modal-input-selesai" name="waktu_selesai" required
                                   class="w-full h-11 bg-white border border-slate-200 hover:border-slate-300 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 rounded-[12px] px-3 text-[13px] font-semibold text-slate-800 transition-all outline-none cursor-pointer shadow-sm"
                                   title="Jam Selesai">
                        </div>
                    </div>
                </div>

                {{-- Catatan Tambahan --}}
                <div class="flex flex-col gap-2">
                    <label for="modal-input-catatan" class="flex items-center justify-between text-[11px] font-bold text-slate-700 uppercase tracking-widest">
                        <span>Catatan untuk Ibu Balita</span>
                        <span class="text-[9px] font-bold text-slate-400 bg-slate-100 px-2 py-0.5 rounded-md">OPSIONAL</span>
                    </label>
                    <textarea id="modal-input-catatan" name="catatan" rows="3"
                              placeholder="Contoh: Harap membawa Buku KIA dan kartu identitas anak."
                              class="w-full bg-white border border-slate-200 hover:border-slate-300 focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 rounded-[12px] px-4 py-3 text-[13px] font-medium text-slate-800 placeholder:text-slate-400 transition-all outline-none resize-none shadow-sm"></textarea>
                </div>
            </form>

            {{-- Footer --}}
            <div class="px-6 py-5 flex items-center justify-end gap-4 shrink-0">
                <button type="button" 
                        onclick="closeJadwalModal()"
                        class="text-[13px] font-bold text-slate-500 hover:text-slate-800 transition-colors">
                    Batal
                </button>
                <button type="button" 
                        onclick="document.getElementById('form-jadwal-modal').submit()"
                        class="h-10 px-5 rounded-xl bg-[#008998] hover:bg-teal-700 active:scale-[0.98] text-white font-bold text-[13px] shadow-sm transition-all flex items-center justify-center gap-2">
                    <x-icon name="check-circle" weight="bold" class="text-[16px]" />
                    <span id="modal-btn-submit-text">Simpan Perubahan</span>
                </button>
            </div>

        </div>
    </div>
</div>

{{-- ── 2. MODAL DETAIL JADWAL (Spacious & Clean Popup) ── --}}
<div id="modal-detail-wrapper" class="fixed inset-0 z-[100] hidden opacity-0 transition-opacity duration-200">
    {{-- Backdrop --}}
    <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeDetailModal()"></div>
    
    {{-- Modal Box Container --}}
    <div class="fixed inset-0 z-10 flex items-center justify-center p-4 pointer-events-none">
        <div id="modal-detail-box" class="bg-white rounded-[24px] shadow-2xl border border-slate-200/60 w-full max-w-xl max-h-[90vh] flex flex-col transform transition-all scale-95 duration-200 overflow-hidden pointer-events-auto" onclick="event.stopPropagation()">
            
            {{-- Header --}}
            <div class="px-6 py-5 border-b border-slate-100/50 flex items-center justify-between shrink-0">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-8 h-8 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center shrink-0">
                        <x-icon name="calendar-blank" weight="bold" class="text-[18px]" />
                    </div>
                    <span class="text-[16px] font-bold text-slate-800 truncate">Detail Agenda Kegiatan</span>
                    <span id="detail-badge-status" class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest text-emerald-700 bg-emerald-50 border border-emerald-100">
                        AKAN DATANG
                    </span>
                    <span id="detail-countdown" class="inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest text-cyan-700 bg-cyan-50 border border-cyan-100 hidden">
                        2 HARI LAGI
                    </span>
                </div>
                <button type="button" onclick="closeDetailModal()" class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-slate-600 transition-colors shrink-0">
                    <x-icon name="x" weight="bold" class="text-[18px]" />
                </button>
            </div>

            {{-- Body --}}
            <div class="p-6 flex-1 min-h-0 overflow-y-auto overscroll-contain space-y-5">
                
                {{-- NAMA AGENDA Card --}}
                <div class="relative overflow-hidden rounded-[20px] border border-teal-50 p-5">
                    {{-- Watermark Icon --}}
                    <div class="absolute right-[-10px] top-[-10px] opacity-[0.03] text-teal-900 pointer-events-none">
                        <x-icon name="calendar-blank" weight="fill" class="text-[120px]" />
                    </div>
                    
                    <span class="text-[10px] font-bold text-teal-600 uppercase tracking-widest block mb-1.5">Nama Agenda</span>
                    <h3 id="detail-judul" class="text-[18px] font-bold text-slate-800 leading-snug mb-3">
                        Layanan Penimbangan & Imunisasi Balita Agustus 2026
                    </h3>
                    <div class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-600">
                        <x-icon name="check-circle" weight="bold" class="text-[14px]" />
                        <span class="text-[11px] font-semibold">Terjadwal & Terpublikasi ke Beranda Portal Ibu Balita</span>
                    </div>
                </div>

                {{-- Key Facts Grid --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    {{-- Tanggal --}}
                    <div class="p-4 rounded-[16px] border border-slate-100 flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-full bg-cyan-50 text-cyan-600 flex items-center justify-center shrink-0">
                            <x-icon name="calendar-blank" weight="bold" class="text-[18px]" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Hari & Tanggal</span>
                            <span id="detail-tanggal" class="text-[13px] font-bold text-slate-800 block truncate">-</span>
                        </div>
                    </div>

                    {{-- Waktu --}}
                    <div class="p-4 rounded-[16px] border border-slate-100 flex items-center gap-3.5">
                        <div class="w-10 h-10 rounded-full bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                            <x-icon name="clock" weight="bold" class="text-[18px]" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Waktu Operasional</span>
                            <span id="detail-waktu" class="text-[13px] font-bold text-slate-800 block truncate">-</span>
                        </div>
                    </div>
                </div>

                {{-- Lokasi --}}
                <div class="p-4 rounded-[16px] border border-slate-100 flex items-center gap-3.5">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <x-icon name="map-pin" weight="bold" class="text-[18px]" />
                    </div>
                    <div class="flex-1 min-w-0">
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest block mb-0.5">Lokasi Pelaksanaan</span>
                        <span id="detail-lokasi" class="text-[13px] font-bold text-slate-800 block truncate">-</span>
                    </div>
                </div>

                {{-- Layanan & Fasilitas --}}
                <div class="pt-2">
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Layanan & Fasilitas Posyandu</span>
                    <div class="flex flex-wrap gap-2">
                        <span class="px-3 py-1.5 rounded-full border border-slate-100 text-slate-600 text-[11px] font-medium flex items-center gap-1.5">
                            <span class="text-[13px]">⚖️</span> Penimbangan BB
                        </span>
                        <span class="px-3 py-1.5 rounded-full border border-slate-100 text-slate-600 text-[11px] font-medium flex items-center gap-1.5">
                            <span class="text-[13px]">📏</span> Pengukuran TB / PB
                        </span>
                        <span class="px-3 py-1.5 rounded-full border border-slate-100 text-slate-600 text-[11px] font-medium flex items-center gap-1.5">
                            <span class="text-[13px]">💉</span> Imunisasi Rutin
                        </span>
                        <span class="px-3 py-1.5 rounded-full border border-slate-100 text-slate-600 text-[11px] font-medium flex items-center gap-1.5">
                            <span class="text-[13px]">🍎</span> Konseling Gizi & PMT
                        </span>
                    </div>
                </div>

                {{-- Catatan --}}
                <div id="detail-catatan-container" class="mt-2 p-4 rounded-[16px] bg-[#FFFAF0] border border-amber-100/50 hidden">
                    <div class="flex items-center gap-2 mb-1.5">
                        <x-icon name="info" weight="fill" class="text-amber-500 text-[16px]" />
                        <span class="text-[10px] font-bold text-amber-700 uppercase tracking-widest">Catatan Tambahan Kader</span>
                    </div>
                    <p id="detail-catatan" class="text-[12px] text-amber-900/80 font-medium leading-relaxed pl-6"></p>
                </div>

            </div>

            {{-- Footer --}}
            <div class="px-6 py-5 flex items-center justify-end gap-4 shrink-0">
                <button type="button" onclick="closeDetailModal()" class="text-[13px] font-bold text-slate-500 hover:text-slate-800 transition-colors">
                    Tutup
                </button>
                <button type="button" id="detail-btn-edit" class="h-10 px-5 rounded-xl bg-[#008998] hover:bg-teal-700 active:scale-[0.98] text-white font-bold text-[13px] shadow-sm transition-all flex items-center justify-center gap-2">
                    <x-icon name="pencil-simple" weight="bold" class="text-[16px]" />
                    <span>Edit Jadwal</span>
                </button>
            </div>

        </div>
    </div>
</div>

{{-- JavaScript Modal Controllers --}}
<script>
    // 1. Form Modal Elements (Edit & Create)
    const modalWrapper = document.getElementById('modal-jadwal-wrapper');
    const modalBox = document.getElementById('modal-jadwal-box');
    const formModal = document.getElementById('form-jadwal-modal');
    const formMethod = document.getElementById('form-method');
    const modalTitle = document.getElementById('modal-title');
    const submitBtnText = document.getElementById('modal-btn-submit-text');

    const inputJudul = document.getElementById('modal-input-judul');
    const inputLokasi = document.getElementById('modal-input-lokasi');
    const inputTanggal = document.getElementById('modal-input-tanggal');
    const inputMulai = document.getElementById('modal-input-mulai');
    const inputSelesai = document.getElementById('modal-input-selesai');
    const inputCatatan = document.getElementById('modal-input-catatan');

    // 2. Detail Modal Elements
    const detailWrapper = document.getElementById('modal-detail-wrapper');
    const detailBox = document.getElementById('modal-detail-box');
    const detailJudul = document.getElementById('detail-judul');
    const detailTanggal = document.getElementById('detail-tanggal');
    const detailWaktu = document.getElementById('detail-waktu');
    const detailLokasi = document.getElementById('detail-lokasi');
    const detailStatusBadge = document.getElementById('detail-badge-status');
    const detailCountdown = document.getElementById('detail-countdown');
    const detailCatatanContainer = document.getElementById('detail-catatan-container');
    const detailCatatan = document.getElementById('detail-catatan');
    const detailBtnEdit = document.getElementById('detail-btn-edit');

    // Open Edit Modal
    function openEditJadwalModal(jadwal) {
        if (!jadwal) return;
        closeDetailModal();

        formModal.action = "/kader/jadwal/" + jadwal.id;
        formMethod.value = "PUT";
        modalTitle.innerText = "Edit Jadwal Posyandu";
        submitBtnText.innerText = "Simpan Perubahan";

        inputJudul.value = jadwal.judul || '';
        inputLokasi.value = jadwal.lokasi || '';
        inputTanggal.value = jadwal.raw_tanggal || '';
        inputMulai.value = jadwal.waktu_mulai || '08:30';
        inputSelesai.value = jadwal.waktu_selesai || '11:30';
        inputCatatan.value = jadwal.catatan || '';

        showFormModal();
    }

    // Open Create Modal
    function openCreateJadwalModal() {
        closeDetailModal();

        formModal.action = "{{ route('jadwal.store') }}";
        formMethod.value = "POST";
        modalTitle.innerText = "Tambah Jadwal Baru";
        submitBtnText.innerText = "Simpan & Terbitkan";

        inputJudul.value = 'Layanan Penimbangan & Imunisasi Balita';
        inputLokasi.value = '';
        inputTanggal.value = new Date().toISOString().split('T')[0];
        inputMulai.value = '08:30';
        inputSelesai.value = '11:30';
        inputCatatan.value = '';

        showFormModal();
    }

    function showFormModal() {
        modalWrapper.classList.remove('hidden');
        setTimeout(() => {
            modalWrapper.classList.remove('opacity-0');
            modalBox.classList.remove('scale-95');
            modalBox.classList.add('scale-100');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeJadwalModal() {
        modalWrapper.classList.add('opacity-0');
        modalBox.classList.remove('scale-100');
        modalBox.classList.add('scale-95');
        setTimeout(() => {
            modalWrapper.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }

    // Open Detail Modal
    function openDetailJadwalModal(jadwal) {
        if (!jadwal) return;

        detailJudul.innerText = jadwal.judul || 'Layanan Posyandu';
        detailTanggal.innerText = (jadwal.hari ? jadwal.hari + ', ' : '') + (jadwal.tanggal || '-');
        detailWaktu.innerText = jadwal.waktu || '-';
        detailLokasi.innerText = jadwal.lokasi || '-';

        // Badge Status
        detailStatusBadge.innerText = jadwal.status || 'Akan Datang';
        if (jadwal.status_type === 'today') {
            detailStatusBadge.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest text-amber-700 bg-amber-50 border border-amber-100';
        } else if (jadwal.status_type === 'upcoming') {
            detailStatusBadge.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest text-emerald-700 bg-emerald-50 border border-emerald-100';
        } else {
            detailStatusBadge.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest text-slate-500 bg-slate-50 border border-slate-200';
        }

        // Countdown (Only show if upcoming and not identical to status)
        if (jadwal.countdown && jadwal.countdown !== 'Selesai' && jadwal.status_type === 'upcoming' && jadwal.countdown.toLowerCase() !== (jadwal.status || '').toLowerCase()) {
            detailCountdown.innerText = jadwal.countdown;
            detailCountdown.className = 'inline-flex items-center px-2.5 py-1 rounded-full text-[9px] font-bold uppercase tracking-widest text-cyan-700 bg-cyan-50 border border-cyan-100';
        } else {
            detailCountdown.className = 'hidden';
        }

        // Catatan
        if (jadwal.catatan && jadwal.catatan.trim() !== '') {
            detailCatatan.innerText = jadwal.catatan;
            detailCatatanContainer.classList.remove('hidden');
        } else {
            detailCatatanContainer.classList.add('hidden');
        }

        // Wire edit button to open edit modal for this item
        detailBtnEdit.onclick = function() {
            openEditJadwalModal(jadwal);
        };

        showDetailModal();
    }

    function showDetailModal() {
        detailWrapper.classList.remove('hidden');
        setTimeout(() => {
            detailWrapper.classList.remove('opacity-0');
            detailBox.classList.remove('scale-95');
            detailBox.classList.add('scale-100');
        }, 10);
        document.body.style.overflow = 'hidden';
    }

    function closeDetailModal() {
        detailWrapper.classList.add('opacity-0');
        detailBox.classList.remove('scale-100');
        detailBox.classList.add('scale-95');
        setTimeout(() => {
            detailWrapper.classList.add('hidden');
            document.body.style.overflow = '';
        }, 200);
    }

    // Global Keydown Handler
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (!modalWrapper.classList.contains('hidden')) closeJadwalModal();
            if (!detailWrapper.classList.contains('hidden')) closeDetailModal();
        }
    });
</script>

@endsection
