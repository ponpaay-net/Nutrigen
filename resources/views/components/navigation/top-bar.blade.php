@props(['title' => '', 'hasBack' => true, 'backUrl' => url()->previous(), 'class' => ''])

<header {{ $attributes->merge(['class' => "sticky top-0 z-40 bg-surface/95 backdrop-blur-md px-6 py-4 flex items-center justify-between border-b border-slate-100/50 $class"]) }}>
    <div class="flex items-center space-x-3">
        @if($hasBack)
            <a href="{{ $backUrl }}" class="w-10 h-10 rounded-full bg-white border border-slate-200 flex items-center justify-center text-slate-600 hover:text-brand transition-colors focus:outline-none shadow-sm active:scale-95">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M15 19l-7-7 7-7"></path></svg>
            </a>
        @endif
        <h1 class="text-[19px] font-black text-slate-800 tracking-tight">{{ $title }}</h1>
    </div>
    {{ $slot }}
</header>
