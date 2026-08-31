<div class="flex overflow-x-auto hide-scrollbar border-b border-slate-200 mt-2">
    <button onclick="switchReportTab('rekap')" id="btn-tab-rekap" class="report-tab-btn flex items-center gap-2 px-3 py-2 text-sm min-h-[44px].5 text-sm font-bold whitespace-nowrap transition-colors text-teal-700 border-b-2 border-teal-600 bg-teal-50/50">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm-.375 5.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
        </svg>
        Rekap Data
    </button>
    <button onclick="switchReportTab('grafik')" id="btn-tab-grafik" class="report-tab-btn flex items-center gap-2 px-3 py-2 text-sm min-h-[44px].5 text-sm font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-50 whitespace-nowrap transition-colors border-b-2 border-transparent">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 107.5 7.5h-7.5V6z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0013.5 3v7.5z" />
        </svg>
        Grafik Analisis
    </button>
    <button onclick="switchReportTab('trend')" id="btn-tab-trend" class="report-tab-btn flex items-center gap-2 px-3 py-2 text-sm min-h-[44px].5 text-sm font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-50 whitespace-nowrap transition-colors border-b-2 border-transparent">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
        </svg>
        Trend Cakupan
    </button>
    <button onclick="switchReportTab('export')" id="btn-tab-export" class="report-tab-btn flex items-center gap-2 px-3 py-2 text-sm min-h-[44px].5 text-sm font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-50 whitespace-nowrap transition-colors border-b-2 border-transparent">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
            <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
        </svg>
        Export & Cetak
    </button>
</div>
