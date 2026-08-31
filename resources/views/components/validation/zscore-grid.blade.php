@props(['zscores'])

{{--
  Compact horizontal stat row — each metric is a slim pill, not a card stack.
  Scrollable on mobile so it never wraps into a confusing grid.
--}}
<div class="flex gap-2 overflow-x-auto hide-scrollbar pb-0.5">
    @foreach($zscores as $key => $valData)
        @php
            $isNormal = str_contains(strtolower($valData['status']), 'normal');
            $isDanger = str_contains(strtolower($valData['status']), 'stunting')
                     || str_contains(strtolower($valData['status']), 'pendek')
                     || str_contains(strtolower($valData['status']), 'kurus');

            $valColor  = $isDanger  ? 'text-rose-600'
                       : (!$isNormal ? 'text-amber-600' : 'text-slate-800');
            $bg        = $isDanger  ? 'bg-rose-50 border-rose-100'
                       : (!$isNormal ? 'bg-amber-50 border-amber-100' : 'bg-white border-slate-100');
            $dot       = $isDanger  ? 'bg-rose-400'
                       : (!$isNormal ? 'bg-amber-400' : 'bg-emerald-400');
            $statusTxt = $isNormal  ? 'Normal' : $valData['status'];
        @endphp

        <div class="flex-none flex flex-col items-center gap-1 border rounded-xl px-3 py-2.5 min-w-[80px] {{ $bg }}">
            <span class="text-[9.5px] font-bold uppercase tracking-wider text-slate-500 text-center leading-tight">{{ $key }}</span>
            <span class="text-[22px] font-black leading-none {{ $valColor }} tabular-nums">{{ $valData['val'] }}</span>
            <div class="flex items-center gap-1">
                <span class="w-1.5 h-1.5 rounded-full {{ $dot }} shrink-0"></span>
                <span class="text-[9px] font-semibold text-slate-500">{{ $statusTxt }}</span>
            </div>
        </div>
    @endforeach
</div>
