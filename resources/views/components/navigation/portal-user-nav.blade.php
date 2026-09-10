@props(['initials' => 'A', 'avatar' => null])

{{-- Komponen bersama: Avatar + Notifikasi. Dipakai di SEMUA halaman Portal Ibu --}}
<div class="flex items-center gap-3 shrink-0">
    <!-- Avatar -->
    <div class="w-[46px] h-[46px] rounded-full bg-[#2E7D32] border-[3px] border-[#C8E6C9] shadow-sm flex items-center justify-center overflow-hidden relative">
        @if($avatar)
            <img src="{{ $avatar }}" alt="Avatar" class="w-full h-full object-cover">
        @else
            <span class="text-white font-black text-[16px] tracking-wide">{{ strtoupper(substr($initials, 0, 1)) }}</span>
        @endif
    </div>
</div>
