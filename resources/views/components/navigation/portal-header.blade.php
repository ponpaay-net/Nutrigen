@props([
    'variant' => 'page',           // 'greeting' (beranda) | 'page' (halaman dalam)
    'title' => '',
    'eyebrow' => '',               // teks kecil di atas judul (varian page)
    'hasBack' => false,
    'backUrl' => url()->previous(),
    'name' => '',                  // nama anak (varian greeting)
    'avatar' => null,
    'initials' => 'A',
])

{{-- ============================================================
    KOMPONEN HEADER BERSAMA PORTAL IBU
    Dipakai di SEMUA halaman portal-ibu agar mudah diubah
    dari satu tempat saja.
============================================================= --}}

{{-- ====== VARIAN GREETING (Beranda) ====== --}}
@if($variant === 'greeting')
    @php
        $hour = (int) now()->format('H');
        if ($hour < 11)      { $greeting = 'Selamat pagi'; $greetIcon = '☀️'; }
        elseif ($hour < 15)  { $greeting = 'Selamat siang'; $greetIcon = '🌞'; }
        elseif ($hour < 19)  { $greeting = 'Selamat sore'; $greetIcon = '🌤️'; }
        else                 { $greeting = 'Selamat malam'; $greetIcon = '🌙'; }
        $initial = strtoupper(substr($name ?: $initials ?: 'IB', 0, 1));
    @endphp

    <header {{ $attributes->merge(['class' => 'flex items-start justify-between mb-6']) }}>
        <div class="flex items-center gap-3.5">
            <!-- Avatar -->
            <div class="w-[58px] h-[58px] rounded-full bg-[#2E7D32] border-[3px] border-[#C8E6C9] shadow-sm flex items-center justify-center shrink-0 overflow-hidden">
                @if($avatar)
                    <img src="{{ $avatar }}" alt="Avatar" class="w-full h-full object-cover">
                @else
                    <span class="text-white font-black text-[20px] tracking-wide">{{ $initial }}</span>
                @endif
            </div>
            <!-- Info -->
            <div>
                <p class="text-[13px] text-slate-500 font-semibold mb-0.5">{{ $greeting }}, Ibu! <span>{{ $greetIcon }}</span></p>
                <h1 class="text-[21px] font-black text-[#1B5E20] leading-none tracking-tight truncate max-w-[150px] sm:max-w-[200px] mb-1.5">
                    {{ $name ?: 'Ibu' }}
                </h1>
                <div class="inline-flex items-center gap-1 bg-[#E8F5E9] px-2.5 py-1 rounded-full w-max">
                    <svg class="w-3 h-3 text-[#2E7D32]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                    <span class="text-[10px] text-[#2E7D32] font-extrabold">Akun Terverifikasi</span>
                </div>
            </div>
        </div>

        <!-- Avatar Kecil + Notifikasi -->
        <x-navigation.portal-user-nav :initials="$initial" :avatar="$avatar" class="mt-0.5" />
    </header>
@else
    {{-- ====== VARIAN PAGE (Pertumbuhan / Edukasi / Posyandu) ====== --}}
    <header {{ $attributes->merge(['class' => 'sticky top-0 z-30 bg-[#F1F8F2]/95 backdrop-blur-xl px-5 pt-8 pb-4 flex items-center justify-between border-b border-[#C8E6C9]/40']) }}>
        <div class="flex items-center gap-3 min-w-0">
            @if($hasBack)
                <a href="{{ $backUrl }}" aria-label="Kembali" class="w-10 h-10 shrink-0 rounded-xl bg-white border border-[#C8E6C9]/60 shadow-sm flex items-center justify-center text-slate-700 active:scale-95 transition-transform focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
            @endif
            <div class="min-w-0">
                @if($eyebrow)
                    <h2 class="text-[13px] font-extrabold tracking-tight text-[#2E7D32] leading-none mb-1">{{ $eyebrow }}</h2>
                @endif
                <h1 class="text-[20px] font-black text-[#1B5E20] leading-none tracking-tight truncate">{{ $title }}</h1>
            </div>
        </div>

        <!-- Avatar + Notifikasi -->
        <x-navigation.portal-user-nav :initials="$initials" :avatar="$avatar" />
    </header>
@endif
