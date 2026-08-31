<?php
namespace App\Services;

use App\Models\Pengukuran;
use App\Models\Balita;
use Carbon\Carbon;

/**
 * StatisticsService
 *
 * Centralised source of truth for all statistical calculations across the NutriGen application.
 * All methods return an associative array with a consistent set of keys that are consumed by
 * Blade views (Dashboard, Validation Queue, Data Balita, Report, Portal Kader, Portal Ibu).
 */
class StatisticsService
{
    /**
     * Get dashboard statistics for a given Puskesmas.
     */
    public function getDashboardStats(int $puskesmasId): array
    {
        // Total balita linked to this Puskesmas (through Posyandu)
        $totalBalita = Balita::whereHas('posyandu', function ($q) use ($puskesmasId) {
            $q->where('puskesmas_id', $puskesmasId);
        })->count();

        // Pending measurements (status_validasi = 'pending')
        $pending = Pengukuran::whereHas('balita.posyandu', function ($q) use ($puskesmasId) {
            $q->where('puskesmas_id', $puskesmasId);
        })->where('status_validasi', 'pending')->count();

        // Pending Anomali (pending + status_gizi = Risiko or Kurang)
        $pendingAnomali = Pengukuran::whereHas('balita.posyandu', function ($q) use ($puskesmasId) {
            $q->where('puskesmas_id', $puskesmasId);
        })->where('status_validasi', 'pending')
            ->whereIn('status_gizi', ['Risiko', 'Kurang'])
            ->count();

        // Pending Berisiko (pending + status_gizi = Stunting)
        $pendingBerisiko = Pengukuran::whereHas('balita.posyandu', function ($q) use ($puskesmasId) {
            $q->where('puskesmas_id', $puskesmasId);
        })->where('status_validasi', 'pending')
            ->where('status_gizi', 'Stunting')
            ->count();

        // Approved (approved) measurements for the current month
        $now = Carbon::now();
        $approvedThisMonth = Pengukuran::whereHas('balita.posyandu', function ($q) use ($puskesmasId) {
            $q->where('puskesmas_id', $puskesmasId);
        })->where('status_validasi', 'approved')
            ->whereYear('tanggal_ukur', $now->year)
            ->whereMonth('tanggal_ukur', $now->month)
            ->count();

        // Total measurements taken this month (regardless of status)
        $diukurThisMonth = Pengukuran::whereHas('balita.posyandu', function ($q) use ($puskesmasId) {
            $q->where('puskesmas_id', $puskesmasId);
        })->whereYear('tanggal_ukur', $now->year)
            ->whereMonth('tanggal_ukur', $now->month)
            ->count();

        // Distribution for the month (normal, risiko, stunting)
        $normal = Pengukuran::whereHas('balita.posyandu', function ($q) use ($puskesmasId) {
            $q->where('puskesmas_id', $puskesmasId);
        })->where('status_gizi', 'Normal')
            ->whereYear('tanggal_ukur', $now->year)
            ->whereMonth('tanggal_ukur', $now->month)
            ->count();
        $risiko = Pengukuran::whereHas('balita.posyandu', function ($q) use ($puskesmasId) {
            $q->where('puskesmas_id', $puskesmasId);
        })->where('status_gizi', 'Risiko')
            ->whereYear('tanggal_ukur', $now->year)
            ->whereMonth('tanggal_ukur', $now->month)
            ->count();
        $stunting = Pengukuran::whereHas('balita.posyandu', function ($q) use ($puskesmasId) {
            $q->where('puskesmas_id', $puskesmasId);
        })->where('status_gizi', 'Stunting')
            ->whereYear('tanggal_ukur', $now->year)
            ->whereMonth('tanggal_ukur', $now->month)
            ->count();

        return [
            'total_balita'      => $totalBalita,
            'pending'           => $pending,
            'pending_total'     => $pending, // backward compatibility
            'pending_anomali'   => $pendingAnomali,
            'pending_berisiko'  => $pendingBerisiko,
            'approved'          => $approvedThisMonth,
            'valid'             => $approvedThisMonth, // alias used by existing views
            'diukur'            => $diukurThisMonth,
            'normal'            => $normal,
            'risiko'            => $risiko,
            'stunting'          => $stunting,
            'current_month'     => $now->translatedFormat('F Y'),
        ];
    }

    /**
     * Get validation queue statistics (Antrean Validasi).
     */
    public function getValidationQueueStats(int $puskesmasId, ?string $posyanduNama = null): array
    {
        $baseQuery = function () use ($puskesmasId, $posyanduNama) {
            return Pengukuran::whereHas('balita.posyandu', function ($q) use ($puskesmasId, $posyanduNama) {
            $q->where('puskesmas_id', $puskesmasId);
                if ($posyanduNama) {
                    $q->where('nama', $posyanduNama);
                }
            })->where('status_validasi', 'pending');
        };

        $pending = $baseQuery()->count();
        $anomali = $baseQuery()
            ->whereIn('status_gizi', ['Risiko', 'Kurang'])
            ->count();

        $berisiko = $baseQuery()
            ->where('status_gizi', 'Stunting')
            ->count();

        $normal = $baseQuery()
            ->whereRaw('LOWER(status_gizi) = ?', ['normal'])
            ->count();

        return [
            'pending' => $pending,
            'anomali' => $anomali,
            'berisiko' => $berisiko,
            'normal' => $normal,
        ];
    }

    /**
     * Get statistics for Portal Ibu (only approved measurements).
     */
    public function getPortalIbuStats(int $balitaId): array
    {
        $approved = Pengukuran::where('balita_id', $balitaId)
            ->where('status_validasi', 'approved')
            ->count();
        $pending = Pengukuran::where('balita_id', $balitaId)
            ->where('status_validasi', 'pending')
            ->count();
        return [
            'approved' => $approved,
            'pending'  => $pending,
        ];
    }

    /**
     * Get report statistics – only approved measurements.
     */
    public function getReportStats(int $puskesmasId, ?int $month = null, ?int $year = null): array
    {
        $base = Pengukuran::whereHas('balita.posyandu', function ($q) use ($puskesmasId) {
            $q->where('puskesmas_id', $puskesmasId);
        })->where('status_validasi', 'approved');

        if ($month && $year) {
            $base->whereYear('tanggal_ukur', $year)
                 ->whereMonth('tanggal_ukur', $month);
        }

        $total = $base->count();
        $normal = (clone $base)->where('status_gizi', 'Normal')->count();
        $risiko = (clone $base)->where('status_gizi', 'Risiko')->count();
        $stunting = (clone $base)->where('status_gizi', 'Stunting')->count();

        return [
            'total'    => $total,
            'normal'   => $normal,
            'risiko'   => $risiko,
            'stunting' => $stunting,
        ];
    }

    /**
     * Get dashboard statistics for a given Posyandu (Portal Kader).
     */
    public function getKaderDashboardStats(int $posyanduId, ?int $month = null, ?int $year = null): array
    {
        $totalBalita = Balita::where('posyandu_id', $posyanduId)->count();

        $targetMonth = $month ?: Carbon::now()->month;
        $targetYear = $year ?: Carbon::now()->year;

        $pengukuranBulanIni = Pengukuran::whereHas('balita', function ($q) use ($posyanduId) {
            $q->where('posyandu_id', $posyanduId);
        })->whereYear('tanggal_ukur', $targetYear)
          ->whereMonth('tanggal_ukur', $targetMonth)
          ->get();

        $diukurBulanIni = $pengukuranBulanIni->count();
        $stunting = $pengukuranBulanIni->where('status_gizi', 'Stunting')->count();
        $risiko = $pengukuranBulanIni->where('status_gizi', 'Risiko')->count();
        $kurang = $pengukuranBulanIni->where('status_gizi', 'Kurang')->count();
        $normal = $pengukuranBulanIni->where('status_gizi', 'Normal')->count();

        // Pending revision (rejected by Puskesmas) - independent of month/year
        $perluRevisi = Pengukuran::whereHas('balita', function ($q) use ($posyanduId) {
            $q->where('posyandu_id', $posyanduId);
        })->where('status_validasi', 'rejected')->count();

        return [
            'total_balita' => $totalBalita,
            'bulan_ini'    => $diukurBulanIni,
            'stunting'     => $stunting,
            'risiko'       => $risiko,
            'kurang'       => $kurang,
            'normal'       => $normal,
            'perlu_revisi' => $perluRevisi,
        ];
    }
}
?>
