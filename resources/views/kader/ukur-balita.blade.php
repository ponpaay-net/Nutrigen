@extends('layouts.app')

@section('page-title', 'Ukur Balita')

@php
    $inp = 'w-full h-11 sm:h-12 rounded-xl border border-slate-200 bg-slate-50 px-4 text-[14px] sm:text-[15px] font-semibold text-slate-800 placeholder:text-slate-300 shadow-sm focus:outline-none focus:ring-4 focus:ring-teal-500/15 focus:border-teal-600 focus:bg-white transition-all';
    $lbl = 'block text-[12.5px] sm:text-[13px] font-semibold text-slate-700';
    $field = 'flex flex-col gap-1.5';
    $secHdr = 'flex items-center gap-3 mb-4 sm:mb-5 pb-4 border-b border-slate-100';
    @endphp

@section('content')
<div class="bg-slate-50 min-h-full">
    <div class="max-w-3xl mx-auto w-full px-4 sm:px-6 py-5 sm:py-8">

        {{-- Header --}}
        <div class="flex items-center gap-3 mb-4 sm:mb-6">
            <a href="{{ route('balita.show', $balitaId) }}" aria-label="Kembali"
               class="w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 flex items-center justify-center transition-colors">
                <x-icon name="arrow-left" weight="bold" class="text-[15px] sm:text-[17px]" />
            </a>
            <div>
                <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight leading-tight">Catat Pengukuran</h1>
                <p class="text-[12px] sm:text-[13px] text-slate-500">Isi hasil pengukuran balita hari ini.</p>
            </div>
        </div>

        {{-- Card 1: Ringkasan anak --}}
        <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_1px_3px_rgba(15,23,42,0.06),0_16px_40px_-24px_rgba(15,23,42,0.18)] overflow-hidden mb-4 sm:mb-6">
            <div class="px-5 sm:px-6 py-3.5 sm:py-4 bg-gradient-to-r from-teal-600 to-teal-700 text-white flex items-center gap-3">
                <div class="w-10 h-10 sm:w-11 sm:h-11 shrink-0 rounded-xl bg-white/20 ring-1 ring-white/30 flex items-center justify-center">
                    <span class="text-base sm:text-lg font-black">{{ strtoupper(substr($childName, 0, 1)) }}</span>
                </div>
                <div class="min-w-0">
                    <div class="flex items-center gap-2 flex-wrap">
                        <h2 class="text-[15px] sm:text-[16px] font-bold leading-tight truncate">{{ $childName }}</h2>
                        <span class="text-[10.5px] sm:text-[11px] font-bold bg-white/20 px-2 py-0.5 rounded-lg">{{ $age }}</span>
                    </div>
                    <p class="text-[12px] text-teal-50 mt-0.5">{{ $gender === 'L' ? 'Laki-laki' : 'Perempuan' }} · Lahir {{ $birthDate }}</p>
                </div>
            </div>
            @if($lastDate)
            <div class="px-5 sm:px-6 py-3 sm:py-3.5 flex flex-wrap items-center gap-x-4 gap-y-1.5 text-[12px] sm:text-[13px]">
                <span class="inline-flex items-center gap-1.5 text-slate-500 font-medium"><x-icon name="clock" weight="bold" class="text-[13px] text-teal-600" /> Terakhir</span>
                <span class="text-slate-500">Berat <b class="text-slate-800">{{ $lastWeight ? number_format($lastWeight, 1, ',', '.') : '—' }} kg</b></span>
                <span class="text-slate-500">Tinggi <b class="text-slate-800">{{ $lastHeight ? number_format($lastHeight, 1, ',', '.') : '—' }} cm</b></span>
                @if($lastStatus)<span class="text-[11px] font-semibold text-teal-700 bg-teal-50 border border-teal-200 px-2 py-0.5 rounded-lg">{{ is_array($lastStatus) ? ($lastStatus['label'] ?? 'Normal') : $lastStatus }}</span>@endif
                <span class="text-slate-400 ml-auto">{{ $lastDate }}</span>
            </div>
            @endif
        </div>

        @if($errors->any())
        <div class="mb-4 sm:mb-6 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-[12.5px] sm:text-[13px] text-rose-700">
            <p class="font-semibold mb-1 flex items-center gap-1.5"><x-icon name="warning" weight="fill" class="text-[14px]" /> Periksa kembali:</p>
            <ul class="list-disc pl-4 space-y-0.5">{{ implode('', $errors->all('<li class="inline">:message</li>')) }}</ul>
        </div>
        @endif

        {{-- Card 2: Form --}}
        <form id="measurementForm" action="{{ route('pengukuran.store') }}" method="POST" class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            @csrf
            <input type="hidden" name="balita_id" value="{{ $balitaId }}">

            <div class="p-5 sm:p-6 space-y-6 sm:space-y-8">

                {{-- 01 Antropometri --}}
                <section>
                    <div class="{{ $secHdr }}">
                        <span class="w-8 h-8 sm:w-9 sm:h-9 shrink-0 rounded-xl bg-teal-600 text-white flex items-center justify-center text-[12px] sm:text-[13px] font-bold">01</span>
                        <div><h2 class="text-[14px] sm:text-[15px] font-bold text-slate-900 leading-tight">Antropometri</h2><p class="text-[11.5px] sm:text-[12.5px] text-slate-500">Ukuran fisik balita saat ini.</p></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5">
                        <div class="{{ $field }}">
                            <label for="berat" class="{{ $lbl }}">Berat Badan <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="text" inputmode="decimal" id="berat" name="berat_badan" value="{{ old('berat_badan') }}" required placeholder="8.20" class="{{ $inp }} pr-14 text-right">
                                <span class="absolute right-4 inset-y-0 flex items-center text-[12px] sm:text-[13px] font-bold text-slate-400">kg</span>
                            </div>
                            @error('berat_badan') <p class="text-[12px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                            <div id="weight-warning" class="hidden mt-2 bg-amber-50 border border-amber-200 rounded-xl p-2.5 text-[12px] font-semibold text-amber-800 flex items-start gap-2">
                                <x-icon name="warning" weight="fill" class="text-[14px] text-amber-600 mt-0.5 shrink-0" /> Berat turun &gt; 0.5 kg dari bulan lalu.
                            </div>
                        </div>
                        <div class="{{ $field }}">
                            <label for="tinggi" class="{{ $lbl }}">Tinggi / Panjang <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="text" inputmode="decimal" id="tinggi" name="tinggi_badan" value="{{ old('tinggi_badan') }}" required placeholder="72.50" class="{{ $inp }} pr-14 text-right">
                                <span class="absolute right-4 inset-y-0 flex items-center text-[12px] sm:text-[13px] font-bold text-slate-400">cm</span>
                            </div>
                            @error('tinggi_badan') <p class="text-[12px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div class="{{ $field }}">
                            <label for="lingkar" class="{{ $lbl }} flex items-center justify-between"><span>Lingkar Kepala</span><span class="text-[11px] font-medium text-slate-400">Opsional</span></label>
                            <div class="relative">
                                <input type="text" inputmode="decimal" id="lingkar" name="lingkar_kepala" value="{{ old('lingkar_kepala') }}" placeholder="43.00" class="{{ $inp }} pr-14 text-right">
                                <span class="absolute right-4 inset-y-0 flex items-center text-[12px] sm:text-[13px] font-bold text-slate-400">cm</span>
                            </div>
                            @error('lingkar_kepala') <p class="text-[12px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>

                <div class="border-t border-slate-100"></div>

                {{-- 02 Status & Informasi --}}
                <section>
                    <div class="{{ $secHdr }}">
                        <span class="w-8 h-8 sm:w-9 sm:h-9 shrink-0 rounded-xl bg-teal-600 text-white flex items-center justify-center text-[12px] sm:text-[13px] font-bold">02</span>
                        <div><h2 class="text-[14px] sm:text-[15px] font-bold text-slate-900 leading-tight">Status & Informasi</h2><p class="text-[11.5px] sm:text-[12.5px] text-slate-500">Kenaikan berat, ASI, tanggal & catatan.</p></div>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                        <div class="{{ $field }}">
                            <label for="status_kenaikan" class="{{ $lbl }}">Status Kenaikan BB (KMS)</label>
                            <div class="relative">
                                <select id="status_kenaikan" name="status_kenaikan" class="{{ $inp }} appearance-none pr-10 cursor-pointer">
                                    <option value="" {{ old('status_kenaikan') == '' ? 'selected' : '' }}>— Pilih Status KMS —</option>
                                    <option value="N" {{ old('status_kenaikan') == 'N' ? 'selected' : '' }}>N — Naik sesuai garis kurva</option>
                                    <option value="T" {{ old('status_kenaikan') == 'T' ? 'selected' : '' }}>T — Tidak naik / tetap / turun</option>
                                    <option value="B" {{ old('status_kenaikan') == 'B' ? 'selected' : '' }}>B — Baru / belum ada data lalu</option>
                                </select>
                                <x-icon name="caret-down" weight="bold" class="w-[16px] h-[16px] text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none" />
                            </div>
                            @error('status_kenaikan') <p class="text-[12px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div class="{{ $field }}">
                            <label for="tanggal" class="{{ $lbl }}">Tanggal Pengukuran <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="date" id="tanggal" name="tanggal_ukur" value="{{ old('tanggal_ukur', now()->format('Y-m-d')) }}" required class="{{ $inp }} appearance-none pr-10">
                                <x-icon name="calendar" weight="bold" class="w-[17px] h-[17px] text-slate-400 absolute right-3.5 top-1/2 -translate-y-1/2 pointer-events-none" />
                            </div>
                            @error('tanggal_ukur') <p class="text-[12px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div class="{{ $field }} sm:col-span-2">
                            <label class="{{ $lbl }}">Pemberian ASI Eksklusif</label>
                            <div class="grid grid-cols-2 gap-2 p-1 rounded-xl bg-slate-100 border border-slate-200">
                                <label class="relative">
                                    <input type="radio" name="asi_eksklusif" value="1" {{ old('asi_eksklusif', '1') == '1' ? 'checked' : '' }} class="peer sr-only">
                                    <span class="flex items-center justify-center gap-1.5 h-11 sm:h-12 rounded-lg text-[13px] sm:text-[13.5px] font-semibold text-slate-500 cursor-pointer peer-checked:bg-teal-600 peer-checked:text-white peer-checked:shadow-sm transition-all">Ya, ASI saja</span>
                                </label>
                                <label class="relative">
                                    <input type="radio" name="asi_eksklusif" value="0" {{ old('asi_eksklusif') === '0' ? 'checked' : '' }} class="peer sr-only">
                                    <span class="flex items-center justify-center gap-1.5 h-11 sm:h-12 rounded-lg text-[13px] sm:text-[13.5px] font-semibold text-slate-500 cursor-pointer peer-checked:bg-teal-600 peer-checked:text-white peer-checked:shadow-sm transition-all">Tidak</span>
                                </label>
                            </div>
                        </div>
                        <div class="{{ $field }} sm:col-span-2">
                            <label for="catatan_kader" class="{{ $lbl }} flex items-center justify-between"><span>Catatan Kader</span><span class="text-[11px] font-medium text-slate-400">Opsional</span></label>
                            <textarea id="catatan_kader" name="catatan_kader" rows="3" placeholder="Contoh: Nafsu makan baik, imunisasi lengkap, tidak ada keluhan." class="w-full rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-[14px] font-medium text-slate-800 placeholder:text-slate-400 shadow-sm focus:outline-none focus:ring-4 focus:ring-teal-500/15 focus:border-teal-600 focus:bg-white transition-all resize-none">{{ old('catatan_kader') }}</textarea>
                            @error('catatan_kader') <p class="text-[12px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </section>
            </div>

            {{-- Action row --}}
            <div class="border-t border-slate-100 px-5 sm:px-6 py-4 flex items-center justify-end gap-2.5 sm:gap-3">
                <a href="{{ route('balita.show', $balitaId) }}" class="h-11 sm:h-12 px-5 rounded-xl border border-slate-300 bg-white text-slate-700 text-[13.5px] sm:text-[14px] font-semibold hover:bg-slate-50 transition-colors inline-flex items-center justify-center">Batal</a>
                <button type="submit" id="btn-submit"
                    class="flex-1 sm:flex-none h-11 sm:h-12 px-7 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-[13.5px] sm:text-[14px] font-bold transition-colors inline-flex items-center justify-center gap-2 shadow-md shadow-teal-600/20">
                    <x-icon name="check" weight="bold" class="text-[15px] sm:text-[16px]" /> Simpan Pengukuran
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const previousWeight = {{ $lastWeight ?? 0 }};
    function decimalMask(s) {
        let v = String(s || '').replace(/[^\d.]/g, '').replace(/(\..*)\./g, '$1');
        if (v.includes('.')) { let p = v.split('.'); let i = p[0].replace(/^0+(?=\d)/, ''); return (i === '' ? '0' : i) + '.' + p[1].slice(0, 1); }
        let d = v.replace(/^0+(?=\d)/, '');
        if (!d) return '';
        if (d.length <= 2) return d;
        return d.slice(0, -1) + '.' + d.slice(-1);
    }
    function validateWeight(v) {
        const wa = document.getElementById('weight-warning');
        if (!wa) return;
        if (previousWeight && v && (parseFloat(previousWeight) - parseFloat(v) > 0.5)) wa.classList.remove('hidden');
        else wa.classList.add('hidden');
    }
    ['berat', 'tinggi', 'lingkar'].forEach(id => {
        const el = document.getElementById(id);
        if (el) el.addEventListener('input', () => { el.value = decimalMask(el.value); if (id === 'berat') validateWeight(el.value); });
    });
});
</script>
@endsection
