@props(['title', 'subtitle' => null])

<div {{ $attributes->merge(['class' => 'px-1 mb-4']) }}>
    <h2 class="text-[17px] font-black text-slate-800 tracking-tight">{{ $title }}</h2>
    @if($subtitle)
        <p class="text-[13px] font-medium text-slate-400 mt-0.5">{{ $subtitle }}</p>
    @endif
</div>
