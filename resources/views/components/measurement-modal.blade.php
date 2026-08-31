@props([
    'childName',
    'age',
    'lastWeight' => null,
    'lastHeight' => null,
    'lastDate'   => null,
])

{{--
|--------------------------------------------------------------------------
| x-measurement-modal (Clean & Spacious Startup ProMax Edition)
|--------------------------------------------------------------------------
| Backend bindings — NEVER MODIFY:
|   Props   : childName, age, lastWeight, lastHeight, lastDate
|   Form    : action="{{ route('pengukuran.store') }}" method="POST"
|   Hidden  : name="balita_id"  value="{{ $balitaId }}"
|   Fields  : name="berat_badan" id="berat"    → oninput="validateWeight(this.value)"
|             name="tinggi_badan" id="tinggi"
|             name="lingkar_kepala" id="lingkar"
|             name="tanggal_ukur"  id="tanggal"  value="{{ old('tanggal_ukur', now()->format('Y-m-d')) }}"
|             name="catatan"       id="catatan"
|   JS IDs  : measurementModal, modal-input-state, modal-result-state
|             btn-submit, btn-text, btn-icon, btn-spinner
|             diagnosis-label, diagnosis-text, weight-warning
|   JS fns  : openMeasurementModal(), closeMeasurementModal(isSuccess?)
|             resetModalState(), setLoadingState(), transitionToResult()
|             validateWeight(v), previousWeight = {{ $lastWeight ?? 0 }}
--}}

<div id="measurementModal" class="fixed inset-0 z-[100] hidden opacity-0 transition-opacity duration-200" role="dialog" aria-modal="true" aria-labelledby="modal-title">

    {{-- ── BACKDROP ────────────────────────────────────────────────────── --}}
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-xs transition-opacity" onclick="closeMeasurementModal()"></div>

    {{-- ── POSITIONING WRAPPER ─────────────────────────────────────────── --}}
    <div class="absolute inset-0 flex items-end sm:items-center justify-center p-0 sm:p-4 md:p-6 pointer-events-none">

        {{-- ── UNIFIED MODAL SURFACE ───────────────────────────────────── --}}
        <div class="w-full max-w-2xl bg-white rounded-t-[28px] sm:rounded-3xl shadow-2xl flex flex-col max-h-[88dvh] sm:max-h-[90vh] overflow-hidden pointer-events-auto border border-slate-200/90">

            {{-- ════════════════════════════════════════════════════════════
                 STATE 1: INPUT FORM
            ═══════════════════════════════════════════════════════════════ --}}
            <div id="modal-input-state" class="flex flex-col flex-1 min-h-0 overflow-hidden">

                {{-- ── Mobile Drag Handle ── --}}
                <div class="w-full pt-3 pb-1 flex justify-center sm:hidden bg-slate-50/70 shrink-0">
                    <div class="w-10 h-1 bg-slate-300 rounded-full"></div>
                </div>

                {{-- ── Clean Header & Patient Info ── --}}
                <div class="flex-shrink-0 px-5 pt-4 pb-4 sm:px-8 sm:pt-6 sm:pb-5 border-b border-slate-100 bg-slate-50/70">
                    <div class="flex items-center justify-between gap-4">
                        <div class="min-w-0">
                            <h2 id="modal-title" class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight leading-tight">
                                Input Pengukuran
                            </h2>
                            <p class="text-xs sm:text-sm text-slate-500 font-medium mt-0.5">
                                Catat data pertumbuhan bulanan untuk dipantau oleh sistem.
                            </p>
                        </div>

                        <button type="button" 
                                onclick="closeMeasurementModal()" 
                                class="w-9 h-9 rounded-full bg-slate-200/70 hover:bg-slate-200 text-slate-600 hover:text-slate-900 flex items-center justify-center transition-colors focus:outline-none cursor-pointer shrink-0"
                                aria-label="Tutup popup pengukuran">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-4 h-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    {{-- Child Quick Context Strip --}}
                    <div class="mt-3.5 flex items-center justify-between p-3 bg-white rounded-2xl border border-slate-200/80 shadow-2xs">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-black text-base shrink-0 border border-teal-100">
                                {{ strtoupper(substr($childName ?? 'B', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h3 class="text-sm sm:text-base font-bold tracking-tight text-slate-900 truncate">{{ $childName }}</h3>
                                    <span class="text-[10px] font-extrabold text-teal-800 bg-teal-50 px-2 py-0.5 rounded-md border border-teal-200/70 shrink-0">{{ $age }}</span>
                                </div>
                                @if($lastWeight && $lastDate)
                                <div class="text-xs text-slate-500 font-medium mt-0.5 truncate flex items-center gap-1.5">
                                    <span>Terakhir:</span>
                                    <span class="font-bold text-slate-700">{{ $lastWeight }} kg</span>
                                    <span class="text-slate-300">•</span>
                                    <span class="font-bold text-slate-700">{{ $lastHeight }} cm</span>
                                    <span class="text-slate-400 hidden sm:inline">pada {{ $lastDate }}</span>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Scrollable Form Area with Generous Spacing ── --}}
                <div class="flex-1 min-h-0 overflow-y-auto overscroll-contain px-5 sm:px-8 py-5 sm:py-6">
                    <form id="measurementForm" action="{{ route('pengukuran.store') }}" method="POST" onsubmit="setTimeout(() => setLoadingState(true), 10);">
                        @csrf
                        <input type="hidden" name="balita_id" value="{{ $balitaId }}">

                        {{-- Spacious 2-Column Responsive Grid --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">

                            {{-- ── 1. Berat Badan ── --}}
                            <div class="flex flex-col gap-1.5">
                                <label for="berat" class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Berat Badan <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative flex items-center group">
                                    <input
                                        type="text" inputmode="decimal"
                                        id="berat" name="berat_badan"
                                        value="{{ old('berat_badan') }}" required
                                        placeholder="Contoh: 7.90"
                                        oninput="validateWeight(this.value)"
                                        class="w-full h-12 sm:h-13 bg-slate-50/80 border border-slate-200 group-hover:border-slate-300 focus:bg-white focus:border-teal-600 focus:ring-4 focus:ring-teal-500/15 rounded-2xl pl-4 pr-12 text-base font-semibold text-slate-800 placeholder:text-slate-300 transition-all outline-none">
                                    <span class="absolute right-4 text-xs sm:text-sm font-bold text-slate-400 group-focus-within:text-teal-600 transition-colors pointer-events-none uppercase">kg</span>
                                </div>
                                @error('berat_badan')
                                    <p class="text-xs text-rose-500 font-semibold mt-0.5">{{ $message }}</p>
                                @enderror

                                <div id="weight-warning" class="hidden mt-1.5 bg-amber-50 border border-amber-200/80 rounded-xl p-3 flex items-start gap-2.5">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-amber-600 shrink-0 mt-0.5">
                                        <path fill-rule="evenodd" d="M8.485 2.495c.673-1.167 2.357-1.167 3.03 0l6.28 10.875c.673 1.167-.17 2.625-1.516 2.625H3.72c-1.347 0-2.189-1.458-1.515-2.625L8.485 2.495zM10 5a.75.75 0 01.75.75v3.5a.75.75 0 01-1.5 0v-3.5A.75.75 0 0110 5zm0 9a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd" />
                                    </svg>
                                    <span class="text-xs font-semibold text-amber-900 leading-tight">Perhatian: Berat badan turun > 0.5kg dari bulan lalu. Periksa kembali timbangan.</span>
                                </div>
                            </div>

                            {{-- ── 2. Tinggi / Panjang Badan ── --}}
                            <div class="flex flex-col gap-1.5">
                                <label for="tinggi" class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Tinggi / Panjang Badan <span class="text-rose-500">*</span>
                                </label>
                                <div class="relative flex items-center group">
                                    <input
                                        type="text" inputmode="decimal"
                                        id="tinggi" name="tinggi_badan"
                                        value="{{ old('tinggi_badan') }}" required
                                        placeholder="Contoh: 68.7"
                                        class="w-full h-12 sm:h-13 bg-slate-50/80 border border-slate-200 group-hover:border-slate-300 focus:bg-white focus:border-teal-600 focus:ring-4 focus:ring-teal-500/15 rounded-2xl pl-4 pr-12 text-base font-semibold text-slate-800 placeholder:text-slate-300 transition-all outline-none">
                                    <span class="absolute right-4 text-xs sm:text-sm font-bold text-slate-400 group-focus-within:text-teal-600 transition-colors pointer-events-none uppercase">cm</span>
                                </div>
                                @error('tinggi_badan')
                                    <p class="text-xs text-rose-500 font-semibold mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- ── 3. Lingkar Kepala ── --}}
                            <div class="flex flex-col gap-1.5">
                                <label for="lingkar" class="flex items-center justify-between text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    <span>Lingkar Kepala</span>
                                    <span class="text-[10px] font-semibold text-slate-400 normal-case tracking-normal">Opsional</span>
                                </label>
                                <div class="relative flex items-center group">
                                    <input
                                        type="text" inputmode="decimal"
                                        id="lingkar" name="lingkar_kepala"
                                        value="{{ old('lingkar_kepala') }}"
                                        placeholder="Contoh: 42.5"
                                        class="w-full h-12 sm:h-13 bg-slate-50/80 border border-slate-200 group-hover:border-slate-300 focus:bg-white focus:border-teal-600 focus:ring-4 focus:ring-teal-500/15 rounded-2xl pl-4 pr-12 text-base font-semibold text-slate-800 placeholder:text-slate-300 transition-all outline-none">
                                    <span class="absolute right-4 text-xs sm:text-sm font-bold text-slate-400 group-focus-within:text-teal-600 transition-colors pointer-events-none uppercase">cm</span>
                                </div>
                                @error('lingkar_kepala')
                                    <p class="text-xs text-rose-500 font-semibold mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- ── 4. Status Kenaikan KMS ── --}}
                            <div class="flex flex-col gap-1.5">
                                <label for="status_kenaikan" class="flex items-center justify-between text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    <span>Status Kenaikan BB (KMS)</span>
                                    <span class="text-[10px] font-semibold text-slate-400 normal-case tracking-normal">Opsional</span>
                                </label>
                                <div class="relative flex items-center">
                                    <select
                                        id="status_kenaikan" name="status_kenaikan"
                                        class="w-full h-12 sm:h-13 bg-slate-50/80 border border-slate-200 hover:border-slate-300 focus:bg-white focus:border-teal-600 focus:ring-4 focus:ring-teal-500/15 rounded-2xl pl-4 pr-10 text-xs sm:text-sm font-semibold text-slate-800 transition-all outline-none appearance-none cursor-pointer">
                                        <option value="" {{ old('status_kenaikan') == '' ? 'selected' : '' }}>-- Pilih Status KMS --</option>
                                        <option value="N" {{ old('status_kenaikan') == 'N' ? 'selected' : '' }}>N — Naik sesuai garis kurva</option>
                                        <option value="T" {{ old('status_kenaikan') == 'T' ? 'selected' : '' }}>T — Tidak naik / Tetap / Turun</option>
                                        <option value="B" {{ old('status_kenaikan') == 'B' ? 'selected' : '' }}>B — Baru / Belum ada data lalu</option>
                                    </select>
                                    <div class="absolute right-4 pointer-events-none text-slate-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.2" stroke="currentColor" class="w-4 h-4"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
                                    </div>
                                </div>
                                @error('status_kenaikan')
                                    <p class="text-xs text-rose-500 font-semibold mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- ── 5. Pemberian ASI Eksklusif ── --}}
                            <div class="flex flex-col gap-1.5">
                                <label class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Pemberian ASI Eksklusif
                                </label>
                                <div class="grid grid-cols-2 gap-2.5 h-12 sm:h-13">
                                    <label class="relative flex items-center justify-center px-4 rounded-2xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-teal-600 has-[:checked]:bg-teal-50 has-[:checked]:text-teal-900 font-semibold text-xs sm:text-sm text-slate-600 shadow-2xs">
                                        <input type="radio" name="asi_eksklusif" value="1" {{ old('asi_eksklusif', '1') == '1' ? 'checked' : '' }} class="sr-only">
                                        <span class="flex items-center gap-1.5">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="w-4 h-4 text-teal-600"><path fill-rule="evenodd" d="M16.704 4.153a.75.75 0 01.143 1.052l-8 10.5a.75.75 0 01-1.127.075l-4.5-4.5a.75.75 0 011.06-1.06l3.894 3.893 7.48-9.817a.75.75 0 011.05-.143z" clip-rule="evenodd" /></svg>
                                            Ya (ASI Saja)
                                        </span>
                                    </label>
                                    <label class="relative flex items-center justify-center px-4 rounded-2xl border border-slate-200 cursor-pointer hover:bg-slate-50 transition-all has-[:checked]:border-slate-500 has-[:checked]:bg-slate-100 has-[:checked]:text-slate-900 font-semibold text-xs sm:text-sm text-slate-600 shadow-2xs">
                                        <input type="radio" name="asi_eksklusif" value="0" {{ old('asi_eksklusif') === '0' ? 'checked' : '' }} class="sr-only">
                                        <span>Tidak</span>
                                    </label>
                                </div>
                            </div>

                            {{-- ── 6. Tanggal Pengukuran ── --}}
                            <div class="flex flex-col gap-1.5">
                                <label for="tanggal" class="text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    Tanggal Pengukuran <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="date"
                                    id="tanggal" name="tanggal_ukur"
                                    value="{{ old('tanggal_ukur', now()->format('Y-m-d')) }}" required
                                    class="w-full h-12 sm:h-13 bg-slate-50/80 border border-slate-200 hover:border-slate-300 focus:bg-white focus:border-teal-600 focus:ring-4 focus:ring-teal-500/15 rounded-2xl px-4 text-xs sm:text-sm font-semibold text-slate-800 transition-all outline-none cursor-pointer">
                                @error('tanggal_ukur')
                                    <p class="text-xs text-rose-500 font-semibold mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- ── 7. Catatan Tambahan Kader ── --}}
                            <div class="flex flex-col gap-1.5 sm:col-span-2">
                                <label for="catatan_kader" class="flex items-center justify-between text-xs font-bold text-slate-700 uppercase tracking-wider">
                                    <span>Catatan Tambahan Kader</span>
                                    <span class="text-[10px] font-semibold text-slate-400 normal-case tracking-normal">Opsional</span>
                                </label>
                                <textarea
                                    id="catatan_kader" name="catatan_kader"
                                    rows="2"
                                    placeholder="Contoh: Balita aktif, nafsu makan baik, catatan imunisasi anak."
                                    class="w-full bg-slate-50/80 border border-slate-200 hover:border-slate-300 focus:bg-white focus:border-teal-600 focus:ring-4 focus:ring-teal-500/15 rounded-2xl px-4 py-3 text-xs sm:text-sm text-slate-800 placeholder:text-slate-400 transition-all outline-none resize-none"
                                >{{ old('catatan_kader') }}</textarea>
                                @error('catatan_kader')
                                    <p class="text-xs text-rose-500 font-semibold mt-0.5">{{ $message }}</p>
                                @enderror
                            </div>

                        </div>{{-- end grid --}}
                    </form>
                </div>

                {{-- ── Clean & Spacious Sticky CTA Footer ── --}}
                <div class="flex-shrink-0 bg-white/95 backdrop-blur-md border-t border-slate-100 px-5 py-3.5 sm:px-8 sm:py-4 flex items-center justify-end gap-3 rounded-b-[28px] sm:rounded-b-3xl">
                    <button type="button" 
                            onclick="closeMeasurementModal()" 
                            class="h-11 sm:h-12 px-5 sm:px-6 rounded-2xl border border-slate-200 text-slate-700 hover:bg-slate-100 font-bold text-xs sm:text-sm transition-colors focus:outline-none cursor-pointer">
                        Batal
                    </button>
                    <button
                        type="submit" form="measurementForm" id="btn-submit"
                        class="flex-1 sm:flex-initial h-11 sm:h-12 px-6 sm:px-8 bg-gradient-to-r from-teal-600 via-teal-700 to-emerald-700 hover:from-teal-500 hover:to-emerald-600 active:scale-[0.99] text-white rounded-2xl font-black text-xs sm:text-sm shadow-sm hover:shadow transition-all focus:outline-none focus:ring-4 focus:ring-teal-500/20 disabled:opacity-60 disabled:cursor-not-allowed flex items-center justify-center gap-2 cursor-pointer">
                        <span id="btn-text">Simpan Pengukuran</span>
                        <svg id="btn-icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                        <svg id="btn-spinner" class="animate-spin h-4 w-4 text-white hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </button>
                </div>

            </div>{{-- end #modal-input-state --}}

            {{-- ════════════════════════════════════════════════════════════
                 STATE 2: RESULT & DIAGNOSIS
            ═══════════════════════════════════════════════════════════════ --}}
            <div id="modal-result-state" class="hidden flex-col items-center justify-center text-center p-6 sm:p-10">
                <div class="w-14 h-14 bg-emerald-50 rounded-full flex items-center justify-center mb-5 ring-8 ring-emerald-50/50">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" class="w-7 h-7 text-emerald-600">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" />
                    </svg>
                </div>

                <h2 class="text-xl font-black text-slate-900 mb-1.5 tracking-tight">Data Berhasil Tersimpan</h2>
                <p class="text-xs sm:text-sm text-slate-500 mb-6 max-w-sm mx-auto leading-relaxed">
                    Pengukuran <span class="font-bold text-slate-800">{{ $childName }}</span> telah terhubung dengan perhitungan Z-Score WHO 2006.
                </p>

                <div class="bg-slate-50 border border-slate-200/80 rounded-2xl p-5 w-full max-w-sm flex flex-col items-center gap-3">
                    <p class="text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">Status Gizi (Z-Score)</p>

                    <div class="bg-white shadow-2xs border border-emerald-200 text-emerald-800 px-4 py-1.5 rounded-xl flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span id="diagnosis-label" class="text-xs sm:text-sm font-extrabold tracking-wide">Gizi Baik (Normal)</span>
                    </div>

                    <p id="diagnosis-text" class="text-xs text-slate-600 font-medium text-center leading-relaxed">
                        Pertumbuhan anak berada dalam kurva normal WHO 2006. Lanjutkan pola makan gizi seimbang.
                    </p>
                </div>

                <button type="button" onclick="closeMeasurementModal(true)"
                    class="mt-6 w-full max-w-sm h-11 sm:h-12 flex items-center justify-center bg-teal-600 hover:bg-teal-700 text-white rounded-2xl font-bold text-xs sm:text-sm shadow-xs transition-all focus:outline-none cursor-pointer">
                    Kembali ke Profil Balita
                </button>
            </div>{{-- end #modal-result-state --}}

        </div>{{-- end unified modal surface --}}

    </div>
</div>

<script>
    // ──────────────────────────────────────────────────────────────────────────
    // Measurement Modal — Frontend Logic Layer
    // ALL LOGIC AND IDs PRESERVED — DO NOT MODIFY
    // ──────────────────────────────────────────────────────────────────────────

    const Elements = {
        modal:          document.getElementById('measurementModal'),
        inputState:     document.getElementById('modal-input-state'),
        resultState:    document.getElementById('modal-result-state'),
        diagnosisLabel: document.getElementById('diagnosis-label'),
        diagnosisText:  document.getElementById('diagnosis-text'),
        btnText:        document.getElementById('btn-text'),
        btnIcon:        document.getElementById('btn-icon'),
        btnSpinner:     document.getElementById('btn-spinner'),
        btnSubmit:      document.getElementById('btn-submit'),
        warningBox:     document.getElementById('weight-warning'),
    };

    const previousWeight = {{ $lastWeight ?? 0 }};

    function openMeasurementModal() {
        Elements.modal.classList.remove('hidden');
        setTimeout(() => Elements.modal.classList.remove('opacity-0'), 10);
        document.body.style.overflow = 'hidden';
    }

    function closeMeasurementModal(isSuccess = false) {
        Elements.modal.classList.add('opacity-0');
        setTimeout(() => {
            Elements.modal.classList.add('hidden');
            document.body.style.overflow = '';
            if (isSuccess) {
                window.location.reload();
            } else {
                resetModalState();
            }
        }, 200);
    }

    function resetModalState() {
        Elements.inputState.classList.remove('hidden');
        Elements.resultState.classList.add('hidden');
        Elements.warningBox.classList.add('hidden');
        document.getElementById('measurementForm').reset();
    }

    function setLoadingState(isLoading) {
        if (isLoading) {
            Elements.btnSubmit.disabled = true;
            Elements.btnText.textContent = 'Menghitung...';
            Elements.btnIcon.classList.add('hidden');
            Elements.btnSpinner.classList.remove('hidden');
        } else {
            Elements.btnSubmit.disabled = false;
            Elements.btnText.textContent = 'Simpan Pengukuran';
            Elements.btnIcon.classList.remove('hidden');
            Elements.btnSpinner.classList.add('hidden');
        }
    }

    function transitionToResult() {
        Elements.inputState.classList.add('hidden');
        Elements.resultState.classList.remove('hidden');
        Elements.resultState.style.opacity = 0;
        setTimeout(() => {
            Elements.resultState.style.transition = 'opacity 0.5s ease';
            Elements.resultState.style.opacity = 1;
        }, 50);
    }

    function validateWeight(currentWeightStr) {
        if (!previousWeight || !currentWeightStr) return;
        const currentWeight = parseFloat(currentWeightStr);
        if (previousWeight - currentWeight > 0.5) {
            Elements.warningBox.classList.remove('hidden');
        } else {
            Elements.warningBox.classList.add('hidden');
        }
    }
</script>
