@extends('layouts.app')

@section('page-title', 'Jadwal Posyandu')

@php
    $upcoming = collect($jadwals)->whereIn('status_type', ['upcoming', 'today'])->values();
    $past     = collect($jadwals)->where('status_type', 'past')->values();
    // sesi terdekat (tanggal paling awal dari yang mendatang)
    $next = $upcoming->sortBy('raw_tanggal')->first();
    // list mendatang TANPA next (hindari duplikasi di hero + list)
    $upcomingList = $upcoming->filter(fn($j) => $j['id'] !== ($next['id'] ?? null))->values();
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
        <button type="button" @click="openCreate()" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-11 px-6 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-[14px] font-semibold shadow-md shadow-teal-600/15 transition-colors">
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
        <div class="relative rounded-2xl border border-teal-600/15 bg-gradient-to-br from-white via-teal-50/40 to-teal-100/40 shadow-sm overflow-hidden group/hero cursor-pointer hover:shadow-md transition-shadow" @click="openDetail(next.id)">
            
            <div class="relative p-5 sm:p-6 flex flex-col lg:flex-row lg:items-center gap-5 sm:gap-6">
                <!-- Info Section -->
                <div class="flex items-start sm:items-center gap-4 sm:gap-5 min-w-0 flex-1">
                    <div class="w-14 h-14 sm:w-16 sm:h-16 shrink-0 rounded-2xl bg-white/90 border border-teal-600/10 flex flex-col items-center justify-center shadow-sm">
                        <span class="text-[9px] sm:text-[10px] font-bold uppercase tracking-wider text-teal-600">{{ $next['tgl_bulan_singkat'] }}</span>
                        <span class="text-[22px] sm:text-[24px] font-black leading-none text-slate-800">{{ $next['tgl_nomor'] }}</span>
                    </div>
                    <div class="min-w-0 flex-1 pt-0.5 sm:pt-0">
                        <div class="flex items-center gap-2 flex-wrap mb-1.5">
                            @if($next['status_type'] === 'past')
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-600 px-2 py-0.5 rounded-md"><x-icon name="check-circle" weight="fill" class="text-[12px]" /> Selesai</span>
                            @else
                            <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider bg-amber-100 text-amber-800 px-2 py-0.5 rounded-md"><x-icon name="{{ $next['status_type']==='today' ? 'warning' : 'hourglass' }}" weight="fill" class="text-[12px]" /> {{ $next['countdown'] }}</span>
                            @endif
                        </div>
                        <h2 class="text-[16px] sm:text-[18px] font-bold text-slate-900 leading-snug line-clamp-2 group-hover/hero:text-teal-700 transition-colors">{{ $next['judul'] }}</h2>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-1.5 mt-2.5 text-[12.5px] text-slate-700 font-medium">
                            <span class="inline-flex items-center gap-1.5"><x-icon name="calendar-blank" weight="fill" class="text-[14px] text-teal-600/80" /> {{ $next['hari'] }}, {{ $next['tanggal'] }}</span>
                            <span class="inline-flex items-center gap-1.5"><x-icon name="clock" weight="fill" class="text-[14px] text-teal-600/80" /> {{ $next['waktu'] }}</span>
                        </div>
                        <div class="mt-1.5 flex">
                            <span class="inline-flex items-start gap-1.5 text-[12.5px] text-slate-700 font-medium"><x-icon name="map-pin" weight="fill" class="text-[14px] text-teal-600/80 shrink-0 mt-0.5" /> <span class="line-clamp-1">{{ $next['lokasi'] }}</span></span>
                        </div>
                    </div>
                </div>

                <!-- Actions Section -->
                <div class="shrink-0 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 mt-3 lg:mt-0 border-t border-teal-600/10 lg:border-t-0 pt-4 lg:pt-0" @click.stop>
                    <button type="button" @click="askNotif(next.id)" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-11 px-5 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-[13.5px] font-semibold transition-all active:scale-95 shadow-sm shadow-teal-600/20"><x-icon name="bell" weight="bold" class="text-[15px]" /> <span class="whitespace-nowrap">Notifikasi</span></button>
                    <button type="button" @click="openEdit(next.id)" class="w-full sm:w-auto inline-flex items-center justify-center gap-2 h-11 px-5 rounded-xl bg-amber-400 hover:bg-amber-500 text-amber-950 text-[13.5px] font-semibold transition-all active:scale-95 shadow-sm shadow-amber-400/20"><x-icon name="pencil-line" weight="bold" class="text-[15px]" /> <span class="whitespace-nowrap">Edit</span></button>
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

    @if(count($upcomingList) > 0)
    <section class="mb-8">
        <div class="flex items-center gap-3 mb-4">
            <span class="w-1 h-6 bg-teal-600 rounded-full"></span>
            <h2 class="text-base font-bold text-slate-900">Jadwal Mendatang</h2>
            <span class="text-[12px] font-semibold text-teal-700 bg-teal-50 px-2 py-0.5 rounded-lg">{{ count($upcomingList) }}</span>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4 sm:gap-5">
            @foreach($upcomingList as $j)
            <article class="group flex flex-col bg-white border border-slate-200 hover:border-teal-600/30 rounded-2xl shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-200 overflow-hidden cursor-pointer" @click="openDetail({{ $j['id'] }})">
                <div class="p-5 flex items-start gap-4">
                    <div class="w-14 h-14 shrink-0 rounded-xl {{ $j['status_type']==='today' ? 'bg-amber-50 text-amber-700 border border-amber-200/60' : 'bg-slate-50 text-slate-700 border border-slate-200/60' }} flex flex-col items-center justify-center shadow-sm">
                        <span class="text-[9px] font-bold uppercase tracking-wider {{ $j['status_type']==='today' ? 'text-amber-600' : 'text-teal-600' }}">{{ $j['tgl_bulan_singkat'] }}</span>
                        <span class="text-[20px] font-black leading-none text-slate-800 mt-0.5">{{ $j['tgl_nomor'] }}</span>
                    </div>
                    <div class="flex-1 min-w-0 pt-0.5">
                        <h3 class="text-[15px] font-bold text-slate-900 group-hover:text-teal-700 transition-colors leading-snug line-clamp-2">{{ $j['judul'] }}</h3>
                        <div class="mt-2 flex flex-col gap-1 text-[12.5px] text-slate-600 font-medium">
                            <span class="inline-flex items-center gap-2"><x-icon name="clock" weight="fill" class="text-[14px] text-slate-400 shrink-0" /> {{ $j['waktu'] }}</span>
                            <span class="inline-flex items-start gap-2"><x-icon name="map-pin" weight="fill" class="text-[14px] text-slate-400 shrink-0 mt-0.5" /> <span class="line-clamp-1">{{ $j['lokasi'] }}</span></span>
                        </div>
                    </div>
                </div>
                @if(!empty($j['catatan']))
                <div class="mx-5 mb-4 px-3.5 py-2.5 rounded-xl bg-teal-50/60 border border-teal-100/80 text-[12.5px] text-teal-900/80 flex items-start gap-2.5">
                    <x-icon name="info" weight="fill" class="text-teal-600 text-[16px] shrink-0 mt-0.5" />
                    <span class="line-clamp-2 leading-relaxed font-medium">{{ $j['catatan'] }}</span>
                </div>
                @endif
                <div class="mt-auto border-t border-slate-100 p-3 sm:px-4 flex items-center justify-between gap-3 bg-slate-50/50" @click.stop>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[11px] font-bold uppercase tracking-wider {{ $j['status_type']==='today' ? 'bg-amber-100 text-amber-800' : 'bg-teal-50 text-teal-700' }}"><span class="w-1.5 h-1.5 rounded-full {{ $j['status_type']==='today' ? 'bg-amber-500' : 'bg-teal-500' }}"></span>{{ $j['countdown'] }}</span>
                    <div class="flex items-center gap-1 sm:gap-1.5">
                        <button type="button" @click="askNotif({{ $j['id'] }})" class="h-10 w-10 sm:h-8 sm:w-auto sm:px-2.5 rounded-lg inline-flex items-center justify-center gap-1.5 text-teal-600 hover:text-teal-800 hover:bg-teal-100/50 transition-colors" title="Kirim Notifikasi"><x-icon name="bell" weight="fill" class="text-[17px] sm:text-[14px]" /><span class="hidden sm:inline text-[12px] font-semibold">Notifikasi</span></button>
                        <button type="button" @click="openEdit({{ $j['id'] }})" class="h-10 w-10 sm:h-8 sm:w-auto sm:px-2.5 rounded-lg inline-flex items-center justify-center gap-1.5 text-amber-950 bg-amber-400 hover:bg-amber-500 transition-colors shadow-sm shadow-amber-400/20" title="Edit"><x-icon name="pencil-line" weight="fill" class="text-[17px] sm:text-[14px]" /><span class="hidden sm:inline text-[12px] font-bold">Edit</span></button>
                        <div class="w-px h-5 sm:h-4 bg-slate-200 mx-1"></div>
                        <button type="button" @click="askDelete({{ $j['id'] }})" class="h-10 w-10 sm:h-8 sm:w-8 rounded-lg inline-flex items-center justify-center text-rose-500 hover:text-rose-700 hover:bg-rose-100/50 transition-colors" title="Hapus"><x-icon name="trash" weight="fill" class="text-[17px] sm:text-[14px]" /></button>
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
                    <div class="flex flex-col-reverse sm:flex-row items-stretch sm:items-center justify-end gap-2.5 border-t border-slate-100 pt-5">
                        <button type="button" @click="formOpen = false" class="w-full sm:w-auto h-11 px-5 rounded-xl border border-slate-200 bg-white text-slate-700 text-[13.5px] font-semibold hover:bg-slate-50 transition-colors">Batal</button>
                        <button type="submit" class="w-full sm:w-auto h-11 px-6 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-[13.5px] font-semibold transition-colors inline-flex items-center justify-center gap-2 shadow-md shadow-teal-600/20"><x-icon name="check" weight="bold" class="text-[15px]" /> <span x-text="form.id ? 'Simpan Perubahan' : 'Simpan & Terbitkan'"></span></button>
                    </div>
                </form>
            </div>
        </div>
    </template>

    {{-- Detail modal --}}
    <template x-teleport="body">
        <div x-show="detail" x-cloak class="fixed inset-0 z-[80] flex items-end sm:items-center justify-center" x-transition.opacity>
            <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm" @click="detail = null"></div>
            <div x-show="detail" x-transition.scale.origin.bottom class="relative bg-white w-full max-w-md rounded-t-3xl sm:rounded-3xl shadow-2xl max-h-[92dvh] overflow-hidden flex flex-col">
                <div class="absolute top-0 inset-x-0 h-1 bg-gradient-to-r from-teal-400 to-emerald-400"></div>
                <div class="px-6 py-5 flex items-center justify-between shrink-0 border-b border-slate-100">
                    <span class="text-[16px] font-bold text-slate-900">Detail Agenda</span>
                    <button type="button" @click="detail = null" aria-label="Tutup" class="w-8 h-8 rounded-full bg-slate-100 text-slate-500 hover:text-slate-700 hover:bg-slate-200 flex items-center justify-center transition-colors"><x-icon name="x" weight="bold" class="text-[14px]" /></button>
                </div>
                <div class="px-6 pb-6 pt-5 space-y-4 max-h-[70dvh] overflow-y-auto">
                    <template x-if="detail">
                        <div>
                            <div class="flex items-center gap-2 mb-3">
                                <span class="inline-flex items-center gap-1.5 text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md" :class="detail.status_type === 'past' ? 'bg-slate-100 text-slate-500' : (detail.status_type === 'today' ? 'bg-amber-100 text-amber-800' : 'bg-teal-50 text-teal-700')">
                                    <span class="w-1.5 h-1.5 rounded-full" :class="detail.status_type === 'past' ? 'bg-slate-400' : (detail.status_type === 'today' ? 'bg-amber-500' : 'bg-teal-500')"></span>
                                    <span x-text="detail.status"></span>
                                </span>
                                <template x-if="detail.countdown && detail.status_type === 'upcoming'">
                                    <span class="inline-flex items-center text-[10px] font-bold uppercase tracking-wider px-2.5 py-1 rounded-md bg-amber-100 text-amber-800" x-text="detail.countdown"></span>
                                </template>
                            </div>
                            <h3 class="text-[18px] sm:text-[20px] font-bold text-slate-900 leading-snug" x-text="detail.judul"></h3>
                            
                            <div class="mt-6 bg-slate-50/50 rounded-2xl border border-slate-100/80 overflow-hidden">
                                <div class="p-4 flex items-start gap-4">
                                    <div class="w-9 h-9 rounded-xl bg-white shadow-sm border border-slate-100 flex items-center justify-center text-teal-600 shrink-0"><x-icon name="calendar-blank" weight="fill" class="text-[16px]" /></div>
                                    <div class="min-w-0 pt-0.5">
                                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Hari & Tanggal</p>
                                        <p class="text-[14px] font-semibold text-slate-800" x-text="detail.hari + ', ' + detail.tanggal"></p>
                                    </div>
                                </div>
                                <div class="h-px bg-slate-100/80 mx-4"></div>
                                <div class="p-4 flex items-start gap-4">
                                    <div class="w-9 h-9 rounded-xl bg-white shadow-sm border border-slate-100 flex items-center justify-center text-teal-600 shrink-0"><x-icon name="clock" weight="fill" class="text-[16px]" /></div>
                                    <div class="min-w-0 pt-0.5">
                                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Waktu</p>
                                        <p class="text-[14px] font-semibold text-slate-800" x-text="detail.waktu"></p>
                                    </div>
                                </div>
                                <div class="h-px bg-slate-100/80 mx-4"></div>
                                <div class="p-4 flex items-start gap-4">
                                    <div class="w-9 h-9 rounded-xl bg-white shadow-sm border border-slate-100 flex items-center justify-center text-teal-600 shrink-0"><x-icon name="map-pin" weight="fill" class="text-[16px]" /></div>
                                    <div class="min-w-0 pt-0.5">
                                        <p class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-0.5">Lokasi</p>
                                        <p class="text-[14px] font-semibold text-slate-800 leading-snug" x-text="detail.lokasi"></p>
                                    </div>
                                </div>
                            </div>
                            
                            <template x-if="detail.catatan">
                                <div class="mt-4 p-4 rounded-xl bg-teal-50/60 border border-teal-100/80 text-teal-900/90 text-[13px] leading-relaxed flex items-start gap-3.5 shadow-sm shadow-teal-100/20">
                                    <x-icon name="info" weight="fill" class="text-teal-600 text-[18px] shrink-0 mt-0.5" />
                                    <div class="flex-1">
                                        <span class="block font-bold text-teal-900 mb-0.5 text-[11px] uppercase tracking-wider">Catatan Khusus</span>
                                        <span class="font-medium" x-text="detail.catatan"></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                </div>
                <div class="p-5 border-t border-slate-100 flex flex-col-reverse sm:flex-row items-stretch sm:items-center sm:justify-end gap-2.5 shrink-0 bg-slate-50/50">
                    <button type="button" @click="detail = null" class="h-11 px-5 rounded-xl border border-slate-200 bg-white hover:bg-slate-50 text-slate-700 text-[13.5px] font-semibold transition-all active:scale-95 shadow-sm flex items-center justify-center">Tutup</button>
                    <template x-if="detail && detail.status_type !== 'past'"><button type="button" @click="askNotif(detail.id)" class="h-11 px-5 rounded-xl bg-teal-50 hover:bg-teal-100 text-teal-700 text-[13.5px] font-semibold transition-all active:scale-95 inline-flex items-center justify-center gap-2"><x-icon name="bell" weight="bold" class="text-[15px]" /> <span class="whitespace-nowrap">Kirim Notifikasi</span></button></template>
                    <button type="button" @click="openEditFromDetail()" class="h-11 px-6 rounded-xl bg-amber-400 hover:bg-amber-500 text-amber-950 text-[13.5px] font-semibold transition-all active:scale-95 inline-flex items-center justify-center gap-2 shadow-sm shadow-amber-400/20"><x-icon name="pencil-line" weight="bold" class="text-[15px]" /> <span class="whitespace-nowrap">Edit</span></button>
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
