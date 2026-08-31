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
    <div class="flex-1 flex flex-col overflow-y-auto bg-slate-50 p-4 lg:p-8 relative">
        <!-- Background Decorations -->
        <div class="absolute top-0 right-0 w-96 h-96 bg-teal-100/40 rounded-full blur-3xl pointer-events-none -mt-20 -mr-20"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-emerald-50/60 rounded-full blur-3xl pointer-events-none -mb-10 -ml-10"></div>
        
        <div class="max-w-4xl w-full mx-auto relative z-10">
            @if (session('success'))
                <div class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-2xl p-4 flex items-center gap-3.5 shadow-sm">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0 text-emerald-600">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span class="text-sm font-medium">{{ session('success') }}</span>
                </div>
            @endif

            <form method="POST" action="{{ route('puskesmas.pengaturan.update') }}" class="flex flex-col gap-6 lg:gap-8">
                @csrf
                @method('PUT')

                <!-- SECTION: Profil Institusi -->
                <div class="bg-white/80 backdrop-blur-xl border border-slate-200/60 rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden transition-all duration-300 hover:shadow-[0_8px_40px_rgb(0,0,0,0.08)]">
                    <!-- Header Card -->
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-5 p-6 sm:p-8 border-b border-slate-100 bg-white/50">
                        <div class="flex items-center gap-4.5">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-teal-50 to-emerald-50 border border-teal-100/50 text-teal-600 flex items-center justify-center shrink-0 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7">
                                  <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6zm14.25 6a.75.75 0 01-.75.75h-2.25v2.25a.75.75 0 01-1.5 0v-2.25H10.5v2.25a.75.75 0 01-1.5 0v-2.25H6.75a.75.75 0 010-1.5h2.25V6.75a.75.75 0 011.5 0v2.25h2.25v-2.25a.75.75 0 011.5 0v2.25h2.25a.75.75 0 01.75.75z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold tracking-tight text-slate-800">Profil Institusi</h3>
                                <p class="text-sm font-medium text-slate-500 mt-0.5">Informasi resmi puskesmas yang tercatat pada sistem NutriGen.</p>
                            </div>
                        </div>
                        <div class="shrink-0">
                            <button type="button" x-show="!editMode" @click="editMode = true" class="w-full sm:w-auto px-5 py-2.5 text-sm font-semibold text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200/60 rounded-xl flex items-center justify-center gap-2.5 transition-all duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4">
                                  <path d="M2.695 14.763l-1.262 3.152a.5.5 0 00.65.65l3.152-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z" />
                                </svg>
                                Edit Profil
                            </button>
                            <div x-show="editMode" class="flex flex-col sm:flex-row items-center gap-2.5" x-cloak>
                                <button type="button" @click="editMode = false; formData.nama = '{{ addslashes($puskesmas['nama']) }}'; formData.alamat = '{{ addslashes($puskesmas['alamat']) }}'" class="w-full sm:w-auto px-5 py-2.5 text-sm font-semibold text-slate-600 bg-white hover:bg-slate-50 border border-slate-200 rounded-xl transition-all duration-300 text-center">
                                    Batal
                                </button>
                                <button type="submit" class="w-full sm:w-auto px-6 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-teal-500 to-emerald-500 hover:from-teal-600 hover:to-emerald-600 rounded-xl shadow-md shadow-teal-500/20 transition-all duration-300 text-center">
                                    Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Body Card -->
                    <div class="p-6 sm:p-8">
                        <div class="flex flex-col lg:flex-row gap-8 lg:gap-10">
                            <!-- Left: Logo -->
                            <div class="w-full lg:w-56 shrink-0 flex flex-col items-center gap-4">
                                <div class="w-40 h-40 rounded-3xl bg-slate-50 border-2 border-dashed border-slate-200 flex flex-col items-center justify-center p-4 text-slate-400 shadow-sm relative overflow-hidden group">
                                    <div class="absolute inset-0 bg-teal-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 mb-3 text-teal-400">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                                    </svg>
                                    <span class="text-xs font-semibold text-center leading-tight">Unggah Logo</span>
                                </div>
                                <button type="button" disabled class="px-5 py-2.5 w-40 text-[13px] font-semibold text-teal-700 bg-teal-50/50 border border-teal-200/50 rounded-xl cursor-not-allowed flex items-center justify-center gap-2 opacity-50">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" />
                                    </svg>
                                    Ganti Logo
                                </button>
                            </div>

                            <!-- Right: Data Fields -->
                            <div class="flex-1 flex flex-col gap-6">
                                <!-- Field: Nama Puskesmas -->
                                <div class="flex flex-col sm:flex-row sm:items-center py-2">
                                    <span class="w-48 shrink-0 text-[13px] font-semibold uppercase tracking-wider text-slate-500 mb-2 sm:mb-0">Nama Puskesmas</span>
                                    <div class="flex-1">
                                        <p x-show="!editMode" class="text-[14px] font-semibold text-slate-800 bg-slate-50/50 px-4 py-3 rounded-xl border border-transparent">{{ $puskesmas['nama'] }}</p>
                                        <div x-show="editMode" x-cloak>
                                            <input type="text" name="nama" x-model="formData.nama" class="w-full px-4 py-3 text-[14px] bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 font-medium transition-all shadow-sm">
                                            @error('nama') <p class="text-xs font-semibold text-rose-500 mt-1.5 px-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>

                                <!-- Field: Kode Registrasi -->
                                <div class="flex flex-col sm:flex-row sm:items-center py-2">
                                    <span class="w-48 shrink-0 text-[13px] font-semibold uppercase tracking-wider text-slate-500 mb-2 sm:mb-0">Kode Registrasi</span>
                                    <div class="flex-1">
                                        <div class="inline-flex items-center gap-2 bg-slate-100/80 px-4 py-2.5 rounded-xl border border-slate-200 cursor-not-allowed">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-slate-400">
                                              <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                            </svg>
                                            <span class="text-[14px] font-medium text-slate-600">{{ $puskesmas['kode_registrasi'] }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Field: Alamat -->
                                <div class="flex flex-col sm:flex-row py-2">
                                    <span class="w-48 shrink-0 text-[13px] font-semibold uppercase tracking-wider text-slate-500 mb-2 sm:mb-0 pt-3">Alamat Lengkap</span>
                                    <div class="flex-1">
                                        <p x-show="!editMode" class="text-[14px] font-medium text-slate-700 bg-slate-50/50 px-4 py-3 rounded-xl border border-transparent leading-relaxed">{{ $puskesmas['alamat'] }}</p>
                                        <div x-show="editMode" x-cloak>
                                            <textarea name="alamat" x-model="formData.alamat" rows="3" class="w-full px-4 py-3 text-[14px] bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 font-medium transition-all shadow-sm resize-none"></textarea>
                                            @error('alamat') <p class="text-xs font-semibold text-rose-500 mt-1.5 px-1">{{ $message }}</p> @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION: Tentang Puskesmas -->
                <div class="bg-white/80 backdrop-blur-xl border border-slate-200/60 rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6 sm:p-8 flex flex-col md:flex-row gap-8 items-start md:items-center justify-between transition-all duration-300 hover:shadow-[0_8px_40px_rgb(0,0,0,0.08)]">
                    <div class="flex items-start gap-5 flex-1">
                        <div class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 border border-teal-100/50 shadow-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-7 h-7">
                                <path fill-rule="evenodd" d="M4.5 3.75a3 3 0 00-3 3v10.5a3 3 0 003 3h15a3 3 0 003-3V6.75a3 3 0 00-3-3h-15zm4.125 3a2.25 2.25 0 100 4.5 2.25 2.25 0 000-4.5zm-3.873 8.703a4.126 4.126 0 017.746 0 .75.75 0 01-.71.947h-6.326a.75.75 0 01-.71-.947zM15 9a.75.75 0 01.75-.75h2.25a.75.75 0 01.75.75v.5a.75.75 0 01-.75.75h-2.25a.75.75 0 01-.75-.75V9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold tracking-tight text-slate-800">Cakupan Wilayah & Kapasitas</h3>
                            <p class="text-[13px] font-medium text-slate-500 mt-1 leading-relaxed">Puskesmas ini melayani berbagai kegiatan posyandu di wilayah kerja terkait.</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col gap-4 border-l-0 md:border-l-2 border-slate-100 pl-0 md:pl-10 shrink-0 w-full md:w-auto">
                        <div class="bg-slate-50 px-5 py-4 rounded-2xl border border-slate-100 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center shadow-sm text-slate-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                                </svg>
                            </div>
                            <div>
                                <p class="text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-0.5">Total Posyandu Aktif</p>
                                <p class="text-xl font-bold text-slate-800">{{ $puskesmas['jumlah_posyandu'] }} <span class="text-sm text-slate-500 font-medium">Titik Layanan</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- INFO BAR -->
                <div class="bg-gradient-to-r from-teal-50 to-emerald-50 border border-teal-100/60 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
                    <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shrink-0 shadow-sm text-teal-500">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                    </div>
                    <p class="text-[13px] font-medium text-teal-900 leading-relaxed">
                        Pastikan data yang Anda lihat sudah benar. Klik tombol <span class="font-semibold">"Edit Profil"</span> di atas untuk melakukan pembaruan informasi.
                    </p>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
