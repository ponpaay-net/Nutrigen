@extends('layouts.puskesmas')
@section('page-title', 'Pengaturan Notifikasi')
@section('page-breadcrumbs')
    Pengaturan 
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 text-[#CBD5E1]">
        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
    </svg>
    Notifikasi
@endsection
@section('page-mode', 'app')
@section('content')

<!-- Full-viewport Split View: Notifikasi Management -->
<div class="flex flex-col lg:flex-row flex-1 overflow-hidden" x-data="{ 
    editMode: false,
}">

    <!-- LEFT PANEL: Settings Navigation -->
    <x-puskesmas.settings-sidebar active="notifikasi" />

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

            <form method="POST" action="{{ route('puskesmas.pengaturan.notifikasi.update') }}" class="flex flex-col gap-6 lg:gap-8">
                @csrf
                @method('PUT')

                <!-- PROFILE BANNER -->
                <div class="relative bg-gradient-to-br from-indigo-600 via-indigo-700 to-indigo-900 rounded-[32px] p-6 sm:p-8 shadow-md overflow-hidden">
                    <!-- Background Decorations -->
                    <div class="absolute -right-10 -top-10 w-64 h-64 bg-indigo-400/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute left-0 bottom-0 w-40 h-40 bg-indigo-900/40 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute inset-0 opacity-[0.05] pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
                    
                    <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-6 sm:gap-8">
                        <!-- Bell Icon Container -->
                        <div class="w-20 h-20 sm:w-24 sm:h-24 rounded-2xl bg-white/10 p-1.5 backdrop-blur-md shadow-inner shrink-0 group relative overflow-hidden">
                            <div class="w-full h-full rounded-xl bg-indigo-500/20 text-indigo-100 flex items-center justify-center overflow-hidden border border-white/10">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-10 h-10 group-hover:rotate-12 transition-transform duration-300">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                                </svg>
                            </div>
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-1 text-center sm:text-left mt-1">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-white text-[11px] font-medium tracking-wide mb-3 shadow-sm backdrop-blur-sm">
                                Preferensi Personal
                            </div>
                            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight leading-tight drop-shadow-sm mb-1.5">Pusat Notifikasi</h1>
                            <p class="text-indigo-100/80 text-[14px] font-medium max-w-lg leading-relaxed">
                                Atur bagaimana Anda ingin menerima pemberitahuan mengenai aktivitas kader, antrean validasi, dan laporan gizi dari sistem.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- SECTION: Edit Form -->
                <div class="bg-white/80 backdrop-blur-xl border border-slate-200/60 rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                    
                    <!-- Header Card -->
                    <div class="px-6 sm:px-8 py-5 border-b border-slate-100 bg-white/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100/50">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.34 15.84c-.688-.06-1.386-.09-2.09-.09H7.5a4.5 4.5 0 110-9h.75c.704 0 1.402-.03 2.09-.09m0 9.18c.253.962.584 1.892.985 2.783.247.55.06 1.21-.463 1.511l-.657.38c-.551.318-1.26.117-1.527-.461a20.845 20.845 0 01-1.44-4.282m3.102.069a18.03 18.03 0 01-.59-4.59c0-1.586.205-3.124.59-4.59m0 9.18a23.848 23.848 0 018.835 2.535M10.34 6.66a23.847 23.847 0 008.835-2.535m0 0A23.74 23.74 0 0018.795 3m.38 1.125a23.91 23.91 0 011.014 5.395m-1.014-8.81c-2.868-1.1-5.809-2.046-8.835-2.535m11.647 1.597a24.39 24.39 0 012.168 3.043m-2.168-3.043c.27-.406.84-.52 1.26-.263l.658.404c.484.298.663.921.408 1.41a20.814 20.814 0 01-1.353 2.19m0 0a24.23 24.23 0 01-1.082 1.543m0 0a24.23 24.23 0 01-1.636 1.895M19.175 4.125A24.24 24.24 0 0121 7.168m-1.825-3.043c.27-.406.84-.52 1.26-.263l.658.404c.484.298.663.921.408 1.41a20.814 20.814 0 01-1.353 2.19" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold tracking-tight text-slate-800">Kanal Pemberitahuan</h3>
                                <p class="text-xs font-medium text-slate-500 mt-0.5">Pilih cara sistem menghubungi Anda terkait aktivitas terbaru.</p>
                            </div>
                        </div>
                        <div class="mt-4 sm:mt-0">
                            <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-slate-900 text-white text-[13px] font-bold rounded-xl shadow-[0_4px_15px_rgba(0,0,0,0.1)] hover:shadow-[0_6px_20px_rgba(0,0,0,0.15)] hover:bg-slate-800 transition-all flex items-center justify-center">
                                Simpan Preferensi
                            </button>
                        </div>
                    </div>

                    <!-- Body Card -->
                    <div class="p-6 sm:p-8 flex flex-col gap-4">
                        
                        <!-- Toggle 1 -->
                        <label class="flex items-start sm:items-center justify-between gap-5 p-4 rounded-2xl bg-white border border-slate-100 hover:border-slate-200 transition-colors cursor-pointer group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center shrink-0 border border-slate-200/60 group-hover:bg-indigo-50 group-hover:text-indigo-600 group-hover:border-indigo-200 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-[15px] font-bold text-slate-800">Email Digest Mingguan</h4>
                                    <p class="text-[13px] text-slate-500 font-medium mt-0.5">Kirim ringkasan laporan operasional posyandu ke email petugas setiap akhir pekan.</p>
                                </div>
                            </div>
                            <div class="shrink-0 pt-2 sm:pt-0">
                                <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input type="checkbox" name="email_digest" id="toggle1" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" checked/>
                                    <label for="toggle1" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-200 cursor-pointer"></label>
                                </div>
                            </div>
                        </label>

                        <!-- Toggle 2 -->
                        <label class="flex items-start sm:items-center justify-between gap-5 p-4 rounded-2xl bg-white border border-slate-100 hover:border-slate-200 transition-colors cursor-pointer group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center shrink-0 border border-slate-200/60 group-hover:bg-indigo-50 group-hover:text-indigo-600 group-hover:border-indigo-200 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-[15px] font-bold text-slate-800">Peringatan Kasus Stunting</h4>
                                    <p class="text-[13px] text-slate-500 font-medium mt-0.5">Terima notifikasi darurat saat kader mengirimkan data balita dengan status Stunting atau Gizi Buruk.</p>
                                </div>
                            </div>
                            <div class="shrink-0 pt-2 sm:pt-0">
                                <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input type="checkbox" name="alert_stunting" id="toggle2" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" checked/>
                                    <label for="toggle2" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-200 cursor-pointer"></label>
                                </div>
                            </div>
                        </label>

                        <!-- Toggle 3 -->
                        <label class="flex items-start sm:items-center justify-between gap-5 p-4 rounded-2xl bg-white border border-slate-100 hover:border-slate-200 transition-colors cursor-pointer group">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-500 flex items-center justify-center shrink-0 border border-slate-200/60 group-hover:bg-indigo-50 group-hover:text-indigo-600 group-hover:border-indigo-200 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="text-[15px] font-bold text-slate-800">Laporan Aktivitas Kader</h4>
                                    <p class="text-[13px] text-slate-500 font-medium mt-0.5">Beritahu saya setiap kali kader melakukan aktivitas penambahan data dalam jumlah masif di sistem.</p>
                                </div>
                            </div>
                            <div class="shrink-0 pt-2 sm:pt-0">
                                <div class="relative inline-block w-12 mr-2 align-middle select-none transition duration-200 ease-in">
                                    <input type="checkbox" name="alert_kader" id="toggle3" class="toggle-checkbox absolute block w-6 h-6 rounded-full bg-white border-4 appearance-none cursor-pointer" />
                                    <label for="toggle3" class="toggle-label block overflow-hidden h-6 rounded-full bg-slate-200 cursor-pointer"></label>
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
        border-color: #0d9488;
    }
    .toggle-checkbox:checked + .toggle-label {
        background-color: #0d9488;
    }
    .toggle-checkbox {
        right: 24px;
        z-index: 1;
        border-color: #e2e8f0;
        transition: all 0.3s;
    }
    .toggle-label {
        transition: all 0.3s;
    }
</style>
@endpush

@endsection
