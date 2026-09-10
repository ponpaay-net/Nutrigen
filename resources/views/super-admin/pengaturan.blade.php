@extends('layouts.app')

@section('page-title', 'Pengaturan Sistem — NutriGen')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full min-h-screen" style="background:#F7F8FA; font-family:'Plus Jakarta Sans',sans-serif;">

    {{-- Breadcrumb --}}
    <nav class="flex text-sm font-medium text-slate-500 mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center">
            <li><a href="{{ route('super-admin.dashboard') }}" class="inline-flex items-center hover:text-teal-600 transition-colors"><x-icon name="squares-four" weight="fill" class="text-base mr-1.5" />Dashboard</a></li>
            <li class="flex items-center"><x-icon name="caret-right" weight="bold" class="text-sm mx-1.5" /><span class="text-slate-900 font-bold">Pengaturan Sistem</span></li>
        </ol>
    </nav>

    {{-- Header --}}
    <div class="flex items-center gap-4 mb-8 bg-white p-6 rounded-2xl shadow-sm border border-slate-100">
        <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0"><x-icon name="gear-six" weight="fill" class="w-6 h-6" /></div>
        <div>
            <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Pengaturan Sistem Nasional</h1>
            <p class="text-sm text-slate-500 mt-1">Profil instansi dan ambang batas pemantauan gizi.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 rounded-xl bg-teal-50 border border-teal-200 px-4 py-3 text-sm font-semibold text-teal-900 flex items-center gap-2.5">
            <x-icon name="check-circle" weight="fill" class="text-lg text-teal-600" /> {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('super-admin.pengaturan.update') }}" method="POST" class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        @csrf
        @method('PUT')

        <div class="p-6 sm:p-8 space-y-6">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Profil Instansi</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Institusi <span class="text-rose-500">*</span></label>
                        <input type="text" name="institution_name" value="{{ old('institution_name', $settings['institution_name']) }}" required
                               class="block w-full border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5">
                        @error('institution_name') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Unit / Direktorat</label>
                        <input type="text" name="institution_unit" value="{{ old('institution_unit', $settings['institution_unit']) }}"
                               class="block w-full border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5">
                        @error('institution_unit') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Email Kontak</label>
                        <input type="email" name="contact_email" value="{{ old('contact_email', $settings['contact_email']) }}"
                               class="block w-full border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5">
                        @error('contact_email') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="border-t border-slate-100 pt-6">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4">Pemantauan Gizi</p>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Ambang Waspada Prevalensi Stunting (%) <span class="text-rose-500">*</span></label>
                        <input type="number" step="0.1" min="0" max="100" name="stunting_threshold" value="{{ old('stunting_threshold', $settings['stunting_threshold']) }}" required
                               class="block w-full sm:w-48 border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5 tabular-nums">
                        <p class="text-[11px] text-slate-500 mt-1">Puskesmas dengan prevalensi di atas nilai ini akan ditandai "butuh perhatian" pada dashboard.</p>
                        @error('stunting_threshold') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Sumber Data</label>
                        <input type="text" name="data_source" value="{{ old('data_source', $settings['data_source']) }}"
                               class="block w-full border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5">
                        @error('data_source') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-slate-50 px-6 sm:px-8 py-4 flex justify-end">
            <button type="submit" class="inline-flex items-center gap-2 rounded-xl bg-teal-600 px-6 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-teal-700 transition-all active:scale-95">
                <x-icon name="floppy-disk" weight="bold" /> Simpan Pengaturan
            </button>
        </div>
    </form>
</div>
@endsection