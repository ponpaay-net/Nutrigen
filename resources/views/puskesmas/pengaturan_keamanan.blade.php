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

            <form method="POST" action="{{ route('puskesmas.pengaturan.keamanan.update') }}" class="flex flex-col gap-6">
                @csrf
                @method('PUT')

                <!-- PROFILE BANNER -->
                <div class="bg-slate-900 rounded-xl p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row items-center sm:items-start gap-6 sm:gap-8">
                    <!-- Lock Icon Container -->
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded bg-slate-800 p-1.5 shrink-0 group relative overflow-hidden flex items-center justify-center text-teal-300 border border-slate-700">
                        <i class="ph-bold ph-shield-check text-4xl"></i>
                    </div>
                    
                    <!-- Info -->
                    <div class="flex-1 text-center sm:text-left mt-1">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-emerald-900/50 border border-emerald-700/50 text-emerald-400 text-[10px] font-bold tracking-widest uppercase mb-3">
                            <i class="ph-bold ph-check-circle"></i>
                            Sistem Terlindungi
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-tight mb-1.5">Keamanan Akun</h1>
                        <p class="text-slate-400 text-[13px] font-medium max-w-lg leading-relaxed">
                            Jaga kerahasiaan kata sandi Anda dan perbarui secara berkala untuk mencegah akses yang tidak sah ke portal Puskesmas.
                        </p>
                    </div>

                    <!-- Action Buttons (Desktop) -->
                    <div class="hidden lg:flex flex-col gap-2 mt-4 shrink-0">
                        <button type="button" x-show="!editMode" @click="editMode = true" class="px-4 py-2 bg-white text-slate-900 text-[12px] font-bold rounded-lg transition-colors hover:bg-slate-50 flex items-center justify-center gap-2 border border-slate-200 focus:outline-none focus:ring-2 focus:ring-slate-300 shadow-sm">
                            <i class="ph-bold ph-key"></i>
                            Ubah Kata Sandi
                        </button>
                        <div x-show="editMode" class="flex flex-col gap-2 w-full" x-cloak>
                            <button type="submit" class="w-full px-4 py-2 bg-teal-600 text-white text-[12px] font-bold rounded-lg hover:bg-teal-700 transition-colors flex items-center justify-center shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-1 focus:ring-offset-slate-900">
                                Simpan Sandi Baru
                            </button>
                            <button type="button" @click="editMode = false" class="w-full px-4 py-2 bg-slate-800 hover:bg-slate-700 border border-slate-700 text-slate-300 text-[12px] font-bold rounded-lg transition-colors flex items-center justify-center focus:outline-none focus:ring-2 focus:ring-slate-600">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>

                <!-- SECTION: Edit Form -->
                <div x-show="editMode" x-cloak x-transition:enter="transition ease-out duration-200 transform" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-2">
                    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                        
                        <!-- Header Card -->
                        <div class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex items-center gap-4">
                            <div class="w-10 h-10 rounded bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 border border-teal-200">
                                <i class="ph-bold ph-lock-key text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-[15px] font-bold tracking-tight text-slate-900">Ubah Kata Sandi</h3>
                                <p class="text-[12px] font-medium text-slate-500 mt-0.5">Pastikan kata sandi baru Anda kuat dan belum pernah digunakan sebelumnya.</p>
                            </div>
                        </div>

                        <!-- Body Card -->
                        <div class="p-6 flex flex-col gap-6">
                            
                            <!-- Input: Current Password -->
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 sm:items-center">
                                <label class="w-48 shrink-0 text-[12px] font-bold text-slate-700 sm:text-right">Kata Sandi Saat Ini</label>
                                <div class="flex-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph-bold ph-lock-key text-slate-400 text-lg"></i>
                                    </div>
                                    <input :type="showCurrent ? 'text' : 'password'" name="current_password" class="w-full pl-10 pr-10 py-2 text-[13px] bg-white border border-slate-300 rounded-md text-slate-900 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 font-medium transition-colors shadow-sm placeholder:text-slate-400" placeholder="Masukkan kata sandi lama">
                                    <button type="button" @click="showCurrent = !showCurrent" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-teal-600 transition-colors focus:outline-none">
                                        <i class="ph-bold" :class="showCurrent ? 'ph-eye-slash' : 'ph-eye'"></i>
                                    </button>
                                </div>
                            </div>

                            <hr class="border-slate-200">

                            <!-- Input: New Password -->
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 sm:items-center">
                                <label class="w-48 shrink-0 text-[12px] font-bold text-slate-700 sm:text-right">Kata Sandi Baru</label>
                                <div class="flex-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph-bold ph-key text-slate-400 text-lg"></i>
                                    </div>
                                    <input :type="showNew ? 'text' : 'password'" name="password" class="w-full pl-10 pr-10 py-2 text-[13px] bg-white border border-slate-300 rounded-md text-slate-900 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 font-medium transition-colors shadow-sm placeholder:text-slate-400" placeholder="Minimal 8 karakter">
                                    <button type="button" @click="showNew = !showNew" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-teal-600 transition-colors focus:outline-none">
                                        <i class="ph-bold" :class="showNew ? 'ph-eye-slash' : 'ph-eye'"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Input: Confirm Password -->
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 sm:items-center">
                                <label class="w-48 shrink-0 text-[12px] font-bold text-slate-700 sm:text-right">Konfirmasi Kata Sandi</label>
                                <div class="flex-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="ph-bold ph-check-circle text-slate-400 text-lg"></i>
                                    </div>
                                    <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" class="w-full pl-10 pr-10 py-2 text-[13px] bg-white border border-slate-300 rounded-md text-slate-900 focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 font-medium transition-colors shadow-sm placeholder:text-slate-400" placeholder="Ketik ulang kata sandi baru">
                                    <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-teal-600 transition-colors focus:outline-none">
                                        <i class="ph-bold" :class="showConfirm ? 'ph-eye-slash' : 'ph-eye'"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons (Mobile) -->
                <div x-show="editMode" class="lg:hidden flex flex-col gap-2 mt-2" x-cloak>
                    <button type="submit" class="w-full px-4 py-2 bg-teal-600 text-white text-[12px] font-bold rounded-lg shadow-sm transition-colors flex items-center justify-center">
                        Simpan Sandi Baru
                    </button>
                    <button type="button" @click="editMode = false" class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-700 text-[12px] font-bold rounded-lg transition-colors flex items-center justify-center shadow-sm">
                        Batal
                    </button>
                </div>
                
                <div x-show="!editMode" class="lg:hidden mt-2" x-cloak>
                    <button type="button" @click="editMode = true" class="w-full px-4 py-2 bg-white border border-slate-300 text-slate-900 text-[12px] font-bold rounded-lg shadow-sm transition-colors flex items-center justify-center gap-2">
                        <i class="ph-bold ph-key"></i>
                        Ubah Kata Sandi
                    </button>
                </div>
                
                <!-- Security Tip Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6 flex flex-col sm:flex-row gap-5 items-start">
                    <div class="w-12 h-12 rounded bg-blue-100 text-blue-600 flex items-center justify-center shrink-0 border border-blue-200">
                        <i class="ph-bold ph-lightbulb text-2xl"></i>
                    </div>
                    <div>
                        <h4 class="text-[14px] font-bold text-blue-900 mb-1">Tips Keamanan</h4>
                        <p class="text-[13px] font-medium text-blue-800 leading-relaxed mb-3">Gunakan kata sandi yang kuat dengan kombinasi huruf besar, huruf kecil, angka, dan simbol untuk melindungi data puskesmas.</p>
                        <ul class="text-[12px] font-bold text-blue-800 space-y-1.5 list-disc ml-4">
                            <li>Minimal 8 karakter.</li>
                            <li>Jangan gunakan kata sandi yang sama dengan situs lain.</li>
                            <li>Ubah kata sandi secara berkala.</li>
                        </ul>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
