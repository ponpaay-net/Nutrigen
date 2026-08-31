import re

with open('app/Http/Controllers/Puskesmas/PuskesmasController.php', 'r', encoding='utf-8') as f:
    content = f.read()

method = """
    public function reviewValidasi($id)
    {
        $puskesmasId = $this->getPuskesmasId();
        
        $p = \App\Models\Pengukuran::with(['balita.orangTua', 'balita.posyandu', 'kader.user'])
            ->whereHas('balita.posyandu', function ($q) use ($puskesmasId) {
                $q->where('puskesmas_id', $puskesmasId);
            })
            ->findOrFail($id);
            
        $statusGizi = strtolower($p->status_gizi);
        $statusType = 'success';
        $statusLabel = 'Normal';
        if (in_array($statusGizi, ['stunting'])) {
            $statusType = 'danger';
            $statusLabel = 'Stunting';
        } elseif (in_array($statusGizi, ['risiko', 'kurang'])) {
            $statusType = 'warning';
            $statusLabel = 'Risiko Stunting';
        }

        $history = \App\Models\Pengukuran::where('balita_id', $p->balita_id)
            ->where('tanggal_ukur', '<', $p->tanggal_ukur)
            ->orderBy('tanggal_ukur', 'desc')
            ->limit(3)
            ->get()
            ->map(function ($h) {
                return [
                    'date' => \Carbon\Carbon::parse($h->tanggal_ukur)->translatedFormat('d M Y'),
                    'age' => $h->umur_bulan . ' bln',
                    'bb' => $h->berat_badan,
                    'tb' => $h->tinggi_badan,
                    'bbu' => $h->z_score_bbu,
                    'tbu' => $h->z_score_tbu,
                    'imtu' => null,
                    'status' => $h->status_gizi,
                ];
            })
            ->toArray();

        $zTbu = (float) $p->z_score_tbu;
        $indicator = 'TB/U';
        $valText = $zTbu;
        if ($zTbu < -2) {
            $valText .= ' (Pendek)';
        }
        $allMeasurements = \App\Models\Pengukuran::where('balita_id', $p->balita_id)
            ->orderBy('tanggal_ukur', 'asc')
            ->get();

        $chartData = [
            'labels' => [],
            'tb' => [],
            'bb' => [],
            'tbu' => [],
            'bbu' => []
        ];

        foreach ($allMeasurements as $m) {
            $chartData['labels'][] = $m->umur_bulan . ' bln';
            $chartData['tb'][] = (float) $m->tinggi_badan;
            $chartData['bb'][] = (float) $m->berat_badan;
            $chartData['tbu'][] = (float) $m->z_score_tbu;
            $chartData['bbu'][] = (float) $m->z_score_bbu;
        }

        $child = [
            'id' => $p->id,
            'name' => $p->balita->nama,
            'nik' => $p->balita->nik,
            'gender' => $p->balita->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            'age' => $p->umur_bulan . ' bln',
            'indicator' => $indicator,
            'value' => $valText,
            'posyandu' => $p->balita->posyandu->nama ?? '-',
            'kader' => $p->kader->nama ?? $p->kader->user->name ?? '-',
            'time' => \Carbon\Carbon::parse($p->tanggal_ukur)->format('H:i'),
            'date' => \Carbon\Carbon::parse($p->tanggal_ukur)->translatedFormat('d F Y'),
            'statusType' => $statusType,
            'statusLabel' => $statusLabel,
            'parent' => $p->balita->orangTua->nama_ibu ?? '-',
            'bb' => $p->berat_badan,
            'tb' => $p->tinggi_badan,
            'catatan_kader' => $p->catatan_kader,
            'catatan_validator' => $p->catatan_validator,
            'zscores' => [
                'BB (kg)' => ['val' => number_format((float)$p->berat_badan, 1), 'status' => 'Normal', 'color' => 'slate'],
                'TB (cm)' => ['val' => number_format((float)$p->tinggi_badan, 1), 'status' => 'Normal', 'color' => 'slate'],
                'BB/U' => ['val' => number_format((float)$p->z_score_bbu, 2), 'status' => ((float)$p->z_score_bbu < -2 ? 'Kurang' : 'Normal'), 'color' => 'slate'],
                'TB/U' => ['val' => number_format((float)$p->z_score_tbu, 2), 'status' => ((float)$p->z_score_tbu < -2 ? 'Pendek' : 'Normal'), 'color' => ((float)$p->z_score_tbu < -2 ? 'rose' : 'slate')],
                'IMT/U'=> ['val' => number_format((float)$p->z_score_bbu, 2), 'status' => 'Normal', 'color' => 'slate'],
            ],
            'history' => $history,
            'chartData' => $chartData,
            'status_validasi' => $p->status_validasi,
            'balita_id' => $p->balita_id
        ];

        return view('puskesmas.validasi-review', compact('child'));
    }
"""

if "public function reviewValidasi" not in content:
    content = content.replace("public function validasi(Request $request)\n    {", method + "\n    public function validasi(Request $request)\n    {")
    with open('app/Http/Controllers/Puskesmas/PuskesmasController.php', 'w', encoding='utf-8') as f:
        f.write(content)
    print("Method inserted.")
else:
    print("Method already exists.")
