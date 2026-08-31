@props(['padding' => 'p-5'])

<div {{ $attributes->merge(['class' => "bg-white rounded-[28px] shadow-[0_4px_24px_-8px_rgba(0,0,0,0.06)] border border-slate-100/60 " . $padding]) }}>
    {{ $slot }}
</div>
