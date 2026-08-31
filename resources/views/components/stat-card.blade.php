@props(['color' => 'blue', 'title', 'value'])

@php
    $bgClass = 'bg-white border-slate-100';
    $iconBgClass = '';
    $iconColorClass = '';
    
    switch($color) {
        case 'emerald':
            $iconBgClass = 'bg-emerald-50';
            $iconColorClass = 'text-emerald-500';
            break;
        case 'blue':
            $iconBgClass = 'bg-blue-50';
            $iconColorClass = 'text-blue-500';
            break;
        case 'amber':
            $iconBgClass = 'bg-amber-50';
            $iconColorClass = 'text-amber-500';
            break;
        case 'teal':
            $iconBgClass = 'bg-[#06667A]/10';
            $iconColorClass = 'text-[#06667A]';
            break;
        default:
            $iconBgClass = 'bg-slate-50';
            $iconColorClass = 'text-slate-500';
            break;
    }
@endphp

<div class="{{ $bgClass }} rounded-[24px] p-5 flex flex-col border shadow-sm transition-all duration-300 hover:shadow-md hover:border-slate-200 h-full">
    
    <!-- Icon Container -->
    <div class="w-10 h-10 rounded-full {{ $iconBgClass }} {{ $iconColorClass }} flex items-center justify-center shrink-0 mb-4">
        {{ $slot }}
    </div>
    
    <!-- Content -->
    <div class="flex flex-col flex-1">
        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-1">{{ $title }}</span>
        
        <div class="mt-auto flex flex-wrap items-baseline gap-2">
            <span class="text-3xl lg:text-4xl font-bold text-slate-900 tracking-tight leading-none">{{ $value }}</span>
            
            @if(isset($subtext))
                <div class="text-[11px] text-slate-500 font-medium">
                    {{ $subtext }}
                </div>
            @endif
        </div>
    </div>
</div>
