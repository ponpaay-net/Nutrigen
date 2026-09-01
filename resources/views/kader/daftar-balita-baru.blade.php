@extends('layouts.app')

@section('page-title', ($isEdit ?? false) ? 'Edit Data Anak' : 'Daftar Balita Baru')

@php
    $isEdit = $isEdit ?? false;
    $secs = ['identitas' => ['user', 'Identitas Anak', 'Nama, NIK & tanggal lahir'],
             'kelahiran' => ['baby', 'Kelahiran', 'Antropometri saat lahir'],
             'orangtua'  => ['users', 'Orang Tua / Wali', 'Identitas & kontak wali'],
             'lokasi'    => ['map-pin', 'Lokasi & Posyandu', 'Domisili saat ini']];
    $inp = 'w-full rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-[14px] font-medium text-slate-900 placeholder:text-slate-400 shadow-sm focus:outline-none focus:ring-2 focus:ring-teal-600/30 focus:border-teal-500 focus:bg-white transition-colors';
    $lbl = 'block text-[13px] font-semibold text-slate-700';
    $field = 'flex flex-col gap-1.5';
    @endphp

@section('content')
<div class="bg-slate-50 min-h-[100dvh]" x-data="editForm()">
    <div class="max-w-3xl mx-auto w-full px-4 sm:px-6 py-6 sm:py-8">

        {{-- Header --}}
        <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-3 mb-5">
            <div>
                <a href="{{ $isEdit ? route('balita.show', $balitaId ?? '') : route('balita.index') }}"
                   class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-slate-500 hover:text-slate-800 transition-colors">
                    <x-icon name="arrow-left" weight="bold" class="text-[15px]" /> Kembali
                </a>
                <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight mt-2">
                    {{ $isEdit ? 'Edit Data Anak' : 'Daftar Balita Baru' }}
                </h1>
            </div>
            @if($isEdit)
            <div class="inline-flex items-center gap-1.5 self-start text-[12px] font-medium text-slate-500">
                <x-icon name="clock" weight="bold" class="text-[13px] text-slate-400" />
                Terakhir diubah: <span class="font-semibold text-slate-800">{{ \Carbon\Carbon::now()->translatedFormat('d M Y, H:i') }}</span>
            </div>
            @endif
        </div>

        {{-- Section nav (slim pills) --}}
        <nav class="flex gap-1.5 overflow-x-auto hide-scrollbar mb-5 pb-1 lg:static">
            @foreach($secs as $id => $sec)
            <a href="#{{ $id }}" @click.prevent="scrollTo('{{ $id }}')"
               :class="activeSection === '{{ $id }}' ? 'bg-teal-600 text-white border-teal-600' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-50'"
               class="shrink-0 inline-flex items-center gap-1.5 px-4 h-9 rounded-full border text-[13px] font-semibold transition-colors">
                <x-icon name="{{ $sec[0] }}" weight="bold" class="text-[14px]" /> {{ $sec[1] }}
            </a>
            @endforeach
        </nav>

        {{-- Form --}}
        <form id="balitaForm" action="{{ $isEdit ? route('balita.update', $balitaId) : route('balita.store') }}" method="POST">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">

                {{-- Edit: child summary strip --}}
                @if($isEdit)
                <div class="flex items-center gap-3 px-5 sm:px-6 py-4 bg-teal-50/60 border-b border-teal-100">
                    <div class="w-11 h-11 shrink-0 rounded-xl bg-teal-600 text-white flex items-center justify-center">
                        <span class="text-lg font-black select-none">{{ strtoupper(substr($childName, 0, 1)) }}</span>
                    </div>
                    <div class="min-w-0">
                        <p class="text-[15px] font-bold text-slate-900 truncate">{{ $childName }}</p>
                        <p class="text-[12px] text-slate-500 flex items-center gap-2 flex-wrap">
                            <span>{{ $gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                            <span>Lahir {{ \Carbon\Carbon::parse($birthDate)->format('d-m-Y') }}</span>
                        </p>
                    </div>
                </div>
                @endif

                @if($errors->any())
                <div class="mx-5 sm:mx-6 mt-5 rounded-lg bg-rose-50 border border-rose-200 px-4 py-3 text-[13px] text-rose-700">
                    <p class="font-semibold mb-1 flex items-center gap-1.5"><x-icon name="warning" weight="fill" class="text-[14px]" /> Ada beberapa data yang perlu diperbaiki:</p>
                    <ul class="list-disc pl-4 space-y-0.5">{{ implode('', $errors->all('<li class="inline">:message</li>')) }}</ul>
                </div>
                @endif

                <div class="p-5 sm:p-6 space-y-9">

                    {{-- 1. Identitas --}}
                    <section id="identitas" class="scroll-mt-24">
                        <div class="flex items-center gap-2.5 mb-5">
                            <span class="w-9 h-9 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0"><x-icon name="user" weight="bold" class="text-[16px]" /></span>
                            <div><h2 class="text-base font-bold text-slate-900 leading-tight">Identitas Anak</h2><p class="text-[12.5px] text-slate-500">Nama, NIK & tanggal lahir.</p></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="{{ $field }} md:col-span-2">
                                <label for="nama" class="{{ $lbl }}">Nama Lengkap Anak <span class="text-rose-500">*</span></label>
                                <input id="nama" type="text" name="nama" value="{{ old('nama', $childName ?? '') }}" required placeholder="Contoh: Aisyah Putri" class="{{ $inp }}">
                                @error('nama') <p class="text-[12px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div class="{{ $field }}">
                                <label for="nik" class="{{ $lbl }}">NIK Anak <span class="text-rose-500">*</span></label>
                                <input id="nik" type="text" name="nik" value="{{ old('nik', $nik ?? '') }}" required placeholder="16 digit" maxlength="16" inputmode="numeric" class="{{ $inp }}">
                                @error('nik') <p class="text-[12px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div class="{{ $field }}">
                                <label for="no_bpjs" class="{{ $lbl }}">No. BPJS <span class="text-[12px] font-medium text-slate-400">(opsional)</span></label>
                                <input id="no_bpjs" type="text" name="no_bpjs" value="{{ old('no_bpjs', $noBpjs ?? '') }}" placeholder="Nomor BPJS" class="{{ $inp }}">
                            </div>
                            <div class="{{ $field }}">
                                <label for="tanggal_lahir" class="{{ $lbl }}">Tanggal Lahir <span class="text-rose-500">*</span></label>
                                <input id="tanggal_lahir" type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $birthDate ?? '') }}" required class="{{ $inp }} appearance-none">
                                @error('tanggal_lahir') <p class="text-[12px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div class="{{ $field }}">
                                <label class="{{ $lbl }}">Jenis Kelamin <span class="text-rose-500">*</span></label>
                                <div class="grid grid-cols-2 gap-2 p-1 rounded-lg bg-slate-100 border border-slate-200">
                                    <label class="relative">
                                        <input type="radio" name="jenis_kelamin" value="L" required class="peer sr-only" {{ old('jenis_kelamin', $gender ?? '') === 'L' ? 'checked' : '' }}>
                                        <span class="flex items-center justify-center gap-1.5 h-10 rounded-md text-[13px] font-semibold text-slate-500 cursor-pointer peer-checked:bg-white peer-checked:text-teal-700 peer-checked:shadow-sm transition-all">Laki-laki</span>
                                    </label>
                                    <label class="relative">
                                        <input type="radio" name="jenis_kelamin" value="P" required class="peer sr-only" {{ old('jenis_kelamin', $gender ?? '') === 'P' ? 'checked' : '' }}>
                                        <span class="flex items-center justify-center gap-1.5 h-10 rounded-md text-[13px] font-semibold text-slate-500 cursor-pointer peer-checked:bg-white peer-checked:text-teal-700 peer-checked:shadow-sm transition-all">Perempuan</span>
                                    </label>
                                </div>
                                @error('jenis_kelamin') <p class="text-[12px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-slate-100"></div>

                    {{-- 2. Kelahiran --}}
                    <section id="kelahiran" class="scroll-mt-24">
                        <div class="flex items-center gap-2.5 mb-5">
                            <span class="w-9 h-9 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0"><x-icon name="baby" weight="bold" class="text-[16px]" /></span>
                            <div><h2 class="text-base font-bold text-slate-900 leading-tight">Kelahiran</h2><p class="text-[12.5px] text-slate-500">Antropometri saat lahir.</p></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                            <div class="{{ $field }}">
                                <label for="berat_lahir" class="{{ $lbl }}">Berat Lahir</label>
                                <div class="relative">
                                    <input id="berat_lahir" type="text" inputmode="decimal" name="berat_lahir" value="{{ old('berat_lahir', $birthWeight ?? '') }}" placeholder="3.20" class="{{ $inp }} pr-12 text-right">
                                    <span class="absolute inset-y-0 right-3 flex items-center text-[13px] font-medium text-slate-400">kg</span>
                                </div>
                            </div>
                            <div class="{{ $field }}">
                                <label for="panjang_lahir" class="{{ $lbl }}">Panjang Lahir</label>
                                <div class="relative">
                                    <input id="panjang_lahir" type="text" inputmode="decimal" name="panjang_lahir" value="{{ old('panjang_lahir', $birthLength ?? '') }}" placeholder="49.5" class="{{ $inp }} pr-12 text-right">
                                    <span class="absolute inset-y-0 right-3 flex items-center text-[13px] font-medium text-slate-400">cm</span>
                                </div>
                            </div>
                            <div class="{{ $field }}">
                                <label for="lingkar_kepala_lahir" class="{{ $lbl }}">Lingkar Kepala</label>
                                <div class="relative">
                                    <input id="lingkar_kepala_lahir" type="text" inputmode="decimal" name="lingkar_kepala_lahir" value="{{ old('lingkar_kepala_lahir', $birthHeadCirc ?? '') }}" placeholder="33.0" class="{{ $inp }} pr-12 text-right">
                                    <span class="absolute inset-y-0 right-3 flex items-center text-[13px] font-medium text-slate-400">cm</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-slate-100"></div>

                    {{-- 3. Orang Tua --}}
                    <section id="orangtua" class="scroll-mt-24">
                        <div class="flex items-center gap-2.5 mb-5">
                            <span class="w-9 h-9 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0"><x-icon name="users" weight="bold" class="text-[16px]" /></span>
                            <div><h2 class="text-base font-bold text-slate-900 leading-tight">Orang Tua / Wali</h2><p class="text-[12.5px] text-slate-500">Identitas & kontak wali.</p></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="{{ $field }} md:col-span-2">
                                <label for="no_kk" class="{{ $lbl }}">No. Kartu Keluarga <span class="text-[12px] font-medium text-slate-400">(opsional)</span></label>
                                <input id="no_kk" type="text" name="no_kk" value="{{ old('no_kk', $noKk ?? '') }}" placeholder="16 digit Nomor Kartu Keluarga" maxlength="16" inputmode="numeric" class="{{ $inp }}">
                            </div>

                            <div class="md:col-span-2 flex items-center gap-2 mt-1">
                                <span class="w-7 h-7 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-[12px]">I</span>
                                <h3 class="text-[13px] font-bold text-slate-700 uppercase tracking-wide">Identitas Ibu</h3>
                            </div>

                            <div class="{{ $field }}">
                                <label for="nama_ibu" class="{{ $lbl }}">Nama Ibu <span class="text-rose-500">*</span></label>
                                <input id="nama_ibu" type="text" name="nama_ibu" value="{{ old('nama_ibu', $motherName ?? '') }}" required placeholder="Nama lengkap ibu" class="{{ $inp }}">
                                @error('nama_ibu') <p class="text-[12px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div class="{{ $field }}">
                                <label for="nik_ibu" class="{{ $lbl }}">NIK Ibu <span class="text-[12px] font-medium text-slate-400">(opsional)</span></label>
                                <input id="nik_ibu" type="text" name="nik_ibu" value="{{ old('nik_ibu', $motherNik ?? '') }}" placeholder="16 digit" maxlength="16" inputmode="numeric" class="{{ $inp }}">
                            </div>
                            <div class="{{ $field }}">
                                <label for="no_hp" class="{{ $lbl }}">No. WhatsApp Ibu <span class="text-rose-500">*</span></label>
                                <input id="no_hp" type="tel" name="no_hp" value="{{ old('no_hp', $motherPhone ?? '') }}" required placeholder="08xxxxxxxxxx" inputmode="tel" class="{{ $inp }}">
                                @error('no_hp') <p class="text-[12px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div class="{{ $field }}">
                                <label for="pekerjaan_ibu" class="{{ $lbl }}">Pekerjaan Ibu <span class="text-[12px] font-medium text-slate-400">(opsional)</span></label>
                                <input id="pekerjaan_ibu" type="text" name="pekerjaan_ibu" value="{{ old('pekerjaan_ibu', $motherJob ?? '') }}" placeholder="Ibu Rumah Tangga" class="{{ $inp }}">
                            </div>

                            <div class="md:col-span-2 flex items-center gap-2 mt-1">
                                <span class="w-7 h-7 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center font-bold text-[12px]">A</span>
                                <h3 class="text-[13px] font-bold text-slate-700 uppercase tracking-wide">Identitas Ayah</h3>
                            </div>

                            <div class="{{ $field }}">
                                <label for="nama_ayah" class="{{ $lbl }}">Nama Ayah <span class="text-[12px] font-medium text-slate-400">(opsional)</span></label>
                                <input id="nama_ayah" type="text" name="nama_ayah" value="{{ old('nama_ayah', $fatherName ?? '') }}" placeholder="Nama lengkap ayah" class="{{ $inp }}">
                            </div>
                            <div class="{{ $field }}">
                                <label for="nik_ayah" class="{{ $lbl }}">NIK Ayah <span class="text-[12px] font-medium text-slate-400">(opsional)</span></label>
                                <input id="nik_ayah" type="text" name="nik_ayah" value="{{ old('nik_ayah', $fatherNik ?? '') }}" placeholder="16 digit" maxlength="16" inputmode="numeric" class="{{ $inp }}">
                            </div>
                            <div class="{{ $field }} md:col-span-2">
                                <label for="pekerjaan_ayah" class="{{ $lbl }}">Pekerjaan Ayah <span class="text-[12px] font-medium text-slate-400">(opsional)</span></label>
                                <input id="pekerjaan_ayah" type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $fatherJob ?? '') }}" placeholder="Wiraswasta" class="{{ $inp }}">
                            </div>
                        </div>
                    </section>

                    <div class="border-t border-slate-100"></div>

                    {{-- 4. Lokasi --}}
                    <section id="lokasi" class="scroll-mt-24">
                        <div class="flex items-center gap-2.5 mb-5">
                            <span class="w-9 h-9 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center shrink-0"><x-icon name="map-pin" weight="bold" class="text-[16px]" /></span>
                            <div><h2 class="text-base font-bold text-slate-900 leading-tight">Lokasi & Posyandu</h2><p class="text-[12.5px] text-slate-500">Domisili tempat tinggal saat ini.</p></div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div class="{{ $field }}">
                                <label for="desa" class="{{ $lbl }}">Desa / Kelurahan <span class="text-[12px] font-medium text-slate-400">(opsional)</span></label>
                                <input id="desa" type="text" name="desa" value="{{ old('desa', $address ?? '') }}" placeholder="Nama desa" class="{{ $inp }}">
                            </div>
                            <div class="{{ $field }}">
                                <label for="kecamatan" class="{{ $lbl }}">Kecamatan <span class="text-[12px] font-medium text-slate-400">(opsional)</span></label>
                                <input id="kecamatan" type="text" name="kecamatan" value="{{ old('kecamatan', $addressSub ?? '') }}" placeholder="Nama kecamatan" class="{{ $inp }}">
                            </div>
                            <div class="{{ $field }} md:col-span-2">
                                <label class="{{ $lbl }}">Posyandu Pendaftar</label>
                                <input type="text" value="{{ $posyanduName ?? 'Posyandu Kader' }}" disabled readonly class="{{ $inp }} bg-slate-100 text-slate-600 font-semibold cursor-not-allowed">
                            </div>
                        </div>
                    </section>
                </div>
            </div>

            {{-- Action bar --}}
            <div class="sticky bottom-0 mt-6 -mb-2 bg-slate-50/95 backdrop-blur border-t border-slate-200 py-3.5 -mx-4 sm:mx-0 px-4 sm:px-0 flex items-center justify-between gap-3 sm:static sm:border-none sm:bg-transparent sm:px-0 sm:pt-0">
                <span class="hidden md:inline-flex items-center gap-1.5 text-[12.5px] font-medium text-slate-500"><x-icon name="info" weight="bold" class="text-[14px]" /> Periksa kembali data sebelum menyimpan.</span>
                <div class="flex items-center gap-2.5 w-full sm:w-auto">
                    <a href="{{ $isEdit ? route('balita.show', $balitaId ?? '') : route('balita.index') }}"
                       class="flex-1 sm:flex-none h-11 px-5 rounded-xl border border-slate-200 bg-white text-slate-700 text-[13.5px] font-semibold hover:bg-slate-50 transition-colors inline-flex items-center justify-center">Batal</a>
                    <button type="submit"
                       class="flex-1 sm:flex-none h-11 px-6 rounded-xl bg-teal-600 hover:bg-teal-700 text-white text-[13.5px] font-semibold transition-colors inline-flex items-center justify-center gap-2 shadow-sm">
                       <x-icon name="check" weight="bold" class="text-[15px]" /> Simpan Data
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
.hide-scrollbar::-webkit-scrollbar { display: none; }
.hide-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('editForm', () => ({
        activeSection: 'identitas',
        init() {
            const main = document.querySelector('main') || window;
            const obs = new IntersectionObserver((entries) => {
                entries.forEach(e => { if (e.isIntersecting) this.activeSection = e.target.id; });
            }, { root: main, rootMargin: '-140px 0px -65% 0px', threshold: 0 });
            ['identitas','kelahiran','orangtua','lokasi'].forEach(id => { const el = document.getElementById(id); if (el) obs.observe(el); });
        },
        scrollTo(id) {
            const el = document.getElementById(id);
            if (!el) return;
            const main = document.querySelector('main');
            if (main) {
                const y = el.getBoundingClientRect().top + main.scrollTop - main.getBoundingClientRect().top - 90;
                main.scrollTo({ top: y, behavior: 'smooth' });
            } else { el.scrollIntoView({ behavior: 'smooth' }); }
            this.activeSection = id;
        }
    }));
});
</script>
@endsection
