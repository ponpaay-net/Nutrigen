@props(['recipe' => []])

<!-- ALPINE COMPONENT: Should be placed inside x-data="{ openRecipe: false }" -->
<div x-show="openRecipe" class="fixed inset-0 z-50 flex items-end justify-center sm:items-center" style="display: none;">
    <!-- Backdrop -->
    <div x-show="openRecipe" x-transition.opacity class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" @click="openRecipe = false"></div>
    
    <!-- Sheet -->
    <div x-show="openRecipe" 
         x-transition:enter="transition ease-out duration-300 transform" 
         x-transition:enter-start="translate-y-full" 
         x-transition:enter-end="translate-y-0" 
         x-transition:leave="transition ease-in duration-200 transform" 
         x-transition:leave-start="translate-y-0" 
         x-transition:leave-end="translate-y-full"
         class="w-full max-w-md bg-white rounded-t-[32px] h-[85vh] flex flex-col relative z-10 shadow-2xl overflow-hidden sm:rounded-[32px] sm:h-[80vh]">
        
        <!-- Handle -->
        <div class="w-full flex justify-center py-4 bg-white/90 backdrop-blur-sm absolute top-0 z-20 cursor-pointer" @click="openRecipe = false">
            <div class="w-12 h-1.5 bg-slate-200 rounded-full"></div>
        </div>

        <div class="flex-1 overflow-y-auto hide-scrollbar pt-14 pb-12">
            <div class="px-6 space-y-6">
                <!-- Recipe Image Placeholder -->
                <div class="w-full h-48 rounded-[24px] bg-mint-50 overflow-hidden relative border border-mint-100 flex items-center justify-center">
                    <span class="text-6xl filter drop-shadow-sm">🍳</span>
                </div>
                
                <div>
                    <h2 class="text-[22px] font-black text-slate-800 leading-tight mb-2">{{ $recipe['title'] ?? 'Sup Ayam Makaroni Sayur' }}</h2>
                    <p class="text-[13px] font-bold text-brand">{{ $recipe['calories'] ?? 'Tinggi Kalori • 350 Kkal' }}</p>
                </div>

                <x-ui.divider />

                <div>
                    <h3 class="text-[15px] font-black text-slate-800 mb-3">Bahan-bahan</h3>
                    <ul class="space-y-3 text-[13.5px] font-bold text-slate-600">
                        <li class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-mint-400 mr-3 shadow-sm"></span>100gr Ayam fillet potong dadu</li>
                        <li class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-mint-400 mr-3 shadow-sm"></span>50gr Makaroni rebus</li>
                        <li class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-mint-400 mr-3 shadow-sm"></span>Wortel & Brokoli cincang</li>
                        <li class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-mint-400 mr-3 shadow-sm"></span>Bawang putih, kaldu bubuk, lada</li>
                    </ul>
                </div>
                
                <x-ui.divider />

                <div>
                    <h3 class="text-[15px] font-black text-slate-800 mb-3">Langkah Memasak</h3>
                    <ol class="space-y-4 text-[13.5px] font-bold text-slate-600">
                        <li class="flex items-start">
                            <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0 mr-3 mt-0.5 text-[11px] font-black">1</span>
                            <span class="pt-0.5 leading-relaxed">Tumis bawang putih hingga harum, masukkan air secukupnya. Tunggu hingga mendidih.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0 mr-3 mt-0.5 text-[11px] font-black">2</span>
                            <span class="pt-0.5 leading-relaxed">Masukkan potongan ayam fillet, masak hingga ayam matang dan kaldu keluar.</span>
                        </li>
                        <li class="flex items-start">
                            <span class="w-6 h-6 rounded-full bg-slate-100 text-slate-600 flex items-center justify-center flex-shrink-0 mr-3 mt-0.5 text-[11px] font-black">3</span>
                            <span class="pt-0.5 leading-relaxed">Tambahkan wortel, brokoli, dan makaroni. Bumbui dengan kaldu bubuk dan lada. Sajikan.</span>
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        
        <!-- Sticky Action Button -->
        <div class="absolute bottom-0 w-full p-4 bg-white border-t border-slate-100">
            <x-ui.button variant="primary" x-on:click="openRecipe = false">Tutup Resep</x-ui.button>
        </div>
    </div>
</div>
