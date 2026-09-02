@extends('layouts.app')

@section('page-title', 'Jadwal Posyandu')

@php
    $upcoming = collect($jadwals)->whereIn('status_type', ['upcoming', 'today'])->values();
    $past     = collect($jadwals)->where('status_type', 'past')->values();
    // sesi terdekat (tanggal paling awal dari yang mendatang)
    $next = $upcoming->sortBy('raw_tanggal')->first();
    $cardActions = fn($j) => $j['status_type'] === 'past';
    @endphp

@section('content')
<div class="bg-slate-50 min-h-full">
<div class="max-w-6xl mx-auto w-full px-4 sm:px-6 pt-5 sm:pt-8 pb-28 sm:pb-12" x-data="jadwalPage()">
    <script>window.__NUTRI_JADWALS = @json($jadwals, 15); window.__NUTRI_NEXT = @json($next, 15);</script>

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-5">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">Jadwal Posyandu</h1>
            <p class="text-[13px] text-slate-500 mt-0.5 flex items-center gap-1.5"><x-icon name="calendar-blank" weight="bold" class="text-[14px] text-teal-600" /> {{ $posyanduName ?? 'Posyandu Kader' }} · Jadwal otomatis tersinkron ke Portal Ibu.</p>
        </div>
        <button type="button" @click="openCreate()" class="inline-flex items-center justify-center gap-2 h-11 px-5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-[14px] font-semibold shadow-md shadow-teal-600/15 transition-colors">
            <x-icon name="plus" weight="bold" class="text-[16px]" /> Tambah Jadwal
        </button>
    </div>

    @if(session('success'))
    <div class="mb-5 rounded-xl bg-teal-50 border border-teal-200 px-4 py-3 text-[13px] text-teal-900 flex items-center gap-2.5">
        <span class="w-8 h-8 shrink-0 rounded-lg bg-teal-600 text-white flex items-center justify-center"><x-icon name="check" weight="bold" class="text-[15px]" /></span>
        {{ session('success') }}
    </div>
    @endif

    {{-- Sesi Terdekat (Spotlight) --}}
    @if($next)
    <section class="mb-6">
        <div class="rounded-2xl border border-teal-200 bg-gradient-to-r from-teal-600 to-teal-700 text-white overflow-hidden">
            <div class="p-5 sm:p-6 flex flex-col lg:flex-row lg:items-center gap-4">
                <div class="flex items-center gap-4 min-w-0 flex-1">
                    <div class="w-16 h-16 shrink-0 rounded-2xl bg-white/15 ring-1 ring-white/30 flex flex-col items-center justify-center">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-teal-100">{{ $next['tgl_bulan_singkat'] }}</span>
                        <span class="text-[24px] font-black leading-none">{{ $next['tgl_nomor'] }}</span>
                    </div>
                    <div class="min-w-0">
                        <div class="flex items-center gap-2 flex-wrap">
                            <span class="text-[10.5px] font-bold uppercase tracking-wider bg-white/20 px-2 py-0.5 rounded-lg">{{ $next['status'] }}</span>
                            @if($next['countdown'] && $next['status_type'] !== 'past')<span class="text-[10.5px] font-bold uppercase tracking-wider bg-amber-400/90 text-amber-950 px-2 py-0.5 rounded-lg">{{ $next['countdown'] }}</span>@endif
                        </div>
                        <h2 class="text-[17px] sm:text-lg font-bold leading-snug mt-1 line-clamp-2">{{ $next['judul'] }}</h2>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-1.5 text-[12px] text-teal-50">
                            <span class="inline-flex items-center gap-1.5"><x-icon name="calendar-blank" weight="bold" class="text-[13px]" /> {{ $next['hari'] }}, {{ $next['tanggal'] }}</span>
                            <span class="inline-flex items-center gap-1.5"><x-icon name="clock" weight="bold" class="text-[13px]" /> {{ $next['waktu'] }}</span>
                        </div>
                        <span class="inline-flex items-center gap-1.5 mt-1 text-[12px] text-teal-50"><x-icon name="map-pin" weight="bold" class="text-[13px]" /> {{ $next['lokasi'] }}</span>
                    </div>
                </div>
                <div class="shrink-0 flex items-center gap-2">
                    <button type="button" @click="askNotif(next.id)" class="inline-flex items-center justify-center gap-2 h-11 px-4 rounded-xl border border-white/30 bg-white/10 hover:bg-white/20 text-white text-[13.5px] font-semibold transition-colors"><x-icon name="bell" weight="bold" class="text-[15px]" /> Kirim Notifikasi</button>
                    <button type="button" @click="openDetail(next.id)" class="inline-flex items-center justify-center gap-2 h-11 px-5 rounded-xl bg-white hover:bg-teal-50 text-teal-700 text-[14px] font-semibold transition-colors"><x-icon name="eye" weight="bold" class="text-[15px]" /> Detail</button>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Grup Jadwal --}}
    @if(count($jadwals) === 0)
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 sm:p-10 text-center flex flex-col items-center">
        <span class="w-14 h-14 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center mb-3"><x-icon name="calendar-plus" weight="fill" class="text-[26px]" /></span>
        <h3 class="text-base font-bold text-slate-900">Belum Ada Jadwal Posyandu</h3>
        <p class="text-[13px] text-slate-500 mt-1 max-w-xs leading-relaxed">Buat jadwal pertama agar para Ibu menerima pengingat penimbangan di aplikasi.</p>
        <button type="button" @click="openCreate()" class="mt-4 inline-flex items-center gap-2 h-11 px-5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-[14px] font-semibold transition-colors"><x-icon name="plus" weight="bold" class="text-[16px]" /> Buat Jadwal Pertama</button>
    </div>
    @endif

    @if(count($upcoming) > 0)
    <section class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <span class="w-1 h-6 bg-teal-600 rounded-full"></span>
            <h2 class="text-base font-bold text-slate-900">Jadwal Mendatang</h2>
            <span class="text-[12px] font-semibold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-lg">{{ count($upcoming) }}</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-5">
            @foreach($upcoming as $j)
            <article class="group flex flex-col bg-white border border-slate-200 hover:border-teal-300 rounded-2xl shadow-sm hover:shadow-md transition-all overflow-hidden">
                <div class="p-5 flex items-start gap-4">
                    <div class="w-12 shrink-0 rounded-xl {{ $j['status_type']==='today' ? 'bg-amber-50 text-amber-700 ring-1 ring-amber-200' : 'bg-teal-50 text-teal-700 ring-1 ring-teal-100' }} flex flex-col items-center justify-center py-1.5">
                        <span class="text-[9px] font-black uppercase tracking-wider">{{ $j['tgl_bulan_singkat'] }}</span>
                        <span class="text-[18px] font-black leading-none">{{ $j['tgl_nomor'] }}</span>
                        <span class="text-[8.5px] font-bold uppercase text-slate-400">{{ substr($j['hari'], 0, 3) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-[14px] font-bold text-slate-800 group-hover:text-teal-700 transition-colors leading-snug line-clamp-2">{{ $j['judul'] }}</h3>
                        <div class="mt-2 flex flex-col gap-1 text-[12px] text-slate-500 font-medium">
                            <span class="inline-flex items-center gap-1.5"><x-icon name="clock" weight="regular" class="text-[14px] text-slate-400" /> {{ $j['waktu'] }}</span>
                            <span class="inline-flex items-center gap-1.5"><x-icon name="map-pin" weight="regular" class="text-[14px] text-slate-400" /> <span class="line-clamp-2">{{ $j['lokasi'] }}</span></span>
                        </div>
                    </div>
                </div>
                @if(!empty($j['catatan']))
                <div class="mx-5 mb-4 px-3 py-2 rounded-xl bg-slate-50 border border-slate-100 text-[11.5px] text-slate-600 flex items-start gap-2">
                    <x-icon name="info" weight="regular" class="text-teal-600 text-[14px] mt-0.5 shrink-0" />
                    <span class="line-clamp-2 leading-relaxed">{{ $j['catatan'] }}</span>
                </div>
                @endif
                <div class="mt-auto border-t border-slate-100 px-5 py-3 flex items-center justify-between gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider {{ $j['status_type']==='today' ? 'bg-amber-50 text-amber-700' : 'bg-teal-50 text-teal-700' }}"><span class="w-1.5 h-1.5 rounded-full {{ $j['status_type']==='today' ? 'bg-amber-500' : 'bg-teal-500' }}"></span>{{ $j['status'] }}</span>
                    <div class="flex items-center gap-1.5">
                        <button type="button" @click="askNotif({{ $j['id'] }})" aria-label="Kirim Notifikasi" title="Kirim Notifikasi" class="h-9 w-9 sm:h-8 sm:w-8 inline-flex items-center justify-center text-teal-600 hover:bg-teal-100 bg-teal-50 border border-teal-200 rounded-lg transition-colors"><x-icon name="bell" weight="bold" class="text-[15px] sm:text-[13px]" /></button>
                        <button type="button" @click="openDetail({{ $j['id'] }})" title="Detail" class="h-9 w-9 sm:w-auto sm:px-2.5 inline-flex items-center justify-center gap-1 text-[11.5px] font-semibold text-teal-700 bg-teal-50 border border-teal-200 hover:bg-teal-100 rounded-lg transition-colors"><x-icon name="eye" weight="bold" class="text-[15px] sm:text-[13px]" /><span class="hidden sm:inline">Detail</span></button>
                        <button type="button" @click="openEdit({{ $j['id'] }})" title="Edit" class="h-9 w-9 sm:w-auto sm:px-2.5 inline-flex items-center justify-center gap-1 text-[11.5px] font-semibold text-teal-700 bg-white border border-teal-200 hover:bg-teal-50 rounded-lg transition-colors"><x-icon name="pencil-line" weight="bold" class="text-[15px] sm:text-[13px]" /><span class="hidden sm:inline">Edit</span></button>
                        <button type="button" @click="askDelete({{ $j['id'] }})" aria-label="Hapus" title="Hapus" class="h-9 w-9 sm:h-8 sm:w-8 inline-flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 border border-slate-200 hover:border-rose-200 rounded-lg transition-colors"><x-icon name="trash" weight="bold" class="text-[15px] sm:text-[13px]" /></button>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </section>
    @endif

    @if(count($past) > 0)
    <section>
        <div class="flex items-center gap-3 mb-4">
            <span class="w-1 h-6 bg-slate-300 rounded-full"></span>
            <h2 class="text-base font-bold text-slate-900">Jadwal Selesai</h2>
            <span class="text-[12px] font-semibold text-slate-500 bg-slate-100 px-2 py-0.5 rounded-lg">{{ count($past) }}</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-5">
            @foreach($past as $j)
            <article class="group flex flex-col bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-5 flex items-start gap-4 opacity-80">
                    <div class="w-12 shrink-0 rounded-xl bg-slate-100 text-slate-500 flex flex-col items-center justify-center py-1.5">
                        <span class="text-[9px] font-black uppercase tracking-wider">{{ $j['tgl_bulan_singkat'] }}</span>
                        <span class="text-[18px] font-black leading-none">{{ $j['tgl_nomor'] }}</span>
                        <span class="text-[8.5px] font-bold uppercase text-slate-400">{{ substr($j['hari'], 0, 3) }}</span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <h3 class="text-[14px] font-bold text-slate-700 leading-snug line-clamp-2">{{ $j['judul'] }}</h3>
                        <div class="mt-2 flex flex-col gap-1 text-[12px] text-slate-500 font-medium">
                            <span class="inline-flex items-center gap-1.5"><x-icon name="clock" weight="regular" class="text-[14px]" /> {{ $j['waktu'] }}</span>
                            <span class="inline-flex items-center gap-1.5"><x-icon name="map-pin" weight="regular" class="text-[14px]" /> <span class="line-clamp-2">{{ $j['lokasi'] }}</span></span>
                        </div>
                    </div>
                </div>
                <div class="mx-5 mb-4 px-3 py-2 rounded-xl bg-slate-50 border border-slate-100 text-[11.5px] text-slate-500 flex items-start gap-2">
                    <x-icon name="check-circle" weight="regular" class="text-slate-400 text-[14px] mt-0.5 shrink-0" />
                    <span class="leading-relaxed">Kegiatan selesai dilaksanakan.</span>
                </div>
                <div class="mt-auto border-t border-slate-100 px-5 py-3 flex items-center justify-between gap-2">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500"><span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span> Selesai</span>
                    <div class="flex items-center gap-1.5">
                        <button type="button" @click="openDetail({{ $j['id'] }})" class="h-8 px-2.5 inline-flex items-center gap-1 text-[11.5px] font-semibold text-teal-700 bg-teal-50 border border-teal-200 hover:bg-teal-100 rounded-lg transition-colors"><x-icon name="eye" weight="bold" class="text-[13px]" /> Detail</button>
                        <a href="{{ route('laporan.index') }}" class="h-8 px-2.5 inline-flex items-center gap-1 text-[11.5px] font-semibold text-teal-700 bg-white border border-teal-200 hover:bg-teal-50 rounded-lg transition-colors"><x-icon name="printer" weight="bold" class="text-[13px]" /> Laporan</a>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </section>
    @endif

    {{-- Create/Edit modal --}}
    <template x-teleport="body">
        <div x-show="formOpen" x-cloak class="fixed inset-0 z-[80] flex items-end sm:items-center justify-center" x-transition.opacity>
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="formOpen = false"></div>
            <div x-show="formOpen" x-transition.scale.origin.bottom class="relative bg-white w-full max-w-xl rounded-t-2xl sm:rounded-2xl shadow-2xl max-h-[92dvh] flex flex-col overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
                    <h2 class="text-[15px] font-bold text-slate-900" x-text="form.id ? 'Edit Jadwal Posyandu' : 'Tambah Jadwal Baru'"></h2>
                    <button type="button" @click="formOpen = false" aria-label="Tutup" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-50 flex items-center justify-center transition-colors"><x-icon name="x" weight="bold" class="text-[16px]" /></button>
                </div>
                <form :action="form.id ? '{{ route('jadwal.update', '__id__') }}'.replace('__id__', form.id) : '{{ route('jadwal.store') }}'" method="POST" class="flex-1 min-h-0 overflow-y-auto px-5 sm:px-6 py-5 space-y-5">
                    @csrf
                    <input type="hidden" name="_method" :value="form.id ? 'PUT' : 'POST'">
                    <div>
                        <label class="block text-[12.5px] font-semibold text-slate-700 mb-1.5">Nama / Judul Kegiatan <span class="text-rose-500">*</span></label>
                        <input type="text" name="judul" x-model="form.judul" required placeholder="Contoh: Penimbangan Rutin & Imunisasi" class="w-full h-11 rounded-xl border border-slate-200 bg-slate-50 px-4 text-[14px] font-medium text-slate-800 placeholder:text-slate-400 shadow-sm focus:outline-none focus:ring-4 focus:ring-teal-500/15 focus:border-teal-600 focus:bg-white transition-all">
                    </div>
                    <div>
                        <label class="block text-[12.5px] font-semibold text-slate-700 mb-1.5">Tempat / Lokasi <span class="text-rose-500">*</span></label>
                        <input type="text" name="lokasi" x-model="form.lokasi" required placeholder="Contoh: Balai Posyandu RW 01" class="w-full h-11 rounded-xl border border-slate-200 bg-slate-50 px-4 text-[14px] font-medium text-slate-800 placeholder:text-slate-400 shadow-sm focus:outline-none focus:ring-4 focus:ring-teal-500/15 focus:border-teal-600 focus:bg-white transition-all">
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[12.5px] font-semibold text-slate-700 mb-1.5">Tanggal <span class="text-rose-500">*</span></label>
                            <input type="date" name="tanggal" x-model="form.tanggal" required class="w-full h-11 rounded-xl border border-slate-200 bg-slate-50 px-4 text-[14px] font-medium text-slate-800 shadow-sm focus:outline-none focus:ring-4 focus:ring-teal-500/15 focus:border-teal-600 focus:bg-white transition-all appearance-none">
                        </div>
                        <div>
                            <label class="block text-[12.5px] font-semibold text-slate-700 mb-1.5">Jam Mulai & Selesai <span class="text-rose-500">*</span></label>
                            <div class="flex items-center gap-2">
                                <input type="time" name="waktu_mulai" x-model="form.mulai" required class="w-full h-11 rounded-xl border border-slate-200 bg-slate-50 px-3 text-[14px] font-medium text-slate-800 shadow-sm focus:outline-none focus:ring-4 focus:ring-teal-500/15 focus:border-teal-600 focus:bg-white transition-all">
                                <span class="text-slate-400 font-bold">–</span>
                                <input type="time" name="waktu_selesai" x-model="form.selesai" required class="w-full h-11 rounded-xl border border-slate-200 bg-slate-50 px-3 text-[14px] font-medium text-slate-800 shadow-sm focus:outline-none focus:ring-4 focus:ring-teal-500/15 focus:border-teal-600 focus:bg-white transition-all">
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="flex items-center justify-between text-[12.5px] font-semibold text-slate-700 mb-1.5"><span>Catatan untuk Ibu Balita</span><span class="text-[11px] font-medium text-slate-400">Opsional</span></label>
                        <textarea name="catatan" x-model="form.catatan" rows="3" placeholder="Contoh: Harap membawa Buku KIA." class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-[14px] font-medium text-slate-800 placeholder:text-slate-400 shadow-sm focus:outline-none focus:ring-4 focus:ring-teal-500/15 focus:border-teal-600 focus:bg-white transition-all resize-none"></textarea>
                    </div>
                    <div class="flex items-center justify-end gap-2.5 border-t border-slate-100 pt-4">
                        <button type="button" @click="formOpen = false" class="h-11 px-5 rounded-xl border border-teal-200 bg-teal-50 text-teal-700 text-[13.5px] font-semibold hover:bg-teal-100 transition-colors">Batal</button>
                        <button type="submit" class="h-11 px-6 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-[13.5px] font-semibold transition-colors inline-flex items-center gap-2 shadow-md shadow-teal-600/20"><x-icon name="check" weight="bold" class="text-[15px]" /> <span x-text="form.id ? 'Simpan Perubahan' : 'Simpan & Terbitkan'"></span></button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    {{-- Detail modal --}}
    <template x-teleport="body">
        <div x-show="detail" x-cloak class="fixed inset-0 z-[80] flex items-end sm:items-center justify-center" x-transition.opacity>
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="detail = null"></div>
            <div x-show="detail" x-transition.scale.origin.bottom class="relative bg-white w-full max-w-lg rounded-t-2xl sm:rounded-2xl shadow-2xl max-h-[92dvh] overflow-hidden">
                <div class="px-5 sm:px-6 py-4 border-b border-slate-100 flex items-center justify-between shrink-0">
                    <span class="text-[15px] font-bold text-slate-900">Detail Agenda</span>
                    <button type="button" @click="detail = null" aria-label="Tutup" class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-50 flex items-center justify-center transition-colors"><x-icon name="x" weight="bold" class="text-[16px]" /></button>
                </div>
                <div class="px-5 sm:px-6 py-5 space-y-4 max-h-[70dvh] overflow-y-auto">
                    <template x-if="detail">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap mb-2">
                                <span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-lg" :class="detail.status_type === 'past' ? 'bg-slate-100 text-slate-500' : (detail.status_type === 'today' ? 'bg-amber-50 text-amber-700' : 'bg-teal-50 text-teal-700')" x-text="detail.status"></span>
                                <template x-if="detail.countdown && detail.status_type === 'upcoming'"><span class="text-[10px] font-bold uppercase tracking-wider px-2 py-0.5 rounded-lg bg-cyan-50 text-cyan-700" x-text="detail.countdown"></span></template>
                            </div>
                            <h3 class="text-[17px] font-bold text-slate-900 leading-snug" x-text="detail.judul"></h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-4">
                                <div class="p-3.5 rounded-xl border border-slate-100 flex items-center gap-3"><span class="w-9 h-9 shrink-0 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center"><x-icon name="calendar-blank" weight="bold" class="text-[16px]" /></span><div class="min-w-0"><p class="text-[9.5px] font-bold text-slate-400 uppercase tracking-wide">Hari & Tanggal</p><p class="text-[13px] font-bold text-slate-800 truncate" x-text="detail.hari + ', ' + detail.tanggal"></p></div></div>
                                <div class="p-3.5 rounded-xl border border-slate-100 flex items-center gap-3"><span class="w-9 h-9 shrink-0 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center"><x-icon name="clock" weight="bold" class="text-[16px]" /></span><div class="min-w-0"><p class="text-[9.5px] font-bold text-slate-400 uppercase tracking-wide">Waktu</p><p class="text-[13px] font-bold text-slate-800 truncate" x-text="detail.waktu"></p></div></div>
                            </div>
                            <div class="p-3.5 rounded-xl border border-slate-100 flex items-center gap-3 mt-3"><span class="w-9 h-9 shrink-0 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center"><x-icon name="map-pin" weight="bold" class="text-[16px]" /></span><div class="min-w-0"><p class="text-[9.5px] font-bold text-slate-400 uppercase tracking-wide">Lokasi</p><p class="text-[13px] font-bold text-slate-800" x-text="detail.lokasi"></p></div></div>
                            <template x-if="detail.catatan">
                                <div class="mt-3 p-3.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-900 text-[12.5px] leading-relaxed"><span class="font-bold flex items-center gap-1.5 mb-1"><x-icon name="info" weight="fill" class="text-[14px]" /> Catatan Kader</span><span x-text="detail.catatan"></span></div>
                            </template>
                        </div>
                    </template>
                </div>
                <div class="px-5 sm:px-6 py-4 border-t border-slate-100 flex items-center justify-end gap-2.5 shrink-0">
                    <button type="button" @click="detail = null" class="h-10 px-5 rounded-xl border border-teal-200 bg-teal-50 text-teal-700 text-[13.5px] font-semibold hover:bg-teal-100 transition-colors">Tutup</button>
                    <template x-if="detail && detail.status_type !== 'past'"><button type="button" @click="askNotif(detail.id)" class="h-10 px-4 rounded-xl border border-teal-200 bg-teal-50 text-teal-700 text-[13.5px] font-semibold hover:bg-teal-100 transition-colors inline-flex items-center gap-2"><x-icon name="bell" weight="bold" class="text-[15px]" /> Kirim Notifikasi</button></template>
                    <button type="button" @click="openEditFromDetail()" class="h-10 px-5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-[13.5px] font-semibold transition-colors inline-flex items-center gap-2 shadow-md shadow-teal-600/20"><x-icon name="pencil-line" weight="bold" class="text-[15px]" /> Edit</button>
                </div>
            </div>
        </div>
    </template>

    {{-- Delete confirm modal --}}
    <template x-teleport="body">
        <div x-show="deleteId" x-cloak class="fixed inset-0 z-[90] flex items-center justify-center p-4" x-transition.opacity>
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="deleteId = null"></div>
            <div x-show="deleteId" x-transition.scale.origin.center class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl p-6">
                <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center mx-auto"><x-icon name="warning" weight="fill" class="text-[22px]" /></div>
                <h3 class="text-center text-[16px] font-bold text-slate-900 mt-3">Hapus Jadwal?</h3>
                <p class="text-center text-[13px] text-slate-500 mt-1.5 leading-relaxed">Jadwal ini tidak akan tampil lagi di Portal Ibu Balita.</p>
                <form :action="'{{ route('jadwal.destroy', '__id__') }}'.replace('__id__', deleteId)" method="POST" class="grid grid-cols-2 gap-2.5 mt-5">
                    @csrf @method('DELETE')
                    <button type="button" @click="deleteId = null" class="h-11 rounded-xl border border-teal-200 bg-teal-50 text-teal-700 text-[13.5px] font-semibold hover:bg-teal-100 transition-colors">Batal</button>
                    <button type="submit" class="h-11 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-[13.5px] font-semibold inline-flex items-center justify-center gap-2 transition-colors"><x-icon name="trash" weight="bold" class="text-[15px]" /> Ya, Hapus</button>
                </form>
            </div>
        </div>
    </template>

    {{-- Kirim notifikasi confirm modal --}}
    <template x-teleport="body">
        <div x-show="notifId" x-cloak class="fixed inset-0 z-[90] flex items-center justify-center p-4" x-transition.opacity>
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm" @click="notifId = null"></div>
            <div x-show="notifId" x-transition.scale.origin.center class="relative w-full max-w-sm bg-white rounded-2xl shadow-2xl p-6">
                <div class="w-12 h-12 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center mx-auto"><x-icon name="bell" weight="fill" class="text-[22px]" /></div>
                <h3 class="text-center text-[16px] font-bold text-slate-900 mt-3">Kirim Notifikasi?</h3>
                <p class="text-center text-[13px] text-slate-500 mt-1.5 leading-relaxed">Pengingat WhatsApp tentang jadwal posyandu akan dikirim ke <span class="font-semibold text-slate-700">semua Ibu balita di posyandu ini</span>.</p>
                <form :action="'{{ route('jadwal.notif', '__id__') }}'.replace('__id__', notifId)" method="POST" class="grid grid-cols-2 gap-2.5 mt-5">
                    @csrf
                    <button type="button" @click="notifId = null" class="h-11 rounded-xl border border-teal-200 bg-teal-50 text-teal-700 text-[13.5px] font-semibold hover:bg-teal-100 transition-colors">Batal</button>
                    <button type="submit" class="h-11 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-[13.5px] font-semibold inline-flex items-center justify-center gap-2 transition-colors"><x-icon name="bell" weight="bold" class="text-[15px]" /> Ya, Kirim</button>
                </form>
            </div>
        </div>
    </template>

</div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('jadwalPage', () => ({
        formOpen: false,
        detail: null,
        deleteId: null,
        notifId: null,
        jadwals: [],
        next: null,
        form: { id: null, judul: '', lokasi: '', tanggal: '', mulai: '08:30', selesai: '11:30', catatan: '' },
        init() { this.jadwals = window.__NUTRI_JADWALS || []; this.next = window.__NUTRI_NEXT || null; },
        byId(id) { return this.jadwals.find(x => x.id === id); },
        openCreate() { this.form = { id: null, judul: '', lokasi: '', tanggal: new Date().toISOString().split('T')[0], mulai: '08:30', selesai: '11:30', catatan: '' }; this.formOpen = true; },
        openEdit(id) { const j = this.byId(id); if (!j) return; this.form = { id: j.id, judul: j.judul || '', lokasi: j.lokasi || '', tanggal: j.raw_tanggal || '', mulai: j.waktu_mulai || '08:30', selesai: j.waktu_selesai || '11:30', catatan: j.catatan || '' }; this.detail = null; this.formOpen = true; },
        openDetail(id) { this.detail = this.byId(id); },
        openEditFromDetail() { if (this.detail) this.openEdit(this.detail.id); },
        askDelete(id) { this.deleteId = id; },
        askNotif(id) { this.detail = null; this.notifId = id; },
    }));
});
</script>
@endsection
