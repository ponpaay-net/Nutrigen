<?php

namespace App\Http\Controllers\PortalIbu;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Balita;
use App\Models\OrangTua;
use App\Models\Pengukuran;
use App\Models\Kader;
use App\Models\Jadwal;
use App\Services\RecommendationService;
use Carbon\Carbon;

class PortalIbuController extends Controller
{
    public function __construct(
        protected RecommendationService $recommendationService,
        protected \App\Services\GrowthCalculationService $growthCalculationService
    ) {}

    /**
     * Memastikan akses data hanya untuk data milik OrangTua yang login (Secure Read-Only).
     */
    private function getActiveBalita()
    {
        $balitaId = request('balita');
        $orangTuaId = request('orang_tua');
        if (!$balitaId || !$orangTuaId) return null;

        // KRITIS-04: scope ke orang_tua pemilik link — balita milik
        // orang tua lain tidak akan pernah ter-resolve (empty state).
        return Balita::with(['pengukurans' => function($q) {
            $q->where('status_validasi', 'approved')->latest('tanggal_ukur');
        }, 'posyandu'])
            ->where('id', $balitaId)
            ->where('orang_tua_id', $orangTuaId)
            ->first();
    }

    public function home()
    {
        $balita = $this->getActiveBalita();

        if (!$balita) {
            return view('portal-ibu.home.index', ['pageState' => 'empty']);
        }

        // Check if there are any measurements at all in DB to determine if pending vs completely empty
        $hasPending = false;
        if ($balita->pengukurans->isEmpty()) {
            // Check if there are measurements waiting for validation
            $pendingCount = Pengukuran::where('balita_id', $balita->id)->where('status_validasi', 'pending')->count();
            if ($pendingCount > 0) {
                $hasPending = true;
            }
        } else {
            // Check if the actual latest measurement in DB is pending
            $absoluteLatest = Pengukuran::where('balita_id', $balita->id)->where('status_validasi', '!=', 'draft')->latest('tanggal_ukur')->first();
            if ($absoluteLatest && $absoluteLatest->status_validasi === 'pending') {
                $hasPending = true;
            }
        }

        if ($balita->pengukurans->isEmpty()) {
            return view('portal-ibu.home.index', [
                'pageState' => 'empty',
                'hasPending' => $hasPending,
                'user' => ['child_name' => $balita->nama, 'avatar' => null]
            ]);
        }

        $pengukurans = $balita->pengukurans;
        $latest = $pengukurans->first();
        $previous = $pengukurans->skip(1)->first();

        // Riwayat pengukuran terformat (semuanya berasal dari data yang sudah
        // divalidasi / approved oleh Puskesmas).
        $history = $pengukurans->map(function ($p) use ($balita) {
            $ageParts = Carbon::parse($balita->tanggal_lahir)->diff(Carbon::parse($p->tanggal_ukur));
            $ageLabel = $ageParts->y > 0
                ? $ageParts->y . ' Thn ' . $ageParts->m . ' Bln'
                : $ageParts->m . ' Bln ' . $ageParts->d . ' Hari';
            return [
                'id'               => $p->id,
                'date'             => Carbon::parse($p->tanggal_ukur)->translatedFormat('d M Y'),
                'age'              => $ageLabel,
                'umur_bulan'       => $p->umur_bulan,
                'weight'           => number_format((float) $p->berat_badan, 1),
                'height'           => number_format((float) $p->tinggi_badan, 1),
                'head_circ'        => $p->lingkar_kepala !== null ? number_format((float) $p->lingkar_kepala, 1) : null,
                'z_bbu'            => $p->z_score_bbu,
                'z_tbu'            => $p->z_score_tbu,
                'status'           => $p->status_gizi,
                'catatan_validator'=> $p->catatan_validator,
                'catatan_kader'    => $p->catatan_kader,
            ];
        })->values()->toArray();

        // Kurva pertumbuhan: [umur_bulan, nilai] diurutkan naik sesuai waktu.
        $chartWeight = $pengukurans->sortBy('tanggal_ukur')->map(fn ($p) => [(int) $p->umur_bulan, (float) $p->berat_badan])->values()->toArray();
        $chartHeight = $pengukurans->sortBy('tanggal_ukur')->map(fn ($p) => [(int) $p->umur_bulan, (float) $p->tinggi_badan])->values()->toArray();

        $deltaWeight = '';
        $deltaHeight = '';
        if ($latest && $previous) {
            $diffWeight = $latest->berat_badan - $previous->berat_badan;
            $diffHeight = $latest->tinggi_badan - $previous->tinggi_badan;
            $deltaWeight = ($diffWeight >= 0 ? 'Naik ' : 'Turun ') . abs(round($diffWeight * 1000)) . 'g';
            $deltaHeight = ($diffHeight >= 0 ? 'Naik ' : 'Turun ') . abs(round($diffHeight, 1)) . 'cm';
        }

        $recommendation = null;
        $pageState = 'normal';
        if ($latest) {
            $recommendation = $this->recommendationService->generate(
                $latest->status_gizi,
                $latest->umur_bulan,
                $latest->z_score_bbu,
                $latest->z_score_tbu
            );

            $gizi = strtolower((string) $latest->status_gizi);
            if (in_array($gizi, ['stunting'])) {
                $pageState = 'merah';
            } elseif (in_array($gizi, ['risiko', 'kurang'])) {
                $pageState = 'kuning';
            }
        }

        // Fetch live upcoming Posyandu schedule created by Kader
        $upcomingJadwal = null;
        $posyanduName = $balita->posyandu?->nama ?? 'Posyandu';
        $scheduleText = 'Sesuai info Kader';
        $countdownText = 'Menunggu Jadwal';
        $location = $balita->posyandu?->alamat ?? 'Balai Posyandu';
        $notes = null;

        if ($balita && $balita->posyandu_id) {
            $today = Carbon::today('Asia/Jakarta');
            $upcomingJadwal = Jadwal::where('posyandu_id', $balita->posyandu_id)
                ->where('tanggal', '>=', $today)
                ->orderBy('tanggal', 'asc')
                ->orderBy('waktu_mulai', 'asc')
                ->first();

            if ($upcomingJadwal) {
                $tgl = Carbon::parse($upcomingJadwal->tanggal, 'Asia/Jakarta')->startOfDay();
                $scheduleText = $tgl->translatedFormat('d M Y') . ' (' . substr($upcomingJadwal->waktu_mulai, 0, 5) . ' WIB)';
                $location = $upcomingJadwal->lokasi;
                $notes = $upcomingJadwal->catatan;

                if ($tgl->isToday()) {
                    $countdownText = 'HARI INI';
                } else {
                    $diffDays = (int) $today->diffInDays($tgl, false);
                    $countdownText = $diffDays === 1 ? 'BESOK' : ($diffDays > 1 ? $diffDays . ' HARI LAGI' : 'AKAN DATANG');
                }
            }
        }

        $data = [
            'pageState' => $pageState,
            'hasPending' => $hasPending,
            'user' => [
                'child_name' => $balita->nama,
                'avatar' => null,
            ],
            'summary' => [
                'status' => $recommendation['status'] ?? 'Belum Ada Data',
                'title' => $recommendation['title'] ?? 'Tumbuh Kembang Si Kecil',
                'message' => $recommendation['education'] ?? 'Silakan lakukan penimbangan rutin di Posyandu.',
                'action' => $recommendation['follow_up_action'] ?? 'Tunggu jadwal Posyandu berikutnya.',
                'catatan_validator' => $latest->catatan_validator ?? null
            ],
            'measurement' => $latest ? [
                'date' => Carbon::parse($latest->tanggal_ukur)->format('d M Y'),
                'weight' => $latest->berat_badan,
                'height' => $latest->tinggi_badan,
                'age' => $latest->umur_bulan . ' bln',
                'gender' => $balita->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                'birth_date' => $balita->tanggal_lahir ? Carbon::parse($balita->tanggal_lahir)->format('d M Y') : null,
                'head_circ' => $latest->lingkar_kepala !== null ? number_format((float) $latest->lingkar_kepala, 1) : null,
            ] : [
                'date' => '-',
                'weight' => '-',
                'height' => '-',
                'age' => '-',
                'gender' => $balita->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan',
                'birth_date' => $balita->tanggal_lahir ? Carbon::parse($balita->tanggal_lahir)->format('d M Y') : null,
                'head_circ' => null,
            ],
            'delta' => [
                'is_first' => !$previous,
                'weight' => $deltaWeight ?: 'Data Awal',
                'height' => $deltaHeight ?: 'Data Awal'
            ],
            'recommendation' => [
                'message' => $recommendation['dietary_advice'] ?? 'Ayo penuhi nutrisi anak setiap hari.',
                'cta' => 'Lihat Menu Hari Ini'
            ],
            'posyandu' => [
                'name' => $posyanduName,
                'schedule' => $scheduleText,
                'countdown' => $countdownText,
                'location' => $location,
                'notes' => $notes,
                'cta' => 'Chat Kader'
            ],
            'history' => $history,
            'chart' => [
                'weight' => $chartWeight,
                'height' => $chartHeight,
            ],
        ];

        return view('portal-ibu.home.index', $data);
    }

    public function growth()
    {
        $balita = $this->getActiveBalita();
        
        $pengukurans = $balita ? $balita->pengukurans : collect();

        $timeline = $pengukurans->map(function ($p) use ($balita) {
            $ageParts = Carbon::parse($balita->tanggal_lahir)->diff(Carbon::parse($p->tanggal_ukur));
            return [
                'date' => Carbon::parse($p->tanggal_ukur)->translatedFormat('l, d M Y'),
                'age' => $ageParts->y . ' Tahun ' . $ageParts->m . ' Bulan',
                'weight' => $p->berat_badan,
                'height' => $p->tinggi_badan,
                'status' => strtolower((string) $p->status_gizi)
            ];
        })->toArray();

        // Format points for chart: [umur_bulan, berat_badan].
        // Dedup per bulan umur: bila ada 2+ pengukuran dalam bulan yang sama,
        // ambil yang TERBARU (praktik KMS — catatan terakhir yang berlaku),
        // supaya dua titik tidak jatuh di X sama dan bertumpuk di grafik.
        // $pengukurans urut tanggal terbaru dulu -> first() per grup = terbaru.
        $points = $pengukurans
            ->groupBy('umur_bulan')
            ->map(function ($group) {
                $last = $group->first();
                return [$last->umur_bulan, (float) $last->berat_badan];
            })
            ->sortKeys()
            ->values()
            ->toArray();

        // Kurva referensi WHO BB/U (standar WHO 2006, tabel LMS asli) sesuai
        // jenis kelamin anak, dipotong ke rentang umur data pengukuran.
        $whoCurve = [];
        if ($balita && count($points) >= 2) {
            $ages = array_column($points, 0);
            $whoCurve = $this->growthCalculationService->bbuWhoCurve(
                (int) min($ages),
                (int) max($ages),
                (string) $balita->jenis_kelamin
            );
        }

        $latest = $pengukurans->first();
        $recommendation = null;
        $pageState = 'normal';
        $storyState = 'normal';
        
        if ($latest) {
            $recommendation = $this->recommendationService->generate(
                $latest->status_gizi,
                $latest->umur_bulan,
                $latest->z_score_bbu,
                $latest->z_score_tbu
            );
            $gizi = strtolower((string) $latest->status_gizi);
            if (in_array($gizi, ['stunting'])) $storyState = 'merah';
            elseif (in_array($gizi, ['risiko', 'kurang'])) $storyState = 'kuning';
        }

        $data = [
            'pageState' => 'normal',
            'childName' => $balita ? explode(' ', $balita->nama)[0] : '-',
            'avatar' => null,
            'initials' => $balita ? strtoupper(substr($balita->nama, 0, 1)) : '',
            'story' => [
                'state' => $storyState,
                'status' => $recommendation['status'] ?? 'Belum Ada Data',
                'title' => $recommendation['title'] ?? 'Tumbuh Kembang Si Kecil',
                'message' => $recommendation['education'] ?? 'Silakan lakukan penimbangan rutin di Posyandu.'
            ],
            'comparison' => [
                'icon' => '💡',
                'message' => 'Grafik di bawah ini disusun berdasarkan panduan kurva pertumbuhan resmi dari WHO.'
            ],
            'chartPoints' => $points,
            'whoCurve' => $whoCurve,
            'timeline' => $timeline
        ];

        return view('portal-ibu.growth.index', $data);
    }

    public function nutrition()
    {
        $balita = $this->getActiveBalita();
        $latest = $balita ? $balita->pengukurans->first() : null;

        $advice = 'Berikan variasi makanan sehat setiap hari.';
        if ($latest) {
            $recommendation = $this->recommendationService->generate(
                $latest->status_gizi,
                $latest->umur_bulan,
                $latest->z_score_bbu,
                $latest->z_score_tbu
            );
            $advice = $recommendation['dietary_advice'];
        }

        $data = [
            'pageState' => 'normal',
            'user' => [
                'initials' => $balita ? strtoupper(substr($balita->nama, 0, 1)) : '',
                'avatar' => null,
            ],
            'trustBannerMessage' => $advice,
            'heroMeal' => [],
            'alternatives' => []
        ];

        return view('portal-ibu.nutrition.index', $data);
    }

    public function posyandu()
    {
        $balita = $this->getActiveBalita();
        $posyanduId = $balita?->posyandu_id;
        
        $kader = $posyanduId ? Kader::where('posyandu_id', $posyanduId)->with('user')->first() : null;

        $upcomingJadwal = null;
        if ($posyanduId) {
            $upcomingJadwal = Jadwal::where('posyandu_id', $posyanduId)
                ->where('tanggal', '>=', Carbon::today())
                ->orderBy('tanggal', 'asc')
                ->orderBy('waktu_mulai', 'asc')
                ->first();
        }

        $scheduleData = null;
        if ($posyanduId && $balita->posyandu) {
            if ($upcomingJadwal) {
                $tgl = Carbon::parse($upcomingJadwal->tanggal);
                $diffDays = Carbon::today()->diffInDays($tgl, false);
                $countdown = $tgl->isToday() ? 'Hari Ini' : ($diffDays > 0 ? $diffDays . ' Hari Lagi' : 'Segera');
                
                $scheduleData = [
                    'posyanduName' => $balita->posyandu?->nama,
                    'title' => $upcomingJadwal->judul,
                    'date' => $tgl->translatedFormat('l, d F Y'),
                    'time' => substr($upcomingJadwal->waktu_mulai, 0, 5) . ' - ' . substr($upcomingJadwal->waktu_selesai, 0, 5) . ' WIB',
                    'countdown' => $countdown,
                    'address' => $upcomingJadwal->lokasi,
                    'notes' => $upcomingJadwal->catatan
                ];
            } else {
                $scheduleData = [
                    'posyanduName' => $balita->posyandu?->nama,
                    'title' => 'Layanan Rutin Posyandu',
                    'date' => 'Menunggu jadwal kader',
                    'time' => 'Sesuai Jadwal',
                    'countdown' => '-',
                    'address' => $balita->posyandu?->alamat ?? '-',
                    'notes' => null
                ];
            }
        }

        $data = [
            'pageState' => 'normal',
            'user' => [
                'initials' => $balita ? strtoupper(substr($balita->nama, 0, 1)) : '',
                'avatar' => null,
            ],
            'announcement' => $upcomingJadwal && $upcomingJadwal->catatan ? [
                'badge' => 'PENGUMUMAN POSYANDU',
                'title' => $upcomingJadwal->judul,
                'message' => $upcomingJadwal->catatan
            ] : null,
            'schedule' => $scheduleData,
            'kader' => $kader ? [
                'name' => $kader->user?->name ?? $kader->nama,
                'role' => 'Kader Posyandu',
                // wa.me butuh format internasional (62...); fallback hanya jika kader belum punya nomor
                'whatsapp_url' => 'https://wa.me/' . (function () use ($kader) {
                    $digits = preg_replace('/[^0-9]/', '', $kader->no_hp ?? '');
                    if ($digits === '') return '';
                    if (str_starts_with($digits, '62')) return $digits;
                    if (str_starts_with($digits, '0')) return '62' . substr($digits, 1);
                    if (str_starts_with($digits, '8')) return '62' . $digits;
                    return $digits;
                })(),
                'avatar' => null
            ] : null,
            'checklist' => [
                [
                    'task' => 'Bawa Buku KIA (KMS Balita)',
                    'checked' => true
                ],
                [
                    'task' => 'Pastikan anak dalam kondisi sehat',
                    'checked' => false
                ],
                [
                    'task' => 'Bawa fotokopi KK jika ada pembaruan data',
                    'checked' => false
                ]
            ]
        ];

        return view('portal-ibu.posyandu.index', $data);
    }

    public function childSelector(Request $request)
    {
        // Portal Ibu memakai signed URL (tanpa login), jadi identitas
        // OrangTua diambil dari parameter yang terikat signature.
        $orangTuaId = $request->query('orang_tua');
        $orangTua = $orangTuaId ? OrangTua::find($orangTuaId) : null;

        // Ambil semua balita yang terdaftar di bawah OrangTua tersebut
        $balitaList = $orangTua
            ? Balita::where('orang_tua_id', $orangTua->id)
                ->with(['pengukurans' => function ($q) {
                    $q->where('status_validasi', 'approved')->latest('tanggal_ukur');
                }])
                ->get()
            : collect();

        $hour = Carbon::now('Asia/Jakarta')->hour;
        if ($hour < 12)       $greeting = "Selamat pagi, Ibunda";
        elseif ($hour < 15)   $greeting = "Selamat siang, Ibunda";
        elseif ($hour < 18)   $greeting = "Selamat sore, Ibunda";
        else                  $greeting = "Selamat malam, Ibunda";

        if ($balitaList->isEmpty()) {
            return view('portal-ibu.child-selector.index', [
                'pageState' => 'empty',
                'children'  => [],
                'greeting'  => $greeting,
            ]);
        }

        $children = $balitaList->map(function ($balita) use ($orangTuaId) {
            $latest = $balita->pengukurans->first();
            $status = $latest ? ucfirst(strtolower((string) $latest->status_gizi)) : null;

            $ageParts = Carbon::parse($balita->tanggal_lahir)->diff(Carbon::now('Asia/Jakarta'));
            $age = $ageParts->y > 0
                ? $ageParts->y . ' Tahun ' . $ageParts->m . ' Bulan'
                : $ageParts->m . ' Bulan ' . $ageParts->d . ' Hari';

            return [
                'id'       => $balita->id,
                'name'     => $balita->nama,
                'initials' => strtoupper(substr($balita->nama, 0, 1)),
                'avatar'   => null,
                'age'      => $age,
                'status'   => $status,
                // TINGGI-01: link antar halaman portal wajib signed + membawa orang_tua
                'url'      => \Illuminate\Support\Facades\URL::temporarySignedRoute(
                    'portal-ibu.home',
                    now()->addDays(config('portal.link_ttl_days')),
                    ['balita' => $balita->id, 'orang_tua' => $orangTuaId]
                ),
            ];
        })->toArray();

        return view('portal-ibu.child-selector.index', [
            'pageState' => 'normal',
            'children'  => $children,
            'greeting'  => $greeting,
        ]);
    }
}
