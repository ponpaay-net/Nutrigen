@props(['reports'])

<div class="w-full h-full flex flex-col">
    <!-- Header with Export Button -->
    <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
        <h3 class="font-extrabold text-slate-900 text-[16px]">Rekapitulasi Bulanan per Posyandu</h3>
        
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" @click.away="open = false" 
                class="flex items-center gap-2 px-4 py-2 bg-white border border-slate-200 text-slate-700 font-bold text-[13px] rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all shadow-sm focus:ring-2 focus:ring-[#06667A]/20 outline-none"
                :class="{ 'bg-slate-50 border-slate-300 ring-2 ring-[#06667A]/20': open }">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4 text-[#06667A]">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                <span>Export Laporan</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-slate-400 transition-transform duration-200" :class="{ 'rotate-180': open }">
                    <path fill-rule="evenodd" d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z" clip-rule="evenodd" />
                </svg>
            </button>
            
            <div x-show="open" 
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-1 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                x-transition:leave-end="opacity-0 translate-y-1 scale-95"
                class="absolute right-0 mt-3 w-56 bg-white border border-slate-100 rounded-[16px] shadow-[0_10px_40px_rgba(0,0,0,0.08)] py-2 z-50 overflow-hidden origin-top-right" 
                style="display: none;">
                
                <div class="px-4 py-2 border-b border-slate-50 mb-1">
                    <span class="text-[11px] font-extrabold text-slate-400 uppercase tracking-wider">Pilih Format</span>
                </div>

                <button type="button" onclick="exportTableToCSV('rekapitulasi_posyandu.csv')" class="w-full text-left px-4 py-2.5 text-[13px] font-bold text-slate-700 hover:text-[#06667A] hover:bg-slate-50 transition-colors flex items-center gap-3 group">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m6.75 12l-3-3m0 0l-3 3m3-3v6m-1.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                        </svg>
                    </div>
                    Export Excel (CSV)
                </button>
                
                <button type="button" onclick="window.print()" class="w-full text-left px-4 py-2.5 text-[13px] font-bold text-slate-700 hover:text-rose-600 hover:bg-slate-50 transition-colors flex items-center gap-3 group">
                    <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center group-hover:scale-110 transition-transform">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.728 12.364l4.95-4.95a1.5 1.5 0 012.122 0l4.95 4.95m-4.95-4.95v10.5m-11.25-3A1.5 1.5 0 004.5 21h15a1.5 1.5 0 001.5-1.5v-10.5" />
                        </svg>
                    </div>
                    Cetak PDF
                </button>
            </div>
        </div>
    </div>
        
    <div class="overflow-x-auto hide-scrollbar flex-1 bg-white">
        <table id="rekapTable" class="w-full text-left border-collapse min-w-[600px]">
            <thead>
                <tr class="border-b border-slate-100 text-[10px] text-slate-500 uppercase tracking-widest font-bold">
                    <th class="px-6 py-5">Nama Posyandu</th>
                    <th class="px-4 py-5 text-center">Sasaran</th>
                    <th class="px-4 py-5 text-center">Diukur</th>
                    <th class="px-4 py-5 text-center">Normal</th>
                    <th class="px-4 py-5 text-center">Berisiko</th>
                    <th class="px-6 py-5 text-right">% Cakupan</th>
                </tr>
            </thead>
            <tbody class="text-sm">
                @forelse($reports as $row)
                    <tr class="border-b border-slate-50 last:border-0 hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center shrink-0 text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                                    </svg>
                                </div>
                                <span class="font-bold text-slate-900 text-[13px] block">{{ $row['nama_posyandu'] }}</span>
                            </div>
                        </td>
                        <td class="px-4 py-4 text-center text-slate-500 text-[13px]">{{ number_format($row['sasaran']) }}</td>
                        <td class="px-4 py-4 text-center font-bold text-[#06667A] text-[13px]">{{ number_format($row['diukur']) }}</td>
                        <td class="px-4 py-4 text-center">
                            <span class="inline-block px-2 py-0.5 bg-emerald-50 text-emerald-600 font-bold text-[12px] rounded-full border border-emerald-100/50 min-w-[32px]">
                                {{ number_format($row['normal']) }}
                            </span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            <div class="inline-flex items-center gap-1 px-2 py-0.5 bg-rose-50 text-rose-600 font-bold text-[12px] rounded-full border border-rose-100/50 min-w-[32px]">
                                <span>{{ number_format($row['berisiko']) }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <span class="w-9 text-right font-bold text-[#06667A] text-[13px]">{{ $row['persentase_hadir'] }}</span>
                                <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    @php
                                        $pct = floatval(str_replace('%', '', $row['persentase_hadir']));
                                        $color = $pct >= 80 ? 'bg-emerald-500' : ($pct >= 50 ? 'bg-[#06667A]' : 'bg-rose-500');
                                    @endphp
                                    <div class="h-full {{ $color }} rounded-full" style="width: {{ $pct }}%;"></div>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-10 text-center text-slate-400 text-[13px]">
                            Belum ada data laporan untuk bulan ini.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
    function exportTableToCSV(filename) {
        var csv = [];
        var rows = document.querySelectorAll("#rekapTable tr");
        
        for (var i = 0; i < rows.length; i++) {
            var row = [], cols = rows[i].querySelectorAll("td, th");
            
            for (var j = 0; j < cols.length; j++) 
                row.push('"' + cols[j].innerText.trim() + '"');
            
            csv.push(row.join(","));        
        }

        downloadCSV(csv.join("\n"), filename);
    }

    function downloadCSV(csv, filename) {
        var csvFile;
        var downloadLink;

        csvFile = new Blob([csv], {type: "text/csv"});
        downloadLink = document.createElement("a");
        downloadLink.download = filename;
        downloadLink.href = window.URL.createObjectURL(csvFile);
        downloadLink.style.display = "none";
        document.body.appendChild(downloadLink);
        downloadLink.click();
    }
</script>
