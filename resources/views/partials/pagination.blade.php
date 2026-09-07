@if ($paginator->hasPages())
<nav class="flex items-center justify-center gap-1.5 sm:gap-2 max-w-full" role="navigation" aria-label="Navigasi halaman">
    {{-- Prev --}}
    @if ($paginator->onFirstPage())
        <span class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl text-slate-300 border border-slate-200 bg-white opacity-60 cursor-not-allowed shrink-0" aria-hidden="true"><x-icon name="caret-left" weight="bold" class="text-sm" /></span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl text-slate-600 border border-slate-200 bg-white hover:border-teal-300 hover:text-teal-600 transition-colors shrink-0" aria-label="Halaman sebelumnya"><x-icon name="caret-left" weight="bold" class="text-sm" /></a>
    @endif

    {{-- Mobile Compact Info (sm:hidden, mencegah overflow pagination di HP) --}}
    <div class="sm:hidden px-3 py-1.5 rounded-xl bg-white border border-slate-200 text-xs font-bold text-slate-700 shadow-2xs">
        Hal <span class="text-teal-600 font-extrabold">{{ $paginator->currentPage() }}</span> / {{ $paginator->lastPage() }}
    </div>

    {{-- Desktop Page numbers (hidden sm:flex) --}}
    <div class="hidden sm:flex items-center gap-1.5">
        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="px-1.5 text-slate-400 text-sm font-medium">{{ $element }}</span>
            @endif
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-teal-600 text-white font-bold text-sm shadow-sm" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-600 border border-slate-200 bg-white hover:border-teal-300 hover:text-teal-600 font-medium text-sm transition-colors">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach
    </div>

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl text-slate-600 border border-slate-200 bg-white hover:border-teal-300 hover:text-teal-600 transition-colors shrink-0" aria-label="Halaman berikutnya"><x-icon name="caret-right" weight="bold" class="text-sm" /></a>
    @else
        <span class="w-9 h-9 sm:w-10 sm:h-10 flex items-center justify-center rounded-xl text-slate-300 border border-slate-200 bg-white opacity-60 cursor-not-allowed shrink-0" aria-hidden="true"><x-icon name="caret-right" weight="bold" class="text-sm" /></span>
    @endif
</nav>
@endif
