@extends('layouts.app')

@section('page-title', 'Edit Profil Kader')

@section('content')

{{-- Script for Framer Motion --}}
<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        if(window.Motion) {
            const { animate, stagger, hover } = window.Motion;
            
            animate('.motion-card', 
                { opacity: [0, 1], y: [20, 0] }, 
                { delay: stagger(0.08), duration: 0.45, easing: "ease-out" }
            );

            document.querySelectorAll('.motion-hover').forEach(el => {
                hover(el, () => {
                    animate(el, { scale: 1.01, y: -2 }, { duration: 0.2 });
                    return () => animate(el, { scale: 1, y: 0 }, { duration: 0.2 });
                });
            });
        }
    });
</script>

@php
    $kader = Auth::user()?->kader;
    $posyandu = $kader?->posyandu;
    $puskesmas = $posyandu?->puskesmas;
    
    $alamatRaw = $posyandu?->alamat ?? '';
    $alamatData = json_decode($alamatRaw, true);
    if (json_last_error() === JSON_ERROR_NONE && is_array($alamatData)) {
        $desa = $alamatData['desa'] ?? $posyandu?->desa ?? '-';
        $kecamatan = $alamatData['kecamatan'] ?? '-';
    } else {
        $desa = $posyandu?->desa ?? '-';
        $kecamatan = '-';
    }
    $posyanduName = $posyandu?->nama ?? 'Posyandu Kader';
    $puskesmasName = $puskesmas?->nama ?? 'Puskesmas Pembina';
@endphp

<div class="w-full min-h-screen bg-slate-50/50 pb-20 lg:pb-12 selection:bg-teal-100 selection:text-teal-900">
    
    <!-- ── HERO SECTION (Identik dengan Halaman Profil Kader) ── -->
    <div class="relative bg-gradient-to-br from-teal-700 via-teal-600 to-teal-800 pt-8 pb-20 lg:pt-12 lg:pb-24 px-4 sm:px-6 lg:px-8 overflow-hidden lg:rounded-b-[40px] shadow-sm border-b border-teal-900/10">
        <div class="absolute -right-10 -top-10 w-64 h-64 bg-teal-400/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute left-0 bottom-0 w-40 h-40 bg-teal-900/40 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute inset-0 opacity-[0.05] pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
        
        <div class="max-w-6xl mx-auto relative z-10 motion-card opacity-0 flex flex-col sm:flex-row gap-6 sm:gap-8 items-center sm:items-start text-center sm:text-left">
            
            <!-- Avatar Ring Preview -->
            <div class="relative group/avatar shrink-0">
                <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full p-1.5 bg-white/20 backdrop-blur-sm shadow-md flex items-center justify-center transition-transform duration-500 hover:scale-105 relative z-10">
                    <div class="w-full h-full rounded-full overflow-hidden bg-white text-slate-300 relative flex items-center justify-center">
                        @if(isset($avatarUrl))
                            <img src="{{ $avatarUrl }}" alt="Foto Kader" class="w-full h-full object-cover">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-14 h-14 text-slate-300">
                                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                            </svg>
                        @endif
                        <div class="absolute inset-0 bg-slate-900/35 flex items-center justify-center backdrop-blur-[1px]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-7 h-7 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                            </svg>
                        </div>
                    </div>
                </div>
                <!-- Status Indicator -->
                <div class="absolute bottom-1 right-1 sm:bottom-2 sm:right-2 z-20">
                    <div class="bg-emerald-400 w-5 h-5 rounded-full shadow-sm relative border-2 border-teal-700 ring-2 ring-emerald-400/30">
                        <div class="absolute inset-0 rounded-full bg-emerald-400 animate-ping opacity-40"></div>
                    </div>
                </div>
            </div>

            <!-- Profile Info & Back Button -->
            <div class="flex flex-col flex-1 mt-2 sm:mt-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 border border-white/25 text-white text-[12px] font-medium mb-2 tracking-wide backdrop-blur-sm">
                            <span class="w-2 h-2 rounded-full bg-teal-300 animate-pulse"></span>
                            Pengaturan Profil Kader
                        </div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white tracking-tight drop-shadow-sm leading-tight">
                            Edit Data Akun
                        </h1>
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-3">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white text-[13px] font-semibold text-teal-800 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-teal-600"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" /></svg>
                                Kader Posyandu
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white text-[13px] font-semibold text-teal-800 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-teal-600"><path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd" /></svg>
                                {{ $posyanduName }}
                            </span>
                        </div>
                    </div>

                    <!-- Tombol Kembali ke Profil -->
                    <div class="flex-shrink-0 mt-2 sm:mt-0">
                        <a href="{{ route('kader.profil') }}" 
                           class="group inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-teal-800 px-5 py-2.5 rounded-xl text-[14px] font-semibold shadow-md transition-all focus:outline-none focus:ring-4 focus:ring-white/30 active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-teal-600 group-hover:-translate-x-1 transition-transform">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                            </svg>
                            Kembali ke Profil
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ── MAIN WORKSPACE (Overlapping Floating Grid) ── -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 lg:-mt-14 relative z-20 flex flex-col gap-6 lg:gap-8">
        
        @if(session('success'))
            <div class="motion-card opacity-0 p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 shadow-sm">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
                <div class="text-[14px] font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="motion-card opacity-0 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-3 shadow-sm">
                <div class="w-9 h-9 rounded-xl bg-rose-100 flex items-center justify-center flex-shrink-0 text-rose-600 mt-0.5">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                    </svg>
                </div>
                <div class="text-[13px] font-medium">
                    <p class="font-semibold mb-1">Periksa kembali data yang dimasukkan:</p>
                    <ul class="list-disc list-inside space-y-0.5 text-xs text-rose-700">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:gap-8">
            
            <!-- ── KOLOM FORMULIR EDIT (2 Kolom di Desktop) ── -->
            <div class="lg:col-span-2">
                <div class="bg-white p-6 sm:p-8 lg:p-10 rounded-[28px] shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-slate-200/70 motion-card opacity-0 relative overflow-hidden">
                    
                    <div class="flex items-center justify-between pb-6 mb-6 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-600 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-slate-800 tracking-tight">Formulir Informasi Kader</h2>
                                <p class="text-xs font-medium text-slate-500">Perbarui identitas nama dan kontak resmi operasional Anda.</p>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('kader.profil.update') }}" method="POST" class="flex flex-col gap-6">
                        @csrf
                        @method('PUT')

                        <!-- Input Nama Lengkap -->
                        <div class="flex flex-col gap-2">
                            <label for="nama" class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-teal-600">
                                    <path d="M10 8a3 3 0 100-6 3 3 0 000 6zM3.465 14.493a1.23 1.23 0 00.41 1.412A9.957 9.957 0 0010 18c2.31 0 4.438-.784 6.131-2.1.43-.333.604-.903.408-1.41a7.002 7.002 0 00-13.074.003z" />
                                </svg>
                                Nama Lengkap Kader <span class="text-rose-500">*</span>
                            </label>
                            <input type="text" id="nama" name="nama" 
                                   value="{{ old('nama', $name ?? Auth::user()->name) }}" 
                                   required
                                   placeholder="Contoh: Ibu Siti Aminah"
                                   class="w-full bg-slate-50 border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-medium rounded-2xl px-4 py-3.5 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all placeholder:text-slate-400">
                            @error('nama') 
                                <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p> 
                            @enderror
                        </div>

                        <!-- Input Nomor WhatsApp -->
                        <div class="flex flex-col gap-2">
                            <label for="no_hp" class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-emerald-600">
                                    <path fill-rule="evenodd" d="M2 3.5A1.5 1.5 0 013.5 2h1.148a1.5 1.5 0 011.465 1.175l.716 3.223a1.5 1.5 0 01-1.052 1.767l-.933.267c-.41.117-.643.555-.48.95a11.542 11.542 0 006.254 6.254c.395.163.833-.07.95-.48l.267-.933a1.5 1.5 0 011.767-1.052l3.223.716A1.5 1.5 0 0118 15.352V16.5a1.5 1.5 0 01-1.5 1.5H15c-1.149 0-2.263-.15-3.326-.43A13.022 13.022 0 012.43 8.326 13.019 13.019 0 012 5V3.5z" clip-rule="evenodd" />
                                </svg>
                                Nomor Telepon / WhatsApp <span class="text-rose-500">*</span>
                            </label>
                            <input type="tel" id="no_hp" name="no_hp" 
                                   value="{{ old('no_hp', $phone ?? '') }}" 
                                   required
                                   placeholder="Contoh: 081234567890"
                                   class="w-full bg-slate-50 border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-medium rounded-2xl px-4 py-3.5 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all placeholder:text-slate-400">
                            <span class="text-[11.5px] text-slate-400 font-medium">Digunakan untuk notifikasi jadwal posyandu dan koordinasi dengan ibu balita.</span>
                            @error('no_hp') 
                                <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p> 
                            @enderror
                        </div>

                        <!-- Email (Terkunci / Read-Only) -->
                        <div class="flex flex-col gap-2">
                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-slate-400">
                                        <path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" />
                                        <path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" />
                                    </svg>
                                    Alamat Email Akun
                                </label>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 text-slate-500"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" /></svg>
                                    Terkunci
                                </span>
                            </div>
                            <input type="email" value="{{ $email ?? Auth::user()->email }}" readonly disabled
                                   class="w-full bg-slate-100/80 border border-slate-200 text-slate-500 text-[14px] font-medium rounded-2xl px-4 py-3.5 cursor-not-allowed select-none outline-none">
                            <span class="text-[11.5px] text-slate-400 font-medium">Alamat email login dikelola secara resmi oleh admin Puskesmas demi keamanan data.</span>
                        </div>

                        <!-- Action Buttons -->
                        <div class="pt-6 mt-2 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-3">
                            <a href="{{ route('kader.profil') }}" 
                               class="w-full sm:w-auto px-6 py-3.5 rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-[14px] transition-all text-center">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="w-full sm:w-auto px-8 py-3.5 bg-teal-600 hover:bg-teal-500 active:bg-teal-700 text-white rounded-2xl font-semibold text-[14px] shadow-sm shadow-teal-500/25 hover:-translate-y-0.5 transition-all flex items-center justify-center gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                                </svg>
                                <span>Simpan Perubahan</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <!-- ── KOLOM KANAN: Detail Penugasan & Keamanan (Identik dengan Card Profile) ── -->
            <div class="flex flex-col gap-4 lg:gap-6">
                
                <!-- Area Penugasan Card -->
                <div class="flex flex-col bg-white p-6 lg:p-8 rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-slate-200/70 motion-card opacity-0">
                    <h2 class="text-[15px] font-semibold tracking-tight text-slate-800 flex items-center gap-2 px-1 mb-5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-teal-600">
                            <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                        </svg>
                        Area Penugasan Resmi
                    </h2>
                    
                    <div class="flex flex-col gap-4">
                        <!-- Posyandu & Wilayah -->
                        <div class="flex items-center gap-4 bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100">
                            <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-600 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M3 6a3 3 0 013-3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6zm4.5 7.5a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0v-2.25a.75.75 0 01.75-.75zm3.75-1.5a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0V12zm3-3a.75.75 0 01.75.75v6.75a.75.75 0 01-1.5 0V9.75A.75.75 0 0114.25 9z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-[12px] font-medium text-slate-500 mb-0.5">Posyandu Induk</span>
                                <span class="text-[14px] font-semibold text-slate-800 tracking-tight truncate">{{ $posyanduName }}</span>
                            </div>
                        </div>

                        <!-- Puskesmas Pembina -->
                        <div class="flex items-center gap-4 bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100">
                            <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="flex flex-col min-w-0">
                                <span class="text-[12px] font-medium text-slate-500 mb-0.5">Puskesmas Pembina</span>
                                <span class="text-[14px] font-semibold text-slate-800 tracking-tight truncate">{{ $puskesmasName }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Info Box Card -->
                <div class="flex flex-col bg-gradient-to-br from-teal-800 to-teal-950 p-6 lg:p-7 rounded-[24px] text-white shadow-sm motion-card opacity-0 relative overflow-hidden">
                    <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-teal-400/10 rounded-full blur-xl pointer-events-none"></div>
                    
                    <div class="flex items-center gap-2.5 mb-3">
                        <div class="w-8 h-8 rounded-xl bg-white/15 border border-white/20 flex items-center justify-center text-teal-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                        </div>
                        <span class="text-[11px] font-bold uppercase tracking-wider text-teal-200">Bantuan Perubahan Data</span>
                    </div>

                    <p class="text-[12.5px] text-teal-100/90 leading-relaxed font-medium mb-3">
                        Perubahan wilayah posyandu tugas dan alamat email induk hanya dapat dilakukan melalui koordinator Bidan Pembina di Puskesmas.
                    </p>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection
