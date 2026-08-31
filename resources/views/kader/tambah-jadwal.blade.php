@extends('layouts.app')

@section('page-title', isset($isEdit) && $isEdit ? 'Edit Jadwal Posyandu' : 'Tambah Jadwal Baru')

@section('content')

{{-- Script for Framer Motion --}}
<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        if(window.Motion) {
            const { animate, stagger, hover } = window.Motion;
            animate('.motion-card', 
                { opacity: [0, 1], y: [20, 0] }, 
                { delay: stagger(0.06), duration: 0.4, easing: "ease-out" }
            );
        }
    });
</script>

<div class="flex flex-col bg-slate-50/50 min-h-screen pb-24 lg:pb-16 selection:bg-teal-100 selection:text-teal-900">

    {{-- ── 1. HEADER SECTION (Sticky & Branded) ── --}}
    <div class="bg-white px-5 pt-6 pb-5 shadow-sm border-b border-slate-100 sticky top-0 z-30 relative overflow-hidden">
        <div class="max-w-5xl mx-auto w-full flex items-start gap-4 relative z-10">
            <a href="{{ route('jadwal.index') }}" 
               class="flex flex-shrink-0 items-center justify-center w-11 h-11 -ml-2 mt-0.5 text-slate-500 hover:text-slate-800 bg-slate-50 hover:bg-slate-100 border border-slate-200/80 rounded-2xl transition-all focus:outline-none focus:ring-2 focus:ring-teal-500/20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5" />
                </svg>
            </a>
            <div class="flex flex-col">
                <span class="inline-flex items-center px-2 py-0.5 bg-teal-50 text-teal-700 text-[10px] font-extrabold uppercase tracking-widest rounded-md border border-teal-200/60 w-max mb-1">
                    Operasional Posyandu
                </span>
                <h1 class="text-xl lg:text-2xl font-black text-slate-800 tracking-tight leading-none mb-1">
                    {{ isset($isEdit) && $isEdit ? 'Edit Jadwal Posyandu' : 'Tambah Jadwal Baru' }}
                </h1>
                <p class="text-[12px] font-medium text-slate-500">
                    {{ isset($isEdit) && $isEdit ? 'Perbarui informasi agenda layanan kesehatan Posyandu.' : 'Jadwal yang dibuat akan otomatis tampil di beranda aplikasi para Ibu.' }}
                </p>
            </div>
        </div>
    </div>

    {{-- ── MAIN CONTENT CONTAINER ── --}}
    <div class="max-w-5xl mx-auto w-full px-4 sm:px-6 mt-6">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            
            {{-- ── FORM AREA (2 Columns on Desktop) ── --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-[28px] shadow-[0_4px_24px_rgba(0,0,0,0.04)] border border-slate-200/80 p-6 sm:p-8 relative overflow-hidden motion-card opacity-0">
                    
                    <form action="{{ isset($isEdit) && $isEdit ? route('jadwal.update', $jadwal->id) : route('jadwal.store') }}" 
                          method="POST" 
                          class="flex flex-col gap-6">
                        @csrf
                        @if(isset($isEdit) && $isEdit)
                            @method('PUT')
                        @endif
                        
                        {{-- SECTION 1: Informasi Kegiatan & Lokasi --}}
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-6 h-6 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center font-black text-[11px]">1</div>
                                <h3 class="text-[12px] font-extrabold text-slate-800 uppercase tracking-wider">Informasi Kegiatan & Tempat</h3>
                            </div>

                            <div class="space-y-4">
                                {{-- Posyandu (Readonly / Assigned) --}}
                                <div class="flex flex-col gap-1.5">
                                    <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-teal-600">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008zm0 3h.008v.008h-.008v-.008z" />
                                        </svg>
                                        Posyandu Penyelenggara
                                    </label>
                                    <input type="text" value="{{ $posyanduName ?? 'Posyandu Kader' }}" readonly disabled
                                           class="w-full bg-slate-100/70 border border-slate-200 text-slate-600 text-[14px] font-semibold rounded-2xl px-4 py-3.5 cursor-not-allowed outline-none">
                                </div>

                                {{-- Judul Kegiatan --}}
                                <div class="flex flex-col gap-1.5">
                                    <label for="judul" class="flex items-center gap-1.5 text-xs font-bold text-slate-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-teal-600"><path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25H12" /></svg>
                                        Nama / Judul Kegiatan <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="judul" name="judul" 
                                           value="{{ old('judul', $jadwal->judul ?? 'Layanan Penimbangan & Imunisasi Balita') }}" 
                                           placeholder="Contoh: Penimbangan Rutin Balita & Pemberian Vitamin" 
                                           required
                                           class="w-full bg-slate-50 border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-semibold rounded-2xl px-4 py-3.5 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all placeholder:text-slate-400">
                                    @error('judul') <p class="text-[12px] text-rose-500 font-medium">{{ $message }}</p> @enderror
                                </div>

                                {{-- Lokasi / Tempat --}}
                                <div class="flex flex-col gap-1.5">
                                    <label for="lokasi" class="flex items-center gap-1.5 text-xs font-bold text-slate-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-teal-600">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                        </svg>
                                        Tempat / Alamat Pelaksanaan <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="text" id="lokasi" name="lokasi" 
                                           value="{{ old('lokasi', $jadwal->lokasi ?? '') }}" 
                                           placeholder="Contoh: Balai RW 03, Jl. Mawar No. 12" 
                                           required
                                           class="w-full bg-slate-50 border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-semibold rounded-2xl px-4 py-3.5 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all placeholder:text-slate-400">
                                    @error('lokasi') <p class="text-[12px] text-rose-500 font-medium">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 my-1"></div>

                        {{-- SECTION 2: Waktu Pelaksanaan --}}
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-6 h-6 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center font-black text-[11px]">2</div>
                                <h3 class="text-[12px] font-extrabold text-slate-800 uppercase tracking-wider">Waktu Pelaksanaan</h3>
                            </div>

                            <div class="space-y-4">
                                {{-- Tanggal --}}
                                <div class="flex flex-col gap-1.5">
                                    <label for="tanggal" class="flex items-center gap-1.5 text-xs font-bold text-slate-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-teal-600">
                                          <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                                        </svg>
                                        Tanggal Kegiatan <span class="text-rose-500">*</span>
                                    </label>
                                    <input type="date" id="tanggal" name="tanggal" 
                                           value="{{ old('tanggal', isset($jadwal->tanggal) ? (is_string($jadwal->tanggal) ? $jadwal->tanggal : $jadwal->tanggal->format('Y-m-d')) : now()->addDays(2)->format('Y-m-d')) }}" 
                                           required
                                           class="w-full bg-slate-50 border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-semibold rounded-2xl px-4 py-3.5 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all">
                                    @error('tanggal') <p class="text-[12px] text-rose-500 font-medium">{{ $message }}</p> @enderror
                                </div>

                                {{-- Jam Mulai & Selesai --}}
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="flex flex-col gap-1.5">
                                        <label for="waktu_mulai" class="flex items-center gap-1.5 text-xs font-bold text-slate-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-teal-600"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            Jam Mulai <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="time" id="waktu_mulai" name="waktu_mulai" 
                                               value="{{ old('waktu_mulai', isset($jadwal->waktu_mulai) ? substr($jadwal->waktu_mulai, 0, 5) : '08:30') }}" 
                                               required
                                               class="w-full bg-slate-50 border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-semibold rounded-2xl px-4 py-3.5 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all">
                                        @error('waktu_mulai') <p class="text-[12px] text-rose-500 font-medium">{{ $message }}</p> @enderror
                                    </div>

                                    <div class="flex flex-col gap-1.5">
                                        <label for="waktu_selesai" class="flex items-center gap-1.5 text-xs font-bold text-slate-700">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-teal-600"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            Jam Selesai <span class="text-rose-500">*</span>
                                        </label>
                                        <input type="time" id="waktu_selesai" name="waktu_selesai" 
                                               value="{{ old('waktu_selesai', isset($jadwal->waktu_selesai) ? substr($jadwal->waktu_selesai, 0, 5) : '11:30') }}" 
                                               required
                                               class="w-full bg-slate-50 border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-semibold rounded-2xl px-4 py-3.5 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all">
                                        @error('waktu_selesai') <p class="text-[12px] text-rose-500 font-medium">{{ $message }}</p> @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="border-t border-slate-100 my-1"></div>

                        {{-- SECTION 3: Catatan & Petunjuk --}}
                        <div>
                            <div class="flex items-center gap-2 mb-4">
                                <div class="w-6 h-6 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center font-black text-[11px]">3</div>
                                <h3 class="text-[12px] font-extrabold text-slate-800 uppercase tracking-wider">Petunjuk untuk Para Ibu (Opsional)</h3>
                            </div>

                            <div class="flex flex-col gap-1.5">
                                <label for="catatan" class="text-xs font-bold text-slate-700">Catatan Khusus</label>
                                <textarea id="catatan" name="catatan" rows="3" 
                                          placeholder="Contoh: Harap membawa Buku KIA, kartu BPJS, dan fotokopi KK untuk update KMS balita."
                                          class="w-full bg-slate-50 border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-medium rounded-2xl px-4 py-3 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all placeholder:text-slate-400">{{ old('catatan', $jadwal->catatan ?? '') }}</textarea>
                                @error('catatan') <p class="text-[12px] text-rose-500 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-4 flex items-center justify-end gap-3">
                            <a href="{{ route('jadwal.index') }}" 
                               class="px-5 py-3.5 rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-bold text-[14px] transition-all">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="px-8 py-3.5 bg-teal-600 hover:bg-teal-500 active:bg-teal-700 text-white rounded-2xl font-bold text-[14px] shadow-sm shadow-teal-500/25 hover:-translate-y-0.5 transition-all flex items-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>
                                <span>{{ isset($isEdit) && $isEdit ? 'Simpan Perubahan' : 'Terbitkan Jadwal' }}</span>
                            </button>
                        </div>

                    </form>

                </div>
            </div>

            {{-- ── RIGHT: INFO CARD (Pro-Max Design) ── --}}
            <div class="flex flex-col gap-4">
                
                {{-- Live Connection Card --}}
                <div class="bg-gradient-to-br from-teal-700 to-teal-900 rounded-[28px] p-6 text-white shadow-[0_8px_30px_rgb(13,148,136,0.15)] relative overflow-hidden motion-card opacity-0">
                    <div class="absolute -right-6 -bottom-6 w-32 h-32 bg-teal-400/20 rounded-full blur-2xl pointer-events-none"></div>
                    
                    <div class="w-10 h-10 rounded-2xl bg-white/15 border border-white/20 flex items-center justify-center text-teal-200 mb-4 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904L9 18.75l-.813-2.846a4.5 4.5 0 00-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 003.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 003.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 00-3.09 3.09zM18.259 8.715L18 9.75l-.259-1.035a3.375 3.375 0 00-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 002.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 002.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 00-2.456 2.456zM16.894 20.567L16.5 21.75l-.394-1.183a2.25 2.25 0 00-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 001.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 001.423 1.423l1.183.394-1.183.394a2.25 2.25 0 00-1.423 1.423z" />
                        </svg>
                    </div>

                    <h4 class="text-[16px] font-black tracking-tight mb-2">Terintegrasi Otomatis</h4>
                    <p class="text-[13px] text-teal-100/90 leading-relaxed font-medium">
                        Saat jadwal ini disimpan, aplikasi NutriGen akan langsung menampilkan pengingat di beranda <strong>Portal Ibu</strong> yang terdaftar di posyandu ini.
                    </p>
                </div>

                {{-- Tips Card --}}
                <div class="bg-white rounded-[28px] p-6 border border-slate-200/80 shadow-sm motion-card opacity-0">
                    <h4 class="text-[13px] font-black text-slate-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        Panduan Pengisian
                    </h4>
                    <ul class="space-y-2.5 text-[12.5px] text-slate-600 font-medium">
                        <li class="flex items-start gap-2">
                            <span class="text-teal-600 font-bold">•</span>
                            <span>Pilih tanggal yang tidak bertepatan dengan hari libur nasional.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <span class="text-teal-600 font-bold">•</span>
                            <span>Cantumkan catatan spesifik seperti perlengkapan yang perlu dibawa warga.</span>
                        </li>
                    </ul>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection
