<x-layout.mobile-shell>
    <div x-data="{ state: '{{ $pageState ?? 'normal' }}' }" class="flex-1 overflow-y-auto hide-scrollbar flex flex-col relative pb-[100px] pb-safe w-full">
        
        <!-- TOP BAR (komponen bersama) -->
        <x-navigation.portal-header
            variant="page"
            title="Pertumbuhan Anak"
            :hasBack="true"
            :backUrl="\Illuminate\Support\Facades\URL::temporarySignedRoute('portal-ibu.home', now()->addDays(config('portal.link_ttl_days')), ['balita' => request('balita'), 'orang_tua' => request('orang_tua')])"
            :initials="$initials ?? 'A'"
            :avatar="$avatar ?? null"
        />

        <div class="px-5 pt-6 pb-6 space-y-6 flex-1 flex flex-col">
            <!-- LOADING OVERLAY -->
            <template x-if="state === 'loading'">
                <x-feedback.loading-state class="top-20" />
            </template>

            <!-- ERROR STATE -->
            <template x-if="state === 'error'">
                <div class="flex-1 flex items-center justify-center -mt-20">
                    <x-feedback.error-state />
                </div>
            </template>

            <!-- EMPTY STATE -->
            <template x-if="state === 'empty'">
                <div class="flex-1 flex flex-col items-center justify-center -mt-10">
                    <x-feedback.empty-state 
                        title="Belum Ada Grafik" 
                        message="Grafik pertumbuhan akan muncul setelah ada minimal dua pencatatan." 
                        actionText="Lihat Jadwal Posyandu">
                        <x-slot name="icon">
                            <svg class="w-10 h-10 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path></svg>
                        </x-slot>
                    </x-feedback.empty-state>
                </div>
            </template>

            <!-- NORMAL STATE -->
            <div x-show="state === 'normal'" style="display: none;" class="space-y-6" x-transition>
                
                <!-- 1. GROWTH STORY -->
                <x-ui.section-title title="Rapor {{ $childName ?? 'Si Kecil' }}" subtitle="Kesimpulan pengukuran bulan ini." />
                
                <div class="rounded-[32px] p-7 text-center shadow-[0_16px_40px_-12px_rgba(5,150,105,0.4)] transition-all duration-500 relative overflow-hidden flex flex-col items-center"
                     :class="{
                        'bg-gradient-to-br from-emerald-500 to-emerald-600 shadow-[0_16px_40px_-12px_rgba(5,150,105,0.4)]': '{{ $story['state'] ?? 'normal' }}' === 'normal',
                        'bg-gradient-to-br from-amber-400 to-amber-500 shadow-[0_16px_40px_-12px_rgba(245,158,11,0.4)]': '{{ $story['state'] ?? 'normal' }}' === 'kuning',
                        'bg-gradient-to-br from-rose-500 to-rose-600 shadow-[0_16px_40px_-12px_rgba(225,29,72,0.4)]': '{{ $story['state'] ?? 'normal' }}' === 'merah',
                        'bg-gradient-to-br from-blue-500 to-blue-600 shadow-[0_16px_40px_-12px_rgba(37,99,235,0.4)]': !['normal', 'kuning', 'merah'].includes('{{ $story['state'] ?? 'normal' }}')
                     }">

                    <div class="mt-2 mb-4">
                        <span class="px-4 py-1.5 rounded-full text-[11px] font-black uppercase tracking-widest bg-white/20 text-white backdrop-blur-md">
                            {{ $story['status'] ?? 'Belum Ada Data' }}
                        </span>
                    </div>

                    <h2 class="text-[20px] font-black text-white leading-tight tracking-tight mb-2.5 z-10 drop-shadow-sm px-4">
                        {{ $story['title'] ?? 'Tumbuh Kejar Ideal' }}
                    </h2>
                    
                    <p class="text-[14px] font-medium text-white/90 max-w-[290px] mx-auto mb-6 leading-relaxed z-10 break-words drop-shadow-sm">
                        {{ $story['message'] ?? 'Grafik pertumbuhan si Kecil terus menunjukkan tren positif bulan ini. Lanjutkan pola makan seimbangnya ya, Bu.' }}
                    </p>
                </div>

                <!-- 2. COMPARISON CARD -->
                @if(isset($comparison))
                    <x-domain.comparison-card 
                        message="{{ $comparison['message'] ?? 'Anak Ibu lebih tinggi dari 80% anak seusianya!' }}"
                        icon="{{ $comparison['icon'] ?? '🏆' }}" 
                    />
                @endif

                <!-- 3. MEDICAL CHART PLACEHOLDER -->
                <div class="mt-8">
                    <x-ui.section-title title="Kurva Pertumbuhan WHO" subtitle="Jejak pertumbuhan si Kecil dari waktu ke waktu." />
                    <x-ui.card padding="p-2" class="bg-white border border-slate-100 shadow-sm mt-3 relative overflow-hidden">
                        <div id="growthChart" class="w-full h-72 z-10 relative"></div>
                    </x-ui.card>
                </div>

                <!-- 4. TIMELINE MILESTONES -->
                <div class="mt-8">
                    <x-ui.section-title title="Catatan Posyandu" subtitle="Riwayat pengukuran." />
                    
                    <x-ui.card padding="p-5" class="mt-3">
                        @forelse($timeline ?? [] as $index => $item)
                            <x-domain.growth-timeline-item 
                                date="{{ $item['date'] }}"
                                age="{{ $item['age'] }}"
                                weight="{{ $item['weight'] }}"
                                height="{{ $item['height'] }}"
                                status="{{ $item['status'] ?? 'normal' }}"
                                :isLast="$loop->last"
                            />
                        @empty
                            <!-- Dummy data fallback for local dev -->
                            <x-domain.growth-timeline-item 
                                date="Senin, 12 Ags 2026" age="2 Tahun 4 Bulan" weight="10.2" height="85" status="normal"
                            />
                            <x-domain.growth-timeline-item 
                                date="Senin, 12 Jul 2026" age="2 Tahun 3 Bulan" weight="9.9" height="84" status="normal" :isLast="true"
                            />
                        @endforelse
                    </x-ui.card>
                </div>
                
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var rawData = {!! $chartData ?? '[]' !!};
            
            var options = {
                series: [{
                    name: 'Berat Badan (kg)',
                    data: rawData
                }],
                chart: {
                    type: 'area',
                    height: 280,
                    toolbar: { show: false },
                    zoom: { enabled: false },
                    fontFamily: 'Nunito, sans-serif',
                    parentHeightOffset: 0
                },
                colors: ['#10B981'],
                dataLabels: {
                    enabled: true,
                    formatter: function(val) {
                        return val + ' kg';
                    },
                    offsetY: -5,
                    style: {
                        fontSize: '10px',
                        colors: ['#047857']
                    },
                    background: {
                        enabled: true,
                        foreColor: '#fff',
                        borderRadius: 4,
                        padding: 4,
                        borderWidth: 0,
                    }
                },
                stroke: {
                    curve: 'smooth',
                    width: 3
                },
                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.4,
                        opacityTo: 0.05,
                        stops: [0, 100]
                    }
                },
                xaxis: {
                    type: 'numeric',
                    title: {
                        text: 'Umur (Bulan)',
                        style: { color: '#9CA3AF', fontSize: '11px', fontWeight: 700 }
                    },
                    labels: {
                        formatter: function (val) {
                            return val + ' bln';
                        }
                    },
                    tickAmount: rawData.length > 5 ? 5 : rawData.length
                },
                yaxis: {
                    title: {
                        text: 'Berat (kg)',
                        style: { color: '#9CA3AF', fontSize: '11px', fontWeight: 700 }
                    },
                    min: function(min) { return min > 2 ? min - 2 : 0; },
                    max: function(max) { return max + 2; }
                },
                grid: {
                    borderColor: '#F3F4F6',
                    strokeDashArray: 4,
                    padding: { top: 10, right: 10, bottom: 0, left: 15 }
                },
                theme: {
                    mode: 'light'
                },
                markers: {
                    size: 5,
                    colors: ['#fff'],
                    strokeColors: '#10B981',
                    strokeWidth: 3,
                    hover: { size: 7 }
                }
            };

            if (document.getElementById('growthChart')) {
                if (rawData.length > 0) {
                    var chart = new ApexCharts(document.querySelector("#growthChart"), options);
                    chart.render();
                } else {
                    document.getElementById('growthChart').innerHTML = '<div class="h-full flex items-center justify-center text-sm text-slate-400 font-bold">Belum ada cukup data pengukuran.</div>';
                }
            }
        });
    </script>
    @endpush
</x-layout.mobile-shell>
