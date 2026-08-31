@props(['stats'])

@php
    $totalBalita = max($stats['total_balita'], 1);
    $pctNormal = round(($stats['normal'] / $totalBalita) * 100);
    $pctBerisiko = round(($stats['berisiko'] / $totalBalita) * 100, 1);
    $pctTerukur = round(($stats['sudah_validasi'] / $totalBalita) * 100);
@endphp

<div class="grid grid-cols-2 lg:grid-cols-5 gap-4">
    <!-- Total Balita -->
    <div class="bg-white rounded-[24px] border border-slate-100 p-6 flex flex-col relative transition-all shadow-sm hover:shadow-md">
        <div class="flex items-start justify-between mb-2">
            <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-tight">Total Balita</h4>
            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-slate-600 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-auto">
            <div class="text-[32px] font-bold text-slate-900 leading-none">{{ number_format($stats['total_balita']) }}</div>
        </div>
    </div>

    <!-- Normal -->
    <div class="bg-white rounded-[24px] border border-slate-100 p-6 flex flex-col relative transition-all shadow-sm hover:shadow-md">
        <div class="flex items-start justify-between mb-2">
            <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-tight">Normal</h4>
            <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500 shrink-0 border border-emerald-100/50">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                </svg>
            </div>
        </div>
        <div class="mt-auto flex items-end gap-2">
            <div class="text-[32px] font-bold text-slate-900 leading-none">{{ number_format($stats['normal']) }}</div>
            <div class="bg-emerald-50 text-emerald-600 font-bold text-[11px] px-2 py-0.5 rounded border border-emerald-100/50 mb-1">
                {{ $pctNormal }}%
            </div>
        </div>
    </div>

    <!-- Berisiko -->
    <div class="bg-white rounded-[24px] border border-rose-50 shadow-[0_4px_12px_rgba(244,63,94,0.05)] p-6 flex flex-col relative transition-all hover:shadow-md">
        <div class="flex items-start justify-between mb-2">
            <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-tight">Berisiko</h4>
            <div class="w-8 h-8 rounded-full bg-rose-500 flex items-center justify-center text-white shrink-0 shadow-sm shadow-rose-200">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2.25m0 4.5h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-auto flex items-end gap-2">
            <div class="text-[32px] font-bold text-slate-900 leading-none">{{ number_format($stats['berisiko']) }}</div>
            <div class="bg-rose-50 text-rose-600 font-bold text-[11px] px-2 py-0.5 rounded border border-rose-100/50 mb-1">
                {{ $pctBerisiko }}%
            </div>
        </div>
    </div>

    <!-- Pending -->
    <div class="bg-white rounded-[24px] border border-slate-100 p-6 flex flex-col relative transition-all shadow-sm hover:shadow-md">
        <div class="flex items-start justify-between mb-2">
            <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-tight">Pending</h4>
            <div class="w-8 h-8 rounded-full bg-amber-50 flex items-center justify-center text-amber-500 shrink-0 border border-amber-100/50">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-auto">
            <div class="text-[32px] font-bold text-slate-900 leading-none">{{ number_format($stats['pending_validasi']) }}</div>
        </div>
    </div>

    <!-- Terukur -->
    <div class="bg-white rounded-[24px] border border-slate-100 p-6 flex flex-col relative transition-all shadow-sm hover:shadow-md">
        <div class="flex items-start justify-between mb-2">
            <h4 class="text-[10px] font-bold text-slate-500 uppercase tracking-widest leading-tight">Terukur</h4>
            <div class="w-8 h-8 rounded-full bg-[#06667A] flex items-center justify-center text-white shrink-0 shadow-sm shadow-[#06667A]/20">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                </svg>
            </div>
        </div>
        <div class="mt-auto flex items-end gap-2">
            <div class="text-[32px] font-bold text-slate-900 leading-none">{{ number_format($stats['sudah_validasi']) }}</div>
            <div class="bg-[#06667A]/10 text-[#06667A] font-bold text-[11px] px-2 py-0.5 rounded border border-[#06667A]/20 mb-1">
                {{ $pctTerukur }}%
            </div>
        </div>
    </div>
</div>
