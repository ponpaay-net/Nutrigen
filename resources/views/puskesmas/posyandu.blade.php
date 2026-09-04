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
@endphp

<div id="toastContainer" class="fixed top-10 right-5 z-50 flex flex-col gap-2"></div>

<div class="flex flex-col h-full bg-white">
    <!-- Header -->
    <div class="shrink-0 px-6 py-6 border-b border-slate-200 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-4 z-10 relative">
        <div>
            <h1 class="text-2xl font-black text-slate-900 tracking-tight">Manajemen Posyandu</h1>
            <p class="text-[13px] text-slate-500 mt-1 font-medium">Kelola data posyandu, kader, dan jadwal kegiatan operasional.</p>
        </div>
        <button type="button" data-open-modal="posyanduModal" class="px-4 py-2 bg-teal-600 hover:bg-teal-700 text-white text-[13px] font-bold rounded-lg transition-colors shadow-sm focus:ring-2 focus:ring-offset-2 focus:ring-teal-500 flex items-center gap-2">
            <i class="ph-bold ph-plus"></i> Tambah Posyandu
        </button>
    </div>

    <!-- Main Workspace -->
    <div class="flex-1 flex overflow-hidden">
        
        <!-- LEFT PANEL: Direktori List -->
        <div class="w-full lg:w-[320px] xl:w-[360px] flex-shrink-0 border-r border-slate-200 bg-slate-50 flex flex-col {{ $selectedPosyandu ? 'hidden lg:flex' : 'flex' }}">
            <!-- Search -->
            <div class="p-4 border-b border-slate-200 bg-white shrink-0">
                <form action="{{ route('puskesmas.posyandu') }}" method="GET">
                    <div class="relative">
                        <i class="ph-bold ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                        <input type="text" name="q" value="{{ $filters['q'] ?? '' }}"
                            placeholder="Cari posyandu..."
                            class="w-full pl-9 pr-3 py-2 bg-slate-50 border border-slate-300 rounded-md text-[13px] focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 font-medium text-slate-700 transition-colors placeholder:text-slate-400 shadow-sm">
                    </div>
                </form>
            </div>

            <!-- List -->
            <div class="flex-1 overflow-y-auto divide-y divide-slate-200">
                @forelse($posyandus as $posyandu)
                    @php
                        $isActive = $selectedPosyandu && $selectedPosyandu['id'] === $posyandu['id'];
                    @endphp
                    <a href="{{ route('puskesmas.posyandu', ['id' => $posyandu['id']]) }}" 
                        class="block p-4 transition-colors relative {{ $isActive ? 'bg-teal-50/50' : 'bg-white hover:bg-slate-50' }}">
                        
                        @if($isActive)
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-teal-600"></div>
                        @endif

                        <div class="flex items-start justify-between mb-1">
                            <h3 class="text-[14px] font-bold {{ $isActive ? 'text-teal-800' : 'text-slate-800' }} truncate pr-2">
                                {{ $posyandu['nama'] }}
                            </h3>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest shrink-0">
                                POS-{{ str_pad($posyandu['id'], 3, '0', STR_PAD_LEFT) }}
                            </span>
                        </div>
                        
                        <div class="flex items-center gap-1.5 text-[12px] text-slate-500 font-medium mb-3">
                            <i class="ph-bold ph-map-pin text-slate-400"></i>
                            <span class="truncate">Desa {{ $posyandu['desa'] }}</span>
                        </div>

                        <div class="flex items-center gap-4 text-[11px] font-semibold">
                            <div class="flex items-center gap-1.5 text-slate-600">
                                <i class="ph-bold ph-users text-slate-400"></i>
                                {{ $posyandu['kader_count'] ?? count($posyandu['kaders'] ?? []) }} Kader
                            </div>
                            <div class="flex items-center gap-1.5 text-slate-600">
                                <i class="ph-bold ph-baby text-slate-400"></i>
                                {{ $posyandu['balita_count'] }} Balita
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="p-8 text-center text-slate-500 text-[13px] font-medium">
                        Tidak ada posyandu ditemukan.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- RIGHT PANEL: Detail -->
        <div class="flex-1 flex flex-col bg-white overflow-hidden {{ $selectedPosyandu ? 'flex' : 'hidden lg:flex' }}">
            @if ($selectedPosyandu)
                <!-- Mobile Back -->
                <div class="lg:hidden shrink-0 border-b border-slate-200 bg-slate-50 p-2">
                    <a href="{{ route('puskesmas.posyandu') }}" class="inline-flex items-center gap-2 text-[12px] font-bold text-slate-600 px-3 py-1.5 bg-white border border-slate-300 rounded hover:bg-slate-50">
                        <i class="ph-bold ph-arrow-left"></i> Kembali ke Direktori
                    </a>
                </div>

                <div class="flex-1 overflow-y-auto">
                    <div class="p-6 lg:p-8 max-w-6xl mx-auto flex flex-col gap-8">
                        
                        <!-- Title & Stats -->
                        <div>
                            <div class="flex items-center gap-3 mb-2">
                                <h2 class="text-2xl font-black text-slate-900">{{ $selectedPosyandu['nama'] }}</h2>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-widest bg-emerald-100 text-emerald-700 border border-emerald-200">Aktif</span>
                            </div>
                            <p class="text-[13px] text-slate-500 font-medium mb-6">
                                Berlokasi di Desa {{ $selectedPosyandu['desa'] }} @if($selectedPosyandu['alamat']), {{ $selectedPosyandu['alamat'] }} @endif
                            </p>

                            <!-- Metric Row -->
                            @php
                                $total_balita = $selectedPosyandu['stats']['total_balita'] ?? 0;
                                $diukur = $selectedPosyandu['stats']['diukur_bulan_ini'] ?? 0;
                                $rasio = $total_balita > 0 ? round(($diukur / $total_balita) * 100) : 0;
                            @endphp
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="bg-white border border-slate-200 p-4 rounded-lg shadow-sm">
                                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-1">Total Balita</div>
                                    <div class="text-2xl font-black text-slate-800">{{ $total_balita }}</div>
                                </div>
                                <div class="bg-white border border-slate-200 p-4 rounded-lg shadow-sm">
                                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-1">Pengukuran Bulan Ini</div>
                                    <div class="text-2xl font-black text-slate-800">{{ $diukur }}</div>
                                </div>
                                <div class="bg-white border border-slate-200 p-4 rounded-lg shadow-sm">
                                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-1">Rasio Pengukuran</div>
                                    <div class="text-2xl font-black {{ $rasio >= 80 ? 'text-emerald-600' : ($rasio < 50 ? 'text-rose-600' : 'text-amber-600') }}">{{ $rasio }}%</div>
                                </div>
                                <div class="bg-white border border-slate-200 p-4 rounded-lg shadow-sm">
                                    <div class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-1">Total Kader</div>
                                    <div class="text-2xl font-black text-slate-800">{{ count($selectedPosyandu['kaders'] ?? []) }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Data Tables -->
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-8 items-start">
                            
                            <!-- KADER TABLE -->
                            <div class="bg-white border border-slate-200 rounded-lg shadow-sm flex flex-col">
                                <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                                    <h3 class="text-[14px] font-bold text-slate-800">Daftar Kader</h3>
                                    <button type="button" data-open-modal="kaderModal" class="text-[12px] font-bold text-teal-600 hover:text-teal-700 flex items-center gap-1">
                                        <i class="ph-bold ph-plus"></i> Tambah
                                    </button>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead class="bg-slate-50 border-b border-slate-200">
                                            <tr>
                                                <th class="px-4 py-2 text-[10px] font-black text-slate-500 uppercase tracking-widest">Nama</th>
                                                <th class="px-4 py-2 text-[10px] font-black text-slate-500 uppercase tracking-widest text-right">Kontak</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @forelse($selectedPosyandu['kaders'] ?? [] as $kader)
                                                <tr class="hover:bg-slate-50 transition-colors">
                                                    <td class="px-4 py-3">
                                                        <div class="text-[13px] font-bold text-slate-800">{{ $kader['nama'] }}</div>
                                                        <div class="text-[11px] text-slate-500 font-medium mt-0.5">Aktif • {{ $kader['aktivitas_bulan_ini'] ?? 0 }} Pengukuran</div>
                                                    </td>
                                                    <td class="px-4 py-3 text-right">
                                                        @if(!empty($kader['no_hp']))
                                                            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $kader['no_hp']) }}" target="_blank" class="inline-flex items-center justify-center w-7 h-7 rounded bg-emerald-50 text-emerald-600 hover:bg-emerald-100 transition-colors" title="WhatsApp">
                                                                <i class="ph-bold ph-whatsapp-logo"></i>
                                                            </a>
                                                        @else
                                                            <span class="text-[11px] text-slate-400 italic">No HP</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="px-4 py-8 text-center text-[12px] text-slate-500 font-medium">
                                                        Belum ada kader terdaftar.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- JADWAL TABLE -->
                            <div class="bg-white border border-slate-200 rounded-lg shadow-sm flex flex-col">
                                <div class="p-4 border-b border-slate-200 flex items-center justify-between">
                                    <h3 class="text-[14px] font-bold text-slate-800">Jadwal Operasional</h3>
                                </div>
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead class="bg-slate-50 border-b border-slate-200">
                                            <tr>
                                                <th class="px-4 py-2 text-[10px] font-black text-slate-500 uppercase tracking-widest">Agenda</th>
                                                <th class="px-4 py-2 text-[10px] font-black text-slate-500 uppercase tracking-widest">Waktu & Lokasi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100">
                                            @forelse($selectedPosyandu['jadwals'] ?? [] as $jadwal)
                                                <tr class="hover:bg-slate-50 transition-colors">
                                                    <td class="px-4 py-3">
                                                        <div class="text-[13px] font-bold text-slate-800">{{ $jadwal['judul'] }}</div>
                                                        <div class="text-[11px] text-slate-500 font-medium mt-0.5">
                                                            {{ \Carbon\Carbon::parse($jadwal['tanggal'])->translatedFormat('d M Y') }}
                                                        </div>
                                                    </td>
                                                    <td class="px-4 py-3">
                                                        <div class="text-[12px] font-semibold text-slate-700">{{ substr($jadwal['waktu_mulai'], 0, 5) }} WIB</div>
                                                        @if(!empty($jadwal['lokasi']))
                                                            <div class="text-[11px] text-slate-500 mt-0.5 truncate max-w-[150px]" title="{{ $jadwal['lokasi'] }}">{{ $jadwal['lokasi'] }}</div>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="2" class="px-4 py-8 text-center text-[12px] text-slate-500 font-medium">
                                                        Belum ada jadwal operasional.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            
                        </div>

                    </div>
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center p-8 bg-slate-50/50">
                    <div class="w-16 h-16 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 mb-4 border border-slate-200">
                        <i class="ph-bold ph-buildings text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-slate-800 mb-1">Pilih Posyandu</h3>
                    <p class="text-[13px] text-slate-500">Pilih posyandu dari daftar di sebelah kiri untuk melihat detail.</p>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Add Posyandu Modal -->
<div id="posyanduModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-xl overflow-hidden border border-slate-200 flex flex-col max-h-[90vh]">
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
            <h2 class="text-[15px] font-bold text-slate-800">Tambah Posyandu</h2>
            <button type="button" data-close-modal="posyanduModal" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
        <div class="p-5 overflow-y-auto">
            <form action="{{ route('puskesmas.posyandu.store') }}" method="POST" class="flex flex-col gap-4">
                @csrf
                <input type="hidden" name="form_type" value="posyandu">
                @if ($errors->any() && old('form_type') === 'posyandu')
                    <div class="p-3 bg-rose-50 border border-rose-200 rounded-md text-[12px] font-medium text-rose-700">
                        {{ $errors->first() }}
                    </div>
                @endif
                <div>
                    <label class="block text-[12px] font-bold text-slate-700 mb-1.5">Nama Posyandu</label>
                    <input type="text" name="nama" value="{{ old('form_type') === 'posyandu' ? old('nama') : '' }}" required
                        class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-[13px] focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 shadow-sm"
                        placeholder="Contoh: Posyandu Melati">
                </div>
                <div>
                    <label class="block text-[12px] font-bold text-slate-700 mb-1.5">Desa/Kelurahan</label>
                    <input type="text" name="desa_kelurahan" value="{{ old('form_type') === 'posyandu' ? old('desa_kelurahan') : '' }}" required
                        class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-[13px] focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 shadow-sm"
                        placeholder="Nama desa atau kelurahan">
                </div>
                <div>
                    <label class="block text-[12px] font-bold text-slate-700 mb-1.5">Alamat Lengkap</label>
                    <textarea name="alamat" rows="3" 
                        class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-[13px] focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 shadow-sm"
                        placeholder="Opsional">{{ old('form_type') === 'posyandu' ? old('alamat') : '' }}</textarea>
                </div>
                <div class="mt-2 flex justify-end gap-2">
                    <button type="button" data-close-modal="posyanduModal" class="px-4 py-2 text-[12px] font-bold text-slate-600 bg-white border border-slate-300 rounded-md hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-slate-200">
                        Batal
                    </button>
                    <button type="submit" class="px-4 py-2 text-[12px] font-bold text-white bg-teal-600 rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-teal-500 shadow-sm">
                        Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Add Kader Modal -->
<div id="kaderModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-slate-900/50 backdrop-blur-sm p-4">
    <div class="w-full max-w-md bg-white rounded-xl shadow-xl overflow-hidden border border-slate-200 flex flex-col max-h-[90vh]">
        <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between bg-slate-50">
            <h2 class="text-[15px] font-bold text-slate-800">Tambah Kader Baru</h2>
            <button type="button" data-close-modal="kaderModal" class="text-slate-400 hover:text-slate-600 focus:outline-none">
                <i class="ph-bold ph-x text-lg"></i>
            </button>
        </div>
        <div class="p-5 overflow-y-auto">
            @if ($selectedPosyandu)
                <form action="{{ route('puskesmas.posyandu.kader.store', $selectedPosyandu['id']) }}" method="POST" class="flex flex-col gap-4">
                    @csrf
                    <input type="hidden" name="form_type" value="kader">
                    @if ($errors->any() && old('form_type') === 'kader')
                        <div class="p-3 bg-rose-50 border border-rose-200 rounded-md text-[12px] font-medium text-rose-700">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    <div>
                        <label class="block text-[12px] font-bold text-slate-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="nama" value="{{ old('form_type') === 'kader' ? old('nama') : '' }}" required
                            class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-[13px] focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[12px] font-bold text-slate-700 mb-1.5">Email (Username Login)</label>
                        <input type="email" name="email" value="{{ old('form_type') === 'kader' ? old('email') : '' }}" required
                            class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-[13px] focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[12px] font-bold text-slate-700 mb-1.5">No HP / WhatsApp</label>
                        <input type="text" name="no_hp" value="{{ old('form_type') === 'kader' ? old('no_hp') : '' }}"
                            class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-[13px] focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                    </div>
                    <div>
                        <label class="block text-[12px] font-bold text-slate-700 mb-1.5">Password Awal</label>
                        <input type="password" name="password" required minlength="8"
                            class="w-full px-3 py-2 bg-white border border-slate-300 rounded-md text-[13px] focus:outline-none focus:ring-1 focus:ring-teal-500 focus:border-teal-500 shadow-sm">
                        <span class="block text-[10px] text-slate-500 mt-1">Minimal 8 karakter.</span>
                    </div>
                    <div class="mt-2 flex justify-end gap-2">
                        <button type="button" data-close-modal="kaderModal" class="px-4 py-2 text-[12px] font-bold text-slate-600 bg-white border border-slate-300 rounded-md hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-slate-200">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 text-[12px] font-bold text-white bg-teal-600 rounded-md hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-offset-1 focus:ring-teal-500 shadow-sm">
                            Simpan Kader
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
</div>

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
            const errorModal = document.getElementById('{{ old('form_type') }}Modal');
            if (errorModal) errorModal.classList.replace('hidden', 'flex');
        @endif
    });
</script>
@endpush
@endsection
