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
    <!-- Notification Bell -->
    <div class="relative">
        <button class="w-11 h-11 rounded-xl bg-white border border-[#C8E6C9]/60 shadow-[0_2px_10px_rgba(46,125,50,0.08)] flex items-center justify-center text-slate-700 focus:outline-none active:scale-95 transition-transform">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
        </button>
        <!-- Red Dot -->
        <div class="absolute top-1 right-1.5 w-[9px] h-[9px] bg-[#EF4444] rounded-full border-2 border-white"></div>
    </div>
</div>
