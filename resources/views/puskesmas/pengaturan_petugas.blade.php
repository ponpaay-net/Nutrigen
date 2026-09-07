@extends('layouts.puskesmas')
@section('page-title', 'Pengaturan')
@section('page-breadcrumbs', 'Profil Petugas')
@section('page-mode', 'app')
@section('content')

{{-- Backend Contract:
    Controller: PuskesmasController@petugas & updatePetugas
    Expected Variables: $user, $puskesmas
--}}

<div class="flex flex-col lg:flex-row flex-1 overflow-hidden" x-data="{ 
    editMode: false,
    formData: {
        nama: '{{ addslashes($user['nama']) }}',
        email: '{{ addslashes($user['email']) }}'
    }
}">

    <!-- RIGHT PANEL: Settings Canvas -->
    <div class="flex-1 flex flex-col overflow-y-auto bg-slate-50/50 relative">
        
        <!-- Colorful Vibrant Header -->
        <div class="shrink-0 px-6 sm:px-10 py-10 bg-gradient-to-r from-teal-700 via-teal-600 to-emerald-600 text-white relative overflow-hidden shadow-sm">
            <!-- Decorative elements -->
            <div class="absolute -right-16 -top-16 w-80 h-80 bg-white/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute right-10 bottom-0 opacity-10 pointer-events-none translate-y-1/4">
                <i class="ph-fill ph-user-circle text-9xl"></i>
            </div>
            
            <div class="relative z-10 max-w-4xl mx-auto">
                <h1 class="text-3xl font-extrabold text-white tracking-tight">Profil Petugas</h1>
                <p class="text-[13px] text-teal-50 font-medium max-w-xl mt-1.5 leading-relaxed">Kelola identitas personal, email, dan rincian otorisasi akun operator di {{ $puskesmas['nama'] }}.</p>
                
                <x-puskesmas.settings-tabs active="petugas" />
            </div>
        </div>

        <div class="flex-1 p-6 sm:px-10 py-8 relative">
            <div class="max-w-4xl w-full mx-auto relative z-10 -mt-10">
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
                
                @if($errors->any())
                    <div class="mb-6 bg-rose-50 border border-rose-200 text-rose-800 rounded-xl p-4 flex items-start gap-3.5 shadow-sm">
                        <div class="w-10 h-10 rounded-lg bg-white border border-rose-100 flex items-center justify-center shrink-0 text-rose-600 shadow-sm mt-0.5">
                            <i class="ph-bold ph-warning-circle text-xl"></i>
                        </div>
                        <div>
                            <p class="text-[14px] font-bold text-slate-900 mb-1">Terjadi kesalahan validasi:</p>
                            <ul class="list-disc ml-4 space-y-1 font-medium text-[12px] text-rose-700">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                @endif

                <form method="POST" action="{{ route('puskesmas.pengaturan.petugas.update') }}" class="flex flex-col gap-6">
                    @csrf
                    @method('PUT')

                    <!-- SECTION: Main Profile Card -->
                    <div class="bg-white border rounded-2xl overflow-hidden shadow-[0_8px_30px_rgb(0,0,0,0.04)] transition-all duration-300"
                         :class="editMode ? 'border-teal-400 ring-4 ring-teal-500/10' : 'border-slate-200'">
                        
                        <!-- Header Card with Officer Info & Edit Button -->
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-6 p-6 lg:p-8 border-b border-slate-100 bg-white">
                            <div class="flex items-center gap-5">
                                <div class="w-16 h-16 rounded-2xl border flex items-center justify-center shrink-0 shadow-sm transition-colors duration-200 relative overflow-hidden"
                                     :class="editMode ? 'bg-teal-600 border-teal-600 text-white' : 'bg-teal-50 border-teal-100 text-teal-700'">
                                    <i class="ph-bold ph-user text-3xl"></i>
                                </div>
                                <div class="min-w-0">
                                    <div class="flex flex-wrap items-center gap-2.5">
                                        <h2 class="text-xl font-extrabold text-slate-900 tracking-tight" x-text="formData.nama"></h2>
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-teal-50 text-teal-700 border border-teal-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-teal-500"></span>
                                            Operator Faskes
                                        </span>
                                        <span x-show="editMode" x-cloak class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-amber-100 text-amber-800 border border-amber-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>
                                            Mode Edit
                                        </span>
                                    </div>
                                    <p class="text-[13px] font-medium text-slate-500 mt-1 flex items-center gap-2">
                                        <i class="ph-bold ph-envelope-simple text-slate-400"></i>
                                        <span x-text="formData.email"></span>
                                    </p>
                                </div>
                            </div>

                            <div class="shrink-0">
                                <div x-show="!editMode" x-transition:enter="transition ease-out duration-150" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                                    <button type="button" @click="editMode = true; $nextTick(() => { $refs.namaInput && $refs.namaInput.focus(); })" class="w-full sm:w-auto px-5 py-2.5 text-sm font-semibold text-teal-700 bg-teal-50 hover:bg-teal-100 border border-teal-200 rounded-xl flex items-center justify-center gap-2 transition-all shadow-sm focus:outline-none">
                                        <i class="ph-bold ph-pencil-simple"></i>
                                        Edit Profil
                                    </button>
                                </div>
                                <div x-show="editMode" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="flex flex-col sm:flex-row items-center gap-3" x-cloak>
                                    <button type="button" @click="editMode = false; formData.nama = '{{ addslashes($user['nama']) }}'; formData.email = '{{ addslashes($user['email']) }}'" class="w-full sm:w-auto px-5 py-2.5 text-sm font-semibold text-slate-800 bg-white hover:bg-slate-50 border border-slate-300 rounded-xl transition-all text-center focus:outline-none shadow-sm flex items-center justify-center gap-2">
                                        <i class="ph-bold ph-x"></i>
                                        Batal
                                    </button>
                                    <button type="submit" class="w-full sm:w-auto px-5 py-2.5 text-sm font-bold text-white bg-teal-600 hover:bg-teal-700 rounded-xl shadow-sm shadow-teal-500/30 transition-all text-center focus:outline-none flex items-center justify-center gap-2">
                                        <i class="ph-bold ph-check"></i>
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Edit Mode Alert Notice -->
                        <div x-show="editMode" 
                             x-transition:enter="transition ease-out duration-300 transform" 
                             x-transition:enter-start="-translate-y-2 opacity-0" 
                             x-transition:enter-end="translate-y-0 opacity-100"
                             class="mx-6 lg:mx-8 mt-6 bg-teal-50/90 border border-teal-200 rounded-xl px-4 py-3 flex items-center gap-2.5 text-teal-900 text-xs sm:text-sm font-medium shadow-xs" x-cloak>
                            <i class="ph-fill ph-note-pencil text-teal-600 text-lg"></i>
                            <span>Silakan perbarui nama lengkap atau alamat email operasional Anda di bawah ini.</span>
                        </div>

                        <!-- Form Inputs -->
                        <div class="p-6 lg:p-8 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8">
                            <!-- Input: Nama -->
                            <div class="flex flex-col gap-2">
                                <label class="text-[12px] font-bold text-slate-600 uppercase tracking-wider">Nama Lengkap</label>
                                <div x-show="!editMode" class="w-full bg-slate-50/80 border border-slate-200 text-slate-800 text-[14px] font-bold rounded-xl px-4 py-3 shadow-2xs min-h-[46px] flex items-center">
                                    {{ $user['nama'] }}
                                </div>
                                <div x-show="editMode" x-cloak>
                                    <input type="text" x-ref="namaInput" name="nama" x-model="formData.nama" class="w-full bg-white border-2 border-teal-500 text-slate-900 text-[14px] font-bold rounded-xl px-4 py-2.5 focus:outline-none ring-4 ring-teal-500/15 transition-all shadow-sm">
                                    @error('nama') <p class="text-[11px] font-bold text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                                </div>
                                <span class="text-[11px] text-slate-500">Nama resmi yang tercantum pada catatan validasi.</span>
                            </div>

                            <!-- Input: Email -->
                            <div class="flex flex-col gap-2">
                                <label class="text-[12px] font-bold text-slate-600 uppercase tracking-wider">Alamat Email</label>
                                <div x-show="!editMode" class="w-full bg-slate-50/80 border border-slate-200 text-slate-800 text-[14px] font-bold rounded-xl px-4 py-3 shadow-2xs min-h-[46px] flex items-center">
                                    {{ $user['email'] }}
                                </div>
                                <div x-show="editMode" x-cloak>
                                    <input type="email" name="email" x-model="formData.email" class="w-full bg-white border-2 border-teal-500 text-slate-900 text-[14px] font-bold rounded-xl px-4 py-2.5 focus:outline-none ring-4 ring-teal-500/15 transition-all shadow-sm">
                                    @error('email') <p class="text-[11px] font-bold text-rose-500 mt-1.5">{{ $message }}</p> @enderror
                                </div>
                                <span class="text-[11px] text-slate-500">Digunakan untuk login dan menerima notifikasi sistem.</span>
                            </div>
                        </div>
                    </div>

                    <!-- ROW: Penugasan & Keamanan Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Card 1: Penugasan Resmi -->
                        <div class="bg-white border border-slate-200 rounded-2xl p-6 lg:p-7 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex flex-col justify-between">
                            <div>
                                <h3 class="text-[15px] font-extrabold tracking-tight text-slate-900 flex items-center gap-2.5 mb-5">
                                    <div class="w-8 h-8 rounded-lg bg-teal-50 border border-teal-100 text-teal-600 flex items-center justify-center text-lg">
                                        <i class="ph-bold ph-buildings"></i>
                                    </div>
                                    Penugasan Unit Faskes
                                </h3>
                                
                                <div class="flex flex-col gap-3.5">
                                    <div class="flex items-center gap-3.5 bg-slate-50/80 p-3.5 rounded-xl border border-slate-100">
                                        <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 border border-teal-100">
                                            <i class="ph-fill ph-hospital text-xl"></i>
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Unit Kerja</span>
                                            <span class="text-[13px] font-bold text-slate-900 truncate">{{ $puskesmas['nama'] }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3.5 bg-slate-50/80 p-3.5 rounded-xl border border-slate-100">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100">
                                            <i class="ph-fill ph-calendar-blank text-xl"></i>
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Terdaftar Sejak</span>
                                            <span class="text-[13px] font-bold text-slate-900 truncate">{{ $user['created_at'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Status Keamanan & Sesi -->
                        <div class="bg-white border border-slate-200 rounded-2xl p-6 lg:p-7 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex flex-col justify-between">
                            <div>
                                <h3 class="text-[15px] font-extrabold tracking-tight text-slate-900 flex items-center gap-2.5 mb-5">
                                    <div class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center text-lg">
                                        <i class="ph-bold ph-shield-check"></i>
                                    </div>
                                    Integritas Akun
                                </h3>
                                
                                <div class="flex flex-col gap-3.5">
                                    <div class="flex items-center gap-3.5 bg-slate-50/80 p-3.5 rounded-xl border border-slate-100">
                                        <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100">
                                            <i class="ph-fill ph-password text-xl"></i>
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Pembaruan Sandi</span>
                                            <span class="text-[13px] font-bold text-slate-900 truncate">{{ $user['updated_at'] }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-3.5 bg-slate-50/80 p-3.5 rounded-xl border border-slate-100">
                                        <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                                            <i class="ph-fill ph-clock text-xl"></i>
                                        </div>
                                        <div class="flex flex-col min-w-0">
                                            <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Sesi Terkini</span>
                                            <span class="text-[13px] font-bold text-slate-900 truncate">{{ now()->translatedFormat('d M Y, H:i') }} WIB</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- INFO FOOTER -->
                    <div class="bg-blue-50/50 border border-blue-200 rounded-2xl p-5 flex items-start gap-4">
                        <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center shrink-0 text-blue-600">
                            <i class="ph-bold ph-info text-lg"></i>
                        </div>
                        <p class="text-[13px] font-medium text-blue-800 leading-relaxed pt-1">
                            Perubahan email akan langsung mempengaruhi identitas login Anda. Pastikan email yang dimasukkan tetap aktif untuk menerima konfirmasi verifikasi.
                        </p>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
