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
    <div class="flex-1 flex flex-col overflow-y-auto bg-slate-50/50 p-6 lg:p-10 relative">
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
                <div class="bg-gradient-to-r from-teal-800 via-teal-700 to-emerald-600 rounded-2xl p-8 sm:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.08)] flex flex-col sm:flex-row items-center sm:items-start gap-6 sm:gap-8 relative overflow-hidden">
                    <!-- Decorative Elements -->
                    <div class="absolute -right-10 -bottom-10 w-64 h-64 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute top-0 right-0 opacity-10 pointer-events-none translate-x-1/4 -translate-y-1/4">
                        <i class="ph-fill ph-shield-check text-9xl text-white"></i>
                    </div>

                    <!-- Avatar Container -->
                    <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl bg-white/10 p-2 shrink-0 group relative overflow-hidden flex items-center justify-center text-teal-100 border border-white/20 backdrop-blur-sm z-10 shadow-inner">
                        <i class="ph-bold ph-user text-6xl"></i>
                    </div>
                    
                    <!-- Info -->
                    <div class="flex-1 text-center sm:text-left mt-2 z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-teal-900/40 border border-teal-500/30 text-teal-50 text-[10px] font-bold tracking-widest uppercase mb-3 backdrop-blur-sm">
                            <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                            Petugas {{ $user['role'] }}
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight mb-2 drop-shadow-sm" x-text="formData.nama"></h1>
                        <p class="text-teal-100 text-[14px] font-medium flex items-center justify-center sm:justify-start gap-2 mt-2 bg-black/10 w-max mx-auto sm:mx-0 px-3 py-1.5 rounded-lg border border-white/5">
                            <i class="ph-bold ph-envelope-simple"></i>
                            <span x-text="formData.email"></span>
                        </p>
                    </div>
                    
                    <!-- Action -->
                    <div class="shrink-0 mt-4 sm:mt-0 z-10">
                        <button type="button" x-show="!editMode" @click="editMode = true" class="inline-flex items-center justify-center gap-2 bg-white text-teal-800 px-5 py-2.5 rounded-xl text-[13px] font-bold transition-all hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-white/20 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                            <i class="ph-bold ph-pencil-simple text-lg"></i>
                            Edit Profil
                        </button>
                    </div>
                </div>

                <!-- SECTION: Informasi Akun (Form) -->
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    <div class="p-6 lg:p-8">
                        <div class="flex items-center gap-4 mb-8">
                            <div class="w-12 h-12 rounded-xl bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-600 shrink-0 shadow-sm">
                                <i class="ph-bold ph-identification-card text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-[16px] font-extrabold text-slate-900 tracking-tight">Data Profil Pribadi</h2>
                                <p class="text-[12px] font-medium text-slate-500 mt-0.5">Identitas otorisasi Anda di dalam sistem.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Input: Nama -->
                            <div class="flex flex-col gap-2.5">
                                <label class="text-[12px] font-bold text-slate-500 uppercase tracking-widest">Nama Lengkap</label>
                                <div x-show="!editMode" class="w-full bg-slate-50/80 border border-slate-200 text-slate-900 text-[14px] font-bold rounded-xl px-4 py-3 shadow-sm min-h-[48px]">{{ $user['nama'] }}</div>
                                <div x-show="editMode" x-cloak>
                                    <input type="text" name="nama" x-model="formData.nama" class="w-full bg-white border border-teal-300 text-slate-900 text-[14px] font-bold rounded-xl px-4 py-3 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all shadow-sm">
                                    @error('nama') <p class="text-[11px] font-bold text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <!-- Input: Email -->
                            <div class="flex flex-col gap-2.5">
                                <label class="text-[12px] font-bold text-slate-500 uppercase tracking-widest">Alamat Email</label>
                                <div x-show="!editMode" class="w-full bg-slate-50/80 border border-slate-200 text-slate-900 text-[14px] font-bold rounded-xl px-4 py-3 shadow-sm min-h-[48px]">{{ $user['email'] }}</div>
                                <div x-show="editMode" x-cloak>
                                    <input type="email" name="email" x-model="formData.email" class="w-full bg-white border border-teal-300 text-slate-900 text-[14px] font-bold rounded-xl px-4 py-3 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all shadow-sm">
                                    @error('email') <p class="text-[11px] font-bold text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div x-show="editMode" x-cloak class="pt-8 mt-8 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-3" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 transform translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                            <button type="button" @click="editMode = false; formData.nama = '{{ addslashes($user['nama']) }}'; formData.email = '{{ addslashes($user['email']) }}'" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-600 hover:bg-slate-50 font-bold text-[13px] transition-all text-center focus:outline-none shadow-sm">
                                Batal
                            </button>
                            <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold text-[13px] shadow-sm shadow-teal-500/30 transition-all text-center focus:outline-none">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- ROW: Penugasan & Keamanan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    
                    <!-- Area Penugasan -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 lg:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                        <h2 class="text-[15px] font-extrabold tracking-tight text-slate-900 flex items-center gap-2 mb-6">
                            <i class="ph-bold ph-buildings text-teal-600 text-xl"></i>
                            Penugasan Resmi
                        </h2>
                        
                        <div class="flex flex-col gap-4">
                            <div class="flex items-center gap-4 bg-slate-50/80 p-4 rounded-xl border border-slate-100">
                                <div class="w-12 h-12 rounded-xl bg-teal-50 border border-teal-100 text-teal-600 flex items-center justify-center shrink-0 shadow-sm">
                                    <i class="ph-fill ph-hospital text-2xl"></i>
                                </div>
                                <div class="flex flex-col min-w-0 pt-0.5">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Unit Kerja</span>
                                    <span class="text-[14px] font-bold text-slate-900 tracking-tight truncate">{{ $puskesmas['nama'] }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 bg-slate-50/80 p-4 rounded-xl border border-slate-100">
                                <div class="w-12 h-12 rounded-xl bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center shrink-0 shadow-sm">
                                    <i class="ph-fill ph-calendar-blank text-2xl"></i>
                                </div>
                                <div class="flex flex-col min-w-0 pt-0.5">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Terdaftar Sejak</span>
                                    <span class="text-[14px] font-bold text-slate-900 tracking-tight truncate">{{ $user['created_at'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Keamanan -->
                    <div class="bg-white border border-slate-200 rounded-2xl p-6 lg:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                        <h2 class="text-[15px] font-extrabold tracking-tight text-slate-900 flex items-center gap-2 mb-6">
                            <i class="ph-bold ph-shield-check text-slate-700 text-xl"></i>
                            Keamanan Akun
                        </h2>
                        
                        <div class="flex flex-col gap-4">
                            <div class="flex items-center gap-4 bg-slate-50/80 p-4 rounded-xl border border-slate-100">
                                <div class="w-12 h-12 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center shrink-0 shadow-sm">
                                    <i class="ph-fill ph-password text-2xl"></i>
                                </div>
                                <div class="flex flex-col min-w-0 pt-0.5">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Sandi Diperbarui</span>
                                    <span class="text-[14px] font-bold text-slate-900 tracking-tight truncate">{{ $user['updated_at'] }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 bg-slate-50/80 p-4 rounded-xl border border-slate-100">
                                <div class="w-12 h-12 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center shrink-0 shadow-sm">
                                    <i class="ph-fill ph-clock text-2xl"></i>
                                </div>
                                <div class="flex flex-col min-w-0 pt-0.5">
                                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-0.5">Sesi Login Terakhir</span>
                                    <span class="text-[14px] font-bold text-slate-900 tracking-tight truncate">{{ now()->translatedFormat('d M Y, H:i') }} WIB</span>
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
