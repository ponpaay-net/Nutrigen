@props(['icon' => '<svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>', 'title' => 'Belum ada data', 'message' => '', 'actionText' => null, 'class' => ''])

<div {{ $attributes->merge(['class' => "flex flex-col items-center justify-center text-center p-6 mt-6 $class"]) }}>
    <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mb-5 border border-slate-100 shadow-sm">
        {!! $icon !!}
    </div>
    <h2 class="text-[17px] font-black text-slate-800 mb-2 leading-tight tracking-tight">{{ $title }}</h2>
    <p class="text-[13px] font-medium text-slate-500 mb-8 max-w-[260px] leading-relaxed">{{ $message }}</p>
    
    @if($actionText)
        <button class="w-full sm:max-w-[200px] min-h-[44px] bg-brand hover:bg-mint-600 text-white font-bold py-3 px-6 rounded-[20px] shadow-[0_8px_24px_-4px_rgba(16,185,129,0.3)] transition-all duration-300 text-[14px] flex justify-center items-center active:scale-95 z-10 focus:outline-none focus-visible:ring-2 focus-visible:ring-brand/50 cursor-pointer">
            {{ $actionText }}
        </button>
    @endif
</div>
