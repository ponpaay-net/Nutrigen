@props(['active' => 'home'])

<nav class="fixed bottom-0 left-0 right-0 w-full bg-white px-8 py-3 z-50 flex justify-between items-center rounded-t-3xl shadow-[0_-4px_24px_rgba(0,0,0,0.04)] pb-safe">

    <!-- Beranda Tab -->
    <a href="{!! \Illuminate\Support\Facades\URL::temporarySignedRoute('portal-ibu.home', now()->addDays(config('portal.link_ttl_days')), ['balita' => request('balita'), 'orang_tua' => request('orang_tua')]) !!}" class="flex flex-col items-center justify-center focus:outline-none w-16 group relative pb-2">
        @if($active === 'home')
            <div class="flex flex-col items-center text-[#10B981]">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-[10px] font-bold tracking-tight">Beranda</span>
                <div class="absolute bottom-0 w-1.5 h-1.5 bg-[#10B981] rounded-full"></div>
            </div>
        @else
            <div class="flex flex-col items-center text-slate-400 group-hover:text-[#10B981] transition-colors">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                <span class="text-[10px] font-medium tracking-tight">Beranda</span>
            </div>
        @endif
    </a>

    <!-- Edukasi Tab -->
    <a href="{!! \Illuminate\Support\Facades\URL::temporarySignedRoute('portal-ibu.nutrition', now()->addDays(config('portal.link_ttl_days')), ['balita' => request('balita'), 'orang_tua' => request('orang_tua')]) !!}" class="flex flex-col items-center justify-center focus:outline-none w-16 group relative pb-2">
        @if($active === 'nutrition')
            <div class="flex flex-col items-center text-[#10B981]">
                <!-- Book outline icon -->
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span class="text-[10px] font-bold tracking-tight">Edukasi</span>
                <div class="absolute bottom-0 w-1.5 h-1.5 bg-[#10B981] rounded-full"></div>
            </div>
        @else
            <div class="flex flex-col items-center text-slate-400 group-hover:text-[#10B981] transition-colors">
                <svg class="w-6 h-6 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                <span class="text-[10px] font-medium tracking-tight">Edukasi</span>
            </div>
        @endif
    </a>

    <!-- Posyandu Tab -->
    <a href="{!! \Illuminate\Support\Facades\URL::temporarySignedRoute('portal-ibu.posyandu', now()->addDays(config('portal.link_ttl_days')), ['balita' => request('balita'), 'orang_tua' => request('orang_tua')]) !!}" class="flex flex-col items-center justify-center focus:outline-none w-16 group relative pb-2">
        @if($active === 'posyandu')
            <div class="flex flex-col items-center text-[#10B981]">
                <!-- People icon outline -->
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"></path></svg>
                <span class="text-[10px] font-bold tracking-tight">Posyandu</span>
                <div class="absolute bottom-0 w-1.5 h-1.5 bg-[#10B981] rounded-full"></div>
            </div>
        @else
            <div class="flex flex-col items-center text-slate-400 group-hover:text-[#10B981] transition-colors">
                <svg class="w-6 h-6 mb-1" fill="currentColor" viewBox="0 0 24 24"><path d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V19h14v-2.5c0-2.33-4.67-3.5-7-3.5zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V19h6v-2.5c0-2.33-4.67-3.5-7-3.5z"></path></svg>
                <span class="text-[10px] font-medium tracking-tight">Posyandu</span>
            </div>
        @endif
    </a>
</nav>
