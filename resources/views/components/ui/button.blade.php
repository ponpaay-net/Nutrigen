@props(['variant' => 'primary'])

@php
    $baseClasses = 'font-extrabold py-3.5 px-6 rounded-[20px] transition-all duration-300 text-[14px] flex justify-center items-center active:scale-95 focus:outline-none focus-visible:ring-2';
    
    $variantClasses = match($variant) {
        'primary' => 'bg-brand hover:bg-mint-600 text-white shadow-[0_12px_24px_-4px_rgba(16,185,129,0.3)] focus-visible:ring-brand/50',
        'secondary' => 'bg-white border-2 border-slate-100 hover:border-brand hover:bg-emerald-50 text-slate-700 shadow-sm focus-visible:ring-brand/50',
        'danger' => 'bg-rose-500 hover:bg-rose-600 text-white shadow-[0_12px_24px_-4px_rgba(225,29,72,0.3)] focus-visible:ring-rose/50',
        default => 'bg-slate-100 hover:bg-slate-200 text-slate-800',
    };
@endphp

<button {{ $attributes->merge(['class' => "$baseClasses $variantClasses"]) }}>
    {{ $slot }}
</button>
