@extends('layouts.puskesmas')
@section('page-title', 'Pengaturan Keamanan')
@section('page-breadcrumbs', 'Keamanan Akun')
@section('page-mode', 'app')
@section('content')

<!-- Full-viewport Split View: Keamanan Management -->
<div class="flex flex-col lg:flex-row flex-1 overflow-hidden" x-data="{ 
    editMode: false,
    showCurrent: false,
    showNew: false,
    showConfirm: false,
}">

    <!-- LEFT PANEL: Settings Navigation -->
    <x-puskesmas.settings-sidebar active="keamanan" />

    <!-- RIGHT PANEL: Settings Canvas -->
    <div class="flex-1 flex flex-col overflow-y-auto bg-slate-50/50 p-6 lg:p-10 relative">
        <div class="max-w-4xl w-full mx-auto relative z-10">
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

            <form method="POST" action="{{ route('puskesmas.pengaturan.keamanan.update') }}" class="flex flex-col gap-8">
                @csrf
                @method('PUT')

                <!-- PROFILE BANNER -->
                <div class="bg-gradient-to-r from-slate-900 to-slate-800 rounded-2xl p-8 sm:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.08)] flex flex-col sm:flex-row items-center sm:items-start gap-6 sm:gap-8 relative overflow-hidden">
                    <!-- Decorative Elements -->
                    <div class="absolute -right-10 -top-10 w-64 h-64 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute right-10 bottom-0 opacity-10 pointer-events-none translate-y-1/4">
                        <i class="ph-fill ph-lock-key text-9xl text-white"></i>
                    </div>

                    <!-- Lock Icon Container -->
                    <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl bg-white/10 p-2 shrink-0 group relative overflow-hidden flex items-center justify-center text-teal-300 border border-white/20 backdrop-blur-sm z-10 shadow-inner">
                        <i class="ph-bold ph-shield-check text-6xl"></i>
                    </div>
                    
                    <!-- Info -->
                    <div class="flex-1 text-center sm:text-left mt-2 z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-emerald-900/40 border border-emerald-500/30 text-emerald-300 text-[10px] font-bold tracking-widest uppercase mb-3 backdrop-blur-sm">
                            <i class="ph-bold ph-check-circle"></i>
                            Sistem Terlindungi
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight mb-2 drop-shadow-sm">Keamanan Akun</h1>
                        <p class="text-slate-300 text-[14px] font-medium max-w-lg leading-relaxed">
                            Jaga kerahasiaan kata sandi Anda dan perbarui secara berkala untuk mencegah akses yang tidak sah ke portal puskesmas.
                        </p>
                    </div>

                    <!-- Action Button (View-only to edit mode toggle) -->
                    <div class="shrink-0 mt-4 sm:mt-0 z-10">
                        <button type="button" x-show="!editMode" @click="editMode = true" class="inline-flex items-center justify-center gap-2 bg-white text-slate-900 px-5 py-2.5 rounded-xl text-[13px] font-bold transition-all hover:bg-slate-50 focus:outline-none focus:ring-4 focus:ring-white/20 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                            <i class="ph-bold ph-key text-lg"></i>
                            Ubah Kata Sandi
                        </button>
                    </div>
                </div>

                <!-- SECTION: Edit Form -->
                <div x-show="editMode" x-cloak x-transition:enter="transition ease-out duration-200 transform" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4">
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                        
                        <!-- Header Card -->
                        <div class="px-6 lg:px-8 py-6 border-b border-slate-100 bg-white flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 border border-teal-100 shadow-sm">
                                <i class="ph-bold ph-lock-key text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-[16px] font-extrabold tracking-tight text-slate-900">Ubah Kata Sandi</h3>
                                <p class="text-[12px] font-medium text-slate-500 mt-0.5">Pastikan kata sandi baru Anda kuat dan belum pernah digunakan sebelumnya.</p>
                            </div>
                        </div>

                        <!-- Body Card -->
                        <div class="p-6 lg:p-8 flex flex-col gap-6">
                            
                            <!-- Input: Current Password -->
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 sm:items-start">
                                <label class="w-48 shrink-0 text-[12px] font-bold text-slate-500 uppercase tracking-widest sm:text-right mt-3">Kata Sandi Saat Ini</label>
                                <div class="flex-1 relative min-w-0">
                                    <div class="absolute top-0 left-0 h-12 w-12 flex items-center justify-center pointer-events-none">
                                        <i class="ph-bold ph-lock-key text-slate-400 text-lg"></i>
                                    </div>
                                    <input :type="showCurrent ? 'text' : 'password'" name="current_password" class="w-full pl-12 pr-12 py-3 text-[14px] bg-white border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 font-medium transition-all shadow-sm placeholder:text-slate-400" placeholder="Masukkan kata sandi lama">
                                    <button type="button" @click="showCurrent = !showCurrent" class="absolute top-0 right-0 h-12 w-12 flex items-center justify-center text-slate-400 hover:text-teal-600 transition-colors focus:outline-none">
                                        <i class="ph-bold" :class="showCurrent ? 'ph-eye-slash' : 'ph-eye'"></i>
                                    </button>
                                    @error('current_password') <p class="text-[11px] font-bold text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- Input: New Password -->
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 sm:items-start">
                                <label class="w-48 shrink-0 text-[12px] font-bold text-slate-500 uppercase tracking-widest sm:text-right mt-3">Kata Sandi Baru</label>
                                <div class="flex-1 relative min-w-0">
                                    <div class="absolute top-0 left-0 h-12 w-12 flex items-center justify-center pointer-events-none">
                                        <i class="ph-bold ph-key text-slate-400 text-lg"></i>
                                    </div>
                                    <input :type="showNew ? 'text' : 'password'" name="password" class="w-full pl-12 pr-12 py-3 text-[14px] bg-white border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 font-medium transition-all shadow-sm placeholder:text-slate-400" placeholder="Minimal 8 karakter">
                                    <button type="button" @click="showNew = !showNew" class="absolute top-0 right-0 h-12 w-12 flex items-center justify-center text-slate-400 hover:text-teal-600 transition-colors focus:outline-none">
                                        <i class="ph-bold" :class="showNew ? 'ph-eye-slash' : 'ph-eye'"></i>
                                    </button>
                                    @error('password') <p class="text-[11px] font-bold text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                                </div>
                            </div>

                            <!-- Input: Confirm Password -->
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 sm:items-start">
                                <label class="w-48 shrink-0 text-[12px] font-bold text-slate-500 uppercase tracking-widest sm:text-right mt-3">Konfirmasi Sandi Baru</label>
                                <div class="flex-1 relative min-w-0">
                                    <div class="absolute top-0 left-0 h-12 w-12 flex items-center justify-center pointer-events-none">
                                        <i class="ph-bold ph-check-circle text-slate-400 text-lg"></i>
                                    </div>
                                    <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" class="w-full pl-12 pr-12 py-3 text-[14px] bg-white border border-slate-300 rounded-xl text-slate-900 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 font-medium transition-all shadow-sm placeholder:text-slate-400" placeholder="Ketik ulang kata sandi baru">
                                    <button type="button" @click="showConfirm = !showConfirm" class="absolute top-0 right-0 h-12 w-12 flex items-center justify-center text-slate-400 hover:text-teal-600 transition-colors focus:outline-none">
                                        <i class="ph-bold" :class="showConfirm ? 'ph-eye-slash' : 'ph-eye'"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="p-6 lg:p-8 pt-0 mt-2 flex flex-col sm:flex-row items-center justify-end gap-3 border-t border-slate-100/0">
                            <button type="button" @click="editMode = false" class="w-full sm:w-auto px-5 py-2.5 rounded-xl border border-slate-300 bg-white text-slate-600 hover:bg-slate-50 font-bold text-[13px] transition-all text-center focus:outline-none shadow-sm">
                                Batal
                            </button>
                            <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white rounded-xl font-bold text-[13px] shadow-sm shadow-teal-500/30 transition-all text-center focus:outline-none">
                                Simpan Sandi Baru
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- Security Tip Box -->
                <div class="bg-blue-50/50 border border-blue-200 rounded-2xl p-6 flex flex-col sm:flex-row gap-5 items-start">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-blue-500 to-blue-600 text-white flex items-center justify-center shrink-0 shadow-sm shadow-blue-500/30">
                        <i class="ph-bold ph-lightbulb text-3xl"></i>
                    </div>
                    <div class="pt-1">
                        <h4 class="text-[16px] font-extrabold text-blue-900 mb-1.5">Tips Keamanan</h4>
                        <p class="text-[13px] font-medium text-blue-800 leading-relaxed mb-3">Gunakan kata sandi yang kuat dengan kombinasi huruf besar, huruf kecil, angka, dan simbol untuk melindungi data puskesmas.</p>
                        <ul class="text-[12px] font-bold text-blue-800 space-y-1.5 list-disc ml-4">
                            <li>Minimal 8 karakter.</li>
                            <li>Jangan gunakan kata sandi yang sama dengan situs web lain.</li>
                            <li>Ubah kata sandi Anda secara berkala untuk meminimalisasi risiko pembobolan.</li>
                        </ul>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
