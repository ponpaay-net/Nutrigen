@extends('layouts.puskesmas')
@section('page-title', 'Pengaturan Keamanan')
@section('page-breadcrumbs', 'Keamanan Akun')
@section('page-mode', 'app')
@section('content')

{{-- Backend Contract:
    Controller: PuskesmasController@keamanan & updateKeamanan
--}}

<div class="flex flex-col lg:flex-row flex-1 overflow-hidden" x-data="{ 
    showCurrent: false,
    showNew: false,
    showConfirm: false,
}">

    <!-- RIGHT PANEL: Settings Canvas -->
    <div class="flex-1 flex flex-col overflow-y-auto bg-slate-50/50 relative">
        
        <!-- Colorful Vibrant Header -->
        <div class="shrink-0 px-6 sm:px-10 py-10 bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-600 text-white relative overflow-hidden shadow-sm">
            <!-- Decorative elements -->
            <div class="absolute -right-16 -top-16 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-10 bottom-0 opacity-10 pointer-events-none translate-y-1/4">
                <i class="ph-fill ph-shield-check text-9xl"></i>
            </div>
            
            <div class="relative z-10 max-w-4xl mx-auto">
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Keamanan Akun</h1>
                <p class="text-[13px] text-teal-50 font-medium max-w-xl mt-1.5 leading-relaxed">Perbarui kata sandi akun secara berkala untuk menjaga kerahasiaan dan integritas data faskes.</p>
                
                <x-puskesmas.settings-tabs active="keamanan" />
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

                @if($errors->any())
                    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 flex items-start gap-3.5 shadow-sm">
                        <div class="w-10 h-10 rounded-lg bg-white border border-rose-100 flex items-center justify-center shrink-0 text-rose-600 shadow-sm mt-0.5">
                            <i class="ph-bold ph-warning-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[14px] font-bold text-slate-900 mb-1">Gagal memperbarui kata sandi:</p>
                            <ul class="list-disc ml-4 space-y-1 font-medium text-[12px] text-rose-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('puskesmas.pengaturan.keamanan.update') }}" class="flex flex-col gap-6">
                    @csrf
                    @method('PUT')

                    <!-- SECTION: Form Card -->
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                        <!-- Header Card -->
                        <div class="px-6 lg:px-8 py-6 border-b border-slate-100 bg-white flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 border border-teal-100 shadow-sm">
                                <i class="ph-bold ph-lock-key text-2xl"></i>
                            </div>
                            <div>
                                <h2 class="text-[16px] font-extrabold tracking-tight text-slate-900">Ubah Kata Sandi</h2>
                                <p class="text-[12px] font-medium text-slate-500 mt-0.5">Gunakan kombinasi minimal 8 karakter dengan huruf, angka, dan simbol.</p>
                            </div>
                        </div>

                        <!-- Body Card Inputs -->
                        <div class="p-6 lg:p-8 flex flex-col gap-6">
                            <!-- Input: Current Password -->
                            <div class="flex flex-col gap-2">
                                <label class="text-[12px] font-bold text-slate-600 uppercase tracking-wider">Kata Sandi Saat Ini</label>
                                <div class="relative max-w-xl">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="ph-bold ph-lock-key text-lg"></i>
                                    </div>
                                    <input :type="showCurrent ? 'text' : 'password'" 
                                           name="current_password" 
                                           required
                                           class="w-full pl-10 pr-12 py-3 text-[14px] bg-slate-50/50 hover:bg-white focus:bg-white border border-slate-200 focus:border-teal-500 rounded-xl text-slate-900 focus:outline-none focus:ring-4 focus:ring-teal-500/10 font-medium transition-all shadow-2xs placeholder:text-slate-400" 
                                           placeholder="Masukkan kata sandi lama">
                                    <button type="button" @click="showCurrent = !showCurrent" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-teal-600 transition-colors focus:outline-none">
                                        <i class="ph-bold text-lg" :class="showCurrent ? 'ph-eye-slash' : 'ph-eye'"></i>
                                    </button>
                                </div>
                                @error('current_password') <p class="text-[11px] font-bold text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="h-px bg-slate-100 my-1"></div>

                            <!-- Input: New Password -->
                            <div class="flex flex-col gap-2">
                                <label class="text-[12px] font-bold text-slate-600 uppercase tracking-wider">Kata Sandi Baru</label>
                                <div class="relative max-w-xl">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="ph-bold ph-key text-lg"></i>
                                    </div>
                                    <input :type="showNew ? 'text' : 'password'" 
                                           name="password" 
                                           required
                                           class="w-full pl-10 pr-12 py-3 text-[14px] bg-slate-50/50 hover:bg-white focus:bg-white border border-slate-200 focus:border-teal-500 rounded-xl text-slate-900 focus:outline-none focus:ring-4 focus:ring-teal-500/10 font-medium transition-all shadow-2xs placeholder:text-slate-400" 
                                           placeholder="Minimal 8 karakter">
                                    <button type="button" @click="showNew = !showNew" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-teal-600 transition-colors focus:outline-none">
                                        <i class="ph-bold text-lg" :class="showNew ? 'ph-eye-slash' : 'ph-eye'"></i>
                                    </button>
                                </div>
                                @error('password') <p class="text-[11px] font-bold text-rose-500 mt-1">{{ $message }}</p> @enderror
                            </div>

                            <!-- Input: Confirm Password -->
                            <div class="flex flex-col gap-2">
                                <label class="text-[12px] font-bold text-slate-600 uppercase tracking-wider">Konfirmasi Kata Sandi Baru</label>
                                <div class="relative max-w-xl">
                                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                                        <i class="ph-bold ph-check-circle text-lg"></i>
                                    </div>
                                    <input :type="showConfirm ? 'text' : 'password'" 
                                           name="password_confirmation" 
                                           required
                                           class="w-full pl-10 pr-12 py-3 text-[14px] bg-slate-50/50 hover:bg-white focus:bg-white border border-slate-200 focus:border-teal-500 rounded-xl text-slate-900 focus:outline-none focus:ring-4 focus:ring-teal-500/10 font-medium transition-all shadow-2xs placeholder:text-slate-400" 
                                           placeholder="Ketik ulang kata sandi baru">
                                    <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-teal-600 transition-colors focus:outline-none">
                                        <i class="ph-bold text-lg" :class="showConfirm ? 'ph-eye-slash' : 'ph-eye'"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer CTA -->
                        <div class="px-6 lg:px-8 py-5 bg-slate-50/80 border-t border-slate-100 flex items-center justify-end">
                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold text-[13px] shadow-sm shadow-teal-500/30 transition-all text-center focus:outline-none flex items-center justify-center gap-2">
                                <i class="ph-bold ph-check"></i>
                                Perbarui Kata Sandi
                            </button>
                        </div>
                    </div>
                    
                    <!-- Security Tip Box -->
                    <div class="bg-blue-50/50 border border-blue-200 rounded-2xl p-6 flex flex-col sm:flex-row gap-5 items-start shadow-xs">
                        <div class="w-12 h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center shrink-0 shadow-sm border border-blue-200">
                            <i class="ph-bold ph-lightbulb text-2xl"></i>
                        </div>
                        <div class="pt-0.5">
                            <h3 class="text-[15px] font-extrabold text-blue-900 mb-1">Pedoman Keamanan Sandi</h3>
                            <p class="text-[13px] font-medium text-blue-800 leading-relaxed mb-3">Lindungi akun puskesmas Anda dari akses tidak berwenang dengan mengikuti panduan standar berikut:</p>
                            <ul class="text-[12px] font-medium text-blue-900 space-y-1.5 list-disc ml-4">
                                <li>Panjang sandi minimal 8 karakter dengan variasi angka dan huruf besar/kecil.</li>
                                <li>Hindari penggunaan nama faskes, tanggal lahir, atau pola umum yang mudah ditebak.</li>
                                <li>Jangan membagikan akun operator dengan pihak yang tidak memiliki kewenangan validasi.</li>
                            </ul>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
