@props(['class' => ''])

<div {{ $attributes->merge(['class' => "absolute inset-0 z-10 bg-surface p-5 space-y-6 $class"]) }}>
    <div class="flex items-center justify-between animate-pulse">
        <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-slate-200 rounded-full"></div>
            <div class="space-y-2">
                <div class="h-4 bg-slate-200 rounded-full w-24"></div>
                <div class="h-5 bg-slate-200 rounded-full w-32"></div>
            </div>
        </div>
    </div>
    <div class="h-52 bg-slate-200 rounded-[28px] animate-pulse"></div>
    <div class="h-28 bg-slate-200 rounded-3xl animate-pulse"></div>
    <div class="h-32 bg-slate-200 rounded-3xl animate-pulse"></div>
</div>
