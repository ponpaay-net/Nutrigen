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
        nama: '{{ addslashes($puskesmas['nama']) }}',
        alamat: '{{ addslashes($puskesmas['alamat']) }}'
    }
}">

    <!-- LEFT PANEL: Settings Navigation -->
    <x-puskesmas.settings-sidebar active="profil" />

    <!-- RIGHT PANEL: Settings Canvas -->
    <div class="flex-1 flex flex-col overflow-y-auto bg-white p-4 lg:p-8 relative">
        <div class="max-w-4xl w-full mx-auto relative z-10">
            @if (session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-lg p-4 flex items-center gap-3.5 shadow-sm">
                    <div class="w-8 h-8 rounded bg-emerald-100 flex items-center justify-center shrink-0 text-emerald-600">
                        <i class="ph-bold ph-check text-lg"></i>
                    </div>
                    <span class="text-[13px] font-bold">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('puskesmas.pengaturan.update') }}" class="flex flex-col gap-6">
                @csrf
                @method('PUT')

                <!-- SECTION: Profil Institusi -->
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                    <!-- Header Card -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 p-6 border-b border-slate-200 bg-slate-50">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded bg-teal-50 border border-teal-200 text-teal-600 flex items-center justify-center shrink-0">
                                <i class="ph-bold ph-buildings text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-[16px] font-bold text-slate-900">Profil Institusi</h3>
                                <p class="text-[12px] font-medium text-slate-500 mt-0.5">Informasi resmi puskesmas yang tercatat pada sistem NutriGen.</p>
                            </div>
                        </div>
                        <div class="shrink-0">
                            <button type="button" x-show="!editMode" @click="editMode = true" class="w-full sm:w-auto px-4 py-2 text-[12px] font-bold text-teal-700 bg-white hover:bg-slate-50 border border-slate-300 rounded-lg flex items-center justify-center gap-2 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500">
                                <i class="ph-bold ph-pencil-simple"></i>
                                Edit Profil
                            </button>
                            <div x-show="editMode" class="flex flex-col sm:flex-row items-center gap-2" x-cloak>
                                <button type="button" @click="editMode = false; formData.nama = '{{ addslashes($puskesmas['nama']) }}'; formData.alamat = '{{ addslashes($puskesmas['alamat']) }}'" class="w-full sm:w-auto px-4 py-2 text-[12px] font-bold text-slate-600 bg-white hover:bg-slate-50 border border-slate-300 rounded-lg transition-colors text-center focus:outline-none focus:ring-2 focus:ring-slate-200">
                                    Batal
                                </button>
                                <button type="submit" class="w-full sm:w-auto px-4 py-2 text-[12px] font-bold text-white bg-teal-600 hover:bg-teal-700 rounded-lg shadow-sm transition-colors text-center focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-1">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Body Card -->
                    <div class="p-6">
                        <div class="flex flex-col lg:flex-row gap-8 lg:gap-10">
                            <!-- Left: Logo -->
                            <div class="w-full lg:w-48 shrink-0 flex flex-col items-center gap-4">
                                <div class="w-32 h-32 rounded border border-dashed border-slate-300 flex flex-col items-center justify-center p-4 text-slate-400 bg-slate-50 relative overflow-hidden group">
                                    <i class="ph-bold ph-image text-3xl mb-2 text-slate-300"></i>
                                    <span class="text-[11px] font-semibold text-center leading-tight">Logo Puskesmas</span>
                                </div>
                                <button type="button" disabled class="px-4 py-2 w-32 text-[11px] font-bold text-slate-500 bg-white border border-slate-300 rounded-lg cursor-not-allowed flex items-center justify-center gap-2 opacity-60">
                                    <i class="ph-bold ph-upload-simple"></i>
                                    Ganti Logo
                                </button>
                            </div>

                            <!-- Right: Data Fields -->
                            <div class="flex-1 flex flex-col gap-6">
                                <!-- Field: Nama Puskesmas -->
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <span class="w-48 shrink-0 text-[12px] font-bold uppercase tracking-widest text-slate-500 mb-1 sm:mb-0">Nama Puskesmas</span>
                                    <div class="flex-1">
                                        <p x-show="!editMode" class="text-[13px] font-bold text-slate-900 px-3 py-2 bg-slate-50 border border-slate-200 rounded-md">{{ $puskesmas['nama'] }}</p>
                                        <div x-show="editMode" x-cloak>
                                            <input type="text" name="nama" x-model="formData.nama" class="w-full px-3 py-2 text-[13px] bg-white border border-slate-300 rounded-md text-slate-900 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 font-medium transition-colors shadow-sm">
                                            @error('nama') <p class="text-[11px] font-bold text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Field: Kode Registrasi -->
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <span class="w-48 shrink-0 text-[12px] font-bold uppercase tracking-widest text-slate-500 mb-1 sm:mb-0">Kode Registrasi</span>
                                    <div class="flex-1">
                                        <div class="inline-flex items-center gap-2 bg-slate-100 px-3 py-2 rounded-md border border-slate-200 cursor-not-allowed">
                                            <i class="ph-bold ph-lock-key text-slate-400"></i>
                                            <span class="text-[13px] font-bold text-slate-600">{{ $puskesmas['kode_registrasi'] }}</span>
                                        </div>
                                        <p class="text-[11px] text-slate-500 font-medium mt-1">Kode ini tidak dapat diubah oleh puskesmas.</p>
                                    </div>
                                </div>

                                <!-- Field: Alamat -->
                                <div class="flex flex-col sm:flex-row">
                                    <span class="w-48 shrink-0 text-[12px] font-bold uppercase tracking-widest text-slate-500 mb-1 sm:mb-0 pt-2">Alamat Lengkap</span>
                                    <div class="flex-1">
                                        <p x-show="!editMode" class="text-[13px] font-medium text-slate-700 bg-slate-50 px-3 py-2 rounded-md border border-slate-200 leading-relaxed">{{ $puskesmas['alamat'] }}</p>
                                        <div x-show="editMode" x-cloak>
                                            <textarea name="alamat" x-model="formData.alamat" rows="3" class="w-full px-3 py-2 text-[13px] bg-white border border-slate-300 rounded-md text-slate-900 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 font-medium transition-colors shadow-sm resize-none"></textarea>
                                            @error('alamat') <p class="text-[11px] font-bold text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION: Tentang Puskesmas -->
                <div class="bg-white border border-slate-200 rounded-xl p-6 flex flex-col md:flex-row gap-6 items-start md:items-center justify-between">
                    <div class="flex items-start gap-4 flex-1">
                        <div class="w-12 h-12 rounded bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 border border-teal-200">
                            <i class="ph-bold ph-map-pin-line text-xl"></i>
                        </div>
                        <div>
                            <h3 class="text-[14px] font-bold text-slate-900">Cakupan Wilayah & Kapasitas</h3>
                            <p class="text-[12px] font-medium text-slate-500 mt-1 leading-relaxed max-w-md">Puskesmas ini melayani berbagai kegiatan posyandu di wilayah kerja terkait.</p>
                        </div>
                    </div>
                    
                    <div class="shrink-0 w-full md:w-auto">
                        <div class="bg-slate-50 px-5 py-3 rounded-lg border border-slate-200 flex items-center gap-4">
                            <div class="w-10 h-10 rounded bg-white border border-slate-200 flex items-center justify-center text-slate-400">
                                <i class="ph-bold ph-house text-lg"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-0.5">Total Posyandu Aktif</p>
                                <p class="text-[16px] font-black text-slate-900">{{ $puskesmas['jumlah_posyandu'] }} <span class="text-[11px] text-slate-500 font-bold uppercase tracking-wider">Titik</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- INFO BAR -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 flex items-start gap-3">
                    <i class="ph-bold ph-info text-blue-500 mt-0.5 text-lg"></i>
                    <p class="text-[12px] font-medium text-blue-800 leading-relaxed">
                        Pastikan data yang Anda lihat sudah benar. Klik tombol <strong class="font-bold">"Edit Profil"</strong> di atas untuk melakukan pembaruan informasi.
                    </p>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
