<?php

namespace App\Http\Controllers\Puskesmas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DashboardService;
use App\Services\StatisticsService;
use App\Services\WhatsAppService;
use App\Models\Balita;
use App\Models\Posyandu;
use App\Models\Pengukuran;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Requests\Puskesmas\StorePosyanduRequest;
use App\Http\Requests\Puskesmas\StoreKaderRequest;
use App\Http\Requests\Puskesmas\UpdateKeamananRequest;

class PuskesmasController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService,
        protected StatisticsService $statisticsService,
        protected WhatsAppService $whatsAppService
    ) {}

    /**
     * Helper untuk mendapatkan ID Puskesmas dari user yang login.
     */
    private function getPuskesmasId(): int
    {
        $puskesmasId = Auth::user()?->puskesmas?->id;
        if (!$puskesmasId) {
            abort(403, 'Akses ditolak: Anda tidak memiliki relasi Puskesmas yang valid.');
        }
        return $puskesmasId;
    }

    public function dashboard()
    {
        $puskesmasId = $this->getPuskesmasId();

        // Retrieve all dashboard statistics from SSOT service
        $stats = $this->statisticsService->getDashboardStats($puskesmasId);

        // Prepare distribution data for view (normal, risiko, stunting percentages)
        $totalDist = $stats['normal'] + $stats['risiko'] + $stats['stunting'];
        $totalDist = $totalDist > 0 ? $totalDist : 1;
        $distribution = [
            'normal' => ['count' => $stats['normal'], 'percentage' => round(($stats['normal'] / $totalDist) * 100)],
            'perlu_perhatian' => ['count' => $stats['risiko'], 'percentage' => round(($stats['risiko'] / $totalDist) * 100)],
            'berisiko' => ['count' => $stats['stunting'], 'percentage' => round(($stats['stunting'] / $totalDist) * 100)],
            'total_diukur' => $totalDist,
        ];

        // Recent activities (latest 4 measurements)
        $recentActivities = Pengukuran::whereHas('balita.posyandu', function ($q) use ($puskesmasId) {
            $q->where('puskesmas_id', $puskesmasId);
        })->with(['balita.posyandu'])
            ->orderBy('created_at', 'desc')
            ->take(4)
            ->get();

        // Add user name for UI consistency
        $stats['user_name'] = Auth::user()->name;

        return view('puskesmas.dashboard', compact('stats', 'distribution', 'recentActivities'));
    }


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

    public function validasi(Request $request)
    {
        $puskesmasId = $this->getPuskesmasId();

        $filters = [
            'tab' => $request->input('tab', 'pending'),
            'posyandu_id' => $request->input('posyandu_id', '')
        ];

        $posyandus = Posyandu::where('puskesmas_id', $puskesmasId)->get(['id', 'nama'])->toArray();

        // Determine validation status by tab ('pending' default, 'selesai' = approved)
        $statusValidation = $filters['tab'] === 'selesai' ? 'approved' : 'pending';

        // Eager-load all measurements per balita once to avoid N+1 on history/chart
        $query = Pengukuran::with(['balita.orangTua', 'balita.posyandu', 'kader.user', 'balita.pengukurans'])
            ->whereHas('balita.posyandu', function ($q) use ($puskesmasId) {
                $q->where('puskesmas_id', $puskesmasId);
            })
            ->where('status_validasi', $statusValidation);

        if ($filters['posyandu_id']) {
            $query->whereHas('balita.posyandu', function ($q) use ($filters) {
                $q->where('id', $filters['posyandu_id']);
            });
        }

        // Limit to recent two months
        $recentDate = Carbon::now()->subMonths(2)->startOfMonth();
        $query->where('tanggal_ukur', '>=', $recentDate)
              ->orderBy('tanggal_ukur', 'desc');

        $allPengukurans = $query->get();

        // Use StatisticsService for aggregated queue stats
        $stats = $this->statisticsService->getValidationQueueStats(
            $puskesmasId,
            $filters['posyandu_id'] ?: null
        );

        $children = [];
        foreach ($allPengukurans as $p) {
            $statusGizi = strtolower($p->status_gizi);
            $statusType = 'success';
            $statusLabel = 'Normal';
            $isAnomali = false;
            $isBerisiko = false;
            $isNormal = $statusGizi === 'normal';
            if (in_array($statusGizi, ['stunting'])) {
                $statusType = 'danger';
                $statusLabel = 'Stunting';
                $isBerisiko = true;
            } elseif (in_array($statusGizi, ['risiko', 'kurang'])) {
                $statusType = 'warning';
                $statusLabel = 'Risiko Stunting';
                $isAnomali = true;
            }
            // Apply tab filter
            if ($filters['tab'] === 'normal' && !$isNormal) continue;
            if ($filters['tab'] === 'anomali' && !$isAnomali) continue;
            if ($filters['tab'] === 'berisiko' && !$isBerisiko) continue;

            // Load already-aware measurements from eager-loaded relation (no N+1)
            $measurements = $p->balita->pengukurans->sortByDesc('tanggal_ukur');
            $history = $measurements
                ->filter(fn($h) => $h->tanggal_ukur < $p->tanggal_ukur)
                ->take(3)
                ->map(function ($h) {
                    return [
                        'date' => Carbon::parse($h->tanggal_ukur)->translatedFormat('d M Y'),
                        'age' => $h->umur_bulan . ' bln',
                        'bb' => $h->berat_badan,
                        'tb' => $h->tinggi_badan,
                        'bbu' => $h->z_score_bbu,
                        'tbu' => $h->z_score_tbu,
                        'imtu' => null,
                        'status' => $h->status_gizi,
                    ];
                })
                ->values()
                ->toArray();

            $zTbu = (float) $p->z_score_tbu;
            $indicator = 'TB/U';
            $valText = $zTbu;
            if ($zTbu < -2) {
                $valText .= ' (Pendek)';
            }
            // Use already-loaded measurements for the chart (ascending order)
            $allMeasurements = $p->balita->pengukurans->sortBy('tanggal_ukur');

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

            $children[] = [
                'id' => $p->id,
                'name' => $p->balita->nama,
                'nik' => $p->balita->nik,
                'gender' => $p->balita->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                'age' => $p->umur_bulan . ' bln',
                'indicator' => $indicator,
                'value' => $valText,
                'posyandu' => $p->balita->posyandu->nama ?? '-',
                'kader' => $p->kader->nama ?? $p->kader->user->name ?? '-',
                'time' => Carbon::parse($p->tanggal_ukur)->format('H:i'),
                'date' => Carbon::parse($p->tanggal_ukur)->translatedFormat('d F Y'),
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
                    'IMT/U'=> ['val' => number_format((float)$p->z_score_bbu, 2), 'status' => 'Normal', 'color' => 'slate'], // Using BBU as fallback for IMTU if not available in DB
                ],
                'history' => $history,
                'chartData' => $chartData,
            ];
        }

        return view('puskesmas.validasi', [
            'children' => $children,
            'filters' => $filters,
            'posyandus' => $posyandus,
            'stats' => $stats
        ]);
    }

    public function riwayat($id)
    {
        $puskesmasId = $this->getPuskesmasId();

        $currentMeasurement = Pengukuran::with(['balita.posyandu', 'validator'])
            ->whereHas('balita.posyandu', function ($q) use ($puskesmasId) {
                $q->where('puskesmas_id', $puskesmasId);
            })
            ->findOrFail($id);

        $measurements = Pengukuran::with('validator')
            ->where('balita_id', $currentMeasurement->balita_id)
            ->orderBy('tanggal_ukur', 'desc')
            ->get();

        return view('puskesmas.riwayat-validasi', [
            'child' => $currentMeasurement->balita,
            'posyandu' => $currentMeasurement->balita->posyandu->nama ?? '-',
            'measurements' => $measurements,
        ]);
    }

    public function approve(Request $request, $id)
    {
        $puskesmasId = $this->getPuskesmasId();

        $pengukuran = Pengukuran::whereHas('balita.posyandu', function ($q) use ($puskesmasId) {
            $q->where('puskesmas_id', $puskesmasId);
        })->findOrFail($id);

        $pengukuran->update([
            'status_validasi' => 'approved',
            'catatan_validator' => $request->input('catatan_validator'),
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);

        // KRITIS-02: signed URL untuk Portal Ibu kini DIKIRIM ke petugas
        // untuk diteruskan ke Ibu (tombol Salin/WhatsApp), bukan dibuang.
        // TTL dari config('portal.link_ttl_days'); orang_tua disertakan
        // agar server memvalidasi kepemilikan balita (anti-IDOR).
        $ttlDays = (int) config('portal.link_ttl_days', 7);
        $signedUrl = \Illuminate\Support\Facades\URL::temporarySignedRoute(
            'portal-ibu.home',
            now()->addDays($ttlDays),
            ['balita' => $pengukuran->balita_id, 'orang_tua' => $pengukuran->balita->orang_tua_id]
        );

        // TINGGI-02 (short-term): link wa.me dengan pesan berisi URL portal
        $waDigits = preg_replace('/[^0-9]/', '', $pengukuran->balita->orangTua->no_hp_whatsapp ?? '');
        if ($waDigits !== '') {
            if (!str_starts_with($waDigits, '62')) {
                $waDigits = str_starts_with($waDigits, '0') ? '62' . substr($waDigits, 1) : '62' . $waDigits;
            }
        }
        $waMessage = "Assalamualaikum Bu, data pengukuran anak Ibu telah divalidasi. "
            . "Silakan buka portal NutriGen berikut (berlaku {$ttlDays} hari): " . $signedUrl;

        // TINGGI-02: kirim pesan & catat ke notification_logs.
        // Driver default 'log' -> tidak butuh akun/token/nomor (demo-safe).
        // Kirim asli tinggal dinyalakan via WA_DRIVER + token di .env.
        $notification = null;
        if ($waDigits !== '') {
            $notification = $this->whatsAppService->send(
                $pengukuran->balita->orang_tua_id,
                $pengukuran->id,
                $waDigits,
                $waMessage
            );
        }

        $portalLink = [
            'url' => $signedUrl,
            'ttl_days' => $ttlDays,
            'wa_url' => $waDigits !== '' ? 'https://wa.me/' . $waDigits . '?text=' . rawurlencode($waMessage) : null,
            'child_name' => $pengukuran->balita->nama,
            'notif_log_id' => $notification['log_id'] ?? null,
            'notif_status' => $notification['status'] ?? null,
        ];

        return redirect()
            ->route('puskesmas.validasi')
            ->with('success', 'Data balita berhasil disetujui dan divalidasi.')
            ->with('portal_link', $portalLink);
    }

    public function reject(Request $request, $id)
    {
        $puskesmasId = $this->getPuskesmasId();

        $pengukuran = Pengukuran::whereHas('balita.posyandu', function ($q) use ($puskesmasId) {
            $q->where('puskesmas_id', $puskesmasId);
        })->findOrFail($id);

        $pengukuran->update([
            'status_validasi' => 'rejected',
            'catatan_validator' => $request->input('catatan_validator', 'Data tidak valid, mohon perbaiki.'),
            'validated_by' => Auth::id(),
            'validated_at' => now(),
        ]);

        // Get updated stats to pass back to frontend
        $stats = $this->statisticsService->getValidationQueueStats(
            $puskesmasId,
            $request->input('posyandu_id') ?: null
        );

        return redirect()->route('puskesmas.validasi')->with('success', 'Data berhasil ditolak dan dikembalikan ke Kader untuk revisi.');
    }

    public function balita(Request $request)
    {
        $puskesmasId = $this->getPuskesmasId();

        // Eager Loading: Hindari N+1 untuk orangTua, posyandu, dan pengukurans
        $posyandus = Posyandu::where('puskesmas_id', $puskesmasId)->get(['id', 'nama'])->toArray();

        $q = $request->input('q');
        $posyanduFilter = $request->input('posyandu_id');
        $statusGizi = $request->input('status_gizi');

        $query = Balita::with(['orangTua', 'posyandu', 'pengukurans' => function($query) {
            $query->orderBy('tanggal_ukur', 'desc'); // To easily get latest status_gizi
        }])
        ->whereHas('posyandu', fn($q) => $q->where('puskesmas_id', $puskesmasId));

        if ($q) {
            $query->where(function($subq) use ($q) {
                $subq->where('nama', 'like', "%{$q}%")
                     ->orWhere('nik', 'like', "%{$q}%");
            });
        }

        if ($posyanduFilter) {
            $query->whereHas('posyandu', function($subq) use ($posyanduFilter) {
                $subq->whereKey($posyanduFilter);
            });
        }

        $balitas = $query->when($statusGizi, function($q) use ($statusGizi) {
            $statusMap = [
                'normal' => 'Normal',
                'kurang' => 'Kurang',
                'risiko' => 'Risiko',
                'stunting' => 'Stunting'
            ];
            $expected = $statusMap[strtolower($statusGizi)] ?? $statusGizi;
            return $q->whereHas('latestPengukuran', function($subq) use ($expected) {
                $subq->whereRaw('LOWER(status_gizi) = ?', [strtolower($expected)]);
            });
        })->get();

        $children = $balitas->map(function($b) {
            $posyanduName = $b->posyandu->nama ?? '-';

            $formattedPengukurans = $b->pengukurans->sortByDesc('tanggal_ukur')->map(function($p) {
                return [
                    'id'              => $p->id,
                    'umur_bulan'      => $p->umur_bulan,
                    'berat_badan'     => $p->berat_badan,
                    'tinggi_badan'    => $p->tinggi_badan,
                    'z_score_bb_u'    => $p->z_score_bbu,
                    'z_score_tb_u'    => $p->z_score_tbu,
                    'status_gizi'     => $p->status_gizi,
                    'status_validasi' => $p->status_validasi,
                    'created_at'      => Carbon::parse($p->tanggal_ukur)->format('Y-m-d H:i:s'),
                ];
            })->values()->toArray();

            $latestPengukuran = count($formattedPengukurans) > 0 ? $formattedPengukurans[0] : null;
            $rawStatus = $latestPengukuran ? $latestPengukuran['status_gizi'] : 'Belum Diukur';
            $statusLabel = ucwords($rawStatus);
            $checkStatus = strtolower($rawStatus);

            $statusType = 'success'; // Default normal
            if(in_array($checkStatus, ['kurang', 'kurus', 'risiko lebih', 'risiko'])) $statusType = 'warning';
            elseif(in_array($checkStatus, ['stunting', 'gizi buruk', 'sangat kurus', 'obesitas'])) $statusType = 'danger';
            elseif($checkStatus === 'belum diukur') $statusType = 'slate';

            return [
                'id'            => $b->id,
                'nik'           => $b->nik,
                'nama'          => $b->nama,
                'tanggal_lahir' => Carbon::parse($b->tanggal_lahir)->format('Y-m-d'),
                'jenis_kelamin' => $b->jenis_kelamin,
                'berat_lahir'   => $b->berat_lahir,
                'tinggi_lahir'  => $b->panjang_lahir,
                'ibu'           => ['nama' => $b->orangTua->nama_ibu ?? '-', 'no_hp_wa' => $b->orangTua->no_hp_whatsapp ?? '-'],
                'posyandu'      => ['nama' => $posyanduName],
                'pengukurans'   => $formattedPengukurans,
                'statusLabel'   => $statusLabel,
                'statusType'    => $statusType,
            ];
        })->toArray();

        return view('puskesmas.balita', ['children' => $children, 'posyandus' => $posyandus, 'filters' => $request->all()]);
    }

    public function posyandu(Request $request)
    {
        $puskesmasId = $this->getPuskesmasId();
        $q = $request->input('q');

        $query = Posyandu::where('puskesmas_id', $puskesmasId)
            ->with(['kaders.user'])
            ->withCount(['pengukurans as diukur_bulan_ini' => function($q) {
                $q->whereMonth('pengukurans.tanggal_ukur', Carbon::now()->month)
                  ->whereYear('pengukurans.tanggal_ukur', Carbon::now()->year);
            }]);

        if ($q) {
            $query->where(function($subq) use ($q) {
                $subq->where('nama', 'like', "%{$q}%")
                     ->orWhere('desa_kelurahan', 'like', "%{$q}%");
            });
        }

        $posyandusCollection = $query->get();

        $posyandus = $posyandusCollection->map(function($p) {
            $kaders = $p->kaders->map(function($k) {
                return [
                    'id' => $k->id,
                    'nama' => $k->user->name ?? $k->nama,
                    'nik' => '-', // NIK Kader dihilangkan dari tabel V2
                    'no_hp' => $k->no_hp,
                    'aktivitas_bulan_ini' => $k->pengukurans()->whereMonth('tanggal_ukur', Carbon::now()->month)->count(),
                    'terakhir_aktif' => Carbon::now()->format('Y-m-d'),
                ];
            })->toArray();

            $totalBalita = Balita::where('posyandu_id', $p->id)->count();

            return [
                'id'                    => $p->id,
                'nama'                  => $p->nama,
                'desa'                  => $p->desa_kelurahan,
                'alamat'                => $p->alamat ?? '-',
                'balita_count'          => $totalBalita,
                'kader_count'           => $p->kaders->count(),
                'has_jadwal_this_month' => false, // Jadwal di-drop pada V2
                'stats'                 => [
                    'total_balita'     => $totalBalita,
                    'diukur_bulan_ini' => $p->diukur_bulan_ini,
                ],
                'kaders' => $kaders,
                'jadwals' => [],
            ];
        })->toArray();

        $selectedPosyandu = null;
        if ($request->input('id')) {
            $selectedPosyandu = collect($posyandus)->firstWhere('id', (int) $request->input('id'));
        }
        if (! $selectedPosyandu && count($posyandus) > 0) {
            $selectedPosyandu = $posyandus[0];
        }

        return view('puskesmas.posyandu', ['posyandus' => $posyandus, 'selectedPosyandu' => $selectedPosyandu, 'filters' => $request->all()]);
    }

    public function storePosyandu(StorePosyanduRequest $request)
    {
        $puskesmasId = $this->getPuskesmasId();

        $validated = $request->validated();

        Posyandu::create(array_merge($validated, ['puskesmas_id' => $puskesmasId]));

        return redirect()->route('puskesmas.posyandu')->with('success', 'Posyandu berhasil ditambahkan.');
    }

    public function storeKader(StoreKaderRequest $request, $id)
    {
        $puskesmasId = $this->getPuskesmasId();

        $posyandu = Posyandu::where('puskesmas_id', $puskesmasId)->findOrFail($id);
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $posyandu) {
            $user = User::create([
                'name' => $validated['nama'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'kader',
            ]);

            $posyandu->kaders()->create([
                'user_id' => $user->id,
                'nama' => $validated['nama'],
                'no_hp' => $validated['no_hp'] ?? null,
            ]);
        });

        return redirect()->route('puskesmas.posyandu', ['id' => $posyandu->id])
            ->with('success', 'Kader berhasil ditambahkan.');
    }

    public function laporan(Request $request)
    {
        $puskesmasId = $this->getPuskesmasId();

        $bulan = $request->input('bulan', Carbon::now()->format('m'));
        $tahun = $request->input('tahun', Carbon::now()->format('Y'));
        $posyanduId = $request->input('posyandu_id', 'semua');

        $posyandus = Posyandu::where('puskesmas_id', $puskesmasId)->get(['id', 'nama'])->toArray();

        // Retrieve base stats via StatisticsService (approved data only)
        $reportStats = $this->statisticsService->getReportStats($puskesmasId, (int)$bulan, (int)$tahun);

        // Additional pending validation count (still uses pending status)
        $pendingValidasi = Pengukuran::whereHas('balita.posyandu', function ($q) use ($puskesmasId) {
            $q->where('puskesmas_id', $puskesmasId);
        })->where('status_validasi', 'pending')->count();

        $stats = [
            'total_balita' => $reportStats['total'],
            'normal' => $reportStats['normal'],
            'berisiko' => $reportStats['risiko'] + $reportStats['stunting'],
            'pending_validasi' => $pendingValidasi,
            'sudah_validasi' => $reportStats['total'], // approved count equals total approved for month
        ];

        $reports = [];
        if ($posyanduId === 'semua') {
            foreach ($posyandus as $p) {
                $pStats = $this->dashboardService->getKaderDashboardStats($p['id'], (int) $bulan, (int) $tahun);
                $reports[] = [
                    'nama_posyandu' => $p['nama'],
                    'sasaran' => $pStats['total_balita'],
                    'diukur' => $pStats['bulan_ini'],
                    'normal' => $pStats['normal'],
                    'berisiko' => $pStats['risiko'] + $pStats['stunting'],
                    'persentase_hadir' => $pStats['total_balita'] > 0 ? round(($pStats['bulan_ini']/$pStats['total_balita'])*100).'%' : '0%'
                ];
            }
        } else {
            $reports[] = [
                'nama_posyandu' => collect($posyandus)->firstWhere('id', (int)$posyanduId)['nama'] ?? 'Posyandu',
                'sasaran' => $stats['total_balita'],
                'diukur' => $reportStats['total'],
                'normal' => $stats['normal'],
                'berisiko' => $stats['berisiko'],
                'persentase_hadir' => $stats['total_balita'] > 0 ? round(($reportStats['total']/$stats['total_balita'])*100).'%' : '0%'
            ];
        }

        // Distribution already provided by reportStats
        $distTotal = $reportStats['normal'] + $reportStats['risiko'] + $reportStats['stunting'];
        $distribution = [
            'normal' => $reportStats['normal'],
            'pct_normal' => $distTotal > 0 ? round(($reportStats['normal'] / $distTotal) * 100) : 0,
            'stunting' => $reportStats['risiko'] + $reportStats['stunting'], // treated as risk total
            'pct_stunting' => $distTotal > 0 ? round((($reportStats['risiko'] + $reportStats['stunting']) / $distTotal) * 100) : 0,
        ];

        $topBerisiko = $this->dashboardService->getTopBerisiko($puskesmasId, $posyanduId, (int) $bulan, (int) $tahun);
        $rentang = $request->input('rentang', 6);
        $trends = $this->dashboardService->getTrend6Bulan($puskesmasId, $posyanduId, (int) $bulan, (int) $tahun, (int) $rentang);

        $filters = [
            'bulan' => str_pad($bulan, 2, '0', STR_PAD_LEFT),
            'tahun' => $tahun,
            'posyandu_id' => $posyanduId,
            'rentang' => $rentang
        ];

        return view('puskesmas.laporan', compact('stats', 'reports', 'distribution', 'trends', 'filters', 'posyandus', 'topBerisiko'));
    }

    public function pengaturan()
    {
        $puskesmas = Auth::user()?->puskesmas;

        return view('puskesmas.pengaturan', [
            'puskesmas' => [
                'id'             => $puskesmas->id ?? null,
                'nama'           => $puskesmas->nama ?? 'Puskesmas',
                'kode_registrasi' => $puskesmas->kode_faskes ?? '-',
                'alamat'         => $puskesmas->alamat ?? '-',
                'logo_url'       => null,
                'jumlah_posyandu'=> $puskesmas ? $puskesmas->posyandus()->count() : 0,
            ],
            'user' => [
                'nama'   => Auth::user()->name ?? 'Admin',
                'nip'    => '-', // Dihilangkan dari arsitektur V2
                'email'  => Auth::user()->email ?? '-',
                'no_hp'  => '-',
            ]
        ]);
    }

    public function updatePengaturan(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'nullable|string'
        ]);

        $user = Auth::user();
        $puskesmas = $user->puskesmas;

        if ($puskesmas) {
            $puskesmas->update([
                'nama' => $request->nama,
                'alamat' => $request->alamat
            ]);
        }

        return redirect()->route('puskesmas.pengaturan')->with('success', 'Profil institusi berhasil diperbarui.');
    }

    public function petugas()
    {
        $user = Auth::user();

        return view('puskesmas.pengaturan_petugas', [
            'user' => [
                'nama' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-',
                'updated_at' => $user->updated_at ? $user->updated_at->translatedFormat('d F Y, H:i') . ' WIB' : '-',
            ],
            'puskesmas' => [
                'nama' => $user->puskesmas->nama ?? 'Puskesmas',
            ]
        ]);
    }

    public function showBalita($id)
    {
        $puskesmasId = $this->getPuskesmasId();

        $b = Balita::with(['orangTua', 'posyandu', 'latestPengukuran', 'pengukurans' => function($q) {
            $q->orderBy('tanggal_ukur', 'desc')->orderBy('id', 'desc');
        }])->whereHas('posyandu', function($q) use ($puskesmasId) {
            $q->where('puskesmas_id', $puskesmasId);
        })->findOrFail($id);

        $this->authorize('view', $b);

        $ageDiff = Carbon::parse($b->tanggal_lahir)->diff(Carbon::now());
        $ageStr = $ageDiff->y > 0 ? $ageDiff->y . ' Tahun ' . $ageDiff->m . ' Bulan' : $ageDiff->m . ' Bulan';

        $measurementsList = $b->pengukurans->values();
        $totalMeasures = $measurementsList->count();

        $measurements = $measurementsList->map(function($p, $index) use ($measurementsList, $totalMeasures, $b) {
            $statusType = match(strtolower($p->status_gizi)) {
                'normal' => 'success',
                'risiko', 'kurang', 'kurus', 'risiko lebih' => 'warning',
                'stunting', 'gizi buruk', 'sangat kurus', 'obesitas' => 'danger',
                default => 'success'
            };

            $weightTrend = null;
            $heightTrend = null;

            if ($index + 1 < $totalMeasures) {
                $prev = $measurementsList[$index + 1];
                if ($prev->berat_badan !== null && $p->berat_badan !== null) {
                    $weightTrend = round($p->berat_badan - $prev->berat_badan, 2);
                }
                if ($prev->tinggi_badan !== null && $p->tinggi_badan !== null) {
                    $heightTrend = round($p->tinggi_badan - $prev->tinggi_badan, 1);
                }
            }

            $measureDate = Carbon::parse($p->tanggal_ukur);
            $birthDate = Carbon::parse($b->tanggal_lahir);
            $ageDiff = $birthDate->diff($measureDate);
            $ageAtMeasure = $p->umur_bulan
                ? $p->umur_bulan . ' Bulan'
                : ($ageDiff->y > 0 ? $ageDiff->y . ' Thn ' . $ageDiff->m . ' Bln' : $ageDiff->m . ' Bulan');

            return [
                'id' => $p->id,
                'date' => $measureDate->translatedFormat('d M Y'),
                'raw_date' => $measureDate->format('Y-m-d'),
                'age_at_measure' => $ageAtMeasure,
                'weight' => $p->berat_badan,
                'weight_trend' => $weightTrend,
                'height' => $p->tinggi_badan,
                'height_trend' => $heightTrend,
                'z_score_bbu' => $p->z_score_bbu ? round($p->z_score_bbu, 2) : null,
                'z_score_tbu' => $p->z_score_tbu ? round($p->z_score_tbu, 2) : null,
                'head_circ' => $p->lingkar_kepala ? round($p->lingkar_kepala, 1) : null,
                'asi_eksklusif' => (bool)$p->asi_eksklusif,
                'status_kenaikan' => $p->status_kenaikan,
                'status' => $p->status_gizi,
                'status_type' => $statusType,
                'status_validasi' => $p->status_validasi,
                'catatan_validator' => $p->catatan_validator,
                'catatan_kader' => $p->catatan_kader,
            ];
        })->toArray();

        $latestMeasure = count($measurements) > 0 ? $measurements[0] : null;

        $alamatRaw = $b->orangTua->alamat ?? '';
        $alamatData = json_decode($alamatRaw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($alamatData)) {
            $desa = $alamatData['desa'] ?? '';
            $kecamatan = $alamatData['kecamatan'] ?? ($b->orangTua->kecamatan ?? '');
        } else {
            $desa = $alamatRaw;
            $kecamatan = $b->orangTua->kecamatan ?? '';
        }

        $data = [
            'balitaId'       => $b->id,
            'childName'      => $b->nama,
            'gender'         => $b->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
            'age'            => $ageStr,
            'birthDate'      => Carbon::parse($b->tanggal_lahir)->translatedFormat('d F Y'),
            'nik'            => $b->nik,
            'noBpjs'         => $b->no_bpjs,
            'birthWeight'    => $b->berat_lahir,
            'birthLength'    => $b->panjang_lahir,
            'birthHeadCirc'  => $b->lingkar_kepala_lahir,
            'noKk'           => $b->orangTua->no_kk ?? null,
            'motherName'     => $b->orangTua->nama_ibu ?? '-',
            'motherNik'      => $b->orangTua->nik_ibu ?? null,
            'motherJob'      => $b->orangTua->pekerjaan_ibu ?? null,
            'motherPhone'    => $b->orangTua->no_hp_whatsapp ?? '-',
            'fatherName'     => $b->orangTua->nama_ayah ?? null,
            'fatherNik'      => $b->orangTua->nik_ayah ?? null,
            'fatherJob'      => $b->orangTua->pekerjaan_ayah ?? null,
            'posyanduName'   => $b->posyandu->nama ?? '-',
            'address'        => $desa ?: '-',
            'addressSub'     => $kecamatan ?: null,
            'status'         => $latestMeasure ? ucwords($latestMeasure['status']) : 'Belum Ada',
            'status_type'    => $latestMeasure ? $latestMeasure['status_type'] : 'success',
            'measurements'   => $measurements,
            'latestMeasure'  => $latestMeasure,
            'child'          => $b
        ];

        return view('puskesmas.balita-show', $data);
    }

    public function updatePetugas(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
        ]);

        $user->update([
            'name' => $request->nama,
            'email' => $request->email,
        ]);

        return redirect()->route('puskesmas.pengaturan.petugas')->with('success', 'Profil petugas berhasil diperbarui.');
    }

    public function keamanan()
    {
        return view('puskesmas.pengaturan_keamanan');
    }

    public function updateKeamanan(UpdateKeamananRequest $request)
    {
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('puskesmas.pengaturan.keamanan')->with('success', 'Kata sandi berhasil diperbarui.');
    }

    public function notifikasi()
    {
        return view('puskesmas.pengaturan_notifikasi');
    }

    public function updateNotifikasi(Request $request)
    {
        // Notifikasi is mostly a mock UI for now.
        return redirect()->route('puskesmas.pengaturan.notifikasi')->with('success', 'Preferensi notifikasi berhasil disimpan.');
    }
}
