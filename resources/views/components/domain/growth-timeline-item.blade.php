@props(['date', 'age', 'weight', 'height', 'status' => 'normal', 'isLast' => false])

<div class="relative flex space-x-4">
    <!-- Vertical Line -->
    @if(!$isLast)
        <div class="absolute left-6 top-10 bottom-[-24px] w-[2px] bg-slate-100"></div>
    @endif
    
    <!-- Marker -->
    <div class="w-12 h-12 rounded-full flex items-center justify-center flex-shrink-0 z-10 border-4 border-white shadow-sm
        {{ $status === 'normal' ? 'bg-brand text-white' : ($status === 'kuning' ? 'bg-peach-500 text-white' : 'bg-rose-500 text-white') }}">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
    </div>
    
    <!-- Content -->
    <div class="flex-1 pb-6 pt-1">
        <h4 class="font-black text-slate-800 text-[15px] mb-0.5">{{ $date }}</h4>
        <p class="text-[12px] font-bold text-slate-500 mb-2">{{ $age }}</p>
        <div class="flex space-x-3">
            <x-ui.badge color="{{ $status === 'normal' ? 'mint' : ($status === 'kuning' ? 'peach' : 'red') }}">{{ $weight }} kg</x-ui.badge>
            <x-ui.badge color="{{ $status === 'normal' ? 'mint' : ($status === 'kuning' ? 'peach' : 'red') }}">{{ $height }} cm</x-ui.badge>
        </div>
    </div>
</div>
