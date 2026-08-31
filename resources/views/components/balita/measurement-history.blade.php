@props(['pengukurans'])

<div class="bg-white p-5 rounded-[2rem] border border-slate-200 shadow-sm">
    <h3 class="text-xs font-bold tracking-tight text-slate-800 uppercase tracking-widest mb-4 flex items-center gap-2">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-slate-400">
            <path fill-rule="evenodd" d="M12 2.25v1.5a.75.75 0 01-1.5 0V2.25H9v1.5a.75.75 0 01-1.5 0V2.25H6v1.5a.75.75 0 01-1.5 0V2.25H3v19.5h18V2.25h-1.5v1.5a.75.75 0 01-1.5 0V2.25h-1.5v1.5a.75.75 0 01-1.5 0V2.25h-1.5zM7.5 12a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm4.5 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm4.5 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm-9 4.5a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm4.5 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0zm4.5 0a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z" clip-rule="evenodd" />
        </svg>
        Riwayat Pengukuran & Validasi
    </h3>
    
    <div class="relative pl-5 border-l-2 border-slate-100 flex flex-col gap-6 ml-2 mt-2">
        @forelse($pengukurans as $p)
            @php
                // Since validation is dropped in MVP, we simulate everything as valid
                $statusColor = 'emerald';
                $simulatedStatus = 'VALID';
            @endphp
            <div class="relative">
                <!-- Glowing Timeline Dot -->
                <div class="absolute -left-[27px] top-1.5 w-3 h-3 rounded-full bg-{{ $statusColor }}-500 ring-4 ring-{{ $statusColor }}-50 shadow-sm"></div>
                
                <div class="bg-white border border-slate-200 rounded-[1.5rem] p-5 flex flex-col gap-4 shadow-sm hover:shadow-sm border border-slate-200/60 transition-shadow">
                    
                    <!-- Top Row: Date & Status -->
                    <div class="flex flex-wrap items-center justify-between gap-2 border-b border-slate-100 pb-3">
                        <span class="text-[13px] font-black text-slate-800">{{ date('d M Y', strtotime($p['created_at'])) }} <span class="text-slate-400 font-medium ml-1">(Umur: {{ $p['umur_bulan'] }} Bln)</span></span>
                        <div class="flex items-center gap-1.5 px-2.5 py-1 rounded-md text-[10px] font-bold uppercase tracking-wide bg-{{ $statusColor }}-50 text-{{ $statusColor }}-700 ring-1 ring-{{ $statusColor }}-200/50">
                            <span class="w-1.5 h-1.5 rounded-full bg-{{ $statusColor }}-500"></span>
                            {{ $simulatedStatus }}
                        </div>
                    </div>
                    
                    <!-- Data Pengukuran -->
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 text-sm bg-slate-50 p-4 rounded-xl border border-slate-100">
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Berat (BB)</span>
                            <span class="font-black text-slate-800 text-[14px]">{{ $p['berat_badan'] }} <span class="text-slate-500 font-medium text-[11px]">kg</span></span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Tinggi (TB)</span>
                            <span class="font-black text-slate-800 text-[14px]">{{ $p['tinggi_badan'] }} <span class="text-slate-500 font-medium text-[11px]">cm</span></span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Z-Score (BB/U)</span>
                            <span class="font-black text-slate-800 text-[14px]">{{ $p['z_score_bb_u'] }}</span>
                        </div>
                        <div class="flex flex-col gap-1">
                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">Status Gizi</span>
                            @php
                                $giziColor = 'slate';
                                $giziLower = strtolower($p['status_gizi']);
                                if(in_array($giziLower, ['normal', 'gizi baik'])) $giziColor = 'emerald';
                                elseif(in_array($giziLower, ['kurang', 'kurus', 'risiko lebih'])) $giziColor = 'amber';
                                elseif(in_array($giziLower, ['stunting', 'gizi buruk', 'sangat kurus', 'obesitas'])) $giziColor = 'rose';
                            @endphp
                            <span class="font-black text-{{ $giziColor }}-600 text-[14px]">{{ $p['status_gizi'] }}</span>
                        </div>
                    </div>

                    <!-- Audit Trail (Jika ada validasi) -->
                    @if(isset($p['validasi']))
                        <div class="mt-2 p-3 bg-white border border-slate-100 rounded-lg text-xs flex flex-col gap-1 shadow-sm">
                            <div class="flex items-center gap-1.5 text-slate-500 mb-1">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-3.5 h-3.5">
                                  <path fill-rule="evenodd" d="M10 2a.75.75 0 01.75.75v7.5a.75.75 0 01-1.5 0v-7.5A.75.75 0 0110 2zM5.404 4.343a.75.75 0 010 1.06 6.5 6.5 0 109.192 0 .75.75 0 111.06-1.06 8 8 0 11-11.313 0 .75.75 0 011.06 0z" clip-rule="evenodd" />
                                </svg>
                                <span class="font-bold">Audit Trail Validasi</span>
                            </div>
                            <div class="flex flex-col sm:flex-row sm:gap-4 text-slate-600">
                                <span><span class="text-slate-400">Oleh:</span> {{ $p['validasi']['validator_name'] }}</span>
                                <span><span class="text-slate-400">Pada:</span> {{ date('d M Y H:i', strtotime($p['validasi']['created_at'])) }}</span>
                            </div>
                            @if($p['validasi']['catatan'])
                                <div class="mt-1 pt-1 border-t border-slate-50 italic text-slate-500">
                                    "{{ $p['validasi']['catatan'] }}"
                                </div>
                            @endif
                        </div>
                    @endif
                    
                </div>
            </div>
        @empty
            <div class="text-sm text-slate-400 italic">Belum ada riwayat pengukuran.</div>
        @endforelse
    </div>
</div>
