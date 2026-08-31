@props(['distribution', 'topBerisiko' => []])

@php
    $pctNormal = $distribution['pct_normal'] ?? 0;
    $pctStunting = $distribution['pct_stunting'] ?? 0;
    $totalDiukur = ($distribution['normal'] ?? 0) + ($distribution['stunting'] ?? 0);
@endphp

<div class="w-full h-full flex flex-col relative">
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <h3 class="font-extrabold text-slate-900 text-[18px]">Status Gizi</h3>
        <button class="text-slate-400 hover:text-slate-600 transition-colors">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-5 h-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM18.75 12a.75.75 0 11-1.5 0 .75.75 0 011.5 0z" />
            </svg>
        </button>
    </div>
    
    <div class="flex-1 flex flex-col justify-center items-center">
        <!-- Donut Container using Chart.js -->
        <div class="relative w-56 h-56 shrink-0 mb-10 flex items-center justify-center">
            <canvas id="statusGiziChart" class="w-full h-full drop-shadow-md z-10"></canvas>
            
            <!-- Inner Text -->
            <div class="absolute inset-0 flex flex-col items-center justify-center z-0">
                <span class="text-[36px] font-bold text-slate-900 leading-none tracking-tight">{{ number_format($totalDiukur) }}</span>
                <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mt-1">Total<br>Diukur</span>
            </div>
        </div>

        <!-- Legend (Combined Pill) -->
        <div class="bg-slate-50/80 border border-slate-100 rounded-2xl py-3 px-6 flex items-center justify-center gap-6 w-full max-w-[280px]">
            <!-- Normal -->
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded bg-[#06667A]"></div>
                <div class="text-[13px] font-medium text-slate-700">
                    Normal <span class="font-bold">({{ $pctNormal }}%)</span>
                </div>
            </div>
            
            <!-- Berisiko -->
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded bg-[#E11D48]"></div>
                <div class="text-[13px] font-medium text-slate-700">
                    Berisiko <span class="font-bold">({{ $pctStunting }}%)</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Load Chart.js locally or via CDN, we'll assume it's available or load it -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('statusGiziChart').getContext('2d');
        const normalVal = {{ $distribution['normal'] ?? 0 }};
        const berisikoVal = {{ $distribution['stunting'] ?? 0 }};
        
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Normal', 'Berisiko'],
                datasets: [{
                    data: [normalVal, berisikoVal],
                    backgroundColor: [
                        '#06667A', // Teal
                        '#E11D48'  // Rose/Red
                    ],
                    borderWidth: 0,
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '75%',
                plugins: {
                    legend: {
                        display: false // We use custom HTML legend
                    },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        padding: 12,
                        titleFont: { size: 13, family: "'Inter', sans-serif" },
                        bodyFont: { size: 14, weight: 'bold', family: "'Inter', sans-serif" },
                        displayColors: true,
                        cornerRadius: 8,
                    }
                },
                animation: {
                    animateScale: true,
                    animateRotate: true
                }
            }
        });
    });
</script>
