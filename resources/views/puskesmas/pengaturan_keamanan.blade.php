@extends('layouts.puskesmas')
@section('page-title', 'Pengaturan Keamanan')
@section('page-breadcrumbs')
    Pengaturan 
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 text-[#CBD5E1]">
        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
    </svg>
    Keamanan Akun
@endsection
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
            
            @if($errors->any())
                <div class="mb-8 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 flex items-start gap-3.5 shadow-sm">
                    <div class="w-9 h-9 rounded-xl bg-rose-100 flex items-center justify-center shrink-0 text-rose-600 mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold mb-1">Terjadi kesalahan pada input data:</p>
                        <ul class="list-disc ml-5 space-y-1 font-medium text-[13px] text-rose-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('puskesmas.pengaturan.keamanan.update') }}" class="flex flex-col gap-6 lg:gap-8">
                @csrf
                @method('PUT')

                <!-- PROFILE BANNER -->
                <div class="relative bg-gradient-to-br from-slate-800 via-slate-700 to-slate-900 rounded-[32px] p-6 sm:p-8 shadow-md overflow-hidden">
                    <!-- Background Decorations -->
                    <div class="absolute -right-10 -top-10 w-64 h-64 bg-teal-500/10 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute left-0 bottom-0 w-40 h-40 bg-teal-600/20 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute inset-0 opacity-[0.03]" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
                    
                    <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-6 sm:gap-8">
                        <!-- Lock Icon Container -->
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-white/10 p-1.5 backdrop-blur-md shadow-inner shrink-0 group relative overflow-hidden">
                            <div class="w-full h-full rounded-xl bg-slate-800/80 text-teal-300 flex items-center justify-center overflow-hidden border border-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-1 text-center sm:text-left mt-1">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 border border-emerald-500/30 text-emerald-300 text-[11px] font-medium tracking-wide mb-3 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3">
                                  <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                </svg>
                                Sistem Terlindungi
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight leading-tight drop-shadow-sm mb-1.5">Keamanan Akun</h1>
                            <p class="text-slate-300 text-[14px] font-medium max-w-lg leading-relaxed">
                                Jaga kerahasiaan kata sandi Anda dan perbarui secara berkala untuk mencegah akses yang tidak sah ke portal Puskesmas.
                            </p>
                        </div>

                        <!-- Action Buttons (Desktop) -->
                        <div class="hidden lg:flex flex-col gap-3 mt-4 shrink-0">
                            <button type="button" x-show="!editMode" @click="editMode = true" class="px-5 py-2.5 bg-white text-slate-800 text-sm font-bold rounded-xl shadow-[0_4px_15px_rgba(0,0,0,0.1)] hover:shadow-[0_8px_20px_rgba(0,0,0,0.15)] hover:-translate-y-0.5 transition-all duration-300 flex items-center justify-center gap-2 border border-white/40">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-teal-600">
                                    <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                                </svg>
                                Ubah Kata Sandi
                            </button>
                            <div x-show="editMode" class="flex flex-col gap-2 w-full" x-cloak>
                                <button type="submit" class="w-full px-5 py-2.5 bg-gradient-to-r from-teal-400 to-emerald-400 text-slate-900 text-sm font-bold rounded-xl shadow-[0_4px_15px_rgba(20,184,166,0.3)] hover:shadow-[0_6px_20px_rgba(20,184,166,0.4)] transition-all flex items-center justify-center">
                                    Simpan Sandi Baru
                                </button>
                                <button type="button" @click="editMode = false" class="w-full px-5 py-2.5 bg-slate-700 hover:bg-slate-600 border border-slate-600 text-white text-sm font-medium rounded-xl transition-colors flex items-center justify-center">
                                    Batal
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SECTION: Edit Form -->
                <div x-show="editMode" x-cloak x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 -translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" x-transition:leave="transition ease-in duration-200 transform" x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 -translate-y-4">
                    <div class="bg-white/80 backdrop-blur-xl border border-slate-200/60 rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                        
                        <!-- Header Card -->
                        <div class="px-6 sm:px-8 py-5 border-b border-slate-100 bg-white/50 flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 border border-teal-100/50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 013 3m3 0a6 6 0 01-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1121.75 8.25z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold tracking-tight text-slate-800">Ubah Kata Sandi</h3>
                                <p class="text-xs font-medium text-slate-500 mt-0.5">Pastikan kata sandi baru Anda kuat dan belum pernah digunakan sebelumnya.</p>
                            </div>
                        </div>

                        <!-- Body Card -->
                        <div class="p-6 sm:p-8 flex flex-col gap-6">
                            
                            <!-- Input: Current Password -->
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 sm:items-center">
                                <label class="w-48 shrink-0 text-[13px] font-semibold text-slate-700 sm:text-right">Kata Sandi Saat Ini</label>
                                <div class="flex-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-400"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                                    </div>
                                    <input :type="showCurrent ? 'text' : 'password'" name="current_password" class="w-full pl-11 pr-12 py-3 text-[14px] bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 font-medium transition-all shadow-sm placeholder:text-slate-400" placeholder="Masukkan kata sandi lama">
                                    <button type="button" @click="showCurrent = !showCurrent" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-teal-600 transition-colors">
                                        <svg x-show="!showCurrent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        <svg x-show="showCurrent" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                                    </button>
                                </div>
                            </div>

                            <hr class="border-slate-100">

                            <!-- Input: New Password -->
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 sm:items-center">
                                <label class="w-48 shrink-0 text-[13px] font-semibold text-slate-700 sm:text-right">Kata Sandi Baru</label>
                                <div class="flex-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-400"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                                    </div>
                                    <input :type="showNew ? 'text' : 'password'" name="password" class="w-full pl-11 pr-12 py-3 text-[14px] bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 font-medium transition-all shadow-sm placeholder:text-slate-400" placeholder="Minimal 8 karakter">
                                    <button type="button" @click="showNew = !showNew" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-teal-600 transition-colors">
                                        <svg x-show="!showNew" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        <svg x-show="showNew" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Input: Confirm Password -->
                            <div class="flex flex-col sm:flex-row gap-2 sm:gap-6 sm:items-center">
                                <label class="w-48 shrink-0 text-[13px] font-semibold text-slate-700 sm:text-right">Konfirmasi Kata Sandi</label>
                                <div class="flex-1 relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-slate-400"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>
                                    <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation" class="w-full pl-11 pr-12 py-3 text-[14px] bg-white border border-slate-200 rounded-xl text-slate-900 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 font-medium transition-all shadow-sm placeholder:text-slate-400" placeholder="Ketik ulang kata sandi baru">
                                    <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-teal-600 transition-colors">
                                        <svg x-show="!showConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                                        <svg x-show="showConfirm" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons (Mobile) -->
                <div x-show="editMode" class="lg:hidden flex flex-col gap-3 mt-4" x-cloak>
                    <button type="submit" class="w-full px-5 py-3.5 bg-gradient-to-r from-teal-500 to-emerald-500 text-white text-sm font-bold rounded-xl shadow-[0_4px_15px_rgba(20,184,166,0.3)] transition-all flex items-center justify-center">
                        Simpan Sandi Baru
                    </button>
                    <button type="button" @click="editMode = false" class="w-full px-5 py-3.5 bg-white border border-slate-200 text-slate-700 text-sm font-bold rounded-xl transition-colors flex items-center justify-center shadow-sm">
                        Batal
                    </button>
                </div>
                
                <div x-show="!editMode" class="lg:hidden mt-4" x-cloak>
                    <button type="button" @click="editMode = true" class="w-full px-5 py-3.5 bg-white border border-slate-200 text-slate-700 hover:text-teal-700 text-sm font-bold rounded-xl shadow-sm hover:shadow-[0_4px_15px_rgba(0,0,0,0.05)] transition-all flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-teal-600">
                            <path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" />
                        </svg>
                        Ubah Kata Sandi
                    </button>
                </div>
                
                <!-- Security Tip Box -->
                <div class="bg-blue-50/80 border border-blue-100 rounded-[24px] p-6 flex flex-col sm:flex-row gap-5 items-start">
                    <div class="w-12 h-12 rounded-2xl bg-blue-100/80 text-blue-600 flex items-center justify-center shrink-0 border border-blue-200/50">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-[15px] font-bold text-blue-900 mb-1">Tips Keamanan</h4>
                        <p class="text-sm font-medium text-blue-700/80 leading-relaxed mb-3">Gunakan kata sandi yang kuat dengan kombinasi huruf besar, huruf kecil, angka, dan simbol untuk melindungi data puskesmas.</p>
                        <ul class="text-[13px] font-medium text-blue-700/70 space-y-1.5 list-disc ml-4">
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
