@props(['active' => 'profil'])

@php
    $tabs = [
        [
            'id' => 'profil',
            'label' => 'Profil Institusi',
            'route' => 'puskesmas.pengaturan',
            'icon' => 'ph-bold ph-buildings'
        ],
        [
            'id' => 'petugas',
            'label' => 'Profil Petugas',
            'route' => 'puskesmas.pengaturan.petugas',
            'icon' => 'ph-bold ph-user-circle'
        ],
        [
            'id' => 'keamanan',
            'label' => 'Keamanan Akun',
            'route' => 'puskesmas.pengaturan.keamanan',
            'icon' => 'ph-bold ph-shield-check'
        ],
        [
            'id' => 'notifikasi',
            'label' => 'Notifikasi Sistem',
            'route' => 'puskesmas.pengaturan.notifikasi',
            'icon' => 'ph-bold ph-bell-ringing'
        ],
    ];
@endphp

<div class="mt-6 flex flex-wrap gap-1.5 p-1.5 bg-black/20 backdrop-blur-md rounded-2xl border border-white/15 max-w-max shadow-inner">
    @foreach($tabs as $tab)
        @php $isActive = $active === $tab['id']; @endphp
        <a href="{{ route($tab['route']) }}"
           class="inline-flex items-center gap-2 px-3.5 sm:px-4 py-2 rounded-xl text-[12px] sm:text-[13px] font-bold transition-all {{ $isActive ? 'bg-white text-teal-900 shadow-sm' : 'text-teal-50 hover:text-white hover:bg-white/10' }}">
            <i class="{{ $tab['icon'] }} text-base {{ $isActive ? 'text-teal-600' : 'text-teal-100' }}"></i>
            <span>{{ $tab['label'] }}</span>
        </a>
    @endforeach
</div>
