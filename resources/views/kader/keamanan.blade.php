@extends('layouts.app')

@section('page-title', 'Keamanan Akun Kader')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 pt-5 sm:pt-8 pb-28 sm:pb-12">
    <div class="max-w-4xl mx-auto">

        {{-- Page header --}}
        <div class="mb-6">
            <a href="{{ route('kader.profil') }}" class="inline-flex items-center gap-2 h-11 px-4 rounded-xl bg-white border border-slate-300 hover:border-teal-400 hover:text-teal-700 text-slate-700 text-[13px] font-semibold shadow-sm transition-colors">
                <x-icon name="arrow-left" weight="bold" class="text-[16px]" />Kembali ke Profil
            </a>
            <div class="flex items-center gap-3 mt-4">
                <span class="w-1 h-6 bg-amber-400 rounded-full"></span>
                <h1 class="text-lg font-bold text-slate-900">Keamanan Akun Kader</h1>
            </div>
            <p class="text-[13px] text-slate-500 mt-1 ml-4">Atur kata sandi dan lindungi akses login Anda.</p>
        </div>

        @if(session('success'))
            <div class="mb-5 flex items-center gap-3 p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800">
                <span class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0"><x-icon name="check-circle" weight="fill" class="text-[18px]" /></span>
                <div class="text-[13.5px] font-medium">{{ session('success') }}</div>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800">
                <div class="flex items-center gap-3 mb-1.5">
                    <span class="w-9 h-9 rounded-xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0"><x-icon name="warning-circle" weight="fill" class="text-[18px]" /></span>
                    <p class="text-[13.5px] font-semibold">Periksa kembali data yang dimasukkan.</p>
                </div>
                <ul class="list-disc list-inside ml-10 text-xs text-rose-700 space-y-0.5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 lg:gap-6" x-data="{ showCurrent:false, showNew:false, showConfirm:false }">
            {{-- Form ubah kata sandi --}}
            <div class="lg:col-span-2">
                <form action="{{ route('kader.keamanan.update') }}" method="POST" class="bg-white ring-1 ring-slate-200/70 rounded-2xl shadow-sm shadow-slate-200/60 overflow-hidden">
                    @csrf
                    @method('PUT')

                    <div class="p-6 sm:p-8 border-b border-slate-100">
                        <h2 class="text-[13px] font-bold text-slate-800 flex items-center gap-2 mb-2"><span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center"><x-icon name="lock" weight="bold" class="text-[16px]" /></span>Ubah Kata Sandi</h2>
                        <p class="text-[12.5px] text-slate-500 ml-10">Pastikan kata sandi baru kuat dan belum pernah digunakan sebelumnya.</p>
                    </div>

                    <div class="p-6 sm:p-8">
                        {{-- Kata Sandi Saat Ini --}}
                        <div class="flex flex-col gap-2">
                            <label for="current_password" class="text-[12.5px] font-semibold text-slate-700">Kata Sandi Saat Ini <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input :type="showCurrent ? 'text' : 'password'" id="current_password" name="current_password" required placeholder="Masukkan kata sandi lama"
                                    class="w-full bg-slate-50 border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-medium rounded-xl px-4 py-3 pr-12 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all placeholder:text-slate-400">
                                <button type="button" @click="showCurrent = !showCurrent" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-teal-600 transition-colors" :aria-label="showCurrent ? 'Sembunyikan' : 'Lihat'">
                                    <x-icon name="eye" weight="bold" class="text-[18px]" x-show="!showCurrent" />
                                    <x-icon name="eye-slash" weight="bold" class="text-[18px]" x-show="showCurrent" x-cloak />
                                </button>
                            </div>
                            @error('current_password') <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        {{-- Kata Sandi Baru --}}
                        <div class="flex flex-col gap-2 mt-5">
                            <label for="password" class="text-[12.5px] font-semibold text-slate-700">Kata Sandi Baru <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input :type="showNew ? 'text' : 'password'" id="password" name="password" required minlength="8" placeholder="Masukkan kata sandi baru"
                                    class="w-full bg-slate-50 border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-medium rounded-xl px-4 py-3 pr-12 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all placeholder:text-slate-400">
                                <button type="button" @click="showNew = !showNew" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-teal-600 transition-colors"><x-icon name="eye" weight="bold" class="text-[18px]" x-show="!showNew" /><x-icon name="eye-slash" weight="bold" class="text-[18px]" x-show="showNew" x-cloak /></button>
                            </div>
                            <p class="text-[11.5px] text-slate-500 font-medium mt-1.5">Minimal 8 karakter, gunakan kombinasi huruf besar, kecil, angka, dan simbol.</p>
                            @error('password') <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        {{-- Konfirmasi --}}
                        <div class="flex flex-col gap-2 mt-5">
                            <label for="password_confirmation" class="text-[12.5px] font-semibold text-slate-700">Konfirmasi Kata Sandi <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input :type="showConfirm ? 'text' : 'password'" id="password_confirmation" name="password_confirmation" required placeholder="Ketik ulang kata sandi baru"
                                    class="w-full bg-slate-50 border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-medium rounded-xl px-4 py-3 pr-12 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all placeholder:text-slate-400">
                                <button type="button" @click="showConfirm = !showConfirm" class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-teal-600 transition-colors"><x-icon name="eye" weight="bold" class="text-[18px]" x-show="!showConfirm" /><x-icon name="eye-slash" weight="bold" class="text-[18px]" x-show="showConfirm" x-cloak /></button>
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="px-6 sm:px-8 py-5 border-t border-slate-100 bg-slate-50/40 flex items-center gap-3">
                        <a href="{{ route('kader.profil') }}" class="inline-flex items-center justify-center h-11 px-5 rounded-xl bg-white border border-slate-400 hover:bg-slate-50 text-slate-700 text-[13.5px] font-semibold transition-colors">Batal</a>
                        <button type="submit" class="flex-1 sm:flex-none ml-auto inline-flex items-center justify-center gap-2 h-11 px-6 rounded-xl bg-teal-600 hover:bg-teal-500 active:bg-teal-700 text-white text-[13.5px] font-bold shadow-sm shadow-teal-600/25 transition-all active:scale-[0.98]"><x-icon name="check-circle" weight="bold" class="text-[16px]" />Simpan Kata Sandi</button>
                    </div>
                </form>
            </div>

            {{-- RIGHT RAIL --}}
            <div class="flex flex-col gap-5 lg:sticky lg:top-24">
                {{-- Tips Keamanan (teal) --}}
                <div class="bg-teal-50 border border-teal-100 rounded-2xl p-5 sm:p-6">
                    <h2 class="text-[13px] font-bold text-teal-800 flex items-center gap-2 mb-3"><span class="w-7 h-7 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center"><x-icon name="shield-check" weight="bold" class="text-[16px]" /></span>Tips Keamanan</h2>
                    <ul class="flex flex-col gap-2 text-[12.5px] text-teal-900/80 leading-relaxed">
                        <li class="flex items-start gap-2"><x-icon name="check-circle" weight="bold" class="text-[15px] text-teal-600 shrink-0" />Gunakan kombinasi huruf besar, kecil, angka, dan simbol.</li>
                        <li class="flex items-start gap-2"><x-icon name="check-circle" weight="bold" class="text-[15px] text-teal-600 shrink-0" />Jangan gunakan kata sandi yang sama dengan akun lain.</li>
                        <li class="flex items-start gap-2"><x-icon name="check-circle" weight="bold" class="text-[15px] text-teal-600 shrink-0" />Ubah kata sandi secara berkala untuk keamanan data Posyandu.</li>
                    </ul>
                </div>

                {{-- Keamanan Data --}}
                <div class="bg-white ring-1 ring-slate-200/70 rounded-2xl shadow-sm shadow-slate-200/50 p-5 sm:p-6">
                    <h2 class="text-[13px] font-bold text-slate-800 flex items-center gap-2 mb-3"><span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center"><x-icon name="key" weight="bold" class="text-[16px]" /></span>Keamanan Data</h2>
                    <p class="text-[12px] text-slate-500 leading-relaxed">Data balita di Posyandu Anda bersifat sensitif. Pastikan hanya Anda yang memegang akses login kader.</p>
                    <p class="text-[11px] text-slate-500 mt-2.5">Akun terdaftar dengan email: <span class="font-semibold text-teal-700">{{ Auth::user()->email ?? '-' }}</span></p>
                </div>

                {{-- Bantuan Sandi (isi whitespace rail + relevan) --}}
                <div class="bg-teal-50 border border-teal-100 rounded-2xl p-5 sm:p-6">
                    <h2 class="text-[13px] font-bold text-teal-800 flex items-center gap-2 mb-2.5"><span class="w-7 h-7 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center"><x-icon name="lifebuoy" weight="bold" class="text-[16px]" /></span>Bantuan Sandi</h2>
                    <p class="text-[12px] text-teal-900/80 leading-relaxed">Lupa kata sandi? Hubungi koordinator Bidan Pembina di Puskesmas untuk pengaturan ulang akses login Anda.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
