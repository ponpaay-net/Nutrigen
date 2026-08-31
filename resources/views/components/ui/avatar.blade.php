@props(['src' => null, 'initials' => 'A', 'size' => 'w-12 h-12'])

<div {{ $attributes->merge(['class' => "rounded-full overflow-hidden flex items-center justify-center bg-slate-100 border border-slate-200/50 flex-shrink-0 $size"]) }}>
    @if($src)
        <img src="{{ $src }}" alt="Avatar" class="w-full h-full object-cover">
    @else
        <span class="text-slate-500 font-bold text-[14px]">{{ $initials }}</span>
    @endif
</div>
