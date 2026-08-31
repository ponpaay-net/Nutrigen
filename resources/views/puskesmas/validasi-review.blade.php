@extends('layouts.puskesmas')
@section('page-title', 'Tinjau Validasi')
@section('page-mode', 'default')
@section('content')

<div class="min-h-screen bg-slate-50/50 w-full pb-20 relative">

    {{-- ══════════════════════════════════════════
         TOP HEADER (BREADCRUMB & BACK)
    ══════════════════════════════════════════ --}}
    <div class="bg-white border-b border-slate-200/80 px-4 py-3 lg:px-8 lg:py-4 sticky top-0 z-30 shadow-sm">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('puskesmas.validasi') }}" class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-colors border border-slate-200/60">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" /></svg>
                </a>
                <div>
                    <h1 class="text-[16px] font-bold text-slate-800 leading-tight">Tinjau Data Pengukuran</h1>
                    <p class="text-[12px] text-slate-500 font-medium">Validasi antrean untuk {{ $child['name'] }}</p>
                </div>
            </div>
            
            <div class="hidden sm:flex items-center gap-3">
                @if($child['status_validasi'] === 'pending')
                    <div class="flex items-center gap-1.5 text-amber-700 bg-amber-50 px-3 py-1.5 rounded-full border border-amber-200 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm.75-13a.75.75 0 00-1.5 0v5c0 .414.336.75.75.75h4a.75.75 0 000-1.5h-3.25V5z" clip-rule="evenodd"/></svg>
                        <span class="text-[11px] font-bold font-mono uppercase tracking-wider">Menunggu Validasi</span>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         MAIN CONTENT AREA
    ══════════════════════════════════════════ --}}
    <div class="max-w-7xl mx-auto px-4 lg:px-8 mt-6 pb-24">
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- COLUMN 1: IDENTITAS & HISTORI --}}
            <div class="flex flex-col gap-6 lg:col-span-1">
                
                {{-- Card: Identitas Diri --}}
                <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-sm p-6 relative overflow-hidden">
                    <div class="absolute left-0 top-6 bottom-6 w-1 bg-cyan-400 rounded-r-lg"></div>

                    <div class="flex items-center gap-3 mb-6 ml-2">
                        <div class="w-10 h-10 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-700">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 9h3.75M15 12h3.75M15 15h3.75M4.5 19.5h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5zm6-10.125a1.875 1.875 0 11-3.75 0 1.875 1.875 0 013.75 0zm1.294 6.336a6.721 6.721 0 01-3.17.789 6.721 6.721 0 01-3.168-.789 3.376 3.376 0 016.338 0z" /></svg>
                        </div>
                        <h3 class="text-[16px] font-bold text-slate-900">Data Balita & Kader</h3>
                    </div>

                    <div class="flex flex-col gap-5 ml-2">
                        <div>
                            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Nama Balita</span>
                            <span class="text-[14px] font-bold text-slate-800">{{ $child['name'] }}</span>
                            <div class="flex items-center gap-2 mt-1">
                                <span class="inline-flex items-center text-[11px] font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ $child['age'] }}</span>
                                <span class="inline-flex items-center text-[11px] font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded">{{ $child['gender'] }}</span>
                            </div>
                        </div>
                        
                        <div class="h-px w-full bg-slate-100 my-1"></div>
                        
                        <div>
                            <span class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider mb-1 block">Diukur Oleh Kader</span>
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-full bg-[#E6F8FB] text-[#00A9C0] flex items-center justify-center text-[10px] font-bold">
                                    {{ substr($child['kader'], 0, 1) }}
                                </div>
                                <span class="text-[13px] font-semibold text-slate-800">{{ $child['kader'] }}</span>
                            </div>
                            <span class="text-[12px] text-slate-500 mt-1 block">{{ $child['posyandu'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Card: Riwayat Sebelumnya --}}
                <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-sm p-6">
                    <h3 class="text-[14px] font-bold text-slate-900 mb-4">Riwayat Pengukuran Sebelumnya</h3>
                    
                    @if(empty($child['history']))
                        <div class="text-center py-6 px-4 bg-slate-50 rounded-xl border border-slate-100 border-dashed">
                            <span class="text-[13px] text-slate-500 font-medium">Belum ada riwayat sebelumnya.</span>
                        </div>
                    @else
                        <div class="flex flex-col gap-4">
                            @foreach($child['history'] as $hist)
                                <div class="flex items-start gap-4">
                                    <div class="flex flex-col items-center mt-1">
                                        <div class="w-2.5 h-2.5 rounded-full bg-slate-300"></div>
                                        @if(!$loop->last)
                                            <div class="w-px h-12 bg-slate-200 my-1"></div>
                                        @endif
                                    </div>
                                    <div class="flex-1 pb-1">
                                        <span class="text-[12px] font-bold text-slate-800 block">{{ $hist['date'] }}</span>
                                        <div class="flex flex-wrap gap-2 mt-1.5">
                                            <span class="text-[11px] font-medium text-slate-600 bg-slate-100 px-2 py-0.5 rounded">BB: {{ $hist['bb'] }}kg</span>
                                            <span class="text-[11px] font-medium text-slate-600 bg-slate-100 px-2 py-0.5 rounded">TB: {{ $hist['tb'] }}cm</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

            </div>

            {{-- COLUMN 2: DATA & TINDAKAN VALIDASI --}}
            <div class="flex flex-col gap-6 lg:col-span-2">
                
                {{-- Z-SCORE & STATUS GIZI --}}
                <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-sm p-6 lg:p-8">
                    
                    <div class="flex items-center justify-between border-b border-slate-100 pb-5 mb-6">
                        <div>
                            <h2 class="text-[18px] font-bold text-slate-900 tracking-tight">Hasil Pengukuran</h2>
                            <p class="text-[13px] text-slate-500 font-medium mt-1">{{ $child['date'] }} &bull; {{ $child['time'] }}</p>
                        </div>
                        
                        <div class="px-4 py-2 rounded-xl border
                            @if($child['statusType'] === 'success') bg-emerald-50 border-emerald-100 text-emerald-700
                            @elseif($child['statusType'] === 'warning') bg-amber-50 border-amber-100 text-amber-700
                            @else bg-rose-50 border-rose-100 text-rose-700 @endif
                        ">
                            <span class="text-[10px] font-bold uppercase tracking-wider block opacity-80 mb-0.5">Status Gizi</span>
                            <span class="text-[15px] font-black">{{ $child['statusLabel'] }}</span>
                        </div>
                    </div>

                    {{-- Metric Grid --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                        @foreach($child['zscores'] as $label => $data)
                            <div class="bg-slate-50 border border-slate-100 rounded-[16px] p-4 flex flex-col items-center justify-center text-center">
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $label }}</span>
                                <span class="text-[22px] font-black text-slate-800 my-1">{{ $data['val'] }}</span>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded bg-white border border-slate-200 {{ $data['color'] === 'rose' ? 'text-rose-600' : 'text-slate-600' }}">
                                    {{ $data['status'] }}
                                </span>
                            </div>
                        @endforeach
                    </div>

                    {{-- KMS Chart --}}
                    <div class="mt-8 border-t border-slate-100 pt-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-[15px] font-bold text-slate-800">Grafik KMS (Visualisasi Tren)</h3>
                            <select id="chartMetricSelector" onchange="updateChartMetric()" class="bg-white border border-slate-200 text-slate-600 text-[12px] rounded-lg px-3 py-1.5 focus:ring-2 focus:ring-cyan-300/40 outline-none">
                                <option value="bb">Berat Badan (kg)</option>
                                <option value="tb">Tinggi Badan (cm)</option>
                                <option value="bbu">Z-Score BB/U</option>
                                <option value="tbu">Z-Score TB/U</option>
                            </select>
                        </div>
                        <div class="w-full h-64 bg-slate-50/50 rounded-xl border border-slate-100 p-4">
                            <canvas id="growthChart"></canvas>
                        </div>
                    </div>

                </div>

                {{-- CATATAN & FORM VALIDASI --}}
                <div class="bg-white rounded-[24px] border border-slate-200/80 shadow-sm p-6 lg:p-8">
                    
                    @if (!empty($child['catatan_kader']))
                        <div class="flex items-start gap-3 p-4 mb-6 bg-[#f0fdfa] border border-[#ccfbf1] rounded-2xl relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-1 bg-teal-400"></div>
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-teal-600 shrink-0 mt-0.5">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                            </svg>
                            <div>
                                <span class="text-[12px] font-bold text-teal-800 uppercase tracking-wider block mb-1">Catatan dari Kader ({{ $child['kader'] }})</span>
                                <p class="text-[14px] text-teal-900 leading-relaxed font-medium">{{ $child['catatan_kader'] }}</p>
                            </div>
                        </div>
                    @endif

                    <h3 class="text-[15px] font-bold text-slate-800 mb-3">Catatan Ahli Gizi / Validator (Opsional)</h3>
                    <textarea id="catatanValidatorInput" rows="3" placeholder="Berikan catatan, diagnosa, atau instruksi intervensi untuk balita ini..." class="w-full bg-slate-50 border border-slate-200 text-slate-700 text-[13px] rounded-xl px-4 py-3 focus:bg-white focus:ring-2 focus:ring-[#00A9C0]/40 focus:border-[#00A9C0] transition-all resize-none outline-none leading-relaxed"></textarea>
                    <p class="text-[11px] text-slate-400 mt-2">Catatan ini akan muncul di riwayat balita dan dapat dibaca oleh ibu/orang tua di Portal Ibu.</p>

                </div>

            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════════
         BOTTOM STICKY ACTION BAR
    ══════════════════════════════════════════ --}}
    <div class="fixed bottom-0 left-0 right-0 lg:left-64 bg-white/90 backdrop-blur-md border-t border-slate-200/80 p-4 lg:px-8 z-40 shadow-[0_-4px_20px_rgba(0,0,0,0.03)]">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="hidden sm:block">
                <span class="text-[13px] font-medium text-slate-500">Tindakan Validasi untuk <strong class="text-slate-800">{{ $child['name'] }}</strong></span>
            </div>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <button type="button" onclick="openRejectModal()" class="flex-1 sm:flex-none px-6 py-3 rounded-xl bg-white border border-rose-200 text-rose-600 text-[14px] font-bold hover:bg-rose-50 hover:border-rose-300 transition-colors">
                    Tolak Data
                </button>
                <button type="button" onclick="openApproveModal()" class="flex-[2] sm:flex-none px-8 py-3 rounded-xl bg-gradient-to-r from-[#0097B0] to-[#00C4E0] text-white text-[14px] font-bold shadow-md shadow-cyan-200/60 hover:from-[#0086A0] hover:to-[#00B0CC] transition-all">
                    Setujui & Validasi
                </button>
            </div>
        </div>
    </div>
    
    {{-- MODALS --}}
    
    {{-- Approve Modal --}}
    <div id="approveModal" class="fixed inset-0 z-[60] hidden">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeApproveModal()"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-[24px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-sm">
                    <div class="p-6">
                        <div class="mx-auto w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-500 flex items-center justify-center mb-4">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <h3 class="text-center text-lg font-bold text-slate-900 mb-2">Konfirmasi Persetujuan</h3>
                        <p class="text-center text-[13px] text-slate-500 mb-6 leading-relaxed">
                            Apakah Anda yakin ingin menyetujui data pengukuran ini? Data akan diverifikasi secara permanen.
                        </p>
                        
                        <div class="flex flex-col gap-3">
                            <button type="button" onclick="submitApprove()" class="w-full py-3 rounded-xl bg-[#00A9C0] text-white text-[14px] font-bold shadow-md shadow-cyan-200/50 hover:bg-[#0092a6] transition-colors">
                                Ya, Setujui Data
                            </button>
                            <button type="button" onclick="closeApproveModal()" class="w-full py-3 rounded-xl bg-slate-100 text-slate-600 text-[14px] font-bold hover:bg-slate-200 transition-colors">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Reject Modal --}}
    <div id="rejectModal" class="fixed inset-0 z-[60] hidden">
        <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm" onclick="closeRejectModal()"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
            <div class="flex min-h-full items-end justify-center p-4 sm:items-center sm:p-0">
                <div class="relative transform overflow-hidden rounded-[24px] bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-sm">
                    <div class="p-6">
                        <div class="mx-auto w-14 h-14 rounded-2xl bg-rose-50 text-rose-500 flex items-center justify-center mb-4">
                            <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                        </div>
                        <h3 class="text-center text-lg font-bold text-slate-900 mb-2">Tolak Pengukuran</h3>
                        <p class="text-center text-[13px] text-slate-500 mb-4 leading-relaxed">
                            Data akan ditolak dan dikembalikan ke kader Posyandu untuk diperbaiki.
                        </p>
                        
                        <div class="mb-6">
                            <label class="block text-[11px] font-bold text-slate-700 uppercase tracking-wider mb-2">Alasan Penolakan</label>
                            <textarea id="alasanTolakInput" rows="3" placeholder="Masukkan alasan..." class="w-full rounded-xl border border-slate-200 bg-slate-50 px-3 py-2 text-sm text-slate-700 focus:border-rose-400 focus:ring-2 focus:ring-rose-200/40 outline-none transition-colors resize-none"></textarea>
                        </div>
                        
                        <div class="flex flex-col gap-3">
                            <button type="button" onclick="submitReject()" class="w-full py-3 rounded-xl bg-rose-500 text-white text-[14px] font-bold shadow-md shadow-rose-200/50 hover:bg-rose-600 transition-colors">
                                Tolak Data
                            </button>
                            <button type="button" onclick="closeRejectModal()" class="w-full py-3 rounded-xl bg-slate-100 text-slate-600 text-[14px] font-bold hover:bg-slate-200 transition-colors">
                                Batal
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<form id="actionForm" method="POST" class="hidden">
    @csrf
    <input type="hidden" name="catatan_validator" id="formCatatan">
</form>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // ── CHARTS ──
    const chartData = @json($child['chartData']);
    const ctx = document.getElementById('growthChart');
    let growthChart;
    
    if (ctx) {
        growthChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartData.labels,
                datasets: [{
                    label: 'Berat Badan (kg)',
                    data: chartData.bb,
                    borderColor: '#00A9C0',
                    backgroundColor: 'rgba(0,169,192,0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#fff',
                    pointBorderColor: '#00A9C0',
                    pointBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { grid: { color: '#f1f5f9' }, border: { display: false } },
                    x: { grid: { display: false }, border: { display: false } }
                }
            }
        });
    }

    window.updateChartMetric = function() {
        const metric = document.getElementById('chartMetricSelector').value;
        if (!growthChart) return;
        
        const map = {
            bb: { label: 'Berat Badan (kg)', data: chartData.bb, color: '#00A9C0' },
            tb: { label: 'Tinggi Badan (cm)', data: chartData.tb, color: '#f59e0b' },
            bbu: { label: 'Z-Score BB/U', data: chartData.bbu, color: '#10b981' },
            tbu: { label: 'Z-Score TB/U', data: chartData.tbu, color: '#6366f1' },
        };
        const m = map[metric];
        
        growthChart.data.datasets[0].label = m.label;
        growthChart.data.datasets[0].data = m.data;
        growthChart.data.datasets[0].borderColor = m.color;
        growthChart.data.datasets[0].pointBorderColor = m.color;
        growthChart.data.datasets[0].backgroundColor = m.color + '1A'; // 10% opacity hex
        growthChart.update();
    };

    // ── ACTIONS ──
    const actionForm = document.getElementById('actionForm');
    const formCatatan = document.getElementById('formCatatan');
    const mainCatatanInput = document.getElementById('catatanValidatorInput');
    const alasanTolakInput = document.getElementById('alasanTolakInput');
    
    window.openApproveModal = () => document.getElementById('approveModal').classList.remove('hidden');
    window.closeApproveModal = () => document.getElementById('approveModal').classList.add('hidden');
    
    window.openRejectModal = () => document.getElementById('rejectModal').classList.remove('hidden');
    window.closeRejectModal = () => document.getElementById('rejectModal').classList.add('hidden');

    window.submitApprove = () => {
        formCatatan.value = mainCatatanInput.value;
        actionForm.action = `/puskesmas/validasi/{{ $child['id'] }}/approve`;
        actionForm.submit();
    };

    window.submitReject = () => {
        if(!alasanTolakInput.value.trim()) {
            alasanTolakInput.focus();
            return;
        }
        formCatatan.value = alasanTolakInput.value;
        actionForm.action = `/puskesmas/validasi/{{ $child['id'] }}/reject`;
        actionForm.submit();
    };
</script>
@endpush

@endsection
