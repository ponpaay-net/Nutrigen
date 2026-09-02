@extends('layouts.app')

@section('page-title', 'Edit Profil Kader')

@section('content')
@php
    $ini = strtoupper(collect(explode(' ', $name ?? 'Kader'))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->implode(''));
@endphp

<div class="w-full pb-28 sm:pb-12">
    <div class="max-w-5xl mx-auto">

        {{-- Page header --}}
        <div class="mb-5">
            <a href="{{ route('kader.profil') }}" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-slate-500 hover:text-teal-700 transition-colors"><x-icon name="arrow-left" weight="bold" class="text-[15px]" />Kembali ke Profil</a>
            <div class="flex items-center gap-3 mt-3">
                <span class="w-1 h-6 bg-amber-400 rounded-full"></span>
                <h1 class="text-lg font-bold text-slate-900">Edit Profil Kader</h1>
            </div>
            <p class="text-[13px] text-slate-500 mt-1 ml-4">Perbarui identitas dan kontak resmi operasional Anda.</p>
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

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 lg:gap-6">
            {{-- FORM --}}
            <div class="lg:col-span-2">
                <form action="{{ route('kader.profil.update') }}" method="POST" class="bg-white ring-1 ring-slate-200/70 rounded-2xl shadow-sm shadow-slate-200/60 overflow-hidden">
                    @csrf
                    @method('PUT')

                    {{-- 01 Identitas --}}
                    <div class="p-6 sm:p-8">
                        <h2 class="text-[13px] font-bold text-slate-800 flex items-center gap-2 mb-5">
                            <span class="w-6 h-6 rounded-md bg-teal-100 text-teal-700 text-[12px] font-black flex items-center justify-center">01</span>
                            <span class="inline-flex items-center gap-2"><x-icon name="user-circle" weight="bold" class="text-[16px] text-teal-600" />Identitas Kader</span>
                        </h2>
                        <div class="flex flex-col gap-2">
                            <label for="nama" class="text-[12.5px] font-semibold text-slate-700">Nama Lengkap Kader <span class="text-rose-500">*</span></label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama', $name ?? '') }}" required placeholder="Contoh: Ibu Siti Aminah"
                                class="w-full bg-slate-50 border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-medium rounded-xl px-4 py-3 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all placeholder:text-slate-400">
                            @error('nama') <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    {{-- 02 Kontak & Notifikasi --}}
                    <div class="px-6 sm:px-8 pb-6 sm:pb-8">
                        <h2 class="text-[13px] font-bold text-slate-800 flex items-center gap-2 mb-5">
                            <span class="w-6 h-6 rounded-md bg-teal-100 text-teal-700 text-[12px] font-black flex items-center justify-center">02</span>
                            <span class="inline-flex items-center gap-2"><x-icon name="phone" weight="bold" class="text-[16px] text-teal-600" />Kontak & Notifikasi</span>
                        </h2>
                        <div class="flex flex-col gap-2">
                            <label for="no_hp" class="text-[12.5px] font-semibold text-slate-700">Nomor Telepon / WhatsApp <span class="text-rose-500">*</span></label>
                            <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp', $phone ?? '') }}" required placeholder="Contoh: 081234567890"
                                class="w-full bg-slate-50 border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-medium rounded-xl px-4 py-3 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all placeholder:text-slate-400">
                            <p class="text-[11.5px] text-slate-400 font-medium">Dipakai untuk notifikasi jadwal posyandu dan koordinasi dengan ibu balita.</p>
                            @error('no_hp') <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex flex-col gap-2 mt-5">
                            <div class="flex items-center justify-between">
                                <label class="text-[12.5px] font-semibold text-slate-700">Alamat Email Akun</label>
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wide text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md border border-slate-200"><x-icon name="lock" weight="bold" class="text-[11px]" />Terkunci</span>
                            </div>
                            <input type="email" value="{{ $email ?? '' }}" readonly disabled
                                class="w-full bg-slate-100/80 border border-slate-200 text-slate-500 text-[14px] font-medium rounded-xl px-4 py-3 cursor-not-allowed select-none outline-none">
                            <p class="text-[11.5px] text-slate-400 font-medium">Alamat email login dikelola secara resmi oleh admin Puskesmas demi keamanan data.</p>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="px-6 sm:px-8 py-5 border-t border-slate-100 bg-slate-50/40 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
                        <a href="{{ route('kader.profil') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-11 px-6 rounded-xl bg-white border border-slate-300 text-slate-600 hover:bg-slate-50 text-[13.5px] font-semibold transition-colors">Batal</a>
                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-11 px-6 rounded-xl bg-teal-600 hover:bg-teal-500 active:bg-teal-700 text-white text-[13.5px] font-bold shadow-sm shadow-teal-600/25 transition-all active:scale-[0.98]"><x-icon name="check-circle" weight="bold" class="text-[16px]" />Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            {{-- RIGHT RAIL --}}
            <div class="flex flex-col gap-5">
                {{-- Area Penugasan --}}
                <div class="bg-white ring-1 ring-slate-200/70 rounded-2xl shadow-sm shadow-slate-200/50 p-5 sm:p-6">
                    <h2 class="text-[13px] font-bold text-slate-800 flex items-center gap-2 mb-4"><span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center"><x-icon name="map-pin" weight="bold" class="text-[16px]" /></span>Area Penugasan</h2>
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-3.5 bg-slate-50 border border-slate-100 p-3.5 rounded-xl">
                            <span class="w-10 h-10 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center shrink-0"><x-icon name="building" weight="bold" class="text-[18px]" /></span>
                            <div class="min-w-0">
                                <p class="text-[11px] font-medium text-slate-400">Posyandu Induk</p>
                                <p class="text-[13.5px] font-semibold text-slate-800 truncate">{{ $posyanduName }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3.5 bg-slate-50 border border-slate-100 p-3.5 rounded-xl">
                            <span class="w-10 h-10 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center shrink-0"><x-icon name="cross" weight="regular" class="text-[18px]" /></span>
                            <div class="min-w-0">
                                <p class="text-[11px] font-medium text-slate-400">Puskesmas Pembina</p>
                                <p class="text-[13.5px] font-semibold text-slate-800 truncate">{{ $puskesmasName }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bantuan (info) --}}
                <div class="bg-teal-50 border border-teal-100 rounded-2xl p-5 sm:p-6">
                    <h2 class="text-[13px] font-bold text-teal-800 flex items-center gap-2 mb-2.5"><span class="w-7 h-7 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center"><x-icon name="info" weight="bold" class="text-[16px]" /></span>Perubahan Data</h2>
                    <p class="text-[12.5px] text-teal-900/80 leading-relaxed">Perubahan wilayah posyandu tugas dan alamat email induk hanya dapat dilakukan melalui koordinator Bidan Pembina di Puskesmas.</p>
                </div>

                {{-- Avatar preview --}}
                <div class="bg-white ring-1 ring-slate-200/70 rounded-2xl shadow-sm shadow-slate-200/50 p-5 sm:p-6 flex items-center gap-4">
                    <div class="relative shrink-0">
                        <div class="w-14 h-14 rounded-full bg-teal-600 text-white flex items-center justify-center font-bold text-lg ring-2 ring-white shadow-md shadow-teal-700/30">{{ $ini }}</div>
                        <span class="absolute bottom-0 right-0 w-3.5 h-3.5 rounded-full bg-emerald-500 ring-2 ring-white"></span>
                    </div>
                    <div>
                        <p class="text-[12px] font-medium text-slate-400">Foto profil kelola akun</p>
                        <p class="text-[13.5px] font-semibold text-slate-800 truncate">{{ $name }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
