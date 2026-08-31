@extends('layouts.puskesmas')
@section('page-title', 'Pengaturan')
@section('page-breadcrumbs')
    Pengaturan 
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 text-[#CBD5E1]">
        <path fill-rule="evenodd" d="M7.21 14.77a.75.75 0 01.02-1.06L11.168 10 7.23 6.29a.75.75 0 111.04-1.08l4.5 4.25a.75.75 0 010 1.08l-4.5 4.25a.75.75 0 01-1.06-.02z" clip-rule="evenodd" />
    </svg>
    Profil Petugas
@endsection
@section('page-mode', 'app')
@section('content')

{{-- Backend Contract:
    Controller: PuskesmasController@petugas & updatePetugas
    Expected Variables: $user, $puskesmas
--}}

<!-- Full-viewport Split View: Petugas Management -->
<div class="flex flex-col lg:flex-row flex-1 overflow-hidden" x-data="{ 
    editMode: false,
    formData: {
        nama: '{{ addslashes($user['nama']) }}',
        email: '{{ addslashes($user['email']) }}'
    }
}">

    <!-- LEFT PANEL: Settings Navigation -->
    <x-puskesmas.settings-sidebar active="petugas" />

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
            
            @if($errors->any())
                <div class="mb-8 bg-rose-50 border border-rose-200 text-rose-800 rounded-2xl p-4 flex items-start gap-3.5 shadow-sm">
                    <div class="w-9 h-9 rounded-xl bg-rose-100 flex items-center justify-center shrink-0 text-rose-600 mt-0.5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-semibold mb-1">Terjadi kesalahan pada input data:</p>
                        <ul class="list-disc ml-5 space-y-1 font-medium text-[13px] text-rose-700">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('puskesmas.pengaturan.petugas.update') }}" class="flex flex-col gap-6 lg:gap-8">
                @csrf
                @method('PUT')

                <!-- PROFILE BANNER -->
                <div class="relative bg-gradient-to-br from-teal-600 via-teal-700 to-emerald-800 rounded-[32px] p-6 sm:p-8 shadow-md overflow-hidden">
                    <!-- Background Decorations -->
                    <div class="absolute -right-10 -top-10 w-64 h-64 bg-teal-400/20 rounded-full blur-3xl pointer-events-none"></div>
                    <div class="absolute left-0 bottom-0 w-40 h-40 bg-teal-900/40 rounded-full blur-2xl pointer-events-none"></div>
                    <div class="absolute inset-0 opacity-[0.05] pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>
                    
                    <div class="relative z-10 flex flex-col sm:flex-row items-center sm:items-start gap-6 sm:gap-8">
                        <!-- Avatar Container -->
                        <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-white/10 p-1.5 backdrop-blur-md shadow-inner shrink-0 group relative overflow-hidden">
                            <div class="w-full h-full rounded-xl bg-white text-teal-300 flex items-center justify-center overflow-hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-14 h-14 mt-3">
                                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="absolute inset-0 bg-slate-900/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity backdrop-blur-[2px] rounded-xl cursor-not-allowed">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6 text-white"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.774 48.774 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" /></svg>
                            </div>
                        </div>
                        
                        <!-- Info -->
                        <div class="flex-1 text-center sm:text-left mt-2">
                            <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-white/10 border border-white/20 text-white text-[11px] font-medium tracking-wide mb-3 backdrop-blur-sm shadow-sm">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                Petugas {{ $user['role'] }}
                            </div>
                            <!-- Live Name update via x-text -->
                            <h1 class="text-2xl sm:text-3xl font-bold text-white tracking-tight leading-tight drop-shadow-sm mb-1" x-text="formData.nama"></h1>
                            <p class="text-teal-100 text-[14px] font-medium flex items-center justify-center sm:justify-start gap-1.5 mt-2">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 opacity-80"><path d="M3 4a2 2 0 00-2 2v1.161l8.441 4.221a1.25 1.25 0 001.118 0L19 7.162V6a2 2 0 00-2-2H3z" /><path d="M19 8.839l-7.77 3.885a2.75 2.75 0 01-2.46 0L1 8.839V14a2 2 0 002 2h14a2 2 0 002-2V8.839z" /></svg>
                                <span x-text="formData.email"></span>
                            </p>
                        </div>
                        
                        <!-- Action -->
                        <div class="shrink-0 mt-2 sm:mt-0">
                            <button type="button" x-show="!editMode" @click="editMode = true" class="group inline-flex items-center justify-center gap-2 bg-white/10 hover:bg-white/20 border border-white/20 text-white px-5 py-2.5 rounded-xl text-[13px] font-semibold backdrop-blur-md transition-all focus:outline-none focus:ring-4 focus:ring-white/20 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 group-hover:-translate-y-0.5 transition-transform">
                                  <path d="M2.695 14.763l-1.262 3.152a.5.5 0 00.65.65l3.152-1.262a4 4 0 001.343-.885L17.5 5.5a2.121 2.121 0 00-3-3L3.58 13.42a4 4 0 00-.885 1.343z" />
                                </svg>
                                Edit Data
                            </button>
                        </div>
                    </div>
                </div>

                <!-- SECTION: Informasi Akun (Form) -->
                <div class="bg-white/80 backdrop-blur-xl border border-slate-200/60 rounded-[32px] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 rounded-2xl bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-600 shrink-0">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                                    <path fill-rule="evenodd" d="M7.5 6a4.5 4.5 0 119 0 4.5 4.5 0 01-9 0zM3.751 20.105a8.25 8.25 0 0116.498 0 .75.75 0 01-.437.695A18.683 18.683 0 0112 22.5c-2.786 0-5.433-.608-7.812-1.7a.75.75 0 01-.437-.695z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-[16px] font-bold text-slate-800 tracking-tight">Data Profil</h2>
                                <p class="text-xs font-medium text-slate-500">Perbarui identitas pribadi Anda.</p>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Input: Nama -->
                            <div class="flex flex-col gap-2">
                                <label class="text-[13px] font-semibold text-slate-700">Nama Lengkap</label>
                                <div x-show="!editMode" class="w-full bg-slate-50/50 border border-slate-100/50 text-slate-800 text-[14px] font-semibold rounded-2xl px-4 py-3.5">{{ $user['nama'] }}</div>
                                <div x-show="editMode" x-cloak>
                                    <input type="text" name="nama" x-model="formData.nama" class="w-full bg-white border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-medium rounded-2xl px-4 py-3.5 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all shadow-sm">
                                    @error('nama') <p class="text-xs font-semibold text-rose-500 mt-1.5 px-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                            <!-- Input: Email -->
                            <div class="flex flex-col gap-2">
                                <label class="text-[13px] font-semibold text-slate-700">Alamat Email</label>
                                <div x-show="!editMode" class="w-full bg-slate-50/50 border border-slate-100/50 text-slate-800 text-[14px] font-semibold rounded-2xl px-4 py-3.5">{{ $user['email'] }}</div>
                                <div x-show="editMode" x-cloak>
                                    <input type="email" name="email" x-model="formData.email" class="w-full bg-white border border-slate-200 hover:border-teal-300 text-slate-900 text-[14px] font-medium rounded-2xl px-4 py-3.5 focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-500 transition-all shadow-sm">
                                    @error('email') <p class="text-xs font-semibold text-rose-500 mt-1.5 px-1">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div x-show="editMode" x-cloak class="pt-8 mt-6 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-3" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform translate-y-4" x-transition:enter-end="opacity-100 transform translate-y-0">
                            <button type="button" @click="editMode = false; formData.nama = '{{ addslashes($user['nama']) }}'; formData.email = '{{ addslashes($user['email']) }}'" class="w-full sm:w-auto px-6 py-3 rounded-2xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-[14px] transition-all text-center focus:outline-none focus:ring-4 focus:ring-slate-100">
                                Batal
                            </button>
                            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-teal-600 hover:bg-teal-500 active:bg-teal-700 text-white rounded-2xl font-semibold text-[14px] shadow-sm shadow-teal-500/25 transition-all text-center focus:outline-none focus:ring-4 focus:ring-teal-500/30">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </div>
                
                <!-- ROW: Penugasan & Keamanan -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                    
                    <!-- Area Penugasan -->
                    <div class="bg-white/80 backdrop-blur-xl border border-slate-200/60 rounded-[32px] p-6 sm:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                        <h2 class="text-[15px] font-bold tracking-tight text-slate-800 flex items-center gap-2 mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-teal-500"><path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" /></svg>
                            Penugasan Resmi
                        </h2>
                        
                        <div class="flex flex-col gap-4">
                            <div class="flex items-center gap-4 bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100">
                                <div class="w-12 h-12 rounded-2xl bg-teal-100 text-teal-600 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M3 6a3 3 0 013-3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6zm4.5 7.5a.75.75 0 01.75.75v2.25a.75.75 0 01-1.5 0v-2.25a.75.75 0 01.75-.75zm3.75-1.5a.75.75 0 00-1.5 0v4.5a.75.75 0 001.5 0V12zm3-3a.75.75 0 01.75.75v6.75a.75.75 0 01-1.5 0V9.75A.75.75 0 0114.25 9z" clip-rule="evenodd" /></svg>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[12px] font-medium text-slate-500 mb-0.5">Unit Kerja Puskesmas</span>
                                    <span class="text-[14px] font-bold text-slate-800 tracking-tight truncate">{{ $puskesmas['nama'] }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100">
                                <div class="w-12 h-12 rounded-2xl bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6"><path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 6a.75.75 0 00-1.5 0v6c0 .414.336.75.75.75h4.5a.75.75 0 000-1.5h-3.75V6z" clip-rule="evenodd" /></svg>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[12px] font-medium text-slate-500 mb-0.5">Terdaftar Sejak</span>
                                    <span class="text-[14px] font-semibold text-slate-800 tracking-tight truncate">{{ $user['created_at'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Keamanan -->
                    <div class="bg-white/80 backdrop-blur-xl border border-slate-200/60 rounded-[32px] p-6 sm:p-8 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                        <h2 class="text-[15px] font-bold tracking-tight text-slate-800 flex items-center gap-2 mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-slate-400"><path fill-rule="evenodd" d="M12 1.5a5.25 5.25 0 00-5.25 5.25v3a3 3 0 00-3 3v6.75a3 3 0 003 3h10.5a3 3 0 003-3v-6.75a3 3 0 00-3-3v-3c0-2.9-2.35-5.25-5.25-5.25zm3.75 8.25v-3a3.75 3.75 0 10-7.5 0v3h7.5z" clip-rule="evenodd" /></svg>
                            Keamanan Akun
                        </h2>
                        
                        <div class="flex flex-col gap-4">
                            <div class="flex items-center gap-4 bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" /></svg>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[12px] font-medium text-slate-500 mb-0.5">Sandi Diperbarui</span>
                                    <span class="text-[14px] font-semibold text-slate-800 tracking-tight truncate">{{ $user['updated_at'] }}</span>
                                </div>
                            </div>

                            <div class="flex items-center gap-4 bg-slate-50/70 p-3.5 rounded-2xl border border-slate-100">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-500 flex items-center justify-center shrink-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 01-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0115 18.257V17.25m6-12V15a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 15V5.25m18 0A2.25 2.25 0 0018.75 3H5.25A2.25 2.25 0 003 5.25m18 0V12a2.25 2.25 0 01-2.25 2.25H5.25A2.25 2.25 0 013 12V5.25" /></svg>
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[12px] font-medium text-slate-500 mb-0.5">Sesi Login Terakhir</span>
                                    <span class="text-[14px] font-semibold text-slate-800 tracking-tight truncate">{{ now()->translatedFormat('d M Y, H:i') }} WIB</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection
