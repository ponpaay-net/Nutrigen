@extends('layouts.puskesmas')
@section('page-title', 'Pengaturan Notifikasi')
@section('page-breadcrumbs', 'Notifikasi Sistem')
@section('page-mode', 'app')
@section('content')

{{-- Backend Contract:
    Controller: PuskesmasController@notifikasi & updateNotifikasi
--}}

<div class="flex flex-col lg:flex-row flex-1 overflow-hidden" x-data="{
    alertStunting: true,
    alertValidasi: true,
    emailDigest: false
}">

    <!-- RIGHT PANEL: Settings Canvas -->
    <div class="flex-1 flex flex-col overflow-y-auto bg-slate-50/50 relative">
        
        <!-- Colorful Vibrant Header -->
        <div class="shrink-0 px-6 sm:px-10 py-10 bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-600 text-white relative overflow-hidden shadow-sm">
            <!-- Decorative elements -->
            <div class="absolute -right-16 -top-16 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-10 bottom-0 opacity-10 pointer-events-none translate-y-1/4">
                <i class="ph-fill ph-bell-ringing text-9xl"></i>
            </div>
            
            <div class="relative z-10 max-w-4xl mx-auto">
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Notifikasi Sistem</h1>
                <p class="text-[13px] text-teal-50 font-medium max-w-xl mt-1.5 leading-relaxed">Atur preferensi peringatan dini kasus gizi balita, antrean validasi data, dan rekapitulasi posyandu.</p>
                
                <x-puskesmas.settings-tabs active="notifikasi" />
            </div>
        </div>

        <div class="flex-1 p-6 sm:px-10 py-8 relative">
            <div class="max-w-4xl w-full mx-auto relative z-10 mt-0 lg:-mt-10">
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

                    <!-- SECTION: Notification Preferences Card -->
                    <div class="bg-white border border-slate-200 rounded-2xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                        <!-- Header Card -->
                        <div class="px-6 lg:px-8 py-6 border-b border-slate-100 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 border border-teal-100 shadow-sm">
                                    <i class="ph-bold ph-broadcast text-2xl"></i>
                                </div>
                                <div>
                                    <h2 class="text-[16px] font-extrabold tracking-tight text-slate-900">Preferensi Kanal Peringatan</h2>
                                    <p class="text-[12px] font-medium text-slate-500 mt-0.5">Tentukan bagaimana sistem memberikan pemberitahuan operasional.</p>
                                </div>
                            </div>
                            <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-[13px] font-bold rounded-xl transition-all shadow-sm shadow-teal-500/30 flex items-center justify-center gap-2 focus:outline-none">
                                <i class="ph-bold ph-check"></i>
                                Simpan Preferensi
                            </button>
                        </div>

                        <!-- Body Card: Toggle List -->
                        <div class="p-6 lg:p-8 flex flex-col gap-4">
                            
                            <!-- Toggle 1: Peringatan Kasus Stunting -->
                            <div @click="alertStunting = !alertStunting" 
                                 class="flex items-start sm:items-center justify-between gap-5 p-5 rounded-xl border transition-all cursor-pointer select-none"
                                 :class="alertStunting ? 'bg-rose-50/40 border-rose-200 ring-1 ring-rose-300/30' : 'bg-slate-50/60 border-slate-200 hover:bg-slate-100/60'">
                                <div class="flex items-start sm:items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 border shadow-2xs transition-colors"
                                         :class="alertStunting ? 'bg-white border-rose-200 text-rose-600' : 'bg-white border-slate-200 text-slate-400'">
                                        <i class="ph-bold ph-warning-octagon text-2xl"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-[15px] font-bold text-slate-900">Peringatan Kasus Stunting & Gizi Buruk</h3>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wide bg-rose-100 text-rose-800">Kritis</span>
                                        </div>
                                        <p class="text-[13px] text-slate-500 font-medium mt-0.5">Notifikasi instan saat kader mengirimkan data anak dengan indikasi stunting atau gizi buruk untuk intervensi cepat.</p>
                                    </div>
                                </div>
                                <input type="hidden" name="alert_stunting" :value="alertStunting ? 1 : 0">
                                <button type="button" 
                                        role="switch" 
                                        :aria-checked="alertStunting"
                                        class="shrink-0 relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none"
                                        :class="alertStunting ? 'bg-teal-600' : 'bg-slate-300'">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform shadow-xs"
                                          :class="alertStunting ? 'translate-x-6' : 'translate-x-1'"></span>
                                </button>
                            </div>

                            <!-- Toggle 2: Antrean Validasi Data Baru -->
                            <div @click="alertValidasi = !alertValidasi" 
                                 class="flex items-start sm:items-center justify-between gap-5 p-5 rounded-xl border transition-all cursor-pointer select-none"
                                 :class="alertValidasi ? 'bg-teal-50/40 border-teal-200 ring-1 ring-teal-300/30' : 'bg-slate-50/60 border-slate-200 hover:bg-slate-100/60'">
                                <div class="flex items-start sm:items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 border shadow-2xs transition-colors"
                                         :class="alertValidasi ? 'bg-white border-teal-200 text-teal-600' : 'bg-white border-slate-200 text-slate-400'">
                                        <i class="ph-bold ph-check-square-offset text-2xl"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-[15px] font-bold text-slate-900">Antrean Validasi Data Masuk</h3>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wide bg-teal-100 text-teal-800">Operasional</span>
                                        </div>
                                        <p class="text-[13px] text-slate-500 font-medium mt-0.5">Tampilkan indikator badge dan pemberitahuan berkala jika terdapat penimbangan baru dari posyandu yang menunggu review.</p>
                                    </div>
                                </div>
                                <input type="hidden" name="alert_validasi" :value="alertValidasi ? 1 : 0">
                                <button type="button" 
                                        role="switch" 
                                        :aria-checked="alertValidasi"
                                        class="shrink-0 relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none"
                                        :class="alertValidasi ? 'bg-teal-600' : 'bg-slate-300'">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform shadow-xs"
                                          :class="alertValidasi ? 'translate-x-6' : 'translate-x-1'"></span>
                                </button>
                            </div>

                            <!-- Toggle 3: Rekapitulasi Mingguan Email -->
                            <div @click="emailDigest = !emailDigest" 
                                 class="flex items-start sm:items-center justify-between gap-5 p-5 rounded-xl border transition-all cursor-pointer select-none"
                                 :class="emailDigest ? 'bg-teal-50/40 border-teal-200 ring-1 ring-teal-300/30' : 'bg-slate-50/60 border-slate-200 hover:bg-slate-100/60'">
                                <div class="flex items-start sm:items-center gap-4">
                                    <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 border shadow-2xs transition-colors"
                                         :class="emailDigest ? 'bg-white border-teal-200 text-teal-600' : 'bg-white border-slate-200 text-slate-400'">
                                        <i class="ph-bold ph-envelope-simple-open text-2xl"></i>
                                    </div>
                                    <div>
                                        <div class="flex items-center gap-2">
                                            <h3 class="text-[15px] font-bold text-slate-900">Rekapitulasi Mingguan via Email</h3>
                                            <span class="px-2 py-0.5 rounded text-[10px] font-extrabold uppercase tracking-wide bg-slate-200 text-slate-700">Opsional</span>
                                        </div>
                                        <p class="text-[13px] text-slate-500 font-medium mt-0.5">Kirim ringkasan capaian pengukuran seluruh posyandu dan tren stunting ke email faskes setiap akhir pekan.</p>
                                    </div>
                                </div>
                                <input type="hidden" name="email_digest" :value="emailDigest ? 1 : 0">
                                <button type="button" 
                                        role="switch" 
                                        :aria-checked="emailDigest"
                                        class="shrink-0 relative inline-flex h-6 w-11 items-center rounded-full transition-colors focus:outline-none"
                                        :class="emailDigest ? 'bg-teal-600' : 'bg-slate-300'">
                                    <span class="inline-block h-4 w-4 transform rounded-full bg-white transition-transform shadow-xs"
                                          :class="emailDigest ? 'translate-x-6' : 'translate-x-1'"></span>
                                </button>
                            </div>

                        </div>
                    </div>

                    <!-- INFO FOOTER -->
                    <div class="bg-blue-50/50 border border-blue-200 rounded-2xl p-5 flex items-start gap-4 shadow-xs">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center shrink-0 text-blue-600">
                            <i class="ph-bold ph-info text-lg"></i>
                        </div>
                        <p class="text-[13px] font-medium text-blue-800 leading-relaxed pt-1">
                            Notifikasi sistem ini disinkronkan secara real-time dengan aplikasi kader Posyandu saat data penimbangan dikirimkan.
                        </p>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
