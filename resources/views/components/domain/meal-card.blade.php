@props(['image' => '', 'title' => 'Menu Alternatif', 'calories' => '300 Kkal'])

<button class="w-[160px] flex-shrink-0 text-left focus:outline-none group active:scale-95 transition-transform" x-on:click="openRecipe = true">
    <div class="w-full h-40 rounded-[24px] overflow-hidden bg-slate-100 relative mb-3 shadow-soft group-hover:shadow-card transition-shadow border border-slate-100">
        @if($image)
            <img src="{{ $image }}" alt="{{ $title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
        @else
            <div class="w-full h-full flex items-center justify-center bg-slate-50 text-4xl">🍛</div>
        @endif
    </div>
    <h4 class="font-black text-[14px] text-slate-800 leading-snug mb-1 group-hover:text-brand transition-colors">{{ $title }}</h4>
    <p class="text-[11px] font-bold text-brand">{{ $calories }}</p>
</button>
