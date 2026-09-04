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

            <form method="POST" action="{{ route('puskesmas.pengaturan.notifikasi.update') }}" class="flex flex-col gap-6">
                @csrf
                @method('PUT')

                <!-- PROFILE BANNER -->
                <div class="bg-gradient-to-br from-slate-800 to-indigo-900 rounded-2xl p-8 sm:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.08)] flex flex-col sm:flex-row items-center sm:items-start gap-6 sm:gap-8 relative overflow-hidden">
                    <!-- Decorative Elements -->
                    <div class="absolute -right-10 -top-10 w-64 h-64 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute right-10 bottom-0 opacity-10 pointer-events-none translate-y-1/4">
                        <i class="ph-fill ph-bell-ringing text-9xl text-white"></i>
                    </div>

                    <!-- Bell Icon Container -->
                    <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-2xl bg-white/10 p-2 shrink-0 group relative overflow-hidden flex items-center justify-center text-indigo-300 border border-white/20 backdrop-blur-sm z-10 shadow-inner">
                        <i class="ph-bold ph-bell-ringing text-6xl group-hover:rotate-12 transition-transform duration-300"></i>
                    </div>
                    
                    <!-- Info -->
                    <div class="flex-1 text-center sm:text-left mt-2 z-10">
                        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg bg-indigo-900/40 border border-indigo-500/30 text-indigo-300 text-[10px] font-bold tracking-widest uppercase mb-3 backdrop-blur-sm">
                            <i class="ph-bold ph-sliders"></i>
                            Preferensi Personal
                        </div>
                        <h1 class="text-3xl sm:text-4xl font-extrabold text-white tracking-tight leading-tight mb-2 drop-shadow-sm">Pusat Notifikasi</h1>
                        <p class="text-indigo-100/80 text-[14px] font-medium max-w-lg leading-relaxed">
                            Atur bagaimana Anda ingin menerima pemberitahuan mengenai aktivitas kader, antrean validasi, dan laporan gizi dari sistem.
                        </p>
                    </div>
                </div>

                <!-- SECTION: Edit Form -->
                <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                    
                    <!-- Header Card -->
                    <div class="px-6 lg:px-8 py-6 border-b border-slate-100 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 border border-teal-100 shadow-sm">
                                <i class="ph-bold ph-broadcast text-2xl"></i>
                            </div>
                            <div>
                                <h3 class="text-[16px] font-extrabold tracking-tight text-slate-900">Kanal Pemberitahuan</h3>
                                <p class="text-[12px] font-medium text-slate-500 mt-0.5">Pilih cara sistem menghubungi Anda terkait aktivitas terbaru.</p>
                            </div>
                        </div>
                        <div class="mt-2 sm:mt-0">
                            <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-teal-600 text-white text-[13px] font-bold rounded-xl hover:bg-teal-700 transition-colors flex items-center justify-center shadow-sm shadow-teal-500/30 focus:outline-none focus:ring-4 focus:ring-teal-500/20">
                                Simpan Preferensi
                            </button>
                        </div>
                    </div>

                    <!-- Body Card -->
                    <div class="p-6 lg:p-8 flex flex-col gap-5">
                        
                        <!-- Toggle 1 -->
                        <label class="flex items-start sm:items-center justify-between gap-5 p-5 rounded-xl bg-slate-50/50 border border-slate-200 hover:border-teal-300 hover:bg-teal-50/30 transition-colors cursor-pointer group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-white text-slate-500 flex items-center justify-center shrink-0 border border-slate-200 shadow-sm group-hover:text-teal-600 group-hover:border-teal-200 transition-colors">
                                    <i class="ph-bold ph-envelope-simple-open text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-[15px] font-bold text-slate-900">Rekap Laporan Mingguan</h4>
                                    <p class="text-[13px] text-slate-500 font-medium mt-0.5">Kirim ringkasan laporan operasional posyandu ke email petugas setiap akhir pekan.</p>
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
                        <label class="flex items-start sm:items-center justify-between gap-5 p-5 rounded-xl bg-slate-50/50 border border-slate-200 hover:border-teal-300 hover:bg-teal-50/30 transition-colors cursor-pointer group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-white text-slate-500 flex items-center justify-center shrink-0 border border-slate-200 shadow-sm group-hover:text-amber-600 group-hover:border-amber-200 transition-colors">
                                    <i class="ph-bold ph-warning-circle text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-[15px] font-bold text-slate-900">Peringatan Kasus Stunting</h4>
                                    <p class="text-[13px] text-slate-500 font-medium mt-0.5">Terima notifikasi darurat saat kader mengirimkan data balita dengan status Stunting atau Gizi Buruk.</p>
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
                        <label class="flex items-start sm:items-center justify-between gap-5 p-5 rounded-xl bg-slate-50/50 border border-slate-200 hover:border-teal-300 hover:bg-teal-50/30 transition-colors cursor-pointer group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-white text-slate-500 flex items-center justify-center shrink-0 border border-slate-200 shadow-sm group-hover:text-indigo-600 group-hover:border-indigo-200 transition-colors">
                                    <i class="ph-bold ph-users text-xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-[15px] font-bold text-slate-900">Laporan Aktivitas Kader</h4>
                                    <p class="text-[13px] text-slate-500 font-medium mt-0.5">Beritahu saya setiap kali kader melakukan aktivitas penambahan data dalam jumlah masif di sistem.</p>
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
        border-color: #0d9488; /* teal-600 */
    }
    .toggle-checkbox:checked + .toggle-label {
        background-color: #0d9488;
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
