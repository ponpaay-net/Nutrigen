<div class="px-5 lg:px-6 mt-6 pb-10">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Export PDF -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 hover:border-rose-300 transition-all duration-300 hover:-translate-y-1 hover:shadow-sm border border-slate-200/60 group">
            <div class="flex gap-4 items-start">
                <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold tracking-tight text-slate-800">Laporan Resmi (PDF)</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-xs leading-relaxed">Unduh laporan dalam format PDF yang siap dicetak. Berisi kop surat Puskesmas, tabel rekapitulasi, dan grafik analitik.</p>
                </div>
            </div>
            <button onclick="window.NutriAlert.warning('Fitur Demo', 'Fitur generate PDF akan dikerjakan oleh Backend Developer.')" class="w-full sm:w-auto px-3 py-2 text-sm min-h-[44px] bg-rose-600 hover:bg-rose-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors whitespace-nowrap flex items-center justify-center gap-2 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Unduh PDF
            </button>
        </div>

        <!-- Export Excel -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6 hover:border-emerald-300 transition-all duration-300 hover:-translate-y-1 hover:shadow-sm border border-slate-200/60 group">
            <div class="flex gap-4 items-start">
                <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-18.375 0h7.5c.621 0 1.125-.504 1.125-1.125m.75 3.75v-1.5c0-.621-.504-1.125-1.125-1.125m1.125 2.625v-1.5c0-.621-.504-1.125-1.125-1.125m-1.125 2.625h-7.5c-.621 0-1.125-.504-1.125-1.125m0-3.75v-1.5c0-.621.504-1.125 1.125-1.125m17.25 0V8.25" />
                    </svg>
                </div>
                <div>
                    <h3 class="font-bold tracking-tight text-slate-800">Dataset Raw (Excel)</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-xs leading-relaxed">Unduh data mentah lengkap per baris (balita, umur, hasil ukur) format `.xlsx` untuk diolah mandiri menggunakan Microsoft Excel.</p>
                </div>
            </div>
            <button onclick="window.NutriAlert.warning('Fitur Demo', 'Fitur generate Excel akan dikerjakan oleh Backend Developer menggunakan Laravel Excel.')" class="w-full sm:w-auto px-3 py-2 text-sm min-h-[44px] bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-bold rounded-xl shadow-sm transition-colors whitespace-nowrap flex items-center justify-center gap-2 shrink-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Unduh Excel
            </button>
        </div>

    </div>
</div>
