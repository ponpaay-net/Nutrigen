@props(['trends'])

@php
    $labels = [];
    $dataNormal = [];
    $dataBerisiko = [];
    $validTrendsCount = 0;
    
    foreach($trends as $t) {
        $labels[] = $t['bulan'];
        $dataNormal[] = $t['pct_normal'];
        $dataBerisiko[] = $t['pct_berisiko'];
        if ($t['total'] > 0) $validTrendsCount++;
    }
    
    $rentang = request('rentang', 6);
    $is6Bulan = $rentang == 6;
    $btnActive = "px-4 py-2 bg-white text-[#06667A] rounded-lg shadow-sm border border-slate-200/50";
    $btnInactive = "px-4 py-2 text-slate-500 hover:text-slate-800 transition-colors";
@endphp

<div class="px-5 lg:px-6 mt-6 pb-10">
    <div class="bg-white rounded-[24px] shadow-sm p-6 lg:p-8">
        
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <h3 class="font-extrabold text-[18px] tracking-tight text-slate-900">Tren Status Gizi ({{ $rentang }} Bulan Terakhir)</h3>
                <p class="text-[13px] text-slate-500 mt-1">Pergerakan persentase sasaran gizi normal vs berisiko dari waktu ke waktu</p>
            </div>
            
            <div class="flex items-center gap-1 text-[13px] font-bold bg-slate-100/80 p-1 rounded-xl">
                <a href="{{ request()->fullUrlWithQuery(['rentang' => 6]) }}" class="{{ $is6Bulan ? $btnActive : $btnInactive }}">6 Bulan</a>
                <a href="{{ request()->fullUrlWithQuery(['rentang' => 12]) }}" class="{{ !$is6Bulan ? $btnActive : $btnInactive }}">12 Bulan</a>
            </div>
        </div>

        <!-- Chart.js Container -->
        <div class="w-full h-[320px] relative">
            <canvas id="trendGiziChart" class="w-full h-full"></canvas>
        </div>

        <!-- Conclusion Insight -->
        @php
            $validTrends = array_filter($trends, fn($t) => $t['total'] > 0);
            
            if (count($validTrends) < 2) {
                $insight = "Belum cukup data historis untuk analisis tren yang valid.";
                $iconColor = "text-slate-400";
                $bgColor = "bg-slate-50 border-slate-100";
                $iconBg = "bg-white border-slate-200";
            } else {
                $first = reset($validTrends)['pct_normal'];
                $last = end($validTrends)['pct_normal'];
                $diff = $last - $first;
                
                if ($diff == 0) {
                    $insight = "Tren persentase balita berstatus normal terpantau stabil tanpa perubahan signifikan.";
                    $iconColor = "text-[#06667A]";
                    $bgColor = "bg-[#06667A]/5 border-[#06667A]/10";
                    $iconBg = "bg-white border-[#06667A]/20";
                } else {
                    $isPositive = $diff > 0;
                    $sign = $isPositive ? '+' : '';
                    $colorClass = $isPositive ? 'text-emerald-600' : 'text-rose-600';
                    $trendWord = $isPositive ? 'peningkatan' : 'penurunan';
                    $insight = "Terjadi <span class=\"font-bold text-slate-800\">{$trendWord} persentase balita normal</span> sebesar <span class=\"font-bold {$colorClass}\">{$sign}{$diff}%</span> dibandingkan bulan pertama tercatat.";
                    
                    if ($isPositive) {
                        $iconColor = "text-emerald-500";
                        $bgColor = "bg-emerald-50/50 border-emerald-100";
                        $iconBg = "bg-white border-emerald-200";
                    } else {
                        $iconColor = "text-rose-500";
                        $bgColor = "bg-rose-50/50 border-rose-100";
                        $iconBg = "bg-white border-rose-200";
                    }
                }
            }
        @endphp
        
        <div class="mt-10 {{ $bgColor }} border rounded-2xl p-5 flex gap-4 items-start transition-colors">
            <div class="w-12 h-12 rounded-xl {{ $iconBg }} {{ $iconColor }} border flex items-center justify-center shrink-0 shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-6 h-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z" />
                </svg>
            </div>
            <div class="pt-1">
                <h4 class="text-[14px] font-bold tracking-tight text-slate-900">Insight & Analisis</h4>
                <p class="text-[13px] text-slate-600 mt-1 leading-relaxed">
                    {!! $insight !!}
                </p>
            </div>
        </div>

    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('trendGiziChart').getContext('2d');
        
        // Gradient for Normal Line (Teal)
        const gradientNormal = ctx.createLinearGradient(0, 0, 0, 400);
        gradientNormal.addColorStop(0, 'rgba(6, 102, 122, 0.2)');
        gradientNormal.addColorStop(1, 'rgba(6, 102, 122, 0)');
        
        // Gradient for Berisiko Line (Rose)
        const gradientBerisiko = ctx.createLinearGradient(0, 0, 0, 400);
        gradientBerisiko.addColorStop(0, 'rgba(225, 29, 72, 0.2)');
        gradientBerisiko.addColorStop(1, 'rgba(225, 29, 72, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($labels) !!},
                datasets: [
                    {
                        label: 'Normal (%)',
                        data: {!! json_encode($dataNormal) !!},
                        borderColor: '#06667A',
                        backgroundColor: gradientNormal,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#06667A',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Berisiko (%)',
                        data: {!! json_encode($dataBerisiko) !!},
                        borderColor: '#E11D48',
                        backgroundColor: gradientBerisiko,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#E11D48',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        position: 'top',
                        align: 'end',
                        labels: {
                            usePointStyle: true,
                            boxWidth: 8,
                            boxHeight: 8,
                            color: '#64748b',
                            font: {
                                size: 12,
                                family: "'Inter', sans-serif",
                                weight: 'bold'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1E293B',
                        padding: 12,
                        titleFont: { size: 13, family: "'Inter', sans-serif" },
                        bodyFont: { size: 14, weight: 'bold', family: "'Inter', sans-serif" },
                        displayColors: true,
                        cornerRadius: 8,
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + '%';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false,
                        },
                        ticks: {
                            color: '#94a3b8',
                            font: {
                                size: 11,
                                weight: 'bold',
                                family: "'Inter', sans-serif"
                            }
                        }
                    },
                    y: {
                        min: 0,
                        max: 100,
                        grid: {
                            color: '#f1f5f9',
                            drawBorder: false,
                            tickLength: 0
                        },
                        ticks: {
                            stepSize: 25,
                            color: '#94a3b8',
                            padding: 10,
                            font: {
                                size: 11,
                                family: "'Inter', sans-serif"
                            },
                            callback: function(value) {
                                return value + '%';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
