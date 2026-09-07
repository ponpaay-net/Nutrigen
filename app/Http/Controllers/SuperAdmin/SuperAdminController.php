<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Puskesmas;
use App\Models\Posyandu;
use App\Models\Balita;
use App\Models\Pengukuran;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;

class SuperAdminController extends Controller
{
    public function dashboard(Request $request)
    {
        // ── Filter ─────────────────────────────────────────────────────────────
        $currentMonth = max(1, min(12, (int) $request->input('month', date('n'))));
        $currentYear  = max(2000, min((int) date('Y') + 1, (int) $request->input('year', date('Y'))));

        // ── Previous period (for real delta badges) ─────────────────────────────
        $prevDate  = Carbon::create($currentYear, $currentMonth, 1)->subMonth();
        $prevMonth = $prevDate->month;
        $prevYear  = $prevDate->year;

        // ── Global counts (not filtered by month) ───────────────────────────────
        $totalPuskesmas = Puskesmas::count();
        $totalPosyandu  = Posyandu::count();
        $totalBalita    = Balita::count();

        // ── This-period measurements ────────────────────────────────────────────
        $measurementsThisMonth = Pengukuran::whereMonth('tanggal_ukur', $currentMonth)
            ->whereYear('tanggal_ukur', $currentYear)
            ->where('status_validasi', 'approved')
            ->get();

        $totalMeasured = $measurementsThisMonth->count();
        $totalStunting = $measurementsThisMonth->where('status_gizi', 'Stunting')->count();
        $totalRisiko   = $measurementsThisMonth->whereIn('status_gizi', ['Risiko', 'Kurang'])->count();
        $totalNormal   = $measurementsThisMonth->whereIn('status_gizi', ['Normal', 'Baik'])->count();

        $stuntingRate = $totalMeasured > 0 ? round(($totalStunting / $totalMeasured) * 100, 1) : 0;
        $risikoRate   = $totalMeasured > 0 ? round(($totalRisiko  / $totalMeasured) * 100, 1) : 0;
        $normalRate   = $totalMeasured > 0 ? round(($totalNormal  / $totalMeasured) * 100, 1) : 0;

        // ── Previous-period measurements (for real delta) ────────────────────────
        $prevMeasured = Pengukuran::whereMonth('tanggal_ukur', $prevMonth)
            ->whereYear('tanggal_ukur', $prevYear)
            ->where('status_validasi', 'approved')
            ->count();

        // Real delta: positive = grew, negative = shrank
        $measuredDelta     = null; // will display as nothing if both are 0
        $measuredDeltaSign = '';
        if ($prevMeasured > 0 || $totalMeasured > 0) {
            $measuredDelta     = $totalMeasured - $prevMeasured;
            $measuredDeltaSign = $measuredDelta >= 0 ? '+' : '';
        }

        // ── 6-month trend for bar chart ─────────────────────────────────────────
        $months       = [];
        $trendNormal  = [];
        $trendRisiko  = [];
        $trendStunting = [];

        for ($i = 5; $i >= 0; $i--) {
            $date    = Carbon::now()->subMonths($i);
            $months[] = $date->translatedFormat('M Y');

            $monthlyData = Pengukuran::whereMonth('tanggal_ukur', $date->month)
                ->whereYear('tanggal_ukur', $date->year)
                ->where('status_validasi', 'approved')
                ->get();

            $trendNormal[]   = $monthlyData->whereIn('status_gizi', ['Normal', 'Baik'])->count();
            $trendRisiko[]   = $monthlyData->whereIn('status_gizi', ['Risiko', 'Kurang'])->count();
            $trendStunting[] = $monthlyData->where('status_gizi', 'Stunting')->count();
        }

        // ── Puskesmas leaderboard (real pagination) ─────────────────────────────
        $perPage = 8;
        $page    = max(1, (int) $request->input('page', 1));

        $allPuskesmas = Puskesmas::withCount(['posyandus', 'balitas'])->get()
            ->map(function ($p) use ($currentMonth, $currentYear) {
                $p->measured_count = Pengukuran::whereHas('balita.posyandu', fn($q) => $q->where('puskesmas_id', $p->id))
                    ->whereMonth('tanggal_ukur', $currentMonth)
                    ->whereYear('tanggal_ukur', $currentYear)
                    ->where('status_validasi', 'approved')
                    ->count();

                $p->stunting_count = Pengukuran::whereHas('balita.posyandu', fn($q) => $q->where('puskesmas_id', $p->id))
                    ->whereMonth('tanggal_ukur', $currentMonth)
                    ->whereYear('tanggal_ukur', $currentYear)
                    ->where('status_gizi', 'Stunting')
                    ->where('status_validasi', 'approved')
                    ->count();

                $p->stunting_rate = $p->measured_count > 0
                    ? round(($p->stunting_count / $p->measured_count) * 100, 1)
                    : null; // null = no data this month

                return $p;
            })
            ->sortByDesc(fn($p) => $p->stunting_rate ?? -1)
            ->values();

        $totalPuskesmasCount  = $allPuskesmas->count();
        $puskesmasLeaderboard = $allPuskesmas->forPage($page, $perPage);
        $lastPage             = (int) ceil($totalPuskesmasCount / $perPage);

        // Count how many need attention (stunting_rate > 15%)
        $attentionCount = $allPuskesmas->filter(fn($p) => $p->stunting_rate !== null && $p->stunting_rate > 15)->count();

        // Last month that has data (for empty state helper)
        $lastDataDate = DB::table('pengukurans')
            ->where('status_validasi', 'approved')
            ->whereNotNull('tanggal_ukur')
            ->orderByDesc('tanggal_ukur')
            ->value('tanggal_ukur');

        $lastDataMonth = $lastDataDate ? Carbon::parse($lastDataDate)->translatedFormat('F Y') : null;

        return view('super-admin.dashboard', compact(
            'currentMonth', 'currentYear',
            'prevMonth', 'prevYear',
            'totalPuskesmas', 'totalPosyandu', 'totalBalita',
            'totalMeasured', 'prevMeasured', 'measuredDelta', 'measuredDeltaSign',
            'totalStunting', 'totalRisiko', 'totalNormal',
            'stuntingRate', 'risikoRate', 'normalRate',
            'months', 'trendNormal', 'trendRisiko', 'trendStunting',
            'puskesmasLeaderboard', 'totalPuskesmasCount', 'page', 'lastPage', 'perPage',
            'attentionCount', 'lastDataMonth'
        ));
        }
    public function showPuskesmas($id)
    {
        // Fetch the Puskesmas with related statistics for the current filter
        $puskesmas = Puskesmas::withCount('posyandus')->with(['posyandus', 'balitas'])->findOrFail($id);

        // Determine current month/year from request (fallback to current)
        $request = request();
        $currentMonth = max(1, min(12, (int) $request->input('month', date('n'))));
        $currentYear  = max(2000, min((int) date('Y') + 1, (int) $request->input('year', date('Y'))));

        // Measurements for this Puskesmas in the selected period
        $measurements = Pengukuran::whereHas('balita.posyandu', fn($q) => $q->where('puskesmas_id', $puskesmas->id))
            ->whereMonth('tanggal_ukur', $currentMonth)
            ->whereYear('tanggal_ukur', $currentYear)
            ->where('status_validasi', 'approved')
            ->get();

        $totalMeasured = $measurements->count();
        $totalStunting = $measurements->where('status_gizi', 'Stunting')->count();
        $totalRisiko   = $measurements->whereIn('status_gizi', ['Risiko', 'Kurang'])->count();
        $totalNormal   = $measurements->whereIn('status_gizi', ['Normal', 'Baik'])->count();

        $stuntingRate = $totalMeasured > 0 ? round(($totalStunting / $totalMeasured) * 100, 1) : null;

        // KPI agregat Posyandu/Kader/Balita untuk Mini Dashboard
        $posyandus = $puskesmas->posyandus()
            ->withCount(['kaders', 'balitas'])
            ->orderBy('nama')
            ->get();

        $kpis = [
            'total_posyandu' => $posyandus->count(),
            'total_kader'    => $posyandus->sum('kaders_count'),
            'total_balita'   => $posyandus->sum('balitas_count'),
        ];

        return view('super-admin.puskesmas.show', compact(
            'puskesmas',
            'posyandus',
            'kpis',
            'currentMonth', 'currentYear',
            'totalMeasured', 'totalStunting', 'totalRisiko', 'totalNormal',
            'stuntingRate'
        ));
    }

    public function exportCsv()
    {
        $request = request();
        $currentMonth = max(1, min(12, (int) $request->input('month', date('n'))));
        $currentYear  = max(2000, min((int) date('Y') + 1, (int) $request->input('year', date('Y'))));

        $measurements = Pengukuran::whereMonth('tanggal_ukur', $currentMonth)
            ->whereYear('tanggal_ukur', $currentYear)
            ->where('status_validasi', 'approved')
            ->with(['balita', 'balita.posyandu.puskesmas'])
            ->get();

        $filename = 'rekap_gizi_nasional_' . $currentYear . '_' . str_pad($currentMonth, 2, '0', STR_PAD_LEFT) . '.csv';
        $handle = fopen('php://temp', 'r+');
        
        // UTF-8 BOM for Excel compatibility
        fputs($handle, "\xEF\xBB\xBF");
        
        // Header row
        fputcsv($handle, ['No', 'Puskesmas', 'Posyandu', 'Nama Balita', 'NIK', 'Tanggal Ukur', 'Berat Badan (kg)', 'Tinggi Badan (cm)', 'Status Gizi', 'Status Validasi']);
        
        $no = 1;
        foreach ($measurements as $m) {
            $puskesmas = $m->balita?->posyandu?->puskesmas?->nama ?? '-';
            $posyandu  = $m->balita?->posyandu?->nama ?? '-';
            $nama      = $m->balita?->nama ?? '-';
            $nik       = $m->balita?->nik ? "'" . $m->balita?->nik : '-';
            
            fputcsv($handle, [
                $no++,
                $puskesmas,
                $posyandu,
                $nama,
                $nik,
                $m->tanggal_ukur,
                $m->berat_badan ?? '-',
                $m->tinggi_badan ?? '-',
                ucfirst($m->status_gizi ?? '-'),
                ucfirst($m->status_validasi ?? '-'),
            ]);
        }
        
        rewind($handle);
        $csv = stream_get_contents($handle);
        fclose($handle);

        return response($csv)
            ->header('Content-Type', 'text/csv; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    public function exportExcel()
    {
        $request = request();
        $currentMonth = max(1, min(12, (int) $request->input('month', date('n'))));
        $currentYear  = max(2000, min((int) date('Y') + 1, (int) $request->input('year', date('Y'))));

        $measurements = Pengukuran::whereMonth('tanggal_ukur', $currentMonth)
            ->whereYear('tanggal_ukur', $currentYear)
            ->where('status_validasi', 'approved')
            ->with(['balita', 'balita.posyandu.puskesmas'])
            ->get();

        $monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $monthLabel = $monthNames[$currentMonth - 1] ?? '';
        $filename = 'rekap_gizi_nasional_' . $currentYear . '_' . str_pad($currentMonth, 2, '0', STR_PAD_LEFT) . '.xls';

        $output = '<html xmlns:x="urn:schemas-microsoft-com:office:excel">';
        $output .= '<head><meta charset="utf-8">';
        $output .= '<style>';
        $output .= 'table { border-collapse: collapse; width: 100%; font-family: sans-serif; font-size: 12px; }';
        $output .= 'th { background-color: #0D9B76; color: #ffffff; font-weight: bold; padding: 10px; border: 1px solid #cccccc; }';
        $output .= 'td { padding: 8px; border: 1px solid #e2e8f0; }';
        $output .= '.title { font-size: 16px; font-weight: bold; margin-bottom: 8px; }';
        $output .= '.subtitle { font-size: 12px; color: #64748b; margin-bottom: 16px; }';
        $output .= '</style></head><body>';
        $output .= '<div class="title">REKAPITULASI PENGUKURAN GIZI BALITA NASIONAL</div>';
        $output .= '<div class="subtitle">Periode: ' . $monthLabel . ' ' . $currentYear . ' | Sumber: NutriGen Kemenkes</div><br>';
        $output .= '<table>';
        $output .= '<thead><tr>';
        $output .= '<th>No</th><th>Puskesmas</th><th>Posyandu</th><th>Nama Balita</th><th>NIK</th><th>Tanggal Ukur</th><th>Berat (kg)</th><th>Tinggi (cm)</th><th>Status Gizi</th><th>Status Validasi</th>';
        $output .= '</tr></thead><tbody>';

        $no = 1;
        foreach ($measurements as $m) {
            $puskesmas = htmlspecialchars($m->balita?->posyandu?->puskesmas?->nama ?? '-');
            $posyandu  = htmlspecialchars($m->balita?->posyandu?->nama ?? '-');
            $nama      = htmlspecialchars($m->balita?->nama ?? '-');
            $nik       = htmlspecialchars($m->balita?->nik ? "'" . $m->balita?->nik : '-');
            $status    = htmlspecialchars(ucfirst($m->status_gizi ?? '-'));
            $validasi  = htmlspecialchars(ucfirst($m->status_validasi ?? '-'));

            $output .= "<tr>";
            $output .= "<td style='text-align:center;'>{$no}</td>";
            $output .= "<td>{$puskesmas}</td>";
            $output .= "<td>{$posyandu}</td>";
            $output .= "<td>{$nama}</td>";
            $output .= "<td style='mso-number-format:\"\\@\";'>{$nik}</td>";
            $output .= "<td style='text-align:center;'>{$m->tanggal_ukur}</td>";
            $output .= "<td style='text-align:center;'>{$m->berat_badan}</td>";
            $output .= "<td style='text-align:center;'>{$m->tinggi_badan}</td>";
            $output .= "<td style='text-align:center;'>{$status}</td>";
            $output .= "<td style='text-align:center;'>{$validasi}</td>";
            $output .= "</tr>";
            $no++;
        }

        if ($measurements->isEmpty()) {
            $output .= "<tr><td colspan='10' style='text-align:center; padding:20px; color:#94a3b8;'>Belum ada data pengukuran terverifikasi untuk periode ini.</td></tr>";
        }

        $output .= '</tbody></table></body></html>';

        return response($output)
            ->header('Content-Type', 'application/vnd.ms-excel; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=\"$filename\"");
    }

    public function exportPdf()
    {
        $request = request();
        $currentMonth = max(1, min(12, (int) $request->input('month', date('n'))));
        $currentYear  = max(2000, min((int) date('Y') + 1, (int) $request->input('year', date('Y'))));

        $monthNames = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
        $monthLabel = $monthNames[$currentMonth - 1] ?? '';

        // National global counts
        $totalPuskesmas = Puskesmas::count();
        $totalPosyandu  = Posyandu::count();
        $totalBalita    = Balita::count();

        // Measurements for the selected period
        $measurements = Pengukuran::whereMonth('tanggal_ukur', $currentMonth)
            ->whereYear('tanggal_ukur', $currentYear)
            ->where('status_validasi', 'approved')
            ->with(['balita', 'balita.posyandu.puskesmas'])
            ->get();

        $totalMeasured = $measurements->count();

        $statusCounts = [
            'normal'      => 0,
            'stunting'    => 0,
            'wasting'     => 0,
            'overweight'  => 0,
        ];

        foreach ($measurements as $m) {
            $st = strtolower((string) $m->status_gizi ?? '');
            if (str_contains($st, 'stunting') || str_contains($st, 'pendek')) {
                $statusCounts['stunting']++;
            } elseif (str_contains($st, 'kurang') || str_contains($st, 'buruk') || str_contains($st, 'wasting')) {
                $statusCounts['wasting']++;
            } elseif (str_contains($st, 'lebih') || str_contains($st, 'obesitas')) {
                $statusCounts['overweight']++;
            } else {
                $statusCounts['normal']++;
            }
        }

        $prevalensiNasional = $totalMeasured > 0 
            ? round(($statusCounts['stunting'] / $totalMeasured) * 100, 1) 
            : 0;

        // Puskesmas breakdown
        $puskesmasList = Puskesmas::withCount(['posyandus', 'balitas'])->get()->map(function ($p) use ($currentMonth, $currentYear) {
            $pMeasurements = Pengukuran::whereHas('balita.posyandu', function ($q) use ($p) {
                $q->where('puskesmas_id', $p->id);
            })
            ->whereMonth('tanggal_ukur', $currentMonth)
            ->whereYear('tanggal_ukur', $currentYear)
            ->where('status_validasi', 'approved')
            ->get();

            $pTotal = $pMeasurements->count();
            $pStunting = $pMeasurements->filter(fn($m) => str_contains(strtolower((string) $m->status_gizi ?? ''), 'stunting') || str_contains(strtolower((string) $m->status_gizi ?? ''), 'pendek'))->count();
            $pNormal = $pMeasurements->filter(fn($m) => str_contains(strtolower((string) $m->status_gizi ?? ''), 'normal'))->count();
            $pPrevalensi = $pTotal > 0 ? round(($pStunting / $pTotal) * 100, 1) : 0;

            return [
                'nama' => $p->nama,
                'kode' => $p->kode_faskes,
                'wilayah' => ($p->kecamatan ? $p->kecamatan . ', ' : '') . ($p->kabupaten_kota ?? '-'),
                'posyandu_count' => $p->posyandus_count,
                'total_ukur' => $pTotal,
                'normal_count' => $pNormal,
                'stunting_count' => $pStunting,
                'prevalensi' => $pPrevalensi,
            ];
        });

        return view('super-admin.laporan-pdf', compact(
            'currentMonth',
            'currentYear',
            'monthLabel',
            'totalPuskesmas',
            'totalPosyandu',
            'totalBalita',
            'totalMeasured',
            'statusCounts',
            'prevalensiNasional',
            'puskesmasList'
        ));
    }



    // =========================================================================
    // Master Data Puskesmas
    // =========================================================================

    public function indexPuskesmas(Request $request)
    {
        $search = $request->input('search');
        $query = Puskesmas::with('user')->withCount('posyandus');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('kode_faskes', 'like', "%{$search}%")
                  ->orWhere('kecamatan', 'like', "%{$search}%");
            });
        }

        $puskesmas = $query->orderBy('nama')->paginate(10)->withQueryString();

        return view('super-admin.puskesmas.index', compact('puskesmas', 'search'));
    }

    public function storePuskesmas(Request $request)
    {
        $request->validate([
            'nama'            => 'required|string|max:255',
            'kode_faskes'     => 'required|string|max:100|unique:puskesmas,kode_faskes',
            'kepala_puskesmas'=> 'nullable|string|max:255',
            'alamat'          => 'required|string',
            'kecamatan'       => 'nullable|string|max:255',
            'kabupaten_kota'  => 'nullable|string|max:255',
            'provinsi'        => 'nullable|string|max:255',
            'no_telp'         => 'nullable|string|max:30',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|string|min:6',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'name'               => $request->nama,
                'email'              => $request->email,
                'password'           => Hash::make($request->password),
                'role'               => 'puskesmas',
                'email_verified_at'  => now(),
            ]);

            Puskesmas::create([
                'user_id'          => $user->id,
                'nama'             => $request->nama,
                'kode_faskes'      => $request->kode_faskes,
                'kepala_puskesmas' => $request->kepala_puskesmas,
                'alamat'           => $request->alamat,
                'kecamatan'        => $request->kecamatan,
                'kabupaten_kota'   => $request->kabupaten_kota,
                'provinsi'         => $request->provinsi,
                'no_telp'          => $request->no_telp,
            ]);

            DB::commit();
            return redirect()->route('super-admin.puskesmas.index')->with('success', 'Data Puskesmas berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan Puskesmas: ' . $e->getMessage())->withInput();
        }
    }

    public function updatePuskesmas(Request $request, $id)
    {
        $puskesmas = Puskesmas::findOrFail($id);
        
        $request->validate([
            'nama'            => 'required|string|max:255',
            'kode_faskes'     => 'required|string|max:100|unique:puskesmas,kode_faskes,'.$id,
            'kepala_puskesmas'=> 'nullable|string|max:255',
            'alamat'          => 'required|string',
            'kecamatan'       => 'nullable|string|max:255',
            'kabupaten_kota'  => 'nullable|string|max:255',
            'provinsi'        => 'nullable|string|max:255',
            'no_telp'         => 'nullable|string|max:30',
            'email'           => 'required|email|unique:users,email,'.$puskesmas->user_id,
            'password'        => 'nullable|string|min:6',
        ]);

        DB::beginTransaction();
        try {
            $user = $puskesmas->user;
            $user->name  = $request->nama;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $puskesmas->update([
                'nama'             => $request->nama,
                'kode_faskes'      => $request->kode_faskes,
                'kepala_puskesmas' => $request->kepala_puskesmas,
                'alamat'           => $request->alamat,
                'kecamatan'        => $request->kecamatan,
                'kabupaten_kota'   => $request->kabupaten_kota,
                'provinsi'         => $request->provinsi,
                'no_telp'          => $request->no_telp,
            ]);

            DB::commit();
            return redirect()->route('super-admin.puskesmas.index')->with('success', 'Data Puskesmas berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui Puskesmas: ' . $e->getMessage());
        }
    }

    public function destroyPuskesmas($id)
    {
        $puskesmas = Puskesmas::withCount('posyandus')->findOrFail($id);
        
        if ($puskesmas->posyandus_count > 0) {
            return redirect()->back()->with('error', 'Puskesmas tidak dapat dihapus karena memiliki data Posyandu.');
        }

        DB::beginTransaction();
        try {
            $userId = $puskesmas->user_id;
            $puskesmas->delete();
            if ($userId) {
                User::find($userId)->delete();
            }
            DB::commit();
            return redirect()->route('super-admin.puskesmas.index')->with('success', 'Puskesmas berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus Puskesmas: ' . $e->getMessage());
        }
    }
}
