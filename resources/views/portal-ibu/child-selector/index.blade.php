<x-layout.mobile-shell>
    <div x-data="{ state: '{{ $pageState ?? 'normal' }}' }" class="flex-1 flex flex-col p-5 relative overflow-y-auto hide-scrollbar w-full pb-safe">
        
        <!-- LOADING OVERLAY -->
        <template x-if="state === 'loading'">
            <div class="space-y-6">
                <!-- Header Skeleton -->
                <div class="mb-8 space-y-2 animate-pulse mt-4">
                    <div class="h-4 bg-slate-200 rounded-full w-32"></div>
                    <div class="h-6 bg-slate-200 rounded-full w-48"></div>
                </div>
                <!-- Card Skeleton 1 -->
                <x-ui.card padding="p-4" class="flex items-center space-x-4 animate-pulse">
                    <div class="w-[60px] h-[60px] bg-slate-200 rounded-full flex-shrink-0"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-5 bg-slate-200 rounded-full w-3/4"></div>
                        <div class="h-3 bg-slate-200 rounded-full w-1/2"></div>
                        <div class="h-3 bg-slate-200 rounded-full w-1/3"></div>
                    </div>
                </x-ui.card>
                <!-- Card Skeleton 2 -->
                <x-ui.card padding="p-4" class="flex items-center space-x-4 animate-pulse">
                    <div class="w-[60px] h-[60px] bg-slate-200 rounded-full flex-shrink-0"></div>
                    <div class="flex-1 space-y-2">
                        <div class="h-5 bg-slate-200 rounded-full w-3/4"></div>
                        <div class="h-3 bg-slate-200 rounded-full w-1/2"></div>
                        <div class="h-3 bg-slate-200 rounded-full w-1/3"></div>
                    </div>
                </x-ui.card>
            </div>
        </template>

        <!-- EMPTY / UNLINKED STATE -->
        <template x-if="state === 'empty' || state === 'unlinked'">
            <div class="flex-1 flex flex-col items-center justify-center -mt-10">
                <x-feedback.empty-state 
                    title="Belum Ada Data Anak" 
                    message="Ibu belum memiliki data anak yang ditautkan ke akun ini. Silakan hubungi kader posyandu."
                    actionText="Hubungi Kader">
                    <x-slot name="icon">
                        <svg class="w-10 h-10 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </x-slot>
                </x-feedback.empty-state>
                <x-ui.button variant="primary" disabled class="w-full max-w-[280px] mt-4 bg-[#C8E6C9] text-[#2E7D32] cursor-not-allowed opacity-80 shadow-none">
                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span>Hubungi Kader (Segera Hadir)</span>
                </x-ui.button>
            </div>
        </template>

        <!-- ERROR STATE -->
        <template x-if="state === 'error'">
            <div class="flex-1 flex items-center justify-center">
                <x-feedback.error-state />
            </div>
        </template>

        <!-- MAIN CONTENT (MULTI-CHILD) -->
        <div x-show="state === 'normal'" style="display: none;" class="flex-1 flex flex-col" x-transition>
            
            <!-- HEADER -->
            <div class="mb-6 mt-2 px-1">
                <p class="text-[13px] text-slate-500 font-extrabold mb-0.5 tracking-wide">{{ $greeting ?? 'Selamat pagi, Ibunda' }}</p>
                <h1 class="text-slate-900 font-black text-[22px] leading-tight">
                    Mari pilih profil si kecil
                </h1>
            </div>

            <!-- CHILD LIST -->
            <div class="space-y-4 flex-1">
                @forelse($children ?? [] as $child)
                    <!-- Child Card Button -->
                    <a href="{{ $child['url'] }}" class="block w-full text-left focus:outline-none focus:ring-4 focus:ring-mint-100 rounded-[28px] transition-transform active:scale-[0.98] group">
                        <x-ui.card padding="p-4" class="flex items-center group-hover:border-mint-200 transition-colors">
                            
                            <x-ui.avatar src="{{ $child['avatar'] ?? null }}" initials="{{ $child['initials'] ?? 'A' }}" size="w-[60px] h-[60px]" class="mr-4" />
                            
                            <div class="flex-1 min-w-0 pr-2">
                                <h3 class="font-black text-[17px] text-slate-800 leading-tight mb-0.5 truncate">{{ $child['name'] ?? 'Nama Anak' }}</h3>
                                <p class="text-[13px] text-slate-500 font-bold mb-1 truncate">{{ $child['age'] ?? 'Umur Anak' }}</p>
                                
                                @if(isset($child['status']))
                                    <p class="text-[11px] font-black text-brand tracking-wide truncate">{{ $child['status'] }}</p>
                                @endif
                            </div>

                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-slate-300 group-hover:text-brand transition-colors bg-slate-50 group-hover:bg-mint-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                            </div>

                        </x-ui.card>
                    </a>
                @empty
                    <!-- Fallback data dummy jika array kosong untuk preview saat development -->
                    <button class="w-full text-left focus:outline-none focus:ring-4 focus:ring-mint-100 rounded-[28px] transition-transform active:scale-[0.98] group">
                        <x-ui.card padding="p-4" class="flex items-center group-hover:border-mint-200 transition-colors">
                            <x-ui.avatar initials="A" size="w-[60px] h-[60px]" class="mr-4" />
                            <div class="flex-1 min-w-0 pr-2">
                                <h3 class="font-black text-[17px] text-slate-800 leading-tight mb-0.5 truncate">Aisyah Putri</h3>
                                <p class="text-[13px] text-slate-500 font-bold mb-1 truncate">2 Tahun 4 Bulan</p>
                                <p class="text-[11px] font-black text-brand tracking-wide truncate">Perlu Pantauan</p>
                            </div>
                            <div class="w-10 h-10 rounded-full flex items-center justify-center text-slate-300 group-hover:text-brand transition-colors bg-slate-50 group-hover:bg-mint-50">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"></path></svg>
                            </div>
                        </x-ui.card>
                    </button>
                @endforelse
            </div>

            <!-- FOOTER HELP -->
            <div class="mt-8 mb-6 text-center">
                <p class="text-[12px] font-bold text-slate-400 mb-1">Ada profil anak yang belum terdaftar?</p>
                <button class="text-[12px] font-black text-brand hover:text-emerald-700 underline focus:outline-none">
                    Minta bantuan Kader Posyandu
                </button>
            </div>
            
        </div>
    </div>
</x-layout.mobile-shell>
