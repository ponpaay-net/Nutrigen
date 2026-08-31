@props(['color' => 'mint'])

@php
    $baseClasses = 'px-3 py-1.5 rounded-full text-[11px] font-black uppercase tracking-widest border inline-flex items-center justify-center';
    $colorClasses = match($color) {
        'mint' => 'bg-emerald-50 text-emerald-700 border-emerald-200/50',
        'amber' => 'bg-amber-50 text-amber-700 border-amber-200/50',
        'rose' => 'bg-rose-50 text-rose-700 border-rose-200/50',
        'peach' => 'bg-orange-50 text-orange-700 border-orange-200/50',
        'red' => 'bg-rose-50 text-rose-700 border-rose-200/50',
        'blue' => 'bg-blue-50 text-blue-700 border-blue-200/50',
        'gray' => 'bg-slate-50 text-slate-700 border-slate-200/50',
        default => 'bg-slate-50 text-slate-700 border-slate-200/50',
    };
@endphp

<span {{ $attributes->merge(['class' => "$baseClasses $colorClasses"]) }}>
    {{ $slot }}
</span>
