@props([
    'child',
    'isActive' => false
])

@php
    $initials = collect(explode(' ', $child['name']))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');

    $statusConfig = [
        'danger'  => ['dot' => 'bg-rose-500',   'badge' => 'bg-rose-50 text-rose-600 border-rose-100',   'avatar' => 'bg-rose-100 text-rose-600'],
        'warning' => ['dot' => 'bg-amber-400',   'badge' => 'bg-amber-50 text-amber-600 border-amber-100', 'avatar' => 'bg-amber-100 text-amber-600'],
        'success' => ['dot' => 'bg-emerald-400', 'badge' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 'avatar' => 'bg-sky-100 text-sky-600'],
    ];
    $sc = $statusConfig[$child['statusType']] ?? $statusConfig['success'];
@endphp

<button type="button"
    data-validation-id="{{ $child['id'] }}"
    data-name="{{ strtolower($child['name']) }}"
    data-posyandu="{{ strtolower($child['posyandu']) }}"
    data-kader="{{ strtolower($child['kader']) }}"
    class="validation-card-btn w-full text-left px-4 py-3.5 border-b border-slate-100/80 transition-all duration-200 cursor-pointer focus:outline-none flex items-start gap-3.5 relative group
    {{ $isActive ? 'bg-[#E8F8FA] border-l-[3px] border-l-[#00A9C0]' : 'bg-white hover:bg-slate-50/80 border-l-[3px] border-l-transparent' }}">

    {{-- Avatar --}}
    <div class="shrink-0 mt-0.5 relative">
        @if(isset($child['photo']) && $child['photo'])
            <img src="{{ $child['photo'] }}" alt="{{ $child['name'] }}"
                class="w-10 h-10 rounded-xl object-cover ring-2 ring-white shadow-sm">
        @else
            <div class="w-10 h-10 rounded-xl {{ $sc['avatar'] }} flex items-center justify-center font-bold text-[11px] shadow-sm ring-2 ring-white">
                {{ strtoupper($initials) }}
            </div>
        @endif
        {{-- Status dot --}}
        <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full {{ $sc['dot'] }} border-2 border-white shadow-sm"></span>
    </div>

    {{-- Content --}}
    <div class="flex-1 min-w-0">
        <div class="flex items-start justify-between gap-2 mb-1">
            <h4 class="font-semibold tracking-tight truncate text-[13px] text-slate-800 leading-tight validation-card-name">
                {{ $child['name'] }}
            </h4>
            <span class="shrink-0 text-[9px] font-bold px-1.5 py-0.5 rounded-md border {{ $sc['badge'] }} uppercase tracking-wider whitespace-nowrap">
                {{ $child['statusLabel'] }}
            </span>
        </div>
        <div class="flex items-center gap-1.5 text-[10.5px] text-slate-500 font-medium">
            <span class="truncate">{{ $child['age'] }}</span>
            <span class="text-slate-300">·</span>
            <span class="truncate">{{ $child['posyandu'] }}</span>
        </div>
        <div class="flex items-center gap-1 mt-1.5 text-[10px] text-slate-400">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3 h-3 shrink-0 text-slate-300">
                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
            </svg>
            <span class="truncate">{{ $child['kader'] }}</span>
            @if(!empty($child['date']))
                <span class="text-slate-300 ml-auto shrink-0">{{ $child['date'] }}</span>
            @endif
        </div>
    </div>

    {{-- Arrow --}}
    <div class="shrink-0 self-center opacity-0 group-hover:opacity-100 transition-opacity duration-150 {{ $isActive ? '!opacity-100' : '' }}">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5 text-[#00A9C0]">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5" />
        </svg>
    </div>
</button>
