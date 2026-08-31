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
                <x-ui.button variant="primary" class="w-full max-w-[280px] mt-4 shadow-card-green">
                    <svg class="w-5 h-5 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                    <span>Hubungi Kader via WA</span>
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
