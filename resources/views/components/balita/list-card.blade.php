@props([
    'child',
    'isActive' => false
])

@php
    $initials = collect(explode(' ', $child['nama']))->map(fn($n) => substr($n, 0, 1))->take(2)->join('');
    
    // Get latest measurement for list display (if any)
    $latestMeasurement = count($child['pengukurans']) > 0 ? $child['pengukurans'][0] : null;
    
    // Determine badge color based on status_gizi (simplified logic for dummy)
    $rawStatus = $latestMeasurement ? $latestMeasurement['status_gizi'] : 'Belum Diukur';
    $statusGizi = ucwords($rawStatus);
    $checkStatus = strtolower($rawStatus);
    
    $statusType = 'slate';
    if(in_array($checkStatus, ['normal', 'gizi baik'])) $statusType = 'success';
    elseif(in_array($checkStatus, ['kurang', 'kurus', 'risiko lebih', 'risiko'])) $statusType = 'warning';
    elseif(in_array($checkStatus, ['stunting', 'gizi buruk', 'sangat kurus', 'obesitas'])) $statusType = 'danger';
@endphp

<button type="button" 
    data-balita-id="{{ $child['id'] }}"
    data-nama="{{ strtolower($child['nama']) }}"
    data-posyandu="{{ strtolower($child['posyandu']['nama']) }}"
    data-status="{{ strtolower($latestMeasurement['status_gizi'] ?? 'belum_diukur') }}"
    class="balita-card-btn w-full text-left px-5 py-4 border-b border-slate-100 transition-all duration-200 focus:outline-none flex gap-4 relative
    {{ $isActive ? 'bg-sky-50/60 border-l-4 border-l-sky-500 z-10' : 'bg-white hover:bg-slate-50 hover:border-l-slate-300 border-l-4 border-l-transparent' }}">
    
    <!-- Photo / Avatar -->
    <div class="shrink-0 mt-0.5">
        @if(isset($child['foto']) && $child['foto'])
            <img src="{{ $child['foto'] }}" alt="{{ $child['nama'] }}" class="w-12 h-12 rounded-full object-cover border border-slate-200 shadow-sm">
        @else
            <div class="w-12 h-12 rounded-full bg-gradient-to-br from-slate-100 to-slate-200 text-slate-600 flex items-center justify-center font-bold text-sm border border-slate-200 shadow-sm">
                {{ strtoupper($initials) }}
            </div>
        @endif
    </div>

    <!-- Content -->
    <div class="flex-1 min-w-0">
        <!-- Top Row -->
        <div class="flex justify-between items-start mb-0.5">
            <h4 class="font-bold tracking-tight truncate text-sm text-slate-800 balita-card-name">
                {{ $child['nama'] }}
            </h4>
            <span class="text-[10px] {{ $child['jenis_kelamin'] == 'L' ? 'text-sky-600 bg-sky-50 border-sky-100' : 'text-rose-600 bg-rose-50 border-rose-100' }} border px-1.5 py-0.5 rounded font-bold whitespace-nowrap ml-2">
                {{ $child['jenis_kelamin'] == 'L' ? 'Laki-laki' : 'Perempuan' }}
            </span>
        </div>
        
        <!-- Second Row -->
        <div class="flex items-center gap-1.5 text-[11px] mb-2">
            <span class="text-slate-600 font-bold">
                @if($latestMeasurement)
                    {{ $latestMeasurement['umur_bulan'] }} bln
                @else
                    Baru Daftar
                @endif
            </span>
            <span class="text-slate-300">&bull;</span>
            <x-status-badge :type="$statusType" :label="$statusGizi" />
        </div>

        <!-- Bottom Row -->
        <div class="flex items-center gap-1.5 text-[11px] text-slate-500 mt-1.5 truncate">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5 text-slate-400 shrink-0">
              <path fill-rule="evenodd" d="M11.54 22.351l.07.04.028.016a.76.76 0 00.723 0l.028-.015.071-.041a16.975 16.975 0 001.144-.742 19.58 19.58 0 002.683-2.282c1.944-1.99 3.963-4.98 3.963-8.827a8.25 8.25 0 00-16.5 0c0 3.846 2.02 6.837 3.963 8.827a19.58 19.58 0 002.682 2.282 16.975 16.975 0 001.145.742zM12 13.5a3 3 0 100-6 3 3 0 000 6z" clip-rule="evenodd" />
            </svg>
            <span class="truncate font-medium">{{ $child['posyandu']['nama'] }}</span>
        </div>
    </div>
</button>
