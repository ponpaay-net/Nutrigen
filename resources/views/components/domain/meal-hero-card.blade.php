@props(['image' => '', 'title' => '', 'calories' => '', 'duration' => ''])

<div class="relative w-full rounded-[32px] overflow-hidden shadow-card group">
    <!-- Image -->
    <div class="h-72 w-full bg-slate-200 relative">
        @if($image)
            <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
        @else
            <div class="w-full h-full flex items-center justify-center bg-slate-100 text-5xl">🍲</div>
        @endif
        
        <!-- Gradient Overlay -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent"></div>
    </div>

    <!-- Content -->
    <div class="absolute bottom-0 left-0 w-full p-6 text-white">
        <div class="flex items-center space-x-2 mb-2">
            <x-ui.badge color="mint" class="bg-brand/90 backdrop-blur border-none text-white shadow-none">{{ $calories }}</x-ui.badge>
            <x-ui.badge color="gray" class="bg-white/20 backdrop-blur border-none text-white shadow-none">{{ $duration }}</x-ui.badge>
        </div>
        <h2 class="text-[24px] font-black leading-tight mb-4 drop-shadow-sm">{{ $title }}</h2>
        <x-ui.button variant="primary" class="shadow-none border border-white/20 bg-white/20 backdrop-blur hover:bg-white hover:text-brand" x-on:click="openRecipe = true">
            Lihat Resep Lengkap
        </x-ui.button>
    </div>
</div>
