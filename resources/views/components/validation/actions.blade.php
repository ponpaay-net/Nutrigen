@props(['childId'])

{{-- Sticky action bar — slim, floats above content --}}
<div class="shrink-0 px-5 py-3 border-t border-slate-100 bg-white/90 backdrop-blur-sm">
    <div class="flex items-stretch gap-2.5">

        {{-- Tolak --}}
        <button type="button" data-id="{{ $childId }}"
            class="btn-reject flex-1 h-11 flex items-center justify-center gap-2 rounded-xl border border-rose-100 bg-rose-50/70 text-rose-500 text-[12.5px] font-semibold hover:bg-rose-100 hover:border-rose-200 transition-all focus:outline-none focus:ring-2 focus:ring-rose-200 focus:ring-offset-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-3.5 h-3.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
            Tolak
        </button>

        {{-- Setujui --}}
        <button type="button" data-id="{{ $childId }}"
            class="btn-approve flex-[3] h-11 flex items-center justify-center gap-2 rounded-xl bg-gradient-to-r from-[#0097B0] to-[#00C4E0] text-white text-[12.5px] font-bold shadow-sm shadow-cyan-200/60 hover:from-[#0086A0] hover:to-[#00B0CC] transition-all focus:outline-none focus:ring-2 focus:ring-cyan-300 focus:ring-offset-1">
            {{-- Check --}}
            <svg class="icon-approve w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
            </svg>
            {{-- Spinner --}}
            <svg class="spinner-approve animate-spin w-4 h-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-approve">Setujui Data</span>
        </button>

    </div>
</div>
