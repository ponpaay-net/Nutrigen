@extends('layouts.puskesmas')
@section('page-title', 'Pengaturan')
@section('page-breadcrumbs', 'Profil Petugas')
@section('page-mode', 'app')
@section('content')

{{-- Backend Contract:
    Controller: PuskesmasController@petugas & updatePetugas
    Expected Variables: $user, $puskesmas
--}}

<div class="flex flex-col lg:flex-row flex-1 overflow-hidden" x-data="{ 
    editMode: false,
    formData: {
        nama: '{{ addslashes($user['nama']) }}',
        email: '{{ addslashes($user['email']) }}'
    }
}">

    <!-- LEFT PANEL: Settings Navigation -->
    <x-puskesmas.settings-sidebar active="petugas" />

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
            
            @if($errors->any())
                <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 rounded-lg p-4 flex items-start gap-3.5 shadow-sm">
                    <div class="w-8 h-8 rounded bg-rose-100 flex items-center justify-center shrink-0 text-rose-600 mt-0.5">
                        <i class="ph-bold ph-warning-circle text-lg"></i>
                    </div>
                    <div>
                        <p class="text-[13px] font-bold mb-1">Terjadi kesalahan pada input data:</p>
                        <ul class="list-disc ml-4 space-y-1 font-medium text-[12px] text-rose-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('puskesmas.pengaturan.petugas.update') }}" class="flex flex-col gap-6">
                @csrf
                @method('PUT')

                <!-- PROFILE BANNER -->
                <div class="bg-teal-700 rounded-xl p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row items-center sm:items-start gap-6 sm:gap-8">
                    <!-- Avatar Container -->
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded bg-teal-800 p-1.5 shrink-0 group relative overflow-hidden flex items-center justify-center text-teal-300 border border-teal-600">
                        <i class="ph-bold ph-user text-5xl"></i>
                    </div>
                    
                    <!-- Info -->
                    <div class="flex-1 text-center sm:text-left mt-2">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-teal-800/50 border border-teal-600/50 text-teal-50 text-[10px] font-bold tracking-widest uppercase mb-3">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                            Petugas {{ $user['role'] }}
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-tight mb-1" x-text="formData.nama"></h1>
                        <p class="text-teal-100 text-[13px] font-medium flex items-center justify-center sm:justify-start gap-1.5 mt-2">
                            <i class="ph-bold ph-envelope-simple"></i>
                            <span x-text="formData.email"></span>
                        </p>
                    </div>
                    
                    <!-- Action -->
                    <div class="shrink-0 mt-2 sm:mt-0">
                        <button type="button" x-show="!editMode" @click="editMode = true" class="inline-flex items-center justify-center gap-2 bg-white text-teal-700 px-4 py-2 rounded-lg text-[12px] font-bold transition-colors hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-teal-500 shadow-sm">
                            <i class="ph-bold ph-pencil-simple"></i>
                            Edit Data
                        </button>
                    </div>
                </div>

                <!-- SECTION: Informasi Akun (Form) -->
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded bg-teal-50 border border-teal-200 flex items-center justify-center text-teal-600 shrink-0">
                                <i class="ph-bold ph-identification-card text-xl"></i>
                            </div>
                            <div>
                                <h2 class="text-[15px] font-bold text-slate-900 tracking-tight">Data Profil</h2>
                                <p class="text-[12px] font-medium text-slate-500">Perbarui identitas pribadi Anda.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Input: Nama -->
                            <div class="flex flex-col gap-2">
                                <label class="text-[12px] font-bold text-slate-700">Nama Lengkap</label>
                                <div x-show="!editMode" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-[13px] font-bold rounded-md px-3 py-2">{{ $user['nama'] }}</div>
                                <div x-show="editMode" x-cloak>
                                    <input type="text" name="nama" x-model="formData.nama" class="w-full bg-white border border-slate-300 text-slate-900 text-[13px] font-medium rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-colors shadow-sm">
                                    @error('nama') <p class="text-[11px] font-bold text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <!-- Input: Email -->
                            <div class="flex flex-col gap-2">
                                <label class="text-[12px] font-bold text-slate-700">Alamat Email</label>
                                <div x-show="!editMode" class="w-full bg-slate-50 border border-slate-200 text-slate-800 text-[13px] font-bold rounded-md px-3 py-2">{{ $user['email'] }}</div>
                                <div x-show="editMode" x-cloak>
                                    <input type="email" name="email" x-model="formData.email" class="w-full bg-white border border-slate-300 text-slate-900 text-[13px] font-medium rounded-md px-3 py-2 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 transition-colors shadow-sm">
                                    @error('email') <p class="text-[11px] font-bold text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div x-show="editMode" x-cloak class="pt-6 mt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-end gap-2" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                            <button type="button" @click="editMode = false; formData.nama = '{{ addslashes($user['nama']) }}'; formData.email = '{{ addslashes($user['email']) }}'" class="w-full sm:w-auto px-4 py-2 rounded-lg border border-slate-300 text-slate-600 hover:bg-slate-50 font-bold text-[12px] transition-colors text-center focus:outline-none focus:ring-2 focus:ring-slate-200">
                                Batal
                            </button>
                            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white rounded-lg font-bold text-[12px] shadow-sm transition-colors text-center focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-1">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- ROW: Penugasan & Keamanan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- Area Penugasan -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6">
                        <h2 class="text-[14px] font-bold tracking-tight text-slate-900 flex items-center gap-2 mb-6">
                            <i class="ph-bold ph-buildings text-teal-600 text-lg"></i>
                            Penugasan Resmi
                        </h2>
                        
                        <div class="flex flex-col gap-4">
                            <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-lg border border-slate-200">
                                <div class="w-10 h-10 rounded bg-teal-50 border border-teal-200 text-teal-600 flex items-center justify-center shrink-0">
                                    <i class="ph-bold ph-hospital text-lg"></i>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Unit Kerja</span>
                                    <span class="text-[13px] font-bold text-slate-900 tracking-tight truncate">{{ $puskesmas['nama'] }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-lg border border-slate-200">
                                <div class="w-10 h-10 rounded bg-emerald-50 border border-emerald-200 text-emerald-600 flex items-center justify-center shrink-0">
                                    <i class="ph-bold ph-calendar-blank text-lg"></i>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Terdaftar Sejak</span>
                                    <span class="text-[13px] font-bold text-slate-900 tracking-tight truncate">{{ $user['created_at'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Keamanan -->
                    <div class="bg-white border border-slate-200 rounded-xl p-6">
                        <h2 class="text-[14px] font-bold tracking-tight text-slate-900 flex items-center gap-2 mb-6">
                            <i class="ph-bold ph-shield-check text-slate-600 text-lg"></i>
                            Keamanan Akun
                        </h2>
                        
                        <div class="flex flex-col gap-4">
                            <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-lg border border-slate-200">
                                <div class="w-10 h-10 rounded bg-white border border-slate-200 text-slate-500 flex items-center justify-center shrink-0">
                                    <i class="ph-bold ph-password text-lg"></i>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Sandi Diperbarui</span>
                                    <span class="text-[13px] font-bold text-slate-900 tracking-tight truncate">{{ $user['updated_at'] }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 bg-slate-50 p-4 rounded-lg border border-slate-200">
                                <div class="w-10 h-10 rounded bg-white border border-slate-200 text-slate-500 flex items-center justify-center shrink-0">
                                    <i class="ph-bold ph-clock text-lg"></i>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Sesi Login Terakhir</span>
                                    <span class="text-[13px] font-bold text-slate-900 tracking-tight truncate">{{ now()->translatedFormat('d M Y, H:i') }} WIB</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
