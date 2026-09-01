@if ($paginator->hasPages())
<nav class="flex items-center justify-center gap-1.5" role="navigation" aria-label="Navigasi halaman">
    {{-- Prev --}}
    @if ($paginator->onFirstPage())
        <span class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-300 border border-slate-200 bg-white opacity-60 cursor-not-allowed" aria-hidden="true"><x-icon name="caret-left" weight="bold" class="text-[14px]" /></span>
    @else
        <a href="{{ $paginator->previousPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-600 border border-slate-200 bg-white hover:border-teal-300 hover:text-teal-600 transition-colors" aria-label="Halaman sebelumnya"><x-icon name="caret-left" weight="bold" class="text-[14px]" /></a>
    @endif

    {{-- Page numbers --}}
    @foreach ($elements as $element)
        @if (is_string($element))
            <span class="px-1 text-slate-400 text-sm font-medium">{{ $element }}</span>
        @endif
        @if (is_array($element))
            @foreach ($element as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="w-10 h-10 flex items-center justify-center rounded-xl bg-teal-600 text-white font-bold text-sm shadow-sm aria-current="page">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-600 border border-slate-200 bg-white hover:border-teal-300 hover:text-teal-600 font-medium text-sm transition-colors">{{ $page }}</a>
                @endif
            @endforeach
        @endif
    @endforeach

    {{-- Next --}}
    @if ($paginator->hasMorePages())
        <a href="{{ $paginator->nextPageUrl() }}" class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-600 border border-slate-200 bg-white hover:border-teal-300 hover:text-teal-600 transition-colors" aria-label="Halaman berikutnya"><x-icon name="caret-right" weight="bold" class="text-[14px]" /></a>
    @else
        <span class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-300 border border-slate-200 bg-white opacity-60 cursor-not-allowed" aria-hidden="true"><x-icon name="caret-right" weight="bold" class="text-[14px]" /></span>
    @endif
</nav>
@endif
