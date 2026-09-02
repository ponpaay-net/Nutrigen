@extends('layouts.app')

@section('page-title', 'Edit Profil Kader')

@section('content')
@php
    $ini = strtoupper(collect(explode(' ', $name ?? 'Kader'))->map(fn($w) => mb_substr($w, 0, 1))->take(2)->implode(''));
@endphp

<div class="w-full pb-28 sm:pb-12">
    <div class="max-w-4xl mx-auto">

        {{-- Page header --}}
        <div class="mb-5 flex items-center justify-between">
            <a href="{{ route('kader.profil') }}" class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-slate-500 hover:text-teal-700 transition-colors"><x-icon name="arrow-left" weight="bold" class="text-[15px]" />Kembali ke Profil</a>
        </div>

        <div class="mb-6">
            <div class="flex items-center gap-3">
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
            {{-- FORM (identitas header + field) --}}
            <div class="lg:col-span-2">
                <form action="{{ route('kader.profil.update') }}" method="POST" class="bg-white ring-1 ring-slate-200/70 rounded-2xl shadow-sm shadow-slate-200/60 overflow-hidden">
                    @csrf
                    @method('PUT')

                    {{-- Identitas header (avatar + nama + role) --}}
                    <div class="flex items-center gap-4 p-6 sm:p-8 border-b border-slate-100">
                        <div class="relative shrink-0">
                            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-teal-600 text-white flex items-center justify-center font-bold text-xl sm:text-2xl ring-2 ring-white shadow-md shadow-teal-700/30">{{ $ini }}</div>
                            <span class="absolute bottom-0 right-0 w-4 h-4 rounded-full bg-emerald-500 ring-2 ring-white"></span>
                        </div>
                        <div class="min-w-0">
                            <h2 class="text-lg font-bold text-slate-900 leading-tight truncate">{{ $name ?? '-' }}</h2>
                            <p class="text-[13px] text-slate-500 mt-0.5">Kader Posyandu · {{ $posyanduName }}</p>
                        </div>
                    </div>

                    {{-- Seksi Informasi Kader (seimbang, satu seksi) --}}
                    <div class="p-6 sm:p-8">
                        <h3 class="text-[13px] font-bold text-slate-800 flex items-center gap-2 mb-4">
                            <span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center"><x-icon name="identification-card" weight="bold" class="text-[16px]" /></span>Informasi Kader
                        </h3>

                        <div class="flex flex-col gap-2">
                            <label for="nama" class="text-[12.5px] font-semibold text-slate-700">Nama Lengkap Kader <span class="text-rose-500">*</span></label>
                            <input type="text" id="nama" name="nama" value="{{ old('nama', $name ?? '') }}" required placeholder="Contoh: Ibu Siti Aminah"
                                class="w-full bg-slate-50 border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-medium rounded-xl px-4 py-3 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all placeholder:text-slate-400">
                            @error('nama') <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex flex-col gap-2 mt-5">
                            <label for="no_hp" class="text-[12.5px] font-semibold text-slate-700">Nomor Telepon / WhatsApp <span class="text-rose-500">*</span></label>
                            <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp', $phone ?? '') }}" required placeholder="Contoh: 081234567890"
                                class="w-full bg-slate-50 border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-medium rounded-xl px-4 py-3 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all placeholder:text-slate-400">
                            <p class="text-[11.5px] text-slate-400 font-medium">Dipakai untuk notifikasi jadwal posyandu dan koordinasi dengan ibu balita.</p>
                            @error('no_hp') <p class="text-xs text-rose-500 font-semibold">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex flex-col gap-2 mt-5">
                            <label class="text-[12.5px] font-semibold text-slate-700">Alamat Email Akun</label>
                            <input type="email" value="{{ $email ?? '' }}" readonly disabled
                                class="w-full bg-slate-100/80 border border-slate-200 text-slate-500 text-[14px] font-medium rounded-xl px-4 py-3 cursor-not-allowed select-none outline-none">
                            <p class="text-[11.5px] text-slate-400 font-medium flex items-center gap-1.5"><x-icon name="lock" weight="bold" class="text-[12px]" />Alamat email login dikelola resmi oleh admin Puskesmas demi keamanan data.</p>
                        </div>
                    </div>

                    {{-- Actions (Batal = teks, Simpan = teal primary) --}}
                    <div class="px-6 sm:px-8 py-5 border-t border-slate-100 bg-slate-50/40 flex items-center gap-3">
                        <a href="{{ route('kader.profil') }}" class="inline-flex items-center justify-center h-11 px-4 text-[13.5px] font-semibold text-slate-500 hover:text-slate-700 transition-colors">Batal</a>
                        <button type="submit" class="flex-1 sm:flex-none ml-auto inline-flex items-center justify-center gap-2 h-11 px-6 rounded-xl bg-teal-600 hover:bg-teal-500 active:bg-teal-700 text-white text-[13.5px] font-bold shadow-sm shadow-teal-600/25 transition-all active:scale-[0.98]"><x-icon name="check-circle" weight="bold" class="text-[16px]" />Simpan Perubahan</button>
                    </div>
                </form>
            </div>

            {{-- RIGHT RAIL (ringkas: penugasan + bantuan) --}}
            <div class="flex flex-col gap-5">
                <div class="bg-white ring-1 ring-slate-200/70 rounded-2xl shadow-sm shadow-slate-200/50 p-5 sm:p-6">
                    <h2 class="text-[13px] font-bold text-slate-800 flex items-center gap-2 mb-4"><span class="w-8 h-8 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center"><x-icon name="map-pin" weight="bold" class="text-[16px]" /></span>Area Penugasan</h2>
                    <div class="flex flex-col gap-3">
                        <div class="flex items-center gap-3.5 bg-slate-50 border border-slate-100 p-3.5 rounded-xl">
                            <span class="w-10 h-10 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center shrink-0"><x-icon name="building" weight="bold" class="text-[18px]" /></span>
                            <div class="min-w-0">
                                <p class="text-[11px] font-medium text-slate-400">Posyandu Induk</p>
                                <p class="text-[13.5px] font-semibold text-slate-800 break-words">{{ $posyanduName }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3.5 bg-slate-50 border border-slate-100 p-3.5 rounded-xl">
                            <span class="w-10 h-10 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center shrink-0"><x-icon name="cross" weight="regular" class="text-[18px]" /></span>
                            <div class="min-w-0">
                                <p class="text-[11px] font-medium text-slate-400">Puskesmas Pembina</p>
                                <p class="text-[13.5px] font-semibold text-slate-800 break-words">{{ $puskesmasName }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-teal-50 border border-teal-100 rounded-2xl p-5 sm:p-6">
                    <h2 class="text-[13px] font-bold text-teal-800 flex items-center gap-2 mb-2.5"><span class="w-7 h-7 rounded-lg bg-teal-100 text-teal-700 flex items-center justify-center"><x-icon name="info" weight="bold" class="text-[16px]" /></span>Perubahan Data</h2>
                    <p class="text-[12.5px] text-teal-900/80 leading-relaxed">Perubahan wilayah posyandu tugas dan alamat email induk hanya melalui koordinator Bidan Pembina di Puskesmas.</p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
