@extends('layouts.puskesmas')
@section('page-title', 'Posyandu & Kader')
@section('page-mode', 'app')
@section('content')

@php
    $filters = ['q' => request('q', '')];
    $requestedId = request('id');
    $selectedPosyandu = null;

    if ($requestedId) {
        $selectedPosyandu = collect($posyandus)->firstWhere('id', (int) $requestedId);
    }
    if (!$selectedPosyandu && count($posyandus) > 0) {
        $selectedPosyandu = $posyandus[0];
    }

    function getInitials($name) {
        $words = explode(' ', $name);
        $initials = '';
        foreach (array_slice($words, 0, 2) as $w) {
            $initials .= strtoupper(substr($w, 0, 1));
        }
        return $initials;
    }
@endphp

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 20px; }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb { background-color: #94a3b8; }
</style>

<div id="toastContainer" class="fixed top-10 right-5 z-50 flex flex-col gap-2 pointer-events-none"></div>

<div class="flex flex-col h-full bg-slate-50 font-sans text-slate-900">
    
    <!-- Colorful Vibrant Header -->
    <div class="shrink-0 px-6 py-5 bg-gradient-to-r from-teal-600 to-emerald-600 text-white flex flex-col sm:flex-row sm:items-center justify-between gap-4 z-10 shadow-md">
        <div class="flex items-center gap-3.5">
            <div class="w-11 h-11 rounded-xl bg-white/20 flex items-center justify-center text-white backdrop-blur-sm border border-white/30 shadow-inner">
                <i class="ph-bold ph-buildings text-2xl"></i>
            </div>
            <div>
                <h1 class="text-xl font-black leading-tight tracking-tight shadow-sm">Manajemen Posyandu</h1>
                <p class="text-[13px] text-teal-50 font-medium">Kelola data pusat operasional gizi dan kader.</p>
            </div>
        </div>
        <button type="button" data-open-modal="posyanduModal" class="px-5 py-2.5 bg-white text-teal-700 hover:bg-teal-50 text-[13px] font-bold rounded-xl transition-all shadow-sm hover:shadow flex items-center gap-2 shrink-0">
            <i class="ph-bold ph-plus text-lg"></i> Tambah Posyandu
        </button>
    </div>

    <!-- Main Workspace -->
    <div class="flex-1 flex overflow-hidden">
        
        <!-- LEFT PANEL: Direktori List -->
        <div class="w-full lg:w-[340px] flex-shrink-0 border-r border-slate-200 bg-white flex flex-col {{ $selectedPosyandu ? 'hidden lg:flex' : 'flex' }}">
            
            <!-- Search -->
            <div class="p-4 border-b border-slate-100 bg-white shrink-0 shadow-sm z-10">
                <form action="{{ route('puskesmas.posyandu') }}" method="GET">
                    <div class="relative group">
                        <i class="ph-bold ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-500 transition-colors text-lg"></i>
                        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}"
                            placeholder="Cari posyandu..."
                            class="w-full pl-10 pr-4 py-2.5 bg-slate-50/80 border border-slate-200 rounded-xl text-[13px] focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 font-medium text-slate-700 transition-colors placeholder:text-slate-400">
                    </div>
                </form>
            </div>

            <!-- List -->
            <div class="flex-1 overflow-y-auto custom-scrollbar bg-slate-50/50">
                @forelse($posyandus as $posyandu)
                    @php
                        $isActive = $selectedPosyandu && $selectedPosyandu['id'] === $posyandu['id'];
                    @endphp
                    <a href="{{ route('puskesmas.posyandu', ['id' => $posyandu['id']]) }}" 
                        class="block px-5 py-4 border-b border-slate-100 transition-all relative {{ $isActive ? 'bg-teal-50/50' : 'hover:bg-white' }}">
                        
                        @if($isActive)
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-teal-500"></div>
                        @endif

                        <div class="flex items-start justify-between mb-1.5">
                            <h3 class="text-[14px] font-bold {{ $isActive ? 'text-teal-800' : 'text-slate-800' }} truncate pr-2">
                                {{ $posyandu['nama'] }}
                            </h3>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest shrink-0 mt-0.5">
                                POS-{{ str_pad($posyandu['id'], 3, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-1.5 text-[12px] text-slate-500 mb-3 font-medium">
                            <i class="ph-fill ph-map-pin text-teal-600/60"></i>
                            <span class="truncate">Desa {{ $posyandu['desa'] }}</span>
                        </div>

                        <div class="flex items-center gap-4 text-[11px] font-bold text-slate-600">
                            <div class="flex items-center gap-1.5 bg-white px-2 py-1 rounded border border-slate-100 shadow-sm">
                                <i class="ph-fill ph-users text-indigo-400"></i>
                                {{ $posyandu['kader_count'] ?? count($posyandu['kaders'] ?? []) }} Kader
                            </div>
                            <div class="flex items-center gap-1.5 bg-white px-2 py-1 rounded border border-slate-100 shadow-sm">
                                <i class="ph-fill ph-baby text-rose-400"></i>
                                {{ number_format($posyandu['balita_count']) }} Balita
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-10 flex flex-col items-center text-center">
                        <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mb-3">
                            <i class="ph-bold ph-magnifying-glass text-xl"></i>
                        </div>
                        <p class="text-[13px] text-slate-500 font-medium">Tidak ada posyandu ditemukan.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RIGHT PANEL: Detail -->
        <div class="flex-1 flex flex-col bg-white overflow-hidden {{ $selectedPosyandu ? 'flex' : 'hidden lg:flex' }}">
            @if ($selectedPosyandu)
                <!-- Mobile Back -->
                <div class="lg:hidden shrink-0 border-b border-slate-200 bg-white p-3">
                    <a href="{{ route('puskesmas.posyandu') }}" class="inline-flex items-center gap-2 text-[13px] font-bold text-teal-700 px-4 py-2 bg-teal-50 border border-teal-100 rounded-lg hover:bg-teal-100 transition-colors">
                        <i class="ph-bold ph-arrow-left"></i> Kembali ke Direktori
                    </a>
                </div>

                <div class="flex-1 overflow-y-auto custom-scrollbar">
                    <div class="p-4 sm:p-6 lg:p-8 max-w-5xl mx-auto flex flex-col gap-8">
                        
                        <!-- Header Details (Colorful Accents) -->
                        <div class="flex flex-col gap-4">
                            <div class="flex items-center gap-3 flex-wrap">
                                <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ $selectedPosyandu['nama'] }}</h2>
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest bg-emerald-100 text-emerald-700 border border-emerald-200 shadow-sm flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                    Aktif
                                </span>
                            </div>
                            <div class="text-[14px] text-slate-600 flex flex-col gap-1.5 max-w-2xl font-medium">
                                <div class="flex items-start gap-2">
                                    <i class="ph-fill ph-map-pin text-rose-500 text-lg mt-0.5"></i>
                                    <span><strong class="text-slate-800">Desa {{ $selectedPosyandu['desa'] }}</strong>
                                    @if($selectedPosyandu['alamat'])
                                        <span class="text-slate-300 mx-1">|</span> {{ $selectedPosyandu['alamat'] }}
                                    @endif
                                    </span>
                                </div>
                                <div class="flex items-start gap-2">
                                    <i class="ph-bold ph-hash text-indigo-400 text-lg mt-0.5"></i>
                                    <span class="font-mono text-slate-500">ID: POS-{{ str_pad($selectedPosyandu['id'], 3, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Dense Inline Metrics with Colorful Icons -->
                        @php
                            $total_balita = $selectedPosyandu['stats']['total_balita'] ?? 0;
                            $diukur = $selectedPosyandu['stats']['diukur_bulan_ini'] ?? 0;
                            $rasio = $total_balita > 0 ? round(($diukur / $total_balita) * 100) : 0;
                        @endphp
                        <div class="border border-slate-200 rounded-2xl bg-white flex flex-col sm:flex-row divide-y sm:divide-y-0 sm:divide-x divide-slate-100 shadow-[0_2px_10px_-3px_rgba(0,0,0,0.05)]">
                            <div class="flex-1 p-5 lg:p-6 flex items-center gap-4 hover:bg-slate-50/50 transition-colors">
                                <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0 border border-indigo-100">
                                    <i class="ph-fill ph-baby text-2xl"></i>
                                </div>
                                <div>
                                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Total Sasaran</div>
                                    <div class="text-2xl font-black text-slate-900 font-mono tracking-tighter">{{ number_format($total_balita) }}</div>
                                </div>
                            </div>
                            <div class="flex-1 p-5 lg:p-6 flex items-center gap-4 hover:bg-slate-50/50 transition-colors">
                                <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 border border-emerald-100">
                                    <i class="ph-fill ph-check-circle text-2xl"></i>
                                </div>
                                <div>
                                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-0.5">Telah Diukur</div>
                                    <div class="text-2xl font-black text-slate-900 font-mono tracking-tighter">{{ number_format($diukur) }}</div>
                                </div>
                            </div>
                            <div class="flex-1 p-5 lg:p-6 flex items-center gap-4 hover:bg-slate-50/50 transition-colors">
                                <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center shrink-0 border border-amber-100">
                                    <i class="ph-fill ph-chart-pie-slice text-2xl"></i>
                                </div>
                                <div class="flex-1">
                                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Kehadiran</div>
                                    <div class="flex items-center gap-3">
                                        <div class="flex-1 h-2.5 bg-slate-100 rounded-full overflow-hidden">
                                            <div class="h-full rounded-full {{ $rasio >= 80 ? 'bg-emerald-500' : ($rasio < 50 ? 'bg-rose-500' : 'bg-amber-500') }}" style="width: {{ $rasio }}%"></div>
                                        </div>
                                        <div class="text-[16px] font-black font-mono leading-none {{ $rasio >= 80 ? 'text-emerald-600' : ($rasio < 50 ? 'text-rose-600' : 'text-amber-600') }}">{{ $rasio }}%</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Density Tables (Colorful Accents) -->
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 items-start">
                            
                            <!-- KADER LIST -->
                            <div>
                                <div class="flex items-center justify-between mb-4 px-1">
                                    <h3 class="text-[16px] font-black text-slate-900">Daftar Kader</h3>
                                    <button type="button" data-open-modal="kaderModal" class="text-[13px] font-bold text-teal-600 hover:text-teal-700 flex items-center gap-1.5 transition-colors bg-teal-50 px-3 py-1.5 rounded-lg border border-teal-100 hover:bg-teal-100">
                                        <i class="ph-bold ph-user-plus text-lg"></i> Tambah Kader
                                    </button>
                                </div>
                                <div class="border border-slate-200 rounded-2xl bg-white overflow-hidden shadow-sm">
                                    <div class="divide-y divide-slate-100">
                                        @forelse($selectedPosyandu['kaders'] ?? [] as $kader)
                                            <div class="flex items-center justify-between p-4 hover:bg-slate-50 transition-colors group">
                                                <div class="flex items-center gap-3.5">
                                                    <!-- Colorful Avatar -->
                                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-teal-400 to-emerald-500 text-white flex items-center justify-center font-bold text-[13px] shadow-sm shrink-0">
                                                        {{ getInitials($kader['nama']) }}
                                                    </div>
                                                    <div>
                                                        <div class="text-[14px] font-bold text-slate-900 group-hover:text-teal-700 transition-colors">{{ $kader['nama'] }}</div>
                                                        <div class="flex items-center gap-1.5 mt-0.5">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                            <span class="text-[12px] text-slate-500 font-medium">Aktivitas: <strong class="text-slate-700">{{ $kader['aktivitas_bulan_ini'] ?? 0 }}</strong> Pengukuran</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="flex items-center gap-1.5 shrink-0 opacity-80 group-hover:opacity-100 transition-opacity">
                                                    @if(!empty($kader['no_hp']))
                                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kader['no_hp']) }}" target="_blank" class="w-8 h-8 rounded-lg bg-emerald-50 border border-emerald-100 text-emerald-600 flex items-center justify-center hover:bg-emerald-500 hover:text-white hover:border-emerald-500 transition-all shadow-sm" title="Hubungi via WhatsApp">
                                                            <i class="ph-fill ph-whatsapp-logo text-lg"></i>
                                                        </a>
                                                    @endif
                                                    <button type="button" data-open-modal="editKaderModal{{ $kader['id'] }}" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-slate-500 flex items-center justify-center hover:bg-slate-100 hover:text-slate-800 transition-colors shadow-sm" title="Edit Kader">
                                                        <i class="ph-bold ph-pencil-simple"></i>
                                                    </button>
                                                    <button type="button" data-open-modal="deleteKaderModal{{ $kader['id'] }}" class="w-8 h-8 rounded-lg bg-white border border-slate-200 text-rose-500 flex items-center justify-center hover:bg-rose-50 hover:border-rose-200 transition-colors shadow-sm" title="Hapus Kader">
                                                        <i class="ph-bold ph-trash"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="p-10 flex flex-col items-center text-center">
                                                <div class="w-12 h-12 rounded-full bg-slate-50 text-slate-300 flex items-center justify-center mb-3">
                                                    <i class="ph-bold ph-user-list text-2xl"></i>
                                                </div>
                                                <p class="text-[13px] text-slate-500 font-medium">Belum ada kader terdaftar.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>

                            <!-- JADWAL LIST -->
                            <div>
                                <div class="flex items-center justify-between mb-4 px-1">
                                    <h3 class="text-[16px] font-black text-slate-900">Jadwal Operasional</h3>
                                </div>
                                <div class="border border-slate-200 rounded-2xl bg-white overflow-hidden shadow-sm">
                                    <div class="divide-y divide-slate-100">
                                        @forelse($selectedPosyandu['jadwals'] ?? [] as $jadwal)
                                            <div class="flex items-start gap-4 p-4 hover:bg-slate-50 transition-colors">
                                                <!-- Colorful Date Box -->
                                                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-amber-100 to-amber-200 text-amber-800 flex flex-col items-center justify-center shrink-0 border border-amber-300/50 shadow-sm">
                                                    <span class="text-[10px] font-bold uppercase leading-none">{{ \Carbon\Carbon::parse($jadwal['tanggal'])->translatedFormat('M') }}</span>
                                                    <span class="text-[18px] font-black leading-tight mt-0.5">{{ \Carbon\Carbon::parse($jadwal['tanggal'])->format('d') }}</span>
                                                </div>
                                                <div class="flex-1 min-w-0 pt-0.5">
                                                    <div class="text-[14px] font-bold text-slate-900 truncate mb-1.5">{{ $jadwal['judul'] }}</div>
                                                    <div class="flex flex-wrap items-center gap-3 text-[12px] text-slate-600 font-medium">
                                                        <span class="flex items-center gap-1.5 bg-white border border-slate-200 px-2 py-0.5 rounded shadow-sm">
                                                            <i class="ph-fill ph-clock text-amber-500"></i>
                                                            {{ substr($jadwal['waktu_mulai'], 0, 5) }} WIB
                                                        </span>
                                                        @if(!empty($jadwal['lokasi']))
                                                            <span class="flex items-center gap-1.5 truncate max-w-[200px]">
                                                                <i class="ph-fill ph-map-pin text-rose-400 shrink-0"></i>
                                                                <span class="truncate" title="{{ $jadwal['lokasi'] }}">{{ $jadwal['lokasi'] }}</span>
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="p-10 flex flex-col items-center text-center">
                                                <div class="w-12 h-12 rounded-full bg-slate-50 text-slate-300 flex items-center justify-center mb-3">
                                                    <i class="ph-bold ph-calendar-x text-2xl"></i>
                                                </div>
                                                <p class="text-[13px] text-slate-500 font-medium">Belum ada jadwal operasional.</p>
                                            </div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                            
                        </div>

                    </div>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center p-8 bg-slate-50/50">
                    <div class="w-20 h-20 rounded-full bg-white border border-slate-200 shadow-sm flex items-center justify-center mb-4">
                        <i class="ph-fill ph-buildings text-4xl text-teal-600/30"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 mb-2">Pilih Posyandu</h3>
                    <p class="text-[14px] text-slate-500 font-medium max-w-sm text-center">Pilih posyandu dari direktori di sebelah kiri untuk melihat detail metrik operasional dan data kader.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modals -->
<!-- Add Posyandu Modal -->
<div id="posyanduModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-100 flex flex-col">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-white">
            <h2 class="text-[16px] font-black text-slate-900">Tambah Posyandu</h2>
            <button type="button" data-close-modal="posyanduModal" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:text-slate-700 hover:bg-slate-100 flex items-center justify-center transition-colors focus:outline-none">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
        <div class="p-6 bg-slate-50/30">
            <form action="{{ route('puskesmas.posyandu.store') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <input type="hidden" name="form_type" value="posyandu">
                @if ($errors->any() && old('form_type') === 'posyandu')
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-[13px] font-medium text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif
                <div>
                    <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Nama Posyandu</label>
                    <input type="text" name="nama" value="{{ old('form_type') === 'posyandu' ? old('nama') : '' }}" required
                        class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-[14px] focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm transition-all"
                        placeholder="Contoh: Posyandu Melati">
                </div>
                <div>
                    <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Desa/Kelurahan</label>
                    <input type="text" name="desa_kelurahan" value="{{ old('form_type') === 'posyandu' ? old('desa_kelurahan') : '' }}" required
                        class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-[14px] focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm transition-all"
                        placeholder="Nama desa atau kelurahan">
                </div>
                <div>
                    <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Alamat Lengkap</label>
                    <textarea name="alamat" rows="2" 
                        class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-[14px] focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm transition-all resize-none"
                        placeholder="Opsional">{{ old('form_type') === 'posyandu' ? old('alamat') : '' }}</textarea>
                </div>
                <div class="mt-2 flex justify-end gap-3">
                    <button type="button" data-close-modal="posyanduModal" class="px-5 py-2.5 text-[13px] font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 focus:outline-none shadow-sm">
                        Batal
                    </button>
                    <button type="submit" class="px-5 py-2.5 text-[13px] font-bold text-white bg-teal-600 rounded-xl hover:bg-teal-700 focus:outline-none shadow-sm shadow-teal-500/30">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Kader Modal -->
<div id="kaderModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-100 flex flex-col">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-white">
            <h2 class="text-[16px] font-black text-slate-900">Tambah Kader Baru</h2>
            <button type="button" data-close-modal="kaderModal" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:text-slate-700 hover:bg-slate-100 flex items-center justify-center transition-colors focus:outline-none">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
        <div class="p-6 bg-slate-50/30">
            @if ($selectedPosyandu)
                <form action="{{ route('puskesmas.posyandu.kader.store', $selectedPosyandu['id']) }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <input type="hidden" name="form_type" value="kader">
                    @if ($errors->any() && old('form_type') === 'kader')
                        <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-[13px] font-medium text-rose-700">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    <div>
                        <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('form_type') === 'kader' ? old('nama') : '' }}" required
                            class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-[14px] focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Email (Username Login)</label>
                        <input type="email" name="email" value="{{ old('form_type') === 'kader' ? old('email') : '' }}" required
                            class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-[14px] focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-slate-700 mb-1.5">No HP / WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('form_type') === 'kader' ? old('no_hp') : '' }}"
                            class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-[14px] focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm transition-all">
                    </div>
                    <div>
                        <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Password Awal</label>
                        <input type="password" name="password" required minlength="8"
                            class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-[14px] focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm transition-all" placeholder="Minimal 8 karakter">
                    </div>
                    <div class="mt-2 flex justify-end gap-3">
                        <button type="button" data-close-modal="kaderModal" class="px-5 py-2.5 text-[13px] font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 focus:outline-none shadow-sm">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-[13px] font-bold text-white bg-teal-600 rounded-xl hover:bg-teal-700 focus:outline-none shadow-sm shadow-teal-500/30">
                            Simpan Kader
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

<!-- Edit Kader & Delete Kader Modals -->
@if ($selectedPosyandu)
    @foreach($selectedPosyandu['kaders'] ?? [] as $kader)
        <!-- Edit Kader Modal -->
        <div id="editKaderModal{{ $kader['id'] }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div class="w-full max-w-md bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-100 flex flex-col">
                <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-white">
                    <h2 class="text-[16px] font-black text-slate-900">Edit Data Kader</h2>
                    <button type="button" data-close-modal="editKaderModal{{ $kader['id'] }}" class="w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:text-slate-700 hover:bg-slate-100 flex items-center justify-center transition-colors focus:outline-none">
                        <i class="ph-bold ph-x text-lg"></i>
                    </button>
                </div>
                <div class="p-6 bg-slate-50/30">
                    <form action="{{ route('puskesmas.posyandu.kader.update', $kader['id']) }}" method="POST" class="flex flex-col gap-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="form_type" value="editKaderModal{{ $kader['id'] }}">
                        @if ($errors->any() && old('form_type') === 'editKaderModal'.$kader['id'])
                            <div class="p-3 bg-rose-50 border border-rose-200 rounded-xl text-[13px] font-medium text-rose-700">
                                {{ $errors->first() }}
                            </div>
                        @endif
                        
                        <div>
                            <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                            <input type="text" name="nama" value="{{ old('nama', $kader['nama']) }}" required
                                class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-[14px] focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Email (Username Login)</label>
                            <input type="email" name="email" value="{{ old('email', $kader['email'] ?? '') }}" required
                                class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-[14px] focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-slate-700 mb-1.5">No HP / WhatsApp</label>
                            <input type="text" name="no_hp" value="{{ old('no_hp', $kader['no_hp'] ?? '') }}"
                                class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-[14px] focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm transition-all">
                        </div>
                        <div>
                            <label class="block text-[13px] font-bold text-slate-700 mb-1.5">Ganti Password <span class="text-slate-400 font-normal">(Opsional)</span></label>
                            <input type="password" name="password" minlength="8"
                                class="w-full px-4 py-2.5 bg-white border border-slate-300 rounded-xl text-[14px] focus:outline-none focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 shadow-sm transition-all" placeholder="Kosongkan jika tidak diubah">
                        </div>
                        <div class="mt-2 flex justify-end gap-3">
                            <button type="button" data-close-modal="editKaderModal{{ $kader['id'] }}" class="px-5 py-2.5 text-[13px] font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 focus:outline-none shadow-sm">
                                Batal
                            </button>
                            <button type="submit" class="px-5 py-2.5 text-[13px] font-bold text-white bg-teal-600 rounded-xl hover:bg-teal-700 focus:outline-none shadow-sm shadow-teal-500/30">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Kader Modal -->
        <div id="deleteKaderModal{{ $kader['id'] }}" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
            <div class="w-full max-w-sm bg-white rounded-2xl shadow-2xl overflow-hidden border border-slate-100 flex flex-col">
                <div class="p-8 flex flex-col items-center text-center gap-4 bg-slate-50/30">
                    <div class="w-16 h-16 rounded-full bg-rose-100 text-rose-500 flex items-center justify-center border border-rose-200">
                        <i class="ph-bold ph-warning text-3xl"></i>
                    </div>
                    <div>
                        <h3 class="text-[18px] font-black text-slate-900 mb-1">Hapus Kader?</h3>
                        <p class="text-[14px] text-slate-500 font-medium leading-relaxed">Anda yakin ingin menghapus akses <strong>{{ $kader['nama'] }}</strong>? Tindakan ini permanen.</p>
                    </div>
                    <form action="{{ route('puskesmas.posyandu.kader.destroy', $kader['id']) }}" method="POST" class="w-full flex gap-3 mt-4">
                        @csrf
                        @method('DELETE')
                        <button type="button" data-close-modal="deleteKaderModal{{ $kader['id'] }}" class="flex-1 px-4 py-3 text-[14px] font-bold text-slate-600 bg-white border border-slate-300 rounded-xl hover:bg-slate-50 focus:outline-none shadow-sm">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 px-4 py-3 text-[14px] font-bold text-white bg-rose-600 border border-rose-700 rounded-xl hover:bg-rose-700 focus:outline-none shadow-sm shadow-rose-500/30">
                            Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endif

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('[data-open-modal]').forEach(function(button) {
            button.addEventListener('click', function() {
                const modal = document.getElementById(button.dataset.openModal);
                if (modal) modal.classList.replace('hidden', 'flex');
            });
        });
        document.querySelectorAll('[data-close-modal]').forEach(function(button) {
            button.addEventListener('click', function() {
                const modal = document.getElementById(button.dataset.closeModal);
                if (modal) modal.classList.replace('flex', 'hidden');
            });
        });
        document.querySelectorAll('[id$="Modal"]').forEach(function(modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === modal) modal.classList.replace('flex', 'hidden');
            });
        });
        @if ($errors->any() && old('form_type'))
            const oldFormType = '{{ old('form_type') }}';
            const modalId = oldFormType.endsWith('Modal') ? oldFormType : oldFormType + 'Modal';
            const errorModal = document.getElementById(modalId);
            if (errorModal) errorModal.classList.replace('hidden', 'flex');
        @endif
    });
</script>
@endpush
@endsection
