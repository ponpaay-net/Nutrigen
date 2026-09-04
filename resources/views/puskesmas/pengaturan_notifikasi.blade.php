@extends('layouts.puskesmas')
@section('page-title', 'Pengaturan Notifikasi')
@section('page-breadcrumbs', 'Notifikasi')
@section('page-mode', 'app')
@section('content')

<!-- Full-viewport Split View: Notifikasi Management -->
<div class="flex flex-col lg:flex-row flex-1 overflow-hidden" x-data="{ 
    editMode: false,
}">

    <!-- LEFT PANEL: Settings Navigation -->
    <x-puskesmas.settings-sidebar active="notifikasi" />

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

            <form method="POST" action="{{ route('puskesmas.pengaturan.notifikasi.update') }}" class="flex flex-col gap-6">
                @csrf
                @method('PUT')

                <!-- PROFILE BANNER -->
                <div class="bg-indigo-700 rounded-xl p-6 sm:p-8 shadow-sm flex flex-col sm:flex-row items-center sm:items-start gap-6 sm:gap-8">
                    <!-- Bell Icon Container -->
                    <div class="w-20 h-20 sm:w-24 sm:h-24 rounded bg-indigo-800 p-1.5 shrink-0 group relative overflow-hidden flex items-center justify-center text-indigo-300 border border-indigo-600">
                        <i class="ph-bold ph-bell-ringing text-4xl group-hover:rotate-12 transition-transform duration-300"></i>
                    </div>
                    
                    <!-- Info -->
                    <div class="flex-1 text-center sm:text-left mt-1">
                        <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded bg-indigo-900/50 border border-indigo-600/50 text-indigo-100 text-[10px] font-bold tracking-widest uppercase mb-3">
                            <i class="ph-bold ph-sliders"></i>
                            Preferensi Personal
                        </div>
                        <h1 class="text-2xl sm:text-3xl font-black text-white tracking-tight leading-tight mb-1.5">Pusat Notifikasi</h1>
                        <p class="text-indigo-100 text-[13px] font-medium max-w-lg leading-relaxed">
                            Atur bagaimana Anda ingin menerima pemberitahuan mengenai aktivitas kader, antrean validasi, dan laporan gizi dari sistem.
                        </p>
                    </div>
                </div>

                <!-- SECTION: Edit Form -->
                <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
                    
                    <!-- Header Card -->
                    <div class="px-6 py-5 border-b border-slate-200 bg-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-200">
                                <i class="ph-bold ph-broadcast text-xl"></i>
                            </div>
                            <div>
                                <h3 class="text-[15px] font-bold tracking-tight text-slate-900">Kanal Pemberitahuan</h3>
                                <p class="text-[12px] font-medium text-slate-500 mt-0.5">Pilih cara sistem menghubungi Anda terkait aktivitas terbaru.</p>
                            </div>
                        </div>
                        <div class="mt-2 sm:mt-0">
                            <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-indigo-600 text-white text-[12px] font-bold rounded-lg hover:bg-indigo-700 transition-colors flex items-center justify-center shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1">
                                Simpan Preferensi
                            </button>
                        </div>
                    </div>

                    <!-- Body Card -->
                    <div class="p-6 flex flex-col gap-4">
                        
                        <!-- Toggle 1 -->
                        <label class="flex items-start sm:items-center justify-between gap-5 p-4 rounded-lg bg-slate-50 border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/30 transition-colors cursor-pointer group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded bg-white text-slate-500 flex items-center justify-center shrink-0 border border-slate-200 group-hover:text-indigo-600 group-hover:border-indigo-200 transition-colors">
                                    <i class="ph-bold ph-envelope-simple-open text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-[14px] font-bold text-slate-900">Email Digest Mingguan</h4>
                                    <p class="text-[12px] text-slate-500 font-medium mt-0.5">Kirim ringkasan laporan operasional posyandu ke email petugas setiap akhir pekan.</p>
                                </div>
                            </div>
                            <div class="shrink-0 pt-2 sm:pt-0">
                                <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input type="checkbox" name="email_digest" id="toggle1" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" checked/>
                                    <label for="toggle1" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-300 cursor-pointer"></label>
                                </div>
                            </div>
                        </label>

                        <!-- Toggle 2 -->
                        <label class="flex items-start sm:items-center justify-between gap-5 p-4 rounded-lg bg-slate-50 border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/30 transition-colors cursor-pointer group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded bg-white text-slate-500 flex items-center justify-center shrink-0 border border-slate-200 group-hover:text-indigo-600 group-hover:border-indigo-200 transition-colors">
                                    <i class="ph-bold ph-warning-circle text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-[14px] font-bold text-slate-900">Peringatan Kasus Stunting</h4>
                                    <p class="text-[12px] text-slate-500 font-medium mt-0.5">Terima notifikasi darurat saat kader mengirimkan data balita dengan status Stunting atau Gizi Buruk.</p>
                                </div>
                            </div>
                            <div class="shrink-0 pt-2 sm:pt-0">
                                <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input type="checkbox" name="alert_stunting" id="toggle2" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" checked/>
                                    <label for="toggle2" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-300 cursor-pointer"></label>
                                </div>
                            </div>
                        </label>

                        <!-- Toggle 3 -->
                        <label class="flex items-start sm:items-center justify-between gap-5 p-4 rounded-lg bg-slate-50 border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/30 transition-colors cursor-pointer group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded bg-white text-slate-500 flex items-center justify-center shrink-0 border border-slate-200 group-hover:text-indigo-600 group-hover:border-indigo-200 transition-colors">
                                    <i class="ph-bold ph-users text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-[14px] font-bold text-slate-900">Laporan Aktivitas Kader</h4>
                                    <p class="text-[12px] text-slate-500 font-medium mt-0.5">Beritahu saya setiap kali kader melakukan aktivitas penambahan data dalam jumlah masif di sistem.</p>
                                </div>
                            </div>
                            <div class="shrink-0 pt-2 sm:pt-0">
                                <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input type="checkbox" name="alert_kader" id="toggle3" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                                    <label for="toggle3" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-300 cursor-pointer"></label>
                                </div>
                            </div>
                        </label>

                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    /* Toggle switch styles */
    .toggle-checkbox:checked {
        right: 0;
        border-color: #4f46e5;
    }
    .toggle-checkbox:checked + .toggle-label {
        background-color: #4f46e5;
    }
    .toggle-checkbox {
        right: 24px;
        z-index: 1;
        border-color: #cbd5e1;
        transition: all 0.3s;
    }
    .toggle-label {
        transition: all 0.3s;
    }
</style>
@endpush

@endsection
