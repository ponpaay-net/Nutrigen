@props(['child'])

@php
    $chartId = 'chart-' . uniqid();
    $curveColor = $child['jenis_kelamin'] == 'L' ? '#0ea5e9' : '#ec4899'; // Blue for boys, Pink for girls
@endphp

<div id="{{ $chartId }}" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-xs font-bold tracking-tight text-slate-800 uppercase tracking-widest flex items-center gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-slate-400">
                <path fill-rule="evenodd" d="M2.25 13.5a8.25 8.25 0 018.25-8.25.75.75 0 01.75.75v6.75H18a.75.75 0 01.75.75 8.25 8.25 0 01-16.5 0z" clip-rule="evenodd" />
                <path fill-rule="evenodd" d="M12.75 3a.75.75 0 01.75-.75 8.25 8.25 0 018.25 8.25.75.75 0 01-.75.75h-7.5a.75.75 0 01-.75-.75V3z" clip-rule="evenodd" />
            </svg>
            Grafik Pertumbuhan KMS
        </h3>
        <select class="chart-indicator-select text-xs border-slate-200 rounded text-slate-600 bg-slate-50 px-2 py-1 focus:ring-sky-500 font-bold">
            <option value="tbu" selected>TB/U (Tinggi Badan menurut Umur)</option>
            <option value="bbu">BB/U (Berat Badan menurut Umur)</option>
        </select>
    </div>
    
    <div class="relative h-64 w-full bg-slate-50 border border-slate-100 rounded-xl overflow-hidden flex items-center justify-center">
        <!-- SVG Dynamic Curve (Menyesuaikan Jenis Kelamin & Data Real) -->
        <svg class="w-full h-full text-slate-200" viewBox="0 -10 600 220" preserveAspectRatio="none">
            <!-- Zones (WHO Standard Z-Score mapping: y=100 is 0 SD. 1 SD = 25px) -->
            <!-- +3 SD to +2 SD (y=25 to y=50) -->
            <rect x="0" y="25" width="600" height="25" fill="#fef08a" opacity="0.3"/>
            <!-- +2 SD to -2 SD (y=50 to y=150) (Normal) -->
            <rect x="0" y="50" width="600" height="100" fill="#f0fdf4"/>
            <!-- -2 SD to -3 SD (y=150 to y=175) (Kurang/Stunting/Wasted) -->
            <rect x="0" y="150" width="600" height="25" fill="#fef08a" opacity="0.5"/>
            <!-- Below -3 SD (y=175 to y=200) (Buruk/Severely Stunted) -->
            <rect x="0" y="175" width="600" height="25" fill="#fee2e2" opacity="0.6"/>
            
            <!-- Grid Lines -->
            <!-- +2 SD -->
            <line x1="0" y1="50" x2="600" y2="50" stroke="#cbd5e1" stroke-dasharray="4" stroke-width="1" />
            <!-- 0 SD -->
            <line x1="0" y1="100" x2="600" y2="100" stroke="#94a3b8" stroke-dasharray="2" stroke-width="1" />
            <!-- -2 SD -->
            <line x1="0" y1="150" x2="600" y2="150" stroke="#cbd5e1" stroke-dasharray="4" stroke-width="1" />
            <!-- -3 SD -->
            <line x1="0" y1="175" x2="600" y2="175" stroke="#fca5a5" stroke-dasharray="4" stroke-width="1" />

            <!-- Y Axis SVG Labels (to prevent wrapping) -->
            <text x="10" y="22" fill="#64748b" font-size="11" font-weight="bold">+3 SD</text>
            <text x="10" y="46" fill="#64748b" font-size="11" font-weight="bold">+2 SD</text>
            <text x="10" y="96" fill="#64748b" font-size="11" font-weight="bold">0 SD</text>
            <text x="10" y="146" fill="#64748b" font-size="11" font-weight="bold">-2 SD</text>
            <text x="10" y="171" fill="#ef4444" font-size="11" font-weight="bold">-3 SD</text>

            <!-- Plot Line (Dynamic) -->
            <path class="plot-line" d="" fill="none" stroke="{{ $curveColor }}" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
            
            <!-- Points (Dynamic) -->
            <g class="dots-group"></g>
        </svg>

        <!-- Empty State Overlay -->
        <div class="empty-state-overlay absolute inset-0 flex items-center justify-center bg-slate-50/80 backdrop-blur-sm hidden">
            <div class="text-center">
                <p class="text-sm font-bold text-slate-500">Belum ada data pengukuran.</p>
            </div>
        </div>
    </div>
</div>

<script>
    (function() {
        const container = document.getElementById('{{ $chartId }}');
        const select = container.querySelector('.chart-indicator-select');
        const plotPath = container.querySelector('.plot-line');
        const dotsGroup = container.querySelector('.dots-group');
        const emptyState = container.querySelector('.empty-state-overlay');
        
        // Data dari database. Reverse array agar urutan kronologis (kiri ke kanan)
        const rawData = @json($child['pengukurans'] ?? []);
        const data = [...rawData].reverse();

        function drawChart(indicator) {
            let d = "";
            dotsGroup.innerHTML = ''; // clear previous dots

            if (data.length === 0) {
                plotPath.setAttribute('d', '');
                emptyState.classList.remove('hidden');
                return;
            } else {
                emptyState.classList.add('hidden');
            }

            // Simpan koordinat untuk mempermudah perhitungan kurva
            const points = [];

            data.forEach((p, index) => {
                // Pilih z-score sesuai indikator
                const z = indicator === 'tbu' ? p.z_score_tb_u : p.z_score_bb_u;
                const zVal = parseFloat(z) || 0;
                
                // Hitung koordinat SVG
                let x = 300; 
                if (data.length > 1) {
                    const padding = 60;
                    const availableWidth = 600 - (padding * 2);
                    const spacing = availableWidth / (data.length - 1);
                    x = padding + (index * spacing);
                }
                
                let y = 100 - (zVal * 25);
                y = Math.max(10, Math.min(210, y)); // clamp
                
                points.push({x, y, zVal, umur: parseFloat(p.umur_bulan) || 0});
            });

            // Gambar garis dan titik
            if (points.length === 1) {
                // If only 1 data point, draw a dashed horizontal line from the left to show the level
                d = `M0,${points[0].y} L${points[0].x},${points[0].y}`;
                plotPath.setAttribute('stroke-dasharray', '5,5');
                plotPath.setAttribute('stroke-opacity', '0.4');
            } else {
                plotPath.removeAttribute('stroke-dasharray');
                plotPath.removeAttribute('stroke-opacity');
                
                points.forEach((pt, index) => {
                    // Construct path string dengan kurva Bezier halus (Monotone Cubic)
                    if (index === 0) {
                        d += `M${pt.x},${pt.y} `;
                    } else {
                        const prevPt = points[index - 1];
                        const cpX = (prevPt.x + pt.x) / 2;
                        // Format: C cp1X,cp1Y cp2X,cp2Y endX,endY
                        d += `C${cpX},${prevPt.y} ${cpX},${pt.y} ${pt.x},${pt.y} `;
                    }
                });
            }

            points.forEach((pt, index) => {

                // Create dot
                const dot = document.createElementNS('http://www.w3.org/2000/svg', 'circle');
                dot.setAttribute('cx', pt.x);
                dot.setAttribute('cy', pt.y);
                dot.setAttribute('r', index === data.length - 1 ? '5' : '3.5');
                dot.setAttribute('fill', '{{ $curveColor }}');
                dot.setAttribute('stroke', '#fff');
                dot.setAttribute('stroke-width', '2.5');
                
                if (index === data.length - 1) {
                    dot.classList.add('animate-pulse');
                    dot.setAttribute('r', '6');
                }
                
                // Hover tooltip
                const title = document.createElementNS('http://www.w3.org/2000/svg', 'title');
                title.textContent = `Umur: ${pt.umur} bln\nZ-Score: ${pt.zVal.toFixed(2)}`;
                dot.appendChild(title);
                
                dotsGroup.appendChild(dot);
            });

            plotPath.setAttribute('d', d);
        }

        // Event listener untuk perubahan indikator
        select.addEventListener('change', function(e) {
            drawChart(e.target.value);
        });

        // Eksekusi awal
        drawChart(select.value);
    })();
</script>
