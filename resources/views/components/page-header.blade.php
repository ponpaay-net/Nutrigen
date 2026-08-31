@props([
    'title',
    'subtitle' => null,
    'breadcrumbs' => null,
    'actions' => null,
])

<div {{ $attributes->merge(['class' => 'flex flex-col sm:flex-row sm:items-end justify-between gap-4 mb-6']) }}>
    <div>
        @if($breadcrumbs)
        <p class="text-xs font-semibold text-slate-400 uppercase tracking-widest mb-2">{{ $breadcrumbs }}</p>
        @endif
        <h1 class="text-2xl font-extrabold text-slate-900 tracking-tight leading-tight">{{ $title }}</h1>
        @if($subtitle)
        <p class="text-sm text-slate-500 mt-1.5 font-medium">{{ $subtitle }}</p>
        @endif
    </div>
    @if($actions)
    <div class="flex items-center gap-2 shrink-0">
        {{ $actions }}
    </div>
    @endif
</div>
