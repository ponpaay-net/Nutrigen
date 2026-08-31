@extends('layouts.app')

@section('page-title', 'Keamanan Akun Kader')

@section('content')

@php
    $kader = Auth::user()?->kader;
    $posyandu = $kader?->posyandu;
    $posyanduName = $posyandu?->nama ?? 'Posyandu Kader';
@endphp

<div class="w-full min-h-screen bg-slate-50/50 pb-20 lg:pb-12 selection:bg-teal-100 selection:text-teal-900">

    <!-- ── HERO SECTION ── -->
    <div class="relative bg-gradient-to-br from-teal-700 via-teal-600 to-teal-800 pt-8 pb-20 lg:pt-12 lg:pb-24 px-4 sm:px-6 lg:px-8 overflow-hidden lg:rounded-b-[40px] shadow-sm border-b border-teal-900/10">
        <div class="absolute -right-10 -top-10 w-64 h-64 bg-teal-400/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute left-0 bottom-0 w-40 h-40 bg-teal-900/40 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute inset-0 opacity-[0.05] pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>

        <div class="max-w-6xl mx-auto relative z-10 flex flex-col sm:flex-row gap-6 sm:gap-8 items-center sm:items-start text-center sm:text-left">

            <!-- Lock Icon -->
            <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-white/10 p-1.5 backdrop-blur-sm shadow-md shrink-0 group relative overflow-hidden">
                <div class="w-full h-full rounded-xl bg-teal-800/80 text-teal-200 flex items-center justify-center overflow-hidden border border-white/10">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
            </div>

            <!-- Info & Back -->
            <div class="flex flex-col flex-1 mt-2 sm:mt-4">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/15 border border-white/25 text-white text-[12px] font-medium mb-2 tracking-wide backdrop-blur-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 text-teal-200"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" /></svg>
                            Sistem Terlindungi
                        </div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-white tracking-tight drop-shadow-sm leading-tight">Keamanan Akun</h1>
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

    <!-- ── MAIN WORKSPACE ── -->
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 lg:-mt-14 relative z-20 flex flex-col gap-6 lg:gap-8" x-data="{ showCurrent: false, showNew: false, showConfirm: false }">

        @if(session('success'))
            <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 flex items-center gap-3 shadow-sm">
                <div class="w-9 h-9 rounded-xl bg-emerald-100 flex items-center justify-center flex-shrink-0 text-emerald-600">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>
                <div class="text-[14px] font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 flex items-start gap-3 shadow-sm">
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

        <div class="bg-white p-6 sm:p-8 lg:p-10 rounded-[28px] shadow-[0_8px_30px_rgb(0,0,0,0.06)] border border-slate-200/70 relative overflow-hidden">
            <div class="flex items-center gap-3 pb-6 mb-6 border-b border-slate-100">
                <div class="w-10 h-10 rounded-2xl bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-600 shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-slate-800 tracking-tight">Ubah Kata Sandi</h2>
                    <p class="text-xs font-medium text-slate-500">Pastikan kata sandi baru kuat dan belum pernah digunakan sebelumnya.</p>
                </div>
            </div>

            <form action="{{ route('kader.keamanan.update') }}" method="POST" class="flex flex-col gap-6">
                @csrf
                @method('PUT')

                <!-- Kata Sandi Saat Ini -->
                <div class="flex flex-col gap-2">
                    <label for="current_password" class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-teal-600"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" /></svg>
                        Kata Sandi Saat Ini <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showCurrent ? 'text' : 'password'" id="current_password" name="current_password" required
                               placeholder="Masukkan kata sandi lama"
                               class="w-full bg-slate-50 border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-medium rounded-2xl px-4 py-3.5 pr-12 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all placeholder:text-slate-400">
                        <button type="button" @click="showCurrent = !showCurrent" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-teal-600 transition-colors">
                            <svg x-show="!showCurrent" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <svg x-show="showCurrent" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                    @error('current_password') <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
                </div>

                <hr class="border-slate-100">

                <!-- Kata Sandi Baru -->
                <div class="flex flex-col gap-2">
                    <label for="password" class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-emerald-600"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        Kata Sandi Baru <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showNew ? 'text' : 'password'" id="password" name="password" required minlength="8"
                               placeholder="Minimal 8 karakter"
                               class="w-full bg-slate-50 border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-medium rounded-2xl px-4 py-3.5 pr-12 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all placeholder:text-slate-400">
                        <button type="button" @click="showNew = !showNew" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-teal-600 transition-colors">
                            <svg x-show="!showNew" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <svg x-show="showNew" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                    @error('password') <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
                </div>

                <!-- Konfirmasi Kata Sandi -->
                <div class="flex flex-col gap-2">
                    <label for="password_confirmation" class="flex items-center gap-2 text-xs font-semibold text-slate-700">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4 text-slate-500"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Konfirmasi Kata Sandi <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required
                               placeholder="Ketik ulang kata sandi baru"
                               class="w-full bg-slate-50 border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-medium rounded-2xl px-4 py-3.5 pr-12 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all placeholder:text-slate-400">
                        <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-400 hover:text-teal-600 transition-colors">
                            <svg x-show="!showConfirm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            <svg x-show="showConfirm" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                        </button>
                    </div>
                </div>

                <!-- Action -->
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
                        <span>Simpan Kata Sandi</span>
                    </button>
                </div>
            </form>
        </div>

        <!-- Security Tip -->
        <div class="bg-blue-50/80 border border-blue-100 rounded-[24px] p-6 flex flex-col sm:flex-row gap-5 items-start">
            <div class="w-12 h-12 rounded-2xl bg-blue-100/80 text-blue-600 flex items-center justify-center shrink-0 border border-blue-200/50">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                </svg>
            </div>
            <div>
                <h4 class="text-[15px] font-bold text-blue-900 mb-1">Tips Keamanan</h4>
                <p class="text-sm font-medium text-blue-700/80 leading-relaxed mb-3">Gunakan kata sandi yang kuat untuk melindungi data balita di Posyandu Anda.</p>
                <ul class="text-[13px] font-medium text-blue-700/70 space-y-1.5 list-disc ml-4">
                    <li>Minimal 8 karakter.</li>
                    <li>Kombinasi huruf besar, huruf kecil, angka, dan simbol.</li>
                    <li>Ubah kata sandi secara berkala.</li>
                </ul>
            </div>
        </div>

    </div>

</div>
@endsection
