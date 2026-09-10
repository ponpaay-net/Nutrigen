@extends('layouts.app')

@section('page-title', $posyandu->nama . ' — NutriGen')

@section('content')
<div class="max-w-[1440px] mx-auto px-4 sm:px-6 lg:px-8 py-8 w-full min-h-screen" style="background:#F7F8FA; font-family:'Plus Jakarta Sans',sans-serif;" x-data="kaderData()">

    {{-- Breadcrumb --}}
    <nav class="flex items-center justify-between text-sm font-medium text-slate-500 mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center flex-wrap gap-y-1">
            <li><a href="{{ route('super-admin.dashboard') }}" class="inline-flex items-center hover:text-teal-600 transition-colors"><x-icon name="squares-four" weight="fill" class="text-base mr-1.5" />Dashboard</a></li>
            <li class="flex items-center"><x-icon name="caret-right" weight="bold" class="text-sm mx-1.5" /><a href="{{ route('super-admin.puskesmas.show', $posyandu->puskesmas_id) }}" class="hover:text-teal-600 transition-colors">{{ $posyandu->puskesmas?->nama ?? 'Puskesmas' }}</a></li>
            <li class="flex items-center"><x-icon name="caret-right" weight="bold" class="text-sm mx-1.5" /><span class="text-slate-900 font-bold">{{ $posyandu->nama }}</span></li>
        </ol>
        <a href="{{ route('super-admin.puskesmas.show', $posyandu->puskesmas_id) }}" class="hidden sm:inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-200 bg-white text-slate-600 hover:bg-slate-50 font-bold text-xs shadow-sm">
            <x-icon name="arrow-left" weight="bold" class="text-sm mr-1.5" /> Kembali
        </a>
    </nav>

    {{-- Header --}}
    <div class="relative rounded-2xl shadow-sm overflow-hidden mb-8 border border-slate-100">
        <div class="absolute inset-0 bg-gradient-to-br from-teal-600 to-emerald-800"></div>
        <x-icon name="storefront" weight="fill" class="absolute -right-8 -bottom-12 text-[220px] text-white opacity-5 -rotate-12" />
        <div class="relative p-6 sm:p-8 flex flex-col sm:flex-row sm:items-end justify-between gap-5">
            <div class="flex items-start sm:items-center gap-5 text-white">
                <div class="w-16 h-16 rounded-2xl bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center shrink-0">
                    <x-icon name="storefront" weight="fill" class="text-[38px] text-white" />
                </div>
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-teal-100/90 mb-1">Detail Posyandu</p>
                    <h1 class="text-2xl sm:text-3xl font-extrabold tracking-tight">{{ $posyandu->nama }}</h1>
                    <p class="text-sm text-teal-50/90 mt-1.5 flex items-center gap-1.5">
                        <x-icon name="map-pin" weight="fill" class="text-base opacity-75" />
                        {{ $posyandu->desa_kelurahan ?? '-' }}{{ $posyandu->alamat ? ' · ' . $posyandu->alamat : '' }}
                    </p>
                </div>
            </div>
            <button @click="openModal('create')" class="inline-flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-white text-teal-700 hover:bg-teal-50 font-bold text-sm shadow-sm active:scale-95 transition-all shrink-0">
                <x-icon name="user-plus" weight="bold" /> Tambah Kader
            </button>
        </div>
    </div>

    {{-- KPI --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0"><x-icon name="users" weight="fill" class="text-2xl" /></div>
            <div><p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Total Kader</p><h3 class="text-2xl font-extrabold text-slate-900">{{ $posyandu->kaders_count }}</h3></div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0"><x-icon name="baby" weight="fill" class="text-2xl" /></div>
            <div><p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Balita Terdaftar</p><h3 class="text-2xl font-extrabold text-slate-900">{{ $posyandu->balitas_count }}</h3></div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-4 col-span-2">
            <div class="w-12 h-12 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0"><x-icon name="buildings" weight="fill" class="text-2xl" /></div>
            <div class="min-w-0"><p class="text-xs font-bold text-slate-500 uppercase tracking-wide">Puskesmas Induk</p><h3 class="text-lg font-extrabold text-slate-900 truncate">{{ $posyandu->puskesmas?->nama ?? '-' }}</h3></div>
        </div>
    </div>

    {{-- Tabel Kader --}}
    <div class="mb-8">
        <h2 class="text-lg font-extrabold text-slate-900 mb-4">Daftar Kader</h2>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Kader</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Akun & Kontak</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Ukur Bulan Ini</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($kaders as $k)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-4 px-6">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-full bg-teal-50 text-teal-700 border border-teal-100 flex items-center justify-center font-bold text-sm shrink-0">{{ strtoupper(substr($k['nama'], 0, 1)) }}</div>
                                        <div>
                                            <div class="font-bold text-slate-900">{{ $k['nama'] }}</div>
                                            <div class="text-[11px] text-slate-400">Bergabung {{ $k['created_at'] }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6">
                                    <div class="text-sm text-slate-700">{{ $k['email'] }}</div>
                                    <div class="text-[11px] text-slate-400 mt-0.5">{{ $k['no_hp'] }}</div>
                                </td>
                                <td class="py-4 px-6 text-center">
                                    <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-xs font-bold bg-teal-50 text-teal-700">{{ $k['aktivitas_bulan_ini'] }}×</span>
                                </td>
                                <td class="py-4 px-6 text-right space-x-2 whitespace-nowrap">
                                    <button @click="openModal('edit', {{ json_encode($k) }})" class="inline-flex items-center justify-center p-2.5 rounded-xl bg-teal-50 text-teal-600 hover:bg-teal-100 transition-colors" title="Edit"><x-icon name="pencil-simple" weight="bold" class="w-5 h-5" /></button>
                                    <form action="{{ route('super-admin.kader.destroy', $k['id']) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus Kader {{ $k['nama'] }}? Akun login kader juga akan dihapus.');">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center p-2.5 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-100 transition-colors" title="Hapus"><x-icon name="trash" weight="bold" class="w-5 h-5" /></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="4" class="py-14 text-center">
                                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-slate-100 text-slate-400 mb-3"><x-icon name="users" weight="fill" class="text-2xl" /></div>
                                <h3 class="text-base font-bold text-slate-900 mb-1">Belum ada Kader</h3>
                                <p class="text-sm text-slate-500">Tambahkan kader untuk posyandu ini.</p>
                            </td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Tabel Balita --}}
    <div>
        <h2 class="text-lg font-extrabold text-slate-900 mb-4">Daftar Balita</h2>
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-100">
                            <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Balita</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider">Usia</th>
                            <th class="py-3 px-6 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status Gizi Terakhir</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($balitas as $b)
                            <tr class="hover:bg-slate-50/50 transition-colors">
                                <td class="py-3.5 px-6">
                                    <div class="font-semibold text-slate-900">{{ $b['nama'] }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $b['gender'] === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                                </td>
                                <td class="py-3.5 px-6 text-sm text-slate-600">{{ $b['age'] }}</td>
                                <td class="py-3.5 px-6 text-center">
                                    @php $sc = strtolower($b['status']); $cls = str_contains($sc,'stunting') ? 'bg-rose-50 text-rose-700' : (str_contains($sc,'risiko')||str_contains($sc,'kurang') ? 'bg-amber-50 text-amber-700' : (str_contains($sc,'belum') ? 'bg-slate-100 text-slate-500' : 'bg-emerald-50 text-emerald-700')); @endphp
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold {{ $cls }}">{{ ucfirst($b['status']) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="py-14 text-center text-sm text-slate-500">Belum ada balita terdaftar di posyandu ini.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- MODAL KADER --}}
    <div x-show="modalOpen" style="display:none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4" role="dialog" aria-modal="true">
        <div x-show="modalOpen" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="closeModal()"></div>
        <div x-show="modalOpen" x-transition:enter="ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-4 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             class="relative bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden z-10">
            <form :action="formAction" method="POST">
                @csrf
                <input type="hidden" name="id" :value="formData.id">
                <template x-if="mode === 'edit'"><input type="hidden" name="_method" value="PUT"></template>

                <div class="px-6 pt-6 pb-6 border-b border-slate-100">
                    <div class="flex items-center justify-between mb-5">
                        <h3 class="text-xl font-extrabold text-slate-900" x-text="mode === 'create' ? 'Tambah Kader' : 'Edit Kader'"></h3>
                        <button type="button" @click="closeModal()" class="text-slate-400 hover:text-slate-600 rounded-lg hover:bg-slate-100 p-1"><x-icon name="x" weight="bold" class="w-5 h-5" /></button>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Nama Kader <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama" x-model="formData.nama" required placeholder="Contoh: Cut Malahayati"
                                   class="block w-full border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5">
                            @error('nama') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Email Login <span class="text-rose-500">*</span></label>
                            <input type="email" name="email" x-model="formData.email" required placeholder="kader@nutrigen.id"
                                   class="block w-full border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5">
                            @error('email') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">No. WhatsApp</label>
                            <input type="text" name="no_hp" x-model="formData.no_hp" placeholder="08xxxxxxxxxx"
                                   class="block w-full border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5">
                            @error('no_hp') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">
                                Password <span x-show="mode === 'create'" class="text-rose-500">*</span>
                            </label>
                            <input type="password" name="password" :required="mode === 'create'" placeholder="Min. 6 karakter"
                                   class="block w-full border border-slate-200 rounded-xl text-sm focus:border-teal-500 focus:ring-teal-500/20 bg-slate-50 px-3 py-2.5">
                            <p class="text-[11px] text-slate-500 mt-1" x-show="mode === 'edit'">Kosongkan jika tidak ingin mengubah password.</p>
                            @error('password') <p class="text-xs text-rose-500 mt-1 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-6 py-4 flex flex-row-reverse gap-3">
                    <button type="submit" class="inline-flex justify-center rounded-xl bg-teal-600 px-5 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-teal-700 transition-all active:scale-95">Simpan</button>
                    <button type="button" @click="closeModal()" class="inline-flex justify-center rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-bold text-slate-700 hover:bg-slate-50 transition-all active:scale-95">Batal</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function kaderData() {
        return {
            modalOpen: false,
            mode: 'create',
            formAction: '{{ route('super-admin.posyandu.kader.store', $posyandu->id) }}',
            formData: { id: null, nama: '', email: '', no_hp: '' },
            openModal(mode, data = null) {
                this.mode = mode;
                if (mode === 'edit' && data) {
                    this.formData = { id: data.id, nama: data.nama || '', email: data.email || '', no_hp: data.no_hp || '' };
                    this.formAction = '{{ url('/super-admin/kader') }}/' + data.id;
                } else {
                    this.formData = { id: null, nama: '', email: '', no_hp: '' };
                    this.formAction = '{{ route('super-admin.posyandu.kader.store', $posyandu->id) }}';
                }
                this.modalOpen = true;
            },
            closeModal() { this.modalOpen = false; }
        }
    }
</script>
@endpush