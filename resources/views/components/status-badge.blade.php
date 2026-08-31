@props(['type' => 'warning', 'label' => ''])

@php
    $bgClass = '';
    $textClass = '';
    $dotClass = '';

    switch($type) {
        case 'danger':
        case 'berisiko':
        case 'rejected':
            $bgClass = 'bg-rose-50 border-rose-200';
            $textClass = 'text-rose-700';
            $dotClass = 'bg-rose-500';
            break;
        case 'warning':
        case 'anomali':
        case 'pending':
            $bgClass = 'bg-amber-50 border-amber-200';
            $textClass = 'text-amber-700';
            $dotClass = 'bg-amber-500';
            break;
        case 'success':
        case 'approved':
            $bgClass = 'bg-emerald-50 border-emerald-200';
            $textClass = 'text-emerald-700';
            $dotClass = 'bg-emerald-500';
            break;
        case 'info':
            $bgClass = 'bg-blue-50 border-blue-200';
            $textClass = 'text-blue-700';
            $dotClass = 'bg-blue-500';
            break;
        default:
            $bgClass = 'bg-slate-50 border-slate-200';
            $textClass = 'text-slate-700';
            $dotClass = 'bg-slate-500';
            break;
    }
@endphp

<div {{ $attributes->merge(['class' => "flex items-center gap-1.5 font-bold $textClass w-max"]) }}>
    <div class="w-1.5 h-1.5 rounded-full {{ $dotClass }}"></div>
    <span class="text-[9px] uppercase tracking-wider">{{ $label }}</span>
</div>
