@extends('layouts.app')

@section('page-title', 'Master Data Puskesmas — NutriGen')

@section('content')
<div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full min-h-screen" style="background:#F7F8FA; font-family:'Plus Jakarta Sans',sans-serif;" x-data="puskesmasData()">
    
    {{-- Breadcrumb Navigation --}}
    <nav class="flex text-sm font-medium text-slate-500 mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-2 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('super-admin.dashboard') }}" class="inline-flex items-center hover:text-teal-600 transition-colors">
                    <x-icon name="squares-four" weight="fill" class="w-4 h-4 mr-1.5" />
                    Dashboard
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <x-icon name="caret-right" weight="bold" class="w-3.5 h-3.5 mx-1" />
                    <span class="ml-1 md:ml-2 text-slate-900 font-bold">Master Data Puskesmas</span>
                </div>
            </li>
        </ol>
    </nav>

    {{-- Header & Actions --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 bg-white p-6 rounded-2xl shadow-sm border border-teal-100/50 relative overflow-hidden">
        <!-- Subtle medical cross / brand pattern background -->
        <div class="absolute -right-10 -top-10 text-teal-50 opacity-50 rotate-12 pointer-events-none">
            <x-icon name="heartbeat" weight="fill" class="w-48 h-48" />
        </div>
        
        <div class="relative z-10 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0 border border-teal-100">
                <x-icon name="buildings" weight="fill" class="w-6 h-6" />
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight">Master Data Puskesmas</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola data wilayah, kontak, dan akun login seluruh Puskesmas.</p>
            </div>
        </div>
        <div class="relative z-10 flex items-center gap-3">
            <button @click="openModal('create')" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 bg-teal-600 text-white rounded-xl font-bold text-sm shadow-sm hover:bg-teal-700 hover:shadow transition-all active:scale-95">
                <x-icon name="plus" weight="bold" />
                Tambah Puskesmas
            </button>
        </div>
    </div>

    {{-- Filter & Search --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-slate-100 mb-6">
        <form method="GET" action="{{ route('super-admin.puskesmas.index') }}" class="relative w-full max-w-md">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <x-icon name="magnifying-glass" weight="bold" class="text-slate-400" />
            </div>
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari nama atau kode faskes..." 
                   class="block w-full pl-10 pr-3 py-2 border border-slate-200 rounded-xl leading-5 bg-slate-50 text-slate-900 placeholder-slate-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 sm:text-sm transition-colors">
            @if($search)
            <a href="{{ route('super-admin.puskesmas.index') }}" class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-rose-500">
                <x-icon name="x-circle" weight="fill" />
            </a>
            @endif
        </form>
    </div>

    {{-- Data Table --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden relative">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Puskesmas</th>
                        <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Kode Faskes</th>
                        <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Akun & Kontak</th>
                        <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Posyandu</th>
                        <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($puskesmas as $p)
                        <tr class="hover:bg-slate-50/50 transition-colors {{ $p->posyandus_count == 0 ? 'bg-rose-50/20' : '' }}">
                            <td class="py-4 px-6">
                                <a href="{{ route('super-admin.puskesmas.show', $p->id) }}" class="font-bold text-slate-900 hover:text-teal-600 transition-colors">{{ $p->nama }}</a>
                                <div class="text-xs text-slate-400 mt-0.5">{{ $p->kecamatan ? $p->kecamatan.($p->kabupaten_kota ? ', '.$p->kabupaten_kota : '') : '-' }}</div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-bold bg-slate-100 text-slate-700 font-mono">
                                    {{ $p->kode_faskes }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="text-sm font-medium text-slate-900">{{ optional($p->user)->email ?? '-' }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5">{{ $p->kepala_puskesmas ?? '-' }}</div>
                                <div class="text-[11px] text-slate-400 mt-0.5 truncate max-w-xs">{{ $p->alamat }}</div>
                            </td>
                            <td class="py-4 px-6 text-center">
                                <div class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $p->posyandus_count > 0 ? 'bg-indigo-50 text-indigo-700' : 'bg-slate-100 text-slate-500' }} font-bold text-sm">
                                    {{ $p->posyandus_count }}
                                </div>
                            </td>
                            <td class="py-4 px-6 text-right space-x-2">
                                <a href="{{ route('super-admin.puskesmas.show', $p->id) }}" class="inline-flex items-center justify-center p-2.5 rounded-xl bg-slate-50 text-slate-600 hover:bg-slate-100 transition-colors" title="Lihat Detail">
                                    <x-icon name="eye" weight="bold" class="w-5 h-5" />
                                </a>
                                
                                <button @click="openModal('edit', {{ json_encode([
                                    'id'               => $p->id,
                                    'nama'             => $p->nama,
                                    'kode_faskes'      => $p->kode_faskes,
                                    'kepala_puskesmas' => $p->kepala_puskesmas,
                                    'no_telp'          => $p->no_telp,
                                    'kecamatan'        => $p->kecamatan,
                                    'kabupaten_kota'   => $p->kabupaten_kota,
                                    'provinsi'         => $p->provinsi,
                                    'email'            => $p->user?->email ?? '',
                                    'alamat'           => $p->alamat,
                                ]) }})" class="inline-flex items-center justify-center p-2.5 rounded-xl bg-teal-50 text-teal-600 hover:bg-teal-100 transition-colors" title="Edit">
                                    <x-icon name="pencil-simple" weight="bold" class="w-5 h-5" />
                                </button>
                                
                                @if($p->posyandus_count == 0)
                                    <form action="{{ route('super-admin.puskesmas.destroy', $p->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Apakah Anda yakin ingin menghapus Puskesmas ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center p-2.5 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors" title="Hapus">
                                            <x-icon name="trash" weight="bold" class="w-5 h-5" />
                                        </button>
                                    </form>
                                @else
                                    <button type="button" onclick="alert('Tidak dapat menghapus Puskesmas karena memiliki {{ $p->posyandus_count }} Posyandu terdaftar. Hapus/pindahkan Posyandu terlebih dahulu.')" class="inline-flex items-center justify-center p-2.5 rounded-xl bg-slate-100 text-slate-400 cursor-not-allowed" title="Hapus diblokir">
                                        <x-icon name="trash" weight="bold" class="w-5 h-5" />
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-12 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-slate-100 text-slate-400 mb-4">
                                    <x-icon name="buildings" weight="fill" class="text-3xl" />
                                </div>
                                <h3 class="text-lg font-bold text-slate-900 mb-1">Belum ada Puskesmas</h3>
                                <p class="text-sm text-slate-500 max-w-md mx-auto">
                                    {{ $search ? 'Pencarian tidak menemukan hasil apapun. Coba kata kunci lain.' : 'Sistem belum memiliki data Puskesmas. Klik tombol "Tambah Puskesmas" untuk memulai.' }}
                                </p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($puskesmas->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $puskesmas->links() }}
            </div>
        @endif
    </div>

    {{-- MODAL FORM (Create & Edit) --}}
    <div x-show="modalOpen" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Background overlay -->
        <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm transition-opacity" @click="closeModal()"></div>

        <!-- Modal panel -->
        <div x-show="modalOpen"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="relative bg-white rounded-2xl shadow-xl overflow-hidden w-full max-w-lg transition-all sm:my-8 z-10">
            
            <form :action="formAction" method="POST">
                @csrf
                <template x-if="mode === 'edit'">
                    <input type="hidden" name="_method" value="PUT">
                </template>

                <div class="bg-white px-6 pt-6 pb-6 border-b border-slate-100">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-xl font-extrabold text-slate-900" id="modal-title" x-text="mode === 'create' ? 'Tambah Puskesmas' : 'Edit Puskesmas'"></h3>
                        <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-500 rounded-lg hover:bg-slate-100 p-1 transition-colors">
                            <x-icon name="x" weight="bold" class="w-5 h-5" />
                        </button>
                    </div>

                    <div class="space-y-4">
                        {{-- Row 1: Nama + Kode Faskes --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Puskesmas <span class="text-rose-500">*</span></label>
                                <input type="text" name="nama" x-model="formData.nama" required placeholder="Contoh: Puskesmas Kuta Alam"
                                       class="block w-full border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5">
                                @error('nama') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Kode Faskes <span class="text-rose-500">*</span></label>
                                <input type="text" name="kode_faskes" x-model="formData.kode_faskes" required placeholder="P1101010101"
                                       class="block w-full border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5 font-mono">
                                @error('kode_faskes') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Row 2: Kepala Puskesmas + No Telepon --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Kepala Puskesmas</label>
                                <input type="text" name="kepala_puskesmas" x-model="formData.kepala_puskesmas" placeholder="dr. Nama Lengkap"
                                       class="block w-full border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5">
                                @error('kepala_puskesmas') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Nomor Telepon</label>
                                <input type="text" name="no_telp" x-model="formData.no_telp" placeholder="0651-xxxxxxx"
                                       class="block w-full border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5">
                                @error('no_telp') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Row 3: Kecamatan + Kabupaten/Kota --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Kecamatan</label>
                                <input type="text" name="kecamatan" x-model="formData.kecamatan" placeholder="Kuta Alam"
                                       class="block w-full border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5">
                                @error('kecamatan') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Kabupaten / Kota</label>
                                <input type="text" name="kabupaten_kota" x-model="formData.kabupaten_kota" placeholder="Banda Aceh"
                                       class="block w-full border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5">
                                @error('kabupaten_kota') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Row 4: Provinsi + Alamat --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Provinsi</label>
                                <input type="text" name="provinsi" x-model="formData.provinsi" placeholder="Aceh"
                                       class="block w-full border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5">
                                @error('provinsi') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>
                            <div class="col-span-2 sm:col-span-1">
                                <label class="block text-sm font-bold text-slate-700 mb-1.5">Alamat Lengkap <span class="text-rose-500">*</span></label>
                                <textarea name="alamat" x-model="formData.alamat" required rows="2" placeholder="Jl. Cut Nyak Dien No. 1..."
                                          class="block w-full border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5"></textarea>
                                @error('alamat') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        {{-- Divider --}}
                        <div class="border-t border-slate-100 pt-4">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Akun Login</p>
                            <div class="grid grid-cols-2 gap-4">
                                {{-- Email Akun --}}
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Email Login <span class="text-rose-500">*</span></label>
                                    <input type="email" name="email" x-model="formData.email" required placeholder="p.kutaalam@nutrigen.id"
                                           class="block w-full border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5">
                                    @error('email') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                                </div>

                                {{-- Password --}}
                                <div class="col-span-2 sm:col-span-1">
                                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                                        Password <span x-show="mode === 'create'" class="text-rose-500">*</span>
                                    </label>
                                    <input type="password" name="password" :required="mode === 'create'" placeholder="Min. 6 karakter"
                                           class="block w-full border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5">
                                    <p class="text-[11px] text-slate-500 mt-1" x-show="mode === 'edit'">Kosongkan jika tidak ingin mengubah.</p>
                                    @error('password') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-2xl">
                    <button type="submit" class="inline-flex justify-center rounded-xl border border-transparent bg-teal-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500/20 transition-all active:scale-95">
                        Simpan Data
                    </button>
                    <button type="button" @click="closeModal()" class="inline-flex justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 focus:outline-none transition-all active:scale-95">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    function puskesmasData() {
        return {
            modalOpen: false,
            mode: 'create', // 'create' or 'edit'
            formAction: '{{ route('super-admin.puskesmas.store') }}',
            formData: {
                id: null,
                nama: '',
                kode_faskes: '',
                kepala_puskesmas: '',
                no_telp: '',
                kecamatan: '',
                kabupaten_kota: '',
                provinsi: '',
                email: '',
                alamat: ''
            },
            init() {
                @if($errors->any())
                    this.modalOpen = true;
                    this.mode = '{{ old('_method') === 'PUT' ? 'edit' : 'create' }}';
                    this.formAction = this.mode === 'edit' ? `/super-admin/puskesmas/{{ old('id') }}` : '{{ route('super-admin.puskesmas.store') }}';
                    this.formData = {
                        id: '{{ old('id') }}',
                        nama: '{!! addslashes(old('nama')) !!}',
                        kode_faskes: '{!! addslashes(old('kode_faskes')) !!}',
                        kepala_puskesmas: '{!! addslashes(old('kepala_puskesmas')) !!}',
                        no_telp: '{!! addslashes(old('no_telp')) !!}',
                        kecamatan: '{!! addslashes(old('kecamatan')) !!}',
                        kabupaten_kota: '{!! addslashes(old('kabupaten_kota')) !!}',
                        provinsi: '{!! addslashes(old('provinsi')) !!}',
                        email: '{!! addslashes(old('email')) !!}',
                        alamat: '{!! addslashes(old('alamat')) !!}'
                    };
                @elseif(request('edit'))
                    @php
                        $editTarget = \App\Models\Puskesmas::with('user')->find(request('edit'));
                    @endphp
                    @if($editTarget)
                        this.openModal('edit', {
                            id: {{ $editTarget->id }},
                            nama: '{{ addslashes($editTarget->nama) }}',
                            kode_faskes: '{{ addslashes($editTarget->kode_faskes) }}',
                            kepala_puskesmas: '{{ addslashes($editTarget->kepala_puskesmas ?? '') }}',
                            no_telp: '{{ addslashes($editTarget->no_telp ?? '') }}',
                            kecamatan: '{{ addslashes($editTarget->kecamatan ?? '') }}',
                            kabupaten_kota: '{{ addslashes($editTarget->kabupaten_kota ?? '') }}',
                            provinsi: '{{ addslashes($editTarget->provinsi ?? '') }}',
                            email: '{{ addslashes($editTarget->user?->email ?? '') }}',
                            alamat: '{{ addslashes($editTarget->alamat ?? '') }}'
                        });
                    @endif
                @endif
            },
            openModal(mode, data = null) {
                this.mode = mode;
                if (mode === 'edit' && data) {
                    this.formData = { ...data };
                    this.formAction = `/super-admin/puskesmas/${data.id}`;
                } else {
                    this.formData = {
                        id: null,
                        nama: '',
                        kode_faskes: '',
                        kepala_puskesmas: '',
                        no_telp: '',
                        kecamatan: '',
                        kabupaten_kota: '',
                        provinsi: '',
                        email: '',
                        alamat: ''
                    };
                    this.formAction = '{{ route('super-admin.puskesmas.store') }}';
                }
                this.modalOpen = true;
            },
            closeModal() {
                this.modalOpen = false;
            }
        }
    }
</script>
@endpush
