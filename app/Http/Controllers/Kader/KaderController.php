<?php

namespace App\Http\Controllers\Kader;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\DashboardService;
use App\Services\GrowthCalculationService;
use App\Services\RecommendationService;
use App\Services\StatisticsService;
use App\Models\Balita;
use App\Models\Pengukuran;
use App\Models\Posyandu;
use App\Models\OrangTua;
use App\Models\User;
use App\Http\Requests\Kader\StoreBalitaRequest;
use App\Http\Requests\Kader\UpdateBalitaRequest;
use App\Http\Requests\Kader\StorePengukuranRequest;
use App\Http\Requests\Kader\StoreJadwalRequest;
use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class KaderController extends Controller
{
    public function __construct(
        protected DashboardService $dashboardService,
        protected GrowthCalculationService $growthService,
        protected RecommendationService $recommendationService,
        protected StatisticsService $statisticsService
    ) {}

    private function getKaderPosyanduId(): int
    {
        $posyanduId = Auth::user()?->kader?->posyandu_id;
        if (!$posyanduId) {
            abort(403, 'Akses ditolak: Anda tidak memiliki data Kader/Posyandu yang valid.');
        }
        return $posyanduId;
    }

    private function formatDisplayStatus(?string $status, ?string $statusValidasi = null): string
    {
        if (!$status || strtolower($status) === 'belum ada') {
            return 'Belum Diukur';
        }

        // Jika BELUM divalidasi oleh Puskesmas (status_validasi == 'pending'):
        // Sesuai standar Buku KIA / KMS, kader belum mengeluarkan vonis medis klinis
        if ($statusValidasi === 'pending' || $statusValidasi === null) {
            return match(strtolower($status)) {
                'stunting', 'pendek', 'sangat pendek' => 'Perlu Konfirmasi Gizi (TB Rendah)',
                'risiko', 'kurang', 'gizi kurang' => 'Garis Kuning (Perlu Pemantauan)',
                'normal', 'gizi baik' => 'Gizi Baik (Sesuai KMS)',
                default => 'Menunggu Validasi Puskesmas'
            };
        }

        if ($statusValidasi === 'rejected') {
            return 'Perlu Revisi Kader';
        }

        // Jika SUDAH divalidasi oleh Dokter/Ahli Gizi Puskesmas (status_validasi == 'approved'):
        // Tampilkan diagnosa klinis resmi
        return match(strtolower($status)) {
            'stunting' => 'Stunting',
            'risiko' => 'Risiko Stunting',
            'kurang', 'gizi kurang' => 'Gizi Kurang',
            'gizi buruk' => 'Gizi Buruk',
            'obesitas' => 'Obesitas',
            'normal', 'gizi baik' => 'Normal',
            default => ucfirst($status)
        };
    }

    public function dashboard()
    {
        $posyanduId = $this->getKaderPosyanduId();
        
        $priorityBalitas = Balita::where('posyandu_id', $posyanduId)
            ->where(function ($query) {
                $query->doesntHave('pengukurans')
                      ->orWhereHas('latestPengukuran', function ($q) {
                          $q->whereIn('status_gizi', ['Stunting', 'Risiko', 'Kurang', 'stunting', 'risiko', 'kurang']);
                      });
            })
            ->with(['latestPengukuran', 'orangTua'])
            ->take(5)
            ->get();

        $priorityChildren = $priorityBalitas->map(function ($b) {
            $latest = $b->latestPengukuran;
            $age = Carbon::parse($b->tanggal_lahir)->diff(Carbon::now());
            
            $status = $latest ? $latest->status_gizi : 'Belum Ada';
            $statusValidasi = $latest ? $latest->status_validasi : null;
            $statusType = match(strtolower($status)) {
                'stunting' => 'danger',
                'risiko', 'kurang' => 'warning',
                'normal' => 'success',
                default => 'warning'
            };

            $shortStatus = match(strtolower($status)) {
                'stunting', 'pendek' => 'Konfirmasi TB',
                'risiko', 'kurang' => 'Pantauan Gizi',
                'normal' => 'Gizi Baik',
                default => 'Belum Diukur'
            };

            return (object) [
                'id' => $b->id,
                'name' => $b->nama,
                'gender' => $b->jenis_kelamin,
                'mother' => $b->orangTua->nama_ibu ?? '-',
                'avatar' => null,
                'age' => $age->y . ' Thn ' . $age->m . ' Bln',
                'status' => $this->formatDisplayStatus($status, $statusValidasi),
                'shortStatus' => $shortStatus,
                'statusType' => $statusType,
            ];
        })->toArray();

        $ds = $this->statisticsService->getKaderDashboardStats($posyanduId);

        // Fetch real upcoming schedule from database
        Carbon::setLocale('id');
        $today = Carbon::today('Asia/Jakarta');
        
        $upcomingJadwal = Jadwal::where('posyandu_id', $posyanduId)
            ->where('tanggal', '>=', $today)
            ->orderBy('tanggal', 'asc')
            ->orderBy('waktu_mulai', 'asc')
            ->first();

        // Fallback to latest schedule if none upcoming
        $nextJadwal = $upcomingJadwal ?? Jadwal::where('posyandu_id', $posyanduId)->latest('tanggal')->first();

        $jadwalTerdekat = null;
        if ($nextJadwal) {
            $tgl = Carbon::parse($nextJadwal->tanggal, 'Asia/Jakarta')->startOfDay();
            $diffDays = (int) $today->diffInDays($tgl, false);
            
            $countdown = $tgl->isToday() ? 'Hari Ini' : ($diffDays === 1 ? 'Besok' : ($diffDays > 1 ? $diffDays . ' Hari Lagi' : 'Selesai'));
            $statusType = $tgl->isToday() ? 'today' : ($tgl->isPast() && !$tgl->isToday() ? 'past' : 'upcoming');

            $jadwalTerdekat = [
                'id' => $nextJadwal->id,
                'judul' => $nextJadwal->judul,
                'tanggal' => $tgl->translatedFormat('d F Y'),
                'tgl_nomor' => $tgl->format('d'),
                'tgl_bulan' => strtoupper($tgl->translatedFormat('M')),
                'hari' => $tgl->translatedFormat('l'),
                'waktu' => substr($nextJadwal->waktu_mulai, 0, 5) . ' - ' . substr($nextJadwal->waktu_selesai, 0, 5) . ' WIB',
                'lokasi' => $nextJadwal->lokasi,
                'catatan' => $nextJadwal->catatan,
                'countdown' => $countdown,
                'status_type' => $statusType
            ];
        }

        $data = [
            'kaderName' => Auth::user()?->kader?->nama ?? Auth::user()->name ?? 'Kader',
            'statTotal' => $ds['total_balita'],
            'statSudah' => $ds['bulan_ini'],
            'statBelum' => max(0, $ds['total_balita'] - $ds['bulan_ini']),
            'statPerlu' => count($priorityChildren),
            'statRevisi' => $ds['perlu_revisi'] ?? 0,
            'priorityChildren' => $priorityChildren,
            'jadwalTerdekat' => $jadwalTerdekat,
            'activityName' => $jadwalTerdekat['judul'] ?? 'Belum ada jadwal',
            'activityTime' => $jadwalTerdekat['waktu'] ?? '-',
            'activityLocation' => Auth::user()?->kader?->posyandu?->nama ?? 'Posyandu',
            'activityAddress' => $jadwalTerdekat['lokasi'] ?? (Auth::user()?->kader?->posyandu?->alamat ?? '-'),
        ];

        return view('kader.dashboard', $data);
    }

    public function daftarBalita(Request $request)
    {
        $posyanduId = $this->getKaderPosyanduId();
        
        $q = $request->input('q');
        $statusGizi = $request->input('status_gizi');
        $filter = $request->input('filter');

        $query = Balita::where('posyandu_id', $posyanduId)
            ->with(['orangTua', 'latestPengukuran', 'pengukurans']);

        if ($q) {
            $query->where(function($subq) use ($q) {
                $subq->where('nama', 'like', "%{$q}%")
                     ->orWhere('nik', 'like', "%{$q}%");
            });
        }

        if ($statusGizi) {
            $query->whereHas('latestPengukuran', function($subq) use ($statusGizi) {
                $statusMap = [
                    'normal' => 'Normal',
                    'kurang' => 'Risiko',
                    'stunting' => 'Stunting'
                ];
                $expected = $statusMap[strtolower($statusGizi)] ?? $statusGizi;
                $subq->where('status_gizi', $expected);
            });
        }

        if ($filter) {
            if ($filter === 'belum_diukur') {
                $thisMonth = Carbon::now()->month;
                $thisMonthYear = Carbon::now()->year;
                $query->whereDoesntHave('pengukurans', function ($subq) use ($thisMonth, $thisMonthYear) {
                    $subq->whereMonth('tanggal_ukur', $thisMonth)
                         ->whereYear('tanggal_ukur', $thisMonthYear);
                });
            } elseif ($filter === 'absen_bulan_lalu') {
                $lastMonth = Carbon::now()->subMonth()->month;
                $lastMonthYear = Carbon::now()->subMonth()->year;
                $query->whereDoesntHave('pengukurans', function ($subq) use ($lastMonth, $lastMonthYear) {
                    $subq->whereMonth('tanggal_ukur', $lastMonth)
                         ->whereYear('tanggal_ukur', $lastMonthYear);
                });
            } elseif ($filter === 'bayi_6_bln') {
                $sixMonthsAgo = Carbon::now()->subMonths(6);
                $query->where('tanggal_lahir', '>=', $sixMonthsAgo);
            } elseif ($filter === 'selesai') {
                $thisMonth = Carbon::now()->month;
                $thisMonthYear = Carbon::now()->year;
                $query->whereHas('pengukurans', function ($subq) use ($thisMonth, $thisMonthYear) {
                    $subq->whereMonth('tanggal_ukur', $thisMonth)
                         ->whereYear('tanggal_ukur', $thisMonthYear);
                });
            } elseif ($filter === 'ditolak' || $filter === 'revisi') {
                $query->whereHas('pengukurans', function ($subq) {
                    $subq->where('status_validasi', 'rejected');
                });
            }
        }

        $basePosyanduQuery = Balita::where('posyandu_id', $posyanduId);
        $thisMonth = Carbon::now()->month;
        $thisMonthYear = Carbon::now()->year;
        $lastMonth = Carbon::now()->subMonth()->month;
        $lastMonthYear = Carbon::now()->subMonth()->year;
        $sixMonthsAgo = Carbon::now()->subMonths(6);

        $filterCounts = [
            'belum_diukur'     => (clone $basePosyanduQuery)->whereDoesntHave('pengukurans', function ($subq) use ($thisMonth, $thisMonthYear) {
                $subq->whereMonth('tanggal_ukur', $thisMonth)->whereYear('tanggal_ukur', $thisMonthYear);
            })->count(),
            'absen_bulan_lalu' => (clone $basePosyanduQuery)->whereDoesntHave('pengukurans', function ($subq) use ($lastMonth, $lastMonthYear) {
                $subq->whereMonth('tanggal_ukur', $lastMonth)->whereYear('tanggal_ukur', $lastMonthYear);
            })->count(),
            'bayi_6_bln'       => (clone $basePosyanduQuery)->where('tanggal_lahir', '>=', $sixMonthsAgo)->count(),
            'selesai'          => (clone $basePosyanduQuery)->whereHas('pengukurans', function ($subq) use ($thisMonth, $thisMonthYear) {
                $subq->whereMonth('tanggal_ukur', $thisMonth)->whereYear('tanggal_ukur', $thisMonthYear);
            })->count(),
            'ditolak'          => (clone $basePosyanduQuery)->whereHas('pengukurans', function ($subq) {
                $subq->where('status_validasi', 'rejected');
            })->count(),
        ];

        $balitas = $query->with(['orangTua', 'pengukurans'])->get();

        $formattedBalitas = $balitas->map(function($b) {
            $latest = $b->latestPengukuran;
            $age = Carbon::parse($b->tanggal_lahir)->diff(Carbon::now());
            
            $status = $latest ? $latest->status_gizi : 'Belum Ada';
            $statusType = match(strtolower($status)) {
                'stunting' => 'danger',
                'risiko', 'kurang' => 'warning',
                'normal' => 'success',
                default => 'warning'
            };

            $rejectedMeasurement = $b->pengukurans->where('status_validasi', 'rejected')->first();
            $status_validasi = $rejectedMeasurement ? 'rejected' : ($latest ? $latest->status_validasi : 'pending');

            if ($rejectedMeasurement) {
                $statusType = 'danger';
            }

            $isGirl = in_array(strtolower($b->jenis_kelamin ?? ''), ['p', 'perempuan', 'female']);
            $genderLabel = $isGirl ? 'Perempuan' : 'Laki-laki';

            $maskedNik = $b->nik;
            if ($b->nik && strlen($b->nik) >= 12) {
                $maskedNik = substr($b->nik, 0, 6) . '*********' . substr($b->nik, -4);
            }

            $bbTbText = '-';
            if ($latest && $latest->berat_badan > 0 && $latest->tinggi_badan > 0) {
                $bbTbText = number_format($latest->berat_badan, 1, ',', '.') . ' kg / ' . number_format($latest->tinggi_badan, 1, ',', '.') . ' cm';
            } elseif ($latest && $latest->berat_badan > 0) {
                $bbTbText = number_format($latest->berat_badan, 1, ',', '.') . ' kg';
            } elseif ($b->berat_lahir > 0) {
                $bbTbText = number_format($b->berat_lahir, 1, ',', '.') . ' kg';
            }

            return [
                'id' => $b->id,
                'name' => $b->nama,
                'age' => $age->y > 0 ? $age->y . ' Thn ' . $age->m . ' Bln' : $age->m . ' Bln',
                'gender' => $b->jenis_kelamin,
                'gender_label' => $genderLabel,
                'nik' => $b->nik,
                'masked_nik' => $maskedNik,
                'mother' => $b->orangTua->nama_ibu ?? '-',
                'last_measure' => $latest ? Carbon::parse($latest->tanggal_ukur)->translatedFormat('d M Y') : 'Belum Ada',
                'bb_tb' => $bbTbText,
                'status' => $this->formatDisplayStatus($status, $status_validasi),
                'status_type' => $statusType,
                'status_validasi' => $status_validasi,
                'rejection_note' => $rejectedMeasurement?->catatan_validator,
            ];
        });

        $ds = $this->statisticsService->getKaderDashboardStats($posyanduId);

        return view('kader.daftar-balita', [
            'balitas' => $formattedBalitas,
            'filters' => $request->all(),
            'filterCounts' => $filterCounts,
            'statSelesai' => $ds['bulan_ini'],
            'statBelum' => $ds['total_balita'] - $ds['bulan_ini'],
            'posyanduName' => Auth::user()?->kader?->posyandu?->nama ?? 'Posyandu'
        ]);
    }

    public function createBalita()
    {
        $kaderPosyandu = Auth::user()?->kader?->posyandu;
        return view('kader.daftar-balita-baru', [
            'posyanduName' => $kaderPosyandu->nama ?? 'Posyandu'
        ]);
    }

    public function simpanBalita(StoreBalitaRequest $request)
    {
        $posyanduId = $this->getKaderPosyanduId();

        $alamatJson = json_encode([
            'desa'      => $request->desa,
            'kecamatan' => $request->kecamatan
        ]);

        // Auto-create OrangTua User if not exists
        $userIbu = User::firstOrCreate(
            ['email' => $request->no_hp . '@nutrigen.com'],
            ['name' => $request->nama_ibu, 'password' => Hash::make('password'), 'role' => 'ibu']
        );

        $orangTua = OrangTua::updateOrCreate(
            ['user_id' => $userIbu->id],
            [
                'no_kk'          => $request->no_kk,
                'nama_ibu'       => $request->nama_ibu,
                'nik_ibu'        => $request->nik_ibu,
                'pekerjaan_ibu'  => $request->pekerjaan_ibu,
                'nama_ayah'      => $request->nama_ayah ?: '-',
                'nik_ayah'       => $request->nik_ayah,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'no_hp_whatsapp' => $request->no_hp,
                'alamat'         => $alamatJson,
            ]
        );

        Balita::create([
            'orang_tua_id'         => $orangTua->id,
            'posyandu_id'          => $posyanduId,
            'nik'                  => $request->nik,
            'no_bpjs'              => $request->no_bpjs,
            'nama'                 => $request->nama,
            'jenis_kelamin'        => $request->jenis_kelamin,
            'tanggal_lahir'        => $request->tanggal_lahir,
            'berat_lahir'          => $request->berat_lahir,
            'panjang_lahir'        => $request->panjang_lahir,
            'lingkar_kepala_lahir' => $request->lingkar_kepala_lahir,
        ]);

        return redirect()->route('balita.index')->with('success', 'Data Balita berhasil disimpan.');
    }

    public function editBalita($id)
    {
        $posyanduId = $this->getKaderPosyanduId();
        $balita = Balita::with('orangTua')->where('posyandu_id', $posyanduId)->findOrFail($id);
        
        $alamatRaw = $balita->orangTua->alamat ?? '';
        $alamatData = json_decode($alamatRaw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($alamatData)) {
            $desa = $alamatData['desa'] ?? '';
            $kecamatan = $alamatData['kecamatan'] ?? '';
        } else {
            $desa = $alamatRaw;
            $kecamatan = '';
        }

        return view('kader.daftar-balita-baru', [
            'isEdit'           => true,
            'balitaId'         => $balita->id,
            'childName'        => $balita->nama,
            'nik'              => $balita->nik,
            'noBpjs'           => $balita->no_bpjs,
            'birthDate'        => \Carbon\Carbon::parse($balita->tanggal_lahir)->format('Y-m-d'),
            'gender'           => $balita->jenis_kelamin,
            'birthWeight'      => $balita->berat_lahir,
            'birthLength'      => $balita->panjang_lahir,
            'birthHeadCirc'    => $balita->lingkar_kepala_lahir,
            'noKk'             => $balita->orangTua->no_kk ?? '',
            'motherName'       => $balita->orangTua->nama_ibu ?? '',
            'motherNik'        => $balita->orangTua->nik_ibu ?? $balita->orangTua->user->nik ?? '',
            'motherJob'        => $balita->orangTua->pekerjaan_ibu ?? '',
            'motherPhone'      => $balita->orangTua->no_hp_whatsapp ?? '',
            'fatherName'       => $balita->orangTua->nama_ayah ?? '',
            'fatherNik'        => $balita->orangTua->nik_ayah ?? '',
            'fatherJob'        => $balita->orangTua->pekerjaan_ayah ?? '',
            'address'          => $desa,
            'addressSub'       => $kecamatan,
            'posyanduName'     => $balita->posyandu->nama ?? 'Posyandu'
        ]);
    }

    public function updateBalita(UpdateBalitaRequest $request, $id)
    {
        $posyanduId = $this->getKaderPosyanduId();
        $balita = Balita::where('posyandu_id', $posyanduId)->findOrFail($id);

        $alamatJson = json_encode([
            'desa'      => $request->desa,
            'kecamatan' => $request->kecamatan
        ]);

        $balita->update([
            'nik'                  => $request->nik,
            'no_bpjs'              => $request->no_bpjs,
            'nama'                 => $request->nama,
            'jenis_kelamin'        => $request->jenis_kelamin,
            'tanggal_lahir'        => $request->tanggal_lahir,
            'berat_lahir'          => $request->berat_lahir,
            'panjang_lahir'        => $request->panjang_lahir,
            'lingkar_kepala_lahir' => $request->lingkar_kepala_lahir,
        ]);

        if ($balita->orangTua) {
            $balita->orangTua->update([
                'no_kk'          => $request->no_kk,
                'nama_ibu'       => $request->nama_ibu,
                'no_hp_whatsapp' => $request->no_hp,
                'nik_ibu'        => $request->nik_ibu,
                'pekerjaan_ibu'  => $request->pekerjaan_ibu,
                'nama_ayah'      => $request->nama_ayah,
                'nik_ayah'       => $request->nik_ayah,
                'pekerjaan_ayah' => $request->pekerjaan_ayah,
                'alamat'         => $alamatJson,
            ]);
            
            if ($balita->orangTua->user) {
                $balita->orangTua->user->update([
                    'name' => $request->nama_ibu,
                ]);
            }
        }

        return redirect()->route('balita.show', $balita->id)->with('success', 'Data Balita berhasil diperbarui.');
    }

    /**
     * Menghapus data Balita secara permanen (Hard Delete)
     * Beserta seluruh relasinya (Pengukuran, OrangTua, dan User) jika relevan.
     */
    public function hapusBalita(Request $request, $id)
    {
        $posyanduId = $this->getKaderPosyanduId();
        $balita = Balita::where('posyandu_id', $posyanduId)->findOrFail($id);
        $this->authorize('delete', $balita);

        $orangTuaId = $balita->orang_tua_id;
        $orangTua = OrangTua::find($orangTuaId);
        $userId = $orangTua ? $orangTua->user_id : null;

        // 1. Delete all measurements (Pengukurans) related to this balita
        Pengukuran::where('balita_id', $balita->id)->delete();

        // 2. Delete the Balita
        $balita->delete();

        // 3. Orphan Cleanup
        // If this OrangTua has no other Balitas, delete the OrangTua and User account
        if ($orangTua) {
            $otherChildrenCount = Balita::where('orang_tua_id', $orangTuaId)->count();
            if ($otherChildrenCount === 0) {
                $orangTua->delete();
                if ($userId) {
                    User::where('id', $userId)->delete();
                }
            }
        }

        return redirect()->route('balita.index')->with('success', 'Data balita dan riwayat pengukuran berhasil dihapus secara permanen.');
    }

    public function profilBalita($id)
    {
        $posyanduId = $this->getKaderPosyanduId();

        $b = Balita::with(['orangTua', 'posyandu', 'latestPengukuran', 'pengukurans' => function($q) {
            $q->orderBy('tanggal_ukur', 'desc')->orderBy('id', 'desc');
        }])->where('posyandu_id', $posyanduId)->findOrFail($id);

        $ageDiff = Carbon::parse($b->tanggal_lahir)->diff(Carbon::now());
        $ageStr = $ageDiff->y > 0 ? $ageDiff->y . ' Tahun ' . $ageDiff->m . ' Bulan' : $ageDiff->m . ' Bulan';

        $measurementsList = $b->pengukurans->values();
        $totalMeasures = $measurementsList->count();

        $measurements = $measurementsList->map(function($p, $index) use ($measurementsList, $totalMeasures, $b) {
            $statusType = match(strtolower($p->status_gizi)) {
                'normal' => 'success',
                'risiko' => 'warning',
                'stunting' => 'danger',
                default => 'success'
            };

            $weightTrend = null;
            $heightTrend = null;

            // In descending order, previous chronologically is index + 1
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
                'status' => $this->formatDisplayStatus($p->status_gizi, $p->status_validasi),
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
            'birthDate'      => \Carbon\Carbon::parse($b->tanggal_lahir)->translatedFormat('d F Y'),
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
            'status'         => $latestMeasure ? $latestMeasure['status'] : 'Belum Ada',
            'status_type'    => $latestMeasure ? $latestMeasure['status_type'] : 'success',
            'measurements'   => $measurements,
            'latestMeasure'  => $latestMeasure,
        ];

        return view('kader.profil-balita', $data);
    }

    public function pengukuran()
    {
        // Measurements are now handled via modal in the profile page.
        // Redirect to balita index to prevent 500 ViewNotFound errors if accessed directly.
        return redirect()->route('balita.index')->with('info', 'Silakan pilih balita terlebih dahulu untuk melakukan pengukuran.');
    }

    public function simpanPengukuran(StorePengukuranRequest $request)
    {
        if (!Auth::user()?->kader) {
            abort(403, 'Akses ditolak: Anda tidak memiliki data Kader yang valid.');
        }

        $posyanduId = $this->getKaderPosyanduId();

        // Pastikan Balita yang diukur berada di Posyandu Kader yang login
        $balita = Balita::where('posyandu_id', $posyanduId)->findOrFail($request->balita_id);
        
        // 1. Panggil GrowthCalculationService (Pure Logic)
        $calc = $this->growthService->calculate(
            Carbon::parse($balita->tanggal_lahir),
            Carbon::parse($request->tanggal_ukur),
            $balita->jenis_kelamin,
            (float) $request->berat_badan,
            (float) $request->tinggi_badan
        );

        // 2. Simpan ke database
        $pengukuran = Pengukuran::create([
            'balita_id'        => $balita->id,
            'kader_id'         => Auth::user()->kader->id,
            'tanggal_ukur'     => $request->tanggal_ukur,
            'umur_bulan'       => $calc['umur_bulan'],
            'berat_badan'      => $request->berat_badan,
            'tinggi_badan'     => $request->tinggi_badan,
            'lingkar_kepala'   => $request->lingkar_kepala,
            'asi_eksklusif'    => $request->boolean('asi_eksklusif'),
            'status_kenaikan'  => $request->status_kenaikan,
            'catatan_kader'    => $request->input('catatan_kader'),
            'z_score_bbu'      => $calc['z_score_bbu'],
            'z_score_tbu'      => $calc['z_score_tbu'],
            'status_gizi'      => $calc['status_gizi'],
            'status_validasi'  => 'pending'
        ]);

        // 3. Panggil RecommendationService (Bisa dikirim ke Session atau UI)
        $recommendation = $this->recommendationService->generate(
            $calc['status_gizi'], 
            $calc['umur_bulan'], 
            $calc['z_score_bbu'], 
            $calc['z_score_tbu']
        );

        return redirect()->route('balita.show', $balita->id)
            ->with('success', 'Pengukuran berhasil disimpan. Status: ' . $recommendation['status'])
            ->with('advice', $recommendation['dietary_advice']);
    }

    // =========================================================================
    // JADWAL POSYANDU CRUD
    // =========================================================================
    public function jadwal() 
    { 
        Carbon::setLocale('id');
        $posyanduId = $this->getKaderPosyanduId();
        $posyanduName = Auth::user()?->kader?->posyandu?->nama ?? 'Posyandu Kader';
        
        $jadwalList = Jadwal::where('posyandu_id', $posyanduId)
            ->orderBy('tanggal', 'desc')
            ->orderBy('waktu_mulai', 'asc')
            ->get();

        $today = Carbon::today('Asia/Jakarta');
        
        $formattedJadwals = $jadwalList->map(function ($j) use ($today) {
            $tgl = Carbon::parse($j->tanggal, 'Asia/Jakarta')->startOfDay();
            $isPast = $tgl->isPast() && !$tgl->isToday();
            $isToday = $tgl->isToday();

            $statusText = 'Akan Datang';
            $statusType = 'upcoming'; // upcoming, today, past
            $countdown = '';
            
            if ($isToday) {
                $statusText = 'Hari Ini';
                $statusType = 'today';
                $countdown = 'Hari Ini';
            } elseif ($isPast) {
                $statusText = 'Selesai';
                $statusType = 'past';
                $countdown = 'Selesai';
            } else {
                $diffDays = (int) $today->diffInDays($tgl, false);
                $countdown = $diffDays === 1 ? 'Besok' : ($diffDays > 1 ? $diffDays . ' Hari Lagi' : 'Segera');
            }

            return [
                'id' => $j->id,
                'judul' => $j->judul,
                'lokasi' => $j->lokasi,
                'tanggal' => $tgl->translatedFormat('d F Y'),
                'tgl_nomor' => $tgl->format('d'),
                'tgl_bulan_singkat' => strtoupper($tgl->translatedFormat('M')),
                'tgl_tahun' => $tgl->format('Y'),
                'raw_tanggal' => $tgl->format('Y-m-d'),
                'hari' => $tgl->translatedFormat('l'),
                'countdown' => $countdown,
                'waktu' => substr($j->waktu_mulai, 0, 5) . ' - ' . substr($j->waktu_selesai, 0, 5) . ' WIB',
                'waktu_mulai' => substr($j->waktu_mulai, 0, 5),
                'waktu_selesai' => substr($j->waktu_selesai, 0, 5),
                'catatan' => $j->catatan,
                'status' => $statusText,
                'status_type' => $statusType,
                'kader_nama' => $j->kader?->user?->name ?? 'Kader Posyandu'
            ];
        });

        $totalJadwal = $formattedJadwals->count();
        $jadwalMendatang = $formattedJadwals->whereIn('status_type', ['upcoming', 'today'])->count();

        return view('kader.jadwal', [
            'jadwals' => $formattedJadwals,
            'posyanduName' => $posyanduName,
            'totalJadwal' => $totalJadwal,
            'jadwalMendatang' => $jadwalMendatang,
        ]); 
    }

    public function tambahJadwal() 
    { 
        $posyanduId = $this->getKaderPosyanduId();
        $posyandu = Posyandu::find($posyanduId);
        $posyanduName = $posyandu?->nama ?? 'Posyandu Kader';

        return view('kader.tambah-jadwal', [
            'posyanduId' => $posyanduId,
            'posyanduName' => $posyanduName,
            'isEdit' => false,
            'jadwal' => null
        ]); 
    }

    public function simpanJadwal(StoreJadwalRequest $request)
    {
        $posyanduId = $this->getKaderPosyanduId();
        $kaderId = Auth::user()?->kader?->id;

        $validated = $request->validated();

        Jadwal::create([
            'posyandu_id' => $posyanduId,
            'kader_id' => $kaderId,
            'judul' => $validated['judul'],
            'lokasi' => $validated['lokasi'],
            'tanggal' => $validated['tanggal'],
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'catatan' => $validated['catatan'] ?? null,
            'status' => 'akan_datang',
        ]);

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal Posyandu berhasil dibuat dan otomatis terbit di Portal Ibu.');
    }

    public function detailJadwal($id)
    {
        Carbon::setLocale('id');
        $posyanduId = $this->getKaderPosyanduId();
        $jadwal = Jadwal::where('posyandu_id', $posyanduId)->findOrFail($id);

        $tgl = Carbon::parse($jadwal->tanggal);
        $isPast = $tgl->isPast() && !$tgl->isToday();
        $isToday = $tgl->isToday();

        $statusText = $isToday ? 'Hari Ini' : ($isPast ? 'Selesai' : 'Akan Datang');
        $statusType = $isToday ? 'today' : ($isPast ? 'past' : 'upcoming');

        $data = [
            'id' => $jadwal->id,
            'judul' => $jadwal->judul,
            'lokasi' => $jadwal->lokasi,
            'tanggal' => $tgl->translatedFormat('d F Y'),
            'hari' => $tgl->translatedFormat('l'),
            'waktu' => substr($jadwal->waktu_mulai, 0, 5) . ' - ' . substr($jadwal->waktu_selesai, 0, 5) . ' WIB',
            'waktu_mulai' => substr($jadwal->waktu_mulai, 0, 5),
            'waktu_selesai' => substr($jadwal->waktu_selesai, 0, 5),
            'catatan' => $jadwal->catatan,
            'status' => $statusText,
            'status_type' => $statusType,
            'posyandu_nama' => $jadwal->posyandu?->nama ?? 'Posyandu Kader',
            'desa' => $jadwal->posyandu?->desa_kelurahan ?? '-',
            'alamat_posyandu' => $jadwal->posyandu?->alamat ?? '-',
            'kader_nama' => $jadwal->kader?->user?->name ?? 'Kader Posyandu'
        ];

        return view('kader.detail-jadwal', ['jadwal' => $data]);
    }

    public function editJadwal($id)
    {
        $posyanduId = $this->getKaderPosyanduId();
        $jadwal = Jadwal::where('posyandu_id', $posyanduId)->findOrFail($id);
        $posyandu = Posyandu::find($posyanduId);
        $posyanduName = $posyandu?->nama ?? 'Posyandu Kader';

        return view('kader.tambah-jadwal', [
            'posyanduId' => $posyanduId,
            'posyanduName' => $posyanduName,
            'isEdit' => true,
            'jadwal' => $jadwal
        ]);
    }

    public function updateJadwal(Request $request, $id)
    {
        $posyanduId = $this->getKaderPosyanduId();
        $jadwal = Jadwal::where('posyandu_id', $posyanduId)->findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'lokasi' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'waktu_mulai' => 'required',
            'waktu_selesai' => 'required',
            'catatan' => 'nullable|string|max:1000',
        ], [
            'judul.required' => 'Judul kegiatan wajib diisi.',
            'lokasi.required' => 'Lokasi pelaksanaan wajib diisi.',
            'tanggal.required' => 'Tanggal kegiatan wajib diisi.',
            'waktu_mulai.required' => 'Jam mulai kegiatan wajib diisi.',
            'waktu_selesai.required' => 'Jam selesai kegiatan wajib diisi.',
        ]);

        $jadwal->update([
            'judul' => $validated['judul'],
            'lokasi' => $validated['lokasi'],
            'tanggal' => $validated['tanggal'],
            'waktu_mulai' => $validated['waktu_mulai'],
            'waktu_selesai' => $validated['waktu_selesai'],
            'catatan' => $validated['catatan'] ?? null,
        ]);

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal Posyandu berhasil diperbarui.');
    }

    public function hapusJadwal($id)
    {
        $posyanduId = $this->getKaderPosyanduId();
        $jadwal = Jadwal::where('posyandu_id', $posyanduId)->findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal.index')
            ->with('success', 'Jadwal Posyandu berhasil dihapus.');
    }
    public function laporan(Request $request) 
    { 
        $posyanduId = $this->getKaderPosyanduId();
        
        // Parsing periode
        $periodeReq = $request->input('periode', Carbon::now()->format('Y-m'));
        $parts = explode('-', $periodeReq);
        $year = (int) $parts[0];
        $month = (int) ($parts[1] ?? Carbon::now()->month);
        
        $stats = $this->statisticsService->getKaderDashboardStats($posyanduId, $month, $year);
        
        $posyanduName = Auth::user()?->kader?->posyandu?->nama ?? 'Posyandu Kader';
        
        Carbon::setLocale('id');
        $periodeLabel = Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');
        
        $belumDiukur = max(0, $stats['total_balita'] - $stats['bulan_ini']);
        $perluPerhatian = $stats['risiko'] + $stats['stunting'] + ($stats['kurang'] ?? 0);
        $berisiko = $stats['stunting']; 
        $persentase = $stats['total_balita'] > 0 ? round(($stats['bulan_ini'] / $stats['total_balita']) * 100) : 0;
        
        $dataKosong = $stats['total_balita'] === 0 || $stats['bulan_ini'] === 0;

        $previewBalitas = Balita::where('posyandu_id', $posyanduId)
            ->whereHas('pengukurans', function ($q) use ($month, $year) {
                $q->whereMonth('tanggal_ukur', $month)
                  ->whereYear('tanggal_ukur', $year);
            })
            ->with(['pengukurans' => function ($q) use ($month, $year) {
                $q->whereMonth('tanggal_ukur', $month)
                  ->whereYear('tanggal_ukur', $year)
                  ->latest('tanggal_ukur');
            }, 'orangTua'])
            ->take(5)
            ->get();

        return view('kader.laporan', [
            'posyanduAktif' => $posyanduName,
            'periode' => $periodeLabel,
            'periodeValue' => $periodeReq,
            'totalBalita' => $stats['total_balita'],
            'sudahDiukur' => $stats['bulan_ini'],
            'belumDiukur' => $belumDiukur,
            'perluPerhatian' => $perluPerhatian,
            'berisiko' => $berisiko,
            'persentase' => $persentase,
            'dataKosong' => $dataKosong,
            'previewBalitas' => $previewBalitas
        ]); 
    }

    public function generatePdf(Request $request)
    {
        $posyanduId = $this->getKaderPosyanduId();
        $kader = Auth::user()?->kader;
        
        // Parsing periode
        $periodeReq = $request->input('periode', Carbon::now()->format('Y-m'));
        $parts = explode('-', $periodeReq);
        $year = (int) $parts[0];
        $month = (int) ($parts[1] ?? Carbon::now()->month);
        
        $stats = $this->statisticsService->getKaderDashboardStats($posyanduId, $month, $year);
        $posyandu = $kader?->posyandu;
        $posyanduRawName = $posyandu?->nama ?? 'Mawar';
        $cleanPosyanduName = preg_replace('/^posyandu\s+/i', '', trim($posyanduRawName));
        $puskesmasRawName = $posyandu?->puskesmas?->nama ?? 'UPTD Puskesmas';
        $cleanPuskesmasName = preg_replace('/^puskesmas\s+/i', '', trim($puskesmasRawName));
        
        $desa = $posyandu?->desa_kelurahan ?? ($posyandu?->desa ?? 'Desa Sehat');
        $alamat = $posyandu?->alamat ?? 'Kecamatan Sehat';
        
        Carbon::setLocale('id');
        $periodeLabel = Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');
        
        $belumDiukur = max(0, $stats['total_balita'] - $stats['bulan_ini']);
        $perluPerhatian = $stats['risiko'] + $stats['stunting'] + ($stats['kurang'] ?? 0);
        $berisiko = $stats['stunting']; 
        $persentase = $stats['total_balita'] > 0 ? round(($stats['bulan_ini'] / $stats['total_balita']) * 100) : 0;

        // Ambil data balita yang diukur pada periode ini lengkap dengan relasi orang tua
        $balitas = Balita::where('posyandu_id', $posyanduId)
            ->whereHas('pengukurans', function($q) use ($month, $year) {
                $q->whereMonth('tanggal_ukur', $month)
                  ->whereYear('tanggal_ukur', $year);
            })
            ->with(['orangTua', 'pengukurans' => function($q) use ($month, $year) {
                $q->whereMonth('tanggal_ukur', $month)
                  ->whereYear('tanggal_ukur', $year)
                  ->latest('id');
            }])->get();

        $data = [
            'posyandu' => $posyandu,
            'posyanduName' => 'Posyandu ' . $cleanPosyanduName,
            'cleanPosyanduName' => $cleanPosyanduName,
            'puskesmasName' => 'Puskesmas ' . $cleanPuskesmasName,
            'cleanPuskesmasName' => $cleanPuskesmasName,
            'desa' => $desa,
            'alamat' => $alamat,
            'periode' => $periodeLabel,
            'totalBalita' => $stats['total_balita'],
            'sudahDiukur' => $stats['bulan_ini'],
            'belumDiukur' => $belumDiukur,
            'perluPerhatian' => $perluPerhatian,
            'berisiko' => $berisiko,
            'persentase' => $persentase,
            'stats' => $stats,
            'balitas' => $balitas,
            'kaderName' => $kader?->nama ?? Auth::user()->name
        ];

        return view('kader.laporan-pdf', $data);
    }

    public function exportExcel(Request $request)
    {
        $posyanduId = $this->getKaderPosyanduId();
        $kader = Auth::user()?->kader;
        $posyandu = $kader?->posyandu;
        $posyanduRawName = $posyandu?->nama ?? 'Mawar';
        $cleanPosyanduName = preg_replace('/^posyandu\s+/i', '', trim($posyanduRawName));
        $puskesmasRawName = $posyandu?->puskesmas?->nama ?? 'UPTD Puskesmas';
        $cleanPuskesmasName = preg_replace('/^puskesmas\s+/i', '', trim($puskesmasRawName));

        // Parsing periode
        $periodeReq = $request->input('periode', Carbon::now()->format('Y-m'));
        $parts = explode('-', $periodeReq);
        $year = (int) $parts[0];
        $month = (int) ($parts[1] ?? Carbon::now()->month);

        Carbon::setLocale('id');
        $periodeLabel = Carbon::createFromDate($year, $month, 1)->translatedFormat('F Y');

        // Ambil data balita yang diukur pada periode ini
        $balitas = Balita::where('posyandu_id', $posyanduId)
            ->whereHas('pengukurans', function($q) use ($month, $year) {
                $q->whereMonth('tanggal_ukur', $month)
                  ->whereYear('tanggal_ukur', $year);
            })
            ->with(['orangTua', 'pengukurans' => function($q) use ($month, $year) {
                $q->whereMonth('tanggal_ukur', $month)
                  ->whereYear('tanggal_ukur', $year)
                  ->latest('id');
            }])->get();

        $fileName = 'Laporan_Posyandu_' . preg_replace('/[^A-Za-z0-9_]/', '_', $cleanPosyanduName) . '_' . Carbon::createFromDate($year, $month, 1)->format('Y_m') . '.xls';

        $headers = [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($balitas, $cleanPosyanduName, $cleanPuskesmasName, $posyandu, $periodeLabel, $kader) {
            echo "\xEF\xBB\xBF"; // UTF-8 BOM for Excel
            echo "<!DOCTYPE html><html><head><meta charset='UTF-8'><style>
                body { font-family: Arial, sans-serif; }
                table { border-collapse: collapse; width: 100%; font-size: 10pt; }
                th, td { border: 1px solid #64748b; padding: 6px 8px; text-align: left; }
                th { background-color: #0f766e; color: #ffffff; font-weight: bold; text-align: center; }
                .kop-header { font-size: 13pt; font-weight: bold; text-align: center; border: none; }
                .kop-sub { font-size: 10pt; text-align: center; border: none; color: #475569; }
                .text-center { text-align: center; }
                .text-right { text-align: right; }
                .font-bold { font-weight: bold; }
                .bg-alt { background-color: #f8fafc; }
            </style></head><body>";

            echo "<table>";
            echo "<tr><td colspan='16' class='kop-header'>LAPORAN BULANAN HASIL PENIMBANGAN & PENGUKURAN POSYANDU</td></tr>";
            echo "<tr><td colspan='16' class='kop-sub'><strong>Posyandu {$cleanPosyanduName}</strong> &bull; Wilayah Kerja: <strong>Puskesmas {$cleanPuskesmasName}</strong> &bull; Periode: <strong>{$periodeLabel}</strong></td></tr>";
            echo "<tr><td colspan='16' style='border:none; height:12px;'></td></tr>";
            
            echo "<tr>
                <th style='width:35px;'>No</th>
                <th style='width:130px;'>NIK Balita</th>
                <th style='width:160px;'>Nama Balita</th>
                <th style='width:45px;'>L/P</th>
                <th style='width:85px;'>Tgl Lahir</th>
                <th style='width:65px;'>Umur</th>
                <th style='width:150px;'>Nama Ibu / Ortu</th>
                <th style='width:130px;'>No. KK</th>
                <th style='width:85px;'>Tgl Ukur</th>
                <th style='width:65px;'>BB (kg)</th>
                <th style='width:65px;'>TB (cm)</th>
                <th style='width:65px;'>LK (cm)</th>
                <th style='width:60px;'>ASI Eks</th>
                <th style='width:60px;'>KMS</th>
                <th style='width:140px;'>Status / Diagnosa</th>
                <th style='width:180px;'>Catatan</th>
            </tr>";

            if ($balitas->isEmpty()) {
                echo "<tr><td colspan='16' class='text-center' style='padding:15px; color:#64748b;'>Belum ada data balita yang diukur pada periode {$periodeLabel}.</td></tr>";
            } else {
                foreach ($balitas as $idx => $b) {
                    $m = $b->pengukurans->first();
                    $no = $idx + 1;
                    $nik = "'" . ($b->nik ?? '-');
                    $nama = htmlspecialchars($b->nama);
                    $jk = $b->jenis_kelamin;
                    $tglLahir = $b->tanggal_lahir ? Carbon::parse($b->tanggal_lahir)->format('d/m/Y') : '-';
                    $umur = $m ? ($m->umur_bulan . ' bln') : '-';
                    $ibu = htmlspecialchars($b->orangTua->nama_ibu ?? '-');
                    $kk = "'" . ($b->orangTua->no_kk ?? '-');
                    $tglUkur = $m ? Carbon::parse($m->tanggal_ukur)->format('d/m/Y') : '-';
                    $bb = $m ? number_format((float)$m->berat_badan, 2) : '-';
                    $tb = $m ? number_format((float)$m->tinggi_badan, 1) : '-';
                    $lk = ($m && $m->lingkar_kepala) ? number_format((float)$m->lingkar_kepala, 1) : '-';
                    $asi = $m ? ($m->asi_eksklusif ? 'Ya' : 'Tdk') : '-';
                    $kms = $m ? ($m->status_kenaikan ?? '-') : '-';
                    $status = $m ? $this->formatDisplayStatus($m->status_gizi, $m->status_validasi) : 'Belum Diukur';
                    $catatan = $m ? ($m->catatan_kader ?? ($m->catatan_validator ?? '-')) : '-';
                    $bgClass = $idx % 2 == 1 ? 'class="bg-alt"' : '';

                    echo "<tr {$bgClass}>
                        <td class='text-center'>{$no}</td>
                        <td class='text-center'>{$nik}</td>
                        <td class='font-bold'>{$nama}</td>
                        <td class='text-center'>{$jk}</td>
                        <td class='text-center'>{$tglLahir}</td>
                        <td class='text-center'>{$umur}</td>
                        <td>{$ibu}</td>
                        <td class='text-center'>{$kk}</td>
                        <td class='text-center'>{$tglUkur}</td>
                        <td class='text-center font-bold'>{$bb}</td>
                        <td class='text-center font-bold'>{$tb}</td>
                        <td class='text-center'>{$lk}</td>
                        <td class='text-center'>{$asi}</td>
                        <td class='text-center font-bold'>{$kms}</td>
                        <td class='text-center font-bold'>{$status}</td>
                        <td>" . htmlspecialchars($catatan) . "</td>
                    </tr>";
                }
            }

            echo "<tr><td colspan='16' style='border:none; height:20px;'></td></tr>";
            echo "<tr>
                <td colspan='8' style='border:none; text-align:center;'>
                    Mengetahui,<br>
                    <strong>Petugas Gizi / Bidan Pembina Puskesmas</strong><br><br><br><br>
                    ( .................................................... )<br>
                    NIP. .............................................
                </td>
                <td colspan='8' style='border:none; text-align:center;'>
                    Dicetak pada: " . now()->translatedFormat('d F Y') . "<br>
                    <strong>Pelaksana Kader Posyandu {$cleanPosyanduName}</strong><br><br><br><br>
                    <strong><u>" . htmlspecialchars($kader?->nama ?? Auth::user()->name) . "</u></strong><br>
                    Kader Penanggung Jawab
                </td>
            </tr>";
            echo "</table></body></html>";
        };

        return response()->stream($callback, 200, $headers);
    }
    
    public function profilKader()
    {
        $kader = Auth::user()->kader;
        
        $alamatRaw = $kader->posyandu->alamat ?? '';
        $alamatData = json_decode($alamatRaw, true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($alamatData)) {
            $desa = $alamatData['desa'] ?? $kader->posyandu->desa ?? '-';
            $kecamatan = $alamatData['kecamatan'] ?? '-';
        } else {
            $desa = $kader->posyandu->desa ?? '-';
            $kecamatan = '-';
        }
        
        return view('kader.profil-kader', [
            'kaderName' => $kader->nama ?? Auth::user()->name,
            'role' => 'Kader Posyandu',
            'email' => Auth::user()->email,
            'phone' => $kader->no_hp ?? '-',
            'posyanduName' => $kader->posyandu->nama ?? '-',
            'desa' => $desa,
            'kecamatan' => $kecamatan,
            'puskesmas' => $kader->posyandu->puskesmas->nama ?? '-',
            'status' => 'Aktif'
        ]);
    }

    public function editProfilKader()
    {
        $kader = Auth::user()->kader;
        return view('kader.edit-profil-kader', [
            'name' => $kader->nama ?? Auth::user()->name,
            'email' => Auth::user()->email,
            'phone' => $kader->no_hp ?? ''
        ]);
    }

    public function updateProfilKader(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20'
        ]);

        $user = Auth::user();
        $kader = $user->kader;

        if ($kader) {
            $kader->update([
                'nama' => $request->nama,
                'no_hp' => $request->no_hp
            ]);
        }

        $user->update([
            'name' => $request->nama
        ]);

        return redirect()->route('kader.profil')->with('success', 'Profil berhasil diperbarui.');
    }

    /**
     * Show the Kader account security page.
     */
    public function keamanan()
    {
        return view('kader.keamanan');
    }

    /**
     * Update the Kader's password.
     */
    public function updateKeamanan(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Kata sandi saat ini tidak cocok.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('kader.keamanan')->with('success', 'Kata sandi berhasil diperbarui.');
    }

    /**
     * Show rejected measurements for the Kader's Posyandu.
     */
    public function rejectedData(Request $request)
    {
        $posyanduId = $this->getKaderPosyanduId();

        $pengukuran = Pengukuran::whereHas('balita', function ($q) use ($posyanduId) {
            $q->where('posyandu_id', $posyanduId);
        })->where('status_validasi', 'rejected')
            ->with(['balita', 'balita.orangTua'])
            ->get();

        $data = $pengukuran->map(function ($p) {
            return [
                'id' => $p->id,
                'childName' => $p->balita->nama ?? '-',
                'measureDate' => Carbon::parse($p->tanggal_ukur)->translatedFormat('d M Y'),
                'statusGizi' => $p->status_gizi,
                'catatan' => $p->catatan_validator ?? '-',
            ];
        })->toArray();

        return view('kader.rejected', ['rejected' => $data]);
    }

    /**
     * Update a rejected measurement after correction and resubmit.
     */
    public function updatePengukuran(Request $request, $id)
    {
        $request->validate([
            'tanggal_ukur'    => 'required|date',
            'berat_badan'     => 'required|numeric|min:1|max:999.99',
            'tinggi_badan'    => 'required|numeric|min:10|max:999.99',
            'lingkar_kepala'  => 'nullable|numeric|min:10|max:99.99',
            'asi_eksklusif'   => 'nullable',
            'status_kenaikan' => 'nullable|string|max:10',
            'catatan_kader'   => 'nullable|string|max:500',
        ]);

        $pengukuran = Pengukuran::findOrFail($id);
        // Ensure it belongs to this Kader's Posyandu
        $posyanduId = $this->getKaderPosyanduId();
        if ($pengukuran->balita->posyandu_id !== $posyanduId) {
            abort(403, 'Akses ditolak');
        }

        // Panggil GrowthCalculationService (Pure Logic) untuk menghitung ulang Z-Score
        $calc = $this->growthService->calculate(
            Carbon::parse($pengukuran->balita->tanggal_lahir),
            Carbon::parse($request->tanggal_ukur),
            $pengukuran->balita->jenis_kelamin,
            (float) $request->berat_badan,
            (float) $request->tinggi_badan
        );

        // Update measurement fields beserta z-score dan status gizi baru
        $pengukuran->update([
            'tanggal_ukur'      => $request->tanggal_ukur,
            'umur_bulan'        => $calc['umur_bulan'],
            'berat_badan'       => $request->berat_badan,
            'tinggi_badan'      => $request->tinggi_badan,
            'lingkar_kepala'    => $request->lingkar_kepala,
            'asi_eksklusif'     => $request->boolean('asi_eksklusif'),
            'status_kenaikan'   => $request->status_kenaikan,
            'catatan_kader'     => $request->input('catatan_kader', $pengukuran->catatan_kader),
            'z_score_bbu'       => $calc['z_score_bbu'],
            'z_score_tbu'       => $calc['z_score_tbu'],
            'status_gizi'       => $calc['status_gizi'],
            'status_validasi'   => 'pending',
            'catatan_validator' => null,
        ]);

        return back()->with('success', 'Pengukuran berhasil diperbaiki dan dikirim kembali untuk validasi.');
    }
}
