@extends('layouts.app')

@section('page-title', 'Profil Kader')

{{--
|--------------------------------------------------------------------------
| kader.profil-kader
|--------------------------------------------------------------------------
| Controller contract — expected variables (from auth()->user() or KaderController@profil):
|   $kaderName     (string) — full name
|   $role          (string) — e.g. 'Kader Posyandu'
|   $email         (string)
|   $phone         (string)
|   $status        (string) — e.g. 'Aktif'
|   $avatarUrl     (string|null) — URL to profile photo, null = default SVG
|   $posyanduName  (string)
|   $desa          (string)
|   $puskesmas     (string)
|   $kecamatan     (string)
--}}

@section('content')

{{-- Script for Framer Motion --}}
<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        if(window.Motion) {
            const { animate, stagger, hover } = window.Motion;
            
            animate('.motion-card', 
                { opacity: [0, 1], y: [20, 0] }, 
                { delay: stagger(0.1), duration: 0.5, easing: "ease-out" }
            );

            document.querySelectorAll('.motion-hover').forEach(el => {
                hover(el, () => {
                    animate(el, { scale: 1.02, y: -2 }, { duration: 0.2 });
                    return () => animate(el, { scale: 1, y: 0 }, { duration: 0.2 });
                });
            });
        }
    });
</script>

<div class="w-full min-h-screen bg-slate-50/50 pb-20 lg:pb-12">
    <!-- HERO SECTION (Teal Gradient) -->
    <div class="relative bg-gradient-to-br from-teal-700 via-teal-600 to-teal-800 pt-8 pb-20 lg:pt-12 lg:pb-24 px-4 sm:px-6 lg:px-8 overflow-hidden lg:rounded-b-[40px] shadow-sm border-b border-teal-900/10">
        <div class="absolute -right-10 -top-10 w-64 h-64 bg-teal-400/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute left-0 bottom-0 w-40 h-40 bg-teal-900/40 rounded-full blur-2xl pointer-events-none"></div>
        <div class="absolute inset-0 opacity-[0.05] pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
        
        <div class="max-w-6xl mx-auto relative z-10 motion-card opacity-0 flex flex-col sm:flex-row gap-6 sm:gap-8 items-center sm:items-start text-center sm:text-left">
            <!-- Avatar -->
            <div class="relative group/avatar shrink-0">
                <div class="w-24 h-24 sm:w-32 sm:h-32 rounded-full p-1.5 bg-white/20 backdrop-blur-sm shadow-md flex items-center justify-center transition-transform duration-500 hover:scale-105 relative z-10 cursor-pointer">
                    <div class="w-full h-full rounded-full overflow-hidden bg-white text-slate-300 relative flex items-center justify-center">
                        @if(isset($avatarUrl))
                            <img src="{{ $avatarUrl }}" alt="Foto Kader" class="w-full h-full object-cover">
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-14 h-14 text-slate-300">
                                <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                            </svg>
                        @endif
                        <!-- Camera Overlay -->
                        <div class="absolute inset-0 bg-slate-900/40 flex items-center justify-center opacity-0 group-hover/avatar:opacity-100 transition-opacity backdrop-blur-[2px]">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-8 h-8 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" /></svg>
                        </div>
                    </div>
                </div>
                <!-- Status Indicator -->
                <div class="absolute bottom-1 right-1 sm:bottom-2 sm:right-2 z-20">
                    <div class="bg-emerald-400 w-5 h-5 rounded-full shadow-sm relative border-2 border-teal-700 ring-2 ring-emerald-400/30">
                        <div class="absolute inset-0 rounded-full bg-emerald-400 animate-ping opacity-40"></div>
                    </div>
                </div>
            </div>

            <!-- Profile Info -->
            <div class="flex flex-col flex-1 mt-2 sm:mt-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl lg:text-4xl font-black text-white tracking-tight drop-shadow-sm leading-tight">{{ $kaderName ?? 'Ibu Siti Aminah' }}</h1>
                        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-2 mt-3">
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white text-[13px] font-bold text-teal-800 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-teal-600"><path fill-rule="evenodd" d="M10 1a4.5 4.5 0 00-4.5 4.5V9H5a2 2 0 00-2 2v6a2 2 0 002 2h10a2 2 0 002-2v-6a2 2 0 00-2-2h-.5V5.5A4.5 4.5 0 0010 1zm3 8V5.5a3 3 0 10-6 0V9h6z" clip-rule="evenodd" /></svg>
                                {{ $role ?? 'Kader Posyandu' }}
                            </span>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white text-[13px] font-bold text-teal-800 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-teal-600"><path fill-rule="evenodd" d="M9.69 18.933l.003.001C9.89 19.02 10 19 10 19s.11.02.308-.066l.002-.001.006-.003.018-.008a5.741 5.741 0 00.281-.14c.186-.096.446-.24.757-.433.62-.384 1.445-.966 2.274-1.765C15.302 14.988 17 12.493 17 9A7 7 0 103 9c0 3.492 1.698 5.988 3.355 7.584a13.731 13.731 0 002.273 1.765 11.842 11.842 0 00.976.544l.062.029.018.008.006.003zM10 11.25a2.25 2.25 0 100-4.5 2.25 2.25 0 000 4.5z" clip-rule="evenodd" /></svg>
                                {{ $posyanduName ?? 'Posyandu Melati 1' }}
                            </span>
                        </div>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-2 flex-shrink-0 mt-2 sm:mt-0">
                        <a href="{{ route('kader.profil.edit') }}" class="group inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-teal-800 px-5 py-2.5 rounded-xl text-[14px] font-bold shadow-md transition-all focus:outline-none focus:ring-4 focus:ring-white/30 active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-teal-600 group-hover:scale-110 transition-transform">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                            </svg>
                            Edit Profil
                        </a>
                        <a href="{{ route('kader.keamanan') }}" class="group inline-flex items-center justify-center gap-2 bg-white/90 hover:bg-white text-teal-800 px-5 py-2.5 rounded-xl text-[14px] font-bold shadow-md transition-all focus:outline-none focus:ring-4 focus:ring-white/30 active:scale-95">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4 text-teal-600 group-hover:scale-110 transition-transform">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                            Keamanan Akun
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN WORKSPACE -->
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 -mt-10 lg:-mt-14 relative z-20 flex flex-col gap-6 lg:gap-8">
        
        <!-- Stats Row -->
        <div class="grid grid-cols-2 gap-3 lg:gap-4 motion-card opacity-0">
            <!-- Bergabung -->
            <div class="flex flex-col bg-white rounded-[24px] p-4 lg:p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-24 h-24 bg-slate-50 rounded-bl-full pointer-events-none -z-10 group-hover:scale-110 transition-transform"></div>
                <div class="w-10 h-10 rounded-2xl bg-teal-100 text-teal-600 flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path d="M12.75 12.75a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0zM12.75 19.5v-1.5a3 3 0 00-3-3h-4.5a3 3 0 00-3 3v1.5a.75.75 0 00.75.75h9a.75.75 0 00.75-.75zM22.5 12.75a5.25 5.25 0 11-10.5 0 5.25 5.25 0 0110.5 0zM22.5 19.5v-1.5a3 3 0 00-3-3h-4.5a3 3 0 00-3 3v1.5a.75.75 0 00.75.75h9a.75.75 0 00.75-.75z" /></svg>
                </div>
                <span class="text-[13px] font-semibold text-slate-500 mb-0.5">Bergabung Sejak</span>
                <span class="text-xl font-bold text-slate-800 tracking-tight leading-none mt-0.5">Jan 2024</span>
            </div>
            
            <!-- Balita -->
            <div class="flex flex-col bg-white rounded-[24px] p-4 lg:p-5 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 relative overflow-hidden group">
                <div class="absolute right-0 top-0 w-24 h-24 bg-slate-50 rounded-bl-full pointer-events-none -z-10 group-hover:scale-110 transition-transform"></div>
                <div class="w-10 h-10 rounded-2xl bg-amber-100 text-amber-600 flex items-center justify-center mb-3">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5"><path fill-rule="evenodd" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" clip-rule="evenodd" /></svg>
                </div>
                <span class="text-[13px] font-semibold text-slate-500 mb-0.5">Balita Aktif</span>
                <span class="text-xl font-bold text-slate-800 tracking-tight leading-none mt-0.5">32 Anak</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 lg:gap-6 motion-card opacity-0">
            <!-- Informasi Akun -->
            <div class="flex flex-col bg-white p-6 lg:p-8 rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-slate-200/60">
                <h2 class="text-[15px] font-bold tracking-tight text-slate-800 flex items-center gap-2 px-1 mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-teal-500"><path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" /></svg>
                    Detail Kontak
                </h2>
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-4 bg-slate-50/50 p-3 rounded-2xl border border-slate-100/50 hover:bg-slate-50 transition-colors">
                        <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path d="M1.5 8.67v8.58a3 3 0 003 3h15a3 3 0 003-3V8.67l-8.928 5.493a3 3 0 01-3.144 0L1.5 8.67z" /><path d="M22.5 6.908V6.75a3 3 0 00-3-3h-15a3 3 0 00-3 3v.158l9.714 5.978a1.5 1.5 0 001.572 0L22.5 6.908z" /></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[13px] font-medium text-slate-500 mb-0.5">Alamat Email</span>
                            <span class="text-[15px] font-semibold text-slate-800 tracking-tight">{{ $email ?? 'siti.aminah@posyandu.go.id' }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 bg-slate-50/50 p-3 rounded-2xl border border-slate-100/50 hover:bg-slate-50 transition-colors">
                        <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M1.5 4.5a3 3 0 013-3h1.372c.86 0 1.61.586 1.819 1.42l1.105 4.423a1.875 1.875 0 01-.694 1.955l-1.293.97c-.135.101-.164.249-.126.352a11.285 11.285 0 006.697 6.697c.103.038.25.009.352-.126l.97-1.293a1.875 1.875 0 011.955-.694l4.423 1.105c.834.209 1.42.959 1.42 1.82V19.5a3 3 0 01-3 3h-2.25C8.552 22.5 1.5 15.448 1.5 6.75V4.5z" clip-rule="evenodd" /></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[13px] font-medium text-slate-500 mb-0.5">Nomor WhatsApp</span>
                            <span class="text-[15px] font-semibold text-slate-800 tracking-tight">{{ $phone ?? '0812-3456-7890' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Area Penugasan -->
            <div class="flex flex-col bg-white p-6 lg:p-8 rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-slate-200/60">
                <h2 class="text-[15px] font-bold tracking-tight text-slate-800 flex items-center gap-2 px-1 mb-5">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-slate-500"><path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
                    Area Penugasan
                </h2>
                <div class="flex flex-col gap-4">
                    <div class="flex items-center gap-4 bg-slate-50/50 p-3 rounded-2xl border border-slate-100/50 hover:bg-slate-50 transition-colors">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[13px] font-medium text-slate-500 mb-0.5">Cakupan Wilayah</span>
                            <span class="text-[15px] font-semibold text-slate-800 tracking-tight">{{ $desa ?? 'Desa Lampeuneurut' }}, Kec. {{ $kecamatan ?? 'Darul Imarah' }}</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-4 bg-slate-50/50 p-3 rounded-2xl border border-slate-100/50 hover:bg-slate-50 transition-colors">
                        <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-600 flex items-center justify-center shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M3 6a3 3 0 013-3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6zm4.5 7.5a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0v-2.25a.75.75 0 01.75-.75zm3.75-1.5a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0V12zm3-3a.75.75 0 01.75.75v6.75a.75.75 0 01-1.5 0V9.75A.75.75 0 0114.25 9z" clip-rule="evenodd" /></svg>
                        </div>
                        <div class="flex flex-col">
                            <span class="text-[13px] font-medium text-slate-500 mb-0.5">Fasilitas Rujukan Utama</span>
                            <span class="text-[15px] font-semibold text-slate-800 tracking-tight">{{ $puskesmas ?? 'Puskesmas Darul Imarah' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Danger Zone Card -->
        <div class="flex flex-col bg-white p-6 lg:p-8 rounded-[24px] shadow-[0_8px_30px_rgb(0,0,0,0.08)] border border-slate-200/60 motion-card opacity-0 mb-8">
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-rose-100 text-rose-600 flex items-center justify-center shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75" /></svg>
                    </div>
                    <div class="flex flex-col">
                        <h2 class="text-[15px] font-bold tracking-tight text-slate-800">Keamanan Sesi</h2>
                        <span class="text-[13px] text-slate-500 font-medium">Akhiri sesi Anda sekarang untuk menjaga keamanan data Posyandu.</span>
                    </div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="w-full sm:w-auto" onsubmit="if(window.NutriAlert && typeof window.NutriAlert.confirm === 'function'){ event.preventDefault(); const form = this; window.NutriAlert.confirm('Keluar dari Akun?', 'Apakah Anda yakin ingin keluar dari Portal Kader?', 'Keluar', 'Batal').then((r) => { if(r.isConfirmed) form.submit(); }); return false; } return confirm('Keluar dari Portal Kader?');">
                    @csrf
                    <button type="submit" class="w-full sm:w-auto px-6 py-3 bg-white hover:bg-rose-600 text-rose-600 hover:text-white border border-rose-300 hover:border-rose-600 rounded-xl text-[14px] font-bold transition-all focus:outline-none focus:ring-4 focus:ring-rose-500/30 active:scale-95 flex justify-center items-center group">
                        Keluar Perangkat
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
