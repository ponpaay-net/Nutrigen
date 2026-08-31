@props(['state' => 'normal', 'icon' => '🥰', 'title' => 'Hebat!', 'message' => '', 'class' => ''])

@php
    $bgClasses = match ($state) {
        'normal', 'pending' => 'bg-emerald-600 border-emerald-500 shadow-[0_12px_40px_-12px_rgba(5,150,105,0.5)]',
        'kuning' => 'bg-amber-500 border-amber-400 shadow-[0_12px_40px_-12px_rgba(245,158,11,0.5)]',
        'merah' => 'bg-rose-600 border-rose-500 shadow-[0_12px_40px_-12px_rgba(225,29,72,0.5)]',
        'empty' => 'bg-blue-600 border-blue-500 shadow-[0_12px_40px_-12px_rgba(37,99,235,0.5)]',
        default => 'bg-slate-800 border-slate-700',
    };

    $textClasses = match ($state) {
        default => 'text-white',
    };
@endphp

<div {{ $attributes->merge(['class' => "rounded-[32px] p-6 text-center relative overflow-hidden flex flex-col items-center border transition-all duration-500 $bgClasses $class"]) }}>
    <!-- Decorative Circle -->
    <div class="absolute top-0 right-0 -mr-4 -mt-4 opacity-10 text-current">
        <svg width="100" height="100" viewBox="0 0 100 100" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="50"/></svg>
    </div>

    <!-- Dynamic Emoji -->
    <div class="text-[56px] mb-2 mt-1 filter drop-shadow-sm leading-none z-10 transform hover:scale-105 transition-transform duration-300">
        {{ $icon }}
    </div>

    <!-- Title & Message -->
    <h2 class="text-[20px] font-black leading-tight mb-1.5 z-10 {{ $textClasses }}">
        {{ $title }}
    </h2>
    <p class="text-[12.5px] font-bold opacity-80 max-w-[260px] mx-auto mb-4 z-10 {{ $textClasses }}">
        {{ $message }}
    </p>

    {{ $slot }}
</div>
