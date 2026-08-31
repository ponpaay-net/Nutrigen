@props(['countdown' => null, 'posyanduName' => 'Posyandu', 'schedule' => '', 'address' => null])

<x-ui.card padding="p-5" class="relative overflow-hidden bg-gradient-to-b from-blue-50 to-white border-blue-100">
    <!-- Countdown Badge -->
    @if($countdown)
    <div class="absolute top-4 right-4 bg-red-50 text-red-600 px-3 py-1.5 rounded-full border border-red-100 flex items-center shadow-sm">
        <span class="w-1.5 h-1.5 bg-red-500 rounded-full mr-1.5 animate-pulse"></span>
        <span class="text-[10px] font-black uppercase tracking-wider">{{ $countdown }}</span>
    </div>
    @endif

    <p class="text-[11px] font-black text-blue-500 uppercase tracking-widest mb-3">{{ $posyanduName }}</p>
    
    <div class="flex items-start space-x-4 mb-4 mt-2">
        <div class="bg-blue-100 text-blue-600 p-3.5 rounded-[18px] border border-blue-200 flex-shrink-0 mt-1">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
        </div>
        <div>
            <h3 class="font-black text-slate-800 text-[14px] mb-1">Jadwal Penimbangan</h3>
            <p class="text-[18px] font-black text-brand leading-tight">{{ $schedule }}</p>
        </div>
    </div>

    @if($address)
    <div class="pt-4 border-t border-blue-100 mt-2 flex items-start space-x-2 text-slate-600">
        <svg class="w-4 h-4 flex-shrink-0 mt-0.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        <p class="text-[12px] font-bold leading-relaxed">{{ $address }}</p>
    </div>
    @endif
</x-ui.card>
