@extends('layouts.puskesmas')
@section('page-title', 'Pengaturan')
@section('page-breadcrumbs', 'Pengaturan')
@section('page-mode', 'app')
@section('content')

{{-- Backend Contract:
    Controller: PuskesmasController@pengaturan & updatePengaturan
    Expected Variables: $puskesmas, $user
--}}

<div class="flex flex-col lg:flex-row flex-1 overflow-hidden" x-data="{ 
    editMode: false,
    formData: {
        kepala_puskesmas: '{{ addslashes($puskesmas['kepala_puskesmas']) }}',
        no_telp: '{{ addslashes($puskesmas['no_telp']) }}',
        alamat: '{{ addslashes($puskesmas['alamat']) }}'
    }
}">

    <!-- RIGHT PANEL: Settings Canvas -->
    <div class="flex-1 flex flex-col overflow-y-auto bg-slate-50/50 relative">
        
        <!-- Colorful Vibrant Header -->
        <div class="shrink-0 px-6 sm:px-10 py-10 bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-600 text-white relative overflow-hidden shadow-sm">
            <!-- Decorative elements -->
            <div class="absolute -right-16 -top-16 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-10 bottom-0 opacity-10 pointer-events-none translate-y-1/4">
                <i class="ph-fill ph-buildings text-9xl"></i>
            </div>
            
            <div class="relative z-10 max-w-4xl mx-auto">
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Profil Institusi</h1>
                <p class="text-[13px] text-teal-50 font-medium max-w-xl mt-1.5 leading-relaxed">Informasi resmi puskesmas yang tercatat pada sistem NutriGen. Data ini akan digunakan sebagai rujukan administratif sistem.</p>
                
                <x-puskesmas.settings-tabs active="profil" />
            </div>
        </div>

        <div class="flex-1 p-6 sm:px-10 py-8 relative">
            <div class="max-w-4xl w-full mx-auto relative z-10 -mt-10">
                @if (session('success'))
                    <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl p-4 flex items-center gap-3.5 shadow-sm">
                        <div class="w-10 h-10 rounded-lg bg-white border border-emerald-100 flex items-center justify-center shrink-0 text-emerald-600 shadow-sm">
                            <i class="ph-bold ph-check text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[14px] font-bold text-slate-900">Pembaruan Berhasil</p>
                            <p class="text-[12px] font-medium text-emerald-700 mt-0.5">{{ session('success') }}</p>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('puskesmas.pengaturan.update') }}" class="flex flex-col gap-8">
                    @csrf
                    @method('PUT')

                    <!-- SECTION: Profil Institusi -->
                    <div class="bg-white border rounded-2xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300"
                         :class="editMode ? 'border-teal-400 ring-4 ring-teal-500/10' : 'border-slate-200'">
                        <!-- Header Card -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 p-6 lg:px-8 lg:py-6 border-b border-slate-100 bg-white">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl border flex items-center justify-center shrink-0 shadow-sm transition-colors duration-200"
                                     :class="editMode ? 'bg-teal-600 border-teal-600 text-white' : 'bg-teal-50 border-teal-100 text-teal-600'">
                                    <i class="ph-bold ph-buildings text-xl"></i>
                                </div>
                                <div>
                                    <div class="flex items-center gap-2.5">
                                        <h3 class="text-[16px] font-extrabold text-slate-900">Identitas Dasar</h3>
                                        <span x-show="editMode" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-cloak class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                            Mode Edit Aktif
                                        </span>
                                    </div>
                                    <p class="text-[12px] font-medium text-slate-500 mt-0.5">Detail identitas fasilitas kesehatan.</p>
                                </div>
                            </div>
                            <div class="shrink-0">
                                <div x-show="!editMode" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                                    <button type="button" @click="editMode = true; $nextTick(() => { $refs.firstInput && $refs.firstInput.focus(); })" class="w-full sm:w-auto px-5 py-2.5 text-sm font-semibold text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200 rounded-xl flex items-center justify-center gap-2 transition-all shadow-sm focus:outline-none">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                        Edit Profil
                                    </button>
                                </div>
                                <div x-show="editMode" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="flex flex-col sm:flex-row items-center gap-3" x-cloak>
                                    <button type="button" @click="editMode = false; formData.kepala_puskesmas = '{{ addslashes($puskesmas['kepala_puskesmas']) }}'; formData.no_telp = '{{ addslashes($puskesmas['no_telp']) }}'; formData.alamat = '{{ addslashes($puskesmas['alamat']) }}'" class="w-full sm:w-auto px-5 py-2.5 text-sm font-semibold text-slate-800 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition-all text-center focus:outline-none shadow-sm flex items-center justify-center gap-2">
                                        <i class="ph-bold ph-x"></i>
                                        Batal
                                    </button>
                                    <button type="submit" class="w-full sm:w-auto px-5 py-2.5 text-sm font-bold text-white bg-teal-600 hover:bg-teal-700 rounded-xl shadow-sm shadow-teal-500/30 transition-all text-center focus:outline-none flex items-center justify-center gap-2">
                                        <i class="ph-bold ph-check"></i>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Body Card -->
                        <div class="p-6 lg:p-8">
                            <!-- Edit Mode Alert Banner -->
                            <div x-show="editMode" 
                                 x-transition:enter="transition ease-out duration-300 transform" 
                                 x-transition:enter-start="-translate-y-2 opacity-0" 
                                 x-transition:enter-end="translate-y-0 opacity-100"
                                 x-transition:leave="transition ease-in duration-200 transform"
                                 x-transition:leave-start="translate-y-0 opacity-100"
                                 x-transition:leave-end="-translate-y-2 opacity-0"
                                 class="mb-6 bg-teal-50/90 border border-teal-200 rounded-xl px-4 py-3 flex items-center justify-between gap-3 text-teal-900 shadow-xs" x-cloak>
                                <div class="flex items-center gap-2.5 text-xs sm:text-sm font-medium">
                                    <i class="ph-fill ph-note-pencil text-teal-600 text-lg"></i>
                                    <span>Formulir sekarang dapat diedit. Silakan ubah data yang dibutuhkan lalu klik <strong>Simpan Perubahan</strong>.</span>
                                </div>
                            </div>

                            <div class="flex flex-col lg:flex-row gap-8 lg:gap-12">
                                <!-- Left: Logo -->
                                <div class="w-full lg:w-48 shrink-0 flex flex-col items-center gap-4">
                                    <div class="w-32 h-32 rounded-2xl border-2 border-dashed border-slate-200 flex flex-col items-center justify-center p-4 text-slate-400 bg-slate-50/50 hover:bg-slate-50 transition-colors relative overflow-hidden group cursor-not-allowed">
                                        <div class="absolute inset-0 bg-teal-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                        <i class="ph-fill ph-image text-4xl mb-2 text-slate-300 group-hover:text-teal-300 transition-colors relative z-10"></i>
                                        <span class="text-[10px] font-bold uppercase tracking-widest text-center leading-tight relative z-10">Logo Institusi</span>
                                    </div>
                                    <button type="button" disabled class="px-5 py-2.5 w-32 text-[11px] font-bold text-slate-400 bg-white border border-slate-200 rounded-xl cursor-not-allowed flex items-center justify-center gap-2 opacity-60">
                                        <i class="ph-bold ph-upload-simple"></i>
                                        Unggah
                                    </button>
                                </div>

                                <!-- Right: Data Fields -->
                                <div class="flex-1 flex flex-col gap-6 pt-1">
                                        <!-- Field: Nama Puskesmas (read‑only) -->
                                        <div class="flex flex-col sm:flex-row sm:items-start gap-2 sm:gap-6">
                                            <span class="sm:w-48 shrink-0 text-sm font-semibold uppercase tracking-widest text-slate-600 mt-2.5">Nama Puskesmas</span>
                                            <div class="flex-1 min-w-0">
                                                <div class="w-full px-4 py-3 bg-slate-50 border border-slate-200 shadow-2xs text-base font-medium text-slate-700 rounded-xl flex items-center justify-between">
                                                    <span>{{ $puskesmas['nama'] }}</span>
                                                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider flex items-center gap-1 bg-white px-2.5 py-1 rounded-md border border-slate-200 shadow-2xs">
                                                        <i class="ph-bold ph-lock-key"></i> Terkunci
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Field: Kepala Puskesmas (editable) -->
                                        <div class="flex flex-col sm:flex-row sm:items-start gap-2 sm:gap-6">
                                            <span class="sm:w-48 shrink-0 text-sm font-semibold uppercase tracking-widest text-slate-600 mt-2.5">Kepala Puskesmas</span>
                                            <div class="flex-1 min-w-0">
                                                <div x-show="!editMode" 
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0"
                                                     x-transition:enter-end="opacity-100"
                                                     class="w-full px-4 py-3 bg-slate-50/80 border border-slate-200 shadow-2xs text-base font-medium text-slate-900 rounded-xl">
                                                    {{ $puskesmas['kepala_puskesmas'] ?? '-' }}
                                                </div>
                                                <div x-show="editMode" 
                                                     x-transition:enter="transition ease-out duration-200 transform"
                                                     x-transition:enter-start="opacity-0 -translate-y-1"
                                                     x-transition:enter-end="opacity-100 translate-y-0"
                                                     x-cloak>
                                                    <input type="text" x-ref="firstInput" name="kepala_puskesmas" x-model="formData.kepala_puskesmas" class="w-full px-4 py-3 text-base bg-white border-2 border-teal-500 rounded-xl text-slate-900 focus:outline-none ring-4 ring-teal-500/15 font-medium transition-all shadow-sm" />
                                                    @error('kepala_puskesmas') <p class="text-[11px] font-bold text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Field: No. Telepon (editable) -->
                                        <div class="flex flex-col sm:flex-row sm:items-start gap-2 sm:gap-6">
                                            <span class="sm:w-48 shrink-0 text-sm font-semibold uppercase tracking-widest text-slate-600 mt-2.5">No. Telepon</span>
                                            <div class="flex-1 min-w-0">
                                                <div x-show="!editMode" 
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0"
                                                     x-transition:enter-end="opacity-100"
                                                     class="w-full px-4 py-3 bg-slate-50/80 border border-slate-200 shadow-2xs text-base font-medium text-slate-900 rounded-xl">
                                                    {{ $puskesmas['no_telp'] ?? '-' }}
                                                </div>
                                                <div x-show="editMode" 
                                                     x-transition:enter="transition ease-out duration-200 transform"
                                                     x-transition:enter-start="opacity-0 -translate-y-1"
                                                     x-transition:enter-end="opacity-100 translate-y-0"
                                                     x-cloak>
                                                    <input type="text" name="no_telp" x-model="formData.no_telp" class="w-full px-4 py-3 text-base bg-white border-2 border-teal-500 rounded-xl text-slate-900 focus:outline-none ring-4 ring-teal-500/15 font-medium transition-all shadow-sm" />
                                                    @error('no_telp') <p class="text-[11px] font-bold text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Field: Alamat (editable) -->
                                        <div class="flex flex-col sm:flex-row sm:items-start gap-2 sm:gap-6">
                                            <span class="sm:w-48 shrink-0 text-sm font-semibold uppercase tracking-widest text-slate-600 mt-2.5">Alamat Operasional</span>
                                            <div class="flex-1 min-w-0">
                                                <div x-show="!editMode" 
                                                     x-transition:enter="transition ease-out duration-200"
                                                     x-transition:enter-start="opacity-0"
                                                     x-transition:enter-end="opacity-100"
                                                     class="w-full px-4 py-3 bg-slate-50/80 border border-slate-200 shadow-2xs text-base font-medium text-slate-700 rounded-xl leading-relaxed min-h-[80px]">
                                                    {{ $puskesmas['alamat'] }}
                                                </div>
                                                <div x-show="editMode" 
                                                     x-transition:enter="transition ease-out duration-200 transform"
                                                     x-transition:enter-start="opacity-0 -translate-y-1"
                                                     x-transition:enter-end="opacity-100 translate-y-0"
                                                     x-cloak>
                                                    <textarea name="alamat" x-model="formData.alamat" rows="3" class="w-full px-4 py-3 text-base bg-white border-2 border-teal-500 rounded-xl text-slate-900 focus:outline-none ring-4 ring-teal-500/15 font-medium transition-all shadow-sm resize-none"></textarea>
                                                    @error('alamat') <p class="text-[11px] font-bold text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                                                </div>
                                            </div>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION: Tentang Puskesmas -->
                <div class="mt-4 bg-white border border-slate-200 rounded-2xl p-6 lg:p-8 flex flex-col md:flex-row gap-6 items-start md:items-center justify-between shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <div class="flex items-start gap-5 flex-1">
                        <div class="w-14 h-14 rounded-2xl bg-teal-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-teal-500/30">
                            <i class="ph-bold ph-map-pin-line text-2xl"></i>
                        </div>
                        <div class="pt-1">
                            <h3 class="text-[16px] font-extrabold text-slate-900">Cakupan Wilayah & Kapasitas</h3>
                            <p class="text-[13px] font-medium text-slate-500 mt-1.5 leading-relaxed max-w-md">Unit faskes ini membawahi pengawasan langsung untuk data operasional stunting di seluruh posyandu yang terdaftar.</p>
                        </div>
                    </div>
                    
                    <div class="shrink-0 w-full md:w-auto">
                        <div class="bg-slate-50/80 px-6 py-4 rounded-xl border border-slate-200 flex items-center gap-5">
                            <div class="w-12 h-12 rounded-xl bg-white border border-slate-200 shadow-sm flex items-center justify-center text-slate-400">
                                <i class="ph-fill ph-house text-2xl"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-0.5">Posyandu Aktif</p>
                                <p class="text-[20px] font-extrabold text-slate-900 leading-none">{{ $puskesmas['jumlah_posyandu'] }} <span class="text-[11px] text-slate-500 font-bold uppercase tracking-wider ml-1">Titik</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- INFO BAR -->
                <div class="mt-4 bg-blue-50/50 border border-blue-200 rounded-2xl p-5 flex items-start gap-4">
                    <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center shrink-0 text-blue-600">
                        <i class="ph-bold ph-info text-lg"></i>
                    </div>
                    <p class="text-[13px] font-medium text-blue-800 leading-relaxed pt-1">
                        Pastikan data yang Anda lihat sudah benar. Klik tombol <strong class="font-bold">"Edit Profil"</strong> di atas untuk melakukan pembaruan informasi agar sertifikat laporan Anda presisi.
                    </p>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
