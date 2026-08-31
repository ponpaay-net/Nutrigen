<div class="bg-white p-4 rounded-lg border border-slate-200 flex flex-col h-full">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-[10px] font-bold tracking-tight text-slate-400 uppercase tracking-widest flex items-center gap-1.5">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
            </svg>
            Grafik KMS
        </h3>
        <select class="text-[10px] font-bold border-slate-200 rounded text-slate-600 bg-slate-50 px-2 py-0.5 focus:ring-sky-500 focus:border-sky-500 transition-colors uppercase tracking-wider outline-none">
            <option>BB/U</option>
            <option selected>TB/U</option>
            <option>BB/TB</option>
        </select>
    </div>
    
    <div class="relative h-40 w-full bg-slate-50 border border-slate-100 rounded-lg overflow-hidden flex items-center justify-center">
        <!-- SVG Dummy Curve -->
        <svg class="w-full h-full text-slate-200" viewBox="0 0 600 200" preserveAspectRatio="none">
            <!-- Zones -->
            <path d="M0,70 Q150,65 300,55 T600,40 L600,200 L0,200 Z" fill="#f0fdf4" />
            <path d="M0,130 Q150,125 300,115 T600,95 L600,200 L0,200 Z" fill="#fffbeb" />
            <path d="M0,170 Q150,165 300,155 T600,140 L600,200 L0,200 Z" fill="#fff1f2" />
            <!-- Grid Lines -->
            <line x1="0" y1="70" x2="600" y2="70" stroke="currentColor" stroke-dasharray="4" stroke-width="1" />
            <line x1="0" y1="130" x2="600" y2="130" stroke="currentColor" stroke-dasharray="4" stroke-width="1" />
            <!-- Plot Line -->
            <path d="M0,110 Q150,105 300,120 T600,180" fill="none" stroke="#0ea5e9" stroke-width="3" />
            <!-- Points -->
            <circle cx="300" cy="120" r="4" fill="#0ea5e9" stroke="#fff" stroke-width="2" />
            <circle cx="600" cy="180" r="6" fill="#0ea5e9" stroke="#fff" stroke-width="2" class="animate-pulse" />
        </svg>
        <!-- Y Axis Labels -->
        <div class="absolute left-2 top-0 bottom-0 py-3 flex flex-col justify-between text-[9px] text-slate-400 font-bold tracking-wider">
            <span>+2 SD</span>
            <span>0 SD</span>
            <span>-2 SD</span>
            <span>-3 SD</span>
        </div>
    </div>
</div>
