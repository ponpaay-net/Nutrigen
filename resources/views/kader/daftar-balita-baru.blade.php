@extends('layouts.app')

@section('page-title', ($isEdit ?? false) ? 'Edit Data Anak' : 'Daftar Balita Baru')

@php
    $isEdit = $isEdit ?? false;
    $secs = ['identitas' => ['user', '01', 'Identitas Anak', 'Nama, NIK & tanggal lahir'],
             'kelahiran' => ['baby', '02', 'Kelahiran', 'Antropometri saat lahir'],
             'orangtua'  => ['users', '03', 'Orang Tua / Wali', 'Identitas & kontak wali'],
             'lokasi'    => ['map-pin', '04', 'Lokasi & Posyandu', 'Domisili saat ini']];
    $inp = 'w-full rounded-xl border border-slate-200 bg-slate-50 px-3.5 py-2.5 text-[14px] font-medium text-slate-900 placeholder:text-slate-400 shadow-sm focus:outline-none focus:ring-4 focus:ring-teal-500/15 focus:border-teal-500 focus:bg-white transition-all';
    $lbl = 'block text-[13px] font-semibold text-slate-700';
    $field = 'flex flex-col gap-1.5';
    @endphp

@section('content')
<div class="bg-slate-50 min-h-full" x-data="editForm()">
    <div class="max-w-6xl mx-auto w-full px-4 sm:px-6 py-6 sm:py-8">

        {{-- Breadcrumb + header --}}
        <div class="mb-6">
            <nav class="flex items-center gap-1.5 text-[13px] font-medium text-slate-500 mb-2">
                <a href="{{ route('balita.index') }}" class="hover:text-slate-800 transition-colors">Data Balita</a>
                <span class="text-slate-300">/</span>
                <span class="text-slate-700">{{ $isEdit ? 'Edit Data' : 'Tambah Data' }}</span>
            </nav>
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div class="flex items-center gap-2.5">
                    <a href="{{ $isEdit ? route('balita.show', $balitaId ?? '') : route('balita.index') }}" aria-label="Kembali"
                       class="w-9 h-9 rounded-lg bg-white border border-slate-200 text-slate-600 hover:bg-slate-50 flex items-center justify-center transition-colors">
                        <x-icon name="arrow-left" weight="bold" class="text-[16px]" />
                    </a>
                    <h1 class="text-xl sm:text-2xl font-bold text-slate-900 tracking-tight">{{ $isEdit ? 'Edit Data Anak' : 'Daftar Balita Baru' }}</h1>
                </div>
                @if($isEdit)
                <span class="inline-flex items-center gap-1.5 text-[12px] font-medium text-slate-500">
                    <x-icon name="clock" weight="bold" class="text-[13px] text-slate-400" />
                    Terakhir diubah: {{ \Carbon\Carbon::now()->translatedFormat('d M Y, H:i') }}
                </span>
                @endif
            </div>
        </div>

        <form id="balitaForm" action="{{ $isEdit ? route('balita.update', $balitaId) : route('balita.store') }}" method="POST">
            @csrf
            @if($isEdit) @method('PUT') @endif

            <div class="grid grid-cols-1 lg:grid-cols-[1fr_320px] gap-6 items-start">

                {{-- MAIN: form --}}
                <div class="bg-white rounded-2xl border border-slate-200 shadow-[0_1px_3px_rgba(15,23,42,0.06),0_20px_50px_-24px_rgba(15,23,42,0.18)] overflow-hidden min-w-0">

                    @if($errors->any())
                    <div class="mx-5 sm:mx-7 mt-5 rounded-xl bg-rose-50 border border-rose-200 px-4 py-3 text-[13px] text-rose-700">
                        <p class="font-semibold mb-1 flex items-center gap-1.5"><x-icon name="warning" weight="fill" class="text-[14px]" /> Ada beberapa data yang perlu diperbaiki:</p>
                        <ul class="list-disc pl-4 space-y-0.5">{{ implode('', $errors->all('<li class="inline">:message</li>')) }}</ul>
                    </div>
                    @endif

                    <div class="p-5 sm:p-7 space-y-8">

                        {{-- 01 IDENTITAS --}}
                        <section id="identitas" class="scroll-mt-24">
                            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                                <span class="w-9 h-9 shrink-0 rounded-xl bg-teal-600 text-white flex items-center justify-center text-[13px] font-bold">01</span>
                                <div><h2 class="text-[15px] font-bold text-slate-900 leading-tight">Identitas Anak</h2><p class="text-[12.5px] text-slate-500">Nama, NIK & tanggal lahir.</p></div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="{{ $field }} sm:col-span-2">
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
                                    <div class="relative">
                                        <input id="tanggal_lahir" type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $birthDate ?? '') }}" required class="{{ $inp }} appearance-none pr-10">
                                        <x-icon name="calendar" weight="bold" class="w-[18px] h-[18px] text-slate-400 absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none" />
                                    </div>
                                    @error('tanggal_lahir') <p class="text-[12px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                                </div>
                                <div class="{{ $field }}">
                                    <label class="{{ $lbl }}">Jenis Kelamin <span class="text-rose-500">*</span></label>
                                    <div class="grid grid-cols-2 gap-1.5 p-1.5 rounded-xl bg-slate-100 border border-slate-200">
                                        <label class="relative">
                                            <input type="radio" name="jenis_kelamin" value="L" required class="peer sr-only" {{ old('jenis_kelamin', $gender ?? '') === 'L' ? 'checked' : '' }}>
                                            <span class="flex items-center justify-center gap-1.5 h-10 rounded-lg text-[13px] font-semibold text-slate-500 cursor-pointer peer-checked:bg-teal-600 peer-checked:text-white peer-checked:shadow-sm transition-all">Laki-laki</span>
                                        </label>
                                        <label class="relative">
                                            <input type="radio" name="jenis_kelamin" value="P" required class="peer sr-only" {{ old('jenis_kelamin', $gender ?? '') === 'P' ? 'checked' : '' }}>
                                            <span class="flex items-center justify-center gap-1.5 h-10 rounded-lg text-[13px] font-semibold text-slate-500 cursor-pointer peer-checked:bg-teal-600 peer-checked:text-white peer-checked:shadow-sm transition-all">Perempuan</span>
                                        </label>
                                    </div>
                                    @error('jenis_kelamin') <p class="text-[12px] text-rose-600 font-medium">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </section>

                        <div class="border-t border-slate-100"></div>

                        {{-- 02 KELAHIRAN --}}
                        <section id="kelahiran" class="scroll-mt-24">
                            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                                <span class="w-9 h-9 shrink-0 rounded-xl bg-teal-600 text-white flex items-center justify-center text-[13px] font-bold">02</span>
                                <div><h2 class="text-[15px] font-bold text-slate-900 leading-tight">Kelahiran</h2><p class="text-[12.5px] text-slate-500">Antropometri saat lahir.</p></div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
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

                        {{-- 03 ORANG TUA --}}
                        <section id="orangtua" class="scroll-mt-24">
                            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                                <span class="w-9 h-9 shrink-0 rounded-xl bg-teal-600 text-white flex items-center justify-center text-[13px] font-bold">03</span>
                                <div><h2 class="text-[15px] font-bold text-slate-900 leading-tight">Orang Tua / Wali</h2><p class="text-[12.5px] text-slate-500">Identitas & kontak wali.</p></div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="{{ $field }} sm:col-span-2">
                                    <label for="no_kk" class="{{ $lbl }}">No. Kartu Keluarga <span class="text-[12px] font-medium text-slate-400">(opsional)</span></label>
                                    <input id="no_kk" type="text" name="no_kk" value="{{ old('no_kk', $noKk ?? '') }}" placeholder="16 digit Nomor Kartu Keluarga" maxlength="16" inputmode="numeric" class="{{ $inp }}">
                                </div>
                                <div class="sm:col-span-2 flex items-center gap-2.5 mt-1">
                                    <span class="w-7 h-7 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center text-[11px] font-bold">I</span>
                                    <h3 class="text-[12.5px] font-bold text-slate-700 uppercase tracking-wide">Identitas Ibu</h3>
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
                                <div class="sm:col-span-2 flex items-center gap-2.5 mt-1">
                                    <span class="w-7 h-7 rounded-full bg-teal-50 text-teal-600 flex items-center justify-center text-[11px] font-bold">A</span>
                                    <h3 class="text-[12.5px] font-bold text-slate-700 uppercase tracking-wide">Identitas Ayah</h3>
                                </div>
                                <div class="{{ $field }}">
                                    <label for="nama_ayah" class="{{ $lbl }}">Nama Ayah <span class="text-[12px] font-medium text-slate-400">(opsional)</span></label>
                                    <input id="nama_ayah" type="text" name="nama_ayah" value="{{ old('nama_ayah', $fatherName ?? '') }}" placeholder="Nama lengkap ayah" class="{{ $inp }}">
                                </div>
                                <div class="{{ $field }}">
                                    <label for="nik_ayah" class="{{ $lbl }}">NIK Ayah <span class="text-[12px] font-medium text-slate-400">(opsional)</span></label>
                                    <input id="nik_ayah" type="text" name="nik_ayah" value="{{ old('nik_ayah', $fatherNik ?? '') }}" placeholder="16 digit" maxlength="16" inputmode="numeric" class="{{ $inp }}">
                                </div>
                                <div class="{{ $field }} sm:col-span-2">
                                    <label for="pekerjaan_ayah" class="{{ $lbl }}">Pekerjaan Ayah <span class="text-[12px] font-medium text-slate-400">(opsional)</span></label>
                                    <input id="pekerjaan_ayah" type="text" name="pekerjaan_ayah" value="{{ old('pekerjaan_ayah', $fatherJob ?? '') }}" placeholder="Wiraswasta" class="{{ $inp }}">
                                </div>
                            </div>
                        </section>

                        <div class="border-t border-slate-100"></div>

                        {{-- 04 LOKASI --}}
                        <section id="lokasi" class="scroll-mt-24">
                            <div class="flex items-center gap-3 mb-5 pb-4 border-b border-slate-100">
                                <span class="w-9 h-9 shrink-0 rounded-xl bg-teal-600 text-white flex items-center justify-center text-[13px] font-bold">04</span>
                                <div><h2 class="text-[15px] font-bold text-slate-900 leading-tight">Lokasi & Posyandu</h2><p class="text-[12.5px] text-slate-500">Domisili tempat tinggal saat ini.</p></div>
                            </div>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                <div class="{{ $field }}">
                                    <label for="desa" class="{{ $lbl }}">Desa / Kelurahan <span class="text-[12px] font-medium text-slate-400">(opsional)</span></label>
                                    <input id="desa" type="text" name="desa" value="{{ old('desa', $address ?? '') }}" placeholder="Nama desa" class="{{ $inp }}">
                                </div>
                                <div class="{{ $field }}">
                                    <label for="kecamatan" class="{{ $lbl }}">Kecamatan <span class="text-[12px] font-medium text-slate-400">(opsional)</span></label>
                                    <input id="kecamatan" type="text" name="kecamatan" value="{{ old('kecamatan', $addressSub ?? '') }}" placeholder="Nama kecamatan" class="{{ $inp }}">
                                </div>
                                <div class="{{ $field }} sm:col-span-2">
                                    <label class="{{ $lbl }}">Posyandu Pendaftar</label>
                                    <input type="text" value="{{ $posyanduName ?? 'Posyandu Kader' }}" disabled readonly class="{{ $inp }} bg-slate-100 text-slate-600 font-semibold cursor-not-allowed">
                                </div>
                            </div>
                        </section>
                    </div>
                </div>

                {{-- RIGHT RAIL --}}
                <aside class="hidden lg:block min-w-0">
                    <div class="sticky top-6 space-y-4">

                        {{-- Ringkasan Anak --}}
                        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm overflow-hidden">
                            <div class="px-5 py-4 bg-gradient-to-r from-teal-600 to-teal-700 text-white">
                                <p class="text-[11px] font-semibold uppercase tracking-wider text-teal-100">Ringkasan Anak</p>
                            </div>
                            <div class="px-5 py-4">
                                @if($isEdit)
                                <div class="flex items-center gap-3">
                                    <div class="w-11 h-11 shrink-0 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center">
                                        <span class="text-lg font-black">{{ strtoupper(substr($childName, 0, 1)) }}</span>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[14px] font-bold text-slate-900 truncate">{{ $childName }}</p>
                                        <p class="text-[12px] text-slate-500">{{ $gender === 'L' ? 'Laki-laki' : 'Perempuan' }} · Lahir {{ \Carbon\Carbon::parse($birthDate)->format('d-m-Y') }}</p>
                                    </div>
                                </div>
                                @else
                                <p class="text-[13px] font-semibold text-slate-700">Anak baru kader</p>
                                <p class="text-[12px] text-slate-500">Isi form di samping untuk mendaftarkan balita baru.</p>
                                @endif
                            </div>
                        </div>

                        {{-- Bagian Form --}}
                        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-4">
                            <h3 class="text-[12px] font-bold text-slate-900 uppercase tracking-wide mb-2.5">Bagian Form</h3>
                            <div class="space-y-1">
                                @foreach($secs as $id => $sec)
                                <a href="#{{ $id }}" @click.prevent="scrollTo('{{ $id }}')"
                                   :class="activeSection === '{{ $id }}' ? 'bg-teal-50 text-teal-700 border-teal-200' : 'text-slate-600 border-transparent hover:bg-slate-50'"
                                   class="flex items-center gap-2 px-2.5 py-1.5 rounded-lg border text-[12.5px] font-semibold transition-colors">
                                    <span :class="activeSection === '{{ $id }}' ? 'bg-teal-600 text-white' : 'bg-teal-50 text-teal-600'"
                                          class="w-5 h-5 rounded-md flex items-center justify-center text-[10px] font-bold shrink-0">{{ $sec[1] }}</span>
                                    {{ $sec[2] }}
                                </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Panduan --}}
                        <div class="rounded-2xl border border-slate-200 bg-white shadow-sm p-5">
                            <h3 class="text-[13px] font-bold text-slate-900 flex items-center gap-1.5"><x-icon name="lightbulb" weight="bold" class="text-[14px] text-teal-600" /> Panduan Pengisian</h3>
                            <ul class="mt-3 space-y-2.5">
                                @foreach([
                                    'NIK & No. KK sesuai Kartu Keluarga.',
                                    'Tanggal lahir & jenis kelamin penting untuk kurva WHO.',
                                    'No. WhatsApp dipakai untuk info & pengingat hasil pengukuran.',
                                    'Data lahir (berat/panjang/lingkar) diisi dari Buku KIA.'
                                ] as $tip)
                                <li class="flex items-start gap-2 text-[12.5px] text-slate-600 leading-snug">
                                    <x-icon name="check" weight="bold" class="w-4 h-4 mt-0.5 shrink-0 text-teal-500" /> {{ $tip }}
                                </li>
                                @endforeach
                            </ul>
                        </div>

                        {{-- Simpan Desktop --}}
                        <button type="submit" id="btn-submit-desktop" class="w-full h-12 rounded-xl bg-teal-600 hover:bg-teal-700 disabled:opacity-60 text-white text-[14px] font-semibold transition-all inline-flex items-center justify-center gap-2 shadow-md shadow-teal-600/20 active:scale-[0.99]">
                            <span class="btn-icon"><x-icon name="check" weight="bold" class="text-[16px]" /></span>
                            <span class="btn-text">Simpan Data</span>
                        </button>
                    </div>
                </aside>
            </div>

            {{-- Mobile action bar --}}
            <div class="lg:hidden mt-6 bg-white border border-slate-200 rounded-2xl p-4 flex items-center justify-between gap-3">
                <a href="{{ $isEdit ? route('balita.show', $balitaId ?? '') : route('balita.index') }}"
                   class="flex-1 h-11 rounded-xl border border-slate-200 bg-white text-slate-700 text-[13.5px] font-semibold hover:bg-slate-50 transition-colors inline-flex items-center justify-center">Batal</a>
                <button type="submit" id="btn-submit-mobile"
                   class="flex-1 h-11 rounded-xl bg-teal-600 hover:bg-teal-700 disabled:opacity-60 text-white text-[13.5px] font-semibold transition-all inline-flex items-center justify-center gap-2 shadow-md shadow-teal-600/20 active:scale-[0.99]">
                   <span class="btn-icon"><x-icon name="check" weight="bold" class="text-[15px]" /></span>
                   <span class="btn-text">Simpan Data</span>
                </button>
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

            // AUTO: nama -> kapital di awal kata
            ['nama','nama_ibu','nama_ayah'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.addEventListener('input', () => { el.value = titleCase(el.value); });
            });
            // AUTO: berat/panjang/lingkar kepala -> titik desimal
            ['berat_lahir','panjang_lahir','lingkar_kepala_lahir'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.addEventListener('input', () => { el.value = decimalMask(el.value); });
            });
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

// Kapital di awal tiap kata (huruf setelah spasi/hyphen/apostrof)
function titleCase(s) {
    return String(s || '').toLowerCase().replace(/(^|[\s-'’])(\w)/g, (m, sep, c) => sep + c.toUpperCase());
}

// Auto titik desimal untuk pengukuran (berat/panjang/lingkar)
function decimalMask(s) {
    let v = String(s || '').replace(/,/g, '.').replace(/[^\d.]/g, '').replace(/(\..*)\./g, '$1');
    if (v.includes('.')) {
        let p = v.split('.');
        let i = p[0].replace(/^0+(?=\d)/, '');
        return (i === '' ? '0' : i) + '.' + p[1].slice(0, 2);
    }
    let d = v.replace(/^0+(?=\d)/, '');
    if (!d) return '';
    if (d.length <= 2) return d;
    return d.slice(0, -1) + '.' + d.slice(-1);
}

document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('balitaForm');
    const btns = [document.getElementById('btn-submit-desktop'), document.getElementById('btn-submit-mobile')].filter(Boolean);
    if (form && btns.length) {
        form.addEventListener('submit', () => {
            btns.forEach(btn => {
                btn.disabled = true;
                const txt = btn.querySelector('.btn-text');
                const ico = btn.querySelector('.btn-icon');
                if (txt) txt.textContent = 'Menyimpan...';
                if (ico) ico.innerHTML = `<svg class="animate-spin h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;
            });
        });
    }
});
</script>
@endsection
